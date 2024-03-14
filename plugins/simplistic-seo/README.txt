=== Plugin Name ===
Contributors: walkeezy, ruvus
Donate link: https://www.paypal.me/walkeezy
Plugin URI: https://github.com/Walkeezy/Simplistic-SEO
Tags: seo, search engine, metatags, titletag, metadescription, sitemap xml, sitemap.xml, google
Requires at least: 4.4
Tested up to: 6.2
Stable tag: 2.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Everything you need for basic SEO in one simple plugin. Lets you optimize title tags and meta descriptions directly from the post screen.

== Description ==

Everything you need for basic SEO in one simple plugin. Lets you optimize title tags and meta descriptions directly from the post screen and shows you a preview of how your website will look like in Googles search results.

If you don't want to set a title and description for every single post or page, you can set a template and the plugin will automatically generate the title tags and meta description for you.

On top of that, there is an option to automatically generate the XML sitemap for your whole WordPress site.

Full features list:

* Edit post title tags and meta descriptions
* Automatically generate title tags based on your template
* Automatically generate meta descriptions from the content
* Preview how your website will look like in Googles search results
* Automatically generated XML sitemap

Plugin translations:

* English
* German

Planed for future releases:

* Ability to create different title and meta description templates for all post types
* Ability to choose which post types to include in the XML sitemap
* Support for Custom Fields (including those from the Advanced Custom Fields plugin) to generate meta descriptions from

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the plugins screen in WordPress.
3. Use the `Settings -> SEO settings` screen to configure the plugin.
4. Optimize your title tags and meta descriptions directly from the post, page or custom post type editing screen.

== Screenshots ==

1. Optimize title tag and meta description directly from the post screen. On the right you will see how your website will look like in Googles search results.

== Changelog ==

= 2.3.0 =
* Now you can select, which post types / taxonomies are showed in the sitemap
* bugfixes

= 2.2.1 =
* Bugfix multi domain rewrite

= 2.2.0 =
* Added New Feature to generate multiple sitemaps per domain (Only usefull with multi domain on same instance). 
* Bugfix: Error Message handling_post.php line 140

= 2.1.5 =
* Bugfix: Fixes on WP6

= 2.1.4 =
* Bugfix: Empty excluded page array -> setting improvements

= 2.1.3 =
* Bugfix: Empty excluded page array -> setting improvements

= 2.1.2 =
* Bugfix title tag generator

= 2.1.1 =
* Auto generate Titeltag for taxonomies


= 2.1.0 =
* Added terms & taxonomies to sitemap

= 2.0.1 =
* Bugfix sitemap generating

= 2.0.0 =
*Release date: 2021-10-18*

* New Version => 2
* Exclude pages from sitemap generating
* Custom post type which not queryable not showing in settings
* Add title and meta description now to categories and terms


= 1.6.2 =
*Release date: 2021-09-14*

* Bugfix: Small fixes Titel and Meta description.



= 1.6.1 =
*Release date: 2021-09-12*

* Bugfix: Title on archive pages dont show title from the first post.


= 1.6 =
*Release date: 2021-08-10*

* Added option to select on which post types seo settings should be displayed
* Only rebuild sitemap on save_post or if file isn't existing anymore
* Removed xsl-stylesheet from sitemap.xml

= 1.5 =
*Release date: 2021-07-27*

* Prevent WPError after plugin updates

= 1.4 =
*Release date: 2018-08-07*

* Added support for Twitter cards.
* Tested up to WordPress 5.0.

= 1.3 =
*Release date: 2017-05-31*

* Reduced the priority of the SEO metabox on the post screen to 'low'.
* Fixed a bug with placeholders in the preview of the title tag.

= 1.2 =
*Release date: 2017-05-29*

* Fixed an important bug with the meta description tag.

= 1.1 =
*Release date: 2017-05-16*

* If the content of a page or post is empty, the meta description tag will no longer be added to the html of your page.
* A default pattern for the title setting has been added.
* Fixed a bug where the sitemap.xml has been generated every time a post has been saved, even if the sitemap has been disabled in the settings.
* Fixed a CSS bug in the Google preview feature.
