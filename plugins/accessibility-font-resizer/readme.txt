=== Accessibility Font Resizer ===
Contributors: satellitewp, maximejobin
Tags: font size, text size, text resizer, font resizer, accessibility, font resize
Requires at least: 4.9
Tested up to: 6.2.2
Stable tag: 1.0.4
Requires PHP: 7.2
License: GPLv3

Make accessibility better for your visitors by enabling them to resize the text on your website and make it bigger.

== Description ==

Would you like to enable all your visitors to enjoy your site? Not all visitors sees your website the way you do.

This plugin allows you to make your website easier to read and give the visitors of your site the option to change the font size of your text.

Benefits:

* Enables more visitor to enjoy your website with custom large and very large font sizes.
* Works no matter if your site uses percentage, pixels, em or rem as your font sizes.
* Your visitor's text size is remembered when (s)he revisits the site.
* No coding skills required.
* Works within minutes.
* Flexible and easy to use.
* Won't slow your website.

This plugin was created and is maintained by SatelliteWP, a [WordPress maintenance service](https://www.satellitewp.com/en?utm_source=accessibility-font-resizer).


= Compatibility =

This plugin is multilingual ready.


== Installation ==

1. In your WordPress administration section, goto `Plugins > Add new`.
1. Search for `Accessibility font resizer`.
1. Click on 'Install now'.
1. Once the plugin is installed, click on 'Activate'.
1. Go to `Settings > Font resizer` to edit the plugin's settings.


== Frequently Asked Questions ==

= How can I activate the function of the plugin? =

Once the plugin is activated, the functions of the plugin work.


= I have activated the plugin, now what ? =

To be able to use the plugin, you should create a menu. You can use (or not) that menu in a widget.

The trick is to simply add the CSS class `afr-normal` to display normal size, `afr-large` for large text and `afr-xlarge` for very large text.

You can also apply this CSS technique in your PHP files or directly in your content.


= I click the resizer, but (some of) my fonts don't change in size. How to fix that? =

Simply inspect the element that is not resizing properly to know what element it is. Then, simply add it to the 'HTML Elements' section in the options' screen.

You can find the plugin's settings in `Settings > Font resizer`.


= I use font-size in rem in my theme, will it work? =

It's not a problem. The plugin works with all units!


= I use font-size in pixels in my theme, will it work? =

It's not a problem. The plugin works with all units!


= I use font-size in percentage in my theme, will it work? =

It's not a problem. The plugin works with all units!


= I want to use a shortcode in my content element =

There is no shortcode for this plugin. You can call CSS classes directly without the need of a shortcode.

= I found a bug =

Oh no! We are really sorry about that. Please [report bugs here](https://github.com/SatelliteWP/accessibility-font-resizer/issues).

== Changelog ==

= 1.0.4 =
* Added links in the options page for support
* Fixed a typo in the documentation
* Fixed a typo in class name (leaving the previous error for backward compatibility)

= 1.0.3 =
* Add debugging option in the settings

= 1.0.2 =
* Remove renamed file that cause plugin to install twice

= 1.0.1 =
* Fix version error

= 1.0.0 =
* First stable version
