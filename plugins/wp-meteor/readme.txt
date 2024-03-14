=== WP Meteor Website Speed Optimization Addon ===
Contributors: aguidrevitch
Donate link: 
Tags: pagespeed, performance, optimization, caching
Requires at least: 4.5
Tested up to: 6.4.1
Stable tag: 3.4.0
Requires PHP: 7.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

2x-5x improvement in your Page Speed score. A completely new way of optimizing your page speed.

== Description ==

WP Meteor offers an original way to further optimize your website speed. 
With this plugin you can **increase your PageSpeed score** by up to 5x.
The plugin is 100% free, it is compatible and works well with most popular caching plugins like:
* Autoptimize
* WP Rocket
* WP Total Cache
* WP Super Cache
* Phastpress
* LiteSpeed
* Hummingbird
* WP Fastest Cache
* SiteGround Optimizer

It also works well with website builders like Elementor or with image optimizers like ShortPixel.

The few known incompatibilities are listed in the FAQ.

= BENEFITS =

WP Meteor can improve your [Pagespeed](https://pagespeed.web.dev/) results and the actual loading time of your website by **up to 5x**! You do not have to take our word for it, it only takes 2 minutes to install the plugin and [test how it improves](https://test.fastpixel.io/) your website speed.
And since the plugin is designed to leave no traces on your hard drive or database when deactivated and removed, **there is no risk for you**.

= HOW IT WORKS =

If the user does not interact with the page immediately, WP Meteor defers loading and firing scripts until the page is rendered, which increases the pagespeed metric, on average, by **2-5x**.

This delay in loading scripts greatly improves perceived load times for your visitors. It also significantly improves the following **important SEO metrics**:

* [Page Speed](https://pagespeed.web.dev/)
* [Largest Contentful Paint (LCP)](https://web.dev/lcp/)
* [Time To Interactive (TTI)](https://web.dev/tti/)
* [Total Blocking Time (TBT)](https://web.dev/tbt/)

== FURTHER ACCELERATE YOUR WEBSITE ==
If you want to optimize your website's speed to the fullest extent automatically while ensuring that all Core Web Vitals are taken care of, we recommend trying [FastPixel.io](https://fastpixel.io/?utm_source=wpmeteor-readme) for free. 
The **FastPixel** project is a collaboration between the creator of WP Meteor and ShortPixel, and it addresses, with just a few clicks, [all the common issues](https://fastpixel.io/blog/easy-way-to-improve-core-web-vitals-on-wordpress/?utm_source=wpmeteor-readme#common-issues-affecting-core-web-vitals) that affect Core Web Vitals.

== Frequently Asked Questions ==

= Can this plugin be used on any website? =
While the plugin may not be compatible with every setup, it's important to note that delaying scripts can have drawbacks in some cases.

* For mission-critical websites, we strongly recommend thorough testing of essential pages, forms, and your checkout process. If you encounter any issues, please report them by [creating a support ticket](https://wordpress.org/support/plugin/wp-meteor/#new-topic-0).

* It's worth mentioning that the plugin is designed to leave no residual data on your server or database upon deactivation and removal. You are welcome to install it and assess its suitability for optimizing your site.

* After installing the plugin, we advise performing a comprehensive site test. If the results don't meet your expectations, you have the option to uninstall the plugin. Alternatively, you can explore [FastPixel Website Accelerator](https://fastpixel.io/?utm_source=wpmeteor-readme).

= Known issues = 
* WP Meteor is not compatible with Nitropack. 
* Also, WP Meteor is known to have delay issues withElementor Offcanvas addon.
* Using the "Infinite Delay" option will postpone the activation of Google Analytics (GA) and Google Tag Manager (GTM) until there is user interaction. While we do provide this option due to popular demand, I do not recommend its use. If you choose to use it, please exercise caution and be aware that you do so at your own risk.

= How can I easily compare speed with/without WP Meteor ? =
Once WP Meteor is enabled, you can add the query string parameter ?wpmeteordisable to a page URL to load the page without optimizations.

= How do I exclude a page from optimization? =

Use the `wpmeteor_enabled` filter and return false to disable WP Meteor completely, as in this example:

 add_filter('wpmeteor_enabled', function ($value) {
 global $post;
 if ($post && $post-> ID == 1) {
 return false;
 }
 return $value;
 });

= How to exclude a script from optimization =

1. Use the `Exclusions` tab to exclude scripts by matching src or inline content with regexp.
2. Use the `wpmeteor_exclude` filter which accepts 2 arguments: $exclude and $content. The $content variable contains either the src attribute or the text content of the script tag. Return true if you want to exclude the script. Example:

` add_filter('wpmeteor_exclude', function ($exclude, $content) {
 if (preg_match('/yourscript\.js/', $content)) {
 return true;
 }
 return $exclude;
 }, null, 2);`

Alternatively, you can use the script attribute data-wpmeteor-nooptimize="true" to exclude it from optimization.

= How to adjust the delay outside 1s, 2s and Infinity =

Use the `wpmeteor-frontend-adjust-wpmeteor` filter in the following way:

 add_filter('wp-meteor-frontend-adjust-wpmeteor', function ($wpmeteor, $settings) {
 $wpmeteor['rdelay'] = 4000; // number of milliseconds to delay
 return $wpmeteor;
 }, 100, 2);


== Installation ==

1. Upload the plugin files to the "/wp-content/plugins/wp-meteor" directory, or install the plugin directly from the WordPress plugin screen.
1. Activate the plugin via the 'Plugins' screen in WordPress
1. Configure the plugin via the Settings screen - > WP Meteor


== Changelog ==

3.4.0 - Javascript runner sync with Fastpixel Acceleration [FastPixel Website Accelerator](fastpixel.io)
3.3.3 - links improved
3.3.2 - readme.txt updated
3.3.1 - Support for WP Fastest Cache and SiteGround Optimizer returned, but not guaranteed to work
3.3.0 - Interaction with caching plugins reworked
3.2.6 - Added https://legalblink.it/ GDPR banner to exclude
3.2.5 - Fix for jQuery being excluded from optimization (e.g. by Phastpress)
3.2.4 - Fix for is_plugin_active not being available [Issue](https://wordpress.org/support/topic/newest-version-3-2-3-causes-a-fatal-error/)
3.2.3 - bug fix
3.2.2 - better compatibility with LiteSpeed cache
3.2.1 - support for automatic exclusion of GDPR/cookie banners, try to reduce blocking time when inserting scripts
3.2.0 - Migration to external wp-meteor javascript library, added support for shadow root inserted scripts to address [issue](https://wordpress.org/support/topic/wp-meteor-breaking-js-code-from-heyflow-form/)
3.1.9 - Migration to esbuild to generate javascript with smaller footprint, upgrade wp-notice library to fix CSRF perfmanently
3.1.8 - jQuery mock improved
3.1.7 - [problem](https://wordpress.org/support/topic/error-php-29/) fixed
3.1.6 - [issue](https://wordpress.org/support/topic/undefined-array-key-query_string-when-running-wp-cli/) fixed, PHP 8.2+ compatibility fixed
3.1.5 - Speed improvements, blocking time reduction, CSRF security fix for wp-notice library [CVE-2023-26543](https://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-2023-26543)
3.1.4 - Maintain optimization of Avada's lazysizes.js as it depends on jQuery
3.1.3 - [Problem](https://wordpress.org/support/topic/store-wpmeteor-settings-in-database/) cleanup only on uninstallation
3.1.2 - Visual Builders compatibility improvements
3.1.1 - removed lazySizesConfig from optimization
3.1.0 - fixed document.write, improvements in loading scripts and pre-binding
3.0.9 - bug fixes released
3.0.8 - bug fixes for feature released in 3.0.6
3.0.7 - Script removal improved to make third-party scripts happy, willing to remove scripts themselves
3.0.6 - bugfix release, support for onload attributes
3.0.5 - possibility to remove comments
3.0.4 - [Problem](https://wordpress.org/support/topic/need-code-snippet-to-exclude-pages-using-subdirectory/) fixed, wp-login.php is excluded from optimization
3.0.3 - [issue](https://wordpress.org/support/topic/please-fix-the-bug-2/) fixed, support for modules restored
3.0.2 - [Problem](https://wordpress.org/support/topic/please-fix-the-bug-2/) partially fixed
3.0.1 - [Problem](https://wordpress.org/support/topic/ultimatereorder-php/) fixed, body onload tag handling added to fix "wordpress social login" plugin and probably others
3.0.0 - Several improvements:
 * Zero-delay mode.
 * Improvements in forwarding events to third-party scripts loaded via GTM
 * Integration of script exclusions with Autoptimize, WP Rocket and Breeze
 * Improved detection when loading assets.
 * Support for late events fixed
 * Support for privacy services, specifically tested for OneTrust
2.3.10 - WP Fastest Cache compatibility
2.3.9 - Regexp exclusions fixed
2.3.8 - Event queue should not be processed when script loading is in progress [Issue](https://wordpress.org/support/topic/meteor-blocks-contact-form-email/)
2.3.7 - defer converted to data-defer to comply with standards
2.3.6 - support for missing tag
2.3.5 - Better readyState management
2.3.4 - added lazy images by Jetpack support
2.3.3 - Compatibility with PhastPress when 'Load scripts asynchronously' is disabled
2.3.2 - Support for script attributes without quotes src and type
2.3.1 - Fixed lazyloading of images for [Swift Performance] (https://wordpress.org/plugins/swift-performance-lite/)
2.3.0 - added support for window messaging, several improvements in event handling
2.2.21 - added [Swift Performance](https://wordpress.org/plugins/swift-performance-lite/) support
2.2.20 - added support for [Breeze](https://wordpress.org/plugins/breeze/)
2.2.19 - jQueryMock returned to trigger jQuery.ready early
2.2.18 - minor fixes
2.2.17 - document.write now correctly processes inserted scripts
2.2.16 - better detection of AJAX calls
2.2.15 - better tracking of readyState, removal of jQueryMock
2.2.14 - support for onreadystatechange property of document
2.2.13 - Bugfix in event queue management, introduced in 2.2.12
2.2.12 - important bugfix in event queue management
2.2.11 - [Meta Slider support](https://wordpress.org/support/topic/meta-slider-support/) and general compatibility improvements
2.2.10 - Bug fix
2.2.9 - bug fix
2.2.8 - Memory usage optimization
2.2.7 - [Elementor bug fixed](https://wordpress.org/support/topic/critical-error-with-elementor-2/)
2.2.6 - [Newrelic forced exclusion](https://wordpress.org/support/topic/gravity-forms-cannot-be-submitted/)
2.2.5 - [Avada theme compatibility](https://wordpress.org/support/topic/wp-meteor-conflict-with-avada-builder/)
2.2.4 - [Swift performance](https://wordpress.org/plugins/swift-performance-lite/) compatibility
2.2.3 - Readme updated
2.2.2 - CloudFlare RocketLoader compatibility [issue](https://wordpress.org/support/topic/not-clickable-menu-links-and-products/
2.2.1 - [Problem](https://plugintests.com/plugins/wporg/wp-meteor/2.2.0) fixed
2.2.0 - UI to exclude scripts
2.1.4 - improved tracking for natively slowed down images
2.1.3 - Abolish built-in lazyload handling in favor of native. Refactoring.
2.1.2 - Cloudflare Rocket Loader compatibility fixed
2.1.1 - Backend support for wpmeteor_exclude filter, also fix Fast Velocity css preload in a different way [Issue](https://wordpress.org/support/topic/rendering-delay-with-google-fonts-and-autoptimize/)
2.1.0 - Event redispatching improved, compatibility fixes, refactoring
2.0.5 - Better Fast Velocity Minify compatibility
2.0.4 - Minor fix for CSS rewriting
2.0.3 - Support for onload properties of window, document and body [Issue](https://wordpress.org/support/topic/issue-with-woodmart-theme/)
2.0.2 - support for onload events in [Issue](https://wordpress.org/support/topic/issues-with-fast-velocity-minify-plugin-merge-fonts-and-icons-separately/)
2.0.1 - fixed rewriting inside script tags [Issue](https://wordpress.org/support/topic/wp-meteor-affecting-script-tag-inside-script-tag-in-a-woocommerce-site/)
2.0.0 - Major update of script load logic
1.5.7 - Added missing files
1.5.6 - Moved previous  code to align with Web Vitals best practices [Issue](https://wordpress.org/support/topic/position-of-var-_wpmeteor-in-the-code/)
1.5.5 - Skipped rewriting content that is not text/html
1.5.4 - Improved CloudFlare detection, better/safer script rewrites, removal of support for rocket_buffer
1.5.3 - SEOPress sitemaps.xml fixed [Issue](https://wordpress.org/support/topic/sitemap-seopress-error-after-wp-meteor/)
1.5.2 - Prevent clicks on touchmove, RTL support added
1.5.1 - Elementor Powerpack Pro menu emulation
1.5.0 - added wpmeteor_enabled filter to disable optimizations occasionally
1.4.9 - document.write override allowed for those who know how to do it better (e.g. Divi Editor)
1.4.8 - Divi Theme Builder compatibility fixed
1.4.7 - SiteGround Optimizer + WP Rocket issue fixed
1.4.6 - Elementor Offcanvas double animation fixed [issue](https://wordpress.org/support/topic/menu-submenu-not-showing-in-elementor/)
1.4.5 - Elementor Offcanvas animations suppressed [issue](https://wordpress.org/support/topic/menu-submenu-not-showing-in-elementor/)
1.4.4 - fixed problems with Elementor input animations
1.4.3 - added support for Elementor input animations
1.4.2 - Removed override for currentTarget on repeated events, fixed some navigation menus
1.4.1 - For WP Rocket compatibility, use rocket_buffer filter to include javascript
1.4.0 - Elimination of { passive: true } for replayed pointer events
1.3.9 - DOMContentLoaded passing to the window object, correct event handler bindings
1.3.8 - Better fronted detection to avoid rewriting AJAX and REST requests
1.3.7 - Correct contexts for domcontentloaded and window.onload event handlers
1.3.6 - Better handling of jQuery.ready
1.3.5 - Fixed CookieBot compatibility [issue] (https://wordpress.org/support/topic/error-with-my-website-2/)
1.3.4 - Stop click forwarding when capturing events [issue](https://wordpress.org/support/topic/great-plugin-but-causes-double-tap-issue-with-safari/)
1.3.3 - Better script loading in Firefox, fixed scripts with src and inline loading [Issue](https://wordpress.org/support/topic/checkout-page-error-12/)
1.3.2 - Better delayed events for mobile devices [Issue](https://wordpress.org/support/topic/great-plugin-but-causes-double-tap-issue-with-safari/)
1.3.1 - Improved click handling in mobile Safari [Issue](https://wordpress.org/support/topic/great-plugin-but-causes-double-tap-issue-with-safari/)
1.3.0 - Gutenberg memory bug fixed
1.2.9 - fixed jQuery mockup to support window.load inside ready() function
1.2.8 - fixed header tag breaking bug
1.2.7 - Phastpress compatibility removed
1.2.6 - Minor improvement when triggering domcontentloaded and window.onload for non-optimized scripts
1.2.5 - Support for autoptimize native lazyload
1.2.4 - added support for delayed click/mouseover/mouseout events
1.2.3 - Added native support for WP Rocket lazyload
1.2.2 - added phastpress compatibility
1.2.1 - added stripped lazysizes, using bgsizes plugin
1.2.0 - minor cleanup
1.1.9 - simple lazyload polyfill
1.1.8 - added infinite delay
1.1.7 - rewriting reworked to support Google AMP and other plugins that initialize late
1.1.6 - working support for Beaver Builder / Edit Mode
1.1.5 - working support for Elementor / Edit Mode, Google AMP, AMP for WP
1.1.4 - support for Elementor / Edit Mode
1.1.3 - added support for AMP for WP plugin
1.1.2 - added support for Google AMP plugin
1.1.1 - readme.txt updated, added warning that it might not work for someone
1.1.0 - JetPack compatibility fixed
1.0.9 - added ?wpmeteornopreload to test preload disabling
1.0.8 - added data-cfasync="false" to optimize scripts if they are behind CloudFlare
1.0.7 - added ?wpmeteorcfasync parameter to test disabling CloudFlare optimizations
1.0.6 - further iteration of domcontentloaded and window.onload handlers, jQuery mock rewrite
1.0.5 - better handling for broken domcontentloaded and window.onload handlers, better jQuery mock
1.0.4 - better cleanup on plugin deactivation
1.0.3 - readme.txt updated
1.0.2 - readme.txt updated
1.0.1 - minor Settings panel improvements
1.0.0 - initial release
