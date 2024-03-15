=== Reverse Order Comments ===
Contributors: TimZ
Donate link: http://www.kiva.org/invitedby/tim5156
Tags: guestbook, g&#228;stebuch, comments, reverse, order
Requires at least: 1.5
Tested up to: 3.4.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows to display the comments in reverse order. Latest comment first, oldest last.

== Description ==

A really simple WordPress Plugin. It provides the function `ro_comments_template()`, which allows the comments to be displayed in reverse order (thus the newest comments first, oldest last).

== Installation ==

1. Install & activate the plugin.
1. Edit your theme files and implement the new function as described in the FAQ section.


== Frequently Asked Questions ==

= How do I implement the plugin function in my templates? =

Open the template file which should show the comments in a reverse order in your favorite editor.
Locate the line

`<?php comments_template(); ?>`

and replace it with

`<?php if(function_exists('ro_comments_template')) ro_comments_template(); else comments_template(); ?>`

__Note:__ 

The arguments of the function can be different in your template, for example if it is 

`<?php comments_template('', true); ?>`
change the plugin function accordingly to 
`<?php if(function_exists('ro_comments_template')) ro_comments_template('', true); else comments_template('', true); ?>`

= What are the files in theme-examples directory? =

`  theme-examples/
    default                      (English default theme)
      comments-topinput.php      comments.php adapted for a guestbook
      tpl_guestbook.php          template for a guestbook

    default_de                   (German default theme)
      comments-topinput.php      comments.php adapted for a guestbook
      tpl_gaestebuch.php         template for a guestbook
`

= Will it work with paged comments? =

Yes, but if you use comment navigations with labels like "older" and "newer", then it might be puzzling for the user, as the comment order is opposite to the navigation.
In this case I suggest not to use the plugin in combination with comment-pagination or you should change your navigation labels.

= Building a guestbook for the Kubrik theme =

Precondition: comments-topinput.php and tpl_gaestebuch.php are in in your template directory.

1. Create a new page. Title e.g. "guestbook"
2. Allow comments for this page
3. Choose the template "Guestbook" for this page.

Done!

If you want the comment input fields "blogstyle" at the bottom of the page, just change inside
tpl_gaestebuch.php the line

`<?php if(function_exists('ro_comments_template')) ro_comments_template("/comments-topinput.php"); else comments_template(); ?>`

to

`<?php if(function_exists('ro_comments_template')) ro_comments_template(); else comments_template(); ?>`


= Building a guestbook for other themes =

If you don't use the Kubrik/Default theme, it is still very easy to build your own template.
A good template to start with is "page.php" and "single.php". Copy "page.php" and rename it.
The line `<?php comments_template(); ?>` is important. Change it as described above.
Add a header to your new page, to tell Wordpress it is a template.

e.g.

`<?php
/*
Template Name: Guestbook
*/
?>`

[Page Template Documentation](http://codex.wordpress.org/Pages#Creating_your_own_Page_Templates)


== Changelog ==

= 1.1.1 =
* 16.07.12 Tested with WP 3.4.1

= 1.1 =
* 08.06.12 Updated to work with WP 3.3.2; Now using filers, making it compatible, faster and future proof

= 1.0.3 =

* 07.06.12 maintenance release; change of plugin owner

= 1.0.2 =

* 18.05.08 release on the WordPress plugin directory; renamed the plugin to reverse-order-comments; changed the directory structure to enable the WordPress plugin autoupdate

= 1.0.1 =

* 13.09.06 small bugfix inside comments-topinput.php templates

= 1.0 =

* 11.11.05 Initial Release


== Upgrade Notice ==

= 1.1 =
Now compatible with WP 3.3.2 

= 1.0.3 =
Maintenance release; upgrade is not required