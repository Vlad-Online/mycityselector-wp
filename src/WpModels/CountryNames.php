<?php


namespace Mcs\WpModels;

class CountryNames extends BaseModel {

	protected $properties = [
		'id',
		'country_id',
		'lang_code',
		'name'
	];

	public $id;
	public $country_id;
	public $lang_code;
	public $name;

	public static function getTableName(): string {
		return MCS_PREFIX . 'country_names';
	}

	public function getProperties(): array {
		return $this->properties;
	}
}
