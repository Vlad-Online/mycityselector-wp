<?php

namespace Mcs;

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
}
