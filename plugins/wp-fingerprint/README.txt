=== WP Fingerprint ===
Contributors: tnash, kayleighthorpe, danfoster
Tags: security, plugins, checksums
Requires at least: 4.9
Tested up to: 6.4.1
Requires PHP: 5.6
License: GPLv3
Stable tag: 2.1.2
== Description ==
WP Fingerprint adds an additional layer of security to your WordPress website, working to check your plugins for signs of hack or exploit. WP Fingerprint works by collecting checksums of your plugins and comparing it with the checksums collected by WP Fingerprint. If the plugin detects any abnormalities it will let you know so you can take immediate action.
This plugin transmits and stores checksums on WP Fingerprint servers(all hosted in EU and run by 34SP.com) & WordPress.org to work for details see https://wpfingerprint.com/how-it-works/ for the data we collect and store.
== Installation ==
As normal click activate to activate the plugin and go make a cup of tea while it works away in the background. Allow an hour after installation for WPFingerprint to complete its first set of checks.
== Frequently Asked Questions ==
How does WPFingerprint work? When files on your website change, WP Fingerprint uses checksums to verify if these files are authentic. The WP Fingerprint plugin on your WordPress website runs through each of your plugins and creates a SHA-1 checksum for each file within any plugin folder it finds. It also compiles some other basic information about the plugins such as version number it sends this information back to WP Fingerprint servers to validate the checksum.

Why should I use WPFingerprint?
WordPress is the most popular open source CMS on the planet. By most estimations a third of the entire web runs WordPress. If you’re a hacker, that’s a huge target to aim for. As such, WordPress security should be at the very top of your considerations for your website.

Hacked WordPress sites are often used for a wide variety of activities you don’t want to be linked to: sending large volumes of spam, SEO spam links, propagating viruses and more. All of these can have negative impacts on your website, from losing search engine rankings to dissuading your site visitors from checking out your site.

One of the main vectors of attack against WordPress are plugins. WP Fingerprint works to identify if the plugins on your website have been exploited. Every time a plugin or core file changes, our scanner will verify the file for you.

Will it work with Premium Plugins?
Yes, While plugins from wordpress.org are checked directly from source. WPFingerprint crowdsources the correct checksums for plugins not found on wordpress.org. It will then return a percentage of how often it's seen changed files.

== Changelog ==
2.1.2 - Bumped PHP version to PHP5.6 - 8th May 2019
2.1 - Remove notice in admin section, refactored the primary checker, added ability to diff files if source allows, added WP-CLI commands for report and Diff - 16th October 2018
2.0.4 - Show the source with a human friendly name, clear down logs so they are not showing spurious data
2.0.3 - Added a "what does this mean notification", created clear logs wp-cli command  - 26th September 2018
2.0.2 - Fixed issue where incorrectly announcing that checksums did not match - 24th September 2018
2.0.1 - Fixed bug where Notification counter wasn't showing
2.0.0 - Rewritten WP Fingerprint see blog post for full details - 19th September 2018
1.0.1 - 6th April 2018
Bug fix - Removed notice, when plugin is not in the results array
Bug fix - Temporarily disabling the "we are waiting go have coffee notice" this causes confusion when transient remains.
Bug fix - wp.org naming was different to beta wpfingerprint.php renamed wp-fingerprint.php
1.0 - WordPress.org initial release
0.7 - Switched to MD5 to support shared hosts
0.6 - Internal Preview release
