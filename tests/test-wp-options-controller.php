<?php

use Mcs\WpModels\Cities;
use Mcs\WpModels\Options;

class testWpOptionsController extends WP_Test_REST_Controller_Testcase {

	/**
	 * @var int
	 */
	protected static $user;

	/**
	 * @var Options
	 */
	protected $options;

	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		activate_mcs_plugin();
		self::$user = $factory->user->create(
			[
				'role' => 'administrator',
			]
		);
	}

	public static function wpTearDownAfterClass() {
		self::delete_user( self::$user );
	}

	public function setUp() {
		$this->options = new Options();
		$this->options->setBaseDomain( '' );
		$this->options->setDefaultLocationId( null );
		$this->options->setSeoMode( Options::SEO_MODE_COOKIE );
		$this->options->setCountryChooseEnabled( false );
		$this->options->setProvinceChooseEnabled( false );
		$this->options->setAskMode( Options::ASK_MODE_DIALOG );
		$this->options->setRedirectNextVisits( false );
		$this->options->setLogEnabled( false );
		$this->options->setDebugEnabled( false );
	}


	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();
		$this->assertArrayHasKey( '/mcs/v1/Options', $routes );
		$this->assertArrayHasKey( '/mcs/v1/Options/(?P<id>[\d]+)', $routes );
	}

	public function test_context_param() {
		// Collection.
		$request  = new WP_REST_Request( 'OPTIONS', '/mcs/v1/Options' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertArrayHasKey('filter', $data["endpoints"][0]["args"]);
		$this->assertArrayHasKey('range', $data["endpoints"][0]["args"]);
		$this->assertArrayHasKey('sort', $data["endpoints"][0]["args"]);
		//$this->assertEquals( 'view', $data['endpoints'][0]['args']['context']['default'] );
		//$this->assertEqualSets( array( 'view', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );

		// Single.
		$request  = new WP_REST_Request( 'OPTIONS', '/mcs/v1/Options/0' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$this->assertEquals( 'view', $data['endpoints'][0]['args']['context']['default'] );
		$this->assertEqualSets( array( 'view', 'edit' ), $data['endpoints'][0]['args']['context']['enum'] );
	}

	public function test_get_items() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'GET', '/mcs/v1/Options' );
		$request->set_param( 'context', 'view' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );

		$all_data  = $response->get_data();
		$data      = $all_data[0];
		$modelData = new Options();
		$this->check_model_data( $modelData, $data );
	}

	public function test_get_item() {
		wp_set_current_user( self::$user );

		$request  = new WP_REST_Request( 'GET', '/mcs/v1/Options/0' );
		$response = rest_get_server()->dispatch( $request );
		$this->check_get_options_response( $response, 'embed' );
	}

	public function test_create_item() {
		/*wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'POST', '/mcs/v1/Options' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = $this->set_model_data();
		$request->set_body_params( $params );
		$response = rest_get_server()->dispatch( $request );
		$this->check_create_model_response( $response );*/
		$this->assertTrue( true );
	}

	public function test_update_item() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'PUT', '/mcs/v1/Options/0' );
		$request->add_header( 'content-type', 'application/x-www-form-urlencoded' );
		$params = $this->set_model_data();
		$request->set_body_params( $params );
		$response = rest_get_server()->dispatch( $request );

		$this->check_update_model_response( $response );
		$model = new Options();
		$this->check_model_data( $model, $params );
	}

	public function test_delete_item() {
		$this->assertTrue( true );
	}

	public function test_prepare_item() {
		wp_set_current_user( self::$user );

		$request = new WP_REST_Request( 'GET', '/mcs/v1/Options/0' );
		$request->set_query_params( array( 'context' => 'edit' ) );
		$response = rest_get_server()->dispatch( $request );

		$this->check_get_model_response( $response );
	}


	public function test_get_item_schema() {
		$request    = new WP_REST_Request( 'OPTIONS', '/mcs/v1/Options' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];
		$this->assertSame( 10, count( $properties ) );
		$this->assertArrayHasKey( 'ask_mode', $properties );
		$this->assertArrayHasKey( 'base_domain', $properties );
		$this->assertArrayHasKey( 'country_choose_enabled', $properties );
		$this->assertArrayHasKey( 'debug_enabled', $properties );
		$this->assertArrayHasKey( 'default_city_id', $properties );
		$this->assertArrayHasKey( 'log_enabled', $properties );
		$this->assertArrayHasKey( 'province_choose_enabled', $properties );
		$this->assertArrayHasKey( 'redirect_next_visits', $properties );
		$this->assertArrayHasKey( 'seo_mode', $properties );
	}

	protected function check_get_options_response( $response, $context = 'view' ) {
		$this->assertEquals( 200, $response->get_status() );

		$data      = $response->get_data();
		$modelData = new Options();
		$this->check_model_data( $modelData, $data );
	}

	protected function check_model_data( Options $options, $data ) {
		$this->assertEquals( 0, $data['id'] );
		$this->assertEquals( $options->getBaseDomain(), $data['base_domain'] );
		$this->assertEquals( $options->getDefaultLocation()->getId(), $data['default_city_id'] );
		$this->assertEquals( $options->getSeoMode(), $data['seo_mode'] );
		$this->assertEquals( $options->getCountryChooseEnabled(), $data['country_choose_enabled'] );
		$this->assertEquals( $options->getProvinceChooseEnabled(), $data['province_choose_enabled'] );
		$this->assertEquals( $options->getAskMode(), $data['ask_mode'] );
		$this->assertEquals( $options->getRedirectNextVisits(), $data['redirect_next_visits'] );
		$this->assertEquals( $options->getLogEnabled(), $data['log_enabled'] );
		$this->assertEquals( $options->getDebugEnabled(), $data['debug_enabled'] );
	}

	protected function set_model_data( $args = array() ) {
		$city = Cities::all()[0];
		if ( ! $city->published ) {
			$city->update( [
				'id'        => $city->id,
				'published' => 1
			] );
		}
		$defaults = [
			'id'                      => 0,
			'base_domain'             => 'test.com',
			'default_city_id'         => $city->id,
			'seo_mode'                => Options::SEO_MODE_SUBDOMAIN,
			'country_choose_enabled'  => true,
			'province_choose_enabled' => true,
			'ask_mode'                => Options::ASK_MODE_TOOLTIP,
			'redirect_next_visits'    => true,
			'log_enabled'             => true,
			'debug_enabled'           => true
		];

		return wp_parse_args( $args, $defaults );
	}

	protected function check_update_model_response( WP_REST_Response $response ) {
		$this->assertNotWPError( $response );
		$response = rest_ensure_response( $response );

		$this->assertEquals( 200, $response->get_status() );
		$headers = $response->get_headers();
		$this->assertArrayNotHasKey( 'Location', $headers );

		$data = $response->get_data();
		$this->check_model_data( $this->options, $data );
	}

	protected function check_get_model_response( WP_REST_Response $response ) {
		$this->assertNotWPError( $response );
		$response = rest_ensure_response( $response );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();
		$this->check_model_data( $this->options, $data );
	}
}
