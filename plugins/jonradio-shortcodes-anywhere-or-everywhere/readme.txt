=== Shortcodes Anywhere or Everywhere ===
Contributors: dgewirtz, adiant
Donate link: http://zatzlabs.com/plugins/
Tags: shortcode, custom
Requires at least: 3.1
Tested up to: 5.8
Stable tag: 1.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows Shortcodes to be used nearly everywhere, not just in posts and pages.

== Description ==

Shortcodes can be added to Post Excerpts, Page Titles, Post Titles, Widgets, Widget Titles, Custom Fields, Site Title and Description, not just Pages and Posts as is already provided by WordPress itself.  This allows Shortcodes to be used in Sidebars, Menus, Headers, Footers and even the HTML `<title>` element that appears in the Title Bar of most browsers.

A Settings page includes checkboxes to select where Shortcodes might appear:

* In Post Excerpts, both manual and automatically-created Excerpts
* In Page and Post Titles
* In Browser Title Bar via `<title>`
* In Widgets used in Sidebars, Menus, Headers, Footers, etc.
* In Widget Titles
* In Site Title, Description and other bloginfo options
* In Page or Post Custom Fields

Priority is an Advanced Setting beside each checkbox that controls the timing of the WordPress Filter associated with each Where? setting.  This is provided solely to address conflicts with other themes and plugins that use the same WordPress Filter ("hook"). 

A default Warning feature, which can be disabled on the Settings page, displays a message at the top of every Admin panel if the plugin is Activated but doing nothing because no Where? checkboxes are selected (other than the mandatory In Pages and In Posts settings).

All Shortcodes are supported by this plugin, no matter whether you are using Shortcodes defined by:

* WordPress itself, such as `[gallery]`, `[audio]`, `[caption]`, `[embed]` or `[video]`
* Jetpack Shortcode Embeds
* Your Active Theme
* Another Plugin, for example, 
Current Year and Copyright Shortcodes
* Writing your own Plugin and using the `add_shortcode()` function
* Using a Shortcode creation Plugin, for example, Shortcode Exec PHP

Future versions will include more areas of the WordPress web site where Shortcodes may be used, based on the needs of users of this Plugin.

No attempt has been made to allow Shortcodes to be used to display values in Admin panels.  This is the default WordPress behaviour, and changing it may have unintended (negative) consequences.

Plugin Developers may find the Table-Driven Design of interest.  This was a popular concept in the 1980s and was used in this plugin to simplify the use of WordPress Filters.  The two-dimensional global array $jr_saoe_filters is the Table involved.

This plugin was created to satisfy a request from a user of the [Current Year and Copyright Shortcodes plugin](http://wordpress.org/plugins/jonradio-current-year-and-copyright-shortcodes/ "jonradio Current Year and Copyright Shortcodes") to use these Shortcodes in a Page Title.

== Installation ==

This section describes how to install the *jonradio Shortcodes Anywhere or Everywhere* plugin and get it working.

1. Use **Add Plugin** within the WordPress Admin panel to download and install this *jonradio Shortcodes Anywhere or Everywhere* plugin from the WordPress.org plugin repository (preferred method).  Or download and unzip this plugin, then upload the `/jonradio-shortcodes-anywhere-or-everywhere/` directory to your WordPress web site's `/wp-content/plugins/` directory.
1. Activate the *jonradio Shortcodes Anywhere or Everywhere* plugin through the **Installed Plugins** Admin panel in WordPress.  If you have a WordPress Network ("Multisite"), you can either **Network Activate** this plugin through the **Installed Plugins** Network Admin panel, or Activate it individually on the sites where you wish to use it.  Activating on individual sites within a Network avoids some of the confusion created by WordPress' hiding of Network Activated plugins on the Plugin menu of individual sites.  Alternatively, to avoid this confusion, you can install the [jonradio Reveal Network Activated Plugins](http://wordpress.org/plugins/jonradio-reveal-network-activated-plugins/ "jonradio Reveal Network Activated Plugins") plugin.
1. View the Settings page, either by clicking on the **Settings** link in the entry for this plugin shown in the Installed Plugins Admin panel in WordPress, or from the Admin menu, **Settings-Shortcodes Anywhere or Everywhere**.
1. Select where Shortcodes will be used, and click the Save Changes button.

== Frequently Asked Questions ==

= Where should Feature Requests be sent? =

Support has moved to the ZATZLabs site and is no longer provided on the WordPress.org forums. Please visit the new [ZATZLabs Forums](http://zatzlabs.com/forums/). If you need a timely reply from the developer, please [open a ticket](http://zatzlabs.com/submit-ticket/). There's a field on the support ticket specifically for feature requests.

== Changelog ==

= 1.4.2 =
* Minor support update

= 1.4.1 =
* Error messages added for invalid Priority values on Settings page

= 1.4 =
* Add a Priority setting for each Filter in the Where? section

= 1.3.1 =
* Add Recommendations to Settings page for multiple Settings for "same thing", i.e. - Titles and Post Excerpts 

= 1.3 =
* Support Shortcodes in Post Excerpts, both those manually and automatically created

= 1.2 =
* Support Shortcodes in Custom Fields: Page and Posts only, using `get_post_metadata` filter

= 1.1 =
* Support Shortcodes in Titles, Widgets, Widget Titles, Site Title/Description, including `<title>`
* Settings Page to turn each Filter used on and off, and to show Pages and Posts as always on
* Warning when nothing selected

= 1.0 =
* Beta Version

== Upgrade Notice ==

= 1.4.1 =
Error Messages for Priority Settings

= 1.4 =
Control Priority setting for each WordPress Filter enabled

= 1.3.1 =
Explain (on Settings page) multiple Settings for Titles and Post Excerpts

= 1.3 =
Shortcodes in Post Excerpts (manual and automatically created)

= 1.2 =
Shortcodes in Custom Fields (Pages and Posts)

= 1.1 =
First Production Version