=== Flex Posts - Widget and Gutenberg Block ===
Contributors: tajam
Donate link: https://tajam.id/
Tags: category posts, responsive, magazine, news, grid, list, tiles, flexbox, recent posts, latest posts
Requires at least: 5.2
Tested up to: 6.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A widget to display posts with thumbnails in various layouts. Fits nicely in any widget area size.

== Description ==

Flex Posts is a widget to display posts in various different layouts. It is useful for a news site where you need to display a lot of posts in a page.

The widget is responsive so you can place it in any widget area. The widget content will adapt based on the width of its container. In a narrow area like standard sidebar, posts will be displayed vertically, but in a wider area, posts will be displayed in 2 or 3 columns depends on the container's width.

= Widget Settings =

* **Title**: Set the widget title. Leave it empty to hide the title section.
* **Title URL**: Set the title link url. Leave it empty to disable link in the title.
* **Layout**: Select a widget layout, from layout 1 to 4.
* **Post type**: Select the post type. Options include: Post, Page, custom post types if available, and any.
* **Category**: Select a category for the posts, or choose All Categories to disable this filter.
* **Tag(s)**: Set a post tag (using the tag slug). You can also use comma separated value for multiple tags. Prepending a tag with a hyphen will exclude posts matching that tag. Eg, `featured, -video` will show posts tagged with `featured` but not `video`.
* **Order by**: Set the order in which the posts will be displayed. Options include: Newest, Oldest, Most Commented, Alphabetical, Random, Modified Date.
* **Number of posts to show**: Set the number of posts displayed.
* **Number of posts to skip**: Set the number of posts to displace or pass over.
* **Exclude current post**: Choose to show or hide current post.
* **Show image on**: Select in which posts the image will be displayed. Options include: All posts, First post only, or none.
* **Image size**: Select image size from registered image sizes.
* **Show post title**: Choose to show or hide the post title.
* **Show categories**: Choose to show or hide the categories.
* **Show author**: Choose to show or hide the author.
* **Show date**: Choose to show or hide the date.
* **Show comments number**: Choose to show or hide the comments number.
* **Show excerpt**: Choose to show or hide the excerpt
* **Excerpt length**: Set the number of words for the excerpt.
* **Show read more link**: Choose to show or hide the Read More link.
* **Read more text**: Set the text for the read more link. You can leave it empty to use the default text `Read More`.
* **Show pagination**: Choose to show or hide the pagination links.
* **Block title HTML element**: Choose the HTML element for the block title. Options include: h1, h2, h3, h4, h5, h6, div, p, span.
* **Post title HTML element**: Choose the HTML element for the post title. Options include: h1, h2, h3, h4, h5, h6, div, p, span.
* **Additional class(es)**: Set a custom class for the widget container. You can use spaces to separate multiple classes.

= Gutenberg Block =

Since version 1.1.0, Flex Posts also includes a gutenberg block. You can add the widget directly into the post/page content with the WP 5.0 block editor.

= Demo =

Please visit the live demo here: [Flex Posts Demo](https://tajam.id/flex-posts-demo/)

= Requirements =

This plugin has been tested and works with at least PHP 5.3 installed in your environment. But we strongly recommend you to use the latest PHP version, as using older versions may expose you to security vulnerabilities.

== Installation ==

1. Upload the `flex-posts` directory to the `/wp-content/plugins/` directory.
2. In your WordPress dashboard, go to Plugins, search for Flex Posts and click Activate.
3. Go to Appearance » Widgets to add Flex Posts widget into your widget area.
4. You can also insert the widget from post/page edit screen. Click Add block button, go to Widgets section, and click Flex Posts to add the widget into your content area.

== Frequently Asked Questions ==

== Screenshots ==

1. Widget settings
2. Widget settings / Filter
3. Widget settings / Display
4. Layout 1 in sidebar
5. Layout 1 in content area
6. Layout 2 in sidebar
7. Layout 2 in content area
8. Layout 3 in sidebar
9. Layout 3 in content area
10. Layout 4
11. Block editor

== Changelog ==

= 1.9.0 =
* Fixed style not loaded on widget
* Fixed layout 3 style in full width page
* Added post title element option
* Added block title element option
* Added exclude current post option
* Added custom order option

= 1.8.1 =
* Fixed widget admin script
* Added minified style

= 1.8.0 =
* Fixed excerpt function
* Fixed block deprecated warnings
* Added show post title option
* Added rtl style
* Added image size option
* Added additional classes option in widget
* Added conditional logic to the widget/block settings
* Added disable links in the editor
* Changed Filter label to Query

= 1.7.1 =
* Fixed language file path

= 1.7.0 =
* Fixed the_date filter bug
* Added order by modified date
* Added any post type

= 1.6.0 =
* Fixed excerpt length value bug
* Fixed compatibility with older WordPress 5.x versions
* Added alignwide and alignfull support
* Optimized query results
* Updated block registration to init hook

= 1.5.0 =
* Fixed styles for WordPress default themes
* Fixed other styling issues
* Fixed a notice in thumbnail display
* Updated compatibility to WordPress 5.3
* Updated widget css load to lower priority
* Updated args filter hook

= 1.4.1 =
* Fixed css not loaded correctly

= 1.4.0 =
* Added title URL
* Added excerpt length
* Added read more
* Added pagination
* Added exclude tags
* Updated order by random to work with skip
* Removed post number & skip limit

= 1.3.0 =
* Added post type option
* Added show image option
* Added a filter hook to set template directory
* Updated template tags to make the functions pluggable

= 1.2.0 =
* Added a new layout (Layout 4)
* Added filter by tags
* Added order by random
* Added a filter hook to modify block title
* Updated image size filter hook
* Updated row class & styling (changed fp-posts to fp-row)

= 1.1.1 =
* Fixed excerpt display function

= 1.1.0 =
* Added gutenberg block
* Fixed some style issues

= 1.0.1 =
* Fixed compatibility with old php versions

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==
