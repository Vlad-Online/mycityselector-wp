<?php

namespace Mcs\Interfaces;

use Mcs\WpModels\FieldValues;

interface FieldValuesInterface {

	public static function findDefaultValue( int $fieldId ): FieldValues;

	public static function findForLocation(FieldsInterface $field, ModelInterface $location): FieldValuesInterface;

	public function getValue(): string;

	public function delete(  ): bool;
}
