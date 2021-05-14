<?php
declare(strict_types=1);

namespace Vonage\Video;

use Vonage\Client\APIResource;
use Vonage\Client\Credentials\Handler\KeypairHandler;
use Vonage\Client\Factory\MapFactory;
use Vonage\Video\Entity\IterableAPICollection;

class ClientFactory
{
    public function __invoke(MapFactory $containerInterface): Client
    {
        /** @var APIResource $apiResource */
        $apiResource = $containerInterface->make(APIResource::class);
        $apiResource->setBaseUrl('https://anvil-tbdev.opentok.com');
        $apiResource->setBaseUri('/');
        $apiResource->setIsHAL(false);
        $apiResource->setAuthHandler(new KeypairHandler());
        $apiResource->setCollectionPrototype(new IterableAPICollection());

        return new Client($apiResource);
    }
}