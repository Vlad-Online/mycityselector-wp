<?php


namespace Mcs\Interfaces;

interface ModelInterface {

	/**
	 * @param int $id
	 *
	 * @return ModelInterface|mixed
	 */
	public static function findById( int $id );

	/**
	 * @param $property
	 * @param $value
	 *
	 * @return ModelInterface
	 */
	public static function findFirstByPropertyValue( $property,  $value): ModelInterface;

	/**
	 * @param $property
	 * @param $value
	 *
	 * @return ModelInterface[]
	 */
	public static function findByPropertyValue( $property,  $value): array;

	/**
	 * @return ModelInterface[]
	 */
	public static function all(): array;

	public static function total() : int;

	public static function create( $data = [] ): ModelInterface;

	public function update( $data = [] ): ModelInterface;

	public function delete( ): bool;

	public static function getTableName(): string;

	public function getProperties(): array;
}
