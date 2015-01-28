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
class WebDriver_Session extends WebDriver_Base {

	public
		$strategies = array(
			'class name', 'css selector', 'id', 'name', 'link text',
			'partial link text', 'tag name', 'xpath'
		);


	/**
	 * Used to construct the requested Session action.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 */
	public function request($type, $pathEnd, $payload = array()) {
		return parent::request($type, 'session/' . $this->getSessionId() . '/' . $pathEnd, $payload);
	}


	/**
	 * Accepts an active alert dialog.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/accept_alert
	 */
	public function accept_alert() {
		$this->request(parent::HTTP_POST, 'accept_alert');
	}


	/**
	 * Gets the text of an active alert, confirm, or prompt dialog.  If text is supplied to the
	 * function, it will set the value to a prompt dialog.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/alert_text
	 * @param string $text If defined, text is sent to the active prompt dialog.
	 * @return string Text of the active dialog.
	 */
	public function alert_text($text = null) {
		$http = parent::HTTP_GET;
		$payload = array();

		if (!is_null($text)) {
			$http = parent::HTTP_POST;
			$payload['text'] = $text;
		}
		return $this->request($http, 'alert_text', $payload);
	}


	/**
	 * Navigates backward in the browser history.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/back
	 */
	public function back() {
		$this->request(parent::HTTP_POST, 'back');
	}


	/**
	 * Click and hold the left mouse button down on the current position.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/buttondown
	 */
	public function buttondown() {
		$this->request(parent::HTTP_POST, 'buttondown');
	}


	/**
	 * Releases the left mouse button on the current positon.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/buttonup
	 */
	public function buttonup() {
		$this->request(parent::HTTP_POST, 'buttonup');
	}


	/**
	 * Click the appropriate mouse button on the current position.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/click
	 * @param integer $button The mouse button (LEFT = 0, MIDDLE = 1 , RIGHT = 2) to click.
	 */
	public function click($button = 0) {
		$payload = array(
			'button' => $button
		);
		$this->request(parent::HTTP_POST, 'click', $payload);
	}


	/**
	 * Gets, sets, or deletes a browser cookie for the current page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/cookie
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#Cookie_JSON_Object
	 * @param string $name The name of the cookie to set or delete (if only this is specified).
	 * @param mixed $value The value to set the cookie to.
	 * @param string $path The path relative to the current domain for the cookie.
	 * @param string $domain The domain of the cookie (defaults to the current pages domain if not set).
	 * @param boolean $secure Should the cookie be a secure cookie.
	 * @param integer $expiry When the cookie expires since the Unix Epoch.
	 * @return WebDriver_Cookie
	 */
	public function cookie($name, $value = null, $path = null, $domain = null, $secure = null, $expiry = null) {
		$payload = array(
			'name' => $name
		);

		if (!is_null($name) && !is_null($value)) {
			$cookie = new WebDriver_Cookie(
				$this, $name, $value, $path, $domain, $secure, $expiry
			);
			$cookie->set();
			return $cookie;
		} else {
			$this->request(parent::HTTP_DELETE, 'cookie', $payload);
		}
	}


	/**
	 * Gets all browser cookies for the current page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#Cookie_JSON_Object
	 * @return array
	 */
	public function cookies() {
		$response = $this->request(parent::HTTP_GET, 'cookie');

		$cookies = array();
		foreach ($response as $cookie) {
			array_push(
				$cookies,
				new WebDriver_Cookie(
					$this, $cookie->name, $cookie->value, $cookie->path, $cookie->domain, $cookie->secure, $cookie->expiry
				)
			);
		}
		return $cookies;
	}


	/**
	 * Dismisses an active alert dialog.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/dismiss_alert
	 */
	public function dismiss_alert() {
		$this->request(parent::HTTP_POST, 'dismiss_alert');
	}


	/**
	 * Double-clicks the mouse button on the current position.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/doubleclick
	 */
	public function doubleclick() {
		$this->request(parent::HTTP_POST, 'doubleclick');
	}


	/**
	 * Searches for an element on the current page and returns it if found.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element
	 * @param string $using The locator strategy to apply in searching for the element.
	 * @param string $value The target of the search strategy.
	 * @return WebDriver_Element
	 */
	public function element($using, $value) {
		return $this->elements($using, $value, true);
	}


	/**
	 * Searches for elements on the current page and returns any found.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/elements
	 * @param string $using The locator strategy to apply in searching for elements.
	 * @param string $value The target of the search strategy.
	 * @param boolean $element Switches our endpoint for single element searching.
	 * @return mixed
	 */
	public function elements($using, $value, $element = false) {
		$payload = array();
		if (in_array($using, $this->strategies)) {
			$payload['using'] = $using;
			$payload['value'] = $value;
		}
		$endpoint = $element ? 'element' : 'elements';
		$response = $this->request(parent::HTTP_POST, $endpoint, $payload);

		if (!is_array($response) && isset($response)) {
			return new WebDriver_Element($this, $response->ELEMENT, $value);
		}

		$elements = array();
		foreach ($response as $element) {
			array_push($elements, new WebDriver_Element($this, $element->ELEMENT, $value));
		}
		return $elements;
	}


	/**
	 * Executes a synchronous script in the client browser.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/execute
	 * @param string $script What to be executed in the form of a function body.
	 * @param array $args Any arguments that should be passed to the executed script.
	 * @param boolean $async Whether this call should be done asynchronously.
	 * @return mixed
	 */
	public function execute($script, $args = array(), $async = false) {
		$payload = array(
			'script' => $script,
			'args' => $args
		);

		$endpoint = $async ? 'execute_async' : 'execute';
		return $this->request(parent::HTTP_POST, $endpoint, $payload);
	}


