=== Clariti ===
Contributors: clariti
Tags: blogging, site optimization
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Seamlessly sync your content with Clariti.

== Description ==

The Clariti plugin allows you to easily sync your content to Clariti. Clariti uses the WordPress REST API to gather valuable insights about your site to help you optimize and organize your content. This plugin enables Clariti to sync your content in realtime.

**A Clariti account is required to use this plugin.** You cannot use Clariti with this plugin alone. To learn more about Clariti, head to [clariti.com](https://www.clariti.com).

You should download this plugin **if you have an active Clariti account** and you want to be able to sync your WordPress site changes in realtime with Clariti.

= Optimize & Organize Your Content =

Clariti saves you loads of time. It's a solid replacement for the spreadsheet tracker you've been using. Clariti allows you to sync your content data so that you can plan, organize, and optimize your content.

= Seamless Syncing =

The Clariti Plugin does not alter any of your data. This plugin allows Clariti to sync all new and updated posts in near realtime.

== Installation ==

The Clariti plugin can be installed much like any other WordPress plugin. **A Clariti account is required to use this plugin.**

1. Upload the plugin ZIP archive file via "Plugins" -> "Add New" in the WordPress admin, or extract the files and upload them via FTP.
2. Activate the Clariti plugin through the "Plugins" list in the WordPress admin.
3. Click "Settings" next to the Clariti plugin. Follow the steps below to get your Clariti API key.

To obtain the API key from Clariti, follow these steps:
1. Log in to Clariti.com.
2. Within Clariti, go to Settings.
3. Under Settings > WordPress Plugin, you should see an API Key.
4. Copy the API Key in Clariti and paste it into the WordPress Plugins settings (see above).

If the API key was installed successfully, you should see "Connected" within Clariti Settings in the WordPress Plugin panel.

== Changelog ==

= 1.2.0 (March 5, 2024) =
* Only set an API secret when an API key is added or modified.
* Only send events to Clariti if an API key and secret are set.
* Clear existing API secret when a specific Clariti response is received.

= 1.1.1 (December 15, 2023) =
* Avoid sending multiple update events for a single post's recipe rating updates.
* Only send recipe rating update events for published posts.

= 1.1.0 (November 17, 2023) =
* Notify Clariti when Tasty Recipes or WPRM recipe ratings change.

= 1.0.2 (August 4rd, 2023) =
* Improve plugin version reporting.

= 1.0.1 (July 17th, 2023) =
* Add uninstall routine to remove all plugin data.

= 1.0.0 (July 11th, 2023) =
* Update handling of API calls to and from the mother ship.

= 0.2.0 (April 15th, 2022) =
* Adds necessary integration points for custom post type support.

= 0.1.2 (April 29th, 2021) =
* Attempts non-blocking requests, if possible, with a shorter timeout.

= 0.1.1 (March 30th, 2021) =
* Fixes issue where Clariti settings link appeared for every plugin.

= 0.1.0 (March 8th, 2021) =
* Initial release.
