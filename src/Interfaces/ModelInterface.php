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
	 * @param int $limit
	 *
	 * @return ModelInterface[]
	 */
	public static function all( int $limit ): array;

	public static function create( $data = [] ): ModelInterface;

	public function update( int $id, $data = [] ): ModelInterface;

	public function delete( $force = false ): bool;

	public function getTableName(): string;

	public function getProperties() : array;
}
