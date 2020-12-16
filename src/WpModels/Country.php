<?php


namespace Mcs\WpModels;

class Country extends BaseModel {

	protected $properties = [
		'id',
		'subdomain',
		'published',
		'ordering',
		'code',
		'domain',
		'lat',
		'lng',
		'default_city_id'
	];

	public $id;
	public $subdomain;
	public $published;
	public $ordering;
	public $code;
	public $domain;
	public $lat;
	public $lng;
	public $default_city_id;

	public function getTableName(): string {
		return MCS_PREFIX . 'countries';
	}

	public function getProperties(): array {
		return $this->properties;
	}
}
