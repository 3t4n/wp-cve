=== Comment Images Reloaded ===
Contributors: wppuzzle, egolacrima
Tags: comment, image, attachment, images, comments, attachments, image zoom
Donate link: https://www.liqpay.com/checkout/wppuzzle
Requires at least: 3.2
Tested up to: 4.5.3
Stable tag: 2.2.1
License: GPLv2 or later 

Plugin allows users to add photos or images to their comments. Позволяет прикреплять фото к комментариям.

== Description ==

**CIR** ([Comment Images Reloaded](http://wp-puzzle.com/comment-images-reloaded/ "Comment Images Reloaded")) allows users to add photos, images or animation to post in comments. 
**The plugin monitors technical issues, which reduce number of queries to the database and the size of the page with hundreds of comments.**

Popular plugin [Comment Images](https://wordpress.org/plugins/comment-images/ "Comment Images") is taken as a basis and free solution of additional tasks is implemented for users of this product on its basis, operating peculiarities of some functionality are redone. 

= Improvements: =
* Uploaded photo is automatically reduced to optimal size and only in this form it is published in comments (height or width of Photo of maximum of 1024 pixels is used by default).
* Plugin algorithm and displaying comments are modified, all these formidably reduced load on server (and consequently on hosting). 
* Correct operation of option is debugged which prohibits to add images in comments for all posts.

= New abilities: =
1. Customization of image size which will be displayed in comments(change at any time — images responds to this option in new and existing comment):
 * Thumbnail — 150х150 pixels
 * Medium — 300х300 pixels 
 * Large — 1024×1024 pixels
 * Full — source image size 
 * Custom sizes are supported 
1. Page with plugin settings is implemented 
1. Limiting of files weight for downloaded custom images
1. Zooming of image in comment by clicking
1. One can change standard inscription above button commentary file selection
1. Output button "Choose file" in any part of comment form using special function
1. Data import function during transition from plugin Comment Images.

All new abilities and improvements are implemented using standard abilities of CMS WordPress.

== Installation ==


= Automatic installation: =

1. Log-in to your WordPress admin interface.
1. Hover over "Plugins" and click on "Add New".
1. Under Search enter Comment Images Reloaded and click the "Search Plugins" button.
1. In results page click the "Install Now" link for "Comment Images Reloaded".
1. Click "Activate Plugin" to finish installation. You're done!

= Manual installation: =

1. Download [Comment Images Reloaded](https://downloads.wordpress.org/plugin/comment-images-reloaded.zip "Comment Images Reloaded") and unzip the plugin folder.
1. Upload `hierarchical-sitemap` folder into to the `/wp-content/plugins/` directory.
1. Go to WordPress dashboard and navigate to "Plugins" -> "Installed Plugins".
1. Activate "Comment Images Reloaded".


== Frequently Asked Questions ==

= How to display field for picture inserting manually? =
Image insert field is automatically displayed after button 'Submit comment'
You can place it in any form place using special functions. To do this:
1. Tick option in plugin settings image insert field (it disables automatic display)
1. Call one of the following functions in your form template in necessary place:
 * To display HTML code: `if (function_exists("the_cir_upload_field")) { the_cir_upload_field(); }`
 * To get variable with HTML code: `if (function_exists("get_cir_upload_field")) { get_cir_upload_field(); }`


== Screenshots ==

1. The default comment form in Twenty Sixteen with image upload form.
1. Comments Dashboard showing image for each comment.
1. Admin page with plugin settings.



== Changelog ==

= 2.2.1 =

* fix: comments were published without admin approval

= 2.2 =

* You can attach more than one file to comment
* Added additional customization for selecting number of uploading files at one time
* Added image import button from plugin Comment Attachment
* Checked work of plugin on version WordPress 4.5.1


= 2.1.4 =
* new: removed column with Comment Images Reloaded image on posts page in admin  
* fix: correct work of auto insert url link for themes without html5 support for comment-list

= 2.1.3 =
* fix: correct working auto url feature

= 2.1.2 =
* fix: changed operation of action `comment_text` for themes which supports html5 comment-list
* new: added functions for manual paste of download field of picture in template of commenting form: - `the_cir_upload_field()` outputs html code field for inserting picture 
* new: added option for tripping automatic typing-out field in form comments 

= 2.1.1 =
* fix: fixed filename error while connecting zoom and connection of style files 
* fix: added validations on `WP_Error` while downloading file 

= 2.1.0 =
* new: added capability to activate image expansion in comment by clicking 
* new: added 'delete image' button on page with list of comments 
* new: added option for setting downloaded file size (maximum value is limited by `php.ini` settings) 
* new: added option for setting text before field of inserting picture 
* new: added author link (with ability to displug its output in options) 
* new: deleted metadata fields with date of attached image during final removal of comments (clear basket) 
* new: metadata of connected comments are found and deleted while deleting image from Media Library 
* fix: while importing from Comment Images:
 * `get_comments()` chooses not all comments but only those which included images 
 * copy of file is not created, just simply ID of existing input is found and its data are used 
 * checked existence of file, specified in metafield comment-image (on file and on `ABSPATH` + url)
 * metadata are not recorded and picture is not moved if file was not found on disc.
* fix: unnecessary characters of its name are deleted before saving file (only Latin letters, figures, dot, underscore and hyphen remain); if there is empty file name after that – it is generated from comment number and random number (from 100 to 900)

= 2.0.3 =
* fixed problem of incorrect displaying custom image sizes in plug-in settings 
* picture is displayed in comments with standard size 'large' by default.

= 2.0.2 =
* fixed data storage (transition from text format on serialized array) 

= 2.0.1 =
* added use of custom image size 
* added function for correct work `comments_array` on all themes 
* fixed file names 
* fixed storage of array data in base 

= 2.0 =
* Initial release **Comment Images Reloaded**

== Upgrade Notice ==

= 2.1.4 =
This version fixes a bag with auto url feature in themes without supporting html5 comment-list
