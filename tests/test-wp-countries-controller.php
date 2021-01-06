<?php


use Mcs\WpModels\Country;

class testWpCountriesController extends WP_Test_REST_Controller_Testcase {
	/**
	 * /**
	 * @var Country
	 */
	protected static $country;

	/**
	 * @var WP_User
	 */
	protected static $user;

	public static function wpSetUpBeforeClass( $factory ) {
		activate_mcs_plugin();
		self::$country = Country::create( [
			'subdomain' => 'test',
			'code'      => 'ru',
			'domain'    => 'ru'
		] );
		self::$user    = $factory->user->create(
			array(
				'role' => 'administrator',
			)
		);
	}

	public static function wpTearDownAfterClass() {
		self::$country->delete();
		self::delete_user( self::$user );
	}


	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();
		$this->assertArrayHasKey( '/mcs/v1/countries', $routes );
		$this->assertArrayHasKey( '/mcs/v1/countries/(?P<id>[\d]+)', $routes );
	}

	public function test_context_param() {
		// Collection.
		$request  = new WP_REST_Request( 'OPTIONS', '/mcs/v1/countries' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertEquals( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertEqualSets( array( 'view', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );

		// Single.
		$request  = new WP_REST_Request( 'OPTIONS', '/mcs/v1/countries/' . self::$country->id );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertEquals( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertEqualSets( array( 'view', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
	}

	public function test_get_items() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'GET', '/mcs/v1/countries' );
		$request->set_param( 'context', 'view' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );

		$all_data    = $response->get_data();
		$data        = $all_data[0];
		$countryData = Country::findById( $data['id'] );
		//$userdata = get_userdata( $data['id'] );
		$this->check_model_data( $countryData, $data );
	}

	public function test_get_item() {
		$country_id = self::$country->id;

		wp_set_current_user( self::$user );

		$request  = new WP_REST_Request( 'GET', sprintf( '/mcs/v1/countries/%d', $country_id ) );
		$response = rest_get_server()->dispatch( $request );
		$this->check_get_country_response( $response, 'embed' );
	}

	public function test_create_item() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'POST', '/mcs/v1/countries' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = $this->set_model_data();
		$request->set_body_params( $params );
		$response = rest_get_server()->dispatch( $request );

		$this->check_create_model_response( $response );
	}

	public function test_update_item() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'PUT', sprintf( '/mcs/v1/countries/%d', self::$country->id ) );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = $this->set_model_data();
		$request->set_body_params( $params );
		$response = rest_get_server()->dispatch( $request );

		$this->check_update_model_response( $response );
		$model = Country::findById( self::$country->id );
		$this->check_model_data( $model, $params );
	}

	public function test_delete_item() {
		wp_set_current_user( self::$user );

		$request  = new WP_REST_Request( 'DELETE', sprintf( '/mcs/v1/countries/%d', self::$country->id ) );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );

		$this->expectException( 'Exception' );
		Country::findById( self::$country->id );
	}

	public function test_prepare_item() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'GET', sprintf( '/mcs/v1/countries/%d', self::$country->id ) );
		$request->set_query_params( array( 'context' => 'edit' ) );
		$response = rest_get_server()->dispatch( $request );

		$this->check_get_model_response( $response );
	}


	public function test_get_item_schema() {
		$request    = new WP_REST_Request( 'OPTIONS', '/mcs/v1/countries' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];
		$this->assertSame( 9, count( $properties ) );
		$this->assertArrayHasKey( 'id', $properties );
		$this->assertArrayHasKey( 'subdomain', $properties );
		$this->assertArrayHasKey( 'published', $properties );
		$this->assertArrayHasKey( 'ordering', $properties );
		$this->assertArrayHasKey( 'code', $properties );
		$this->assertArrayHasKey( 'domain', $properties );
		$this->assertArrayHasKey( 'lat', $properties );
		$this->assertArrayHasKey( 'lng', $properties );
		$this->assertArrayHasKey( 'default_city_id', $properties );
	}

	protected function check_get_country_response( $response, $context = 'view' ) {
		$this->assertEquals( 200, $response->get_status() );

		$data        = $response->get_data();
		$countryData = Country::findById( $data['id'] );
		$this->check_model_data( $countryData, $data );
	}

	protected function check_model_data( Country $country, $data ) {
		if ( isset( $data['id'] ) ) {
			$this->assertEquals( $country->id, $data['id'] );
		}

		$this->assertEquals( $country->subdomain, $data['subdomain'] );
		$this->assertEquals( $country->published, $data['published'] );
		$this->assertEquals( $country->ordering, $data['ordering'] );
		$this->assertEquals( $country->code, $data['code'] );
		$this->assertEquals( $country->domain, $data['domain'] );
		$this->assertEquals( $country->lat, $data['lat'] );
		$this->assertEquals( $country->lng, $data['lng'] );
		$this->assertEquals( $country->default_city_id, $data['default_city_id'] );
	}

	protected function set_model_data( $args = array() ) {
		$defaults = array(
			'subdomain'       => 'test-subdomain',
			'published'       => 1,
			'ordering'        => 10,
			'code'            => 'ru',
			'domain'          => 'test-domain',
			'lat'             => 30.000000,
			'lng'             => 50.000000,
			'default_city_id' => null
		);

		return wp_parse_args( $args, $defaults );
	}

	protected function check_create_model_response( WP_REST_Response $response ) {
		$this->assertNotWPError( $response );
		$response = rest_ensure_response( $response );

		$this->assertEquals( 201, $response->get_status() );
		$headers = $response->get_headers();
		$this->assertArrayHasKey( 'Location', $headers );

		$data = $response->get_data();

		$model = Country::findById( $data['id'] );
		$this->check_model_data( $model, $data );
	}

	protected function check_update_model_response( WP_REST_Response $response ) {
		$this->assertNotWPError( $response );
		$response = rest_ensure_response( $response );

		$this->assertEquals( 200, $response->get_status() );
		$headers = $response->get_headers();
		$this->assertArrayNotHasKey( 'Location', $headers );

		$data  = $response->get_data();
		$model = Country::findById( $data['id'] );
		$this->check_model_data( $model, $data );
	}

	protected function check_get_model_response( WP_REST_Response $response ) {
		$this->assertNotWPError( $response );
		$response = rest_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );

		$data  = $response->get_data();
		$model = Country::findById( $data['id'] );
		$this->check_model_data( $model, $data );
	}
}
