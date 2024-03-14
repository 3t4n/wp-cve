=== Music Player for Easy Digital Downloads ===
Contributors: codepeople
Donate link: http://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads
Tags:Easy Digital Downloads,music player,audio,music,song,player,audio player,media player,mp3,wav,oga,ogg
Requires at least: 3.5.0
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Music Player for Easy Digital Downloads includes the MediaElement.js music player in the pages of the downloads with audio files associated.

== Description ==

Music Player for Easy Digital Downloads features:

- Includes an audio player that supports formats: OGA, MP3, WAV, WMA
- Includes multiple skins for the Music Player
- Supports all most popular web browsers and mobile devices
- Includes a widget to insert a playlist on sidebars
- Includes a block to insert the playlists on pages using Gutenberg
- Includes a widget to insert the playlists on pages using Elementor
- Includes a widget for inserting the playlists on pages with Page Builder by SiteOrigin
- Includes a control for inserting the playlists on pages with BeaverBuilder
- Includes an element for inserting the playlists on pages with Visual Composer
- Includes a module for inserting the playlists on pages with DIVI

Note: for the other editors, insert directly the playlists' shortcodes.

Music Player for Easy Digital Downloads includes the MediaElement.js music player in the pages of the downloads with audio files associated, and in the store's pages, furthermore, the plugin allows selecting between multiple skins.

MediaElement.js is an music player compatible with all major browsers: Internet Explorer, MS Edge, Firefox, Opera, Safari, Chrome and mobile devices: iPhone, iPad, Android. The music player is developed following the html5 standard. The music player supports the following file formats: MP3, WAV, WMA and OGA.

The basic version of the plugin, available for free from the WordPress Directory, has the features needed to include a music player in the pages of the downloads and the store.

**Premium Features**

*	Allows playing the audio files in secure mode to prevent unauthorized downloading of the audio files.
*	Allows to define the percent of the audio file's size to be played in secure mode.
*	Allows to store the generated audio files in Google Drive (when the truncate feature is enabled).

== Installation ==

**To install Music Player for Easy Digital Downloads, follow these steps:**

1. Download and unzip the plugin
2. Upload the entire "edd_music_player" directory to the "/wp-content/plugins/" directory
3. Activate the plugin through the "Plugins" menu in "WordPress"
4. Go to the downloads pages to configure the players.

or Simply install it through the plugins section in WordPress.

== Interface ==

**Global Settings of Music Players**

The global settings are accessible through the menu option: "Settings/Music Player for Easy Digital Downloads".

*   Include music player in all all downloads: checkbox to include the music player in all downloads.
*   Include in: radio button to decide where to display the music player, in pages with a single entry, multiple entries, or both (both cases by default).
*   Include players in cart: checkbox to include the music players on the cart page or not.
*   Player layout: list of available skins for the music player.
*   Preload: to decide if preload the audio files, their metadata, or don't preload nothing at all.
*	Play all: play all players in the page (one after the other).
*	Loop: plays the audio player on the product page in a loop.
*   Player controls: determines the controls to include in the music player.
*   Display the player's title: show/hide the name associated to the downloadable file.
*   Protect the file: checkbox to playback the songs in secure mode (only available in the pro version of the plugin).
*   Percent of audio used for protected playbacks: integer number from 0 to 100, that represents the percent of the size of the original audio file that will be used in the audio file for demo (only available in the pro version of the plugin).
* 	Apply the previous settings to all downloads pages in the website: tick the checkbox to apply the previous settings to all downloads overwriting the downloads' settings.

**Google Analytics Integration**

*	Tracking id: Enter the tracking id in the property settings of Google Analytics account.

**Setting up the Music Players through the downloads' pages**

The Music Players are configured from the downloads pages.

**Settings Interface**

*   Include music player: checkbox to include the music player in the download's page, or not.
*   Include in: radio button to decide where to display the music player, in pages with a single entry, multiple entries, or both (both cases by default).
*   Player layout: list of available skins for the music player.
*   Preload: to decide if preload the audio files, their metadata, or don't preload nothing at all.
*	Play all: play all players in the page (one after the other).
*	Loop: plays the audio player on the product page in a loop.
*   Player controls: determines the controls to include in the music player.
*   Display the player's title: show/hide the name associated to the downloadable file.
*   Protect the file: checkbox to playback the songs in secure mode (only available in the pro version of the plugin).
*   Percent of audio used for protected playbacks: integer number from 0 to 100, that represents the percent of the size of the original audio file that will be used in the audio file for demo (only available in the pro version of the plugin).
*	Select my own demo files: checkbox to use different audio files for demo, than the audio files for selling (only available in the pro version of the plugin).
*	Demo files: section similar to the audio files for selling, but in this case it allows to select different audio files for demo, and their names (only available in the pro version of the plugin).

