=== Podcast Feed Player Widget and Shortcode ===
Contributors: douglaskarr
Tags: podcast, podcasting, podcaster, feed, widget, player, audio player, mp3 player, media player, audio, mp3, audioplayer, rss, google play, itunes, rss,  media, podcast widget, soundcloud, libsyn, stitcher, tunein, apple podcasts, overcast, pocketcasts, castro, shortcode
Requires at least: 4.0.0
Tested up to: 5.2.2
Stable tag: 2.2.0
Version: 2.1.1
License: GPLv2

The default RSS widget didn't provide an audio player nor the image for the episode, so I built one. I've also since added a shortcode in the event you'd like to embed a podcast feed (or feeds) within a page.

== Description ==

A simple feed widget or shortcode that displays the iTunes set image for the episode and embeds the default WordPress’ internal audio player to play it.

= About Podcast Feed Player Widget =

WordPress has an RSS Widget, but it doesn’t includes custom podcast tags for the episode image and the audio file, this one does!

Shortcode Usage: 

	[podcastfeed feedurl="" quantity="" imgsize="" imgclass="" itunes="" google="" soundcloud="" icons=""]Here are our latest podcasts.[/podcastfeed]

* feedurl - Your podcast feed address.
* quantity - The quantity of podcasts you wish to display.
* imgsize - The size of the image you'd like to display, 0 for no image.
* imgclass - The class for the image, default is alignleft 
* itunes - Your iTunes address to display in the icons.
* google - Your Google Play address you'd like to display in the icons.
* soundcloud  - Your SoundCloud address you'd like to display in the icons.
* icons - Whether you want to display icons, default is true.

== Installation ==

1. Upload the `podcast-feed-player-widget` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Drag the widget into your sidebar.
1. Set the title of your widget area.
1. Add your Podcast Feed.
1. Specify your iTunes and Google Play URLs.
1. Identify how many podcasts you wish to display.
1. Set the image size of your episode image.
1. Option: Set your iTunes, Google Play, and optional SoundCloud URLs to promote your podcast.
1. Click Save


== Frequently Asked Questions ==

= Do you have a shortcode for embedding in pages or posts? =

Yes! Shortcode usage:

	[podcastfeed feedurl="" quantity="" imgsize="" imgclass="" itunes="" google="" soundcloud="" icons=""]Here are our latest podcasts.[/podcastfeed]

= Can I disable the thumbnail? =

Yes, you can set the thumbnail size to 0 and then it will not display it.

= Why is the thumbnail loaded via Weserv? =

The image size on your podcast feed is not optimal and could slow down your site, so we use a free image resize and caching service.

= Can it support multiple feeds? =

Yes, just enter the feeds with a comma as the delimiter.

= What if my image is very large and slowing down the site? =

We've added a Cloudimage integration so you can add your token and the images are cached and resized on the fly.

= What if I don’t have an image? =

No worries, the image area will not be displayed.

= Can I customize the styling? =

Yes, classes are designated for each element.

== Screenshots ==

1. Widget Options
2. Widget Output

== Changelog ==

= 2.2.0 =

Fixed a bug where no image was still outputting an image tag.

= 2.1.1 =

Added code so the feed doesn't include anything.

= 2.1.0 =

Added code to disable the shortcode in a feed since feeds don't support the audio player.

= 2.0.2 =

Really added Shortcode documentation.

= 2.0.1 =

Added Shortcode documentation.

= 2.0.0 =

Added Shortcode capabilities to pull Podcast Feeds into a page or post.

= 1.4.5 =

Additional change to logic to look for a 0 in the size field.

= 1.4.4 =

Minor change to logic to look for a 0 in the size field.

= 1.4.3 =

Added an option to display no thumbnail if the size is set to 0.

= 1.4.2 =

Updated the plugin to utilize Weserv, a free image resizing and caching proxy.

= 1.4.1 =

Fixed an issue with the validation and multiple feeds.

= 1.4.0 =

Added a Cloudimage integration to cache and resize images.

= 1.3.3 =

Corrected an issue where the image was outside the paragraph tag for the description.

= 1.3.2 =

Added "Listen to " and the title of the podcast to the image alt tag for accessibility.

= 1.3.1 =

Added SoundCloud as a subscription option to display.

= 1.3.0 =

Added a check to ensure the feeds URLs are valid and secure if your site is secure.

= 1.2.0 =

Added options to display your iTunes and Google Play URLs with Font Awesome Icons.

= 1.1.1 =

The default audio player for WordPress doesn't work if there's a querystring on the audio URL. Added code to strip it off.

= 1.1.0 =

Added support for an array of feeds comma delimited.

= 1.0 =

First version.

== Upgrade Notice ==

= 2.2.0 =

Fixed a bug where no image was still outputting an image tag.

= 2.1.1 =

Added code so the feed doesn't include anything.

= 2.1.0 =

Added code to disable the shortcode in a feed since feeds don't support the audio player.

= 2.0.2 =

Really added Shortcode documentation.

= 2.0.1 =

Added Shortcode documentation.

= 2.0.0 =

Added Shortcode capabilities to pull Podcast Feeds into a page or post.

= 1.4.5 =

Additional change to logic to look for a 0 in the size field.

= 1.4.4 =

Minor change to logic to look for a 0 in the size field.

= 1.4.3 =

Added an option to display no thumbnail if the size is set to 0.

= 1.4.2 =

Updated the plugin to utilize Weserv, a free image resizing and caching proxy.

= 1.4.1 =

Fixed an issue with the validation and multiple feeds.

= 1.4.0 =

Added a Cloudimage integration to cache and resize images.

= 1.3.3 =

Corrected an issue where the image was outside the paragraph tag for the description.

= 1.3.2 =

Added "Listen to " and the title of the podcast to the image alt tag for accessibility.

= 1.3.1 =

Added SoundCloud as a subscription option to display.

= 1.3.0 =

Added a check to ensure the feeds URLs are valid and secure if your site is secure.

= 1.2.0 =

Added options to display your iTunes and Google Play URLs with Font Awesome Icons.

= 1.1.1 =

The default audio player for WordPress doesn't work if there's a querystring on the audio URL. Added code to strip it off.

= 1.1.0 =

Added support for an array of feeds comma delimited.

= 1.0 =

First version.