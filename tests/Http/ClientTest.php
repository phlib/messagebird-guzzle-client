<?php

namespace Phlib\MbGuzzleClient\Http;

use GuzzleHttp\Client as GClient;
use GuzzleHttp\Exception\RequestException;
use MessageBird\Common\Authentication;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\HttpException;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GClient|\Prophecy\Prophecy\ObjectProphecy
     */
    protected $guzzle;

    public function setUp()
    {
        $this->guzzle = $this->prophesize(GClient::class);
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->guzzle = null;
    }

    public function testChecksForAuthentication()
    {
        $this->expectException(AuthenticateException::class);
        $this->create(false)->performHttpRequest('method', 'resource');
    }

    public function testCallsRequest()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $this->guzzle
            ->request(Argument::any(), Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn($response);
        $client = $this->create();
        $client->performHttpRequest('method', 'resource');
    }

    public function testAuthenticationKeyIsSet()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $this->guzzle
            ->request(Argument::any(), Argument::any(), Argument::containingString('AccessKey'))
            ->shouldBeCalled()
            ->willReturn($response);
        $client = $this->create();
        $client->performHttpRequest('method', 'resource');
    }

    public function testContainsBodyForPostEtalWhenPresent()
    {
        $body = 'ThisIsTheBody';
        $response = $this->prophesize(ResponseInterface::class);
        $this->guzzle
            ->request(Argument::any(), Argument::any(), Argument::withEntry('body', $body))
            ->shouldBeCalled()
            ->willReturn($response);
        $client = $this->create();
        $client->performHttpRequest('POST', 'resource', null, $body);
    }

    public function testRethrowsExceptionsAsMessageBirdException()
    {
        $this->expectException(HttpException::class);
        $this->guzzle
            ->request(Argument::cetera())
            ->willThrow($this->prophesize(RequestException::class)->reveal());
        $this->create()
            ->performHttpRequest('POST', 'resource');
    }

    /**
     * @param bool $withAuth Default to true
     * @return Client
     */
    protected function create($withAuth = true)
    {
        $client = new Client('http://base.url/', $this->guzzle->reveal());
        if ($withAuth) {
            $client->setAuthentication(new Authentication('AccessKey'));
        }
        return $client;
    }
}
