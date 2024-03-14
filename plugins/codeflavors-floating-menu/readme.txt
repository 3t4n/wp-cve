=== CodeFlavors floating menu ===
Contributors: codeflavors, constantin.boiangiu
Tags: menu, WordPress menu, animation, drop down menu, floating menu
Requires at least: 3.4
Tested up to: 4.7
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create animated navigation menus on the left or right side of any WordPress blog.

== Description ==

**CodeFlavors floating menu** creates an animated multidimensional menu on the left or right side of a WordPress blog. Menu can be assigned directly from WordPress Menus.

**Available options**

* menu animation on page scroll; can be fixed, no animation, or can smoothly scroll up and down when scrolling window;
* menu position (left or right side of your blog);
* top distance - minimum distance from top of the window;
* menu title - if left not empty, the first item from the menu will be the text entered into this option.

**Important links:**

* [Documentation](http://www.codeflavors.com/documents/floating-menu/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=cf-floating-menu-readme "CodeFlavors Floating Menu for WordPress documentation") on plugin usage and CSS structure;
* [Forum](http://www.codeflavors.com/codeflavors-forums/forum/codeflavors-floating-menu-plugin-for-wordpress/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=cf-floating-menu-readme "CodeFlavors Floating Menu forum") (while we try to keep up with the forums here, please post any requests on our forums for a faster response);

== Installation ==

Like any other plugin, it can be installed manually or directly from WordPress installation Plugins page. 

Once activated, under Appearance, a new entry will be created called **CodeFlavors Menu** with the available settings.

To display a menu, from Appearance->Menus, select a menu to be placed into **CodeFlavors floating menu** menu position. 

You may also view the [documentation](http://www.codeflavors.com/documents/floating-menu/?utm_source=wordpressorg&utm_medium=readme&utm_campaign=cf-floating-menu-readme "CodeFlavors Floating Menu for WordPress documentation") on plugin usage and CSS structure.

That's  all, enjoy.  

== Screenshots ==

1. Plugin options
2. Menu assignment
3. Front-end display

== Changelog ==

= 1.1.5 - April 25th 2016 = 
* Floating menu script stopps without errors if script parameters variable is not defined.

= 1.1.4 - January 20th 2016 =
* New filter "cfm_show_menu" that can be used to prevent the menu from being displayed (callback function should return false).
* Removed menu CSS styling that was preventing the menu from being displayed on scrrens < 960px wide 

= 1.1.3 - October 8th 2015 =
* New option to keep menu into the same position within the page.
* Added translation file
* Translated to Romanian (ro_RO)

= 1.1.2 - March 18th 2013 =
* Solved IE9 menu animation bug (menu covered whole page).

= 1.1.1 - December 12th 2012 =
* Solves a problem regarding the use of wp_is_mobile() function within the plugin that issues a fatal error on some installations.

= 1.1 - November 23rd 2012 =
* Additional option to hide/show menu title
* Option to hide menu on mobile devices
* Added class "has-children" on elements having submenus and styles

= 1.0.1 - October 22nd 2012 =
* Solved Chrome/Safari bug on menu animation

= 1.0 - August 27th 2012 =
* Initial release

== Troubleshooting ==

Plugin was tested using WordPress 3.4.1 with theme TwentyEleven in FireFox, Chrome and IE8. Other themes may not be CSS compatible with the plugin. If it's your case, please post on [CodeFlavors forums](http://www.codeflavors.com/codeflavors-forums/ "CodeFlavors Community Forums") the theme you're using, WordPress version and browser used to view the website. 