simple-json-rpc-client
======================

Simple JSON-RPC 2.0 client which utilizes Zend for HTTP functionality.

## Installation

Install using Composer

## Usage

```php
<?php

use SimpleJsonRpcClient;

// Initialize a client. Credentials are optional.
$client = new Client('localhost', 'username', 'password');

// The client will rethrow all Zend exceptions from its own namespace 
// so we only need to catch one type of exception
try 
{
	// Only the first parameter is required. 
	$request = new Request('method', array('param1'=>'value1'), 1);
	
	// When treated as a string the Request object returns its JSON representation
	
	// Returns a Response object
	$response = $client->performRequest($request));
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
