=== Login IP & Country Restriction ===
Contributors: Iulia Cazan
Tags: login restriction, security, authenticate, ip, country code, login filter, login, restrict, allow IP, allow country code, auth, security redirect, block country code, block IP
Requires at least: not tested
Tested up to: 6.3
Stable tag: 6.4.1
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ


== Description ==
This plugin hooks in the authenticate filter. By default, the plugin is set to allow all access and you can configure the plugin to allow the login only from some specified IPs or the specified countries. PLEASE MAKE SURE THAT YOU CONFIGURE THE PLUGIN TO ALLOW YOUR OWN ACCESS. If you set a restriction by IP, then you have to add your own IP (if you are using the plugin in a local setup the IP is 127.0.0.1 or ::1, this is added in your list by default). If you set a restriction by country, then you have to select from the list of countries at least your country. Both types of restrictions work independent, so you can set only one type of restriction or both if you want. Also, you can configure the redirects to frontpage when the URLs are accessed by someone that has a restriction. The restriction is either by country, or not in the specified IPs list.


== Installation ==
* Upload `Login IP & Country Restriction` to the `/wp-content/plugins/` directory of your application
* Login as Admin
* Activate the plugin through the 'Plugins' menu in WordPress


== Hooks ==
authenticate


== Frequently Asked Questions ==
None


== Screenshots ==
1. Options to select a specific login restriction types.
2. Options to disable XML-RPC authenticated methods.
3. Options to allow and block specified IPs.
4. Options to select/deselect countries as allowed or blocked.
5. Options to configure redirects for visitors that match the authentication restrictions.


== Changelog ==
= 6.4.1 =
* Tested up to 6.3
* Minor styles updates
* Added the country code after the name in the listing

= 6.4.0 =
* Tested up to 6.2
* Improved PHP 8.1 compatibility
* Added the option to remove the 120.0.0.1 and ::1 from the allowed IPs (intended only for Cloudflare use)
* Added WP native headers to the redirects when used

= 6.3.0 =
* Tested up to 5.9
* Added a new debug option that allows to test the country code that the application detects for a specified IP
* Added a new option that allows to bypass the PHP native function used for country code detection (if that does not work properly), and in this way to enable alternative detection methods

= 6.2.2 =
* Tested up to 5.8.1
* Additional support for the PRO key reset
* Fix warning for string replacement in debug output

= 6.2.1 =
* Tested up to 5.8
* Fix the styles after applying settings
* Fix the key input for other settings
* Added more string translation

= 6.2.0 =
* Tested up to 5.7.2
* Tested up to PHP 8.0
* Implemented compatibility changes for PHP 5.6, 7.3, 7.4
* Added debug tab that allows to export/import plugin settings
* Added debug info tab (mostly for troubleshooting)
* Accessibility improvements

= 6.1 =
* Tested up to 5.6.2
* Added note about using wildcard for the IPs hence no filter to apply
* Added note about no country filter to apply
* Fix deselection of country code
* Added more filter types
* Fix filter when no whitelisted IPs

= 6.0 =
* Added custom firewall rules
* Added blocked IPs feature
* Added blocked countries feature
* Added the option to disable XML-RPC authenticated methods (suggested by Florin Oprea)
* New UI for selecting countries
* JavaScript updates decouple the plugin from the jQuery library
* PHP 8 compatibility updates
* Additional support for the PRO version that could include more firewall rules and IP and country simulation
* Added the current IP in the list of allowed IPs when you want to enable the restriction
* Tested up to 5.6.1

= 5.1 =
* Fix parse error

= 5.0 =
* Tested up to 5.6, + minor standards updates for compatibilty
* Additional support for the PRO version that include resticting login from a single IP per user, custom forbidden message, simulate IP and country code, users listing restriction info column.

= 4.1 =
* Tested up to 5.4
* Cloudflare compatibility.

= 4.0 =
* Tested up to 5.3.2
* Icons and styles updates.
* Added support for extended options.

= 3.6 =
* Tested up to 5.2.2
* Fix settings last tab select after save
* Sticky letters list, for better navigation
* Added more padding to the countries letters blocks (for better view on initial scroll)

= 3.5 =
* Tested up to 5.2.1
* Added new screenshots with the latest UI

= 3.4 =
* Tested up to 5.1.1
* UI update, compact options, responsive
* Add redirect options
* Add current user info and restriction info

= 3.3 =
* Tested up to 4.9.7
* Added translations
* Added geoplugin fallback

= 3.2 =
* Tested up to 4.8.3
* Added the readable info about the login restriction
* Added the countries letters for a faster navigation
* Added more save buttons

= 3.1 =
* Update the method to retrieve the data

= 3.0 =
* The allowed countries are separated visually from the rest of countries, compatibility update

= 2.0 =
* allow to configure the IP list
* allow to select the allowed countries


== Upgrade Notice ==
None


== License ==
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.


== Version history ==
6.4.1 - Tested up to 6.3, minor styles updates, added the country code after the name in the listing
6.4.0 - Tested up to 6.2, new the option to remove the localhost from the allowed IPs, PHP 8 compatibility, redirect headers updates
6.3.0 - Tested up to 5.9, new debug option for test the country code, new debug option to bypass the PHP native function used for country code detection
6.2.2 - Tested up to 5.8.1, PRO key reset, fix warning for string replacement in debug output
6.2.1 - Tested up to 5.8, fix the styles after applying settings when using with core >= 5.8, fix key input, more string translation
6.2.0 - Tested up to 5.7.2 and PHP 8.0, implemented compatibility changes for PHP 5.6, 7.3, 7.4, new debug tab that allows to export/import plugin settings, debug info (mostly for troubleshooting), accessibility improvements
6.1 - Added notes no IPs or no country filter to apply, fix deselection of country code, more filter types, fix filter when no whitelisted IPs, assets updates.
6.0 - Tested up to 5.6.1, added custom firewall rules, blocked IPs, blocked countries, disable XML-RPC authenticated methods, new UI for selecting countries, JavaScript updates, PHP 8 compatibility, additional support for the PRO version that could include more firewall rules and IP and country simulation, added the current IP.
5.1 - Fix parse error.
5.0 - Tested up to 5.6, + minor standards updates for compatibilty, additional support for the PRO version.
4.1 - Tested up to 5.4, Cloudflare compatibility.
4.0 - Tested up to 5.3.2, icons and styles updates, support for extended options.
3.6 - Tested up to 5.2.2, fix settings last tab select after save, sticky letters list, for better navigation, more padding to the countries letters blocks
3.5 - Tested up to 5.2.1, new screenshots with the latest UI
3.4 - Tested up to 5.1.1, UI update, add redirect options, add current user info and restriction info
3.3 - Tested up to 4.9.7, added translations, added geoplugin fallback
3.2 - Tested up to 4.8.3, added the readable info about the login restriction, added the countries letters for a faster navigation
3.1 - Update method
3.0 - The allowed countries are separated visually from the rest of countries + version test
2.0 - Configurable version
1.0 - Initial version
