=== SoundCloud Shortcode ===
Contributors: indextwo, theophani, jowagener, por_
Tags: soundcloud, shortcode
Requires at least: 3.1.0
Tested up to: 6.4.3
Stable tag: 4.0.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SoundCloud Shortcode plugin for WordPress

== Description ==

This plugin converts all SoundCloud shortcodes into embeddable SoundCloud players. It works for any SoundCloud track, playlist, user, or group. Once you install this plugin, it will work for any of your WordPress posts & pages. I mean, *sure* you could use oEmbed or the snappy new Gutenberg editor to simply paste in a SoundCloud URL; but we like to keep things *old-school* 😎

A simple example:

`[soundcloud]http://soundcloud.com/forss/flickermood[/soundcloud]`

**More Options**

SoundCloud Shortcodes support these optional parameters:

* `width`
* `height`
* `params`

The `params` parameter passes additional options to the SoundCloud embeddable player. You can find a full list on the SoundCloud Developers pages: https://developers.soundcloud.com/docs/api/html5-widget

An example of a track that starts playing automatically, with hot-pink controls:

`[soundcloud params="auto_play=true&color=#F368E0"]http://soundcloud.com/forss/flickermood[/soundcloud]`

== Installation ==

1. Upload `soundcloud-shortcode` to your plugins directory or install it from the WordPress Plugin Repository
2. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 4.0.2 =
* Updated sanitization of potential inputs from both admin and directly within shortcode
* Minor coding-standards tweaks and normalization
* Updated `Supports` version

= 4.0.1 =
* Bumped minor version number, removing `trunk` as it's no longer supported
* Added `Requires PHP` version
* Updated some option descriptions
* Updated `Update URI`

= 4.0.0 =
* Hefty sanitization overhaul to address vulnerability raised in CVE-2023-34018
* Major refactor of code, including removing Flash widget option & addressing miscellaneous bugs
* Removed outdated tests
* Added several new default options to reflect available SoundCloud widget parameters
* Updated language on settings page to make options clearer
* Redesigned settings page, now with a shiny new logo

= 3.1.0 =
* Update ternary operator to be compatible with PHP8
* Added `soundcloud_shortcode_options` filter
* Update readme to reflect SoundCloud docs & API options
* Added icons & banners

= 3.0.2 =
* Always load embeds over https

= 3.0.1 =
* Minor copy updates in readme.txt

= 3.0.0 =
* Make visual player the default player (option to disable in settings)

= 2.3.2 =
* Add developer documentation
* Update README

= 2.3.1 =
* Add support for permalinks in HTML5 shortcode

= 2.3.0 =
* Don’t use oEmbed anymore because of various bugs.
* Standard http://soundcloud.com/<user> permalinks will always return the flash widget. Use the widget generator on the website to get an API url.

= 2.2.0 =
* Improved default options support

= 2.1.0 =
* Integrate oEmbed

= 2.0.0 =
* HTML5 Player added as the default player, with Flash as an option and fallback for legacy URL formats.

= 1.2.1 =
* Removed Flash fallback HTML

= 1.2.0 =
* Added options page to allow blog-wide global default settings.

= 1.1.9 =
* Fix to support resources from api.soundcloud.com
* Security enhancement. Only support players from player.soundcloud.com, player.sandbox-soundcloud.com and player.staging-soundcloud.com

= 1.1.8 =
* Bugfix to use correct SoundCloud player host

= 1.0 =
* First version
