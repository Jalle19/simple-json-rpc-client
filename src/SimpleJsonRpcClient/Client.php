<?php

namespace SimpleJsonRpcClient;
use SimpleJsonRpcClient\Request;

/**
 * Simple JSON-RPC client. It uses the Zend HTTP client for performing the 
 * RPC requests.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Client
{
	
	/**
	 * Normally an exception is thrown when the client receives JSON containing 
	 * malformed UTF-8. When this flag is set, the client will attempt to 
	 * recover such situations by decoding and subsequently encoding the JSON 
	 * response before attempting to unserialize the JSON.
	 */
	const FLAG_ATTEMPT_UTF8_RECOVERY = 1;
	
	/**
	 * The version string
	 */
	const JSON_RPC_VERSION = '2.0';

	/**
	 * @var int the flags that have been set
	 */
	public static $flags;
	
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
	 * @param string $endPoint the URL JSON-RPC API endpoint
	 * @param string $username (optional) username to use
	 * @param string $password (optional) password to use
	 * @param int $flags flags for the client
	 */
	public function __construct($endPoint, $username = null, $password = null, $flags = 0)
	{
		$this->_endPoint = $endPoint;
		$this->_username = $username;
		$this->_password = $password;
		self::$flags = $flags;

		// Initialize the HTTP client
		$this->_httpClient = new \Zend\Http\Client();
		$this->_httpClient->setOptions(array('keepalive'=>true));

		if ($this->_username && $this->_password)
			$this->_httpClient->setAuth($this->_username, $this->_password);
	}

	/**
	 * Sends a request and returns the response
	 * @param \SimpleJsonRpcClient\Request\Request $request the request
	 * @throws \SimpleJsonRpcClient\Exception if the request fails
	 * @return \SimpleJsonRpcClient\Response the response
	 */
	public function sendRequest(Request\Request $request)
	{
		$httpRequest = $this->createHttpRequest($request);

		try
		{
			$httpResponse = $this->_httpClient->dispatch($httpRequest);
		}
		catch (\Exception $e)
		{
			throw new \SimpleJsonRpcClient\Exception($e->getMessage(), $e->getCode());
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
	
	/**
	 * Sends a notification request
	 * @param \SimpleJsonRpcClient\Notification $notification the notification
	 * @throws \SimpleJsonRpcClient\Exception if the request fails
	 */
	public function sendNotification(Request\Notification $notification)
	{
		$httpRequest = $this->createHttpRequest($notification);

		try
		{
			$this->_httpClient->dispatch($httpRequest);
		}
		catch (\Exception $e)
		{
			throw new \SimpleJsonRpcClient\Exception($e->getMessage(), $e->getCode());
		}
	}
	
	/**
	 * Creates a new HTTP POST request with the appropriate content, headers 
	 * and such, which can then be used to send JSON-RPC requests.
	 * @param string $content the request content
	 * @return \Zend\Http\Request the request
	 */
	private function createHttpRequest($content)
	{
		$httpRequest = new \Zend\Http\Request();
		$httpRequest->setUri($this->_endPoint);
		$httpRequest->setMethod(\Zend\Http\Request::METHOD_POST);
		$httpRequest->setContent($content);

		// Set headers
		$headers = $httpRequest->getHeaders();
		$headers->addHeaderLine('Content-Type', 'application/json');
		$httpRequest->setHeaders($headers);

		return $httpRequest;
	}

}