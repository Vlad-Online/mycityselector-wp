<?php

namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\FieldValuesInterface;

class FieldValues extends BaseModel implements FieldValuesInterface {

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

	public function getValue(): string {
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

	/**
	 * @param int $fieldId
	 *
	 * @return $this
	 * @throws Exception
	 */
	public static function findDefaultValue( int $fieldId ): FieldValues {
		global $wpdb;
		$model     = new static();
		$table     = $model->getTableName();
		$modelData = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table} WHERE field_id = %d AND `default` LIMIT 1",
				$fieldId
			), 'ARRAY_A'
		);
		if ( ! $modelData ) {
			throw new Exception( 'Not found' );
		}
		$model->fillProperties( $modelData );

		return $model;
	}
}
