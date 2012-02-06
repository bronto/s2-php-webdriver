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
