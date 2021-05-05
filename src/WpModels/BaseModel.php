<?php


namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\ModelInterface;
use WP_Error;

abstract class BaseModel implements ModelInterface {

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @param int $id
	 *
	 * @return static|WP_Error
	 */
	public static function findById( int $id ) {
		global $wpdb;
		$model = new static();
		$table = $model->getTableName();
		/** @noinspection SqlResolve */
		$modelData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE id = %s LIMIT 1",
				$id
			), 'ARRAY_A'
		);
		if ( ! $modelData ) {
			return new WP_Error( 404, 'Entry not found.' );
		}
		$model->fillProperties( $modelData );

		return $model;
	}

	/**
	 * @param $property
	 * @param $value
	 *
	 * @return static
	 * @throws Exception
	 */
	public static function findFirstByPropertyValue( $property, $value ): ModelInterface {
		global $wpdb;
		$model     = new static();
		$table     = $model->getTableName();
		$modelData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE {$property} = %s LIMIT 1",
				$value
			), 'ARRAY_A'
		);
		if ( ! $modelData ) {
			throw new Exception( 'Not found' );
		}
		$model->fillProperties( $modelData );

		return $model;
	}

	/**
	 * @param $property
	 * @param $value
	 *
	 * @return static[]
	 */
	public static function findByPropertyValue( $property, $value ): array {
		global $wpdb;
		$table      = static::getTableName();
		$modelsData = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE {$property} = %s",
				$value
			), 'ARRAY_A'
		);
		$result     = [];
		if ( ! empty( $modelsData ) ) {
			foreach ( $modelsData as $modelData ) {
				$model = new static();
				$model->fillProperties( $modelData );
				$result[] = $model;
			}
		}

		return $result;
	}

	/**
	 * @return static[]
	 */
	public static function all(): array {
		global $wpdb;

		$table      = ( new static() )->getTableName();
		$modelsData = $wpdb->get_results( "SELECT * FROM {$table}", 'ARRAY_A' );
		$models     = [];
		foreach ( $modelsData as $modelData ) {
			$model = new static();
			$model->fillProperties( $modelData );
			$models[] = $model;
		}

		return $models;
	}

	public static function total(): int {
		global $wpdb;

		$table  = ( new static() )->getTableName();
		$result = $wpdb->get_row( "SELECT count(*) as cnt FROM {$table}", 'ARRAY_A' );

		return $result['cnt'];
	}

	/**
	 * @param array $data
	 *
	 * @return static
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

	/**
	 * @param array $data
	 *
	 * @return ModelInterface
	 * @throws Exception
	 */
	public function update( $data = [] ): ModelInterface {
		global $wpdb;

		foreach ( $this->getProperties() as $property ) {
			if ( $property != 'id' ) {
				$this->$property = $data[ $property ] ?? $this->$property;
			}
		}

		if ( ! $wpdb->update( $this->getTableName(), $data, [
			'id' => $this->id
		] ) ) {
			throw new Exception( 'Error creating model' );
		}

		return $this;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function delete(): bool {
		global $wpdb;
		if ( ! $wpdb->delete( $this->getTableName(), [ 'id' => (int) $this->id ], [ '%d' ] ) ) {
			throw new Exception( 'Error delete model id: ' . $this->id );
		}

		return true;
	}

	public function fillProperties( array $data ) {
		foreach ( $this->getProperties() as $propertyName ) {
			if ( isset( $data[ $propertyName ] ) ) {
				switch ( $propertyName ) {
					case 'id':
					case 'ordering':
						$this->$propertyName = (int) $data[ $propertyName ];
						break;
					case 'published':
						$this->$propertyName = (bool) $data[ $propertyName ];
						break;
					default:
						if ( substr( $propertyName, - 3 ) == '_id' ) {
							$this->$propertyName = (int) $data[ $propertyName ];
						} else {
							$this->$propertyName = $data[ $propertyName ];
						}

				}
			}
		}
	}

	abstract public static function getTableName(): string;

	abstract public function getProperties(): array;
}
