=== ACF: User Role Selector ===
Contributors: danielpataki
Requires at least: 3.4
Tested up to: 4.2
Stable tag: trunk
Tags: acf, custom fields
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A field for Advanced Custom Fields which allows you to select one or more user roles

== Description ==

This plugin helps you out if you need to have the ability to select one or more user roles. Roles can be chosen with a select element (single or multiple choice), checkboxes or radio buttons. Roles can be returned as a role name or a role object.

You also have the option of specifying allowed roles. This lets you control the roles users are allowed to select.

= ACF Compatibility =

This ACF field type is compatible with both *ACF 4* and *ACF 5*.

= Thanks =

* [Advanced Custom Fields](http://www.advancedcustomfields.com/) for the awesome base plugin.
* [Umar Irshad](https://www.iconfinder.com/Umar) for the field icon.


== Installation ==

= Automatic Installation =

Installing this plugin automatically is the easiest option. You can install the plugin automatically by going to the plugins section in WordPress and clicking Add New. Type "ACF Role Selector" in the search bar and install the plugin by clicking the Install Now button.

= Manual Installation =

1. Copy the `acf-role_selector-field` folder into your `wp-content/plugins` folder
2. Activate the Role Selector plugin via the plugins admin page
3. Create a new field via ACF and select the Role Selector type
4. Please refer to the description for more info regarding the field type settings

== Changelog ==

= 3.0.2 (2015-04-27) =
* Fixed an error displayed in ACF 4 when returning roles as object

= 3.0.1 (2015-04-21) =
* Fixed a typo which prevented checkbox fields from saving

= 3.0.0 (2015-04-21) =
* Updated for WordPress 4.2
* Complete rewrite of code documentation
* Fixed a bug where an empty checkbox set would not save properly
* Added hook to manipulate roles shown
* Added Hungarian translation

= 2.0.1 =
* Updated for WordPress 4.1

= 2.0 =
* Added ACF 5 Support
* Removed ACF 3 Support
* Fixed an undefined variable error

= 1.0 =
* Initial Release.
