===  Bulk edit image alt tag, caption & description  - WordPress Media Library Helper by Codexin  ===
Contributors: cxntech, cxnmedia
Tags: Alt tag, Image caption, Bulk edit, Media Library, SEO
Requires at least: 4.8
Stable tag: trunk.
Tested up to: 6.3
Requires PHP: 5.6.39
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Add or edit or bulk edit image ALT tag, caption & description with one click straight from the WordPress media library to improve your SEO score.


== Description ==
If you have a large number of images in your WordPress media library and are concerned about updating their ALT tags, captions, descriptions or titles, worry no more. This plugin allows you to easily modify, delete, or update these elements with just a few clicks directly from the media library page in your WordPress dashboard.

This plugin is the perfect solution to update metadata for multiple images without visiting each edit page. Accessing the WordPress media library page lets you easily view the existing images' alt tags, captions, descriptions and titles. The plugin also identifies the images without an assigned ALT tag, caption, or description, enabling you to update them quickly and easily with just a few clicks. 

On-page SEO is crucial in boosting your website's SEO score, and assigning proper image ALT tags to every image on your website is critical. With the help of this plugin, you can quickly identify images with empty or blank ALT tags, blank captions or descriptions in your media library and update them to enhance your SEO score.


= Features =
1. Add/edit/update the image titles, ALT tag, caption & description with one click directly from WordPress Media Library
1. Search for a specific SEO keyword assigned to your images as an alt tag, caption or description.
1. Search for blank or empty ALT tags, captions and descriptions and update/edit as needed.
1. Sort media library images by ALT tag, image caption or image Description.
1. Bulk edit image ALT tag or alt attribute
1. Bulk edit image title
1. Bulk edit image caption
1. Bulk edit image description
1. Improve SEO score by assigning the proper image metadata.


= How does this plugin work or How to edit image metadata =
1. Install the plugin "Media Library Helper by Codexin"
1. From Dashboard, go to media --> Library --> Open the "List View"
1. Unlock the edit mode. (You will see a button at the top, named "Edit mode is locked")
1. Continue updating image ALT text, caption and description as you need.
1. Once finished, keep the edit mode Locked again.

== Installation ==
= Install using WordPress dashboard =
1. Go to the WordPress dashboard.
2. Click on Plugins -> Add New
3. Type "Media Library Helper by Codexin" in the search box and hit enter.
4. Once you found our plugin, click on the "Install Now" button
5. Once installed, then click on the "Activate" button

= Install using FTP =

1. Download the plugin from the WordPress plugin repository
2. Unzip the archive on your computer
3. Upload the media-library-helper directory to the /wp-content/plugins/ directory
4. Activate the plugin through the "Plugins" menu in WordPress

== Screenshots ==
1. Image ALT tag, Caption & Description Visibility
2. Unlock Edit Mode
3. Single Edit Image ALT Tag, Caption & Description
4. Bulk Edit Image ALT Tag, Caption & Description
5. Perform a Search

== Frequently Asked Questions ==

= Will this plugin update existing images title, ALT tags, captions or descriptions in the media library? =

This plugin will not automatically update existing images' metadata in the media library. Once you install this plugin, then you have to update the image title, ALT Tag (ALT Text), Caption, and Description manually using this plugin.

= I can't see image ALT, Caption or Description columns inside Media Library =

Make sure you have set the ALT tag, Caption and Description visible to the media library by going to the "Screen Options" at the top right corner of the page.

= How to bulk edit image metadata =

Go to the list view in your media library dashboard, then select the images you want to bulk edit. From the top dropdown menu, select "Edit" and then click on "Apply". This will open the bulk edit editor. You can set image alt tag, caption and description as needed. The provided image alt tag, caption and description will be set for all the selected images.

= How to search for blank or empty alt tag, caption and description? =

Go to the list view in your media library dashboard. Keep the search box empty or blank. Select alt, caption or description from the search box dropdown list as you want and finally, click on filter.

= I have updated/modified the image titles, ALT tags, captions or descriptions for some of the images, but those images still have the old titles, ALT tags, captions or descriptions where they are attached  =

This usually happens when the image titles, ALT tag, caption or descriptions have been hard-coded into your pages or posts. In other words, those image metadata are not being called dynamically from the media library database. If your pages/posts have hard-coded image metadata or use a page builder that provides a separate option to add image ALT tags, captions or descriptions, then updating image metadata from the WordPress media library won't help. You need to go to that specific page or post and need to find out the existing hard-coded image alt tag, and replace them as you need. 

= Will this plugin update the images URLs or slugs as soon as I modify the image titles?  =

No, modifying the image titles using this plugin will not update the image URLs or slugs. Only the image titles will be updated, while the URLs or slugs will remain unchanged.

== Changelog ==

= 1.3.0 =
* Addressing the Cross-Site Request Forgery (CSRF) vulnerability. 
* Code optimization and security enhancements.
* Updated readme.txt

= 1.2.0 =
* New feature: Title editing functionality has been added, allowing users to modify titles
* Code optimization and security enhancements
* Minor CSS adjustments
* Updated readme.txt

= 1.1.0 =
* New features integrated. Users can now search directly in the media library search box for blank or empty alt tags, captions and descriptions.
* Compatibility checked with PHP version up to 8.1
* Minor CSS changes
* Updated readme.txt

= 1.0.4 =
* Resolved compatibility issues with WordPress version 6.0
* Minor CSS changes
* Updated readme.txt

= 1.0.3 =
* Removed unused public ajax callback function
* Fixed a conflict when jQuery UI draggable feature is available on the WordPress media library page (list view).
* Updated readme.txt

= 1.0.2 =
* Resolved unknown column issue when GiveWP plugin is active
* Updated readme.txt

= 1.0.1 =
* Resolved media library nav menu visual glitch
* Tweaked search reult by alt tag

= 1.0.0 =
* First version!