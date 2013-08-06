<?php

/**
 * Respresents a JSON-RPC response
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace SimpleJsonRpcClient;

class Response
{

	/**
	 * @var string the JSON-RPC version of the response
	 */
	public $version;

	/**
	 * @var object the result
	 */
	public $result;

	/**
	 * @var int the ID of the response
	 */
	public $id;

	/**
	 * Class constructor. It takes a raw response in JSON as parameter and 
	 * assembles itself from it. If the response represents an error, an 
	 * exception will be thrown
	 * @param string $json the response data
	 * @throws SimpleJsonRpcClient\Exception if response represents an error
	 */
	public function __construct($json)
	{
		$response = json_decode($json);
		
		$this->version = $response->jsonrpc;
		$this->id = $response->id;
		
		if (isset($response->error))
			throw new Exception($response->error->message, $response->error->code);
		
		$this->result = $response->result;
	}

}