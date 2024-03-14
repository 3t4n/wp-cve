=== Flexible Frontend Login ===

Contributors: palatino

Donate link: 

Tags: login, frontend, popup, form, modal, widget

Requires at least: 3.0.1

Tested up to: 3.6

Stable tag: trunk

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html


Easily place a link to a __Login Form Popup__ at any place of your site. Saves a lot of screen property and looks very nice.

== Description ==

Provides shortcode and template tag to place a __link to popup a login form__ at any place you want to.

Features:

* __Modal form with overlay or simple popup form__
* Shows a __login link__ for not logged in users that __pops up a login form on click__ 
* __Multiple instances__ possible: Place login form links to sidebar, header, and footer
*  __Fully customizable__ HTML, CSS and text lines ( but works completely out of the box )
* Reads your __custom CSS__ file from your themes folder if existing
* Provides __shortcodes__ for use in posts and widgets
* __Template tags__ for easy use in template files

Languages so far:

* English
* German
* Nederlands by [Sjoerd Lohuis](http://www.sjoerdlohuis.nl/)
* French by Denis ([Boulli](http://wordpress.org/support/profile/boulli))

__If you like to provide a translation please [drop me a line](http://www.flexibleplugins.com/frontend-login/).__

__See [Other Notes](/extend/plugins/flexible-frontend-login/other_notes/) section__ for further information.

__See [flexibleplugins.com](http://www.flexibleplugins.com/frontend-login/)__ for usage examples.

__See [the experimental page](http://www.flexibleplugins.com/frontend-login/experimental)__ for upcoming features.


== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. __For details on usage see [Other Notes](/extend/plugins/flexible-frontend-login/other_notes/) section__

or:

1. On your plugin's installation page do a search for "Flexible Frontend Login"
1. Click on "Install this plugin" 
1. Click on "Activate this plugin"
1. __For details on usage see [Other Notes](/extend/plugins/flexible-frontend-login/other_notes/) section__


== Screenshots ==

1. Login link whereever you like it to have.
1. Form pops up as overlay as default.
1. Modal form popup. 
1. Options page general.
1. Options page HTML Editor
1. Preview functions 


== Other Notes ==

= How to use the Flexible Frontend Login shortcode on your site =

On the widget settings page choose the Flexible Frontend Login and adjust the settings to your site.

In a post or page place __`[flexible-frontend-login]`__ 

By default the shortcode uses the settings set on the admin options page

You can override those default values with the following parameters and options:

* `vertical=top, bottom`
* `horizontal=left, right`

So your shortcode might look like this:

* __`[flexible-frontend-login vertical=bottom horizontal=left]`__

If you want to use the centered login form with darkened overlay in the background
use __`[flexible-frontend-login-modal]`__ without any parameters.


= How to use the Flexible Frontend Login template tag on your site =

In template files place:

 __`<?php if ( function_exists('flexible_frontend_login') ) flexible_frontend_login( 'bottom', 'left' ); ?>`__

To change popups behavior you can use

* "bottom" or "top"
* "left" or "right"

For popup with overlay use: 

 __`<?php if ( function_exists('flexible_frontend_login_modal') ) flexible_frontend_login_modal(); ?>`__
 

= Live examples =

* For more examples see [plugin's website](http://www.flexibleplugins.com/frontend-login/)

= How to add your own styling =

1. Place a folder `flexible-frontend-login` in your theme folder
1. Copy the files from `/wp-content/plugins/flexible-frontend-login/customization/` into the newly created theme subfolder
1. Adjust the CSS to your theme. You'll find all classes provided by the plugin in that file.
1. If necessary change the HTML markup in the template file.

= To Do =

* Include option to delete options on deactivation
* Add option to display register link and form
* Add admin option for redirection on logout
* Add languages
* Add option for Gravatar display for logged in users
* Validate inside of modal window/current page without jumping to the basic wp-login.php
* redirect to a specific User Role accessible page after a succesful login, add shortcode options for this

== Changelog ==

= 1.0.5 =
* Fixed bug in logged in user display
* Moved HTML and CSS manipulation from Admin Page to templating with custom files in theme folder
* Improved help section

= 1.0.4 =

* Added missing files for Admin Page

= 1.0.3 =

* Added more classes to access all elements easily by CSS
* Added Nederlands
* Added Français

= 1.0.2 =

* Added option to choose how to display username when logged in


= 1.0.1 =

* Update of the Settings page
* fixed localization issue

= 1.0 =

* Updated for WP 3.6
* Fixed popup behavior


= 0. 97.5 =

* Fixed the Markdown fix :-)

= 0.97.4 =

* Fixed Markdown class inclusion ( if class_exists )  

= 0.97.3 =

* Fixed reading stylesheet support order for template and child themes

= 0.97.2 =

* Fixed bug in default value storage 

= 0.97.1 =

* Fixed redirection issue for login/logout links

= 0.97 =

* Added more options for further customization
* Beautified options page (Thanks to Ohad Raz for the awesome [Admin Page Class](http://en.bainternet.info/2012/my-options-panel))
* * Editor for HTML
* * Editor for CSS
* * Extended preview function

= 0.96 = 

* Fixed admin settings page, settings get stored again

= 0.95 =

* Added template tag and shortcode for modal form with overlay
* included a php class FrontendLogin for better handling
* Switched to JQuery instead of inline javascript
* Fixed a few bugs which could cause error notices

= 0.94 =

* fixed shortcode output
* whitelisted settings 
* Added customizable widget
* Added option to pass parameters with the shortcode
* Added option to pass parameters with the template tag

= 0.93 =

* Fixed redirection issue for logout link
* Fixed CSS for Popup to be the highest layer

= 0.92 =

* Fixed an issue to make sure shortcodes get processed in text widgets

= 0.91 =

* Added missing files

= 0.9 =

* Initial release

