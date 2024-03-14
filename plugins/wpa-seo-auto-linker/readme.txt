=== SEO Auto Linker ===
Contributors: arjanolsder
Plugin Name: SEO Auto Linker
Plugin URI: https://www.digishock.com
Tags:  post, posts, pages, tags, categories, comments, links, seo, google, automatic, link, cornerstone, RSS
Author URI: https://www.digishock.com
Author: WP Assist
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5LRFCEJLZQW7A (digishock)
Requires at least: 5.6
Tested up to: 6.0
Stable tag: 1.5.2
Version: 1.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SEO Auto Linker assists in creating cornerstone SEO content. This is not a full replacement for SEO plugins.

== Description == 

Want to automatically create cornerstone content? WPA SEO Auto Linker helps get this done. Simply create a new keyword or a new phrase. The system will link that keyword or phrase to your chosen dofollow URL. Through the settings, it is easy to finetune the workings of this plugin. For performance, it is best to make use of a caching engine.

"Using this plugin didn't just help define cornerstone content in our SEO strategy, it also increased pageviews by 18%. The average visitor spends 13 seconds more on our website." - Roelof van Doorn, technical editor at GadgetGear.nl

While our plugin has been without support for three years, we have seen a lot of similar plugins moving in. Please note we will not be adding fancy interfaces or click tracking. The reason is we want to remain the fastest tool on the market. Click tracking takes a heavy hit on your database while creating a smooth graphical interface will lead to code bloat and the security risks that come with maintaining huge heaps of code. We just don't want that.

== Installation ==

1. Upload the complete wpa-seo-auto-linker folder to your /wp-content/plugins/ folder.
2. Go to the Plugins page and activate the plugin.
3. Use the WPA SEO Auto Linker settings page under settings to change the settings for WPA SEO Auto Linker.
4. Enjoy the automatically inserted links.

Migrating from SEO Auto Links?
Just deactivate the original plugin. The current version uses the exact same settings table in the database. This means you don't loose your list of URL's you have built. We will maintain this functionality till July 2018. Do create a back-up before installation. Better safe than sorry.

== Frequently Asked Questions ==

= Where are the settings? =

You can find WPA SEO Auto Linker under the WordPress settings menu. If you are also using Yoast, SEOpress Pro, Rankmath, All in One SEO, Schema Pro or SEOquake, you will find the settings in their menu's too.

= What page builders are supported? =

* Gutenberg
* Elementor (Pro)
* WP Bakery
* Divi
* Zion Builder

Oxygen Builder support is under development. Contact us if you need anything else.

= Is this plugin compatible with Yoast? =

Yes is is, just like with other SEO plugins. We are working on menu integration so you have our tool sitting next to Yoast's options.

= Is this plugin compatible with SEOpresss Pro? =

Yes is is, just like with other SEO plugins. We are working on menu integration so you have our tool sitting next to SEOpro's options.

= Is this plugin compatible with Woocommerce? =

Support has not been tested. After the Gutenburg update, we will put our resources on offering Woocommerce support. Meanwhile, leave your experience in the support forum. (https://wordpress.org/support/topic/woocommerce-382/)

== Screenshots ==

1. Plugin settings page

== Changelog == 

= In development =
* Code performance improvements
* Nofollow option per link instead of everything
* Custom ALT tag
* updating translation support
* Integration with YOAST
* Integration with SEOpress
* Open in new tab option
* Oxygen Builder compatilibity
* Smarter cache handling

=1.5 22-06-2022 =
* Bugfix

=1.5 22-06-2022 =
* PHP 8.0 compatibility
* Cleaned up PHP notices and warnings as much as possible
* Removed option to open links in new tab, added to development pipeline
* Ignore keywords that are already inside an A tag
* Ignore text in image captions (beta)
* Translation support
* Add our settings to Yoast, SEOpress Pro, Rankmath, All in One SEO, Schema Pro and SEOquake

= 1.3 10-06-2019 =
* Applied a patch from @ravipatel to support special characters like used in the Greek language. Thanks!

= 1.2 07-06-2019 =
* This update is a version bump. We are now restarting active development of this plugin.

= 1.1 05-04-2018 =
* Tested for compatibility Wordpress 4.9.5
* Removed branding on top of the settings page
* Simplified styling to better fit Wordpress defaults
* Bugfix: deprecated user level management has been replaced

= 1.0 14-01-2018 =
* Bugfix for adding rel=nofollow
* Bugfix for adding target=_blank

= 0.2 06-01-2018 =
* Security improvements to prevent XSS attacks through the codebase and avoid users corrupting the database
* Visual style update
* Changed order of the admin form to reflect most used areas
* Cleaning up deprecated code

= 0.1 05-01-2018 =
* First release

== Credits ==

WPA SEO Auto Linker plugin is based on the SEO Auto Links 0.5 plugin by Maarten Brakkee.
https://wordpress.org/plugins/seo-auto-links/

The SEO Auto links plugin is based on the SEO Smart Links 2.7.6 plugin by Vladimir Prelovac:
https://wordpress.org/plugins/seo-automatic-links/

Inspiration for SEO Smart Links originated from the Autolink plugin by Chris Lynch
http://www.planetofthepenguins.com/

== License ==

This file is part of WPA SEO Auto Linker.

WPA SEO Auto Linker is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

WPA SEO Auto Linker is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with WPA SEO Auto Linker. If not, see <http://www.gnu.org/licenses/>.
