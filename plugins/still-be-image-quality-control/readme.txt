=== Image Quality Control | Still BE ===
Contributors: analogstudio
Donate link: https://www.amazon.jp/hz/wishlist/ls/GGO6O4H4I4M?ref_=wl_share
Tags: optimize, image, webp, cwv, speedup, exif
Requires at least: 5.3
Tested up to: 6.4
Stable tag: 1.7.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Control the compression quality level of each image size individually to speed your site up display. It also contributes to improving Core Web Vitals (CWV) by automatically generating WebP.

== Description ==

You can individually set the compression quality of the resized image that is automatically generated when you upload the image to the WP media library.

Setting the optimum compression quality level helps ensure image quality and speed up page display.
The quality level can be examined while checking in real time.

The EXIF data deletion function keeps GPS location information confidential and contributes to improved security.

This plugin will automatically generate the same quality and more compressed WebP if your server supports WebP.
And if user's browser supports WebP, deliver automatically WebP instead of JPEG or PNG. (No need to rewrite the URL of the <img> tag)
It contributes to improving CWV (Core Web Vitals).

- Set the quality level in the image type (JPEG / PNG / WebP) and size name table.
- Set the default quality level if not set.
- Set the quality level for WebP compression of the original image.

The following can be set as options.

- Guarantee a Secure Filename (Deny a multibyte filename)
- Strip Exif Data
- Autoset Alt from Exif (only JPEG)
- Add a Quality Level Suffix
- Enable Interlace/Progressive
- Enable Generating WebP
- Enable PNG8
- Optimize "srcset" Attribute
- Force Adding the Query String for Image Cache Clear
- Optimize Delay by WP-Cron


### Other functions

In addition, it has the following functions.

- Add Some Custom Image Sizes
- Test Quality Level
- Change of Big Image Threshold
- Reset Settings
- Run Re-Compression
- Auto Re-Compression using WP-Cron


### How to use

After installing and activated, WebP auto-generation and small image compression will be high.
Adjust the settings if necessary and recompress all images.

1. Install
2. Activate
3. Change Settings (if you need)
4. Recompress uploaded images (if you need)

Be sure to make a backup before recompressing.


#### Access to the settings screen

- Setting Screen
- Go to "Image Qualities" 


== Installation ==

1. Enter "Image Quality Control" in the plugin search field in your admin screen.
2. Once you find this plugin, click "Install Now" to install.  
   (Alternative) Upload "stillbe-image-quality-control.zip" directly to your Plugins -> Add New in your admin screen.  
   (Alternative) Upload an unzipped "stillbe-image-quality-control" directory under the "/wp-content/plugins" directory.
3. Activate the plugin through the Plugins menu in WordPress.
4. Leave image optimization to this plugin. Let's enjoy WordPress!!


== Frequently Asked Questions ==

= Does the image loss quality if I change the quality level many times and recompress it? =

No, it will not be lost.
When recompressing, resize and compress from the original image.

= If I generate some WebPs, do I need to rewrite the image URL in the articles? =

There is no need to rewrite.
If user browser supports WebP, .htaccess will automatically deliver WebP instead of JPEG or PNG.

= Should I recompress it? =

Not required.
If you want to compress the uploaded images with the new settings, recompress it.
Recompression is recommended when creating a new WebP. (WebP for uploaded images is not automatically generated)

= The image sharpness of WebP is not good. =

Raise the quality level of WebP.
Test the file size and image quality to decide the quality level.

= Can EXIF data be deleted individually for each item? =

It cannot be done.
You can only specify whether you want to delete all or keep it.

= Can I delete EXIF from uploaded images? =

Yes, you can.
Recompression will delete the EXIF according to the current settings.
However, EXIF that have already been deleted cannot be restored.

= Does disabling the plugin stop the automatic delivery of WebP? =

Yes, it will stop.
If you install '.htaccess' properly in /wp-content/uploads, it can be delivered automatically even after it is stopped.

= Does the setting value of this plugin take precedence over 'jpeg_quality' hook? =

Yes, it has priority. (Version 0.10.9+)
However, it is unstable which one is prioritized up to version 0.10.8.

= How to uninstall? Is there anything I need to do after uninstalling? =

Just deactivate it from the plugins (admin screen) and then delete it.
There is no cache or database, so there is nothing you to do after uninstalling.

= If an image that was of poor quality is recompressed with higher quality, there will be no change in the image. =

It is possible that the browser cache is still active.
Clear the browser cache or update this plugin to Version 1.2.0+. Version 1.2.0+ will clear the cache when there is a change in the image.
However, if the quality of the original image is low, it will not improve over that quality.


== Screenshots ==

1. Quality Level Table
2. Test Quality Level
3. Options
4. Recompress the Uploaded Images
5. Generate WebP Image Automatically


== Changelog ==

= 1.7.1 =

Checked that it works with WordPress 6.4.

= 1.7.0 =

