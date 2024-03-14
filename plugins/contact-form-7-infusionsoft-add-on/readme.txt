=== Contact Form 7 - InfusionSoft Add-on ===
Contributors: rnevius
Tags: contact form 7, cf7, contact form, contact forms 7, infusionsoft, infusion soft, forms, infusionsoft form, form, contact form 7 add-on, cf7 infusionsoft, contact form 7 infusionsoft, lead capture, contact form 7 crm, infusionsoft crm, lead form, email capture, business, CRM, e-commerce, forms, marketing
Requires at least: 3.8.2
Tested up to: 4.3
Stable tag: trunk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

An add-on for Contact Form 7 that provides a way to capture leads, tag customers, and send contact form data to InfusionSoft.


== Description ==

An add-on for Contact Form 7 (CF7) that provides a straightforward method to capture leads, tag customers, and send contact form data to InfusionSoft. Supports HTML5 input types.

**NOTE:** This plugin requires Contact Form 7 version 3.9 or later. 

*This plugin is not offered, sponsored, associated with or endorsed by Infusion Software, Inc.*


== Installation ==

1. Unzip the downloaded plugin archive.
2. Upload the inner 'contact-form-7-infusionsoft-add-on' directory to the '/wp-content/plugins/' directory on your web server.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Navigate to the [CF7 InfusionSoft Add-on Settings Page](https://wordpress.org/plugins/contact-form-7-infusionsoft-add-on/screenshots/) in the 'Settings' menu of your WordPress Dashboard.
5. Save your [InfusionSoft App Name and API Key](https://wordpress.org/plugins/contact-form-7-infusionsoft-add-on/faq/).

Please note that [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) (version 3.9 or later) must be installed and activated in order for this plugin to be functional.

*This plugin is not offered, sponsored, associated with or endorsed by Infusion Software, Inc.*


== Frequently Asked Questions ==

= Why doesn't this plugin do *insert feature here*? =

If you have a feature suggestion, I'd love to hear about it. Feel free to leave a message on the [Support tab of the plugin page](http://wordpress.org/support/plugin/contact-form-7-infusionsoft-add-on), and I'll follow up as soon as possible.

= What contact data can I collect with this plugin? =

This add-on currently supports collection of First Name, Last Name, Company, Email, Person Notes, and Phone Number. If enough people find this plugin useful, I will include any other Contact fields that are requested. If you have a feature suggestion, feel free to leave a message on the "Support" forum.

= Can I use HTML5 with this plugin? =

Absolutely! While the tag generator creates simple `[text]` fields by default, you can use HTML5 by manually creating tags, following the Contact Form 7 guidelines. For example, an HTML5 "email" input with a placeholder would look like: `[email* infusionsoft-email placeholder "Email"]`.

= How do I find my "App Name"? =

Your App Name is the part of your InfusionSoft login URL that comes before *.infusionsoft.com"*. For example, if you login at *https://wp123.infusionsoft.com*, your App Name will be *wp123*.

= How do I find my "API Key"? =

To generate an API Key (also referred to as the InfusionSoft API Encrypted Key), please follow the [how-to article in the InfusionSoft User Guide](http://ug.infusionsoft.com/article/AA-00442/0/How-do-I-enable-the-Infusionsoft-API-and-generate-an-API-Key.html).

= How do I tag contacts? =

Tags must be [created in InfusionSoft](http://ug.infusionsoft.com/article/AA-00306/0/Add-edit-or-delete-a-tag.html) before they can be used with this plugin. Once a tag has been created, the "tag name" can be added on each contact form edit page. Contacts will be tagged if CF7 successfully delivers a message. If you want to add multiple tags to a contact, simply input a list of tags separated by commas (e.g. tag 1, tag 2, tag 3).

= Can I contact InfusionSoft support, if I need help with this plugin? =

This plugin is not offered, sponsored, associated with or endorsed by Infusion Software, Inc. If you need help finding your App Name, API Key, or adding contact tags, InfusionSoft support *should* be able to help you. However, as this plugin is not affiliated with InfusionSoft in any way, you'll need to contact the plugin author via the [Support Forum](http://wordpress.org/support/plugin/contact-form-7-infusionsoft-add-on), if you need help with the plugin itself.


== Screenshots ==

1. Upon plugin activation, a new options page will appear in your Admin Settings menu. Your InfusionSoft App Name and [API Key](http://ug.infusionsoft.com/article/AA-00442/0) will need to be set here before you can use the plugin. 

2. This add-on will add a new tag generator to the Contact Form 7 edit page.

3. All InfusionSoft input types are housed under the tag generator. To add InfusionSoft fields to your form, generate a tag and copy it to the form. HTML5 input types are supported, but must be manually entered into the form.

4. This add-on also adds a "Contact Tag" input field on each form edit page. These tags must already exist in InfusionSoft. To add multiple tags to a contact, simply input a comma-delimited list of tags (e.g. tag 1, tag 2, tag 3). Copy these exactly how they appear in InfusionSoft.

*This plugin is not offered, sponsored, associated with or endorsed by Infusion Software, Inc.*


== Changelog ==

= 1.2.2 =
* Adds a "Website" input field to the Form Tag generator.

= 1.2.1 =
* Fixes bug caused by short-hand PHP array syntax in PHP versions 5.3 and below.

= 1.2.0 =
* Adds support for Contact Form 7 version 4.2+.

= 1.1.1 =
* Adds a "Person Notes" input field to the Form Tag generator.

= 1.1.0 =
* Adds support for adding multiple tags to contacts.
* Adds a "Company" input field to the Form Tag generator.

= 1.0.3 =
* Fixes a bug that prevented contacts from being added to InfusionSoft, if a tag was not supplied.

= 1.0.2 =
* Upgrade process for verifying that Contact Form 7 is installed and up to date. 

= 1.0.1 =
* Modification to Form Tag generator
* Update Options page with link to InfusionSoft User Guide

= 1.0.0 =
* Initial version
