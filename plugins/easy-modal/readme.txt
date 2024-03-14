=== Easy Modal ===
Contributors: danieliser, wppopupmaker
Author URI: https://wizardis.com/
Plugin URI: https://wppopupmaker.com/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PQTG2JYUKSLFW
Tags: modal,modal box,modal form,modal window,popup,popup box,popup form,popup window,ajax forms, lightbox
Requires at least: 3.3.0
Tested up to: 4.7.3
Stable tag: 2.1.0

The #1 WordPress Popup Plugin! Make glorious & powerful popups and market your content like never before - all in minutes!

== Description ==

Did you know, that Easy Modal has a fancy new replacement called **[Popup Maker](https://wordpress.org/plugins/popup-maker/)**? It is the highest user rated popup & modal plugin available for WordPress.

* Unlimited themes
* Precision Targeting, Triggers & Cookies
* Customize everything
* Full line of extensions
* Extensive Documentation & Developer APIs
* [Learn more](https://wordpress.org/plugins/popup-maker/)!

We hope you enjoy using Popup Maker to destroy your old conversion rates!

[Continue to Popup Maker](https://wppopupmaker.com "Popup Maker Website")

If you're an existing Easy Modal user, transitioning from Easy Modal to Popup Maker is a snap with our custom Importer! [See How!](https://wppopupmaker.com/kb/upgrading-easy-modal-popup-maker "Upgrade from Easy Modal to Popup Maker")

== Installation ==
1. Login to WordPress and update to/install EasyModal version 2.0.13.
2. For current users, settings from previously existing functionality will be imported to the appropriate Addons containing respective functionality automatically.
4. Theme, customize, and make a popup in minutes.

== Changelog ==

= v2.1.0 =
* Tweak: Added a dismissible notice to the admin to let users know about **[Popup Maker](https://wordpress.org/plugins/popup-maker/)**.
* Fix: Patched minor security vulnerability.
  * Discovered with DefenseCode ThunderScan Source Code Security Analyzer by Neven Biruski
  * This can only be exploited by a user who already has access to the admin with a valid nonce.

= v2.0.17 =
* Tweak: Updated language domain information for upcoming translation by glotpress.

= v2.0.16 =
* Bug
 * Added backward compatibiilty fix for deprecated JS function.
= v2.0.15 =
* Bug
 * Changed misspelled function name in site js.
 * Included jQuery cookie and emodal utilities - strtotime JS function
* Improvement
 * Added callbacks to open and animation.
 * Added display:none to modals by default.
 * Added script deference.
 * Registered jquery cookies and emodal utilities strtotime js.

= v2.0.14 =
* Bug
 * Fixed a bug with visibility that happened before a modal is opened.
 * Fixed misconfiguration in admin javascript.
 * Fixed bug in click even for modal links.
 * Fixed an issue with responsive modals.
 * Fixed issue with trashed modals appearing in drop downs.
 * Fixed compatibility issues by adding jquery-ui-core.
* Improvement
 * Updated the visuals for default theme options to make them more user friendly.
 * Added per_page parameter and changed default to 10 per page.
 * Moved convert_hex into emodal utitlities.
 * Removed unused is_active column from tables.
 * Reorganized some triggers.
 * Added default modal settings.

= v2.0.13 =
* Bug
 * Fixed bugs in the Update API and Addon API that were causing extremely high loading times.
 * Fixed bug with Admin Notice.
 * Fixed numerous issues with modal and theme defaults which caused problems in the Java Script.
 * Fixed a bug with Existing Pro Addon causing compatibility issues.

= v2.0.11 =
* Bug
 * Fixed a bug in the default theme causing it to appear broken.		
 * Fixed bugs in missing options and defaults being applied.
 * Fixed issues with modals closing.		
 * Fixed PHP backward compatability issues.				
 * Table relationship fixes and API change.
* Improvement
 * Updates for new licensing system and API.		
 * Added reset options.		
 * Reorganized settings page.
 * WordPress Admin GUI Improvements.

= v2.0.10 =
* Bug
 * Removed unused files.
 * Check for count of modals before looking up meta.
 * Added note for wp.org admins.
 * If old modals exist dont create demo data.
 * Fixed issue with JS error when overlay was disabled.
 * Fixed bugs for PHP 5.2 computability.
* Improvement
 * Added Reset, Migrate from v1.3 and earlier, and Uninstall functionality to settings page.

= v2.0.9 =
* Bug
 * Patched bug in migration.

= v2.0.8 =
* Bug
 * Bug fix "class not found".

= v2.0.7 =
* Bug
 * Fixed Typos
 * Added min-width:1em to close button
 * Removed class modal from modals. Conflict with bootstrap css. All styles under emodal class now.

= v2.0.6 =
* Bug
 * Bug in migration for pro users.
 * Removed preview button until its functional.

= v2.0.5 =
* Bug
 * Bugs in addons page fixed.
 * Fixed bug in site JS.

= v2.0.4 =
* Bug
 * Fixed api request args bug.

= v2.0.3 =
* Bug
 * Fixed bug in easy modal admin page detection.
 * Replaced get_option with emodal_get_option for multisite compatiblity.

= v2.0.2 =
* Bug
 * Fixed migration failure bug.

= v2.0.1 =
* Bug
 * Fixed whitespace bug in sidebar.php

= v2.0 =
* Bug
 * Most bugs from previous versions have been addressed!
* Improvement
 * EasyModal has been rewritten to support platforming, extendability, and customization
 * Introduced EasyModal Add Ons as part of the platform
 * Introduced Hooks & Filters Code Base as part of the extendability and customization of the plug-in
 * There are now triple the theme settings, including:
  * Pixel perfect positioning of the modal container, its' shadows, and its' text shadows
  * Pixel perfect positioning of the close button, its' shadows, and its' text shadows
  * Create your own custom background images for the overlay, modal container and close button (Pro Only)
 * Modals now have an additional animation type and smoother animations, plus you can customize the animations' origins and finish positions
 * We have added modal positioning options to both set the modal to fixed positioning as well as the ability to place it:
  * To the right, to the left, on the bottom, or on the top
  * Plus, use associated settings to place the modal exactly where you want, down to the pixel!
 * Added the ability to use Auto Open Modals on scroll position (Pro Only)
 * Plus many, many more options to personalize and customize your modal any way you like!
* Add Ons
 * Added the EasyModal Pro Add On
  * This add on upgrades your Core Version to the Pro Version
 * Added the Exit Modals Add On (Pro & Pro Developer Only)
  * This add on allows you to create modals and pop-ups that appear when a user attempts to leave or exit your site
 * Added the Login Modals Add On (Pro & Pro Developer Only)
  * This add on gives you the ability to have login forms appear in a pop-up
 * Added the Import/Export Add On (Pro & Pro Developer Only)
  * This add on imports and exports settings from the EasyModal Plug-In
 * Added the Age Verification Add On (Pro & Pro Developer Only)
  * This add on will pop-up and prompt users to verify their age by inputting the information in a form (or drop down), or use a simple button (click to proceed) format


= v1.3.0.3 =
* Bug
 * [EM-67] - Modal Custom Height Not Working
 * [EM-69] - Undefined variable: user_login

* Improvement
 * [EM-51] - Videos play when modals are closed
= v1.3.0.1 =
* Bug
 * [EM-63] - Path issue for gravityforms.php and shortcodes.php
= v1.3 =
* Bug
 * [EM-5] - Modal is offcenter on mobile screens
 * [EM-6] - Fatal error: easy-modal-pro.php:122
 * [EM-7] - Modal title styles not applied
 * [EM-8] - Registration modal not working properly.
 * [EM-19] - GF Form detection and auto load scripts.
* Improvement
 * [EM-4] - Added Shortcodes
 * [EM-48] - Add modal display setting on modal list. Sitewide etc.
 * [EM-52] - Move jquery animate color script to its own file and enqueue.
= v1.2.5 =
* Several changes to the pro version and import from older versions.
= v1.2.2 =
* Added filter to add meta boxes and em options to custom post types.
= v1.2.1 =
* Fixes compatibility issues with Ultimate TinyMCE Plugin.
* Added plugin update notes to plugin page when updates are available.
= v1.2.0.9 =
* Fixed CSS z-index issues ( set modal z-index to 999, and overlay to 998 to make sure they are above other elements )
* Fixed an issue with upgrading from previous versions.
= v1.2.0.4 =
* Fixed data migration issue ( wasn't setting sites to sitewide )
* Added filters for modal content. Use add_filter('em_modal_content', 'your_custom_function'); function your_custom_function($content);
= v1.2.0.2 =
* Fixed issue of undefined array key.
= v1.2.0.1 =
* Fixed issue that caused wp editor to not load with certain themes.
= v1.2 =
* Code has been rewritten from ground up, JS, admin panels etc.
* Added animations
* Added responsive modals
* Added several additional settings.
= v1.0.2 =
* Fix for installation glitch.
= v1.0.0 =
* Release v1.0.0 Is a was rebuilt from the ground up. Features Include:
* Unlimited Modals
* Lighter Filesizes for Faster Loading
* Auto Centers no matter what the content
* Recenters on window resize/move
= v0.9.0.11 =
* Bug Fix in Settings page color picker.
= v0.9.0.10 =
* Bug Fix in CSS Fixes Form scrolling only when needed.
= v0.9.0.9 =
* Bug Fix in CSS Fixes Form scrolling.
= v0.9.0.8 =
* Bug Fix in JS (Missing " fixed)
= v0.9.0.7 =
* Bug Fix in JS (Affected loading of content into window)
= v0.9.0.6 =
* Bug Fix in JS (Affected WordPress versions below 3.1)
= 0.9.0.5 =
* Bug Fix in JS (Affected IE7).
= v0.9.0.4 =
* Added "Default" Theme for Modal windows. Includes CF7 Styles and Inline AJAX Styleing. See Screenshots.
* Default Options Tweaked for better OOB Experience.
* Added Version to WP Options table to provide better update functionality.
= v0.9.0.3 =
* Overlay Click to Close Option
* Auto Position Option
* Position Top Option
* Position Left Option
* Auto Resize Option
= v0.9.0.2 =
* Added Overlay Color Picker.
= v0.9.0.1 =
* Added Height & Width options.
= v0.9 =
* Initial Release
== Upgrade Notice ==
= v1.2.1 = 
* Fixes compatibility issues with Ultimate TinyMCE Plugin.
= v1.0.0 =
* This is a new build your settings will be reset.
= v0.9.0.4 =
* Options will be overwritten with default options.
= 0.9 =
* Initial Release