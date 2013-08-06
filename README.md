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
	$request = new Request('method', array('param1'=>'value1), 1);
	
	// When treated as a string the Request object returns its JSON representation
	
	// Returns a Response object
	$response = $client->performRequest($request));
}
catch (Exception $e) 
{
	echo $e->getMessage();
}
```

## License

This code is licensed under the [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
