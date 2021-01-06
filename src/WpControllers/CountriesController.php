<?php

namespace Mcs\WpControllers;

use Exception;
use Mcs\Interfaces\ModelInterface;
use Mcs\WpModels\Country;
use stdClass;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class CountriesController extends WP_REST_Controller {

	protected $namespace = 'mcs/v1';
	protected $rest_base = 'countries';

	// Here initialize our namespace and resource name.
	public function __construct() {
		/*$this->namespace     = '/mcs/v1';
		$this->resource_name = 'countries';*/
	}

	// Register our routes.
	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base, [
			// Here we register the readable endpoint for collections.
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_items' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
				'args'                => $this->get_collection_params(),
			],
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'create_item' ],
				'permission_callback' => [ $this, 'create_item_permissions_check' ],
				'args'                => $this->get_endpoint_args_for_item_schema(),
			],
			// Register our schema callback.
			'schema' => array( $this, 'get_public_item_schema' ),
		] );

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<id>[\d]+)',
			[
				'args'   => [
					'id' => [
						'description' => __( 'Unique identifier for the country.' ),
						'type'        => 'integer',
					],
				],
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'context' => $this->get_context_param( [ 'default' => 'view' ] ),
					],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'update_item_permissions_check' ],
					'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => [
						'force' => [
							'type'        => 'boolean',
							'default'     => false,
							'description' => __( 'Required to be true, as countries do not support trashing.' )
						]
					],
				],
				'schema' => [ $this, 'get_public_item_schema' ],
			]
		);
	}

	/**
	 * Check permissions for the posts.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return bool|WP_Error
	 */
	public function get_items_permissions_check( $request ) {
		//xdebug_break();
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the countries resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}

		return true;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool|WP_Error
	 */
	public function create_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot create the countries resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}

		return true;
	}

	/**
	 * @return bool|WP_Error
	 */
	protected function check_delete_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot delete the countries resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}

		return true;
	}


	/**
	 * Grabs the five most recent posts and outputs them as a rest response.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_items( $request ) {

		/*$args  = array(
			'post_per_page' => 5,
		);*/
		$countries = Country::all();

		$data = array();

