=== LWS Hide Login ===
Contributors: aurelienlws
Tags: LWS, Security
Requires at least: 5.0
Tested up to: 6.3
Stable tag: 2.2.0
Requires PHP: 7.0
Author : LWS
Author URI: https://www.lws.fr/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Secure your access to the admin page with this plugin !

== License ==
Released under the terms of the GNU General Public License.

== Description ==
Secure your website with this plugin !
Redirect your users if they try to access your admin page directly.
Choose your own link from your login page and protect your website.

= Dashboard redirection =
By default, the <strong>404 page</strong> is displayed when trying to access the administration or login page without being logged in or with the wrong URL. You can change this redirection to any page you like.

= New login address =
By default, the login page to access your wordpress dashboard is accessible at the address of your domain to which we add the suffix <strong>/wp-admin</strong> or <strong>/wp-login.php</strong>. 
By changing the login address via the LWS Hide Login plugin, the wp-admin directory and the wp-login.php page become inaccessible, you will have to use the new URL to login.
If you deactivate this plugin, your site will be as it was before, accessible at the old URL.

This plugin is pre-installed when ordering one of these LWS webhosts: [WordPress hosting](https://www.lws.fr/hebergement_wordpress.php), Classic shared [web hosting](https://www.lws.fr/hebergement_web.php) and [cPanel hosting](https://www.lws.fr/hebergement-cpanel.php) (soon)

== Frequently Asked Questions ==

= <strong>Why optimize your WordPress site?</strong> =
Optimizing your WordPress site allows you to gain speed when loading your pages, among other things. The shorter this time is the more pleasant the navigation is for your visitors. It is also a significant asset for your web referencing on Google and other search engines. Some of the included tools also allow you to reinforce the security of your WordPress site.

= <strong>What to do if you have forgotten your URL or login? </strong> =
To find the login url you modified but forgot, you can go in your MySQL database and search the value of lws_aff in the options table (sitemeta table if multisite). Otherwise, you can delete the folder corresponding to the lws-hide-login plugin in the WordPress files. So the connection via wp-login.php / wp-admin is restored. You can then reinstall the plugin.
Do the same if you have lost your login.

= <strong>Does this plugin need any particular web hosting to work?</strong> =
This plugin can be used with all WordPress websites whether they are hosted by LWS or not. But [LWS hosting](https://www.lws.fr/) offers you many other benefits: free domain, SSL certificate (https), pro emails based on your domain, low prices, premium WordPress themes and plugins...

If you want to host your WordPress site with LWS you can choose one of these solutions:

[WordPress hosting](https://www.lws.fr/hebergement_wordpress.php)
Classic shared [web hosting](https://www.lws.fr/hebergement_web.php)
[cPanel hosting](https://www.lws.fr/hebergement-cpanel.php)

= <strong>Do you have a valid promo code on recommended web hosting?</strong> =
Yes, you can enter the coupon code WPEXT15 at checkout (on [LWS](https://www.lws.fr/)) to receive an additional 15% discount (cumulative with current promotional offers!)

= <strong>Where can I get help?</strong> =
Find out more about LWS Hide Login by searching for this keyword on our [LWS online help](https://aide.lws.fr/). A free 7 days / 7 support is also available in France when you have ordered a web hosting or other service from LWS. Videos are also published regularly on our [YouTube channel](https://www.youtube.com/c/LwsFrance).

= <strong>Do you have any other plugins to recommend?</strong> =
Other WordPress extensions have been created by LWS :

* <strong>[LWS Tools](https://wordpress.org/plugins/lws-tools/)</strong> : Get a hold on various tools and options to optimize your website. From deactivating emotes or hiding sensible informations to deactivating REST API!
* <strong>[LWS Cleaner](https://wordpress.org/plugins/lws-cleaner/)</strong> : Helps you clean your website and giving it a second youth, fast and easily!
* <strong>[LWS Affiliation](https://wordpress.org/plugins/lws-affiliation/)</strong> : Easily add banners and widgets such as search domain name availability or a summary table of our web hosting plans on your website.
* <strong>[LWS SMS](https://wordpress.org/plugins/lws-sms/)</strong> : Create SMS templates and configurate your website to send SMS to clients when you want it ! Requires [ordering SMS credits](https://www.lws.fr/envoyer-sms-par-internet.php)
* <strong>[LWS Cache](https://wordpress.org/plugins/lwscache/)</strong> : This plugin works only on servers using the LWSCache system. This cache is pre-installed with shared web hosting , WordPress hosting, cPanel (soon) hosting from LWS.

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

There are 3 different ways to install LWS Hide Login (as with any other wordpress.org plugin).

= Using the WordPress dashboard =

1. In WordPress, in the Extensions menu, click on "Add"
2. Search for the 'LWS Hide Login' plugin
3. Click 'Install Now'
4. Activate the plugin

= Uploading in WordPress Dashboard =

1. Download the latest version of this [plugin](https://wordpress.org/plugins/lws-hide-login/)
2. In WordPress, in the Extensions menu, click on "Add"
3. Click on the top button "Upload an extension"
4. Select the zip file from your computer (zip file from step 1.)
5. Click 'Install Now'
4. Activate the plugin

= Using FTP =

1. Download the latest version of this [plugin](https://wordpress.org/plugins/lws-hide-login/)
2. Unzip the zip file. This extracts the files from the compressed folder on your computer
3. Upload the LWS Hide Login folder to the /wp-content/plugins/ directory in your web space
4. Activate the plugin in WordPress

== Screenshots ==

1. Home of the LWS Hide Login plugin
2. How the LWS Hide Login plugin works
3. Dashboard Redirection & New Login Address

== Changelog ==

= 2.2 =
* Fixed a problem when hiding pages/articles behind a password, where the form would break

= 2.1.9 =
* Small update to fix the missing CSS in network mode
* Fix small issue when accessing certain pages of the website in multisite that would reveal the new login URL

= 2.1.8 =
* Update compatibility up to 6.3
* Translations fixed
* Minor UI adjustement

= 2.1.7 =
* Minor CSS fix
* Fixed CSRF vulnerabilities we missed

= 2.1.6 =
* Minor CSS update

= 2.1.5 =
* Minor improvements

= 2.1 =
* Preventive measures for security
* Added a new plugin to "Our plugins"

= 2.0.2 =
* Added missing translations
* Added indication when plugin installation failed in "Our plugins"
* Install button for ad blocks and "Our plugins" synchronized

= 2.0.1 = 
* Corrected the Warning when disconnecting
* Added missing redirection with '/login'

= 2.0 =
* Rework of the plugin
* New design

= 1.1.2 = 
* Stability update

= 1.0.1 =
* Minor changes
* Adding banner

= 1.0 =
* Plugin created and published
