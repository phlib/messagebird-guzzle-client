# phlib/mb-guzzle-client

[![Build Status](https://img.shields.io/travis/phlib/mb-guzzle-client/master.svg)](https://travis-ci.org/phlib/mb-guzzle-client)
[![Codecov](https://img.shields.io/codecov/c/github/phlib/mb-guzzle-client.svg)](https://codecov.io/gh/phlib/mb-guzzle-client)
[![Latest Stable Version](https://img.shields.io/packagist/v/phlib/mb-guzzle-client.svg)](https://packagist.org/packages/phlib/mb-guzzle-client)
[![Total Downloads](https://img.shields.io/packagist/dt/phlib/mb-guzzle-client.svg)](https://packagist.org/packages/phlib/mb-guzzle-client)
![Licence](https://img.shields.io/github/license/phlib/mb-guzzle-client.svg?style=flat-square)

MessageBird Guzzle HTTP client implementation.

## Install

Via Composer

``` bash
$ composer require phlib/mb-guzzle-client
```

## Creating a Client

``` php
<?php
use Phlib\MbGuzzleClient\Client;
use GuzzleHttp\Client as GuzzleClient;

$guzzleClient = new GuzzleClient($options = []);
$httpClient = new Client(MessageBird\Client::ENDPOINT, $guzzleClient);

```

## Using the client with MessageBird

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
