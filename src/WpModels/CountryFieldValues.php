<?php
namespace Mcs\WpModels;

use Mcs\Interfaces\CountryFieldValuesInterface;

class CountryFieldValues extends BaseModel implements CountryFieldValuesInterface {

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var int
	 */
	public $field_value_id;

	/**
	 * @var int
	 */
	public $country_id;

	public static function getTableName(): string {
		return MCS_PREFIX . 'country_field_values';
	}

	public function getProperties(): array {
		return [
			'id',
			'field_value_id',
			'country_id',
		];
	}
}
