=== Fathom Analytics Conversions ===
Contributors: dloxton, khanhvo
Donate link: https://www.fathomconversions.com
Tags: analytics, events, conversions, fathom
Requires at least: 5.9
Tested up to: 6.4
Stable tag: 1.0.12
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add conversions in WordPress plugins to Fathom Analytics

== Description ==

This Fathom Analytics *partner plugin* makes it easy to enable WordPress forms and pages as [Fathom Analytics](https://usefathom.com) Events with *no code*.

👉 Go to the official [Fathom Analytics plugin](https://wordpress.org/plugins/fathom-analytics/)


= What does this companion plugin do? =

Makes it easy to track user actions such as form submissions and landing page visits as events in your Fathom Analytics account.

Best of all *no technical knowledge is required* to implement events on your site.

= Key Features =

* No code event tracking for marketers and website owners
* Common form plugins supported
* Make any page or post an Event
* Synchronized event names between website and analytics


= Watch the setup walkthrough =
https://youtu.be/nyi7d1SMBeo

= This is not an official Fathom plugin =
This WordPress plugin "Fathom Conversions" is not part of, or associated with "Fathom Analytics" by Conva Ventures Inc.

= ⚠️ Warning BETA API in use =
This plugin uses the Beta Fathom Analytics API, which is still in early access, and subject to changes in the future, this plugin could stop working without warning if updates occur.

= Privacy Notices =

This plugin:

* does not track any users
* does not write any data to the database
* sends data to the Fathom Analytics servers - more information can be found on [Fathom Analytics](https://usefathom.com) website

= Demo =

You can find more information about the plugin, and see a demo and installation instructions on the [Fathom Conversions website](https://fathomconversions.com)

= Requirements =

For this to work you will need a paid [Fathom Analytics account](https://usefathom.com/ref/LBSJIU) (get $10 off your first month with this link)

And a supported WordPress plugin listed below installed and active.

= Currently supported plugins =

*   [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)
*   [WPForms](https://wordpress.org/plugins/wpforms-lite/) & [WPForms Pro](https://wpforms.com/)
*   [Gravity Forms](https://www.gravityforms.com/)
*   [Ninja Forms](https://wordpress.org/plugins/ninja-forms/) & [Ninja Forms Pro](https://ninjaforms.com/)
*   [Fluent Forms](https://wordpress.org/plugins/fluentform/) & [Fluent Forms Pro](https://fluentforms.com/)
*   [WooCommmerce](https://woocommerce.com/)

== Installation ==

This section describes how to install the plugin and get it working.

Easy method:

1. From the WordPress dashboard Plugins > Add New search for 'Fathom Analytics' and click install then Activate
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Follow the instructions on the settings page to set up your API Key

Manual method:

1. Download the zip file from this page and unzip
1. Upload the entire `fathom-analytics-conversions` folder to the `/wp-content/plugins/` directory using your favourite FTP tool
1. Follow the instructions on the settings page to set up your API Key


== Frequently Asked Questions ==

= Is this an official plugin? =

No.

The team at Fathom Analytics have been supportive of the production of this plugin, but it is not an official Fathom Analytics plugin.

= Why did you build this plugin? =

In January 2022 [Google Analytics was deemed illegal in the EU](https://www.sixfive.com.au/2022/02/austrian-dpa-rules-that-google-analytics-is-not-gdpr-compliant/) as data would be sent to Google server's in the US. this started our search for a more privacy focussed platform - we found Fathom Analytics.

In March 2022 [Google announced sunset July 1, 2023 and data deletion (+6 months) of Google Analytics Universal](https://www.searchenginejournal.com/google-sunsetting-universal-analytics-in-2023/442168/), with no data being kept essentially forcing users on to Google Analytics v4. This only complicates matters for the billions of sites using Google Analytics, and does not deal with the privacy requirements of EU users.

Looking for an analytics tool to replace Google Analytics, we found Fathom and loved it for it's simplicity and GDPR compliance. The one blocker however, was adding events was a code reliant task, something we badly wanted to be easier. The official plugin makes it easy to place the tracking code on the page, it does not add the ability to create events/conversions easily.

So to make this easier for ourselves, marketers, website owners and non-coders - we built this plugin to have the ability to easily track these user events with only a few clicks. Fathom Analytics Conversions is the **no code** answer for WordPress and Fathom Analytics users.

= Who are you? =

We are the team from [SixFive](https://www.sixfive.com.au) and we build, host and care for our clients websites built on WordPress. We have more than 20 years experience with hundreds of clients globally.

= How do I get started? =

1. Start by creating your account on [Fathom Analytics](https://usefathom.com/ref/LBSJIU)
1. Install the official [Fathom Analytics](https://wordpress.org/plugins/fathom-analytics/) plugin, and configure it with your site ID.
1. Install or upload this plugin
1. Go to Settings > Fathom Analytics Conversions and follow the steps to create your API Key
1. Open the [Fathom Analytics API Settings](https://app.usefathom.com/#/settings/api) page
1. Create a new token, using a sensible name
1. Create as a 'Site-specific key'
1. Set Access to 'Manage' (this is because we need to create Events, not just track against them)
1. Click Generate API Key
1. Copy the API Key and paste into Settings > Fathom Analytics Conversions
1. Click 'Save Changes'
1. Check the boxes for the installed plugins you want to track data from

The plugin will then go through all our supported plugins and create the matching events. As soon as your form has a submission it will be recorded in your Fathom Analytics dashboard.


= I have a feature request = 

Please create a feature request in the [WordPress plugin support](https://wordpress.org/support/plugin/fathom-analytics-conversions/) pages.

= I have a bug or issue =

Please create a thread in the [WordPress plugin support](https://wordpress.org/support/plugin/fathom-analytics-conversions/) pages.

== Screenshots ==

1. Add your API Key here, and enable / disable integrations via the plugin settings.
2. We display the Event ID created by the plugin in the Fathom Dashboard, on a tab in Contact Form 7.
3. Events are created automatically in Fathom Analytics
4. You will need an API Key from Fathom Analytics

== Changelog ==

= 1.0.12 =
Added ability to add classes or id's to elements to trigger events

= 1.0.11 =
* Added ability to flag conversion for Login and Registration events

= 1.0.10 =
* Moved JS to files to support caching plugins and deferred loading

= 1.0.9 =
* WooCommerce support, adding order total to the event

= 1.0.8 =
* Allowing custom fathom code option, and removal of reliance on official Fathom Plugin

= 1.0.7 =
* Removed check of site name

= 1.0.6 =
* Changed URL to the API Page to meet the new usefathom.com URL

= 1.0.5 =
* Added support for Gravity Forms
* Added support for Fluent Forms
* Added support for Ninja Forms
* Added support for URL based conversions

= 1.0.3 =
* Added support for WP Forms Free & Pro

= 1.0.1 =
* Added deletion of plugin settings on plugin delete

= 1.0 =
* First version supporting Contact Form 7


== Upgrade Notice ==

= 1.0 =
This is the first version.
