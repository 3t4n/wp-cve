=== Plugin Name ===
Contributors: stringfold
Donate link: http://azindex.englishmike.net/ 
Tags: index, indexes, indexing, alphabet, alphabetical, alphabetize, alphabetized, alphabetizing, ordering, organize, atoz, posts, tags, categories, custom fields
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: 0.8.1

Stylish Indexes for your WordPress Blog. Create alphabetical indexes of your posts, with headings, subheadings, and descriptions, based on post title, 
tags, categories, extract, author name, or custom fields.

== Description ==

Enhance your blog by creating multiple, three-level alphabetical indexes of your posts based on any combination of 
post title, author, extract, tags, categories, or custom fields of your choice. Select which posts are included in your indexes using
a combination of categories and tags, display them in up to four columns on a single page or over multiple pages.

For example, the AZIndex plugin can be used to:

* Create an alphabetical index of all your blog posts, based on their titles, using extracts as short descriptions.
* Create a music review index, sorted by the name of the artist and the name of their albums.
* On blogs with multiple authors, create an index of posts by each author's name.
* Create a full alphabetical index of all your blog's published pages in seconds with no PHP, HTML, or CSS required.

Full list of features:

* Index blog posts by title, extract, author, or any custom field.
* Create indexes for your blog pages. Now you can create a full alphabetical index of every page and post on your site. 
* Select index contents using a combination of categories and tags.
* One, two or three-level entries - heading, subheading, and description, all customizable.
* Up to four columns on a page.
* Multiple indexes can be created, each customized with its own settings.
* Indexes may be embedded in posts as well as pages.
* Fully customizable from the WordPress admin console. No need to know HTML or PHP.
* Single or multi-page indexes with page links.
* Optional alphabetical links.
* Display alphabetical headings in your index with links back to the top of the page.
* Group items with the same heading under one entry (e.g. all the novels of the same author).
* Add your own text to the index page.
* Control over the number of index items per page.
* Indexes are cached to ensure speedy operation when dealing with large indexes.
* Customize the stylesheets for the index contents directly from the index administration page.
* Use CSS style striping to decorate your index entries (e.g. add a gray background to alternate entries). 
* Use your own customized sort to sort an index.
* Customize which alphabetical links appear with your index. 
* Index entries that do not begin with a character or number can be placed at the beginning or the end of the index.
* Punctuation like quotes or double-quotes can be ignored while sorting the index.
* You can exclude categories and tags from an index.  Useful if you just need to exclude a few posts from an index.  
* Option to automatically include child categories in the index. 
* Support for the superfast WP Super Cache plugin.
* Support for national languages.  Non-English characters in headings and alphabetical links should now be displayed correctly. 
* Translatable text provided -- help requested for translating the strings (in azindex.pot), it would only take a few minutes of your time.
* **NEW in 0.8.0** Sort indexes by tag names or category names.
* **NEW in 0.8.0** New item filter allows you to tailor the sorting of the index to you needs.
* **NEW in 0.8.0** Three new output filters allow you to modify the HTML of the index, or even create your own!
* **NEW in 0.8.0** The table of indexes on the Index Management page is now sortable.
* **NEW in 0.8.1** Item display filter, allowing you to modify the content of an item before it is displayed.

**IMPORTANT NOTE:** This is still a **BETA** release, meaning that while the plugin is stable and has undergone 
considerable testing, you may encounter some bugs as new features and code are added. I have not been able to test
all the plugin's features on all combinations of WordPress versions, browsers, databases, and PHP versions, so
some problems could remain on some of the many possible configurations.  The best thing to do is just give it 
a try!

Please read the Other Notes section before installing.

