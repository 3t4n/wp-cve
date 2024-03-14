=== Easy Heads Up Bar ===
Contributors: Greenweb
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7YA9D9G4TE9BA
Tags: heads up bar, heads up, heads up, heads up bar, Callout Bar, top of the page, notification bar, notification, self promotion, floating-bar, beforesite, action bar, alert bar
Requires at least: 4.0
Tested up to: 4.6
Stable tag: trunk
License: GPLv2 or later

The Easy Heads Up Bar Plugin allows you to quickly add a customizable notification bar to your WordPress website.

== Description ==

This plugin adds an easy to use notification bar to the top of your WordPress website

[youtube https://www.youtube.com/watch?v=DOsTdfnmtmI]

= Key Features =

* Customizable color schemes
* Create multiple bars, as many as you want.
* If there is more than one bar then the bars will display randomly
* Schedule when your bars show up by setting an start and end date.
* Choose where to display bars, eg:
 * All pages
 * Only the interior pages
 * Just the home page

= Display Date Options =

* The Bar can be set to expire on a specified date
* The Bar can be set to start on a specified date
* The Bar can be set to run between on a specified dates

= New Features =

* New bar management screen
* New bar editor
* No limit on bar height, it will just fit your content
* No limit to the amount of text or links in a bar
* Add images to bar
* Use another plugin's shortcodes in bar
* Choose between the top or the bottom of a page to display your bar
* Allow your users to hide or show the Heads Up Bar

== Installation ==

Install the plugin via WordPress's installation system then activate it.

=OR=

1. Upload the `easy-heads-up-bar` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

* **Q:** Is the Bar Responsive?
 * **A:** Yes.

* **Q:** Can the bar be closed?
 * **A:** Yes.

* **Q:** Is it Possible to remove the line under bar?
 * **A:** Yes, in the bar's edit screen set the color option of the line to the same color as the bar's background.

* **Q:** Can the bar remain at the top of the screen when I scroll?
 * **A:** Not right now, this may be added later. But there is nothing stopping you from archiving this effect by adding a bit of CSS to your theme. You can target the bar using it's ID *#ehu-bar*

* Feel free to ask any questions you may have at the [Support Forum](http://wordpress.org/support/plugin/easy-heads-up-bar)

== Screenshots ==

1. Easy Heads Up Bar manager
2. Easy Heads Up Bar editor
3. Easy Heads Up Bar supports shortcodes
4. Easy Heads Up Bar is responsive
5. Key features of the Easy Heads Up Bar
6. New features added to version 2 of the Easy Heads Up Bar

== Changelog ==

= 2.1.7 =

Modified capability_type to include users who can edit posts

= 2.1.6 =

N/A

= 2.1.5 =

Addressing a minor display bug introduced in v 2.1.4

= 2.1.4 =

Wrote a function to stop the shareaholic plugin from pushing the social links onto the plugin bar

= 2.1.3 =

Minor change to work around the shareaholic plugin

* Removed apply_filters('the_content', $bar_content);
* Added  do_shortcode( $bar_content );

= 2.1.2 =

* New Bar Display option that floats the bar over the content and fixes it while scrolling.
* Added jQuery to ensure that the bar display well if there is the WordPress Admin Bar above it.

= 2.1.1 =

*Minor Changes*

* New CSS to override Theme's CSS with relation to padding on HTML elements like p tags
* New bar open and close icons

= 2.1 =

* Changed out a br tag for a span tag to address a spacing issue in IE9

= 2.0 =

*New features:*

* Bars are now a custom post type
* New Icon
* New bar management screen
* New bar editor
* No limit to the amount of text or links in a bar
* No limit on bar height, it will just fit your content
* Add images to bars
* Use another plugin's shortcodes
* Choose between the top or the bottom of a page to display your bar
* Allow your users to hide and show the Heads Up Bar

== Upgrade Notice ==

Minor change to work around the shareaholic plugin
