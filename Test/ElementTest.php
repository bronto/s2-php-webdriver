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

class WebDriver_ElementTest extends Test_Base {

	protected
		$session = null;


	/**
	 * @covers WebDriver_Element::attribute
	 */
	public function testAttribute() {
		$elementName = $this->session->element('name', 'testName');
		$this->assertEquals('John Doe', $elementName->attribute('value'));
		$this->assertTrue(strlen($elementName->attribute('style')) === 0);
	}


	/**
	 * @covers WebDriver_Element::clear
	 */
	public function testClear() {
		$elementName = $this->session->element('name', 'testName');
		$elementDesc = $this->session->element('name', 'testDesc');

		$this->assertEquals('John Doe', $elementName->attribute('value'));
		$this->assertEquals('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $elementDesc->attribute('value'));

		$elementName->clear();
		$elementDesc->clear();

		$this->assertTrue(strlen($elementName->attribute('value')) === 0);
		$this->assertTrue(strlen($elementDesc->attribute('value')) === 0);
	}


	/**
	 * @covers WebDriver_Element::click
	 */
	public function testClick() {
		$elementLink = $this->session->element('xpath', '//h3/a');
		$elementLink->click();

		$elementName = $this->session->element('name', 'testName');
		$this->assertEquals('Jane Doe', $elementName->attribute('value'));
	}


	/**
	 * @covers WebDriver_Element::css
	 */
	public function testCss() {
		$elementImage = $this->session->element('id', 'selenium-icon');
		$this->assertEquals('48px', $elementImage->css('width'));

		$elementDiv = $this->session->element('id', 'testContent');
		$this->assertEquals('rgba(206, 206, 206, 1)', $elementDiv->css('background-color'));
		$this->assertEquals('left', $elementDiv->css('text-align'));
	}


	/**
	 * @covers WebDriver_Element::displayed
	 */
	public function testDisplayed() {
		$elementImage = $this->session->element('id', 'selenium-icon');
		$this->assertTrue($elementImage->displayed());

		$elementDiv = $this->session->element('id', 'testHidden');
		$this->assertFalse($elementDiv->displayed());
	}


	/**
	 * @covers WebDriver_Element::enabled
	 */
	public function testEnabled() {
		$elementName = $this->session->element('name', 'testName');
		$this->assertTrue($elementName->enabled());

		$elementEvent = $this->session->element('name', 'testEvent');
		$this->assertFalse($elementEvent->enabled());
	}


	/**
	 * @covers WebDriver_Element::equals
	 */
	public function testEquals() {
		$elementName1 = $this->session->element('name', 'testName');
		$elementDesc = $this->session->element('name', 'testDesc');
		$this->assertFalse($elementName1->equals($elementDesc->id));

		$elementName2 = $this->session->element('name', 'testName');
		$this->assertTrue($elementName1->equals($elementName2->id));
	}


	/**
	 * @covers WebDriver_Element::name
	 */
	public function testName() {
		$elementLink = $this->session->element('link text', 'Sauce Labs');
		$this->assertEquals('a', $elementLink->name());

		$elementName = $this->session->element('name', 'testName');
		$this->assertEquals('input', $elementName->name());

		$elementDesc = $this->session->element('name', 'testDesc');
		$this->assertEquals('textarea', $elementDesc->name());
	}


	/**
	 * @covers WebDriver_Element::selected
	 */
	public function testSelected() {
		$elementSelected1 = $this->session->element('xpath', '//form/div[5]/input[1]');
		$this->assertFalse($elementSelected1->selected());

		$elementSelected2 = $this->session->element('xpath', '//form/div[5]/input[2]');
		$this->assertTrue($elementSelected2->selected());

		$elementSelected3 = $this->session->element('xpath', "//select/option[@value='element']");
		$this->assertTrue($elementSelected3->selected());
	}


	/**
	 * @covers WebDriver_Element::size
	 */
	public function testSize() {
		$elementImage = $this->session->element('name', 'testButton');
		$imageSize = $elementImage->size();

		// input (20) + border on each side (2)
		$this->assertEquals(22, $imageSize->width);
	}


	/**
	 * @covers WebDriver_Element::submit
	 */
	public function testSubmit() {
		$elementLink = $this->session->element('xpath', '//h3/a');
		$elementLink->click();

		$elementName1 = $this->session->element('name', 'testName');
		$this->assertEquals('Jane Doe', $elementName1->attribute('value'));

		$elementSubmit = $this->session->element('name', 'testSubmit');
		$elementSubmit->click();
		sleep(1);

		$elementName2 = $this->session->element('name', 'testName');
		$this->assertEquals('John Doe', $elementName2->attribute('value'));

		$assertURL = $this->testWebDriver . '?testName=Jane+Doe&testMethod=element&testDesc=I+love+Selenium+testing%21&testCheck=0&testSubmit=submit';
		$this->assertEquals($assertURL, $this->session->url());
	}


	/**
	 * @covers WebDriver_Element::value
	 */
	public function testValue() {
		$elementName = $this->session->element('name', 'testName');
		$this->assertEquals('John Doe', $elementName->value());

		$elementName->clear();
		$elementName->value('Valerie Valuator');
		$this->assertEquals('Valerie Valuator', $elementName->value());
	}
}
