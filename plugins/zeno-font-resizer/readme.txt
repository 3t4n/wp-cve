=== Zeno Font Resizer ===
Contributors: mpol
Tags: font size, text size, text resizer, font resizer, accessibility
Requires at least: 4.1
Tested up to: 6.4
Stable tag: 1.8.0
License: GPLv2
Requires PHP: 7.0

Zeno Font Resizer allows the visitors of your website to change the font size of your text.

== Description ==

This plugin allows you to give the visitors of your site the option to change the font size of your text.

Features:

* Uses JavaScript and jQuery to set the fontsize.
* Settings are saved in a cookie, so the visitor sees the same fontsize on a revisit.
* Admin page to set which content is being resized, the resize steps and other options.
* There is a classic widget.
* Alternatively, you can add PHP code to a template in your theme, preferably a child theme.
* Simple and Lightweight.

This plugin is a fork of font-resizer with many bugfixes applied and features added.

= Compatibility =

This plugin is compatible with [ClassicPress](https://www.classicpress.net).

= Contributions =

This plugin is also available in [Codeberg](https://codeberg.org/cyclotouriste/zeno-font-resizer).


== Installation ==

1. Upload the directory `zeno-font-resizer` to the `/wp-content/plugins/` directory or install the plugin directly with the 'Install' function in the 'Plugins' menu in WordPress.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the sidebar widget through the 'Appearance / Widgets' menu in WordPress.
4. If you don't want to use the widget, you can use the template code somewhere in your template. Please check the FAQ.
5. Define which content should be resized on the 'Zeno Font Resizer' admin page (optional). If you are not familiar with html and css, select the html option (default). This would resize all the content of your site.

== Screenshots ==

1. A productive example of the widget.
2. Adding the widget.
3. Settings page.

== Frequently Asked Questions ==

= How can I activate the function of the plugin? =
Go to the admin page of the plugin and select your option. If you are not familiar with html and css, select the html option (default). This would resize all the content of your site.

= I click the resizer, but (some of) my fonts don't change in size. =

The plugin expects the CSS of your theme to be set up in a flexible way. When you have a static font-size like 14px or 14pt for your menu or content elements, this will not be affected by the plugin. This way of using font-size is maybe pixel-perfect for the designer, but not accessible for the user, so you should only use it for design elements, like a text overlay for an image.

When you use a percentage, like 100% or 124%, it is dynamic and will follow (inherit) the font-size of the parent (and so up).
The same dynamic counts for setting in em.

= I use font-size in rem in my theme, what do I do? =

The font-size in rem is relative to the font-size of the root <html> element. So you can go to the Settingspage of this plugin,
and set the html element as the element to change the font-size of (default since 1.4.4). Now your rem elements should follow the resizing.

= How can I use the plugin without the widget? =
Use this snippet of PHP code (in your theme or somewhere):

	<?php
		if (function_exists('zeno_font_resizer_place')) {
			zeno_font_resizer_place();
		}
	?>

= How can I use the template code and do stuff with it? =
You can use the parameter '$echo = false' and the function will return the html-string:

	<?php
		if (function_exists('zeno_font_resizer_place')) {
			$font_resizer = zeno_font_resizer_place( false );
			// do stuff with $font_resizer...
		}
	?>

= How can I change the color of the A's? =
With CSS in your theme.
Use something like:

	p.zeno_font_resizer > a {
		color: blue;
	}

= On the widget I see text meant for screen-readers. =

Your theme is missing some necessary CSS for '.screen-reader-text'. Please contact the maker of your theme.
More information can be found in the [Handbook](https://make.wordpress.org/accessibility/handbook/markup/the-css-class-screen-reader-text/) about Accessibility.

= I want to use a shortcode in my content element =

There is an additional plugin on [Codeberg](https://codeberg.org/cyclotouriste/zeno-font-resizer-shortcode) you can use for that.

== Changelog ==

= 1.8.0 =
* 2023-02-15
* Fix security issue (thanks Rio Darmawan).
* Escape more output.

= 1.7.9 =
* 2022-03-24
* Dependency for jquery should be an array.

= 1.7.8 =
* 2021-12-09
* Upgrade js-cookie.js from 2.2.0 to 3.0.1.
* Take some hints from phpcs and wpcs.

= 1.7.7 =
* 2021-02-22
* Fix deprecated jQuery calls with WP 5.6 and jQuery 3.5.

= 1.7.6 =
* 2020-12-02
* Use autoload for all options on new install.

= 1.7.5 =
* 2020-04-13
* Better use of esc_html functions.
* Update support text on admin page.

= 1.7.4 =
* 2019-01-25
* Add CSS for broken themes.

= 1.7.3 =
* 2018-12-30
* Update js-cookie.js from 2.1.3 to 2.2.0.
* Font-size for increase character changed from 1.2em to 1.3em.

= 1.7.2 =
* 2018-07-04
* Add screen-reader-text to widget links.

= 1.7.1 =
* 2017-08-26
* Add callback after font resize with example code.
* Font size has maximum 2 decimals.

= 1.7.0 =
* 2016-10-25
* Update js-cookie to 2.1.3.

= 1.6.3 =
* 2016-04-13
* No need to set the cursor to pointer, it is default behaviour.
* More use of parseFloat.
* Update Donate text.

= 1.6.2 =
* 2016-02-09
* Cast to float so value saved in cookie can be used.

= 1.6.1 =
* 2015-12-04
* Drop pot, nl_NL, they are maintained at GlotPress.

= 1.6.0 =
* 2015-11-29
* Use Settings API.

= 1.5.0 =
* 2015-11-28
* Only support WordPress 3.7+, since they really are supported.
* Add option for Min and Max font size (default 10 and 24 px).
* On click, return false everywhere.
* Add link to FAQ on Settings page.
* Update pot, nl_NL.

= 1.4.6 =
* 2015-09-05
* Change text-domain to slug.
* Add radiobutton for 'body'.
* Update pot, nl_NL.

= 1.4.5 =
* 2015-08-05
* Use h1 header on admin page.
* Update pot, nl_NL.

= 1.4.4 =
* 2015-07-30
* Use 'html' as default element instead of 'body' for compatibility with rem sizes.

= 1.4.3 =
* 2015-05-31
* Add About text.
* Update pot and nl_NL.

= 1.4.2 =
* 2015-05-26
* Add 'return false' on click event.

= 1.4.1 =
* 2015-05-24
* Add $echo parameter to template code.

= 1.4.0 =
* 2015-05-24
* Redo widget properly.
* Update pot, nl_NL.

= 1.3.0 =
* 2015-05-22
* Forked from font-resizer.
* Capability for settingspage is manage_options.
* Radio buttons have working labels.
* Delete cookieTime option on uninstall.
* Add Copyright notice.
* Add Settings link to main Plugins page.
* Don't use WP_PLUGIN_URL for JavaScript enqueue.
* Add version to JavaScript enqueue.
* Only enqueue on frontend.
* Load JavaScript in footer.
* Update jQuery.cookie to js-cookie 1.5.1.
* Integrate main.js into jquery.fontsize.js to trim down on loaded files.
* Move screenshots from trunk to assets.
* Set list-style to none.
* Add href attribute for accessibility, tabbing works now.
* Add option to define your own letter, default is 'A'.
* Add header to the widget, but not the template function.
* Add maximum and minimum sizes (5 steps from startsize).
* Add possibility for translation.
* Add pot, nl_NL.

= 1.2.3 =
* Widget bug fix

= 1.2.2 =

* Added banner img

= 1.2.1 =

* Nothing relevant

= 1.2.0 =

* Fixed some deprecated functions

= 1.1.9 =

* Updated readme

= 1.1.7 =

* Little jquery bugfix for function ownid

= 1.1.6 =

* Fixed PHP problem

= 1.1.5 =

* Fixed problem with Internet Explorer

= 1.1.4 =

* Added option for cookie save time to admin pane
* Edited install instructions

= 1.1.3.1 =

* Added an answer to FAQ

= 1.1.3 =

* Fixed JavaScript issue with qTranslate
* Refactured jQuery scripts

= 1.1.2 =
* Added an option for changing the font resize steps
* Added comments to source code
* Cleaned up source code
* Changed css classes of the visible resizer element in the sidebar

= 1.1.1 =
* Bugfix for different directory structure (like language structure, yourdomain.tld/en/ for english)

= 1.1.0 =
* Added menu page
* Changed default resizable element from a div with id innerbody to body element
* Added uninstall hooks
* Added some answer to FAQ

= 1.0.0 =
* First stable version
* Publish the plugin


