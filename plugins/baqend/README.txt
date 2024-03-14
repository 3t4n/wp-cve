=== Speed Kit ===
Contributors: baqend
Tags: performance, caching, optimization, fast, secure, static website generator, speed, image optimizer
Requires at least: 4.6.0
Tested up to: 6.2.2
Stable tag: 2.0.1
Requires PHP: 7.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Speed Kit makes your WordPress website load instantly with one simple click.

== Description ==
Speed Kit makes your WordPress website load instantly with one simple click.

= Only supported for Plesk users =
This plugin is only supported for users of the [Plesk](https://www.plesk.com/) hosting platform in combination with  the [Plesk Speed Kit extension](https://www.plesk.com/extensions/speed-kit/). All WordPress users who already purchased a Speed Kit license can still use the WordPress plugin as usual. Only new subscriptions via WordPress are not possible anymore.

= WHAT OUR CUSTOMERS LOVE =
Speed Kit powers about 7,000 websites, serving 200 million users monthly, accelerated 3.6 billion euros of annual revenue and scaling 25,000 requests per second. By using Speed Kit, you’ll have the following benefits:
* **User Experience:** Turn page speed into your competitive advantage.
* **SEO Ranking:** Let the speed-based search rank boost your visibility.
* **Time on Site:** Make your users stay longer and click more.
* **Conversion Rate:** Grow e-commerce revenue and funnel metrics.
* **Bounce Rate:** Reduce your marketing costs through lower bounce rates.

= HOW IT WORKS =
After activating the plugin, Speed Kit adds a Service Worker to your WordPress. The Service Worker then reroutes all requests from your WordPress backend to a fast cache which is synchronized with your website. Speed Kit accelerates your WordPress website without any changes to the backend servers. Instead of re-engineering your server, network, and frontend performance, Speed Kit hooks into the traffic and accelerates it from the user side.

= WHAT IT COSTS =
The first 10 days of trial are free. No bank details required.

After the trial period ends you can continue using Speed Kit for 9.99 € per month. If you don’t do anything, Speed Kit simply stops handling your requests and they will be executed by your backend again. Your site remains online all the time without your users noticing anything.

Transparent and straightforward. No strings attached.

== Frequently Asked Questions ==

= How does Speed Kit work? =
After activating the plugin, Speed Kit adds a Service Worker to your WordPress which will be accessible from /speed-kit-sw.js. The Service Worker then reroutes all requests from your WordPress backend to a fast cache which is synchronized with your website.

Speed Kit accelerates your WordPress website without any changes to the backend servers. Instead of re-engineering your server, network, and frontend performance, Speed Kit hooks into the traffic and accelerates it from the user side. Think of Speed Kit as the layer on top of your system, CDNs, and load balancers that guarantees the best possible performance.

With our research-backed caching technology for dynamic data (e.g. for search and personalization), Speed Kit delivers your pages extremely fast. We take care of keeping content in-sync and distributing it to many edge locations. From low-level details such as network protocols to high-level concerns like tailored images, Speed Kit handles everything automatically.

= How can I measure my performance uplift? =
We recommend to run a performance test with our [Page Speed Analyzer](https://test.speed-kit.com/) or with [WebPageTest](https://www.webpagetest.org). Simply enter the URL of your WordPress website after installing Speed Kit.

If you want to measure your performance uplift manually in your browser, [please read this instruction]( https://www.baqend.com/guide/topics/speed-kit/analyzer/).

**Please note:** Speed Kit is built on [Service Workers](https://developers.google.com/web/fundamentals/getting-started/primers/service-workers), a new technology that is currently entering all major web browsers. Unlike [WebPageTest](https://www.webpagetest.org), most of the other popular performance measurement tools are currently not suited to provide accurate performance test results for websites that use Service Workers.

= What is the difference between Speed Kit and traditional content delivery networks (CDN)? =
With Speed Kit, you get all the advantages of a CDN. In fact, Speed Kit uses a CDN under the hood. In addition to a traditional CDN, you get the following benefits:
* Speed Kit caches static content such as stylesheets or images as well as dynamic content such as your HTML file or user comments.
* You decide how often Speed Kit updates your content in the cache.
* Speed Kit uses the browser cache which is located in the users device. Thus, Speed Kit serves data even faster than common CDN caches.

= Why do I need SSL for Speed Kit? =
Speed Kit is built on [Service Workers](https://developers.google.com/web/fundamentals/getting-started/primers/service-workers), a new technology that is currently entering all major web browsers. Since Service Workers can only be enabled on SSL-secured websites, Speed Kit is also only available when SSL is turned on.

= Is WooCommerce supported? =
Yes! Our integration features for WooCommerce come automatically with this plugin.

= When is Speed Kit active? =
Speed Kit is caching content everywhere on your website except in the following cases:
* You are logged in
* You are using WooCommerce and have items in the shopping basket or on the wishlist
* Cache update failed multiple times

= Why can I not install or update the plugin? =
Probably the write permissions are not set correctly. The Service Worker needs to be stored in the root directory as /speed-kit-sw.js. You need write permissions to the root directory to let the plugin do so.

= How can I control Speed Kit? =
You can edit your white- and blacklists that control which pages and resources are handled by Speed Kit. In addition, you can turn off Speed Kit via “Deactivate Now” in your Speed Kit “Overview”.

If you have any questions, please contact us! We are happy to help you with your plugin setup.

= Do I have to insert something into my HTML? =
No, this plugin provides the full integration with your WordPress, including HTML.

= Do I have to change my DNS settings? =
No, your website will still be served from your IP. Traffic will be redirected invisibly to your users.

== Installation ==
1. Log into your WordPress website.
2. On the left menu, hover over “Plugins” and click on “Add New”.
3. In the “Search Plugins” field, type in “Speed Kit” and press Enter.
4. You will see a list of search results which should include the Speed Kit plugin. Click on the “Install Now” button to install the plugin.
5. After installing the plugin you will be prompted to activate it. Click on the “Activate Plugin” link.
6. The Speed Kit plugin will now open and guide you through the setup.

== Screenshots ==

1. The Speed Kit landingpage. You can see your used traffic and quickly revalidate your website.
2. The Speed Kit settings tab. Here you can set a white- and blacklist to configure which pages should be handled by Speed Kit.
3. In the Speed Kit settings tab, you can also find settings for automatic Image Optimization.
4. In case you get lost, the Speed Kit help contains information on how to use this plugin.

== Changelog ==

= v2.0.1 (2023-7-11) =


Features

* Update plugin description

= v2.0.0 (2023-7-11) =


Features

* Make the plugin only available for Plesk users
* Remove sign up option

= v1.17.20 (2023-1-24) =


Features

* Add PHP 8 support

= v1.17.19 (2023-1-16) =


Bug Fixes

* Fix typo in plugin description

= v1.17.18 (2023-1-16) =


Features

* Reduce trial duration to 10 days
* Adapt plugin description

= v1.17.17 (2022-6-30) =


Bug Fixes

* Change internal call to receive available apps

= v1.17.16 (2022-6-28) =


Bug Fixes

*  Optimize revalidation filter if too many URLs

= v1.17.15 (2022-6-28) =


Features

* Update dependencies to PHP 7.3.0
* Test plugin up to WordPress v. 7.4.0

Bug Fixes

* Fix null pointer in stats overview

= v1.17.14 (2021-5-31) =


Features

* Adapt revalidation request structure



= v1.17.13 (2021-1-18) =


Features

* Improve performance of url revalidation

= v1.17.12 (2021-1-6) =


Features

* Improve performance of url revalidation

Bug Fixes

* Fix null pointer on stats view

= v1.17.11 (2020-6-15) =


Features

* Update plugin description

= v1.17.10 (2020-4-28) =


Features

* Minor code refactoring

= v1.17.9 (2020-4-20) =


Features

* Consider query params for revalidation

= v1.17.8 (2020-3-30) =


Features

* Collapse revalidation of widget updates

= v1.17.7 (2020-3-25) =


Features

* Expand tracking for widget updates

= v1.17.6 (2020-3-23) =


Features

* Add GIF files with parameters to blacklist

= v1.17.5 (2020-3-19) =


Features

* Automatically disable Speed Kit on cache revalidation error

Bug Fixes

* Remove cache invalidation of third party caching plugins

= v1.17.4 (2020-3-11) =


Features

* Improve cache revalidation



= v1.17.3 (2020-2-28) =


Bug Fixes

* Send widget revalidation only if settings have changed

= v1.17.2 (2020-2-28) =


Bug Fixes

* Fix schedule of HTML revalidation jobs

= v1.17.1 (2020-2-21) =


Bug Fixes

* Fix snippet src

= v1.17.0 (2020-2-21) =


Features

* Serve specific installation script for admin user

* Enable login with user token for non admin user

* Add deactivation warning

Bug Fixes

* Fix installation for managed hosting (e.g. IONOS)

* Scope automatic relavidation jobs to site URL

= v1.16.3 (2019-12-19) =


Bug Fixes

* Fix legacy source path

= v1.16.2 (2019-12-19) =


Bug Fixes

* Fix Speed Kit update info

= v1.16.1 (2019-12-18) =


Bug Fixes

* Fix directory for Speed Kit updates

= v1.16.0 (2019-12-18) =


Features

* Blacklist PDF files on default
* Add userAgent detection as new option
* Remove option to enable metrics

Bug Fixes

* Do not blacklist empty array
* Scope JavaScript to plugin pages

= v1.15.0 (2019-11-12) =


Features

Expand default configuration

Bug Fixes

Remove source maps

= v1.14.0 (2019-11-12) =


Features

Expand default configuration

Bug Fixes

Remove source maps

= v1.13.1 (2019-8-27) =


Bug Fixes

* Fix Woocommerce blacklist cookie

= v1.13.0 (2019-7-11) =


Features

Use inline script to install Speed Kit
Bug Fixes

Fix cache revalidation of homepage

= v1.12.8 (2019-5-3) =


Bug Fixes

* Remove upselling for Plesk users
* Fix performance issues

= v1.12.7 (2019-4-24) =


Features

* Use long life token to verify user permissions

Bug Fixes

* Remove warning if Speed Kit has been updated successfully

= v1.12.6 (2019-4-12) =


Bug Fixes

* Fix WooCommerce URL blacklist

= v1.12.5 (2019-2-14) =


Improvements

* Only log critical errors in production mode
* Add support for Metrics Snippet


= v1.12.4 (2019-1-30) =


Features

* Support new Speed Kit Trial

= v1.12.3 (2019-1-21) =


Bug Fixes

* Fix plugin issues with PHP versions < 7.0

= v1.12.2 (2019-1-4) =


Features

* Add custom configuration option
* Analyse PHP metadata

= v1.12.1 (2018-11-29) =


Bug Fixes

* Fix broken release

= v1.12.0 (2018-11-29) =


Features

* Let user login with token link
* Add setting to strip query parameters when caching with Speed Kit
* Test plugin with WordPress 5.0.0-beta1 and PHP 7.3
* Support non-SSL for localhost

Bug Fixes

* Fix regular expressions in Speed Kit configuration
* Fix login with token user rights
* Fix "Could not update Speed Kit" message


= v1.11.3 (2018-10-8) =


Bug Fixes

* Fix support for Simple Cache
* Fix support for WP Fastest Cache

= v1.11.2 (2018-10-1) =


Bug Fixes

* Fix error while logging in to new apps

= v1.11.1 (2018-9-25) =


Bug Fixes

* Fix redirect to settings page if not a Baqend admin (thanks to @glueckpress)
* Fix Service Worker scope for unequal hosts of home_url & site_url
* Fix usage of non-existing password attribute

= v1.11.0 (2018-9-21) =


Features

* Add link to login with token on registration
* Rename "Getting Started" to "Overview"

Bug Fixes

* Improve security of connect call
* Improve stats for apps with exceeded limit

= v1.10.5 (2018-8-24) =


Bug Fixes

* Hide dashboard widget for Plesk users

= v1.10.4 (2018-8-20) =


Bug Fixes

* Fix bug with undefined page in `is_on_baqend_admin`
* Fix redirect bug for Plesk users after activating the plugin
* Limit cookie blacklisting to documents
* Use URLs instead of prefixes for revalidation requests
* Ensure triggered revalidations don't contain empty prefixes

= v1.10.3 (2018-8-6) =


Bug Fixes

* Fix widgets being unable to save
* Fix settings link in plugin overview for Plesk users

= v1.10.2 (2018-8-3) =


Bug Fixes

* Fix menu for Plesk users

= v1.10.1 (2018-8-2) =


Bug Fixes

* Fix release version

= v1.10.0 (2018-8-2) =


Features

* Add new tab for advanced settings
* Revalidate page for profile and taxonomy changes
* Change behavior for Plesk users

Bug Fixes

* Fix division by zero bug
* Trigger cache invalidation after saving settings
* Fix showing HTTPS note when on localhost
* Fix division by zero in stats
* Fix broken "Upgrade Now" button

= v1.9.0 (2018-7-12) =


Features

* Redo the getting started page
* Show performance metrics on the getting started page
* Redo Speed Kit dashboard widget
* Add “Upgrade” tab
* Add examples for whitelist and blacklist

Bug Fixes

* Fix caching plugins not revalidating Speed Kit
* Fix issues with Strato’s ServerSide Security
* Fix plugin not working for multiple WordPress instances with same app
* Fix Speed Kit installed in multiple versions after update
* Fix Speed Kit scope for differing protocols in home and site URL

= v1.8.1 (2018-6-28) =


Bug Fixes

* Fix snippet and dynamic fetcher missing after update

= v1.8.0 (2018-6-26) =


Features

* Add support for [**Image Optimization**](https://www.baqend.com/guide/topics/speed-kit/image-optimization/)
* Add a component to enable pages by click
* Add support for [**Dynamic Fetcher**](https://www.baqend.com/speed-kit/latest/#DynamicBlockConfig)

Bug Fixes

* Fix and refactor blacklisting home URL when using WooCommerce
* Fix service worker request path
* Fix update link in plugin outdated note

Changes

* Add a note that one needs to be logged out for Speed Kit to work

= v1.7.2 (2018-6-8) =


Bug Fixes

* Revert the speed-kit-sw.js back to the root folder

= v1.7.1 (2018-6-5) =


Changes

* Add home URL, wp-content, and wp-includes/js to whitelist
* Add wp-json and login to blacklist
* Send Service Worker with PHP under "/speed-kit-sw.js"

Removes

* Remove site URL from whitelist

= v1.7.0 (2018-5-29) =


Changes

* Rename plugin from “Baqend” to “Speed Kit”
* Rename “Speed Kit” tab to “Settings”
* Rename menu item from “Baqend” to “Speed Kit”
* Update help text

Removes

* Remove Hosting feature
* Remove manual installation instructions

Features

* Update dependencies
* Add Measure Performance widget on getting started
* Update plugin description
* Add FAQs to the README
* Remove hosting settings
* Add single mail registration
* Test up to WordPress 4.9.6

Bug Fixes

* Fix showing wrong username in account tab
* Wrap WooCommerce blacklist pathnames in regex
* Prevent the front page from being blacklisted by WooCommerce

= v1.6.3 (2018-5-16) =


Bug Fixes

* Add terms of service to registration form
* Remove automatic registration on first start

= v1.6.2 (2018-5-8) =


Bug Fixes

* Fix layout of account form
* Fix log out button bug

= v1.6.1 (2018-5-2) =


Bug Fixes

* Fix bug while updating a widget

= v1.6.0 (2018-4-24) =


Features

* Make revalidation more prominent
* Add dashboard widget
* Change design of Speed Kit config section
* Add update widgets action to trigger revalidation
* Add additional actions to trigger revalidation
* Extend default whitelist with common CDNs
* Add new cookies to blacklist

Bug Fixes

* Be more robust in finding the Speed Kit config entry

= v1.5.1 (2018-4-3) =


Features

* Display error messages if Speed Kit update fail
* Increase Speed Kit update frequency

Bug Fixes

* Fix pathname generation for WooCommerce
* Fix Speed Kit file updates
* Hide hosting on Getting Started page

= v1.5.0 (2018-3-21) =


Features

* Add French, German (Switzerland), and German (formal) translation
* Automatically enable Speed Kit on first activation
* Automatically login user on first activation
* Redirect to “Getting Started” if not logged in
* Redirect to “Getting Started” on plugin activation
* Add warning if user has not enabled SSL
* Add hint to the help in Speed Kit and Hosting tab
* Hide hosting help if hosting is disabled

Bug Fixes

* Fix plugin URI
* Fix typos and URLs in texts
* Update help text
* Fix building of admin URLs
* Fix PHP compatibility check

= v1.4.5 (2018-3-16) =


Improvements

* Improve hosting deployment speed
* Reduce plugin size by 70 kB

Bug Fixes

* Fix filenames not found by translate.wordpress.org

= v1.4.4 (2018-3-12) =


Features

* Translate into formal German (Switzerland)
* Make German (Germany) translation informal
* Update Baqend SDK

Bug Fixes

* Fix calculated scope URL
* Fix wrong example content in Speed Kit tab

= v1.4.3 (2018-3-12) =


Features

* Calculate the Service Worker scope by its URL

= v1.4.2 (2018-3-9) =


Bug Fixes

* Fix paths in subdirectory installations of WordPress

= v1.4.1 (2018-3-9) =


Features

* Reduce built ZIP size by 200 kB
* Improve logging with Monolog
* Improve bootstrapping performance

Bug Fixes

* Fix check whether login is broken
* Fix bug in check_plugin_update hook

= v1.4.0 (2018-3-7) =


Features

* Add API token support for revalidation
* Revalidate HTML after updating the plugin
* Add Speed Kit update cron hook

Bug Fixes

* Fix token revalidation code

= v1.3.5 (2018-2-19) =


Bug Fixes

* Fix release process

= v1.3.4 (2018-2-19) =


Bug Fixes

* Fix check for connection in frontend
* Update release process

= v1.3.3 (2018-2-19) =


Bug Fixes

* Fix resolving dependencies with plugin in subdirectory

= v1.3.2 (2018-2-12) =


Bug Fixes

* Fix bug with "maxStaleness" in config
* Fix dependencies for PHP 5.5.9

= v1.3.1 (2018-2-8) =


Bug Fixes

* Register new users with Speed Kit sign-up type
* Move composer.json up and remove Dockerfile

= v1.3.0 (2018-2-7) =


Bug Fixes

* Fix IDs in help view

Features

* Check the PHP version before activating
* Automatically retrieve fetch origin interval
* Tested up to WordPress 4.9.4

= v1.2.0 (2018-2-6) =


Features

* Tested up to WordPress 4.9.3
* Allow comments using semicolon in whitelist, blacklist, and cookie

= v1.1.5 (2018-2-1) =


Bug Fixes

* Fix PHP 7.0 Polyfill

= v1.1.4 (2018-2-1) =


Improvements

* Update Baqend PHP SDK

= v1.1.3 (2018-2-1) =


Bug Fixes

* Fix release cycle for README and CHANGELOG
* Add script to convert Markdown to WordPress README

= v1.1.2 (2018-2-1) =


Bug Fixes

* Tested up to WordPress 4.9.2

= v1.1.0 (2018-1-23) =


Bug Fixes

* Remove third-party plugin update checker
* Improve packed ZIP file
* Update translations
* Update meta properties

Features

* Add fetch_origin_interval option to Speed Kit
* Improve README

= v1.0.16 (2017-11-23) =


Bug Fixes

* Exclude some generated files from release ZIP

= v1.0.15 (2017-11-23) =


Bug Fixes

* Fix ZIP dist dir in release

= v1.0.14 (2017-11-23) =


Bug Fixes

* Fix ZIP command in release

= v1.0.13 (2017-11-23) =


Bug Fixes

* Fix pushd command in release

= v1.0.12 (2017-11-23) =


Features

* Improve released ZIP archives
* Update the README

= v1.0.11 (2017-11-16) =


Bug Fixes

* Fix bug with wish lists in WooCommerce
* Fix bug which manipulates the options
* Fix bug in SimplyStaticOptionsAdapter
* Immunize against all exceptions in revalidation trigger
* Do safely logout on exception

Features

* Add helpful heading in hosting
* Support new snippet

= v1.0.10 (2017-10-16) =


Bug Fixes

* Revalidate user if API access fails

= v1.0.9 (2017-10-10) =


Features

* Advanced WooCommerce handling: automatically parse blacklist from WooCommerce shops
* Update Speed Kit snippet

= v1.0.8 (2017-10-5) =


Features

* Add hosting tab checkbox

= v1.0.7 (2017-9-19) =


Bug Fixes

* Change ServiceWorker path to "/sw.js"

Features

* Show note when plugin is outdated

= v1.0.6 (2017-9-18) =


Bug Fixes

* Update only to version 1.X.X
* Update Composer hash
* Remove Hosting from help
* Fix content types, add track type
* Refactor config builder
* Fix preload ServiceWorker destination
* Improve error handling in upload_archive

Improvements

* Improve labels of Speed Kit options
* Remove Hosting from view
* Replace content types with checkboxes
* Add bypass cache by cookie configuration

= v1.0.5 (2017-9-7) =


Bug Fixes

* Fix default blacklist
* Fix empty lines in lists
* Fix line breaks in list views
* Fix ServiceWorker URL

Improvements

* Apply new ServiceWorker rules API
* Update the snippet

= v1.0.4 (2017-9-5) =


Bug Fixes

* Fix configuration parameters
* Map dev ServiceWorker from host to client
* Fix getting started design

Features

* Add max staleness and app domain settings

= v1.0.3 (2017-8-17) =


Bug Fixes

* Stop calling identity on every request
* Fix line breaks when showing settings
* Revalidate Speed Kit when its settings are changed
* Stop sending Service Worker with PHP

Features

* Add an example for white and blacklist in Speed Kit settings
* Add statistics to getting started

= v1.0.2 (2017-8-17) =


Bug Fixes

* Optimize frontend code
* Do not send Bloom filter preload anymore
* Send rel=serviceworker header
* Deactivate ServiceWorker manually in Admin
* Send revalidation asynchronously

Other

* Update README.txt metadata

= v1.0.1-alpha (2017-8-16) =


First Release

