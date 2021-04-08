<?php

namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\FieldsInterface;
use Mcs\Interfaces\FieldValuesInterface;

class Fields extends BaseModel implements FieldsInterface {

	protected $properties = [
		'id',
		'name',
		'published'
	];

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var boolean;
	 */
	public $published;

	public static function getTableName(): string {
		return MCS_PREFIX . 'fields';
	}

	public function getProperties(): array {
		return [
			'id',
			'name',
			'published'
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @inheritDoc
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @inheritDoc
	 */
	public function isPublished() {
		return $this->published;
	}

	/**
	 * @return FieldValues[]
	 */
	public function getFieldValues() {
		return FieldValues::findByPropertyValue( 'field_id', $this->id );
	}
}
