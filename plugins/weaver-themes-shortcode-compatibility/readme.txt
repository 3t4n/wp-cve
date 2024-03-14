=== Weaver Themes Shortcode Compatibility ===
Contributors: Bruce Wampler
Plugin Name: Weaver Themes Shortcode Compatibility
Plugin URI: http://weavertheme.com/plugins/
Author URI: http://weavertheme.com/about/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: weaver theme, settings, save, subthemes
Requires at least: 3.9
Tested up to: 4.9
Stable tag: 1.0.4

== Description ==

This plugin provides shortcode compatibility between Weaver II, Aspen, Weaver Xtreme, and any other WordPress theme.

= Weaver II Shortcodes Supported =

The following Weaver II shortcodes are supported for Weaver Xtreme and other WordPress plugins.

Weaver II Shortcodes supported include:

* Breadcrumbs - **[weaver_breadcrumbs]**
* Header Image - **[weaver_header_image]**
* HTML - **[weaver_html]**
* DIV - **[div]text[/div]**
* SPAN - **[span]text[/span]**
* iFrame - **[weaver_iframe]**
* Blog Page Navigation - **[weaver_pagenav]**
* Show If Mobile - **[weaver_show_if_mobile]**
* Hide If Mobile - **[weaver_hide_if_mobile]**
* Show If Logged In - **[weaver_show_if_logged_in]**
* Hide If Logged In - **[weaver_hide_if_logged_in]**
* Site Title - **[weaver_site_title]**
* Site Tagline - **[weaver_site_desc]**
* Vimeo - **[weaver_vimeo]**
* YouTube - **[weaver_youtube]**

Weaver II Shortcodes NOT supported:

* Show Posts - **[weaver_show_posts]** - This short code has been replaced by the ATW Show Posts plugin.

= Weaver Xtreme Shortcodes Supported =

* **[tab_group]** - Display content in a tabbed box.
* **[youtube]** - Show your YouTube videos responsively, and with the capability to use any of the YouTube custom display options.
* **[vimeo]** -  Show your Vimeo videos responsively, and with the capability to use any of the Vimeo custom display options.
* **[iframe]** - Quick and easy display of content in an iframe.
* **[div]**, **[span]**, **[html]** - Add div, span, and other html to pages/posts without the need to switch to Text view.
* **[hide/show_if]** - Show or hide content depending upon options: device, page ID, user capability, logged in status.
* **[bloginfo]** - Display any information available from WordPress bloginfo function.
* **[user_can]** - Display content base on logged in user role.
* **[site_title]** - Display Site title.
* **[site_tagline]** - Display Site tag line.
* **[login]** - login/out

= Aspen Shortcodes Supported =

* Breadcrumbs - **[aspen_breadcrumbs]**
* Header Image - **[aspen_header_image]**
* HTML - **[aspen_html]**
* DIV - **[div]text[/div]**
* SPAN - **[span]text[/span]**
* iFrame - **[aspen_iframe]**
* Blog Page Navigation - **[aspen_pagenav]**
* Show If Mobile - **[aspen_show_if_mobile]**
* Hide If Mobile - **[aspen_hide_if_mobile]**
* Show If Logged In - **[aspen_show_if_logged_in]**
* Hide If Logged In - **[aspen_hide_if_logged_in]**
* Site Title - **[aspen_site_title]**
* Site Tagline - **[aspen_site_desc]**
* Tab Group - **[aspen_tab_group]** - Display content in a tabbed box.
* Vimeo - **[aspen_vimeo]**
* YouTube - **[aspen_youtube]**

Aspen Shortcodes NOT supported:

* Show Posts - **[aspen_show_posts]** - This short code has been replaced by the ATW Show Posts plugin.
* Slider - **[aspen_slider]** - No longer supported. Try ATW Show Sliders instead.

= Licenses =

* The Weaver Theme Compatibility plugin is licensed under the terms of the GNU GENERAL PUBLIC LICENSE, Version 2,
June 1991. (GPL) The full text of the license is in the license.txt file.
* All images included with this plugin are either original works of the author which
have been placed into the public domain, or have been derived from other public domain sources,
and thus need no license. (This does not include the images provided with any of the
below listed scripts and libraries. Those images are covered by their respective licenses.)

This plugin also includes several scripts and libraries that are covered under the terms
of their own licenses in the listed files in the plugin distribution.

== Upgrade Notice ==

This is the initial release of this plugin.


== Installation ==

It is easiest to use the Plugin Add Plugin page, but you can do it manually, too:

1. Download the plugin archive and expand it
2. Upload the weaver-ii-theme-extra.php file to your wp-content/plugins/weaver-ii-theme-extras directory
3. Go to the Plugins page in your WordPress Administration area and click 'Activate' for Weaver II Theme Extras.

== Frequently Asked Questions ==

= Will I lose all my Weaver II or Weaver Xtreme settings and design work if I use this plugin? =

No - this plugin is intended to ease the transition to a different theme. This will most frequently
be from Weaver II to Weaver Xtreme, but it will work equally well with any WordPress theme.

= What about all my Weaver II settings? =

If you need to convert your existing Weaver II settings to transition to Weaver Extreme, there is
a settings converter available called Weaver II to Weaver Xtreme. That plugin will non-destructively
convert your existing Weaver II settings to Weaver Xtreme.


== Changelog ==

= 1.0 =
First release.

= 1.0.1 =
New: Aspen compatibility
Fix: z-index on tabs shortcode

= 1.0.2 =
* Fix: [ youtube ] extra " removed

= 1.0.3 =
Update: WP 4.3 compatility
