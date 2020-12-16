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
	protected $resource_name = 'countries';

	// Here initialize our namespace and resource name.
	public function __construct() {
		/*$this->namespace     = '/mcs/v1';
		$this->resource_name = 'countries';*/
	}

	// Register our routes.
	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->resource_name, [
			// Here we register the readable endpoint for collections.
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_items' ],
				'permission_callback' => [ $this, 'get_items_permissions_check' ],
				'args'                => $this->get_collection_params(),
			],
			// Register our schema callback.
			'schema' => array( $this, 'get_public_item_schema' ),
		] );

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name . '/(?P<id>[\d]+)',
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
						'force'    => [
							'type'        => 'boolean',
							'default'     => false,
							'description' => __( 'Required to be true, as countries do not support trashing.' ),
						],
						'reassign' => [
							'type'              => 'integer',
							'description'       => __( 'Reassign the deleted country\'s posts and links to this country ID.' ),
							'required'          => true,
							'sanitize_callback' => [ $this, 'check_reassign' ],
						],
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
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the countries resource.' ), array( 'status' => $this->authorization_status_code() ) );
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

		if ( empty( $countries ) ) {
			return rest_ensure_response( $data );
		}

		foreach ( $countries as $country ) {
			$response = $this->prepare_item_for_response( $country, $request );
			$data[]   = $this->prepare_response_for_collection( $response );
		}

		// Return all of our comment response data.
		return rest_ensure_response( $data );
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
	 */
	public function get_item( $request ) {
		$id   = (int) $request['id'];
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
	 * @return array Response data, ready for insertion into collection data.
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

	/**
	 * Get our sample schema for a post.
	 *
	 * @param WP_REST_Request $request Current request.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			// Since WordPress 5.3, the schema can be cached in the $schema property.
			return $this->schema;
		}

		$this->schema = array(
			// This tells the spec of JSON Schema we are using which is draft 4.
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			// The title property marks the identity of the resource.
			'title'      => 'country',
			'type'       => 'object',
			// In JSON Schema you can specify object properties in the properties attribute.
			'properties' => array(
				'id'      => array(
					'description' => esc_html__( 'Unique identifier for the object.', 'my-textdomain' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'content' => array(
					'description' => esc_html__( 'The content for the object.', 'my-textdomain' ),
					'type'        => 'string',
				),
			),
		);

		return $this->schema;
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

		return $query_params;
	}

	public function prepare_item_for_response( $item, $request ) {
		$data = [];
		foreach ( $item->getProperties() as $property ) {
			$data[ $property ] = $item->$property;
		}

		return $data;
	}

	public function create_item( $request ) {
		if ( ! empty( $request['id'] ) ) {
			return new WP_Error(
				'rest_country_exists',
				__( 'Cannot create existing country.' ),
				array( 'status' => 400 )
			);
		}

		try {
			$country = Country::create($request);
		} catch ( Exception $exception) {
			$error = new WP_Error();
			$error->add_data( array( 'status' => 400 ), $exception->getCode() );
		}

		if ( empty( $country ) ) {
			return rest_ensure_response( array() );
		}

		$country = $this->prepare_item_for_response( $country, $request );

		// Return all of our post response data.
		return rest_ensure_response( $country );
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
		$prepared_country->counry_id = (int)$request['country_id'];
		$prepared_country->province_id = (int)$request['province_id'];
		$prepared_country->subdomain = (string)$request['subdomain'];
		$prepared_country->post_index = (int)$request['post_index'];
		$prepared_country->lat = (float)$request['lat'];
		$prepared_country->lng = (float)$request['lng'];
		$prepared_country->published = (int)$request['published'];
		$prepared_country->ordering = (int)$request['ordering'];
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


}

// Function to register our new routes from the controller.
function mcs_register_countries_routes() {
	$controller = new CountriesController();
	$controller->register_routes();
}

add_action( 'rest_api_init', __NAMESPACE__ . '\mcs_register_countries_routes' );
