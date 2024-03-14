=== WP Custom Login ===
Contributors: Ninos Ego
Tags: login, custom login, login page, custom page, header, footer
 
Requires at least: 3.2.1
Tested up to: 4.6
Stable tag: 1.4.9

Adding the header and footer to your login page.

== Description ==

Designed for users which want to include the header and footer to the login page. You can also change the design. Just create a wp-custom-login.css in your theme directory.


== Installation ==

This section describes how to install the plugin and get it working.

It can be installed in two ways, firstly by downloading the plugin from the Wordpress directory website or by via the Wordpress admin page for adding a new plugin

Downloading from Wordpress Website

1. Download the plugin from the wordpress plugin directory
2. Unzip the plugin
3. Upload `/wp-custom-login/` directory to the `/wp-content/plugins/` directory
4. Activate the plugin through the 'Plugins' menu in WordPress
5. Create a custom-login.css in your theme directory
6. Use the wp-custom-login action hooks
* wp_custom_login_header_before
* wp_custom_login_header_after
* wp_custom_login_footer_before
* wp_custom_login_footer_after

Using the Wordpress Admin page for installing

1. Go to the admin page and select the 'Plugins' menu, using the 'Add new' menu item
2. Search for 'WP Custom Login'
3. Select install for the plugin 'WP Custom Login' by Ninos Ego
4. Activate the plugin through the 'Installing Plugin' page in WordPress
5. Create a wp-custom-login.css in your theme directory
6. Use the wp-custom-login action hooks
* wp_custom_login_header_before
* wp_custom_login_header_after
* wp_custom_login_footer_before
* wp_custom_login_footer_after


== Screenshots ==

1. Custom login page.


== Changelog ==

= 1.4.9 =
Fixed: JavaScript error - Element is null

= 1.4.8 =
Compatible with Wordpress 4.1

= 1.4.7 =
Compatible with Wordpress 4.0
Fixed: JavaScript error

= 1.4.6 =
Deactivated for login popup

= 1.4.5 =
Compatible with Wordpress 3.9

= 1.4.4 =
Compatible with Wordpress 3.8

= 1.4.3 =
Compatible with Wordpress 3.7

= 1.4.2 =
Compatible with Wordpress 3.6

= 1.4.1 =
Added index.php

= 1.4.0 =
Stylesheet is loaded only if it exists

= 1.3.2 =
This version of the plugin is working without jQuery. Version 1.3.2 should be compatible with all themes

= 1.3.1 =
There was a litte error. Version 1.3.1 should now work with all jquery compatible themes

= 1.3.0 =
This version is removing following files from the login head:
wp-admin-css
colors-fresh-css
Maybe some themes need this files on login page, so you're welcome for feedback

= 1.2.0 =
Added 4 action hooks:
wp_custom_login_header_before
wp_custom_login_header_after
wp_custom_login_footer_before
wp_custom_login_footer_after

= 1.1.0 =
Because of conflicts with other plugins rename the custom-login.css to wp-custom-login.css

= 1.0.0 =
First edition