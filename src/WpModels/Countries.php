<?php


namespace Mcs\WpModels;

class Countries extends BaseModel {

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
}
