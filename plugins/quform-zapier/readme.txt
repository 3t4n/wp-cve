=== Quform Zapier ===
Contributors: ThemeCatcher
Donate link: https://www.themecatcher.net/#buy-us-a-coffee
Tags: zapier, form builder, forms, quform
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 1.1.1
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily integrate Zapier with Quform forms.

== Description ==

Integrates with any of your Quform forms to automatically trigger Zaps when forms are submitted.

### Features

Create unlimited integrations from Quform forms to Zapier.

**Conditional logic**

Create rules to determine whether or not to run the integrations based on the values of form fields.

**Additional fields**

Save extra data in addition to the submitted form data.

**Permissions**

Allow other user roles to manage integrations.

**GDPR friendly**

Use conditional logic to only run the integration if the user has given consent.

> Note: this plugin is a free add-on for the [Quform Premium WordPress Form Builder plugin](https://www.quform.com/). Quform version 2.6.0 or later is required for this plugin to function.

== Installation ==

Upload the Quform Zapier plugin to your site. Activate it, then configure the Zapier integrations at Forms -> Zapier on the WordPress menu.

For full instructions please see our [Zapier integration guide](https://support.themecatcher.net/quform-wordpress-v2/guides/integration/zapier).

== Changelog ==

= 1.1.1 =
* Fixed deprecated warnings on PHP 8.2

= 1.1.0 =
* Added support for icon changes coming in the next Quform version

= 1.0.3 =
* Fixed logic rule field size
* Fixed jQuery Migrate warning
* Fixed icon classes when using Quform 2.13.0+

= 1.0.2 =
* Added a filter hook on the form processor hook
* Added a filter hook on the form processor result after the response from Zapier

= 1.0.1 =
* Fixed a conflict with WPML

= 1.0.0 =
* Initial release
