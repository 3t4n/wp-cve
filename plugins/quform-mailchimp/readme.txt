=== Quform Mailchimp ===
Contributors: ThemeCatcher
Donate link: https://www.themecatcher.net/#buy-us-a-coffee
Tags: mailchimp, form builder, email marketing, forms, quform, gdpr
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 1.3.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily add contacts to Mailchimp from Quform forms.

== Description ==

Integrates with any of your Quform forms to automatically add contacts to your Mailchimp mailing list.

### Features

Create unlimited integrations from Quform forms to Mailchimp.

**Double opt-in**

Send the contact an opt-in confirmation email when they subscribe to your list.

**Merge fields**

Save additional form data about the contact.

**Groups**

Choose the groups to add the contact to.

**Conditional logic**

Create rules to determine whether or not to run the integrations based on the values of form fields.

**Permissions**

Allow other user roles to manage integrations.

**GDPR friendly**

Use conditional logic to only add the contact to Mailchimp if they have given consent.

> Note: this plugin is a free add-on for the [Quform Premium WordPress Form Builder plugin](https://www.quform.com/). Quform version 2.6.0 or later is required for this plugin to function.

== Installation ==

Upload the Quform Mailchimp plugin to your site. Activate it, then configure the Mailchimp integrations at Forms -> Mailchimp on the WordPress menu.

For full instructions please see our [Mailchimp integration guide](https://support.themecatcher.net/quform-wordpress-v2/guides/integration/mailchimp).

== Changelog ==

= 1.3.1 =
* Fixed deprecated warnings on PHP 8.2

= 1.3.0 =
* Added support for icon changes coming in the next Quform version
* Fixed conflict with older versions of ACF Pro

= 1.2.0 =
* Added a Tags option to apply tags to contacts

= 1.1.0 =
* Added the {uniqid} variable to the insert variable menu
* Fixed only 10 merge fields returned
* Fixed padding on select menus
* Fixed logic rule field size
* Fixed jQuery Migrate warning
* Fixed icon classes when using Quform 2.13.0+

= 1.0.4 =
* Fixed re-subscribing for contacts who have unsubscribed
* Fixed searching integrations not working for the integration name
* Fixed a conflict with WPML

= 1.0.3 =
* Added missing arguments to the integration hooks
* Fixed the "Mailchimp" menu item not appearing after activating the plugin
* Fixed the loading of translations

= 1.0.2 =
* Changed the API method for adding contacts to PUT so that existing contacts can be updated
* Fixed the insert variable menu to only show element variables for the currently selected form

= 1.0.1 =
* Fixed only showing a maximum of 10 available lists

= 1.0.0 =
* Initial release
