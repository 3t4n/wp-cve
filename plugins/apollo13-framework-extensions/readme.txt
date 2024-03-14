=== Apollo13 Framework Extensions ===
Contributors: apollo13themes, air-1
Tags: custom post types, shortcodes, elementor widgets, wpbakery page builder support
Requires at least: 4.7
Tested up to: 6.4
Requires PHP: 5.4.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds custom post types, shortcodes and some features that are used in themes built on Apollo13 Framework.

== Description ==

**Apollo13 Framework Extensions** adds few features to themes build on Apollo13 Framework. These are:

* Designs Importer,
* shortcodes based on Apollo13 Framework features: writtng effect, count down, socials, scroller, slider, galleries, post grid,
* support for WPBakery Page Builder elements added by Apollo13 Framework,
* custom post types: albums, works & people,
* Export/Import of theme options,
* Custom Sidebar,
* Custom CSS,
* Meta options that are creating content for posts, pages, albums and works,
* Responsive Image resizing ,
* Maintenance mode.

This plugin requires one of themes build on **Apollo13 Framework** theme to be installed.

It is mostly used for:

* [Rife Free](https://apollo13themes.com/rife/free/)
* [Rife Pro](https://apollo13themes.com/rife/)


== Installation ==

1. Upload **Apollo13 Framework Extensions** to the `/wp-content/plugins/` directory
2. Activate the plugin through the **Plugins** menu in WordPress
3. Done!

== Frequently Asked Questions ==

= I installed the plugin but it does not work =

This plugin will only work with themes build on **Apollo13 Framework**.
All themes compatible are listed above in description.



== Changelog ==

= 1.9.3(18.01.2024) =

Tested with WordPress 6.4.2

Fixed:
-XSS for writing effect

----------------

= 1.9.2(09.11.2023) =

Tested with WordPress 6.4

Fixed:
-checking for nonce security in admin actions

----------------

= 1.9.1(25.09.2023) =

Tested with WordPress 6.3

Fixed:
-Proper resizing of slider when put in force stretched section
-Requesting scroller script when it is not available in Elementor
-libxml_disable_entity_loader deprecated since PHP 8.0


Improved:
-Code protection when empty widgets are imported
-validation of parameters in the theme shortocodes

----------------

= 1.9.0(05.03.2023) =

Tested with WordPress 6.1

Fixed:
-The plugin CSRF and broken access control vulnerabilities
-Displaying category selection in for custom post types in the Gutenberg editor

Improved:
-Displaying warning when no image libraries are installed

----------------

= 1.8.10(21.04.2022) =

Tested with WordPress 5.9

Fixed:
-Notices thrown for Elementor widgets since Elementor 3.6.4

Improved:
-Exporting default active kit of Elementor

----------------

= 1.8.9(26.07.2021) =

Tested with WordPress 5.8

Fixed:
-Desging import issue with Elementor 3.3 and higher

Improved:
-possibility to use the block editor in written content of works and albums

----------------

= 1.8.8(05.01.2021) =

Tested with WordPress 5.6

Fixed:
-broken removing of the content while importing when Elementor 3.0.15 or higher is active
-importing of global font setting in Elementor Design in Elementor 3.x

----------------

= 1.8.7(01.12.2020) =

Tested with WordPress 5.6

Fixed:
-Not all paths to images being rewriten on the demo import
-some incompatibilities with jQuery 3.5

Improved:
-Enabled texts for gallery if they are needed, even if it is disabled in original gallery
-All occurrence of intval() was changed to (int) - PHP 8 ready

----------------

= 1.8.6(07.09.2020) =

Tested with WordPress 5.5

Improved:
-JavaScript code changes to make plugin work with upcoming WodrPress versions.

----------------

= 1.8.5(22.07.2020) =

Tested with WordPress 5.4

Fixed:
-Hotfix for issues while importing Designs with Elementor in version 2.9.14 and above

----------------

= 1.8.4(01.04.2020) =

Added:
-Support for better extending admin menu options that is available since WordPress 5.4.
 Now you will be able to use plugins extending menus without conflicts.

----------------

= 1.8.3(24.02.2020) =

Added:
-List of selected files in photo proofing process available in 2 formats

----------------

= 1.8.2(13.11.2019) =

Fixed:
-Error in console when trying to use Scroller widget for Elementor with Rife Free
-Notices in WordPress 5.3 caused by A13FRAMEWORK_Plugin_Installer_Skin

Tested with WordPress 5.3

----------------

= 1.8.1(20.09.2019) =

Fixed:
-Support for Revolution Slider in version 6.0 and above
-typo in script for slider that resulted in error(thanks AndreLIma)

----------------

= 1.8.0(08.08.2019) =

Improved:
-Rewritten theme slider to use anime.js - library that is GPL compatible. This change saves almost 100KB on page load

Fixed:
-Gallery Elementor widget respecting album setting for displaying title/description on items
-Gallery shortcode respecting album setting for displaying title/description on items



== Credits & Copyright ==

= Anime.js, Copyright 2019 Julian Garnier =
Licenses: MIT
Source: https://animejs.com/