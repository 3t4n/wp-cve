=== Audio Album ===
Contributors: numeeja
Donate link: https://cubecolour.co.uk/wp
Tags: audio, album, playlist, music, mp3, ogg, m4a, wma, wav, media
Requires at least: 4.9
Tested up to: 6.4
Stable tag: 1.5.0
License: GPLv2

Displays a collection of audio tracks as an audio album using the native WordPress audio features. Includes a customizer section.

== Description ==

The plugin was originally created for [Dave Draper's](https://davedrapercreations.co.uk/ "Dave Draper") website where it is used on the music pages. For an example, please see the page for the [Wild Bunch album](http://davedrapercreations.co.uk/music/the-wild-bunch/ "The Wild Bunch") featuring Dave on vocals and bass.

Audio Album uses the default audio capabilities of mediaelement.js included with core WordPress files and enables you to style a group of audio files (MP3 etc) as single block formatted as an album. The colours can be customized in the Audio Album section of the WordPress customizer.

You can display as many Audio Albums as you need on your site, with multiple albums on each page.

= Usage: =

There are two shortcodes that can be used `[audioalbum]` and `[audiotrack]`

`[audioalbum]`
This shortcode is required and can be used as a header before the `[audiotrack]` shortcodes.

* title
* detail
* date

`[audiotrack]`
This acts as a wrapper to the WordPress [audio] shortcode so uses the same parameters as the [audio] shortcode, plus the following which are specific to this plugin:

* title
* width
* height
* songwriter
* buttontext
* buttonlink

The following attributes can also be used in the `[audiotrack]` shortcode in the same way in which they are used in the default WordPress [audio] shortcode:

* src
* mp3
* ogg
* wma
* m4a
* wav
* loop
* autoplay
* preload

= Example =

`
[audioalbum title="The Album Title" detail="Some other Details" date="2022"]

[audiotrack title="Song One" songwriter="credit" mp3="http://domain.com/wp-content/uploads/audiofile1.mp3"]

[audiotrack title="Song Two" songwriter="credit" mp3="http://domain.com/wp-content/uploads/audiofile2.mp3"]
`

= Lyrics / other info in a popup window =
There are some additional parameters which can be added to the [audioalbum] shortcode to optionally add a button to allow a visitor to open a link on each audio track to open a page in your site within a popup window.

You need to first create the page, post, or custom post type post and make a note of the post/page id.

There are four parameter/value pairs which can be added to the [audiotrack] shortcode to make a popup link

* buttonlink
* buttontext
* width
* height

Enter the page/post id of the target page as the value for the buttonlink parameter. A button will only be shown when a value is set for the buttonlink parameter.

The buttontext attribute is optional, if no value is specified, the default label of 'lyrics' will be shown on the button.

The dimensions of the popup window can also be specified using optional width and height attributes. If no values are given, default values of 520px (width) and 400px (height) will be used.

If your site is using a Genesis child theme, as a little bonus, a landing-page template without a masthead, menus, sidebars or other distractions will be applied to the popup page.

= Example with default 'lyrics' button =
`[audiotrack title="Song One" songwriter="credit" mp3="http://domain.com/wp-content/uploads/audiofile1.mp3" buttonlink="808"]`

= Example with custom button and custom popup window dimensions =
`[audiotrack title="Song Two" songwriter="credit" mp3="http://domain.com/wp-content/uploads/audiofile2.mp3" buttonlink="909" buttontext="linklabel" width="300" height="500"]`

The parameters used with the standard native WordPress audio shortcode outlined in the codex: [Audio Shortcode](https://codex.wordpress.org/Audio_Shortcode "Audio Shortcode") page can also be used in the `[audiotrack]` shortcode.

== Installation ==

1. Upload the plugin folder to your '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where is the plugin's admin page? =

Go to Appearance -> Customize -> Audio Album

= Is it responsive? =

Yes - it should work with any responsive theme. It defaults to 100% of the width of the enclosing element. If you are using a theme or plugin that provides column shortcodes, it should, it work well with these.

= Can I change the fonts? =

The fonts used are inherited from your theme, so in most cases they should work well, but if you do need to change them, ensure that the font you want to use has already been made available in your theme, and add the following css rule (with the appropriate font name) to your child theme or a custom CSS plugin:

`
.audioheading,
.audioalbum,
.track {
	font-family: 'verdana', sans-serif;
}
`

= How can I make more substantial customizations to the styles? =

Select the checkbox for manual CSS in the Audio Album customizer section. This will prevent the audio album styles from loading. Then copy the default audio album stylesheet from the plugin into your child theme or custom theme's stylesheet or into a custom CSS plugin, and make your customisations to this copy. The manual CSS option is intended for experienced developers with CSS skills.

= Can I display multiple albums on a single page? =

Yes you can. As many as you like.

= What about playlists? =

Audio Album was created before playlist functionality was introduced to WordPress with version 3.9, so it does not use or provide any playlist features.

= Can you create for me a customized stylesheet to fit in with the colours of my website? =

I would love to, but my time is limited and this is beyond of the scope of the free support that I can give on the forums, I can offer this as a paid service. Please send me details of your requirements via the [cubecolour contact form](http://cubecolour.co.uk/contact/ "cubecolour contact form").

= Are there any other free cubecolour plugins? =

If you like this plugin, you may like some of my other plugins in the WordPress.org plugins directory. These are listed in the plugins page on my WordPress.org [profile](https://profiles.wordpress.org/numeeja/#content-plugins "cubecolour profile") page.

= All of the tracks have number '1' rather than incrementing numbers =

This can occur if you don't have an audioalbum shortcode before the audiotrack shortcodes

= Why do you spell the word 'color' incorrectly? =

I don't, I'm from England and 'colour' is the correct spelling.

= Why is there base64 stuff in the plugin's stylesheet? =

Base64 is a way of representing binary data using only text characters. Although base64 is sometimes used to hide scripts with nefarious intent in php files, it is used legitimately to enable small gif and png images to be embedded in the stylesheet as data URIs.

= I am using the plugin and love it, how can I show my appreciation? =

You can donate via [my donation page](http://cubecolour.co.uk/wp/ "cubecolour donation page")

If you find the plugin useful I would also appreciate a five star review on the [plugin review page](http://wordpress.org/support/view/plugin-reviews/audio-album/ "audio album plugin reviews")

= Why doesn't it work? =

The plugin does work on the sites it has been tested on. If it is not working for you, you may have done something wrong or maybe your theme was not built to WordPress standards. If it isn't working for you, please read the documentation carefully, and if that doesn't address your issue, post a question on the [plugin support forum](http://wordpress.org/support/plugin/audio-album/ "audio album plugin support forum") so we can have an opportunity to try to get it working before you leave a review that tells people more about you than about the plugin.

= What levels of support are available? =

I can offer free forum support for free cubecolour plugins where all communication takes place on the WordPress.org forums and a link is provided to the page on your site where I can see the issue without needing a password.

If the conditions for obtaining free support on the public forum are not compatible with the level of support required, non-free support via email is available. This prepaid email support can be requested at: [cubecolour.co.uk/premium-support](http://cubecolour.co.uk/premium-support "cubecolour.co.uk/premium-support")

== Screenshots ==

1. The default style should fit most site designs.
2. Change the colour scheme using the audio album section of the WordPress customiser.

== Changelog ==

= 1.5.0 =
* Removed jQuery as dependency on the front end (although mediaelement still requires jQuery)

= 1.4.4 =
* Make translatable

= 1.4.3 =
* Fix js error caused by WP migrate no longer being loaded in WP

= 1.4.2 =
* Formatting improvements

= 1.4.0 =

* Inline script loaded with a dependancy to the enqueued script to ensure jQuery is loaded.

= 1.3.1 =

* added CSS for replay button after track has completed playing.

= 1.3.0 =

* corrected register_style issue in shortcode function
* corrected $preload variable used instead of array value
* added animated gif loading image as data url
* added transparent png images for time & volume track as data urls
* improved default colours & added customizer controls for colour
* removed font definition from stylesheet to enable the font to be inherited from theme's body style
* removed rem values from stylesheet
* improved alignment of generated track numbers
* now uses Dashicons for play/pause/mute/unmute buttons - so they can be styled
* Popup template improvements (Genesis only)
* The [audioalbum] shortcode is no longer mandatory before [audiotrack] shortcodes

= 1.2.0 =

* improve delimiting for comments
* add default value for preload shortcode param: metadata so total track time appears in player
* improved parameter value sanitization
* use array for shortcode parameters instead of extract
* use constant for plugin version
* use visibility instead of display for delaying appearance of tracks

= 1.1.0 =

* Fixed WordPress 4.4 compatibility issue
* Improved enqueuing so script & stylesheet are only loaded when needed

= 1.0.4 =

* Improved function to audio players with default style briefly showing as the page loads
* Uses Dashicons in plugin page links
* Minor CSS tweaks

= 1.0.3 =

* Fixes incorrect path to svg file in stylesheet

= 1.0.2 =

* Prevent unstyled players being flashed onscreen until the page has fully loaded
* Flat Player buttons to replace default gradient filled buttons

= 1.0.1 =

* Fixes function name collision when another cubecolour plugin is installed

= 1.0.0 =

* Initial Version

== Upgrade Notice ==

= 1.5.0 =
* Removed jQuery as dependency on the front end (although mediaelement still requires jQuery)

= 1.4.4 =
* Make translatable

= 1.4.3 =
* Fix js error caused by WP migrate no longer being loaded in WP

= 1.4.2 =
* Formatting improvements

= 1.4.0 =

* Inline script loaded with a dependancy to the enqueued script to ensure jQuery is loaded.

= 1.3.1 =

* added CSS for replay button after track has completed playing.

= 1.3.0 =

Various minor bug fixes, audio album customizer section.

= 1.2.0 =

* improve delimiting for comments
* add default value for preload shortcode param: metadata so total track time appears in player
* improved parameter value sanitization
* use array for shortcode parameters instead of extract
* use constant for plugin version
* use visibility instead of display for delaying appearance of tracks

= 1.1.0 =

* Fixed WordPress 4.4 compatibility issue
* Improved enqueuing so script & stylesheet are only loaded when needed

= 1.0.4 =

* Improved function to audio players with default style briefly showing as the page loads
* Uses Dashicons in plugin page links
* Minor CSS tweaks

= 1.0.3 =

* Fixes incorrect path to svg file in stylesheet

= 1.0.2 =

* Prevent unstyled players being flashed onscreen until the page has fully loaded
* Flat Player buttons to replace default gradient filled buttons

= 1.0.1 =

* Fixes function name collision when another cubecolour plugin is installed

= 1.0.0 =

* Initial Version