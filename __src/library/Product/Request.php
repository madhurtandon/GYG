<?php
/**
 * GYG product availabilities
 *
 * @package        GYG
 * @author         Madhur Tandon
 */

namespace GYG\Library\Product;


use GYG\Library\Exception;
use GYG\Library\Provider;


/**
 * Request class validate the response from the API
 *
 * @package GYG\Library\Product
 */
class Request
{
	const PRODUCT_AVAILABILITIES       = 'product_availabilities';
	const PRODUCT_ID                   = 'product_id';
	const PLACES_AVAILABLE             = 'places_available';
	const ACTIVITY_START_DATETIME      = 'activity_start_datetime';
	const ACTIVITY_DURATION_IN_MINUTES = 'activity_duration_in_minutes';

	/* @var Provider */
	protected $Provider;

	/**
	 * @param Provider $Provider
	 */
	public function __construct(Provider $Provider)
	{
		$this->Provider = $Provider;
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @return array
	 * @throws Exception\InvalidData
	 */
	public function GetProducts()
	{
		$products = $this->Provider->Response();

		$this->Validate($products);

		return $products[self::PRODUCT_AVAILABILITIES];
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param array $products
	 *
	 * @throws Exception\InvalidData
	 */
	public function Validate(array $products)
	{
		if (!isset($products[self::PRODUCT_AVAILABILITIES])) {
			throw new Exception\InvalidData('Missing product availabilities');
		}
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param array $product
	 *
	 * @return bool
	 */
	public function ValidateItem($product)
	{
		return isset($product[self::ACTIVITY_START_DATETIME])
			&& isset($product[self::PLACES_AVAILABLE])
			&& isset($product[self::ACTIVITY_DURATION_IN_MINUTES])
			&& isset($product[self::PRODUCT_ID]);
	}
}