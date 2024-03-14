=== Voltax Video Player ===

Contributors: minutemedia
Tags: video, videos, voltax, vms, minute media
Stable tag: 1.6.4
Requires at least: 4.8
Tested up to: 6.4
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily embed videos and playlists with the Minute Media Online Video Platform.

== Description ==
###What is the Voltax WordPress Plugin?
The Voltax Video Player plugin allows you to embed videos and playlists from an account on Minute Media's Online Video Platform (OVP) into your WordPress site.

###What is Minute Media's OVP?
A Publish-as-a-Service platform that powers **Publisher Peace of Mind**

Voltax Video is a comprehensive online video platform that provides publishers with a suite of video technology tools designed to grow engagement, content, audience and monetization. Voltax Video delivers innovative video experiences with state-of-the-art video technology, access to an extensive premium content library of more than 400,000 videos including premium sports & culture content by Minute Media's owned content brands and a powerful revenue engine including direct and programmatic advertising and other commercial revenue solutions.

##Voltax Video's Cutting Edge Features
* Built specifically for innovative publishers 
* Proprietary HTML5 video player that is fast, lightweight & flexible
* Customizable player controls, advertising & design settings
* Access to thousands of Minute Media originals, including athlete-led videos, fun facts & trivia, behind-the-scenes footage, gaming tips & more
* Utilize publishing partner content across a multitude of genres: sports, lifestyle, culture, news, finance, health, weather & more
* Save OVP Costs - no licensing, storage, bandwidth or serving fees
* Boost yield & fill rate with integrated ad support, leverage high quality global direct demand, and utilize Minute Media header bidding demand stack
* Pair articles with videos in real-time through our semantic recommendation engine
* Enable maximum distribution across partner sites via video syndication tools
* Utilize video upload, import, tags & playlist management
* Migrate existing library with no storage fees
* Access key data via real-time analytics dashboards and revise and improve operations with our dedicated BI Team

##Current Voltax Users
To help partners quickly and efficiently update their Voltax WordPress plugin, the team has added it to the WordPress repository. Simply update the plugin in your WordPress dashboard, and continue engaging your audience with video. 

##Interested in Voltax Video But Don't Know Where to Start?
Please reach out to sales@minutemedia.com to learn more.

== Frequently Asked Questions ==

= Do you need an account? (free vs paid) =

Yes, you are required to set up a Voltax account with Minute Media in order to use the Voltax WordPress plugin. We currently do not offer a free or “freemium” version of the plugin. For help, please contact sales@minutemedia.com

= How do I attach a playlist to a single embedded video? =

* Select a video you’d like to embed onto your page
* Using the drop-down menu, choose which playlist you’d like to attach to your embedded video.
* Click the Add button on the bottom right.
* Adding a playlist at the end of a video will ensure content keeps playing even when the user finishes watching the initial video 

= How do I attach a player to a single embedded video? =

* Select a video you’d like to embed onto your page
* Using the drop-down menu, choose which player you’d like to attach to your embedded video.
* Click the Add button on the bottom right.
* Adding a playlist at the end of a video will ensure content keeps playing even when the user finishes watching the initial video

Videos and playlists can be embedded using the integrated embed features provided for the classic WordPress editor, or using the **[mm-video]** shortcode with the following attributes:

- **id**: (required) the content id of the video or playlist you wish to embed
- **image**: full URL to a preview image for the content
- **type**: embed type (video / playlist)

= How do I upload a video? =

* First, ensure the “Enable Video Upload” checkbox is selected in the Voltax Video plugin Settings
* Click on the “Add Voltax Video” button 
* On the right side of the page, click on the upload button.
* In order to properly upload and save the video, you will need to give the video a title and fill in the creator name.
* Once the status changes to “ready” in Voltax, the video is available (processing might take up to 10 minutes, depending on the video size).

== Installation ==

1.  Download and activate the plugin through the *Plugins* menu in WordPress.
2.  Configure the plugin under *Settings > Voltax Video* using the credentials provided by Minute Media.
3.  Use the Voltax buttons in the classic editor to embed videos and playlists into your posts (see screenshots)
4.  Enable "featured videos" and use the Featured Videos meta box to add them to your posts' metadata. To view featured videos you also need to integrate the `MinuteMedia\Ovp\Player` class into your theme.
5.  Enable "video upload" to upload videos to your Voltax account.

== Screenshots ==

1. Add your client authentication params into the Settings page. If it all works, you should see a new access token with a green checkmark!
2. The plugin supports the classic editor with “Add Voltax Video“ and “Add Voltax Playlist“ buttons.
3. After clicking on “Add Voltax Video”, users have the option to upload a video from their local device or select a video from their library. To upload a video from your local device, first make sure the checkbox is selected in “Settings” and then “Voltax Video”. Then click on the “Add Voltax Video” button, and click the “Upload” button in the top right corner.  
4. To upload the video file, ensure you choose one of the following formats: .mp4 or .mov. After selecting a file to upload, make sure to fill out the metadata for the video. It is mandatory to fill out the “Video Title” and “Creator Name” fields. All other fields are optional. By default, “Opt out of external publishing services” will be selected. When selected, videos won’t be added to Voltax's shared library.
5. To embed a new Minute Media OVP asset, search for the video/playlist by typing in the search bar and then clicking on the video you require.
6. After clicking on the video you wish to embed, you can select a player and/or playlist from your Minute Media OVP account to attach to that specific video. **Notes:** 
The default player attached to the video is configured via the plugin settings screen (see step number 3 within the installation guide)
By default, no Playlist will be attached to your video (indicated by the “None” option”)
7. When embedding a playlist, you can select a player to attach to that specific playlist. **Note:** The default player attached to the video is configured via the plugin settings screen (see step number 3 within the installation guide).
8. If the “Featured Video” option has been enabled in the Plugin Settings page, you can add a Featured Video to your post via the editor. The featured video object is saved in post meta with the key, mm-featured-video-data.  Additional development is required to integrate featured videos into your site’s theme for display on the front-end of your site.
9. If the "Video Upload" option has been enabled in the Plugin Settings page, you can upload a video to your Voltax account.


== Changelog ==

The Changelog exists in the file CHANGELOG.md in the containing git directory as of the plugin's public release.
