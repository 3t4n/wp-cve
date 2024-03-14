=== Bop Search Box Item Type For Nav Menus ===
Contributors: joe_bopper
Tags: bop, nav, menu, nav menu, nav menus, nav menu item type, search, search box, navigation, customizer
Requires at least: 3.4
Tested up to: 4.8
Stable tag: 1.6.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds search box as a choice of item in navigation menus admin area.

== Description ==

Adds search box as a choice of item in navigation menus admin area.

Features include:


* **now compatible with the WordPress appearance customizer**,
* search box available as a choice in the admin area for navigation menus,
* as many boxes can be added to a nav menu as one likes,
* search boxes can be added as children of other menu items (useful for, e.g., mega-menus),
* capacity to change button text, placeholder and css classes from the admin area to each search box,
* hooks to modify output (see FAQ and/or code comments),
* very lightweight,
* in keeping styles, html, behaviour, etc., with wordpress defaults,
* works straight out of the box, no configuration needed.


= Those wanting to translate this plugin, please do. =

I believe the plugin is in a translatable state and I'll do everything I can to help in translation. It should be relatively quick as there isn't much text to the plugin.

== Installation ==

Simply install and search box should appear as an option in the *Appearance > Menus* section of the admin area upon activation. No configuration needed.

If it fails to appear, open the screen options tab in your Menus admin page and check Search Box.

 

**N.B.** The search box may not appear stylistically as desired in your menu. This would be because of a conflict between display of the search form and the display of the menu in the theme you are using. This plugin does not assume anything of your theme, nor try to impose anything upon your theme (i.e. no added frontend CSS or JS). It only provides the functionality as stated.

