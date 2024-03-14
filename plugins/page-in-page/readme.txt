=== Page In Page ===
Contributors: tcyr
Donate link: http://goo.gl/m4r02B
Tags: post, page, widget, shortcode, insert post in page, facebook posts, twitter feeds
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 2.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin helps you insert a post or page from the WP posts database table within another, bring your Facebook posts and Twitter feeds to your blog.

== Description ==

The page-in-page plugin has a very simple mission: 

*	Insert posts and pages within each other with no stress.

*	Bring your Facebook Page posts to your WP pages.

*	Show your Tweets in your WP blog.

It provides possibilities to use both widgets and shortcodes. If using a widget you are only able to include pages within pages but if using the shortcode, you are able to able to insert posts/pages
within other posts/pages.

For Facebook Page posts, only the first 25 most recent posts are returned and for Tweets, only the first 20 most recent tweets are returned. In future releases maybe we will include pagination for social feeds.

== Installation ==

Steps to install this plugin.

1. In the Zip file, there is a folder with name 'page-in-page'
2. Upload the 'page-in-page' folder to the '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Read the usage instructions below (or go to 'Other Notes' section)

== Usage ==

= Using the widget =

The settings in the widget include:

*	*Title*: Widget Title. This title will be shown as the page's title if 'Show page title' option is not selected.

*	*Page*: Select the page that will be included when widget is called.

*	*Show page title*: If checked (selected) then the page title will be shown and the 'Widget Title' ignored.

*	*Show title as link*: If checked (selected) the title will be displayed as a link to the page.

*	*Show page content*: If checked (selected) then the page content will be included in the output.

*	*Show featured image*: If checked (selected) then the featured image will be included in the output.

*	*Show featured image as link* : If checked (selected) then the featured image will be included and linked to page in the output.

*	*Output Template*: Insert an HTML template that will be used to display content of the widget. If not provided the default template will be used

	Slugs that can be used in your template are **${page_title}**, **${page_content}**, **${page_link}**, **${page_image}** . Each are self explanatory of what they will be replaced with.

	The default output template is 

	&lt;div class="twl-page-in-page"&gt;
	
	&lt;span class="twl-page-in-page-title"&gt;${page_title}&lt;/span&gt;
		
	&lt;div class="twl-page-in-page-content"&gt;
	
	&lt;div class="twl-page-in-page-image"&gt;&lt;img src="${page_image}" /&gt;&lt;/div&gt;
	
	&lt;div class="twl-page-in-page-text"&gt;${page_content}&lt;/div&gt;
			
	&lt;/div&gt;
		
	&lt;/div&gt;


= Using shortcode =

With shortcodes, you can insert posts in posts, pages in pages, posts in pages and vice versa.

Settings that can be used in a shortcode are:

*	id : The ID of the page/post you want to insert
*	show_page_title: should the page title be displayed? (Can be 1 for true or 0 for false. Defaults to 1)
*	show_page_content: should the page title be displayed? (Can be 1 for true or 0 for false. Defaults to 1)
*	show_title_as_link: Can be 1 for true or 0 for false. Defaults to 0. If set to 1, the page title will be displayed as a link to the page/post
*	show_featured_image: Can be 1 for true or 0 for false. Defaults to 0. If set to 1, the page's featured image will be included
*	show_featured_image_as_ink: Can be 1 for true or 0 for false. Defaults to 0. If set to 1, the page's featured image will be included as a link

**Note that** if you are specifying a template in the shortcode, then the above settings will be ignored and the slugs you insert in your template will be replaced with appropriate content.
See a template example and allowed slugs above.

To insert a shortcode you can do one of the following:

*	To insert without specifying a template you can simply use

	[twl_page_in id=123] OR [twl_page_in id=123 show_page_title=1].

