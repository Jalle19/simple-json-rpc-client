simple-json-rpc-client
======================

Simple JSON-RPC 2.0 client which utilizes Zend for HTTP functionality. It supports both standard requests and notifications, but not batch requests (mainly due to the single-threaded nature of PHP).

## Installation

Install using Composer (the package is published on Packagist).

## Usage

```php
<?php

use SimpleJsonRpcClient;
use SimpleJsonRpcClient\Request;

// Initialize a client. Credentials are optional.
$client = new Client('localhost', 'username', 'password');

// The client will rethrow all Zend exceptions from its own namespace 
// so we only need to catch one type of exception
try 
{
	// Send a request without parameters. The "id" will be added automatically unless supplied.
	// Request objects return their JSON representation when treated as strings.
	$request = new Request('method');
	$response = $client->sendRequest($request);
	
	// Send a request with parameters specified as an array
	$request = new Request('method', array('param1'=>'value1'));
	$response = $client->sendRequest($request);
	
	// Send a request with parameters specified as an object
	$params = new stdClass();
	$params->param1 = 'value1';
	$request = new Request('method', $params);
	$response = $client->sendRequest($request);
	
	// Send a parameter-less request with specific "id"
	$request = new Request('method', null, 2);
	$response = $client->sendRequest($request);
	
	// Send a notification
	$request = new Notification('notification');
	$client->sendNotification($request);
}
catch (Exception $e) 
{
	echo $e->getMessage();
}
```

## Flags

The client constructor takes a set of flags as the forth parameter. These flags can be used to alter the behavior of the client, mostly useful for working with buggy servers. For example, the `FLAG_ATTEMPT_UTF8_RECOVERY` flag will attempt to avoid "Malformed UTF-8 in response" errors by re-encoding the raw response as UTF-8 before passing it to `json_decode()` (this is only done if the raw response is determined not to be valid UTF-8).

## License

This code is licensed under the [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
