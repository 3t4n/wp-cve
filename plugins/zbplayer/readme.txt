===zbPlayer ===
Contributors: zubaka
Donate link: http://gilevich.com/portfolio/zbplayer
Tags: mp3, flash player, audio player, small mp3 player, media player, music player, mp3 player, cyrillic mp3 player, mp4 player, wav player
Requires at least: 3.5
Tested up to: 5.5
Stable tag: 2.4.2
License: Dual Licensed under the MIT and GPLv2 or later

zbPlayer is a small and very easy plugin. It does one thing: capture mp3 links and insert a small flash player instead.

== Description ==

zbPlayer is a very easy audio plugin - you can select some options like: include Download link or no, enable autoplay or no and setup width of player. Other things will be done by zbPlayer plugun automatically. One nice feature - player support cyrillic filenames without problem.

== Installation ==

The most basic installation is a simple two step:

1. Upload the `zbplayer` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

That's it, you're done.

== Frequently Asked Questions ==

= Do I need to do anything special in my posts? =

No, zbPlayer automatically converts any link to an mp3/m4a/m4b/mp4/wav file into player. So, if you put in &lt;a href="audio_file.mp3"&gt;A Link&lt;/a&gt; zbPlayer will automatically add a flash player on page.

= Does player support cyrillic names for mp3 files? =

Yes, here no problems with cyrillic filenames.


== Screenshots ==

1.  The minimized player.
2.  The expanded player.


== Known Issues ==

= Sometime my .m4a fields doesn't play. Why is it? =

Unfortunately, some of .m4a files has nuances and cannot play by player. We can't fix that. Use .mp3 files - they works always.

== Changelog ==

= 2.4.2 =
*    Preload files metadata to fix Download problem for links with special characters

= 2.4.1 =
*    Tested on WP version 5.5
*    Fixed deprecated syntax for PHP 7.4

= 2.4.0 =
*    Tested on WP version 5.4
*    Removed broken Facebook share functionality

= 2.3.1 =
*    Remove player options after uninstall


= 2.3.0 =
*    Added Desktop Native player controls in admin
*    Tested up to 4.8.1 Wordpress version

= 2.2.1 =
*    Changes regarding new Wordpress Plugin Guidelines

= 2.2 =
*    Show native player for mobile if flag selected in admin

= 2.1.13 =
*    Fixed special characters in the link
*    Admin design minor fixes

= 2.1.12 =
*    Fixed new system requirements for wp_enqueue_style() method

= 2.1.11 =
*    Tested up to Wordpress version 4.3.1
*    Fixed problem with display symbols like "&#8217;"

= 2.1.10 =
*    For now player understand also default Wordpress [audio mp3="http://xxxxxx"][/audio] tags.

= 2.1.8/9 =
*    Fix links/file name problem. Sometime flash player did not understand correctly file names to play.

= 2.1.7 =
*    Get rid limit of preg_replace() problems by exact number of expected replaces

= 2.1.6 =
*    Hide warnings for some hosting servers about disabled method ini_restore()

= 2.1.5 =
*    Fixed problem with lost audio files from &lt;a&gt; links.

= 2.1.4 =
*    Fixed problem with empty posts (regex errors).

= 2.1.1/2/3 =
*    Improved search algorithm for audio files/links.

= 2.1.0 =
*    If no possibility to use nice Flash player, we will use standard <audio> tags instead. It can be very useful for mobile devices
*    Added possibility to hide song name, but leave Download link

= 2.0.6 =
*    Fixed Facebook share functionality
*    Improved admin design

= 2.0.5 =
*    Added controls to allow payer loops

= 2.0.4 =
*    Added HTML5 tag 'download' for download link to let browser know that this link should be downloaded(not played)

= 2.0.3 =
*    Added possibility set custom song title via "data-title" tags. For example &lt;a data-title="Custom title"&gt;Standard title&lt;/a&gt;

= 2.0.2 =
*    Moved control for ID3 tags to gobal menu

= 2.0.1 =
*    Added possibility to use ID3 tags from files instead link name
*    Fixed time counter in player

= 2.0.0 =
*    Added controls for player colors
*    Changed descritpion and known bugs
*    Added possibility to play audio format like .wav

= 1.9.4 =
*    Added possibility to play audio formats like m4a, m4b and mp4

= 1.9.1/2/3 =
*    Fixed song name views for characters like "-" and "&"

= 1.9 =
*    Added Facebook share button functionality. Now you can share your mp3 files on Facebook and listen them on Facebook on your Timeline.

= 1.8.2 =
*    Added control for player animation to let possibility show full size of player at once.

= 1.8.1 =
*    Fixed donation button

= 1.8 =
*    Added option in admin to show/hide song name above player.
*    Added "Multiplayer" option in admin to setup only one player on page with all mp3's
*    Improved admin design, added Donation button.

= 1.7.1 =
*    Fix for admin part in debug mode.

= 1.7 =
*    Added localization for 'Download' link

= 1.6 =
*    Added initial volume control for player.

= 1.5 =
*    Replaced characters from '_' to ' ' in song names.

= 1.4 =
*    Fixed problem with player path if wordpress works not from default directory
*    Fixed pathinfo function - now it works with UTF-8 encoded file names too

= 1.3 =
*    Allow to play files with cyrillic filenames.

= 1.2 =
*    Increased player width. Now player looks better.

= 1.1 =
*    Fix constants namings

= 1.0 =
*    Init version

== Upgrade Notice ==

= 1.0 =
*    Just Init version
