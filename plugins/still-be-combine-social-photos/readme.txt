=== Combine Social Photos | Still BE ===
Contributors: analogstudio
Donate link: https://donate.stripe.com/aEUg2Q0iKgzbf0Q9AE
Tags: Instagram, block, Instagram feed, Instagram photos
Requires at least: 6.0
Tested up to: 6.5
Stable tag: 0.13.6
Requires PHP: 7.4
License: GPL2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides Instagram embedding functionality exclusively for WP Block Editor. Your feeds, other Pro accounts' feeds and posts related to hashtags.


== Description ==

Add blocks where you can embed instagram feed. Provides embedding optimized for Block Editor.
Multiple accounts can be managed at a site.

You can be done **on the block editor for all visual editing**, making it possible to achieve the desired layout **more comfortably, flexibility and speedy**.

You can embed your own feeds, other Pro accounts' feeds and posts related to hashtags.

Getting feeds from other Pro accounts (Business Discovery) or posts related to hashtags requires authentication with the Instagram Graph API.

Data got from Instagram is cached for faster display.
When the cache expires, it is automatically got data in the background and updated when the reacquisition is complete. This ensures that a valid cache is always available.


### Block; Simple Grid

A block of posts placed on a grid.
The following customizations are available

+ Advanced Getting Posts
	- Type of Getting Posts
	- Other User's Username
	- Hashtag
+ Video Option
	- Displaying Video
+ Outline Gap (PC / Tablet / SP)
+ Layout (PC / Tablet / SP)
	- Columns
	- Rows
	- Aspect Ratio
	- Gap
+ Highlight
	- Size
	- Position (Left / Top)
+ Instagram Post Where to Open
+ Post Caption
	- Show / Hidden
	- Lines
+ Post Author
	- Show / Hidden
+ Post Time
	- Show / Hidden
+ Header
	- Show / Hidden
	- Position (Left / Center / Right)
+ Footer
	- Show / Hidden
	- Position (Left / Center / Right)
+ Hover Effect
	- Frosted Glass Effect
	- Tilt Effect
+ Follows Count
	- Show / Hidden
+ Followers Count
	- Show / Hidden
+ Like / Comments Count
	- Show / Hidden


### Block; Simple Slider

A block can slide horizontally.
The following customizations are available

+ Advanced Getting Posts
	- Type of Getting Posts
	- Other User's Username
	- Hashtag
+ Video Option
	- Displaying Video
+ Layout
	- Base Width
	- Min Width
	- Min Columns
	- Columns
	- Rows
	- Aspect Ratio
	- Gap (between columns / rows)
+ Instagram Post Where to Open
+ Post Caption
	- Show / Hidden
	- Lines
	- Position (hover on image / below the image)
+ Post Author
	- Show / Hidden
	- Position (hover on image / below the image)
+ Post Time
	- Show / Hidden
	- Position (hover on image / below the image)
+ Footer
	- Show / Hidden
	- Position (Left / Center / Right)
+ Hover Effect
	- Frosted Glass Effect
	- Tilt Effect
+ Like / Comments Count
	- Show / Hidden
	- Position (hover on image / below the image)
+ Scrolling
	- Duration Time
	- Easing Function (Linear / InOutSine / InOutQuad / InOutCubic / OutBounce / Cubic-bezier)
+ Exclude Navigation Buttons


### Modal Window

Selecting a modal window allows user to view the details of Instagram posts without leaving your website.
You can also put a CTA (Call to Action) within the modal window.


### Link with Instagram Account

Easily link to your Instagram accounts.
Multiple accounts can be managed, and the account to be used can be selected individually when put a block.



== Installation ==

1. Enter "Combine Social Photos" in the plugin search field in your admin screen.
2. Once you find this plugin, click "Install Now" to install.  
   (Alternative) Upload "still-be-combine-social-photos.zip" directly to your Plugins -> Add New in your admin screen.  
   (Alternative) Upload an unzipped "stillbe-image-quality-control" directory under the "/wp-content/plugins" directory.
3. Activate the plugin through the Plugins menu in WordPress.



