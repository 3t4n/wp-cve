=== Plugin Name ===
Contributors: wordpressdotorg
Tags: wordpress 3.0, memory limit
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: 0.1

If you are trying to update to WordPress 3.0 and you are frozen on "Downloading..." or seeing "Fatal error: Allowed memory size exhausted" errors, don't fear! Simply activate this plugin and try again.

== Description ==

If you are trying to update to WordPress 3.0 and you are frozen on "Downloading..." or seeing "Fatal error: Allowed memory size exhausted" errors, don't fear! Simply activate this plugin and try again.

You can remove this plugin once you have updated. You'll never need it again!

== Installation ==

If you experience this error, your site will still work, but you won't be running 3.0. Visit the plugin installer and look for 'Memory Bump' by the WordPress team, or download the plugin here and follow these steps:

1. Upload `memory-bump/memory-bump.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go install WordPress 3.0!

== Frequently Asked Questions ==

= Why is this plugin necessary? =

The size of the download package for WordPress 3.0 is a bit larger than previous versions, due to the merge with WordPress MU and the awesome Twenty Ten theme. WordPress needs a bit more memory to download the larger file, which this plugin handles. 

= Why doesn't it work? =

Your host may prevent PHP from increasing its own memory limit. Please contact your host. WordPress recommends at least 32 MB, but it may need a bit more during an automatic upgrade. This plugin bumps the theoretical limit to 256 MB, though we'd never need that much.

== Changelog ==

= 0.1 =
* Release.