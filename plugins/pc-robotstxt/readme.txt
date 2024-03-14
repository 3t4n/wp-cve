=== Virtual Robots.txt ===
Contributors: Marios Alexandrou
Donate link: https://infolific.com/technology/software-worth-using/robots-txt-plugin-for-wordpress
Tags: robots, robots.txt, robot, crawler
Requires at least: 5.0
Tested up to: 6.4.2
License: GPLv2 or later

Virtual Robots.txt automatically creates a robots.txt file for your site. Your robots.txt file can be easily edited from the plugin settings page.

== Description ==

Virtual Robots.txt is an easy (i.e. automated) solution to creating and managing a robots.txt file for your site. Instead of mucking about with FTP, files, permissions ..etc, just upload and activate the plugin and you're done.

By default, the Virtual Robots.txt plugin allows access to the parts of WordPress that good bots like Google need to access. Other parts are blocked.

If the plugin detects an existing XML sitemap file, a reference to it will be automatically added to your robots.txt file.

== Installation ==

1. Upload pc-robotstxt folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Once you have the plugin installed and activated, you'll see a new Robots.txt menu link under the Settings menu. Click that menu link to see the plugin settings page. From there you can edit the contents of your robots.txt file.

== Frequently Asked Questions ==

= Will it conflict with an existing robots.txt file? =

If a physical robots.txt file exists on your site, WordPress won't process any request for one, so there will be no conflict.

= Will this work for sub-folder installations of WordPress? =

Out of the box, no. Because WordPress is in a sub-folder, it won't "know" when someone is requesting the robots.txt file which must be at the root of the site.

= Does this plugin modify individual posts, pages, or categories? =

No it doesn't.

= Why does the default plugin block certain files and folders? =

By default, the virtual robots.txt is set to block WordPress files and folders that don't need to be accessed by search engines. Of course, if you disagree with the defaults, you can easily change them.

== Changelog ==

= 1.10 =
* Fix to prevent the saving of HTML tags within the robots.txt form field. Thanks to TrustWave for identifying this issue.

= 1.9 =
* Fix for PHP 7. Thanks to SharmPRO.

= 1.8 =
* Undoing last fixes as they had unintended side-effects.

= 1.7 =
* Further fixes to issue with newlines being removed. Thanks to FAMC for reporting and for providing the code fix.
* After upgrading, visit and re-save your settings and confirm they look correct.

= 1.6 =
* Fixed bug where newlines were being removed. Thanks to FAMC for reporting.

= 1.5 =
* Fixed bug where plugin assumed robots.txt would be at http when it may reside at https. Thanks to jeffmcneill for reporting.

= 1.4 =
* Fixed bug for link to robots.txt that didn't adjust for sub-folder installations of WordPress.
* Updated default robots.txt directives to match latest practices for WordPress.
* Plugin development and support transferred to Marios Alexandrou.

= 1.3 =
* Now uses do_robots hook and checks for is_robots() in plugin action.

= 1.2 =
* Added support for existing sitemap.xml.gz file.

= 1.1 =
* Added link to settings page, option to delete settings.

= 1.0 =
* Initial release.
