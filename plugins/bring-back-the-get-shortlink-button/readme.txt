=== Bring Back the Get Shortlink Button ===
Contributors: tfrommen
Tags: link, short, shortlink
Requires at least: 4.4
Tested up to: 6.1
Requires PHP: 7.4
Stable tag: 2.1.0
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

This plugin brings back the Get Shortlink button, which is hidden by default since WordPress 4.4.

== Description ==

As of WordPress 4.4, the _Get Shortlink_ button is hidden by default. This plugin brings it back. Yay.

Please note that the button does not exist at all in a Block Editor context. It will only show up when using the Classic Editor, for example, when editing a single attachment.

== Installation ==

This plugin requires PHP 7.0.

1. Upload the `bring-back-the-get-shortlink-button` folder to the `/wp-content/plugins/` directory on your web server.
1. Activate the plugin through the _Plugins_ menu in WordPress.
1. Find the _Get Shortlink_ button just where it was before WordPress 4.4.

== Changelog ==

= 2.1.0 =
* Add unit tests.
* Split main plugin file into multiple functions to allow for better testability.
* Rename folder including WordPress.org assets.

= 2.0.0 =
* Add type declarations.
* Add code quality tooling and config.
* **BREAKING:** Require PHP 7.4 or higher.
* Bump "Tested up to" header.

= 1.2.0 =
* Bump "Tested up to" header.

= 1.1.0 =
WordPress.org release.

= 1.0.0 =
Initial release.
