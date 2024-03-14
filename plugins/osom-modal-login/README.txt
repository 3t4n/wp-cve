=== Osom Modal Login ===
Contributors: osompress, esther_sola, nahuai, davidperalvarez
Donate link: https://osompress.com
Tags:  Login, modal, logout, login form, custom login, wordpress login, login popup, popup login form, modal popup login, login popup modal, registration, lost password
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.4.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 

== Description ==

Osom Modal Login lets you easily create a modal box (pop-up) displaying the WordPress login form. In block themes, Osom Modal Login uses the native WordPress login/out block, so you can introduce this login/out block on the header (or footer) navigation block and it will display a modal box with the login form when clicked. On the other hand, in classic themes it automatically adds a menu item named "Login", which you can customize, at the end of the selected menu(s). Once you click on it, it will also launch the login modal box. 

Alternatively, you can also use the included shortcode or the native login/out block to add the modal login box in any place of the web.

= Features =

With Osom Modal Login you can customize several parameters in the options page.
Both in classic and block themes:
1. Customize "Login" text.
2. Customize "Logout" text.
3. Set login and logout URL redirection. 
4. Display/hide "Remember me" checkbox.
5. Display/hide "Did you forget your password" link.
6. Display/hide Register link.
7. Set Register text link (if displayed).
Only in classic themes:
8. Set the title of the modal box.
9. Select the navigation menu where you want to add login/out item. You can choose more than one or the option 'none' if you don't want to add it in any menu location.

You can also use the built-in shortcode to add the modal box any where in your website or use the WordPress login/out native block with the setting "Display login as form" unselected.

= Shortcode usage (optional) =

You just need to enclose your custom text in [osom-login] shortcode.
For example: [osom-login] Custom text [/osom-login]

= Quick Setup Videos =

[In classic themes](https://youtu.be/0LJGYnq6G3o):

https://youtu.be/0LJGYnq6G3o

[In block themes](https://youtu.be/y0GOzfNsptI):

https://youtu.be/y0GOzfNsptI

If you want more info about the setup and configuration you can check the tutorials below.

= Tutorials =

* [How to add button styles to Login/out Block in a WordPress Block Theme](https://osompress.com/add-button-styles-login-out-block-wordpress-block-theme/)
* [Add a login modal/pop-up window to a WordPress block theme](https://osompress.com/add-login-modal-pop-up-window-wordpress-block-theme/)
* [How to add SVG icons to Osom Modal Login login/logout items](https://osompress.com/add-svg-icons-osom-modal-login-logout-items/)
* [How to add a login popup modal in WordPress classic themes](https://osompress.com/show-the-login-form-in-modal-window/)

= Dev Features =
* The plugin uses Vanilla JavaScript so you can use it even if you dequeue WordPress jQuery. It's always nice to keep the dependencies to the minimum.

== Installation ==

This plugin can be installed directly from your site.

1. Log in and navigate to Plugins &rarr; Add New.
2. Type "Osom Modal Login" into the Search and hit Enter.
3. Locate the Osom Modal Login plugin in the list of search results and click **Install Now**.
4. Once installed, click the Activate link.
5. Now you have the new plugin available on WordPress.

It can also be installed manually.

1. Download the Osom Modal Login plugin from WordPress.org.
2. Unzip the package and move to your plugins directory.
3. Log into WordPress and navigate to the Plugins screen.
4. Locate Osom Modal Login in the list and click the *Activate* link.
5. Now you have the new plugin available on WordPress.


== Frequently Asked Questions ==

= Can I use Osom Modal Login with any theme? =

Yes, you can use Osom Modal Login with any theme, including a block theme.

= Where can I modify Osom Modal Login settings? =

You can find the settings page on WordPress left sidebar under OsomPress > Osom Modal Login. 

= Can I use Osom Modal Login in other locations apart from the menus? =

Yes, you can add a login modal window anywhere on the website using the shortcode [osom-login] Custom text [/osom-login].
Alternatively, you can also use the native login/out block.

= Can I change the Login/Logout text? =

Yes, you can do it.

= Can I use Osom Modal Login on WordPress Multisite?  =

Yes, you can. Take into account that if you set the login or logout URL you will have to use an absolute URL, ie, https://yoursite.com/redirect-page. If you use a relative ULR, such as /redirect-page/, it will point to the the main site URL (of the network).

= Is Osom Modal Login compatible with WordPress Multilingual plugin?  =

Yes, it is. 

= Will Osom Modal Login work on header/footers created with Elementor?  =

No at the moment. We will explore to add support on future updates.

== Screenshots == 
 
1. Dashboard plugin view in a classic theme
2. Front-end modal window in a classic theme
3. Dashboard plugin view in a block theme

== Changelog ==

= 1.4.1 =
* Revert conditional loading of styles and scripts due to some issues on edge cases.
= 1.4 =
* Improve conditional loading of styles and scripts. On block themes it will only load the style and script files if the login/out block is present.
= 1.3.1 =
* Improve modal behaviour on block themes.
* CSS tweaks.
= 1.3 =
* Added new options for block themes. Now you can change the text for the login and logout and also set the URL redirections after login and logout.
= 1.2 =
* Added compatibility with block themes. Now you can use the native login/out block, when the user click on it it will display a modal window with the login form.
= 1.1.5 =
* Small fixes and tested up to WordPress 6.4.
= 1.1.4 =
* Fix new PHP warning (in PHP 8 or superior).
= 1.1.3 =
* Set empty login fields as login failed.
= 1.1.2 =
* Remove Dashicons dependencie.
= 1.1.1 =
* Tested on WordPress 6.0.
* Fix new PHP notice (in PHP 8 or superior).
= 1.1 =
* Tested on WordPress 5.9.
* Fix PHP notices.
= 1.0.8 =
* Improve shortcode perfomance.
* Improve performance for multiple locations.
= 1.0.7 =
* Add option to display register link.
= 1.0.6 =
* Add option to select multiple menu location.
= 1.0.5 =
* Add redirection to the plugin settings page when activated.
* Tested on WordPress Multisite.
* Tested with WordPress Multilingual.
= 1.0.4 =
* Add labels for login/logut menu item
* Removal of the jQuery used and replaced with Vanilla JavaScript
* Add settings link in plugins page
= 1.0.3 =
* Fix warnings
= 1.0.2 =
* Translation improvements
= 1.0 =
* Initial release. 
