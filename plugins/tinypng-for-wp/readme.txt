=== Plugin Name ===
Contributors: kakashihatake2
Donate link: http://techjunkie.in/tinypng-for-wordpress/
Tags: tinypng, images, pngs, reduce, optimize
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl.html

Reduce/Optimize Image Size by connecting to the TinyPNG API

== Description ==

This is not the official plugin for TinyPNG. Please go to the following link for the official plugin - https://wordpress.org/plugins/tiny-compress-images/

This is a WordPress Plugin used to connect with the TinyPNG.com API and optimize/reduce the size of your PNG file on the go. Its fast and reliable.

Instructions on How to Use:

1. Put in the correct email address and the TinyPNG API Key associated with it. (If you haven't made one yet, you can visit this link to make one - https://tinypng.com/developers )

2. Upload the PNG file using the uploader. As soon as the uploading finishes, the plugin will connect to the TinyPNG API and get you the Optimized image. (The files uploaded and optimized are saved in the TInyPNG folder in the main wordpress media folder. For eg - /wp-content/TinyPNG )

Note :- Because of too much work and less of time, I am not able to work on this plugin much. I asure of you testing it in all Wordpress Platforms, but any updates on its features wont be possible before July. Sorry for the inconvenience.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= 1. How to get the API Key? = 

You can get the API Key of TinyPNG from the following link (An email will be sent with a link. Click on it and you'll be redirected to a page where you'll get the API Key).

= 2. How to check the no. of images left that can be uploaded till the end of the month? = 

In the link ( https://tinypng.com/developers ), You'll see a 'Already Subscribed?' box, write your email address there. An email will be sent, click on the link that you received and you'll be redirected to your subscription page
Note : At first, you get a free subscription that gives you a limit upto 500 images. You can even get more images by paying a small amount to TinyPNG.

The rest is easy to understand. If you still have problems, Contact me at animeonfire@gmail.com . Please write the subject of the email in this form 'TinyPNG: Your Issues/etc'

== Screenshots ==

1. Just upload upto 5 images. Wait for a few seconds till upload finishes. Your done.

== Changelog ==

= Version 0.2 =
1. Removed Email (wasn't required)
1. Fixed compatibility with 4.1

== Upgrade Notice ==

Nothing Upgraded yet.