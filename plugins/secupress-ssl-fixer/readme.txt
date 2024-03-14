=== SecuPress SSL Fixer ===
Contributors: SecuPress
Tags: ssl, https, security
Requires at least: 4.7
Tested up to: 5.8.1
Requires PHP: 7.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Temporarily fix the SSL Check to prevent "cURL error 60: certificate problem: certificate has expired"

== Description ==

If you encounter the issue "cURL error 60: certificate problem: certificate has expired", you may need to temporarily deactivate the SSL Check.

SecuPress SSL Fixer will do it for you in a small interface.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/secupress-ssl-fix` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Use the Settings > SecuPress SSL Fixer screen to configure the plugin.


== Frequently Asked Questions ==

= What does SecuPress SSL Fixer do, exactly? =

SecuPress SSL Fixer will deactivate the SSL Check for each HTTP request coming from your WordPress site for a short period of time, because this is not secure no never check it.

= Do you have a good solution to secure my website? =

Yes, SecuPress.me does the job, this is our main product. Free and Pro versions exist.

== Screenshots ==

== Changelog ==

= 1.0 =

* 05 October 2021
* First release