=== Speed Up - Lazy Load ===
Contributors: nigro.simone
Donate link: http://paypal.me/snwp
Tags: lazyload, lazy load, lazy loading, optimize, front-end optimization, performance, images, iframe, thumbnail, thumbnails, avatar, gravatar, speed, web performance optimization, wordpress optimization tool
Requires at least: 3.5
Tested up to: 6.0
Stable tag: 1.0.25
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Improves load speed of page and save the bandwidth.

== Description ==

This plugin, implementing "Lazy Load" technique, avoids download of the pictures and iframe that are not displayed on the screen (for example: images in the bottom of the page) until the user will display them. This improves load speed of page and save the bandwidth.

Configurations are not required! You just have to install it and after the plugin does it all, none further action it's required.
This plugin is very light: only 5 kb.


== Installation ==

1. Upload the complete `speed-up-lazy-load` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How can i deactivate Lazy Load on some areas (eg. page, widget)? =

Simply add a string `no-lazy-area` in the content (eg. in a content of the page write `<!-- no-lazy-area -->`).

= How can i deactivate Lazy Load on some images? = 

Simply add a string `no-lazy` in you IMG/DIV tag (eg. in the class, alt, title or file name).

== Changelog ==

= 1.0.25 =
* Tested up to Wordpress 6.0

= 1.0.24 =
* Tested up to Wordpress 5.9

= 1.0.23 =
* Tested up to Wordpress 5.7

= 1.0.22 =
* Tested up to Wordpress 5.5

= 1.0.21 =
* Add support for "skip-lazy" stop word (fix)

= 1.0.20 =
* Add support for "skip-lazy" stop word

= 1.0.19 =
* Tested up to Wordpress 5.3

= 1.0.18 =
* Tested up to Wordpress 5.2

= 1.0.17 =
* Add lazy load for div with a inline background

= 1.0.16 =
* Small fix

= 1.0.15 =
* Tested up to Wordpress 4.9
* Disable lazy loading for feed
* Improve readme.txt

= 1.0.14 =
* Add italian

= 1.0.13 =
* Tested up to Wordpress 4.7

= 1.0.12 =
* Improve jQuery SpeedUpLazyLoad plugin

= 1.0.11 =
* Little fix with script enqueue

= 1.0.10 =
* Little fix

= 1.0.9 =
* Improve mobile support

= 1.0.8 =
* Improve readme.txt

= 1.0.7 =
* Add support to iframe
* Improved plugin script load (now async)
* Improve readme.txt

= 1.0.6 =
* Improved plugin script load

= 1.0.5 =
* Improved image search
* Improved plugin script load

= 1.0.4 =
* Removed double call to the "the_content" filter

= 1.0.3 =
* Fix readme.txt

= 1.0.2 =
* Fix readme.txt

= 1.0.1 =
* Improve readme.txt

= 1.0.0 =
* Initial release.