=== Wistia WordPress Plugin ===
Contributors: wistia
Tags: wistia, oembed, video, embed
Requires at least: 2.9.1
Tested up to: 5.1.1
Stable tag: 0.10

Enables all Wistia embed types to be used in your WordPress blog.

== Description ==

Wistia's embed codes are designed to be very durable, but WordPress has a
history of being particularly troublesome. This plugin adds oEmbed support so
that pasting a link to the video's page in Wistia will embed the video. The
video's URL must be by itself on its own line for this to work.

As of version 0.6 of this plugin, it is recommended that you check
"Use oEmbed?" under Advanced Options when generating your embed code.

As of version 0.8 of this plugin, the legacy "Anti-Mangler" feature is an
option that is turned off by default.

See the Wistia documentation for more:
http://wistia.com/doc/wordpress#using_the_oembed_embed_code

== Automatic Installation ==

1. In Admin Dashboard, go to Plugins > Add New.
2. Search for "Wistia WordPress Plugin".
3. Find the plugin and click "Install".

== Manual Installation ==

1. Make a 'wistia-wordpress-oembed-plugin' directory in '/wp-content/plugins/'.
2. Upload all files in the repository to the
'/wp-content/plugins/wistia-wordpress-oembed-plugin/' directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 0.10 =
* Update implode function for php 8 compatibility

= 0.9 =
* Add *.wistia.net as a recognized domain

= 0.8 =
* Make Anti-Mangler an option and turn it off by default for new installs.

= 0.7 =
* Try to fix an issue with string concatenation in certain environemnts.

= 0.6 =
* Changed oembed regexp to properly detect new Wistia oembed URLs.

= 0.5.1 =
* Fixed a debug error complaining about `extended_valid_elements`
undefined in `add_valid_tiny_mce_elements`

= 0.5 =
* Updated the oembed endpoint for Wistia
* Updated the regexes for matching Wistia video URLs to the latest recommended in the doc

= 0.4 =
* Added support for the new Playlist embed structure

= 0.3 =
* Added support for all SuperEmbed style Wistia embeds

= 0.2 =
* Added support for SuperEmbed style oEmbeds

= 0.1 =
* Initial release
