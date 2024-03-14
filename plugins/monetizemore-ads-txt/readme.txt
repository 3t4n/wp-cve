=== Ads.txt ===
Contributors: jose@monetizemore.com,Brandon@monetizemore.com,Kean@monetizemore.com
Donate link: http://example.com/
Tags: Ads.txt,DFP,DoubleClick for Publishers,AdSense,AdX,DoubleClick Ad Exchange
Requires at least: 4.6
Tested up to: 4.8.3
Stable tag: 4.3
License: GPL2 
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily edit your ads.txt files and ensure your site is validated for each of your ad network partners like Google Adsense and many more. 

== Description ==

The programmatic advertising industry is full of fraud, which remains an ever present problem.  The solution developed by the Interactive Advertising Bureau (IAB) for all the fraudulent online activities taking place is called the Authorized Digital Sellers project or Ads.txt for short. This ads.txt method, battles bot traffic that ends up viewing and clicking on ads, is secure, and very easy to implementat for publishers. Our Ads.txt Wordpress plugin will help you implement and edit your ads.txt file via one easy-to-use interface to make sure your site is validated for each of your ad network partners like Google AdSense, Ad Exchange and more. 

== Installation ==


1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. (Make your instructions match the desired user flow for activating and installing your plugin. Include any steps that might be needed for explanatory purposes)


== Frequently Asked Questions ==

= How do I install this plugin? =

	1.	Upload Ads.txt to the /wp-content/plugins/ directory
	2.	Activate the plugin through the ‘Plugins’ menu in WordPress
	3.	Use the Settings->Plugin-Name screen to configure the plugin
	4.	Insert the data as per need

= Why is my /ads.txt page showing a 404 error? =

Some WordPress hosting services like WPEngine have "Reverse Proxies" that try serving static files out of your server more efficiently, unfortunately these techniques block WordPress from serving ads.txt files correctly resulting in 404s. If this has happened to you, please contact your WordPress hosting provider with this message:

"We need to allow /ads.txt to be served directly the WP instance not as a file (we are creating that "file" dynamically). If you have a reverse proxy in front WordPress please add a rule to bypass it and send the HTTP request directly to the WordPress PHP web server. The response HTTP headers should also remain unmodified."

Some caching plugins like "WP Fastest Cache" are unable to correctly cache dynamically generated files from other plugins. If your ads.txt file is not showing, please add an exception to your caching plugin for /ads.txt

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

