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
