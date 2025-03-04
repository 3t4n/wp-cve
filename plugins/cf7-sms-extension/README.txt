=== SMS Extension for Contact Form 7 ===
Contributors: kofimokome
Donate link: https://ko-fi.com/kofimokome
Tags: sms, contact form, contact form 7, extension, twilio, vonage, nexmo, whatsapp
Requires at least: 6.2
Tested up to: 6.4
Stable tag: 1.3.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Receive text message notifications when a form is submitted.

== Description ==

This Plugin adds SMS Texting capability to Contact Form 7, from supported providers ( see list below ).
Configure your forms to send Text messages and WhatsApp messages to you and your visitors from the SMS tab in Contact Form 7 plugin.
It also allows for Text notifications when you receive through your forms.

Note: This is just an extension. This plugin is not affiliated with or endorsed by Contact Form 7.

== Supported Providers ==
1. Twilio
2. Nexmo
3. WhatsApp
We will add more providers with time.

== Pro Features ==
Upgrade to the pro version from the Account submenu page or using [this link](https://checkout.freemius.com/mode/dialog/plugin/13504/plan/22687/licenses/1/) to have access the following features:
1. SMS History: See all SMS sent from your website and resend an SMS that was not sent
2. WhatsApp Template Parameters: Add parameters to your WhatsApp templates and send personalized messages

== Installation ==
1. Download the plugin
2. Install and activate
3. Open CF7 SMS Extension from your admin menu
4. Go to Options and fill your SMS credentials (API key, etc ..) and save
5. Open the contact form in which you would like to add SMS notifications. Click on the SMS tab for configurations.

== Changelog ==

= 1.3.3 =
* Fix email sending even when the prevent form submission checkbox is checked

= 1.3.2.1 =
* Update Freemius SDK
* Update WordPressTools library

= 1.3.2 =
* Add WhatsApp message feature
* Update WordPressTools library

= 1.3.1 =
* Update WordPressTools library

= 1.3.0 =
* Add SMS History Tab

= 1.2.2 =
* Add new SMS provider (Nexmo)

= 1.2.1 =
* Add ability to send SMS to more than one admin number

= 1.2.0 =
* Add page to test your configurations
* Add option to prevent form from submitting if an error occurs while sending sms or skip error and
  submit the form

= 1.1.0 =
* Add option to show/hide error messages if an sms was not sent
* Add page to send a test SMS
* Enhancements

= 1.0.1 =
* Fix SMS not sending
* Remove Twilio PHP SDK, Switch to Twilio API

= 1.0.0 =
* Initial Release


== Screenshots ==

1. SMS Provider Configuration Page
2. Contact Form 7 SMS Settings Tab
3. Contact Form 7 SMS Settings Tab

== How to Contribute ==
The source codes can be downloaded here [GitHub](https://github.com/kofimokome/cf7-sms-extension)