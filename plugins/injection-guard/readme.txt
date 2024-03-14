=== Injection Guard ===
Contributors: fahadmahmood, alianwaar91
Tags: security, tool, anti-hacking, blacklist
Requires at least: 3.0
Tested up to: 6.2
Stable tag: 1.2.3
Requires PHP: 7.0
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
This plugin will block all unauthorized and irrelevant requests through query strings by redirecting them to an appropriate error page instead of generating identical results for it.

== Description ==
* Author: [Fahad Mahmood](https://www.androidbubbles.com/contact)

* Project URI: <http://androidbubble.com/blog/wordpress/plugins/injection-guard>

* License: GPL 3. See License below for copyright jots and tittles.

Injection Guard is a wordpress plugin which helps you to get relax about security of your website which can be disturbed by invalid query string based requests. It is much better that if you are using pretty permalinks so you can deny all of the query string parameters straightaway instead of having headache of a list of whitelisted parameters and blacklisted as well. I am a PHP, Wordpress developer and i faced a lot of inconvenience regarding keep an eye on security threats related to query strings and user's activity. Our debugging process demands continuous monitoring to the number of requests and their types. So, I coded a number of fixes for wordpress sites and few of them are in form of articles on my blog.

= Tags =
injection shield


Important!

1- Be in touch with your Google Webmaster Tools.

2- Keep visitng author's blog for the updates.

[Blog][Wordpress][]: http://androidbubble.com/blog/category/website-development/php-frameworks/wordpress

== Installation ==
To use Injection Guard, you will need:
* 	an installed and configured copy of [WordPress][]

	(version 3.0 or later).
*	FTP, SFTP or shell access to your web host
= New Installations =

Method-A:

1. Go to your wordpress admin "yoursite.com/wp-admin"

2. Login and then access "yoursite.com/wp-admin/plugin-install.php?tab=upload

3. Upload and activate this plugin

4. Now go to admin menu -> settings -> IG Settings

5. Click on save settings button.

5. That's it, now wait for the magic

Method-B:

1.	Download the Injection Guard installation package and extract the files on

	your computer. 
2.	Create a new directory named `Injection Guard` in the `wp-content/plugins`

	directory of your WordPress installation. Use an FTP or SFTP client to

	upload the contents of your Injection Guard archive to the new directory

	that you just created on your web host.
3.	Log in to the WordPress Dashboard and activate the Injection Guard plugin.
4.	Once the plugin is activated, a new **IG Settings** sub-menu will appear in your Wordpress admin -> settings menu.

[Injection Guard]: http://androidbubble.com/blog/wordpress/plugins/injection-guard


== Frequently Asked Questions ==

= Does this plugin help in saving SEO effort? =
YES

= Is it secure? If yes, how? =
It immediately senses the unauthorized access through query string and block it immediately. It does not let the page generate a valid content for an invalid request. It saves you from an extreme headache.

= What if I am still being hacked? =
Make sure that your plugin version is updated because protection and related knowledge is evolving every moment. Keep an eye on invalid requests through query strings you have, either restrict few of them or restrict them all if not required.

= I have some other queries, other than this plugin, may I ask to the plugin author? =
YES, if the queries are about WordPress and data security then you are welcome.

= What best method is to contact plugin author? =
It is good if you use support tab or plugin's author blog. If you want to reach the author immediately then use contact form on his blog.

= I am not sure that I configured it properly or not? =
Contact plugin author, he might will do on your behalf or will guide you shortly.

== Tags ==
sql injection, http injection, site hacked, site hacking, anti hacking, injection guard, hacking

== Screenshots ==
1. Settings & Reports

== Features ==

**&#128204; Log all the unique query strings which are trying to penetrate your website
**&#128204; Blocked some query parameter
**&#128204; With an add-on you can ask a free diagnosis for your site


== Changelog ==
= 1.2.3 =
* Updated version for pioneer. [Thanks to alianwaar91][11/05/2023]
= 1.2.2 =
* Updated version for vulnerable to Broken Access Control. [Thanks to Darius Sveikauskas | Patchstack Alliance overlord][10/05/2023]
= 1.2.1 =
* Updated version for WordPress. [07/09/2022]
= 1.2.0 =
* Bootstrap, FontAwesome and timestamp based log added. [Thanks to Team Ibulb Work]
= 1.1.9 =
* Updating jQuery functions.
= 1.1.8 =
* Updating FAQs.
= 1.1.7 =
* Languages added. [Thanks to Abu Usman]
= 1.1.6 =
* Dashboard refined with customers results.
= 1.1.5 =
* Dashboard introduced for registered users activity regarding orders and logins from different locations.
= 1.1.4 =
* Sanitized input and fixed direct file access issues.
= 1.1.3 =
* Updating a few Illegal string offset conditions. [Thanks to PapGeo]
= 1.1.2 =
* Updating a few Illegal string offset conditions.
= 1.1.0 =
* Releasing with WP Mechanic free help feature.

== Upgrade Notice ==
= 1.2.3 =
Updated version for pioneer.
= 1.2.2 =
Updated version for vulnerable to Broken Access Control.
= 1.2.1 =
Updated version for WordPress.
= 1.2.0 =
Bootstrap, FontAwesome and timestamp based log added.
= 1.1.9 =
Updating jQuery functions.
= 1.1.8 =
Updating FAQs.
= 1.1.7 =
Languages added.
= 1.1.6 =
Dashboard refined with customers results.
= 1.1.5 =
Dashboard introduced for registered users activity regarding orders and logins from different locations.
= 1.1.4 =
Sanitized input and fixed direct file access issues.
= 1.1.3 =
Updating a few Illegal string offset conditions.
= 1.1.2 =
Updating a few Illegal string offset conditions.
= 1.1.0 =
Releasing with WP Mechanic free help feature.

= Upgrades =

To *upgrade* an existing installation of Injection Guard to the most recent release:

1.	Download the Injection Guard installation package and extract the files on your computer. 
2.	Upload the new PHP files to `wp-content/plugins/Injection Guard`, overwriting any existing Injection Guard files that are there.
3.	Log in to your WordPress administrative interface immediately in order to see whether there are any further tasks that you need to perform to complete the upgrade.
4.	Enjoy your newer and hotter installation of Injection Guard

[Injection Guard]: https://www.androidbubbles.com/extends/wordpress/plugins/


== License ==
This WordPress Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This free software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.