=== Plugin Name ===
Contributors: don@don-benjamin.co.uk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=accounts@webhammer.co.uk&item_name=Custom Search Donation&currency_code=GBP
Tags: search,custom fields,widget,sidebar
Requires at least: 3.1.1
Tested up to: 5.4-beta3
Stable tag: 1.2.35
License: Apache 2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0
 
Build search forms to provide custom search functionality allowing search of built in post fields, custom fields via a variety of different inputs and comparison types.
 
== Description ==
 
This plugin provides an admin interface allowing you to build powerful search forms for your wordpress site.  

With this you can give your readers the ability to search and filter your posts / catalogue to quickly find the information they need.  Any custom fields you have added to your posts can be made searchable as well as the core post fields like title, author, categories etc.  Configurable input widgets allow you to customise the form further to build exactly the search you need for your site.

You can configure a number of inputs of different types, to search different fields of your posts in different ways.  These will then be presented to your users as a simple form interface allowing them to find the content they need.

= Add a sidebar widget =

1. Navigate to the widgets page in your wordpress admin area ( Appearance > Widgets )
1. In the available widgets list you should see "WPCFS Custom Search Form", drag this into the appropriate sidebar.
1. Add at least one field (see 'Configuring your form' below)
1. click save on the new widget.
1. Navigate to the front-end of your site

You should now see a very basic search form in that sidebar.  You can expand on this using the instructions below under configuring your form


= Include a preset =

1. Navigate the WP Custom Fields Search section in the menu
1. Click the "New Preset" button
1. Add at least one field (see 'Configuring your form' below)
1. Either copy the shortcode text into a post / page
1. Or copy the php code into your template
1. Navigate to the front-end of your site

You should now see a very basic search form in that sidebar.  You can expand on this using the instructions below under configuring your form

= Configuring your form =

** Adding Fields **

Each form consists of a list of fields.  

Click the "Add Field" button to add a new field to the list.  You will be prompted to select a number of options which will control the appearance and behaviour of your new field.  The different settings are described below, but a basic search form could be built from a single field with the following options:

* What should this field be called? "Search Term"
* How would you like this field to appear? "Text Input"
* What do you want to search? "Core Post Field", and "All"
* How do you want to match the search to the data? "Contains Text"

Once you have configured your field close the popup with the X in the top-right corner.

You can add as many fields as you want, and re-order the fields by dragging them up and down the list.  You can delete them by clicking the little X icon in the list, and you can reconfigure them by clicking the edit / cog icon in the list.

** Global Settings **

