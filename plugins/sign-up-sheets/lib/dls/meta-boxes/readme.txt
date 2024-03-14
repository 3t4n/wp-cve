=== Meta Boxes Library ===
Author: DLS Software Studios <www.dlssoftwarestudios.com>
Version: 1.0.0.5


== Installation ==

This library is for use inside WordPress plugins or themes.

It is recommended that you update the main class name from `DLS_Meta_Boxes` to something more specific to your plugin or theme to prevent conflicts if used in multiple places in the same WordPress install.  Ex: `DLSPET_Meta_Boxes`


== Changelog ==

= 1.0.0.5 =
* Fixed multiselect and checkboxes in_array to check in strict mode to prevent values like "0" from causing issues
* Fixed multiselect and checkboxes to allow numeric values by converting to a string when checking is_array now that strict mode is enabled
* Fixed translations to include escape for improved security
* Fixed checkbox label to remove duplicate opening label tag
* Added field 'wrap_class' to allow appending to class attribute string on parent wrapping element
* Fixed bug where textarea field id="" didn't match label for=""
* Fixed issue with custom checkboxes fields not selecting the right box when clicking on the label
* Fixed deprecation notice with PHP 8 due to invalid type of null on str_replace() $subject

= 1.0.0.4 =
* Added 'order' field with sorting
* Added classed on TD and TH of repeater fields to improve selectors
* Fixed contrast of sort icon on tasks in edit sheet grid
* Updated translations to include escape for improved security

= 1.0.0.3 =
* Fixed performance issue by removing check for external jQuery UI file
* Fixed Checkboxes from displaying dropdown as well
* Fixed repeater output from assuming it is saved in separate metafields

= 1.0.0.2 =
* Fixed issue with JS failing when using deprecated .live

= 1.0.0.1 =
* Fixed multi-checkbox fields to use key as meta_value and sanitized dynamic ID
* Fixed empty checkbox fields from saving as NULL

= 1.0 =
* Fixed fields not sent (like checkbox) where value is not reset on field loop
* Fixed issue with repeater fields sometimes not incrementing properly when being added.
* Added ability to add custom actions on repeaters
* Added ability to override the default repeater output
* Added filters: dlsmb_display_meta_field_value and dlsmb_update_post_metadata
* Added 'dropdown' as field type (same as 'select')
* Updated repeater JS logic
* Updated line endings to use platform agnostic PHP_EOL constant

= 0.8 =
* Added JS fix on adding repeaters

= 0.7 =
* Added box_label_override which allows overriding the label on a single checkbox

= 0.6.1 =
* Added Google Map field
