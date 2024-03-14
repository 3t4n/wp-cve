=== WP Custom Author URL ===
Contributors: chrisjallen
Donate link: https://paypal.me/chrisjimallen
Tags: banner, author, author url, profile, profile url, custom url, custom link
Requires at least: 3.0.1
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Set a custom URL for your author name link, on a global or author-specific basis. Also redirects all author pages.

== Description ==

This plugin will allow you to choose a custom URL for your author links, instead of the standard WordPress author page.
This can be useful if you want to link to your own Twitter, LinkedIn or other social media profile.

There are two areas where this plugin can be configured: 

Firstly, a global settings section is available under WordPress Settings. Second, under each users profile page.

A user can set their own custom URL on their profile page, and this will apply just to them. 
This can can be overriden by the global admin setting, if the 'Override Individual Authors' setting is checked.

Custom author URL's also redirect author pages when directly accessed. 
For example if your user is called Bob, and you try to access https://yourblog.com/author/bob, it will redirect.


== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'WP Custom Author URL'
3. Activate 'WP Custom Author URL' from your Plugins page.
4. Visit 'WP Custom Author URL Options' in the 'Plugins' submenu to access the settings.

= From WordPress.org =

1. Download 'WP Custom Author URL'.
2. Upload the 'wp-custom-author-url' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate 'WP Custom Author URL' from your Plugins page.
4. Visit 'Custom Author URL' in the 'Settings' menu to access the settings.

== Frequently Asked Questions ==

= Can I set it to open in a new tab? =

Not at the moment, this will have to be configured via the theme until I find a reliable way of doing this.

= Do you have any other useful plugins? =

Yes, I have two other ( completely free ) plugins called WP Dev Flag & WP Custom Author URL, both on the Wordpress Repository here: 

[WP Dev Flag](https://wordpress.org/plugins/wp-dev-flag/)
[Woo Custom Empty Price](https://wordpress.org/plugins/woo-custom-empty-price/)

== Screenshots ==

1. The global settings:
2. User specific settings:

== Changelog ==

= 1.0.5 =
* Fix a minor vulnerability in the admin settings page, where a user could potentially insert unescaped HTML into the settings page.

= 1.0.4 =
* Fix a file structure error introduced by yours truly, causing plugin activation to break.

= 1.0.3 =
* Fix an undefined option value and tidy up comments.

= 1.0.1 =
* Remove unnecessary public script enqueue calls.

= 1.0.0 =
* First Version.

== Upgrade Notice ==

= 1.0.1 =
Remove unnecessary public script enqueue calls.

= 1.0.0 =
This is the first version.
