=== Bandsintown Events ===
Contributors: kwestion505, konard
Tags: concerts, bandsintown, events, tour dates
Requires at least: 2.7
Tested up to: 5.9
Stable tag: 1.3.1

Bandsintown's Events plugin for displaying your upcoming events.

== Description ==

Bandsintown's Events plugin makes it easy for artists to showcase their upcoming events anywhere on their WordPress-powered blog or website.

Easily display an automatically updated list of your events to your fans using the widget, shortcode or template tag.

* Automatically syncs to Facebook, Tumblr and Twitter.
* Buy tickets and RSVP to your events right from your website.
* Fully customizable CSS (uses theme styles by default).

== Installation ==

Click [here](https://manager.bandsintown.com/support/integrations) for installation instructions.

== Screenshots ==

See [here](https://manager.bandsintown.com/support/integrations).

== Changelog ==

= 1.3.1 =
* Fix widget initialization

= 1.3.0 =
* Fix deprecation warning for `create_function()`.

= 1.2.0 =
* Fix PHP7 Class constructors deprecation warning. Thanks
[olimax](https://wordpress.org/support/topic/php7-error-warning-_fix/)
* Add support for Display Track Button and Display Details widget settings.

= 1.1.9 =
* Fix error where it would sometimes not correctly fallback to Artist name
specified in Settings page when using the shortcode without options.

As of version 1.1.6 the preferred way to use the Bandsintown Events widget and
this plugin is through the `[bandsintown_events]` shortcode, either for pages,
posts or widget.

Please refer to our [integrations page](http://www.artists.bandsintown.com/integrations)
for more information.

= 1.1.8 =
* Remove ‘Elvis’ operator to make plugin compatible with PHP < 5.3

As of version 1.1.6 the preferred way to use the Bandsintown Events widget and
this plugin is through the `[bandsintown_events]` shortcode, either for pages,
posts or widget.

Please refer to our [integrations page](http://www.artists.bandsintown.com/integrations)
for more information.

= 1.1.7 =
* Fixes an issue where shortcode would not fallback to values set in Settings page.

As of version 1.1.6 the preferred way to use the Bandsintown Events widget and
this plugin is through the `[bandsintown_events]` shortcode, either for pages,
posts or widget.

Please refer to our [integrations page](http://www.artists.bandsintown.com/integrations)
for more information.

= 1.1.6 =
* Update shortcode settings.
You can now use all the settings defined [here](http://www.artists.bandsintown.com/events-widget#need-more-customization)
when using the `[bandsintown_events]` shortcode.

Each option matches the HTML5 attribute name, without the `data-` prefix.
For example, to set a custom Artist Name, Font, Link Color and Language you would use the following shortcode.

`[bandsintown_events artist-name="Shakira" font="Arial" link-color="#ff0000" language="de"]`

= 1.1.5 =
* Updates Plugin name and assets
* Small general fixes

= 1.1.4 =
* Fixes an issue where single quotes weren't correctly escaped for Artist name

= 1.1.3 =
* Use parent::__construct when extending WP_Widget
Fixes: https://wordpress.org/support/topic/plugin-broke-with-https/#post-9557510

= 1.1.2 =
* Now works with our new and improved Bandsintown Widget V2

= 1.1.1 =
* Support of HTTPS

= 1.1.0 =
* Improved release running on latest bandsintown widget

= 1.0.1 =
* Updated settings admin menu to use new settings API

= 1.0.0 =
* Completely reworked
* Single artist based
* Custom CSS (uses theme styles by default)
* Data loaded live from Bandsintown API (JSONP)
* No caching

= 0.2.0 =
* Initial release.
