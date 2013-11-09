<?php

namespace SimpleJsonRpcClient\Request;

/**
 * Represents a notification request
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Notification extends BaseRequest
{

	public function __toString()
	{
		$object = new \stdClass();
		$object->jsonrpc = '2.0';
		$object->method = $this->_method;

		if ($this->_params !== null)
			$object->params = $this->_params;

		return json_encode($object);
	}

}