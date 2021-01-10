<?php


namespace Mcs\Interfaces;

use Exception;

interface CitiesInterface {
	/**
	 * @param int $countryId
	 * @param int $provinceId
	 * @param string $name
	 *
	 * @return static
	 * @throws Exception
	 */
	public static function findByName( $countryId, $provinceId, $name );
}
