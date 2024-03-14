=== Cloudways WordPress Migrator ===
Contributors: blogvault, akshatc
Tags: Cloudways, migration
Requires at least: 4.0
Tested up to: 6.4
Requires PHP: 5.4.0
Stable tag: 5.25
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The easiest way to migrate your site to Cloudways

== Description ==

The Cloudways WordPress Migrator plugin automates WordPress migration(s) from any other hosting provider to Cloudways, the World’s Easiest Cloud Hosting Platform. The only requirement from your end is to provide the Cloudways SFTP details to the Plugin. Sit back and relax as the Migrator does the tedious work for you!

This tool saves valuable time for all kinds of website owners.

= Agencies =

•	You don’t need in-house technical experts to manage the complicated procedure of migrating your websites to the Cloudways Cloud Platform.
•	Your website themes and plugins are preserved with utmost care.

= Developers =

•	Focus your efforts on time-critical projects, instead of spending hours on manual migrations.
•	Database values are updated automatically, providing seamless transition to the Cloudways Platform.

= Ecommerce Merchants =

•	All product pages, themes, and customer records are moved to Cloudways easily.
•	Minimum downtime to ensure your sales are affected as little as possible. There’s no faster way to move an ecommerce site!

= Small and Medium Enterprises =

•	The Cloudways WordPress Migrator is best for SMEs who don’t have the resources to employ specialists to handle their website migration.
•	Search Engine Rank Positions (SERP) won’t fall. Your SEO links stays intact. So, there will be no adverse effects on SEO.
•	It’s as easy as 1-2-3! The Cloudways Platform specializes in serving SMEs, as does this plugin.

= Bloggers =

•	Your widgets and customizations stay in one piece.
•	All your ad codes, referral codes, and media files integrated into your posts will be transferred without any breakage.

= * Please Note * =

This plugin will only migrate your site to [Cloudways](https://www.cloudways.com/en/). This will not migrate you to any other host.

== Installation ==

= Method 1 =

1- Download the plugin and extract the zip file. 
2- Upload bv-cloudways-automated-migration plugin to your /wp-content/plugins/ directory.
3- Activate the plugin through WordPress admin "Plugin" tab. 

= Method 2 =

1- Navigate to Plugins -> Add New and search for Cloudways migrator.
2- Install and Activate the plugin to use it.

= Method 3 =

If you are comfortable using WP-CLI (WordPress Commmand Line), then you can easily install the plugin by running this command in SSH terminal. 

wp plugin install bv-cloudways-automated-migration

== Frequently Asked Questions ==

=Why do you need my email?=
We require an email address to send you updates on the migration process, notify you of any errors that occur during the migration.

== Changelog ==
= 5.25 =
* Bug fix get_admin_url

= 5.24 =
* SHA256 Support
* Stream Improvements

= 5.22 =
* Code Improvements
* Reduced Memory Footprint

= 5.16 =
* Bug Fixes

= 5.15 =
* Upgraded Authentication

= 5.05 =
* Code Improvements for PHP 8.2 compatibility
* Site Health BugFix

= 4.97 =
* Code Improvements
* Sync Improvements
* Code Cleanup
* Bug Fixes

= 4.78 =
* Better handling for plugin, theme infos
* Sync Improvements

= 4.69 =
* Improved network call efficiency for site info callbacks.

= 4.68 =
* Removing use of constants for arrays for PHP 5.4 support.
* Post type fetch improvement.

= 4.65 =
* Robust handling of requests params.
* Callback wing versioning.

= 4.62 =
* MultiTable Sync in single callback functionality added.
* Improved host info
* Fixed services data fetch bug
* Fixed account listing bug in wp-admin

= 4.54 =
* Upgrading to new UI
* Added Support for Multi Table Callbacks

= 4.35 =
* Improved scanfiles and filelist api

= 4.28 =
* Fetching Mysql Version
* Robust data fetch APIs
* Core plugin changes
* Sanitizing incoming params

= 3.4 =
* Plugin branding fixes

= 3.2 =
* Updating account authentication struture

= 3.1 =
* Adding params validation
* Adding support for custom user tables

= 2.1 =
* Restructuring classes

= 1.88 =
* Callback improvements

= 1.86 =
* Updating tested upto 5.1

= 1.84 =
* Disable form on submit

= 1.82 =
* Updating tested upto 5.0

= 1.77 =
* Adding function_exists for getmyuid and get_current_user functions 

= 1.76 =
* Removing create_funtion for PHP 7.2 compatibility

= 1.72 =
* Adding Misc Callback

= 1.71 =
* Adding logout functionality in the plugin

= 1.69 =
* Adding support for chunked base64 encoding

= 1.68 =
* Updating upload rows

= 1.66 =
* Updating TOS and privacy policies

= 1.64 =
* Bug fixes for lp and fw

= 1.62 =
* SSL support in plugin for API calls
* Adding support for plugin branding

= 1.44 =
* Removed bv_manage_site
* Updated asym key

= 1.41 =
* Better integrity checking
* Woo Commerce Dynamic sync support

= 1.40 =
* Manage sites straight from BlogVault dashboard

= 1.31 =
* Changing dynamic backups to be pull-based

= 1.30 =
* Using dbsig based authentication

= 1.22 =
* Adding support for GLOB based directory listings

= 1.21 =
* Adding support for PHP 5 style constructors

= 1.20 =
* Adding DB Signature and Server Signature to uniquely identify a site
* Adding the stats api to the WordPress Backup plugin.
* Sending tablename/rcount as part of the callback
* Adding app folder as a field

= 1.17 =
* First release of Cloudways Migration Plugin
