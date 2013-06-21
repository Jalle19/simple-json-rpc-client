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
	private static $_httpClient;

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
	 * Class constructor. It sets properties and initializes the HTTP client
	 * @param type $endPoint
	 * @param type $username
	 * @param type $password
	 */
	public function __construct($endPoint, $username = null, $password = null)
	{
		$this->_endPoint = $endPoint;
		$this->_username = $username;
		$this->_password = $password;

		if (!isset(self::$_httpClient))
			$this->initHttpClient();
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

		$httpResponse = self::$_httpClient->dispatch($httpRequest);

		// TODO: Error handling
		return new Response($httpResponse->getContent());
	}

	/**
	 * Initializes the HTTP client
	 */
	private function initHttpClient()
	{
		self::$_httpClient = new \Zend\Http\Client();

		if ($this->_username && $this->_password)
			self::$_httpClient->setAuth($this->_username, $this->_password);
	}

}