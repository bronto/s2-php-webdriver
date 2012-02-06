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
class WebDriver_Element {

	private $session = null;
	public
		$id = null,
		$locator = null;


	/**
	 * Sets default values for the Element object.
	 *
	 * @param WebDriver_Session $session The current session with the server.
	 * @param integer $id The identifier assigned by the server for this element.
	 * @param string $locator The target of the element search strategy.
	 */
	public function __construct($session, $id, $locator) {
		$this->id = $id;
		$this->locator = $locator;
		$this->session = $session;
	}


	/**
	 * Used to construct the requested Element action for the session.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 */
	public function request($type, $pathEnd, $payload = array()) {
		return $this->session->request($type, 'element/' . $this->id . '/' . $pathEnd, $payload);
	}


	/**
	 * Gets the specified element attribute value.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/attribute/:name
	 * @param string $name The specific attribute to retrieve a value for.
	 * @return mixed The value or null if the attribute doesn't exist.
	 */
	public function attribute($name) {
		return $this->request(WebDriver_Base::HTTP_GET, 'attribute/' . $name);
	}


	/**
	 * Clears the value of an input or textarea.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/clear
	 */
	public function clear() {
		$this->request(WebDriver_Base::HTTP_POST, 'clear');
	}


	/**
	 * Click on the current element.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/click
	 */
	public function click() {
		$this->request(WebDriver_Base::HTTP_POST, 'click');
	}


	/**
	 * Gets the value of the specified element css property.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/css/:propertyName
	 * @param string $propertyName The css property to retrieve a value for.
	 * @return string The value of the css property.
	 */
	public function css($propertyName) {
		return $this->request(WebDriver_Base::HTTP_GET, 'css/' . $propertyName);
	}


	/**
	 * Determines if the element is displayed on the page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/displayed
	 * @return boolean If the element is displayed.
	 */
	public function displayed() {
		return (bool) $this->request(WebDriver_Base::HTTP_GET, 'displayed');
	}


	/**
	 * Searches for an element on the current page starting from the current element
	 * and returns it if found.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/element
	 * @param string $using The locator strategy to apply in searching for the element.
	 * @param string $value The target of the search strategy.
	 * @return WebDriver_Element
	 */
	public function element($using, $value) {
		return $this->elements($using, $value, true);
	}


	/**
	 * Searches for elements on the current page starting from the current element
	 * and returns any found.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/elements
	 * @param string $using The locator strategy to apply in searching for elements.
	 * @param string $value The target of the search strategy.
	 * @param boolean $element Switches our endpoint for single element searching.
	 * @return mixed
	 */
	public function elements($using, $value, $element = false) {
		$payload = array();

		if (in_array($using, $this->session->strategies)) {
			$payload['using'] = $using;
			$payload['value'] = $value;
		}

		$endpoint = $element ? 'element' : 'elements';
		$response = $this->request(WebDriver_Base::HTTP_POST, $endpoint, $payload);

		if (!is_array($response) && isset($response)) {
			return new WebDriver_Element($this->session, $response->ELEMENT, $value);
		}

		$elements = array();
		foreach ($response as $element) {
			array_push($elements, new WebDriver_Element($this->session, $element->ELEMENT, $value));
		}
		return $elements;
	}


	/**
	 * Determines if the element is enabled on the page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/enabled
	 * @return boolean If the element is enabled on the page.
	 */
	public function enabled() {
		return (bool) $this->request(WebDriver_Base::HTTP_GET, 'enabled');
	}


	/**
	 * Determines if two element identifiers are refering to the same element in the DOM.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/equals/:other
	 * @param integer $other The identifier of the comparative element.
	 * @return boolean If the elements are equal to each other.
	 */
	public function equals($other) {
		return (bool) $this->request(WebDriver_Base::HTTP_GET, 'equals/' . $other);
	}


	/**
	 * Gets the location (x, y) of the element relative to the top-left corner of the page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/location
	 * @return mixed The x and y coordinates of the element.
	 */
	public function location() {
		return $this->request(WebDriver_Base::HTTP_GET, 'location');
	}


	/**
	 * Gets the location (x, y) of the element relative to the top-left corner of the page when
	 * the element comes into view.
	 *
	 * @internal This should only be used to determine an element's location for correctly generating native events.
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/location_in_view
	 * @return mixed The x and y coordinates of the element.
	 */
	public function location_in_view() {
		return $this->request(WebDriver_Base::HTTP_GET, 'location_in_view');
	}


	/**
	 * Gets the tag of the current element.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/name
	 * @return string The name of the elements tag.
	 */
	public function name() {
		return $this->request(WebDriver_Base::HTTP_GET, 'name');
	}


	/**
	 * Determines if the current element is selected for select options, checkboxes, or radiobuttons.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/selected
	 * @return boolean If the element is selected.
	 */
	public function selected() {
		return (bool) $this->request(WebDriver_Base::HTTP_GET, 'selected');
	}


	/**
	 * Gets the size (height, width) of the current element.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/size
	 * @return mixed The height and width of the element.
	 */
	public function size() {
		return $this->request(WebDriver_Base::HTTP_GET, 'size');
	}


	/**
	 * Submits an element if it is or is a descendant of a form element.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/submit
	 */
	public function submit() {
		$this->request(WebDriver_Base::HTTP_POST, 'submit');
	}


	/**
	 * Gets the visible text of the current element.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/text
	 * @return string The visible text of an element.
	 */
	public function text() {
		return $this->request(WebDriver_Base::HTTP_GET, 'text');
	}


	/**
	 * Sends a sequence of keys to be input for the current active element.  If no input is
	 * defined, then the value of the input is returned.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value
	 * @param mixed $value If defined as an array of characters or a string of text, it is input.
	 * @return string The value of the element if no input is defined.
	 */
	public function value($value = null) {
		$http = WebDriver_Base::HTTP_GET;
		$payload = array();

		if (!is_null($value)) {
			$http = WebDriver_Base::HTTP_POST;
			$payload['value'] = is_array($value) ? $value : str_split($value);
		}
		return $this->request($http, 'value', $payload);
	}
}
