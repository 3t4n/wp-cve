=== WP Performance Pack ===
Contributors: greencp
Tags: performance, image resizing, gettext, disable, cdn
Requires at least: 4.7
Tested up to: 6.0.2
Requires PHP: 5.3
Stable tag: 2.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Boost WordPress performance: Faster localization, (on the fly) dynamic image resizing and CDN support for images.

== Description ==

WP Performance Pack is your first choice for speeding up WordPress core the easy way, no core patching required. It features options to improve localization performance and image handling (faster upload, reduced webspace usage). Combined with CDN support for images, both on Frontend and Backend, this offers similar image acceleration as [Jetpack's Photon](http://jetpack.me/support/photon/).

= Features =

**Improve localization performance**

Translating WordPress is slow and uses much memory because by default WordPress loads all translations upon each request. WPPP improves performance and memory usage by using native gettext or dynamic loading of translations. If an object cache is installed optional caching improves performance even further.

* Use of PHP gettext extension if available.
* Dynamic loading of translation files, only loading and localizing used text.
* Disable Backend localization while maintaining frontend localization (with optional override by user)
* Caching of translations to further improve performance. A persistent object cache has to be installed for this to be effective.

**Improve image handling**

Create intermediate image sizes on demand not on upload. 

* Don't create intermediate images on upload, instead dynamically create intermediate images when accessed.
* Choose to save intermediate image sizes or to recreate them upon each access. Created images can be saved into a folder different from the original images folder.
* Use EXIF thumbnail (if available) as source for thumbnail images. This improves memory and cpu usage as the source for the thumbnail is much smaller.
* Adjust quality settings for intermediate images.
* Regenerate Thumbnails integration: Hook into the thumbnail regeneration process to delete existing intermediate images. Supported plugins: [Regenerate Thumbnails](http://wordpress.org/plugins/regenerate-thumbnails/), [AJAX Thumbnail Rebuild](http://wordpress.org/plugins/ajax-thumbnail-rebuild/), [Simple Image Sizes](http://wordpress.org/plugins/simple-image-sizes/)

**Disable WordPress default widgets (BETA)**

*Really* disable WordPress' default widgets. Other plugins which allow you to disable WordPress default widgets only hide them, but the widgets' files will still be loaded. WPPP really removes the widgets: Files of disabled widgets won't get loaded.

**CDN support**

* Serve (dynamically generated) images through CDN. Applies to all images uploaded via media library both on frontend and backend. No need to save thumbnails locally.
* Fallback to local serving if CDN fails to return a valid response.
* Dynamic image links: Image urls are generated dynamically when displaying post content.
* Supported CDNs: CoralCDN, MaxCDN, Custom

**Change or deactivate WordPress features**

* Disable header elements, such as generator name, feed links and more
* Change heartbeat settings
* Disable edit lock
* Disable emoji support
* Use persistent database connection

== Screenshots ==

1. Enable only required modules. (v2.0)
2. Improved image handling, simple view (v.20)
3. Improved image handling, advanced view (v2.0)
4. Improve localization performance, advanced view (v2.0)
5. Change or disable WordPress features, advanced view (v2.0)
6. Debug Bar integration (v1.0)
7. MO-Dynamic benchmark: Comparing front page of a "fresh" WordPress 3.8.1 installation with active apc cache using different configurations. As you can see, using MO-Dynamic with active caching is just as fast as not translating the blog or using native gettext. Benchmarked version 0.6, times are mean of four test runs measured using XDebug.

== Installation ==

**Requires PHP >= 5.3 and WordPress >= 4.7**

* Download, install and activate. Usage of MO-Dynamic is enabled by default.
* Gettext support requires PHP Gettext extension and the folder *wp-content/wppp/localize* must be writeable for php.
* Caching is only effective if a persisten object cache is installed
* Debugging requires [Debug Bar](http://wordpress.org/plugins/debug-bar/) to be installed and activated

== Frequently Asked Questions ==

= How do I check if caching works? =

Caching only works when using alternative MO implementation. To check if the cache works, activate WPPP debugging (requires [Debug Bar](http://wordpress.org/plugins/debug-bar/)) Plugin). This adds the panel *WP Performance Pack* to the Debug Bar. Textdomains using *MO_dynamic* implementation show information about translations loaded from cache. If no translations are getting loaded from cache, cache persistence isn't working.

= Which persisten object cache plugins are recommended? =

Any persisten object cache will do, but it has to be supported in your hosting environment. Check if any caches like APC, XCache, Memcache, etc. are installed on your webserver and select a suitable cache plugin respectively. File based object caches should work always and might improve performance, same goes for data base based caches. Performance gains depend on the available caching method and its configuration.

= Does WPPP support multisite? =

Localization improvements are supported on multisite installations. When installed network wide only the network admin can see and edit WPPP options.
**Image handling improvements are only available if WPPP is network activated**

= What's the difference between Dynamic Image Resizer and WPPPs dynamic images? =

In previous versions, WPPPs dynamic image resizing feature was based on [Dynamic Image Resizer](http://wordpress.org/plugins/dynamic-image-resizer/), at first with only some improvements. The first big change was a completely different way to serve the dynamically created images (using rewrite rules instead of the 404 handler), including support for the latest WordPress features. Since WPPP version 1.8 the way how creation of intermediate images at upload works also changed completely. Dynamic Image Resizer did prevent this by using different hooks called at upload. WPPP now overrides the registered image editors (those didn't exist when Dynamic Image Resizer was written) to only create the necessary metadata. This is way more robust and also works when editing images with WordPress.

According to its author, Dynamic Image Resizer is intended only as a proof of concept. You might say, WPPPs dynamic image feature is the working implementation of that proof of concept.

= Dynamic links broke my site, how do I restore static links? =

Your first try should be the button "Restore static links" in WPPP settigns advanced view. That function will also be executed on deactivation of WPPP.
If any errors occur (please post them in the support forums so I can try to improve the restore function), you can execute the following SQL query manually to restore the static links:

*UPDATE wp_posts SET post_content = REPLACE ( post_content, '{{wpppdynamic}}', 'http://your.base-url/wp-content/uploads/' )*

You have to change the base URL (third parameter of REPLACE) to your uploads URL!

= How localization improvements work =

WPPP overrides WordPress' default implementation by using the *override_load_textdomain* hook. The fastest way for translations is using the native gettext implementation. This requires the PHP Gettext extension to be installed on the server. WPPPs Gettext implementation is based on *Bernd Holzmuellers* [Translate_GetText_Native](http://oss.tiggerswelt.net/wordpress/3.3.1/) implementation. Gettext support is still a bit tricky and having the gettext extension installed doesn't mean it will work. 

As second option WPPP features a complete rewrite of WordPress' MO imlementation: MO_dynamic (the alternative MO reader). The default WordPress implementaion loads the complete mo file right after a call to *load_textdomain*, whether any transaltions from this textdomain are needed or not. This needs quite some time and even more memory. Mo_dynamic features on demand loading. It doesn't load a mo file until the first translation call to that specific textdomain. And it doesn't load the entire mo file either, only the requested translation. Though the (highly optimized) search for an individual translation is slower, the vastly improved loading time and reduced memory foot print result in an overall performance gain.

Caching can further improve performance. When using MO_dynamic with activated caching, translations get cached using WordPress Object Cache API. Frontend pages usually don't use many translations, so for all Frontend pages one cache is used per textdomain. Backend pages on the other hand use many translations. So Backend pages get each their own individual translation cache with one *base cache* for each textdomain. This *base cache* consists of those translations that are used on all Backend pages (i.e. they have been used up to *admin_init* hook). Later used translations are cached for each page. All this is to reduce cache size, which is very limited on many caching methods like APC. To even further reduce cache size, the transaltions get compressed before being saved to cache.

= How dynamic image resizing works =

Images don't get resized on upload. Instead intermediate images and respective meta data are created on demand. WPPP extends all registered image editors to prevent creation of intermediate image sizes by overriding the *multi_resize* function. As the classes get extended dynamically this should work with any image editor implementation. Serving the intermediate sizes is done using rewrite rules. Requests to none existent intermediate images are redirected to a special PHP file which loads only a minimum of necessary WordPress code to improve performance. Redirection is done via htaccess. If the requested file does exists it is served directly.

When a none existend image is requested WPPP checks if the requested image size corresponds to a registered image size (either one of the default sizes "thumbnail", "medium" or "large" or any other sizes registered by themes or plugins). This check also tells WPPP if to crop the image while resizing. Only if this check passes the intermediate image is created. This prevents unwanted creation of thumbnails.

= How does WPPPs disable widget feature differ from other plugins =

Most other plugins which allow you to disable widgets just unset the respective widgets in WordPress' global widget list. This doesn't prevent the widgets' code to be loaded. WPPP disables default loading of widgets completely and only loads those files required to create the enabled widgets. Available widgets are detected when the modules settings page is displayed by scanning the *wp-includes/widgets* folder for any classes extending *WP_Widget*. It only has a small impact on performance and memory usage but many small improvements can become quite big.

== Changelog ==

= 2.5.3 =
* [gettext][mo-dynamic] Normalize linebreaks for correct translation of multiline strings (thanks @madmax4ever)

= 2.5.2 =
* [dynimg] Correct handling of special image sizes like "medium_large" (thanks @madmax4ever)
* [dynimg] Improved rewrite rules (thanks @madmax4ever)
* [dynimg] Better 404 handling (thanks @madmax4ever)
* Removed deprecated call when deactivating the plugin (thanks @madmax4ever)

= 2.5.1 =
* [wpfeatures] Fixed comment entries in admin menu not being disabled correctly

= 2.5 =
* [wpfeatures] NEW! Added option do disable comments and pingbacks
* PHP 8 deprecated fixes
* various small fixes

= 2.4 =
* [dynimg] Some fixes and improvements when using object cache for intermediate image sizes.
* [gettext] Added code page to setlocale calls.
* "Simple view" for options removed.
* jQuery switchButton removed (it didn't work in WordPress 5.5)

= 2.3.3 =
* [dynimg] "Cache folders" for intermediate images aren't created anymore if saving of intermediate images is disabled.
* [dynimg] Improved method for determining local file path for requested images.

= 2.3.2 =
* [wpfeatures] Additional script is deregistered when heartbeat is deactivated
* [mo-dynamic] Some bug fixes (thanks @gr0b1)
* Fixed undefined index on clean install (thanks @jmslbam)
* Cache hint was displayed even if an object cache was installed
* Debugging now works with Query Monitor.

= 2.3.1 =
* [dynimg] Small fix when using new serve method and intermediate images aren't saved into the same folder as the original.

= 2.3 =
* [dynimg] NEW! New method to dynamically create intermediate image sizes added: You can now use WordPress' internal 404 handling as method. This is slower but doesn't require any additional rewrite rules so it should work out of the box with any web server that supports WordPress permalinks (i.e. IIS).
* [jit] Support for WordPress 5.3.2 added.
* Fixed some typos.

= 2.2.6 =
* [gettext] Improved error handling during test for native gettext support.
* [mo-dynamic] Some smaller improvements.

= 2.2.5 =
* Fixed some JavaScript errors in options.
* [mo-dynamic] Fixed PHP notice (thanks @erwinbr)
* Some minor changes and improvements

= 2.2.4 =
* [dynimg] Delete rewrite rules on plugin deactivation (thanks @lucabarelli)
* [dynimg] Bugfix when PHP is not loaded as Apache module (thanks @lucabarelli)
* [dynimg] Dynamically created intermediate image sizes of on upload scaled images now get saved if saving of intemediate sizes isn't disabled (thanks @wimsjohn)

= 2.2.3 =
* [dynimg] Added option "Inherit rewrite rules" for compatibility with e.g. WebP Express
* [dynimg] Some fixes and improvements in writing rewrite rules.

= 2.2.2 =
* [dynimg] Bugfix in overriding existing image editors
* [dynimg] Fixed Regenerate Thumbnails integration (now works with REST API version of Regenerate Thumbnails)
* Some minor changes and improvements

= 2.2.1 =
* [dynimg] Bugfix while editing images
* Reduced plugin base load

= 2.2 =
* [widgets] NEW! Module Widgets allows to *really* deactivate WordPress' default widgets.
* Some smaller fixes and improvements

= 2.1.3 =
* [mo-dynamic] PHP <= 7.0 compatibility fix (thanks @no3x)

= 2.1.2 =
* [wpfeatures] NEW! Disable JQuery migrate
* [mo-dynamic] Some minor tweaks and fixes for PHP >= 7.3
* [dynimg] Settings page adjustments

= 2.1.1 =
* [mo-dynamic] Warning/error with PHP 7.2 removed (thanks @jensmombaerts)
* [mo-dynamic] Fixed plurals translation (thanks @wordpressserg)

= 2.1 =
* [dynimg] Compatible with WordPress 5.3 changes
* [wpfeatures] NEW! Big image scaling added.
* [dynimg] NEW! Option to save dynamically created images into a "cache folder" to keep your upload folders "clean"

= 2.0.5 =
* [dynimg] Bugfix in wp_get_attachment_meta filter
* [cdn] Bugifx in CDN down warning
* [mo-dynamic] Futher performance improvements
* [mo-dynamic] Added improvements by @mte90
* [jit] Support for WordPress 4.8.1 added

= 2.0.4 =
* [jit] Support for WordPress versions prior to 4.7 removed
* Text and translation changes

= 2.0.3 =
* [jit] Bugfix: Some scripts failed to localize.
* [wpfeatures] NEW! Heartbeat frequency added.

= 2.0.2 =
* [mo-dynamic] Bugfix: Translations using context failed.
* text and readme changes

= 2.0 =
It's been quite a while since the last update and meanwhile I reworked many parts of WPPP without releasing any updates or keeping a changelog. So the following is a very incomplete list of changes made since the last released version 1.10.4. Be cautious when using on a multisite installation. Version 2.0 isn't testet on multisite yet.

* [wpfeatures] NEW! New module "WP Features" allows to change or disable WordPress features, e.g. emoji support and edit lock, header elements 
* [dynimg] NEW! EXIF support for ImageMagick.
* [dynimg] NEW! All registered image sizes are supported regardless of image metadata.
* [dynimg] NEW! On upload no intermediate image metadata is created. Only when dynamically created intermediate images are saved to disc, the respective size information is added to image metadata.
* [jit] Added support for WordPress versions 4.7.4, 4.7.5 and 4.8.