=== Sullivan Compatibility Plugin ===
Contributors: Anlino
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=anders%40andersnoren%2ese&lc=US&item_name=Free%20WordPress%20Themes%20from%20Anders%20Noren&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: compatibility, compat, sullivan,
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.0.4
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Compatibility plugin for the WordPress theme Sullivan (sullivan).

== Description ==

Compatibility plugin for the WordPress theme Sullivan. This plugin includes the custom post type Slideshows, which allows you to add slideshows to the blog home page and the shop home page.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. The "Slideshows" menu item will now appear in your admin dashboard. Here, you can add slides and select which area they should be displayed in.

If you have WooCommerce installed, both the "Shop" and the "Blog" slideshow areas will be available. If WooCommerce is not installed, only the "Blog" area will be listed.

== Changelog ==

= 1.0.4 =
* Updated the text domain to match the WordPress.org slug, rather than the plugin filename.

= 1.0.3 =
* Fixed the trunk folder being included in the latest release tag folder by mistake

= 1.0.2 =
* Updated text domain to match plugin folder, moved load_plugin_textdomain() to the plugins_loaded action

= 1.0.1 =
* Updated all functions to be pluggable.
* Updated with the proper readme.txt format.

= 1.0 =
* First version.