<?php
/**
 * Customer Invitation
 *
 * @package        App
 * @author         Madhur Tandon
 */

namespace GYG\Test;


use GYG\Library\Product;
use GYG\Library\Product\Request;
use GYG\Library\Provider\API;
use GYG\Library\Test\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;


/**
 * @package GYG\Test
 */
class ProductTest extends TestCase
{
	/* @var Request */
	private $Request;

	private $responseDefault = '{"product_availabilities": [
            {"places_available": 25, "activity_duration_in_minutes": 255, "product_id": 679, "activity_start_datetime": "2017-07-07T10:30"},
            {"places_available": 25, "activity_duration_in_minutes": 255, "product_id": 23, "activity_start_datetime": "2017-07-05T10:30"},
            {"places_available": 63, "activity_duration_in_minutes": 915, "product_id": 197, "activity_start_datetime": "2017-12-14T13:45"},
            {"places_available": 3, "activity_duration_in_minutes": 165, "product_id": 679, "activity_start_datetime": "2017-10-07T14:45"},
            {"places_available": 3, "activity_duration_in_minutes": 165, "product_id": 679, "activity_start_datetime": "2017-07-06T14:45"},
            {"places_available": 55, "activity_duration_in_minutes": 1305, "product_id": 277, "activity_start_datetime": "2017-10-10T21:30"}
            ]}';

	/**
	 * @author Madhur Tandon
	 */
	public function setUp()
	{
		$this->setUpRequest($this->DefaultResponse());
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @param Response $response
	 */
	private function setUpRequest(Response $response)
	{
		$mock          = new MockHandler([
											 $response
										 ]);
		$handler       = HandlerStack::create($mock);
		$httpClient    = new Client(['handler' => $handler]);
		$Provider      = (new API($httpClient));
		$this->Request = (new Request($Provider));
	}

	/**
	 * @author Madhur Tandon
	 *
	 * @return Response
	 */
	private function DefaultResponse()
	{
		return new Response(200, ['Content-type' => 'application/json;charset=utf-8'], $this->responseDefault);
	}

	/**
	 * @author Madhur Tandon
	 * @expectedException \GYG\Library\Exception\InvalidData
	 */
	public function testSearchProductWithEmptyParams()
	{
		$this->setUpRequest(new Response(200, ['Content-type' => 'application/json;charset=utf-8'], '{"product_availabilities": []}'));

		(new Product($this->Request))->Search('', '2017-12-15T00:00', 3);
	}

	/**
	 * @author Madhur Tandon
	 */
	public function testSearchProductsEmpty()
	{
		$this->setUpRequest(new Response(200, ['Content-type' => 'application/json;charset=utf-8'], '{"product_availabilities": []}'));

		$response = (new Product($this->Request))->Search('2017-07-04T10:20', '2017-12-15T00:00', 3);
		$this->assertEmpty($response);
	}

	/**
	 * @author Madhur Tandon
	 */
	public function testSearchProductsShouldBeAllProducts()
	{
		$response = (new Product($this->Request))->Search('2017-07-04T10:20', '2017-12-15T00:00', 3);
		$this->assertEquals(4, count($response));
		$this->assertEquals('23', $response[0]['product_id']);
		$this->assertEquals(3, count($response[3]['available_starttimes']));
		$this->assertEquals('2017-07-06T14:45', $response[3]['available_starttimes'][0]);
		$this->assertEquals('2017-07-07T10:30', $response[3]['available_starttimes'][1]);
	}

	/**
	 * @author Madhur Tandon
	 */
	public function testSearchProductsShouldBeThreeProductsWithLessStartTimes()
	{
		$response = (new Product($this->Request))->Search('2017-07-07T10:20', '2017-12-15T00:00', 4);
		$this->assertEquals(3, count($response));
		$this->assertEquals('679', $response[2]['product_id']);
		$this->assertEquals(1, count($response[2]['available_starttimes']));
		$this->assertEquals('2017-07-07T10:30', $response[2]['available_starttimes'][0]);
	}

	/**
	 * @author Madhur Tandon
	 */
	public function testSearchProductsShouldBeOTwoProduct()
	{
		$response = (new Product($this->Request))->Search('2017-10-07T10:20', '2017-11-15T00:00', 3);
		$this->assertEquals(2, count($response));
		$this->assertEquals('679', $response[1]['product_id']);
		$this->assertEquals(1, count($response[1]['available_starttimes']));
		$this->assertEquals('2017-10-07T14:45', $response[1]['available_starttimes'][0]);
	}

	/**
	 * @expectedException \GYG\Library\Exception\RequestFailed
	 */
	public function testSearchProductsShouldBeRaiseException()
	{
		$this->setUpRequest(new Response(502, [], 'Bad Gateway'));
		(new Product($this->Request))->Search('2017-10-07T10:20', '2017-11-15T00:00', 3);
	}

	/**
	 * @expectedException \GYG\Library\Exception\InvalidData
	 */
	public function testSearchProductsErrorParametersShouldBeRaiseException()
	{
		$this->setUpRequest(new Response(200, [], '{}'));
		(new Product($this->Request))->Search('2017-10-07T10:20', '2017-11-15T00:00', 3);
	}
}
