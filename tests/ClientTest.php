<?php

namespace Phlib\MbGuzzleClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultHttpClientCreated()
    {
        $client = new Client();
        $this->assertInstanceOf(Http\Client::class, $this->getPropertyValue($client, 'HttpClient'));
    }

    public function testInjectedHttpClientIsSet()
    {
        $httpClient = $this->prophesize(Http\Client::class)->reveal();
        $client = new Client('MyKey', $httpClient);
        $this->assertSame($httpClient, $this->getPropertyValue($client, 'HttpClient'));
    }

    /**
     * @param object $object
     * @param string $property
     * @return mixed
     */
    protected function getPropertyValue($object, $property)
    {
        $property = (new \ReflectionObject($object))->getProperty($property);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}
