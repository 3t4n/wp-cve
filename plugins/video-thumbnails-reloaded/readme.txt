=== Video Thumbnails Reloaded ===
Contributors: yehudah
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=yehuda@myinbox.in&item_name=Donation+for+VTS
Tags: Video, Thumbnails, YouTube, Vimeo, Vine, Twitch, Dailymotion, Youku, Rutube, Featured Image
Requires at least: 3.2
Tested up to: 5.7.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Video Thumbnails simplifies the process of automatically displaying video thumbnails in your WordPress template.

== Description ==

Video Thumbnails makes it easy to automatically display video thumbnails in your template. When you publish a post, this plugin will find the first video embedded and retrieve a thumbnail for you. Thumbnails can be saved to your media library and set as a featured image automatically. There's even support for custom post types and custom fields!

Video Thumbnails Reloaded is based on Video Thumbnails developed by Sutherland Boswell
(https://wordpress.org/plugins/video-thumbnails/)

= Supported Sites =

* YouTube
* Vimeo
* Facebook
* Vine
* Twitch
* Dailymotion
* Metacafe
* TED
* VK
* Blip
* Google Drive
* Funny or Die
* CollegeHumor
* MPORA
* Livestream
* Yahoo Screen
* Wistia
* Youku
* Tudou
* SAPO
* Rutube


== Installation ==

1. Upload the `/video-thumbnails/` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Some functions are available to advanced users who want to customize their theme:

* `<?php video_thumbnail(); ?>` will echo a thumbnail URL or the default image located at `wp-content/plugins/video-thumbnails/default.jpg` if a thumbnail cannot be found. Here is an example: `<img src="<?php video_thumbnail(); ?>" width="300" />`
* `<?php $video_thumbnail = get_video_thumbnail(); ?>` will return the thumbnail URL or return NULL if none is found. In this example, a thumbnail is only shown if one is found: `<?php if( ( $video_thumbnail = get_video_thumbnail() ) != null ) { echo "<img src='" . $video_thumbnail . "' />"; } ?>`

== Frequently Asked Questions ==

= No video thumbnail for this post =

1. Ensure you have saved any changes to your post.
1. If you are using a a plugin or theme that stores videos in a special location other than the main post content area, be sure you've entered the correct custom field on the settings page. If you don't know the name of the field your video is being saved in, please contact the developer of that theme or plugin.
1. Copy and paste your embed code into the "Test Markup for Video" section of the Debugging page. If this doesn't find the thumbnail, you'll want to be sure to include the embed code you scanned when you request support. If it does find a thumbnail, please double check that you have the Custom Field set correctly in the settings page if you are using a a plugin or theme that stores videos in a special location.
1. Go to the Debugging page and click "Test Image Downloading" to test your server's ability to save an image from a video source.
1. Try posting a video from other sources to help narrow down the problem.
1. Check the support threads to see if anyone has had the same issue.
1. If you are still unable to resolve the problem, start a thread with a good descriptive title ("Error" or "No thumbnails" is a bad title) and be sure to include the results of your testing as well. Also be sure to include the name of your theme, any video plugins you're using, and any other details you can think of.

= Why are there black bars on some YouTube thumbnails? =

Video Thumbnails uses high-resolution widescreen thumbnails whenever they are available. If a video is not in HD, a fullscreen thumbnail is used. This can result in letterboxing when the video is actually widescreen. Users of the [pro version](https://refactored.co/plugins/video-thumbnails) can select an aspect ratio in the settings if this is a constant problem. [More info](https://refactored.co/blog/remove-black-bars-youtube-thumbnails).

= Can I get thumbnails from a specific time? =

No, Video Thumbnails only uses thumbnails provided by the source. If you're posting videos from your own account, many providers allow you to choose the thumbnail.

= Can it get thumbnails for my self-hosted videos? =

No, it will only fetch thumbnails for videos from the list of supported sites. Decoding local video files to create thumbnails would require server resources and packages unavailable to most users, so we only focus on supporting major video sites.

= My theme isn't showing thumbnails, what's wrong? =

The most likely problem is that your theme doesn't support post thumbnails. If thumbnails are supported, you should see a box titled "Featured Image" on the edit post page. If thumbnails aren't supported, your theme will have to be modified to support Featured Images or to support one of our custom functions.

= How can I use Video Thumbnails if I use a custom field to store the video? =

On the Video Thumbnails settings page just enter the name of the custom field and the plugin will scan it.

= Can I use the functions outside of a loop? =

Yes, but be sure to include the post ID as a parameter. For example: `<?php $thumbnail = get_video_thumbnail(25); ?>`

= My video service/embedding plugin isn't included, can you add it? =

If the service allows a way to retrieve thumbnails, I'll do my best to add it.

= How do I use this plugin with custom post types? =

The settings page includes a checklist of all your post types so you can pick and choose.

= I am editing my theme and only want to display a thumbnail if one is found. How do I do this? =

`<?php if( ( $video_thumbnail = get_video_thumbnail() ) != null ) { echo "<img src='" . $video_thumbnail . "' />"; } ?>` will only display a thumbnail when one exists, but I recommend using the Featured Image setting and [the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) template tag.

= I edited my theme and now I'm getting huge thumbnails, how can I resize them? =

The best solution is to use the Featured Image setting and [the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) template tag.

As an alternative you could assign a class to the element and style it with CSS.

= I edited my theme and now I'm seeing the thumbnail and the video, how do I only display the thumbnail? =

Every theme is different, so this can be tricky if you aren't familiar with WordPress theme development. You need to edit your template in the appropriate place, replacing `<?php the_content(); >` with `<?php the_excerpt(); >` so that only an excerpt of the post is shown on the home page or wherever you would like to display the video thumbnail.

= Why did it stop finding thumbnails for Vimeo? =

The Vimeo API has a rate limit, so in rare cases you may exceed this limit. Try again after a few hours.

== Screenshots ==

1. The Video Thumbnail meta box on the Edit Post page
1. Settings page

== Changelog ==

= 1.1 =
* Update readme

= 1.0 =
* Initial release

== Known Issues ==

* In some cases you may have to manually search for thumbnails on the post editor