Checked that it works with WordPress 6.3.

= 1.6.0 =

Changed Requests_Transport_fsockopen class to WpOrg\Requests\Transport\Fsockopen class.

= 1.5.2 =

Fixed a bug that caused batch recompression to loop without finishing.

= 1.5.1 =

Fixed a bug that failed to set image quality when the size name was changed after an image size was added.
Changed the specification to display only the number of files instead of displaying the attachment id of the targets in the tab of recompression.

Fixed other minor bugs.

Checked that it works with WordPress 6.2.

= 1.5.0 =

Fixed an error when an empty file is passed during EXIF removal.

Checked that it works with WordPress 6.1.

= 1.4.0 =

Added process to strip EXIF data embeded in images.

= 1.3.0 =

Fixed an error that occurred with PHP 7.2 and older.
Checked that it works with WordPress 6.0.

= 1.2.4 =

Fix an issue where resized images were not generated when uploading WebP that WebP was not automatically generated to replace images in conventional format.

= 1.2.3 =

Fix the text domain in some translation functions.

= 1.2.2 =

Change translation function to wp.i18n.
Adjust the tab control of the setting screen.

= 1.2.1 =

Add an option to optimize delay by WP-Cron. cURL in version older than 7.32.0, decimal values cannot be used for timeout.
Chang the method of obtaining the modification date and time of the query string to clear the image cache.
Fix a bug in Imagick that WebP mime-type was not detected correctly.
Fix a bug in the quality test that the quality level of the original image might not be set to the set level.

= 1.2.0 =

Add an option to add a query string to clear the image cache.
Changed the interlace flag to be set separately for JPEG and PNG.

= 1.1,2 =

Add setting of target conditions for batch conversion.
Checked that it works with WordPress 5.9.

= 1.1.1 =

Fix a bug that toggle options could not be displayed when loading by the setting page opening other than the test image tab in the quality level test.
GD changed the initial value of PNG8 enablement to 'false' because transparent colors are not preserved when converted to PNG8 at sites that use the GD library.

= 1.1.0 =

Supports PNG8. Supports changing toggle options in quality level testing.

= 1.0.0 =

Official Release
Change of the quality levels can be set according to the size of the original image.

= 0.10.9 =

Fix a bug that the setting value of this plugin is not set except for 'medium' size when the quality level is set by 'jpeg_quality' hook.

= 0.10.8 =

Fix a bug that the default quality setting values are not used when the quality setting values are not set.
Add the function to display the saved setting values.

= 0.10.7 =

Increase the priority of the quality level setting for this plugin.

= 0.10.6 =

Fix the quality levels in compression informations table.

= 0.10.5 =

Add the quality level table for site icon.
Add single compression button to the Media Library page (list view).
Update translations.

= 0.10.4 =

Add compression informations to the Media Library page (list view).
Fix a bug that occasionally prevented setting the site icon.

= 0.10.3 =

Show the default value for toggle settings.

= 0.10.2 =

Fix because the deletion of 0.10.1 was not successful.

= 0.10.1 =

Delete the old files that remained when dividing into folders under /includes.

= 0.10.0 =

Tab the setting screen.
Fix some bugs.

= 0.9.1 =

Modulated the processes for extension plugin.

= 0.9.0 =

Fix some bugs.
Add the funcitions of extension plugin.

= 0.8.1 =

Fix a bug that the quality level cannot be set in the quality test.
Add the function to regenerate only one image.

= 0.8.0 =

Add the bellow functions;
1. Add Custom Image Sizes
2. Optimize "srcset" Attribute
3. Big Image Threshold
4. Images are automatically regenerated using WP-Cron

= 0.7.5 =

Compatible with WordPress 5.8.1.
Changed the default values.

= 0.7.4 =

Fix a bug that the setting values cannot reset.

= 0.7.3 =

Fix some bugs.

= 0.7.2 =

Changed the test image preview to an image size that takes into account the device pixel ratio.

= 0.7.1 =

Add the function to test quality level.

= 0.6.1 =

Update the description on the setting screen.

= 0.6.0 =

Compatible with WordPress 5.8. Fixed a bug that the original image WebP was not generated.
Updated to interrupt the recompression process.

= 0.5.3 =

First Release on The WordPress Plugin Directory

= 0.5.0 =

Organized items on the setting page and added explanations. Changed the settings to apply.

= 0.4.0 =

Add a Setting Page & Selected the Editor Changed to Prioritize WebP-enabled Library.

= 0.3.1 =

When Deleting an Attachment, Delete WebP at the Same Time.

= 0.3.0 =

Changed WebP Creation Method to 'cwebp' Utility.

= 0.2.0 =

Add Classes that extends the Core Image Editor and Set the Compression Quality when Creating Resized Images with GD / Imagick.

= 0.1.0 =

Overwrites the All Resized Images with GD functions after 'wp_generate_attachment_metadata' Hook.
