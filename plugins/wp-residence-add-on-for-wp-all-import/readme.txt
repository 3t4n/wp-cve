=== Import Property Listings into WP Residence ===
Contributors: soflyy, wpallimport
Tags: real estate, import real estate, import real estate listings, import properties, import property listings, wpresidence, import wpresidence, import wpresidence properties, import wpresidence listings
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.9
Tested up to: 6.4
Stable tag: 1.3.0

Easily import property listings from any XML or CSV file to the WP Residence theme with the WP Residence Add-On for WP All Import.

== Description ==

The WP Residence Add-On for [WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import") makes it easy to bulk import your property listings to the WP Residence theme in less than 10 minutes.

The left side shows all of the fields that you can import to and the right side displays a property listing from your XML/CSV file. Then you can simply drag & drop the data from your XML or CSV into the WP Residence fields to import it.

The importer is so intuitive it is almost like manually adding a property listing in WP Residence.

We have several other real estate add-ons available, each specific to a different theme. This is a walkthrough of the Realia Add-On, which is very similar to the WP Residence Add-On.

https://www.youtube.com/watch?v=_wvz0FfbutA

= Why you should use the WP Residence Add-On for WP All Import =

* Instead of using the Custom Fields section of WP All Import, you are shown the fields like Property Address, Price, etc. in plain English.

* Complete support for WP Residence custom fields - add new fields in WP Residence's theme options and import to them with the add-on's drag and drop interface.

* Automatically find the property location using either the property address or the latitude and longitude.

* Import property actions, type, city and areas to WP Residence' advanced property search dropdowns.

* Link your imported properties to their real estate agent. If they don’t exist yet new agents will be created.

* Assign properties to WordPress users.

* Add new features and amenities automatically during import.

* Easily import propert images, sliders, and property videos.

* Supports files in any format and structure. There are no requirements that the data in your file be organized in a certain way. CSV imports into WP Residence are easy, no matter the structure of your file.

* Supports files of practically unlimited size by automatically splitting them into chunks. WP All Import is limited solely by your server settings.

= WP All Import Professional Edition =

The WP Residence Add-On for WP All Import is fully compatible with [the free version of WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import"). 

However, [the professional edition of WP All Import](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wpresidence) includes premium support and adds the following features:

* Import files from a URL: Download and import files from external websites, even if they are password protected with HTTP authentication. 

* Cron Job/Recurring Imports: WP All Import Pro can check periodically check a file for updates, and add, edit, delete, and update your property listings.

* Custom PHP Functions: Pass your data through custom functions by using [my_function({data[1]})] in your import template. WP All Import will pass the value of {data[1]} through my_function and use whatever it returns.

* Access to premium technical support.

[Upgrade to the professional edition of WP All Import now.](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wpresidence)

= Developers: Create Your Own Add-On =
This Add-On was created using the [Rapid Add-On API](http://www.wpallimport.com/documentation/addon-dev/overview/) for WP All Import. We've made it really easy to write your own Add-On. 

Don't have time? We'll write one for you.

[Read more about getting an Add-On made for your plugin or theme.](http://www.wpallimport.com/add-ons/#developers)

= Related Plugins =
[Import Listings into WP Pro Real Estate 7](https://wordpress.org/plugins/wp-pro-real-estate-7-xml-csv-property-listings-import/)  
[Import Properties into Real Places Theme](https://wordpress.org/plugins/realplaces-xml-csv-property-listings-import/)  
[Import Properties into RealHomes Theme](https://wordpress.org/plugins/realhomes-xml-csv-property-listings-import/)  
[Import Properties into the Reales WP Theme](https://wordpress.org/plugins/reales-wp-xml-csv-property-listings-import/)  
[Import Property Listings into Realia](https://wordpress.org/plugins/realia-xml-csv-property-listings-import/)

== Installation ==

First, install [WP All Import](http://wordpress.org/plugins/wp-all-import "WordPress XML & CSV Import").

Then install the WP Residence Add-On.

To install the WP Residence Add-On, either:

* Upload the plugin from the Plugins page in WordPress

* Unzip import-property-listings-into-wpresidence.zip and upload the contents to /wp-content/plugins/, and then activate the plugin from the Plugins page in WordPress

The WP Residence Add-On will appear in the Step 3 of WP All Import.

== Frequently Asked Questions ==

= WP All Import works with any theme, so what’s the point of using the WP Residence Add-On? =

Aside from making your import easier and simpler, the WP Residence Add-On will fully support your theme’s various image galleries and file attachments as well as allow you to easily import location data.

= Can I import location data for my properties? =

The WP Residence Add-On for WP All Import uses the Google Maps API to import your location data. For free, and without registration, the API allows you to make 2,500 requests per day. If you need more than that you can create a Google for Work account to make up to 100,000 requests per day.

== Changelog ==

= 1.3.0 =
* improvement: add WP-CLI support
* improvement: add ability to assign agencies to properties
* improvement: add missing fields in "Agents" imports
* improvement: add missing fields in "Properties" imports
* improvement: add failed geocoding explanations in import history log
* improvement: remove deprecated fields
* improvement: add import history log updates for all updates
* improvement: update rapid-addon API
* API: add filter wpai_wpresidence_addon_enable_logs
* bug fix: Google Maps data doesn't import correctly in WP Residence 3.2
* bug fix: field "Property in Theme Slider" doesn't import correctly
* bug fix: PHP notices during imports
* bug fix: "No API Key" option for geocoding shouldn't be available (Google requires an API key)

= 1.2.3 =
* bug fix: warnings & notices in debug log
* bug fix: can't detect Custom Fields in latest version of WP Residence
* bug fix: can't match existing features with non-English characters
* bug fix: can't change "Search through the Media Library for existing images" option
* improvement: add ability to import Secondary Agents
* improvement: update rapid add-on api
* API: add new filter wpai_wp_res_is_set_menu_order

= 1.2.2 =
* Update Rapid Add-On API.
* Fix duplicate properties being created.
* Fix PHP warnings & notices.
* Fix detect Property Custom Fields in latest version of WP Residence.

= 1.2.1 =
* Update Rapid Add-On API.
* Add support for energy index and class.
* Fix the order of imported images.
* Fix PHP notices/warnings.
* Fix "Leave these fields alone, update all other Custom Fields" for custom fields added by users.

= 1.2.0 =
* Import "hidden_address" field to fix property search results, update rapid add-on api.

= 1.1.9 =
* Check if current slider is array to avoid import termination

= 1.1.8 =
* Added 'Property in theme Slider' field

= 1.1.7 =
* Fixed import image gallery for new version of WP Residence
* Added subunits option

= 1.1.6 =
* Fixed PHP notice.
* Added 'Show Title' field
* Added 'Use a custom property page template' field

= 1.1.5 =
* Fixed cron import bug.

= 1.1.4 =
* Added ability to import Agents.
* Added before price label field.
* Update Rapid Add-On API.

= 1.1.3 =
* Update Rapid Add-On API to fix images behavior.
* Fixed importing empty features.

= 1.1.2 =
* Added missing checks for empty variables in foreach loops.

= 1.1.1 =
* Fixed field update permission checks and updated rapid addon api.

= 1.1.0 =
* Fix bug related to Property Custom Fields

= 1.0.9 =
* Fix PHP Warning when no WP Residence custom property details are set
* Fix add-on running for non-property imports

= 1.0.8 =
* Fix manually importing lat/long location data

= 1.0.7 =
* Fix bug related to assigning properties to users

= 1.0.6 =
* Udpate WP All Import add-on API

= 1.0.5 =
* Fix admin notice bug

= 1.0.4 =
* Fix radio options bug

= 1.0.3 =
* Fix admin notice bug

= 1.0.2 =
* Fix title tooltips
* Child theme compatibility

= 1.0.1 =
* Minor bug fix

= 1.0.0 =
* Initial release on WP.org.

== Support ==

We do not handle support in the WordPress.org community forums.

We do try to handle support for our free version users at the following e-mail address:

E-mail: support@wpallimport.com

Support for free version customers is not guaranteed and based on ability. For premium support, purchase [WP All Import Pro](http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=wpresidence).
