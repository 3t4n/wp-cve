*** Vimeotheque Lite Changelog ***

2023-12-15 - Version 2.2.8
* Modified Vimeotheque template styling to be less restrictive;
* Added post ID as element data (data-video_id="the post ID") to video embed container.

2023-11-10 - Version 2.2.7
* Solved a bug in WP 6.4+ that prevented the Block Editor from loading the Vimeotheque blocks.

2023-10-19 - Version 2.2.6
* Solved a bug when importing videos that generated a warning when the Vimeo API query ended with an error that was not issued by the Vimeo API;
* Introduced compatibility for playlists shortcode and block to sort the videos using the menu order; this allows compatibility with Post Types Order plugin to manually order the videos displayed in playlists.  

2023-10-14 - Version 2.2.5
* Solved a bug that caused responses from the block editor to end up with an undefined variable error.

2023-05-19 - Version 2.2.4
* Player embedded by using the player embed URL from the Vimeo API response;
* Separated filter that allows embedding into the post content (vimeotheque\post_content_embed) into a front-end filter and an administration filter (vimeotheque\admin_post_content_embed) to avoid conflicts;
* Introduced filter 'vimeotheque\the_video_embed' that allows the output of templating function vimeotheque_the_video_embed() to be modified.

2023-05-05 - Version 2.2.3
* Solved bug that caused broken documentation links to be displayed into the plugin.

2023-04-14 - Version 2.2.2
* Solved XSS vulnerability in admin area;
* Solved a bug that caused playlist theme to issue an error.

2023-02-13 - Version 2.2.1
* Solved bugs that prevented the title and post content from being properly imported when templates are enabled.

