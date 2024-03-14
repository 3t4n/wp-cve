=== AW WordPress Yearly Category Archives ===
Contributors: awarren
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=C6AZTULD7TEMA 
Tags: Yearly Category Archives, Archives, Yearly Archives, Category Archives by Year
Requires at least: 3.5.2
Tested up to: 4.9.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will allow for yearly archives of specific categories from all post types and "Posts". 

== Description ==

#### AW WordPress Yearly Category Archives has two (2) shortcodes available, both of which are required for the plugin to function properly. ####  


Follow the instructions below to use the plugin.  
  
**The first shortcode is `[aw_year_links cat="X" postslug="slug-to-post-or-page"]`, which is used to build and display the year links.**  
  
The following list explains this shortcode's usage and requirements.  
  
* This shortcode has two (3) attributes.  Two (2) are required, and one (1) is optional.  
* The `cat="X"` attribute is the category ID you wish to display yearly links from. Replace the X with the numerical ID of the category you wish to query.  You may include a comma separated list of category IDs with this attribute if you want to query multiple categories.  **This attribute is required.**  
* The `postslug="slug-to-post-or-page"` attribute is the slug to the page that will display your yearly archived posts. This is also the slug of the page you will include the second shortcode on.  **This attribute is required.**
* The `dropdown="yes"` attribute will allow a dropdown select input to be used in place of the standard unordered list of the year links.  **This attribute is optional and can be left off completely.**  
* Place this shortcode where you would like to display the year links to the specified category.
  
  
**The second shortcode is `[aw_show_posts cat="X" readmore="Continue Reading" publishedon="n/j/Y"]`, which is used to display the post content after click a year link.**    
  
The following list explains this shortcode's usage and requirements.  
  
* This shortcode has three (3) attributes. One (1) is required, and two (2) are optional.  
* The `cat="X"` attribute is the category ID you wish to display yearly archived posts from.  Replace the X with the numerical ID of the category you wish to query.  You may include a comma separated list of category IDs with this attribute if you want to query multiple categories.  **This attribute is required.**  
* The `readmore="Continue Reading"` attribute is the text you wish to display for the "Read More" link. This attribute is optional and will default to "Read More" if left out.  
* The `publishedon="n/j/Y"` attribute is the PHP date format the published on date will appear in the archived posts. This attribute is optional and will default to "M jS, Y" if left out. Refer [here](http://php.net/manual/en/function.date.php) for further info on the PHP date format.
* The `showsubheader="no"` attribute is used to display a subheader above the post output that says which category and year is being displayed. it will read like this: **Category: Example Category Name - Year: 20XX**. This is an optional shortcode attribute. If left off the shortcode the subheader will be shown. Use `showsubheader="no"` to not output the subheader.  **This attribute is optional and can be left off completely.**   
* Place this shortcode where you would like to display your archived posts.
  
  
**Additional Notes**  
  
* The shortcodes can be used multiple times throughout the site as long as they are always used in pairs with each pair having the same `cat="X"` attribute. This is handy for displaying separate yearly category archives.  
* The plugin will query all custom post types as well as the main "Posts".  
* Currently the plugin will display Five (5) elements for each post, unless the admin chooses to write their own post structure on the settings page. They are as follows and in order:
	1. `<h3 class="awyca_subheader">Category: Example Category Name - Year: 20XX</h3>`
	2. `<div class="awyca_postWrapper"></div>` - (this wraps each post including all the elements below in this list)</li>  
    3. `<h2 class="awPostTitle">The Post's Title</h2>`  
    4. `<p class="awPublishedOnDate">Published on Aug 13th, 2013</p>` 
    5. `<p class="awPostExcerpt">The Post's First 25 Words...<a href="http://yoursite.com/the-post-slug">Read More</a></p>` 
    6. `<hr class="awPostDivider"/>`  
* The actual post elements have classes; however they do not have styles. This is to allow you to style them how you choose. The only frontend style included is for the post divider `<hr class="awPostDivider"/>` rule. This can be overriden if you so choose to.  
* Currently there is also no pagination built into the display of yearly archived posts. I do have plans for this in the future if time allows.  
* Currently I will only be able to offer limited support for this plugin. This could change in the future, also if time allows.  
* If you do not know how to find your category IDs, I recommend [Reveal IDs](http://wordpress.org/plugins/reveal-ids-for-wp-admin-25/).

Follow me on Twitter [@iAmAndyWarren](https://twitter.com/iAmAndyWarren) or find me at [andy-warren.net](http://andy-warren.net).  Want to make the plugin better?  Fork it or submit pull requests on GitHub at [https://github.com/andywarren/aw-yearly-category-archives](https://github.com/andywarren/aw-yearly-category-archives).

== Installation ==

To install the plugin follow these instructions:  
  
1. Download the plugin and unzip it.  
2. Upload the folder aw_yearly_category_archives to your /wp-content/plugins/ directory.  
3. Activate the plugin from your WordPress admin panel.  
4. Installation finished.

== Screenshots ==

1. This screenshot is of the shortcode needed to generate the yearly links.  Place this shortcode where you would like to display the year links to the specified category.
2. This screenshot is of the unordered list the above shortcode will generate.
3. This screenshot is of the optional dropdown that can be used in place of the unordered list in the screenshot above.  
4. This screenshot is of the shortcode needed to display the yearly category archives.  Place this shortcode where you would like to display your yearly archived posts.
5. This screenshot is of the post layout you will see when using the above shortcode.
6. This screenshot shows the settings page when you first arrive there.
7. This screenshot shows the settings page when you have opted to include a custom post structure for the output loop.

== Changelog ==

= 1.2.8 =
* Remove get_post_types() from both shortcodes as it was causing issues for logged out users
* changed get_posts() post_type parameter to "any"

= 1.2.7 =
* Added strip_tags() to the returned excerpt text to remove any html tags from the standard post layout. Does not affect custom post layout.

= 1.2.6 =
* Set Year Links to show in Ascending order from most current to least current.

= 1.2.5 =
* Bug Fixes

= 1.2.4 =
* Corrected parameter in WP_Query() for the post output function

= 1.2.3 =
* Wrapped shortcode functions in an output buffer to make rendered content display correctly when the shortcode is used in a widget.

= 1.2.2 =
* Bug/Security Fix

= 1.2.1 =
* Removed jQuery script from being echoed, and properly regsitered/enqueued it.
* Updated "Compatible Up To" version.

= 1.2 =
* Added new attribute `dropdown="yes"` to the shortcode used to generate the year links.  This will allow for a dropdown to be used in place of the standard unordered list of years.
* Added ability to query multiple categories

= 1.1.1 =
* Updated paths to the plugin menu pages.

= 1.1 =
* Added settings page to allow site admin to input custom HTML and/or WordPress Template tags to be used for the archived posts output loop.

= 1.0.1 =
* Corrected file path for menu item icon.
* Bug fixes.

== Upgrade Notice ==

= 1.2 =
* This update will add two (2) new features to the plugin.  You can now choose to use a dropdown in place of the standard unordered list to display year links.  You can also now properly query multiple categories by using a comma separated list in the `cat="X"` attribute of both shortcodes.

= 1.1.1 =
This update will correct the paths the the plugin menu pages.  With this update the pages will no longer 404.

= 1.1 =
This upgrade will add the ability to allow the admin to create a custom post structure using HTML and/or Wordpress template tags.  This is handy to change the appearance of the archived posts to better fit your own blog/site.    