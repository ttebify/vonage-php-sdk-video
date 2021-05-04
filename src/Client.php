<?php
declare(strict_types=1);

namespace Vonage\Video;

use Vonage\Client\APIClient;
use Nexmo\JWT\TokenGenerator;
use Vonage\Client\APIResource;
use Vonage\Client\Credentials\Keypair;
use Vonage\Client\Credentials\CredentialsInterface;

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
            '/session/create'
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

    public function generateClientToken(string $sessionId, array $options = []): string
    {
        // unpack optional arguments (merging with default values) into named variables
        $defaults = array(
            'role' => Role::PUBLISHER,
            'expireTime' => null,
            'data' => null,
            'initialLayoutClassList' => [],
        );
        $options = array_merge($defaults, array_intersect_key($options, $defaults));
        list($role, $expireTime, $data, $initialLayoutClassList) = array_values($options);

        /** @var Keypair $credentials */
        $credentials = $this->extractCredentials(Keypair::class, $this->getAPIResource()->getClient()->getCredentials());
        $token = TokenGenerator::factory($credentials->application, $credentials->key, [
            'role' => $role,
            'connection_data' => $data,
            'initial_layout_class_list' => $initialLayoutClassList,
            'session_id' => $sessionId
        ]);

        return $token;
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
}
