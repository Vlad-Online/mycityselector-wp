<?php


namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\CitiesInterface;

/**
 * Class Cities
 * @package Mcs\WpModels
 */
class Cities extends BaseModel implements CitiesInterface {

	protected $properties = [
		'id',
		'title',
		'country_id',
		'province_id',
		'subdomain',
		'published',
		'ordering',
	];

	public $id;
	public $title;
	public $country_id;
	public $province_id;
	public $subdomain;
	public $published;
	public $ordering;

	public static function getTableName(): string {
		return MCS_PREFIX . 'cities';
	}

	public function getProperties(): array {
		return $this->properties;
	}

	public static function findByName( $countryId, $provinceId, $name ) {
		global $wpdb;
		$model     = new static();
		$table     = $model->getTableName();
		$modelData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table}
					   WHERE title = %s AND country_id = %d AND province_id = %d LIMIT 1",
				$name, $countryId, $provinceId
			), 'ARRAY_A'
		);
		if ( ! $modelData ) {
			throw new Exception( 'Not found' );
		}
		$model->fillProperties( $modelData );

		return $model;
	}

	/**
	 * @inheritDoc
	 * @throws Exception
	 */
	public function getProvince() {
		return Provinces::findById( $this->province_id );
	}

	/**
	 * @inheritDoc
	 * @throws Exception
	 */
	public function getCountry() {
		return Countries::findById( $this->country_id );
	}
}
