<?php

namespace SimpleJsonRpcClient;

/**
 * Represents a response to a batch request. It holds all individual 
 * responses which can be retrieved en masse or individually based on their 
 * respective "id".
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class BatchResponse
{

	/**
	 * @var Response[] the responses. Defaults to an empty array.
	 */
	private $_responses = array();

	/**
	 * Class constructor
	 * @param string $json the raw response JSON
	 */
	public function __construct($json)
	{
		// Decode the response into an array of Request objects
		foreach (json_decode($json) as $response)
			$this->_responses[] = new Response(json_encode($response));
	}

	/**
	 * Returns all the responses
	 * @return array
	 */
	public function getResponses()
	{
		return $this->_responses;
	}

	/**
	 * Returns the specified response, or null if the response is not found
	 * @param mixed $id the response "id"
	 * @return mixed
	 */
	public function getResponse($id)
	{
		foreach ($this->_responses as $response)
			if ($response->id == $id)
				return $response;

		return null;
	}

}
