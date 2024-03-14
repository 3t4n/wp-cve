=== Limit Post Add-On ===
Contributors: Doc4
Tags: limit post, limit text, limit copy, copy, post
Requires at least: 1.5
Tested up to: 6.4.3
Stable tag: 1.4
License: GPL-2.0+
License URL: http://www.gnu.org/licenses/gpl-2.0.txt


== Description ==
Limit-Post is one of the better WordPress post content limiters we have come across, both in terms of usability and size. Developed by labitacora.net Limit-Post provides excellent control over the post character-length and even adds the ability to create a "read more ..." link with a single line of code.

With "Limit Post Add-On" we have expanded on the original plugin to include WordPress' get_the_content tag in order to limit post copy with stripped html tags.

= Plugin URL =
[Limit Post Add-On](https://doc4design.com/limit-post-add-on/)

= Screenshots =
[View Screenshots](https://doc4design.com/limit-post-add-on/)



== Installation ==

To install the plugin just follow these simple steps:

1. Download the plugin and expand it.
2. Copy the limitpost-addon folder into your plugins folder ( wp-content/plugins ).
3. Log-in to the WordPress administration panel and visit the Plugins page.
4. Locate the Limit Post plugin and click on the activate link.
5. Replace the_content(); with the_content_limit(200, "continue..."); or
6. Replace the_content(); with get_the_content(200, "continue...");



== Changelog ==

= 1.4 =
* Updated code to ensure functionality with WordPress 6.4.3+
* Updated Required Headers for readme.txt
* Updated Required Headers for limit-post-add-on.php

= 1.3 =
* Updated code to ensure functionality with WordPress 6.1.1+

= 1.2 =
* Updated code to ensure functionality with WordPress 5.7.1+
