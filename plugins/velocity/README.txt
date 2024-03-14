=== Velocity - Video Lazy Loading for YouTube, Twitch and Vimeo ===
Contributors: dcooney
Donate link: https://connekthq.com/donate/
Tags: YouTube, Vimeo, Twitch, SoundCloud, performance, lazy load, lazy loading, lazy load video, audio, speed, demand, on-demand, responsive, mobile, 
Requires at least: 4.0
Tested up to: 5.3.2
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Improve website performance by lazy loading and customizing your YouTube, Vimeo, Twitch and SoundCloud media embeds.

== Description ==

Velocity is an alternative loading method to the standard to YouTube, Vimeo, Twitch and Soundcloud iframe embeds. 

With Velocity you will decrease the loading time and increase overall performance of your website by lazy loading media on-demand instead of on initial page load.

To add Velocity to your site, simply create a Velocity shortcode by selecting a preview image and media type using the intuitive shortcode builder then add the generated snippet to your page.

**[Get More Information](https://connekthq.com/plugins/velocity/)**

= Shortcode Parameters =
 
*   **type** - Choose a media type [youtube, vimeo, twitch, soundcloud].
*   **id** - The ID of the media item.
*   **options** - Add optional styling and display parameters for the embedded media - e.g. rel=0&controls=0&showinfo=0.
*   **playlist** - Is this a Soundcloud playlist [true/false].
*   **img** - The path to the preview image.
*   **alt** - The alternative text to be attached to the preview image.
*   **color** - Play button arrow color.
*   **bkg_color** - Play button background color.

***

= Example Shortcode =

    [velocity type="youtube" id="239793212" img="http://yourwebsite.com/wp-content/uploads/2016/01/image-1263626715.jpg" alt="Play Video"]

***


= Tested Browsers =

* Firefox (Mac, PC)
* Chrome (Mac, PC, iOS, Android)
* Safari (Mac, iOS)
* IE10+
* Android (Native)
* BB10 (Native)

***

= Website =
https://connekthq.com/plugins/velocity/


== Frequently Asked Questions ==


= What are the steps to getting Velocity integrated on my website =

1. Create your Velocity shortcode.
2. Add the shortcode to your page, by adding it through the content editor or placing it directly within one of your template files.
3. Load a page with Velocity in place, click the preview image and load your media. 


== Installation ==

How to install Velocity.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Velocity'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `velocity.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `velocity.zip`
2. Extract the `velocity` directory to your computer
3. Upload the `velocity` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Screenshots ==

1. Settings.
2. Velocity shortcode builder.
3. Add Velocity (page/post edit screen).
4. Velocity lightbox (page/post edit screen).


== Changelog ==

= 1.2.1 - January 4, 2020 = 
* UPDATE - Update admin CSS styling for issues with new WordPress 5.3 accessibility styles. 
* UPDATE - Improved meta data classes.


= 1.2.0 - July 23, 2019 = 
* NEW - Added Twitch support. 
* NEW - Added responsive styling for play button.
* NEW - Added focus state for accessibility.
* NEW - Add SEO Metadata to YouTube, Vimeo and Twitch embeds. YouTube and Twitch metadata is still a work in progress and will be evolving. 
* FIX - Removed issue with `.clearfix` class causing issue with page builders


= 1.1.1 - March 17, 2017 =
* FIX - Patch for PHP warning regarding $soundcloud_type variable.


= 1.1 - March 14, 2017 =
* Added fade out transition from preview image to media embed.
* Added new `options` shortcode parameter to pass various options to embed video - e.g. autoplay=1&loop=1&title=0
* Added support for Soundcloud playlists - e.g. [velocity type="soundcloud" playlist="true" id="1659224" alt="Play" color="#FFFFFF" bkg_color="#000000"]).
* UI Updates and enhancements.


= 1.0.1 - May 10, 2016 =
* Adding .min js file
* Adding support for loading Velocity media with Ajax


= 1.0 - March 3, 2016 =
* Initial Plugin Release


== Upgrade Notice ==

* None 