If you try this plugin and like what you see, email to azindex@englishmike.net or visit the
[AZIndex Plugin Feedback Page](http://azindex.englishmike.net/feedback) to provide your feedback. 

Whether you're reporting a bug, requesting a new feature, or just wanting to make a comment, I will be happy to hear from you.

Stay up-to-date with all the important information and updates about AZIndex by subscribing to the 
[AZIndex Announcements Mailing List](http://azindex.englishmike.net/email-subscriptions/).

== Installation ==

Installation Instructions:

For WordPress 2.7 and above:

1. Navigate to the **Plugins** >> **Add New** page in your blog's administration section.
1. Search for **AZIndex**.
1. Click Install and follow the on-screen instructions. 

For older versions of WordPress:

1. Download the plugin zip file. 
1. Upload the plugin contents into your WordPress installation's plugin directory.
1. The plugin's .php files and readme.txt should be installed in the 'wp-content/plugins/azindex/' directory. 
1. From the Plugin Management page, activate the AZIndex plugin.

(Note: if you haven't installed the **One Click Plugin Updater** plugin by Janis Elsts yet, then what are you waiting for?)  
   
Getting Started (these instructions are for WordPress 2.7 and above):   
   
1. To create a new index, go to **Tools** >> **AZIndex** and click on the **'Add New'** link.
1. Enter a title for your index and select the options you want. 
1. Click the **'Add Index'** button and a new index, along with a new unpublished blog page, will be created.
1. To view the contents of your new index page, go to **Pages** and preview the page from there.
1. Make sure you try out all the options. They can be used to tailor the look and feel of the index to your satisfaction.
1. Once you are happy with the results, publish the index page (from **Pages**) so your users can access it.
1. Hint: Consider using custom fields in your posts to set the headings and sub-headings of your index. 
For example, if you are indexing book reviews you have posted, you can create an index that uses custom fields 
to access the authors' names as headings, and the book titles as sub-headings. That way you can have a 
well organized index without having to mess with the titles of your reviews.

== Frequently Asked Questions ==

= How can I include all my posts in the index? =

Easy, just leave the 'Included/excluded tags' and 'Included/excluded categories' fields empty.

= How do I add my index to another page or post? =

1. Make a note of the ID of the index from the table on the Manage >> AZIndexes administration page.
1. Edit the page/post in which you want to display the index.
1. Insert the following "short-code" into your page/post:   [az-index id="x"]   (where x is the ID of the index).  

= Can I index pages as well as posts? =

Yes!  Just check the appropriate option in the settings for your index.  If you only want to index pages, 
remember to check the option to exclude posts from the index.

= But I only want to add some of my pages to the index.  How do I do that? =

With posts it's easy, you can tag or categorize the ones you want to put in an index and you're done.  

Unfortunately, there is no easy way to categorize or tag pages, but don't worry, there is a nice little plugin 
called [tags4page](http://wordpress.org/extend/plugins/tags4page/) which allows you to tag pages when you edit 
them. Once you have tagged the pages you want in the index simply specify that tag in the index settings. 

= What if I want an index that includes all my posts except a few that I don't want in there? =

You can tag posts you don't want in your index or put them into a category, and then exclude that category or tag
from the index by adding its name to the "Include/exclude category (or tag)" fields with a '~' character in front of it.
For example, if you want to exclude all your announcements from your index, tag all those posts with an 'announcements'
tag and then add "~announcements" to the "Include/exclude tags" field in the index settings.  

= When I have more than one column on a page, why are the columns sometimes different lengths? =

That is because the index items are split evenly between the columns, but some items can take up more column space
than others.  If there are several large items in one of the columns, it may end up being much longer than the others.

= Help! The headings in my index have no links.  What gives? =

You probably have the 'Group items..." option selected for your index.  If you don't have any subheadings, 
then make sure you deselect this option.

= Why does the layout of my index look strange with some WordPress themes? =

Well, just like anything else appearing in a page or post on your blog, your blog's current theme will affect 
the appearance of the index.

Most of the time that's a good thing, because you don't want your indexes to look out of place in your blog.
But occasionally a WordPress theme's stylesheet just may not work well with your index.  

If that happens there are two ways to resolve the problem:

1. Modify the theme's stylesheet so that it doesn't interfere with the appearance of the index.
1. Turn on the "Use customized stylesheets" option for your index then modify the index's stylesheet to fix any unsightly styling.

Note: One common problem with some themes is that you'll see bullets appearing next to items in the index.  
This happens when there is a CSS style in the theme's stylesheet that defines the style tag 'list-style-type' 
or 'list-style'.  The easiest way to fix this is to comment out or delete this line from your theme's stylesheet, 
but you should check to see if this causes other formatting issues in your blog.  If you feel you can't 
remove that line, the next easiest way to solve the problem is to make a copy of that style (it will begin 
something like "#content .page ul ol {"), turn on custom CSS styling for the index and paste the style 
into the stylesheet for the index.  Then all you have to do is modify the "list-style" (or "list-style-type")
element and replace "disc" (or whatever the type is) with "none".

Another problem I have seen can happen with multi-column indexes where the right-hand column 

Once I have some time, I will write a longer article about modifying stylesheets since this is an important 
topic in getting your index to look just the way you want it.    

= How do I get AZIndex to work with the WP Super Cache plugin? =

Easy.  Just follow the instructions provided with WP Super Cache to install and configure that plugin.  Once
you see that blog pages are being cached (check the "cached pages" counters on the WP Super Cache settings page
if you're not sure if they are) then you should see you index pages being cached too.  If you make a change to 
your blog that affects the contents of your index, AZIndex will tell WP Super Cache to flush the cache so that
a new index page can be generated.

= Help!  I am having problems getting WP Super Cache to work. =

I'm afraid I cannot offer any assistance unless the problem is directly related to AZIndex.  WP Super Cache is a very popular
plugin and there is plenty of advice and help available on the web both at the 
[WordPress site](http://wordpress.org/extend/plugins/wp-super-cache/) and the [plugin owner's
home page](http://ocaoimh.ie/wp-super-cache/).  If you are having trouble with index pages being updated because of WP Super Cache, you can add
the index page URL(s) to the list of "Rejected URIs" in the WP Super Cache settings page. 

= How do I get non-English characters in my index links and headings to display correctly? =

Select the *"Turn on additional support for national languages"* option in the index settings.  Note, in the
vast majority of cases, you should not have to use the other NLS options (locale or collation table).

= I have turned on the national language support, but the items in the index are not being sorted in the correct order for my language. =  

It is possible that you are running on a server that is not using the correct locale for your language.  You can
set a different locale by selecting the *"Set locale to be used while sorting index"* option, but you can only
use locale names that are supported by the server.  Please ask your system administrator for that information.  

= My index is being sorted in the correct order, but items are grouped wrongly and the alphabetical links are incorrect. =

Some languages, like Swedish, have different rules for collating (sorting) accented characters from other languages.  When 
you turn on the alphabetical headings and links, AZIndex uses a default collation table to determine how those accented
characters should be grouped, but this table does not work for all languages.  For those languages where the collation rules
are different, you can select the *"Set locale to be used while sorting index"* option and chose another table from the
supplied list. 

= You say that this is a BETA release, what exact does that mean? =

It means that this is an early release of the plugin.  While I have tested many of the features as thoroughly as 
possible, I am not able to test all the features of the plugin with all the browsers on all versions of WordPress, 
PHP and MySQL.  So it is possible you will come across a combination where there is still a one or two bugs.

It also means that there are one or two features left to be finished, like internationalization.

Finally, you may have to delete and recreate your indexes when you decide to move up to a new version.  Please read 
the release notes before upgrading.

However, the vast majority of users should be able to use this version of the plugin without any trouble.  
Just make sure to test it out before you publish your index page.

Any feedback you can provide me is most welcome.  

= I still have more questions/problems/requests, where do I go for answers? =

Simple, either leave a comment at the [AZIndex Plugin Home Page](http://azindex.englishmike.net/feedback/), 
or email me at azindex@englishmike.net if you want to keep your comments private. 

== Screenshots ==

Please visit the [AZIndex Gallery](http://azindex.englishmike.net/azindex-gallery/) for live examples of indexes 
generated by the AZIndex plugin.

== Release Notes version 0.8.1 ==

**Minimum versions tested**

* WordPress 2.5 is the minimum required (will not work with older versions of WordPress)
* PHP4 (4.4.8) & PHP5 (5.2.6) (will probably work with earlier versions of PHP4 and PHP5)
* MYSQL 4.1.22 & MYSQL 5.0.5 (will probably work with earlier versions of MYSQL 4.0 and 5.0)

**Known issues**
 
* This plugin supports indexes in non-English languages, but all text is English-only.
* The plugin still contains some debug code. It will not affect the behavior or performance of the plugin.  It will be removed in later versions.
* The wp_nonce functions have yet to be implemented.

**Changes in version 0.8.1**

* Added new filter: azindex_item_display
* Fixed bug in item filter.

**Changes in version 0.8.0**

* Added support for sorting the index by tag names and category names.
* Added four new filters: azindex_item, azindex_display_index, azindex_alpha_links, azindex_page_links
* Added sorting of the index table on the Admin Management page.
* Disabled the grouping option if no subheading is selected.
* Updated pointers to new web site: http://azindex.englishmike.net
* Several bug fixes relating to the caching of pages

**Changes in version 0.7.5**

* Rewritten the upgrade process for when a database table change is required.  From now on, the old settings will not be deleted until the plugin has confirmed that the upgrade was successful.
* Started internationalizing the text in the plugin.  This is the text like "(more)" and "(continued)" that appear on the index page.  I will do the admin pages too once the plugin gets closer to being finished.  I will, of course, make the POT file available for people to translate into their languages.  Just email me the translated files and I will incorporate them into a later release.
* Added titles to the links in the alphabetical links and page links in the index (text is included in the POT file)
* The headings in the index now link back to the top of the index, not the top of the page.  This is more convenient for the user when you have a large heading or a lot of text above the index.
* Added a "div" around the whole index and given it the id of "azindex-(index-id)".  This was to let me point the links back to the top of the index, though it's also a good idea in the long run too.  I haven't given it a class name yet, see below for the reason why.
* Fixed a couple of spelling mistakes and some validation issues (there all!)
* Fixed the stray end-style tag (now closes the spans properly)
* Fixed a problem with the admin page's custom field text box not appearing in some browers when selected.
* Fixed a bug in the sorting of non-alphanumeric characters.
* Fixed the "ignore characters" option which has been almost completely broken since 0.7.0.
* Completely rewrote the alphabetical index code so that it doesn't stop displaying links when the contents of the index are not in the expected order.
* And last, but not least, tweaked the multi-column code to fix a problem where it would sometimes not work on IE6/IE7.

**Changes in version 0.7.4**

* Fixed regression in sorting lower case alphabetical character in the wrong order.

**Changes in version 0.7.3**

* Fixed regression when creating the az_indexes database table.

**Changes in version 0.7.2**

* Fixed problem with older databases not working with collation.

**Changes in version 0.7.1**

* National language support has been added.  Please see the FAQ for more information.
* Renamed the AZIndex menu item.

**Changes in version 0.6.3**

* Fixed problems caused by the changes to the Admin Console in Wordpress 2.7.  

**Changes in version 0.6.2**

* Fixed bug where database table names were incorrectly hard-coded.  Should fix SQL errors when using excluded categories or tags.

**Changes in version 0.6.1**

* Added an extra array check to fix bug that might causing error messages when saving a post.

**Changes in version 0.6**

* Added support for excluding post in categories and with tags from an index.  Useful if you only want to exclude a few posts.
* Added option to include child categories along with the specified category.  Useful if you have a heirarchy of categories.
* Added full support for the WP Super Cache plugin.  If you are using that plugin to cache pages on your site, then index pages will be cached too.
* Fixed bug where a single item index was not being displayed.


**Changes in version 0.5.4**

* Fixed problem when getting a serialized option in WordPress 2.6.  Will continue to work on WP 2.5 and WP 2.6 if they decide to fix their bug. 

**Changes in version 0.5.3**

* Fixed sorting bug where the heading sort was case sensitive (yikes!)
* Added filter called 'azindex_heading' to allow custom manipulation of headings before they are sorted.  See the EnglishMike.net blog for more information.

**Changes in version 0.5.2**

* Added "escape" parameter to shortcode to allow bloggers (i.e. me) to display the [azindex] shortcode in a post without it turning into an index or error message! (format is: escape="true" if you want to display the shortcode)
* Fixed name-collision bug with $mutex global variable.
* Fixed bug where a link end tag was missing in some index formats.

**Changes in version 0.5.1**

* Fixed bug that gave error messages when creating a new index.

**Changes in version 0.5**

* Caching has arrived!  Large indexes are now many times faster to load (if you use multiple pages) than before.
* The cache can be cleared manually if necessary, or even disabled completely (though I would recommend against it!)
* Added option to add alphabetical headings to your index. Each new letter of the alphabet now has its own heading.
* Added further options to put an alphabetical heading at the beginning of each page or at the top of each column.
* Improved the pagelinks so that they will never overflow the bounds of the page if you have many pages in your index.
* Added "(continued)" and "(more...)" when a grouped heading crosses between columns or pages. 
* Fixed bug that was resulting in the wrong number of items going into a column.

**Upgrading to version 0.5**

* No incompatible changes from 0.4.x.  Indexes created with versions 0.3.x and 0.4.x will continue to work on 0.5.
* New CSS styles for the indexes will be added if you have defined custom CSS for your index.  Your own modifications will not be affected.  

**Changes in version 0.4**

* Added option for including blog pages in an index.
* Added option for striping index entries (e.g. giving alternate entries a different backgound color).
* Added option for custom sorting of an index.  Simply create your own comparison function and add its name to the index's settings page.
* Added option for filtering out punctuation when sorting the index, so entries don't get out of order if they begin with a quote or double-quote, for example.
* Added option for placing entries that begin with non-alphanumeric characters at the end of the index.
* Fixed bug causing the URL to grow when clicking on the index page links.
* Fixed bug where entries with no heading were appearing in the index. 
* You can now upgrade to the latest version of the plugin without losing your existing index settings (from v0.3 and above). 

**Upgrading to version 0.4**

* No incompatible changes from 0.3.x.  Indexes created with version 0.3.x will continue to work on 0.4.

**Changes in version 0.3.1**

* Fixed bug preventing plugin working with PHP4.
* Added better error checking and error messages to help users during problem determination.

**Upgrading to version 0.3.1**

* No incompatible changes from 0.3.  Indexes created with version 0.3 will continue to work on 0.3.1.

**Changes in version 0.3**

* Fixed bug that prevented the plugin working with MySQL 4.1 and some early versions of MySQL 5.0

**Upgrading to version 0.3**

* **VERY IMPORTANT** You *must* uninstall any earlier versions of the AZIndex plugin by clicking the **Uninstall AZIndex Plugin** link in the Manage >> AZIndexes admin page before you install the latest version.  

**Changes in version 0.2.1**

* Fixed bug where duplicate entries appear in the index when specifying more than one tag or category for the index.
* Fixed bug where the Author (the author of the post) was not displayed correctly when specified.

**Upgrading to version 0.2.1**

* Indexes created on v0.2 should continue to work on v0.2.1

**Changes in version 0.2**

* Indexes are no longer tied to a specific blog page.  For you convenience, a new page containing the '[az-index id="x"]' short-code will be created when you add an index, but you can copy that shortcode to any post or page you like.
* Posts can now have indexes embedded within them.  Just insert the short-code '[az-index id="x"]' where x is the id of the index. e.g. [az-index id="1"]. 
* You can embed the same short-code in more than one post/page.

**Upgrading to version 0.2**

* Delete all the indexes created with version 0.1.  They will no longer work correctly.  The quickest way to do this is to click 'Uninstall' and uninstall the plugin before you upgrade.
* You can no longer preview the index page from the 'Manage Indexes' table.  Go to Manage >> Pages instead.
* The plugin's administration page has been renamed from 'Index Pages' to 'AZIndexes'