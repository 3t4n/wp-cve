=== Plugin Name ===
Contributors: patopaiar, gicolek
Donate link: https://www.paypal.com/donate/?business=MP9PGN5BG65SL&no_recurring=1&item_name=Donate+to+the+development+of+ACF+Recent+Posts&currency_code=USD
Tags: widget, posts, recent, acf, meta keys, admin
Requires at least: 4.6
Tested up to: 6.2.2
Stable tag: 5.9.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

ACF Recent Posts Widget (ACFRPW) is a WordPress plugin which adds a custom, extended Recent Posts Widget - with ACF and Meta Keys support 

== Description ==

ACFRPW adds a new widget to the Appearance -> Widgets -> ACF Recent Posts Widget. Most of the fields are quite obvious, you can choose from a set of settings to display the posts from. 
Each setting alters the amount and type of posts listed in the sidebar.

[youtube https://www.youtube.com/watch?v=cv9BuKcfbhk]

= Available Settings =

The widget supports the following settings:

* Widget Title
* Custom Widget container CSS class
* Option to ignore sticky posts
* Search keyword query
* Id based post exclusion
* Date Display, Date Modified, Relative and Custom Date Format specifiaction
* Listing posts from specific date period (start and end)
* Listing posts with specific password, listing password protected posts only or excluding these
* Post Type selection
* Post Formats selection
* Post Statuses selection
* Listing posts limited to author via author id
* Order specifiaction	(ASC or DESC)
* Orderby specification (ID, Author, Title, Date, Modified, Random, Comment Count, Menu Order, Meta Value, Meta Value Numeric)
* Meta Key specifiaction (if Meta Value or Meta Value Numeric were chosen as orderby rule)
* Meta Comparison selection
* Meta Value Specification (for the Meta Comparison selection)
* Category limitation
* Tag limitation
* Id based custom taxonomy limitation
* Operator specifiaction for the above rules
* Number of posts to show
* Number of posts to skip
* Thumbnail display, thumbnail size (width, height), thumbnail alignment, default thumbnail
* Excerpt display, its word limit, its readmore text (occurs only if the amount of words exceeds the limit)
* Custom HTML to display before the loop
* Custom HTML to display after the loop 
* Custom HTML to display before each posts. It supports custom meta keys and ACF fields
* Custom HTML to display after each posts. It supports custom meta keys and ACF fields
* Custom HTML to display for no posts found
* Custom and default CSS
* Custom HTML templates 

= ACF supported fields =

The plugin has been tested with ACF 6 (Free and Pro).

The plugin supports the following ACF fields:

* Text
* Textarea
* Number
* Email
* Password
* Wysiwg Editor
* Image
* File

No other fields have been tested and are supported at the moment.

= Shortcode =

From version 4.4 the plugin supports shortcode embeds. Given the amount of options and their specific names (as in shortcode attributes) the shorcode builder has been introduced 
see 7th and 8th screenshots for the reference.

You'll see a popup once clicked on ACFRPW button which gives one an ability to automatically set up the shortcode code for you. The rest follows all of the options specified here.

To use the shortcode one needs to have the WordPress editor enabled for the current page / post type. In case it was disabled (say via ACF) the button won't appear.

= Creating Custom Templates =

From version 4.3 the plugin supports custom templates. To make usage of these one needs to make a copy of all the files found
in the acf-recent-posts-widget/templates directory and copy them over to the active template directory to acfrpw subdirectory.
The approach is similar to the way WooCommerce plugin works and has it's drawbacks. With every new feature the files may be outdated.

Similarly to the widget template files, a separate, custom markup can be created for the shortcode build post listing. Copy the acf-recent-posts-widget/template files
to acfrpw-blog directory created inside your active theme directory.

= Template files =

There are 3 main template files: 

* loop-after.php (which displays the markup after each of the posts and closes the markup container)
* loop-before.php (which displays the markup before each of the posts and opens the markup container)
* loop-inner.php (which is enqueued for each of the posts separatelly and contains the markup of every single post)

= Usage =

The usage is quite advanced hence each template file contains a quite detailed documentation on how to use it.
 
First of all the template files mustn't have the global variables removed. Each of these variables stores the widget settings, which are then used to generate the code.
Second of all the variable names are unobvious, loop-inner.php template file contains a list of all the names used, which are then extracted and available as php variables.


= Different template per widget / sidebar =

The templates allow one to adjust the markup of each single widget. One needs first to verify the widget id used, which may then be referenced.
The variable which stores the widget id is $acf_rpw_args['widget_id']. Dumping the value in the template is the best way to find out which automatic id has been created for your widget.

The best way to handle the templates is to learn from their code. My personal suggestion is to copy over the templates to the current theme and work directly on them, doing one change at a time.
The templates require learning curve to use and there's no single answer to everyones problem.

= Complex usage =

This section covers plugin complex usage for advanced user willing to have more control over the behavior of the plugin as well as explains uncommon functionalities.

= Using the HTML textarea fields =

These sections might not be obvious. The HTML or text before / after the whole loop setting is an area where you can specify custom HTML markup to be added before / after the whole posts list.
The HTML or text before / after each post is an area where you can not only specify custom HTML, but you are also given an ability to print any meta key or certain ACF fields (see <a href="#acf-support">ACF supported fields</a>)

= Meta Key Name / ACF Usage =

These fields need to be wrapped inside the {meta name} or {acf field_name} tags (which are similar to shortcodes). The plugin will then parse these fields and print their according value. Say we have a custom ACF field of type text, for which the Field Name is "text". 
To print its value one has to use [acf text] inside the befoe / after each post textarea. A similar solution applies to the meta key.

= Meta Value Usage =
The Meta Value field supports an array of elements, so that all of the meta_compare parameters could be used. To do so, please specify the two parameters separated by semicolon, for instance: 1234;12345.
The plugin will convert these into an array and apply the proper operation for the two. Whatsmore the [date] shortcode can be used here. It takes the timestamp paramater as an argument, which is required - the possible arguments are the same as for 
the function described here: http://php.net/manual/pl/function.strtotime.php. For instance [date +1 day] would generate the tomorrow date in "Ymd" format.
This can be used with custom meta field date. 

= Plugin Filters =

The are several filters that can be used to enchance the plugin programatically:

* 'acf_rwp_query' which gives one the ability to filter the query applied to each widget. 
* 'acp_rwp_before' which gives on the ability to filter the Front End output of the plugin before each post. There are two hooks attached here already which allow usage of the [acf] and [meta] shortcodes.
* 'acp_rwp_after' which gives on the ability to filter the Front End output of the plugin after each post. There are two hooks attached here already which allow usage of the [acf] and [meta] shortcodes.
* 'acf_meta_value' which filters the meta_value query parameter. 

Check the acf_widget_widget file for the arguments and use cases.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload and unpack `acf-widget.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag and Drop the ACF Recent Posts Widget to the sidebar area

== Frequently Asked Questions ==

= Does the Plugin require Advanced Custom Fields? =

No it doesn't. Some functionalities will be missing though and a notification will be shown to the user on Plugins dashboard page.

= What ACF version does it support? =

The plugin supports the latest ACF 6.X.X version.

= Does the Widget support author display? =

No it doesn't. We're considering this as an update.

= Does the Plugin support shortcodes, or custom posts function? =

Yes, the plugin does support shortcodes since version 4.4.

= Does the Widget come with any pre made classes to wrap the HTML with? =

No it doesn't.

= The widget styles are messy and the thumbnail doesn't adjust its position ? =

Please make sure to have the default styles checkbox checked.

== Screenshots ==

1. Installing the plugin
2. Meta Key placement (found at the bottom of each post / page edit screen)
3. ACF Field Name placement (found under Custom Fields section with ACF enabled)
4. First widget screen
5. Second widget screen
6. Third widget screen
7. Shortcode button
8. Shortcode creator screen
9. Custom templates placement

== Changelog ==

= 5.9.3 =
* Added Spanish language support.
* Ensured compatibility with ACF 6

= 5.9.2 = 
** Ensured compatibility with 6.2.2 WordPress Core

= 5.9 = 
** Ensured compatibilty with the Block Widget editor

= 5.2.5 =
** Add ability to specify text for no posts found **
* Introduced a new textarea field allowing one to specify empty query results HTML *

= 5.2.3, 5.2.4 =
** Compatbility Fix and Language Pack Updatess **

* Fix language packs *
* Ensured compatiblity with latest WP

= 4.6.2 =
** Compatbility Fix and Check **

* Fix issues with ACF image and file fields
* Ensured compatiblity with latest WP

= 4.6.1 =

**Shortcode improvement fix**

* Add an ability to use {meta} and {acf} fields within the shortcode, by changing the surrounding wrapper from [] to {}
to avoid built in shortcode conflicts
* Fix some PHP warnings and different case scenarios *

= 4.6 = 

**Compatibility release**

* assure plugin is stable with the latest WordPress version

= 4.5 =

* Remove survey settings tab - introduce options tab
* Add option to disable the shortcode button
* Add option to disable the "Missing ACF" notice
* Fix shortcode button styles in correspondence to other media buttons
* Add more descriptive text to the "Display Thumbnail" setting


= 4.4 =

* fixed meta compare bugs
* made all thumbnails post links
* introduced shortcode builder to embed the posts inside any post or page supporting Visual Editor

= 4.3 =

* introduce customizable plugin templates
* tweak a bug with similar name for two widget arguments causing a conflict

= 4.2.1 =

* revisit the plugin menu page

= 4.2 =

**Compatbility release**

* make sure the plugin is compatible with the latest WordPress version
* add plugin page (with a survey question)

= 4.1.6 =

**Bug Fixes**

* make sure the date can be hidden
* make sure the thumbnail can be hidden

**ACF Pro Beta Support**

* add option to print post modified date

= 4.1.5 =

**ACF Pro Beta Support**

ACF Pro is supported from now on. However due to possible differences between ACF 4
there is no guarantee of its support

**Bug Fixes**

* make sure there are no strict standards errors while printing the excerpt
* fix read more button not appearing due to sticky posts conflict
* make sure ignore sticky posts option works 

= 4.1.4 =

**Bug Fixes**

* Make sure there are no wrong constants used
* Fix undefined index issue in a number of cases

**New language support**

* French, by: wolforg / http://www.wptrads.com/extension/acf-recent-posts-widget/

= 4.1.3 =

**Meta Key options**

* add meta_compare query parameter
* add meta_value query parameter
* add ability to specify date shortcode inside the meta_value box

**Introduce plugin filters**

* add ability to filter the query using add_filter function

**Revamp the readme file**

**Introduce polish version**

= 4.1.2 =

**Small bug fixes**

* fix issue with plugin not enqueueing default styles in certain cases
* fix issue with date being displayed all the time
* improve readme

= 4.1.1 =

**Plugin first release**

== Upgrade Notice ==

= 4.1.5 =

**ACF Pro Beta Support**

ACF Pro is supported from now on. However due to possible differences between ACF 4
there is no guarantee of its support

**Bug Fixes**

* make sure there are no strict standards errors while printing the excerpt
* fix read more button not appearing due to sticky posts conflict
* make sure ignore sticky posts option works 

= 4.1.4 =

**Bug Fixes**

* Make sure there are no wrong constants used
* Fix undefined index issue in a number of cases

**New language support**

* French, by: wolforg / http://www.wptrads.com/extension/acf-recent-posts-widget/

= 4.1.3 =

**Meta Key options**

* add meta_compare query parameter
* add meta_value query parameter
* add ability to specify date shortcode inside the meta_value box

**Introduce plugin filters**

* add ability to filter the query using add_filter function

**Revamp the readme file**

**Introduce polish version**

= 4.1.2 =

**Small bug fixes**

* fix issue with plugin not enqueueing default styles in certain cases
* fix issue with date being displayed all the time
* improve readme

= 4.1.1 =

**Plugin first release**

== Dependencies ==

* <a href="http://www.advancedcustomfields.com/">ACF</a> (optional)
* <a href="https://github.com/gicolek/Widget-Base-Class">Widget Base Class</a> (included)
* <a href="https://github.com/gicolek/shortcode-popups">Shortcode popups generator</a> (included)

= Other =
* <a href="http://acfrpw-demo.wp-doin.com/">Online Demo</a> 
* <a href="http://wp-doin.com/portfolio/acfrpw/">Plugin site and Docs</a>
* <a href="http://wp-doin.com/2015/10/21/acf-recent-posts-widget-survey/">Plugin Development Survey</a>

= Languages Supported =
* English (default)
* Polish (since May 11 2015)
* French (since June 29 2015), by <a href="https://wordpress.org/support/profile/wolforg">wolforg</a>, <a href="http://www.wptrads.com/extension/acf-recent-posts-widget/">standalone source</a>
* Spanish (since July 30 2023)