=== UsageDD ===
Contributors: DionDesigns
Tags: memory,mysql,CPU,resource,usage,monitor
Requires at least: 3.0
Tested up to: 6.4
Requires PHP: 5.4
Stable tag: trunk

UsageDD allows administrators to monitor the resource usage of their WordPress installation.

== Description ==

UsageDD allows administrators to monitor the resource usage of their WordPress installation. It will add a small box at the bottom center of each page, only visible to administrators, that displays the number of MySQL queries, the amount of memory used by the page's code, and if you are using a compatible webserver (most are compatible), the "time to first byte" (TTFB) and the time required to generate the full page. TTFB is used by Google for page-ranking purposes, and it will be the first of the two displayed.

You can use the display to determine if your site has too many plugins loaded, if your theme is too "heavy", or if something is wrong on your server. The plugin itself uses virtually no resources and should be compatible with every theme and plugin that outputs HTML.

Support is available in our [**dedicated support area**](https://forum.dion-designs.com/f35/usagedd-support/). Support questions posted on wordpress.org may go unanswered for an extended period of time.

== Installation ==

Install UsageDD as you would any other plugin. UsageDD has a configuration option that is documented in the usagedd.php file. There are no admin option panels or language files -- those would require resources, and UsageDD was designed to have as little impact as possible on your resource usage.

Support is available in our [**dedicated support area**](https://forum.dion-designs.com/f35/usagedd-support/). Support questions posted on wordpress.org may go unanswered for an extended period of time.

== Frequently Asked Questions ==

= What do the numbers mean? =

The number of queries (for example, "27Q") will give you an idea of whether you are having MySQL problems. The number of queries should ideally be under 50. You may start to see slowdowns if the number is above 75. If it is above 150, you have an issue with your theme and/or plugins on that specific page which should be addressed.

The execution time numbers (for example, "0.09 | 0.15") are explained in the description. The TTFB number should be under 0.2 seconds; a larger number could result in reduced Google page ranks for your site. If the second number is more than one second larger than the first (TTFB) number, your theme is "heavy" and might require some optimization.

The memory usage (for example, "18.3M") will give you an idea of how large your site's code is. This number should be under 50 megabytes (MB), and ideally should be under 32 MB. Memory usage can be dramatically reduced by using an opcode cache such as Zend OPcache.

= How accurate are the numbers? =

The number of queries and execution times are 100% accurate. The memory usage is very slightly lower than the actual number due to the limitations imposed on WordPress plugins.

Please note that the execution time numbers reported by UsageDD may be different than what are reported from your browser or from external sites. Google Pagespeed removes network latency (also known as "ping" time) from its reported TTFB number, so it will be almost exactly the number reported by UsageDD. The numbers you see from sites such as Pingdom and GTMetrix, or from the Network tab in your browser's Dev Tools, will be higher than the numbers reported by UsageDD because they include the network latency.

= Are there any compatibility issues? =

UsageDD will be compatible with every theme and plugin that outputs correctly-formed HTML pages.

Unfortunately, there are some plugins that use WordPress to generate non-HTML output. This will not be a problem if the plugin lets other plugins know they are generating non-HTML output. For example, the WordPress REST API and XML-RPC applications generate non-HTML output, but since they let plugins know they are active, UsageDD automatically suppresses its display when these applications generate their output.

There are also some plugins/themes that generate broken HTML pages. If you activate UsageDD and do not see a usage display at the bottom of each page, this is the likely cause.

If you find that a plugin has compatibility issues with UsageDD, please let us know by posting a topic in our [**dedicated support area**](https://forum.dion-designs.com/f35/usagedd-support/).

UsageDD may not work if your hosting company provides a non-standard installation of WordPress. For example, GoDaddy's WordPress hosting loads "must-use" plugins and cache/database drop-ins that cannot be disabled, and these non-standard additions seem to conflict with UsageDD. If you are affected by this issue, please consider switching to a host that provides a standard WordPress installation!

= Where can I find support? =

Support is available in our [**dedicated support area**](https://forum.dion-designs.com/f35/usagedd-support/). Support questions posted on wordpress.org may go unanswered for an extended period of time.

== Changelog ==

= 2.1 =
Improved compatibility with rogue/questionable plugins/themes. In particular, those that generate broken and/or non-standard HTML output, and those that use non-standard methods to access the REST API.

= 2.0 =
Major rewrite of the UsageDD codebase. UsageDD is now compatible with plugins that "play games" with cached pages.

= 1.4.7 =
Fix an issue with non-standard REST API calls.

= 1.4.6 =
Minor bug fixes and improved CSS. UsageDD will disable itself if ToolkitDD is active.

= 1.4.5 =
Major improvements to usage display. Usage bar for theme display in customizer now displays correctly.

= 1.4.4 =
Fixed RTL issues with usage display. UsageDD 1.4.2 and earlier are now obsolete. Please update!

= 1.4.3 =
Improved compatibility with rogue plugins that generate non-HTML output through WordPress. UsageDD now integrated with other Dion Designs plugins. Removed support for external CSS.

= 1.4.2 =
UsageDD display now disabled for XMLRPC. Added a second method to allow themes/plugins to disable UsageDD display.

= 1.4.1 =
Added ability for other plugins to disable UsageDD display

= 1.4 =
Major improvements to usage display. Added support for external CSS. Usage display fixes for theme customizer.

= 1.3.2 =
New dedicated support area for UsageDD.

= 1.3.1 =
Added support for systems with PHP 5.2.x.

= 1.3 =
Much more detailed documentation. Usage display now compatible with theme customizer.

= 1.2 =
More improvements to usage display.

= 1.1 =
Usage display now compatible with virtually all themes. Much improved AJAX usage display.

= 1.0 =
First publicly-available version.

== Upgrade Notice ==

See Changelog.
