<?php
/**
 * GYG product availabilities
 *
 * @package        GYG
 * @author         Madhur Tandon
 */

namespace GYG\Library\Client;


use GuzzleHttp\Client;
use GYG\Library\Exception;


/**
 * API client to retrieve the data from the external API
 *
 * @package GYG\Library\Client
 */
class API
{
	const URL = 'http://www.mocky.io/v2/58ff37f2110000070cf5ff16';

	/* @var \GuzzleHttp\Client */
	protected $HTTPClient;

	/**
	 * @author Madhur Tandon
	 *
	 * @param Client $Client
	 */
	public function __construct(Client $Client)
	{
		$this->HTTPClient = $Client;
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @return array
	 * @throws Exception\RequestFailed
	 */
	public function Response()
	{
		$clientResponse = $this->HTTPClient->get(self::URL);
		if ($clientResponse->getStatusCode() !== 200) {
			throw new Exception\RequestFailed('Error to fetch data');
		}

		$response = json_decode($clientResponse->getBody()->getContents(), true);

		return $response;
	}
}