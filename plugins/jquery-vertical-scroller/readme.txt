=== jQuery Vertical Scroller ===
Contributors: vamsitech, sirisgraphics
Tags: widget, jQuery, vertical slider, velocity, direction, post scroller, Free scrolling news wordpress plugin, News plugin WordPress, Scrolling posts WordPress, Vertical posts, Vertical scrolling posts, WordPress dynamic posts, scroller, ticker, widget, Recent Posts scroller
Requires at least: 3.4
Tested up to: 5.1
Stable tag: 2.9.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use jQuery Vertical Scroller plugin to display posts as a vertical scroll in a widget, post or page. Supports multiple instances.

== Description ==

jQuery Vertical Scroller plugin is our attempt to create a easy to use widget for adding vertical scrolling to your wordpress site. jQuery vertical Scroller is a versatile vertical scroller that allows you to scroll any post type or page. 

You can scroll anything from your recent posts to custom posts you might have created for other plugins. The scroll is flexible to allow you to scroll in 2 vertical directions. Top-to-Bottom or Bottom-to-Top.

= Do you need more than one scroller? =
No problem with this plug-in. All you need to do is drag-&-drop another copy of the scroller into your widget area and set it up. No need to leave the widgets screen. 

= Want to see a working copy before deciding? = 
Just scroll on over to our website to see this plugin at work: <a href="http://sirisgraphics.com" target="_blank">http://sirisgraphics.com</a>

= Looking for Gapless Scroller = 
We now have a premium scroller that allows you to scroll data on your site. Please visit out website to download Siris Scroller, our new gapless scroller and more... 

= Widget =
<b>Widget Features</b>
<ul>
<li>Easy to install and configure.</li>
<li>It scrolls vertically so you can display more posts.</li>
<li>You can scroll top-to-bottom or bottom-to-top.</li>
<li>You can hide the title of posts and pages being scrolled.</li>
<li>You can customize the height and width of the widget.</li>
<li>You can customize the type of recent posts to display.</li>
<li>You can customize the category of posts to display.</li>
<li>You can customize the number of recent posts to display.</li>
<li>You can customize to display either the content, excerpt or just the heading.</li>
<li>You can customize to display the post date in various date formats.</li>
<li>Supports custom 'read more...' text so you can add your own custom text</li>
</ul>

<b>Localization</b>
jQuery Vertical Slider supports full localization. Just place your .mo files in the languages folder and set your language code in the wp-config.php file.

Translation Files included: <br />
Italian by Vamsi Pulavarthi (Google Translate) <br />
Serbo-Croatian by <a href="http://www.webhostinghub.com/" target="_blank">Borisa Djuraskovic</a>

= Shortcode = 
<b>Usage:</b> [sgvscroller postcount="5" sortby="post_date" sortorder="DESC" category="1" posttype="post" width="250px" height="200px" startfrom="bottom" includecontent="excerpt" showdate="true" showdateformat="F, Y"]

postcount (optional, default value = 5): Sets the number of posts displayed by the scroller. Usage: postcount="5".

sortby (optional, default value = post_date): Set the Order By option for getting posts. 
        Valid Values:   'none' - No order (available with Version 2.8).
                        'ID' - Order by post id. Note the capitalization.
                        'author' - Order by author.
                        'title' - Order by title.
                        'date' - Order by date.
                        'modified' - Order by last modified date.
                        'parent' - Order by post/page parent id.
                        'rand' - Random order.
                        'comment_count' - Order by number of comments (available with Version 2.9). 
        Usage: sortby="post_date" 

sortorder (optional, default value = DESC): Sorts the posts in ascending or descending order of post date. Usage: sortorder="ASC" or "DESC"

category (optional, default value = 1): Sets the category to be displayed by the scroller. Usage: category="1".

posttype (optional, default value = post): Sets the category to be displayed by the scroller. You can either use 'post', for normal posts. For scrolling custom post types, replace it with the custom post type Usage: posttype="post".

width(optional, default value = 1): Sets the width of the scroller. Usage: width="250px".

height (optional, default value = 200px): Sets the height of the scroller. Usage: height="200px".

