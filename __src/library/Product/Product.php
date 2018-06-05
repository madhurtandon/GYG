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
		if (empty($startTime)) {
			throw new Exception\InvalidData('Start time can not be empty');
		} else if (empty($endTime)) {
			throw new Exception\InvalidData('End time can not be empty');
		} else if (empty($numberOfTravellers)) {
			throw new Exception\InvalidData('Number of travellers can not empty');
		}

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
					if (array_key_exists($product[Request::PRODUCT_ID], $results)) {
						array_push($results[$product[Request::PRODUCT_ID]][self::AVAILABLE_STARTTIMES], $product[Request::ACTIVITY_START_DATETIME]);
						usort($results[$product[Request::PRODUCT_ID]][self::AVAILABLE_STARTTIMES], [$this, 'Sort']);
					} else {
						$results[$product[Request::PRODUCT_ID]] = [self::AVAILABLE_STARTTIMES => [$product[Request::ACTIVITY_START_DATETIME]]];
					}
				}
			}
		}

		ksort($results);

		$finalResults = [];
		foreach ($results as $productID => $result) {
			$finalResults[] = [self::PRODUCT_ID           => $productID,
							   self::AVAILABLE_STARTTIMES => $result[self::AVAILABLE_STARTTIMES]];
		}

		return $finalResults;
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
	 * @param string $a
	 * @param string $b
	 *
	 * @return int
	 */
	public function Sort($a, $b)
	{
		return $this->ConvertISOToUnixTimestamp($a) - $this->ConvertISOToUnixTimestamp($b);
	}
}