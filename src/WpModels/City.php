<?php


namespace Mcs\WpModels;


use Exception;
use Mcs\Interfaces\ModelInterface;

class City implements ModelInterface {

	protected $properties = [
		'id',
		'country_id',
		'province_id',
		'subdomain',
		'post_index',
		'lat',
		'lng',
		'published',
		'ordering',
	];

	public $id;
	public $country_id;
	public $province_id;
	public $subdomain;
	public $post_index;
	public $lat;
	public $lng;
	public $published;
	public $ordering;

	/**
	 * @param int $id
	 *
	 * @return City
	 */
	public static function findById( int $id ): ModelInterface {
		global $wpdb;
		$city     = new self();
		$table    = $city->getTableName();
		$cityData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE id = %s LIMIT 1",
				$id
			)
		);
		$city->fillProperties( $cityData );

		return $city;
	}

	public static function all( int $limit ): array {
		// TODO: Implement all() method.
	}

	/**
	 * @param array $data
	 *
	 * @return ModelInterface
	 * @throws Exception
	 */
	public static function create( $data = [] ): ModelInterface {
		global $wpdb;
		$model = new self();

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

	public function getTableName(): string {
		return MCS_PREFIX . 'cities';
	}

	public function getProperties(): array {
		return $this->properties;
	}
}
