=== Announcement Bar ===
Contributors: austyfrosty
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VDD3EDC28RAWS
Tags: admin, bar, announcement bar, announcement, stats, announcement bar, announcement bar
Requires at least: 3.2
Tested up to: 4.2
Stable tag: trunk

A fixed position (header) HTML with jQuery drop-down announcement bar using Custom Post Types.

== Description ==

This plugin adds a jQuery file that will position a fixed bar at the top of your browser screen to show announcements (controlled by a custom post type [CPT]) on each page load. Built with simple HTML and javascript.

Upon installation, you can choose the prefix for your post type. Be sure to choose wisely, because once you publish your first post this can't be changed. If you are having problems with redirects visit your *permalinks* page to flush your rewrite rules by visiting your permalinks settings page (no save needed).

**New**: A cookie is added and deleted when you toggle the bar open and closed. Depending on the position of the bar when you leave the page is where it will be when you re-visit the site.

For question please visit my blog @ [http://austin.passy.co](http://austin.passy.co/wordpress-plugins/announcement-bar/)

**The Link**
Please be aware when publishing a post, as of right now, you have to fill in the content with non-html text and add a link into the link field. This link is really the permalink and is what gets counted when clicked on. It will also revert to the home page if missing, and not count against the counter. If you're getting 404 issues when visiting the permalink page, please refer to the FAQ section.

== Installation ==

Follow the steps below to install the plugin.

1. Upload the `announcement-bar` directory to the /wp-content/plugins/ directory. OR click add new plugin in your WordPress admin.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Announcement Bar settings to edit your settings.

== Future Releases ==

This plugin is brand new and has some future planes.

1. <del>Saved bar position with cookie settings.</del>
2. Post and page per post ID.
3. Per post on/off toggle.
4. ....

== Frequently Asked Questions ==

= I am getting a 404 error? =
Please visit your permalinks settings page after activating the plugin.

== Screenshots ==

1. The settings page.

2. The edit post page.

3. The add post page.

4. The drop-down bar.

== Changelog ==

= Version 0.4.1 (04/23/15)

* Fix unexpected error during activation.

= Version 0.4 (12/17/14)

* LOTS of code cleanup.
* Fix basic PHP notices.
* Ready for WordPress 4.1.

= Version 0.3.2 (12/3/12)

* Updated Dashboard
* Removed PHP4 support

= Version 0.3.1.1 (02/16/12)

* Updated `register_uninstall_hook`.

= Version 0.3.1 (11/8/11)

* Feeds updates.
* WordPress 3.3 check

= Version 0.3 (9/8/11) =

* Dashboard fix.
* Other stuff I can't remember.

= Version 0.2.2 (6/23/11) =

* [BUG FIX] An error in the dashboard widget is causing some large images. Sorry. Always escape.

= Version 0.2.1 (6/13/11) =

* Russian Translation files added (thanks to Elvis: http://wp.turkenichev.ru/).

= Version 0.2 (6/1/11) =

* Complete overhaul.
* [NEW] Now saves bar position with cookies.
* Added backwords compatibility for jQuery < 1.6.x.
* [NEW] Add your own custom CSS.

= Version 0.1.5 (5/16/11) =

* Updated `.attr()` to `.prop()` for jQuery 1.6.x. New minimum install requirements.

= Version 0.1.4 (3/30/11) =

* Dashboard widget updated.

= Version 0.1.3 (2/24/11) =

* Removed javscript link causing hang-ups.

= Version 0.1.2 (2/9/11) =

* Updated the feed parser to comply with deprecated `rss.php` and use `class-simplepie.php`

= Version 0.1.1 (12/23/10) =

* Update CSS `z-index` for WordPress 3.1 admin-bar.

= Version 0.1 (12/21/10) =

* Readme.txt file updated a bit. `POT` file added to languages.

= Version 0.09 (12/20/10) =

* `post_type` **announcementbar** incorect on post-type.php.

= Version 0.08 (12/20/10) =

* Bug fixes.

= Version 0.07 (12/20/10) =

* Plugin name changed.

= Version 0.06 (12/19/10) =

* Fixed issue with `is_admin_bar_showing` to work only with WP 3.1 or greater.
* Couldn't find `wp-load.php` to create dynamic CSS.

= Version 0.05 (12/19/10) =

* Fixed issue with `slug` stuck in readonly setting.

= Version 0.04 (12/19/10) =

* Removed scripts and styles from the admin.
* Removed filter, used during testing.

= Version 0.03 (12/19/10) =

* Readme update.
* Added Custom Post Type.
* Added options panel.
* Added jQuery & CSS.

**TODO**

* Check rewrite rules flushing proper on *slug* change.
* Better stats.

= Version 0.02&alpha; (12/15/10) =

* Readme update.

== Upgrade Notice ==

= 0.2 =
Complete plugin overhaul, now adds cookies to remember bar position.

= 0.09 =
Post Type fix, columns missing, and not showing on front page.
