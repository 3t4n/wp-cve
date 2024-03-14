=== Sitemap by BestWebSoft - WordPress XML Site Map Page Generator Plugin ===
Contributors: bestwebsoft
Donate link: https://bestwebsoft.com/donate/
Tags: add pages to sitemap, add posts to sitemap, add sitemap, google, google sitemap, google sitemap plugin, sitemap file path, update sitemap, google webmaster tools, site map, sitemaps, webmaster tools
Requires at least: 5.6
Tested up to: 6.4.2
Stable tag: 3.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate and add XML sitemap to WordPress website. Help search engines index your blog.

== Description ==

Sitemap plugin automatically generates XML sitemap for your WordPress website and helps search engines index your blog. Such sitemap file helps web crawlers to extract the structure of your website more effectively.

The plugin supports default WordPress pages as well as custom URLs. It can be also added to your Google Webmaster Tools account.

Improve your website SEO today!

[View Demo](https://bestwebsoft.com/demo-for-google-sitemap/?ref=readme)

https://www.youtube.com/watch?v=CgYXKRXpj_0

= Free Features =

* Add the following URLs to the sitemap:
	* Page
	* Post
	* Post category
	* Post tag
	* Custom post types
	* Custom taxonomies
* Add a path to your sitemap file in robots.txt automatically
* Add media sitemap
* Add canonical URLs to pages and posts
* Set the maximum number of URLs in one sitemap file
* Connect your Google Webmaster Tools account to:
	* Add website
	* Add sitemap
	* Delete website
	* Get website info
* Split Sitemap Items
* Disable automatic canonical tag
* Add alternate language pages using [Multilanguage](http://bestwebsoft.com/products/multilanguage/?k=9f9a6f0b1b0b0a093b99ad9ddb4d8759) plugin
* Compatible with latest WordPress version
* Incredibly simple settings for fast setup without modifying code
* Detailed step-by-step documentation and videos

> **Pro Features**
>
> All features from Free version included plus:
>
> * Add external sitemap files
> * Exclude certain pages or post types from your sitemap file
> * Set the frequency of
> 	* Your website content changes for all pages
> 	* External sitemap file update
> * Configure all subsites on the network
> * Add custom URLs to the sitemap file
> * Change Sitemap File name
> * Get answer to your support question within one business day ([Support Policy](https://bestwebsoft.com/support-policy/))
> * Edit title and meta description [NEW]
>
> [Upgrade to Pro Now](https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/?k=8b735c0f7ca51187b5062d5e4f40058b)

If you have a feature suggestion or idea you'd like to see in the plugin, we'd love to hear about it! [Suggest a Feature](https://support.bestwebsoft.com/hc/en-us/requests/new)

= Documentation & Videos =

* [[Doc] User Guide](https://bestwebsoft.com/documentation/sitemap/sitemap-user-guide/)
* [[Doc] Installation](https://bestwebsoft.com/documentation/how-to-install-a-wordpress-product/how-to-install-a-wordpress-plugin/)
* [[Doc] Purchase](https://bestwebsoft.com/documentation/how-to-purchase-a-wordpress-plugin/how-to-purchase-wordpress-plugin-from-bestwebsoft/)
* [[Video] Installation Instruction](https://www.youtube.com/watch?v=NKlAnFTzNrQ)
* [[Video] User Guide](https://www.youtube.com/watch?v=hzz0_Yj4gaQ)

= Help & Support =

Visit our Help Center if you have any questions, our friendly Support Team is happy to help — <https://support.bestwebsoft.com/>

= Affiliate Program =

Earn 20% commission by selling the premium WordPress plugins and themes by BestWebSoft — https://bestwebsoft.com/affiliate/

= Translation =

* Czech (cs_CZ) (thanks to [Michal Kučera](mailto:kucerami@gmail.com), www.n0lim.it)
* Russian (ru_RU)
* Ukrainian (uk)

Some of these translations are not complete. We are constantly adding new features which should be translated. If you would like to create your own language pack or update the existing one, you can send [the text of PO and MO files](http://codex.wordpress.org/Translating_WordPress) to [BestWebSoft](https://support.bestwebsoft.com/hc/en-us/requests/new) and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO [files Poedit](http://www.poedit.net/download.php).

= Recommended Plugins =

* [Updater](https://bestwebsoft.com/products/wordpress/plugins/updater/?k=4b7b8eac2b35e12eaa2d51359f49cfb2) - Automatically check and update WordPress website core with all installed plugins and themes to the latest versions.
* [Htaccess](https://bestwebsoft.com/products/wordpress/plugins/htaccess/?k=6f8794059b2a6618808fa7ac6401ba6e) - Protect WordPress website - allow and deny access for certain IP addresses, hostnames, etc.
* [Multilanguage](http://bestwebsoft.com/products/multilanguage/?k=9f9a6f0b1b0b0a093b99ad9ddb4d8759) - Translate WordPress website content to other languages manually. Create multilingual pages, posts, widgets, menus, etc.

= Credits =

* The plugin uses Google Search Console (Google LLC) services submit your website sitemap file to search console for it to be visible for search engines. <https://search.google.com/search-console/> Terms of service <https://policies.google.com/terms>. Privacy Policy <https://policies.google.com/privacy>.
* This plugin incorporates a license verification mechanism to ensure the authenticity of your license key and provide access to premium features and updates. The verification process involves connecting securely to our external service hosted at BestWebSoft website <https://bestwebsoft.com>. Privacy Policy <https://bestwebsoft.com/privacy-policy/>. End user license agreement <https://bestwebsoft.com/end-user-license-agreement/>.

== Installation ==

1. Upload the folder `google-sitemap-plugin` to the directory `/wp-content/plugins/`.
2. Activate the plugin via the 'Plugins' menu in WordPress.
3. The site settings are available in "Sitemap".

[View a Step-by-step Instruction on Sitemap Installation](https://bestwebsoft.com/documentation/how-to-install-a-wordpress-product/how-to-install-a-wordpress-plugin/)

https://www.youtube.com/watch?v=NKlAnFTzNrQ

== Frequently Asked Questions ==

= How does the Sitemap plugin work? =

Sitemap plugin generates the "sitemap.xml" file, which is located in the website root directory. You can add this file to your Google Tools account.
The plugin cannot apply any visual changes to your website.

= How to create sitemap.xml file? =

After opening the Settings page the sitemap.xml file will be created automatically in the site root.

= Cannot create "sitemap.xml" file (not updating in the "robots.txt" file) =

Check the plugin version and the folder permissions (it is better to use 644 or 755). You should create both files manually ("robots.txt" and "sitemap.xml") and set permissions to 755 for both.

= I have enabled 'Robots.txt' option, but sitemap path hasn't been added to the 'robots.txt' file. The file is the same for any site of a multisite network. Is it normal? =

You should edit '.htaccess' file which is located in the root of your WordPress installation. Please open it with a text editor and add the following code to the end of this file:

`&lt;IfModule mod_rewrite.c&gt;
RewriteEngine On
RewriteBase /
RewriteRule robots\.txt$ index.php?gglstmp_robots=1
&lt;/IfModule&gt;`

Also, "Search Engine Visibility" option should be unmarked on the Settings &gt; Reading page.

= I have a large number of posts and sitemap file isn't created =

Try to increase an available memory limit (i.e. up to 256M) by adding the following lines to your 'wp-config.php' file:

`ini_set('memory_limit','256M');
define('WP_MEMORY_LIMIT', '256M');`

= I have some problems with the plugin's work. What Information should I provide to receive proper support? =

Please make sure that the problem hasn't been discussed yet on our forum (<https://support.bestwebsoft.com>). If no, please provide the following data along with your problem's description:

- The link to the page where the problem occurs
- The name of the plugin and its version. If you are using a pro version - your order number.
- The version of your WordPress installation
- Copy and paste into the message your system status report. Please read more here: [Instruction on System Status](https://docs.google.com/document/d/1Wi2X8RdRGXk9kMszQy1xItJrpN0ncXgioH935MaBKtc/)

== Screenshots ==

1. Settings page.
2. Settings page after the authorization via Google Account.
3. Sitemap Structure Settings.

== Changelog ==

= V3.3.0 - 28.12.2023 =
* Update : We updated all functionality for WordPress 6.4.
* Update : Code updated to WordPress coding standarts.
* Bugfix : Syntax errors and bugs fixed.

= V3.2.9 - 28.08.2023 =
* Update : We updated all functionality for wordpress 6.3.
* Update : BWS Panel section was updated.
* NEW: The ability to change quality of images in the sitemap.

= V3.2.8 - 29.03.2023 =
* Update : We updated all functionality for wordpress 6.2.
* Update : BWS Panel section was updated.
* Update : French file update.
* Update : German file added.
* Update : Spanish file added.
* Bugfix : The issue with Change Frequency has been fixed.
* Bugfix : The issue with Video in sitemap has been fixed.

= V3.2.7 - 29.12.2022 =
* NEW: Added Split Sitemap functionality.
* Update : We updated functionality for the new version of Google OAuth.
* Update : We updated all functionality for wordpress 6.1.
* Update : BWS Panel section was updated.
* Bugfix : The issue with sitemap.xml on multisite has been fixed.
* Bugfix : The issue with custom post type in sitemap has been fixed.

= V3.2.6 - 21.07.2022 =
* NEW: Compatibility with Yoast - Ability enable the sitemap with Yoast plugin activated.
* Update : We updated all functionality for wordpress 6.0.
* Update : BWS Panel section was updated.
* Bugfix : The issue with video sitemap has been fixed.
* PRO: Ability to edit title and meta description has been added.
* PRO : The issue with sitemap.xml on multisite has been fixed.

= V3.2.5 - 26.11.2021 =
* Bugfix : The issue with installing new plugins has been fixed.
* Update: Video User Guide has been added.
* Update : We updated all functionality for wordpress 5.8.2.
* Update : BWS Panel section was updated.

= V3.2.4 - 05.05.2021 =
* Update : We updated all functionality for wordpress 5.7.
* Update : BWS Panel section was updated.

= V3.2.3 - 22.01.2021 =
* Update : We updated all functionality for wordpress 5.6.
* Update : BWS Panel section was updated.
* Update : The plugin settings page was changed.
* PRO : The bug with sitemap structure has been fixed.

= V3.2.2 - 11.12.2019 =
* Update : Plugin was renamed.
* Bugfix : Vulnerabilities and security issues were fixed.

= V3.2.1 - 04.09.2019 =
* Update: The deactivation feedback has been changed. Misleading buttons have been removed.

= V3.2.0 - 08.08.2019 =
* Bugfix : The bug with recording a large number of posts in the sitemap file has been fixed.

= V3.1.9 - 01.07.2019 =
* Bugfix : The bug the possibility of using image title in the image_sitemap.xml file has been fixed.

= V3.1.8 - 02.05.2019 =
* PRO : Ability to add multiple URLs at once has been added.
* Bugfix : Small bugs have been fixed.
* Update : Czech language file has been updated.

= V3.1.7 - 15.01.2019 =
* NEW : The ability to add media sitemap.
* NEW : The ability to add canonical links to pages and posts.

= V3.1.6 - 26.04.2018 =
* Update : Compatibility with PHP versions lower than 5.5 has been added.

= V3.1.5 - 25.04.2018 =
* Update : Compatibility with the Multilanguage plugin has been improved. Ability to add alternate language pages using Multilanguage plugin has been added.

= V3.1.4 - 20.02.2018 =
* Bugfix : The bug with SQL request into the database has been fixed.

= V3.1.3 - 08.02.2018 =
* Bugfix : The ability to add custom post status to the sitemap file has been fixed.

= V3.1.2 - 28.12.2017 =
* Bugfix : Sitemap file updating issue has been fixed.

= V3.1.1 - 31.08.2017 =
* Update : The Czech language file has been updated.

= V3.1.0 - 16.08.2017 =
* Update : Plugin performance has been optimized.
* NEW : Sitemap file splitting functionality for a large amount of posts has been added.
* Pro : Ability to add link to the external sitemap has been added.

= V3.0.9 - 04.05.2017 =
* Update : The Czech language file was updated.
* Bugfix : The bug with editing of robots.txt file was fixed.
* Bugfix : The frontpage duplication in the sitemap was fixed.

= V3.0.8 - 31.03.2017 =
* NEW : The Czech language file was added.
* Update : The plugin settings page has been updated.

= V3.0.7 - 04.10.2016 =
* NEW : The Spanish language file was added.

= V3.0.6 - 22.08.2016 =
* Update : Compatibility with the bbPress plugin improved.
* Update : Performance optimized.
* Update : We updated all functionality for WordPress 4.6.

= V3.0.5 - 15.07.2016 =
* Update : BWS panel section was updated.

= V3.0.4 - 27.06.2016 =
* Update : BWS Panel section is updated.

= V3.0.3 - 05.04.2016 =
* Update : The page of plugin settings has been changed.
* Bugfix : The bug with plugin work for network which is based on sub-directories has been fixed.

= V3.0.2 - 28.12.2015 =
* NEW : Compatibility with Htaccess by BestWebSoft was added. This allows to get an access to the xml files (for network which is based on sub-directories).
* Bugfix : The bug with plugin menu duplicating was fixed.

= V3.0.1 - 21.09.2015 =
* Update : Textdomain was changed.
* Update : We updated all functionality for wordpress 4.3.1.

= V3.0.0 - 18.08.2015 =
* Update : We updated all functionality for wordpress 4.2.4.
* Update : BWS plugins section was updated.

= V2.9.9 - 10.07.2015 =
* Bugfix : We fixed bug with editing of file robots.txt.
* NEW : Ability to restore default settings.

= V2.9.8 - 04.06.2015 =
* NEW : Allows to add links to Post categories and Post tags to the sitemap file.

= V2.9.7 - 05.05.2015 =
* Update : We replaced old Google Webmaster Tools API with new Google Webmaster Tools API v3.

= V2.9.6 - 20.02.2015 =
* Bugfix : Error with access to the plugins settings page was fixed.
* Update : BWS Menu was updated.

= V2.9.5 - 23.12.2014 =
* Bugfix : Error loading stylesheet is fixed.

= V2.9.4 - 11.11.2014 =
* Update : BWS plugins section was updated.
* Bugfix : Plugin optimization is done.

= V2.9.3 - 15.09.2014 =
* Update : We updated all functionality for wordpress 4.0.
* Bugfix : We fixed errors while Quick Edit posts.
* Bugfix : We fixed errors while adding site to Google Webmaster Tools.

= V2.9.2 - 07.08.2014 =
* Bugfix : Security Exploit was fixed.

= V2.9.1 - 14.07.2014 =
* Bugfix : We added updating for the sitemap file when changing the status of the post from publish to private.

= V2.9.0 - 05.06.2014 =
* Update : We updated all functionality for wordpress 3.9.1.
* Update : The Ukrainian language file is updated.

= V2.8.9 - 11.04.2014 =
* Update : We updated all functionality for wordpress 3.8.2.
* Bugfix : Bug related on the sitemap link in the robots.txt was fixed.

= V2.8.8 - 12.03.2014 =
* Bugfix : Plugin optimization is done.

= V2.8.7 - 31.01.2014 =
* Update : We updated all functionality for wordpress 3.8.1.
* Bugfix : Bug related on host name with HTTPS was fixed.

= V2.8.6 - 16.01.2014 =
* Bugfix : Bug related on The link which get's added to the robots.txt was fixed.
* Update : Style of the sitemap file was updated.

= V2.8.5 - 13.01.2014 =
* Bugfix : Bugs related on using string offset as an array were fixed.
* Update : Screenshots was updated.

= V2.8.4 - 10.01.2014 =
* NEW : Installed wordpress version checking was added.
* Update : BWS plugins section was updated.
* Update : We updated all functionality for wordpress 3.8.
* Update : Activation of radio button or checkbox by clicking on its label.

= V2.8.3 - 02.10.2013 =
* Update : We updated all functionality for wordpress 3.6.1.
* NEW : The Ukrainian language file was added to the plugin.

= V2.8.2 - 05.09.2013 =
* Update : We updated all functionality for wordpress 3.6.
* Update : Function for displaying BWS plugins section was placed in a separate file and its own language files were created.
* NEW : Added additional links in activate plugin page.

= V2.8.1 - 18.07.2013 =
* NEW : An ability to view and send system information by mail was added.
* Update : We updated all functionality for wordpress 3.5.2.

= V2.8 - 03.06.2013 =
* Update : BWS plugins section was updated.

= V2.7 - 18.04.2013 =
* Update : The English language was updated in the plugin.

= V2.6 - 29.03.2013 =
* NEW : The Serbian language file was added to the plugin.

= V2.5 - 21.03.2013 =
* New: Ability to create sitemap.xml for multi-sites was added.
* Update : We updated plugin for custom WP configuration.

= V2.4 - 20.02.2013 =
* NEW : The Spanish language file was added to the plugin.

= V2.3 - 31.01.2013 =
* Bugfix : Bugs in admin menu were fixed.

= V2.2 - 29.01.2013 =
* Bugfix : Update option database request bug was fixed.

= V2.1 - 29.01.2013 =
* NEW: The French language file was added to the plugin.
* Update : We updated all functionality for wordpress 3.5.1.

= V2 - 25.01.2013 =
* New: The automatic update of sitemap after a post or page is trashed or published was added.
* Update : We updated all functionality for wordpress 3.5.

= V1.10 - 24.07.2012 =
* Bugfix : Cross Site Request Forgery bug was fixed.
* Update : We updated all functionality for wordpress 3.4.1.

= V1.09 - 27.06.2012 =
* New: Added the Arabic language file for plugin.
* Bugfix: Create new sitemap file and Add sitemap file path in robots.txt errors were fixed.
* Update : We updated all functionality for wordpress 3.4.

= V1.08 - 03.04.2012 =
* NEW: Added a possibility to include links on the selected post types to the sitemap.

= V1.07 - 02.04.2012 =
* Bugfix: CURL and save setting errors were fixed.

= V1.06 - 26.03.2012 =
* New: Language files for plugin were added.

= 1.05 =
* New: Sitemap.xsl stylesheet was added.

= 1.04 =
* New: Ability to add sitemap.xml path in robots.txt was added.

= 1.03 =
* New: Ability to get info about site in google webmaster tools was added.

= 1.02 =
* New: Ability to delete site from google webmaster tools was added.

= 1.01 =
* New: Ability to add site in google webmaster tools, verify it and add sitemap file was added.

== Upgrade Notice ==

= V3.2.9 =
* Plugin optimization completed.
* The compatibility with new WordPress version updated.
* New feature added

= V3.2.8 =
* Plugin optimization completed.
* The compatibility with new WordPress version updated.
* Bugs fixed.

= V3.2.7 =
* New feature added.
* Plugin optimization completed.
* The compatibility with new WordPress version updated.
* Bugs fixed.

= V3.2.6 =
* New features added
* Plugin optimization completed.
* The compatibility with new WordPress version updated.
* Bugs fixed.

= V3.2.5 =
* Bugs fixed.
* Usability improved
* The compatibility with new WordPress version updated.

= V3.2.4 =
* The compatibility with new WordPress version updated.

= V3.2.3 =
* Bugs fixed.
* The compatibility with new WordPress version updated.

= V3.2.2 =
* Bugs fixed.

= V3.2.1 =
* Usability improved

= V3.2.0 =
* Bugs fixed.

= V3.1.9 =
* Bugs fixed.

= V3.1.8 =
* Functionality expanded.
* Bugs fixed.

= V3.1.7 =
* Functionality expanded
* New features added

= V3.1.6 =
* The compatibility with PHP versions lower than 5.5 version updated.

= V3.1.5 =
* The compatibility with new Multilanguage plugin version updated.

= V3.1.4 =
* Bugs fixed.

= V3.1.3 =
* Bugs fixed.

= V3.1.2 =
* Bugs fixed.

= V3.1.1 =
* Languages updated.

= V3.1.0 =
* Performance optimized.
* New features added.

= V3.0.9 =
* Languages updated.
* Bugs fixed.

= V3.0.8 =
* Usability improved.

= V3.0.7 =
* New languages added.

= V3.0.6 =
Performance optimized. Functionality improved. The compatibility with new WordPress version updated.

= V3.0.5 =
Usability improved.

= V3.0.4 =
BWS Panel section is updated.

= V3.0.3 =
The page of plugin settings has been changed. The bug with plugin work for network which is based on sub-directories has been fixed.

= V3.0.2 =
Compatibility with Htaccess by BestWebSoft was added. This allows to get an access to the xml files (for network which is based on sub-directories). The bug with plugin menu duplicating was fixed.

= V3.0.1 =
Textdomain was changed. We updated all functionality for wordpress 4.3.1.

= V3.0.0 =
We updated all functionality for wordpress 4.2.4. BWS plugins section was updated.

= V2.9.9 =
We fixed bug with editing of file robots.txt. Ability to restore default settings.

= V2.9.8 =
Allows to add links to Post categories and Post tags to the sitemap file.

= V2.9.7 =
We replaced old Google Webmaster Tools API with new Google Webmaster Tools API v3.

= V2.9.6 =
Error with access to the plugins settings page was fixed. BWS Menu was updated.

= V2.9.5 =
Error loading stylesheet is fixed.

= V2.9.4 =
BWS plugins section was updated. Plugin optimization is done.

= V2.9.3 =
We updated all functionality for wordpress 4.0. We fixed errors while Quick Edit posts. We fixed errors while adding site to Google Webmaster Tools.

= V2.9.2 =
Security Exploit was fixed.

= V2.9.1 =
We added updating for the sitemap file when changing the status of the post from publish to private.

= V2.9.0 =
We updated all functionality for wordpress 3.9.1. The Ukrainian language file is updated.

= V2.8.9 =
We updated all functionality for wordpress 3.8.2. Bug related on the sitemap link in the robots.txt was fixed.

= V2.8.8 =
Plugin optimization is done.

= V2.8.7 =
We updated all functionality for wordpress 3.8.1. Bug related on host name with HTTPS was fixed.

= V2.8.6 =
Bug related on The link which get's added to the robots.txt was fixed. Style of the sitemap file was updated.

= V2.8.5 =
Bugs related on using string offset as an array were fixed. Screenshots was updated.

= V2.8.4 =
Checking installed wordpress version was added. BWS plugins section was updated. We updated all functionality for wordpress 3.8. Activation of radio button or checkbox by clicking on its label was added.

= V2.8.3 =
We updated all functionality for wordpress 3.6.1. The Ukrainian language file was added to the plugin.

= V2.8.2 =
We updated all functionality for wordpress 3.6. Function for displaying BWS plugins section was placed in a separate file and its own language files were created. Additional links in activate plugin page were added.

= V2.8.1 =
An ability to view and send system information by mail was added. We updated all functionality for wordpress 3.5.2.

= V2.8 =
BWS plugins section was updated.

= V2.7 =
The English language was updated in the plugin.

= V2.6 =
The Serbian language file was added to the plugin.

= V2.5 =
Ability to create sitemap.xml for multi-sites was added. We updated plugin for custom WP configuration.

= V2.4 =
The Spanish language file was added to the plugin.

= V2.3 =
Bugs in admin menu were fixed.

= V2.2 =
Update option database request bug was fixed.

= V2.1 =
The French language file was added to the plugin. We updated all functionality for wordpress 3.5.1.

= V2 =
The automatic update of sitemap after a post or page is trashed or published was added. We updated all functionality for wordpress 3.5.

= V1.10 =
Cross Site Request Forgery bug was fixed. We updated all functionality for wordpress 3.4.1.

= V1.09 =
The Arabic language file for plugin was added. Create new sitemap file and Add sitemap file path in robots.txt errors were fixed. We updated all functionality for wordpress 3.4.

= V1.08 =
A possibility to include links on the selected post types to the sitemap was added.

= 1.07 =
CURL and save setting errors were fixed.

= 1.06 =
Language files for plugin were.

= 1.05 =
Sitemap.xsl stylesheet was added.

= 1.04 =
Ability to add sitemap.xml path in robots.txt was added.

= 1.03 =
Ability to get info about site in google webmaster tools was added.

= 1.02 =
Ability to delete site from google webmaster tools was added.

= 1.01 =
Ability to add site in google webmaster tools, verificate it and add sitemap file was added.
