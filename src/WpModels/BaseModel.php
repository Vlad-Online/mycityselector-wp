<?php


namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\ModelInterface;

abstract class BaseModel implements ModelInterface {

	protected $properties = [
	];

	public $id;

	/**
	 * @param int $id
	 *
	 * @return static
	 */
	public static function findById( int $id ): ModelInterface {
		global $wpdb;
		$model     = new static();
		$table     = $model->getTableName();
		$modelData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE id = %s LIMIT 1",
				$id
			)
		);
		$model->fillProperties( $modelData );

		return $model;
	}

	/**
	 * @return self[]
	 */
	public static function all(): array {
		global $wpdb;

		$table      = ( new static() )->getTableName();
		$modelsData = $wpdb->get_results( "SELECT * FROM {$table}" );
		$models     = [];
		foreach ( $modelsData as $modelData ) {
			$model = new static();
			$model->fillProperties( $modelData );
			$models[] = $model;
		}

		return $models;
	}

	/**
	 * @param array $data
	 *
	 * @return ModelInterface
	 * @throws Exception
	 */
	public static function create( $data = [] ): ModelInterface {
		global $wpdb;
		$model = new static();

		if ( ! $wpdb->insert( $model->getTableName(), $data ) ) {
			throw new Exception( 'Error creating model' );
		}
		$model->fillProperties( $data );
		$model->id = (int) $wpdb->insert_id;

		return $model;
	}

	public function update( int $id, $data = [] ): ModelInterface {
		// TODO: Implement update() method.
	}

	/**
	 * @param false $force
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function delete( $force = false ): bool {
		global $wpdb;
		if ( ! $wpdb->delete( $this->getTableName(), [ 'id' => $this->id ] ) ) {
			throw new Exception( 'Error delete model id: ' . $this->id );
		}

		return true;
	}

	public function fillProperties( $data ) {
		foreach ( $this->properties as $propertyName ) {
			if ( isset( $data->$propertyName ) ) {
				$this->$propertyName = $data->$propertyName;
			}
		}
	}

	abstract public function getTableName(): string;

	abstract public function getProperties(): array;
}
