<?php


namespace Mcs\WpModels;

use Mcs\Interfaces\CountriesInterface;

class Countries extends BaseModel implements CountriesInterface {

	protected $properties = [
		'id',
		'title',
		'subdomain',
		'published',
		'ordering',
		'code',
		'domain',
		'default_city_id'
	];

	public $id;
	public $title;
	public $subdomain;
	public $published;
	public $ordering;
	public $code;
	public $domain;
	public $default_city_id;

	public static function getTableName(): string {
		return MCS_PREFIX . 'countries';
	}

	public function getProperties(): array {
		return $this->properties;
	}

	public function delete( $force = false ): bool {
		$provincesCount = $this->countProvinces();
		$citiesCount    = $this->countCities();
		if ( $provincesCount > 0 || $citiesCount > 0 ) {
			if ( ! $force ) {
				return false;
			}
			foreach ( $this->getCities() as $city ) {
				$city->delete();
			}
			foreach ( $this->getProvinces() as $province ) {
				$province->delete();
			}
		}

		return parent::delete( $force );
	}

	public function countProvinces() {
		global $wpdb;
		$tableName = Provinces::getTableName();

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$tableName} WHERE country_id = %d",
			(int) $this->id ) );
	}

	public function countCities() {
		global $wpdb;
		$tableName = Cities::getTableName();

		return (int) $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM {$tableName} WHERE country_id = %d",
			(int) $this->id ) );

	}

	/**
	 * @return Provinces[]
	 */
	public function getProvinces() {
		global $wpdb;
		$tableName  = Provinces::getTableName();
		$modelsData = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$tableName} WHERE country_id = %d LIMIT 1",
				$this->id
			), 'ARRAY_A'
		);
		$result     = [];
		foreach ( $modelsData as $modelData ) {
			$model = new Provinces();
			$model->fillProperties( $modelData );
			$result[] = $model;
		}

		return $result;
	}

	/**
	 * @return Cities[]
	 */
	public function getCities() {
		global $wpdb;
		$tableName  = Cities::getTableName();
		$modelsData = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$tableName} WHERE country_id = %d LIMIT 1",
				$this->id
			), 'ARRAY_A'
		);
		$result     = [];
		foreach ( $modelsData as $modelData ) {
			$model = new Cities();
			$model->fillProperties( $modelData );
			$result[] = $model;
		}

		return $result;
	}
}
