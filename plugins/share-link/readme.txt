=== Share Link ===
Contributors: harmonicnewmedia
Donate link: https://sharelinktechnologies.com/
Tags: ASX Announcements, Share price, commodity price, finance, ASX
Requires at least: 3.0.1
Tested up to: 6.4
Stable tag: 2.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically manage the addition of ASX Announcements to your website via the Share Link Wordpress plug-in.

== Description ==

Automatically manage the addition of ASX Announcements to your website via the Share Link Wordpress plug-in.

Share Link is a subscription service that includes the ability to display share prices, commodity prices, graphs and automatically upload ASX Announcements to your website.

This plugin allows customisation of the presentation of ASX Announcements received via Share Link. This includes display style, pagination, groupings by year, month and more.

Note: requires a subscription to Share Link available from [Share Link](https://sharelinktechnologies.com/ "Share Link")

== Installation ==

Installation of this plugin works as per any standard plugin:

1. Upload the zip file to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Register for an account via [Share Link Website](https://sharelinktechnologies.com/ "Share Link") and receive an activation code
1. Click on the Share Link icon in the side bar and enter your registration details

== Frequently Asked Questions ==

= I am being asked for a licence code, how do I get one? =

You need to sign up for a plan on the Share Link service, based on the functionality you require. Please visit [Share Link Website](https://sharelinktechnologies.com/ "Share Link") to setup your account.

= Where can I find further instructions? =

Once installed, there are use instructions within the plugin. Further detailed information is available via the [documentation](https://app.sharelinktechnologies.com/docs/3.0/overview "documentation") on our website.

= I cannot find the answer to my query what should I do? =

Please review our [documentation](https://app.sharelinktechnologies.com/docs/3.0/overview "documentation"), though if your query is not answered visit the [Share Link Website](https://sharelinktechnologies.com/ "Share Link") and contact us with your query.

== Screenshots ==

1. An example of displaying ASX Announcements with Share Link
2. The Share Link plugin configuration screen

== Changelog ==

= 2.0.9 =
* Updated test up Wordpress 6.4

= 2.0.8 =
* Updated test up Wordpress 6.2

= 2.0.7 =
* Fix repeated callbacks to main server

= 2.0.6 =
* Update tested upto wordpress version 5.8

= 2.0.5 =
* Update FAQ section

= 2.0.4 =
* Fixed version number 2.0.4

= 2.0.3 =
* Update tested upto wordpress version 5.5

= 2.0.2 =
* Removed depricated plugin code

= 2.0.1 =
* Update Readme file

= 2.0.0 =
* Updated for compatibility with new Share Link system

= 1.3.14 =
* Added flush functionality for 2020 ASX data source update

= 1.3.13 =
* Configuring download feed to follow redirects (HTTPS compatibility)

= 1.3.12 =
* Updated SSL check to default calls to https with smarter checking for non-https

= 1.3.11 =
* Updated Widget to use new PHP7.1 friendly constructor

= 1.3.10 =
* Removed depricated code that now rely's on server rather than client

= 1.3.9 =
* Updated to front-end rendering to load external resources over HTTPS correctly

= 1.3.8 =
* Minor Tweak to feed

= 1.3.7 =
* Revert back to stable data extraction

= 1.3.6 =
* Changed order of data extraction to match new format

= 1.3.5 =
* Added https detection for announcement download

= 1.3.4 =
* Added per-site HTTPS access to data source

= 1.3.3 =
* Fixed Announcement Sorter to display correct order

= 1.3.2 =
* Removed Legacy method of rendering to support full shortcode (with attributes) method.

= 1.3.1 =
* Updated Data feed to https

= 1.3.0 =
* Full release of updated format
* Updated Help page to reflect current state

= 1.2.22 =
* Updated RSS Generator to use correct date formatting

= 1.2.21 =
* Updated Announcement rending to serve annoucements when document is on server correctly.

= 1.2.20 =
* Updated Announcement List renderer to use esc_url

= 1.2.19 =
* Updated rendering to use esc_url

= 1.2.18 =
* Updated Widget rendering to fix announcements rendering

= 1.2.17 =
* Added one time clearing code
* Fixed duplicating announcements bug

= 1.2.16 =
* Changed download to use link over raw download

= 1.2.15 =
* Fixed spelling of 'Announcments' to 'Announcements'

= 1.2.14 =
* Added isPermaLink false flag to RSS to generate valid RSS

= 1.2.13 =
* Fixed Verification Step

= 1.2.12 =
* Fixed Error File

= 1.2.11 =
* Added Error File

= 1.2.9 =
* Fixed External data feed dependency

= 1.2.6 =
* Fixes for duplicate announcements under certain conditions.

= 1.2.5 =
* Fix an error that caused announcements not to update.

= 1.2.4 =
* Use SSL when the requesting page is on SSL.

= 1.2.3 =
* Allow short codes to be real short codes.

= 1.2.2 =
* Bug fixes for HTML embedding.

= 1.2.1 =
* Updated graph embed help.

= 1.2.0 =
* Minor update to resync version numbers correctly.

= 1.1.9 =
* Minor update to ASX announcements.

= 1.1.8 =
* Fix a bug related to ASX announcements.

= 1.1.7 =
* Add a short tag to use the Version 3 Graph renderer.

= 1.1.6 =
* Bug fix for installed state detection.

= 1.1.5 =
* Updates to make it work with Wordpress 4.0.

= 1.1.4 =
* Adjustments for announcement downloads.

= 1.1.3 =
* Pagination links are more robust based on WP URI configuration

= 1.1.2 =
* Made initial creation of wp-content/sharelink folder more reliable

= 1.1.1 =
* Small changes to plugin to update read me etc.

= 1.0 =
* Initial release

= 0.5 =
* Beta release

== Upgrade Notice ==

= 1.0 =
Removes beta tag and is production ready.

= 0.5 =
This version was our initial beta release.
