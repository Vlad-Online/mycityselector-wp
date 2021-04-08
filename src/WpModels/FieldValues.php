<?php

namespace Mcs\WpModels;

use Mcs\Interfaces\FieldValuesInterface;

class FieldValues extends BaseModel implements FieldValuesInterface {

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var int
	 */
	public $field_id;

	/**
	 * @var string
	 */
	public $value;

	/**
	 * @var boolean
	 */
	public $default;

	/**
	 * @var boolean
	 */
	public $is_ignore;

	public static function getTableName(): string {
		return MCS_PREFIX . 'field_values';
	}

	public function getProperties(): array {
		return [
			'id',
			'field_id',
			'value',
			'default',
			'is_ignore'
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
	public function getFieldId() {
		return $this->field_id;
	}

	/**
	 * @inheritDoc
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @inheritDoc
	 */
	public function isDefault() {
		return $this->default;
	}

	/**
	 * @inheritDoc
	 */
	public function isIgnore() {
		return $this->is_ignore;
	}
}