*	To insert your shortcode specifying a template for page/post's title and content use the following. You can include other supported slugs mentioned above

	[twl_page_in id=123 show_page_title=1]
	
	&lt;h3 class="my-awesome-title-class"&gt;${page_title}&lt;/h3&gt;
	
	&lt;div class="my-awesome-content-class"&gt;${page_content}&lt;/div&gt;
	
	[/twl_page_in]

	The template specified in the [twl_page_in] tag will be used to display the page/post with the specified id when inserting it. The template is optional and if not specified then the default template will be used.


	**IMPORTANT!!!** If you specify a template, you MUST have the slugs you want to be shown else you might get unexpected results

	**IMPORTANT!!!** This template has to be defined when Editor is in 'Visual' mode and NOT in 'Text' mode (see screen shot). If you do not respect this you might have unexpected results because HTML tags might not be parsed properly.
	However if you have escaping with magic quotes off on your server then template should be defined when editor is in 'Text' mode.

*	To insert posts from your Facebook page, Go to Admin > Settings > Page In Page Plugin and insert your facebook application credentials.
	Next edit the page where you want the posts to appear and insert the short code **[twl_page_in_fb]**. See screenshot 3 for output.

*	To insert tweets from your twitter account, Go to Admin > Settings > Page In Page Plugin and insert your twitter application credentials.
	Next edit the page where you want the posts to appear and insert the short code **[twl_page_in_tw]**. See screenshot 4 for output.

== Screenshots ==

1. Widget settings (see 'Usage' section of the readme.txt file for explanations)
2. Using shortcode (with template) in editor
3. Facebook posts listing
4. Twitter post listing

== What Next? ==

Find a bug or got any worries? well never mind just send an email to cyril.tata@hotmail.com.

Future releases: Integrate same functionality across multi sites and pagination for social page feeds.

== Frequently Asked Questions ==

= What is the shortcode used to insert a page using the WP editor =

Shortcode: **[twl_page_in_wp id=5 show_page_title=1]** where *id* should be the id of the WP page and *show_page_title* can be 1 or 0. If 1 then the post/page's title will be shown too.

OR you can specify a template for the title and content (or with other supported slugs ${page_image}, ${page_link})

[twl_page_in id=123 show_page_title=1]
	
&lt;h3 class="my-awesome-title-class"&gt;${page_title}&lt;/h3&gt;
	
&lt;div class="my-awesome-content-class"&gt;${page_content}&lt;/div&gt;
	
[/twl_page_in]

= What shortcodes should I use to insert Facebook posts and Twitter tweets in my WP page =

Frist go to *Admin > Settings > Page-In-Page Plugin* and enter your Facebook or Twitter application credentials and then use any of the shortcodes below.

Facebook shortcode: **[twl_page_in_fb]**

Twitter shortcode: **[twl_page_in_tw]**

== Changelog ==

== 2.0.3 ==

* Remove non-GPL compatible jquery isotope library

= 2.0.2 =

* **Important** In the widget, the "title template" and "content template" fields have been unified to "output template". Template specification in editor remains unchanged but more slugs added.
* More settings added
	* show page title (shortcode parameter: show_page_title. Can be 1 or 0)
	* show page title as link (shortcode parameter: show_title_as_link. Can be 1 or 0)
	* show page content (shortcode parameter: show_page_content. Can be 1 or 0)
	* show featured image (shortcode parameter: show_featured_image. Can be 1 or 0)
	* show featured image as link (shortcode parameter: show_featured_image_as_link. Can be 1 or 0)
* If an output template is specified, then the settings above are ignored so all necessary slugs should be specified in output template.
* Slugs to be used in templates: **${page_title}**, **${page_content}**, **${page_link}**, **${page_image}** .
* Usage of namespace for twitter SDK removed due to complaints for PHP < 5.3 (this change is only for those who have not been able to use the twitter shortcode)
* Some code re-factoring

= 2.0.1 =

* code re-factoring

= 2.0 =

* Add possibility to include **posts** from a facebook page.
* Add possibility to include tweets from a twitter timeline.
* Include new shortcodes: **[twl_page_in_wp]** for inserting a WordPress page, **[twl_page_in_fb]** for inserting Facebook posts and **[twl_page_in_tw]** for inserting user tweets.

= 1.0 =
* Initial version of plugin

== Upgrade Notice ==

** make sure you understand the new template merger before updating especially if you used the widget to specify title and content templates**
** fixed title linking and added option to link images **
** change compatibility up to WP 3.8 **
** Remove non-GPL compatible jquery isotope library



