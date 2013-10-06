<?php

namespace SimpleJsonRpcClient\Request;

/**
 * Base class for JSON-RPC v2.0 requests.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class BaseRequest
{

	/**
	 * @var string the method
	 */
	protected $_method;

	/**
	 * @var mixed the request parameters as a key-value array or null when 
	 * parameters are unused 
	 */
	protected $_params;

	/**
	 * Class constructor
	 * @param string the method
	 * @param mixed the request parameters. Defaults to null, meaning the 
	 * request is sent without parameters
	 * @throws Exception if any of the parameters are malformed
	 */
	function __construct($method, $params = null)
	{
		if ($params !== null && !is_array($params))
			throw new Exception('Parameters must be specified as an array');

		$this->_method = $method;
		$this->_params = $params;
	}

	/**
	 * Turns the request into its JSON representation
	 * @return string the JSON for the request
	 */
	abstract public function __toString();

}