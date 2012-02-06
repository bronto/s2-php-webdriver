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
