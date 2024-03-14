=== VS Website Closed ===
Contributors: Guido07111975
Version: 2.9
License: GNU General Public License v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 7.0
Requires at least: 5.3
Tested up to: 6.4
Stable tag: 2.9
Tags: simple, close, closed, maintenance, under construction


With this lightweight plugin you can close your website on selected days of the week.


== Description ==
= About =
With this lightweight plugin you can close your website on selected days of the week.

On selected days your website is closed and a landing page is displayed.

When to use:

* Close webshop during your holiday
* Close for religious reasons
* Close for maintenance or under construction

Main difference between this plugin and others is that you can select certain days instead of just close or open your website.

= How to use =
After installation go to the settings page. This page is located at Settings > Website closed.

Select days and customize the landing page that is displayed when website is closed.

= Styling =
Additional styling (CSS) via the Customizer is being ignored when this plugin is active. But you can add custom CSS via plugin settings page.

= Have a question? =
Please take a look at the FAQ section.

= Translation =
Translations are not included, but the plugin supports WordPress language packs.

More [translations](https://translate.wordpress.org/projects/wp-plugins/very-simple-website-closed) are very welcome!

The translation folder inside this plugin is redundant, but kept for reference.

= Credits =
Without the WordPress codex and help from the WordPress community I was not able to develop this plugin, so: thank you!

Enjoy!


== Frequently Asked Questions ==
= How do I set plugin language? =
The plugin will use the website language, set in Settings > General.

If translations are not available in the selected language, English will be used.

= Which timezone does plugin use? =
It uses the local timezone set in Settings > General.

= Can I preview the landing page before closing website? =
Yes, this is possible. Plugin has a preview feature.

= Can I close a website permanently? =
Yes, this is possible. Just select all days and save the page.

= Where to find the image ID? =
Every image contains an unique ID. You will find this ID when hovering the image title in the media library of your dashboard (list view) or when editing the image.

It's the number that comes after: `post=` or `item=`

= Is SEO taken into account? =
Yes, when plugin is active it adds a 503 Service Unavailable server error response code. This tells search engines that your site is (temporary) down.

= Why is there no semantic versioning? =
The version number won't give you info about the type of update (major, minor, patch). You should check the changelog to see whether or not the update is a major or minor one.

= How can I make a donation? =
You like my plugin and want to make a donation? There's a PayPal donate link at my website. Thank you!

= Other questions or comments? =
Please open a topic in the WordPress.org support forum for this plugin.


== Changelog ==
= Version 2.9 =
* Minor changes in code

= Version 2.8 =
* Fix: admin bar
* New: admin bar always visible for logged in administrators

= Version 2.7 =
* Bumped the "requires PHP" version to 7.0
* Minor changes in code

= Version 2.6 =
* New: background image
* Minor changes in code

= Version 2.5 =
* Textual changes

= Version 2.4 =
* Minor changes in code

= Version 2.3 =
* New: admin bar is displayed in preview mode
* New: setting for page title
* Added files vswc-variables and vswc-template
* Relocated template from file vswc to these files

= Version 2.2 =
* Improved notification at settings page

= Version 2.1 =
* New: you can add custom CSS via the settings page

= Version 2.0 =
* Removed function load_plugin_textdomain() because redundant
* Plugin uses the WP language packs for its translation
* Kept translation folder for reference

For all versions please check file changelog.


== Screenshots == 
1. Settings page (dashboard)
2. Settings page (dashboard)
3. Landing page
4. Landing page