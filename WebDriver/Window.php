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
