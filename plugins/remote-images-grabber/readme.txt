=== Remote Images Grabber ===

Plugin page: http://andrey.eto-ya.com/wordpress/my-plugins/remote-images-grabber
Tags: image, images, upload, gallery, galleries, grabber
Requires at least: 2.8.6
Tested up to: 4.8.2
Stable tag: 0.6
Contributors: andreyk
License: GPLv2 or later

Fetches images from an URL or a piece of html-code, saves them directly into your blog media directory, and attaches to the appointed post.

== Description ==

Use this plugin instead of tiresome saving images from the internet and uploading them to your blog. You learn the value of this grabber e.g. when you need to move your old non-WordPress site with many pictures to the WordPress-based.

== Installation ==

1. Upload `remote-images-grabber` folder to the plugins directory, usually `wp-content/plugins` (or use the native wordpress plugin installation interface).
2. Activate the plugin through the 'Plugins' menu in WordPress, then 'Images Grabber' link in the Mediafiles submenu and 'Images Grabber' tab in a post editor upload box appear.

== Frequently Asked Questions ==

= How can I grab images in case they are represented with relative path (no http: in SRC or HREF) in HTML? =
Fill in the Base URL field. Sample: to grab http://example.com/some-dir/files/my-image.png which is described as &lt;img src="files/my-image.png" on http://example.com/some-dir/some-page.html you should add http://example.com/some-dir/ into the Base URL field.

= I enter an URL of a page that has pictures but the plugin grab nothing. =
Look through HTML of that page, maybe it is a case described above.

= How to use the plugin? =

1. On the 'Images Grabber' page in your wordpress admin panel, specify the post ID where images should be attached to (if such post doesn't exist, images stay unattached), paste the list of images URLs you wish to grab (separated by spaces or one link by line) or simply paste a piece of html-code, and push 'Go!' button. That's all! Or, instead of the above, you may put into the next text field an URL of a web page that links to images.
2. The 2nd way is to select 'Images Grabber' tab in an upload box, then grabbed images are being attached to the currently edited post automatically. 

I recommend to use [`[gallery]`](http://codex.wordpress.org/Gallery_Shortcode) or other gallery shortcode in the post where you attach images, then they will appear in the post automatically.

The plugin has no options but one can specify some parameters before running grabber script: minimum and maximum file size, grab images from <code>&lt;img src=</code> and <code>&lt;a href=</code> html-tags or only URLs listed as plain text, and base URL for images presented as relative paths.

== Other Notes ==
The plugin recognizes images by the presence (jpg|jpeg|gif|png) in URLs.

If you like this plugin please vote for it.

Please feel free to contact me with your questions or suggestions.

== Changelog ==

= 0.6 =
* more changes in recognizing image URLs (thanks to Thomas who reported a bug);
* base URL field value is kept in cookies now and filled in automatically until you change it or close your browser.

= 0.5.6 =
* added: recognition of square brackets [] in image URLs;
* added: autofill of base URL field.

= 0.5.5 =
* fixed: php notice when WP_DEBUG is on
* added: https requests replaced with http 

= 0.5.4 =
* fixed bugs: ignoring image URLs containing "+" character and error on long urlencoded file names. Thanks for reports to Ibn Al Gnoub.

= 0.5 =
* now grabber works from a tab in the upload box and attaches grabbed files autamatically to the post you are editing;
* lower file size limit added (ignoring icons etc.);
* base URL for grabbing images from relative paths;
* some minor bugs corrected.

= 0.4 =
* Now the plugin can work not only with lists of URLs but with any piece of html-code (it's useful when a html-page given by it's address contains too many links to unnecessary images).
* Options to grab images from <code>&lt;A href=</code> and <code>&lt;IMG src=</code> (one or both) can be turned off.

= 0.3 =
* WPMU compatibility added (get_space_allowed and fileupload_maxk check). Tested on WPMU 2.8.6.
* Form action parameter and plugin textdomain corrected.

= 0.2 =
* Now can handle URL of pages linking to images.

= 0.1 =
* It's simple but it works.

== Screenshots ==

1. 'Images Grabber' tab in an upload box.
