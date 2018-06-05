<?php
/**
 * GYG product availabilities
 *
 * @package        GYG
 * @author         Madhur Tandon
 */

namespace GYG\Library\Product;


/**
 * Response class will display the output in desired format
 *
 * @package GYG\Library\Product
 */
class Response
{
	/**
	 * @param array $data
	 */
	public function JSON(array $data)
	{
		echo json_encode($data, JSON_PRETTY_PRINT);
	}
}