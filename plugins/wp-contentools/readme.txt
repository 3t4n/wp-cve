=== Contentools Integration ===
Contributors: Luciano Camargo Cruz, Davi Duarte, Douglas Schneider, Brunno Schardosin, Vinicius Bossle Fagundes

Donate link: 

Tags: Contentools, integration, marketing, platform, content marketing

Requires at least: 4.6

Tested up to: 6.2.2

Stable tag: 3.1.1

Requires PHP: 5.6

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin enables the integration between Contentools and Wordpress, also edit in WordPress and live preview functions.

== Description ==

Contentools platformas allows the user tu build a collaborative Content Operation by invitingthe team of content creators to collaborate in a tool that centralizes the entire process, from strategy, ideation, up to the publication of content across your blog, social, and website.  Your content team will get a space to centralize all communications and information about the content being produced from start to finish, so you can reach the right audience with the right content every time, and at scale.

Learn more: https://growthhackers.com/workflow

This plugin enables the integration between Contentools Platform and your WordPress’ blog. With the integrations users are able to: Create, update, and automatically publish content directly from Contentools as well as retrieve WordPress content and content versions.

The plugin can be accessed and found in the WordPress’ plugins by any WordPress user, but the full connection and execute all the features just for customers engaged in both products (WordPress and Contentools).

Current Features:

1. Allow X-Frame-Options
When you access the WP-admin page we add the field `X-Frame-Options` with value `https://go.contentools.com/` in header.
This allows your wp-admin page be accessed inside Contentools Platform

2. Set WP Contentools enabled plugin
It adds "WP-Contentools" flag on HTTP headers with value "true".

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Activate the plugin through the 'Plugins' screen in WordPress
2. Go to your Contentools account, on the left side menu go to Settings > Media Integration > Add Account > WordPress.

== Frequently Asked Questions ==

Is Contentools Plugin available to every WordPress Users?
Yes, the plugin is currently available to every WordPress user, but only can be used by Contentools customers engaged withaccess to our platform.

Learn more about our plugin and platform features in:  https://help.growth.software/pt-br/knowledge/workflow-contentools

== Upgrade Notice ==

1. Publish as draft feature;
2. Custom Post Types;
3. Import WordPress Content.

== Screenshots ==

`/assets/screenshot-1.png`

== Changelog ==
= 3.1.1 =
* Test Wordpress version 6.2.2

= 3.1.0 =
* Data sanitizing, validation and escaping

= 3.0.9 =
* Test Wordpress version 6.1.1

= 3.0.8 =
* Added new parameters for query posts

= 3.0.7 =
* Test Wordpress version 5.6

= 3.0.6 =
* Test Wordpress version 5.5

= 3.0.5 =
* Token auto generated

= 3.0.4 =
* HTTPS update

= 3.0 =
* Added new integration method and authorization by Token with Contentools.

= 2.2 =
* Added allowed methods

= 2.1 =
* Fixed X-Frame-Options not showing in a few screens

= 2.0 =
* Add support to the Wordpress REST API

= 1.0 =
* Add support to allow X-Frame-Options
* Add "WP-Contentools" flag on HTTP headers
