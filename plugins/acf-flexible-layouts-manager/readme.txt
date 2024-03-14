=== ACF Flexible Layouts Manager ===
Contributors: valentinpellegrin, damchtlv, hwk-fr
Donate link: 
Tags: ACF, flexible content, duplicate, advanced custom fields, import, export, duplicate flexible layout, import flexible layout, ACF flexible layouts manager
Requires at least: 4.0
Tested up to: 5.2.2
Stable tag: 1.1.6
Requires PHP: 5.4
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add "Copy", "Duplicate", "Import" & "Paste" options for layout in ACF Flexible Content.

== Description ==

#### UPDATE
*I don't really have time to work actively on this plugin anymore. I'm sorry for the delay on bug fixes.
My friend and mentor @hwk work on the plugin [ACF Extended](https://wordpress.org/plugins/acf-extended/) this plugin is awesome! 
ACFE integrate all features of AFLM and so more! Test this plugin and folow [@hwk on twitter](https://twitter.com/hwkfr) to be aware from all update comming.*

### ACF Flexible Layout Manager description:

This WordPress plugin allows you to easily **manage your layouts** in the **Advanced Custom Fields "Flexible Content"**.

You can **copy/duplicate** any layout & **import** partials/full layouts from post/page.

**Here are the main features:**

* Duplicate one layout.
* Copy the JSON data of one layout.
* Copy the JSON data of all layouts.
* Paste the JSON data copied from other post/page/user/term(s).
* Import layout(s) dynamically from other post/page(s).

When you Paste or Import layout(s), you can choose if you want to add those layouts in your current flexible content or if you want to replace the current layouts of this flexible content.

This plugin add buttons in a "Flexible Content" field and its layouts.

**Features to expect in future:**

* Duplicate button for multiple levels of flexible content
* Autocomplete search & pagination in import modal
* Import from Terms/Users
* CodeMirror implementation in paste modal

#### Notice	

This plugin is now compatible with ACF 5.7+ new JS syntax.
This plugin works current only with the first level of flexible content.
This plugin doesn't work with clone of flexible content too.

#### Video

[This video](https://youtu.be/2SpCRCse_g0) shows you the main features of this plugin.

### Requirement

* Advanced Custom Fields Pro 5.3.1 or greater has to be installed and activated.

### Languages

* English
* Français

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/acf-flexible-layouts-manager` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Make sure that Advanced Custom Fields Pro is installed and activated on your website.
1. Add a flexible content field with ACF and you will see our buttons appear.

== Frequently Asked Questions ==

= Why aren't there any buttons in my flexible content? =

This plugin currently works on the first level of flexible content only.
There is currently no support for clone of flexible content.

If you meet the current requirements and it's still doesn't work, check for errors in the javascript console that may be caused by 3rd party library/scripts.
You can submit an issue to report the bug if you think it may help others.


== Screenshots ==

1. Duplicate layout button
2. Copy layout button
3. Main button action "Copy all", "Paste", "Import"
4. Paste layout modal
5. Import layout modal first step "Select Post"
6. Import layout modal second step "Select Layout"

== Changelog ==

= 1.1.6 =
* Fix bug to duplicate layout on the new ACF version

= 1.1.4 =
* Fix bug with '$format_value' on 'get_field'
* Fix bug to copy layout on the new ACF version

= 1.1.3 =
* Fix bug with field WYSIWYG and filter 'acf_the_content'
* Fix bug display button "Copy" and "Import" in field group options

= 1.1.2 =
* Fix bug for people who don't use "acf-json" folder

= 1.1.1 =
* Fix minor bug with old version of ACF

= 1.1.0 =
* Add compatibility with ACF options pages

= 1.0.9 =
* ACF 5.7+ JS fix

= 1.0.8 =
* Fixed JavaScript errors
* Fixed CSS rules
* Improved plugin loading process
* Improved french translation
* Removed unecessary icons

= 1.0.4 =
* Initial commit