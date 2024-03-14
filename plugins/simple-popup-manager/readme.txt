=== Simple Popup Manager ===
Contributors: mbcreation
Tags: plugin, popup, lightbox, message, jquery, cookie
Requires at least: 3.3
Tested up to: 4.3.1
Stable tag: 1.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple Popup Manager allows you to easily popup and display a simple message box on your WordPress front page.

== Description ==

Popup a message on your WordPress blog is now very easy.

If you want to promote your brand new newsletter functionnality, display advertising on your homepage or get more Facebook fans you can use Simple Popup Manager to popup a message all over your content in a lovely "lightbox effect". You can customize your message using a WP editor and of course specify the width and the height of the message box. You can limit the display of the message on either session or days basis.

* Choose wether the popup will be displayed only on homepage or on every page
* Set size, width and threshold of the popup (you can disable mobile)
* Customize background-color and opacity
* Popup content is just a normal WordPress editor (you can use shortcode)

== Installation ==

1. Upload `simple-popup-manager` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Popup Parameters" to configure and activate your popup message

== Frequently Asked Questions ==

= What does the plugin actually do ?  =

The plugin allows you to configure an html message like an add or a facebook like box or whatever you want to promote on your site and displays it in a lovely "lightbox effect".

= Is the plugin currently available in other languages ?  =

The plugin is available in english and french.

== Screenshots ==

1. BackEnd view where you need to setup the message.
2. FrontEnd view where visitors see the message on your homepage (the final look depends on your own theme stylesheet). 

== Changelog ==

= 1.3.5 =

* Cleanup

= 1.3.4 =

* Removed some html5 form constraint
* Add optionnal CSS reset (*{margin:0;padding:0;}) inside the popup (ie. for iframe embed)

= 1.3.3 =

* Home page issue

= 1.3.2 =

* Bug fix

= 1.3.1 =
* Add is_admin check to load only necessary files.
* Add settings page shortcut from plugins listing page

= 1.3 =
* Add threshold option : to set a minimum screensize where the popup will be shown
* Add context option : wheiter to display popup on home page or every page
* Add background-color option
* Remove deprecated jQuery live instruction
* English is now the default language


= 1.2 =
* Add debug mode : only logged in admins can see the popup message

= 1.1 =
* Add touchstart event binding for mobile devices
* Add optionnal close button icon
* Add option to disable default behaviour when user click outside de popup 

= 1.0 =
* Initial release


== Upgrade Notice ==

N/A