You can name each search form using the text input at the top of the edit form.  This name can be displayed to your site visitors (if you click the cog icon to it's right and tick the option "Show Title?") or it can be helpful if you have a number of forms and need to keep track of which is which.

= Input Types / How would you like this field to appear? =

The input type controls the form that the user sees when they visit your site to perform a search and how they provide you with a search term.  The search term may be something they've typed or it might be a selection they've made from options you provide.

** Text Input **

A simple text field which allows your visitor to type a free text search query.

This can be powerful when paired with the "Contains Text" comparison as it will match any posts where the configured field contains the visitor's search term.

By default text inputs treat their input as a single string so if a user types multiple words, they will need to appear in exactly that order.  This behaviour can be changed in the input settings options.  Click the cog next to the input type selector and you will see the text input settings popup.  Choose "Split Words?" to search for each word separately.  So e.g. a search for "Big Jumper" might match a post with the text "Big Red Jumper" or "Jumper and Big Socks".  The 'Matches If' option controls if the resulting posts must contain all the search words, or just any one of them.

** Drop Down **

This creates a html select element or drop-down menu.  This is useful where the user must select from a known list of search terms, e.g. searching by category.

For certain datatypes the options for a dropdown can be automatically populated.  This works for e.g. author or category, where there is a set list of items.  This is the default behaviour for dropdown items.  The first item in the list is always a placeholder in case the user doesn't want to search on this field, by default this shows as "Any".

If you need to customise this list you can in the input settings popup (click the cog icon to the right of the input type selector.)

If you just want to change the text of the placeholder item at the top of the list, you can set this with the "Blank Prompt" option.

** Configuring options in a list **

To configure which options are shown in the list, you will need to switch the data source from "Auto" to "Manual".  YOu can now configure the list of options for the dropdown.

Each item has a value and a label.  The label is what will be displayed to the user, the value is what will actually be searched in the database, this is helpful if you want to format e.g. numbers differently for the user to how they are stored in the database, or if you want to show different labels entirely to the end user.

Items can be added and removed using the relevant buttons.

** Checkboxes **

These are displayed as a list of checkbox inputs which the user can tick or not.

The options which are displayed are configured in the same way as for dropdowns (See "Configuring options in a list" above)

Because it's possible to select multiple checkboxes at once, you may want to show posts which match all the selections, or those which only match some of them.  This can be configured in the settings popup, by selecting "Any" or "All" for the "Matches If" parameter.

** Radio Buttons **

These are displayed as a list of radio button inputs from which the user can select only a single item.

The options which are displayed are configured in the same way as for dropdowns (See "Configuring options in a list" above)

** Hidden Constant **

This option is not displayed to the user at all, but always searches for a fixed value in the database.

This is useful if you want this search form to always search through only particular posts.  E.g. if you want to search a particular post type, you could use a hidden constant on the field "Post Type".  You can set the term which is searched in the settings popup, in the Post Type example you would want this set to the name of one of the post types in your installation, e.g. post or page or a custom post type.

= Data Types =

Data types control which fields in the database are searched on when a search term is provided for each field.

** Core Post Field **

This searches on the standard properties associated with a post or page in a standard wordpress install.  e.g. you can select to search the Title, Author, Date, Content, Excerpt, Post Type or ID.

There is also an all option which will search on all of the title, content and author fields.

** Custom Post Field **

This allows searching on any of the custom fields you have set up for your posts.  This includes all the custom fields you have manually added as well as those added by the wordpress core, and any plugins which use the custom field functionality.

This can allow powerful 'catalogue' style search in that you can add any properties you would like to your posts and then filter based on these values.

** Category Field **

This allows you to search based on what category a post is in.  If you have multiple categories with the same name it's recommended to use the ID search in order to separate them, however if you want to attach this to e.g. a text input, you will probably want to use the name search, otherwise the user will have to type the ID number for the category they watn.

** Tag Field **

Essentially the same as the category field but operates on tags rather than categories.


= Comparison Types =

How do you want to match the search to the data?

These control the rules used to match the post field against the search term to decide which posts to display to the user.

** Exact Match **

The search term has to exactly match the data field.

** Contains Text **

This will match if the search term is anywhere in the data field, so e.g. "Best Post" would find posts with the value "This is the Best Post", or "How to write the best Post" or simply "Best Post".

** Greater Than **

This will return any posts for which the field is considered greater than the search term, so a search term of 200 would match a post with a greater value, e.g. 300.

**= Numeric or Alphabetic Search? **=

Comparing things by order is tricky as what would be first alphabetically is not always the same as what would be first numerically.  e.g. if you sort alphabetically then 20 comes before 3, because comparison is done digit by digit and the first digit is lower.

As such if your field contains numerical data you will want to open the settings dialog (using the cog icon) then select Numeric from the first drop down.

Also when comparing ordering of items, it's important to know whether to include exact matches, e.g 200 is not greater than 200 so should posts with that value be shown for searches which exactly match?  This can be controlled by selecting Inclusive (yes they should) or Exclusive (no they shouldn't) in the settings popup.


** Less Than **

Shows all posts which have a value less than the search term.  (See "Numeric or Alphabetic search" above for notes on ordered searches)

** In Range **

This requires two values and will return posts which fall between the two values.  The search term has to be of the form "a:b" where a is the lower limit and b is the upper limit.  So e.g. a search term of 100:200 would return posts with values between 100 and 200.  You will probably want to use this with manually configured drop downs or checkboxes to avoid your users having to format these search terms themselves. Either term can be left blank to mean unlimited eg ":100" would show posts with a field less than 100. 

See "Numeric or Alphabetic search" above for notes on ordered searches.

** In category or sub category **

This is a special comparison which requires a category name or id for the search term and will match any post which is either in that category or is in a sub-category.

== Frequently Asked Questions ==

= Why are my posts not showing in the search results? =

There are basically 4 reasons why no results may show up.

1. The wordpress core search logic may be filtering by post type
2. The wpcfs search may be filtering the posts out for some reason
3. Another plugin or config setting may be filtering the results
4. There may be a conflict between wpcfs and another plugin/theme used on your site

To identify which is the problem, we'll go through them step by step.

