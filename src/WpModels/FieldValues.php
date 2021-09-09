<?php

namespace Mcs\WpModels;

use Exception;
use Mcs\Interfaces\CitiesInterface;
use Mcs\Interfaces\CountriesInterface;
use Mcs\Interfaces\FieldsInterface;
use Mcs\Interfaces\FieldValuesInterface;
use Mcs\Interfaces\ModelInterface;
use Mcs\Interfaces\ProvincesInterface;

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

//	/**
//	 * @param ModelInterface $model
//	 *
//	 * @return int FieldValue Id
//	 * @throws Exception
//	 */
//	public function findIdForModel( ModelInterface $model ): int {
//		switch ( get_class( $model ) ) {
//			case Cities::class:
//				$related = CityFieldValues::findFirstByPropertyValue( 'city_id', $model->getId() );
//				break;
//			case Provinces::class:
//				$related = ProvinceFieldValues::findFirstByPropertyValue( 'province_id', $model->getId() );
//				break;
//			case Countries::class:
//				$related = CountryFieldValues::findFirstByPropertyValue( 'country_id', $model->getId() );
//				break;
//			default:
//				$related = CityFieldValues::findFirstByPropertyValue( 'city_id', $model->getId() );
//		}
//
//		return $related->getFieldValueId();
//	}

	/**
	 * @param FieldsInterface $field
	 * @param CitiesInterface|ProvincesInterface|CountriesInterface|ModelInterface $location
	 *
	 * @return FieldValuesInterface
	 */
	public static function findForLocation( FieldsInterface $field, ModelInterface $location ): FieldValuesInterface {

	}
}