startfrom (optional, default value = bottom): Sets the direction of scroll for the scroller. Usage: startfrom="bottom". Options: ‘bottom’ or ‘top’.

includecontent (optional, default value = none): Allows you to display excerpt, if desired. Usage: includecontent="excerpt". Optons: 'content', 'excerpt' or 'none'

showdate (optional, default value = false): Allows you to display the post date for each post displayed in the scroller. Usage: showdate="true". Options: 'true' or 'false'

showdateformat (optional, default value = 'F, Y'): Allows you to configure format in which the post date for each post is displayed in the scroller. Usage: showdateformat="F, Y". You can use any valid format described in the [Date Formats](http://codex.wordpress.org/Formatting_Date_and_Time) page

<b>Shortcode Features</b>
<ul>
<li>Easy to install and configure.</li>
<li>It scrolls vertically so you can display more posts.</li>
<li>You can scroll top-to-bottom or bottom-to-top.</li>
<li>You can hide the title of posts and pages being scrolled.</li>
<li>You can customize the height and width of the widget.</li>
<li>You can customize the type of recent posts to display.</li>
<li>You can customize the category of posts to display.</li>
<li>You can customize the number of recent posts to display.</li>
<li>You can customize to display either the excerpt or just the heading.</li>
<li>You can customize to display the post date in various [date formats](http://codex.wordpress.org/Formatting_Date_and_Time).</li>
<li>Displays 'read more...' text at bottom of scroller</li>
</ul>

= Support =
Contact us at <a href="http://sirisgraphics.com">our website</a> for any kind of support. We promise to get back to you as soon as possible.

Like our plugin? Please rate us and send us a comment so we can make the plugin better for you.

== Installation ==

1. Download and unzip the plugin.
2. Upload `jquery-vertical-scroller` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress

= Usage = 
Widget
1. Drag and drop the widget into your widget area
2. Select the type of post, category, speed and direction
3. Save the options
4. Enjoy the widget on your site

Shortcode
[sgvscroller postcount="5" category="1" posttype="post" width="250px" height="200px" startfrom="bottom"]


== Frequently Asked Questions ==

1. **How do I use it to scroll posts?** - To scroll posts in your widget, set the 'Post Type' to 'post' and set the category to the numeric identifier of the category you want to scroll. 

2. **How do I use this plugin to scroll custom post types?** - To scroll custom post types, set the 'Post Type' to your custom post type. In this case, you would leave the category field empty if you don't have any categories. Otherwise, you can use the category id of your custom post type.

== Screenshots ==

1. Scroller in a post
2. Widget Admin screen in Widget Widget Section
3. Multiple widget instances simultaneusly
4. Multiple instances working seamlessly on the site

== Changelog ==
= 2.9.2 =
* Tested with Wordpress Version 5.1

= 2.9.1 =
* Fixed defect with shortcode code

= 2.9 =
* Tested with Wordpress Version 4.5.1

= 2.8 =
* Adding Sorting option to Shortcode

= 2.7 =
* Tested with Wordpress Version 4.0

= 2.6 =
* Tested with Wordpress Version 3.9.1

= 2.5 =
* Tested with Wordpress Version 3.8
* Added Serbo-Croatian Translations by Borisa Djuraskovic

= 2.4 =
* Tested with Wordpress Version 3.7

= 2.3 =
* Added option to hide titles from scroll when using as widget and shortcode
* Added option to customize date formats for widget and shortcode
* Added functionality to manage element layout through css3 (basic functionality only)

= 2.2 =
* Added full content support in shortcode
* Tested upto Wordpress version 3.5.2

= 2.1 = 
* Updated shortcode function to add excerpt and date
* Updated widget to add date

= 2.0 = 
* Added shortcode support

= 1.3 =
* Added features to manage width and height

= 1.2 =
* Tested with Wordpress 3.5
* Added full localization support
* Added support to show content or excerpt

= 1.1 =
* Added a new easy to use selection to add 2 scroll modes - Top-to-Bottom and Bottom-to-Top.
* Removed the standard list icon being displayed in the old version since it was conflicting with the built-in bullet icons for some themes.

= 1.0 =
* First Version.

== Upgrade Notice ==

= 2.9.1 =
* Fixed defect with shortcode code