=== Auto Featured Image from Title ===
Contributors: brochris
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=chris@designsbychris.com
Author URI: http://designsbychris.com
Plugin URI: http://designsbychris.com/auto-featured-image-from-title
Tags: featured image, featured images, generate thumbnail, generate thumbnails, text picture, text pictures, automatic featured image, auto featured image, automatically generate featured image, automatically set featured image
Requires at least: 3.5
Tested up to: 6.2.2
Stable tag: 2.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically generates an image from the post title of a new or updated post and sets it as the featured image.

== Description ==

This plugin makes the process of publishing content more simple.

<ol>
  <li>Write your blog post.</li>
  <li><strike>Spend hours searching stock photography websites online for the perfect image to go with your blog post.</strike></li>
  <li>Done!</li>
</ol>

If you don't set a featured image manually, this plugin will automatically generate an image from the post title, excerpt, or content of a new or updated post or page and set it as the featured image. The image will then be included in your theme wherever the featured image for the post or page is called for.

<a href="http://designsbychris.com/auto-featured-image-from-title/">Upgrade to the PRO version for more features and control!</a>!

It's good to have an image in every post and page that you create. It helps for things like search engine optimization, social sharing, and just the attractiveness of your blog. But sometimes it can take longer to find a good image for a particular blog post than to write the post itself.

This plugin simplifies the process of publishing content. It will automatically create a customized image for each post or page that you create. You can select a background image to match the look and feel of your blog, and the plugin will automatically write the title, excerpt, or first 55 characters of the content of a new or updated post or page on top of this background image to create a unique image for each post.

By popular demand, the plugin now has an option to simply set the featured image from the first attached image in a post, without generating a new image.

== Installation ==

1. Upload to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings (optional, but encouraged)

== Frequently Asked Questions ==

= Why doesn't the generated featured image appear at the top of the post? =

That's up to your Wordpress theme. Some themes do this by default, some don't. If yours doesn't, edit your Wordpress theme and insert the following code where you'd like the featured image to appear.

`<?php
if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
  the_post_thumbnail();
}
?>`

= How can I customize the font, colors, and image to match my site? =

You can customize the generated image somewhat via the options page which can be found in under Settings > Auto Featured Image. The <a href="http://designsbychris.com/auto-featured-image-from-title/">PRO version</a> also allows you to upload and use your own fonts and background images.

= When does the plugin create an image for my new blog post? =

When you click "Save Draft" or "Update" or "Publish."

= Can I easily generate featured images for all of my previous posts? =

This is a feature of the PRO version of the plugin. <a href="http://designsbychris.com/auto-featured-image-from-title/">Upgrade to the PRO version</a>!

= Will this plugin overwrite all of the featured images that are already set? =

No, it will only create featured images for posts that do not have featured images set.

== Screenshots ==

1. Admin Settings
2. An example of an automatically generated image
3. An example of an automatically generated image
4. An example of an automatically generated image
5. The image is automatically set as the featured image

== Changelog ==

= 2.3 =
* Added option to keep or remove linebreaks when writing text to images
* Added option to set maximum length of text to write to images
* Replaced certain fonts and images (see <a href='http://designsbychris.com/blog/2016/08/13/using-free-quality-images-and-fonts/'>this post</a>)
* Fixed a bug that created images for new posts even when disable was enabled by default

= 2.2 =
* Added option which allows you to write text to the image or not
* Fixed bug that wrote html tags to the featured image
* Added checkbox to edit screen for pages that allows you to disable the plugin for that page

= 2.1 =
* Added option to disable auto image generation for posts by default
* Fixed bug that could have resulted in blank file names
* Option that sets first image as the featured image now works with external images as well

= 2.0 =
* Added option to simply set the first attached image in a post as the featured image
* Added a check box on the edit post screen that allows you to keep the plugin from generating a featured image for that post
* Fixed bug that generated images when a post was moved to the trash

= 1.9 =
* Fixed "hide notice" link for upgrade notice
* Added a couple background images

= 1.8 =
* Added option to use the first 55 characters of the post's content as the text to write to the generated image
* Changed the default font size to 30, making the plugin run faster, and eliminating timeout errors some users experienced
* Fixed bug that caused the background color to always be black
* Fixed bug which sometimes resulted in the generated image not being set as the post's featured image

= 1.7 =
* Generates an image when saving a post via 'quick edit'
* Changed the way images are named to prevent overwriting files
* Rewrote the code that adds the text, making it faster
* Bug fixes

= 1.6 =
* Added caption, description, and alt texts to the media gallery for the generated images for better SEO capabilities

= 1.5 =
* Fixed a bug caused by deprecated code

= 1.4 =
* Fixed a bug that produced errors regarding missing settings

= 1.3 =
* Added option to choose between using the Post Title or Post Excerpt on the generated image

= 1.2 =
* Added option to enable/disable for posts/pages

= 1.1 =
* Fixed color picker installation error

= 1.0 =
* Initial release

== Upgrade Notice ==

New options and bug fixes