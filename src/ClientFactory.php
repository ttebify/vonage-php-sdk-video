<?php
declare(strict_types=1);

namespace Vonage\Video;

use Vonage\Client\APIResource;
use Vonage\Client\Credentials\Handler\KeypairHandler;
use Vonage\Client\Factory\MapFactory;

class ClientFactory
{
    public function __invoke(MapFactory $containerInterface): Client
    {
        $apiResource = $containerInterface->make(APIResource::class);
        $apiResource->setBaseUri('/video');
        $apiResource->setAuthHandler(new KeypairHandler());

        return new Client($apiResource);
    }
}