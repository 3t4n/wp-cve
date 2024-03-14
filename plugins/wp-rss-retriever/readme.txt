=== RSS Feed Retriever ===
Contributors: tjtaylor
Donate link: https://thememason.com/plugins/rss-retriever/
Tags: rss aggregator, rss feed, rss import, rss parsing, news aggregator
Requires at least: 2.8
Tested up to: 6.1.1
Stable tag: 1.6.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The fastest RSS feeds plugin for WordPress. Includes excerpt & thumbnail image. Use as a news aggregator, autoblog, or RSS parsing.

== Description ==

This plugin fetches an RSS feed, or multiple feeds, and displays them in an unordered list using shortcode.

<a title="WordPress RSS Feed Retriever Demo" href="http://demo.thememason.com/rss/" target="_blank">**Demo**</a> | <a title="WordPress RSS Feed Retriever Tutorial" href="https://www.youtube.com/watch?v=2EPdD65zS5U" target="_blank">**Video Tutorial**</a> | <a title="WordPress RSS Feed Retriever PRO" href="https://thememason.com/plugins/rss-retriever/">**PRO Version**</a>

<h3>How to use:</h3>

[youtube https://www.youtube.com/watch?v=2EPdD65zS5U]

Simply copy and paste the example code below to wherever you would like to display your RSS feed. Replace the url and other properties as needed. (See FAQ section below to use in Gutenberg blocks, widgets, and PHP)

<h3>Example:</h3>
<pre><code>[wp_rss_retriever url="http://feeds.feedburner.com/TechCrunch/" items="10" excerpt="50" read_more="true" credits="true" new_window="true" thumbnail="200" cache="12 hours"]</code></pre>


<h3>Live Demo:</h3>
<p><a title="WordPress RSS Feed Retriever Demo" href="http://demo.thememason.com/rss/" target="_blank">http://demo.thememason.com/rss/</a></p>

<h3>Features:</h3>
<ul>
	<li>Fetch as many RSS feeds as you want</li>
	<li>Display the RSS feed wherever you want using shortcode, including text widgets</li>
	<li>Control whether to display the entire RSS feeds content or just an excerpt</li>
	<li>Control how many words display in the excerpt</li>
	<li>Control whether it has a Read more link or not</li>
	<li>Control whether links open in a new window or not</li>
	<li>Simple, lightweight, and fast</li>
	<li>Easy to setup</li>
	<li>Fetch thumbnail or first image</li>
	<li>Control size of thumbnail (width and height)</li>
	<li>Set cache time (in seconds)</li>
	<li>Control order of items</li>
	<li>Aggregate multiple feeds into one list</li>
	<li>Dofollow or nofollow options</li>
</ul>

<h3>Properties:</h3>
<ul>
	<li><strong>url</strong> - The url of the rss feed you wish to aggregate from. For multiple urls simply use a comma between them.</li>

	<li><strong>items</strong> - Number of items from the rss feed you wish to fetch <em>(Default is 10)</em></li>

	<li><strong>orderby</strong> - Order the items by date, reverse date, or random <em>(default, date, date_reverse, random)</em></li>

	<li><strong>title</strong> - Whether to display the title or not <em>(true or false, defaults to true)</em></li>

	<li><strong>excerpt</strong> - How many words you want to display for each item	<em>(Default is 20, use 0 for full text, use 'none' to remove the excerpt)</em></li>

	<li><strong>read_more</strong> - Whether to display a read more link or not	<em>(true or false, defaults to true)</em></li>

	<li><strong>new_window</strong> - Whether to open the title link and read more link in a new window	<em>(true or false, defaults to true)</em></li>

	<li><strong>thumbnail**</strong> - Whether or not you want to display a thumbnail, and if so, what size you want it to be<em>(true or false, defaults to true. Inserting a number will change the size, default is 150, use 150x200 format to set both width and height, use percents to fill the width, example: 100%x250 or 50%x250)</em></li>

	<li><strong>source</strong> - Whether to display the source or not <em>(true or false, defaults to true)</em></li>

	<li><strong>date</strong> - Whether to display the publish date or not <em>(true or false, defaults to true)</em></li>

	<li><strong>cache</strong> - How long you want the feed to cache the results <em>(Default is 12 hours, you can use days, hours, seconds etc.)</em></li>

	<li><strong>dofollow</strong> - Whether or not to make links dofollow <em>(true or false, defaults to false)</em></li>

	<li><strong>ajax</strong> - Whether to load the feed via JavaScript or PHP <em>(true or false, defaults to true)</em></li>

	<li><strong>credits</strong> - Whether to give credit to the plugin author <em>(true or false, defaults to false)</em></li>
	
	<li><strong>columns</strong> - Set layout to columns/grid layout with number of columns. <em>(defaults to 0, use 2, 3, 4 etc.) **PRO version only**</em></li>
	
	<li><strong>icons</strong> - Replace source & date labels with icons <em>(true or false, defaults to true)  **PRO version only**</em></li>

	<li><strong>layout</strong> - Set layout to masonry grid layout. <em>(default or masonry) **PRO version only**</em></li>
</ul>

**PLEASE NOTE: Using thumbnails can cause longer load times depending on the feed you are fetching from, use with caution. 

Please post any issues under the support tab. If you use and like this plugin, please don't forget to <strong>rate</strong> it! Additionally, if you would like to see more features for the plugin, please let me know.

Shortcode can be used anywhere including in posts, pages, text widgets, and in PHP files by using the do_shortcode function. This RSS import plugin is very lightweight with a minimal amount of code as to insure it will not slow down your website. Build a custom news aggregator or use this plugin as a simple feed to post plugin by displaying the RSS parsing feed within the pages of your choice. This RSS aggregator is built on the SimplePie API.

== Frequently Asked Questions ==


= The thumbnail is not displaying =
This could be because the source you're fetching from does not include the featured image in their RSS feed. There is nothing you can do to fix this unless you have control of the source website. If so, you can simply install this plugin on the source website and it will include the featured images within the RSS feed automatically.

= The thumbnail is blurry =
This is due to the source website including a low resolution image in their feed. There is nothing you can do to fix this unless you have control of the source website. If so, you can simply install this plugin on the source website and it will include larger featured images within the RSS feed automatically.

= How do I change the layout? (ie. make the thumbnail first) =
You can change the layout via WordPress filters. Add the following code to your themes functions.php file:

<pre><code>
function custom_rss_layout_callback( $layout ) {
    $layout = array(
        'thumbnail',
        'title',
        'content',
        'postdata',
    );
    return $layout;
}
add_filter( 'wp_rss_retriever_layout', 'custom_rss_layout_callback' );
</code></pre>

In addition, you can add your own HTML to the output if you want to wrap elements. Example:

<pre><code>
$layout = array(
    '<div class="my-custom-class">',
        'thumbnail',
        'title',
        'content',
        'postdata',
    '</div>'
);
</code></pre>

Or, if you only want to display the title:

<pre><code>
$layout = array(
    'title',
);
</code></pre>

= How do I display a feed with a Gutenberg Block? =
Click on the "+" icon to add a new block. Search for "shortcode". Click on the shortcode block to add it. Copy and paste the example shortcode above into the block. Replace the url and other parameters as needed. 

= How do I display a feed in my content? =
Copy and paste the example shortcode above into your content. Replace the url and other parameters as needed. Update/publish the page or post.

= How do I display a feed in a widget? =
Create a new text widget. Click on the "Text" tab. Copy and paste the example shortcode above. Replace the url and other parameters as needed.

= How do I display a feed using PHP? =
Here's an example of how to display an RSS feed with PHP
<pre><code><?php echo do_shortcode('[wp_rss_retriever url="http://feeds.feedburner.com/TechCrunch/" items="10" excerpt="50" read_more="true" credits="true" new_window="true" thumbnail="200" cache="7200"]'); ?></code></pre>


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `wp-rss-retriever.zip` to the `/wp-content/plugins/` directory
2. Unzip the file
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Use the example shortcode [wp_rss_retriever url="http://feeds.feedburner.com/TechCrunch/" items="10" excerpt="50" read_more="true" credits="true" new_window="true" thumbnail="200" cache="7200"] anywhere in your content
5. Change the url and other properties as needed


== Changelog ==


= 1.6.0 =
* Add additional methods for retriving thumbnail images

= 1.5.5 =
* Fix a bug where dofollow option was not working with thumbnails

= 1.5.3 = 
* Add more details to RSS errors
* Fallback to original RSS fetch method if feed is invalid

= 1.5.2 =
* Increase performance with built-in caching system for all feeds
* Disable ajax for feeds already cached

= 1.5.1 =
* Fix an issue causing some sources to block SimplePie requests

= 1.5.0 =
* Defer loading feeds via JavaScript (solves slow page loading issue)
* Add ajax option (true or false) to disable deferred loading
* Fix an issue where some feeds would not fetch
* Hide images that contain broken or blocked links

= 1.4.3 =
* Fix an issue with default ordering for single feeds

= 1.4.2 =
* Add ability to grab thumbnail image from rss image tag

= 1.4.1 =
* Add custom filter hook to allow changes to the layout (wp_rss_retriever_layout)
* Thumbnail images now link to the source

= 1.4.0 =
* Refactor feed fetching and formatting for performance
* Improve error handling. Display attribute errors for administrators and editors
* Allow cache setting to be plain text (ie. 2 hours, 2 days, etc.)
* Fix a bug causing the shortcode not to load when an open or closing double quote was used
* Allow thumbnails to take percentages as arguments for full width (ie. 100%x250)

= 1.3.10 =
* Fix a bug causing an error if the rss content is an empty string

= 1.3.9 =
* Fix a bug with function declarations not working with some themes

= 1.3.7 =
* Improve image finder functionality

= 1.3.6 =
* Add support for featured images in RSS feeds for self-hosted sources

= 1.3.5 =
* Suppress php warning from known SimplePie date bug

= 1.3.4 =
* Fix a bug causing a PHP error for feeds with a 404 error

= 1.3.3 =
* Fix a bug when published and source are disabled causing layout issue

= 1.3.2 =
* Fix an issue where feeds were automatically reordered by default WordPress settings

= 1.3.1 =
* Fix an issue where excerpts in East Asian languages were not trimming words properly

= 1.3.0 =
* Refactor image CSS to make img element act as background-size:cover
* Remove default list-item styles
* Include ability to set width and height on thumbnails (ie. 200x150)
* Fix an issue where plugin CSS was not enqueuing when using the shortcode inside a text widget
* Fix an issue where excerpt=0 was not stripping html tags from the feed.

= 1.2.6 =
* Fix HTML validation error

= 1.2.4 =
* Utilizes the local timezone set in WordPress general settings

= 1.2.2 =
* Dates now translates to the language set in WordPress general settings
* Date and time format now uses the format set via WordPress general settings

= 1.2.1 =
* Fix a bug where the error message "No Items" was displaying outside of the list item
* Fix an error that displays sometimes on 404 pages
* Add optional plugin credits (disabled by default)
* Add orderby="random" method

= 1.2 =
* Fix SSL error
* Add default nofollow for links
* Add option for dofollow

= 1.1.1 =
* Support for aggregating multiple feeds into one list
* Ability to order posts by date or reverse date
* Options to display or remove source and date
* Option to remove title

= 1.1 =
* Fixed several bugs
* Fetch thumbnail or first image
* Control size of thumbnail
* Set cache time (in seconds)
* Now includes small CSS file, required for thumbnail support

= 1.0.2 =
* Pulls images & html in when excerpt is not enabled

= 1.0.1 =
* Fixes bug where excerpt included html and broken images

= 1.0 =
* Initial release
