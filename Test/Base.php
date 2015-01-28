<?php

/**
 * Selenium-Saucelabs (S2) PHP WebDriver
 *
 * Copyright 2015 Bronto Software, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @codeCoverageIgnore
 */
require('WebDriver/Autoload.php');

class Test_Base extends PHPUnit_Framework_TestCase {

	protected
		$session = false,
		$sessionSetup = array(
			// default browser to use when all else fails
			'browser'		=> 'firefox',
			'version'		=> '17.0.1',

			// default os/platform (ANY, XP, VISTA, or LINUX)
			'platform'		=> 'ANY',

			// the selenium server tests run against
			'host'			=> 'localhost',
			'port'			=> '4444',
			'timeout'		=> '90000',

			// saucelabs related configuration
			'sauce'			=> array(
				'user'		=> '',
				'key'		=> '',
			),
		),

		// the http location of testWebDriver.html
		$testWebDriver = 'http://localhost/testWebDriver.html';


	/**
	 * Initializes a new Selenium server session.
	 */
	public function setUp() {
		$setup = $this->sessionSetup;

		// are we connected anywhere yet?
		if (!$this->session) {
			if ($setup['sauce']['user'] && $setup['sauce']['key']) {
				$capabilities = array(
					'name'				=> $this->getJobName(),
					'platform'			=> $setup['platform'],
					'selenium-version'	=> '2.25.0',
				);

				$setup['host'] = $setup['sauce']['user'] . ':' . $setup['sauce']['key'] . '@ondemand.saucelabs.com';
				$setup['port'] = '80';
			}

			// configure our session
			$this->session = new WebDriver_Session($setup['host'], $setup['port']);
			$this->session->setBrowser($setup['browser'], $setup['version']);

			if (isset($capabilities)) {
				foreach ($capabilities as $capability => $value) {
					$this->session->setCapability($capability, $value);
				}
			}

			$this->session->start();
			$this->session->timeouts()->implicit_wait($setup['timeout']);
		}

		$this->session->url($this->testWebDriver);
	}


	/**
	 * Drops our connection with the Selenium server.
	 */
	public function tearDown() {
		if ($this->session !== false) {
			// report result to Sauce if necessary
			if ($this->session->hasSessionSauce()) {
				$testPassed = !$this->hasFailed();
				$this->session->setContext('passed', $testPassed);
			}

			// kill our browser session
			$this->session->stop();
		}
	}


	/**
	 * Prepares a unique job name for a Sauce server session.
	 *
	 * @return string The name of our job (classTest::testMethod).
	 */
	protected function getJobName() {
		return get_called_class() . '::' . self::getName();
	}
}
