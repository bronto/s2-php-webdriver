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
class WebDriver_Cookie {

	private
		$session = null,
		$set = false;

	public
		$name = null,
		$value = null,
		$path = null,
		$domain = null,
		$secure = null,
		$expiry = null;


	/**
	 * Sets default values for the Cookie object.
	 *
	 * @param WebDriver_Session $session The current session with the server.
	 * @param string $name The name of the cookie to set or delete (if only this is specified).
	 * @param mixed $value The value to set the cookie to.
	 * @param string $path The path relative to the current domain for the cookie.
	 * @param string $domain The domain of the cookie (defaults to the current pages domain if not set).
	 * @param boolean $secure Should the cookie be a secure cookie.
	 * @param integer $expiry When the cookie expires since the Unix Epoch.
	 */
	public function __construct($session, $name, $value, $path = null, $domain = null, $secure = null, $expiry = null) {
		$this->session = $session;

		$this->name = $name;
		$this->value = $value;
		$this->path = $path ?: '/';
		$this->domain = $domain ?: parse_url($this->session->url(), PHP_URL_HOST);
		$this->secure = $secure ?: null;
		$this->expiry = $expiry ?: null;
	}


	/**
	 * Removes the current cookie by sending a HTTP DELETE request.
	 */
	public function delete() {
		$this->session->request(WebDriver_Base::HTTP_DELETE, 'cookie', array('name' => $this->name));
		$this->set = false;
	}


	/**
	 * Sets the current cookie by sending an HTTP POST request.
	 */
	public function set() {
		if (!$this->set) {
			$cookie = array(
				'name' => $this->name,
				'value' => $this->value,
				'path' => $this->path,
				'domain' => $this->domain,
			);

			if (!is_null($this->secure)) {
				$cookie['secure'] = $this->secure;
			}

			if (!is_null($this->expiry)) {
				$cookie['expiry'] = $this->expiry;
			}

			$this->session->request(WebDriver_Base::HTTP_POST, 'cookie', array('cookie' => $cookie));
			$this->set = true;
		}
	}
}
