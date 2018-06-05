<?php
/**
 * GYG product availabilities
 *
 * @package        GYG
 * @author         Madhur Tandon
 */

namespace GYG\Library;


use GYG\Library\Product\Request;


/**
 * Product class will search the products and also responsible to perform the filtration of the API response with the available inputs
 *
 * @package GYG\Library
 */
class Product
{
	const PRODUCT_ID           = 'product_id';
	const AVAILABLE_STARTTIMES = 'available_starttimes';

	/* @var Request */
	protected $Request;

	/**
	 * @param Request $Request
	 */
	public function __construct(Request $Request)
	{
		$this->Request = $Request;
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param string $startTime
	 * @param string $endTime
	 * @param int    $numberOfTravellers
	 *
	 * @return array
	 * @throws Exception\InvalidData
	 */
	public function Search($startTime, $endTime, $numberOfTravellers)
	{
		$results = [];

		$products = $this->Request->GetProducts();
		foreach ($products as $product) {
			if ($this->Request->ValidateItem($product)) {
				$startTimestamp         = $this->ConvertISOToUnixTimestamp($startTime);
				$endTimestamp           = $this->ConvertISOToUnixTimestamp($endTime);
				$activityStartTimestamp = $this->ConvertISOToUnixTimestamp($product[Request::ACTIVITY_START_DATETIME]);

				if ($activityStartTimestamp >= $startTimestamp
					&& $activityStartTimestamp <= $endTimestamp
					&& $product[Request::PLACES_AVAILABLE] >= $numberOfTravellers) {
					$results[] = [self::PRODUCT_ID           => $product[Request::PRODUCT_ID],
								  self::AVAILABLE_STARTTIMES => $product[Request::ACTIVITY_START_DATETIME]];
				}
			}
		}

		return $results;
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param array $results
	 *
	 * @return array
	 */
	public function Sort(array $results)
	{
		uasort($results, [$this, 'USort']);

		$response = [];
		foreach ($results as $result) {
			$response[] = [self::PRODUCT_ID           => $result[self::PRODUCT_ID],
						   self::AVAILABLE_STARTTIMES => [$result[self::AVAILABLE_STARTTIMES]]];
		}

		return $response;
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param string $isoTimeFormat
	 *
	 * @return int
	 */
	private function ConvertISOToUnixTimestamp(string $isoTimeFormat)
	{
		return (new \DateTime($isoTimeFormat))->getTimestamp();
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param array $a
	 * @param array $b
	 *
	 * @return int
	 */
	public function USort($a, $b)
	{
		return $this->ConvertISOToUnixTimestamp($a['available_starttimes']) - $this->ConvertISOToUnixTimestamp($b['available_starttimes']);
	}
}