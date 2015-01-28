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

    Copyright 2015 Bronto Software, Inc.

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

[bronto]:http://www.bronto.com
[firefox]:ftp://ftp.mozilla.org/pub/firefox/releases/17.0.1/
[saucelabs]:http://www.saucelabs.com
[selenium]:http://www.seleniumhq.org
[seleniumServer]:http://selenium.googlecode.com/files/selenium-server-standalone-2.25.0.jar
