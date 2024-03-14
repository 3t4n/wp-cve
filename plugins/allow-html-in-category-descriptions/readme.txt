=== Allow HTML in Category Descriptions ===
Contributors: arno.esterhuizen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SGS5KSM9N4D3Y
Tags: categories, category descriptions, html, filter
Requires at least: 2.5
Tested up to: 5.8
Stable tag: 1.2.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to use unfiltered HTML in your category descriptions by disabling selected WordPress filters.

== Description ==

When you add text to the category description textarea and save the category, WordPress runs content filters that strips out all but the most basic formatting tags.

This plugin disables those filters for roles with the necessary permissions. Any html code you add to the category description will not be stripped out.

This plugin does not do anything other than disable the filters. It does not protect you from entering invalid HTML, nor does it help you create WYSIWYG HTML. You can use the post or page composing screen to help you create the text and formatting. Switch to the 'code' tab and copy the HTML code into the category description field.

== Installation ==

1. Upload `html-in-category-descriptions.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Paste or type HTML code in the category description and save (Refer to "The category description isn't saving. Why?" in the FAQ if you're having problems saving)
1. Enjoy HTML in your category descriptions (Refer to "I see the HTML in the admin panel preview, but not on my website. Why?" in the FAQ if the HTML isn't appearing on your website)

== Frequently Asked Questions ==

= The category description isn't saving. Why? =

1. For security reasons, the role that you're using to edit the description needs to have the "unfiltered_html" capability
1. In single site installations, Administrators and Editors has this capability by default; in a multisite installation, only Super Admins have this capability
1. Refer to [WordPress Roles and Capabilities](https://wordpress.org/support/article/roles-and-capabilities/#unfiltered_html)

= I see the HTML in the admin panel preview, but not on my website. Why? =

1. For the HTML to show up on your website, the theme you're using needs to output the category description
1. Contact your theme developer and refer them to [WordPress Developer Documentation - category_description](https://codex.wordpress.org/Function_Reference/category_description)

= How do I contact you? =

1. **Email Address:** arno.esterhuizen+wordpress-plugins@gmail.com
1. **Subject Line:** Question: WordPress Plugin: Allow HTML in Category Descriptions
1. **Donations:** https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SGS5KSM9N4D3Y

== Screenshots ==

1. Adding HTML code to the category description
1. A preview of the category description in the category list admin screen
1. What the category looks like on the front-end if your theme supports it
(here shown with the Twenty Fourteen theme)

== Changelog ==

= 1.2.3 =
* Change the plugin initialisation after the previous security update went out in a bit of a rush.

= 1.2.2 =
* Address a security vulnerability

= 1.2.1.1 =
* Add a text domain for translation purposes

= 1.2.1 =
* Minor formatting of the plugin code, syntax, etc.
* Added a banner image for the plugin page

= 1.2 =
* A version bump to indicate to WordPress that the plugin was reviewed and tested in the latest version of WordPress
* Made sure that the pre_filters array had corresponding items in the filters array
* Added a donation link

= 1.1 =
* Added a filter array for the textareas admin displays

= 1.0 =
* First release into the wild after helping someone on a forum post

== Upgrade Notice ==

= 1.2.1 =
Upgraded plugin for the latest versions of WordPress.

= 1.2 =
Upgraded plugin for the latest versions of WordPress.

= 1.1 =
Added code to prevent HTML from being stripped in textareas in the admin display.

== Upgrade Notice ==

= 1.2.2 =
This version fixes a minor security related bug.  Upgrade immediately.
