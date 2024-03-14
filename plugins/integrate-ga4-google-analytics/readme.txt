=== Integrate GA4 Google Analytics ===
Contributors: Obadiah
Donate link: http://middleearmedia.com/
Tags: analytics, ga, google, google analytics, google analytics plugin, tracking, ga4, middle ear media, obadiah, integrate ga4 google analytics
Tested up to: 6.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple, lightweight plugin to easily integrate Google Analytics GA4 tracking into your WordPress site.

== Description ==

The "Integrate GA4 Google Analytics" plugin is a simple, lightweight, and fast plugin that enables Google Analytics tracking for your WordPress site. It supports Google Analytics 4, and allows you to easily integrate the latest Google Analytics code into your site properly.

## Simple to Use
To use the plugin, simply enter your GA4 Measurement ID into the plugin settings page. Press the "Save Changes" button. Once the GA4 Measurement ID is entered, the plugin will insert the appropriate script into your site, allowing Google Analytics to access user activity on the site. It's important to note that it can take up to 24-48 hours after adding your GA4 ID before any analytical data appears in your Google Analytics account.

== Installation ==

## Automatic Installation
* Login to your WordPress Dashboard.
* Visit Plugins > Add New.
* Search for Integrate GA4 Google Analytics.
* Install and activate the Integrate GA4 Google Analytics plugin.

## Manual installation
* Upload the entire integrate-ga4-google-analytics folder to the /wp-content/plugins/ directory.
* Visit Plugins.
* Activate the Integrate GA4 Google Analytics plugin.

## Other Projects
I also have other plugins that might interest you.

[Super Custom Login](https://wordpress.org/plugins/super-custom-login/)

The Super Custom Login plugin offers customization options for the WordPress login screen, including the ability to replace the default WordPress logo with a custom logo that links to the user's homepage. There are custom color settings for all elements on login page. Additionally, the plugin improves login security by removing error messages upon failed login attempts.

== Screenshots ==

1. Enter your Google Analytics GA4 Measurement ID on the Settings page

== Changelog ==

= 1.2 =
* Update tags and description in readme file. 06/09/2023
* Update plugin short description. 06/09/2023
* Add instructions on how to find GA4 Measurement ID. 06/09/2023

= 1.1 =
* Update readme file. 04/13/2023
* Update screenshot image. 04/13/2023
* Retain plugin data upon Plugin deactivation. Remove plugin data upon Plugin uninstall. 04/13/2023 
* Escape all output. 04/13/2023
* Use wp_add_inline_script to insert GA4 tracking script. 04/12/2023
* Use wp_enqueue_script to link to Google Tag Manager. 04/12/2023
* Validate the input. If empty, give error. If formatted incorrectly, give error. 04/12/2023
* Reformat the code to be easier to read. 04/12/2023
* Remove redundancies. 04/11/2023
* Sanitize input. 04/11/2023
* Submit plugin to official WordPress plugin directory. 04/07/2023
* Create banner. Create icon. 04/07/2023
* Test plugin with WordPress 6.2. 04/07/2023
* Update readme file. 04/07/2023
* Update plugin description. 04/07/2023
* Add section header and additional instructions. 04/07/2023
* Define settings page with one input, type text for the GA4 ID. Also a Save Changes button. 04/07/2023
* Register settings and sanitize the inputs for security. 04/07/2023
* Add link to the settings page in the plugin list. 04/07/2023
* Add custom settings page to WordPress Dashboard under the Settings submenu. 04/07/2023
* Add code to abort if the plugin file is called directly, as a security measure. 04/07/2023

= 1.0 =
* Update plugin description. 05/29/2017
* Create readme file. 05/29/2017
* Create plugin file. Requires the user to manually install and manually add tracking script to plugin file. 05/29/2017