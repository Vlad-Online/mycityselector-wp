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
}
