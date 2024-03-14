=== IntelliWidget Per Page Custom Menus and Dynamic Content  ===
Contributors: lilaeamedia, support00
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DE4W9KW7HQJNA
Tags: content aware, navigation menu, vertical menu, featured post, page menu, custom menu, taxonomy menu, custom post types, custom sidebar, dynamic sidebar, per page, widget area, dynamic widget, custom widget
Requires at least: 4.0
Tested up to: 5.2
Stable tag: 2.3.9.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display custom menus, featured posts, custom post types and other dynamic content on a per page, per post or site-wide basis.

== Description ==

IntelliWidget is a versatile WordPress plugin that makes your dynamic sidebars "page-specific." It allows you to create custom "Profiles" for any page or post that can be used where the default sidebar widget would normally appear. You set the rules for each profile to display custom widget content any way you want.

Use the shortcode option to put custom menus, featured posts and other dynamic content anywhere on your site.

Because it is generated using active titles and permalinks, your sidebar content is always current with the latest versions.

Select posts by title, date, category, tag, and many other ways. Combine with custom post types to create unlimited custom sidebar content. Combine with your favorite jQuery Slider plugin to display animated slideshows.

Reuse the settings from existing profiles to save hours of setup time.

With Per Page Menus and Featured Posts IntelliWidget you can add just a few widget areas and create unlimited per page custom menus and featured posts and other custom text.

