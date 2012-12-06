Selenium-Saucelabs (S2) PHP Webdriver
=====================================
This allows for the integration of PHP based unit tests with a [Selenium Server][selenium] or remote [Saucelabs][saucelabs] connection.  The unit tests provided are currently compatible with Firefox [17.0.1][firefox] and Selenium Server [2.25][seleniumServer].

Getting Started
---------------
1. Copy the contents of `s2-php-webdriver` to you server.
2. Modify the configuration settings in `Test/Base.php` if necessary.
3. Verify that your [Selenium Server][seleniumServer] is running and then execute:

		$ phpunit Test

License
-------
Selenium-Saucelabs (S2) PHP WebDriver
Copyright (C) 2012 [Bronto Software, Inc.][bronto]

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

[bronto]:http://www.bronto.com
[firefox]:ftp://ftp.mozilla.org/pub/firefox/releases/17.0.1/
[saucelabs]:http://www.saucelabs.com
[selenium]:http://www.seleniumhq.org
[seleniumServer]:http://selenium.googlecode.com/files/selenium-server-standalone-2.25.0.jar
