=== GeoTargeting Lite - WordPress Geolocation ===
Contributors: timersys
Donate link: https://geotargetingwp.com/
Tags: geolocation, geotargeting, wordpress geotargeting, geo target, cloudflare, reblaze, sucuri, geo targeting, ip geo detect, country redirection, redirect by country, geotargeted popups, geotargeted widgets, ezoic, akamai, woocommerce, ip2location
Requires at least: 3.6
Tested up to: 6.1
Stable tag: 1.3.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

GeoTargeting for WordPress will let you country-target your content based on users IP's and Geocountry Ip database

== Description ==

Based on [Maxmind GeoIP2](http://www.maxmind.com/?rId=timersys) data Geo Targeting plugin for WordPress will let you create dynamic content based on your users country.

With a simple shortcode you will be able to specify which countries are capable of seeing the content.

Compatible with [Wordpress Popups Plugin](https://timersys.com/popups/?utm_source=geot-readme&utm_medium=link&utm_term=popus%20premium&utm_campaign=Popups%20premium). You can now geotarget your popups

If you use popular firewall services such as Cloudflare, Reblaze, Sucuri, Ezoic, Akamai or Clouways the plugin will auto detect real IP from users.

This plugin it's a basic version with limited functionality. For a full geo plugin please refer to https://geotargetingwp.com


Usage:

``[geot country="Argentina"] Messi is the best! [/geot]``
``[geot country="Portugal"] Cristiano ronaldo is the best! [/geot]``
``[geot exclude_country="Portugal"] This text is seeing by everyone except Portuguese people [/geot]``
``Current user is located in [geot_country_name]``
``Current user country code is [geot_country_code]``


The plugin save into it's own cache the country you are in. If you need to test for different countries you have two options:

You can pass a country iso code in the url like this:
``http://demo.com/some-page/?geot_debug=US``

Or you can add in wp-config.php the following to use your own VPN
``define('GEOT_DEBUG',true);``

> <strong>Premium Version</strong><br>
>
> Check the **new premium version** available in ([https://geotargetingwp.com/](https://geotargetingwp.com/?utm_source=geot-readme&utm_medium=link&utm_term=geot%20premium&utm_campaign=Geot%20premium)) that comes with Premium database with much more accuracy.
> * Geo Redirects
> * GeoTarget countries, cities and states
> * Cloudflare geolocation support
> * Geotarget posts / pages entirely
> * Create multiple Redirects based on user countries states or cities
> * Editor button to easily add shortcodes
> * Create multiple regions (group of countries or cities) to use with shortcodes
> * Exclude countries, cities and regions shortcode
> * Dropdown Widget to let users change their country (with flags)
> * Complete set of PHP functions
> * Hide Woocommerce or Easy digital downloads products. Works with any plugin
> * AJAX mode that make plugin compatible with Cache plugins
> * Geotarget menu items, widgets, everything
> * Upcoming integration with other populars plugins
> * Premium support
>


= Wordpress Popups  =

Best popups plugin ever ([https://wppopups.com/](https://wppopups.com/?utm_source=wsi-free-plugin&utm_medium=readme))

== Installation ==

1. Unzip and Upload the directory 'geo-targeting' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to the editor and use as many shortcodes as needed

4. If it fails try uploading files manually by ftp

== Frequently Asked Questions ==

= How can I display content to everyone except some countries =
If you have content that want to be display to USA's users but then you want to show another content to everyone else, you can do the following:
`[geot country="US"] USA only content [/geot]`
`[geot exclude_country="US"] Everyone except USA will see this [/geot]`

2 Letter iso codes are better for geolocation shortcodes but the plugin also accepts country names.

== Changelog ==


= 1.3.6.1 =
* Added country name and code shortcodes

= 1.3.5.1 =
* Settings update

= 1.3.5 =
* Fixed IP on varnish when returning two ips
* Added debug data to ip test page

= 1.3.4.1 =
* Removed start_session from plugin which is not longer used
* Updated latest version

= 1.3.4 =
* Added auto update db into plugin

= 1.3.3 =
* Updated db
* Added support for geot maxmind db updater

= 1.3.2 =
* Updated db

= 1.3.1 =
* Updated db

= 1.3 =
* Updated db
* Added settings page for debug mode
* Added Ip testing page

= 1.2.1 =
* Updated db
* Fixed bug where countries db not being populated after uninstall
* Added new debug method for easy debugging

= 1.2 =
* Updated db
* Updated country db list of iso codes
* Added clouways real ip detection

= 1.1.9 =
* Updated db
* Updated readme file
* Changed default country for bots/crawlers

= 1.1.8 =
* Updated db
* Code improvements

= 1.1.7 =
* Updated db
* Added akamai and Ezoic real IP detection
* Added crawler detect class for better detection

= 1.1.7 =
* Updated db
* Real Ip is autodetected from Cloudflare, sucuri and Reblaze

= 1.1.6 =
* Fixed problem with fallback country generating undefined errors
* Fixed problem that was generating installation errors
* Updated ip database

= 1.1.5 =

* Added catch to all exceptions
* Updated ip country database

= 1.1.4 =

* Fixed bug when address is not found
* Removed country calculation on ajax and cron calls

= 1.1.3 =
* Updated popups integration for latest version
* Updated country Database

= 1.1.2 =
* Fixed function country name
* Added fallback in case IP not found

= 1.1.1 =
* Fixed bug with popups integration
* Fixed bug in some shortcodes and functions

= 1.1 =

* Now we use Maxmind API and mmdb database instead of loading mysql server
* No more heavy databases installs on plugin installation
* Added cloudflare geolocation

= 1.0.3 =

* Added support for [Wordpress Popups Plugin](https://wordpress.org/plugins/popups/)
* Added multisite support

= 1.0.2 =

* Added sessions to cache user country and calculate it just once per session
* Updated IP database
* Removed calculate IP in admin area because was not necessary

= 1.0.1 =

* Fixed error uploading data on activation or certain servers
* Fixed error in php functions
* Updated IP database

= 1.0.0 =

* Plugin launched!
