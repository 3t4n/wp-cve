=== CP Related Posts ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/content-tools/related-posts
Tags: related posts,similar posts,associated pages,post,posts,page,pages,custom post type,related,terms,manual,tags,tags weight,related pages,associate page,associate post,posts similarity,similarity,shortcode,admin,image,images,plugin,sidebar,widget,rating,filters,excerpt
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

CP Related Posts is a plugin that displays related articles on your website, manually, or by the terms in the content, title or abstract, and tags

== Description ==

CP Related Posts is a plugin that displays related articles on your website, manually, or by the terms in the content, title or abstract, including the tags assigned to the articles.

The relationship between posts and pages is determined in a very unique way.  CP Related Posts uses an algorithm that allows you to identify the most representative terms in the content, the title and the abstract of the article, while giving more weight to those terms that match the tags explicitly added to the articles by the author.

In any website, there may be posts that are related, yet with no common terms.  CP Related Posts allows to assign connections to articles manually, attributing the highest level of importance to that type of connection.

CP Related Posts offers a great versatility in displaying related posts. With CP Related Posts, you can define the number of related posts, use a specific layout to display related articles. Similarly, it is possible to decide which information to display for the related post: title, author, tags by which they relate, and importantly, an image that identifies the percentage of correspondence between the two posts.


**CP Related Posts features:**

> Allows select the number of related posts
> Allows extract the related terms from the articles titles, contents
  and abstracts
> Uses an automatic algorithm to determine the weight of relationship
  between posts
> Allows associate posts manually (these are the most strong relationships)
> Allows define a threshold for relations between posts
 (Relations with a weight under the threshold are dismissed)
> Allows to insert Google AdSense ads between related posts

The base plugin, available for free, from the WordPress Plugin Directory, has all the features needed for associate website's articles.

**Premium features:**

> Allows to use different layouts for related posts in multi-posts pages
  (like home page or archives) and for single pages and posts
> Allows to use different graphics to represent the level of similarity
> Allows to use related posts with custom post types

**Demo of Premium Version of Plugin**

