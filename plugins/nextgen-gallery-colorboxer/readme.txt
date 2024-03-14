=== NextGEN Gallery ColorBoxer ===
Contributors: Mark Jeldi
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HRACRNYABWT7G
Author URI: http://www.markstechnologynews.com
Tags: nextgen gallery, nextgen, nextgen gallery colorboxer, nextgen gallery plugins, nextgen gallery addons, nextgen gallery colorbox, colorbox, colorbox plugin, colorbox lightbox, colorbox for wordpress, wordpress colorbox, wordpress optimization
Requires at least: 3.1.2
Tested up to: 3.3.2
Stable tag: 1.0
License: GPLv2

One-click ColorBox lightbox integration with NextGEN Gallery. Only loads when a gallery shortcode is present.

== Description ==

= NextGEN Gallery ColorBoxer =

NextGEN Gallery ColorBoxer automatically integrates the cool ColorBox lightbox effect with your NextGEN galleries, and only loads ColorBox's scripts and styles when a gallery shortcode is present, improving your site's page load speed.

Note: For optimization of NextGEN Gallery's scripts and styles, please see [NextGEN Gallery Optimizer](http://wordpress.org/extend/plugins/nextgen-gallery-optimizer/)

If you have any questions, suggestions, ideas or feedback, please email me at mark@markstechnologynews.com

= Key features: =
1. One-click install of the ColorBox lightbox to display your images in style.
2. Only loads ColorBox's scripts and styles when a gallery shortcode is present.
3. Helps improve your site's page load speed.

NextGEN Gallery Fancyboxer also includes a couple of compatibility fixes right off the bat, including:

1. ColorBox not working in IE6
2. Conflicts with the jQuery $ selector in ColorBox's invocation code

= Requirements: =

1. WordPress version 3.1 or later
2. NextGEN Gallery version 1.6.2 or later

== Installation ==

1. Upload `nextgen-gallery-colorboxer` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Proceed to the plugin settings page to complete installation.

== Frequently Asked Questions ==
 
= Is this plugin compatible with minification/caching tools? =

Yes. However the small, already minified ColorBox script must be excluded from combining/minification or it won't function. This is true of any lightbox script.

For WP Minify, simply add /wp-content/plugins/nextgen-gallery-colorboxer/colorbox/js/jquery.colorbox-min.js in its js file exclusion options and clear the cache.

For W3 Total Cache, do nothing. It doesn't auto-discover, so as long as you don't manually add the script, it won't be included.


= What version of NextGEN Gallery is this plugin compatible with? =

Any version since 1.6.2


== Screenshots ==

1. NextGEN Gallery ColorBoxer settings page.


== Changelog ==

= V1.0 - 25/05/2012 =
* Initial release on May 25th, 2012.


== Upgrade Notice ==
= Upgrades to follow... =
 