=== Auto Hide Admin Bar ===
Contributors: mbootsman
Tags: admin bar, autohide, hide, toolbar
Requires at least: 3.1
Tested up to: 6.4.3
Stable tag: 1.6.3

This plugin adds an auto-hide feature to the WordPress Admin Bar or Toolbar.

== Description ==

Auto Hide Admin Bar makes the WordPress Toolbar disappear - and reappear when hovering the mouse pointer over the top of the browser window.
You end up with a clean view of your site, and keep having access to the WordPress Toolbar.
If you have any comments or questions, please use the [support forum](http://wordpress.org/support/plugin/auto-hide-admin-bar).

== Installation ==

1. Upload `auto-hide-admin-bar` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. We're all done, now you have an auto hiding Toolbar.

== Frequently Asked Questions ==

= Can this plugin be translated in my own language? =
Sure, just go [here](https://translate.wordpress.org/projects/wp-plugins/auto-hide-admin-bar) and translate this plugin in your own language.

== Screenshots ==
How do we make screenshots of things that are hidden? :)

== Changelog ==
= 1.6.3 =
* Removed donate link
* Removed @link lines in function headers

= 1.6.2 =
* Fixed Cross Site Scripting (XSS) Vulnerability. Props: darius.sveikauskas @ patchstack.com

= 1.6.1 =
* Fixed SVN commit gone wrong... Sorry.

= 1.6 =
* Added toggle switch in the admin bar to (temporarily) enable/disable auto-hiding of the admin bar without going to the settings page.
* Fixed links in readme.txt.
* Added GitHub repository link to readme.txt.

= 1.5 =
* Changed behavior of arrow. It now needs to be clicked to show/hide the admin bar, and has been given a little design love.

= 1.4.4 =
* Removed console log lines.

= 1.4.3 =
* Updated donate link.

= 1.4.2 =
* Fixed issue with PHP 7.4.
* Removed Admin settings, since they were not used.

= 1.4.1 =
* Fixed checking for existing options values. [Props: hanfox](https://wordpress.org/support/topic/updating-to-v1-4-throws-errors-due-to-new-empty-options/).

= 1.4 =
* Added option to show an arrow when the toolbar is hidden. [Link to suggestion](https://wordpress.org/support/topic/show-small-arrow-when-hidden/).

= 1.3.1 =
* Fixed SSL mixed content issue. Props: @steveec-1, [topic](https://wordpress.org/support/topic/ssl-mixed-content-9/).

= 1.3 =
* Fixed issue when using Beaver Builder 2.x+.

= 1.2.4 =
* Fixed issue with keyboard shortcut labels. Please save your settings when the shortcut is not working.

= 1.2.3 =
* Fixed issue with keyboard shortcut not working. Key was not picked up correctly.

= 1.2.2 =
* Fixed issue with user roles.

= 1.2.1 =
* Fixed issue because of not checking for availability of variable.

= 1.2 =
* Added setting to disable the plugin for any available user role.
* Added keyboard shortcut setting. Now you can show and hide the Toolbar with a keyboard shortcut! Thanks for the feature request photoMaldives (https://wordpress.org/support/topic/very-useful-works-exactly-as-expected/).
* Added donate link as plugin actions. Want to donate? Go [here](https://nostromo.nl/wordpress-plugins/auto-hide-admin-bar/).
* Added donate link in plugin settings.
* Moved version number in plugin settings screen to bottom.

= 1.1 =
* Moved inline Javascript code to external file and have it registered and enqueued.
  Thanks iCounsellorUK fro reporting [this](https://wordpress.org/support/topic/assumes-jquery-loads-first/).

= 1.0.3 =
* Fixed logged in detection, replaced test for class .logged-in to test for #wpadminbar, which is universal and dependent on theme developers.
  Thanks wloske for this [suggestion](https://wordpress.org/support/topic/not-working-for-me-140).

= 1.0.2 =
* Added load_textdomain() function.

= 1.0.1 =
* Something went wrong in SVN, fixed it with this new commit.

= 1.0 =
* Internationalized the plugin, which was a good reason to increase version to 1.0 :)

= 0.9.2 =
* Fixed 'ReferenceError: adminBarIn is not defined' bug.
* Updated hoverIntent jQuery plugin to version 1.8.1.

= 0.9.1 =
* Code will not execute when in WordPress customizer view, to prevent top of page cut-off.

= 0.9 =
* Changed description.
* Moved settings page to settings menu.
* Minor code cleaning/reorganization.
* Fixed some typo's in strings.
* Fixed the hidden div (the one that triggers te re-appearance of the Toolbar) to not be added on window resize. We only need one...

= 0.8.2 =
* Changed loading of scripts to wp_footer.
* Changed wrapping of jQuery anonymous function to use a document ready function to prevent compatibility issues.

= 0.8.1 =
* Replaced get_current_theme() with wp_get_theme(). Thanks to ElectricFeet via Support Forum; http://wordpress.org/support/topic/wp_debug-gives-message-that-get_current_theme-is-deprecated

= 0.8 =
* Some CSS changes due to larger Toolbar in WordPress 3.8.
* Added option for hiding/showing the Toolbar on small screens.
* Added support for Twenty Fourteen. Need to think of a solid way to support themes with fixed headers/navigation. Tips are welcome.

= 0.7 =
* Removed external jQuery library.
* Added options page.
* Added options for animation speed, delay and mouse polling interval.

= 0.6.3 =
* Changed background-position to background-position-y, because of IE8 problem (of course...). Thanks to per (feja@home.se) for submitting the bug, and the jQuery bugtracker for the hint: http://bugs.jquery.com/ticket/11838

= 0.6.2 =
* Added style adjustment for body background.

= 0.6.1 =
* Switched wp_enqueue_script sequence for jquery and jquery.hoverintent due to problems.

= 0.6 =
* By request, added delay for showing/hiding the Admin Bar. A settings page will be included in the future.

= 0.5 =
* Changed position of hidden div to fixed, so the admin bar is showed also when you have scrolled down on your site.

= 0.4 =
* Changed jQuery enqueue manner. Now using the wp_print_scripts hook. Thanks to Ralph from zonebattler.net for mentioning this bug.

= 0.3 =
* Added - Only activate the plugin when a user is logged in.

= 0.2 =
* Added  - wp_enqueue_script for jQuery.

= 0.1 =
* Initial Release.