**How the Pro version of the Music Player for Easy Digital Downloads protect the audio files?**

If the "Protect the file" checkbox was ticked in the download's page, and was entered an integer number through the attribute: "Percent of audio used for protected playbacks", the plugin will create a truncated copy of the audio files for selling (or the audio files for demo) into the "/wp-content/plugins/eddmp" directory, to be used as demo. The sizes of the audio files for demo are a percentage of the sizes of the original files (the integer number entered in the player's settings). So, the users cannot access to the original audio files, from the public pages of website.

**Music Player for Easy Digital Downloads - Playlist Widget**

The widget allows to include a playlist on sidebars, with the downloadable files associated to all downloads with the music player enabled, or for only some of the downloads.

The widget settings:

*	Title: the title of the widget on sidebar.
*	Downloads IDs: enter the ids of downloads to include in the playlist, separated by comma, or the * symbol to include all downloads.
*	Player layout: select the layout of music players (the widget uses only the play/pause control)
*   Preload: to decide if preload the audio files, their metadata, or don't preload nothing at all. This attribute has a global scope, and will modify the default settings.
*	Play all: play all players in the page (one after the other). This attribute has a global scope, and will modify the default settings.
*	Highlight the current download: if the checkbox is ticked, and the user is in the page of a download, and it is included in the playlist, the corresponding item would be highlighted in the playlist.
*	Continue playing after navigate: if the checkbox is ticked, and there is a song playing when navigates, the player will continue playing after loading the webpage, in the same position.

Note: In mobiles devices (and some browsers) where the direct action of user is required for playing audios and videos, the plugin cannot start playing dynamically.


**Music Player for Easy Digital Downloads - `[eddmp-playlist]` shortcode**

The `[eddmp-playlist]` shortcode allows to include a playlist on the pages' contents, with all downloads, or for some of them.

The shortcode attributes are:

*	downloads_ids: define the ids of downloads to include in the playlist, separated by comma, or the * symbol to include all downloads:

	`[eddmp-playlist downloads_ids="*"]`

*	player_style: select the layout of music players (the playlist displays only the play/pause control):

	`[eddmp-playlist downloads_ids="*" player_style="mejs-classic"]`

*	highlight_current_download: if the playlist is included in a download's page, the corresponding item would be highlighted in the playlist:

	`[eddmp-playlist downloads_ids="*" highlight_current_download="1"]`

*	cover: allows to include the featured images in the playlist. The possible values are: 0 or 1, 0 is the value by default:

	`[eddmp-playlist downloads_ids="*" cover="1"]`

*	continue_playing: if there is a song playing when navigates, the player will continue playing after loading the webpage in the same position:

	`[eddmp-playlist downloads_ids="*" continue_playing="1"]`

*	controls: allows to define the controls to be used with the players on playlist. The possible values are: track or all, to include only a play/pause button or all player's controls respectively.

Note: In mobiles devices where the direct action of user is required for playing audios, the plugin cannot start playing dynamically.


**Hooks (actions and filters)**

* eddmp_before_player_shop_page: action called before the players containers in the shop pages.
* eddmp_after_player_shop_page: action called after the players containers in the shop pages.
* eddmp_before_players_download_page: action called before the players containers in the downloads pages.
* eddmp_after_players_download_page: action called after the players containers in the downloads pages.

* eddmp_audio_tag: filter called when the audio tag is generated. The callback function receives three parameters: the audio tag, the download's id, and the file's id;
* eddmp_file_name: filter called when the file's name is included with the player. The callback function receives three parameters: the file's name, the download's id, and the file's id;

* eddmp_widget_audio_tag: filter called when the audio tag is generated as a widget on sidebars. The callback function receives three parameters: the audio tag, the download's id, and the file's id;
* eddmp_widget_file_name: filter called when the file's name is included with the player as a widget on sidebars. The callback function receives three parameters: the file's name, the download's id, and the file's id;

* eddmp_purchased_download: filter called to know if the download was purchased or not. The callback function receives two parameters: false and the download's id.

* eddmp_ffmpeg_time: filter called to determine the duration of truncated copies of the audio files for demos when the FFmpeg application is used to generate them.

**Other recommended plugins**

