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

	public function getId() {
		return $this->id;
	}

	public function getValue() {
		return $this->value;
	}

	public function isDefault() {
		return $this->default;
	}

	public function isIgnore() {
		return $this->is_ignore;
	}

	public function fillProperties( array $data ) {
		parent::fillProperties( $data );
		$this->default   = (bool) $data['default'];
		$this->is_ignore = (bool) $data['is_ignore'];
	}
}
