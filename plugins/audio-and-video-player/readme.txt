=== CP Media Player - Audio Player and Video Player ===
Contributors: codepeople
Donate link: https://cpmediaplayer.dwbooster.com
Tags: html5,video player,audio player,music player,mp4,m4a,m4v,mp3,ogg,webm,captions,subtitles,websrt,iphone,ipad,android,paypal,media,skin,commerce,ecommerce,image,images,admin,Post,posts,page,shortcode,plugin,Google,youtube
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

CP Media Player - Audio and Video Player supported by major browsers, such as IE, Firefox, Opera, Safari, Chrome, and mobile devices: iPhone, iPad, Android (tablets and mobiles).

== Description ==

CP Media Player - Audio and Video Player features:

♪ Publish Audio and Video players anywhere
♪ Support for audio and video files: MP4, OGG, WebM, MP3, M4A, WAV
♪ Support WebSRT subtitle files
♪ Allow playlist
♪ Allow downloading
♪ Supported most popular browsers: Edge, Firefox, Chrome, Safari, Opera, Brave, IE...
♪ Supported by mobile devices: iPhone, iPad, and Android devices


**CP Media Player - Audio and Video Player** allows playing multiple file formats: MP4, OGG, WebM, MP3, WAV, and loading WebSRT subtitle files. **CP Media Player - Audio and Video Player** is based on MediaElement.js, supporting all browsers that implement the HTML5 standard.

> To make the players responsive, essential in mobile devices, enter the player's width in percentage, Ex. 100%

**Other Features**

* Allows publishing audio and video players anywhere(posts, pages, or directly on template files).
* Support popular audio and video files: MP4, M4A, OGG, WebM, MP3, WAV,  and WebSRT subtitle files.
* Includes **several skins** with the audio and video player.
* Supports most browsers on the web: Edge, Firefox, Chrome, Safari, Opera, Brave, IE, etc. As well as mobile devices such as iPhone, iPad, and Android.

> The plugin allows you to associate directories to the players to generate playlists with the contained media files (use "/" to load all media files in the "Uploads" directory and subdirectories).

**Premium Features**

