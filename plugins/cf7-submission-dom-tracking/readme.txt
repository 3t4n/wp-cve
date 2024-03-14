=== Submission DOM tracking for Contact Form 7 ===
Contributors: apasionados
Donate link: https://apasionados.es/
Author URI: https://apasionados.es/
Tags: contact form, track forms, form submission, form, tracking forms
Requires at least: 4.0.1
Tested up to: 6.3
Requires PHP: 5.5
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Track succesfull form submissions with Contact Form 7 using DOM events

== Description ==

The "on_sent_ok" and its sibling setting "on_submit" of the Contact Form 7 plugin are deprecated and scheduled to be abolished by the end of 2017. The recommended alternative to on_sent_ok is using DOM events (More info: [on_sent_ok Is Deprecated](https://contactform7.com/2017/06/07/on-sent-ok-is-deprecated/)). **This plugin helps to set these DOM events to track form submissions (Google Analytics event, Google Analytics page view and Facebook pixel)**.

You can also **hide the form after correct submission** and **deregister the Contact Form 7 styles and JavaScript on pages without forms**.

> Please note that this plugin does not work with [Google Analytics v4](https://support.google.com/analytics/answer/10089681).

= What can I do with this plugin? =

This plugin **tracks succesfull form submissions with Contact Form 7 using DOM events**. You can track the form submissions as Google Analytics Event, as Google Analytics page view or as Facebook Pixel Lead.

The plugin also gives the option to **hide a contact form** after succesfull form submissions.

And finally it also has the option to **include the styles and JavaScript of Contact Form 7 only on pages with contact forms** and not on all pages which is the standard behaviour of Contact Form 7.

= What ideas is this plugin based on? =

This plugin is based on the instructions of the author of Contact Form 7 when he anounced that [on_sent_ok Is Deprecated](https://contactform7.com/2017/06/07/on-sent-ok-is-deprecated/). We needed a solution for the sites we are running for ourselves and for our customers.

= System requirements =

PHP version 5.5 or greater.

= TROUBLESHOOTING =

__**Before creating a support ticket**__ for [Submission DOM tracking for Contact Form 7](https://wordpress.org/support/plugin/cf7-submission-dom-tracking) please have a look at the [Troubleshooting section of the FAQ](https://wordpress.org/plugins/cf7-submission-dom-tracking/#troubleshooting%20general%20javascript%20errors) and specially at the [Are there any known incompatibilities?](https://wordpress.org/plugins/cf7-submission-dom-tracking/#are%20there%20any%20known%20incompatibilities%3F) section (in short: *the plugin doesn't work with Contact Form 7 redirection plugins or add-ons for Contact Form 7 that redirect the user in some form using the DOM events and the Facebook lead isn't recorded when using PixelYourSite*).

= Submission DOM tracking for Contact Form 7 Plugin in your Language! =
This first release is avaliable in English and Spanish. In the "languages" folder we have included the necessarry files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](https://apasionados.es/contacto/index.php?desde=wordpress-org-contact-form-7-submission-dom-tracking-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [Submission DOM tracking for Contact Form 7 en espa&ntilde;ol](https://apasionados.es/blog/seguimiento-envio-formularios-contact-form-7-wordpress-plugin-7774/).

== Screenshots ==

1. New menu entry in Contact after plugin is activated.
2. DOM Tracking under Contact menu
3. Plugin configuration options.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
3. Go to CONTACT FORM 7 / DOM Tracking.
4. Configure settings.

== Frequently Asked Questions ==

= Why did you make this plugin?  =

**We created this plugin to be able to track form submissions in Google Analytics, with the Facebook pixel and to hide the form once completed**. Before we were doing this with "on_sent_ok". We also included the possibility to deregister the styles and JavaScript of the plugin on pages that doesn\'t contain a contact form.

This was published on the official Contact Form 7 website on June 7th, 2017 by the author of the plugin [on_sent_ok Is Deprecated](https://contactform7.com/2017/06/07/on-sent-ok-is-deprecated/).

> The "on_sent_ok" and its sibling setting "on_submit" of the Contact Form 7 plugin are deprecated and scheduled to be abolished by the end of 2017. The recommended alternative to on_sent_ok is using DOM events. This plugin helps to set this DOM events to track form submissions.
					
In order to minimize the impact on the large amount of sites we run, we decided to code this plugin.

= Does Contact Form 7 Submission DOM make changes to the database? =
Yes. It creates some entries in the options table. These entries are deleted if you deactivate and uninstall the plugin. If you only deactivate, settings are kept.

= How can I check out if the plugin works for me? =
Install and activate. Go to Contact Form 7 / DOM tracking. Configure the plugin.

Go to Google Analytics Real Time.

Fill in a contact form and submit it.

Check if an event or page view is triggered when the form is submitted correctly.

= Is there anything to take into consideration? =
If you are not using Google Analytics or do not have the Facebook pixel installed, please do not select these tracking options because it will lead to a JavaScript errors.

= I use Google Analytics for WordPress by MonsterInsights. Does your plugin work with it? =
We detect if the plugin "Google Analytics for WordPress by MonsterInsights" is active because it uses a non standard call to Google Analytics. Instead of ga it calls __gatracker.

This is taken into account.

= How can I remove Submission DOM tracking for Contact Form 7? =
You can simply activate, deactivate or delete it in your plugin management section. If you delete the plugin through the management section the configuration is deleted (entries in options table are removed). If you delete the plugin through FTP the configuration is not deleted.

= Which PHP version do I need? =
This plugin has been tested and works with PHP versions 5.5 and greater. WordPress itself [recommends using PHP version 7 or greater](https://wordpress.org/about/requirements/). If you're using a PHP version lower than 5.5 please upgrade your PHP version or contact your Server administrator.

= Is this plugin compatible with WPML =
Yes. We are running the plugin on several sites with WPML 3.7.x and 3.8.x.

= Are there any server requirements? =
Yes. The plugin requires a PHP version 5.5 or higher and we recommend using PHP version 7 or higher.

= Do you make use of Submission DOM tracking for Contact Form 7 yourself? = 
Of course we do. That's why we created it. ;-)

= Are there any known incompatibilities? =
Please don't use it with *WordPress MultiSite*, as it has not been tested.

The plugin **[CF7 Redirect](https://wordpress.org/plugins/wpcf7-redirect/)** is great for redirecting the form to a thank you page using the DOM events BUT it should not be used together with this plugin because it could create JavaScript errors.

Be careful with the plugin **Contact Form 7 - PayPal & Stripe Add-on** because after payment it redirects the user to a thank you page using the DOM events and therefore it should not be used together with this plugin because it will create JavaScript errors.

If you want to track the Facebook pixel leads please be advised that the plugin doesn't work with the **PixelYourSite plugin** :-(. PixelYourSite uses it's own functions instead of the standard fbq() functions of the Facebook pixel. The makers of the plugin were asked about how to track Contact Form 7 submissions and [How can we track Contact Form 7 forms?](https://wordpress.org/support/topic/how-can-we-track-contact-form-7-forms/) and they have no real solution (redirecting to another page is not a solution and tracking the click of the submit button is also not a solution as we only want to track when the form was correctly sent). If you are using PixelYourSite and want to track the form submissions without using a thank you page, we would recommend you to use the [Pixel Caffeine plugin from our friends of AdEspresso / Hootsuite](https://wordpress.org/plugins/pixel-caffeine/). We like Pixel Caffeine because it's easy to use, powerful and 100% free including the possibility to manage the Facebook Product Catalog with full WooCommerce & Easy Digital Downloads support.

= TROUBLESHOOTING General JavaScript errors =
If you are having problems with the plugin, first have a look at Javascript error in the Inspector Console. JavaScript errors can prevent the plugin from executing the tracking. No JavaScript errors should appear.

= TROUBLESHOOTING Google Analytics tracking =
In order to Troubleshoot Google Analytics problems you can use the [Tag Assistant (by Google)](https://chrome.google.com/webstore/detail/tag-assistant-by-google/kejbdjndbnbjgmefkgdddjlbokphdefk) in the Google Chrome Browser. There you can track if everything works correctly. Please keep in mind that you have to ENABLE the Tag Assistant and then reload the page.

= TROUBLESHOOTING Facebook Pixel =
In order to Troubleshoot Facebook Pixel problemas you can use the Google Chrome Extension: [Facebook Pixel Helper](https://chrome.google.com/webstore/detail/fb-pixel-helper/fdgfkebogiimcoedlicjlajpkdmockpc). There you can see if everything works correctly.

When you access a page with a Contact Form and the Facebook Pixel tracking enabled, you will se a "PageView" event that is green and a "Lead" event which has a red warning: "ERRORS: The Facebook pixel code on this page didn't load, so no information was sent to Facebook".

That the lead event has a red warning is normal. Here at [Troubleshooting Pixel Errors](https://developers.facebook.com/docs/facebook-pixel/pixel-helper?locale=en_US#did-not-load) you have more information:
> Pixel Did Not Load: This error means that the Pixel Helper found what looks to be Facebook pixel code on your site, but the actual HTTP call was never made. This could typically due to an error in the code, but could also occur if the pixel fires on a dynamic event (e.g. on a button click). In that case, the error will disappear upon clicking on the button.


After sending correctly the contact form, the red warning disappears and a Green sign appears that indicates that the pixel fired correctly.

= TROUBLESHOOTING Cannot read property 'style' of null =
If you get the following JavaScript error in the Inspector Console `uncaught TypeError: Cannot read property 'style' of null`, then it seems that you activated the option: "**Hide form after succesful submission?**" which hides the contact form after it has been completed and sent correctly, but didn't implement the code correctly.

In order to be able to hide only the form and not the succesful submission message, the form must be wrapped in a div called `hidecontactform7contactform`.

This has not been done and as this option adds this code to `wpcf7mailsent`: `document.getElementById('hidecontactform7contactform').style.display = 'none';` it ends with a JavaScript error and the Facebook Pixel is not fired.

Please either uncheck this option or wrap the form into the div called `hidecontactform7contactform`.

== Changelog ==

= 2.1 (19sep2022) =
* FIX: Remove `screen_icon` function call which is deprecated.

= 2.0 (19/SEP/2019) =
* Updated name from "Contact Form 7 Submission DOM tracking" to "Submission DOM tracking for Contact Form 7" in order to comply with the WordPress plugin repository trademark guidelines.

= 1.4.2 (04/09/2018) =
* Solved PHP 7.2.x notice: "Undefined variable: apa_cf7sdomt_Tracker in contact-form-7-s-dom-tracking.php on line 101"

= 1.4.1 (27/05/2018) =
* Added Troubleshooting tips to the FAQ.

= 1.4.0 (27/05/2018) =
* All plugin options are deselected on first install.

= 1.3.1 (20/04/2018) =
* Added comment about tracking form submissions as page view in Google Analytics: It can create 404 errors in Google Search Console so we recommend not to use it.

= 1.3.0 (17/09/2017) =
* Changed default URL to track the pageview to: "URL of the contact form" + "/ok/". For example if the contact form is on /contact/ the pageview when submitted successfully will be /contact/ok/

= 1.2.3 (17/09/2017) =
* Added Event Label information on settings page.

= 1.2.2 (17/09/2017) =
* Added Event Label to Google Analytics Event tracking with the contact form URL.

= 1.2.1 (17/09/2017) =
* Added check for Contact Form 7 version on Settings Page.

= 1.2.0 (04/08/2017) =
* Added options to customize Google Analytics events and page views.

= 1.0.3 (03/08/2017) =
* Added more options and made more options configurable.

= 1.0.2 (02/08/2017) =
* Made tracking options configurable.

= 1.0.1 (01/08/2017) =
* Added admin screen at Contact Form 7 / DOM tracking

= 1.0.0 (31/08/2017) =
* First official release.

== Upgrade Notice ==

= 2.1 =
UPDATED: Remove `screen_icon` function call which is deprecated.

== Contact ==

For further information please send us an [email](https://apasionados.es/contacto/index.php?desde=wordpress-org-contact-form-7-submission-dom-tracking).