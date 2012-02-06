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
class WebDriver_Touch {

	private $session = null;


	/**
	 * Sets default values for the Touch object.
	 *
	 * @param WebDriver_Session $session The current session with the server.
	 */
	public function __construct($session) {
		$this->session = $session;
	}


	/**
	 * Used to construct the requested Touch action for the session.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 */
	public function request($type, $pathEnd, $payload = array()) {
		return $this->session->request($type, 'touch/' . $pathEnd, $payload);
	}


	/**
	 * Performs a tap action on a touch enabled device.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/touch/click
	 * @param integer $elementId The element identifier to perform the action on.
	 * @param mixed $element Switches our endpoint for other types of click events.
	 */
	public function click($elementId, $endpoint = null) {
		$payload = array(
			'element' => $elementId
		);

		$endpoint = in_array($endpoint, array('doubleclick', 'longclick')) ? $endpoint : 'click';
		$this->request(WebDriver_Base::HTTP_POST, $endpoint, $payload);
	}


	/**
	 * Performs a double tap action on a touch enabled device.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#session/:sessionId/touch/doubleclick
	 * @param integer $elementId The element identifier to perform the action on.
	 */
	public function doubleclick($elementId) {
		$this->click($elementId, 'doubleclick');
	}


	/**
	 * Fingers down on the touch enabled device.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/touch/down
	 * @param integer $x The x coordinate on the screen.
	 * @param integer $y The y coordinate on the screen.
	 */
	public function down($x, $y) {
		$this->move($x, $y, 'down');
	}


	/**
	 * Performs a flicking action on the device from either a known or random screen location.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#session/:sessionId/touch/flick
	 * @param integer $x Dependent on element identifier and speed, this is either a horizontal offset or speed.
	 * @param integer $y Dependent on element identifier and speed, this is either a vertical offset or speed.
	 * @param integer $elementId If defined, this is the element identifier to perform the action from.
	 * @param float $speed If defined, this is the speed of the action in pixels/second.
	 */
	public function flick($x, $y, $elementId = null, $speed = null) {
		$payload = array();

		if (is_null($elementId) && is_null($speed)) {
			$payload['xSpeed'] = $x;
			$payload['ySpeed'] = $y;
		} else {
			$payload['element'] = trim($elementId);
			$payload['xOffset'] = $x;
			$payload['yOffset'] = $y;
			$payload['speed'] = $speed;
		}
		$this->request(WebDriver_Base::HTTP_POST, 'flick', $payload);
	}


	/**
	 * Performs a long tap action on a touch enabled device.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#session/:sessionId/touch/longclick
	 * @param integer $elementId The element identifier to perform the action on.
	 */
	public function longclick($elementId) {
		$this->click($elementId, 'longclick');
	}


	/**
	 * Change finger position on a touch enabled device.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#session/:sessionId/touch/move
	 * @param integer $x The x coordinate on the screen.
	 * @param integer $y The y coordinate on the screen.
	 * @param mixed $endpoint Switches our endpoint for other types of movement events.
	 */
	public function move($x, $y, $endpoint = null) {
		$payload = array(
			'x' => $x,
			'y' => $y
		);

		$endpoint = in_array($endpoint, array('down', 'up')) ? $endpoint : 'move';
		$this->request(WebDriver_Base::HTTP_POST, $endpoint, $payload);
	}


	/**
	 * Performs a scrolling action on the device from either a known or random screen location.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#session/:sessionId/touch/scroll
	 * @param integer $xOffset The x offset to scroll the screen by.
	 * @param integer $yOffset The y offset to scroll the screen by.
	 * @param integer $elementId If defined, this is the element identifier to perform the action on.
	 */
	public function scroll($xOffset, $yOffset, $elementId = null) {
		$payload = array(
			'xOffset' => $xOffset,
			'yOffset' => $yOffset
		);

		if (!is_null($elementId)) {
			$payload['element'] = trim($elementId);
		}
		$this->request(WebDriver_Base::HTTP_POST, 'scroll', $payload);
	}


	/**
	 * Fingers up on the touch enabled device.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/touch/up
	 * @param integer $x The x coordinate on the screen.
	 * @param integer $y The y coordinate on the screen.
	 */
	public function up($x, $y) {
		$this->move($x, $y, 'up');
	}
}
