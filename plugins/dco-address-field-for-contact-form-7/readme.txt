=== DCO Address Field for Contact Form 7 ===
Contributors: denisco
Tags: autocomplete, suggestion, suggest, address field, address suggestion contact form 7, cf7
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a autocomplete suggestion address field for Contact Form 7

== Description ==
Adds a autocomplete suggestion address field tag for Contact Form 7.
Uses Yandex Maps API (https://tech.yandex.com/maps/mapsapi/) or Google Maps API (https://developers.google.com/maps/documentation/javascript/) to find the address. 

You can restrict the country and/or city (Yandex only) in which the address will be searched for each field separately.
To restrict, you need to fill the default value attribute in the format `Default value : Country, city` (e.g. `Enter the address : Russia`, Moscow, `Your address : London`, `: Moscow`)

== Installation ==
1. Upload `dco-address-field-for-contact-form-7` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Restricted search only in London
2. Restricted search only in Moscow
3. Unrestricted search
4. Insert Address field tag
5. Example Contact Form

== Changelog ==

= 1.1 =
* Added Google Maps API-based address autocomplete functionality

= 1.0.1 =
* Small Fixes (You may need to re-save the plugin settings page)

= 1.0.0 =
* Initial Release