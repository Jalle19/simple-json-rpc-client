<?php

/**
 * Represents a JSON-RPC v2.0 request
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */

namespace SimpleJsonRpcClient;

class Request
{
	/**
	 * The version string
	 */

	const JSON_RPC_VERSION = '2.0';

	/**
	 * @var string the method
	 */
	private $_method;

	/**
	 * @var mixed the request parameters as a key-value array or null when 
	 * parameters are unused 
	 */
	private $_params;

	/**
	 * @var int optional request ID
	 */
	private $_id;

	/**
	 * Class constructor
	 * @param string the method
	 * @param mixed the request parameters. Defaults to null, meaning the 
	 * request is sent without parameters
	 * @param int optional request ID. Defaults to null, meaning no ID will be 
	 * used in the request
	 * @throws Exception if any of the parameters are malformed
	 */
	function __construct($method, $params = null, $id = null)
	{
		if ($params !== null && !is_array($params))
			throw new Exception('Parameters must be specified as an array');

		if ($id !== null && !is_numeric($id))
			throw new Exception('ID must be specified as an array');

		$this->_method = $method;
		$this->_params = $params;
		$this->_id = $id !== null ? (int)$id : $id;
	}

	/**
	 * Turns the request into its JSON representation
	 * @return string the JSON for the request
	 */
	function __toString()
	{
		$object = new \stdClass();
		$object->jsonrpc = self::JSON_RPC_VERSION;
		$object->method = $this->_method;

		if ($this->_params !== null)
			$object->params = $this->_params;

		if ($this->_id !== null)
			$object->id = $this->_id;

		return json_encode($object);
	}

}