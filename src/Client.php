<?php
declare(strict_types=1);

namespace Vonage\Video;

use Psr\Http\Message\ResponseInterface;
use Vonage\Client\APIClient;
use Vonage\JWT\TokenGenerator;
use Vonage\Client\APIResource;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Credentials\Container;
use Vonage\Client\Credentials\CredentialsInterface;
use Vonage\Video\ArchiveTest\ArchiveObject;

class Client implements APIClient
{
    /**
     * @var APIResource
     */
    protected $apiResource;

    public function __construct(APIResource $apiResource)
    {
        $this->apiResource = $apiResource;
    }

    public function getAPIResource(): APIResource
    {
        return $this->apiResource;
    }

    public function createSession(string $mediaMode = MediaMode::RELAYED, string $archiveMode = ArchiveMode::MANUAL, ?string $location = null): Session
    {
        $response = $this->apiResource->submit(
            [
                'mediaMode' => $mediaMode,
                'archiveMode' => $archiveMode,
                'location' => $location
            ],
            '/session/create',
            ['Accept' => 'application/json']
        );

        $data = json_decode($response, true);
        $data = array_merge(
            $data[0],
            [
                'mediaMode' => $mediaMode,
                'archiveMode' => $archiveMode,
                'location' => $location
            ]
        );
        $session = new Session();
        $session->fromArray($data);

        return $session;
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

    public function generateClientToken(string $sessionId): string
    {
        /** @var Keypair $credentials */
        $credentials = $this->extractCredentials(Keypair::class, $this->getAPIResource()->getClient()->getCredentials());
        $token = TokenGenerator::factory($credentials->application, $credentials->key, [
            'scope' => 'session.connect',
            'session_id' => $sessionId,
        ]);

        return $token;
    }

    public function sendSignal(string $sessionId, string $type, $data, string $connectionId = null): void
    {
        $credentials = $this->extractCredentials(Keypair::class, $this->getAPIResource()->getClient()->getCredentials());
        $url = '/v2/project/' . $credentials->application . '/session/' . $sessionId . '/signal';
        if ($connectionId) {
            $url = '/v2/project/' . $credentials->application . '/session/' . $sessionId . '/connection/' . $connectionId . '/signal';
        }

        $this->apiResource->create(
            ['type' => $type, 'data' => $data],
            $url
        );
    }

    public function startArchive($sessionId, $data)
    {
        $credentials = $this->extractCredentials(Keypair::class, $this->getAPIResource()->getClient()->getCredentials());
        $data['sessionId'] = $sessionId;
        $response = $this->apiResource->create(
            $data,
            '/v2/project/' . $credentials->application . '/archive'
        );

        return $response;
    }
}
