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
class WebDriver_IME {

	private $session = null;


	/**
	 * Sets default values for the IME object.
	 *
	 * @param WebDriver_Session $session The current session with the server.
	 */
	public function __construct($session) {
		$this->session = $session;
	}


	/**
	 * Used to construct the requested IME action for the session.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 */
	public function request($type, $pathEnd, $payload = array()) {
		return $this->session->request($type, 'ime/' . $pathEnd, $payload);
	}


	/**
	 * Sets a given engine to active status so that all keyed input will be processed by it.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/ime/activate
	 * @param string $engine The name of the engine to enable.
	 */
	public function activate($engine) {
		$payload = array(
			'engine' => $engine
		);
		$this->request(WebDriver_Base::HTTP_POST, 'activate', $payload);
	}


	/**
	 * Determines if the engine input is currently active.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/ime/activated
	 * @return boolean If engine input is active.
	 */
	public function activated() {
		return (bool) $this->request(WebDriver_Base::HTTP_GET, 'activated');
	}


	/**
	 * Gets the name of the currently active engine.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/ime/active_engine
	 * @return string The name of the current active engine.
	 */
	public function active_engine() {
		return $this->request(WebDriver_Base::HTTP_GET, 'active_engine');
	}


	/**
	 * Gets a list of all available engines on the machine for use.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/ime/available_engines
	 * @return mixed A potential list of engines available.
	 */
	public function available_engines() {
		return $this->request(WebDriver_Base::HTTP_GET, 'available_engines');
	}


	/**
	 * Deactivates the currently active engine.  Keyed input will no longer be processesed by it.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/ime/deactivate
	 */
	public function deactivate() {
		$this->request(WebDriver_Base::HTTP_POST, 'deactivate');
	}
}
