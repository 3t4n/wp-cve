=== Photoswipe for NextGEN Gallery ===
Contributors: gsenas
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=8HNE3583KAVEQ&lc=US&item_name=Guillermo%20Senas&amount=0%2e10&currency_code=EUR&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: Photoswipe, NGGallery, mobile, ipad, iphone, android, slideshow, media gallery, NextGEN gallery
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

NextGEN Gallery navigations adapted for iPhone, Android and Blackberry: pinch to zoom, swipe to navigate, auto-rotate. 

== Description ==

The default NextGEN gallery navigations (Shutter, Thickbox, etc...) fall short when using a mobile browser?
Now you can use Photoswipe, the best mobile image browser, just installing this plugin. The default gallery effect and configuration is kept when accessing from a desktop browser.

After activation you'll find a new Photoswipe submenu in the NextGEN Gallery menu, allowing you to configure how the plugin works.

Please note, I am not the developer, or related in any way with of the authors of [Photoswipe](http://www.photoswipe.com/ "Photoswipe webpage") or [NextGEN Gallery](http://wordpress.org/extend/plugins/nextgen-gallery/). This plugin is only intended to make Photoswipe integration with NextGEN Gallery dead simple.

If you find this plugin helpful, consider [donating](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=8HNE3583KAVEQ&lc=US&item_name=Guillermo%20Senas&amount=0%2e10&currency_code=EUR&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted "Donate link"), at least ten cents.

= Options =

The plugin is ready to be used with zero configuration, but to make testing easier, there are 3 configuration options:

* *Replace viewer with Photoswipe only on mobile browsers:*
This is the default behavior, and will replace the NextGEN Gallery Effect with Photoswipe only when a mobile browser is detected. 

* *Always replace viewer with Photoswipe:*
Useful for testing.

* *Never replace the viewer:*
Disables the plugin. Much like deactivating the plugin.

* *Disable additional viewers:*
In this section you can disable other supported image viewers, other than the NextGEN Gallery default ones.
Currently only Fancybox.

== Installation ==
STEP 0: Make sure you have the [NextGEN Gallery](http://wordpress.org/extend/plugins/nextgen-gallery/) plugin already installed and activated!

*Automatic Installation*

1. Download and install Photoswipe for NextGEN Gallery using the built in WordPress plugin installer.


*Manual Installation*

1. Download the zip file and unzip it.

2. Upload the ngg-photoswipe folder to your `/wp-content/plugins/` directory. Alternatively, use the Wordpress plugin install in `Plugins >> Add New >> Upload` to upload and install the zip file.

3. Activate the plugin through the `Plugins` menu in WordPress.

Done! Photoswipe will be used when accessing from a mobile browser. 

If you want to be sure, check your "Gallery" menu. A new Photoswipe section should be there.

== Frequently Asked Questions ==

= How is the standard effect (Shutter, Thickbox...) prevented when using Photoswipe? =

The javascript added when a mobile browser is detected does two things:

* a) Fire the Photoswipe viewer when you click on a image

* b) Remove the default additional HTML markup added in the "Link Code Line" of the Effects tab in NextGen Gallery Options

That way, the standard defined effect won't fire when Photoswipe is used. Please don't change the markup, or two viewers may fire at the same time.

If you absolutely need to change the "Link Code Line", change the ngg-photoswipe.js to remove the specific markup you add.
 
= Can I use WPTouch with this plugin? =

No, sorry, this plugin DOES NOT WORK WITH WPTOUCH.

= Will the image descriptions show? =

Yes, the description will show behind the title, if the image has one.

= Is there any menu page to change the default Photoswipe options? =

No, sorry. But you can edit the ngg-photoswipe.js file to achieve what you want.

= When will be my favorite viewer also adapted to use Photoswipe when using a mobile browser? =

When it's done.


== Screenshots ==

1. Plugin options - Choose when Photoswipe should be applied.
2. New Gallery submenu
3. Result 

== Changelog ==

= 1.2.1 =

Description fixed

= 1.2 =

Major bugfixing:

* Works on Wordpress 3.0, as stated in the readme
* Javascript errors fixed when showing pages with no galleries
* Photoswipe CSS & JS files no longer load on admin pages, preventing many related issues 

= 1.1 =

Option to disable more gallery viewers. Starting with Fancybox.

= 1.0.2 =

Wrestling with readme.txt format. Sorry.

= 1.0.1 =

Minor bugfix. Now Photoswipe icons show correctly.

= 1.0 =

* Hello world! Initial version.

== Upgrade Notice ==

Thanks for using this initial version.

