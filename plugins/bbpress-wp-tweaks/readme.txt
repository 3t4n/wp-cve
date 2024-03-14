=== bbPress WP Tweaks ===
Contributors: veppa
Donate link: https://www.paypal.com/donate/?hosted_button_id=LZ4LP4MQJDH7Y
Tags: bbpress,forum,sidebar,login links,forum sidebar,widgets, forum widgets,bbpress sidebar,bbpress tweaks,bbpress wrapper, bbPress WP Tweaks
Requires at least: 4.7
Tested up to: 6.2
Stable tag: 1.4.4


Adds bbPress forum specific sidebar, wrapper, widgets, user columns, login links and other tweaks. 

== Description ==

bbPress WP Tweaks replaces regular sidebar with forum specific "bbPress sidebar". To setup forum specific sidebar you should first select correct forum wrapper file which has sidebar. Then select what action perform with existing sidebar: replace, prepend or append. Then  select which sidebar to target with this action. You can also choose "none" option for target sidebar, in that case no bbPress sidebar will not be added to wordpress. 
If nothing in forum sidebar then regular sidebar will be shown. 
You can choose which forum wrapper template to use in plugin settings page or specify other custom wrapper file that exists in your theme.

Features:

* bbPress specific sidebar instead of default blog sidebar.
* Change default wrapper for forum pages
* bbPress login links widget
* bbPress users widget 
* Login and register links under forum
* Show forum description 
* Forum related columns for users view in admin area


**bbPress specific sidebar** - you can use different sidebar on forum pages. You can choose which sidebar to replace, append or prepend to. Appending or prepending is useful if you only need to add couple forum related widgets. Custom sidebar will be used in all forum related pages including forum, topic, reply,and user profile pages. 

**default wrapper for forum pages** - bbPRess uses wrapper file in your theme in this order: 'bbpress.php',	'forum.php', 'page.php', 'single.php', 'index.php'. First existing template file will be used. You can choose which template file to check first. Most themes  don't have sidebar in 'page.php', 'single.php' files, if you choose them then no sidebar will be shown in forums. On the other hand most themes 'index.php' file always has sidebar. If you cannot see forum sidebar then change this value to index.php in plugin settings (settings -> bbPress WP Tweaks ) page. If your theme has other non standard template files like "page-fullwidth.php" for example then you can use it by selecting "custom" option and writing page-fullwidth.php in text input field. If that file exists then it will be used as forum wrapper.

**bbPress login links widget** - if you want to display login and register links instead of login form in your sidebar then use this widget. By default bbPress will not show login links to visitors if they want to post in forum. Use this widget instead of login form in your bbPress sidebar.

**bbPress users widget** - used to display users linking to their profile pages ordered by:

* most topics with count
* most replies with count
* recently active with time
* online users with time
* new users with registration time
* old users with registration time

**Login and register links under forum** - will add login and register links where "You must be logged in to create new topics." and "You must be logged in to reply to this topic." messages shown. By default you will see above mentioned messages with no login or register links. With this options selected you will see under those messages login links and will be redirected back to that topic or forum after logging in using those links. Login and register links can be customized with custom HTML code to match your website design.

**Show forum description** - By default forum description is shown only on where forums are listed, not in forum page. With this options enabled you will show forum description on Forum page, on sub forums, on topics, on replies. So user will know what this forum about. If it is a product forum then you can add product image, links or buttons to product page, pricing/purchase page etc. This will make navigation between product and forum a lot easier.

**Forum related columns for users view in admin area** - is must have feature to have for any forum administrator. With this option enabled you will have forum related sortable columns "number of topics", "number of replies", "registration date", "last active date"  in "Users" admin page. Topic and reply counts are linked to user profile pages. You can sort by those columns and see latest registered users or users with most topics. Each column seperately can be switched off using "Screen Options" inside users page.

**Disable not used features of plugin** - you can disable sidebar by selecting "none" as target sidebar. Disable widgets by unchecking relaed checkboxes in plugin options page. All other features also can be individually enabled or disabled in plugin options page. 


= Demo =

Check out one of my sites' [bbPress forum page](https://veppa.com/forums/).

= Plugin home page =

[bbPress wp tweaks plugin page & Documentation](https://veppa.com/bbpress-wp-tweaks/). 

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`.

This should result in the following file structure:

`- wp-content
    - plugins
        - bbpress-wp-tweaks
            | bbpress-wp-tweaks.php`
            | readme.txt
			| style.css

Then just visit your admin area and activate the plugin.

Or use plugin installer by navigating to "Plugins" -> "Add new" page and search for "bbPress wp tweaks". Then click install and then activate buttons for related plugin. 

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

###Using The Plugin###

* Add bbPress login links and other bbPress related widgets to bbPress sidebar in "Appearence" -> "Widgets" page
* Select forum wrapper file in plugin settings page ("Settings" -> "bbPress WP Tweaks") 
* Check other options for tweaking your forum 

== Screenshots ==

1. Plugin options page
2. Plugin options page continued
3. Forum has separate sidebar with different widgets. You can select which sidebar to target and replace/append/prepend widgets to that sidebar on forum pages. 
4. Sidebar and widgets editable in widgets admin area and in theme customizer when navigated to forum pages.
5. bbPress related sortable columns (number of topics, number of replies, registration date, last activity date) added to users admin area.
6. Forum description can be shown in top of related forum, sub forums, topics, and replies. Login links can be shown under the forum for adding new topic or reply. 


== Frequently Asked Questions ==

= Does this plugin support other languages? =

Yes, it does. See the [WordPress Codex](http://codex.wordpress.org/Translating_WordPress) for details on how to make a translation file. Then just place the translation file, named `bbpress-wp-tweaks-[value in wp-config].mo`, into the plugin's /languages/ folder.


== ChangeLog ==

**Version 1.4.4 (release date: 13.01.2020)**

* Checks if current post variable exists before using its ID
* Plugin compatible with php8.

**Version 1.4.3 (release date: 06.12.2018)**

* Removed merging empty array

**Version 1.4.2 (release date: 09.10.2018)**

* Removed deprecated create_function directive

**Version 1.4.1 (release date: 14.12.2017)**

* Removed setting default value to forum wrapper. It was breaking forum for some themes that was generating excerpts when called index.php file.
* Fixed error related to some php versions used in users widget
* Translated plugin to Russian and Turkish.

**Version 1.4 (release date: 12.12.2017)**

* Chaneged sidebar auto detection to selecting in admin area
* Added more forum wrappers and admin definable custom wrapper
* Added Forum users widget
* Fixed login links widget
* Added login links to places where login required for adding new topic and reply. Added option formating those links. 
* Added option to show forum description
* Added forum related columns to users table in admin area
* Added last activity time usermeta value
* Added option to disable not used widgets offered by this plugin
* Added option to redirect 404 page for authors to related forum profile page
* Changed minimum requred version WP 4.7, bbPress 2.5
* Made plugin  php7 ready

**Version 1.3.1**

* Minor bugfixes

**Version 1.3**

* Added compatability to Wordpress version 3.5 and greater

**Version 1.2**

* Added compatability to bbPress version 2.1 and greater
* Displaying existing forum wrappers in bold in plugin settings.

**Version 1.1**

* Changed how main sidebar is detected. This adds compatability to more themes.

**Version 1.0.0**

* Initial release of my edition of the plugin.