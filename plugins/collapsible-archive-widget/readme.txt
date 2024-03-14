=== Collapsible Archive Widget ===
Contributors: adywarna
Donate link: http://www.romantika.name/v2/
Tags: collapse, collapsible, archive, collapsible archive, widget
Requires at least: 2.1
Tested up to: 2.7.1
Stable tag: 2.3.1

This simple plugin is a widget that displays a collapsible archives list in your widgetized sidebar by utilizing JavaScript.

== Description ==

This simple plugin is a widget that displays a collapsible archives list in your widgetized sidebar by using JavaScripts. In version 2.0.0 script.aculo.us effects has been added as an option, utilizing the script.aculo.us files supplied with WordPress.

== Installation ==

1. Make a directory `collapsible-archive-widget` under `/wp-content/plugins/`
1. Upload `collapsible-archive.php` to the `/wp-content/plugins/collapsible-archive-widget/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use your 'Presentation'/'Sidebar Widgets' settings to drag and configure
1. If you want to change your plus and minus images, replace the PNG files. Best size is 22x22

== Configuration ==

* Widget title: the title of the widget
* Show post counts for year: Whether or not to show the post number for each year
* Show post counts for month: Whether or not to show the post number for each month
* Abbreviate month names: Check this box to show abbreviation of month names
* Hide year from month names: Do not print year after month names
* Use script.aculo.us effects: Whether or not to show effects
* Expand effect: Effect to use when expanding the list
* Collapse effect: Effect to use when collapsing the list
* Expand the list by default: Check this box to have the list expanded when loaded
* Expand current year by default: Check this box to have the current year expanded when loaded
* Expand current month by default: Check this box to have the current month expanded when loaded
* Show individual posts: Show posts in the list. This should be used in extra caution; if you have a lot of posts consider disabling it as this will take time to load
* Use HTML arrows instead of images (&#9658; &#9660;) 
* Show current month in bold: show current month in bold 
* Show a link to plugin page. Thank you for your support! : Display a link to plugin page as a support method

== TODO ==

* Add ability for multiple instances
* Add ability to work as non-widget
* Add ability to include / exclude categories
* Expand previous month rather the current
* Research the practicality to use CSS / allow CSS options
* List posts without year and month headers (for blogs with few posts)
* Do not list the posts that are listed on the main page

== Change Log ==

* 03-Aug-2007: Initial version
* 04-Sep-2007: Added ability to select whether to use abbreviations for the month names, and script.aculo.us effects!
* 27-Sep-2007: Fixed javascript include - effects.js added and scriptaculous.js removed (For some reason it worked in 2.2).
* 10-Nov-2007: Added ability to display posts (with caution), to expand by default, and also added plus and minus signs as expand/collapse buttons
* 24-Aug-2008: Multiple updates: (1) Enqueue javascripts using WordPress API wp_enqueue_script (2) Validation as XHTML 1.0 Transitional (3) Add option to expand current year and/or month by default (4) REMOVED list type option (5) Added ability to upload own plus and minus images (6) Added ability to display plugin link. If you'd like to support this plugin, having the "powered by" on your blog is the best way; it's our only promotion or advertising.
* 25-Aug-2008: Bugfix to not load javascripts when effects is not used 
* 25-Aug-2008: Code factoring and added ability to use HTML arrows
* 16-Mar-2009: Separated year and month posts counts, added option to hide year after month names, ability to show current month in bold, enabled localized title
* 17-Mar-2009: Fixed valid XHTML, and highlight (bold) bugfix