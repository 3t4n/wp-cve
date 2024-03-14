=== Business Hours Indicator ===
Contributors: maartenbelmans, studiowombat
Donate link: https://www.paypal.me/maartenbelmans
Tags: business hours, opening times, opening hours, open indication, currently open, business hours overview, indicator, closed, open times, closing times
Requires at least: 3.7
Tested up to: 6.4.2
Stable tag: 2.4
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display opening hours and if you're currently open/closed, with countdown to next opening. Show or hide content only when open/closed & a lot more!

== Description ==

* Highly customizable shortcodes to display your opening times in any format
* Shortcodes to indicate if you are currently open or closed
* Alternate message when you're almost opening or closing
* Shortcodes to display something only when you are open or closed
* Easily style everything
* 4 widgets (does the same as the shortcodes)
* Supports Vacations
* Supports 'special dates' with different opening times (e.g: holidays)
* Supports countdown until your next opening/closing time
* Supports multiple locations
* Developer API (more info below)
* Frontend translated in: Dutch, German, English, Greek, Spanish, Italian, French, Bulgarian, Swedish, Danish,
Slovakian, Slovenian, Lithuanian, Croatian, Russian, Czech, Norwegian, Finnish, Icelandic
* beautiful & user-friendly settings screens for you, or your client, to work in

Check out a complete list of features below.

= Quick links =

