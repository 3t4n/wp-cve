=== BNS Corner Logo ===
Contributors: cais
Donate link: http://buynowshop.com/
Tags: image, logo, multiple widgets, gravatar, multisite compatible, widget-only
Requires at least: 3.0
Tested up to: 4.5
Stable tag: 2.2
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Widget to display a logo; or, used as a plugin displays image fixed in one of the four corners.

== Description ==

Widget to display a user selected image as a logo; or, used as a plugin that displays the image fixed in one of the four corners of the display.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `bns-corner-logo.php` to the `/wp-content/plugins/` directory.
2. Activate through the 'Plugins' menu.
3. Place the BNS Corner Logo widget appropriately in the Appearance | Widgets section of the dashboard.
4. Enter the Title for the widget area if you do not want to use the default "My Logo Image"
5. Enter the complete URL to the image, including the `http://`; Optional: Enter `ALT` text for the image; Enter the URL for the image to link to, the "default" URL will be the page the image appears on.
6. Optional: Check the box to use the default admin (user-ID 1) gravatar; change the default gravatar display size (in pixels)
7. Click "Save"

-- or -

1. Go to 'Plugins' menu under your Dashboard
2. Click on the 'Add New' link
3. Search for bns-featured-category
4. Install.
5. Activate through the 'Plugins' menu.
6. Place the BNS Corner Logo widget appropriately in the Appearance | Widgets section of the dashboard.
7. Enter the Title for the widget area if you do not want to use the default "My Logo Image"
8. Enter the complete URL to the image, including the `http://`; Optional: Enter `ALT` text for the image; Enter the URL for the image to link to, the "default" URL destination will be "_self" (the page the image appears on).
9. Optional: Check the box to use the default admin (user-ID 1) gravatar; change the default gravatar display size (in pixels)
10. Click "Save"

* To use like a "fixed position" plugin:
1. Use the checkbox beside "Use like a Plugin?"
2. Choose the corner of the display from the drop-down box.
3. Click "Save"

Read this article for further assistance: http://wpfirstaid.com/2009/12/plugin-installation/

NB: This plugin will not resize your images, please make sure you use an appropriate sized image for the area you are placing it in. For the "fixed position" it is strongly recommended to use an image that is at least partially transparent for those readers with smaller screens.

== Frequently Asked Questions ==

= Can I use this in more than one widget area? =

Yes, this plugin has been made for multi-widget compatibility. Each instance of the widget will display, if wanted, differently than every other instance of the widget.

== Screenshots ==

