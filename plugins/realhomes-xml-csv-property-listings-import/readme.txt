=== Import Properties into RealHomes Theme ===
Contributors: soflyy, wpallimport
Tags: real estate, import real estate, import real estate listings, import properties, import property listings, realhomes, real homes, import real homes, import real homes properties, import real homes listings, import realhomes, import realhomes properties, import realhomes listings
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.1.0
Tested up to: 6.4
Stable tag: 1.1.4

Easily import property listings from any XML or CSV file to the Real Homes theme with the RealHomes Add-On for WP All Import.

== Description ==

The RealHomes Add-On for [WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import") makes it easy to bulk import your property listings to the RealHomes theme in less than 10 minutes.

The left side shows all of the fields that you can import to and the right side displays a property listing from your XML/CSV file. Then you can simply drag & drop the data from your XML or CSV into the RealHomes fields to import it.

The importer is so intuitive it is almost like manually adding a property listing in RealHomes.

We have several other real estate add-ons available, each specific to a different theme. This is a walkthrough of the Realia Add-On, which is very similar to the RealHomes Add-On.

https://www.youtube.com/watch?v=_wvz0FfbutA

= Why you should use the RealHomes Add-On for WP All Import =

* Instead of using the Custom Fields section of WP All Import, you are shown the fields like Property Address, Price, etc. in plain English.

* Automatically find the property location using either the property address or the latitude and longitude.

* Import property features, type, city and status to RealHomes' property search dropdowns.

* Link your imported properties to their real estate agent. If they don’t exist yet new agents will be created.

* Easily import gallery images, property attachments, sliders, and property videos.

* Supports files in any format and structure. There are no requirements that the data in your file be organized in a certain way. CSV imports into RealHomes is easy, no matter the structure of your file.

* Supports files of practically unlimited size by automatically splitting them into chunks. WP All Import is limited solely by your server settings.

= WP All Import Professional Edition =

The RealHomes Add-On for WP All Import is fully compatible with [the free version of WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import"). 

However, [the professional edition of WP All Import](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=realhomes) includes premium support and adds the following features:

* Import files from a URL: Download and import files from external websites, even if they are password protected with HTTP authentication. 

* Cron Job/Recurring Imports: WP All Import Pro can check periodically check a file for updates, and add, edit, delete, and update your property listings.

* Custom PHP Functions: Pass your data through custom functions by using [my_function({data[1]})] in your import template. WP All Import will pass the value of {data[1]} through my_function and use whatever it returns.

* Access to premium technical support.

[Upgrade to the professional edition of WP All Import now.](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=realhomes)

= Developers: Create Your Own Add-On =
This Add-On was created using the [Rapid Add-On API](http://www.wpallimport.com/documentation/addon-dev/overview/) for WP All Import. We've made it really easy to write your own Add-On. 

Don't have time? We'll write one for you.

[Read more about getting an Add-On made for your plugin or theme.](http://www.wpallimport.com/add-ons/#developers)

= Related Plugins =
[Import Listings into WP Pro Real Estate 7](https://wordpress.org/plugins/wp-pro-real-estate-7-xml-csv-property-listings-import/)  
[Import Properties into Real Places Theme](https://wordpress.org/plugins/realplaces-xml-csv-property-listings-import/)  
[Import Properties into the Reales WP Theme](https://wordpress.org/plugins/reales-wp-xml-csv-property-listings-import/)  
[Import Property Listings into Realia](https://wordpress.org/plugins/realia-xml-csv-property-listings-import/)  
[Import Property Listings into WP Residence](https://wordpress.org/plugins/wp-residence-add-on-for-wp-all-import/)

== Installation ==

First, install [WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import").

Then install the RealHomes Add-On.

To install the RealHomes Add-On, either:

* Upload the plugin from the Plugins page in WordPress

* Unzip import-property-listings-into-realhomes.zip and upload the contents to /wp-content/plugins/, and then activate the plugin from the Plugins page in WordPress

The RealHomes Add-On will appear in the Step 3 of WP All Import.

== Frequently Asked Questions ==

= WP All Import works with any theme, so what’s the point of using the RealHomes Add-On? =

Aside from making your import easier and simpler, the RealHomes Add-On will fully support your theme’s various image galleries and file attachments as well as allow you to easily import location data.

= Can I import location data for my properties? =

The RealHomes Add-On for WP All Import uses the Google Maps API to import your location data. For free, and without registration, the API allows you to make 2,500 requests per day. If you need more than that you can create a Google for Work account to make up to 100,000 requests per day.

== Changelog ==

= 1.1.4 =
* bug fix: compatibility with latest version of RealHomes.

= 1.1.3 =
* bug fix: warnings & notices in debug log
* bug fix: can't change "Search through the Media Library for existing images" option
* improvement: update rapid add-on api

= 1.1.2 =
* Add support for Year Built field.
* Fix updating "inspiry_floor_plans" based on import settings.
* Fix PHP warnings.

= 1.1.1 =
* Add inspiry_is_published field.
* Add 360 Virtual Tour field

= 1.1.0 =
* Updated add-on API to fix images behavior

= 1.0.9 =
* Fixed custom field update permission check for new posts.

= 1.0.8 =
* Update WP All Import add-on API

= 1.0.7 =
* Add support for floorplans

= 1.0.6 =
* Update functions to avoid conflicts

= 1.0.5 =
* Update WP All Import add-on API

= 1.0.3 =
* Bug fix related to radio options and empty fields

= 1.0.2 =
* Fix admin notice bug

= 1.0.1 =
* Fix title tooltips
* Child theme compatibility

= 1.0.0 =
* Initial release on WP.org.

== Support ==

We do not handle support in the WordPress.org community forums.

We do try to handle support for our free version users at the following e-mail address:

E-mail: support@wpallimport.com

Support for free version customers is not guaranteed and based on ability. For premium support, purchase [WP All Import Pro](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=realhomes).
