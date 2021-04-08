<?php
namespace Mcs\WpModels;

use Mcs\Interfaces\ProvinceFieldValuesInterface;

class ProvinceFieldValues extends BaseModel implements ProvinceFieldValuesInterface {

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
	public $province_id;

	public static function getTableName(): string {
		return MCS_PREFIX . 'province_field_values';
	}

	public function getProperties(): array {
		return [
			'id',
			'field_value_id',
			'province_id',

		];
	}
}
