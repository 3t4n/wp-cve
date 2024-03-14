=== Plugin Name ===
Contributors: tuxlog
Donate link: http://www.tuxlog.de/
Tags: wordpress, plugin, smiley, smilies, monalisa, comments, post, edit, buddypress, bbpress
Requires at least: 4.0
Tested up to: 6.4
Stable tag: 6.3
wp-monalisa is the plugin that smiles at you like monalisa does. place the smilies of your choice in posts, pages or comments.

== Description ==

wp-monalisa is the plugin that smiles at you like monalisa does. place the smilies of your choice in posts, pages or comments.

There are a lot plugins for smiley support out there and some of them are really useful. 
Most of them don't work out of the box and this is what wp-monalisa tries to achieve, giving you the ability to maintain your smilies and even turn them into img tags. 

it's easy and it smiles at you...what else do you want?

Features:

* maintain your smilies in a separate directory
* activate or deactivate smilies for posts or comments
* replace smilies with img tags
* extend or replace wordpress smiley replacement
* while edit posts or pages, pops-up in a draggable meta-box
* extends your comment form to give you visitors the freedom to smile :-)
* support for fckeditor (tested with v3.3.1)
* fully integrated with BuddyPress, bbPress and wpForo

The video shows a short overview of what wp-monalisa can do for you. [youtube http://www.youtube.com/watch?v=uHXlELn27ko]

Credits:
Thanks go to all who support this plugin, with  hints and suggestions for improvment and specially to

* Michal Maciejewski, polish translation
* Denny from http://www.vau3.de for testing and giving input for the BuddyPress integration
* FJ Bakry, Camisto (https://camisto.com) for indonesian translation

== Installation ==

1. Upload the contents of the zip archive to your plugins folder, usually wp-content/plugins/`, keeping the directory structure intact (i.e. `wp-monalisa.php` should end up in `wp-content/plugins/wp-monalisa/`).
1. Activate the plugin on the plugin screen.
1. Visit the configuration page (wp-Monalisa) to configure the plugin (do not forget to check the comment forms id)
1. Optional If you would like to change the style, just edit wp-monalisa.css

Update:
If you update from a former version please do not forget to deactivate and actiate the plugin once, since database changes will only take effect on activation. You settings will not be deleted during deactivation.



== Frequently Asked Questions ==

= Are there any Tutorials? =

Yes, there are have a look at http://www.tuxlog.de/keinwindowsmehr/2009/wp-monalisa/ or try these screencasts to learn how to install, configure and use wp-monalisa 
Installation: [youtube http://www.youtube.com/watch?v=5w8hiteU8gA]
Configure: [youtube http://www.youtube.com/watch?v=614Gso38v5g]
Use: [youtube http://www.youtube.com/watch?v=uHXlELn27ko]
Import/Export of Smilies: [youtube http://www.youtube.com/watch?v=cedwN0u_XRI]

= The smilies for BuddyPress activities are only shown when the page is reloaded. Is this a bug? =

No, BuddyPress uses local ajax to add new activities to your timeline. Therefore the earliest time the Smilies can be loaded is when the page is loaded from the server again.

= I can't see the smilies in the notices shown in the sidebar of BuddyPress. What's wrong? =

Nothing, the current version of BuddyPress does not offer a filter to show the smilies in there. But there is a workaround editing one line of bb-messages-template.php, change line 546 to 
<?php echo apply_filters('bp_get_message_notice_text', stripslashes( wp_filter_kses( $notice->message) )) ?>
or use the Activity-Stream-Widget for BuddyPress, which is supported by wp-monalisa 

= wp-monalisa does not work with comments. What now? =

Please, check and double check that the id given in the admin dialog of wp-monalisa is the correct id of the comment form textare. This can usually happen if you changed your theme.

= My smilies are gone? What's wrong? =

Plase check and double check the path to your smiley directory.

== Screenshots ==

1. wp-Monalisa admin dialog
2. wp-Monalisa in the wordpress edit dialog
3. wp-Monalisa extends the comment form
4. wp-Monalisa import thickbox dialog

== Changelog ==

= v6.3 (2024-01-22) =
* hardened install routine
* optimized wpdb->prepare function calls
* fixed mixed up blocks in admin dialog

= v6.2 (2022-09-27) =
* finally added support for Gutenberg's Richeditor Blocks
* improved support for the classic editor
* extended security checks for backend
* fixed a lot of issues concerning the WordPress coding standards

= v6.1 (2022-08-22) =
* adopted to wpForo 2.0.6
* added missing translation

= v6.0 (2022-06-27) =
* adopted code to PHP8

= v5.9 (2022-06-17) =
* adopted javascript load to new AMP version

= v5.8 (2022-03-27) =
* added css to overwrite wpforo hard css
* wpforo support, added filter to convert emoticons if entered as text (wpforo removes :*: shortcodes, so we must overrule this
* removed unused button in admin dialog

= v5.7 (2022-03-16) =
* fixed smiley format in wpForo
* extended wpForo support for second tinymce editor

= v5.6 (2022-03-14) =
* extended support for classic editor / classic gutenberg block
* extende wpForo support to become more independent from wpForo settings
* added new filter for BuddyPress messages

= v5.5 (2022-03-13) =
* extend the compatibility with AMP Plugin
* fix javascript load in admin under special conditions
* added support for wpForo
* fixed problem javascript not laoded under specific conditions

= v5.4 (2021-04-12) =
* fixed some PHP notices in wpml_comment

= v5.3 (2021-01-03) =
* fixed css conflict with newer versions of asgaros forums

= v5.3 (2020-08-18) =
* fixed deprecated jquery call

= v5.2 (2020-05-20) =
* fixed deprecated call and WPLANG warnings

= v5.1 (2019-09-21) =
* added max-width to backend smilies, to have a clean admin dialog
* fixed javascript problem with bbpress in admin
* fixed size for svg smilies

= v5.0 (2019-09-12) =
* added french translation. Thanks to Maître Mô
* removed hint to turn of emojis and remove auto disable emojis, seems to work without it now
* added support for SVG files

= v4.9 (2018-12-09) =
* first adoptions to Gutenberg editor for WP5: use meta box only in classic editor,

= v4.8 (2018-10-22) =
* fixed special error while first time plugin activation

= v4.7 (2018-10-04) =
* fixed a warning about some uninitialized variable
* improved loading the javascript at the end of the page (now we wait for jquery)
* improved support for WordPress Multisite, so you can now separately activate wp-monalisa on every sub-blog

= v4.6 (2018-05-06) =
* fixed a problem with missing configuration in wp_options

= v4.5 (2018-05-04) =
* added compatibility with disable_emojis plugin
* added support for Rich-Editor

= v4.4 (2017-10-06) =
* fixed some PHP Notice messages about missing index


= v4.3 (2017-06-04) =
* fixed invalid HTML when using BuddyPress and table layout
* fixed unprecise height adn width calculation
* fixed bbPress with smiley popup

= v4.2 (2017-05-07) =
* fixed buddy press new theme / html structure (now has 5 parents between smilies and textarea)


= v4.1 (2017-05-06) =
* fixed textdomain to make it possible to translate the plugin on translate.wordpress.org

= v4.0 (2017-05-06) =
* extended javascript for use in comment forms and Google Captcha plugin
* support to load javascript in footer
* added support for rtmedia buddypress media plugin when using BuddyPress or bbPress
* added support for Smiley-Popup
* added support to show Smilies before Submitbutton

= v3.9 (2016-12-22) =
* fixed bug with repeatig smilies on the same line

= v3.8 (2016-10-16) =
* make sure WP smilies are reactivated when plugin is deactivated
* added bulk actions to admin dialog
* added indonesian translation thanks to Fajar
* added some css to align smilies in both mode
* support for new bbPress textarea id
* added max width and/or height setting for icons
* added popup option to show the smilies

= v3.7 (2016-08-05) =
* fixed some PHP7 compatibility issues

= v3.6 (2015-11-29) =
* fixed incompatibility with WordPress emojis
* added russian translation
* fixed problem with activities in BuddyPress >= 2.3.2 
* removed old support files from directory tree (directory support)
* secure post action in admin dialog
* added smilies to messages
* added support or GD bbPress tools signature

= v3.5 (2015-04-26) =
* fixed a layout issue with WordPress 4.2.

= v3.4 (2014-06-19) =
* added urkaine translation. thanks to Michael Yunat

= v3.3 (2014-05-04) =
* fixed visual TinyMCE 4 mode with bbPress
* added contextual help
* removed support dialog since it was used rarely

= v3.2 (2013-04-20) =
* fixed a poblem with Firefox and comments in javascript
* adopt to TinyMCE 4

= v3.1 (2013-12-03) =
* added spanish translation. Thanks to Andrew Kurtis from WebHostingHub

= v3.0 (2013-08-04) =
* fixed some php warnings

= v2.9 (2013-07-01) =
* fixed problems with smilies and BuddyPress profiles

= v2.8 (2013-07-01) =
* added support to integrate smilies into bp profile messaging ux free plugin

= v2.7 (2013-04-20) =
* extended wpml to allow using :yes: and :YES: as different emoticons
* separate the support for bbPress and BuddyPress and support bbPress tinyMCE

= v2.6 (2013-03-16) =
* changed hint text to new WordPress labels
* extendd support of use from within php
* fixed bug with BuddyPress when using tables for output

= v2.5 (2012-10-26) =
* with special configurations smilies disappeared or where shown with wrong dimensions

= v2.4 (2012-10-24) =
* with special configurations smilies disappeared due to lack of dimensions

= v2.3 (2012-10-01) =
* added width and height attribute to img tags speeding up browser rendering if many smilies are used
* added deferred loading for the hidden smilies if pulldown smilies are active
* added "more..." Smilies are inserted when "more..." is clicked 
* removed an incompatibility with Better WP Minify

= v2.2 (2012-09-28) =
* fixed warning during plugin activation

= v2.1 (2012-08-05) =
* swtiched to load_plugin_textdomain for compatibility
* load js only when applicable
* added support for BuddyPress (Acitivties, Messages, Notices, Groups, bbpress-Forums)

= v2.0 (2012-06-10) =
* extended multisite support for easier handling

= v1.9 (2012-03-05) =
* fixed a typo with trailing spaces in emoticons
* added default admin email to support form
* added theme name to support form 
* work around a bug in bwp minify with jquery events

= v1.8 (2012-02-19) =
* add new support and donation feature
* add posibility to disable comments smilies on a single post/page
* use standard load for wordpress includes
 
= v1.7 (2011-12-21) =
* clean up more (maybe all?) html5 code errors for 3.3 compatibility

= v1.6 (2011-12-14) =
* now using wp_enqueue_style for css
* clean up html5 code errors for 3.3 compatibility

= v1.5 (2011-10-22) =
* removed russian translation because of a restricton from wordpress.org
* added hebrew translation thanks to Sagive

= v1.4 (2011-08-08) =
* added function get_wpml_comment() which returns the smiley-html-code to integrate within comment_form theme code

= v1.3 (2011-05-03) =
* added simple support for multisite installtions (smilies can be only maintained from mainblog and work on every blog which it is activated for) 

= v1.2 (2011-03-13) =
* fixed a problem with wp 3.1 in network mode, due to a different search path the wrong setup.php was included
* added tooltip support for icons

= v1.1 (2011-01-23) =
* added support for fckeditor (tested with v3.3.1)

= v1.0 (2010-01-17) =
* fixed wrong initial value for show as table option
* added alt attribute to admin dialog icons (xhtml fix)
* set floating control div to display:none in wpml_comments.php
* added support for autoupdate to prevent auto delete of private smilies and custom css
* fixed undefined index warning in wpml_admin.php

= v0.9 (2009-12-19) =
* fixed invalid xhtml in admin dialog
* mark iconfiles not yet mapped with a star

= v0.8 (2009-11-30) =
* fixed invalid XHTML in admin dialog
* fixed strange behaviour when deactivating smilies on comments results in null
* added hint to deactivate wordpress smilies fpr wp-monalisa

= v0.7 (2009-09-27) =
* added russian translation
* added belorussian translation, thanks to ilyuha (http://antsar.info) 
* added .pak export functionality
* divided smiley-list into pages (smiley list navigator using jquery ajax)

= v0.6 (2009-08-18) =
* changed readme to support new changelog feature at wordpress.org
* new option, smilies can also be output in a table (only for comments)
* added support for user specific css file to improve support for automatic update
* fixed handling of slashes in emoticons
* fixed handling of trailing spaces in emoticons

= v0.5 (2009-06-16) =
* added dummy version to javascript includes to hide wordpress version
* insert smilies with trailing space to make sure the shortcodes can be found
* set default smiley to correct file name
* now png icons are also supported
* surpress showing smilies more than once if more than one shortcode is defined for the same file
* modified column width of column iconfile to 80

= v0.4 (2009-05-30) =
* fixed trimming whitespace from emoticons in admin dialog
* fixed replace algorithm, now search for longest substring first and can handle any whitespace situation

= v0.3 (2009-05-29) =
* renamed default icons with prefix wpml_ to get a more or less unique name and prevent override
* modified row width of column emoticon to 25
* add maxlength attribute=25 to input fields for emoticons
* added screenshot for import dialog
* styled admin dialog a bit more wordpress like (alternate background color for table, buttons outside the table, added checkall box)

= v0.2 (2009-05-22) =
* added alt attribute to img tags, to produce correct xhtml
* fixed german translation
* added import dialog to import phpbb3 smiley packages
* added space after shortcode insertion
* automatically extend array allowedtags when oncomment and replace options are set
* improve error handling with directory a bit
* added polish translation

= v0.1 (2009-05-17) =
* Initial release
