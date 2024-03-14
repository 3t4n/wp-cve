=== Show Content Only ===
Contributors: katzwebdesign, katzwebservices, BrashRebel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=zackkatz%40gmail%2ecom&item_name=Show%20Content%20Only&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: content, formatting, strip formatting, strip content, text, SEO, keyword research, keyword research tool, google, keywords, tool
Tested up to: 4.0
Requires at least: 2.5
Stable tag: 1.3.1

Display only the post or page content, without a theme, sidebars, scripts or stylesheets.

== Description ==

Enables you to show only a post or page's content, without sidebars, footers, and other content.

There are many different reasons for wanting to show just the content, but there's never been such an easy way to do it. This plugin adds a box in the post and page editor that provides you with five links:

* Content Only
* Content Only + Styles
* Content with Tags
* Content with Categories
* Content with Categories & Tags

This is very helpful in conjunction with the <a href="https://adwords.google.com/select/KeywordToolExternal" rel="nofollow">Google Keyword Tool</a>, so that Google only analyses the content of your post or page, not the surrounding context as well.

[Read more about the plugin](https://katz.co/content-only/).

> ####You may also be interested in:
> * <strong><a href="http://wordpress.org/extend/plugins/rich-text-tags/">Rich Text Tags</a></strong> - Enable rich text editing of tags, categories, and taxonomies. Add value to your tag & category pages and improve your website' SEO.

== Screenshots ==

1. Edit Posts Page (pre version 1.3)

== Changelog ==

= 1.3.1 =
* Updated translation files

= 1.3 =

* Added links meta box to all public, registered post types
* Formatted the links in nice little buttons
* Refactored lots of code for clarity, standards compliance and greater flexibility

= 1.2 =
* Added option to print styles by adding `css=1` to the URL
	- When printing styles, `post_class()` classes are added to `<body>`
* Added option to print scripts by adding `js=1` to the URL
* Applied `the_content` filter to content by default. Manually add `plain=1` to the URL to not have `the_content` applied.

= 1.1.1 =
* Added internationalization support with `load_plugin_textdomain`
* Improved ReadMe.txt

= 1.1 = 
* Modified so that if the post hasn't been published or saved as a draft, links are not available.

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.3.1 =
* Updated translation files

= 1.3 =

* Added links meta box to all public, registered post types
* Formatted the links in nice little buttons
* Refactored lots of code for clarity, standards compliance and greater flexibility

= 1.2 =
* Added option to print styles by adding `css=1` to the URL
	- When printing styles, `post_class()` classes are added to `<body>`
* Added option to print scripts by adding `js=1` to the URL
* Applied `the_content` filter to content by default. Manually add `plain=1` to the URL to not have `the_content` applied.

= 1.1.1 =
* Added internationalization support with `load_plugin_textdomain`

== Frequently Asked Questions ==

= How do I use this plugin? =

* Add and activate the plugin
* In your Edit Post and Edit Page pages, you will see a new meta box titled "Show Content Only Links". Use these links to link to content-only versions of your page.
* Alternatively, you can simply add `?content-only=1` or `&content-only=1` to your page's URL for it to be shown as content only.

== Installation ==
* Add and activate the plugin
* In your Edit Post and Edit Page pages, you will see a new meta box titled "Show Content Only Links". Use these links to link to content-only versions of your page.
* Alternatively, you can simply add `?content-only=1` or `&content-only=1` to your page's URL for it to be shown as content only.