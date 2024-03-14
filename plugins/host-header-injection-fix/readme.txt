=== Host Header Injection Fix ===

Plugin Name: Host Header Injection Fix
Plugin URI: https://perishablepress.com/host-header-injection-fix/
Description: Sets custom headers for WP notification emails. Also fixes a security issue with WP versions &lt; 5.5.
Tags: headers, injection, security, email, notification
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.6
Tested up to: 6.5
Stable tag: 3.0
Version:    3.0
Requires PHP: 5.6.20
Text Domain: host-header-injection-fix
Domain Path: /languages
License: GPL v2 or later

Sets custom headers for WP notification emails. Also fixes a security issue with WP versions &lt; 5.5.



== Description ==

> Enables custom headers for WP email notifications
> Also "set it and forget it" security fix for WP &lt; 5.5


**Important**

As of WordPress 5.5, this plugin no longer is necessary to fix the [host-header security issue](https://exploitbox.io/vuln/WordPress-Exploit-4-7-Unauth-Password-Reset-0day-CVE-2017-8295.html) reported in [Ticket #25239](https://core.trac.wordpress.org/ticket/25239) **finally** is fixed, and mentioned in this post [WordPress 5.5 Beta 4](https://wordpress.org/news/2020/07/wordpress-5-5-beta-4/). Thank You WordPress devs!


**Is this plugin still useful?**

Yes, it enables you to choose the "From", "Name", and "Return-Path" headers for all WP notification emails. And for versions of WordPress less than 5.5, this plugin continues to fix the host-header injection security issue.


**Features**

This simple plugin does three things:

1. Sets custom From, Name, and Return-Path for WP notifications
2. Fixes a security vulnerability in WordPress versions &lt; 5.5
3. Fixes a bug where invalid email addresses may be generated (in WordPress versions &lt; 5.5)

Choose from the following options:

* Use WordPress defaults (insecure for WP &lt; 5.5)
* Use "Email Address" from WP General Settings
* Use a custom name and address

Plus there is an option to use the specified From address as the Return-Path header.


**Why?**

The security issue fixed by this plugin has been known about since way back in WordPress version 2.3. There has been some talk about fixing, but nothing has been implemented. While the issue does not affect all sites, it does affect a good percentage of them, including some of my own projects. So, not wanting to get hacked, I decided to write my own solution. Hopefully this issue gets fixed in a future version of WordPress, and this plugin will become unnecessary.

As a bonus, setting an explicit From address resolves a long-standing bug whereby an invalid email address is generated under the following conditions:

* A "From" address is not set, 
* And the `$_SERVER['SERVER_NAME']` is empty

So by explicitly setting a "From" address, we prevent this bug from happening.


**Security Issue**

What is the security issue addressed by this plugin? Follows is a quick summary. To learn more in-depth, check out the resources linked in the next section.

* WordPress uses `$_SERVER['SERVER_NAME']` to set the "From" header in email notifications
* This includes sensitive email notifications like password resets and user registration
* In some cases, an attacker could modify the "From" header and intercept the email
* Using the intercepted email, an attacker could gain access to your site and wreak havoc


**More Infos**

This security vulnerability is well-known and has been around for a looong time. To learn more, check out these articles:

* [WP Core Trac Ticket](https://core.trac.wordpress.org/ticket/25239)
* [WP Vulnerability Database](https://wpvulndb.com/vulnerabilities/8807)
* [Exploit Box Info](https://exploitbox.io/vuln/WordPress-Exploit-4-7-Unauth-Password-Reset-0day-CVE-2017-8295.html)
* [Even more infos](https://blog.dewhurstsecurity.com/2017/05/04/exploitbox-wordpress-security-advisories.html)


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

Host Header Injection Fix is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).


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



== Screenshots ==

1. Host Header Injection Fix: Default Plugin Settings

More screenshots available at the [HHIF Plugin Homepage](https://perishablepress.com/host-header-injection-fix/).



== Installation ==

**Installing HHIF**

1. Upload the plugin to your blog and activate
2. Visit the plugin settings to configure options

[More info on installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)



**Uninstalling**

HHIF cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.



**Restore Default Options**

To restore default options, uninstall the plugin via the WP Plugins screen, and then reinstall.



**Like the plugin?**

If you like Host Header Injection Fix, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/host-header-injection-fix/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!



== Upgrade Notice ==

To upgrade HHIF, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 



== Frequently Asked Questions ==

**The bug was fixed? Is this plugin still useful?**

As of WordPress 5.5, this plugin no longer is necessary. They finally fixed the bug reported in [Ticket #25239](https://core.trac.wordpress.org/ticket/25239), mentioned in this post [WordPress 5.5 Beta 4](https://wordpress.org/news/2020/07/wordpress-5-5-beta-4/). Thank You WordPress devs!

"So is the plugin still useful?"

Yes, HHIF enables you to choose the "From", "Name", and "Return-Path" headers for all WP notification emails. And for versions of WordPress less than 5.5, this plugin continues to fix the host-header injection security issue.


**How to test if I need the plugin?**

For fixing the host-header injection security issue, this plugin is necessary only for WordPress versions less than 5.5 (they fixed the bug in WP 5.5). So if you are running WP 5.5 or better, then you do not need this plugin. Unless you want to customize the headers used in WP notification emails.

If you are using WordPress less than 5.5, you can find more information on testing [here](https://www.exploit-db.com/exploits/41963) and [here](https://exploitbox.io/vuln/WordPress-Exploit-4-7-Unauth-Password-Reset-0day-CVE-2017-8295.html).


**Does this work for WP Multisite?**

Yes, if activated on an individual per-site basis. I.e., may not work properly with network-wide activation.


**Does the plugin provide any hooks?**

Yes, there are numerous hooks available for advanced customization. Refer to the source code for details.


**What about the option for Email Return Path?**

When the HHIF option, WP Notifications &gt; "Use custom address" is enabled, the plugin toggles open another option called "Email Return Path". There you can check the box to use the "Email From Address" as the Return Path for all emails sent by WordPress (e.g., new user notifications, new comment notifications, login related notifications, etc.). So check/enable this option only if you want to use the "Email From Address" as the Return Path for *all* emails sent by WordPress. If in doubt, leave the option unchecked/disabled.


**Do you offer any other security plugins?**

Yes, three of them:

* [BBQ Firewall](https://wordpress.org/plugins/block-bad-queries/) for super-fast firewall security
* [Blackhole for Bad Bots](https://wordpress.org/plugins/blackhole-bad-bots/) to protect your site against bad bots
* [Banhammer](https://wordpress.org/plugins/banhammer/) to monitor and ban any user or IP address

Pro versions with more features available at [Plugin Planet](https://plugin-planet.com/).


**Does this plugin work with Gutenberg?**

Yes, works great does not matter which editor (block or classic) is used.


**Got a question?**

Send any questions or feedback via my [contact form](https://plugin-planet.com/support/#contact)



== Changelog ==

If you like Host Header Injection Fix, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/host-header-injection-fix/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**3.0 (2024/03/01)**

* Tests on WordPress 6.5


Full changelog @ [https://plugin-planet.com/wp/changelog/host-header-injection-fix.txt](https://plugin-planet.com/wp/changelog/host-header-injection-fix.txt)
