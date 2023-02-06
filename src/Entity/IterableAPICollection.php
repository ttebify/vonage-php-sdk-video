<?php
declare(strict_types=1);

namespace Vonage\Video\Entity;

use Laminas\Diactoros\Request;
use Vonage\Entity\Filter\KeyValueFilter;
use Vonage\Entity\IterableAPICollection as EntityIterableAPICollection;

class IterableAPICollection extends EntityIterableAPICollection
{
    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $count = 50;

    protected function fetchPage($absoluteUri): void
    {
        //use filter if no query provided
        if (false === strpos($absoluteUri, '?')) {
            $query = [];

            if (isset($this->filter)) {
                $query = array_merge($this->filter->getQuery(), $query);
            }

            $query['offset'] = $query['offset'] ?? $this->offset;
            $query['count'] = $query['count'] ?? $this->count;

            $absoluteUri .= '?' . http_build_query($query);
        }

        $requestUri = $absoluteUri;

        if (filter_var($absoluteUri, FILTER_VALIDATE_URL) === false) {
            $requestUri = $this->getApiResource()->getBaseUrl() . $absoluteUri;
        }

        $cacheKey = md5($requestUri);
        if (array_key_exists($cacheKey, $this->cache)) {
            $this->pageData = $this->cache[$cacheKey];

            return;
        }

        $request = new Request($requestUri, 'GET');
        $response = $this->client->send($request);

        $this->getApiResource()->setLastRequest($request);
        $this->response = $response;
        $this->getApiResource()->setLastResponse($response);

        $body = $this->response->getBody()->getContents();
        $json = json_decode($body, true);
        $this->cache[md5($requestUri)] = $json;
        $this->pageData = $json;

        if ($this->pageData) {
            $this->offset += count($this->pageData['items']);
            $filterQuery = $this->filter->getQuery();
            $filterQuery['offset'] = $this->offset;
            $filterQuery['count'] = $this->count;
            $this->setFilter(new KeyValueFilter($filterQuery));
        }

        if ((int)$response->getStatusCode() !== 200) {
            throw $this->getException($response);
        }
    }
}
