=== WP-CORS ===
Contributors: tstephenson
Tags: CORS, REST, AJAX
Requires at least: 3.6
Tested up to: 6.2.2
Stable tag: 0.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows AJAX requests from other sites to integrate content from your site using the CORS standard.

== Description ==

My use case is to allow content authors to write help pages in WordPress.
This content is fetched and embedded into a single page application hosted on another domain.

AJAX requests to this site from another are typically disallowed by the browser's security model.
To permit legitimate uses the requesting browser may include an Origin header containing its domain.
This plugin uses the Origin header to decide whether to allow the request or not.
Allowed domains can be specified in the plugin's Settings page. 

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the uncompressed contents of `wp-cors.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Why do I need this plugin? =

If you want to integrate content from your site to JavaScript applications running on other host domains (or allow other people to) then the CORS standard is a way to allow this. 

= What is the difference between CORS and JSONP? =

CORS is more modern and more secure since it works _with_ the browser's same-origin policy and XmlHttpRequest objects rather than bypassing them. 

= Ok I'm sold, where can I read more about CORS? =

You can find the CORS spec here: http://www.w3.org/TR/cors/ You can learn more about how to use CORS here: http://www.html5rocks.com/en/tutorials/cors/

= How do I control which sites can integrate using CORS? = 

This plugin's Settings page allows administrators to specify a comma separated list of allowed domains. 

== Screenshots ==

1. The plugin's Settings page. 

== Changelog ==

= 0.2.2 =
Tested up to WordPress 6.2.2
Prevent cross-site script injection on Settings page (CVE-2022-47606).
Note this vulnerability may only be exploited if the user is already logged in with Admin privilege.

= 0.2.1 =
Tested up to WordPress 4.3
Minor fixes to avoid 404 on (unnecessary) files.

= 0.2.0 =

Publish on WordPress.org.

= 0.1.1 =

Stop debugging statements flooding the error log. 

= 0.1.0 =
Initial proof of concept. 

