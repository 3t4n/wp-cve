=== Breadcrumb NavXT Multidimension Extensions===
Contributors: mtekk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=FD5XEU783BR8U&lc=US&item_name=Breadcrumb%20NavXT%20Donation&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: breadcrumb navxt, breadcrumb, breadcrumbs, trail, navigation, menu
Requires at least: 5.0
Tested up to: 5.8
Stable tag: 2.7.1
License: GPLv2 or later
Automates the generation of multidimensional list breadcrumb trails with Breadcrumb NavXT.

== Description ==

In the [Vista-Like Breadcrumbs for WordPress](http://mtekk.us/archives/guides/vista-like-breadcrumbs-for-wordpress/) guide, code was presented for recreating the breadcrumb style featured in Windows Vista and Windows 7. That code eventually was updated and placed into a plugin to ease implementation. This is that plugin.

= Breadcrumb NavXT Versions Supported =

This plugin supports Breadcrumb NavXT 5.1+, and Breadcrumb NavXT 6.0+. Note that not all features are available on older Breadcrumb NavXT versions.

= Translations =

Breadcrumb NavXT Multidimension Extensions is distributed with translations for the following languages:

* English - default -

Don't see your language on the list? Stop by [Breadcrumb NavXT's translation project](http://translate.mtekk.us/projects/breadcrumb-navxt "Go to Breadcrumb NavXT's GlotPress based translation project").

== Installation ==
Breadcrumb NavXT Multidimension Extensions can be installed from within WordPress’ administration panel. After installing and activating the plugin, to get breadcrumb trails to display either use the included widget, or call the breadcrumb trail in your theme (or child theme). See the [Calling the Breadcrumb Trail](https://mtekk.us/extensions/breadcrumb-navxt-multidimension-extensions/#basic "Read more on calling the breadcrumb trail") article for more information on calling the breadcrumb trail.

== Changelog ==

= 2.7.1 =
Release date: December 27th, 2021

* Bug fix: Fixed compatibility issues with Breadcrumb NavXT 7.0.1

= 2.7.0 =
Release date: December 4th, 2021

* Bug fix: Fixed compatibility issues with Breadcrumb NavXT 7.0
* Bug fix: Fixed undefined variable PHP warnings

= 2.6.3 =
Release date: April 27th, 2020

* Bug fix: Fixed compatibility issues with Breadcrumb NavXT 6.4.x

= 2.6.2 =
Release date: December 31th, 2019

* New feature: Added support for Breadcrumb NavXT 6.4.0.

= 2.5.3 =
Release date: March 30th, 2019

* Bug fix: Fixed compatibility issues with Breadcrumb NavXT 6.3.x

= 2.5.2 =
Release date: August 11th, 2018

* Bug fix: Fixed compatibility issues with Breadcrumb NavXT 6.1.x

= 2.5.1 =
Release date: March 13th, 2018

* Bug fix: Fixed issue where when using Breadcrumb NavXT 6.0.x under some circumstances caused an erroneous root page to be included for the page post type when using `bcn_display_multidim_children()`.
* Bug fix: Fixed issue where the `$force` parameter did not work and threw PHP errors for the `bcn_display_list_multidim_*()` functions.

= 2.5.0 =
Release date: November 8th, 2017

* New feature: Added support for Breadcrumb NavXT 6.0.0.
* Bug fix: Moved localization textdomain to be compatible with the .org GlotPress install

= 2.1.0 =
Release date: December 27th, 2015

* New feature: Added support for Breadcrumb NavXT 5.6.0's force parameter in the display functions.

= 2.0.0 =
Release date: December 3rd, 2015

* New feature: Added setting for controlling the display of children of the home page while on the homepage.
* New feature: Added bcn_multidim_term_children filter.
* New feature: Added bcn_multidim_post_children filter. 

= 1.9.0 =
Release date: Release date: August 21st, 2015

* New feature: Added new `bcn_display_list_multidim_children()` function which places the children of a breadcrumb into the second dimension
* New feature: Support for the Breadcrumb NavXT widget, requires Breadcrumb NavXT 5.3.0 or newer
* Bug fix: Fixed issue where the second dimension would not be populated for the current item if the current item was linked
* Bug fix: Fixed issue where an “Empty Category” message would appear in the second dimension for terms without children or siblings

= 1.8.1 =
Release date: July 30th, 2014

* Behavior Change: Dropped support of version of Breadcrumb NavXT prior to 5.1.x
* Bug fix: Fixed issues relating to support for Breadcrumb NavXT 5.1.1

= 1.8.0 =
Release date: June 6th, 2014

* Behavior Change: Refactored entire plugin
* Bug fix: Fixed issues relating to support for Breadcrumb NavXT 5.1.x

= 1.7.0 =
Release date: April 5th, 2014

* Behavior Change: Dropped support of version of Breadcrumb NavXT prior to 5.0.x
* Bug fix: Fixed issues relating to support for Breadcrumb NavXT 5.0.x

= 1.6.0 =
Release date: January 12th, 2013 

* Bug fix: Fixed issues relating to support for Breadcrumb NavXT 4.2.x

= 1.5.0 =
* Initial Public Release

== Upgrade Notice ==
= 2.1.0 =
Added support for Breadcrumb NavXT 5.6.0's force parameter in the display functions.

= 2.0.0 =
Added two new filters and a setting to control the display the home breadcrumb's children when on the home page.