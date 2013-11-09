<?php

namespace SimpleJsonRpcClient\Client;
use SimpleJsonRpcClient\Request;

/**
 * Interface for JSON-RPC client implementations.
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
interface ClientInterface
{

	/**
	 * Sends a request and returns the response
	 * @param Request\Request $request the request
	 * @throws \SimpleJsonRpcClient\ClientException if the request fails
	 * @return \SimpleJsonRpcClient\Response the response
	 */
	public function sendRequest(Request\Request $request);

	/**
	 * Sends a notification request
	 * @param Request\Notification $notification the notification
	 * @throws \SimpleJsonRpcClient\ClientException if the request fails
	 */
	public function sendNotification(Request\Notification $notification);
}