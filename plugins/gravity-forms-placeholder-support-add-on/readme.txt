=== Gravity Forms Placeholder Add-On ===
Contributors: Able Engine, phpmypython
Tags: gravity forms, placeholder
Requires at least: 3.6
Tested up to: 3.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds Placeholder support to gravity forms

== Description ==
**NOTE**
The plugin is based off of the placeholder support add-on initially developed by neojp and can be found at the link below. The main
difference being that we added support for drop-down fields.
https://wordpress.org/plugins/gravity-forms-placeholders/



This plugin gives support for placeholders to the Gravity Forms Wordpress Plugin.


In order to use the plugin you need only add the class of \"gf-add-placeholder\" to any of the fields you wish to have a placeholder on. 


The plugin will take the text of the label and then hide said label and apply the text from the label to the placeholder attribute.


If you would like to to use this on your entire form then you need only add the \"gf-add-placeholder\" class to the form itself and then it will be applied to all the form elements.




DROPDOWN FIELDS


As im sure you know, the select field in HTML does not have a native placeholder attribute. Now you can do what some do and just add an option that says \"please select\" but that is not (in my opinion) the best way. 

This plugin does the following to give a placeholder LIKE support to the select fields


It takes and hides the label just like it does with the other fields but then it takes the text from that label and prepends an option to the select field that contains the text from the label as it\'s value and it also adds a selected and disabled attribute to the option. Ensuring that it is selected and also ensuring that once a user clicks into the field that they wont be able to select and submit with that option.





In the future i fully intend on adding features that will allow the hiding of labels to be optional and the feature for you to be able to manually set your placeholder text as opposed to it just pulling in the label text.


The plugin requires Javascript and Jquery to function and is tested up to Wordpress version 3.8.1 and Gravity Forms Version 1.8 

ENJOY! and happy coding :)

== Installation ==
To install upload the folder placeholder-for-gravity-forms   to the wp-content/plugins folder then login to your dashboard and activate the plugin.

== Changelog ==
1.1.0
Moved the call for the jquery to the footer
Binded the call to the placeholder function to the gform_post_render so that it will reinitialize on form errors.

== Upgrade Notice ==
This upgrade provides a few stability improvements.
