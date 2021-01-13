<?php


namespace Mcs\Interfaces;

use Mcs\WpModels\Cities;
use Mcs\WpModels\Provinces;

interface CountriesInterface {

	/**
	 * @return int
	 */
	public function countProvinces();

	/**
	 * @return int
	 */
	public function countCities();

	/**
	 * @return Provinces[]
	 */
	public function getProvinces();

	/**
	 * @return Cities[]
	 */
	public function getCities();
}
