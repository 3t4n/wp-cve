=== Menu In Menu ===
Contributors: wizzud
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KP2LVCBXNCEB4
Tags: menu
Requires at least: 4.3
Tested up to: 4.5
Stable tag: 1.0.0
License: GPLv2 or Later

Place one Custom Menu inside another Custom Menu

== Description ==

This plugin enables a Custom Menu to "include" any other Custom Menu, so groups of items that get repeated across multiple menus can be defined just once and then included into any other menu at will.

If you have a set of menu items that you repeat across several menus, it can be a bit of a pain when
one of that set needs updating and you have to go through all your menus changing each one.

This plugin allows you to define the subset of items as a separate menu, and then include that menu
into any other menu. If you then need to maintain one of the items in the subset, you only need to update
one menu, and the change automatically gets reflected in all the other menus that include that subset!

It's as easy to use as adding a Post, Page, Category or Tag to your menu...

With the plugin activated, go to your Menus page in admin.
As well as being able to add Posts, Categories, etc, to your menu, you should now see a metabox for
**Navigation Menus** which has checkboxes for each of your defined Menus (if you don't see the metabox,
check your Screen Options settings at the top of the page).
All you need to do is create your submenu (as you would any other menu),
open the menu you want to insert the new submenu into,
select the submenu from the Navigation Menus metabox,
Add it,
and position it.
Save and you're done!

The inserted Menu *replaces* the Navigation Menu item in the menu. You can insert multiple instances of the
same submenu into one menu, and you can include a submenu into as many other menus as you need.

= Perpetual Loops =
The one thing that the plugin guards against is getting into a never-ending loop : it won't prevent you
setting one up (see FAQs as to why), but it *will not* output any submenu that has itself as an antecedent.

For example, you can set up menus such that Menu A includes Menu B, which includes Menu C. Fine, no problem.
However, if you were to also add Menu B into Menu C as a Navigation Menu item,
then no matter which menu you choose to output, you have a potential loop
(... B, includes C, includes B, includes C, ... and so on).

If WP_DEBUG is enabled, the plugin will print a warning wherever it has taken action to prevent a loop condition.
This behaviour can be changed by hooking into a filter (see FAQs).

= Frontend Only =
The plugin only performs the menu replacement at the frontend of WordPress (ie. not on Admin pages).
This behaviour can be changed by hooking into a filter (see FAQs),
but please ensure that you do **not** enable it for the Menus admin page!


== Installation ==

Install and activate via 'Plugins > Add New' in your WP Admin.


== Frequently Asked Questions ==
If you have a question or problem that is not covered here, please use the [Support forum](https://wordpress.org/support/plugin/menu-in-menu).

= Why can't I see the Navigation Menus metabox on the Menus page? =
Check your Screen Options (top of the page). Screen Options give you the ability to turn off/on metaboxes,
and it's possible that Naviagtion Menus is turned off : just activate the checkbox in Screen Options and
the Navigation Menus metabox should appear (assuming the plugin is activated, of course).

= Why isn't an included menu appearing in the menu I put it in? =
It's possible that the inclusion might have created a perpetual loop, and the plugin won't allow that - it
simply refuses to output any menu that is contained within itself (ie. has itself as an antedecent). If you enable
WP_DEBUG you will see a message _if_ the plugin has taken action to prevent a perpetual loop.
If it's not a perpetual loop situation then it may be that another plugin is getting in the way. And that is
a lot harder to resolve!

= Why doesn't the plugin stop me setting up a perpetual loop? =
Because they have the potential to be used deliberately.
Take an example where Menu A includes Menu B, and Menu includes Menu A.
If you output Menu A, you'll get Menu A with Menu B included.
If you output Menu B, you'll get Menu B with Menu A included.
They may be occasions where this is useful.

= How do I resolve an unintended perpetual loop? =
Enable WP_DEBUG and check the message produced by the plugin. Part of the message gives the names of menus
that have included one another, the last one being the one that would have caused the loop. Somewhere
preceding it you should see the same menu name. The first name was the menu that was requested to be
displayed, and it included the 2nd, which in turn included the 3rd, etc. You can use this to determine
how you need to restructure your menus so as to avoid any perpetual loops.

= Can I prevent the notification of a perpetual loop, while keeping WP_DEBUG enabled? =
Yes, there's a filter you can hook into. Add the following code (modified to suit) to your theme's
functions.php (or wherever you feel is more appropriate)...
`
add_filter( 'mim_notify_recursion', your_function_name );
function your_function_name( $notify ) { return false; }`
By default, `$notify` is **false** *unless* WP_DEBUG is enabled.

= Can I get notification of perpetual loops without having to enable WP_DEBUG? =
This is the flip side of the previous question,
and you simply need to return **true** - instead of false - from a 'mim_notify_recursion' filter (see above).

= Can I enable the menu replacement for Admin pages? =
Yes, there's a filter you can hook into. Add the following code (modified to suit) to your theme's
functions.php (or wherever you feel is more appropriate)...
`
add_filter( 'mim_expand_menus', your_function_name );
function your_function_name( $expand, $current_screen ) {
  //ex. enables everywhere except Menus admin page...
  return $expand || empty( $current_screen ) || $current_screen != 'nav-menus';
}`
By default, `$expand` is **true** *unless* running Admin.

== Screenshots ==
1. Navigation Menus metabox


== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* New release
