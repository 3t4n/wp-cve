=== Last-Modified and If-Modified-Since Headers ===
Contributors: zubovd
Tags: Last-Modified,If-Modified-Since, headers
Requires at least: 5.2.4
Tested up to: 5.7.1
Requires PHP: 5.3+
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Last-Modified anв support If-Modified-Since Headers

== Description ==
This plugin adds the 'Last-Modified' header to each post and returns the '304 Not Modified' header to the client without a body in the response if he sent the 'If-Modified-Since' header and the post has not changed since that date.

== Installation ==
1. Upload 'Last-Modified and If-Modified-Since Headers' folder to the 'plugins' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
