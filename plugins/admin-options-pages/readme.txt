=== Plugin Name ===
Contributors: poolghost
Tags: admin options pages, aop, options, settings, settings pages
Requires at least: 5.3
Tested up to: 6.1
Requires PHP: 5.6.20
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create and edit your own options pages with ease.

== Description ==

Admin Options Pages is a beautifully designed WordPress plugin, which makes it incredibly easy to create options menus, pages and fields.

> If you're a seasoned developer or just a beginner, Admin Options Pages tries to make it easy for everybody.

In a nutshell, you can make your own settings pages and add options fields to it and that without writing one single line of code.

Use the `get_option()` function ([link](https://developer.wordpress.org/reference/functions/get_option/)) to do with your option value (Field name) what you want.

#### Field Types
* Text
* Textarea
* Wysiwyg Editor
* Number
* Checkbox
* Radio
* Select
* Image
* Color Picker

#### Documentation
Visit [docs.adminoptionspages.com](https://docs.adminoptionspages.com) for the documentation.

#### Bug reports or tips and ideas
Bug reports for AOP are welcomed in our issues [repository on Github](https://github.com/poolghost/adminoptionspages-issues).
Tips and ideas are also welcome.

== Installation ==

1. Upload folder `admin-options-pages` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start creating your own options pages.

== Frequently Asked Questions ==

= Is there any documentation? =

Sure. [docs.adminoptionspages.com](https://docs.adminoptionspages.com).

= How can I use/see an option value on my website? =

Al the magic happens with the default WordPress function [get_option()](https://developer.wordpress.org/reference/functions/get_option/).

For example, if you have a text field with the name 'my_text_field' as Field name, you can use get_option('my_text_field') this way.

`<?php echo get_option('my_text_field'); ?>`

Or with a default value.

`<?php echo get_option('my_text_field', 'my default text'); ?>`

= Does this plugin make my website slower? =

No, not at all.
And besides that, this plugin does nothing on the frontend of your website.

== Changelog ==

= 0.9.7 =
*Release Date - 5 July 2021*

Bugfixes:

* The Menu page title does now support non-ASCII characters. See issue on [Github](https://github.com/poolghost/adminoptionspages-issues/issues/4).

= 0.9.5 =
*Release Date - 5 May 2021*

Bugfixes:

* 0 (zero) is saved correctly now (Number field).

= 0.9.4 =
*Release Date - 13 April 2021*

Enhancement/bugfix:

* The wpautop() function is added to the wysiwyg editor.

= 0.9.3 =
*Release Date - 11 March 2021*

Enhancements:

* The wysiwyg editor is added.
* Now PHP 8 ready.
* Small styling tweaks.

Bugfixes:

* Options fields modal is fixed for Safari.

= 0.9.2 =
*Release Date - 10 September 2020*

Bugfixes:

* Error 'Undefined index: DOCUMENT_URI' is fixed.

= 0.9.1 =
*Release Date - 10 August 2020*

Admin Options Pages 0.9.1 has one big update. You can give editors now access to options pages.

Enhancements:

* Adds the ability to choose between Administrator or Editor access for each individual page.
* Dashicons are updated. See ([New dashicons in WordPress 5.5](https://make.wordpress.org/core/2020/07/15/dashicons-in-wordpress-5-5-the-final-update/)).
* PHP sessions are not in use anymore.

= 0.9.0 =
*Release Date - 5 June 2020*

Admin Options Pages 0.9.0 is a massive release. The plugin pages are completely redesigned and are more in line with Gutenberg.

Enhancements:

* The pages for creating and Editing options pages are now more in line with Gutenberg.
* A new option field: Select.
* Dashicons are updated. See ([Dashicons in WordPress 5.2](https://make.wordpress.org/core/2019/04/11/dashicons-in-wordpress-5-2/)).
* It is now possible for pages in existing menus to set a custom position. See [developer.wordpress.org/reference/functions/add_submenu_page/](https://developer.wordpress.org/reference/functions/add_submenu_page/).

= 0.7.0 =
*Release Date - 7 November 2019*

Enhancements:

* Adds a new "edit page" button on each option page at the right top corner. You can disable this function on the new settings page.
* Adds a "visit page" button on the edit page for quick entering the options pages.
* Adds an "autoload" toggle for each option.
* Textarea's accepting now more HTML tags when sanitizing. (a, abbr, b, br, em, s, strike, strong, pre)
* Adds some small design changes.

Bugfixes:

* Fixes the error in options.php when in dev mode.
* Fixes a bug when toggle the menutype.

= 0.0.8 =
*Release Date: 28 March 2019*

Bugfixes:

* Fixes the autoload *case sensitive* issue.
