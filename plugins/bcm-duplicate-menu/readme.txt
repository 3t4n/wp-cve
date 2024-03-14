=== Plugin Name ===
Contributors: bcmdev
Donate link: http://bcmdev.nl/donate.html
Tags: duplicate, menu, ClassicPress
Requires at least: 4.0
Tested up to: 6.3
Requires PHP: 5.4
Stable tag: 1.1.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is the easiest way to duplicate your menu

== Description ==

<p>You can now duplicate your menu by just clicking one button.</p>

<p>This plugin is also compatible with ClassicPress.</p>

== Installation ==

<ul>
<li>Install your plugin by uploading it in your Wordpress site or install it directly from the Wordpress Plugin Browser.</li>
<li>Activate the plugin and you are ready to go!</li>
</ul>

<p>This plugin is also compatible with ClassicPress.</p>

== Frequently Asked Questions ==

= How to duplicate a menu? =

We added a duplicate button next to the Save button on the Menu page in the Wordpress Admin. Just click the button and see how your menu duplicates itself.

= Will the duplicated menu remain after I remove the plugin? =

Yes, the duplicated menu will remain.

== Screenshots ==

1. The Duplicate-button can be found next to the Save-button.

== Changelog ==

= 1.1.2 (2022-10-07) =
* Bugfix: In some cases, the menu order was not kept after duplicating the menu.

= 1.1.1 (2022-10-02) =
* Bugfix: A PHP notice was thrown when handling serialized metadata for menu items.

= 1.1.0 (2022-01-29) =
* Feature: Added support for the duplication of additional meta data added by third-party plugins.
* Bugfix: An error could occur when trying to duplicate menu items when third-party plugins were already hooking into the WordPress menu functions.
* Developer Note: The 'duplicate_menu_item' hook as been renamed to 'bcm_duplicate_menu_item'.

= 1.0.5 (2022-01-27) =
* Bugfix: An error could occur when freshly entering the nav-menus.php page.

= 1.0.4 (2021-02-23) =
* Bugfix: An error could occur when you try to duplicate a menu that was already duplicated before.

= 1.0.2 (2020-06-23) =
* Improvement: When you click the Duplicate Menu button, a loading icon is now visible.
* Improvement: Dutch translations updated

= 1.0.1 (2018-11-30) =
* Bugfix: The Duplicate button was also visible while creating a menu which caused unexpected behaviour.

= 1.0.0 (2018-11-28) =
* Initial release