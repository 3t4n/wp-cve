=== Add Expires Headers & Optimized Minify ===
Contributors: passionatebrains
Donate link: http://www.addexpiresheaders.com/
Tags: minify, leverage browser caching, minify css, minify js, serve static assets, efficient cache policy, fast minify, optimized, expires header, expires headers, far future expiration, auto optimize, cache, expiry header, expiry, wp-cache, minify, gzip, speed optimization, etags
Requires at least: 3.5
Tested up to: 6.4.3
Stable tag: 2.8.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will add expires headers for various types of resources of website and also help to add optimized minification and merging of resources to have better performance and speed optimization.

== Description ==
Plugin will improve your website loading speed by caching various types of static files in browser of User along with this it also offer minification of resources which reduce data size of page and reduce number of http requests hence improve performance of page. It is light weight plugin but its impact on page loading speed in very crucial and easy noticeable.

= Advantages =
1) Serves static assets with an efficient cache policy helps to leverage browser caching.

2) Reduces page loading time of website.

3) Improves user experience as page loads very quickly than before.

4) Decreases total data-size of page.

5) Larger band of predefined file types are covered so it will increase bandwidth of files which can have expiry headers.

6) You can have different expire time for cache base on type of resources.

7) Merge multiple CSS files into one helps reducing http requests and improving page load speed.

8) Async loading of processed CSS files.

9) Minify CSS files which reduce data transfer requirement hence increase page load speed.

10) Inline small footer CSS files which helps to improve page speed.

11) Escape admin users from minification to avoid page builders related issues.

= Pro Features =
1) Ability to add expires headers to External Resources

2) Adding new file types for adding expires headers

3) Refresh cache periodically

4) Unset Entity Tags

5) HTTP(Gzip) compression

6) Prevent Specific files from caching

7) Removing version info from files

8) Inline google fonts helps to load page faster and reduce external http requests.

9) Merge multiple JS files into one helps reducing http requests and improving page load speed.

10) Minify JS files which reduce data transfer requirement hence increase page load speed.

11) Plugin offers Defer scripts option to speed up rendering process.

12) HTML minification helps to reduce overall data size of page.

= Documentation =
For Plugin documentation, please refer our <a href="https://www.addexpiresheaders.com/documentation" rel="follow">plugin website</a>.

= Requirements =
1) Make sure that the "mod_expires" module is enabled on your website hosting server.

2) It is necessary to have read/write permission of .htaccess file to plugin. If not then update file permissions accordingly.

3) check status page of plugin for more info.

== Installation ==
1) Deactivate and uninstall any other expires headers plugin you may be using.

2) Login as an administrator to your WordPress Admin account. Using the “Add New” menu option under the “Plugins” section of the navigation, you can either search for: "add expires headers" or if you’ve downloaded the plugin already, click the “Upload” link, find the .zip file you download and then click “Install Now”. Or you can unzip and FTP upload the plugin to your plugins directory (wp-content/plugins/).

3) Activate the plugin through the "Plugins" menu in the WordPress administration panel.

== Usage ==

To use this plugin do the following:

1) Firstly activate Plugin.

2) Go to plugin settings page.

3) Check Files types you want to have expires headers and also add respective expires days for mime type using input box and make sure you enable respective mime type, for which group of files you want to add expires headers.

4) Once you hit "submit" button all options you selected in settings page saved database of website and accordingly .htaccess file will updated and add expires headers for respective selected files.

5) For Minification check respective settings at Minification Tab of plugin settings page.

== Frequently Asked Questions ==

= Does this plugin have custom expiry time for different resources? =
Yes base on Mime Type you can have different expiry time.

= Does this plugin help in gzip compression of output html? =
No, But if you upgrade to pro version you will have facility for same.

= Can we add custom file types for adding expires headers? =
No, But with upgrade you can have facility to add custom file types.

= Can I do CSS files minification and merging using plugin?
Yeah, Plugin by default provides minification and merging of CSS files. You can enable or disable this functionality from plugin settings under Minification Tab.

== Changelog ==

= 1.0 =
Initial Version of Plugin

= 1.1 =
Added Activation and Deactivation hooks.
Added Settings link on plugins page.

= 1.2 =
Adding functionality to disable Etags.

= 2.0 =
Basic feature for adding expires headers for pre define file types
Ability to have Pro-Version

= 2.1 =
Adding functionality for caching and adding expires headers to External resources
Added Plugin compatibility status page
Added more file formats

= 2.2 =
Upgrading facility to serve static assets with an efficient cache policy.
Increasing default values for plugin cache life for various types of files.

= 2.3 =
Adding Minification Functionality to Plugin.
Adding more power to browser caching and better compression of resources.

= 2.4 =
Adding html minification and inlining google fonts functionality
Updated third party library for minification

= 2.5 =
Adding support for async loading of processed css files

= 2.6 =
Updated Freemius SDK to latest stable version

= 2.7 =
Updated Latest Freemius SDK 
Added Purge Notification

= 2.7.1 =
Added Nonce Field to all plugin forms

= 2.7.2 =
Updated Freemius SDK to latest stable version

= 2.7.3 =
Updated Freemius SDK to latest stable version

= 2.8.0 =
Updated Freemius SDK to latest stable version

= 2.8.1 =
Tested Plugin with Latest 6.4.1
Updated Freemius SDK to latest stable version

= 2.8.2 =
Tested Plugin with Latest 6.4.3
Updated Freemius SDK to latest stable version

== Screenshots ==
1. Cache Settings
2. Minify Settings
