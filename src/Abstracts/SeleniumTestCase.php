<?php

namespace Mcs\Abstracts;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use PHPUnit\Framework\TestCase;

abstract class SeleniumTestCase extends TestCase {
	protected $driver;
	protected function setUp($serverUrl = 'http://localhost:4444') {
		$driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
	}
}
