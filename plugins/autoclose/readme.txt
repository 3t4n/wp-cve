=== Auto-Close Comments, Pingbacks and Trackbacks ===
Tags: autoclose, comments, pingback, trackback, revisions, spam, anti-spam
Contributors: webberzone, Ajay
Donate link: https://webberzone.com/donate/
Stable tag: 2.2.0
Requires at least: 5.6
Tested up to: 6.3
Requires PHP: 7.2
License: GPL v2 or later

Automatically close Comments, Pingbacks and Trackbacks. Manage and delete revisions.

== Description ==

Spammers target old posts in a hope that you won't notice the comments on them. Why not stop them in their tracks by just shutting off comments and pingbacks? [Auto-Close Comments, Pingbacks and Trackbacks](https://webberzone.com/plugins/autoclose/) let's you automatically close comments, pingbacks and trackbacks on your posts, pages and custom post types.

You can also choose to keep comments / pingbacks / trackbacks open on certain posts, page or custom post types. Just enter a comma-separated list of post IDs in the Settings page.

An extra feature is the ability delete post revisions or limit their number.


= Key features =

* Close (or open) comments on posts, pages, attachments and even Custom Post Types!
* Close (or open) pingbacks and trackbacks as well across all post types. You can also choose to delete them
* Schedule a cron job to automatically close comments, pingbacks and trackbacks daily
* Delete all post revisions or limit the number of revisions by post type


== Installation ==

= WordPress install =
1. Navigate to Plugins within your WordPress Admin Area

2. Click "Add new" and in the search box enter "autoclose"

3. Find the plugin in the list (usually the first result) and click "Install Now"

= Manual install =
1. Download the plugin

2. Extract the contents of autoclose.zip to wp-content/plugins/ folder. You should get a folder called autoclose.

3. Activate the Plugin in WP-Admin.

4. Goto **Settings &raquo; AutoClose** to configure


== Upgrade Notice ==

= 2.2.0 =
Comment counts updated; settings interface upgraded. Check settings on upgrade and the ChangeLog for details.


== Changelog ==

= 2.2.0 =

Release post: [https://webberzone.com/blog/auto-close-v2-2-0/](https://webberzone.com/blog/auto-close-v2-2-0/)

* Enhancements:
	* The comment count for the post will be updated when deleting Pingbacks/Trackbacks
	* Settings page now uses the latest version of the WebberZone Settings_API class

= 2.1.0 =

Release post: [https://webberzone.com/blog/auto-close-v2-1-0/](https://webberzone.com/blog/auto-close-v2-1-0/)

* Features:
	* New revisions tab with settings to control the number of revisions by post type
	* New button in Tools page to delete all revisions

= 2.0.0 =

* Features:
	* New Tools page with several buttons to open, close and delete comments, pingbacks and trackbacks. You can find the link in the Settings page under the main header
	* New button to delete all pingbacks and trackbacks in the Tools page
	* Activating the plugin on Multisite should upgrade settings from v1.x
	* Uninstalling the plugin on Multisite will delete the settings from all sites

* Enhancements:
	* Migrated options to the Settings API

* Modifications:
	* Main plugin file has been renamed to autoclose.php
	* Cron hook renamed from `ald_acc_hook` to `acc_cron_hook`

For older changes, refer to changelog.txt

== Screenshots ==

1. Autoclose Settings - General
2. Autoclose Settings - Comments
3. Autoclose Settings - Pingbacks/Trackbacks
4. Autoclose Settings - Revisions
5. Autoclose Tools


== Frequently Asked Questions ==

If your question isn't listed there, please create a new post at the [WordPress.org support forum](https://wordpress.org/support/plugin/autoclose). It is the fastest way to get support as I monitor the forums regularly. I also provide [premium *paid* support via email](https://webberzone.com/support/).
