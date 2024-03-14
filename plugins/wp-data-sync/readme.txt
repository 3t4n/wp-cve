=== WP Data Sync ===
Contributors: kevin-brent
Tags: sync data, api feed, data feed, json feed, woocommerce, product feed, csv import, data sync, sync products, google sheets, google forms, wp data sync, sync prices, update prices, sync inventory, update inventory, price feed, inventory feed
Requires at least: 5.0
Tested up to: 6.4.2
Requires PHP: 7.4
Stable tag: /trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sync data from almost any data source to your WordPress or WooCommerce website.

== Description ==

If you’re looking for a plugin to make data management and syncing for your WordPress websites easier, you’ve come to the right place.
Our developer-friendly API supports a variety of data sources, including JSON, XML, CSV, Google Sheets, WordPress and WooCommerce websites. And, you can customize every data field, thanks to action and filter hooks already included in the plugin. You can also manipulate the data with conditional logic, create a single data string from multiple data fields, and much, much, more.

__How does it work?__

The WP Data Sync API is easy to set up and maintain. Map your data source with the data keys from your website and you are ready to go. If you need help mapping your data source, we have support experts available to help.

Import data into your website from almost any data source. Users, posts, pages, products, or other custom post types. Once the data is set in your website, our API will keep the data up to date with the changes in your data feed. This can all be done without writing a single line of code.

__Data Source Types__
&#x2611; JSON
&#x2611; XML
&#x2611; CSV
&#x2611; FTP
&#x2611; Webhook
&#x2611; WordPress
&#x2611; WooCommerce
&#x2611; Google Sheets
&#x2611; Google Forms

__What if I want to know more?__

Here are a variety of links that we’ve found helpful in explaining our plugin and how to get started:

[WP Data Sync](https://wpdatasync.com/?affid=admin "WP Data Sync")
[WP Data Sync Blog](https://wpdatasync.com/blog/?affid=admin "WP Data Sync Blog")
[Developer Documentation - Getting Started](https://wpdatasync.com/documentation-type/getting-started/?affid=admin "Developer Documentation - Getting Started")
[Developer Documentation - Actions](https://wpdatasync.com/documentation-type/actions/?affid=admin "Developer Documentation - Actions")
[Developer Documentation - Filters](https://wpdatasync.com/documentation-type/filters/?affid=admin "Developer Documentation - Filters")

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress Admin.
3. Navigate to WP Admin > Settings > WP Data Sync.
4. For WooCommerce Users - The WooCommerce plugin must be activated.

== Frequently Asked Questions ==

= How does WP Data Sync work? =

We process data in 3 steps:

1. WP Data Sync uses our API to process raw data from your data source.
2. WP Data Sync API syncs the data to your website.
3. WP Data Sync plugin manages updates of the data in the WordPress website.

= How many websites can I sync using the same data? =

WP Data Sync API can sync the same data into as many websites as you like.

= How many requests do I get each month? =

WP Data Sync can perform as many requests as you need. Your account is auto-scaled depending on how many requests are made.

= Is my data private or do other users have access to my data? =

Your data is kept private to you. No one else has access to your data.

= Does WP Data Sync work with Advanced Custom Fields? =

Yes, WP Data Sync can sync most standard ACF post meta fields. If, you are not sure, please ask, we are always happy to help!!

= Can WP Data Sync process WooCommerce products and variations? =

Yes, WP Data Sync can sync WooCommerce product data, variation data, prices, and inventory.

= Is WP Data Sync developer friendly? =

Yes. We have WordPress hooks and filters throughout the plugin to allow for almost any situation. [Developer Documentation](https://wpdatasync.com/documentation/?affid=admin "Developer Documentation")

== Screenshots ==

1. WP Data Sync: Data Flow

== Change Log ==

[Change Log](https://wpdatasync.com/changelog/wp-data-sync-1/?affid=admin "Change Log")

== Upgrade Notice ==

= 2.1.4 =
* Critical Update - Bug fix for fatal error in Product Item Request

= 1.4.5 =
* Critical Update - API endpoint versioning was added.