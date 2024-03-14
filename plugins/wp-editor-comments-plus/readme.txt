=== WP Editor Comments Plus ===
Contributors: neosnc
Donate link: https://wordpress.org/plugins/wp-editor-comments-plus/
Tags: comments, comment, editor, ajax, tinymce, async, wysiwyg, tinymce
Requires at least: 3.5.1
Tested up to: 4.5.1
Stable tag: 1.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enhance your site's comments with the built in WordPress TinyMCE editor, inline comment editing and asynchronous comment posting.

== Description ==

WP Editor Comments Plus brings the power and ease of the WordPress content editor TinyMCE to your website's comments.

= Richer Comment Content =
WP Editor Comments Plus enables users to more easily and quickly compose their comments with formatting, images, colors, links and more.

= Configurable Editing =
Logged in users can edit comments after posting them with the TinyMCE editor. This can be set to expire after certain amount of time or disabled entirely.

= Asynchronous Comment Posting =
With WP Editor Comments Plus activated, your site's comments are submitted asynchronously allowing your users an uninterrupted commenting experience.

= Customizable Toolbars =
The editor toolbars in WP Editor Comments Plus can be configured up to 4 rows. Customize each row's buttons to fit your site's needs. Choose from buttons supported in standard WordPress installations by referencing this [list](http://archive.tinymce.com/wiki.php/TinyMCE3x:Buttons/controls). Note: only buttons supported by the standard WordPress TinyMCE plugins are available.

== Installation ==

1. Upload `wp-editor-comments-plus.zip` to the `/wp-content/plugins/` directory
1. Unzip `wp-editor-comments-plus.zip`
1. Activate the plugin through the "Plugins" menu in WordPress
1. That's it! (No configuration needed. Settings can be adjusted to your preference in Settings -> WP Editor Comments Plus Settings.)

== Frequently Asked Questions ==

= Why are there two edit buttons on my comments? =

WordPress adds it's own Edit link when logged in as an Administrator. Subscriber level users will only see WP Editor Comments Plus Edit links when logged in.

= Why do the buttons not match my site / look weird? =

WP Editor Comments Plus does it's best to integrate into your site's theme. Different themes may require some help with CSS classes to look and position buttons correctly. Use the Custom CSS settings to include CSS classes that tell the WP Editor Comments Plus buttons how to look and position themselves.

= Huh, why doesn't this plugin work with my theme? =

Uh oh, it's possible your theme uses different IDs than the default WordPress IDs. To accommodate this, you can update the WP Editor Comments Plus Settings -> WordPress IDs & Classes values with any IDs that don't match the default IDs ( shown in the empty input boxes ). In case this starts to get too technical, feel free to reach out for help in the plugin page's [support](https://wordpress.org/support/plugin/wp-editor-comments-plus) tab.

== Screenshots ==

1. WP Editor Comments Plus installed in the official WordPress Twenty Sixteen theme.
2. Settings options for WP Editor Comments Plus.

== Changelog ==

= 1.1.4 =
Release date: June 3rd, 2016

* Bug fix: Added check to prevent multiple comment updates

= 1.1.3 =
Release date: April 29th, 2016

* Bug fix: Fixed issue with editor content mismatching when multiple editors were opened

= 1.1.2 =
Release date: April 26th, 2016

* Bug fix: Addressed issue with reply button breaking respond form

= 1.1 =
* Enhancement: Added option to hide toolbars by setting them to 'none' (without quotes)
* Bug fix: Added li, strong and em tags to allowed html tags in comments to address issue with formatting being lost

= 1.0.1 =
* Enhancement: Added preformatted to editor formatting dropdown menu
* Bug fix: Fixed cursor jumping to end of line when editing admin text input fields

= 1.0 =
* Initial release
