=== Plugin Name ===
Contributors: wescleveland
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RM26LBV2K6NAU
Tags: user capabilities, user roles, scroll, mobile, post type, post category, post categories, post tag, post tags, body, class, custom CSS, CSS, custom Body Classes, wp_is_mobile, is_home, is_front_page, is_blog, is_admin, is_admin_bar_showing, is_404, is_super_admin, is_user_logged_in, is_search, is_archive, is_author, is_category, is_tag, is_tax, is_date, is_year, is_month, is_day, is_time, is_single, is_sticky, is-mobile, is-tablet, is-phone, mobile_detect
Requires at least: 4.0
Tested up to: 5.8
Stable tag: 2.1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add unique classes to the body tag for easy styling based on various attributes (archive, user, post, mobile, scrolling) and WordPress "is" functions. In adition you can add unique classes to individual posts.

== Description ==

Add unique classes to the body tag for easy styling based on post attributes (post type, slug, and ID) and various WordPress "is" functions:

 - wp_is_mobile()
 - is_home()
 - is_front_page()
 - is_blog()
 - is_admin()
 - is_admin_bar_showing()
 - is_404()
 - is_super_admin()
 - is_user_logged_in()
 - is_search()
 - is_archive()
 - is_author()
 - is_category()
 - is_tag()
 - is_tax()
 - is_date()
 - is_year()
 - is_month()
 - is_day()
 - is_time()
 - is_single()
 - is_sticky()
 - $post->post_type
 - $post->name
 - $post->ID
 - wp_get_post_categories()	(Page/Post Categories)
 - wp_get_post_tags()		(Page/Post Tags)
 - $user->nicename
 - $user->id
 - $user->roles
 - $user->allcaps
 - $archive->slug (e.g. Category slug, Tag slug, etc.)
 - $archive->id   (e.g. Category id, Tag id, etc.)

Add post specific classes to the body tag in the post editor. Use any class name you want to uniquely style an individual post or a set of posts.

Add classes based on user scrolling through the page. You can check things like:

 - is-scroll-top		(at the top of the page - synonym for is-not-scroll)
 - is-not-scroll-top	(not at the top of the page - synonym for is-scroll)
 - is-scroll			(not at the top of the page - synonym for is-not-scroll-top)
 - is-not-scroll		(at the top of the page - synonym for is-scroll-top)
 - is-scroll-top-px		(has not reached the scroll start value for scroll measurement by pixels)
 - is-scroll-top-vh		(has not reached the scroll start value for scroll measurement by viewport height)
 - is-scroll-top-dh		(has not reached the scroll start value for scroll measurement by document height)
 - is-scroll-10-px		(scrolled down 10 pixels)
 - is-scroll-8-vh		(scrolled down 8% of the viewport height)
 - is-scroll-5-dh		(scrolled down 5% of the document height)
 - is-scroll-mid-px		(has reached the scroll start value but not the scroll limit for scroll measurement by pixels)
 - is-scroll-mid-vh		(has reached the scroll start value but not the scroll limit for scroll measurement by viewport height)
 - is-scroll-mid-vh		(has reached the scroll start value but not the scroll limit for scroll measurement by document height)
 - is-scroll-max-px		(has reached the scroll limit for scroll measurement by pixels)
 - is-scroll-max-vh		(has reached the scroll limit for scroll measurement by percent of viewport height)
 - is-scroll-max-dh		(has reached the scroll limit for scroll measurement by percent of document height)

Add classes based on the results from mobiledetect.net's **Mobile_Detect** script. This script parses the value passed by the browser in the HTTP_USER_AGENT string. Consequently, mobile detection is more of an art than a science and, unfortunately, is not perfect. You can check things like:

 - is-mobile
 - is-tablet
 - is-phone
 - Mobile Operating System
 - Mobile Browser
 - Type of tablet
 - Type of phone

