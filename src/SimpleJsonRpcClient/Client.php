<?php

/**
 * Simple JSON-RPC client. It uses the Zend HTTP client for performing the 
 * RPC requests.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */

namespace SimpleJsonRpcClient;

class Client
{

	/**
	 * @var \Zend\Http\Client the HTTP client
	 */
	private $_httpClient;

	/**
	 * @var string the JSON-RPC API end-point URL
	 */
	private $_endPoint;

	/**
	 * @var string the username used with HTTP authentication
	 */
	private $_username;

	/**
	 * @var password the username used with HTTP authentication
	 */
	private $_password;

	/**
	 * Class constructor
	 * @param type $endPoint
	 * @param type $username
	 * @param type $password
	 */
	public function __construct($endPoint, $username = null, $password = null)
	{
		$this->_endPoint = $endPoint;
		$this->_username = $username;
		$this->_password = $password;

		// Initialize the HTTP client
		$this->_httpClient = new \Zend\Http\Client();

		if ($this->_username && $this->_password)
			$this->_httpClient->setAuth($this->_username, $this->_password);
	}

	/**
	 * Performs a request and returns the response
	 * @param \SimpleJsonRpcClient\Request $request a JSON-RPC request
	 * @return \SimpleJsonRpcClient\Response the response
	 */
	public function performRequest($request)
	{
		$httpRequest = new \Zend\Http\Request();
		$httpRequest->setUri($this->_endPoint);
		$httpRequest->setMethod(\Zend\Http\Request::METHOD_POST);
		$httpRequest->setContent($request);

		// Set headers
		$headers = $httpRequest->getHeaders();
		$headers->addHeaderLine('Content-Type', 'application/json');
		$httpRequest->setHeaders($headers);

		// Try to dispatch the request. If it fails we re-throw the exception 
		// from our own namespace
		try
		{
			$httpResponse = $this->_httpClient->dispatch($httpRequest);
		}
		catch (\Exception $e)
		{
			throw new \SimpleJsonRpcClient\Exception($e->getMessage());
		}
		
		// Check status
		if (!$httpResponse->isSuccess())
		{
			throw new \SimpleJsonRpcClient\Exception(
					$httpResponse->getReasonPhrase(), 
					$httpResponse->getStatusCode());
		}

		return new Response($httpResponse->getContent());
	}

}