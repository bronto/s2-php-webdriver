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
 */
class WebDriver_Exception extends Exception {

	/**
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#Response_Status_Codes
	 */
	private $exceptions = array(
				0 => 'Success',
				7 => 'NoSuchElement',
				8 => 'NoSuchFrame',
				9 => 'UnknownCommand',
				10 => 'StaleElementReference',
				11 => 'ElementNotVisible',
				12 => 'InvalidElementState',
				13 => 'UnknownError',
				15 => 'ElementIsNotSelectable',
				17 => 'JavaScriptError',
				19 => 'XPathLookupError',
				21 => 'Timeout',
				23 => 'NoSuchWindow',
				24 => 'InvalidCookieDomain',
				25 => 'UnableToSetCookie',
				26 => 'UnexpectedAlertOpen',
				27 => 'NoAlertOpenError',
				28 => 'ScriptTimeout',
				29 => 'InvalidElementCoordinates',
				30 => 'IMENotAvailable',
				31 => 'IMEEngineActivationFailed',
				32 => 'InvalidSelector'
			);


	/**
	 * Throws the relevant exception that was returned by the server.
	 *
	 * @param integer $code The identifier for the mapped exception.
	 * @param string $message The message returned by the server as to what occurred.
	 */
	public function __construct($code, $message) {
		die("\n" . $this->exceptions[$code] . " Exception:\n$message\n");
	}
}
