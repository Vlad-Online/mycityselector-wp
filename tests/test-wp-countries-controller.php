<?php


use Mcs\WpModels\Country;

class testWpCountriesController extends WP_Test_REST_Controller_Testcase {
	/**
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
		$this->assertEqualSets( array( 'view', 'embed', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );

		// Single.
		$request  = new WP_REST_Request( 'OPTIONS', '/mcs/v1/countries/' . self::$country->id );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertEquals( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertEqualSets( array( 'view', 'embed', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
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
		$this->check_country_data( $countryData, $data );
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
		$params = $this->set_post_data();
		$request->set_body_params( $params );
		$response = rest_get_server()->dispatch( $request );

		$this->check_create_post_response( $response );
	}

	public function test_update_item() {
		// TODO: Implement test_update_item() method.
	}

	public function test_delete_item() {
		// TODO: Implement test_delete_item() method.
	}

	public function test_prepare_item() {
		// TODO: Implement test_prepare_item() method.
	}

	public function test_get_item_schema() {
		// TODO: Implement test_get_item_schema() method.
	}

	protected function check_get_country_response( $response, $context = 'view' ) {
		$this->assertEquals( 200, $response->get_status() );

		$data        = $response->get_data();
		$countryData = Country::findById( $data['id'] );
		$this->check_country_data( $countryData, $data );
	}

	protected function check_country_data( Country $country, $data ) {
		$this->assertEquals( $country->id, $data['id'] );
		$this->assertEquals( $country->subdomain, $data['subdomain'] );
		$this->assertEquals( $country->published, $data['published'] );
		$this->assertEquals( $country->ordering, $data['ordering'] );
		$this->assertEquals( $country->code, $data['code'] );
		$this->assertEquals( $country->domain, $data['domain'] );
		$this->assertEquals( $country->lat, $data['lat'] );
		$this->assertEquals( $country->lng, $data['lng'] );
		$this->assertEquals( $country->default_city_id, $data['default_city_id'] );
	}

	protected function set_post_data( $args = array() ) {
		$defaults = array(
			'title'   => 'Post Title',
			'content' => 'Post content',
			'excerpt' => 'Post excerpt',
			'name'    => 'test',
			'status'  => 'publish',
			'author'  => get_current_user_id(),
			'type'    => 'post',
		);

		return wp_parse_args( $args, $defaults );
	}
}
