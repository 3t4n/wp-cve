=== Visual Form Builder - Custom Validation Messages ===
Contributors: mmuro
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=G87A9UN9CLPH4&lc=US&item_name=Visual%20Form%20Builder%20Custom%20Validation&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: contact form, forms, form builder, jQuery validation
Requires at least: 3.5.1
Tested up to: 3.8.1
Stable tag: 1.2
License: GPLv2 or later

Customize the default jQuery validation messages for all Visual Form Builder or Visual Form Builder Pro forms.

== Description ==

Customize the default jQuery validation messages for all [Visual Form Builder](http://wordpress.org/extend/plugins/visual-form-builder/) or [Visual Form Builder Pro](http://vfb.matthewmuro.com) forms.

Change messages like "This field is required." and "Please enter a valid email address." to any text you want.  This is a great plugin for those international users who would like to translate these messages to their own language.

== Installation ==

1. Go to Plugins > Add New
1. Click the Upload link
1. Click Browse and locate the `vfb-custom-validation-messages.x.x.zip` file
1. Click Install Now
1. After WordPress installs, click on the Activate Plugin link
1. Go to the `Visual Form Builder > Validation Messages` menu

== Frequently Asked Questions ==

= How do I customize the messages for each form? =

The plugin will customize the default messages for **all** forms.

If you require customizations on a *per form* basis, you will need to customize the validation method yourself.  Please [follow this tutorial](http://vfbpro.com/2012/05/08/adding-custom-jquery-validation-to-your-form/) for tips.

= What are the {0} and {1} placeholders? =

These are special keys within certain validation messages that will display a number.  When indicated, you should ensure your message includes the placeholders.

== Screenshots ==

1. Settings page

== Changelog ==

**Version 1.2 - Jan 28, 2014**

* Add rangelength, minlength, and maxlength (Pro only)
* Remove call to screen options icon

**Version 1.1 - Apr 18, 2013**

* Only print script if jQuery Validation plugin has been output on page

**Version 1.0 - April 16, 2013**

* Plugin launch!

== Upgrade Notice ==

= 1.1 =
Only print script if jQuery Validation plugin has been output on page