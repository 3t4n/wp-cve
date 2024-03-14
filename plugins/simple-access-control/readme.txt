=== Simple Access Control ===
Contributors: pkwooster
Donate link: http://devondev.com/wordpress/
Tags: post, page, menu, security
Requires at least: 3.0
Tested up to: 4.9
Stable tag: 1.6.0

A very simple plugin that hides specific pages, posts and menu items from users based on their logged in staus.

== Description ==

This plugin allows authors to restrict the users's access to individual pages, posts and menu items based on the user's logged in status. 

*Features for Authors*

* You can restrict pages and posts while editing by using the "Simple Access Control" widget.
* You can set the message displayed when a visitor accesses a locked page using the "Simple Access Control" item in the Settings menu
* The locked status is shown in the admin page and post lists
* a "Loggedin Text" widget is available that only displays text to logged in users
* You can set an option to display the menu even when filtered
* You can set an option to force a 404 Not Found error on direct access to a restricted page

*Features seen by users*

* Locked pages and posts are not displayed
* Locked items are removed from standard and custom menus
* A message asking you to log in is displayed if you access it directly using its address or using the next and previous links
* A 404 Not Found error may be displayed on access to a restricted page
* A login/logout link is displayed in the mesage
* Loggedin Text widgets are not visible
* Pages and posts can be hidden from either logged in or not logged in users 

*Additional Features*

There are no additional features supported by Simple Access Control.  The code is simple, small and well documented, 
so you can use it as a starting point for your own access control plugin.

== Installation ==

1. Use the Plugins, Add New menu in WordPress to install the plugin or upload the `simple-access-control` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==
= 1.6.0 =
* fix fatal error extending WP_Widget, now extends WP_Widget_Text
* add option to give a 404 Not Found error on direct access to a restricted page or post

= 1.5.1 =
* set version number

= 1.5 =
* tested support for WordPress 4.7.5 and HTTPS
* updated the readme, no changes to code

= 1.4 =
* tested support for new admin in WordPress 3.8

= 1.3 =
* add control of display in menus on page basis
* add login/logout links to locked displays

= 1.2 =
* add restricting access to users who are logged in
* fix bugs with restricted posts and next/previous links

= 1.1 =
* add Loggedin Text widget

= 1.0.1 =
* Replace deprecated use of WP_User->id with WP_User->ID 

= 1.0 =
* First release.

== Upgrade Notice ==

= 1.3 =
* get support for new features

= 1.2 =
* get support for pages shown only to users who are not logged in

= 1.1 =
* get new Loggedin Text widget

= 1.0.1 =
* removes deprecation warnings 
