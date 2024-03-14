=== Pressable Automated Migration ===
Contributors: pressable, blogvault, akshatc
Tags: pressable, migration
Requires at least: 4.0
Tested up to: 6.4
Requires PHP: 5.6.0
Stable tag: 5.48
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The Pressable Automated Migration plugin makes migrating sites to the Pressable platform effortless. Whether you are a developer with tons of experience moving sites or have never moved a site before, this plugin does all the hard work so that you don't have to. Move as many sites as you need, quickly and with minimal interaction.


**FEATURES**

* 1 click migration process simply requires your Pressable SFTP information to move any site from you current host.
* Move your live site to a Pressable staging environment to preview how your site works before you make DNS changes.
* Once you're done previewing the site on our servers, run the migration one last time to synchronize any changes that were made since it was first moved.
* Handles all database adjustments automatically so you don't have to.
* Doesn't require you to have any expensive plugins or hire any migration services.

== Frequently Asked Questions ==
=What information is required to migrate my site?=
Required information includes an email address, your Pressable SFTP credentials, your Pressable site name, and a destination URL.

=Where can I find instructions for using the plugin?=
You can find a complete automated migration guide in our knowledge base by **[clicking here](https://pressable.com/knowledgebase/using-the-pressable-automated-migration-plugin/)**

=Why do you need my email?=
We require an email address to send you updates on the migration process, notify you of any errors that occur during the migration.

== Instructions ==

**Before Starting Your Migration**

Before migrating your site to Pressable, you'll need to have a few things already established:

* An active account with Pressable.
* A site **[deployed on our system](https://pressable.com/knowledgebase/adding-new-site-to-pressable/)** where you will be migrating your site to.
* A site that is ready to be moved to the Pressable platform.

**Obtain Migration Setting Information**

Login to https://my.pressable.com and open up the settings page for the site you would like to migrate. Click on the "Migrate Site" tab and find the settings for the automated migration plugin near the top.

You'll need the settings from here to complete the next steps in the migration process.

**Install the Automated Migration Plugin**

Login to the site that you will be moving to the Pressable platform (the source site) and navigate to Plugins > Add New. In the plugin search box, search for "Pressable Automated Migration". When the plugin listing comes up, click on Install and then Activate.

Navigate to the plugin settings page and insert the details that you obtained from the "Migrate" tab inside of https://my.pressable.com.

**Migrate the Site**

Click on the "Migrate" button and you will see the BlogVault migration page come up. BlogVault will move the site to Pressable servers and provide live updates on the status of the migration. The amount of time a site takes to migrate will vary based on the size of your site.

You will receive an email upon completion of the migration and your migrated site is now visible at the site's staging URL.

**After Migration**

After your migration is complete, please review the site to ensure everything was migrated properly. If necessary, you can remigrate the site one last time using the plugin to synchronize any changes that have been made to your live site since the initial migration.

Once you've reviewed the site for correctness, you are ready to **[send your site live](https://pressable.com/knowledgebase/taking-site-live/)**

== Screenshots ==

== Changelog ==
= 5.48 =
* Upgrading to New UI

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
* Security Improvement: Upgraded Authentication

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

= 4.58 =
* Better Handling of error message from Server on signup
* Added Support for Multi Table Callbacks

= 4.35 =
* Improved scanfiles and filelist api

= 4.31 =
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

= 1.78 =
* First release of Pressable Plugin
