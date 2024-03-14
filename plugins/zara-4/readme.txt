=== Zara 4 Image Compression ===
Contributors: Zara 4
Tags: Compress Image, Image Optimizer, Image Compression, Optimize Images, Smaller Images, jpeg, png, gif, SEO, smushit, smush.it, compressor.io, kraken, kraken.io, kraken-image-optimizer, tinypng, tinyjpeg, pngquant, jpegmini, ewww, pagespeed, pagespeed insights, sitespeed, optimise gif, optimize gif, optimise jpg, optimize jpg, optimise jpeg, optimize jpeg, optimise png, optimize png, optimise animated gif, optimize animated gif, improve pagerank, google pagerank, faster loading times, faster website, improve page speed
Requires at least: 3.0.1
Tested up to: 5.1
Donate link: https://zara4.com
Stable tag: 1.2.17.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html


Compress your images by up to 90% and make your website load faster. Improve your SEO. Reduce your bandwidth.



== Description ==

Optimise and compress your images automatically with [Zara 4](https://zara4.com "Zara 4").

By optimising, resizing and compressing photographs on your website, you can dramatically reduce the amount of data visitors to your website download.

Less data to download equals faster loading pages, reduced bandwidth usage and less storage space on your servers.

Zara 4 combines intelligent image optimisation and enhanced compression techniques to deliver images that display faster.




= Features =

* Automatically optimise images as soon as you upload them. You can select which thumbnails you want to be processed.
* Existing images can be compressed individually or batch processed by using the bulk action 'Compress with Zara 4' from the WordPress Media Library.
* Restore your original images at any time. Your original uncompressed images are preserved but hidden from your Media Library.
* All image compression is handled by Zara 4 servers. Your servers aren't placed under extra load processing images.
* You can use your Zara 4 API credentials on an unlimited number of sites or blogs.
* View your usage from WordPress. Account usage is shown as graph from your Zara 4 Settings page.
* This plugin requires access to cURL to communicate with the Zara 4 API.
* No compilation is required. No additional binaries are required. This plugin does not require root access
* Supports JPEG and PNG images up to 16MB.



= Getting Started =

1. Install the Zara 4 WordPress plugin into your site.
2. [Register](https://zara4.com/auth/register "Zara 4 registration") your free account at zara4.com.
3. Obtain your account [API credentials](https://zara4.com/account/api-clients "Zara 4 API Clients") to connect you to Zara 4.
4. Copy your API credentials into the Zara 4 settings page on your WordPress.



= Plans and Pricing =

Get started with Zara 4 for free! Simply install the plugin and [register](https://zara4.com/auth/register "Zara 4 registration") now.

We offer various [pricing plans](https://zara4.com/pricing "Zara 4 Pricing") suitable for all users big or small.

Need more? If you are likely to process more than 6000 images per month, [contact us](https://zara4.com/contact-us "Zara 4 Contact") for a customised quote. We can provide maintained dedicated servers to meet your needs.

Worried about privacy? We also provide maintained "on site" servers that can run from inside your internal network. [Contact us](https://zara4.com/contact-us "Zara 4 Contact") for more information.






= Contact us =

If you have any problems / questions please contact us at support@zara4.com and we'll do our best to help.
Any feedback or suggestions for improvement / feature requests are always welcome.

* Website: https://zara4.com
* Email: support@zara4.com






== Installation ==

= Manual Installation =

1. Download the Zara 4 plugin.
2. Upload the `zara-4` folder to the `/wp-content/plugins/` directory of your WordPress.
3. Activate the Zara 4 plugin from the 'Plugins' page in WordPress.
4. [Register](https://zara4.com/auth/register "Zara 4 registration") your free account at zara4.com and obtain your API credentials.
5. Enter your [API credentials](https://zara4.com/account/api-clients "Zara 4 API Clients") on your WordPress under 'Settings -> Zara 4'
6. New images will be automatically optimised, or optimise existing images from your Media Library.





== Screenshots ==

1. This screenshot shows the WordPress Media Library with the two columns added by Zara 4. From here you can optimise and restore individual images.
2. This screenshot shows the Zara 4 Settings page. From here you can enter your API credentials





== Changelog ==

= 1.2.17.2 =
* Update restore original image method to restore each image individually.
* Extend maximum execution time for finding uncompressed images to 300 seconds (5 minutes).

= 1.2.17.1 =
* Bug fixes.

= 1.2.17 =
* Remove legacy status tab from settings view.
* Add new 'management' tab to the settings view.
* Add to management tab - the ability to restore all compressed images to their original uncompressed images (if a backup was kept).
* Add to management tab - the ability to compress all uncompressed images.
* Adds 'please wait' modal which is displayed during potentially slow operations.


= 1.2.16.1 =
* Bug fix for improved compatibility.

= 1.2.16 =
* Update asset loading to use plugin version rather than WordPress version. Acts a cache-buster between versions.

= 1.2.15.1 =
* Update WordPress compatibility to 4.9.2

= 1.2.15 =
* Extend statistics to show how many images are compress/uncompressed/excluded.

= 1.2.14 =
* Add dashboard widget.

= 1.2.13.1 =
* Revise factory reset to prevent potential loop

= 1.2.13 =
* Add factory reset option

= 1.2.12 =
* Extend to prevent test credentials being used.

= 1.2.11 =
* Introduce 'Sign up with GitHub' feature.

= 1.2.10 =
* Extend settings page to add status tab with region status map.

= 1.2.9 =
* Extend to add image compression controls to WordPress media page when in grid mode. Controls added to image modal.

= 1.2.8 =
* Introduce 'Sign up with Facebook' feature.

= 1.2.7 =
* Introduce 'Sign up with Google' feature.

= 1.2.6 =
* New database structure to try and fix primary key too large bug.

= 1.2.5 =
* Extend so 'compress all' feature (displayed at top of media page) can be enabled/disabled from settings page. Upgrade setting page.

= 1.2.4 =
* Bug fix - ensure tables are always created using 'plugins_loaded' hook instead of plugin activation hook.

= 1.2.3 =
* Remove namespacing and use long class names to prevent any potential conflicts. Also protect against duplicate class declaration.

= 1.2.2 =
* Namespacing conflict bugfix.

= 1.2.1 =
* Major restructure.

= 1.2.0 =
* Introduce new option to maintain EXIF data. Moving towards advanced compression options.

= 1.1.18 =
* Revise fallback settings storage method to ensure it is always engaged to override database caching bug. Fixes compression sizes selection bug.

= 1.1.17 =
* Fix bug in clear settings. Add ensure Zara 4 settings are not autoloaded and the cache is cleared.

= 1.1.16 =
* Introduce debug info modal to the settings page. Button in top right will show server set up information.

= 1.1.15 =
* Update settings storage to provide error message if there is a problem with server write permissions.

= 1.1.14 =
* Expand settings storage to provide a fallback method of saving to a local file. Multiple small bug fixes to eliminate warnings.

= 1.1.13 =
* Add admin notice when API credentials are not complete.

= 1.1.12 =
* Extend plugin user-agent signature to include enabled extensions. Helps to debug issues and provide remote assistance.

= 1.1.11 =
* Add error message to settings page, displayed if server does not have cURL installed.

= 1.1.10 =
* Bug fix - Fix optimise now button for scenario where no thumbnails have been generated.

= 1.1.9 =
* Update UserAgent to provide additional data to sever.

= 1.1.8 =
* Bug fix - Remove legacy ApiUtils.

= 1.1.7 =
* Bug fix - update ajax action list of uncompressed images.

= 1.1.6 =
* Bug fix - determining optimised images when back up is deleted. Also fix compress all progress bar.

= 1.1.5 =
* Add delete all back up images functionality.

= 1.1.4 =
* Improve error handling.

= 1.1.3 =
* Add compress all uncompressed images feature - allows user to compress backlog of uncompressed images.

= 1.1.2 =
* Add allowance remaining to settings page. Fix quota running low warning bug.

= 1.1.1 =
* Add ability to disable original image back up, and delete any existing back ups.

= 1.1.0 =
* Upgrade to use new API endpoint https://api.zara4.com

= 1.0.4 =
* Bug fix - add modern CA certificates for backward compatibility with older versions of PHP/cUrl/SSL.

= 1.0.3 =
* Bug fix - correct "Parse error: syntax error, unexpected '[' in /XXXXX/XXXXX/public_html/wp-content/plugins/zara-4/zara-4.php on line 665", caused by incompatibility with PHP versions prior to 5.4.

= 1.0.2 =
* Bug fix - corrects optimise bug when auto optimisation is disabled.
* Bug fix - deletes optimised images when user deletes image.

= 1.0.1 =
* Bug fix - do not process thumbnails when API credentials have not been provided.
* Add link to API sign up page on Zara 4 Setting page, when API credentials have not been provided.

= 1.0.0 =
* Initial version.
* Integrates with Zara 4 API, currently supports JPEG and PNG image types. (GIF coming soon).
* Automatically optimises newly uploaded images (including generated thumbnails).
* Existing images can be optimised from the WordPress Media Library.
* Original images (unoptimised) can be restored from the WordPress Media Library.
* Allows selection of which images are optimised from 'Settings -> Zara 4'






== Frequently Asked Questions ==

= Can I try Zara 4 for free? =
You can optimise up to **15MB of images** for free every month. You simply need to [register](https://zara4.com/auth/register "Zara 4 registration") for a free account and enter your account API credentials in your WordPress under 'Settings -> Zara 4'.

= How do I get my API credentials? =
You must [register](https://zara4.com/auth/register "Zara 4 registration") a Zara 4 account and then obtain your API credentials [here](https://zara4.com/account/api-clients "Zara 4 API Clients").

= Is there an image file size limit? =
Zara 4 can process images up to **16MB** in size.

= Can I get my original images back? =
Yes. Simply go to your WordPress Media Library and you can restore your original images individually. Please note this will delete the optimised versions of the image.

= What happen if I uninstall the Zara 4 plugin? =
All of the images you have optimised will remain optimised and won't be changed. If you want to restore an image to it's original you will need to reinstall the Zara 4 plugin.

= Can I select which thumbnails are optimised? =
Yes, you can choose exactly which thumbnails are optimised from the 'Settings -> Zara 4' page. Simply select the image sizes you want to be optimised by checking the associated tick box. Zara 4 also supports custom thumbnail sizes.

= Does Zara 4 automatically optimise new images I upload? Can I turn this off? =
Zara 4 will automatically optimise new images you upload to WordPress including selected thumbnails. You can turn automatic optimisation on/off from the 'Settings -> Zara 4' page.

= I can't find the Zara 4 options in my Media Library =
Ensure that the Zara 4 plugin has been installed and the plugin is active. In your Media Library ensure you have selected 'List View' instead of thumbnail view. Each image will have two additional columns containing the original file size and Zara 4 optimisation settings for that image.

= How do I find out how many images I processed this month? =
Your image processing usage is shown as a graph on the 'Settings -> Zara 4' page.