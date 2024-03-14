=== A5 Custom Login Page ===

Contributors: tepelstreel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=32XGSBKTQNNHA
Tags: custom login, brand login, customize, brand, login, login page, own look, multisite, foolproof, login redirect, login widget, own css, additional html, inject css 
Requires at least: 3.9
Tested up to: 4.5
Stable tag: 2.8.1

Style your login page differently.

== Description ==

If you are working for a customer or you just want to set a page for a community (band, choir etc.) they might appreciate their own logo at the login screen. With this plugin, you can even go further and style your whole login page according to your needs. At the moment, there is only the way for 'dummies' to style the page, but in a future version you will be able to enter some css code yourself and the final goal is, also to offer the possibility to copy an entire stylesheet into the plugin, to have the most freedom of styling and changing the items on your loginscreen. Since version 1.7 the A5 Custom Login Page is also for a WP networksetup.

Fromon Version 2.2, you can redirect different user roles to different pages after login. There is now also a custom login widget that you can style according to your needs and display it on your blog. Or only in the categories or posts. The widget is very flexible and you can show it on your whole site or only on post pages or only the frontpage etc.

Fromon Version 2.7 there's a shortcode [a5-login-form], to get your login form wherever you want.

== Installation ==

1. Upload the `custom-login-page` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Settings' and style your login page

== Frequently Asked Questions ==

= The login page doesn't change, no matter what i do. =

The CSS style sheet is a virtual one and the plugin adds a rewrite rule to refer to it. In some environments that's not working and thus the styles are not readable.

In the case your login screen is not changing after saving the settings, go to the debug section of the settings page and choose inline display of the styles. That should make your own design available.

= How do I place the login form to a page? =

Simply type `[a5-login-form]`, where ever you want the form to appear. You can style the login form and it's wrapping container in the short code settings section.

== Screenshots ==

1. The options page of the plugin
2. Just logged out with our style on our [blog](http://blog.atelier-fuenf.de)
3. Tried to login with a wrong password on our [blog](http://blog.atelier-fuenf.de)
4. Not the most pretty, but a possibility

== Changelog ==

= 2.8.1 =

* Bug that caused white screen fixed

= 2.8 =

* Framework updated
* CSS is now cached

= 2.7 =

* Put a login form to whatever page or post with the shortcode.

= 2.6.4 =

* Possibility to enter custom messages for the lost password and the registration page

= 2.6.3 =

* bugfix for latest patch

= 2.6.2 =

* Hopefully bugfix for missing stylesheet

= 2.6.1 =

* Finally found and fixed a bug in the import section

= 2.6 =

* Serbo-Croatian translation added thanks to [Andrijana Nikolic](http://webhostinggeeks.com/)
* WP 4.3 ready
* new framework

= 2.5.4 =

* small bugfix

= 2.5.3 =

* small bugfix

= 2.5.2 =

* broken registration in some environments fixed

= 2.5.1 =

* some bugs fixed
* individual intances of the widget can now be redirected

= 2.5 =

* you can now add a video to the login screen

= 2.4.2 =

* small bug in CSS fixed

= 2.4.1 =

* small bug in CSS fixed

= 2.4 =

* Uploader added
* More accurate redirection
* Possibility to hide backend from users
* Inner shadow in foolproof mood
* You can now import the plugin's stylesheet and start fine tuning

= 2.3 =

* Framework updated

= 2.2.2 =

* Some more cleaning and fixing

= 2.2.1 =

* Fixed a huge bug due to missing function in some environments

= 2.2 =

* Preview added (doesn't work in network installations)
* Using own CSS doesn't override other settings anymore
* Login redirect added
* Login Widget added
* Possibility to add custom html snippets added

= 2.1.1 =

* Bug with double logo eliminated

= 2.1 =

* Colorpicker works again
* DSS compressable

= 2.0 =

* Complete overhaul of the whole plugin with more functionalty

= 1.9.2 =

* Debug option added

= 1.9.1 =

* More options for the logo added

= 1.9 =

* More options for the logo added

= 1.8.1 =

* Slovak translation updated

= 1.8 =

* More options for the logo added
* Import / export settings added

= 1.7.3 =

* Slovak translation added thanks to [Branco Radenovich](http://webhostinggeeks.com/blog/)

= 1.7.2 =

* Small bugfix for multisite use

= 1.7.1 =

* Small bugfix

= 1.7 =

* Fixed a bug with login form transparency
* Added multisite functionality

= 1.6 =

* Fixed a bug preventing the option page from showing
* Added a couple of options to make the login form and the message containers disappear without loosing the actual input fields and texts

= 1.5.2 =

* Missing stylesheet fixed

= 1.5.1 =

* Better Spanish translation and no flashing WP-logo

= 1.5 =

* Login container and login form can now be resized and positioned; size of the logo can be changed

= 1.4.1 =

* Spanish translation added thanks to [Jorge Ballesteros](http://profiles.wordpress.org/jballesterosruiz/)

= 1.4 =

* You can now enter a custom error message

= 1.3 =

* Small bugfix that caused issues in WP 3.4

= 1.2 =

* A bit more foolproof

= 1.1 =

* Some bugfixes, you can style login container now

= 1.0 =

* initial release

== Upgrade Notice ==

= 1.1 =

Some bugfixes and you can style login container now

= 1.2 =

A bit more foolproof

= 1.3 =

Small bugfix that caused issues in WP 3.4


= 1.4 =

You can now enter a custom error message

= 1.4.1 =

Spanish translation added

= 1.5 =

Login container and login form can now be resized and positioned; size of the logo can be changed

= 1.5.1 =

Better Spanish translation and no flashing WP-logo

= 1.5.2 =

Missing stylesheet fixed

= 1.6 =

Bugfix and new functionality

= 1.7 =

Fixed a bug with login form transparency; added a multisite functionality

=1.7.1 =

small bugfix

= 1.7.2 =

Small bugfix for multisite use

= 1.7.3 =

Slovak translation added

= 1.8 =

More options for the logo added

= 1.8.1 =

Slovak translation updated

= 1.9 =

More options for the logo added

= 1.9.1 =

More options for the logo added

= 1.9.2 =

Debug option added

= 2.0 =

Complete overhaul of the whole plugin with more functionalty

= 2.1 =

Colorpicker works again; DSS compressable

= 2.1.1 =

Bug with double logo eliminated

= 2.2 =

Redirect added; More options and better CSS added; Login Widget added

= 2.2.1 =

Fixed a huge bug due to missing function in some environments

= 2.2.2 = 

Other small bug fixed

= 2.3 =

Framework updated

= 2.4 =

Uploader added; more accurate redirection; possibility to hide backend from users; inner shadow in foolproof mood

= 2.4.1 =

small bug in CSS fixed

= 2.4.2 =

small bug in CSS fixed

= 2.5 =

you can add now a video to the login screen

= 2.5.1 =

some bugs fixed; individual intances of the widget can now be redirected

= 2.5.2 =

broken registration in some environments fixed

= 2.5.3 =

small bugfix

= 2.5.4 =

small bugfix

= 2.6 =

Serbo-Croatian translation added; WP 4.3 ready; new framework

= 2.6.1 =

Finally found and fixed a bug in the import section

= 2.6.2 =

Hopefully bugfix for missing stylesheet

= 2.6.3 =

Bugfix for latest patch

= 2.6.4 =

Possibility to enter custom messages for the lost password and the registration page

= 2.7 =

Put a login form to whatever post or page with shortcode

= 2.8 =

Framework updated; CSS is now cached

= 2.8.1 =

Bug that caused white screen fixed