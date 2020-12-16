<?php


namespace Mcs\WpModels;

class Province extends BaseModel {

	protected $properties = [
		'id',
		'country_id',
		'subdomain',
		'lat',
		'lng',
		'published',
		'ordering'
	];

	public $id;
	public $country_id;
	public $subdomain;
	public $lat;
	public $lng;
	public $published;
	public $ordering;

	public function getTableName(): string {
		return MCS_PREFIX . 'provinces';
	}

	public function getProperties(): array {
		return $this->properties;
	}
}
