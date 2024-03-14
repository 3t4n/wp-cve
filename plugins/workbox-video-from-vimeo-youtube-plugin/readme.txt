=== Workbox Video from Vimeo & Youtube Plugin ===
Contributors: Workbox
Author URI: http://www.workbox.com/
Tags: Video, Gallery, Vimeo, Youtube, Wistia
Requires at least: 3.5
Tested up to: 4.9.4
Stable tag: trunk

Quick and easy way to add and manage videos on your site or blog. Supports Vimeo, Wistia, YouTube.


== Description ==
The plugin allows to create a video gallery on any wordpress-generated page. 
You can add videos from Youtube, Vimeo and Wistia by simply pasting the video URL. 
Allows to control sort order of videos on the gallery page. Video galleries can be called on a page by using shortcodes now.
This plugin is for advanced users. If you run into problems, please send us detailed notes about your set up and the errors and we'll do our best to get back to you. 
* Version 2.0: Added ability to create multiple galleries!
* Version 3.0: Added ability to map a video to multiple galleries.  

Spanish translation by Andrew Kurtis <a href="http://www.webhostinghub.com/">@WebHostingHub</a> - the updated translation will be provided later

[Plugin Page on Workbox Site](https://www.workbox.com/wordpress-video-gallery-plugin/)


== Installation ==


Download and activate the plugin.



== Frequently Asked Questions ==
1. How do I add the gallery to a page?
Go to Video Categories  and click on Add New, enter the Gallery Name and select the website page from the Gallery Page dropdown list. The gallery description field serves to show intro text above the video list. 
2. How do I control gallery options?
On the plugin settings page you can also control the look and feel of the gallery including most of the tags and number of videos to show per page.
3. Can I use a shortcode to add a gallery to a page or post? 
Yes, to do this, please use the following shortcode [workbox_video_YV_list gallery_name="Test Post Gallery"] where Test Post Gallery is the name of the gallery you want to use on the page. You can also specify the gallery ID instead of the gallery name.  
4. How do I sort videos within a gallery?
Click the Sort in <gallery name> link above the video list. Then drag and drop  the lines. The sort order will automatically change as soon as you have dropped  the line.
5. I upgraded the plugin to Version 3.0 and lost all my videos. What do I do?
Go to plugin options page and click the Upgrade DB Manually button to update your database and get the videos and video categories back.



== Changelog ==


= 2.0 =
* Users can create as many galleries as they want. Each gallery can be attached to one page.
* Videos sort order: displaying the most recently added videos at the top of the list.

= 2.1 =
* Fixed bug with updating plugin files
* Fixed critical bug with adding galleries and videos
* Note! Tables from databases will be deleted after uninstalling the plugin

= 2.1.1 =
* Fixed editor bug: html paragraph tags appearing in visual mode

= 2.2 =
* Users can use a shortcode to add a gallery to a page or post.

= 2.2.1 =
* Fixed bug with automatically adding http in url.

= 2.3 =
* Users can make gallery vertical or horizontal in line.

= 2.3.1 =
* Fixed problem with video ids interfering when more than 1 gallery is attached to one page.

= 2.3.2 =
* Added Russian language and added languages file

= 2.3.3 =
* Little edits code

= 2.3.4 =
* Fixed fatal bug with query() non-object

= 2.3.5 =
* Fixed css bug 

= 2.4 =
added ThickBox popup support. Removed jQuery loading from external source.

= 2.4.1 =
Fixed sort order error

= 3.0 =
* Plugin backend interface enhancements. Is now Wordpress custom posts based which will have a major impact on compatibility with future WordPress versions 
* Added ability to map a video to multiple galleries as well as sort videos within each gallery using the drag & drop feature 
* In the shortcode, users can specify both the gallery name and gallery ID.
* If the shortcode missing the gallery name, the page shows all videos sorted alphabetically
* When used in third-party PHP code (theme or another plugin), the showList method can identify both a gallery ID/name and an array of gallery names as an argument. In this case, the video list displays sorted alphabetically.
* Russian and Spanish translations have been temporarily removed to be added back later 

= 3.1 =
* Fixed the plugin upgrade process from Version 2.* to Version 3.* Added ability to manually update the database.

= 3.1.1 =
* Not using get_plugin_data to avoid some upgrade problems

= 3.1.2 =
* Fixed import process is some categories already exists. (required in manual import and re-import only)

= 3.1.3 =
* Fixed pagination bug

= 3.1.4 =
* Fixed video sorting bug

= 3.2.0 =
* Added "clean upgrade" option for cases when some of the videos are missing after upgrade from v 2.* to v 3.*
* In the upgrade procedure to v 3.* there was a bug: videos were sorted in the opposite way. The bug was fixed, but it may cause sort order changes for users that had upgraded to v 3.* before. To minimize the possible hassle, there is now the option to revert videos sort order for specific categories (for categories only). In cases where all videos are shown, sort order will not change because all videos in such case are sorted by name.
* Video list display optimization: bug fix (slow browser and high memory usage caused by list of 10 or more video files)

= 3.2.1 =
* Fixed undefined WB_VID_URL constant issue
* Added "Play" button (with option to disable it)
* Fixed vertical scroll issue in video popup

= 3.2.2 =
* CSS issues related to long video titles in horizontal layout fixed;
* Error related to using Gallery ID instead of Gallery Name in shortcodes fixed;
* A column with shortcodes automatically generated for each video category added to the video category list.