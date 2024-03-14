=== Gator Cache ===
Contributors: GatorDog
Donate link: http://gatordev.com/gator-cache
Tags: cache, performance, optimize, bbpress, woocommerce, multisite, jetpack mobile
Requires at least: 3.8
Tested up to: 5.0.2
Stable tag: 2.1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A better, stronger, faster page cache for WordPress. Performance that's easy to manage.

== Description ==

Gator Cache is an easy to manage page cache for WordPress. Once installed, it automatically updates new and updated content in your cache. This keeps your website fresh while adding the superior performance advantage of a cache. Key features are as follows:

*   Greatly increases site performance by adding a page cache
*   Automatic update of cache when content is published or updated
*   Automatic update of cache when comments are approved
*   Compatible with WooCommerce, will not cache mini-cart in page
*   Compatible with bbPress, updates when topics, replies, etc are added
*   Compatible with WordPress HTTPS, will cache pages secured by the plugin when applicable
*   Compatible with WordPress Multisite
*   Compatible with Autoptimize
*   Compatible with JetPack Mobile Site
*   Compatible with WPMinifyFix
*   Posts can be cached for logged-in WordPress users by role. You can cache pages for Subscribers, Customers or other roles while skipping the cache for Administrators
*   Http caching supported with Apache and Nginx

== Screenshots ==

1. The Gator Cache management panel.

== Installation ==

