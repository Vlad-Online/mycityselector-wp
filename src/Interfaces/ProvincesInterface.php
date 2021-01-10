<?php


namespace Mcs\Interfaces;


use Exception;

interface ProvincesInterface {
	/**
	 * @param int $countryId
	 * @param string $name
	 *
	 * @return static
	 * @throws Exception
	 */
	public static function findByName($countryId, $name);
}
