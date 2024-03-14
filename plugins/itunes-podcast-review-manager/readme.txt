=== iTunes Podcast Review Manager ===

Contributors: Doug Yuen
Author URI: https://reviewranger.com
Plugin URI: https://efficientwp.com/plugins/itunes-podcast-review-manager
Tags: itunes, podcast, podcasts, podcasting, review, reviews, international, country, countries, audio
Requires at least: 4.0
Tested up to: 5.3.2
Stable tag: trunk
License: GPLv2 or later

Get your iTunes podcast reviews from all countries. Checks iTunes automatically and displays your podcast reviews in a sortable table.

== Description ==

Checks iTunes for all international reviews of a podcast. Your iTunes reviews are displayed in the backend menu, and optionally on the front end of your site using the [iprm] shortcode. iTunes is automatically checked for new podcast reviews every 4 hours. Note: sometimes in iTunes, the review feeds for certain countries are unreachable, and you will need to wait for the next automatic check or click the button to check manually.

We're working on a new service for checking your international podcast reviews. It will include features like email notifications, charts, filtering, multiple podcasts, and more. For more information and to find out when we launch, please go to [ReviewRanger.com](http://reviewranger.com "Review Ranger").

Created by [EfficientWP](https://efficientwp.com "EfficientWP"). Flag icons courtesy of [IconDrawer](http://www.icondrawer.com "IconDrawer").

== Installation ==

1. Upload `itunes-podcast-review-manager.zip` into your plugin directory (typically `/wp-content/plugins/`).
2. Unzip the `itunes-podcast-review-manager.zip` file.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to the Podcast Reviews panel to configure settings.

== Frequently Asked Questions ==

[Plugin page on EfficientWP](https://efficientwp.com/plugins/itunes-podcast-review-manager "iTunes Podcast Review Manager")

== Screenshots ==

1. The plugin panel in the Podcast Reviews menu.

== Changelog ==

= 3.7 =
* \[CHANGED\] Added country names as alt and title text to flag icons
* \[CHANGED\] Added more countries
* \[CHANGED\] Security improvements
* \[CHANGED\] Cleaned up code formatting

= 3.6 =
* \[CHANGED\] Randomized order of country checks (iTunes will time out and not go through the full list of countries)

= 3.5 =
* \[FIXED\] Issue with duplicate reviews from iTunes

= 3.4 =
* \[FIXED\] Issue with podcasts with numbers in title

= 3.3 =
* \[FIXED\] Fix: "Parse error: syntax error, unexpected '[' " on old versions of PHP (UPGRADE!)

= 3.2 =
* \[FIXED\] Issue with missing files on upgrade

= 3.1 =
* \[CHANGED\] Review Averages now show 2 decimal points
* \[CHANGED\] Reviews sort by date by default
* \[FIXED\] Review history not loading/saving properly

= 3.0 =
* \[CHANGED\] Major changes from the previous point versions not previously released
* \[CHANGED\] Responsive design CSS

= 2.3 =
* \[ADDED\] Client-side review sorting
* \[CHANGED\] Improved searching code
* \[CHANGED\] Design changes

= 2.2 =
* \[REMOVED\] Auto check for iTunes URL from PowerPress
* \[CHANGED\] Simplified review sorting code
* \[CHANGED\] Changed to object oriented approach

= 2.1 =
* Added flag icons in a new column
* Added iprm shortcode to display the reviews on the front end of websites
* Design changes - removed navigation borders, set button hover colors
* Added a data reset button
* Added a function to remove the cron job on plugin deactivation
* Added notices and alerts
* Updated screenshot and banner images

= 2.0 =
* Major design and UI improvements
* Code cleanup
* Added localization options
* Confirmed compatibility up to WordPress 4.2.1

= 1.2 =
* Added plugin menu icon
* Added plugin icon to plugin installer
* Added column sorting
* Added capability to get more than the 50 latest reviews for each country
* Improved backend styling
* Added link to email opt-in form for premium service

= 1.1 =
* Added review caching, so the plugin no longer checks for new reviews on every page load. It loads the last cache of reviews, unless the cache is empty, in which case it will check for reviews as it loads the page.
* Added automatic review checking every 4 hours.
* Added a manual review check button and it also displays the results of the last 5 checks.
* Rearranged display of tables and made styling changes.
* Added the total number of reviews and the review average to the main table column headings.
* Added comments to plugin code.
* Updated screenshot.

= 1.0 =
* Changed iTunes feed URLs to use https.

= 0.1 =
* Initial release.

== Upgrade Notice ==

Coming soon.

