<?php

namespace VonageTest\Video;

use Prophecy\Argument;
use Vonage\Video\Client;
use Vonage\Client\APIResource;
use Laminas\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Vonage\Client as VonageClient;
use VonageTest\Psr7AssertionTrait;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\RequestInterface;
use Vonage\Video\ArchiveMode;
use Vonage\Video\MediaMode;

class ClientTest extends TestCase
{
    use ProphecyTrait;
    use Psr7AssertionTrait;

    /**
     * @var APIResource
     */
    protected $apiResource;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var VonageClient
     */
    protected $vonageClient;

    public function setUp(): void
    {
        $this->vonageClient = $this->prophesize(VonageClient::class);
        $this->vonageClient->getRestUrl()->willReturn('https://rest.nexmo.com');
        $this->vonageClient->getApiUrl()->willReturn('https://api.nexmo.com');

        $this->apiResource = new APIResource();
        $this->apiResource
            ->setBaseUri('/video')
            ->setClient($this->vonageClient->reveal())
        ;

        $this->client = new Client($this->apiResource);
    }

    public function testCanCreateSession()
    {
        $this->vonageClient->send(Argument::that(function () {
            return true;
        }))->shouldBeCalledTimes(1)->willReturn($this->getResponse('create-session'));

        $session = $this->client->createSession();

        $this->assertSame('2_999999999999999-MTYxODg4MTU5NjY3N35QY1VEUUl4MVhldEdKU2JCOWlyR2lHY3p-UH4', $session->getSessionId());
        $this->assertSame(null, $session->getLocation());
        $this->assertSame(MediaMode::RELAYED, $session->getMediaMode());
        $this->assertSame(ArchiveMode::MANUAL, $session->getArchiveMode());
        $this->assertSame('99999999', $session->getProjectId());
        $this->assertEquals(new \DateTimeImmutable('2021-01-01 00:00:00'), $session->getCreatedDate());
        $this->assertSame('10.10.10.10', $session->getMediaServerUrl());
    }

    protected function getResponse(string $type = 'success', int $status = 200): Response
    {
        return new Response(fopen(__DIR__ . '/responses/' . $type . '.json', 'rb'), $status);
    }
}
