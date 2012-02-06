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
class WebDriver_Window {

	private $session = null;
	public $handle = null;


	/**
	 * Sets default values for the Window object.
	 *
	 * @param WebDriver_Session $session The current session with the server.
	 * @param string $handle The identifier of the current browser window.
	 */
	public function __construct($session, $handle) {
		$this->handle = $handle;
		$this->session = $session;
	}


	/**
	 * Used to construct the requested Window action for the session.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 */
	public function request($type, $pathEnd, $payload = array()) {
		return $this->session->request($type, 'window/' . $this->handle . '/' . $pathEnd, $payload);
	}


	/**
	 * Gets or sets the position of the window relative to the top-left corner.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/window/:windowHandle/position
	 * @param integer $x The x coordinate of the window.
	 * @param integer $y The y coordinate of the window.
	 * @return mixed The x and y coordinates of the window, if the coordinates are not defined.
	 */
	public function position($x = null, $y = null) {
		$http = WebDriver_Base::HTTP_GET;
		$payload = array();

		if (!is_null($x) && !is_null($y)) {
			$http = WebDriver_Base::HTTP_POST;
			$payload['x'] = $x;
			$payload['y'] = $y;
		}
		return $this->request($http, 'position', $payload);
	}


	/**
	 * Gets or sets the size of the window.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/window/:windowHandle/size
	 * @param integer $height The height of the window.
	 * @param integer $width The width of the window.
	 * @return mixed The height and width of the window, if the size parameters are not defined.
	 */
	public function size($height = null, $width = null) {
		$http = WebDriver_Base::HTTP_GET;
		$payload = array();

		if (!is_null($height) && !is_null($width)) {
			$http = WebDriver_Base::HTTP_POST;
			$payload['height'] = $height;
			$payload['width'] = $width;
		}
		return $this->request($http, 'size', $payload);
	}
}
