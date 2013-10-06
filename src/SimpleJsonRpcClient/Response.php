<?php

namespace SimpleJsonRpcClient;

/**
 * Respresents a JSON-RPC response
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Response
{

	/**
	 * @var string the JSON-RPC version of the response
	 */
	public $jsonrpc;

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
	 * assembles itself from it. An exception will be thrown if the response 
	 * contains invalid JSON, if the JSON-RPC object represents an error or if 
	 * the JSON-RPC response is invalid.
	 * @param string $json the response data
	 * @throws SimpleJsonRpcClient\Exception
	 */
	public function __construct($json)
	{
		$response = $this->decode($json);

		// Check for mandatory fields
		foreach (array('jsonrpc', 'id') as $attribute)
		{
			if (!isset($response->{$attribute}))
				throw new Exception('Invalid JSON-RPC response. The raw response was: '.$json);

			$this->{$attribute} = $response->{$attribute};
		}

		if ($response->jsonrpc !== Client::JSON_RPC_VERSION)
			throw new Exception('This client only supports JSON-RPC '.Client::JSON_RPC_VERSION);
		
		if (isset($response->error))
			throw new Exception($response->error->message, $response->error->code);
		
		$this->result = $response->result;
	}
	
	/**
	 * Decodes the supplied JSON string, throwing an exception if decoding fails
	 * @param string $json the raw JSON string
	 * @return stdClass the decoded object
	 * @throws Exception if a parse error occurs
	 */
	private function decode($json)
	{
		// Attempt to fix bad UTF-8 data when the right flag is set on the client
		if (Client::$flags & Client::FLAG_ATTEMPT_UTF8_RECOVERY)
			if (!preg_match("//u", $json))
				$json = utf8_encode(utf8_decode($json));
		
		$response = json_decode($json);
		$errorCode = json_last_error();
		$errorDescription = '';

		switch ($errorCode)
		{
			case JSON_ERROR_DEPTH:
				$errorDescription = 'Maximum stack depth exceeded';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$errorDescription = 'Underflow or the modes mismatch';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$errorDescription = 'Unexpected control character found';
				break;
			case JSON_ERROR_SYNTAX:
				$errorDescription = 'Syntax error, malformed JSON';
				break;
			case JSON_ERROR_UTF8:
				$errorDescription = 'Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
			default:
				$errorDescription = 'Unknown error';
				break;
		}

		if ($errorCode !== JSON_ERROR_NONE)
			throw new Exception('Unable to decode JSON response: '.$errorDescription, $errorCode);

		return $response;
	}

}