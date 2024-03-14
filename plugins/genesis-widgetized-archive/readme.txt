=== Genesis Widgetized Archive ===
Contributors: daveshine, deckerweb
Donate link: https://www.paypal.me/deckerweb
Tags: genesis, genesiswp, genesis framework, archive, archives, archive page, page template, pages, widgetized, widget, widgets, deckerweb
Requires at least: 3.2
Tested up to: 5.1
Stable tag: 1.2.1
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php

Finally, use widgets to maintain & customize your Archive Page Template in Genesis Framework and Child Themes to create archive/sitemap listings.

== Description ==

> #### New Flexibility plus Enhanced User Experience
> Take control over **Your Archive Pages** in *Genesis* again! Use the ever popular way of *Widgets* to build your own archive listing or sitemap-like content. Use up to three columns that are responsive and enable automatically. Don't lose your archive/sitemap creations if you ever switch the *Genesis* child theme.
>
> A great helper tool for *Genesis Child Themes*!

**Please note:** The plugin requires the *Genesis Theme Framework* (GPL-2.0+), a paid premium product released by StudioPress/ Copyblogger Media LLC (via studiopress.com).

Plus: A premium *PRO Version* of this plugin will be released in the future...! :-)

= Advantages & Benefits =
* Avoids long pages & blog posts listings that seem to happen a lot with the default template...
* Far more possibilities for your archive pages: different listings of site menus, various site content, authors - plus search form, images etc.
* Make sitemap-like pages very easily - use widgets by WordPress, Genesis or third-party plugins
* Easily customizeable for any webmaster!
* Works across all Genesis child themes - so you can switch your "skin" but not loosing this tool :-)
* Support Genesis 2.0+ and HTML5 enabled child themes!
* Ideal for multilingual websites (for example with "WPML"): Much better handling of archive-/ sitemap-like pages for different languages. -- [See bottom of FAQ section here](http://wordpress.org/plugins/genesis-widgetized-archive/faq/) for more info on that.

= General Features =
* Small & lightweight plugin tool: Just activate plugin, place your widgets and you're done!
* Adds up to three new widget areas (Sidebars) for the "Archive Page Template"
* Adds very few CSS styles for the content area to properly divide widgets with some more space (all other styling is recommended via your child theme) and to enable responsive content columns (if more than one widget area is active)
* Customizeable via 2 action hooks: if ever needed you can add content before and after the widgetized section
* Customizeable via 6 filters: if ever needed you can customize the widget titles and descriptions
* Fully internationalized! Real-life tested and developed with international users in mind!
* Fully WPML compatible!
* Fully Multisite compatible, you can also network-enable it if ever needed (per site use is recommended).
* Tested with WordPress versions 3.4 branch and new 3.5 branch - also in debug mode (no stuff there, ok? :)

Please note: The plugin requires the Genesis Theme Framework.

= Localization =
* English (default) - always included
* German (de_DE) - always included
* .pot file (`genesis-widgetized-archive.pot`) for translators is also always included :)
* Easy plugin translation platform with GlotPress tool: [Translate "Genesis Widgetized Archive"...](http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-widgetized-archive)
* *Your translation? - [Just send it in](http://genesisthemes.de/en/contact/)*

[A plugin from deckerweb.de and GenesisThemes](http://genesisthemes.de/en/)

= Feedback =
* I am open for your suggestions and feedback - Thank you for using or trying out one of my plugins!
* Drop me a line [@deckerweb](http://twitter.com/deckerweb) on Twitter
* Follow me on [my Facebook page](http://www.facebook.com/deckerweb.service)
* Or follow me on [+David Decker](http://deckerweb.de/gplus) on Google Plus ;-)

= More =
* My other 'Genesis Widgetized' plugins: [*"Genesis Widgetized Not Found & 404"*](http://wordpress.org/plugins/genesis-widgetized-notfound/) plus [*"Genesis Widgetized Footer"*](http://wordpress.org/plugins/genesis-widgetized-footer/)
* [Also see my other plugins](http://genesisthemes.de/en/wp-plugins/) or see [my WordPress.org profile page](http://profiles.wordpress.org/daveshine/)
* Tip: [*GenesisFinder* - Find then create. Your Genesis Framework Search Engine.](http://genesisfinder.com/)
* Hey, come & join the [Genesis Community on Google+ :)](http://ddwb.me/genesiscommunity)

== Installation ==

**NOTE:** Only works with *Genesis Framework* (GPL-2.0+) as the parent theme. This is a paid premium product by StudioPress/ Copyblogger Media LLC, available via studiopress.com.

= Installation of Plugin: =
1. Upload `genesis-widgetized-archive` folder to the `/wp-content/plugins/` directory -- or just upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
2. Activate the plugin through the "Plugins" menu in WordPress
3. Edit a page and apply the "Archive" page template.
4. Then go to the "Widgets" admin page and configure your widgets for the "Archive Page Template #1 - #3".
5. Enjoy your new archive page :-)

**Note:** The "Genesis Framework" is required for this plugin in order to work. If you don't own a copy it yet, this premium parent theme has to be bought. More info about that you'll find here: http://ddwb.me/getgenesis

**Usage:** The plugin adds 3 new widget areas on your Widget Admin. Just place any widget in the #1 area and if you want to have columns use the optional 2nd and/ or 3rd area. That's all. Enjoy!

**Also note:** This plugin needs NO settings page. You only need your default page edit screen and your widgets admin. Simplifying and no overbloat - you get the idea :).

**Own translation/wording:** For custom and update-secure language files please upload them to `/wp-content/languages/genesis-widgetized-archive/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that, just use a language file like `genesis-widgetized-archive-en_US.mo/.po` to achieve that (for creating one see the tools on "Other Notes").

== Frequently Asked Questions ==

= Why there are 3 new widget areas, isn't ONE enough? =
One should be enough for a lot of use cases. However, having 3 widget areas enables you to use up to 3 columns (see below), which makes sense to make it all more viewable. For example: you've given your archive page the 'Full Width Content" layout option and use widgets in all 3 areas. Result: perfectly layered 3-column layout consisting of widgets. How cool's that? :)

= How do the columns work? =
Column layouts are enabled automatically the same time you place any widget in the second or third widget area (implied the 1st is also active). The needed very few CSS styles are provided by the plugin (unfortunately not all child themes have these column classes included by default) and ONLY enqueued for that archive page (so very lightweight still!).

* 1st area **OR** 2nd area **OR** 3rd area active: *one column layout*
* 1st + 2nd **OR** 1st + 3rd **OR** 2nd + 3rd areas active: *2-column layout*
* all 3 areas active: *3-column layout*

= Are the widgetized areas responsive? =
Yes, of course they are! If your child theme is already responsive and you use columnized areas they just adapt to your viewport nicely. Additionally, the break point for the 2-column and 3-column layout is set to 640px: so on smaller devices/viewports the columns automatically switch to an 100% width. -- You can change all CSS media queries with `!important` or own styles (see below!).

Note, if your child theme isn't responsive yet these CSS media queries won't have any effect and also do no harm :-).

= How can I remove the 2nd and 3rd widget areas? =
That's possible of course! Just add one or both of the following constants to your child theme's `functions.php` file - or to a functionality plugin instead (recommended!):

`
/** Genesis Widgetized Archive: Remove Second Widget Area */
define( 'GWAT_NO_SECOND_WIDGET_AREA', true );

/** Genesis Widgetized Archive: Remove Third Widget Area */
define( 'GWAT_NO_THIRD_WIDGET_AREA', true );
`

= How can I style the content/ widget areas? =
It's all done via your child theme. Maybe you need to add an `!important` to some CSS rules here and there... For more even better styling I included some IDs and classes:

* Each widget in all areas gets an additional class: `.gwat-archive` -- which allows to set some common styles for all widgets on the appropriate page!

* *"Archive Page Template #1" section:*
 * whole content area, before & after all widgets is wrapped in a div with the ID: `#gwat-archive-area-one` plus class `.gwat-archive-area`
 * each widget in this area has its own ID depending on the widget (regular WordPress behavior!)
 * each widget gets an additional class: `.gwat-archive-one` -- which allows to set some common styles for all widgets in this 1st area

* *"Archive Page Template #2" section (optional):*
 * whole content area, before & after all widgets is wrapped in a div with the ID: `#gwat-archive-area-two` plus class `.gwat-archive-area`
 * each widget in this area has its own ID depending on the widget (regular WordPress behavior!)
 * each widget gets an additional class: `.gwat-archive-two` -- which allows to set some common styles for all widgets in this 2nd area

* *"Archive Page Template #3" section (optional):*
 * whole content area, before & after all widgets is wrapped in a div with the ID: `#gwat-archive-area-three` plus class `.gwat-archive-area`
 * each widget in this area has its own ID depending on the widget (regular WordPress behavior!)
 * each widget gets an additional class: `.gwat-archive-three` -- which allows to set some common styles for all widgets in this 3rd area

If that's still not enough, you can even enqueue your own style, an action hook is included for that: `gwat_load_styles` -- This hook fires within the WordPress action hook `wp_enqueue_scripts` just after properly enqueueing the plugin's styles and only if at least one of both widgets is active, so it's fully conditional!

= How can I add own stuff before & after the widgetized section but within #content? =
You guess it, it's just possible :). I have included 2 action hooks to achieve that. For example this could be useful for some admins who use more than one archive page or in general for Multisite installs.

**gwat_before_widgetized_area**

Example code to add stuff before the plugin's widgetized section:
`
add_action( 'gwat_before_widgetized_area', 'custom_content_before_widgetized_area' );
/** Genesis Widgetized Archive: Add custom stuff before widgetized area */
function custom_content_before_widgetized_area() {
	// Your specific before code here...
}
`

**gwat_after_widgetized_area**

Example code to add stuff after the plugin's widgetized section:
`
add_action( 'gwat_after_widgetized_area', 'custom_content_after_widgetized_area' );
/** Genesis Widgetized Archive: Add custom stuff after widgetized area */
function custom_content_after_widgetized_area() {
	// Your specific after code here...
}
`

If needed, add such code snippets to your child theme's `functions.php` file or via the preferred way, a functionality plugin or a code snippets plugin.

**Note:** Only if these hooks are in use a div container with the class `gwat-before-widgetized` (for 'before') respectively `gwat-before-widgetized` (for 'after') is wrapped around the hook's content then.

= Could I disable the Shortcode support for widgets? =
Of course, it's possible! Just add the following constant to your child theme's `functions.php` file or to a functionality plugin:
`
/** Genesis Widgetized Archive: Remove Widgets Shortcode Support */
define( 'GWAT_NO_WIDGETS_SHORTCODE', true );
`
Some webmasters could need this for security reasons regarding their stuff members or for whatever other reasons... :).

= How can I customize the widget titles/strings in the admin? =
I've just included some filters for that - if ever needed (i.e. for clients, branding purposes etc.), you can use these filters:

**gwat_filter_archive_one_widget_title** - default value: "Archive Page Template #1"

**gwat_filter_archive_one_widget_description** - default value: "This is the first widget area for the Archive Page Template (bundled with the Genesis Framework)."

The same principles apply for '#2' and '#3'.

Here's an example code for changing one of these filters:
`
add_filter( 'gwat_filter_archive_one_widget_title', 'custom_archive_one_widget_title' );
/**
 * Genesis Widgetized Archive: Custom Archive Page Template Widget Title
 */
function custom_archive_one_widget_title() {
	return __( 'Custom Archive Page', 'your-child-theme-textdomain' );
}
`


**Final note:** I DON'T recommend to add customization code snippets to your child theme's `functions.php` file! **Please use a functionality plugin or an MU-plugin instead!** This way you are then more independent from child theme changes etc. If you don't know how to create such a plugin yourself just use one of my recommended 'Code Snippets' plugins. Read & bookmark these Sites:

* [**"What is a functionality plugin and how to create one?"**](http://wpcandy.com/teaches/how-to-create-a-functionality-plugin) - *blog post by WPCandy*
* [**"Creating a custom functions plugin for end users"**](http://justintadlock.com/archives/2011/02/02/creating-a-custom-functions-plugin-for-end-users) - *blog post by Justin Tadlock*
* DON'T hack your `functions.php` file: [Resource One](http://thomasgriffinmedia.com/custom-snippets-plugin/) - [Resource Two](http://thomasgriffinmedia.com/blog/2012/09/calling-on-the-wordpress-community/) *(both by Thomas Griffin Media)*
* [**"Code Snippets"** plugin by Shea Bunge](http://wordpress.org/plugins/code-snippets/) - also network wide!
* [**"Code With WP Code Snippets"** plugin by Thomas Griffin](https://github.com/thomasgriffin/CWWP-Custom-Snippets) - Note: Plugin currently in development at GitHub.
* [**"Toolbox Modules"** plugin by Sergej Müller](http://wordpress.org/plugins/toolbox/) - see also his [plugin instructions](http://playground.ebiene.de/toolbox-wordpress-plugin/).

All the custom & branding stuff code above can also be found as a Gist on GitHub: https://gist.github.com/4106349 (you can also add your questions/ feedback there :)


= How can I use the advantages of this plugin for multlingual sites? =
(1) In general: You may use it for "global" widgets.

(2) Usage with the "WPML" plugin:
Widgets can be translated with their "String Translation" component - this is much easier than adding complex information/instructions to the 404 error or search not found pages for a lot of languages...

You can use the awesome ["Widget Logic"](http://wordpress.org/plugins/widget-logic/) plugin (or similar ones) and add additional paramaters, mostly conditional stuff like `is_home()` in conjunction with `is_language( 'de' )` etc. This way widget usage on a per-language basis is possible. Or you place in the WPML language codes like `ICL_LANGUAGE_CODE == 'de'` for German language. Fore more info on that see their blog post: http://wpml.org/2011/03/howto-display-different-widgets-per-language/

With the following language detection code you are now able to make conditional statements, in the same way other WordPress conditional functions work, like `is_single()`, `is_home()` etc.:
`
/**
 * WPML: Conditional Switching Languages
 *
 * @author David Decker - DECKERWEB
 * @link   http://twitter.com/deckerweb
 *
 * @global mixed $sitepress
 */
function is_language( $current_lang ) {

	global $sitepress;

	if ( $current_lang == $sitepress->get_current_language() ) {
		return true;
	}
}
`

*Note:* Be careful with the function name 'is_language' - this only works if there's no other function in your install with that name! If it's already taken (very rare case though), then just add a prefix like `my_custom_is_language()`.

--> You now can use conditionals like that:

`
if ( is_language( 'de' ) ) {
	// do something for German language...
} elseif ( is_language( 'es' ) ) {
	// do something for Spanish language...
}
`

== Screenshots ==

1. Genesis Widgetized Archive: up to 3 additional widget areas/sidebars - here with some example widgets placed in... ([Click here for larger version of screenshot](https://www.dropbox.com/s/lebqioqhbup3mza/screenshot-1.png))

2. Genesis Widgetized Archive: the plugin in action on a live site - displaying here the widgets in area #1 and #2, therefore it switches to a 2-column layout. ([Click here for larger version of screenshot](https://www.dropbox.com/s/11tvhbvbxjvifla/screenshot-2.png))

3. Genesis Widgetized Archive: don't forget to set the 'Archive' template for a page via page edit screen. ([Click here for larger version of screenshot](https://www.dropbox.com/s/q6vpggr1jno16ps/screenshot-3.png))

4. Genesis Widgetized Archive: help tab for the plugin. ([Click here for larger version of screenshot](https://www.dropbox.com/s/3kkpw7tzppv9qg2/screenshot-4.png))

== Changelog ==

= 1.2.1 (2013-09-05) =
* UPDATE: Tweaked widths of the columns in the packaged CSS styles to improve compatibility with even more child themes.

= 1.2.0 (2013-09-01) =
* NEW: Added support for Genesis 2.0+ when HTML5 is supported. That means: the widgetized content area gets hooked properly into the new G2.0 HTML5 hooks. (Note: This plugin is fully compatible with any Genesis XHTML child theme that didn't modify the archive template.)
* UPDATE: Loaded stylesheets now uses the WordPress convention for file names: `gwat-styles.min.css` (`gwat-html5-styles.min.css`) is the minified default version, plus, `gwat-styles.css` (`gwat-html5-styles.css`) is now the development version. If `WP_DEBUG` or `SCRIPT_DEBUG` constants are `true`, the dev styles will be loaded. This makes development/ customizing & debugging a lot easier! :)
* UPDATE: Improved the archive page template check and filter function/ logic.
* UPDATE: Improved translation loading.
* CODE: Minor code optimizations, plus code/documentation updates & improvements.
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.1.0 (2012-12-13) =
* *Maintenance release*
* UPDATE: Added the class placeholder to widget registrations to fullfill WordPress standard for Widgets API.
* CODE: Some code/documentation updates & improvements.
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.0.0 (2012-11-19) =
* Initial release

== Upgrade Notice ==

= 1.2.1 =
Maintenance release: Tweaks for the packaged CSS styles, regarding column widths to improve child theme compatibility.

= 1.2.0 =
Several additions & improvements: Added Genesis 2.0+/ HTML5 support. Minor code/documentation updates & improvements. Updated German translations plus the .pot file for all translators!

= 1.1.0 =
Maintenance release: Improved Widget Area registration. Some code/documentation updates & improvements. Updated German translations plus the .pot file for all translators!

= 1.0.0 =
Just released into the wild.

== Plugin Links ==
* [Translations (GlotPress)](http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-widgetized-archive)
* [User support forums](http://wordpress.org/support/plugin/genesis-widgetized-archive)
* [Code snippets archive for customizing, GitHub Gist](https://gist.github.com/4106349)

== Donate ==
Enjoy using *Genesis Widgetized Archive*? Please consider [making a small donation](https://www.paypal.me/deckerweb) to support the project's continued development.

== Translations ==

* English - default, always included
* German (de_DE): Deutsch - immer dabei! [Download auch via deckerweb.de](http://deckerweb.de/material/sprachdateien/genesis-plugins/#genesis-widgetized-archive)
* For custom and update-secure language files please upload them to `/wp-content/languages/genesis-widgetized-archive/` (just create this folder) - This enables you to use fully custom translations that won't be overridden on plugin updates. Also, complete custom English wording is possible with that as well, just use a language file like `genesis-widgetized-archive-en_US.mo/.po` to achieve that.

**Easy plugin translation platform with GlotPress tool:** [**Translate "Genesis Widgetized Archive"...**](http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-widgetized-archive)

*Note:* All my plugins are internationalized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/), which works fine on Windows, Mac and Linux.

== Idea Behind / Philosophy ==
I never really enjoyed the bundled "Archive" page template in Genesis. So I always wanted this template/ area a bit more easily customizeable since I first worked with Genesis! Widgets in WordPress are powerful and allow for adding really diverse and custom stuff - all in a very simple and user-friendly way. The approach of this plugin is to bring more power to the webmasters and users and help avoid other "archive" or "sitemaps" plugins and instead use the powerful tools from WordPress and Genesis that are already there. This plugin here works primarily as a 'helper' or 'bridge' plugin to just do that :).