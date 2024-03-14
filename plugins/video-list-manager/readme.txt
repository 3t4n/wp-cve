=== Video List Manager ===
Concontributors: thanhtungtnt 
Donate link: http://videolistmanager.blogspot.com/
Tags: video, youtube, vimeo, dailymotion, video list, video manager, video list manager 
Requires at least: 3.0.1
Tested up to: 5.4.1
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display videos easily (from YOUTUBE, VIMEO, DAILYMOTION) with lightbox effect. Especially, all your videos will be fitted on all layouts. 

== Description ==

Video List Manager is the plugin for WordPress, created by Tung Pham (email: tungpham.bh@gmail.com). It helps your site display videos easily with lightbox effect easily. Especially, all your videos will be fitted on all themes. 

Read more at: http://videolistmanager.blogspot.com/

Main Features:

* Display videos
* Support Youtube, Vimeo, Dailymotion Video
* Using colorbox jquery as a lightbox effect
* Support 5 colorbox skins
* Fit all layouts
* Share links via facebook, twitter, pinterest

Tutorial: http://www.youtube.com/watch?v=R_0BmfKC1Jw

== Installation ==

1. Download, install, and activate the Video List Manager plugin.

3. From your WordPress Dashboard, go to Video List Manager > Add Video. To add cateogory, go to Video List Manager > Add Cateogory

3. Add a new post/page, use shortcodes in Categories.

4. Note: You need go to Settings ==> Permalinks and choose Post name as link structure.  

== Frequently Asked Questions ==

No Frequently Asked Questions

== Screenshots ==

1. Video Manager
2. Options
3. Showing in grid 
4. Colorbox Effect

== Changelog ==

= 1.0 =
* The first version
* Support youtube video

= 1.1 =
* Add multi video to a category
* Support Vimeo video
* Add validate to "add video form", "edit video form", "edit category form", "setting form" 

= 1.2 =
* Fix bug add single video
* Fix bug update database
* Add remove button to video item

= 1.3 =
* Support Dailymotion Video

= 1.4 =
* fix bug pagination
* allow to sort results by many ways
* add created date, modified date

= 1.5 =
* fix bug pagination
* remove donation link

= 1.6 =
* fix vimeo thumbnail bug
* add social share feature for every video

= 1.7 =
* fix bug in wordpress 5.4.1

== Upgrade Notice ==

= 1.0 =
* Please enable permalinks (Settings --> Permalinks) before activating this plugin.

= 1.1 =
* About database: If the plugin doesn't automatically insert video type "Vimeo", you should insert manually a Vimeo type in table "wp_tnt_videos_type" (for only developer) 

= 1.2 =
* No notice

= 1.3 =
* No notice

= 1.4 =
* This version have a database update. So, after updating plugin, please deactivate it, then reactivate it once more so that plugin automatically add necessary fields. 

= 1.5 =
* No notice

= 1.6 =
* No notice

= 1.7 =
* No notice

== Arbitrary section ==

CREDIT
Copyright:
Tung Pham © 2012 - 2020
Email: tungpham.bh@gmail.com

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
       
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
   
You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

SHORTCODES EXAMPLE: 
Show a video list with category id = 4 : 
[tnt_video_list id=4] (default: 2 columns, 4 videos per page, width: 480, height: 360)

Show a video list with category id = 4, 3 columns, 10 videos per page, width: 520px, height: 420px : 
[tnt_video_list id=4 col="3" limit="10" width="520" height="420"]

Show a video with video id = 3, width: 400, height: 300 
[tnt_video id=3 width="400" height="300"]

Read more at: http://videolistmanager.blogspot.com/