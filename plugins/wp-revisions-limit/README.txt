=== WP Revisions Limit ===
Contributors: barragan
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4Z3XBJXBPKRW6
Tags: revision, revisions, admin, posts, post revisions, page, page revisions, performance, database, optimize, trash, clean
Requires at least: 3.6
Tested up to: 4.6.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Limit the number of revisions stored for your posts.
Keep your WordPress fast and your database clean!

== Description ==

Keep your WordPress **fast** and your database **clean**!

WordPress 3.6 allows users to control how many revisions are stored for each supported post type.
This plugin provides an interface for this new functionality.

= Why you should use this plugin? =

Each time you click **Save Draft** or **Update**, a revision is saved on the database.
WordPress will store all revisions for each page on your blog. Revisions allow you to look back at the recent changes you’ve made and revert to an earlier version if necessary.

But, what about all those revisions that you won't use anymore? Yes, they are still there, taking space from your hosting space and increasing the volume of your database and making it slower and heavier!

With this plugin you can easily limit the number of revisions that you want to save, with this you are saving space on your database and keeping the overall site performance in good shape.


= How can I change/limit the number of revisions stored on my database? =

Once `WP Revisions Limit` plugin is installed and activated, go to **Settings >  Revisions Limit** in your WordPress site and specify the number of revisions you want to store for each post/page under **Revisions Options** section.


= Compatibility: =

This plugin is fully compatible with any WordPress site with version 3.6 or higher.

Requires at least WordPress 3.6 and PHP 5.3


Please show your support for this plugin by giving it a [rating](http://wordpress.org/support/view/plugin-reviews/wp-revisions-limit?rate=5?rate=5#postform)

**Development is on GitHub: https://github.com/rrodrigonuez/WP-Revisions-Limit**

Pull requests are more than welcome!


== Installation ==

= From your WordPress Dashboard: =

1. Go to the `Plugins > Add New`
1. Search For `WP Revisions Limit`
1. Install it
1. Activate `WP Revisions Limit` plugin through the `Plugins` page in your WordPress site
1. Go to **Settings > Revisions Limit** and set the options under **Revisions Options** section in your WordPress site


= Uploading it in WordPress Dashboard: =

1. Download `WP Revisions Limit` plugin
1. Go to the `Plugins > Add New`
1. Click `Upload`
1. Select `wp-revisions-limit.zip` file from your computer
1. Click `Install Now`
1. Activate `WP Revisions Limit` plugin through the `Plugins` page in your WordPress site
1. Go to **Settings > Revisions Limit** and set the options under **Revisions Options** section in your WordPress site


= From WordPress.org using FTP: =

1. Download `WP Revisions Limit` plugin
1. Unzip `wp-revisions-limit.zip` file
1. Upload `wp-revisions-limit` directory to your `/wp-content/plugins/` directory, using your favorite method (ftp, sftp, scp, etc...)
1. Activate `WP Revisions Limit` plugin through the `Plugins` page in your WordPress site
1. Go to **Settings > Revisions Limit** and set the options under **Revisions Options** section in your WordPress site


== Frequently Asked Questions ==

= Where do I change the plugin's settings? =

Navigate to **Settings > Revisions Limit** in your WordPress Dashboard, and look for the **Revisions Options** section.

= Is this plugin compatible with my WordPress site? =

This plugin is fully compatible with any WordPress site with version 3.6 or higher.

Requires at least WordPress 3.6 and PHP 5.3

= How secure is this plugin? =

This plugin has been developed with one thing in mind: to keep your WordPress site as **secure** as possible, **ad free**, **spam free** and with the maximum **performance** reachable.
The developers that contribute to build this plugin are 100% **reliable** and some of the **most experienced WordPress developers**.


== Changelog ==

= 1.3 =
* Fixed issue when WordPress is not installed in wordpress subfolder.
Thanks to [Dominik Kocuj](https://wordpress.org/support/profile/domko)

= 1.2 =
* Delete redirection settings stored in database when plugin is activated

= 1.1 =
* Added redirection to Plugin Settings page when plugin is activated
* Plugin option settings will be removed when plugin is uninstalled

= 1.0.3 =
* Fixed bug when no limit exists on Plugin initialization.
* Updated Plugin internal version

= 1.0.2 =
* Removed default limit of revisions assigned by this plugin.

= 1.0.1 =
* Check if WP_POST_REVISIONS is already defined in wp-config.php file.

= 1.0.0 =
* Initial public release


== Screenshots ==

1. The plugin's settings section, found under **Settings > Revisions Limit**.