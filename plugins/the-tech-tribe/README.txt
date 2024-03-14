=== The Tribal Plugin ===
Contributors: nigelmoore1, allan.casilum
Donate link: thetechtribe.com
Tags: techtribe, content, syndication
Requires at least: 5.0
Tested up to: 6.4.2
Stable tag: 1.3.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Tech Tribe plugin allows Tech Tribe members to automatically post Blog content to their Wordpress website.

== Description ==

The Tech Tribe plugin allows members of the Tech Tribe to automatically post blog content to their website from the Monthly Marketing Packs included in their Membership. 

It allows members to:

* Set what Author they want as Default on all the Posts
* Decide between Automatic posting or Manual posting in case they want to check first

You can find out more about The Tech Tribe at: https://thetechtribe.com/

== Installation ==

Simply follow these steps:

1. Install the Plugin from the Wordpress Marketplace
2. Activate the Plugin through the 'Plugins' menu in Wordpress
3. Go to the main page of the Plugin and paste in your API Key to Verify
(your API Key can be found at https://portal.thetechtribe.com/my-tribe-account)
4. Once it has been activated, go to the Settings tab and select your Default Author and whether you want your posts to be published Automatically or Manually
5. If you want to kick off a Manual Import, click on the Import Tab and click the "START MANUAL IMPORT" button 

== Frequently Asked Questions ==

= What is the Tech Tribe? =

The Tech Tribe is a program & community for the owners of MSP & IT Support Businesses chock full of resources, templates & workshops to help MSPs & ITSPs better run & grow their business. 

You can find out more at https://thetechtribe.com/

= Where can I get Help or Support? =

Simply shoot an email to help@thetechtribe.com if you ever need any assistance. 

If you're having an error, make sure to include screenshots and any details that might help us work out what is going wrong. 

= What does this plugin do? =

One of the benefits our Tech Tribe members get every month is freshly written blog posts that they can use in their Marketing.

This plugin automatically pulls down those Blog Posts and publishes them on their site so they don't have to lift a finger in the publishing process. 

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Screenshots are stored in the /assets directory.

== Changelog ==

= 1.3.1 - January 22, 2024 =
* Update bootstrap library to version 5.3.2
* Maintenance Update

= 1.3.0 - January 08, 2024 =
* Fix warning popup message
* Maintenance update
* Test in WordPress version 6.4.2

= 1.2.2 - October 11, 2023 =
* Maintenance update.
* Test in WordPress version 6.3.1
* Improve logs, remove unnecessary log.

= 1.2.1 - September 02, 2022 =
* Improve the logs.
* Change log date to have UTC and local time.

= 1.2.0 - August 08, 2022 =
* Improve logs for further troubleshoot.
* Test on WordPress version 6.0.1

= 1.1.1 - May 13, 2022 =
* Update Error text to show not just Error but to show general error if the return error is undocumented.

= 1.1.0 - March 03, 2022 =
* Fix the js and css to include only on the The Tech Tribe page only, to avoid conflict on WordPress Customize tool and other potential plugin conflicts.

= 1.0.0 - November 11, 2021 = 
* Going from Private BETA to First Public Version
* Changed the automatic Rel-Canonical & Link-Back Attribution Line
* Updated Error Log Filename
* More features to come :)

= 0.11.0 - Beta =
* Improve status notification.
* Seperate API Key to its own tab.
* Remove extra carriage return in the content.
* Add log for debug purposes.
* Improve the data sanitization both output and input.
* Update the 3rd party assets.
* Improve the importing posts with categories, accept multiple categories.

= 0.10.0 - Beta =
* Fix inline image in the content, wherein the path is not changed according to the wordpress media settings.

= 0.9.1 - Beta =
* Update notification message when no blogs to import.
* Added progress status when importing blogs.
* Improve activating and deactivating cron jobs, set cron jobs only if the status is active and remove if not.

= 0.8.1 - Beta =
* Add end line attribute on each of the end of content.

= 0.8.0 - Beta =
* Fix the next schedule display.
* Update the text label in date Import status tab.
* Fix the settings update, if publish post and author only changed then update that changes only.

= 0.5.0 - Beta =
* Update status verbage when success.

= 0.4.0 - Beta =
* Add next schedule cron to be display.

= 0.3.0 - Beta =
* Updated the UI to make it just two tabs, settings and import manual.

= 0.2.0 - Beta =
* Created the UI page settings.

= 0.1.0 - Beta =
* Bootstrap Build of the plugin.

== Upgrade Notice ==

= 0.1.0 - Beta =
* Beta version.