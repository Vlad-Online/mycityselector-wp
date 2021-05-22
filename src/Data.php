<?php

namespace Mcs;

use Mcs\Interfaces\CitiesInterface;
use Mcs\Interfaces\CountriesInterface;
use Mcs\Interfaces\ModelInterface;
use Mcs\Interfaces\OptionsInterface;
use Mcs\Interfaces\ProvincesInterface;
use Mcs\WpModels\Cities;
use Mcs\WpModels\Countries;
use Mcs\WpModels\Options;
use Mcs\WpModels\Provinces;

class Data {
	const LIST_MODE_CITIES = 0;
	const LIST_MODE_PROVINCES_CITIES = 1;
	const LIST_MODE_COUNTRIES_PROVINCES_CITIES = 2;
	const LIST_MODE_COUNTRIES_CITIES = 3;

	const LOCATION_TYPE_CITY = 0;
	const LOCATION_TYPE_PROVINCE = 1;
	const LOCATION_TYPE_COUNTRY = 2;

	/**
	 * @var OptionsInterface
	 */
	protected $options;

	/**
	 * Current detected location (Country, Province or City)
	 * @var null|ModelInterface
	 */
	protected $currentLocation = null;

	public static function getInstance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new Data();
		}

		return $instance;
	}

	/**
	 * Data constructor.
	 *
	 * @param OptionsInterface $options
	 */
	public function __construct( $options = null ) {
		if ( $options ) {
			$this->options = $options;
		} else {
			$this->options = Options::getInstance();
		}
	}

	public function replaceTags( $body ) {
		return str_ireplace( 'umoma', 'mcs', $body );
	}

	public function getWidgetDataJson() {
		$countries = [];
		foreach ( Countries::findByPropertyValue( 'published', 1 ) as $country ) {
			$countries[ $country->id ] = $country;
		}
		$provinces = [];
		foreach ( Provinces::findByPropertyValue( 'published', 1 ) as $province ) {
			$provinces[ $province->id ] = $province;
		}
		$cities = [];
		foreach ( Cities::findByPropertyValue( 'published', 1 ) as $city ) {
			$cities[ $city->id ] = $city;
		}

		return json_encode( [
			'countries'       => $countries,
			'provinces'       => $provinces,
			'cities'          => $cities
		] );
	}

	/**
	 * @return CitiesInterface|ModelInterface
	 */
	public function getDefaultCity() {
		static $city;
		if ( ! $city ) {
			$city = Cities::findById( $this->options->getDefaultCityId() );
		}

		return $city;
	}

	public function getDefaultProvince() {
		static $province;
		if ( ! $province ) {
			$province = $this->getDefaultCity()->getProvince();
		}

		return $province;
	}

	public function getDefaultCountry() {
		static $country;
		if ( ! $country ) {
			$country = $this->getDefaultCity()->getCountry();
		}

		return $country;
	}

	/**
	 * @return ModelInterface
	 */
	public function getCurrentLocation() {
		if ( ! $this->currentLocation ) {
			$this->currentLocation = $this->detectCurrentLocation();
		}

		return $this->currentLocation;
	}

	/**
	 * @return ModelInterface
	 */
	protected function detectCurrentLocation() {
		switch ( $this->options->getSeoMode() ) {
			case Options::SEO_MODE_DISABLED:
				return $this->detectCurrentLocationFromCookie();
			case Options::SEO_MODE_SUBDOMAIN:
				return $this->detectCurrentLocationFromSubdomain();
			case Options::SEO_MODE_SUBFOLDER:
				return $this->detectCurrentLocationFromSubFolder();
			default:
				return $this->getDefaultCity();
		}
	}

	/**
	 * @return ModelInterface
	 */
	protected function detectCurrentLocationFromCookie() {
		$locationType = (int) $_COOKIE['mcs_location_type'];
		$locationId   = (int) $_COOKIE['mcs_location_id'];
		switch ( $locationType ) {
			case self::LOCATION_TYPE_CITY:
				$model = Cities::findById( $locationId );
				if ( $model instanceof CitiesInterface ) {
					return $model;
				}
				break;
			case self::LOCATION_TYPE_PROVINCE:
				$model = Provinces::findById( $locationId );
				if ( $model instanceof ProvincesInterface ) {
					return $model;
				}
				break;
			case self::LOCATION_TYPE_COUNTRY:
				$model = Countries::findById( $locationId );
				if ( $model instanceof CountriesInterface ) {
					return $model;
				}
				break;
		}

		return $this->getDefaultCity();
	}

	/**
	 * @return ModelInterface
	 */
	protected function detectCurrentLocationFromSubdomain() {

	}

	/**
	 * @return ModelInterface
	 */
	protected function detectCurrentLocationFromSubFolder() {

	}
}