This plugin adds classes to the html body tag indicating:

 - whether or not the requesting device is a mobile device (.is-mobile or .is-not-mobile)

 - the type of post being viewed (.is-? where ? is the post type (page, post, whetever special post types are defined) ).
     E.g. .is-page or .is-post

 - the slug of the post being viewed (.is-?-! where ? is the post type and ! is the post slug).
     E.g. a post with slug "hello-world' would have class .is-post-hello-world

 - the ID of the post being viewed (.is-?-# where ? is the post type and # is the post ID).
     E.g. a post with ID "1" would have class .is-page-1

 - whether or not the requested page shows archived results (.is-archive or .is-not-archive)

   If the page being displayed is an archive

    - the type of archive being viewed (.is-? where ? represents the type of archive (author, category, date, tag) )
	    E.g. .is-author

    - the slug of the archive being viewed (.is-?-! where ? is the archive type and ! is the archive slug)
	    E.g. a category with slug "uncategorized' would have class .is-category-uncategorized

    - the ID of the archive being viewed (.is-?-# where ? is the archive type and # is the archive ID)
        E.g. a category with ID "1" would have class .is-category-1

 - How far down the page the viewer has scrolled in pixels or as a percentage of viewport height or as a percentage of document height

 - Post specific class(es) that are set in the post editor

Use these classes in your styling to provide a better browsing experience for your viewers.

= Custom Body Classes =

Create your own Custom Body Classes by adding your PHP code in the "Custom Body Classes" section.

Here's an example. Not sure why we would want to do it, but suppose we want to do some custom styling when the page is being displayed to someone that can manage WordPress options. We might enter something like:

`if (current_user_can('manage_options')) $classes[] = 'user-can-manage-options';`

Then we can use the **body.user-can-manage-options** qualifier in our CSS styling.

= Example =

Suppose you have a large h1 top margin that you want to eliminate on mobile devices to avoid a lot of white space. After activating the wp247-body-classes plugin and indicating that the .is-mobile class is desired, all you need to do is add this line to your CSS:

body.is-mobile h1 {
	margin-top: 0;
}

Suppose you have a sticky header but want to shrink it by dynamically reducing the top and bottom padding from 25px to 5px as the viewer scrolls down the page based on 20 pixel scroll increments up to 80 pixels of scrolling:

Set WP247 Body Classes Scroll setting to "Scroll by Pixel" with a 10 pixel increment and an 80 pixel limit and then add the following to your CSS: 

body.is-scroll-20-px header {
	padding-top: 20px;
	padding-bottom: 20px;
}
body.is-scroll-40-px header {
	padding-top: 15px;
	padding-bottom: 15px;
}
body.is-scroll-60-px header {
	padding-top: 10px;
	padding-bottom: 10px;
}
body.is-scroll-max-px header {
	padding-top: 5px;
	padding-bottom: 5px;
}

== Installation ==

In the WordPress backend:

- Go to Plugins->Add New
- Search for the plugin 'wp247 Body Classes'
- Click the "Install" button
- Click on "Activate"

That's it. You're now ready to customize your viewer's browsing experience.

== Screenshots ==

1. Mobile Classes setting selection
2. Environment Classes setting selection
3. User Classes setting selection
4. Archive Classes setting selection
5. Post Classes setting selection
6. Scroll Classes setting selection
7. Custom Classes setting
8. Custom CSS setting
9. Custom Individual Post Body Classes

== Changelog ==

= 2.1.1 =
Fix post type for Custom Individual Post Body Classes

= 2.1 =
Removed Notifications Plugin corequisite
Fixed Settings API for PHP v7
Updated Admin Favorites
Added Custom Individual Post Body Classes
Added Mobile-Detect version 2.8.34
Added Mobile-Detect version 2.8.37
Added User Roles Classes
Added User Capabilities Classes

= 2.0.1 =
Fixed Settings API bug

= 2.0 =
Added Scroll Classes
Added page categories, post categories, and post tags options
Added ability to choose Mobile-Detect Version
Added Mobile-Detect version 2.8.26
Improved performance
Improved memory footprint
Added security to disallow script direct execution

= 1.1.3 =
Removed namespace use in WP247 Settings API to due errors

= 1.1.2 =
Made all admin classes unique to WP247 in order to avoid conflict in Admin Settings API

= 1.1.1 =
Corrected potential 'active_tab' storage conflict in Admin Settings API

= 1.1 =
Implemented Mobile classes based on mobiledetect.net's **Mobile_Detect** script

= 1.0.1 =
Fixed PHP Error in wp247-settings-api

= 1.0 =
First release on 2015-March-1