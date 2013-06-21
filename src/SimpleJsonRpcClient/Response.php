<?php

/**
 * Respresents a JSON-RPC response
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
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
	 * assembles itself from it.
	 * @param string $json the response data
	 */
	public function __construct($json)
	{
		$response = json_decode($json);

		$this->version = $response->jsonrpc;
		$this->result = $response->result;
		$this->id = $response->id;
	}

}