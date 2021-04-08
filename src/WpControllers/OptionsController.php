<?php

namespace Mcs\WpControllers;

use Mcs\WpModels\Cities;
use Mcs\WpModels\Options;
use WP_Error;
use WP_REST_Response;

class OptionsController extends BaseController {

	protected $namespace = 'mcs/v1';
	protected $rest_base = 'Options';

	/**
	 *
	 * @return array Item schema data.
	 *
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->schema;
		}

		$this->schema = [
			// This tells the spec of JSON Schema we are using which is draft 4.
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			// The title property marks the identity of the resource.
			'title'      => $this->getModelName(),
			'type'       => 'object',
			// In JSON Schema you can specify object properties in the properties attribute.
			'properties' => [
				'id'                      => [
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
					'readonly'    => true,
				],
				'base_domain'             => [
					'description' => __( 'Base domain of your site, f.e.: example.com' ),
					'type'        => 'string',
					'context'     => [ 'view', 'edit' ]
				],
				'default_city_id'         => [
					'description' => __( 'Default city id' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ]
				],
				'seo_mode'                => [
					'description' => __( 'SEO mode' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
				],
				'country_choose_enabled'  => [
					'description' => __( 'Country choose enabled' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ]
				],
				'province_choose_enabled' => [
					'description' => __( 'Province choose enabled' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ],
				],
				'ask_mode'                => [
					'description' => __( 'Ask mode' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ]
				],
				'redirect_next_visits'    => [
					'description' => __( 'Redirect on next visits' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ]
				],
				'log_enabled'             => [
					'description' => __( 'Logging enabled' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ]
				],
				'debug_enabled'           => [
					'description' => __( 'Debug enabled' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ]
				]
			]
		];

		return $this->schema;
	}

	protected function getModelName() {
		return $this->rest_base;
	}

	/**
	 * @inheritDoc
	 */
	public function get_item( $request ) {
		return rest_ensure_response( ( new Options() )->toArray() );
	}

	/**
	 * @inheritDoc
	 */
	public function update_item( $request ) {
		$options = new Options();
		$domain  = filter_var( $request['base_domain'], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME );
		if ( ! $domain ) {
			return new WP_REST_Response( [
				'message' => 'Wrong domain name'
			], 400 );
			//return new WP_Error( 400, 'Wrong domain name' );
		}
		$options->setBaseDomain( $domain );
		$city = Cities::findById( $request['default_city_id'] );
		if ( $city instanceof WP_Error || ! $city->published ) {
			return new WP_Error( 400, 'Wrong default city' );
		}
		$options->setDefaultCityId( $city->id );
		$options->setSeoMode( (int) $request['seo_mode'] );
		$options->setCountryChooseEnabled( (bool) $request['country_choose_enabled'] );
		$options->setProvinceChooseEnabled( (bool) $request['province_choose_enabled'] );
		$options->setAskMode( (int) $request['ask_mode'] );
		$options->setRedirectNextVisits( (bool) $request['redirect_next_visits'] );
		$options->setLogEnabled( (bool) $request['log_enabled'] );
		$options->setDebugEnabled( (bool) $request['debug_enabled'] );

		return rest_ensure_response( $options->toArray() );
	}

	/**
	 * @inheritDoc
	 */
	public function get_items( $request ) {
		$data     = [];
		$response = rest_ensure_response( ( new Options() )->toArray() );
		$data[]   = $this->prepare_response_for_collection( $response );


		// Return all of our comment response data.
		$response = rest_ensure_response( $data );
		$response->header( 'X-WP-Total', (int) 1 );
		$response->header( 'X-WP-TotalPages', (int) 1 );
		$response->header( 'Content-Range', "{$this->rest_base} {0}-{0}/{1}" );

		return $response;
	}
}

// Function to register our new routes from the controller.
function mcs_register_options_routes() {
	$controller = new OptionsController();
	$controller->register_routes();
}

add_action( 'rest_api_init', __NAMESPACE__ . '\mcs_register_options_routes' );
