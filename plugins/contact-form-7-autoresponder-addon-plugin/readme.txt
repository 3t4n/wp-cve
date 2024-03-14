=== CF7 AutoResponder Addon ===
Plugin Name: CF7 AutoResponder Addon
Plugin URI: https://wpsolutions-hq.com/
Description: Allows adding visitors to your Mailchimp list when they submit a message using Contact Form 7.
Tags: autoresponder, contact form 7, Mailchimp
Author: wpsolutions, Peter Petreski
Author URI: https://wpsolutions-hq.com/
Donate link: https://www.paypal.me/cf7addondonate
Contributors: wpsolutions
Requires at least: 4.5
Tested up to: 5.0.1
Stable tag: trunk
Version: 3.1
Text Domain: cf7-autoresponder-addon
Domain Path: /languages
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows automatic subscription of people to your MailChimp list after they've submitted a CF7 form.

> GDPR-compliance: This plugin works in tandem with the Contact Form 7 plugin and the Mailchimp API.
This plugin does not collect visitor data. 
If configured to so, this plugin will add visitors who submit a CF7 form to your Mailchimp list. 
(Please see the following MailChimp page for GDPR info: https://mailchimp.com/help/collect-consent-with-gdpr-forms/ )

*** Now easier to use with new improved functionality and more flexibility. ***

== Description ==
The Contact Form 7 AutoResponder Addon plugin allows you to automatically add people to your MailChimp list after they've submitted one of your Contact Form 7 forms.

The plugin will read any type of configured fields which are submitted from your CF7 form and insert the information in the relevant Mailchimp list. 

For more information on how to configure and use this plugin please <a href="https://wpsolutions-hq.com/contact-form-7-mailchimp-addon-plugin" target="_blank">see this</a> page.

== Installation ==

1. FTP the cf7-autoresponder-addon folder to the /wp-content/plugins/ directory, 

OR, alternatively, 

upload the cf7-autoresponder-addon.zip file from the Plugins->Add New page in the WordPress administration panel.

2. Activate the cf7-autoresponder-addon plugin through the 'Plugins' menu in the WordPress administration panel.

If you encounter any bugs, or have comments or suggestions, please use the support forum.


== Frequently Asked Questions ==

= Will this plugin tell me when I have successfully added someone to my MailChimp list? =

The CF7 AutResponder Addon plugin will automatically attempt to add someone to your MC list and it will inform you via a log file if a failure occured during a subscription. 
The subscription actions and results will be taken care of by MailChimp.
The important thing to note is that provided your API Key and list name have been correctly configured, your subscriber will need to opt-in by default by clicking on the 
subscribe link in the email which will be sent from MailChimp.
The plugin also has a setting where you can disable the double opt-in email. Please beware that MailChimp can suspend your account if you abuse such a feature.


= Does this plugin add people my MailChimp list by default or can I ask for their permission? =
As long as you have the enabled the settings and created the approporiate CF7 form with the correct names, this plugin will 
automatically invoke MailChimp to send your visitors an "opt-in" email. It is up to them to accept the opt-in or not.

You can also let your visitors decide at the time of filling in the CF7 form whether they want to subscribe to your list by using a checkbox 
with the name "mc-subscribe" in your CF7 form. In this case, your visitor will receive an opt-in email from MailChimp only if they've enabled
the checkbox in the CF7 form.

Alternatively, the plugin also has a setting where you can disable the double opt-in email which means you can automatically subscribe people without double-optin.

= Can I have multiple CF7 forms on my site where each is used to subscribe people to a different Mailchimp list? =
Yes. Any CF7 form you choose can be configured to add visitors to any Mailchimp list you specify via special configuration which you can add in the Additional Settings tab of Contact Form 7 Settings.
The detailed instructions for how to configure each Contact Form 7 form can be found here:
https://wpsolutions-hq.com/contact-form-7-mailchimp-addon-plugin

= If I have many CF7 forms which are configured for Mailchimp, can I momentarily disable the Mailchimp integration for just one particular form without affecting all other CF7 forms? =
Yes. Simply put the following line inside the "Additional Settings" tab of the appropriate CF7 form:

mc_skip: true

Using the above line saves you from deleting your existing configuration in the "Additional Settings" tab. 
When you are ready to start using that CF7 form with Mailchimp again just delete the "mc_skip: true" line.

= Can I add subscribers to one of my Mailchimp list's interest category (or group)? =
This is a premium feature and is only available in the premium version of this plugin which can be found here:
https://wpsolutions-hq.com/premium-contact-form-7-mailchimp-plugin

== Screenshots ==

1. Screen shot file screenshot-1.jpg shows Settings menu location of this plugin.
2. Screen shot file screenshot-2.jpg shows the administration page of this plugin.
3. Screen shot file screenshot-3.jpg shows the your mailchimp account's list settings for the form merge-tags.
4. Screen shot file screenshot-4.jpg shows how to set the form elements settings inside a CF7 form's settings page.
5. Screen shot file screenshot-5.jpg shows how to configure your Mailchimp list name in the CF7 form so you can add subscribers to that list.
== Changelog ==

= 3.1 =
- Added list ID as alternative config item to subscribe a user. 
- Tested with latest WordPress version 5.0.1.

= 3.0 =
- Improved and changed usage configuration. This plugin is now easier to use and more flexible in the way you administer it with Contact Form 7. Please <a href="https://wpsolutions-hq.com/contact-form-7-mailchimp-addon-plugin">see this</a> page for more info.

= 2.0.1 =
* Corrected minor paste error.

= 2.0 =
* Updated code and tested up to latest WordPress version (4.9.6).
* Updated code to use new MailChimp 3.0 API.
* Cleaned up code and added better sanitization and validation. 

= 1.9.1 =
* Fixed code which was producing a PHP warning.

= 1.9 =
* Added ability to insert custom fields/tags to your mailchimp list.

= 1.8 =
* Made compatible with the latest version of CF7 (3.9).

= 1.7 =
* Implemented new Mailchimp API v2.0.

= 1.6 =
* Fixed bug - Latest CF7 version introduced a bug: people were being subscribed even when checkbox was disabled.
* Made settings data sanitation/validation more robust

= 1.5 =
* added new functionality to allow people to add subsribers to different autoresponder lists on a per Contact Form 7 basis
* minor changes - fix some broken links

= 1.4 =
*added option to disable mailchimp double opt-in email
*fixed bug where the CF7 form appeared to hang whenever an existing MC subscriber tried to subscribe again

= 1.3 =
* Fixed bug where new version of CF7 (v3.3) broke the checkbox "mc-subscribe" functionality

= 1.2 =
* Fixed bug where new version of CF7 broke the checkbox "mc-subscribe"

= 1.1 =
* added ability for checkbox in the CF7 form to govern whether the visitors wants to allow list subscription
* added a check to ensure that the main CF7 plugin is present and activated

= 1.0 =
* First Release


For more information on the cf7-autoresponder-addon and other plugins, visit the <a href="https://wpsolutions-hq.com/" target="_blank">WPSolutions-HQ Blog</a>.
Post any questions or feedback regarding this plugin at our website here: <a href="https://wpsolutions-hq.com/plugins/contact-form-7-autoresponder-addon/" target="_blank">cf7-autoresponder-addon</a>.
