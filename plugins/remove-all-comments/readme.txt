=== Remove All Comments ===
Contributors: php-developer
Tags: comments, spam, delete comments, remove comments, no comments, spam free comments, comments less, remove all comments, auto remove comments
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 3.1.1
Author: php-developer
Author URI: https://profiles.wordpress.org/php-developer-1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plug-in will removed/Delete all comments from posts and pages. 

== Description ==

This plug-in remove all comments from your current word press site. When Plug-in is active its remove all comments for all user. You can also remove comments from specific post type.

You have a option to removed comments only from posts or pages.

You can also remove from custom post type. For that you have to add code in your current theme`s function.php file.

Note: When you select "Yes" to "Do you want to remove all comments?"  Its override all other option settings.


== Installation ==

This section describes how to install the plug-in and get it working.

e.g.

1. Upload ` remove-all-comments` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. There is “Remove All Comments Plugin Settings” where you can makes admin settings 

== Frequently Asked Questions ==

= Can I remove all comments from page only? =

Yes, you can remove all comments from page only.

= Can I remove all comments from post only? =

Yes, you can remove all comments from post only.

= Can I remove all comments from all custom post type? =

Yes, you can remove all comments from custom post type. If you want to remove all comments from specific post type then you have to add this function in your theme's function.php file.
 Example : If you want to remove all comments from post type 'book' then below is your php code.
<?php removeCommentsFromSite("Book");  ?>

== Screenshots ==

1. You have a option to removed comments only from posts or pages.

== Changelog ==
1.0 : basic one plugin

2.0 : updated with WP 4.5

3.0 : updated with WP 4.7.2

3.1 : updated with WP 4.8
== Upgrade Notice ==
Step 1 : deactivated current plugin OR DELETE whole plugin.
Step 2 : now upgrade onece step 1 is done.