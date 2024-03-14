=== Category Featured Images Extended ===
Contributors: CK MacLeod
Tags: thumbnail,featured image,category featured image,categories,CK MacLeod
Donate link: http://ckmacleod.com/wordpress-plugins/#donate
Requires at least: 3.5.0
Tested up to: 4.8.1
Requires PHP: 5.6
Stable tag: trunk
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt


Set images for categories and tags, especially for fallback post thumbnails or featured images.

== Description ==

Category Featured Images Extended (CFIX) can ensure that posts, category archive pages, widgets and other elements of your site will always display a thumbnail or featured image\* when called upon to do so by themes, templates, and plugins. 

If a featured image has been individually set for a post, it will be used. If a featured/thumbnail image has not been set for a post, CFIX will first try to supply an image from one of the post's categories or tags. It will use a Yoast SEO "Primary" Category's image if available. If no category or tag image is found, the plugin will look for a parent category with an image. Finally, if no linked or related post, category, tag, or parent category image is found, a particular fallback tag or category image can be used if set as "global fallback." 

This plugin was initially based on ["Category Featured Images"](https://wordpress.org/plugins/category-featured-images/) (CFI) by Mattia Roccoberton. If you already have been using CFI, when you install this extended version, your already saved category image settings will be preserved and copied.  

In CFI

* If a post does not have its own featured image, a category featured image will be used if available.
* If a post has more than one category with a featured image, the first available category image will be used.
* If none of the post's categories has a featured image, a parent category's featured image will be used if available.

In CFIX

* (New since 1.2) You can also set tag images. 
* You can designate categories or tags whose images will be avoided: This feature may be helpful when numerous posts use the same general category, and you wish to force the use of a more specific image.
* You can also designate a category or tag whose image will be used as a last fallback when no regular featured image, category image, or parent category image is available. This feature may be helpful especially if your installation features many top-level categories, and you either do not wish to find images for each and every one, or you just haven't done so yet. (One way to combine this feature and the previous one would be to designate the same category for *both* avoidance and last resort: The plug-in would always look for a more specific, not-to-be-avoided category, but fall back to the more general category otherwise.)
* When a post has multiple category or tag images available, either the most recently added category or tag will be used. (This behavior is modifiable via hook or filter). A Yoast SEO Primary Category's image will be used if available. 

NOTE: *IF UPGRADING FROM CATEGORY FEATURED IMAGES, DE-ACTIVATE IT TO AVOID CONFLICTS*

As a further precaution, do not uninstall CFI completely until you are sure that CFIX is working as expected for you.

\* The terms "thumbnail" and "featured" are used somewhat interchangeably in WordPress, even though many featured/thumbnail images will be displayed at much larger than thumbnail size. 

== Installation ==

1. Install and activate the plugin
2. DE-ACTIVATE CATEGORY FEATURED IMAGES IF ACTIVATED
2. Go to "Posts/Categories"
3. Edit a category
4. Set the category featured image
5. Go to "Settings/Category Featured Images Extended" to set fallback options. 

== Frequently Asked Questions ==

= 1. Why Aren't My Fallback Images Showing Up on a Few/Some/All Posts? =

Assuming that you've cleared any caches that might be affecting display, and that you've properly added the relevant category images, one possibility is that your post data ("Post Metadata") was corrupted or incompletely inserted during a restore, import, or other operation, and that WordPress wrongly thinks that your posts have thumbnail or featured images when they don't really. Many relatively well-tested, widely and even very widely in use WordPress plug-ins and functions will produce this "thumbnail false positive" behavior. 

CFIX has been written to provide a thumbnail image even in such cases, but please [contact the developer](http://ckmacleod.com/wordpress-plugins/category-featured-images-extended/support/) if you encounter such an outcome, as the plug-in is still new, and use cases are still being assembled. For instructions on Flushing Bad Thumbnail Data see [CFIX Advanced Topics](http://ckmacleod.com/wordpress-plugins/category-featured-images-extended/advanced/).  

= 2. Is CFIX causing a performance hit at my site? =

CFIX WILL add some overhead, especially if your layout features large numbers of posts calling for thumbnails or featured images that have not been set, requiring the plug-in to work overtime to find fallbacks. Slowed page loads should be mitigated with proper use of good caching applications - which you ought to be using in any event, especially if utilizing image-rich multi-post displays, and depending on the plug-in to provide a large number of substitute images. 

== Screenshots ==
1. Settings Page
2. Edit Category Page
3. Posts Archive Before CFIX
4. Posts Archive After CFIX

== Upgrade Notice ==

= 1.0 =
* First Version of the Plug-In

= 1.0.1 = 

* Fixes: Fixed Admin Footer Function, Fixed Installation/Uninstallation Error Message, Corrected Some Typos

= 1.1 =

* Fixed errors preventing correct exclusion/parent image fallback in some configurations. Now also avoids "has thumbnail" false positives. 

= 1.2 = 

* Fixes serious bug inadvertently created in 1.1 - so somewhat critical upgrade. (Really sorry about that!)

= 1.2.1 = 

* Sort order correction: REALLY use most recent category image available.

= 1.3 =

* Added option to set tag images as well as categories. Also: significant performance improvements.

= 1.31 =

* Removed problematic "thumbnail false positive" detection - contact developer or consult documentation if a problem. 

= 1.32 =

* Variable initialized empty ($fall_cat_id); proper js script localization 

= 1.33 =

* Avoid errors in deprecated (pre-5.5) versions of PHP.

= 1.34 =

* Avoid PHP Warnings for illegal offset when no global fallback category has been set; tested in WP 4.7

= 1.4 = 

* Special transitional version distributed directly.

= 1.5 =

* Can use Yoast SEO Primary category for image if available, further streamlined, tested in WP 4.8.1

= 1.51 =

* Fixed transposition error affecting treatment of excluded categories; minor code cleanup

= 1.52 =

* Fixed error for users employing function "tag"

== Changelog ==

= 1.52 = 

* Include 'wp-admin/includes/plugin.php' so that function tag does not produce error - thanks to Emiliano Costanzo!

= 1.51 =

* Fixed parameter transposition error in category-featured-images-extended.php affecting excluded categories vs. Yoast Primary Category

* Fixed offset non-definition throwing PHP Notice

= 1.5 =

* Option to use Yoast SEO Primary category for image if available. Streamlined.

= 1.4 =

* Special transitional version (use Yoast SEO if present)

= 1.34 =

* Avoid PHP Warnings for illegal offset when no global fallback category has been set - now tests whether value has been set.

= 1.33 =

* Corrected syntax to avoid "function return value in write context" errors in deprecated (pre-5.5) versions of PHP.

= 1.32 =

* Added variable definition to prevent pesky undefined warning on activation in some installations; fixed js script localization 


= 1.31 =

* Removed problematic "thumbnail false positive" detection for restoration as future option.

= 1.3 =

* Tags options
* Performance-related enhancements

= 1.2.1 =

* Sort order correction.

= 1.2 =

* Fixes serious bug created in 1.1 - so somewhat critical upgrade if already upgraded to 1.1

= 1.1 =

* Exclusion/Parent Category fallback corrections; avoid "has thumbnail" false positives. 


= 1.0.1 =
* Maintenance: Typos, Uninstall Error, Admin Footer

= 1.0 =
* First Version in WordPress Repo

== Additional Info ==

= Still to Come =

1. Thumbnail and Featured Image Fallback: A plugin with all CFIX capabilities as well as options to use other images (from post content or as uploaded) for fallback purposes.
1. Supplementary Utilities: To handle thumbnail "false positives" (a problem for some imported or restored archives) or flush bad thumbnail data.

Check the [CFIX home pages](http://ckmacleod.com/wordpress-plugins/category-featured-images-extended/) for additional background, examples, documentation, and usage tips... or to [contact the developer directly](http://ckmacleod.com/wordpress-plugins/category-featured-images-extended/get-support/).

= Thanks! =

All gratitude to Mattia Roccoberton for the basic code for his original Categorey Featured Images plug-in, which I had been using for years before I got around to extending it. Thanks to John Prusinski for recommending the Yoast SEO modification,  encouraging me to implement it, and even throwing in a tip via Paypal! Thanks also to all of the developers and everyday code-hackers, far too numerous to name, upon whose work I have depended. 

