=== HurryTimer - An Scarcity and Urgency Countdown Timer for WordPress & WooCommerce ===
Contributors: nlemsieh
Donate link: https://www.paypal.me/nlemsieh
Tags: evergreen countdown, sale countdown, woocommerce,recurring countdown,webinar,countdown timer,delivery timer,elementor countdown,divi countdown,shipping cut-off timer,woocommerce timer,scarcity,urgency timer,beaver timer,funnel countdown timer,landing page
Requires at least: 4.0
Tested up to: 6.4
WC requires at least: 3.0
WC tested up to: 8.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Requires PHP: 5.6
Stable tag: 2.9.2

Create unlimited urgency and scarcity countdown timers for WordPress and WooCommerce to boost conversions and sales instantly.

== Description ==

[HurryTimer](https://hurrytimer.com/) is a multi-purpose countdown timer that allows you to create urgency and scarcity that drives clicks, increases sales, and highlights upcoming events or deadlines.

Use HurryTimer to create:

**Evergreen campaigns:**
Give each visitor their own unique countdown timer regardless of their local timezone and the moment they visited your site.

It's a great way to boost conversion by creating a feeling of scarcity, urgency and exclusivity.

**One-time Campaigns:**
Run a campaign between two fixed dates. No matter who visits your site the countdown timer is the same. It's a great way for event-based sales with a firm deadline.

**[PRO] Recurring campaigns:**
Recurring campaigns are self repeating countdown timers that run according to a set of rules. 

It's great way for telling customers about shipping cut-off times to get them to checkout faster. 

You can schedule minutely, hourly, daily, weekly, and monthly recurrences.

### Lite Features
- Evergreen & One-time countdown timers.
- Cookie & IP detection technique for Evergreen campaigns.
- WooCommerce integration.
- Conditionally display countdown timer on product page
- Expiry actions:
  - Hide countdown timer
  - Redirect to URL
  - Display a message
  - Change WooCommerce product stock status
  - Hide WooCommerce product "Add to cart" button
- Auto-Restart: Restart immediately, or at the next visit.
- Live design customizer
- Custom labels: days, hours, minutes, seconds.
- Call-To-Action button.
- Scheduled Campaigns.
- Display the same countdown timer multiple times on the same page.
- Compatible with all page builders out-of-the-box.

### PRO Features

In addition to all features in Lite, HurryTimer Pro provides access to many other powerful features:

- **Recurring Campaigns**: Schedule minutely, hourly, daily, weekly, and monthly campaigns.
- **User Session** detection for evergreen timers, highly recommended for campaigns restricted to logged-in users.
- **More Exiry Actions**: Expire WooCommerce coupon.
- **Multiple Expiry Actions**: Take more than one action when time is up.
- **Expire WooCommerce Coupon action**: Automatically expire a coupon code after timer reaches zero.
- **Advanced Live Design Customizer**: Unlock more styling capabilities to create unlimited design variations.
- **Live Custom CSS**: Add you own CSS code to every element.
- **Sticky Announcement Bar**: Display the countdown timer at the bottom/top of the page.
- **Priority Support**: Get responses fast with 24/7 email and chat support.

[Check out HurryTimer PRO →](https://hurrytimer.com?utm_source=wp_repo&utm_medium=link&utm_campaign=free_version)

### Usage

1. Visit "HurryTimer > Add New Timer".
2. Choose between Evergreen, and One-time.
3. Enter a period for "Evergreen" mode, or select a date and time for "One-Time" mode.
4. Click on "Publish".
5. Copy shortCode and paste it into your post, page, or text widget content. You can also go to "WooCommerce" tab to integrate the countdown timer directly within a product page.

To learn more, check out [HurryTimer documention](https://docs.hurrytimer.com/).

### Bug reports

If you noticed any bug, please post it on the support forum.

### Further reading

For more info check out the following:

* The [HurryTimer Plugin](https://hurrytimer.com/?utm_source=wp_repo&utm_medium=link&utm_campaign=free_version) official homepage.
* [Documention](https://docs.hurrytimer.com/).
* Follow HurryTimer on [Twitter](https://twitter.com/wp_hurrytimer).

== Installation ==

### From within WordPress

1. Visit _Plugins > Add New_.
2. Search for _Hurrytimer_.
3. Install the plugin.
4. Activate the plugin.

### Manually

1. Upload the _hurrytimer_ folder to the /wp-content/plugins/ directory.
2. Activate the Hurrytimer plugin through the _Plugins_ menu in WordPress.


== Frequently Asked Questions ==

= Does it work for WooCommerce? =

Yes, you can display the countdown timer on any product page.

= Is the plugin compatible with page builders, including: Elementor, Beaver, Divi, etc? =

Yes, the plugin is compatible with all page builders out-of-the-box.

= Can a campaign restart automatically when time is up? =

Yes.

= Can I display multiple instances of the same countdown timer on the same page? =

Yes.

= Can I customize the countdown timer look? =

Sure, you can create unlimited customizations with the built-in live customizer.

= The plugin is missing a feature, can you add it? =

We improve HurryTimer continuously to fit your needs, if you have a feature request or feedback [shot us a line](https://hurrytimer.com/contact).

== Screenshots ==

1. "Evergreen" mode settings.
2. "Recurring" mode settings.
3. "One-Time (regular)" mode settings.
4. Actions settings.
5. Add a countdown timer to a WooCommerce single product page.
6. Change every element visibility.
7. Set custom timer labels.
8. Live design customizer settings.
9. Call-to-Action button settings
10. Sticky Bar settings


== Changelog ==

 = 2.9.2 =

 * Stability improvements

 = 2.9.1 =

 * Stability improvements


 = 2.9.0 =

 * Declare compatibility with WP 6.3
 * [WooCommerce] Declare compatibility with v8.0
 * Stability improvements


= 2.8.1 =

 * [WooCommerce] Declare compatibility with High-Performance Order Storage (HPOS)
 * [WooCommerce] Declare compatibility with v7.7

= 2.8.0 = 

* Added rich-text editing for the headline.
* [WPML] Show translated content when using shortcode
* [WooCommerce] Shows translated campaign on product page when using WPML.
* Improved the `{date}` variable
* Shows the variable `{date}` in localized format. 
* Fixed an error causing the reset buttons not working under HurryTimer > Settings
* Fixed IP logs not being cleaned up effectively
* Fixed an issue where using the "Redirect to" action in conjunction with the "Expire coupon" action resulted in a redirect loop.
* Fixed a compatibility issue with PHP 8.2
* Fixed a minor 3rd party library conflict with Savoy theme.

= 2.7.3 = 

* [WooCommerce] Fixed an issue with Astra theme v3.9.2 causing timer to not display in the specified position

= 2.7.2 = 

* Stability improvements

= 2.7.1 = 

* Fixed an issue with the review request notice's buttons not working.

= 2.7.0 = 

* Add compatibility with PHP 8.x
* Added a work-around to fix a PHP 8.1 compatibility issue with WP `dbDelta`
* Show total count of unit till the end when left unit is hidden
* Fix plugin admin scripts conflict with some 3rd-party.
* Added a new filter `hurrytimer_zero_padded_digits` to control digits padding.
* Added a new filter `hurrytimer_auto_pluralize` to automatically pluralize labels.
* Fixed a minor issue with custom CSS not being loaded properly.

= 2.6.3 = 

* Fix 404 page when resetting or de/activating a campaign in multisite network.
* Fix an issue with expiry actions executed when editing in Elementor.
* [WooCommerce] Fix an issue when using the "Change stock status" with external products. 
* Disable saving unfiltered HTML when `DISALLOW_UNFILTERED_HTML` is set to true.
* Minor bugfixes.

= 2.6.2 =

* Fix a compatibility issue with Elementor pop-ups preventing timer from counting down after pop-up is loaded.
* Fix 404 page when resetting or de/activating a campaign in multisite network.
* Fix an issue with expiry actions executed when editing in Elementor.

= 2.6.1 =

* Fix a bug causing some evergreen campaigns to reset on page reload.

= 2.6.0 =

* Add a new option to restart evergreen timer after a specific time
* Fix a conflict with some plugins/themes using Carbon
* Stability improvement

= 2.5.2 =

* Fix an issue with headline shortcodes not rendering.


= 2.5.1 =

* Fixed an issue with the "Reset countdown" for the admin not working properly when logged-in from another browser.
* Stability improvement

= 2.5.0 =
* Fixed an issue with JS and CSS code in the "Display message" not working properly.

= 2.4.0 =

* Added compatibility with WooCommerce 5.0
* Stability improvement.


= 2.3.4 =

* Stability improvement.

= 2.3.3.1 =

- Added compatibility with 5.6.
- Stability improvement.


= 2.3.3 =

- Added the ability to choose which detection methods to use.
- Fixed an issue with Sticky bar excluded URLs not working properly.

= 2.3.2.1 =

- Fixed an issue with excluded pages URLs not saved properly under the sticky bar settings.

= 2.3.1.1 =

- Stability improvement.

= 2.3.1 =

- Fixed timer labels not showing up.

= 2.3.0 =

- Moved headline to Appearance > Elements > Headline. Now you can add campaign's name
- Added ability to reset evergreen timer on page refresh
- Added action "Expire coupon" for WooCommerce which allows to automatically expire a coupon code once timer reaches zero
- Added possibility to use shortcode when sticky bar is enabled
- Added ability to change sticky bar re-opening delay when closed by user
- Added ability to add rich-content in the "Display message" action
- Added possibility to use private products in WooCommerce integration
- Added new options in sticky bar settings
- Fixed issue with timer CSS being randomly deleted/cached after the plugin update
- Fixed issue with evergreen reset button not working properly
- Improved evergreen timer detection
- Improved appearance interface experience: "General" interface merged with "Elements" interface
- Enhanced color picker
- Other bugfixes and stability improvement

= 2.2.27.1 =
- Fixed a small issue with detection when cookies are cached.

= 2.2.27 =

- Fixed menu position conflict.

= 2.2.26 =

- Fixed an issue with WooCommerce settings not displaying all products selection.
- Stability improvement.

= 2.2.25 =

- Fixed an issue with sticky bar not showing properly.

= 2.2.24 =

- Stability improvement.

= 2.2.23 =

- Fixed a bug causing evergreen timers to expire on page refresh for 32-bit/PHP 7.2.22.

= 2.2.22 =

- Added new JS lifecycle hooks for developers: `hurryt:pre-init`, `hurryt:init`, and `hurryt:started`.


= 2.2.21 =

- Added new javascript event `hurryt:finished` that trigger when timer reaches zero. 
- Fixed reset option doesn't re-open sticky bar.

= 2.2.20 =

- Fixed timer doesn't start when it's dynamically added to DOM. 

= 2.2.19 =

- Fix issue with actions with ajax requests.

= 2.2.18 =

- Removed unecessary jQuery modal lib.
- Stability and performance improvement.

= 2.2.17 =

- Stability and performance improvement.

= 2.2.16 =

- Fix minor issue with Elementor builder.

= 2.2.15 =

- Fixed minor issue with recurring mode.
- Universal end date through all timezones based on WP timezone.
- Added few helpful hooks
- Fixed minor compatibility with Block editor

= 2.2.14 =

- Fixed minor issue with timezone

= 2.2.13 =

- Recurring mode improvements

= 2.2.12 =

- Redirect before showing page content

= 2.2.11 =
- Prevent interaction while redirecting

= 2.2.10 =
- Handle some undefined functions when using the slim build of jQuery.
- Fix admin menu position conflict with some plugins.


= 2.2.9 =

- Fixed minor issue causing duplicate countdown timer instance when using sticky bar on product page.


= 2.2.8 =

- Fixed minor causing `display on` not saved properly under Appearance > Sticky Bar. 

= 2.2.7 =

- Added two new filters for developers to control campaign display `hurryt_show_sticky_bar` to show/hide sticky bar and `hurryt_show_campaign` to show/hide the campaign. 

= 2.2.6 =

- Fixed minor bug when specifying pages in Sticky Bar. 

= 2.2.5 =

- [Fixed] Fix time-to-recur from the browser side.
- [Updated] Tested up to

= 2.2.4 =

- [Improved] Improved recurring mode when setting end option to "Never" for low-resource servers.

= 2.2.3 =

- [Added] Create a set of conditions to determine when a campaign will be displayed on selected products.

= 2.2.2 =

- [Fixed] Can't add additional action (bug since v2.2.0).
- [Fixed] "Show close button" not updated correctly.

= 2.2.1 =

- [Fix] Added a virtual limit when the end option is set to "Never", this will prevents script from crashing on an infinitely recurring rule, you can change the virtual limit using the filter `hurryt_recurring_vlimit` 

= 2.2.0 =

- [New] Create unlimited and customizable recurring countdown timers (Pro).
- [Added] Reset runnning evergreen countdown timers.
- [Added] New setting that allows you to disable actions when editing or previewing a page in the admin area.
- Minor Bugfixes and improvements.

= 2.1.8 =

* [fixed] Fixed a bug that add a delete permanently link to other posts table rows.
* [improved] Move campaign to trash instead of delete permanently.

= 2.1.7 =

* fixed bug with regular mode.

= 2.1.6 =

* bugfix

= 2.1.5 =

* Display sticky bar on selected products in WooCoommerce tab (pro version).
* Improved settings interface.
* Improved stability.

= 2.1.3 =

* [Fix] bugfix.
* Stability improvement.

= 2.1.2 = 

* [Added] Sticky Bar.
* [Added] Call To Action.
* Stability improvement.

= 2.0.4 = 

* Fix some actions that do not run correctly.

= 2.0.3 = 

  * Disable WooCommerce integration by default.

  = 2.0.2 = 

  Stability improvement 

  = 2.0.1 =

  * Clean plugin cache after appearance is changed.
  
  = 2.0.0 =

* Live style customizer.
* Live custom CSS.
* Ability to change every element's visibility.
* New actions.
* Add more than one action at the same time.
* Stability improvement.

  = 1.2.4 =

  * Added compatibility for WordPress 5.1.
  * Stability improvement.
  
  = 1.2.3 =

  * Improved cookie detection.
  
  = 1.2.2 =
  
  * Fixed bug evergreen detection not working
  
  = 1.2.1 =
  
  * IP detection stability improvement.
  
  = 1.2.0 =
 
 * Improved IP/Cookie detection.
 * New feature: Restart evergreen countdown automatically.
 * Fixed some minor bugs.
 
  = 1.1.3 =
 
 * Added seconds in evergreen mode.
 
  = 1.1.2 =
 
 * Fix some compatibility issues with php < 5.6.
 
  = 1.1.1 =
 
 * Fix a compatibility issue with php < 5.6.
 
 = 1.1.0 = 
 
 * Custom labels.
 * Refreshed admin UI.
 
 = 1.0.1 =

* Fixed unclosed tag.

= 1.0.0 =

* Public Release.

 == Upgrade Notice ==

= 2.6.1 =
 Update required for evergreen campaigns.