* [&raquo; Examples](http://demo.studiowombat.com/bhi-demos/?utm_source=wporg&utm_medium=referral&utm_campaign=readme)
* [&raquo; Documentation](https://www.studiowombat.com/knowledge-base/?utm_source=wporg&utm_medium=referral&utm_campaign=readme)
* [&raquo; Pro Version](https://www.studiowombat.com/plugin/business-hours-indicator/?utm_source=wporg&utm_medium=referral&utm_campaign=readme)

= Requirements =

* PHP 7.0 or higher
* WordPress 3.7 or higher

= General features =

* Shortcode to display business hours
* Widget to display business hours
* Shortcode to display if you're currently open/closed
* Widget to display if you're currently open/closed
* Shortcode to display content only when you're open
* Shortcode to display content only when you're closed
* Choose your prefered time zone
* Display times in 12 or 24 hour format
* Enter multiple locations for more businesses (up to 3 in the free version)
* Include special hours such as holidays
* Include vacation hours
* Highlight the current day
* Frontend translated in: Dutch, German, English, Greek, Spanish, Catalan, Italian, French, Bulgarian, Swedish, Danish,
Slovakian, Slovenian, Lithuanian, Croatian, Russian, Czech, Norwegian, Finnish, Icelandic, Latvian, Hungarian.

= Displaying opening times =

* Show the opening hours in a table
* Show the opening hours inline
* Consolidate the output: group days with the same opening hours
* Show/hide holiday hours
* Show/hide vacation hours
* Highlight the current day

= Currently open/closed Indication =

* Edit the "we're currently open" message
* Edit the "we're currently closed" message
* Include the current time in the output
* Include the current day in the output
* Add an opening/closing soon warning like "hurry, we're closing in 35 minutes"

= Limitations =

The free version has some limitations you should be aware of. Our pro version (features below) fixes those.

 * The free version does not work with caching mechanisms.
 * It's not possible to add a break in opening hours.
 * You can add up to 3 locations in the free version.

= Developer API =
Use the plugin anywhere in WordPress by using the following calls:

* `MBHILITE()->is_open('location name')` returns true if the location is currently open. False otherwise.
* `MBHILITE()->is_closed('location name')` returns true if the location is currently closed. False otherwise.
* `MBHILITE()->get_opening_hours('location name')` returns an array of objects containing the location's opening hours.
* `MBHILITE()->get_specials('location name')` returns an array of objects containing the location's special opening hours.
* `MBHILITE()->get_vacations('location name')` returns an array of objects containing the location's vacations.

More documentation coming soon.

= PRO FEATURES =

* Supports caching
* SEO features
* Enter unlimited multiple locations for more businesses
* Multiple time slots/day for when you close at noon
* Woocommerce support: disable orders + hide "add to cart" buttons when you are closed.
* 'Message builder' allows you to add dynamic codes to build any message you like. A few examples:
      * Today is March 8, we're open from 9AM to 5PM.
      * It's 7:12PM, we're closed. We open again on Thursday, in 7 hours and 45 minutes.
      * Hurry, we're closing in 13 minutes!
      * ..And much more!
* Only show today's opening times
* Seasonal hours
* Set how many minutes or hours in advance to show "opening/closing soon" message.
* Customize output with line breaks & separators
* Premium support
* Receive updates & bugfixes
* Show abbreviated days in outputs
* More flexibility
* Priority feauture requests
* And more..!

Find out more about the [pro version here](https://www.studiowombat.com/plugin/business-hours-indicator/?utm_source=wporg&utm_medium=referral&utm_campaign=readme)

== Installation ==

Installing the Business Hours Indicator plugin is very easy:

1. Install from the WordPress Dashboard > Plugin page
2. Or manually download the plugin and extract to wp-content/plugins/
3. Activate the plugin
4. Go to Wordpress Dashboard > Settings > Business Hours Indicator
5. Enter your location, daily opening hours, and fiddle with more settings if you want
6. Use the shortcode `[mbhi location="my location"]` to display the output on pages or posts
7. Or use the PHP code `do_shortcode('[mbhi location="my location"]')` to display the output anywhere in your theme

== Frequently Asked Questions ==

= Does these shortcodes need extra options? =

Yes, at least 1. You should give it a 'location' attribute. If you don't, the first location is used.

= How can I style the business hours overview? =

The code exposes enough CSS classes to give you many styling options. You can
find all classes [here](https://www.studiowombat.com/knowledge-base/styling-the-output/?utm_source=wporg&utm_medium=referral&utm_campaign=readme).

== Screenshots ==

1. Various examples of what this plugin can do.
2. Settings screen.
3. Enter opening times for a location.
4. Example of how it could look like on the frontend.

== Changelog ==

= version 2.4 =
 * Fix: fixed PHP warnings in PHP 8.2.
 * Other: modernized code.
 * Warning: going to require a minimum version of PHP 7.0 in the future, to align with WordPress.

= version 2.3.9 =
 * Other: verify compatibility with WordPress 6.1.
 * Other: update internal links.
 * Other: readme changes.

= version 2.3.8 =
 * Other: verify compatibility with WordPress 6.0.

= version 2.3.7 =
 * Fix: fixed a few minor styling issues on the admin settings page.
 * Other: verify new WordPress version.

= version 2.3.6 =
 * Other: verify new WordPress version.
 * Other: update documentation links.

= version 2.3.5 =
 * Update: secured the indicator HTML output.

= version 2.3.4 =
 * Update: Added Polish translations.

= version 2.3.3 =
 * Update: verify WP compatibility and bump version tags.

= version 2.3.2 =
 * Added: Estonian translations.
 * Update: bumped minimum PHP version to 5.6.

= version 2.3.1 =
 * Fix: fixed a bug with "output" and "display" settings in the shortcode.
 * Update: verified WP 5.6 compatibility.

= version 2.3.0 =
 * Update: Verified WordPress 5.5 compatibility.
 * Fix: minor code cleanup.

= version 2.2.9 =
 * Added: Hungarian translations.

= version 2.2.8 =
 * Added: Catalan translations.
 * Update: verified WP 5.4.1.

= version 2.2.7 =
 * Added: Latvian language.
 * Update: changes to Lithuanian translation of abbreviated months.
 * Update: PHP improvement removed deprecated function create_function.

= version 2.2.6 =
 * Update: Opening times are now selectable with 5 minute intervals instead of 15-minute intervals.

= version 2.2.5 =
 * Fix: fixed a bug in the admin settings screen when location names are numbers.
 * Fix: Italian translation typo.

= version 2.2.4 =
 * Added: New translations for Portuguese and Brazilian Portuguese.

= version 2.2.3 =
 * Added: Icelandic language.

= version 2.2.2 =
 * Fix: fixed a bug with wrong "open" indication for holidays going past midnight.

= version 2.2.1 =
 * Update: better translation compatibility.
 * Added: Swiss High German language.

= version 2.2.0 =
 * Added: Finnish translations.

= version 2.1.9 =
* Added: Norwegian translations.

= version 2.1.8 =
 * Added: Croation translations.
 * Added: Lithuanian translations.

= version 2.1.7 =
 * Added: added NL_BE language.
 * Update: fixed warning for create_function in PHP 7.2.

= version 2.1.6 =
 * Enhancements: added a filter 'mbhi_capability' allowing you to change who can edit hours.

= version 2.1.5 =
 * Fix: fixed a bug when deleting one or more holidays within the same month.

= version 2.1.4 =
 * CSS changes in the backend to support plugins that override WordPress' default style.

= version 2.1.3 =
 * Added: added Slovenian language.

= version 2.1.2 =
 * Added: added Canadian French and Belgian French language.

= version 2.1.1 =
 * Added: Czech language.
 * Fix: bugfix in conversion service.

= version 2.1.0 =
 * Fix: fixed a bug with is_open and holidays.

= version 2.0.9 =
 * Fix: fixed a bug with is_open.

= version 2.0.8 =
 * Update: Lists with vacations and specials are now ordered by ascending date.
 * Update: Added Danish translations.

= version 2.0.7 =
 * Update: added Russian translations.
 * Fix: fixed a bug with the indicator and vacations.

= version 2.0.6 =
 * New feature: added widget to display content only when closed.
 * New feature: added widget to display content only when open.
 * Changes: minor style changes.
 * Testing: verified the plugin on WP 4.7.5

= version 2.0.5 =
 * New feature: easily accessible API for developers.
 * New feature: select quartes of an hour.
 * Testing: verified the plugin on WP 4.7.4

= version 2.0.4 =
 * Fix: if a vacation was today, it wasn't denoted as the current day.

= version 2.0.3 =
 * Fix notice: wrap loops to check for arrays.

= version 2.0.2 =
 * Fix admin screen bug with dependency fields.

= version 2.0.1 =
 * Fixed a bug where some translations weren't working
 * Fixed a bug where a vacation was displayed even though the setting was set to false.
 * AFTER UPGRADING, PLEASE CHECK IF YOUR WIDGETS ARE STILL INTACT.

= version 2.0.0 =
 * Rewritten the core of the plugin to implement features faster.
 * Fixed a few edge-case bugs.
 * AFTER UPGRADING, PLEASE CHECK IF YOUR WIDGETS ARE STILL INTACT.

= Version 1.8.8 =
 * Added Slovak language to translations
 * Verified WP 4.7.1 compatibility

= Version 1.8.7 =
 * Getting rid of closure in favor of normal function. This disables warnings for some versions of WP.

= Version 1.8.6 =
 * Finally added 2 widgets to display (a) your business hours and (b) indication of being currently open/closed.
 * UI changes.

= Version 1.8.5 =
 * Fixed php warning 'invalid foreach'.

= Version 1.8.4 =
 * Added Bulgarian language (special thanks to D. Stoykow)

= Version 1.8.3 =
 * Addition upon 1.8.2

= Version 1.8.2 =

 * Fixed an issue with PHP 5.3/5.4 versions.

= Version 1.8.1 =

 * Fixed an issue where some WooCommerce websites had a faulty system status.

= Version 1.8.0 =

* Bugfixes
* Rewritten frontend
* Updated languages
* Added Swedish language

= Version 1.7.4 =

* Bugfix: when you aren't open for a few days, the frontend gave a silent error.

= Version 1.7.3 =

* Small bugfix in admin javascript where users weren't able to add multiple locations without refreshing the page.

= version 1.7.2 =

* Proper support for the shortcodes in widgets.
* Remove array literals to support php version 5.3.
* Update documentation.
* Fix bug with ifopen/ifclosed shortcodes for multiple locations.

= version 1.7.1 =

* Fixed a bug where do_shortcode() doesn't show content.

= version 1.7 =

* Added a help tab with shortcodes info.
* shortcodes can now override dashboard settings via attributes.
* Added various new functionality for displaying opening hours.

= Version 1.6 =

* Bugfix where holidays couldn't be denoted as 'closed'.

= Version 1.5.1 =

* Bugfix between Woocommerce and this plugin.

= Version 1.5 =

* Added more CSS classes for your styling needs.
* Ability to print a consolidated (= shortened) opening hours table.
* Reorganized and added some settings.

= Version 1.4 =

* Small bugfixes

= Version 1.3 =

* Added [mbhi_ifclosed] shortcode
* Added [mbhi_ifopen] shortcode

= Version 1.2 =

MAJOR CHANGE &mdash; You'll have to re-enter your settings!

* Added locations
* Added vacation/holiday exception
* Changed settings screen
* Added more settings
* Added a new shortcode to display a location's business hours table

= Version 1.1 =

* Code enhancements
* Approximation: include a different sentence when your business is almost opening/closing.

= Version 1.0 =

 * Initial version