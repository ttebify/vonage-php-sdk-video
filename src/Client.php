<?php
declare(strict_types=1);

namespace Vonage\Video;

use Psr\Http\Client\ClientExceptionInterface;
use Vonage\Client\APIClient;
use Vonage\Client\APIResource;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\CredentialsInterface;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Exception\Exception;
use Vonage\Entity\Filter\FilterInterface;
use Vonage\Entity\Hydrator\ConstructorHydrator;
use Vonage\Entity\IterableAPICollection;
use Vonage\JWT\TokenGenerator;
use Vonage\Video\Archive\Archive;
use Vonage\Video\Archive\ArchiveConfig;
use Vonage\Video\Archive\ArchiveLayout;
use Vonage\Video\ProjectDetails;

class Client implements APIClient
{
    /**
     * @var APIResource
     */
    protected $apiResource;

    /**
     * @var Keypair
     */
    protected $credentials;

    public function __construct(APIResource $apiResource)
    {
        $this->apiResource = $apiResource;
        $this->credentials = $this->extractCredentials(Keypair::class, $this->getAPIResource()->getClient()->getCredentials());
    }

    public function getAPIResource(): APIResource
    {
        return $this->apiResource;
    }

    public function createSession(?SessionOptions $options = null): Session
    {
        if (!$options) {
            $options = new SessionOptions();
        }

        $data = [
            'mediaMode' => $options->getMediaMode(),
            'archiveMode' => $options->getArchiveMode(),
        ];

        if ($options->getLocation()) {
            $data['location'] = $options->getLocation();
        }
        
        $response = $this->apiResource->submit(
            $data,
            '/session/create',
            ['Accept' => 'application/json']
        );

        $responseData = json_decode($response, true);
        $responseData = array_merge($responseData[0], $data);

        $session = new Session();
        $session->fromArray($responseData);

        return $session;
    }

    public function deleteArchive(string $archiveId): void
    {
        $this->apiResource->delete('v2/project/' . $this->credentials->application . '/archive/' . $archiveId);
    }

    /**
     * @param string[] $excludedStreams
     */
    public function disableForceMute(string $sessionId): ProjectDetails
    {
        $response = $this->apiResource->create(
            [
                'active' => false,
            ],
            'v2/project/' . $this->credentials->application . '/session/' . $sessionId . '/mute'
        );
        return new ProjectDetails($response);
    }

    public function disconnectClient(string $sessionId, string $connectionId): void
    {
        $this->apiResource->delete('v2/project/' . $this->credentials->application . '/session/' . $sessionId . '/connection/' . $connectionId);
    }

    protected function extractCredentials(string $class, CredentialsInterface $credentials): CredentialsInterface
    {
        if ($credentials instanceof $class) {
            return $credentials;
        }

        if ($credentials instanceof Container) {
            $creds = $credentials->get($class);
            if (!is_null($creds)) {
                return $creds;
            }
        }

        throw new \RuntimeException('Requested auth type not found');
    }

    /**
     * @param string[] $excludedStreams
     */
    public function forceMuteAll(string $sessionId, array $excludedStreamIds = []): ProjectDetails
    {
        $response = $this->apiResource->create(
            [
                'active' => true,
                'excludedStreamIds' => $excludedStreamIds
            ],
            'v2/project/' . $this->credentials->application . '/session/' . $sessionId . '/mute'
        );
        return new ProjectDetails($response);
    }

    public function forceMuteStream(string $sessionId, string $streamId): ProjectDetails
    {
        $response = $this->apiResource->create([], 'v2/project/' . $this->credentials->application . '/session/' . $sessionId . '/stream/' . $streamId . '/mute');
        return new ProjectDetails($response);
    }

    /**
     * @param array<string, mixed> $options 
     */
    public function generateClientToken(string $sessionId, array $options = []): string
    {
        $defaults = [
            'scope' => 'session.connect',
            'session_id' => $sessionId,
        ];

        $options = array_merge($defaults, $options);
        $token = TokenGenerator::factory($this->credentials->application, $this->credentials->key, $options);

        return $token;
    }

    public function getArchive(string $archiveId): Archive
    {
        $response = $this->apiResource->get('v2/project/' . $this->credentials->application . '/archive/' . $archiveId);

        return new Archive($response);
    }

    public function listArchives(FilterInterface $filter = null): IterableAPICollection
    {
        $response = $this->apiResource->search(
            $filter,
            '/v2/project/' . $this->credentials->application . '/archive'
        );
        $response->getApiResource()->setBaseUri('/v2/project/' . $this->credentials->application . '/archive');

        $hydrator = new ConstructorHydrator();
        $hydrator->setPrototype(Archive::class);
        $response->setHydrator($hydrator);
        $response->setNaiveCount(true);
        $response->getApiResource()->setCollectionName('items');

        return $response;
    }

    public function sendSignal(string $sessionId, string $type, string $data, string $connectionId = null): void
    {
        $url = '/v2/project/' . $this->credentials->application . '/session/' . $sessionId . '/signal';
        if ($connectionId) {
            $url = '/v2/project/' . $this->credentials->application . '/session/' . $sessionId . '/connection/' . $connectionId . '/signal';
        }

        $this->apiResource->create(
            ['type' => $type, 'data' => $data],
            $url
        );
    }

    public function startArchive(ArchiveConfig $archiveConfig): Archive
    {
        $response = $this->apiResource->create(
            $archiveConfig->toArray(),
            '/v2/project/' . $this->credentials->application . '/archive'
        );

        return new Archive($response);
    }

    public function stopArchive(string $archiveId): Archive
    {
        $response = $this->apiResource->create(
            [],
            '/v2/project/' . $this->credentials->application . '/archive/' . $archiveId . '/stop'
        );

        return new Archive($response);
    }

    public function updateArchiveLayout(string $archiveId, ArchiveLayout $layout): void
    {
        $this->apiResource->update(
            '/v2/project/' . $this->credentials->application . '/archive/' . $archiveId . '/layout',
            $layout->toArray(),
        );
    }
}
