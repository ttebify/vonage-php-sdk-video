<?php
declare(strict_types=1);

namespace Vonage\Video\Client\Credentials\Handler;

use Vonage\JWT\TokenGenerator;
use Psr\Http\Message\RequestInterface;
use Vonage\Client\Credentials\CredentialsInterface;
use Vonage\Client\Credentials\Handler\HandlerInterface;
use Vonage\Client\Credentials\Handler\KeypairHandler;
use Vonage\Client\Credentials\Keypair;

class VideoKeypairHandler extends KeypairHandler implements HandlerInterface
{
    public function __invoke(RequestInterface $request, CredentialsInterface $credentials): RequestInterface
    {
        /** @var Keypair $credentials */
        $credentials = $this->extract(Keypair::class, $credentials);

        return $request->withHeader(
            'Authorization',
            'Bearer ' . TokenGenerator::factory($credentials->application, $credentials->key)
        );
    }
}
