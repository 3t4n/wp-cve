=== Simple Image XML Sitemap ===
Contributors: blapps
Tags: google image sitemaps, xml image sitemap, advanced custom fields
Requires at least: 4.0
Tested up to: 6.3
Stable tag: 3.4
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

The Simple Image XML Sitemap plugin will generate a XML Sitemap for specifically for all images including images uploaded as Advanced Custom Fields (Plugin).

== Description ==

The Simple Image XML Sitemap plugin will generate a XML sitemap for all your images uploaded within pages and posts (added as attachments).

Therefore, the specific Image XML Sitemap will contain the URL to the post or page and URLs to all attached images and image meta data (caption and title).

The plugin is written by Janine, and is based on Herbert van-Vliet's image sitemap plugin.


== Installation ==
= From your WordPress dashboard =

1. Visit 'Plugins > Add New'.
2. Search for 'Simple Image XML Sitemap'.
3. Activate Simple Image XML Sitemap from your Plugins page.

= From WordPress.org =

1. Download Simple Image XML Sitemap plugin.
2. Upload the 'simple-image-xml-sitemap' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate Simple Image XML Sitemap from your Plugins page.

= Once Activated =

1. Visit WordPress dashboard sidebar in 'Tools > Simple Image XML Sitemap'.
2. Click the "Generate" button to create or update your sitemap.
3. Once you have created your Image XML Sitemap, you can submit it to Google Search Console (formerly known as Webmaster Tools).

== Frequently Asked Questions ==

= How are images technically recognised according to posts and pages? =

Every image you upload to media center via post or page creation is stored as "attachment" and therefore will be added to the Image XML Sitemap

= Where does the image meta data caption and title come from? =

When you upload an image, you enter image meta data such as title, caption, alt text, description. (Otherwise you can add this information also instantly in Media Center).
This place is where the data displayed in Image XML Sitemap does come from.


= How can I speed up sitemap creation? =

With every post save, the sitemap will be recreated. Depending on the amount on posts and images, this can take a bit of time. To speed up, post saving, you can create image sitemaps manually. 
To do so, first uncheck the "auto save" in the sitemap settings, and then click the "generate sitemap" button in the tools section.

= How can I submit the image sitemap to Google? =

In Google Search Console (formerly known as Google Webmaster Tools) there is a menu entry for Sitemap. This location is to enter your path to your Image XML Sitemap.

= Where is the sitemap file stored? =

The sitemap is stored as sitemap-images.xml in the root of your website.

= I am getting errors indicating I do not have the proper permission =

This is most likely caused by the fact that you do not have write permission on the website webroot. Use chmod to set the necessary permissions. Do NOT set that to 0777, unless you know what you are doing.

== Changelog ==

= 3.4 =
* Ready for WP 6.3


= 3.3 =
* Minor bug fixes


= 3.2 =
* Minor bug fixes
* Ready for WP 6.2


= 3.2 =
Ready for WP 5.7

= 3.1 =
Minor changes of translations and added settings link

= 3.0 = Major Release
* Added Settings to customize sitemap
* Choose from different sources to display image caption and title
* Choose which post types to include in sitemap

= 2.6 =
Adjustments to image file names path

= 2.5 =
Added Plugin Recommendation

= 2.4 =
changed post title to post content, tested with 5.3

= 2.3 =
Small adjustments to readme

= 2.3 =
Post types only in status publish will be added to Sitemap

= 2.2 =
Image Sitemap will be updated automatically when editing posts

= 2.1 =
Changes sort order for Sitemap's entries

= 2.0 =
Added language support: German (Deutsch)

= 1.1 =
updated readme, css div class adjustments

= 1.0 =
Initial release