1. Upload `gator-cache.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on Settings or the GatorCache menu icon to run the automated setup
4. Click Install to perform the automated installation process
5. Check the "enabled" box and update your general settings

== Frequently Asked Questions ==

= Does GatorCache write to my htaccess file? =

No, GatorCache does not write to your htaccess. However, recommended Apache rewrite rules are provided, based on your settings, that you can copy and paste to your htaccess file to enable http caching.

= How do I delete the entire cache? =

Click on the "Gator Cache" button on your admin toolbar. From the menu below, choose "Purge entire cache". Alternatively, you can navigate to the Gator Cache settings to the Debug tab and use the purge button.

= What is Cache Warming? =

Typically when a cache is refreshed, the cached file and it's contents are deleted. This is what GatorCache does when a post or page is updated. Then when a vistor visits that page, the cache is re-generated on the fly. This works well in most scenarios. However, if your pages are really slow when not cached, you can set GatorCache to "warm the cache". This simply means that when a post is updated and the content is removed from the cache, the post url will be pinged in the background in order to re-generate the cached content. This will prevent any visitors from getting non-cached content and slow-loading pages. Please note that when you purge the entire cache, and have cache warming enabled, that this will not regenerate the cache for the entire site.

== Changelog ==
= 2.1.8 =
* Fix caching issue when Nginx is used as a reverse proxy
* Remove post from cache when transitioned from published (Switch to Draft)
* Verify compatiblity with latest version of WordPress
* Better error checking when cache directory is not found (due to migrations, etc)
= 2.1.7 =
* Compatiblity with recent versions of WooCommerce, will not cache cart, checkout or other pertinent pages
= 2.1.6 =
* WordPress 4.9.2 compatiblity verified
* PHP 7.x compatibilty, fix warning errors
* Fix warning error with JetPack mobile compatiblity
= 2.1.5 =
* Redis Object Cache plugin compatiblity, fix issue with object-cache deletion.
= 2.1.4 =
* Verify compatiblity with WordPress 4.7
* Use ABSPATH for installation directory when installed in document root
= 2.1.3 =
* New feature - refresh the current page in the cache
* New feature - cache purging and page refreshing directly from the admin toolbar
* Update translations, missing text domains added
= 2.1.2 =
* Update translations, change text domain to match plugin slug
= 2.1.1 =
* Update translations, add translation file
* Minor bugfix php notice loading refresh module
= 2.1.0 =
* Verify compatiblity with WordPress 4.6.1
* Verify compatiblity with Autoptimize 2.1.0
* Permissions for multisite changed to activate plugins instead of install plugins
* Taxonomy pages refreshed when posts removed from tag or category
* Bugfix ssl cache handler, add hook for other plugins to modify buffer
* Remove object cache for further development 
= 2.0.12 =
* Object cache flush also flushes local storage.
* Object cache get function compatible with Worpress wrapper function.
* Supress object cache debug stats when in wp cron, or PHP in CLI or command line mode.
* Purge cache updated to purge mobile caches used in conjunction with the WP Mobile Detect plugin.
* Shows the sys_getloadavg with the advanced setting for the load threshold. 
= 2.0.11 =
* Introduces an object cache.
* Refreshes the cache when a post is quick edited.
* Adds compatiblity with WP Mobile Detect plugin and the option to maintian separate mobile caches.
* Adds compatiblity with Autoptimize plugin.
* Introduces an option for specifying a page load threshold where the cache will not expire and regenerate. Uses sys_getloadavg function. 
= 2.0.9 =
* Adds option for caching RSS feeds.
* Adds compatiblity with WPMinifyFix plugin.
* Fixes issue with excluding the home url, "/", in custom settings.
* Verifies compatiblity with WordPress 4.2.2
* Introduces cache warming.
= 2.0.8 =
* Fixes typo in 2.0.7 bugfix. Props @ronangelo.
= 2.0.7 =
* Fixes bug with posts refreshing when comments are added or updated.
= 2.0.6 =
* A better fix for 2.0.5, checks the request uri for directly accessed php files rather than the script name.
= 2.0.5 =
* Fixes compatiblity issue with FORCE_SSL_ADMIN by serving the cache only when index.php is the handler.
= 2.0.4 =
* Fixes potential cache directory permissions issue with http or Apache caching.
* Adds installation verification check for htaccess that protects the cache directory from direct access.
= 2.0.3 =
* Fixes backwards compatiblity with php 5.2. 
= 2.0.2 =
* Fix bug with 2.0.x version auto-refresh of posts. The cache will now be refreshed upon add / update as usual.
= 2.0.1 =
* Minor admin panel UI tweaks
= 2.0 =
* Purge Cache button added to the admin toolbar
* Install in document root directory by default (the cache dir is protected from direct access by .htaccess)
* Improved administration UI
* Bug fix, don't cache xml files, eg sitemap.xml
* Update cache engine to Reo_Classic_CacheLite
= 1.57 =
* Adds automatic updating for Tag Archives
* Adds Support for Caching Feeds
* Adds character-set to content-type header for php cache (advanced-cache.php)
* Bugfix - JetPack Mobile and ssl cache removed when the cache is purged or content is updated
* No caching for txt files such as robots.txt
= 1.56 =
* Add support for JetPack Mobile Site
* Added hook for caching user content
= 1.55 =
* Maintenance release for purging cache and http rules
* Delete the ssl cache, if it exists, when purging cache
* Improve recommended http rules to avoid serving cache for POST and dynamic requests
= 1.54 =
* Maintenance release for new location of tinyMCE ajax loading image in WordPress 3.9
= 1.53 =
* Checks for users with multiple roles, such as BBPress roles, so content is not cached under certain GatorCache user settings
* Compatibility with WordPress 3.9 verified
= 1.52 =
* Resolves conflict with NextGen Gallery buffering
* Allows choice of cache directory location during install
* Fixes bug with certain settings that can display cached pages for logged in users
= 1.51 =
* Adds WordPress Multisite support
* Improved recommended Apache http cache rewrite rules
= 1.48 =
* Maintenance release for http caching
* Corrects the cache path in the recommended Apache rewrite rules
= 1.47 =
* Maintenance release WooCommerce 2.1 compatibility
* Resolves conflict with WooCommerce registering chosen.js (enhanced selects) out of context
= 1.46 =
* Maintenance release streamlines installation
* Installation is a simpler one-step process
= 1.45 =
* Maintenance release improves comments support
* Adds additional cache refresh check when editing comments without changing status
= 1.44 =
* Maintenance release improves comments support
* Adds additional cache refresh check when new comments are inserted without moderation 
= 1.43 =
* Maintenance release for caching SSL pages
* Improved ssl caching to allow for forcing ssl
* Added support for considering the ssl host set with the Wordress HTTPS plugin
= 1.42 =
* Maintenance release
* Added host name verification for cache serving
* Cache serving enforces the set permalink trailing slash convention
= 1.41 =
* Added feature for custom refresh rules based on page or archive url
= 1.33 =
* Maintenance release
* Improved support for post comments and http caching
= 1.32 =
* Maintenance release for 1.31
* Replace php short tags which may cause fatal errors on some php configurations
= 1.31 =
* Adds support for caching SSL pages and the WordPress HTTPS plugin
= 1.20 =
* Adds the ability to exclude custom directories and pages
= 1.11 =
* Maintenance release for 1.10
* Fixes issue with cache serving
= 1.1 =
* Added support for bbPress
* Enhanced content refresh
* Performance optimizations
= 1.0 =
* Initial Release.

== Upgrade Notice ==

= 1.51 =
If you have Gator Cache http rules in your htaccess file, you will need to update your htaccess file slightly for http caching.
