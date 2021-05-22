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

	/**
	 * @return ?ProvincesInterface
	 */
	public function getProvince();

	/**
	 * @return ?CountriesInterface
	 */
	public function getCountry();
}
