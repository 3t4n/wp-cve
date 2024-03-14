=== Web Fonts ===
Contributors: nickohrn
Tags: fonts, webfonts, content display, theme enhancement, plugin, admin, google, web fonts, api integration
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 1.1.6

Start using web fonts on your site today! Support for web fonts from Fonts.com and Google Web Fonts is included.

== Description ==

Sponsored by [Monotype Imaging](http://www.monotypeimaging.com "Monotype Imaging") and [Fonts.com](http://fonts.com "Fonts.com"),
the Web Fonts plugin provides an administrative interface for browsing and applying web fonts from a variety of sources. The plugin
includes support for web fonts from the [Fonts.com](http://fonts.com "Fonts.com") web fonts collection out of the box. 

In addition, support for [Google Web Fonts](http://www.google.com/webfonts#HomePlace:home "Google Web Fonts") has been added 
in Version 1.1.0. You can now browse and enable any of the many open web fonts available from Google and then add them to your 
stylesheet the same way you do with Fonts.com web fonts. Other web font providers will be added in the future via plugins.

If you run into issues with the plugin, please [open a support topic](http://wordpress.org/tags/web-fonts) on the WordPress.org
support forums.  

== Installation ==

Its easy to get started with the Web Fonts plugin. You can install the plugin by either searching for it
in the WordPress plugin installer or, if you download Web Fonts manually, uploading it to `wp-content/plugins/web-fonts/`.

Activate Web Fonts in the `Plugins` admin panel using the activate link. After doing so, you'll be able to 
access the Web Fonts settings interface to manage your stylesheet or browse fonts by supported providers.

The first thing you should do is set up your Fonts.com web fonts account by visiting the Fonts.com submenu
under the Web Fonts top level menu item in your administrative sidebar. If you have a Fonts.com account
already you can enter your information into the provided fields and it will be validated. If you do not have 
an account you can quickly and easily create one from inside WordPress.

If you wish to use the Google Web Fonts integration you can do so by adding your API key and then browsing
the fonts available from Google. 

== Frequently Asked Questions ==

= Do I need a Fonts.com web fonts account account to use the plugin? =

No, you do not need a Fonts.com account to use the plugin. After installation, you'll need to set
up your Fonts.com account in order to browse and enable fonts for your site. If you don't have an
account, you can create one right in the WordPress administrative area.

= How do I enable Google Web Fonts? Where do I get my API key? =

To use the [Google Web Fonts](http://www.google.com/webfonts#HomePlace:home "Google Web Fonts") integration 
you'll need to log in with your Google account and visit [The Google API Console](https://code.google.com/apis/console/). 
If you haven't previously created a project you'll be prompted to do so at that point.

After creating a project, you can click on "API Access" and then copy and paste the key you find
there into the field in the plugin. Make sure you've enabled the Web Fonts Developer API from the 
list you'll see when clicking on the "Services" tab on the left.

= What web font providers will be integrated in the future? =

It is unknown which web font providers will be integrated in the future. Some of the providers being considered are:

* [Typekit](http://typekit.com "Typekit")
* [Font Squirrel](http://www.fontsquirrel.com "Font Squirrel")
* [KERNEST](http://kernest.com "Kernest")
* [Fontdeck](http://fontdeck.com "Fontdeck")

The timeline for integration of the above depends heavily on usage of this plugin. If it becomes popular, more
web font providers will be integrated at an accelerated rate.

= Do I need to have any programming or theme modification knowledge to use this plugin? =

**No!** The plugin is designed to automate the entire process for you. All you have to do is decide what
fonts you want on your site and what selectors they should be assigned to. After saving your stylesheet, the
plugin takes care of making sure the appropriate code appears in your theme.

This being said, knowledge of CSS selectors is strongly recommended as that is how you will specify
where your enabled fonts show up.

== Screenshots ==

1. Setting up your Fonts.com account is easy, just enter your authentication key or email address and password
2. If you don't have a Fonts.com account, you can easily create one right from WordPress
3. After set up, you can create a new project or choose one of the existing ones from your Fonts.com account
4. After selecting your active project it is easy to browse fonts using a set of filters and keyword search
5. For any font, you can quickly and easily see extended details like the Designer, Foundry and font size 
as well as an appropriate preview
6. You can also see the enabled fonts for your active project at any time
7. After enabling the appropriate fonts for all providers, you can go ahead and set the selectors you want to use
in the Manage Stylesheet interface
8. If you prefer to think in terms of selectors instead of fonts you can go ahead and create your stylehseet that way
9. Starting with Version 1.1.0 we've added support for Google Web Fonts - first add your API key
10. After adding your Google API key you can browse any of the open Google Web Fonts

== Changelog ==

= 1.1.6 =
* Fixed bug preventing users from disabling Google Web Fonts

= 1.1.5 =
* Fixed Google Web Fonts CSS declaration per 

= 1.1.4 =
* Added web fonts icon designed and built by R. York Funston (http://www.ryarts.com/)

= 1.1.2 =
* Modified readme so text wouldn't get cut off on the plugin page

= 1.1.1 =
* Modified readme to change plugin description
* Changed plugin description in plugin header

= 1.1.0 = 
* Added support for Google Web Fonts

= 1.0.4 =
* Fixed silly syntax error that I didn't check before uploading 1.0.3

= 1.0.3 =
* Fixed a bug where I was using the same PHP 5.3+ only feature I fixed in 1.0.2 again

= 1.0.2 =
* Fixed a bug where I was using a PHP 5.3+ only feature on accident

= 1.0.1 =
* Renamed a few screenshots so they would actually show up in the correct section

= 1.0.0 =
* Initial release version

== Upgrade Notice ==
