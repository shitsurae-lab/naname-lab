=== XO Security ===
Contributors: ishitaka
Tags: security, login, pingback, xmlrpc, rest
Requires at least: 4.9
Tested up to: 6.3
Requires PHP: 5.6
Stable tag: 3.6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

XO Security is a plugin to enhance login related security.

== Description ==

XO Security is a plugin to enhance login related security.
This plugin does not write to .htaccess file. Besides Apache, LiteSpeed, Nginx and IIS also work.

= Functions =

* Record login log.
* Limit login attempts.
* Login Alert.
* Add Captcha to the login form and comment form.
* Change the URL of the login page.
* Disable login by mail address.
* Disable login by user name.
* Change login error message.
* Disable XML-RPC and XML-RPC Pingback.
* Disable REST API.
* Change REST API URL prefix.
* Disable author archive page.
* Remove comment author class of comments list.
* Remove the username from the oEmbed response data.
* WooCommerce login page protection.
* Anti-spam comment.
* Hide WordPress version information.
* Edit the author slug.
* Disable RSS and Atom feeds.

= WordPress multisite considerations =

If you set the login page separately for the main site and the subsite, you will not be able to use the password loss function of the subsite. We recommend that you set the login page to be common to all sites.

== Installation ==

1. Upload the `XO-Security` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to "Settings" -> "XO Security" and customize behaviour as needed.

== Screenshots ==

1. Login log page.
2. Status page.
3. Login setting page.
4. Profile page.

== Frequently Asked Questions ==

= Login page is not displayed. =

Please initialize the settings.

* In wp_options table, the value of the option_name field (column) is to remove the record of "xo_security_options".
* If you have set the login page, please delete the file.

= The CAPTCHA is not displayed. =

Please install mbstring and GD module.

== Changelog ==

= 3.6.3 =

* Added `required` attribute to CAPTCHA field in login form.
* Supported WordPress 6.3.

= 3.6.2 =

* Fixed a bug that the login type was not displayed in the login log.
* Faster admin pages.
* Added option to enable/disable login information widget.

= 3.6.1 =

* Fixed a bug that the table optimization function did not work.
* Fixed errors output issue.

= 3.6.0 =

* Added a function to optimize database table.
* Code refactoring to meet WordPress PHP Coding Standards.
* Supported WordPress 6.2.

= 3.5.1 =

* Supported SQLite.

= 3.5.0 =

* Removed password field from login log.

= 3.4.5 =

* Fixed a bug that CAPTCHA may not be displayed in PHP 8.1.
* Removed site information from the status screen.

= 3.4.4 =

* Added support for MySQL 5.5.13 and earlier versions.

= 3.4.3 =

* Fixed a bug that the login log may not be recorded in some environments.

= 3.4.2 =

* Added xo_security_loginlog_checkbox filter hook.

= 3.4.1 =

* Added a function to mark spam comment email addresses as spam.
* Added escaping to multiple translate texts for enhanced security.

= 3.4.0 =

* Bumped the minimum required version of WordPress to 4.9.
* Improved performance.

= 3.3.0 =

* Added the ability to remove username from the oEmbed response data.

= 3.2.1 =

* Fixed a bug where the author slug (Nicename) could not be edited.

= 3.2.0 =

* Added an option to set a common login page for all WordPress multisite sites.

= 3.1.9 =

* Enhanced WordPress multisite support.

= 3.1.8 =

* Fixed a bug that the post list page for each creator on the admin screen is not displayed when the creator archive page is disabled.
* Fixed a bug that login may fail when using CAPTCHA.

= 3.1.7 =

* Fixed the html of the setting screen after it was incorrect.
* Omitted the lazy loading attribute of CAPTCHA in the login form.

= 3.1.6 =

* Fixed a vulnerability in Authenticated (author +) Time-based SQL Injection. (Thanks to Kenta Yoshida)

= 3.1.5 =

* Added the ability to choose whether spam comments should be blocked, marked as spam and saved, or put in the trash.

= 3.1.4 =

* Fixed a bug that an error message may be displayed on the admin screen during a new installation. 

= 3.1.3 =

* Fixed a bug in login log recording.

= 3.1.2 =

* Added an option to set the default display method of the login log.

= 3.1.1 =

* Fixed a bug where CAPTCHA was ignored and login was possible when PHP session was not available. (Thanks to Jazz@ifNoob)

= 3.1.0 =

* In the case of WordPress multisite, the log is recorded for each site.
* Added the ability to disable RSS and Atom feeds.

= 3.0.0 =

* Added the editing function of the author slug. 
* Disabled auto-completion for CAPTCHA input fields.

= 2.9.0 =

* Added the ability to hide WordPress version information.

= 2.8.0 =

* Added the ability to block spam comment.

= 2.7.0 =

* Restructured the settings page.
* Added the function to customize the login form.

= 2.6.0 =
* Changed to remove the standard sitemap user provider when disabling the author archive.

= 2.5.0 =

* Added login type column to login log.

= 2.4.0 =

* Added the option to select the method of acquiring the IP address.

= 2.3.0 =

* Added a feature to disable login by user name and enable login by email only.

= 2.1.3 =

* Fixed a bug that could slow down the display of the admin page. (Thanks to mocchii)

= 2.1.0 =

* Added function to display site information.

= 2.0.0 =

* Added option to change login error message.
* Added option to disable login by mail address.

= 1.5.3 =

* Fixed XSS vulnerability.

= 1.0.0 =

* Initial release.
