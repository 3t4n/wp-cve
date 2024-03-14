=== Plugin Name ===

Contributors: cleavrdev
Tags: nginx, cache, fastcgi, cleavr, caching, clear cache
Requires at least: 4.6
Tested up to: 5.9.1
Stable tag: 1.2
Requires PHP: 7.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Manage your NGINX FastCGI cache for your Cleavr sites. Simply add the clear cache trigger hook and then you 
can click a button to clear your site's cache and optionally clear cache every time content changes.

== Description ==

## Cleavr Clear Cache

Easily clear your [Cleavr](https://cleavr.io) site's NGINX FastCGI cache with the Cleavr Clear Cache plugin for
WordPress. 

The Cleavr Clear Cache plugin allows you to clear site cache with the click of the button. You can 
also set it up to automatically clear cache when content on your site changes. 

This plugin was forked from the [NGINX Cache](https://wordpress.org/plugins/nginx-cache/) plugin by Till Krüss and updated to 
utilize Cleavr's clear cache trigger hook. This has the added benefit of working for site's assigned to multiple server users. 

## Installation
Install the plugin in your WordPress admin panel. Once installed, click on **Activate** to activate the
plugin. 

Click on **Settings** to configure the Cleavr Cache Plugin.

Provide the site's **Clear Cache Trigger Hook** from the site's FastCGI section in Cleavr. 

You can optionally select the option to automatically clear cache when content changes. 

Click **Save** to save the settings. 

Click **Clear Cache** to clear your site's cache.



== Changelog ==

= 1.2 =
* Tested for 5.9.1

= 1.1 =
* Updated placeholder for app.cleavr.io

= 1.0 =
* Initial release