Firstly set your form to show all post types, to do this open the search form settings by clicking the little cog icon on the top right of the form editor.  You should a checkbox called "Show default post types", uncheck this and you should be given a list of all post_types used by your site.  Just select the top option "Show All Post Types" and click save.

Now try using the search form on the frontend of the site, if you now see the posts then that was the issue although you'll probably want to refine which posts are shown (you most likely don't want your site visitors seeing old revisions of posts etc.)

Next try removing all the search fields from the WPCFS search form. Now when you click the search button on the front end, the plugin will not filter anything.

If your results are now showing then there was a problem with one of the search fields. Try adding them back one by one until you find which one is causing the problem. If you think they should match, please let me know and I will try to debug further.

If your results are still not showing even with an empty search form then you’re likely in situation 2 or 3.

Do these posts display anywhere else on your site? If they don’t display anywhere else then you’re likely in situation 2. Basically the plugin only ever filters the results which you see, so it starts with the list of all visible posts and then applies a set of filter rules to gradually filter out some of those posts until your search results are left.

This means if another plugin (or a config setting) has already filtered out some posts, the wpcfs search results will respect this and will also not show those filtered posts.

Debugging this could be tricky, and not really relevant here, but you could try de-activating all your plugins, switching themes, editing your posts to make sure they are published and public etc.

If the posts do display elsewhere on your public facing site but not in the wpcfs search results even with all filter fields removed then there is likely a conflict between the wpcfs plugin and another plugin or theme which you are using. The process for debugging this would be much like in situation 2, disabling different plugins and themes etc. to see which one allows the posts to display. If you can identify which other plugin is conflicting please let me know, if it’s a widely used plugin I will look to make sure we are compatible it in future.


== Installation ==
 
1. Unzip `wp-custom-fields-search.zip` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Either add a sidebar widget or include one of the presets via short_code or php code

== Screenshots ==

1.  Adding a custom search form in the widgets area
2.  How the previous search form might appear to your readers
3.  Each field is configurable for it's appearance and for how it filters the results
4.  Presets can be configured for embedding in themes, posts or pages
5.  Preset search forms can be set up in exactly the same way as widgets

== Changelog ==

= 1.2.35 =
* Fixes escaping issue reported by Darius Sveikauskas patchstack.com

https://patchstack.com/database/report-preview/d070e244-9f1c-4c4b-aa22-69baa3506272

= 1.2.34 =
* Fixed some incompatibilities with PHP8 and block themes

= 1.2.33 =
* Suppress warnings in the dropdown template

= 1.2.32 =
* Fixed incompatibility with newer jQuerys

= 1.2.31 =
* Updated PayPal account

= 1.2.30 =
* Regenerated translation files

= 1.2.29 =
* Some small refactoring for easier extension
* Fixed an issue with wp_query being rewritten for irrelevant queries

= 1.2.28 =
* Custom taxonomy dropdowns were being populated with values from other taxonomies https://wordpress.org/support/topic/custom-taxonomy-dropdown-show-all-category/

= 1.2.26 =
* Fixed some issues with checkbox / any matches.

= 1.2.26 =
* False alarm - reverted to 1.2.24

= 1.2.25 =
* Restored the old behaviour as there was a bug.

= 1.2.24 =
* Changed the search behaviour to only operate on the main query by default.  Previous behaviour was causing issues when the WP_Query object was used outside of the main loop.
* Added a filter to restore the old behaviour, to restore the old behaviour use: `add_filter('wpcfs_should_override_current_query', function() { return true; })` in your `functions.php` file
* Fixed a bug with pagination

= 1.2.23 =
* Fixed bug with post_type selector

= 1.2.22 =
* Added custom taxonomy datatype

= 1.2.21 =
* Added missing config page

= 1.2.20 =
* Added new action 'wpcfs_engine_loaded' for loading subclasses of the core search fields
* Added option to show only a subtree from a taxonomy

= 1.2.19 =
* Search results were not showing if the show_on_front option was set to a page.  Hopefully fixes https://wordpress.org/support/topic/is-the-plugin-compatible-with-avada-theme/

= 1.2.18 =
* Search results were not showing if the site_url pointed to a page.  Hopefully fixes https://wordpress.org/support/topic/is-the-plugin-compatible-with-avada-theme/

