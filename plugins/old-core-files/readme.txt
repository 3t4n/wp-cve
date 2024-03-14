=== Old Core Files ===
Contributors: maor, ramiy
Tags: security, core files, old files, old_files, tool
Requires at least: 3.0
Tested up to: 5.2
Stable tag: 1.4
Requires PHP: 5.2.4
License: GPLv2 or later

Increase your WordPress security by deleting old core files that exist in the filesystem before hackers exploit them for attacks.

== Description ==

Secure your WordPress site by checking and removing obsolete core files.

When core is being upgraded, usually some files are no longer used by WordPress, and they are set for removal.

On some occasions, PHP has no permissions to delete these files, and they stay on the server. Old Core Files alerts you to remove old files before hackers attempt to exploit them for attacks.

It's recommended to delete unused themes and plugins as hackers could exploit them for attacks. Same logic applies for deprecated code files.

== Installation ==

= Installation =
1. In your WordPress Dashboard go to "Plugins" -> "Add Plugin".
2. Search for "Old Core Files".
3. Install the plugin by pressing the "Install" button.
4. Activate the plugin by pressing the "Activate" button.
5. Go to "Tools" -> "Old Core Files".

= Minimum Requirements =
* WordPress version 3.0 or greater.
* PHP version 5.2.4 or greater.
* MySQL version 5.0 or greater.

= Recommended Requirements =
* The latest WordPress version.
* PHP version 7.0 or greater.
* MySQL version 5.6 or greater.

== Screenshots ==

1. Main plugin screen in WordPress 3.5
1. Main plugin screen in WordPress 4.0

== Changelog ==

= 1.4 =

* Bug fix: fix broken nonce.

= 1.3 =

* Security: Prevent direct access to php files.
* Security: Prevent direct access to directory.
* Remove po/mo files from the plugin.
* Use translate.wordpress.org to translate the plugin.

= 1.2 =

* Bug fix: load translation files.
* Add new screenshot.
* update readme file.

= 1.1.3 =

* Add Hebrew translation by [Rami Yushuvaev](https://GenerateWP.com).
* Improve readme file.

= 1.1.2 =

* Add Serbian translation by [Borisa Djuraskovic](http://www.webhostinghub.com).

= 1.1 =

* Delete buttons removed. We'll have to work on it a bit more before making it available.
* Add screenshot.
* Improve readme file.

= 1.0 =

* Initial release.
