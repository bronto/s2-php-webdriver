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
class WebDriver_Timeouts {

	private $session = null;


	/**
	 * Sets default values for the Timeouts object.
	 *
	 * @param WebDriver_Session $session The current session with the server.
	 */
	public function __construct($session) {
		$this->session = $session;
	}


	/**
	 * Used to construct the requested Timeouts action for the session.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 */
	public function request($type, $pathEnd, $payload = array()) {
		return $this->session->request($type, 'timeouts/' . $pathEnd, $payload);
	}


	/**
	 * Sets the amount of time in milliseconds to wait for an asynchronous
	 * script before aborting it.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/timeouts/async_script
	 * @param integer $ms The time in milliseconds before aborting.
	 */
	public function async_script($ms) {
		$payload = array(
			'ms' => (int) $ms
		);
		$this->request(WebDriver_Base::HTTP_POST, 'async_script', $payload);
	}


	/**
	 * Sets the amount of time in milliseconds to wait for an element search
	 * to complete before aborting it.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/timeouts/implicit_wait
	 * @param integer $ms The time in milliseconds before aborting.
	 */
	public function implicit_wait($ms) {
		$payload = array(
			'ms' => (int) $ms
		);
		$this->request(WebDriver_Base::HTTP_POST, 'implicit_wait', $payload);
	}
}
