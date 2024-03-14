=== WPAC Social Tools - Like, React & Share ===
Contributors: mianshahzadraza, wpacademypk
Tags: like, dislike, reactions, post like, social sharing
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 3.0.3
Requires PHP: 5.6.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

The Most Simple WordPress Post Like, Dislike & Reaction System with Social Sharing. 

== Description ==

This will add powerful social features to your WordPress website. Engage with your website visitors by giving them the opportunity to react with your content. This plugin will all like dislike buttons with like vs dislike bar or you can add emoji reactions like Facebook.
Both visitors and logged-in members can react to your posts. Not only reactions but a social sharing bar as well so no more different plugins.
This plugin also has a widget to show most liked or disliked posts anywhere you like.

This plugin is my first project, so feel free to provide feedback via support forums. You can also contribute to help me improve this open-source project.

Github repository: If yu want to contribute to this project you can fork this [Github Repository](https://github.com/wpacademy/wpac-like-system/ "Github Repository for WPAC Like System")

= Features =

* Like & Dislike Buttons 
* Like vs Dislike bar
* Most Liked or Disliked Posts
* Handy shortcodes
* Reaction system with 2 styles of emojis
* Social Sharing

= Shortcodes =

Display Like & Dislike buttons in post or page.

`[WPAC_LIKE_SYSTEM]`

Return Like/Dislike count for current post being viewed.

`[WPAC_LIKE_COUNT] [WPAC_DISLIKE_COUNT]`

Return Like/Dislike count for given post ID.

`[WPAC_LIKE_COUNT id="123"] [WPAC_DISLIKE_COUNT id="123"]`

Return Like/Dislike count wrapped in a string, use `%` where you want to display count value.

`[WPAC_LIKE_COUNT string="Liked % times"] [WPAC_DISLIKE_COUNT string="Disliked % times"]`

Use String with post id

`[WPAC_LIKE_COUNT id="123" string="Liked % times"] [WPAC_DISLIKE_COUNT id="123" string="Disiked % times"]`

= Credits =

Libraries and resources used in this project.
[jQuery](https://jquery.com), [FontAwesome](https://fontawesome.com/), [Google WebFonts](https://fonts.google.com/)

== Frequently Asked Questions ==

= How to Install the Plugin =

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the WPAC Settings screen to configure the plugin

= How to use reaction system =

You can switch between like/dislike buttons and reaction system from plugin settings page.

= How to get help =

You can post into our [Forums]( https://wpacademy.pk/forum/plugins/wpac-like-system/ "WPAC Plugin Help Forum")

= Upgrade Notice =

Make sure to deactivate and activate the plugin once.

== Screenshots ==

1. Plugin Configuration Screen
2. Plugin Configuration - Reactions Settings Page
3. Plugin Configuration - Like & Dislike Button Settings
4. Plugin Configuration - Sharing Settings
5. Like/Dislike Buttons Layout
6. Reacctions Layout
7. WPAC Popolar Posts Widget Settings
8. WPAC Popolar Posts Widget Layout at Front-End

== Changelog ==
= 3.0.3 =
* Updated Font Awesome Library

= 3.0.2 =
* Added option to disable social sharing
* Added option to load/unload FontAwesome icons from plugin
* Fixed reported bugs

= 3.0.1 =
* Bug Fixes
= 3.0.0 =
* New Feature: Now non-logged-in users can also like/dislike or React.
* New Feature: Liked vs Dislike bar.
* New Feature: Social Sharing Bar.
* New Widget: Now you can show most liked/disliked posts anywhere with the new widget.
* Reactions count now updated without reloading the page.
* Improvements in code.
* CSS bug fixes.

= 2.0.3 =
* Fixed new reaction icons

= 2.0.2 =
* Added new reaction styles (emojis).
* Added option to change success and error strings
* upgraded database for future updates to features
* like and dislike count now updates without page reload
* various reported bugs are fixed

= 2.0.1 =
* Fix for fatal error 

= 2.0.0 =
* Added Reaction System
* Added more shortcodes
* Fixed some bugs
* Added new button layouts

= 1.0.0 =
* Started the project
