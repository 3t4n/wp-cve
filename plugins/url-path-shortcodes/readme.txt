=== URL & Path Shortcodes ===
Contributors: gilbitron
Tags: url, path, shortcode, shortcodes
Requires at least: 3.4
Tested up to: 3.4.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a simple plugin that allows you to use common WordPress URL's and Paths in the post editor using shortcodes.

== Description ==

This is a simple plugin that allows you to use common WordPress URL's and Paths in the post editor using shortcodes.
Below is a list of available shortcodes:

* [home_url]
* [site_url]
* [admin_url]
* [network_home_url]
* [network_site_url]
* [network_admin_url]
* [content_url]
* [plugins_url]
* [wp_upload_dir]
* [get_template_directory_uri]
* [get_stylesheet_directory_uri]
* [get_stylesheet_uri]
* [get_theme_root_uri]
* [get_stylesheet_directory]
* [get_theme_root]
* [get_theme_roots]

Note that you can pass in parameters that are available to the corresponding WordPress functions. For example:

[home_url path="faq" scheme="https"]

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `wp-url-path-shortcodes` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Thats it. Start using the shortcodes in your WordPress editor.

== Changelog ==

= 1.0 =
Initial release.
