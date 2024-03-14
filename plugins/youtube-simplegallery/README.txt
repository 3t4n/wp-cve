=== YouTube SimpleGallery ===
Contributors: stiand
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P7AE724GG8MWN
Tags: youtube, vimeo, gallery, feed, widgets
Requires at least: 2.5
Tested up to: 3.6
Stable tag: 2.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create beautiful galleries with videos from YouTube and Vimeo. 

== Installation ==
= Install from Plugins in WP Dashboard =
Search for «YouTube SimpleGallery» 

OR 

= Upload manually with FTP =
1. Unzip the archive
2. Upload the folder "youtube-simplegallery" to "/wp-content/plugins/"
3. Activate the Plugin in the WordPress Dashboard
4. Change settings on the Options page (or use standard settings)

= Modal effects =

Install either [Shadowbox JS](http://wordpress.org/extend/plugins/shadowbox-js/), [Fancybox for WordPress](http://wordpress.org/extend/plugins/fancybox-for-wordpress/) or [Thickbox](http://wordpress.org/extend/plugins/thickbox/) if you want to show videos in a modal box on your site.

== Description ==
YouTube SimpleGallery is a plugin for creating video galleries with thumbnails. Now merged with the [Vimeo SimpleGallery](http://wordpress.org/plugins/vimeo-simplegallery/), this plugin supports both YouTube and Vimeo. You can even combine videos from both services in the same gallery. The plugin will automatically fetch both thumbnails and titles from YouTube/Vimeo.

*Now also supports user feeds – a much requested feature!*

= YouTube SimpleGallery – new and improved! =

* <strong>YouTube SimpleGallery</strong> and <strong>Vimeo SimpleGallery</strong> have merged, i.e. they now have the same codebase. Old shortcodes still functional.
* NEW FEATURE: It is now possible to combine video services, i.e. you can add links from YouTube and Vimeo in the same gallery.
* NEW FEATURE: Automatically fetch titles from video service.
* NEW FEATURE: User-defined attributes in shortcode overrides default settings: cols=x and thumbwidth=y allows for galleries with different thumb sizes, etc.
* NEW SHORTCODE: [youtubeuserfeed user=username service=youtube] – outputs a gallery from a user’s video feed – works with both YouTube (service=youtube) and Vimeo (service=vimeo).

To embed a gallery in a Post use the following code:

	[youtubegallery]
	http:&#47;&#47;www.youtube.com/watch?v=cRdxXPV9GNQ
	http:&#47;&#47;vimeo.com/13470805
	http:&#47;&#47;www.youtube.com/watch?v=jJK-G9-dLzw
	http:&#47;&#47;www.youtube.com/watch?v=S4aqM_wu6Ns
	http:&#47;&#47;vimeo.com/68180971
	[/youtubegallery]

== Usage ==
= Galleries =
You can now combine links from YouTube and Vimeo in the same gallery.

To embed a gallery in a Post use the following code:

	[youtubegallery]
	http:&#47;&#47;www.youtube.com/watch?v=cRdxXPV9GNQ
	http:&#47;&#47;vimeo.com/13470805
	http:&#47;&#47;www.youtube.com/watch?v=jJK-G9-dLzw
	http:&#47;&#47;www.youtube.com/watch?v=S4aqM_wu6Ns
	http:&#47;&#47;vimeo.com/68180971
	[/youtubegallery]

NOTE: Make sure URLs start with http. For the time being, https is not supported. 

If you want to add titles to the videos, add it before the link and separate with | (pipe), like this:

	[youtubegallery]
	Eyes Close|http:&#47;&#47;vimeo.com/13470805
	Jožin z bažin|http:&#47;&#47;www.youtube.com/watch?v=S4aqM_wu6Ns
	[/youtubegallery]

NOTE: If titles are added before videos, these will override titles fetched automatically from services.

= User Feeds =
To set up a gallery that subscribes to a user’s feed, use the following shortcode:
	[youtubeuserfeed user=username service=youtube]

Note that both <code>user</code> and <code>service</code> are <strong>required</strong> for the feed to work. Service is either youtube or vimeo.

Optional attributes are maxitems=x where x is the number of items to fetch:

	[youtubeuserfeed user=username service=youtube maxitems=4]

= Overrides =
Overrides are a way of suppressing the default settings of the plugin, with attributes of your own.

Override columns with <code>cols=x</code>, where x is the number of thumbs per row:

	[youtubegallery cols=8]

Override thumbnail width with <code>thumbwidht=x</code>, where x is a pixel value:

	[youtubegallery thumbwidth=100]

Fetch titles from service with <code>autotitles=fetch</code> (or <code>autotitles=false</code> to not fetch):

	[youtubegallery autotitles=fetch]

And, of course, you can combine them: 
	
	[youtubegallery cols=8 thumbwidth=100 autotitles=false]

These are also applicable for the <code>[youtubeuserfeed]</code> shortcode.

= Supported Video Services =
Supported services are YouTube and Vimeo. Other services might be added, but a requirement is that they can deliver video streams in a HTML5-compatible format.

Note that it is possible to combine videos from both services in galleries.

== Frequently Asked Questions ==

= Shadowbox/Fancybox/Thickbox doesn't work properly. What's wrong? =

Check if your current Theme has both <code>wp_head()</code> in header.php and <code>wp_footer()</code> in footer.php. Both are usually required for these scripts to function properly. Also note that some plugins aren't buddies and create conflicts with each other; try disabling the plugins for the effects you don't use.

= Thumbnails aren’t working. What’s up? =
From version 2.0, <strong>YouTube SimpleGallery</strong> is employing [Timthumb](https://code.google.com/p/timthumb/) to crop/resize thumbnails. This is mostly due to YouTube and Vimeo having a different aspect ratio on their thumbnails. Timthumb may cause issues on certain webservers. If thumbnails are not showing up in your galleries, the cause might be a conflict between Timthumb and your webhost. Here are some things to try:

* Try setting permission (CHMOD) to 777 on the folder<br /> <code>wp-content/plugins/youtube-simplegallery/scripts/chache/</code>
* Make sure <code>index.html</code> in the cache-directory has permissions set to 666. The parent folder should not have permissions higher than 644.
* Read more at [TimThumb Troubleshooting Secrets](http://www.binarymoon.co.uk/2010/11/timthumb-hints-tips/)
* If none of these tips help, try disabling Timthumb under Thumbnails (the bottom setting). This will result in different thumbnail sizes between YouTube and Vimeo.

= Can I add a gallery to my Theme files, outside the Loop? =
If you wish to add a gallery in a part of the Theme that is outside [The Loop](http://codex.wordpress.org/The_Loop) and/or not within a Widget, you can use the [do_shortcode()](http://codex.wordpress.org/Function_Reference/do_shortcode)-function.

	&#60;?php echo do_shortcode('[youtubegallery]
	http:&#47;&#47;www.youtube.com/watch?v=cRdxXPV9GNQ
	http:&#47;&#47;vimeo.com/13470805
	http:&#47;&#47;www.youtube.com/watch?v=jJK-G9-dLzw
	http:&#47;&#47;www.youtube.com/watch?v=S4aqM_wu6Ns
	http:&#47;&#47;vimeo.com/68180971
	[/youtubegallery]'); ?&#62;


Make sure to keep the linebreaks!

= I got an amazing idea for a great feature! Can you implement it? Pretty please? =

The <strong>YouTube SimpleGallery</strong> is in constant development. A lot of features has been added since it's birth, many of them requests, wishes and ideas from the users. If you got an idea, don't hesitate to share it on the [plugin website](http://wpwizard.net/plugins/youtube-simplegallery/).
		
= My problem's not listed here! OMG! What do I do? =

Don't panic! The WordPress community is the best bunch of people in the world. Try posting your problem/question on the [plugin website](http://wpwizard.net/plugins/youtube-simplegallery/) or in the [WP forums](http://wordpress.org/tags/youtube-simplegallery). You'll probably get help in a jiffy!
		
== Screenshots ==
1. Settings let's you set width and height of embedded video, chose if Thickbox or Shadowbox should be used (note: width and height is only necessary if Thickbox or Shadowbox is active), and use included CSS.
2. The gallery will show on your page like this
3. If Thickbox or Shadowbox is active, the embedded video will show in a box on your site

== Changelog ==
= 2.0.6 =
* Added options to turn off error reporting and YouTube API
* Bug-fix for titles when autotitles = off

= 2.0.5 =
* Added error reporting if video service reports video missing/broken
* General bug-fixes & code improvement

= 2.0.4 =
* Improved employment of dynamic styles; works better with sites running cache-plugins
* General bug-fixes & code improvement

= 2.0.3 =
* Added option to disable timthumb
* Bug-fix for built-in styles

= 2.0.2 =
* The missing version!

= 2.0.1 =
* Bug-fix for Widgets drag & drop freeze-up.

= 2.0 =
* Merging of YouTube SimpleGallery and Vimeo SimpleGallery.
* Major refactoring of codebase.
* Complete refurbishment of Options Page.
* Automatic retrieval of titles from video service.
* User-defined attributes in shortcode overrides default settings.
* New shortcode: [youtubeuserfeed user=username service=youtube|vimeo]
* Bug-fixes for broken thumbnails, and Widgets not updating.

= 1.6.1 =
* Quick fix for new oEmbed in WP 3.3.

= 1.6 =
* Added option for Play-button on thumbnails.
* Bug-fix for HTML/links in titles/descriptions.

= 1.5.1 =
* Minor bugfix to conform with new oEmbed in WP 3.1.2.

= 1.5 =
* Added option for thumbnail size
* Added option for columns w/breaking rows
* Added style option for titles

= 1.4.1 =
* Fixed compatability issue with PHP 5.3.5.

= 1.4 = 
* Added support for Fancybox.
* Added option for autoplay on click.
* Changed all embedding to HTML5 compliant. 
* Remodeled Settings page.

= 1.3 =
* Fixes broken thumbs.
* Fixes broken Thickbox.
* Added HD option.
* Minor bug fixes.

= 1.2 =
* Fixes bug with broken thumbs and videos when adding titles. 

= 1.1 =
* Added option to open links in new window/tab when going directly to YouTube.com

= 1.0 =
* 1st official release. 
* Option for Shadowbox JS added. 
* Bugfixes: Fixed broken thumbnails. Fixed URIs with special characters. 

= 0.4.1 BETA =
* Minor bugfix.

= 0.4 BETA =
* Fixed issues with WP's auto embedding of YouTube-URIs, introduced in WP 2.9. 

= 0.3.1 BETA =
* Minor bugfix.

= 0.3 BETA =
* Fixed errors when showing several galleries to one Page, or when showing Home or Archives with galleries in multiple Posts.

= 0.2 BETA =
* Option to include titles/description added

= 0.1 BETA =
* First version

== Upgrade Notice ==
= 2.0.6 =
* Added options to turn off error reporting and YouTube API

= 2.0.5 =
* Added error reporting if video service reports video missing/broken

= 2.0.3 = 
Fixes single column galleries after update, and adds option to disable timthumb.

= 2.0 =
NEW FEATURES: It is now possible to combine links from YouTube and Vimeo in the same gallery. Automatically fetch titles from video service. User-defined attributes in shortcode overrides default settings. NEW SHORTCODE: Output a gallery from a user’s video feed!

= 1.6 =
Added option for Play-button on thumbnails. Bug-fix for HTML/links in titles/descriptions.

= 1.5.1 =
Fixes broken thumbs with WP 3.1.2.

= 1.5 =
YouTube SimpleGallery now has options for thumbnail size and columns. 

= 1.4 =
YouTube SimpleGallery now has support for multiple Widgets. 