	/**
	 * Executes an asynchronous script in the client browser.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/execute_async
	 * @param string $script What to be executed in the form of a function body.
	 * @param array $args Any arguments that should be passed to the executed script.
	 * @return mixed
	 */
	public function execute_async($script, $args = array()) {
		return $this->execute($script, $args, true);
	}


	/**
	 * Navigates forward in the browser history.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/forward
	 */
	public function forward() {
		$this->request(parent::HTTP_POST, 'forward');
	}


	/**
	 * Sets the current focus to a browser frame.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/frame
	 * @param mixed $id If defined, the browser frame identifier is switched to.
	 */
	public function frame($id = null) {
		$payload = array(
			'id' => $id
		);
		$this->request(parent::HTTP_POST, 'frame', $payload);
	}


	/**
	 * Returns a new IME object that allows for engine activation/deactivation.
	 *
	 * @return WebDriver_IME
	 */
	public function ime() {
		return new WebDriver_IME($this);
	}


	/**
	 * Sends a sequence of keys to be input for the current active element.  This is different
	 * from the element value() method as it does not release modifiers after executed.  This
	 * allows for mouse interaction to continue unimpeded.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/keys
	 * @param mixed $value An array of characters or a string of text to input.
	 */
	public function keys($value) {
		$payload = array();

		$keys = is_array($value) ? $value : str_split($value);
		$payload['value'] = $keys;

		$this->request(parent::HTTP_POST, 'keys', $payload);
	}


	/**
	 * Moves the mouse to the specified coordinate or elements bounded center.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/moveto
	 * @param integer $element The element identifier to move to.
	 * @param integer $xoffset The horizontal offset relative to the top-left corner of the page.
	 * @param integer $yoffset The vertical offset relative to the top-left corner of the page.
	 */
	public function moveto($element, $xoffset = null, $yoffset = null) {
		$payload = array(
			'element' => $element,
		);

		if (!is_null($xoffset) && !is_null($yoffset)) {
			$payload['xoffset'] = $xoffset;
			$payload['yoffset'] = $yoffset;
		}
		$this->request(parent::HTTP_POST, 'moveto', $payload);
	}


	/**
	 * Gets or sets the current browser orientation.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/orientation
	 * @param string $orientation If defined (LANDSCAPE or PORTRAIT), it will change the browser orientation.
	 * @return string The current browser orientation.
	 */
	public function orientation($orientation = null) {
		$http = parent::HTTP_POST;
		$payload = array();

		if (!is_null($orientation) && in_array($orientation, array('PORTRAIT', 'LANDSCAPE'))) {
			$http = parent::HTTP_GET;
			$payload['orientation'] = $orientation;
		}
		return $this->request($http, 'orientation', $payload);
	}


	/**
	 * Refreshes the current page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/refresh
	 */
	public function refresh() {
		$this->request(parent::HTTP_POST, 'refresh');
	}


	/**
	 * Captures of a screenshot of the current page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/screenshot
	 * @return string The captured base64 encoded PNG.
	 */
	public function screenshot() {
		return $this->request(parent::HTTP_GET, 'screenshot');
	}


	/**
	 * Gets the current page source code.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/source
	 */
	public function source() {
		return $this->request(parent::HTTP_GET, 'source');
	}


	/**
	 * Gets the title of the current page.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/title
	 */
	public function title() {
		return $this->request(parent::HTTP_GET, 'title');
	}


	/**
	 * Returns a new Timeouts object that allows for constraining asynchronous scripts
	 * and element searching.
	 *
	 * @return WebDriver_Timeouts
	 */
	public function timeouts() {
		return new WebDriver_Timeouts($this);
	}


	/**
	 * Returns a new Touch object that allows for interation of a touch enabled device.
	 *
	 * @return WebDriver_Touch
	 */
	public function touch() {
		return new WebDriver_Touch($this);
	}


	/**
	 * Gets or sets the active browser URL.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/url
	 * @param string $url If defined, this sets and navigates to a new URL.
	 * @return string The current active URL.
	 */
	public function url($url = null) {
		$http = parent::HTTP_GET;
		$payload = array();

		if (!is_null($url)) {
			$http = parent::HTTP_POST;
			$payload['url'] = trim($url);
		}
		return $this->request($http, 'url', $payload);
	}


	/**
	 * Sets the focus to another window or closes the active window.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/window
	 * @param string $name If defined, switches focus to the window handle.
	 * @return WebDriver_Window
	 */
	public function window($name = null) {
		$payload = array();

		if (!is_null($name)) {
			$payload['name'] = trim($name);

			$this->request(parent::HTTP_POST, 'window', $payload);
			return new WebDriver_Window($this, $name);
		} else {
			$this->request(parent::HTTP_DELETE, 'window', $payload);
		}
	}


	/**
	 * Gets the active browser window handle.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/window_handle
	 * @return string The current active window handle.
	 */
	public function window_handle() {
		return $this->window_handles(true);
	}


	/**
	 * Gets a list of all browser window handles.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/window_handles
	 * @param boolean $handle Switches our endpoint for a single window handle.
	 * @return array A list of all window handles.
	 */
	public function window_handles($handle = false) {
		$endpoint = $handle ? 'window_handle' : 'window_handles';
		$response = $this->request(parent::HTTP_GET, $endpoint);

		if (!is_array($response) && isset($response)) {
			return $response;
		}

		$windows = array();
		foreach ($response as $window) {
			array_push($windows, $window);
		}
		return $windows;
	}
}
