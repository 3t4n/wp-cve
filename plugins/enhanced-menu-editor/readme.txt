=== Enhanced Menu Editor ===
Contributors: marcuspope
Donate link: http://www.marcuspope.com/
Tags: enhanced menu, copy menu, sitemap, menu sync, bulk edit, menus, menu editor, appearance, easier, better 
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: trunk
License: GPLv2

Adds menu editing options to the built-in WordPress Menus page like copying entire menus, and synchronizing page hierarchies with menu structures.

== Description ==

The built-in menu editor for WordPress is a nicely built utility.  But often with larger content sites you end up spending a lot of effort keeping the
menus and the pages in sync.  This plugin serves three major purposes:

1. Allows for easy creation of sitemap menus by removing the pagination setting for "View All" pages.
2. Allows for copying entire menus to a new menu.
3. Allows you to synchronize the parent-child hierarchy of pages with the parent-child hierarchy of a given menu.

The first feature is pretty clear, it's a pain to add say 150 pages to a sitemap menu when they are paginated by default at 50 pages.

The second feature allows you to copy an existing menu and play around with tweaks to the structure without losing the effort put into the original menu.

And the third feature is my favorite.  Basically instead of going to every single page and modifying the parent/child relationship, you can create a sitemap menu and use the wonderful drag n drop feature of the menu editor to restructure your entire site.

== Installation ==

1. Upload the plugin contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in Wordpress Admin
1. You're done!

== Frequently Asked Questions ==

Q: How do I use the tool once it's installed?
A: Just go to the Menu editor under Appearance > Menus and you'll see two new buttons next to Save Menu.

Q: I clicked the "Sync Page Structure" (or Copy Menu) button but nothing happened, the cursor just shows an hourglass. What happened?
A: You might have a really large site and because the requests to sync the page are driven by ajax, it might be waiting a while for the process to complete. Give it some time and you should get a response.  If you don't or you get an error about a time-out then you'll need to configure your hosting options to allow longer page load times.

Q: How exactly do I create a site map?
A: Just create a new menu - and click the "View All" link under the Pages widget in the Menus Editor.  Click the "Select All" link and then click "Add to Menu".  If your pages already had parent child relationships defined in the Page Editor, then your menus should reflect that.  If not, you can adjust the nesting structure using the menu editor and then click "Sync Page Strucutre" to update the Page Parent settings of all the items in your menu.

Q: Is ther any way to undo these changes?
A: Nope!, be sure to backup your database before making massive changes to a live site.  You can and should use the "Copy Menu" feature to backup a menu before you make changes to it so you can restor it later if necessary.

== Upgrade Notice ==

1. No upgrade notices

== Screenshots ==

1. No screenshots

== Changelog ==

= 1.1 =
* Fixed some minor warnings for menus that are empty or when the user is on the new menu tab

= 1.0 =
* Initial creation of plugin

== Arbitrary section ==