= 1.2.17 =
* Added post types config (https://wordpress.org/support/topic/is-the-plugin-compatible-with-avada-theme/)
* Fixed the problem with post fields always resetting when re-opening the edit form
* Fixed some javascript error messages

= 1.2.16 =
* Tested with latest wordpress beta
* Removed some warning messages

= 1.2.15 =
* https://wordpress.org/support/topic/fonction-in-range-not-working/

= 1.2.14 =
* Re-tested with Wordpress 5.3.2
* https://wordpress.org/support/topic/warning-count-parameter-must-be-an-array-4/ 

= 1.2.13 =
* Re-tested with Wordpress 5.2.3

= 1.2.12 =
* https://wordpress.org/support/topic/opening-tag-instead-of-closing-tag-in-code/

= 1.2.11 =
* Expanded the description as the plugin repository is cutting off the first line.

= 1.2.10 =
* Short codes were incorrectly implemented - https://wordpress.org/support/topic/invalid-header-26/

= 1.2.9 =
* Switched default form target to pull from the 'site_url' option as '/' was failing on some MU configurations - https://wordpress.org/support/topic/wrong-blog-url-on-multisite/

= 1.2.8 =
* Removed a warning message relating to static methods; https://wordpress.org/support/topic/line-440/
* Improved extension hooks for the angular admin app

= 1.2.7 =
* Fixed some issues with the JS extension code

= 1.2.6 =
* Fixed warning message when form unsubmitted.

= 1.2.5 =
* Added FAQ tab

= 1.2.4 =
* Fixes warning message for dropdowns with post_type field

= 1.2.3 =
* Fixes regressions from 1.2.2

= 1.2.2 =
* Added text domain to translation strings, hopefully fixes https://wordpress.org/support/topic/problems-with-localization-2/

= 1.2.1 =
* Correcting some issues with the README file

= 1.2.0 =
* Complete re-design of the admin interface
* Initial work towards a JS unit test suite

= 1.1.14 =
* Removed double slash from URLs, hopefully this will fix https://wordpress.org/support/topic/preset-page-blank/#post-9939623 and related issues.

= 1.1.13 =
* Added a warning that older version of IE (pre 11) are not supported

= 1.1.12 =
* Fixed another warning when no search inputs are configured

= 1.1.11 =
* Fixed a few warnings being shown
* Fixed an issue with field naming, not sure if this was affecting functionality

= 1.1.10 =
* Corrected the fix from 1.1.8 - was still crashing when form was submitted

= 1.1.9 =
* Fixed empty search results when MySQLi is installed but not being used.

= 1.1.8 =
* Fixed crash when invalid (or no) class specified in the config

= 1.1.7 =
* Fixed crash on systems without legacy MySQL extension
* Deals with Wordpress' magic quotes

= 1.1.6 =
* Fixed bug causing crashes in older PHP versions

= 1.1.5 =
* Fixed bug causing corruption of form configs on save

= 1.1.4 =
* Fixed bug whereby shortcodes were not working

= 1.1.3 =
* Fixed a translation issue crashing the widget editor

= 1.1.2 =
* Fixed an issue with the migration
* Added export option for debugging
 
= 1.1.1 =
* Fixed regression for old php versions

= 1.1.0 =
* Added multi-lingual support
* Added banners / icons for the wordpress repository
* No need to upgrade unless you plan to translate the plugin

= 1.0 =
* This is a major rebuild from 0.3.28, the rebuild should allow for easier extension and configuration
* If you are using bespoke or customised versions based on the 0.3 plugin those customisations will almost certainly not be compatible with this upgrade.
 
= 0.3.28 =
* Stable legacy version
 
== Upgrade Notice ==
 
= 1.1.5 =
* This fixes a serious bug introduced in 1.1.3, if you are using either 1.1.3 or 1.1.4 please upgrade.  Not upgrading may result in your form config being lost.

= 1.0 =
* This is a major rebuild from 0.3.28, this should make form configuration significantly easier.
* This will enable new extended functionalities.
* This has been tested against the latest versions of wordpress
* The shortcode format has changed, format is now [wpcfs-preset preset=x] where x is the id of the preset you wish to show
* If you are using bespoke or customised versions based on the 0.3 plugin those customisations will almost certainly not be compatible with this upgrade and you should proceed with caution or keep your installed version
 
= 0.3.28 =
This version simply adds a notice warning that the upgrade to 1.0.0 may break compatibility with historic extensions and inviting users to the beta release.
 
= 0.3.27 = 
Stable-ish for up to 2 years.
