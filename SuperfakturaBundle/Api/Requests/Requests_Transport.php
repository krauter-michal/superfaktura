<?php

namespace Application\SuperfakturaBundle\Api\Requests;

/**
 * Base HTTP transport
 *
 * @package Requests
 * @subpackage Transport
 */
interface Requests_Transport {
	/**
	 * Perform a request
	 *
	 * @param string $url URL to request
	 * @param array $headers Associative array of request headers
	 * @param string|array $data Data to send either as the POST body, or as parameters in the URL for a GET/HEAD
	 * @param array $options Request options, see {@see Request::response()} for documentation
	 * @return string Raw HTTP result
	 */
	public function request($url, $headers = array(), $data = array(), $options = array());

	/**
	 * Self-test whether the transport can be used
	 * @return bool
	 */
	public static function test();
}