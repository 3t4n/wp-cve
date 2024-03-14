# Mongoose Page Plugin

Contributors: cameronjonesweb, mongoosemarketplace

Tags: like box,facebook like box,facebook page plugin, facebook feed, facebook page

Donate link: https://www.patreon.com/cameronjonesweb

Requires at least: 4.6

Tested up to: 6.4

Requires PHP: 5.3

Stable tag: 1.9.1

License: GPLv2

License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

The most popular way to display the Facebook Page Plugin on your WordPress website. Easy implementation using a shortcode or widget. Now available in 111 different languages

[![Build Status](https://api.travis-ci.com/mongoose-marketplace/facebook-page-feed-graph-api.svg)](https://travis-ci.com/mongoose-marketplace/facebook-page-feed-graph-api)

[![ko-fi](https://www.ko-fi.com/img/donate_sm.png)](https://ko-fi.com/E1E0GU12) [![patreon](https://c5.patreon.com/external/logo/become_a_patron_button.png)](https://patreon.com/cameronjonesweb)


## Description
Seamlessly add a Facebook Page to your WordPress website with the Mongoose Page Plugin! Trusted by more than 30,000 WordPress websites worldwide, the Mongoose Page Plugin is the simplest way to add a Facebook page to your website.

You can add your Facebook page to any widget area with the custom Mongoose Page Plugin widget, with live preview available in the Customizer.

The plugin can be used to add your Facebook page to any post, page or text widget by using the `[facebook-page-plugin]` shortcode to display the plugin wherever you like, as often as you like.

Other features include:

* A shortcode generator on the admin dashboard for easy generating of the shortcode

* Uses your site language by default, but you can display your Facebook page in all 111 languages that Facebook supports, including English, Spanish, Arabic, German, French, Russian and many more

With more than 30,000 installs and over 50 5-star rating, the Mongoose Page Plugin is one of the most popular social media plugins for WordPress.


## Frequently Asked Questions

### The plugin doesn't load properly
The most common causes of the plugin not displaying are an ad blocker, audience restrictions or browser privacy settings.
* Disable or whitelist any ad blocking browser extension
* Disable any audience restrictions in your page settings on Facebook.
* Disable any antivirus software on your device.
* If using Safari, [disable Hide IP From Tracker](https://stackoverflow.com/a/74542146/1672694).

### The plugin loads but doesn't show the facepile or posts
The default height of the plugin is only tall enough to display the main page header. Increase the height until you can see the additional features you want to display.

### Where is the shortcode generator?
As of version 1.9.0, the shortcode generator has moved to Settings > Mongoose Page Plugin > Shortcode Generator

### Is there a Gutenberg block?
Not yet, but it is on our roadmap.


## Screenshots
1. Installation example
2. Example of the new widget introduced in version 1.2.0
3. The new shortcode generator dashboard widget


## Changelog

### 1.9.1 - 27/08/23
* Verified compatibility with WordPress 6.3
* Fixed an issue with changes to tabs not saving in the block widget editor
* Fixed missing image
* Updated SDK version to 17.0

### 1.9.0 - 27/12/22
* Verified compatibility with WordPress 6.1
* Updated plugin landing page to match native WordPress admin styles more closely
* Moved shortcode generator from the dashboard to the plugin page
* Coding standards improvements
* Additional security hardening

### 1.8.3 - 21/03/22
* Verified compatibility with WordPress 5.9
* Fix duplication of a data attribute
* Fix dependencies for the responsive script

### 1.8.2 - 14/07/21
* Verified compatibility with WordPress 5.8, including the block based widgets
* Updated SDK version to 11.0
* Code quality improvements
* Fix RSS news feed
* Remove readme.txt in favour of readme.md

### 1.8.1 - 13/12/20
* No longer escape widget titles
* Verified compatibility with WordPress 5.6

### 1.8.0 - 20/11/20
* Updated SDK version
* Verified compatibility with WordPress 5.5
* Updated supported locales (RIP Pirate English)
* Added iframe embed option

### 1.7.3 - 14/04/20
* Fixed Immediately Invoked Function Expression that was breaking on PHP < 7.0
* Code quality improvements in shortcode render method

### 1.7.2 - 13/04/20
* Improved processing of the widget URL field
* Tested for WordPress 5.4 'Adderley'

### 1.7.1 - 21/06/19
* Rebranded langing page
* Fixed admin resources not being enqueued properly

### 1.7.0 - 03/06/19
* Rebranded under the Mongoose Marketplace umbrella
* Updated RSS feed
* Fixed conflict with Elementor page builder

### 1.6.3 - 30/03/18
* Improved string translations
* Updated Graph API from v2.5 to v2.12
* Fail gracefully when SimpleXML isn't installed
* Fixed changelog link
* Updated the plugin display name to avoid potential trademark issues

### 1.6.2 - 18/11/17
* Changes minimum WordPress version to 4.6 for translations
* Tested for WordPress 4.9 'Tipton'
* Fixed bug with setting the language in the shortcode generator
* Increased accuracy of URL detection in the widget form

### 1.6.1 - 11/04/17
* Adding text domain header
* Adding implementation indicator for debugging

### 1.6.0 - 02/04/17
* New landing page with FAQs to help with onboarding of new users
* Removal of the admin notice review nag
* Localisation of SDK
* Added `events` option to the widget and shortcode generator
* Changed `tabs` setting to checkboxes instead of a dropdown

### 1.5.3 - 25/01/16
* Fixed bug where share button would return `App Not Setup` error

### 1.5.2 - 01/12/15
* Fixed bug where plugin would rerender during scroll on mobile devices

### 1.5.1 - 29/11/15
* Fixed bug where plugin wouldn't rerender
* Fixed bug with languages XML file not loading on installs where the admin is not wp-admin

### 1.5.0 - 23/11/15
* Migrated to object oriented
* Fixed languages XML file being blocked by iThemes Security
* Fixed HTML issue with dashboard widget
* Added script that makes the plugin fully responsive

### 1.4.2 - 23/09/15
* Fixing shortcode not being updated when tabs change in the shortcode generator
* Removing link text parameter and option when `Show Link` is false

### 1.4.1 - 22/09/15
* Fixing shortcode generator using posts instead of tabs
* Verifying compatibility with 4.3.1

### 1.4.0 - 03/09/15
* Options to remove and customise the page link that displays while the plugin loads
* Fixed `undefined index` error when adding a new instance of the plugin in the customizer
* Updated all admin text to be multi-lingual compatible
* Updated `posts` option to `tabs`
* Updated screenshots and example

### 1.3.4 - 13/08/15
* Fixed typo in widget
* Fixed labels in widget
* Changed languages to load from a local file instead of Facebook's Locales XML file. This fixes the issue where approximately 40 languages were supported by Facebook but not for the page plugin, and also users working locally without internet access are now able to change the language from default.
* Re-introduced App ID, while it should not be needed it appears that removing it has affected some sites.

### 1.3.3 - 11/08/15
* Direct access security update
* Verifying compatibility with WP 4.2.4 and WP 4.3
* Fixing bug where some options in the widget would revert to the default instead of false

### 1.3.2 - 25/07/15
* WP 4.2.3 Compatibility
* Upgrading to Graph API 2.4

### 1.3.0 - 02/07/15
* Added hide-cta, small-header and adapt-container-width settings
* Adjusted min height and width

### 1.2.5 - 06/06/15
* Fixed close icon on notice

### 1.2.4 - 05/06/15
* Fixed readme

### 1.2.3 - 04/06/15
* Fixed bug where the admin dashboard and widgets pages would break if the WordPress installation is running on localhost and there is no internet connection

### 1.2.2 - 27/05/15
* Fixed posts option for widget

### 1.2.1 - 27/05/15
* Fixed readme bug

### 1.2.0 - 26/05/15
* Added multilingual support. Language can be specified in the shortcode, otherwise it is retrievd from the site settings.
* Added a shortcode generator dashboard widget to allow easier creation of the shortcode
* Added a custom widget

### 1.1.1 - 14/05/15
* Fixed height bug

### 1.1.0 - 10/05/15
* Added filter to allow calling of shortcodes in the text widget

### 1.0.3 - 28/04/15
* Fixing screenshot issue

### 1.0.1 - 28/04/15
* Cleaning up readme file

### 1.0 - 25/04/15
* Initial release


## Plugin Settings

The Mongoose Page Plugin uses a shortcode to insert the page feed. You set your settings within the shortcode.
`[facebook-page-plugin setting="value"]` 
Available settings: 

`href` (URL path that comes after facebook.com/)

`width` (number, in pixels, between 180 and 500, default 340)

`height` (number, in pixels, minimum 70, default 500)

`cover` (true/false, show page cover photo, default true)

`facepile` (true/false, show facepile, default true)

`tabs` (any combination of [posts, messages, events], default timeline)

`cta` (true/false, hide custom call to action if applicable, default false)

`small` (true/false, display small header (must be true for height to be lower than 130px), default false)

`adapt` (true/false, force plugin to be responsive, default true)

`language` (languageCode_countryCode eg: en_US, language of the plugin, default site language)

`method` (SDK or iframe embed method, default SDK)

* Deprecated Settings *

`posts` (true/false) - posts has been replaced by tabs as of 1.4.0. There is a fallback in place to convert it to tabs

Example: `[facebook-page-plugin href="facebook" width="300" height="500" cover="true" facepile="true" tabs="timeline" adapt="true"]`
This will display a Facebook page feed that loads in the page `facebook.com/facebook` that is 300px wide but adapts to it's container, 500px high, displaying the page's cover photo, facepile and recent posts in the same language as the site. See the screenshots tab for a demonstration of how it will appear


## Filter Reference

`facebook_page_plugin_dashboard_widget_capability`

Changes who can see the shortcode generator on the dashboard. Default: `edit_posts`

`facebook_page_plugin_app_id`

Changes the Facebook App ID.
