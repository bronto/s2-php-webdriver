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
class WebDriver_Base {

	const
		HTTP_DELETE = 'DELETE',
		HTTP_GET = 'GET',
		HTTP_POST = 'POST',
		HTTP_PUT = 'PUT';

	private
		$id = null,
		$sauceKey = null,
		$sauceUser = null,

		$capabilities = array(
			'browserName' => 'firefox',
			'cssSelectorsEnabled' => true,
			'javascriptEnabled' => true,
			'handlesAlerts' => true,
			'locationContextEnabled' => true,
			'nativeEvents' => true,
			'platform' => 'ANY',
			'takesScreenshot' => false,
			'version' => ''
		),
		$debug = false,
		$server = null;


	/**
	 * Sets default values for the server session.
	 *
	 * @param string $server The location of the Selenium server.
	 * @param integer $port The server port number to connect to.
	 */
	public function __construct($server = 'localhost', $port = 4444) {
		$this->server = "http://$server:$port/";

		// grab the user:key pair if it exists for Sauce authentication
		preg_match("/([^:]+):([^@]+)@.+/", $server, $matchResults);
		if ((count($matchResults) == 3) && (strpos($server, 'ondemand.saucelabs.com') > 0)) {
			self::setSessionSauce($matchResults[1], $matchResults[2]);
		}
	}


	/**
	* Determines if there is a current session.
	*
	* @return boolean If there is a valid existing session.
	*/
	public function hasSession() {
		return is_null($this->id) ? false : true;
	}


	/**
	 * Determines if there is current Sauce session.
	 *
	 * @return boolean If there is a valid existing Sauce session.
	 */
	public function hasSessionSauce() {
		return (is_null($this->sauceUser) || is_null($this->sauceKey)) ? false : true;
	}


	/**
	 * Gets the current session id if it exists.
	 *
	 * @return integer The current session identifier.
	 */
	protected function getSessionId() {
		return $this->id;
	}


	/**
	 * Gets the current Sauce session user and access key values.
	 *
	 * @return array A string array containing the user and key if a current
	 * session with Sauce exists.
	 */
	protected function getSessionSauce() {
		if (self::hasSessionSauce()) {
			return array($this->sauceUser, $this->sauceKey);
		}
		return array();
	}


	/**
	 * Sets the user and access key of the active Sauce session.
	 *
	 * @param string $user The username (owner) of the authenticated session.
	 * @param string $key The user's access key for the session.
	 */
	protected function setSessionSauce($user, $key) {
		$this->sauceKey = $key;
		$this->sauceUser = $user;
	}


	/**
	 * Sets the id of the active session.
	 *
	 * @param integer $id The current session identifier.
	 */
	protected function setSessionId($id) {
		$this->id = $id;
	}


	/**
	 * Used for sending requests to the server and illiciting a response.
	 *
	 * @param string $type The HTTP request type to perform on the endpoint.
	 * @param string $pathEnd The endpoint path to request on the server.
	 * @param array $payload Any extra information the endpoint might require.
	 * @return mixed
	 * @throws WebDriver_Exception
	 */
	public function request($type, $pathEnd, $payload = array()) {
		$url = $this->server . 'wd/hub/' . $pathEnd;
		if (self::hasSessionSauce() && strpos($pathEnd, 'rest/v1') !== false) {
			list($sauceUser, $sauceKey) = self::getSessionSauce();
			$url = "https://$sauceUser:$sauceKey@saucelabs.com/$pathEnd";
		}

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json;'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);

		if (is_array($payload) && ($type === self::HTTP_POST)) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
		} else if (is_array($payload) && ($type === self::HTTP_PUT)) {
			$payloadEncoded = json_encode($payload);
			$fh = fopen('php://temp', 'w+');
			fwrite($fh, $payloadEncoded);
			fseek($fh, 0);

			curl_setopt($curl, CURLOPT_PUT, true);
			curl_setopt($curl, CURLOPT_INFILE, $fh);
			curl_setopt($curl, CURLOPT_INFILESIZE, strlen($payloadEncoded));
		} else {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
		}

		$curlResults = curl_exec($curl);
		$curlExplode = explode("\r\n\r\n", $curlResults);
		$jsonResult = json_decode(trim($curlExplode[1]));
		$curlError = curl_error($curl);
		$curlInfo = curl_getinfo($curl);
		curl_close($curl);

