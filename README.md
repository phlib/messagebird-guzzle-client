# phlib/messagebird-guzzle-client

[![Build Status](https://img.shields.io/travis/phlib/messagebird-guzzle-client/master.svg?style=flat-square)](https://travis-ci.org/phlib/messagebird-guzzle-client)
[![Codecov](https://img.shields.io/codecov/c/github/phlib/messagebird-guzzle-client.svg?style=flat-square)](https://codecov.io/gh/phlib/messagebird-guzzle-client)
[![Latest Stable Version](https://img.shields.io/packagist/v/phlib/messagebird-guzzle-client.svg?style=flat-square)](https://packagist.org/packages/phlib/messagebird-guzzle-client)
[![Total Downloads](https://img.shields.io/packagist/dt/phlib/messagebird-guzzle-client.svg?style=flat-square)](https://packagist.org/packages/phlib/messagebird-guzzle-client)
![Licence](https://img.shields.io/github/license/phlib/messagebird-guzzle-client.svg?style=flat-square)

MessageBird Guzzle HTTP client implementation.

Allows you to replace the Message Bird HTTP Client with an implementation that uses the Guzzle HTTP Client. This means
there is a more control over certain options in the client.

## Install

Via Composer

``` bash
$ composer require phlib/messagebird-guzzle-client
```

## Creating a HTTP Client

``` php
<?php
use Phlib\MbGuzzleClient\Http\Client;
use GuzzleHttp\Client as GuzzleClient;

$guzzleClient = new GuzzleClient($options = []);
$httpClient = new Client(MessageBird\Client::ENDPOINT, $guzzleClient);

```

## Using the HTTP Client with MessageBird

``` php

$messageBird = new \MessageBird\Client('YOUR_ACCESS_KEY', $httpClient);

// OR

$messageBird = new \Phlib\MbGuzzleClient\Client('YOUR_ACCESS_KEY');
 
// Get you balance
$balance = $messageBird->balance->read();

```

## Problems

[HTTPClient injection through constructor is wrongfully reused](https://github.com/messagebird/php-rest-api/issues/29)

When constructing the MessageBird client with a custom HTTP client implementation, as is done here, the side effect is
that the same client is used for API and Chat API endpoints.

## License

This package is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