* [Music Player for WooCommerce](https://wordpress.org/plugins/music-player-for-woocommerce/ "Music Player for WooCommerce")
* [Music Store - WordPress eCommerce](https://wordpress.org/plugins/music-store/ "Music Store - WordPress eCommerce")
* [CP Media Player - Audio Player and Video Player](https://wordpress.org/plugins/audio-and-video-player/ "CP Media Player - Audio Player and Video Player")

== Frequently Asked Questions ==

= Q: Why the audio file is played partially? =

A: If you decide to protect the audio files, the plugin creates a truncated version of the file to be used as demo and prevent that the original file be copied by unauthorized users.

= Q: Why the music player is not loading on page? =

A: Verify that the theme used in your website, includes the function wp_footer(); in the template file "footer.php" or the template file "index.php"

= Q: What can I do if the edd_music_player directory exists and the premium version of plugin cannot be installed? =

A: Go to the plugins section in WordPress, deactivate the free version of Music Player for Easy Digital Downloads, and delete it ( Don't worry, this process don't modify players configured with the free version of the plugin), and finally install and activate the premium version of plugin.

= Q: Can be modified the size of audio files played in secure mode? =

A: In the pro version of the plugin the files for demo are generated dynamically to prevent the access to the original files.

Each time save the data of a download, the files for demo are deleted and generated again, so, you simply should modify the percentage of the audio file to be used for demo in the download's page.

== Screenshots ==
01. Music players in the store's pages
02. Music player in the downloads pages
03. Music player skins
04. Music player settings
05. Playlist widget
06. Inserting the playlist in Gutenberg
07. Inserting the playlist in Elementor
08. Inserting the playlist with Page Builder by SiteOrigin
09. Inserting the playlist BeaverBuilder
10. Inserting the playlist Visual Composer

== Changelog ==

= 1.1.5 =

* Removes deprecated JS code.

= 1.1.4 =

* Modifies the module that identifies local files.

= 1.1.3 =

* Fixes an issue with the player seek bar.
* Fixes a warning message when deleting the demo files to create new ones.

= 1.1.2 =

* Modifies the players' styles.
* Modifies the titles of the playlist items.

= 1.1.1 =
= 1.1.0 =

* Modifies the module that loads the audio files for demos.

= 1.0.81 =

* Modifies the banner module.

= 1.0.80 =

* Allows entering multiple hook names separate by comma symbols through the 'Easy Digital Downloads hook used to display the players in the shop pages' and 'Easy Digital Downloads hook used to display the players on the download pages' attributes in the plugin settings.

= 1.0.79 =

* Includes the loop attribute in the player and global plugin settings.

= 1.0.78 =
= 1.0.77 =

* Modifies the module that identifies locally hosted audio files.

= 1.0.76 =

* Modifies the integration with Elementor to ensure compatibility with the latest version of Elementor.

= 1.0.75 =

* Fixes a minor issue by deleting the files for demos.

= 1.0.74 =

* Modifies the global settings.
* Includes a new hook.

= 1.0.73 =

* Loads the resources locally.

= 1.0.72 =

* Modifies the module that reads the audio files.

= 1.0.71 =

* Improves the plugin feedback.

= 1.0.70 =

* Improves the plugin code.

= 1.0.69 =

* Removes invalid end of files.

= 1.0.68 =

* Modifies the preload behavior to avoid affecting server performance.

= 1.0.67 =

* Implements the integration with Google Analytics 4.

= 1.0.66 =

* Includes the new filter eddmp_is_local that receives two parameters, the file path or false and the original file URL.

= 1.0.65 =

* Modifies the module that handles the audio duration.

= 1.0.64 =

* Fixes an issue with the volume when the default value is zero.

= 1.0.63 =

* Removes functions deprecated by the latest Elementor update.

= 1.0.62 =

* Modifies the FFmpeg integration and settings.

= 1.0.61 =

* Includes a modification in the skins.

= 1.0.60 =

* Fixes a deprecated notice with PHP8.1.3.

= 1.0.59 =

* Includes some additional validations to prevent malfunctions when EDD is disabled.

= 1.0.58 =

* Fixes an issue in the integration with the latest update of Visual Composer.

= 1.0.57 =

* Modifies the Elementor widget.

= 1.0.56 =

* Fixes an issue with the quick edit.

= 1.0.55 =

* Fixes a conflict with some theme styles.

= 1.0.54 =

* Implements support for the loop attribute in the playlist shortcode.

= 1.0.53 =

* Modifies the Elementor widget.

= 1.0.52 =

* Includes support for the class attribute in the playlist shortcode. The new attribute allows you to assign a class name to the playlist container to customize the appearance of the players easier.

= 1.0.51 =

* Include the purchased attribute in the playlist shortcode to load the downloads purchased by the registered user.

= 1.0.50 =

* Modifies the files management.

= 1.0.49 =
= 1.0.48 =

* Fixes a conflict with third-party themes.

= 1.0.47 =

* Modifies the global settings. Allows to reset the demos of purchased files.

= 1.0.46 =

* Modifies the module that deletes the files for demo.

= 1.0.45 =

* Improves the integration with the Gutenberg editor.

= 1.0.44 =

* Improves the appearance of players on some themes.

= 1.0.43 =

* Hides the upgrade texts for non-administrator users.

= 1.0.42 =

* Modifies the players' settings.
* In the Professional version of the plugin allows applying watermark audio to the audios for demo.

= 1.0.41 =

* Fixes some notices message on the playlist widget.

= 1.0.40 =

* Includes a new attribute in the plugin's settings for controlling the fade out effect in the demos.
* Fixes an issue in the Google Drive add-on (Professional version of the plugin).

= 1.0.39 =

* Applies a fade out to the audio files for demo.

= 1.0.38 =

* Fixes a conflict between the play all feature and the last version of the MediaElementJS library.

= 1.0.37 =

* Modifies the playlist shortcode to allow inserting the player in downloads' pages.

= 1.0.36 =

* Fixes an issue configuring the players to be loaded in singular or multiple pages.

= 1.0.35 =

* Includes a new option in the plugin's settings to allow multiple players to play simultaneously.

= 1.0.34 =

* Removes unnecessary logs.

= 1.0.33 =

* Improves the accessibility.

= 1.0.32 =

* Modifies the plugin's interface.
* Allows playing the original audio files when the buyers visit the products pages (Professional version of the plugin).

= 1.0.31 =

* Upgrades the version of MediaElement JS library as its core.
* Includes minor changes in the skins designs (caused by the upgrade of MediaElement JS).
* Includes support for M3U and M3U8 playlists.

= 1.0.30 =

* Fixes an issue with the m4a files.

= 1.0.29 =

* The plugin checks the existence of the global variable: $GLOBALS['eddmp_post_types'] to identify those post types where the music players would be integrated. This new variable allows the developers of plugins related to Easy Digital Downloads to include the music players with their custom post types.

= 1.0.28 =

* Loads music players in scrolling events making the music player for EDD compatible with infinite scrolling themes and plugins.

= 1.0.27 =

* Includes the volume attribute in the widget settings.

= 1.0.26 =

* Includes a new attribute in the player's settings for entering the default volume, a decimal number between 0 and 1.

= 1.0.25 =

* Hides the download control of players, when are used the default players of devices.

= 1.0.24 =
= 1.0.23 =

* Fixes a CSS conflict with themes of thirds.

= 1.0.22 =

* Fixes a conflict with themes of thirds.

= 1.0.21 =

* Modifies the module that generates the demo files.

= 1.0.20 =

* Fixes a warning message.

= 1.0.19 =

* Fixes a conflict with a third party plugin.

= 1.0.18 =

* Includes new options in the troubleshoot area, in the settings page of the plugin, to load the players on iPads and iPhones with the default controls of devices.

= 1.0.17 =

* Updates some vendors libraries.

= 1.0.16 =

* Optimizes the javascript files.
* Modifies the plugin's registration module (Professional version).

= 1.0.15 =

* Fixes a conflict with some third party themes.

= 1.0.14 =

* Improves the generation of music players.

= 1.0.13 =

* Fixes an issue encoding the ampersand symbols in some URLs.
* Modifies the playlist widget.

= 1.0.12 =

* Modifies the script that generates the players for those pages whose contents are loaded with AJAX, and don't trigger document onready or window onload events.

= 1.0.11 =

* If the playlist shortcode is inserted into a download product without the downloads_ids attribute, the playlist will be generated with only the current download product.

= 1.0.10 =

* Fixes some conflicts with third party plugins.

= 1.0.9 =

* Adapts the plugin's block to the latest version of the Gutenberg editor.

= 1.0.8 =

* Modifies the admin.js file to prevent conflicts with other plugins.

= 1.0.7 =

* Includes two new actions: eddmp_main_player and eddmp_all_players to allow the themes' developers insert the players in the preferred places of the downloads' pages and the stores' items.

= 1.0.6 =

* Modifies the plugin's interface.

= 1.0.5 =

* Includes a new feature, to allow insert the music players only for registered users.

= 1.0.4 =

* Modifies the playlist styles.

= 1.0.3 =

* Makes easier the access to the EDD hooks.

= 1.0.2 =

* Includes some modifications in the player's styles.

= 1.0.1 =

* Optimizes the public javascript files using Google Compiler.

= 1.0.0 =

* First version released.