[Premium features](https://cpmediaplayer.dwbooster.com/download) are available in the pro version to extend the capabilities of the plugin:

* Allows [protecting the audio files](https://cpmediaplayer.dwbooster.com/documentation#protecting-audio-files). If FFmpeg is installed on the server, the plugin allows you to protect the audio files by embedding a watermark audio layer and generating truncated copies of files for demos.
* Allows [selling files](https://cpmediaplayer.dwbooster.com/documentation#paypal-integration) from the player. It uses PayPal as the payment gateway. Payments are SCA ready (Strong Customer Authentication), compatible with the new Payment services (PSD 2) - Directive (EU)
* Includes sales reports.

The plugin includes the integration with the following editors:

* Gutenberg Editor.
* Classic WordPress Editor.
* Elementor.

For other editors, insert the player's shortcodes into general-purpose blocks orm modules, like text or HTML.

[youtube https://youtu.be/YJSkEdkDJM8]

**Demo of Premium Version of Plugin**

[https://demos.dwbooster.com/audio-and-video/wp-login.php](https://demos.dwbooster.com/audio-and-video/wp-login.php "Click to access the Administration Area demo")

[https://demos.dwbooster.com/audio-and-video/](https://demos.dwbooster.com/audio-and-video/ "Click to access the Public Page")

Additional details about the "CP Media Player - Audio and Video Player" plugin by visiting its website:

[https://cpmediaplayer.dwbooster.com](https://cpmediaplayer.dwbooster.com "Audio and Video Player")

== Installation ==

**To install CP Media Player - Audio and Video Player, follow the steps below:**

1. Download the .zip file with the plugins' code to your computer.
2. Go to the Plugins section on your WordPress.
3. Press the "Add New" button at the top of the Plugins section.
4. Press the "Upload Plugin" button and selects the zipped file downloaded in the first step.
5. Finally, install and activate the plugin.


== Using the Music and Video Player ==

**Generating the players directly on the pages and posts**

**Audio players**

[youtube https://youtu.be/YJSkEdkDJM8]

**Video Player**

[youtube https://youtu.be/QG5gGBnVqB0]

It is possible to generate the player by coding entering the player and items shortcodes into the page's content:

	[cpm-player skin="ball-skin" width="100%" playlist="true" type="audio"]
	[cpm-item file="http://www.wp.local/wp-content/uploads/2018/11/1.mp3"]Audio 1[/cpm-item]
	[cpm-item file="http://www.wp.local/wp-content/uploads/2018/11/2.mp3"]Audio 2[/cpm-item]
	[cpm-item file="http://www.wp.local/wp-content/uploads/2018/11/3.mp3"]Audio 3[/cpm-item]
	[/cpm-player]

To generate the player loading the audio files from the "/wp-content/uploads/2020" directory:

	[cpm-player skin="ball-skin" width="100%" playlist="true" type="audio" dir="2020" /]

The player can load the audio files indicating the directory only. It allows you to add new files to the directory or delete the existing ones, and the player will update the playlist dynamically.

[youtube https://youtu.be/O-IAv4Ij-_0]

The complete list of attributes supported by the shortcodes is available on the [documentation page of the plugin](http://cpmediaplayer.dwbooster.com/documentation#cpm-player-attributes "The attributes supported by the shortcodes").

**Generating the audio and video players from the players gallery**

> To make the players responsive, essential in mobile devices, enter the player's width in percentage, Ex. 100%

Configure the players from the gallery allows you to insert the same player on multiple websites' pages or posts.

[youtube https://youtu.be/WS449LCClA8]

The detailed description of the players' settings is available in the following link: [Creating the players through the players' gallery](http://cpmediaplayer.dwbooster.com/documentation#creating-players-through-players-gallery "Creating the players through the players' gallery")

The [premium version of **CP Media Player - Audio and Video Player**](https://cpmediaplayer.dwbooster.com/download) allows you to sell files from the players. [Configuring the integration with PayPal](http://cpmediaplayer.dwbooster.com/documentation#paypal-integration "Configuring the integration with PayPal").

**Other recommended plugins**

* [Music Player for WooCommerce](https://wordpress.org/plugins/music-player-for-woocommerce/ "Music Player for WooCommerce")
* [Music Player for Easy Digital Downloads](https://wordpress.org/plugins/music-player-for-easy-digital-downloads/ "Music Player for Easy Digital Downloads")

== Frequently Asked Questions ==

= Q: How I know about the new skins for CP Media Player - Audio Player and Video Player? =

A: The skins list in the settings page of the plugin is updated dynamically.

= Q: Why can I enter several audio files per item? =

A: The browsers support different formats of audio and video. So, you can enter an audio file supported by every browser. Or you can enter all disposable files format per item to allow the browsers to play the audio format they support.

= Q: How to enter multiple subtitle files, one per language? =

A: Press the "Add another one" button in the item settings, and enter the data related to the new subtitles, the file, and language.

= Q: Can I sell all the album files? =

A: You can play some of the audio files through the media player, but selling the complete album by selecting a zip file for selling with every album file.

= Q: What audio formats does my browser support? =

A: The audio formats supported by browsers are available on the following link:
[http://www.w3schools.com/html/html5_audio.asp](http://www.w3schools.com/html/html5_audio.asp)

= Q: What video formats does my browser support? =

A: The video formats supported by browsers are available on the following link:
[http://www.w3schools.com/html/html5_video.asp](http://www.w3schools.com/html/html5_video.asp)

== Screenshots ==

1. Audio Player and Video Player
2. Create Player
3. Inserting the Player using the Gutenberg editor
4. Inserting the Player using the Elementor page builder
5. Inserting the Player using the classic WordPress editor
6. Inserting a New Audio or Video Player

== Changelog ==

= 1.2.0 =

* Improves the plugin security. Thanks to the security researcher Steven Julian, and Patchstack team.
* Fixes an issue in the download files link.

= 1.1.3 =

* Removes deprecated jQuery methods.
* Modifies the integration with payment gateways (Professional version).

= 1.1.2 =

* Fixes an issue with controls covering caption.

= 1.1.1 =

* Implements some minor changes in the iframe mode.

= 1.1.0 =

* Implements the iframe attribute in the players' shortcode to isolate them and prevent conflicts with third-party plugins.

= 1.0.73 =

* Modifies the banner module.

= 1.0.72 =

* Improved player editing interface (especially on mobile).

= 1.0.71 =

* Includes a duplicate option in the players' gallery to create new players based on the existing ones.

= 1.0.70 =

* Modifies the plugin settings.
* Fixes an issue hiding the player controls in fullscreen mode.

= 1.0.69 =

* Applies max-width 100% to the playlists of the players.
* Modifies the module that identifies locally hosted media files.

= 1.0.68 =

* Modifies the settings page.

= 1.0.67 =

* Fixes an issue resizing playlists.

= 1.0.66 =

* Modifies the integration with Elementor to ensure compatibility with the latest version of Elementor.

= 1.0.65 =

* Implements a new feature to include a download link in the playlist items.

= 1.0.64 =

* Improves the plugin code and security.

= 1.0.63 =

* Modifies functions deprecated in the latest Elementor update.

= 1.0.62 =

* Fixes an issue with the Elementor integration.

= 1.0.61 =

* If no skin is selected, it loads the default audio and video players on mobiles devices.

= 1.0.60 =

* Modifies the Elementor widget.

= 1.0.59 =

* Modifies the Gutenberg editor integration.
* Includes the play-all feature to allow playing all players on the web page.
* Fixes an issue with the loop feature.

= 1.0.58 =

* Modifies the Gutenberg editor integration.
* Modifies the Elementor pages builder integration.

= 1.0.57 =

* Supports the class attribute in the players' shortcode. The new attribute allows you to assign custom class names to the players' containers.

= 1.0.56 =

* Removes the protocol from the audio and video URLs to avoid issues with protected websites.

= 1.0.55 =

* Modifies the plugin's settings.
* Includes a new feature in the commercial version of the plugin to protect the audio files with a watermark audio layer.

= 1.0.54 =

* Accepts the new attribute shuffle in the player's shortcode for playing the playlist items randomly. The new option is available in the Gutenberg block and Elementor integration.

= 1.0.53 =

* Includes new options in the Gutenberg editor.

= 1.0.52 =
= 1.0.51 =

* Improves the integration with the Gutenberg editor.

= 1.0.50 =

* Fixes some warnings.
* Implements a new default skin.
* Improves the admin section.

= 1.0.49 =

* Improves the Gutenberg blocks.

= 1.0.48 =

* Distribute new skins with the free version of the plugin.
* Improves the player's edition.

= 1.0.47 =

* Improves the accessibility.
* Modifies the module that identifies the media resources.

= 1.0.46 =

* Includes the dir attribute in the player's shortcode to generate the playlist from the media files into the directory and its subdirectories.

= 1.0.45 =

* Includes support for HTTP Live Streaming (also known as HLS) protocol (.m3u8).

= 1.0.44 =

* Improves the user's experience by including some video tutorials directly in the plugin's settings.

= 1.0.43 =

* Modifies the settings for the music players.

= 1.0.42 =

* Includes support for vimeo.

= 1.0.41 =

* Modifies the CSS rules to fix some conflicts with the styles defined on third party themes.
* Improves the registration process (Commercial version of the plugin).

= 1.0.40 =

* Fixes an encoding issue in some ampersand symbols on generated URLs.

= 1.0.39 =

* Adapts the plugin's blocks to the latest version of Gutenberg editor.

= 1.0.38 =

* Implements the random playing feature.

= 1.0.37 =

* Improves the appearance of players on mobile devices.

= 1.0.36 =

* Fixes a javascript issue.
* Modifies the access to the demos.

= 1.0.35 =

* Includes some additional validations.

= 1.0.34 =

* Modifications for accepting .mov video files.

= 1.0.33 =

* Includes some tips in the player's interface.

= 1.0.32 =

* Increases the plugin's security, and fixes some minor errors.

= 1.0.31 =

* Modifies the plugin to allow the play all feature in iOS.

= 1.0.30 =

* Includes the internationalization functions of WordPress.

= 1.0.29 =

* Fixes an issue with some versions of Safari browser.

= 1.0.28 =

* Modifies the blocks for the Gutenberg editor,  preparing the plugin for WordPress 5.1

= 1.0.27 =

* Implements specific widgets for the integration with the Elementor page builder.

= 1.0.26 =

* Creates previews of the music players in the Gutenberg editor.

= 1.0.25 =

* Fixes an issue between the Promote Banner and the official distribution of WP5.0

= 1.0.24 =

* Modifies the shortcode inserted from the "Insert from gallery" icon.

= 1.0.23 =

* Modifies the creation and insertion of audio and video players in the pages and posts.
* Includes new shortcodes.
* For the free version of the plugin allows the creation of multiple players.

= 1.0.22 =

* Fixes a conflict with the latest update of the Gutenberg editor.

= 1.0.21 =

* Fixes a conflict with the latest update of the Gutenberg editor.

= 1.0.20 =

* Fixes a conflict with the latest update of the Gutenberg editor.
* Fixes a conflict with the "Speed Booster Pack" plugin.

= 1.0.19 =

* Removed the CrossOrigin attribute to prevent conflicts with some servers.

= 1.0.18 =

* Modifies the integration with the Gutenberg editor (the editor of the next version of WordPress) to include the  new objects and methods of Gutenberg.

= 1.0.17 =

* Solves an issue in the generation of the pop-up window that inserts the player's shortcode in the pages' contents.

= 1.0.16 =

* Modifies the administration interface.

= 1.0.15 =

* Modifies the activation/deactivation process.
* Includes additional details to allow the users solve common issues.

= 1.0.14 =

* Removes and optimize some queries to increase the plugin's performance.
* Fixes an issue loading the skins' thumbnails on websites protected with SSL.

= 1.0.13 =

* Selects a media type by default (audio or video) in the public website, to prevent possible issues.

= 1.0.12 =

* Fixes some compatibility issues with the new versions of mediaelement.js
* Removes some unnecessary resources.

= 1.0.11 =

* Includes the crossOrigin attribute in the audio and video tags for playing files in external domains.

= 1.0.10 =

* Removes the copy of "MediaElement" player distributed with the plugin to use the version distributed with WordPress.

= 1.0.9 =

* Improves the integration with the Gutenberg Editor.

= 1.0.8 =

* Allows the integration with the Gutenberg Editor, the editor that will be distributed with the next versions of WordPress.

= 1.0.7 =

* Fixes an issue with the next and previous buttons when the playlist is hidden.
* Hides the next and previous buttons if the playlist has less than two items.
* Fixes other issues in the administration section.

= 1.0.6 =

* Fixes an error caused by files with apostrophes in their names.

= 1.0.5 =

* Adds a title to all buttons and controls.
* Modifies the skins' styles.

= 1.0.4 =

* Modifies the insertion of the styles.

= 1.0.3 =

* Allows to insert the shortcode of the player in a Text Widget to display the players in the website's sidebar.
* Escapes all attributes and insertion and update queries to prevent possible vulnerabilities.

= 1.0.2 =

* Displays the shortcodes in the players settings, to allow copy the shortcode, and paste it in the content editor of pages.

= 1.0.1 =

* Improves the plugin documentation.
* Allows to associate subtitle files to the player.
* Allows to associate playlists to the player.
* Allows change the order of the playlist items, from the player's edition.
* Corrects an issue with multiple players in the same page or post.
* Corrects an issue playing videos from YouTube.
* Allows to start playing automatically the next item of playlist.
* Corrects an issue with the loop attribute if the playlist was disabled.
* Corrects an issue with the covers associated to the playlist's items.
* Corrects an issue with the time indicator for long videos.
* Fixes an issue playing videos in fullscreen in Firefox browser.
* Separates the skins from the kernel of player.
* Corrects the controls depending of size of the media player.
* Corrects an issue with the m4a files extension.

= 1.0 =

* First version released.