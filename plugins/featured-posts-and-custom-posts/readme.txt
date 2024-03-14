=== Plugin Name ===
Contributors: jeremyselph
Donate link: http://www.reactivedevelopment.net/snippets/featured-posts-custom-posts
Tags: posts, featured, custom posts, custom post types
Requires at least: 3.1.1
Tested up to: 4.1.1
Stable tag: 5.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Allows the user to feature posts and custom posts. When a post is featured it gets the post metta _jsFeaturedPost.

== Description ==

When working on WordPress themes, we tend to use a lot of custom post types. This gives the end- user custom little sections to manage content in. Often enough we see the need to be able to feature posts or custom posts of custom post types (cpts).

This can be accomplished by using a bunch of different Featured Posts Plugins. And we probably have used all of them over the years. To date we have yet to find one that didn't conflict or break somehting in our WordPress themes. So we created our own, to be specifc we created our own to be easily used with Posts, Custom Posts, and with the Simple Page ordering plugin that we install on every WordPress Theme that we develop.

To use:

1. Download the feature-posts-and-custom-posts.zip file to your computer.
2. Unzip the file.
3. Upload the feature-posts-and-custom-posts folder to your /wp-content/plugins/ directory.
4. Activate the plugin through the Plugins menu in WordPress.
5. In your theme and in the template you need the featured functionality uses a custom query like this query_posts(&quot;post_type=professionals&posts_per_page=-1&meta_key=_jsFeaturedPost&meta_value=yes&quot;); to grad all of the featured posts.

For Plugin and Theme development requets email us at info@reactivedevelopment.net or go here http://www.reactivedevelopment.net/. If you have questions or requests for this plugin go here http://wordpress.org/support/plugin/featured-posts-custom-posts, for quick and paid support message us here at http://www.reactivedevelopment.net/contact/send-message/.

New in Version 2.0
1. New js_featured_is_post_featured( postID[int] ) function
2. short cut is_post_featured( userID[int] ) function
3. js_featured_return_all_featured() function that returns an array of featured posts
4. New widget added
5. Post class "jsFeatured" on archive templates added if the post is featured
6. New shortcode [jsFeaturedPosts posts_per_page="1" wrap_before="<ul>" wrap_after="</ul>" link_before="<li>" link_after="</li>" link_atts="rel='bookmark'" link_title="Link to"]

== Installation ==

1. Download the feature-posts-and-custom-posts.zip file to your computer.
1. Unzip the file.
1. Upload the feature-posts-and-custom-posts folder to your /wp-content/plugins/ directory.
1. Activate the plugin through the Plugins menu in WordPress.
1. In your theme and in the template you need the featured functionality uses a custom query like this query_posts(&quot;post_type=professionals&posts_per_page=-1&meta_key=_jsFeaturedPost&meta_value=yes&quot;); to grad all of the featured posts.

== Frequently Asked Questions ==

= Coming Soon =

Let me know what questions you have!

== Screenshots ==

Screenshots coming soon.