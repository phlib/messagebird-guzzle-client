<?php

namespace Phlib\MbGuzzleClient\Http;

use GuzzleHttp\Exception\RequestException;
use MessageBird\Common\HttpClient;
use GuzzleHttp\Client as GuzzleClient;
use MessageBird\Exceptions\AuthenticateException;
use MessageBird\Exceptions\HttpException;

class Client extends HttpClient
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * @param string $endpoint
     * @param GuzzleClient $client
     */
    public function __construct($endpoint, GuzzleClient $client)
    {
        $this->endpoint = $endpoint;
        $this->guzzleClient = $client;
    }

    /**
     * @return GuzzleClient
     */
    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * @param string $method
     * @param string $resourceName
     * @param mixed $query
     * @param string|null $body
     * @return array
     * @throws AuthenticateException
     * @throws HttpException
     */
    public function performHttpRequest($method, $resourceName, $query = null, $body = null)
    {
        if ($this->Authentication === null) {
            throw new AuthenticateException('Can not perform API Request without Authentication');
        }

        try {
            $uri      = $this->getRequestUrl($resourceName, $query);
            $options  = $this->getGuzzleOptions($method, $body);
            $response = $this->guzzleClient->request($method, $uri, $options);

            return [
                $response->getStatusCode(),
                $response->getHeaders(),
                (string)$response->getBody()
            ];
        } catch (RequestException $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param string $method
     * @param string|null $body
     * @return array
     * @throws HttpException
     */
    private function getGuzzleOptions($method, $body)
    {
        // Some servers have outdated or incorrect certificates, Use the included CA-bundle
        $caFile = realpath(__DIR__ . '/../../vendor/messagebird/php-rest-api/src/MessageBird/ca-bundle.crt');
        if (!file_exists($caFile)) {
            throw new HttpException(sprintf('Unable to find CA-bundle file "%s".', 'ca-bundle.crt'));
        }

        $options = [
            'headers' => [
                'User-Agent'     => implode(' ', $this->userAgent),
                'Accept'         => 'application/json',
                'Content-Type'   => 'application/json',
                'Accept-Charset' => 'utf-8',
                'Authorization'  => sprintf('AccessKey %s', $this->Authentication->accessKey)
            ],
            'ssl_key' => $caFile
        ];

        $method = strtoupper($method);
        if (in_array($method, ['PUT', 'POST', 'PATCH']) && !empty($body)) {
            $options['body'] = $body;
        }

        return $options;
    }
}
