<?php

namespace Mcs;

use Mcs\WpModels\Cities;
use Mcs\WpModels\Countries;
use Mcs\WpModels\Provinces;

class Data {

	public static function getInstance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new Data();
		}

		return $instance;
	}

	public function __construct() {
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
			'countries' => $countries,
			'provinces' => $provinces,
			'cities'    => $cities
		] );
	}
}
