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
require_once('Base.php');

class WebDriver_WindowTest extends Test_Base {

	protected
		$session = null,
		$window = null;


	/**
	 * setUp a new connection to the server and grab our current window.
	 */
	public function setUp() {
		parent::setUp();

		$windowHandle = $this->session->window_handle();
		$this->window = $this->session->window($windowHandle);
	}


	/**
	 * @covers WebDriver_Window::position
	 */
	public function testPosition() {
		$windowPosition1 = $this->window->position();
		$this->assertInternalType('integer', $windowPosition1->x);
		$this->assertInternalType('integer', $windowPosition1->y);

		$positionX = mt_rand(0, 600);
		$positionY = mt_rand(0, 480);

		$this->window->position($positionX, $positionY);
		$windowPosition2 = $this->window->position();
		$this->assertEquals($positionX, $windowPosition2->x);
		$this->assertEquals($positionY, $windowPosition2->y);
	}


	/**
	 * @covers WebDriver_Window::size
	 */
	public function testSize() {
		$windowSize1 = $this->window->size();
		$this->assertInternalType('integer', $windowSize1->height);
		$this->assertInternalType('integer', $windowSize1->width);

		$sizeHeight = mt_rand(50, 480);
		$sizeWidth = mt_rand(150, 600);

		$this->window->size($sizeHeight, $sizeWidth);
		$windowSize2 = $this->window->size();
		$this->assertEquals($sizeHeight, $windowSize2->height);
		$this->assertEquals($sizeWidth, $windowSize2->width);
	}
}
