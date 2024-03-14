=== Mojo Under Construction ===
Contributors: mojowill
Donate link: http://www.mojowill.com/
Tags: construction, under construction, private, preview, security, coming soon
Requires at least: 2.7
Tested up to: 4.7.1
Stable tag: trunk

Easily create a "Coming Soon" page for your WordPress site.

== Description ==

Easily create a "Coming Soon" page for your WordPress site. Perfect for hiding your development of a site on a live server from the world.

This is a fork of an original plugin by Jeremy Massel (https://wordpress.org/plugins/underconstruction/), which unfortunately was passed onto a new developer who felt the need to bombard users with advertising messages! This version is clean and not ad supported!

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. If you want to change the look of the page that is displayed, click Settings->Under Construction and change the settings there.

== Frequently Asked Questions ==

= Wait, how is this different form the original? =
I've been using the excellent plugin from Jeremy for years. However, it looks like it recently (v1.13) got taken on by a nother developer who simply added some ads and bumped the version number with no way to remove the ad. That's not how I work. SO i've forked the plugin, refactored a lot of the code to meeting modern coding standards and follow the WordPress coding standards.

= I'm finished with it, and disabled the plugin, but the "under construction" message is still showing up! =
If you've disabled the plugin, it won't show anything anymore. To be extra super-sure, try deleting the plugin files. Usually, though, the issue is that you're seeing a cached version of the page. Try force-refreshing your browser, and then try clearing your cache on the server and force refreshing again. If you have a caching plugin like W3 Total Cache, make sure you clear that too!

= I can't see the under construction page! =
As long as you're logged in, you won't be able to see it. That's a feature! This way, while you're logged in you can work as usual. To preview what it looks like, either a) log out, or b) try viewing it in another browser

= What kind of HTML can I put in? =
You enter the contents of the entire HTML file. You can include inline styles, or links to external style sheets and external images.

= I have an idea for your plugin! =
That's great. I'm always open to user input, and I'd like to add anything I think will be useful to a lot of people. Visit the homepage for this plugin and leave a comment, and I'll add the functionality as soon as I can.

= I found a bug! =
Oops. That's sure awkward. If you find a problem with this plugin that you can reproduce, if you wouldn't mind leaving a message on the homepage for this plugin with how you made it break, I'd really like to try and fix it! Also, this is incompatible with a couple plugins out there, specifically ones that change the default login url.

= This plugin has helped me a lot, how can I support it? =
I've had a few people ask me this. If you like it, please go to WordPress.org and rate it! Then more people can enjoy it. If you REALLY like it, you can always buy me a coffee. :) There's a donate link on my site.

= You didn't answer my question here =
Sorry, I get a lot of questions. But visit the homepage for this plugin and leave me a comment. They go right to my inbox, and well I might not be able to for a few days, I promise I'll get back to you.

== Changelog ==
= 1.1.2 =
* Fixing Whitelist check (thanks to 7o7marketing)

= 1.1.1 =
* Fixing direct file access

= 1.1.0 =
* Fixing potential security issues.

= 1.0.1 =
* Fixing broken Javascript

= 1.0.0 =
* First forked version, takes original plugin at v1.13 and removes the advertising messaging.
* WordPress Coding Standards.


== Screenshots == 
1. The default page that is displayed (this can be overridden)
2. The editing screen with the default page selected
3. The editing screen with the custom text option selected
4. The editing screen with the custom HTML option selected
