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
require_once('Base.php');

class WebDriver_SessionTest extends Test_Base {

	protected
		$session = null;


	/**
	 * dataProvider for testClick
	 */
	public static function dataClick() {
		return array(
			array(0), array(2)
		);
	}


	/**
	 * @covers WebDriver_Session::accept_alert
	 */
	public function testAcceptAlert() {
		$elementAlert = $this->session->element('name', 'testAlert');
		$elementAlert->click();
		$this->session->accept_alert();

		$elementName = $this->session->element('name', 'testName');
		$elementDesc = $this->session->element('name', 'testDesc');

		$this->assertEquals('Alan Alertable', $elementName->attribute('value'));
		$this->assertEquals('I like to grab attention.', $elementDesc->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::alert_text
	 */
	public function testAlertText() {
		$elementAlert = $this->session->element('name', 'testPrompt');
		$elementAlert->click();
		$this->assertEquals('Enter any string of text:', $this->session->alert_text());

		$this->session->alert_text('This is my string of text!');
		$this->session->accept_alert();

		$elementDesc = $this->session->element('name', 'testDesc');
		$this->assertEquals('This is my string of text!', $elementDesc->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::back
	 */
	public function testBack() {
		$elementLink = $this->session->element('link text', 'Sauce Labs');
		$elementLink->click();
		sleep(3);

		$this->assertEquals('https://saucelabs.com/', $this->session->url());
		$this->session->back();
		sleep(3);

		$this->assertEquals($this->testWebDriver, $this->session->url());
	}


	/**
	 * @covers WebDriver_Session::buttondown
	 */
	public function testButtondown() {
		$elementSpan = $this->session->element('xpath', '//h3/span[1]');
		$this->session->moveto($elementSpan->id);
		$this->session->buttondown();

		$elementEvent = $this->session->element('id', 'testEvent');
		$this->assertEquals('mousedown', $elementEvent->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::buttonup
	 */
	public function testButtonup() {
		$elementImage = $this->session->element('id', 'selenium-icon');
		$this->session->moveto($elementImage->id);
		$this->session->buttondown();
		$this->session->buttonup();

		$elementEvent = $this->session->element('id', 'testEvent');
		$this->assertEquals('mouseup', $elementEvent->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::click
	 * @dataProvider dataClick
	 */
	public function testClick($dataButton) {
		$elementSpan = $this->session->element('xpath', '//h3/span[1]');
		$this->session->moveto($elementSpan->id);
		$this->session->click($dataButton);

		$elementEvent = $this->session->element('id', 'testButton');
		$this->assertEquals($dataButton, $elementEvent->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::cookie
	 * @covers WebDriver_Session::cookies
	 */
	public function testCookie() {
		$cookieExpiry = time() + 120;
		$this->session->cookie('testCookie', 'abc123', null, null, true, $cookieExpiry);
		$cookies1 = $this->session->cookies();

		$found1 = null;
		if (is_array($cookies1)) {
			foreach ($cookies1 as $cookie) {
				if ($cookie->name === 'testCookie') {
					$found1 = $cookie;
					break;
				}
			}
		} else {
			$found1 = $cookies1;
		}

		if (is_null($found1)) {
			$this->fail('Cookie could not be found after being set for the domain!');
		}

		$this->assertEquals('testCookie', $found1->name);
		$this->assertEquals('abc123', $found1->value);
		$this->assertEquals('/', $found1->path);
		$this->assertEquals(parse_url($this->session->url(), PHP_URL_HOST), $found1->domain);
		$this->assertEquals(true, $found1->secure);
		$this->assertEquals($cookieExpiry, $found1->expiry);

		$this->session->cookie('testCookie');
		$cookies2 = $this->session->cookies();

		$found2 = null;
		if (is_array($cookies2)) {
			foreach ($cookies2 as $cookie) {
				if ($cookie->name === 'testCookie') {
					$found2 = $cookie;
					break;
				}
			}
		} else {
			$found2 = $cookies2;
		}
		$this->assertNull($found2);
	}


	/**
	 * @covers WebDriver_Session::dismiss_alert
	 */
	public function testDismissAlert() {
		$elementAlert = $this->session->element('name', 'testConfirm');
		$elementAlert->click();
		$this->session->dismiss_alert();

		$elementName = $this->session->element('name', 'testName');
		$elementDesc = $this->session->element('name', 'testDesc');

		$this->assertEquals('Carl Confirmer', $elementName->attribute('value'));
		$this->assertEquals('I need validation of everything!', $elementDesc->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::doubleclick
	 */
	public function testDoubleclick() {
		$elementSpan = $this->session->element('xpath', '//h3/span[2]');
		$this->session->moveto($elementSpan->id);
		$this->session->doubleclick();

		$elementEvent = $this->session->element('id', 'testEvent');
		$this->assertEquals('dblclick', $elementEvent->attribute('value'));
	}


	/**
	 * @covers WebDriver_Session::forward
	 */
	public function testForward() {
		$elementLink = $this->session->element('link text', 'Sauce Labs');
		$elementLink->click();
		sleep(3);

		$this->assertEquals('https://saucelabs.com/', $this->session->url());
		$this->session->back();
		sleep(3);

		$this->assertEquals($this->testWebDriver, $this->session->url());
		$this->session->forward();
		sleep(3);

		$this->assertEquals('https://saucelabs.com/', $this->session->url());
	}


	/**
	 * @covers WebDriver_Session::ime
	 */
	public function testIme() {
		$this->assertInstanceOf('WebDriver_IME', $this->session->ime());
	}


	/**
	 * @covers WebDriver_Session::screenshot
	 */
	public function testScreenshot() {
		if (is_readable('.') && is_writable('.')) {
			$screenshot = base64_decode($this->session->screenshot());
			file_put_contents('testScreenshot.png', $screenshot);
			$info = getimagesize('testScreenshot.png');
			$this->assertEquals('image/png', $info['mime']);
			unlink('testScreenshot.png');
		} else {
			$this->markTestSkipped('Test was unable to read/write image to directory.');
		}
	}


	/**
	 * @covers WebDriver_Session::source
	 */
	public function testSource() {
		$this->assertTrue(strlen($this->session->source()) > 0);
	}


	/**
	 * @covers WebDriver_Session::title
	 */
	public function testTitle() {
		$this->assertEquals('Selenium WebDriver Testing', $this->session->title());
	}


	/**
	 * @covers WebDriver_Session::touch
	 */
	public function testTouch() {
		$this->assertInstanceOf('WebDriver_Touch', $this->session->touch());
	}


	/**
	 * @covers WebDriver_Session::url
	 */
	public function testUrl() {
		$this->assertEquals($this->testWebDriver, $this->session->url());
	}


	/**
	 * @covers WebDriver_Session::window
	 */
	public function testWindow() {
		$elementLink = $this->session->element('link text', 'Google');
		$elementLink->click();

		$windows = $this->session->window_handles();
		$this->assertEquals(2, count($windows));

		$this->session->window($windows[1]);
		sleep(5);

		$this->assertEquals('http://www.google.com/', $this->session->url());
		$this->session->window();

		$window = $this->session->window($windows[0]);
		$this->assertInstanceOf('WebDriver_Window', $window);
		$this->assertEquals($windows[0], $window->handle);
		$this->assertEquals($this->testWebDriver, $this->session->url());
	}


	/**
	 * @covers WebDriver_Session::window_handle
	 */
	public function testWindowHandle() {
		$window = $this->session->window_handle();
		$this->assertTrue(strlen($window) > 0);
	}


	/**
	 * @covers WebDriver_Session::window_handles
	 */
	public function testWindowHandles() {
		$elementLink = $this->session->element('link text', 'Google');
		$elementLink->click();

		$windows = $this->session->window_handles();
		$this->assertEquals(2, count($windows));

		foreach ($windows as $window) {
			$this->assertTrue(strlen($window) > 0);
		}
	}
}
