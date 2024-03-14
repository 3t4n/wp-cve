=== LWSCache ===
Contributors: aurelienlws
Tags: LWS, cache, nginx
Tested up to: 6.4
Stable tag: 2.8.2
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author URI: https://www.lws.fr/

This plugin lets you manage and automatically purge your hosting's LWSCache whenever you edit your website's content

== Description ==

This plugin, created by [LWS](https://www.lws.fr) help to automatically <strong>manage your LWSCache purge when you edit your pages, post, messages…</strong>

It provide a way to <strong>purge all your LWSCache</strong>.

This plugin works only on servers using the LWSCache system. This cache is pre-installed with Classic shared [web hosting](https://www.lws.fr/hebergement_web.php), [WordPress hosting](https://www.lws.fr/hebergement_wordpress.php) and soon [cPanel hosting](https://www.lws.fr/hebergement-cpanel.php) from LWS.

The loading speed of your site is crucial to its success. The more visitors your site has, the more RAM and CPU memory the system uses. The page is loaded slowly. So you need a cache system to avoid reloading the page when it is not necessary. 

In addition, the site's page load speed is used in Google's ranking algorithm. So caching plugins that can improve load times will <strong>improve your SEO ranking</strong>.  Low rankings, and therefore insufficient exposure, often do not allow you to make a living from your website. The loading time of most websites is less than three seconds. Beyond that, many users have already left the page.

The LWS Cache tool is a system designed and developed by LWS. It <strong>optimize the loading performance of your website</strong> through the use of advanced caching mechanisms, configured at the server level. The tool uses the technologies provided by NGINX.


= Functioning =

When LWS Cache is enabled, a cache server is introduced between the visitor and the web server. 
The aim is to reduce the number of script executions required. For that it keeps the result of the execution in memory for future requests requiring the same response. This means that the same script is no longer executed several times to achieve the same result.

Thus, we eliminate the waiting time of the script execution on the page loading time. At the same time, we save the resources used during the script execution.

* <strong>The visitor requests the page from the web server</strong>. Example: index.php. LWS Cache checks if the page has already been generated and stored in the cache

<strong>If yes</strong>, the page is returned directly to the visitor without the need to access the web service and without executing the script

<strong>If not</strong>, the page is requested to be generated on the web service as a result of the script execution (PHP, NodeJS, Perl, Ruby, ...).

* Once the page is generated, <strong>LWS Cache determines if the page can be cached</strong> (via headers, URL, ...)

<strong>If yes</strong>, the page is saved in the cache and returned to the visitor

<strong>If not</strong>, the page is saved in the microcache (short-lived cache) and returned to the visitor


= Managing LWS Cache with the plugin =

The Wordpress LWS Cache plugin allows you to <strong>automatically purge the cache</strong> of your pages when you modify them or when you add/approve comments. 

To <strong>manage the plugin</strong>, once connected to your Wordpress administration console, go to the "Settings" menu and then "LWS Cache".

From the settings page, you can enable/disable automatic emptying. You can define when to automatically empty the LWS Cache and completely purge the cache.

A button for emptying the entire cache can be found anywhere in the Wordpress admin console (in the quick access bar at the top of the screen).


= Key Features =

Several settings are available to manage your LWS Cache, you can enable or disable these settings:

* Automatic purge

* Home Page Purge (when a post is modified or added, when a published post is trashed)

* Purge Post/Page/Custom Post Type (when a post is published, when a comment is approved/published, when a comment is unapproved/deleted)

* Purge Archives (date, category, tag, author, custom taxonomies)

* Purge all cache


== Frequently Asked Questions ==

= <strong>Does this module work on all server?</strong> =

No, it will only work on server who use LWSCache system 

= <strong>What web hosting do I need to use the LWS Cache plugin?</strong> =

In order to use the LWS Cache plugin, your WordPress site must first be hosted on a shared hosting plan from LWS. Indeed, some options of this plugin cannot work on sites hosted elsewhere because the plugin needs certain technologies and elements related to our hosting.
Here is a list of packages on which the plugin is compatible:

Classic shared [web hosting](https://www.lws.fr/hebergement_web.php)
[WordPress hosting](https://www.lws.fr/hebergement_wordpress.php)
[cPanel hosting](https://www.lws.fr/hebergement-cpanel.php) (soon)

= <strong>Do you have a valid promo code on recommended web hosting?</strong> =

Yes, you can enter the coupon code WPEXT15 at checkout (on [LWS](https://www.lws.fr/)) to receive an additional 15% discount (cumulative with current promotional offers!)

= <strong>How do I enable LWS Cache for my Wordpress LWSCache plugin?</strong> =

LWS Cache management is available in the [LWS Hosting Panel](https://panel.lws.fr/) in the "Optimization and Performance" section. Click on "LWSCache" then select the "Activate" button then "Validate".
A tutorial is available on our [online help](https://aide.lws.fr/base/Hebergement-web-mutualise/Optimisations-et-Performances/Comment-activer-le-LWS-Cache-pour-mon-plugin-Wordpress-LWSCache).

= <strong>What is Nginx?</strong> =

The LWS Cache tool uses the technologies provided by NGINX. NGINX is a performance-oriented web server that can handle many more requests than Apache (see our blog post titled "[Apache VS Nginx : Test de performance](https://blog.lws-hosting.com/serveur-dedie/apache-vs-nginx-test-de-performance)"). With the right configurations in place, NGINX can accommodate more requests on your website. Both speed up the loading time of your page while reducing your CPU and RAM consumption.

= <strong>Is this plugin compatible with multisite?</strong> =

Yes, it is compatible with multisite.

= <strong>Is this plugin compatible with secure https?</strong> =

Yes it is compatible with the https protocol.

= <strong>Is this plugin compatible with Woocommerce themes?</strong> =

Yes it is compatible with Woocommerce themes.

= <strong>Is LWSCache compatible with Gutenberg?</strong> =

Yes it is compatible with Gutenberg .

= <strong>Is LWSCache compatible with CloudFlare ?</strong> =

Yes, page caching is independent of proxy caching (for example Cloudflare). You can easily use both, they complement each other without interfering.

= <strong>How do I disable the LWS Cache?</strong> =

You can disable the different cache settings one by one in the plugin settings.

= <strong>Where can I get help?</strong> =

Find out more about LWSCache by searching for this keyword on our [LWS online help](https://aide.lws.fr/search?q=lws+cache). A free 7 days / 7 support is also available in France when you have ordered a web hosting or other service from LWS. Videos are also published regularly on our [YouTube channel](https://www.youtube.com/c/LwsFrance).

= <strong>Do you have any other plugins to recommend?</strong> =

Other WordPress extensions have been created by LWS :

* <strong>[LWS Tools](https://wordpress.org/plugins/lws-tools/)</strong> : Get a hold on various tools and options to optimize your website. From deactivating emotes or hiding sensible informations to deactivating REST API!
* <strong>[LWS Cleaner](https://wordpress.org/plugins/lws-cleaner/)</strong> : Helps you clean your website and giving it a second youth, fast and easily!
* <strong>[LWS Hide Login](https://wordpress.org/plugins/lws-hide-login/)</strong> : Redirect your users if they try to access your admin page directly. Choose your own page and protect your website.
* <strong>[LWS SMS](https://wordpress.org/plugins/lws-sms/)</strong> : Create SMS templates and configurate your website to send SMS to clients when you want it ! Requires [ordering SMS credits](https://www.lws.fr/envoyer-sms-par-internet.php)
* <strong>[LWS Affiliation](https://wordpress.org/plugins/lws-affiliation/)</strong> : Easily add banners and widgets such as search domain name availability or a summary table of our web hosting plans on your website.
Enjoy our [LWS](https://www.lws.fr) [affiliate program](https://affiliation.lws.fr/)</strong> and earn money!

= <strong>Useful LWS services to get you started on the web</strong> =
* [Domain name](https://www.lws.fr/nom-de-domaine.php)
* [WordPress hosting](https://www.lws.fr/hebergement_wordpress.php)
* Classic [Web hosting](https://www.lws.fr/hebergement_web.php)
* [cPanel hosting](https://www.lws.fr/hebergement-cpanel.php)
* [Reseller web hosting](https://www.lws.fr/hebergement_revendeur.php)
* [Woocommerce Hosting](https://www.lws.fr/hebergement-woocommerce.php)
* [VPS server](https://www.lws.fr/serveur_dedie_linux.php)
* [cPanel server](https://www.lws.fr/serveur-cpanel.php)
* [Cloud server](https://www.lws.fr/serveur_cloud.php)
* [Private Cloud](https://www.lws.fr/private_cloud.php)
* [Email addresses](https://www.lws.fr/adresses-email.php)
* [create a website easily](https://www.lws.fr/creer-un-site-internet.php)
* [Create a WordPress website](https://www.lws.fr/creer-un-site-wordpress.php)
* [Custom website creation](https://www.lws.fr/creation-site-sur-mesure.php)
* [Online store hosting](https://www.lws.fr/hebergement_e_commerce.php)
* [Web referencing](https://www.lws.fr/referencement.php)
* [Online storage](https://www.lws.fr/stockage-en-ligne.php)
* [Online backups](https://www.lws.fr/sauvegarde-en-ligne.php)
* [Send SMS by internet](https://www.lws.fr/envoyer-sms-par-internet.php)
* [Online help](https://aide.lws.fr/)
* [Tutorials](https://tutoriels.lws.fr/)
* [Blog](https://blog.lws-hosting.com/)
* [YouTube Videos](https://www.youtube.com/c/LwsFrance)

== Installation ==

This plugin works only on servers using the LWSCache system. This cache is pre-installed with shared [web hosting](https://www.lws.fr/hebergement_web.php), [WordPress hosting](https://www.lws.fr/hebergement_wordpress.php), [cPanel hosting](https://www.lws.fr/hebergement-cpanel.php) from LWS.

There are 3 different ways to install LWS Cache (as with any other wordpress.org plugin).

= Using the WordPress dashboard =

1. In WordPress, in the Extensions menu, click on "Add"
2. Search for the 'LWS Cache' plugin
3. Click 'Install Now'
4. Activate the plugin

= Uploading in WordPress Dashboard =

1. Download the latest version of this [plugin](https://wordpress.org/plugins/lwscache/)
2. In WordPress, in the Extensions menu, click on "Add"
3. Click on the top button "Upload an extension"
4. Select the zip file from your computer (zip file from step 1.)
5. Click 'Install Now'
4. Activate the plugin

= Using FTP =

1. Download the latest version of this [plugin](https://wordpress.org/plugins/lwscache/)
2. Unzip the zip file. This extracts the files from the compressed folder on your computer
3. Upload the lwscache folder to the /wp-content/plugins/ directory in your web space
4. Activate the plugin in WordPress

== Screenshots ==

1. LWSCache plugin home
2. Purge the LWSCache
3. Purge the LWS cache from the top of the WordPress dashboard


== Changelog ==

= 1.0.1 =
* PHP Warning correction.

= 1.0.2 =
* Improved translation and some enhancements to improve understanding and usability.

= 2.0 =
* Complete redesign of the plugin
* New "Our plugins" page
* Bugs fixes
* Purge option in the topbar now hidden when LWSCache is off

= 2.5 =
* Now working with FastestCache on cPanel
* Can now activate or deactivate FastestCache from the plugin
* New plugin added in "Our Plugins"

= 2.5.5 =
* FastestCache deactivation now working better, without having to reload the page again
* Reviews added

= 2.5.7 =
* Translation error fixed

= 2.5.8 =
* Visuals ameliorations
* Top toolbar in admin dashboard updated
* Description updated
* WPRocket and Powered Cache compatibility

= 2.5.81 =
* Fixed a bug where on some website an error could occur when using LWSCache

= 2.6 =
* Translations changes
* Up to 6.3

= 2.7 =
* Autopurge now cannot be completely deactivated
* Fixed problems where deactivating options would not work
* Fixed issue where purge (manual and automatic) would not work
* In admin, the "LWSCache" menu on top now allow for the complete deletion of the cache

= 2.7.0.1 =
* Minor update adding warnings when some cache plugins are activated due to possible conflicts

= 2.8.0 = 
* Redesign and simplification of the plugin

= 2.8.1 =
* Added more indications when purging cache manually
* Fixed responsive
* LWSCache on the top bar now stay even with Autopurge off
* CSS is confined to the plugin page instead of applying everywhere

= 2.8.2 =
* Remove warnings appearing when WordPress is installed at the root of the server
* Fixed admin_notices getting disabled on all wp-admin, breaking some plugins (like WooCommerce)
