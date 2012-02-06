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
