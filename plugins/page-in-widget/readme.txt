=== Page in Widget ===
Contributors: airspray, carlfredrik.hero
Tags: page, widget
Requires at least: 3.2
Tested up to: 4.5
Stable tag: 1.3
License: GPLv2 or later
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=niklas%40aava%2eeu&lc=FI&item_name=Page%20In%20Widget%20Plugin%20for%20WordPress&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest

A tiny plugin that displays a page content in a widget.

== Description ==

The Page in Widget widget lets you display a page content inside a widget. This way you have more control how the content is displayed, and it's much easier than hacking your own HTML.

The output is filtered through the_content-filter which means that paragraph tags are added, just as any other post or page.

== Installation ==

1. Upload the zipfile to the `/wp-content/plugins/` directory
2. Extract and remove it
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Drag the new widget to desired sidebar, choose a title for the widget and select the appropriate page.

== Changelog ==

= 1.3 =
* New author: <a href="https://profiles.wordpress.org/airspray">airspray</a>. Big thanks to original author <a href="https://profiles.wordpress.org/carlfredrikhero">carlfredrik.hero</a>
* Added support for WordPress 4.5

= 1.2 =
* Added support for WPML translation (thanks to <a href="http://wordpress.org/support/profile/altert">altert</a>)

= 1.1.1 =
* Added missing `echo $before_widget;`

= 1.1 =
* The plugin now consider the `<!--more-->` tag
* Added option to show/hide the more link
* Added proper filtering to output

= 1.0 =
* Initial release