For assistance in how you can change/fix the outputted design, see [this support post](https://wordpress.org/support/topic/search-box-and-submit-button-is-not-in-the-same-line?replies=3#post-7423986).

For suggestions on designs, see [this codepen collection](http://codepen.io/collection/DBaJMB/). Thanks to (Das)[https://wordpress.org/support/profile/adwaitatechnologies].

== Frequently Asked Questions ==
= Q: The items are behaving weirdly in the WordPress appearance customizer? =

A: The customizer is still in active development, especially for menus, at the time of writing this and any odd behaviour is likely because of that. Nonetheless, if you do experience any issues, let me know on the support forum. I still recommend using *Appearance > Menus*

= Q: The search box menu items are being called custom links in the editor? =

A: This is a known issue with the customizer (at the time of writing). Ignore it, they still work just fine.

= Q: This plugin is causing my site to fail an HTML validator. Why is that? =

A: It is not this plugin but WordPress itself that is causing this issue. When using more than one WordPress HTML4 search form, there is a conflict as id attributes are repeated in the same document (not allowed in HTML). The only solutions to this are to only use one search form per page, or add support for HTML5 search forms to your theme. I advise the latter, see [here](https://wordpress.org/support/topic/placholder-in-non-html5-template?replies=3#post-7813012) to find out how this is done.

Alternatively,

A: There is an issue with your theme's nav walker class. Probably worth getting another theme/if it's yours, fixing it.

= Q: I'd like a placeholder in my search input field, how would I go about this? =

A: This is set via the *Attribute Title* field in the menu item editor in the admin area. However, if your theme doesn't support html5 search forms, this won't work. See [here](https://wordpress.org/support/topic/placholder-in-non-html5-template?replies=3#post-7813012) for a support post on how to enable HTML5 support. Note that there are a few id and class changes when going to HTML5 and that if your theme's css/js uses the old ones, you made need to make a few small fixes.

= Q: Annoying html class change in version 1.4.0? =

A: This has been rectified, but depending on when you downloaded v1.4.0, it may be incorrect on your system.

See this support question for more info and a fix: https://wordpress.org/support/topic/please-dont-modify-prev-used-classes-in-the-plugin-it-breaks-developed-theme

= Q: How do I modify the output of the search box? =

A: There are a number of filters available for the output of the search box and they are written about below. The most comprehensive method is to use the filter hook *get_nav_search_box_form* and return the html you want to see. For example:

`add_filter( 'get_nav_search_box_form', function( $current_form, $item, $depth, $args ){
  $new_form = '...my_html...';
  return $new_form;
}, 10, 4 );`

Keep in mind that this is being accessed as part of a walk and that $item, $depth and $args are the same as in [Walker_Nav_Menu::start_el()](https://developer.wordpress.org/reference/classes/walker_nav_menu/start_el/). Try to use some of the features demonstrated in the code there.

= Q: How do I hide/remove the search submit button (i.e. use enter key to submit only)? =

A: The filter hook *bop_nav_search_show_submit_button* will do the job of removing. Use:

`add_filter( 'bop_nav_search_show_submit_button', function( $bool, $item, $depth, $args ){
  $bool = false;
  return $bool;
}, 10, 4 );`

in your theme's *functions.php* file - or other similarly suitable php file.
If you wish to strictly hide the button (i.e. keep outputting html but have it invisible), use

`.bop-nav-search input[type="submit"]{
  display: none;
}`

in your theme's *style.css* - or other similarly suitable css file. Note that `bop-nav-search` is the default class applied list item, so if it's changed, the style rule will need changing accordingly.

= Q: How do I hide/change/remove the screen reader text (i.e. the possibly invisible text before the input box)? =

A: The filter hook *bop_nav_search_screen_reader_text* will do the job of removing or changing the text. Use:

`add_filter( 'bop_nav_search_screen_reader_text', function( $text, $item, $depth, $args ){
  $text = ''; //for nothing
  $text = __( '<span class="screen-reader-text">The text you want</span>', 'myslug' ); //to change - the __() is for theme translation
  return $text;
}, 10, 4 );`

in your theme's *functions.php* file - or other similarly suitable php file.
The output should be hidden in a well written theme as it has screen-reader-text class. However, if this is not the case, you may well wish to add

`.bop-nav-search .screen-reader-text {
	clip: rect(1px, 1px, 1px, 1px);
	height: 1px;
	overflow: hidden;
	position: absolute !important;
	width: 1px;
}`

or simply,

`.screen-reader-text {
	clip: rect(1px, 1px, 1px, 1px);
	height: 1px;
	overflow: hidden;
	position: absolute !important;
	width: 1px;
}`

in your theme's *style.css* - or other similarly suitable css file. Note that `bop-nav-search` is the default class applied list item, so if it's changed, the style rule will need changing accordingly. Also note that, as per WP defaults, the default output for this filter is different depending on whether your theme supports html5.

= Q: Why isn't Search Box appearing as a possible item for menus? =

A: It is most likely that you have it turned off in the Screen Options tab on your Menus admin page.

= Q: Why can't I modify certain fields for my Search Box menu item, e.g., css classes? =

A: It is most likely that you have it turned off in the Screen Options tab on your Menus admin page.

= Q: I'm experiencing conflicts between this plugin and my caching plugin, what should I do? =

A: Your caching plugin is likely being naughty by misusing WP Object Cache. Many of these plugins allow you to turn this on or off; I recommend off. However, you are also likely using an older version of this (BSBITFNM) plugin and you should update. Versions 1.3.1 and above no longer use WP Object Cache and should fix the conflict.

== Changelog ==

= v1.6.0: Hidden Inputs and a few minor bug fixes =
* Fixed a bug with the update system (nothing to be concerned about as it has still never used).
* Introduced friendlier error and warning messages for those using unsupported PHP & WP.
* Introduced new action hook "bop_nav_search_hidden_inputs" to easily allow the addition of extra filters to the search box.
* Introduced new action hook "bop_nav_search_pre_get_search_form" to do more or less the same job as "pre_get_search_form" but for this plugin only.

= v1.5.0: Customizer and proper plugin setup =
* Is now compatible with *Appearnce > Customizer*.
* Added neat and sensible update procedures (this plugin will likely never need them, but it's nice to have).
* Updated uninstall procedure for compatibility with the update procedure.
* Improved translation support.

= v1.4.0: Filters and translation =
* Added filters *bop_nav_search_show_submit_button*, *bop_nav_search_screen_reader_text*, *bop_nav_search_the_title*, and *bop_nav_search_the_attr_title* - read code comments and/or FAQ for more details.
* Fixed a number of translation issues.
* Added a lot of comments to the code to help with hooks.
* Changed a few defaults for them to make more sense.

= v1.3.1: Cache no more. =
* No longer uses WP Object Cache and uses globals instead (shudder). WP Object Cache is abused by numerous caching plugins causing erratic and incorrect behaviour.

= v1.3.0 =
* Abandoned the fix to hidden metaboxes as that area of wordpress itself is quite buggy.
* Moved developer info into a tab in the screen help section
* Moved js to inline document as it is much more brief than it was previously and there's little point in having a separate file

= v1.2.0 =
* Found a workaround to some poor core wp code which shows two unexpected notices when adding a search menu item by ajax into a menu in wp-admin/nav-menus.php.
* Added a fix to wordpress's bizarre decision to hide the plugin from new nav-menu users. Not a problem for most users as most make a menu before activating this plugin.
* Removed some redundant unused code
* Added code comments to help with debugging, etc.

= v1.1.0 =
* Added js and css so the plugin actually works.
* All previous versions updated to include the js and css files which should have shipped in the first place.

= v1.0.1: Very minor changes =
* Added protection against plugin duplication.
* ReadMe improved for greater legibility.

= v1.0.0: Initial release =

== Screenshots ==

1. A view of a *wp-admin/nav-menus.php* screen with the plugin enabled.

2. A view of the expanded search box menu item in the admin area.

== Upgrade Notice ==

= v1.6.0 =
Users of very old PHP have complained about this plugin breaking their site by using syntax that is too modern. This has now been turned into friendly warning messages and nullification of the plugin for those with incompatible servers. A few bugs have been fixed and a couple of new hooks have been added. See changelog for further details.

= v1.5.0 =
See changelog

= v1.4.0 =
Fixed some translation issues (however there still exist no official translations yet). Added ease to customise html output - requires access to theme files. Modified defaults, =please check to make sure this hasn't changed the output on your site= and if it has attempt to use the admin area and the FAQ to rectify it.

= v1.3.1 =
Update to not use WP Object Cache. It is abused by numerous caching plugins causing erratic and incorrect behaviour.

= v1.3.0 =
Some small changes. Check the changelog for further details.

= v1.2.0 =
A fair amount of change, but there shouldn't be too much difference to the user experience. In essence, a couple of minor fixes and a clean up.

= v1.1.0 =
Important update. All previous downloads should update to this. The plugins js and css were missing previously. No thanks go to wp-svn for causing this mistake in the first place.

= 1.0.1 =
Very minor update to protect against plugin duplication.