1. The Options Panel.
2. A sample logo image in the sidebar widget area of the [Shades](http://wordpress.org/extend/themes/shades/) theme.
3. An image in the sidebar and an image "fixed" in the bottom-right corner showing multiple instances of the widget.

== Other Notes ==
  Copyright 2009-2016  Edward Caissie  (email : edward.caissie@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2,
  as published by the Free Software Foundation.

  You may NOT assume that you can use any other version of the GPL.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  The license for this software can also likely be found here:
  http://www.gnu.org/licenses/gpl-2.0.html
    
== Upgrade Notice ==
Please stay current with your WordPress installation, your active theme, and your plugins.

== Changelog ==
= 2.2 =
* Released January 2016
* Added simple dashboard widget for support references
* Hide dashboard widget by default via Screen Options
* Added meta link to WordPress translations page for plugin
* Added hook to allow for arbitrary change to "Requires at least" version
* Minor change to `$exit_message` text
* Minor code formatting changes
* Updated `.pomo` files and copyright year
* Updated inline documentation and added call to dashboard widget

= 2.1 =
* Released November 2015
* Added Plugin Row Meta details
* Added `install` method as part of version compatibility check
* Added `load_plugin_textdomain` call
* Added English (Canada) translation files, and default translation `.pot` file
* Changed to singleton style structure
* Moved compatibility check to be parsed first via `register_activation_hook`
* Moved `update_method` function into class as method
* Moved `plugin_data` into its own method and modified variable used
* Re-wrote compatibility message to be more i18n compatible

= 2.0 =
* Released August 2015
* Added Mallory-Everest filter hook `bnscl_image_tag_alt_title`
* Updated to use PHP5 constructor objects

= 1.9 =
* Released March 2015
* Added `BNS Corner Logo Update Message` hook/callback routine
* Added `BNS_CUSTOM_PATH` and `BNS_CUSTOM_URL` constants
* Added calls to custom JavaScript and CSS files in the `/bns-customs/` folder
* Changed `textdomain` from `bns-cl` to the plugin slug `bns-corner-logo`
* Corrected typo in custom JavaScript file name
* Minor code reformatting
* Removed extraneous comments
* Update version, tested up to, copyright, etc.

= 1.8.5 =
* Released January 2014
* More sanitizing - `$image_url` and `$image_link`; thanks ScheRas

= 1.8.4 =
* Released January 2014
* Properly escape the alt image attribute
* Update copyright year

= 1.8.3 =
* Released December 2013
* Code reformatting to better reflect WordPress Coding Standards (see https://gist.github.com/Cais/8023722)
* Added functional option to place `bns-corner-logo-custom-style.css` in the `/wp-content/` folder

= 1.8.2 =
* Release May 2013
* Version compatibility update
* CSS optimization and formatting
* Minor code formatting

= 1.8.1 =
* Release - February 21, 2013
* Minor documentation updates
* Revert: Removed `width: 100%` from `div.bns-logo a img`

= 1.8 =
* Release - February 2013
* Address "smart-phone" screens being obfuscated by image
* Documentation updates - including code block termination comments
* Move all code into class structure
* Removed `width: 100%` from `div.bns-logo a img`
* Removed `load_textdomain` as redundant
* Renamed `BNS_Corner_Logo_Scripts_and_Styles` to `scripts_and_styles`

= 1.7 =
* Release - November 2012
* Added `bns-corner-logo-scripts.js`
* Added 'no-grav' class to HTML img tag with `jQuery.addClass()` to stop Hovercard effect if Gravatar is used
* Added i18n support to position drop-down in widget control panel
* Adjusted CSS of top positions if user is logged-in
* Enqueued JavaScript 'bns-corner-logo-scripts.js'
* Enqueued JavaScript 'bns-corner-logo-custom-scripts.css' if it exists

= 1.6.2 =
* Use the plugin version data for the version number in `wp_enqueue_style` rather than hard-coding a number

= 1.6.1 =
* confirmed compatible with WordPress 3.4
* inline documentation updates and minor code formatting

= 1.6 =
* released November 2011
* confirmed compatible with WordPress 3.3
* added phpDoc Style documentation
* added i18n support
* added conditional enqueue of `bns-corner-logo-custom-style.css` stylesheet
* removed inline `z-index` reference; see `bns-corner-logo` stylesheet for value
* moved inline style for corner positions to plugin stylesheet
* refactored `use_gravatar` code to have option of choosing gravatar by user ID

= 1.5 =
* released June 2011
* confirmed compatible with WordPress version 3.2-beta2-18085
* enqueue stylesheet
* updated 'option' code to use `selected()`
* cleaned up and re-sized the widget options display
* updated the option panel screenshot to show the new size

= 1.4 =
* released February 6, 2011
* correct version requirement message
* added option to open "URL to follow" in new page
* moved common inline styles to plugin stylesheet

= 1.3.3 =
* released December 12, 2010
* Confirm compatible with WordPress 3.1 (beta)

= 1.3.2 =
* cleaned up temporary fix code

= 1.3.1 =
* temporary fix for non-gravatar images

= 1.3 =
* compatible with WordPress version 3.0
* changed Gravatar identifier to first administrator by user ID

= 1.2.3.2 =
* compatible with WordPress version 2.9.2
* updated license declaration

= 1.2.3.1 =
* clarified the plugin is released under a GPLv2 license

= 1.2.3 =
* compatibility check for 2.9.1 completed

= 1.2.2 =
* addressed a few minor Gravatar display issues
* stopped "ALT" text from displaying when using the Gravatar option
* addressed image borders around the Gravatar image with CSS

= 1.2.1 =
* corrected issue with plugin selection drop-down list, it now maintains the option chosen as the displayed value
* updated installation instructions to reflect addition of Gravatar support

= 1.2 =
* completed Gravatar implementation - displays Gravatar associated with user-ID 1 (main administrator)
* added plugin specific style sheet (required by Gravatar implementation)
* noted suggested maximum pixel size of Gravatar but no restrictions set
* updated screenshot of option panel

= 1.1.1 =
* compatibility check for WP2.9 completed
* increased the width of the option panel
* beginning of code to implement Gravatar included (but not complete or visually apparent)

= 1.1 =
* added version checking using $wp_version
* improved readability of code

= 1.0.1 =
* code clean up and error trapping
* minor improvement in IE6 compatibility

= 1.0 =
* Initial Release