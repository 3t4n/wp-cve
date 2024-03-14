=== WP Cron HTTP Auth ===

Plugin Name: WP Cron HTTP Auth
Plugin URI: https://perishablepress.com/wp-cron-http-auth/
Description: Enable WP Cron on sites using HTTP Authentication
Tags: wp cron, cron, http auth, http, auth
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.6
Tested up to: 6.5
Stable tag: 2.9
Version:    2.9
Requires PHP: 5.6.20
Text Domain: wp-cron-http-auth
Domain Path: /languages
License: GPL v2 or later

Enables WP Cron on sites using HTTP Authentication.



== Description ==

This plugin enables WP Cron on sites using HTTP Authentication.

How to use: Visit the plugin settings, enter your HTTP Auth credentials, save changes, and done.

Everything happens silently and automatically in the background.

> New! Supports defined constants via wp-config.php ([learn more](https://wordpress.org/plugins/wp-cron-http-auth/#installation))


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

WP Cron HTTP Auth is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).


**Support development**

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)
* [Wizard's SQL Recipes for WordPress](https://books.perishablepress.com/downloads/wizards-collection-sql-recipes-wordpress/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect WordPress to Google Analytics
* [Simple Ajax Chat Pro](https://plugin-planet.com/simple-ajax-chat-pro/) - Unlimited chat rooms
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thank you! :)



== Installation ==

**Installing the Plugin**

1. Upload the plugin to your blog and activate
2. Visit the plugin settings to configure options

[More info on installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)


**Using Constants**

Thanks to feedback from [@nowakpiotrek](https://wordpress.org/support/topic/feature-read-configuration-from-config-variables/), this plugin supports username and password constants defined in WordPress `wp-config.php`. So instead of entering the username and password via the plugin settings, you can add the following definitions to the `wp-config.php` file, just before the line that says, "That's all, stop editing!" There you can add the following code:

	define('WP_CRON_HTTP_AUTH_USERNAME', 'your-http-auth-username');
	define('WP_CRON_HTTP_AUTH_PASSWORD', 'your-http-auth-password');

Change `your-http-auth-username` and `your-http-auth-password` to match your username and password, respectively. After saving changes, you can verify the new constants are working by visiting the plugin settings page. If the constants are working, the username and password options will be greyed out, with a message that says, "Username/Password set in wp-config.php".


**Uninstalling**

This plugin cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.


**Restore Default Options**

To restore default options, uninstall the plugin via the WP Plugins screen, and then reinstall.


**Like the plugin?**

If you like WP Cron HTTP Auth, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/wp-cron-http-auth/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



== Upgrade Notice ==

To upgrade this plugin, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 



== Screenshots ==

1. Plugin settings



== Frequently Asked Questions ==

**Does this work for WP Multisite?**

Yes, this plugin works great on Multisite.


**How to test if the plugin is working?**

To test if this plugin is working, follow these steps:

1. Install and activate the excellent plugin, [WP Crontrol](https://wordpress.org/plugins/wp-crontrol/)

2. Visit the "Cron Events" screen in the WP Admin Area (under Tools menu)

3. Look for a warning message at the top of the screen that says:

"There was a problem spawning a call to the WP-Cron system on your site. This means WP-Cron events on your site may not work. The problem was: Unexpected HTTP response code: 401"

If the warning message is displayed, then HTTP Auth is blocking WP Cron. Otherwise, if no warning message is displayed, WP Cron is working normally.

Note: after testing it is fine to delete the WP Crontrol plugin if no longer needed.


**Got a question?**

Send any questions or feedback via my [contact form](https://plugin-planet.com/support/#contact)



== Changelog ==

If you like WP Cron HTTP Auth, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/wp-cron-http-auth/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**2.9 (2024/03/06)**

* Updates plugin settings page
* Updates default translation template
* Improves plugin docs/readme.txt
* Tests on WordPress 6.5 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/wp-cron-http-auth.txt](https://plugin-planet.com/wp/changelog/wp-cron-http-auth.txt)