		if ($this->debug) {
			print("\ncurl info:\n");
			print_r($curlInfo);

			if (!empty($curlError)) {
				print("\ncurl error:\n");
				print_r($curlError);
			}

			if (!empty($curlResults)) {
				print("\ncurl results:\n");
				print_r($curlResults);
			}

			if (!empty($jsonResult)) {
				print("\n\njson result:\n");
				print_r($jsonResult);
			}
		}

		if (!$this->hasSession()) {
			preg_match("/Location: .*\/(.*)\n/", $curlExplode[0], $matchResults);

			if (count($matchResults)) {
				self::setSessionId(trim($matchResults[1]));
			}
		}

		if (isset($jsonResult->value)) {
			$value = $jsonResult->value;

			if (isset($value->message)) {
				throw new WebDriver_Exception($jsonResult->status, $value->message);
			}

			return $jsonResult->value;
		}

		return $jsonResult;
	}


	/**
	 * Defines the browser the session should use for testing with.
	 *
	 * @param string $browser The browser our session requires.
	 * @param decimal $version If defined, this sets a specific version requirement.
	 * @return boolean If the browser configuration was successfully defined.
	 */
	public function setBrowser($browser, $version = null) {
		if (!self::hasSession()) {
			$this->capabilities['browserName'] = $browser;
			$this->capabilities['version'] = $version ?: '';
			return true;
		}
		return false;
	}


	/**
	 * Defines a capability for the server session.
	 *
	 * @param string $name The name of the capability.
	 * @param mixed $value The value of the named capability to set.
	 * @return boolean If the capability was successfully defined.
	 */
	public function setCapability($name, $value) {
		if (!self::hasSession()) {
			$this->capabilities[$name] = $value;
			return true;
		}
		return false;
	}


	/**
	 * Allows for status updates to be sent to the server.  This is primarily for
	 * Sauce Labs testing and mimics the legacy Selenium setContext functionality.
	 *
	 * @param string $name The name of the status information to send.
	 * @param mixed $value THe value of the named status being sent.
	 * @return boolean If the context was successfully defined.
	 */
	public function setContext($name, $value) {
		if (self::hasSession() && self::hasSessionSauce()) {
			$payload = array(
				$name => $value
			);

			$jobId = self::getSessionId();
			list($sauceUser, $sauceKey) = self::getSessionSauce();

			self::request(self::HTTP_PUT, 'rest/v1/' . $sauceUser . '/jobs/' . $jobId, $payload);
			return true;
		}
		return false;
	}


	/**
	 * Creates a new session with the server.
	 *
	 * @return boolean If the session was successfully started.
	 */
	public function start() {
		if (!self::hasSession()) {
			self::session();
			return true;
		}
		return false;
	}


	/**
	 * Destroys the current session with the server.
	 *
	 * @return boolean If the session was successfully stopped.
	 */
	public function stop() {
		if ($this->hasSession()) {
			$sessionId = self::getSessionId();
			self::session($sessionId);
			return true;
		}
		return false;
	}


	/**
	 * Creates or destroys a server session with set capabilities.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session
	 * @param integer $sessionId If defined, this deletes the specified session identifier.
	 */
	public function session($sessionId = null) {
		$http = self::HTTP_DELETE;
		$payload = array();

		if (is_null($sessionId)) {
			$http = self::HTTP_POST;
			$endpoint = 'session';
			$payload['desiredCapabilities'] = $this->capabilities;
		} else {
			$endpoint = "session/$sessionId";
		}
		self::request($http, $endpoint, $payload);
	}


	/**
	 * Gets a list of the current sessions on the server, including their id and
	 * various defined capabilities.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/sessions
	 * @return array The current active sessions on the server.
	 */
	public function sessions() {
		return self::request(self::HTTP_GET, 'sessions');
	}


	/**
	 * Gets various current status information from the server, including the build
	 * and system os.
	 *
	 * @link http://code.google.com/p/selenium/wiki/JsonWireProtocol#/status
	 * @return stdClass An object giving an overview of the server.
	 */
	public function status() {
		return self::request(self::HTTP_GET, 'status');
	}
}
