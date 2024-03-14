=== WP Redirect Permallink ===
Contributors: nabtron
Donate link: https://nabtron.com/
Tags: remove, post_id, end, redirect, permalink
Requires at least: 4.2.2
Tested up to: 6.4.3
Stable tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Efficiently redirect previous urls that were '/postname/post_id/' to  '/postname/' automatically. Respects query variables.

== Description ==
WP Redirect Permalink plugin allows you to efficiently redirect your previous urls that were <strong><code>'/postname/post_id/'</code></strong> to only <strong><code>'/postname/'</code></strong> that WordPress doesn't do automatically. This plugin respects and keeps the query variable in your url while changing the permalink structure.

If you've planned to change your WordPress permalink from <strong><code>/%postname%/%postid%/</code></strong> to only <strong><code>/%postname%/</code></strong> , although the old links still work fine, they're not automatically transferred to the new one without the post id in it. This can cause serious duplicate content issue for your site (can be seen and confirmed by Google webmaster tools). 

This plugin removed those duplicated content issues by redirecting the visitor to the new permalink with a 301 (permanently moved) status code.

This plugin takes care of pagination of categories and blog list and allows them to work normally. 

If you have any feedback, suggestion, request or bug report please let us know at: https://nabtron.com/wp-redirect-permalink/

== Installation ==

How to istall ?

1. Download the plugin
2. Upload entire folder "WP-Redirect-Permalink" to the "/wp-content/plugins/" directory
3. Activate the plugin through the "Plugins" menu in WordPress

== Frequently Asked Questions ==

= Does it respect query variables? =

Yes it does. It keeps the ?variable= values for all variable. If you find any bug please report.

== Screenshots ==
1. Screenshot-1.png

== Changelog ==

= 1.0.9 =
* Confirmed WordPress 6.4.3 compatibility

= 1.0.8 =
* Confirmed WordPress 6.0.2 compatibility

= 1.0.7 =
* Confirmed WordPress 5.8.1 compatibility

= 1.0.6 =
* Confirmed WordPress 5.5.3 compatibility

= 1.0.5 =
* Confirmed WordPress 5.2.2 compatibility

= 1.0.4 =
* Confirmed WordPress 5.0 compatibility

= 1.0.3 =
* Confirmed WordPress 4.9.4 compatibility

= 1.0.2 =
* Confirmed WordPress 4.5 compatibility
* Changed base function name from 'wp_permalink_redirect' to 'wp_redirect_permalink'

= 1.0.1 =
* Fixed typo in Plugin name and URI

= 1.0 =
* This is the first version of the WP Redirect Permalink

== Upgrade Notice ==

= 1.0.8 =
Confirmed WordPress 6.0.2 compatibility