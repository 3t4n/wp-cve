=== SoundCloud Ultimate Plugin ===
Contributors: wpsolutions
Donate link: http://wpsolutions-hq.com/
Tags: SoundCloud, SoundCloud plugin, SoundCloud WordPress, music, mp3, podcast, media
Requires at least: 3.2
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv3

Allows you to upload, display, preview, or delete tracks to/from your SoundCloud account.

== Description ==

The SoundCloud Ultimate plugin allows musicians, podcasters or web owners who use SoundCloud to manage or display their tracks from their WordPress site.
The plugin's features are listed below:

- Secure authentication/connection from your WordPress site to your SoundCloud account using oAuth2

- Display any of your tracks with the special SoundCloud player anywhere on your blog by using a special shortcode

- Upload new tracks from your WordPress administration panel directly to your SoundCloud account.

- View your current tracks from the WordPress admin panel which you have already been uploaded to your SoundCloud account.

- Play/preview currently uploaded tracks directly from the WP admin panel

- Delete tracks from your SoundCloud account directly from the WordPress admin panel

For more information on this and other plugins, visit the <a href="http://wpsolutions-hq.com/" target="_blank">WPSolutions-HQ Blog</a>.
Post any questions or feedback regarding this plugin at our website here: <a href="http://wpsolutions-hq.com/soundcloud-wordpress/" target="_blank">SoundCloud Ultimate</a>.

== Installation ==

1. FTP the wp-soundcloud-ultimate folder to the /wp-content/plugins/ directory, 

OR, alternatively, 

upload the wp-soundcloud-ultimate.zip file from the Plugins->Add New page in the WordPress administration panel.

2. Activate the wp-soundcloud-ultimate plugin through the 'Plugins' menu in the WordPress administration panel.

If you encounter any bugs, or have comments or suggestions, please contact the WPSolutions-HQ team on support@wpsolutions-hq.com.


== Frequently Asked Questions ==

= Will this plugin allow me to display my tracks using the SoundCloud player anywhere on the front-end of my site including widget areas? =

Yes. To display the SoundCloud player with one of your tracks you simply need to use the plugin's shortcode and specify the 
URL of your track. For example:
[soundcloud_ultimate track=http://soundcloud.com/yoursoundcloudname/your-track-name]

If you want to display a SoundCloud player in the WordPress widget area you simply need to place the above
shortcode inside a "text" widget.
(Note: you can easily get the value of your "track" url from the plugin's administration page by going to the "Manage Tracks" tab and copying the "Track URL" value)

= Can I set the track to autoplay when I use the player shortcode mentioned above? =

Yes. To make a track automatically play when displaying SoundCloud player using the plugin's shortcode just specify "auto_play=true" option in the shortcode. For example:

[soundcloud_ultimate track=http://soundcloud.com/yoursoundcloudname/your-track-name auto_play=true]

= Will this plugin allow me to display tracks with the SoundCloud player on the front-end of my site which are from someone else's account? =

Yes you can display any SoundCloud track as long as its owner has allowed it to be publicly shown by using the shortcode discussed
in the section above and pasting the approriate URL of the track. 
NOTE: in the administration settings pages of this plugin you will only have access to tracks from your account.

Go to the plugins webpage for more details about usage and configuration: <a href="http://wpsolutions-hq.com/soundcloud-wordpress/" target="_blank">wp-soundcloud-ultimate</a>.

== Screenshots ==

1. Screen shot showing Settings page of this plugin.
2. Screen shot showing the "Tracks" administration page.
3. Screen shot showing the SoundCloud media player which is displayed when using the plugin's shortcode on the front end



== Changelog ==
= 1.5 =
- Improved the handling of the HTTP code 302 scenarios
- Corrected some shortcode architecture

= 1.4 =
- Added a fix for people who were getting http code error 0 when trying to connect to SoundCloud
- Corrected PHP warnings in settings page

= 1.3 =
- Plugin can now handle the previewing/playing of "private" tracks
- Added autoplay configuration option for player widget
- Added support for shortcode usage inside text widget 

= 1.2 =
- added ability to view playlists in the settings "Manage Tracks" tab

= 1.1 =
- Fixed some error scenarios

= 1.0 =
- First Release

For more information on the wp-soundcloud-ultimate and other plugins, visit the <a href="http://wpsolutions-hq.com/" target="_blank">WPSolutions-HQ Blog</a>.
Post any questions or feedback regarding this plugin at our website here: <a href="http://wpsolutions-hq.com/soundcloud-wordpress/" target="_blank">wp-soundcloud-ultimate</a>.