[https://demos.dwbooster.com/related-posts/wp-login.php](https://demos.dwbooster.com/related-posts/wp-login.php "Click to access the Administration Area demo")

[https://demos.dwbooster.com/related-posts/](https://demos.dwbooster.com/related-posts/ "Click to access the Public Page")

If you want more information about this plugin or another one don't doubt to visit my website:

[http://wordpress.dwbooster.com](http://wordpress.dwbooster.com "CodePeople WordPress Repository")

== Installation ==

**To install CP Related Posts, follow these steps:**

1. Download and unzip the plugin
2. Upload the entire "cp-related-posts" directory to the "/wp-content/plugins /" directory
3. Activate the plugin through the 'Plugins' menu in "WordPress"
4. Go to Settings > CP Related Posts and set up your plugin

Another way to install the plugin.

1. Go to the plugins section in your WordPress
2. Press the "Add New" button at beginning of page
3. Search by the plugin name CP Related Posts and install it

== Interface ==

**Setting up CP Related Posts**

The plugin has several setup options to have a great control over related posts. Among the available configuration options you will find:

* Number of related posts
* Post types that admit related posts (the free version of plugi allows only the posts and pages)
* Display percentage of similarity with the symbol... the option allows to select the symbol to represent the similarity between posts
* Display related posts with a percentage of similarity bigger than. Allows to define an acceptable percentage of similarity

Display options for the related posts on the individual post/page

* Display related posts in single pages (checkbox to display or hide the related posts from single pages)
* Display featured images in related posts
* Display percentage of similarity
* Display excerpt of related posts
* Display related terms between related posts
* Display mode (select the layout for related posts)

How to display related posts in multiple-posts pages

* Display related posts in multiple-posts pages
* Display only in homepage
* Exclude the related posts from homepage
* Display the related posts only in specific pages
* Exclude the related posts from specific pages
* Display featured images in related posts
* Display percentage of similarity
* Display excerpt of related posts
* Display related terms between related posts
* Display mode (select the layout for related posts)

Tip: After the initial setup of the plugin, it is recommended to press the button "Process Previous Posts" in order to extract the terms of the posts created before installing the plugin.

How to integrate with Google AdSense

The plugin allows you to insert Google Ads between related posts. The plugin settings pages includes a new section to enable the Google AdSense, and enter the Google AdSense client and unit. In addition, the ads section allows you to configure the location of the ads into the list of related posts. Furthermore, if the Borlabs Cookies plugin is installed on your WordPress, our plugin hides the Google ads until the user accepts the Google AdSense cookies.

On the Editing screen of each post, page, or post type selected in the plugin's settings, you will see a new form that allows you to create direct links between the post being edited and other posts on the website, as well as allowing to extract relevant terms within the text of the post and select them for use as tags of the post in question.

**The way to use CP Related Post from the posts edition**

After type the post's content, press the "Get Recommended Tags" button, to extract all terms (with its weight, form the sections of post). If the user want to increase the importance of specifics terms, only is required check them.

If you want insert relevant terms that are not present in the post's content, define it as a post's tag.

It is possible associate posts manually. Type the terms and press the "Search" button. In the Items found section will be displayed the list of posts/pages that include the term. Select the posts/pages to associate manually.

Note: The posts associated manually represent the most strong relation.

It is possible exclude a post from the list of related posts, with only check the option: "Exclude this post from others related posts", or hide the related posts from a post through the option: "Hide the related posts from this post"

**Filters called by CP Related Posts**

cprp_post_thumbnail: Requires two parameters, a link tag with the thumbnail, and the object with the post's data.

cprp_post_title: Requires two parameters, a div tag with the post title, and the object with the post's data.

cprp_post_percentage: Requires two parameters, a div tag with the percentage of similarity, and the object with the post's data.

cprp_post_excerpt: Requires two parameters, the text of generated excerpt, and the object with the post's data.

cprp_post_tags: Requires two parameters, a div tag with the tags assigned to the post, and the object with the post's data.

cprp_content: Requires two parameters, the html tags of the generated related posts, and an array with the objects of related posts.

== Frequently Asked Questions ==

= Q: How to relate posts manually? =

A: Type the terms and press the "Search" button. In the Items found section will be displayed the list of posts/pages that include the term. Select the posts/pages to associate manually.

= Q: How to break a manual relation? =

A: Go to the post's edition and press the "-" symbol from the items manually related.

= Q: How to use terms that are present in the post's content, title or abstract? =

A: Type the terms as tags associated to the post.

= Q: How to use a different icon to represent the similarity between posts? =

A: You only should replace the images for icon_on and icon_off, located in "/wp-content/plugins/cp-related-posts/images"

= Q: How to remove posts with little similarity? =

A: You only should increase the similarity percentage, from the settings page of plugin.

= Q: How to vary the number of words in the excerpt of related posts? =

A: Go to the settings page of the plugin and enter an integer number in the attributes: "Number of words on posts excerpts" for related posts on single pages, and pages with multiple entries. The integer number represent the maximum amount of words in the excerpts of posts (50 words is the number by default)

= Q: How can be hidden the related posts from the home page? =

A: Go to the settings page of plugin and check the option "Exclude the related posts from homepage"

= Q: How can hide the related posts from a page? =

A: Go to the page, and check the option "Hide the related posts from this post"

= Q: How can hide some pages of the website from the related posts? =

A: Go to the page or post and check the option "Exclude this post from others related posts"

= Q: I want to select the a size of images to use for related posts =

A: There are two options available in the settings page of the plugin (Size of featured images) for selecting the size of images to associate to the related posts, for single pages, and pages with multiple entries. WordPress includes three images sizes by default: thumbnail, medium, and large, but the themes, and plugins, can add new sizes for images, you simply should select the size of images to use in each case.

== Screenshots ==
01. CP Related Posts with slider layout
02. CP Related Posts with column layout
03. CP Related Posts with accordion layout
04. CP Related Posts with list layout
05. Relating posts
06. Settings page
06. Google AdSense integration

== Changelog ==

= 1.0 =

* First version released.

= 1.0.1 =

* Improves the plugin documentation.
* Corrects a compatibility issue with the JetPack plugin.
* Includes new features to exclude or include, related posts from specific pages or posts.
* Corrects an issue with pages of multiple entries.
* Improves the selection of related posts and pages.
* Removes some extra tags inserted by other plugins in the excerpts of the posts and pages.
* Allows remove all posts related manually.

= 1.0.2 =

* Corrects an issue with the words to be excluded from the tags, in those languages where are not defined the lists of words.
* Corrects an issue when have not extracted the tags from the posts/pages.

= 1.0.3 =

* Modifies the way that posts are selected manually.

= 1.0.4 =

* Prevents conflicts with other plugins that define the same classes.
* Allows disassociate selected tags from pages.
* Allows to define the similarity as zero.
* Improves some styles applied to the related posts.

= 1.0.5 =

* Removes the shortcodes before extract the tags.
* Includes  new terms in the list of tags to exclude.

= 1.0.6 =

* Displays related posts in the onload event of the "window" object, after the images have been loaded.

= 1.0.7 =

* Reduces the priority of the related posts insertion.
* Inserts a DIV tag with clear:both at the end of related posts.

= 1.0.8 =

* Allows to select the size of images associated to the related posts.
* Allows to enter the number of words to display as excerpts of related posts.

= 1.0.9 =

* Includes filters to allow modify  all sections of the related posts from other plugins, and the active theme on WordPress.

= 1.0.10 =

* Corrects an issue in the Quick Edit of posts.

= 1.0.11 =

* Adds a control variable for saving the information of related posts safely.
* Uses the classes constructor of PHP5 on widgets.
* Uses the <H1> tags in the titles of the settings pages.

= 1.0.12 =

* Corrects a conflict in the meta_box

= 1.0.13 =

* Adds new words to exclude from texts in French

= 1.0.14 =

* Modifies some deprecated jQuery functions.

= 1.0.15 =

* Uses the real excerpt of the posts and pages, if exists, or a summary of the post and pages contents in other cases.

= 1.0.16 =

* Escapes the query terms to prevent database errors.

= 1.0.17 =

* Escapes all tags' attributes and texts to prevent possible vulnerabilities.
* Improves the access to the plugin documentation.
* Includes the languages files.

= 1.0.18 =

* Improves the design of the related posts section, resolving conflicts with some themes.

= 1.0.19 =

* Allows the integration with the Gutenberg Editor, the editor distributed with the next versions of WordPress.

= 1.0.20 =

* Modifies the way the scripts are executed to prevent the uncaught javascript errors on the page affect the plugin.

= 1.0.21 =

* Modifies some URLs to support websites hosted in subdirectories.

= 1.0.22 =

* Replaces some functions deprecated in PHP 7.2

= 1.0.23 =

* Hides the promotion banner for the majority of roles and fixes a conflict between the promotion banner and the Gutenberg editor.

= 1.0.24 =

* Solves a conflict with the "Speed Booster Pack" plugin.

= 1.0.25 =

* Fixes an issue between the Promote Banner and the official distribution of WP5.0

= 1.0.26 =

* Modifies the language files and the plugin headers.

= 1.0.27 =

* Improves the module that determines the related pages/posts.

= 1.0.28 =

* Fixes an issue by escaping the apostrophes signs in the related terms.

= 1.0.29 =

* Improves the plugin security sanitizing every value received from the browser.

= 1.0.30 =

* Fixes an issue with the quick edit posts.

= 1.0.31 =

* Modifies the access to the demos.

= 1.0.32 =

* Modifies the module that create the relationship between prioritized tags and posts tags.

= 1.0.33 =

* Fixes an encoding issue in some ampersand symbols on generated URLs.

= 1.0.34 =

* Modifies deprecated code structures.

= 1.0.35 =

* Fixes some notices.

= 1.0.36 =

* Modifies the queries to prevent a conflict with third party plugins.

= 1.0.37 =

* Adds title attributes to the related posts' links, and images.

= 1.0.38 =

* Includes new validation rules to prevent conflicts with third party plugins.

= 1.0.39 =

* Implements the [cp-related-posts] shortcode to allow inserting the related posts into different places of the content.

= 1.0.40 =

* Modifies the way the plugin uses the terms extracted from the posts to generate their tags, making the process more intuitive and less intrusive.

= 1.0.41 =

* Allows extracting terms even from the posts' taxonomies.
* Includes the matching tags in posts related manually.
* Fixes some minor conflicts with third-party plugins.

= 1.0.42 =

* Allows integrating Google AdSense into the list of related posts.
* Includes a new section in the plugin settings page to configure the Google AdSense and integrate Borlabs Cookies.

= 1.0.43 =

* Includes minor styles modifications.
* Implements the Thumbnail Slider display mode (Professional version).

= 1.0.44 =

* Optimize the plugin's code.
* Modifies the Thumbnail Slider mode to allow to define the items' widths (Professional version).

= 1.0.45 =

* Improves the plugin code and security.

= 1.0.46 =

* Modifies the banner module.

== Upgrade Notice ==

= 1.0.46 =

Important note: If you are using the Professional version don't update via the WP dashboard but using your personal update link. Contact us if you need further information: http://wordpress.dwbooster.com/support