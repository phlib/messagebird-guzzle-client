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
            ->request(Argument::cetera())
            ->shouldBeCalled()
            ->willReturn($response);
        $client = $this->create();
        $client->performHttpRequest('method', 'resource');
    }

    public function testAuthenticationKeyIsSet()
    {
        $accessKey = 'ThisIsMySecret';
        $checkCallback = function ($options) use ($accessKey) {
            return array_key_exists('headers', $options) &&
                array_key_exists('Authorization', $options['headers']) &&
                $options['headers']['Authorization'] == "AccessKey {$accessKey}";
        };

        $response = $this->prophesize(ResponseInterface::class);
        $this->guzzle
            ->request(Argument::any(), Argument::any(), Argument::that($checkCallback))
            ->shouldBeCalled()
            ->willReturn($response);
        $this->create($accessKey)
            ->performHttpRequest('method', 'resource');
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
     * @param string $secretKey
     * @return Client
     */
    protected function create($secretKey = 'NoneSet')
    {
        $client = new Client('http://base.url/', $this->guzzle->reveal());
        if ($secretKey) {
            $client->setAuthentication(new Authentication($secretKey));
        }
        return $client;
    }
}