== Frequently Asked Questions ==

= What is user authentication? =

Instagram data is taken through your account. As such, we need your permission for this plugin to access Instagram on your behalf.

To revoke permissions, please check your [Instagram](https://www.instagram.com/accounts/manage_access/) or [Facebook](https://www.facebook.com/settings/applications/app_details/?app_id=713657633383713) app settings.


= What is the difference between the "Basic Display API" and the "Graph API"? =

The Basic Display API is an API for displaying posts from your account; no connection between your Instagram account and your Facebook page is required. (Authrization is done through your Instagram count)
While it is easy to use, it does not allow you to get detailed information such as profile image, like count, comments count, etc.

The Graph API provides a wide range of information through its API and requires a professional account (business / media-creator account) connected to Instagram and a Facebook page. (Authrization is done through your Facebook account).
You must be set up as a pro account, but you can get a profile image, like count, and comments count, as well as posts from other pro accounts and posts related to hashtags.


= New posts do not appear in the embedded feed. =

Wait until the cache lifetime (default: 3,600 sec.) has elapsed, as posted data is cached for a certain period of time.
If the cache is updated too slowly, shorten the cache time in setting screen.

Also, please enable WP-Cron for cache updating if you have disabled it.
When WP-Cron is disabled, cache updates are run when media cannot be displayed from the Instagram CDN. However, users will be notified that the cache is expired and will be prompted to reload the page.


= Can it be used with Classic Editor? =

No, not supported.
This plugin is for Block Editor only.


= Cannot convert a hashtag. =

Hashtags must be converted to IDs, and this conversion process is limited to 30 times in 7 days.
If you are converting a lot of hashtags, please wait a while and try again later.


= Hashtag posts are not displayed. =

In the display of the latest posts, only posts within the last 24 hours can be get due to Instagram specifications.

However, once it is set up, it can be displayed because it caches data that is more than 24 hours old.
In other words, posts from 24 hours before the time you set the block will be displayed.


= Posted author information for posts related to the hashtag is not displayed. =

This is an Instagram specification.
Posted author information cannot be displayed in posts related to the hashtag.


= Video thumbnails are not displayed except for my own feeds. =

This is an Instagram specification.

In version 0.8 or later, the option to either not show the video or to autoplay is available.


= Not displayed in IE. Is this correct? =

Yes, it is correct.
IE is no longer be supported by Microsoft as of June 15, 2022. We do not guarantee the operation of any browsers other than modern browsers.


= When I change the setting to show navigations outside the slider, the navigations will not be displayed. =

Because they are placed outside of the Simple Slider block, they cannot be displayed if "overflow: hidden;" is set for the block's parent element (e.g., a container element in the content area).
Please check your theme settings, or set a group block outside of the Simple Slider block to provide navigations margin on the left and right.



== Screenshots ==

1. Admin Screen of Manage Instagram Accounts
2. Token Generator Screen
3. Manually Set an Account
4. Block; Simple Grid
5. Block; Simple Slider
6. Grid Layout Samples & Block Settings
7. Slider Samples & Block Settings


== Changelog ==


= 0.13.5 =

2024-03-13

Shortened the description.


= 0.13.4 =

2024-03-13

Checked that it works with WordPress 6.5.

Updated Graph API version to v19.0.

Fixed some bugs.


= 0.13.3 =

2023-11-20

Checked that it works with WordPress 6.4.1.
Use 6.4.1 or higher because cURL errors occur in 6.4. (WordPress core bug)


= 0.13.2 =

2023-09-03

Enabled access tokens' validity check by WP-Cron.


= 0.13.1 =

2023-09-01

Added a function to re-authenticate Graph API access token.


= 0.13.0 =

2023-08-16

Fixed a bug that the responsive setting of the Block; Simple Grid was not reflected.

Checked that it works with WordPress 6.3.


= 0.12.1 =

2023-06-21

Updated Graph API version to v17.0.


= 0.12.0 =

2023-03-26

Added an option to the Simple Grid block, which allows you to choose the position of post information (caption, author, post datetime, and impressions) display.

Updated Graph API version to v16.0.

Checked that it works with WordPress 6.2.


= 0.11.3 =

2022-12-21

Fixed a bug where posts could not be retrieved when using the Basic Display API.


= 0.11.2 =

2022-12-01

Improved stability by fixing a problem that sometimes prevented data from being got from the API.
(When the API response time is slow)

Changed so that the edit screen does not check the cache, but always gets the fresh data.


= 0.11.1 =

2022-11-21

Fixed a problem in which the ability to manually link Graph API did not work correctly.

Fixed some bugs. Improved stability.


= 0.11.0 =

2022-11-11

Checked that it works with WordPress 6.1.

Changed the default value for opening Instagram posts to "Open in a New Tab."

Fixed some bugs. Improved stability.


= 0.10.0 =

2022-10-12

Improved the basic behavior of the modal window.

Added buttons to go directly to the next or previous post when a modal window is opened.

Added the ability to add a CTA to the modal window.

\* Cases occur where repair is required on the block editor screen, but block recovery will repair them without worrying.


= 0.9.1 =

2022-10-09

Dark mode supported.


= 0.9.0 =

2022-10-09

Added option to specify the window in which to open Instagram posts. (Self / New / Modal)
In line with this, the Javascript loading process was reorganized.

Updated Graph API version to v15.0.

Fixed some bugs.


= 0.8.1 =

2022-08-20

Added easing function for slider.

Changed the behavior where the number of posts was not updated until the access token refresh in the Basic Display API so that it is updated once a day.

Added a process to access the latest hashtag posts once a day to prevent the CDN cache from expiring. (Beta version)


= 0.8.0 =

2022-08-03

Added options for displaying videos. You can choose to show or hide thumbnails and autoplay.

Changed from cached data for the recent post with hashtag to keep for the past 24 hours or earlier too.

Fixed some bugs.


= 0.7.1 =

2022-07-26

Removed test code for cache update process triggered by expired images.
Set global variables to URLs appropriate for each site.


= 0.7.0 =

2022-07-26

Added option to display posts related to hashtags.

Added a process to refresh the cache when there are expired images.

Improved behavior when scrolling halfway.


= 0.6.1 =

2022-07-12

Adjusted the behavior of the easing function to be interrupted when scrolling is halfway.


= 0.6.0 =

2022-07-10

Added the following functions;

+ Gap outside of Simple Grid block
+ Scrolling behavior of Simple Slider block (transition time, easing)

Corrected the path of the CSS for the editor of the Simple Grid block.

Updated Facebook Graph API version to v14.0.


= 0.5.0 =

2022-05-23

Fixed a bug that Instagram account linkage was not automatically set on the post screen.
Checked that it works with WordPress 6.0.


= 0.4.0 =

2022-05-08

App review for "Instagram Graph API" approved.
Fixed a bug in the Graph API approval process.

Supported dark mode.


= 0.3.1 =

2022-05-05

Implemented Business Discovery.

= 0.3.0 =

2022-04-28

Added Instagram Graph API to be able to process. (Only get own data has been mounted)
Added more detailed settings to each block.
Changed the data structure of the cache so that other data will not be lost if the key (hash value of the conditions) to store the cache data collides.
Changed read timing of translation functions for JS.
Fixed other minor bugs.

= 0.2.4 =

2022-04-14

Supported Japanese language at the access token generator.

= 0.2.3 =

2022-04-11

Fixed a bug.

= 0.2.2 =

2022-04-11

Added responsive settings to the Block; Simple Grid.

= 0.2.1 =

2022-04-11

Changed all admin screens to load admin Javascript for Full Site Editing.
Changed initialization to call wp_set_script_translations() function so that Javascript translation functions work.

= 0.2.0 =

2022-04-10

App review for "Instagram Basic Display API" approved.

= 0.1.1 =

2022-04-09

Up to WordPress plugin directory

= 0.1.0 =

2022-04-07

Initial release


== 3rd party resources ==

= Font Awesome =

WebSite: https://fontawesome.com/
License: https://fontawesome.com/license/free

