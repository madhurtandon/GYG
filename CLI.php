<?php
/**
 * GYG product availabilities
 *
 * @package        GYG
 * @author         Madhur Tandon
 */

namespace GYG;


use GuzzleHttp\Client;
use GYG\Library\Product;
use GYG\Library\Provider\API;


/**
 * A CLI class responsible to print the product availabilities
 *
 * @package GYG
 */
class CLI
{
	/**
	 * @author Madhur Tandon
	 *
	 * @param string $startTime
	 * @param string $endTime
	 * @param int    $numberOfTravellers
	 *
	 * @throws Library\Exception\InvalidData
	 */
	public function Solution($startTime, $endTime, $numberOfTravellers)
	{
		// Initialise the HTTP Client
		$HTTPClient = new Client();

		// Initialise the Provider
		$Provider   = new API($HTTPClient);

		// Now we will search the products based on the CLI arguments
		$Product           = (new Product(new Product\Request($Provider)));
		$availableProducts = $Product->Search($startTime, $endTime, $numberOfTravellers);

		// Return the response in JSON format
		(new Product\Response())->JSON($availableProducts);
	}
}