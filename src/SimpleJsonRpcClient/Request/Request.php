<?php

namespace SimpleJsonRpcClient\Request;
use SimpleJsonRpcClient\Client;

/**
 * Represents a standard JSON-RPC v2.0 request
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Request extends BaseRequest
{
	
	/**
	 * @var mixed the request ID
	 */
	private $_id;

	/**
	 * Class constructor
	 * @param string the method
	 * @param mixed the request parameters. Defaults to null, meaning the 
	 * request is sent without parameters
	 * @param mixed optional request ID. Defaults to 0
	 * @throws Exception if any of the parameters are malformed
	 */
	function __construct($method, $params = null, $id = 0)
	{
		parent::__construct($method, $params);
		$this->_id = $id;
	}

	function __toString()
	{
		$object = new \stdClass();
		$object->jsonrpc = Client::JSON_RPC_VERSION;
		$object->method = $this->_method;

		if ($this->_params !== null)
			$object->params = $this->_params;
		
		$object->id = $this->_id;

		return json_encode($object);
	}

}