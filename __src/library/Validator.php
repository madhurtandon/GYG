<?php
/**
 * GYG product availabilities
 *
 * @package        GYG
 * @author         Madhur Tandon
 */

namespace GYG\Library;


/**
 * @package GYG
 */
class Validator
{
	/**
	 * @author Madhur Tandon
	 *
	 * @param array $arguments
	 *
	 * @return bool
	 * @throws Exception\InvalidArgument
	 * @throws Exception\InvalidData
	 */
	public function IsValidCLIArguments(array $arguments)
	{
		if (!array_key_exists(1, $arguments)
			|| !array_key_exists(2, $arguments)
			|| !array_key_exists(3, $arguments)
		) {
			throw new Exception\InvalidArgument('Must be 3 parameters');
		}

		if (!$this->IsValidISO8601DateFormat($arguments[1]) || !$this->IsValidISO8601DateFormat($arguments[2])) {
			throw new Exception\InvalidData('The first and second parameter should be valid ISO 8601 date format');
		}

		if (intval($arguments[3]) <= 0) {
			throw new Exception\InvalidData('The third param must be a integer greater than 0');
		}

		return true;
	}

	/**
	 * @author Madhur Tandon <madhur.tandon@kayako.com>
	 *
	 * @param string $date
	 *
	 * @return bool
	 * @throws Exception\InvalidArgument
	 */
	public function IsValidISO8601DateFormat($date)
	{
		if (empty($date)) {
			throw new Exception\InvalidArgument('The first and second parameter should be valid ISO 8601 date format');
		}

		if (preg_match('/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/', $date) > 0) {
			return true;
		}

		return false;
	}

}