<?php

namespace Mcs;

class McsData {
	public function __construct() {
	}

	public function replaceTags($body) {
		return str_ireplace('umoma', 'mcs', $body);
	}
}
