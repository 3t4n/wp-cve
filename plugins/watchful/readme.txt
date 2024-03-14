=== Remote Website Management Plugin by Watchful ===
Contributors: watchful
Tags: manage multiple sites, Wordpress Dashboard, backup, WordPress manager, WordPress management, site management, watchful, remote administration, multiple wordpress
Requires at least: 4.4
Tested up to: 6.4.2
Requires PHP: 5.6
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A web developers toolbox for remotely managing and monitoring tens, hundreds, or thousands of WordPress websites at once.

== Description ==

Watchful is a web developers toolbox for remotely managing and monitoring multiple WordPress websites. Simply add all your production and staging sites into the [Watchful Dashboard](https://watchful.net "Manage multiple WordPress websites") and use our tools to monitor uptime, backups, updates to WordPress and core and plugins, and more. You'll be amazed at how much time and money you save managing your WordPress sites with Watchful.

= Top Features at-a-glance =
* Backup scheduler and monitor
* Powerful plugin updates, including auto updates
* Uptime monitor & SSL certificate checker
* Best practice, malware, and filesystem scanners
* Early warning system for intruder detection
* SEO audit tool
* Plugin tracker to monitor when plugins have been added and removed
* Google Analytics integration
* Website reporting for your clients
* Customizable dashboard

Our [Free Trial](https://app.watchful.net/free-trial.html) will let you test up to 5 sites at once to see how much. You won't believe how much time you'll save!

== Customizable Dashboard ==
With your entire portfolio of websites in a single dashboard, you can easily review and prioritize website maintenance tasks. Sort by core version, number of pending updates, backup date, tags and more to optimize Watchful to your workflow.

== Powerful Update Technology ==
The easiest way to protect your websites is to keep WordPress and all of its plugins up-to-date. With Watchful, you can apply updates across your entire portfolio in just one click. Supported updates include WordPress, all of the plugins in the WordPress repo, as well as a growing list of the most popular paid plugins. You can even manage your commercial license keys and remotely register new domains with commercial WordPress software vendors.

== Best Practice Scanner ==
The Site Audit is a best practice scanner that checks your site for security best-practices, hacked or modified core files, and malware. If any problems are detected, advice and/or simple tools are provided to help fix any issues.

== SEO Audit ==
Be sure that the technical aspects of your website are properly configured with the SEO Audit. The audit detects problems such as broken links, missing sitemaps, and content relevance — and recommends fixes — to ensure that your site is performing at it's very best when indexed by search engines.

== Plugin & Extension Tracker ==
The Plugin & Extension Tracker allows you to easily browse all of the WordPress plugins added to your website and logs any changes for unexpected modifications. By correlating the dates of plugin installation/upgrade to any bugs that arise on your sites, this logging feature greatly improves the troubleshooting process. The tracker also integrates with our system-wide search tools. This makes it fast and easy to locate all of your sites with specific plugins — and even a specific version of a plugin — so that maintenance tasks such as addressing zero-day security issues can be applied quickly and efficiently to only the sites that need them.

== Uptime Monitoring ==
The Uptime Monitor checks your sites every minute of every day to make sure it is online. If your site cannot be reached, a notification is sent and the problem recorded. The monitor displays the last 10 uptime/downtime events and calculates the percentage uptime for the last day, 7 days, 30 days, and throughout the lifetime of your site. This information can be included in your white-label reports (see below).

== Google Analytics integration =
The Google Analytics integration allows you to instantly get an overview of your website traffic, visitors, most viewed pages, top referral sources, and most commonly searched for keywords. This information can be included in your white-label reports (see below).

== White-label Reports ==
Our white-label reporting tool makes it easy to generate reports for your clients. Reports can be generated on a per-site basis, or based on tags, giving you a lot of flexibility in the report contents. Each report contains data on website uptime, Google Analytics, and logs for the indicated time period showing software updates and other site activity. These great looking reports can be sent to your client(s) to showcase the value of your services.

== There’s More! ==
Watchful has lots more features and tools and is adding more functionality regularly. Check out our website and [try the free demo](https://app.watchful.net/free-trial.html).

== Installation ==

1. Sign up for a free account at [Watchful.net](https://watchful.net). You will be automatically logged into the Watchful dashboard.
2. Install and activate the plugin through the 'Plugins' menu in WordPress.
3. On the post-install screen, click the button called 'Add to Watchful'.

Full installation details can be [found on our website](https://watchful.net/faqs/installation/installing-the-watchful-client).

== Frequently Asked Questions ==

= Is Watchful compatible with my web host? =

Watchful works well with most hosting companies including managed hosts and digital experience providers. If you have trouble connecting a site, please click the 'Support' link at the top-right of the Watchful Dashboard.

= Is Watchful safe? =

Watchful connects to your website securely to transmit information and perform maintenance tasks. It is important to keep your Watchful account secure, do ewe strongly recommend using [2-factor authentication](https://watchful.net/faqs/security/enabling-2-factor-authentication)) with Watchful.

= Is Watchful free? =

The Forever Free account at Watchful will allow you to manage and perform maintenance on 1 website. Paid plans increase this limit.

= How long has Watchful been around? =

Watchful launched in 2012 and began supporting WordPress in 2017.

== Changelog ==
== v1.7.2 ==
* Add audit test for WordPress debug log

== v1.7.1 ==
* Enable maintenance mode when updating a plugin

== v1.7.0 ==
* Show last backup date according to the Watchful backup profile selected

== v1.6.9 ==
* Show Watchful authentication errors on the backend authentication page only

== v1.6.8 ==
* Fix wrong detection of mySQL version
* Fix validation parameter on activate plugin endpoint

== v1.6.7 ==
* Fix notices

== v1.6.6 ==
* PHP 8 compatibility fixes

== v1.6.5 ==
* Update "tested up to" version

== v1.6.4 ==
* Update "tested up to" version

== v1.6.3 ==
* Remove advice from the vulnerability scanner that is not relevant anymore

== v1.6.2 ==
* Automatically remove the Watchful plugin fixer used to fix an issue with the updater system during the update process

== v1.6.1 ==
* Minor bug fixes

== v1.6.0 ==
* Throw an exception when updating a plugin which requires a PHP version higher than the current PHP version

== v1.5.9 ==
* Fix Audit test

== v1.5.8 ==
* Fix integration with XCloner

== v1.5.7 ==
* Fix a bug open_basedir restriction will cause the plugin to fail executing the vulnerability scanner.

== v1.5.6 ==
* Add audit test for administrators weak password

== v1.5.5 ==
* Fix missing list of weak passwords to compare against the database password during a security scan.

== v1.5.4 ==
* Fix warning when using PHP >=8.0

== v1.5.3 ==
* Fix connection error when AI1WM is disabled

== v1.5.2 ==
* Fix require of XCloner files

== v1.5.1 ==
* Fix an issue when updating the plugin through Watchful from an old version

== v1.5.0 ==
* Added support for Themes listing and updates.
* Minor refactoring on controllers.
* Fixed a PHP notice when using XCloner integration.
* Fixed a bug when looking for latest backup date done by Akeeba Backup.

== v1.4.12 ==
* Tested with last WP version 6.0

== v1.4.11 ==
* Remove unused class.

== v1.4.10 ==
* Fix bug during site validation when WordPress is in debug mode.

== v1.4.9 ==
* Improve function used to get last backup date from supported backup plugins.

== v1.4.8 ==
* Tested with last WP version 5.9.3

== v1.4.7 ==
* Add SSO feature
* Replace direct usage of class "ZipArchive" with WP function "unzip_file"
* Minor fixes

== v1.4.6 ==
* Add compatibility to Watchful Apps
* Add maintenance mode

== v1.4.5 ==
* Fix theme files check

== v1.4.4 ==
* Fix last backup date when using multiple backup profiles

== v1.4.3 ==
* Fix last backup date when using Akeeba API v2

== v1.4.2 ==
* Update tested WP version

== v1.4.1 ==
* Fix missing require to WP plugin administration API

== v1.4.0 ==
* Return new plugin version after an update to avoid log duplicates

== v1.3.2 ==
* Fix missing "permission_callback" while registering public endpoints

== v1.3.1 ==
* Fix Akeeba Backup integration

== v1.3.0 ==
* Add PHP 8.0 compatibility

== v1.2.20 ==
* Fix version number

== v1.2.19 ==
* Check for wp-config.php outside the web root

== v1.2.18 ==
* Add themes files to Early Warning Audit

== v1.2.17 ==
* Add backup status for AI1WM

== v1.2.16 ==
* minor fix

== v1.2.15 ==
* remove type hint from exception handler

== v1.2.14 ==
* fix WP core update form Watchful
* change URL to add a site to Watchful
* added WP admin user detection

== v1.2.13 ==
* fix PublishPress EDD updates

== v1.2.12 ==
* fix XCloner sorting backup dates
* fix XCloner integration

== v1.2.11 ==
* minor fix XCloner last backup date

== v1.2.10 ==
* add support to XCloner remote destinations

= v1.2.9 =
* minor fix on XCloner support

= v1.2.8 =
* change URL to retrieve hashes

= v1.2.7 =
* fix installation themes process
* add support to XCloner backup plugin

= v1.2.6 =
* fix "Add site to Watchful" button
* add XCloner backup plugin discovery

= v1.2.5 =
* fix warning about missing inclusion
* fix Akeeba settings detection

= 1.2.4 =
* Initial Release.

== Upgrade Notice ==

Nothing to declare.

== Screenshots ==

1. Watchful dashboard - Get a high-level overview of your web agency.
2. Remote backup manager - Schedule and monitor your backups.
3. Plugin auto updater - Set plugins to automatically update.
4. Uptime & SSL monitoring - Make sure sites are online and have valid SSL certificates.
5. Best practice, malware, and filesystem scanners - a suite of scanners to keep your site hardened against intruders.
6. Website Reports - Reports for your clients to show the value of your maintenance activities.

== License ==

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
