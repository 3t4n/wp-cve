=== Dropdown Content ===
Contributors: metaloha
Tags: dropdown, drop-down, select, shortcode
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 1.0.2
License: MIT
License URI: https://opensource.org/licenses/MIT

Allows different content to be displayed based on the value of an author-defined drop-down box.

== Description ==

Use a shortcode to define a drop-down form field, and shortcodes to define blocks of content that will be displayed when a specific entry is selected.

== Installation ==

The plugin is simple to install:

1. Download the plugin, it will arrive as a zip file
1. Unzip it
1. Upload `dropdown-content.zip` directory to your WordPress Plugin directory
1. Go to the plugin management page and enable the plugin

== Frequently Asked Questions ==

= What is actually happening? =

There is a tiny bit of Javascript that watches these generated dropdowns for the `change` event, and simply adds the class `dropdowncontent-content-selected` to the content block that has the same value as the selected option.

= How do I override your CSS? =

Only three classes are used: `dropdowncontent-dropdown` for the dropdown control itself and has no default styling, `dropdowncontent-content` which has a single rule (`display:none;`) for content blocks, and the `dropdowncontent-content-selected` class which has a single rule (`display:inherit;`) for selected content blocks. Feel free to override those styles however you like in your own stylesheets.

= Can I style the dropdown box itself? =

Absolutely! It is a normal `SELECT` box and can be modified by any CSS or Javascript you like. You can target the default `dropdowncontent-dropdown` class, any custom class you add to the shortcode, or the field name (which defaults to `dropdown-content` but can be modified by the shortcode).

= What kind of content can be in the content blocks? =

Anything! By default, though, since the blocks are given `display:none;` as their default rule, things like maps or other Javascript targets that require a visible container may not work quite as expected. To account for that, there are two custom jQuery events attached to elements with the `dropdowncontent-content` class and will fire in this order:

1. `dropdown-content:unselect` will fire when a dropdown option is unselected and `$(this)` will refer to the previously selected content block; the previous `value` and `name` fields can be found with `$(this).attr('data-dropdowncontent-value')` and `$(this).attr('data-dropdowncontent-name')`
2. `dropdown-content:select` will fire when a dropdown option is selected, and `$(this)` will refer to the newly selected content block; the current `value` and `name` fields can be found with `$(this).attr('data-dropdowncontent-value')` and `$(this).attr('data-dropdowncontent-name')`

== Screenshots ==

1. The `Test 3` option is currently selected
2. Now the `Test 2` option has been selected and the content below the dropdown has changed

== Usage ==

To add a content dropdown to a post, you can either select the Content Dropdown icon from the TinyMCE editor, or enter the shortcodes manually.

`[dropdown name="controlName" class="customClassName"]` - both `name` and `class` are optional; `name` will default to `dropdown-content`

* the `name` is used to identify which content sections are targetted by this dropdown; if there is only one dropdown on a page, it is optional; if there are multiple dropdowns on a page, each will need a unique name
* `class` can be a space-separated list of class names, or just a single class name
* only `[dropdown-option]` shortcodes may be inside this shortcode

`[dropdown-option value="someValue"]` - the `value` field is technically optional, but is required if this option is meant to make a content block visible

* must be inside a `[dropdown]` shortcode

`[dropdown-content name="controlName" value="someValue"]` - if the `name` matches a dropdown name, and the `value` matches the value of an option within that dropdown, then this content will be displayed when that specific option is selected.

* again, `name` is technically optional, but must match a `[dropdown]` name (also defaults to `dropdown-content`)
* `value` is required, and must match a `value` for a `[dropdown-option]`
* can be anywhere on the page

== Example ==

*Copy and paste the following content to see the plugin in action*

[dropdown]
[dropdown-option]Select one...[/dropdown-option]
[dropdown-option value="option1"]First option[/dropdown-option]
[dropdown-option value="option2"]Second Option[/dropdown-option]
[/dropdown]

[dropdown-content value="option1"]Content for the first option.[/dropdown-content]

[dropdown-content value="option2"]Content for the second option.[/dropdown-content]

Those were all using the default values from the plugin. Let's change it up a bit.

[dropdown]
[dropdown-option value="option1"]The First[/dropdown-option]
[dropdown-option value="option2" default=true]The Second[/dropdown-option]
[dropdown-option value="option3"]The Third[/dropdown-option]
[/dropdown]

You'll notice below that the second option will be displayed by default for you.

[dropdown-content value="option1"]Some different content for the second first option.[/dropdown-content]

[dropdown-content value="option2"]Some different content for the second second option.[/dropdown-content]

[dropdown-content value="option3"]Some different content for the second third option.[/dropdown-content]

== Changelog ==

= v1.0.2 2016/09/23 =

* fixed content block custom event delegation

= v1.0.1 2016/09/23 =

* updated documentation
* added screenshots
* added FAQ entries
* added custom jQuery events to Javascript
* now uses delegated listener in case dropdowns are hidden on page load or loaded via AJAX
* removed references to "visible" in code and documentation, replaced with "select" instead

= v1.0.0 2016/09/22 =

* First public release
