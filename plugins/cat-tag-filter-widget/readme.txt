=== Plugin Name ===
Contributors: ajayver
Donate link: http://goo.gl/SCdKg
Tags: filter, tags, categories, widget
Requires at least: 2.8
Tested up to: 3.4.2
Stable tag: 0.9.1

This plugin adds a widget to your WordPress site that gives your visitors an ability to filter all your posts by a category or/and tag.

== Description ==

If you were searching for an easy way to let your WordPress site visitors to filter your content by a category and several tags in the same time, this plugin will help you a lot. It will add a widget to your widgets admin page, where you can edit the settings and put it in any "widgetized" place on your website.

This plugin will be very useful for websites with hundreds of categories and tags. I wrote it for my travel blog where we have categories for places that we'd been and tags for topics like video, photo, useful, mountains, beaches e.t.c.
So I wanted to give my visitors an ability to easily filter content by any category plus tag, like **category India + tag Video + tag Mountains**. WordPress has the ability to show such pages, we just need to pass the proper address to it. 

If your WordPress template doesn't support widgets, please see the `Installation` tab, there are some instructions on how to manually add this widget in your template files. 

== Installation ==

1. Go to your Wordpress admin dashboard -> Plugins -> Add new, then search for **Cat + Tag Fliter** and agree to install it.
2. Go to youк widget options and change the widgets settings.
3. Sometimes it is needed to manually flush rewrite rules. Go to your Permalinks Options and change it to something else, save, and then change it back to normal.

If it didn't work, try this:

1. Upload `cat-tag-filter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to your widgets settings and add Cat + Tag Filter widget to your sidebar.

If your theme doesn't support widgets, you can use this code:

`<?php the_widget('cat_tag_filter', $instance, $args); ?>`

Here is a full list of default $instance arguments:
`'title' => 'Filter'
'button_title' => 'Show posts'
'cat_list_label' => 'Show posts from:'
'tag_list_label' => 'With tag:' 
'all_cats_text' => 'Any category'
'all_tags_text' => 'Any tag'
'cats_count' => 1 
'tags_count' => 0 
'tag_logic' => 1 // 1 for AND and 0 for OR logic operator 
'tag_type' => 1 // 1 for checkboxes and 0 for dropdown 
'clude_tags' => 'exclude' //can be 'include' or 'exclude'
'exclude_tags' => '' //used for including tags also, if clude_tags is set to 'include'
'clude_cats' => 'exclude' //can be 'include' or 'exclude'
'exclude_cats' => '' //used for including categories also, if clude_cats is set to 'include'
'tag_tax' => 'post_tag', 
'category_tax' => 'category'
`

If you want to override some settings, for example get rid of title and turn on the counter for tag list use this code:

`<?php the_widget('cat_tag_filter','title=&tags_count=1'); ?>`

If you want to get rid of div's that WordPress creates before and after all the widgets, use this code:

`<?php the_widget('cat_tag_filter','title=','before_widget=&after_widget='); ?>`

You can also override `before_title` and `after_title` the same way.


== Screenshots ==

1. This is how the plugin looks in twentyten, almost no styling.
2. This is how it looks on my blog
3. These are the widget options

== Frequently Asked Questions ==

None.

== Changelog ==

= 0.9.1 =
* fixed 404 on pages

= 0.9 =
* Added permalinks support. If it doesn't work - flush rewrite rules!

= 0.8.4 =
* Added an option to switch off corresponding tags mode.
* fixed layout in categories with no corresponding tags.
* fixed corresponding tags for categories with more than one page of posts.


= 0.8.4 =
* fixed layout in categories with no corresponding tags.

= 0.8.3 =
* fixed a bug with tags including\excluding.

= 0.8 =
* Widget shows only corresponding tags for a chosen category.
* It is now possible to include categories and tags instead of excluding
* Added some comments to the code
* Added option for hiding category dropdown list

= 0.6 =
* Added custom taxonomies support (beta).

= 0.5 =
* Added static front page support.

= 0.4 =
* Added options to exclude tags or categories

= 0.3.3 =
* Fixed bug when no tags chosen

= 0.3 =
* New option added: "Show tags as checkboxes". Now users can choose several tags.
* Added the donation button in widgets options. Please consider donating! Thank you!

= 0.2.2 =
* Plugin's widget now retains the selected category and tag.

= 0.2.1 =
* Fixed the output of all tags in the page source code. Please update ASAP, because this bug was inserting a big amount of text in your page source code - very bad for your website indexing by search engines.

= 0.2 =
* Fixed the "no valid header" error during plugin installation.

= 0.1 =
* Plugins first publication


== Upgrade Notice ==

None.