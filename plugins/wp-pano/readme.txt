=== Plugin Name ===
Contributors: Alexey Yuzhakov
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tour3d%40ya%2eru&lc=GB&item_name=WP%2dPano&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted
Tags: wp-pano, pano, panorama, krpano, vtour, virtual tour, 3d panorama, html5 panorama, interactive tour, panorama viewer, real estate tour, responsive panorama, webgl panorama
Requires at least: 4
Tested up to: 5.7
Stable tag: 1.17
License: GPLv3
License URI: http://opensource.org/licenses/GPL-3.0

The WP-Pano is the WordPress plugin for content management of your krpano projects.

== Description ==

The WP-Pano is the WordPress plugin for content management of your krpano projects. The plugin gives you possibility to inserting content such text, galleries, videos into your panoramas and helps to edit them easily. Use power and flexibility of the WordPress cms to create your virtual tours.

note: the wp-pano is not a panorama viewer for your virtual tour. It working as a bridge for a wordpress contents and between the krpano panorama viewer.

= List of features =

* Allow to use PHP, JavaScript, and HTML to create any content (photo galleries, video and audio, custom forms, iframe, and much more)
* Flexible configuration of window templates
* Setup hotspots style
* Support custom post types. Easy way is to use the plugin Custom Post Type UI
* Good compatibility with other WordPress plugins
* Compatible with Polylang and qTranslate X plugins for translating content into any languages

= Docs & Support =

More detailed information about WP-Pano on [wp-pano.yuzhakov.org](https://wp-pano.yuzhakov.org)

= WP-Pano needs your support =

If you enjoy using WP-Pano and find it useful, please consider [making a donation.](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tour3d%40ya%2eru&lc=GB&item_name=WP%2dPano&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted)
Your donation will help encourage and support the plugin's continued development and better user support.

== Installation ==

1. Upload the wp-pano directory to the '/wp-content/plugins/' directory in your WordPress installation
2. Upload your vtour
3. Activate the plugin through the 'Plugins' menu
4. Setup path through the 'Options'->'WP-Pano' menu

= very important =

* add attribute vtour_name="unique_name" into the krpano root tag.

For more information please read [documentation](http://wp-pano.yuzhakov.org/documentation/)

== Screenshots ==

1. Setup vtour
2. Add post to the panorama
3. Hotspots in the panorama
4. Video example
5. Gallery example
6. Google Street View example

== Changelog ==

= 1.17 = 
* add mwheel:false for admin edit pano

= 1.16 = 
* update depricated jquery functions

= 1.15 = 
* fix bugs

= 1.14 = 
* fix the bug when no posttype is checked

= 1.13.9 =
* drag and drop confirmation

= 1.13.8 =
* add drag and drop hotspots feature

= 1.13.7 =
* fix bugs with update hotspots

= 1.13.6 =
* update plugin readme

= 1.13.5 =
* add js event when hotspots has loaded

= 1.13.4 =
* update plugin website url

= 1.13.3 =
* fix $_SERVER['DOCUMENT_ROOT'] ending slash

= 1.13.2 =
* the wp-pano.xml file include to your xml file automaticaly

= 1.13.1 =
* Fix SITE_HOMEPATH incorrect path

= 1.13.0 =
* Add user scripts on open and close content events
* Fix bugs

= 1.12.4 =
* fix get a pano path in the flash mode

= 1.12.3 =
* fix problem with visible a hotspot content

= 1.12.2 =
* fix bug with krpano vars

= 1.12.1 =
* fix problem with old php version

= 1.12 =
* fix problem with display a content in the flash mode

= 1.10 beta =
* Added the ability to save custom settings, regardless of the updates
* Code optimization
* fix problem with add hotspots

= 1.08 beta =
* fix bug compatibility with krpano 1.19-pre4

= 1.07 beta =
* fix get scene name bug

= 1.06 beta =
* add new template
* fix settings page errors
* fix error with abs path
* refactoring code

= 1.05 beta =
* fix: error query posts

= 1.04 beta =
* fix: check vtour path
* fix: WPPANOPATH error

= 1.0 beta =
* Initial release