2023-01-16 - Version 2.2
* Introduced template system for displaying video posts having post type "vimeo-video" that supports override from the WP theme;
* Added first time "Installation Setup Guide" that gets displayed after the plugin is activated for the first time on the website (doesn't trigger if plugin options are already saved into the database);
* Added plugin Settings option to enable templates. When enabled, several options will have predefined values that can't be changed (ie. the 'vimeo-video' post type visibility in front-end will always be true, descriptions will be imported as post content, the video title and featured image will always get imported);
* Changed the defaults for several options: the tags are set by default to be imported, the video date from Vimeo is set to be imported, the featured image is set to be imported and the default post status is set to "Publish";
* Changed the embedding defaults: the embed width is set by default to 900 and the video volume to 45/100;
* Enabling templates from the plugin Settings page or by adding theme support for templates will disable some options from the Settings page and from importers;
* Created several helper functions that can be used in theme templates to embed the video and display various information about the video;
* Added embed end card functionality that displays a message after the current video playback ends asking if it should automatically load the next video post.

2022-11-14 - Version 2.1.17
* Solved a bug that prevented the player from going full screen;
* Solved a compatibility bug with the Classic Editor plugin.

2022-03-25 - Version 2.1.16
* Solved JavaScript bug in video playlist script that prevented multiple playlists from running.

2022-03-10 - Version 2.1.15
* Updated options processing to allow exclusion of options when retrieving the plugin options;
* Added resource "showcase" as a duplicate for "album".

2022-02-10 - Version 2.1.14
* Updated various scripts to avoid usage of jQuery Migrate;
* Solved PHP 8 specific errors and notices.

2022-01-11 - Version 2.1.13
* Updated PHP doc for several methods, classes and hooks.

2021-12-03 - Version 2.1.12
* Added new embed option in plugin settings that allows the setup of a maximum player height in the entire website (useful for social network formats, like 9x16 or 1x1).

2021-11-30 - Version 2.1.11
* Solved bug in Vimeotheque "Add new" page that generated error when trying to import another video after the previous video was successfully imported.
* Updated Vimeo API resource implementations to flag if a resource is enabled for importers.

2021-11-02 - Version 2.1.10
* Added class "video-thumbnail" on the image for lazy loaded videos;
* Implemented JS functionality for centering the image for lazy loaded videos when the image size ratio isn't the same as the video size ratio;
* Implemented CSS functionality for centering lazy loaded images;
* Added new playlist theme called "Simple";
* Added new playlist theme called "Listy";
* Implemented filters in classic widget to allow playlist themes to inject additional options.

2021-10-13 - Version 2.1.8
* Added class 'no-lazy' to images to prevent W3 Total Cache from breaking the display in video playlists;
* Solved display bug for videos in portrait mode that were lazy-loaded;
* Solved bug that retrieved the smallest video image instead of the full-size image when the featured image wasn't set and lazy loading was on.

2021-09-21 - Version 2.1.7
* Implemented a new filter in Video Position block to allow extra parameters to be set on the iframe URL.

2021-09-20 - Version 2.1.6
* Solved a bug in single video import (the Add new page in Vimeotheque) that issued a WP Block Editor error after importing;
* Single video import (the Add new page in Vimeotheque) will import videos having the post status set in the plugin Settings option under "Import Options" as opposed to being set up by default to "Draft".

2021-07-29 - Version 2.1.5
* Added detection for duplicate images;
* Added options toggles in Vimeotheque Settings page for easier display of dependent options;
* Solved a WordPress 5.8 Widgets screen error in Vimeotheque blocks.
* Solved various (non-critical) bugs.

2021-07-26 - Version 2.1.4
* Solved bug that prevented the "Screen Options" and "Help" admin tabs from displaying into the website admin;
* Removed unnecessary CSS rules from the bootstrap.css file used for displaying the playlist block and video importer grid columns and renamed the file from bootstrap.min.css to bootstrap.css.

2021-07-14 - Version 2.1.3
* Added new filter "vimeotheque\classic_editor\show_shortcode_meta_box" that allows disabling of the shortcode metabox when editing posts with the "Classic editor";
* Solved block editor error "Array to string conversion" caused by wrong parameter type in Vimeotheque playlist block.

2021-07-02 - Version 2.1.2
* Added new filter "vimeotheque\duplicate_posts_found" which allows duplicate video posts (functionality will be made available into a future free add-on).

2021-06-08 - Version 2.1.1
* Solved a Block Editor error that caused the Vimeotheque Playlist Block to crash when using the playlist block with an option to change the videos order;
* Removed deprecated jQuery functions that caused jQueryMigrate messages in console;
* Improved detection of variables when saving options in WP Admin;
* Solved a bug in Vimeotheque Playlist script that prevented the videos to autoplay one after the other when option to loop the playlist was on;
* Prevented lazy-loading in Vimeotheque Playlists (not needed and counterintuitive).

2021-05-24 - Version 2.1
* Added new option in plugin settings "Embed options" for option "Display video" to embed video in video posts in place of the featured image;
* Added new option in plugin settings "Embed options" to lazy load videos;
* Added new option in plugin settings "Embed options" to set the play icon color for lazy loaded videos;
* Added new individual video post option in Classic editor under "Display video" to embed video in place of the featured image;
* Added new individual video post option in Classic editor to lazy load video;
* Added new individual video post option in Block editor under "Embedding options" to replace the featured image with the video embed;
* Added new individual video post option in Block editor under "Embedding options" to lazy load video;
* Solved a rare bug that caused a "TypeError" in some cases (Vimeotheque\Front_End::skipped_autoembed() must be an instance of WP_Post, instance of stdClass given);
* Solved a bug in playlist theme "Default" that wasn't switching the class "active-video" between items when loop option was on.

2021-04-29 - Version 2.0.21
* Solved a bug in Video Position Block that cause post saving error/notice when editing a video post managed by Vimeotheque;
* Changed Video Position Block options "Video start time" and "Volume" to range controls;
* Added new option in Video Position Block for video embed background transparency;
* Added new option for videos edited using the Classic Editor to set the video embed background transparency;
* Increased Vimeotheque minimum WordPress version requirement to WordPress 5.3 (for support of object meta type in the REST API);
* Made video background transparency a global option in Vimeotheque Settings, under tab "Embed options";
* Solved a bug in Video Player script implemented by Vimeotheque which caused the player to ignore the embed volume option.

2021-04-21 - Version 2.0.20
* Solved a bug in Playlist shortcode and Playlist block that prevented manually selected "vimeo-video" posts from being displayed into the playlist while option "Video post is public" was checked in plugin settings;
* Solved a bug in Playlist block that caused the block to crash when selecting videos imported as regular posts.

2021-04-19 - Version 2.0.19
* Solved a bug in playlist theme "Default" that prevented clicking on the read more link when showing the excerpts into the playlist.

2021-04-16 - Version 2.0.18
* Solved a bug that issued error "Call to a member function get_page() on null" when Jetpack installed.

2021-04-12 - Version 2.0.17
* Added option for muted video in Classic editor;
* Added option for muted video in Video Position block;
* Added option for background mode in Classic editor;
* Added option for background mode in Video Position block;
* Added options dependency in Classic editor which hides options that don't apply when certain options are selected (i.e. background mode disables a number of options).

2021-04-12 - Version 2.0.17-alpha.2
* Added option for Classic editor to set the video start time when editing a video;
* Added option for Block editor to set the video start time when editing a video.

2021-04-06 - Version 2.0.17-alpha.1
* Order showcases by default by "modified_time";
* Order user uploads feed by default by "date".

2021-03-29 - Version 2.0.16
* Solved an issue with importers that were prevented from using the default sorting value;
* Solved a rare bug that caused errors when checking for duplicates and the feed returned from the Vimeo API was empty.

2021-03-08 - Version 2.0.15
* Created a new option in Block Editor for playlist theme "Default" to display video thumbnails using the original size ratio (thumbnails in list might have different size) or have them displayed with the same size (thumbnails in list might have black bars);
* Created a new option in Classic Editor shortcode visual interface for theme "Default" to display video thumbnails size ratio in original size or the same size for all thumbnails.

2021-03-02 - Version 2.0.14
* Video player adds class "loaded" on the video container once the video is loaded;
* Modified video player display to remove the black background and loader image after the video has loaded;
* Improved processing of tabs in plugin Settings.

2021-02-18 - Version 2.0.13
* Solved a bug in Video Playlist Widget that caused the widget to display videos from all categories even if a category was selected from the widget options.

2021-02-09 - Version 2.0.12
* Added date limit for showcase and channel;
* Made image preloader in playlist themes to use the 640px wide image version for videos.

2021-01-19 - Version 2.0.11
* Solved a bug that caused the Video Playlist Block to crash when custom post type "vimeo-video" had no categories set up;
* Added "empty results" message to Video Playlist Block modal window if there are no categories set up for the plugin's custom post type;
* Improved display of options for Video Playlist Block theme "Default".
  2021-01-14 - Version 2.0.10
* Solved a bug that prevented the "Add new" plugin admin page from being displayed in some cases (i.e. when using WooCommerce without the Classic editor plugin).

2020-12-31 - Version 2.0.9
* Solved a bug in single video embed block that was causing the options for "Loop video" and "Autoplay video" to be always on.

2020-12-23 - Version 2.0.8
* Solved a bug in block "Video position" which caused the player color to be loaded incorrectly when loading the default color set in plugin Settings under Embed options;
* Improved video position block for Block editor to allow additional parameters to be set;
* Added new parameter to filter "vimeotheque\player\embed-parameters" which passes any manually set embed options;
* Added new action "vimeotheque\automatic_embed_in_content" which is triggered when Vimeotheque embeds videos into the post content automatically (normally, when the Classic editor is used instead of the Block editor);
* Added new action "vimeotheque\editor\classic-editor-options-output" which is triggered when Vimeotheque displays the embedding options in post edit screen in Classic editor;
* Introduced actions and filters that allow third party plugins to add new block editor options to video position block.

2020-12-17 - Version 2.0.7
* Added filter "vimeotheque\player\embed-parameters" that allows extra parameters to be added to the video embed iframe URL;
* Updated translation file for Romanian.

2020-11-20 - Version 2.0.6
* Created new option for playlist block to display post excerpts in playlists for theme Default;
* Created new option for playlist block to allow various posts ordering options;
* Created new option for playlist widget to display post excerpts in playlists when using theme Default;
* Created new option for playlist shortcode in Classic editor to display post excerpts when using theme Default;
* Created new option for playlist shortcode to allow various posts ordering options;
* Introduced support for AMP plugin.

2020-11-18 - Version 2.0.5
* Solved occasional single video import error caused by conflicts with third party plugins;
* Introduced player embed option to prevent tracking users, including all cookies and stats;
* Show manually selected videos in playlist shortcode into the exact order that they were selected;
* Preserve videos order in playlist block same as the order they were selected;
* Hide video position block that is introduced automatically into the block editor for Vimeotheque video posts if automatic embedding is disabled by filter.

2020-11-03 - Version 2.0.4
* Stop video player script in case of player error to avoid JavaScript errors in page;
* Re-initialize video playlist script in case the player script returned an error;
* Compatibility with WP plugin "Complianz – GDPR/CCPA Cookie Consent" by "Really Simple Plugins".

2020-10-30 - Version 2.0.3
* Solved a bug in Video Position block that disregarded the option to embed videos in archive pages and always embedded them;
* Updated all Vimeotheque hooks PHPDoc comments;
* Introduced actions and filters to OAuth plugin settings instructions;
* Exposed REST_Api object for new endpoints registrations;
* Introduced Vimeo API request method.

2020-10-07 - Version 2.0.2
* Introduced add-ons management that allow installation of add-ons for various purposes;
* Added option for playlist block to set alignment;
* Optimized resizing for playlist block theme Default;
* Added option for video position block to set alignment;
* Added option for single video embed to set alignment;
* Added option to display manual bulk imports by the order set on Vimeo (applies only for showcase, channel, portfolio, user uploads and folder);
* New plugin Settings option for embed alignment;
* Allow post registration without a valid taxonomy;
* Updated block editor playlist and video blocks to hide the categories select box if no taxonomy is attached to the post type.

2020-09-14 - Version 2.0.1
* Solved a bug that wasn't hiding the video if video was published as block element and filter to prevent the video embed was on;
* Implemented filters "vimeotheque\admin\notice\vimeo_api_notice" and "vimeotheque\admin\notice\review_notice" that can be used to hide plugin notices.

2020-09-14 - Version 2.0
* Initial release of version 2.0.
