<?php


namespace Mcs\WpModels;

class City extends BaseModel {

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

	public function getTableName(): string {
		return MCS_PREFIX . 'cities';
	}

	public function getProperties(): array {
		return $this->properties;
	}
}
