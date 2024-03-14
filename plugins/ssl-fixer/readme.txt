=== SSL Fixer ===
Contributors: laitskin
Donate link: https://tinyurl.com/ybe6spnl
Tags: ssl, fix, mixed content, insecure, https
Requires at least: 4.7
Tested up to: 5.7
Stable tag: trunk
Requires PHP: 7.0
License: GPLv2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

== Description ==

SSL Fixer makes a few changes to the database in order to fix any insecure links. Effectively fixing the HTTPS redirection and mixed content problems in one click. Precisely speaking it does two things:

* Modify any insecure links from your wp-config.php file, such as WP_DEFINE() home and siteurl.
* Convert all your database's HTTP links into HTTPS ones, making the requests secure.

To make these changes, all you need to do is click the plugin's "Fix SSL" button.

== Changelog ==

=0.1=
* First upload
=0.2=
* Tested up to 5.5
* Added translatable strings
=0.3=
* Tested up to 5.7
* Fixed readme.txt and missing license header
* Added description