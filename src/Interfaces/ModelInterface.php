<?php


namespace Mcs\Interfaces;


interface ModelInterface {

	/**
	 * @param int $id
	 *
	 * @return ModelInterface
	 */
	public static function findById( int $id ): ModelInterface;

	/**
	 * @return ModelInterface[]
	 */
	public static function all(): array;

	public static function total() : int;

	public static function create( $data = [] ): ModelInterface;

	public function update( $data = [] ): ModelInterface;

	public function delete( $force = false ): bool;

	public function getTableName(): string;

	public function getProperties(): array;
}
