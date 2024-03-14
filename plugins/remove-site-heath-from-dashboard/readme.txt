=== Remove Site Health From Dashboard ===
Contributors: Fullworks
Donate link: https://ko-fi.com/wpalan
Tags:  dashboard widget, site health, sitehealth
Tested up to: 6.3
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Removes the Site Health from the Dashboard introduced in WP 5.4

== Description ==

If you manage multiple WordPress sites, you may find you get lots of calls about site health as this has been put right in front of your site owners eyes.
This is a good thing if it is your own site but no so good if you are getting calls from non technical clients.

So this plugin removes it.  Simple and as lightweight as any code snippet can be.

This is the code, if you don't want a plugin.

But if you manage multiple sites you probably do want a plugin so you can push it out in bulk via your management tool

`add_action(
 /**
  *   Remove Site Health from the Dashboard
  */
 	'wp_dashboard_setup',
 	function () {
 		global $wp_meta_boxes;
 		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
 	}
 );`

== Installation ==

Install like any plugin

== Frequently Asked Questions ==

= Are there any options? =

No! It is light weight.

= Can I buy you a drink? =

Sure, use the donate link

== Changelog ==

= 1.0 =
* The first and hopefully only release