[](http://coderisk.com/wp/plugin/intelliwidget-per-page-featured-posts-and-menus/RIPS-sL_vZYjKfO)
= Tabbed Profile Panels =

All of the IntelliWidget Profile settings panels are combined into a single tab menu on the Edit Post admin pages. You can hover over the tab to see the IntelliWidget the Profile replaces.

= Intuitive Settings Panels =

Settings have been organized into collapsible sections so the settings you use most can be found in one place.

Hover over any input label and more details describing the input will appear.

General Settings include a new "IntelliWidget Type," which controls whether a normal custom menu, featured post list, WordPress Navigation Menu, or taxonomy menu is displayed. Section title, unique id and classes fields are grouped together for easy access.

We have moved the "Template," "Post Types," "Specific Posts," and "Terms" menus to a "Post Selection" new panel to keep the most-used settings together in one place. Here you will also find the Post Custom Data Field Date condition checkboxes.

= Select Posts Using Any Taxonomy =

You can select posts based on Tags and Custom Taxonomies as well as Categories. You can also use multiple terms in the same profile.

You can control the way posts are sorted, post sort order, number posts shown, excerpt length, HTML filters, image size and image alignment.

You can add a block of text or HTML and control where it appears relative to the custom menus and featured posts. You can even use any shortcodes your theme supports.

= Works with the Customizer =

Test adjustments to your custom menus and featured posts in real time using the Customizer. See how each setting makes any dynamic sidebar content aware by viewing actual pages and posts. 

= Written for stability and performance =

Database queries are optimized and cached. Admin functions are only loaded on demand. Long menus are loaded dynamically to reduce admin page size.

= Actions and Filters =

We've made IntelliWidget extensible by utilizing action and filter hooks at key points of the execution.

= Introducing IntelliWidget Pro =

Now you can have custom IntelliWidgets on any Archive page! Choose the criteria and customize the Profiles for Blog pages, Categories, Tags and more.
See http://www.lilaeamedia.com/plugins/intelliwidget-pro for more information.

= Coming Soon to Pro: IntelliWidget Template Configurator =

* Create your own custom templates right from the IntelliWidget admin.

= More Reasons to use IntelliWidget to add Custom Menus, Featured Posts and Dynamic Sidebar Content =

* Powerful and extensible, does the work of multiple plugins
* No new dynamic sidebars necessary - uses the dynamic sidebars you already have
* Displays page-specific content aware custom menus and featured posts and other dynamic content
* If settings are not set up on a page or post it will default to site-wide widget settings
* Supports Custom Post Types and Custom Navigation Menus
* Doubles as a Text Widget customizable to any page
* Works with the theme customizer
* Use as many different custom menus or featured posts on a single page as you wish
* Saves hours setting up and maintaining your WordPress site
* Set up one page and reuse settings on other pages
* No new database tables
* Lets you keep using all your other widgets
* Shows or hides dynamic sidebar content by date and time

IntelliWidget is flexible and versatile. Please read the documentation to see more ideas how to use it to its full potential.

Spanish translation courtesy of Andrew Kurtis at WebHostingHub.com.
 
== Installation ==

1. To install from the Plugins repository:
    * In the WordPress Admin, go to "Plugins > Add New."
    * Type "intelliwidget" in the "Search" box and click "Search Plugins."
    * Locate "Per Page Custom Menus Featured Posts IntelliWidget" in the list and click "Install Now."

2. To install manually:
    * Download the IntelliWidget plugin from http://wordpress.org/plugins/intelliwidget-per-page-featured-posts-and-menus
    * In the WordPress Admin, go to "Plugins > Add New."
    * Click the "Upload" link at the top of the page.
    * Browse for the zip file, select and click "Install."

3. In the WordPress Admin, go to "Plugins > Installed Plugins." Locate "Per Page Custom Menus Featured Posts IntelliWidget" in the list and click "Activate."

4. Follow the "Quick Introduction" below.

== Frequently Asked Questions ==

= Is there a quick tutorial? =

See the "Getting Started" section or watch the Quick Start Tutorial:

http://www.youtube.com/watch?v=Ttw1xIZ2b-g

= Where can I find full documentation? =

Docs can be found at http://www.lilaeamedia.com/plugins/intelliwidget/

= How do I put a custom menu on one specific page? =

*Method 1:*

 * Add a new IntelliWidget to one of your widget areas on the Widgets Admin. Check the "Placeholder only" box. This keeps the widget from appearing on all the pages.
 * Go to Pages and click the page to edit.
 * You will see a new meta box labeled "IntelliWidget Profiles."
 * Click "+ Add New Profile."
 * Select the dynamic sidebar ( widget area ) to which you added the IntelliWidget in the first step from the "Parent Profile to replace" menu.
 * From the "Post Selection" panel, choose the "Menu" template.
 * Select the posts for your custom menu from the "specific posts" multi-select menu.
 * Click "Save Settings."
 
*Method 2:*

 * If you have a custom menu already set up in the "Appearance > Menus" you can use it instead of building it from scratch.
 * Follow the steps as before.
 * In the Child Profile, open the "General Settings" panel. Select "Nav Menu" from the "IntelliWidget Type" menu.
 * Select the custom menu you want to use from the "Menu to use" select menu.
 * Click "Save Settings."
 
*Method 3:*

 * Use the IntelliWidget Shortcode on the page. You don't need a Placeholder to use this option.
 * Set up a new Child Profile on the page like usual, but select "Shortcode in Post Content" as the "Profile to replace" option.
 * In the post content, add the shortcode [intelliwidget section=#] where # is the number of the Child Profile tab.

= Where do I put custom templates/stylesheets? =

Here are the steps:

1. Add a directory in your theme named "intelliwidget".
2. Create a copy of "intelliwidget.css" (located in the "templates" directory of the plugin) and drop it into this directory.
3. Adjust the styles as necessary.
4. Review the documentation for more information:
    * Templates: http://www.lilaeamedia.com/templates
    * Stylesheet: http://www.lilaeamedia.com/intelliwidget-stylesheet

= Why isn't IntelliWidget displaying the featured image? =

By default, IntelliWidget does not display the featured image. To enable the featured image,
open the "Appearance" panel in the IntelliWidget settings and choose an image size from the "Image Size" select menu.

= Why are posts showing and hiding several hours before or after the time I entered? =

Dates are calculated using the WordPress current_time() function. Make sure you have set the correct
timezone under Settings > General in the WordPress admin.

= Why isn't my Custom Post Type appearing as an option? =

Custom Post Types must support custom data fields (post meta data) for IntelliWidget to recognize them. 
Change the 'supports' parameter in the register_post_type function to include 'custom-fields', e.g.,

    'supports' => array( 
      'title', 
      'editor', 
      'excerpt', 
      'thumbnail', 
      'author', 
      'custom-fields', 
      'revisions', 
    ),

= What does "Content Aware" mean? =

Content aware means plugin decides what to display based on the other content on the page. In other words, WordPress determines how to assemble the page based on the request it receives. IntelliWidget uses the same logic and compares each IntelliWidget profile to the "queried object." It then builds the dynamic sidebar output using this "content aware" logic.

== Screenshots ==

1. Example of the Widgets Admin Panel.
2. Example of the Edit Post Admin Panel.
3. Example of the Profiles Panel.
4. Example of the Post Selection Panel.
5. Example of the Custom Data Fields Panel.

== Changelog ==
= 2.3.9.2 =
* Fix array initialization for PHP 7.3
= 2.3.9.1 = 
* Fix: test for $instance[ 'terms' ]
* Fix: do not allow post to "Use profiles from" itself.
* Fix: verify post object exists before checking for alt title
= 2.3.8.2 =
* Fix: Logic flaw in get_posts_list query function not returning all selected posts 
= 2.3.8.1 =
* Fix: Use profiles from other posts broken in 2.3.8
= 2.3.8 =
* Fix: include template tags for admin
= 2.3.7.5 =
* Fix: added test for admin object before rendering form
= 2.3.7.4 =
* Tweak: Using get_queried_object in Query class instead of global $post in case secondary query was not reset
= 2.3.7.3 =
* Tweak: added test for existence of actions in case themes do widgets_init action is incorrectly called by a theme.
= 2.3.7.2 =
* Fix: Added unique handles for custom stylesheets that were not being loaded.
= 2.3.7.1 =
* Fix: added default argument for post_title to eliminate warnings when the_title filter is applied without second argument.
= 2.3.7 =
* Added ability to apply Nav Menus to Menu Location on per page basis. 
= 2.3.6 =
* Fix: asort breaking on PHP < 5.4
* Added German language support
= 2.3.5 =
* Refactored for compatibility with IntelliWidget Pro 2.0.0. 
* Fix: suppress javascript log output
* Fix: Query class generating taxonomy clause when there are no terms
= 2.3.4 =
* Replaced missing orderby clause in iw_query function. Disabled query caching for now.
= 2.3.3.1 =
* Fixes for legacy shortcode options
= 2.3.2 =
* Refactored class structure and implemented autoloading
* Fixed rendering issues with new 2-column select menus
* Fixed custom data fields not saving
* Refactored queries to match child terms of each selected term
* Added query caching
* Added new profile options: "Do not link images" and "Do not use Alt Title"
= 2.3.1 =
* Fixed bug with shortcodes that was not apparent before the WP 4.3 release.
= 2.3.0 =
* Updated widget object constructor from obsolete PHP 4 syntax.
* Improved customizer widget admin functionality.
* Added two-column multi select menus for specific posts and terms with text search.
* Now menus can handle sites with thousands of posts without overloading the admin.
* Added better keystroke handling in profile area.
* Fixed select posts from current term option
* Improved global variable handling. 
* Reorganized and streamlined ajax calls

= 2.2.2.2 =
* Fix to include children in current term

= 2.2.2.1 =
* New feature - include current term in featured post results

= 2.2.1 =
* Updated strings class
* Cleaned up minified admin script

= 2.2.0 =
* Reorganized form includes into single class.

= 2.1.9 =
* Added any/all option to term selection
* replaced the_content filter with custom filter for excerpts 

= 2.1.8 =
* Check if admin script is enqueued to prevent multiple instance of localization object
* Added link boolean to get_the_intelliwidget_image args to allow featured images with or without links to post
* Default is TRUE (link image)

= 2.1.7.1 =
* Bug fixed - corrected clear attribute in intelliwidget-title style.

= 2.1.7 =
* Bug fixed - added hndle selector to metabox h3 to accommodate recent change to postbox.js in WP core.

= 2.1.6 =
* New Feature - Added get_the_intelliwidget_postmeta() and the_intelliwidget_postmeta() template functions

= 2.1.5 =
* Bug fixed - Shortcode not pulling "Use Profiles From..." values.

= 2.1.4 =
* Bug fixed - fatal error when widget form loaded outside of widgets admin page.

= 2.1.3 =
* Bug fixed - menu template: get_the_ID creating endless loop when using shortcode.
* New Feature - Hide Title - allows title to be entered to identify widget in admin without showing in output

= 2.1.2 =
* Bug fixed - not loading child profile meta box on new posts.
* Bug fixed - not loading IntelliWidgetAdmin class when ajax call does not set is_admin before plugin execution

= 2.1.1 =
* Bux fixed - incorrect object reference in class-intelliwidget-metabox causing taxonomy menu to fail on child profile

= 2.1.0 =
* New feature: taxonomy menu content option. Creates menu of terms from any taxonomy with multiple config options.

= 2.0.5 =
* New feature: include private posts option (visible to users that can read private posts) (thanks support00)
* New feature: setting excerpt length to first instance of <!--more--> if present or max words of not (thanks wakibu)
* "Use Profiles From" menu now only shows posts that have existing IntelliWidget profiles
* Fixed bug not saving multi-select values when no options selected
* Fixed bug not showing IW meta boxes on post types other than page/post 

= 2.0.4 =
* Fixed bug affecting php 5.3 and lower that was causing get_meta() to return a truncated value when the value was a scalar (thanks aschaevitz)
* Made all eligible post types available in the "Use Profiles from" menu

= 2.0.3 =
* Fixed save_post action that was incorrectly failing nonce validation for post types other than post and page (thanks janvbear)
* Moved Widget form object init to admin constructor

= 2.0.2 =
* Fixed incorrect Navigation Menu option values on both Parent and Child Profile forms.

= 2.0.1 =
* Overhauled most of the code to simplify logic flow and enable filter and action hooks for extensibility
* Replaced 'Categories' with 'Terms' to include any taxonomies associated with selected post types
* Reorganized User Interface to be more intuitive
* Added new 'content' option to allow action hooks to replace default content
* Added autoloading to reduce memory footprint

= 1.4.6 =
* Spanish translation courtesy of Andrew Kurtis at WebHostingHub.com
* Changed per page (child) profile to run only if is_singular (excluding search, archive and date queries)

= 1.4.5 =
* Optimized left joins to avoid max_join_size error
* Simplified date range options and behavior - see Additional Notes.

= 1.4.4 =
* rolled back array_walk_recursive function in prep_array() due to incompatibility with 5.4
* fixed regression bug in custom widget admin text inputs

= 1.4.3 =
* Added kses filter for all text inputs
* Modified id of post_types checkboxes to eliminate odd checking/unchecking behavior
* Added esc_attr filter to title tags on links
* Refactored prep_array function to flatten multi-dimensional arrays before passing values to trim()

= 1.4.2 =
* Fixed the inner postboxes so they cannot be dragged outside of the IntelliWidget options panel.
* Fixed the query class to account for empty post_type, category and specific post selections
* Added the section id to the inner postbox handles
* Moved the event delegation outside of the XHR response so that events are correctly bound to newly injected meta boxes

= 1.4.1 =
* Fixed bug in sql class that broke shortcodes in 1.4.0

= 1.4.0 =
* Secured SQL in query class via prepare()
* Added allowed_tags parameter to advanced custom widget options so that html attributes can be preserved in excerpts
* Now loading custom stylesheet as well as and default stylesheet so that only override and new styles need to be added
* Added get_the_intelliwidget_author_meta function to template tags to retrieve author info
* Reorganized get_template to check child theme, then parent theme, then plugin templates directory for files

= 1.3.9 =
* Fixed input field names intelliwidget_link_classes and intelliwidget_link_target so that they save correctly
* Modified query class to retreive expire_date for all queries
* Added new "multi-date" template to display start and end dates for multi-date events

= 1.3.8 =
* Fixed case where IntelliWidgets are orphaned after theme change. 
* Updated to support WP 3.7
* Tweaked img class to set height:auto
* Added new "albums" template

= 1.3.6 =
* Update to metabox for pages

= 1.3.5 =
* Added metaboxes (settings panels) for all eligible post types, not just pages.
* Fixed "Show All" in the query class (it was showing the default 5 instead of "all")

= 1.3.4 =
* Fixed missing global scope on intelliwidget object. Added "widget_intelliwidget" class to shortcode widget wrappers.

= 1.3.3 =
* Added Page Content option to shortcode. Now you can specify an IntelliWidget Section to get the parameters instead of passing them as args.

= 1.3.2 =
* Test if $post is object before attempted to get ID attribute to prevent error notice.

= 1.3.1 =
* Changed method signature of Walker_IntelliWidget::start_el() to match Walker::start_el() to avoid Strict Standards notice in WP 3.6

= 1.3.0 =
* Added intelliwidget-current-menu-item style for items linked to current page
* Added "Override copied settings" feature. You can now keep some or all of the settings sections from a page and copy the rest
* Added "events" template and corresponding styles to intelliwidget.css

= 1.2.6 =
* Fixed bug in "skip current post" (thanks Markus)
* Section Settings Specific Posts Menu now refreshes on save

= 1.2.5 =
* Fixed random sort order bug (thanks Joshua)
* Strip tags from title attribute text on links
* Fixed conditional in skip_expired query

= 1.2.4 =
* Fixed SQL bug that incorrectly joined taxonomies. (thanks AMoy)
* Cleaned up _get_the_intelliwidget_excerpt to strip all but text.

= 1.2.3 =
* Fixed SQL bug that caused duplicate results in the post data. (thanks MNolte)
* Cleaned up i18l functions and created new .pot file.

= 1.2.2 =
* Fixed bug that created invalid path to the admin JavaScript include on Windows
* Added Page Menu as option for Navigation Menu (to automatically generate menu from pages)
* Added ID field option to override default 'intelliwidget' id

= 1.2.1 =
* Fixed bug that horked the query generator when no specific posts are selected
* Renamed the url to array function to prevent JS namespace collisions

= 1.2.0 =
* Supports WordPress Custom Navigation Menus as well as IntelliWidget menus
* Support for IntelliWidget Shortcode
* Support for Event Date and Expire Date on posts
* Hierarchical Select Menus
* Hide Expired post option
* Show only active posts option
* Changed the way IW treats event dates (now separate custom data field)
* Added Query Class separate from The Loop to retrieve all data in a single database call
* Reduced the number of database queries per custom widget instance
* Changed custom data field names so they don't conflict with existing data fields
* Refactored template tags to be more efficient
* Added Custom Data Fields meta box for easy editing of postmeta fields (especially dates)

= 1.1.0 =
* Improved the overall interface.
* Fixed bug in the way IW saves content aware per page custom widgets 

= 1.0.2 =
* Ajax submits on Edit Page
* Commented out &raquo; on read more link

= 1.0.1 =
* Form areas collapsible to save space

= 1.0.0 =
* Combined favorite functionality from different plugins into a powerful content aware custom menu and featured post plugin.
* Packaged for public consumption

== Upgrade Notice ==
2.3.7.5 Fix: added test for admin object before rendering form

== Getting Started ==

Here is a very simple example that illustrates the basics: a Parent Profile replaced by a Child Profile.

1. Start by dragging the widget labeled "IntelliWidget" over to one of your existing sidebars. The new IntelliWidget panel will open revealing the various settings groups. This is now the "Parent Profile" for this IntelliWidget instance.

2. Open the "Post Selection" panel by clicking the bar. You will see a multi-select menu containing all of your pages and posts.

3. Hold down the option key ("Command" on Mac) and select a few of your pages and click the "Save" button at the bottom of the widget panel.

4. Load a page from your site in a browser that uses the sidebar you just modified. You will see a custom menu of the pages you added in the previous step. This is the default behavior: a custom menu of page links sorted by title.

5. In the WordPress Admin, go to "Pages" and select the page you just viewed. You will now see a new meta box labeled "IntelliWidget Profiles."

6. Click "+ Add New Profile." A tabbed panel will appear containing settings almost exactly like the ones in the Widgets Admin.

7. Click the "Parent Profile to Replace" dropdown menu and you will see an option for the sidebar where you added the IntelliWidget Parent Profile. Select this option.

8. Open the "Additional Text/HTML" Panel by clicking the bar.

9. Select "This text only (no posts)" option in the "Display" dropdown menu.

10. Type some text in the "Custom Text/HTML" textarea. Click "Save Settings."

11. Now load the page you just edited in your browser. Instead of the custom menu from before, you now see the new title and the custom text you typed. If you go to any other page that uses the same sidebar, you will see the custom menu from before.

12. Finally, you can select this page in the "Use Profiles From" menu from any other page to re-use these settings. This is useful if you have a sub-set of pages that need to re-use the same dynamic sidebar content.
    
Checking "Only Future Posts" excludes posts with a Start Date < current date/time, regardless of Expire Date. (Formerly "Only Future Events.")
Checking "Exclude Future Posts" hides posts with a Start Date > current date/time, regardless of Expire Date. (Formerly "Only Active Events.")  
The "Exclude Expired Posts" behavior has not changed.

== Documentation ==

Can be found at http://www.lilaeamedia.com/plugins/intelliwidget/

Interested in translating? Contact us at http://www.lilaeamedia.com/about/contact/
