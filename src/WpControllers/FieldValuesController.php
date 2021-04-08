<?php

namespace Mcs\WpControllers;

use Exception;
use Mcs\WpModels\FieldValues;
use stdClass;
use WP_REST_Request;

/**
 * Class CountriesController
 * @package Mcs\WpControllers
 * @method FieldValues get_model( $id )
 */
class FieldValuesController extends BaseController {

	protected $namespace = 'mcs/v1';
	protected $rest_base = 'FieldValues';

	/**
	 *
	 * @return array Item schema data.
	 *
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->schema;
		}
		$modelName    = $this->getModelName();
		$this->schema = [
			// This tells the spec of JSON Schema we are using which is draft 4.
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			// The title property marks the identity of the resource.
			'title'      => $modelName,
			'type'       => 'object',
			// In JSON Schema you can specify object properties in the properties attribute.
			'properties' => [
				'id'        => [
					'description' => __( 'Unique identifier for the object.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
					'readonly'    => true,
				],
				'field_id'        => [
					'description' => __( 'ID of related field.' ),
					'type'        => 'integer',
					'context'     => [ 'view', 'edit' ],
					'readonly'    => true,
				],
				'value'      => [
					'description' => __( 'Value of field.' ),
					'type'        => 'string',
					'context'     => [ 'view', 'edit' ]
				],
				'default'      => [
					'description' => __( 'Is default field.' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ]
				],
				'is_ignore'      => [
					'description' => __( 'Is ignoring field.' ),
					'type'        => 'boolean',
					'context'     => [ 'view', 'edit' ]
				]
			],
		];

		return $this->schema;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return object
	 * @throws Exception
	 */
	protected function prepare_item_for_database( $request ) {
		$prepared_model = new stdClass();

		$existing_model = null;
		if ( isset( $request['id'] ) ) {
			$existing_model = $this->get_model( $request['id'] );
			if ( is_wp_error( $existing_model ) ) {
				return $existing_model;
			}

			$prepared_model->ID = $existing_model->id;
		}

		$prepared_model->field_id      = (int) ( $request['field_id'] ?? ( $existing_model->field_id ?? null ) );
		$prepared_model->value      = (string) ( $request['value'] ?? ( $existing_model->value ?? '' ) );
		$prepared_model->default      = (bool) ( $request['default'] ?? ( $existing_model->default ?? false ) );
		$prepared_model->is_ignore      = (bool) ( $request['is_ignore'] ?? ( $existing_model->is_ignore ?? false ) );

		return $prepared_model;
	}

	protected function getModelName() {
		return $this->rest_base;
	}
}

// Function to register our new routes from the controller.
function mcs_register_field_values_routes() {
	$controller = new FieldValuesController();
	$controller->register_routes();
}

add_action( 'rest_api_init', __NAMESPACE__ . '\mcs_register_field_values_routes' );
