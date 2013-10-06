<?php

namespace SimpleJsonRpcClient;

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
	 * @param type $endPoint
	 * @param type $username
	 * @param type $password
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