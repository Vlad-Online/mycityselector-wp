<?php


namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\ProvincesInterface;

class Provinces extends BaseModel implements ProvincesInterface {

	protected $properties = [
		'id',
		'title',
		'country_id',
		'subdomain',
		//'lat',
		//'lng',
		'published',
		'ordering'
	];

	public $id;
	public $title;
	public $country_id;
	public $subdomain;
	//public $lat;
	//public $lng;
	public $published;
	public $ordering;

	public static function getTableName(): string {
		return MCS_PREFIX . 'provinces';
	}

	public function getProperties(): array {
		return $this->properties;
	}

	/**
	 * @param int $countryId
	 * @param string $name
	 *
	 * @return static
	 * @throws Exception
	 */
	public static function findByName($countryId, $name) {
		global $wpdb;
		$model     = new static();
		$table     = $model->getTableName();
		$modelData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table}
					   WHERE title = %s AND country_id = %d LIMIT 1",
				$name, $countryId
			), 'ARRAY_A'
		);
		if ( ! $modelData ) {
			throw new Exception( 'Not found' );
		}
		$model->fillProperties( $modelData );

		return $model;
	}

}
