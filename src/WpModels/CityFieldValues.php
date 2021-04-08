<?php

namespace Mcs\WpModels;

use Mcs\Interfaces\CityFieldValuesInterface;

class CityFieldValues extends BaseModel implements CityFieldValuesInterface {

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
	public $city_id;

	public static function getTableName(): string {
		return MCS_PREFIX . 'city_field_values';
	}

	public function getProperties(): array {
		return [
			'id',
			'field_value_id',
			'city_id',
		];
	}

	public function getId() {
		return $this->id;
	}

	public function getFieldValueId() {
		return $this->field_value_id;
	}

	public function getCityId() {
		return $this->city_id;
	}
}
