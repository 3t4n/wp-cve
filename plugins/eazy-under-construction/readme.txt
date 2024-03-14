=== Eazy Under Construction ===
Contributors: r0bsc0tt
Tags: construction, under construction, private, preview, security, coming soon
Requires at least: 2.7
Tested up to: 4.9.1
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a 'Coming Soon' page that will show for all users who are not logged in

== Description ==

Creates a 'Coming Soon' page that will show for all users who are not logged in. Useful for developing a site on a live server, without the world being able to see it. This is an ad-free fork of the great Under Construction plugin by Jeremy Massel.

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If you want to change the look of the page that is displayed, click Settings->Under Construction and change the settings there.

== Frequently Asked Questions ==

= I'm finished with it, and disabled the plugin, but the "under construction" message is still showing up! =
If you've disabled the plugin, it won't show anything anymore. To be extra super-sure, try deleting the plugin files. Usually, though, the issue is that you're seeing a cached version of the page. Try force-refreshing your browser, and then try clearing your cache on the server and force refreshing again. If you have a caching plugin like W3 Total Cache, make sure you clear that too!

= I can't see the under construction page! =
As long as you're logged in, you won't be able to see it. That's a feature! This way, while you're logged in you can work as usual. To preview what it looks like, either a) log out, or b) try viewing it in another browser

= What kind of HTML can I put in? =
You enter the contents of the entire HTML file. You can include inline styles, or links to external style sheets and external images.


== Changelog ==

= 1.0 =
* First version

== Upgrade Notice ==

= 1.0 =
* First version

== Screenshots == 
1. The default page that is displayed (this can be overridden)
2. The editing screen with the default page selected
3. The editing screen with the custom text option selected
4. The editing screen with the custom HTML option selected
