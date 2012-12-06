<?php

/**
 * Selenium-Saucelabs (S2) PHP WebDriver
 * Copyright (C) 2012 Bronto Software, Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
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
