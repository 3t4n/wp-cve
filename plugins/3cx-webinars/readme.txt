=== 3CX Webinars ===
Contributors: wordpress3cx
Tags: Webinar, web conference, web meeting, video conferencing, 3CX
Requires at least: 4.8
Tested up to: 6.3
Requires PHP: 7.2.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The 3CX Webinars plugin provides free Webinars functionality to website visitors through 3CX.

== Description ==

The Webinars via 3CX system enables you to easily publish Webinars you create via the 3CX Web Client, wherever you want on your WordPress website. Visitors can subscribe to your webinars at the click of a button, while you can monitor subscriptions through the 3CX Web Client's "New Conference" function. The plugin can be customized through templates using open standards (Javascript and HTML).

The Webinars via 3CX system plugin is completely free but requires 3CX Phone System to be installed. 3CX Phone System is free for unlimited users - you can install it in your office on Linux or Windows or on an Amazon Lightsail or Google Cloud instance for $5 per month. Get 3CX here: <https://www.3cx.com/phone-system/download-phone-system/> 

This plugin works only with 3CX System V18 U2 or higher.

= Features =

* Link Webinar forms anywhere on your website using shortcodes
* Enable visitors to subscribe to Webinars using their name and email address
* List Webinar details including time and date, duration, location and subscribers
* Display Webinars linked to 3CX Extensions anywhere on your website
* Use the built-in webinar functionality of 3CX system from your WordPress website
* Webinars functionality is absolutely free for unlimited users via 3CX system Pro edition
* No monthly subscriptions per user

== Installation ==

1. Go to **“Plugins”** > **“Add New”** in WordPress admin, search for [3CX Webinars](https://www.3cx.com/phone-system/webinar-wordpress/) and click on **“Install Now”**.
1. When the installation completes, switch to the 3CX v18+ Management Console, go to **Settings** > **Conferencing** section and click **“Generate”** to create a new **API token**, authorizing the plugin to request and publish the webinar list on your WordPress site.
1. Click on **“Show”** to display the 3CX API token and click on the **“Copy”** icon to use the token on your WordPress site. Now you can create **Webinar Forms** to publish webinars for your WordPress site’s visitors in specific pages, posts or even site-wide.
1. To create a new **“Webinar Form”** in your WordPress site administration panel, click on the **“3CX Webinars”** sidebar link and then on **“Add New”**.
a) **“PBX Public URL”** - your 3CX system public URL (e.g. https://mypbx.3cx.eu:5001).
b) **“3CX API Token”** - paste the 64 chars token copied previously from **Settings** > **Conferencing** > **API** section of the 3CX system Management Console.
c) **“3CX Extension Number”** - specify the 3CX system extension number from which to retrieve the webinar list.
d) Use the rest of the fields to customize the [3CX Webinars](https://wordpress.org/plugins/3cx-webinars/) plugin functionality on your website and click **“Save”** when done.
1. Test out the new **Webinar Form** in a post, a page or even site-wide using the plugin’s shortcode format [3cx-webinar id="9" title="Upcoming Webinars"]

== Frequently Asked Questions ==

= I’ve installed the Webinars via 3CX system plugin, now what? =

Create a new “Webinar Form” item, specify the “3CX Public HTTPS URL”, “3CX API Key” and “3CX Extension Number” to use in a post or page via shortcode, i.e. [3cx-webinar id="3" title="My Webinars"]

= Where can I find documentation for 3CX Webinars? =

Reviewing the included sample entry and the built-in help tooltips can assist you while configuring **Webinar Form** fields.

= Does Webinars via 3CX system connects to or stores date on a third party server? =

With the default configuration, this plugin does not:
* Track users
* Save personal data to the database
* Use cookies.
This plugin communicates with the 3CX system server for authentication purposes and to retrieve Webinar lists. No personal data is sent during this process.
Name and email addresses of webinar attendees are sent to the 3CX server and stored on 3CX Phone System for the period of time required to allow participants to attend the Webinar, after which they are deleted. No participant data is written or stored on the Wordpress database.

= Can my visitors subscribe to a webinar without entering their name or email address? =

No, visitors need to subscribe to webinars providing their name or email address to get notified for webinars and subscription validation via the 3CX API.

= How does Webinars via 3CX system notify me for visitor subscriptions? =

The plugin notifies 3CX users for webinar subscriptions via the participants for the respective webinar entries in the 3CX Web Client's "New Conference" function.

= Can I show the Webinars via 3CX system entries to registered users only? =

Yes, including a “Webinar Form” shortcode in a private WordPress post or page makes the Webinars via 3CX system plugin accessible only to authorized users.

= How to display a user's organized webinars? =

Use the “3CX Extension Number” field to point each “Webinar Form” entry to a respective 3CX system extension.

== Troubleshooting ==

For troubleshooting issues with the [3CX Webinars plugin](https://www.3cx.com/phone-system/webinar-wordpress/) please visit our [forums page](https://www.3cx.com/community/forums/video-conferencing/).

== Screenshots ==

1. New Webinar options in 3CX Web Client.
2. 3CX Web Client view of scheduled webinars as seen by 3CX user/agent.
3. 3CX Webinars plugin configuration UI and options.
4. Visitor views list of published webinars via the 3CX Webinars plugin
4. Visitor views list of published webinars via the 3CX Webinars plugin while chatting via 3CX Live Chat and Talk plugin on a WordPress website.
5. Visitor subscribes to published webinar via the 3CX Webinars plugin subscribe dialog.
6. Subscription confirmation dialog for published webinar via the 3CX Webinars plugin.

== Changelog ==

18.2.4
Improved compatibility with Elementor and LazyLoad plugins

18.2.3
Updated all translations with .mo files

18.2.2
Added compatibility for V18 U1 (Known issue: no meeting description displayed, only title)
updated polish and russian translations

18.2.1
Added Test API Request function to Webinar editor
Improved error messages
Added Chinese, Dutch, French, German, Japanese, Portuguese, Portuguese (Brazil), Spanish languages

18.2.0
First public release for 3CX System v18 Update 2