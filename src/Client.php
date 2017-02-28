<?php

namespace Phlib\MbGuzzleClient;

use MessageBird\Client as BaseClient;
use MessageBird\Common\HttpClient;
use Phlib\MbGuzzleClient\Http\Client as MbGuzzleClient;
use GuzzleHttp\Client as GuzzleClient;

class Client extends BaseClient
{
    /**
     * @param string|null $accessKey
     * @param HttpClient|null $httpClient
     */
    public function __construct($accessKey = null, HttpClient $httpClient = null)
    {
        if ($httpClient === null) {
            $httpClient = $this->createDefaultClient(self::ENDPOINT);
        }

        parent::__construct($accessKey, $httpClient);
    }

    /**
     * @param string $endPoint
     * @return MbGuzzleClient
     */
    protected function createDefaultClient($endPoint)
    {
        return new MbGuzzleClient($endPoint, new GuzzleClient());
    }
}
