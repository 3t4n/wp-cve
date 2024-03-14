=== Forget About Shortcode Buttons ===
Contributors: DesignsAndCode, codeamp
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZAHPNUHNUBQJY&lc=GB&item_name=Ross%20Morsali&item_number=wordpress%2dplugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: css, button, shortcode, edit, visual editor, buttons, wysiwyg, post editor, tinymce, shortcodes, button editor, wysiwyg
Requires at least: 4.2
Tested up to: 6.4
Stable tag: 2.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A visual way to add CSS buttons in the rich text editor and to your themes.

== Installation ==

1. Upload the entire `forget-about-shortcode-buttons` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

For basic usage, you can also have a look at the [plugin homepage](http://www.designsandcode.com/551/wordpress-forget-about-shortcode-buttons/).

== Frequently Asked Questions ==

Ask me some questions!

== Screenshots ==

1. Some of the buttons you can create in the Post Editor
2. Edit button properties popup
3. Add an icon to your button
4. Buttons on the frontend in the WP 2014 theme


== Changelog ==

= 2.1.3 =
* Fix - a potential security issue with nonce validation for authenticated users

= 2.1.2 =
* Fix - JS issues when no TinyMCE instance is loaded on the page
* Fix - better conditional loading of CSS and JS

= 2.1.1 =
* Fix - various issues with loading & the TinyMCE Editor

= 2.1.0 =
* New - added support for FASC buttons in WP 4.8 widget areas :)
* Fix - an issue with button styles in the admin area, only when they were selected (this is due to some new CSS classes being added in WP 4.8)
* Fix - some copy / paste issues and buttons losing their styles (WP 4.8 bug)
* Fix - a compatibility with other TinyMCE related plugins - FASC was extending the view object incorrectly
* Fix - some issues on the `PostProcess` TinyMCE event, made sure FASC only handles this when there are FASC buttons to be handled

= 2.0.1 =
* Fix - an issue with the border effect on glossy buttons
* Fix - remove `box-shadow` properties from FASC buttons by default, some themes such as twentyseventeen add this to all anchor tags
* Fix - remove button rollover/hover effects from TinyMCE editor
* Fix - some issues when using multiple buttons in a post, and their setting not being saved/retrieved correctly

= 2.0.0 =
* Rewritten the core to use WP standards - no more iframes - much quicker to load
* New - integrated with WP Views - inline toolbars for editing and deleting - more options to come
* New - UI refresh - should make it easier to edit buttons as you are going through button options - the new UI also paves the way for some upcoming new features :)
* New - Added bold, italic & strikethrough options from within the button editor - these are also saved to your templates
* New - nofollow option
* New - Add icons after buttons
* Fix - a bug where the popup wasn't loading / showing forbidden
* Fully backwards compatible with buttons created in 1.x

= 1.1.2 =
* Security Update – XSS patch

= 1.1.1 =
* Fix - "Insert button" icon added for custom post types
* Tested up to WP 4.4

= 1.1.0 =
* Save buttons!
* Prevent directory listing of folders by adding blank index.php files to all folders - silence is golden!

= 1.0.4 =
* Bug fix - properly use WP versioning and cache busting methods - upgrading to 1.0.3 caused some layout errors for some users as assets were being loaded from the cache - should be no more problems regarding caching and updating the plugin now

= 1.0.3 =
* Added [Font Awesome Icons](http://fortawesome.github.io/Font-Awesome/icons/) :)
* Updated Icon Selector UI - no scrolling to find the icon you just used! :)

= 1.0.2 =
* Added subtle rollovers to buttons
* Improved HTML output - removed nearly all cases of using the `data-` attributes on buttons - much cleaner code
* Optimized JS functions for performance

= 1.0.1 =
* JS error Fix for IE9
* prefixed the .rounded css classes

= 1.0.0 =
* Initial Release


== Upgrade Notice ==

* Users from 1.x should have no issues updating to 2.x - CSS & code have remained the same


== Description ==

**Compatible with the WordPress Classic Editor, Gutenberg integration coming soon :)**

**Feature requests/ideas welcome!**

Forget About Shortcode (FASC) Buttons are a visual way to add CSS buttons in the rich text editor and to your themes.  Instead of adding shortcodes in the post editor, you insert real, styled buttons - making the process of adding buttons to your posts and pages much more natural whilst improving the appearance and usability of the post editor.

https://www.youtube.com/watch?v=BA9ruSe-i1A

FASC Buttons tries to be as clean as possible with the html, only using data attributes for necessary features - the buttons you use in the post editor use the exact same classes as on the front end of your site with no extra or hidden markup to make them behave this way.

* New - add FASC buttons to widgets in WP 4.8 :)

* Editing a button couldn't be easier - just double click a button and an options panel appears allowing you to completely reconfigure the current button - beats tedious shortcodes and using precise syntax!

* Utilises the latest CSS properties for styling

* Includes Dashicons & [Font Awesome Icons](http://fortawesome.github.io/Font-Awesome/icons/) to add to your buttons and use in your themes.

**Your ratings mean a lot - if you like the plugin please consider leaving a review.**

**This plugin is only compatible with WordPress 4.2 and upwards (download version 1.x for compatibility with WP 3.9 - 4.1)**


= Links =

* [FASC Buttons Discussion](http://www.designsandcode.com/551/wordpress-forget-about-shortcode-buttons/)
* [FASC Buttons Video Demo](https://www.youtube.com/watch?v=BA9ruSe-i1A)

