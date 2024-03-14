=== Mark New Posts ===
Contributors: ilychkov
Tags: unread, new posts, unread posts, highlight
Requires at least: 3.3
Tested Up To: 6.4
Stable tag: 7.5.1
License: MIT


== Description ==

Highlight unread posts on your blog.

Key features:

 * Works right out of the box
 * Uses cookies, no authorization required
 * 4 different types of markers for highlighting posts (a "new" text label,
an orange circle, etc.)
 * Customizable background color for unread post titles

There's a setting to determine when a post should be considered read. You can pick one of the following:

 * after it was opened
 * after it was displayed in the post list
 * after opening any page of the blog

Functions available for theme developers:

 * mnp_is_new_post($post) - check if a post is unread
 * mnp_new_posts_count($query) - get unread posts count

== Frequently Asked Questions ==

= 1. How can I check that the plugin works? =

1. Install and activate the plugin;
2. Open your blog's main page;
3. Add a new post to your blog;
4. Open the main page once again. An orange circle should appear to the left of the new post's title.

= 2. Is it possible to highlight all the posts published in a recent few days for new visitors? =

Yes. Just set these two options:
 * *"A post only stays highlighted for N days after publishing"* -> set the amount of days
 * *"Show all existing posts as new to new visitors"* -> check

= 3. The plugin is exploding my page's markup. How to fix it? =

Try to enable the option *"Check page markup before displaying a marker"* (plugin options, advanced settings).

= 4. What do I need the mnp_is_new_post() and mnp_new_posts_count() functions for? =

These two functions may be useful for developing WordPress themes.
~~~~
mnp_is_new_post($post)
~~~~
Returns true if a specific post is unread, otherwise false.
Parameters: $post (optional) - post ID or object.
~~~~
mnp_new_posts_count($query)
~~~~
Returns the total number of unread posts, optionally filtered.
Parameters: $query (optional) - WP_Query query string.
Example:
~~~~
echo mnp_new_posts_count('cat=1');
~~~~
This will show the number of unread posts in category with id = 1.

== Screenshots ==

1. Marker type: "New" text (Twenty Twenty-One)
2. Marker type: "New" text (Twenty Twenty)
3. Marker type: Circle (old WP theme)

== Changelog ==

= 7.5.1 =
 * Fixed: "Allow outside the post list" option makes markup appear in admin post list

= 7.5.0 =
 * "Allow outside the post list" option
 * Cosmetic: fixed some of the texts on the settings page

= 7.4.0 =

 * Fixed a conflict with Buddypress. I hope now we can be buddies again. Thanks a lot to teeboy4real and r-a-y!
 * Improved performance
 * If you've read this far AND if you like this plugin, why not give it a nice review? It would be lovely!

= 7.3.2 =

 * Fixed a conflict with WPForms plugin. A message "Notice: is_404 was called incorrectly" appeared when trying to open the Settings page of WPForms.

= 7.3.1 =

 * The "New" marker type now has a better support for the old built-in WordPress themes, because they're awesome.

= 7.3.0 =

 * Post title background colour validation
 * Marker type "Custom image" removed for security reasons

= 7.2.0 =

 * An option to change the background colour for unread post titles
 * Open your blog with ?mnp-test=1 in the page title to test the plugin (all posts will be shown as unread)
 * *mnp-title-wrapper* class replaced with *mnp-unread*

= 7.1.0 =

 * "Disable for custom posts" option

= 7.0.1 =
 * Added a new colour for the "New" text to fit in the Twenty-Twenty WordPress theme. Now it's the default marker type.
 * Changed buttons placement in the settings screen. Yeah, I know... Sorry for that.
 * Fixed flickering pieces of code on blog pages that appear when the "Check markup" option is active

= 7.0.0 =

 * Back after 4 years of inactivity!
 * Temporary new logo
 * Fixed a CSS bug for Image and Flag marker types, where the marker would float away to the left of the screen
 * Fixed warnings in Debug mode

= 6.9.28 =
 * New translation: Russian
 * New option: mark posts as read after opening any page of the blog
 * New option: posts stay marked as new only for a certain amount of days after publishing
 * New option: mark all existing posts as new to new visitors
 * If the two options above are activated, then when someone visits your blog even for the first time, he will see all the most recent posts highlighted

= 6.9.26 =
 * Fixed notices in debug mode

= 6.9.22 =
 * mnp_new_posts_count() speed up

= 6.5.24 =
 * Fixed minor bug: 2nd argument might not be passed to the_title filter in some themes

= 6.5.12 =
 * Fixed blank screen when not running on Apache

= 6.5.10 =
 * Unicode flag marker replaced with an image (because of Unicode issues in Firefox)
icon by [Vectors Market](http://www.flaticon.com/authors/vectors-market) from [www.flaticon.com](http://www.flaticon.com), [CC BY 3.0](https://creativecommons.org/licenses/by/3.0/) license
 * Code refactoring and optimization
 * Better way of markup checking
 * Settings page redesign

= 6.5.6 =
 * Detect prefetching

= 6.5.5 =
 * New marker placement: before and after post title
 * Incorrect markup check is disabled by default to use less memory

= 6.5.4 =
 * Fixed: incorrect markup when the_title() is being called from an attribute value

= 6.3.18 =
 * "Mark posts as read only after opening" option now works for post excerpts too

= 5.6.4 =
 * New marker type: flag (unicode character)
 * New option: the marker can be placed before or after the title of a post
 * New marker type: custom image
 * Fixed bug: after opening a post's preview it's getting marked as read
 * Fixed bug: sometimes the marker falls on another line
 * Fixed: marker gets wrapped on new line in post's navigation block

= 5.5.12 =
 * i18n
 * Added "Mark post as read only after opening" option
 * New marker type: image. "Label New Blue" icon by [Jack Cai](http://www.doublejdesign.co.uk/), [CC BY-ND 3.0](https://creativecommons.org/licenses/by-nd/3.0/) license

= 5.5.8 =
 * This plugin is based upon [KB New Posts 0.1](http://adambrown.info/b/widgets/tag/kb-new-posts/) by [Adam R. Brown](http://adambrown.info/)
 * New functions for using in WordPress themes: *mnp_is_new_post* and *mnp_new_posts_count*
 * 2 new ways of highlighting unread posts