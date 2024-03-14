=== Successful Redirection for Contact Form 7 ===
Tags: Form success redirection thank you page,Successful Redirection for Contact Form 7
Requires at least: 4.7.0
Tested up to: 6.4.3
Stable tag: 1.3.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A simple add-on for  Forms that adds a redirect option after form sent successfully.

== Description ==

The Additional Setting “on_sent_ok” is used to assign a JavaScript code that will be executed when a form submission completes and mail has been sent successfully. It is often used for the purpose of tracking form submissions with web analytics services or redirecting to another page after a form submission.

The “on_sent_ok” and its sibling setting “on_submit” are deprecated and scheduled to be abolished by the end of 2017. It’s not that using those settings is unsafe, but it’s possible that enabling them will increase risk in case there are vulnerabilities in this plugin or in other components of your site. It’s time to replace them with a safer alternative.

Update: on_sent_ok and on_submit have been officially removed from Contact Form 7 5.0.

A straightforward add-on plugin for Contactus Form  adds the option to redirect to any page you choose after mail sent successfully, with DOM Events and without AJAX being disabled.

== Usage ==

Simply go to your form settings, choose the "Redirect Settings" tab and set the page you want to be redirected to.

== Features ==

* Redirect to any URL
* Open page in a new tab
* Run JavaScript after form submission (great for conversion management)
* Pass fields from the form as URL query parameters
* More Information Visit our Official Website: http://www.wordpresstechy.com/

 


> Note: some features are availible only in the Pro version. Which means you need Redirection for Contact Forms
Pro to unlock those features.
 

== Installation ==

Installing Redirection for Contact Forms can be done either by searching for "Form success redirection" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org.
2. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Visit the settings screen and configure, as desired.

== Frequently Asked Questions ==

= Does the plugin disables  Forms Ajax? =

No, it doesn't. The plugin does not disables any of  Forms normal behavior, unlike all other plugins that do the same.

= Does this plugin uses "on_sent_ok" additional setting? =

No. One of the reasons we developed this plugin, is because on_send_ok is now deprecated, and is going to be abolished by the end of 2017. This plugin is the only redirect plugin for Contact Form 7 that has been updated to use [DOM events](https://contactform7.com/dom-events/) to perform redirect, as  developer Takayuki Miyoshi recommends. 

== Screenshots ==

1. Redirect Settings tab

== Changelog ==

= 1.0.0 =
* Initial release.