//		if ( empty( $countries ) ) {
//			return rest_ensure_response( $data );
//		}

		foreach ( $countries as $country ) {
			$response = $this->prepare_item_for_response( $country, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}

		$total    = Country::total();
		$range    = $request['range'];
		$per_page = 10;
		$start    = 0;
		$end      = $start + $per_page;
		if ( $range ) {
			$rangeData = json_decode( $range, true );
			$start     = (int) $rangeData[0] ?? $start;
			$end       = (int) $rangeData[1] ?? $end;
			$per_page  = $end - $start + 1;
		}

		//$per_page    = $request['per_page'];
		$max_pages = ceil( $total / (int) $per_page );

		// Return all of our comment response data.
		$response = rest_ensure_response( $data );
		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );
		$response->header( 'Content-Range', "countries {$start}-{$end}/{$total}" );

		return $response;
	}

	/**
	 * Check permissions for the posts.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return bool|WP_Error
	 */
	public function get_item_permissions_check( $request ) {
		if ( ! current_user_can( 'read' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the post resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}

		return true;
	}

	/**
	 * Grabs the five most recent posts and outputs them as a rest response.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @throws Exception
	 */
	public function get_item( $request ) {
		$id      = (int) $request['id'];
		$country = Country::findById( $id );

		if ( empty( $country ) ) {
			return rest_ensure_response( array() );
		}

		$country = $this->prepare_item_for_response( $country, $request );

		// Return all of our post response data.
		return rest_ensure_response( $country );
	}

	/**
	 * Prepare a response for inserting into a collection of responses.
	 *
	 * This is copied from WP_REST_Controller class in the WP REST API v2 plugin.
	 *
	 * @param WP_REST_Response $response Response object.
	 *
	 * @return array|WP_REST_Response
	 */
	public function prepare_response_for_collection( $response ) {
		if ( ! ( $response instanceof WP_REST_Response ) ) {
			return $response;
		}

		$data   = (array) $response->get_data();
		$server = rest_get_server();

		if ( method_exists( $server, 'get_compact_response_links' ) ) {
			$links = call_user_func( array( $server, 'get_compact_response_links' ), $response );
		} else {
			$links = call_user_func( array( $server, 'get_response_links' ), $response );
		}

		if ( ! empty( $links ) ) {
			$data['_links'] = $links;
		}

		return $data;
	}

	// Sets up the proper HTTP status code for authorization.
	public function authorization_status_code() {

		$status = 401;

		if ( is_user_logged_in() ) {
			$status = 403;
		}

		return $status;
	}

	public function get_collection_params() {
		$query_params = parent::get_collection_params();

		$query_params['context']['default'] = 'view';

		$query_params['exclude'] = array(
			'description' => __( 'Ensure result set excludes specific IDs.' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		);

		$query_params['include'] = array(
			'description' => __( 'Limit result set to specific IDs.' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		);

		$query_params['offset'] = array(
			'description' => __( 'Offset the result set by a specific number of items.' ),
			'type'        => 'integer',
		);

		$query_params['range'] = array(
			'description' => __( 'Range the result set by a specific number of items.' ),
			'type'        => 'string',
		);

		$query_params['order'] = array(
			'default'     => 'asc',
			'description' => __( 'Order sort attribute ascending or descending.' ),
			'enum'        => array( 'asc', 'desc' ),
			'type'        => 'string',
		);

		$query_params['orderby'] = array(
			'default'     => 'name',
			'description' => __( 'Sort collection by object attribute.' ),
			'enum'        => array(
				'id',
				'include',
				'name',
				'registered_date',
				'slug',
				'include_slugs',
				'email',
				'url',
			),
			'type'        => 'string',
		);

		$query_params['filter'] = array(
			'default'     => null,
			'description' => __( 'Filter collection by property.' ),
			'type'        => 'string',
		);

		return $query_params;
	}

	/**
	 * @param mixed $item
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data = [];
		foreach ( $item->getProperties() as $property ) {
			$data[ $property ] = $item->$property;
		}

		return rest_ensure_response( $data );
	}

	public function create_item( $request ) {
		if ( ! empty( $request['id'] ) ) {
			return new WP_Error(
				'rest_country_exists',
				__( 'Cannot create existing country.' ),
				array( 'status' => 400 )
			);
		}

		$preparedData = (array) $this->prepare_item_for_database( $request );

		try {
			$country = Country::create( $preparedData );
		} catch ( Exception $exception ) {
			$error = new WP_Error();
			$error->add_data( array( 'status' => 400 ), $exception->getCode() );

			return $error;
		}

		if ( empty( $country ) ) {
			return rest_ensure_response( array() );
		}

		$id      = $country->id;
		$country = $this->prepare_item_for_response( $country, $request );

		$response = rest_ensure_response( $country );

		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $id ) ) );

		return $response;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return object
	 */
	protected function prepare_item_for_database( $request ) {
		$prepared_country = new stdClass();

		if ( isset( $request['id'] ) ) {
			$existing_country = $this->get_country( $request['id'] );
			if ( is_wp_error( $existing_country ) ) {
				return $existing_country;
			}

			$prepared_country->ID = $existing_country->id;
		}

		$prepared_country->subdomain       = (string) $request['subdomain'];
		$prepared_country->published       = (int) $request['published'];
		$prepared_country->ordering        = (int) $request['ordering'];
		$prepared_country->code            = (string) $request['code'];
		$prepared_country->domain          = (string) $request['domain'];
		$prepared_country->lat             = (float) $request['lat'];
		$prepared_country->lng             = (float) $request['lng'];
		$prepared_country->default_city_id = empty( $request['default_city_id'] ) ? null : (int) $request['default_city_id'];


		return $prepared_country;
	}

	/**
	 * @param $id
	 *
	 * @return ModelInterface|Country|WP_Error
	 */
	protected function get_country( $id ) {
		$error = new WP_Error(
			'rest_country_invalid_id',
			__( 'Invalid country ID.' ),
			array( 'status' => 404 )
		);

		if ( (int) $id <= 0 ) {
			return $error;
		}

		$country = Country::findById( (int) $id );
		if ( empty( $country ) || empty( $country->id ) ) {
			return $error;
		}

		return $country;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		$valid_check = $this->get_country( $request['id'] );
		if ( is_wp_error( $valid_check ) ) {
			return $valid_check;
		}

		$model     = $valid_check;
		$modelData = $this->prepare_item_for_database( $request );

		try {
			$model->update( (array) $modelData );
		} catch ( Exception $exception ) {
			return new WP_Error(
				'',
				'Error',
				array( 'status' => 404 )
			);
		}

		$response = $this->prepare_item_for_response( $model, $request );

		return rest_ensure_response( $response );
	}

	public function update_item_permissions_check( $request ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot update the countries resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}

		return true;
	}

	/**
	 * Deletes a single post.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 * @since 4.7.0
	 *
	 */
	public function delete_item( $request ) {
		$model = $this->get_country( $request['id'] );
		if ( is_wp_error( $model ) ) {
			return $model;
		}

		$force = (bool) $request['force'];

		if ( ! $this->check_delete_permission() ) {
			return new WP_Error(
				'rest_user_cannot_delete_country',
				__( 'Sorry, you are not allowed to delete this country.' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		$previous = $this->prepare_item_for_response( $model, $request );
		$response = new WP_REST_Response();
		$response->set_data(
			array(
				'deleted'  => true,
				'previous' => $previous->get_data(),
			)
		);

		try {
			$model->delete( $force );
		} catch ( Exception $exception ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'The country cannot be deleted.' ),
				array( 'status' => 500 )
			);
		}

		return $response;
	}

	public function delete_item_permissions_check( $request ) {
		$model = $this->get_country( $request['id'] );
		if ( is_wp_error( $model ) ) {
			return $model;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot delete the countries resource.' ), array( 'status' => $this->authorization_status_code() ) );
		}

		return true;
	}

	/**
	 * Retrieves the country's schema, conforming to JSON Schema.
	 *
	 * @return array Item schema data.
	 * @since 4.7.0
	 *
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			//'title'      => $this->post_type,
			'type'       => 'object',
			// Base properties for every Post.
			'properties' => [
				'id'              => [
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
					'readonly'    => true,
				],
				'subdomain'       => [
					'description' => __( 'Subdomain of country.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'edit' ]
				],
				'published'       => [
					'description' => __( 'Publish status of country.' ),
					'type'        => [ 'integer', 'null', 'boolean' ],
					'context'     => [ 'view', 'edit' ],
				],
				'ordering'        => [
					'description' => __( 'Order of countries.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
				],
				'code'            => [
					'description' => __( 'Country code.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'edit' ]
				],
				'domain'          => [
					'description' => __( 'Domain of country.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'edit' ]
				],
				'lat'             => [
					'description' => __( 'Latitude of country.' ),
					'type'        => 'number',
					'context'     => [ 'view', 'edit' ]
				],
				'lng'             => [
					'description' => __( 'Longitude of country.' ),
					'type'        => 'number',
					'context'     => [ 'view', 'edit' ]
				],
				'default_city_id' => [
					'description' => __( 'Default city id.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ]
				]
			],
		];

		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}


}

// Function to register our new routes from the controller.
function mcs_register_countries_routes() {
	$controller = new CountriesController();
	$controller->register_routes();
}

add_action( 'rest_api_init', __NAMESPACE__ . '\mcs_register_countries_routes' );
