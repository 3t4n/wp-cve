=== PlayerJS ===
Contributors: playerjs
Plugin URI: https://playerjs.com/docs/q=wordpress
Author link: https://playerjs.com
Tags: PlayerJS, HTML5 player, video player, audio player, HLS player, DASH player, YouTube player, Vimeo player
Requires at least: 4.6
Tested up to: 6.3.1
Stable tag: trunk
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The official plugin for PlayerJS.com - video & audio player builder. Make an awesome player for your website for free.

== Description ==

Build custom video / audio player on <a href="https://playerjs.com" target="_blank">PlayerJS.com</a> for FREE and place it on your website.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/playerjs` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the `Plugins` screen in WordPress.
3. Build your own player at <a href="https://playerjs.com" target="_blank">playerjs.com</a> and replace the default file playerjs_default.js to yours or upload separately and change the URL on the Settings page of the plugin.
4. Use the button PlayerJS in the WP 4 or simple place a [playerjs] shortcode in WP 5. Below is the list of accepted parameters:

* file: path to the video (mostly MP4) / audio (mostly MP3) / HLS / DASH / YouTube / Vimeo / JSON playlist
* title: text inscription (optional)
* subtitle: path to .srt .ass .ssa or .vtt file (optional)
* poster: path to image (optional)
* thumbnails: path to thumbnails .vtt file (optional)
* width: player width in percentage (50%) or pixels (500), default 100% (optional)
* autoplay: 0/1 (optional)
* start: start playback from the specified second
* end: end playback on the specified second
* align: left, right, center (optional)
* margin: in pixels (optional)

All other settings can be configured in the <a href="https://playerjs.com" target="_blank">builder</a>.

Sample shortcode:

`[playerjs file="//plrjs.com/x.mp4"]`

== Frequently Asked Questions ==

= How can i add quality selection? =

Sample shortcode with quality selection:

`[playerjs file="[480]//site.com/480.mp4,[720]//site.com/720.mp4"]`

= How can i add a protective watermark? =

You can show in the player invisible username in a random place to protect against screen recording. Enable <a href="https://playerjs.com/docs/q=watermarks#wp" target="_blank">watermarks plugin</a> in the builder, update your player and pass to shortcode watermark=1.

`[playerjs file="URL" watermark=1]`

= How can i use playlists? =

Sample shortcode with <a href="https://playerjs.com/docs/q=playlist" target="_blank">JSON playlist</a>:

`[playerjs file="[{'title':'1','file':'http://plrjs.com/x.mp4'},{'title':'2','file':'http://plrjs.com/x.mp4'}]"]`

or

`[playerjs file="//site.com/json.txt"]`

= Where can i find documentation for all player features? =

You can find complete documentation here:
* <a href="https://playerjs.com/docs/search=quality" target="_blank">Quality</a>
* <a href="https://playerjs.com/docs/q=ga" target="_blank">Google Analytics</a>
* <a href="https://playerjs.com/docs/search=api" target="_blank">Javascript API</a>
* <a href="https://playerjs.com/docs/q=playlist" target="_blank">Playlist</a>
* <a href="https://playerjs.com/docs/q=customelements" target="_blank">Logo</a>
* <a href="https://playerjs.com/docs/q=playersize" target="_blank">Aspect ratio</a>
* <a href="https://playerjs.com/docs/search=playback" target="_blank">Playback</a>
* <a href="https://playerjs.com/docs/q=audiotracks" target="_blank">Audio tracks</a>
* <a href="https://playerjs.com/docs/q=thumbnailsphpwebvtt" target="_blank">Thumbnails</a>
* <a href="https://playerjs.com/docs/search=share" target="_blank">Social sharing</a>
* <a href="https://playerjs.com/docs/q=youtube" target="_blank">YouTube</a>
* <a href="https://playerjs.com/docs/q=vimeo" target="_blank">Vimeo</a>
* <a href="https://playerjs.com/docs/q=watermarks" target="_blank">Watermarks</a>
* <a href="https://playerjs.com/docs/search=dash" target="_blank">DASH</a>
* <a href="https://playerjs.com/docs/search=hls" target="_blank">HlS</a>
* <a href="https://playerjs.com/docs/q=encodingbase64" target="_blank">Hotlink protection</a>
* <a href="https://playerjs.com/docs/q=combine" target="_blank">Combining multiple players into one</a>

= How can i set the custom height of the player? =

You can use option `height:` in shortcode (height:300), when <a href="https://playerjs.com/docs/q=playersize" target="_blank">aspect ratio</a> is set to value `container size`.


== Screenshots ==

1. Player shortcode
2. Plugin settings

== Changelog ==

= 2.1 =
* Default player script updated to the version 8.91

= 2.2 =
* Settings page and a special button PlayerJS for WP 4

= 2.3 =
* Minor changes for WP 5

= 2.4 =
* Fixed bugs

= 2.5 =
* Fixed bugs, default player updated

= 2.6 =
* Playlist JSON inside shortcode

= 2.7 =
* Fixed bugs, default player updated

= 2.8 =
* <a href="https://playerjs.com/docs/q=encodingbase64" target="_blank">Hotlink protection</a> added

= 2.9 =
* <a href="https://playerjs.com/docs/q=watermarks#wp" target="_blank">Watermark</a> support added, default player updated

= 2.10 =
* Fixed bugs, default player updated

= 2.11 - 2.13 =
* Fixed bugs, default player updated

= 2.14 =
* Fixed bug with Classic Editor (TinyMCE) button

= 2.15 - 2.17 =
* Fixed bugs, default player updated

= 2.18 =
* Time memorization fixed

= 2.19 =
* Added <a href="https://playerjs.com/docs/q=replacetags" target="_blank">audio/video tag replacement</a>

= 2.20-2.21 =
* Bug fixed

= 2.22 =
* Asynchronous script loading

= 2.23 =
* Bug fixed, default player updated