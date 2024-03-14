=== Plugin Name ===
Contributors: remarkno
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=info%40remark%2eno&item_name=Complete%20Image%20Sitemap
Tags: google image sitemaps, woocommerce image sitemaps
Requires at least: 3.0.1
Tested up to: 6.0
Stable tag: 6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Complete Image Sitemap plugin will generate an XML Sitemap for all images, including Woocommerce products.

== Description ==

Image sitemaps inform search engines about image content location on your website.

The Complete Image Sitemap plugin will generate a sitemap for your WordPress-based website with the image URLs that are attached to your posts: both blog posts, pages and other types, such as Woocommerce products.

It is also possible to specify the license for all images on your website. Be careful before you donate everything to the public domain though :-).

The plugin is written by [Herbert van-Vliet](http://remark.no/about-me/ "Herbert van-Vliet") of [Remark.no](http://remark.no/ "Remark.no"), and is based on Amit Agarwal's image sitemap plugin.

== Changelog ==

= 1.1.1 =
* Fixed bug that displayed a wrong link to the generated file, thanks to @twinword
* Fixed serious bug that referred to a non-used variable, emptying all image references from the xml, thanks to @twinword

= 1.1 =
* Added support to specify licenses for the images

== Installation ==

Here's how you can install the plugin:

1. Upload the plugin folder and its content to the /wp-content/plugins/ directory on your webserver.
1. Activate the plugin via the 'Plugins' menu in WordPress.
1. Expand the Tools menu from WordPress dashboard sidebar and select "Complete Image Sitemap".
1. Click the "Generate Image Sitemap" button to create your sitemap.
1. Once you have created your sitemap, you can submit it to Google using Webmaster Tools.

== Frequently Asked Questions ==

= How can I submit the image sitemap to Google? =

Check Google Webmaster Tools.

= Where is the sitemap file stored? =

The sitemap is stored as "sitemap-images.xml" in the root of your website.

= I am getting errors indicating I do not have the proper permission =

This is most likely caused by the fact that you do not have write permission on the website webroot. Use chmod to set the necessary permissions. Do NOT set that to 0777, unless you know what you are doing.
