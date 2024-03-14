=== Analytics by BestWebSoft - Google Analytics Dashboard and Statistic Plugin for WordPress ===
Contributors: bestwebsoft
Donate link: https://bestwebsoft.com/donate/
Tags: add tracking code, analytics, display statistic report, google analytics, google analytics plugin, google analytics stats, group statistics, metrics, page views, visit duration, tracking, web properties
Requires at least: 5.6
Tested up to: 6.2
Stable tag: 1.7.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add Google Analytics code to WordPress website and track basic stats.

== Description ==

Analytics plugin is the best way to view Google Analytics on your WordPress website dashboard. Configure the reporting mode, select the metrics you need, set the time range for your statistic displaying and keep tracking your WordPress website statistics.

[View Demo](https://bestwebsoft.com/demo-analytics/?ref=readme)

https://www.youtube.com/watch?v=u6GCmG2SYIg

= Free Features =

* Add single tracking code
* Choose statistics view mode:
	* Line chart
	* Table
* Choose statistics time range
* Supports several accounts and webproperties for the statistics displaying
* Choose statistics metrics:
	* Visitor
		* Unique visitors
		* 
 visits
	* Session
		* Visits
		* Bounce rate
		* Average visit duration
	* Page Tracking
		* Pageviews
		* Page/Visit
* Compatible with latest WordPress version
* Incredibly simple settings for fast setup without modifying code
* Detailed step-by-step documentation and videos

> **Pro Features**
>
> All features from Free version included plus:
>
> * View statistics for goals
> * Choose goal metrics:
> 	* Completions
> 	* Value
> 	* Conversion rate
> 	* Abandoned funnels
> * Configure all subsites on the network
> * Get answer to your support question within one business day ([Support Policy](https://bestwebsoft.com/support-policy/))
>
> [Upgrade to Pro Now](https://bestwebsoft.com/products/wordpress/plugins/bws-google-analytics/?k=5891b1a2761b39cd5706eba26c3af1d4)

If you have a feature suggestion or idea you'd like to see in the plugin, we'd love to hear about it! [Suggest a Feature](https://support.bestwebsoft.com/hc/en-us/requests/new)

= Documentation & Videos =

* [[Doc] User Guide](https://docs.google.com/document/d/1crUDzT-SASTmoj3M6lJcR4CyRzCp9Ge1l2-BcsUotZY/)
* [[Doc] Installation](https://docs.google.com/document/d/1-hvn6WRvWnOqj5v5pLUk7Awyu87lq5B_dO-Tv-MC9JQ/)
* [[Doc] Purchase](https://docs.google.com/document/d/1EUdBVvnm7IHZ6y0DNyldZypUQKpB8UVPToSc_LdOYQI/)

= Help & Support =

Visit our Help Center if you have any questions, our friendly Support Team is happy to help — <https://support.bestwebsoft.com/>

= Translation =

* Russian (ru_RU)
* Spanish (es_ES) (thanks to [Jose Bescos](mailto:jmbescos@ibidemgroup.com) - www.ibidemgroup.com)
* Ukrainian (uk)

Some of these translations are not complete. We are constantly adding new features which should be translated. If you would like to create your own language pack or update the existing one, you can send [the text of PO and MO files](https://codex.wordpress.org/Translating_WordPress) to [BestWebSoft](https://support.bestwebsoft.com/hc/en-us/requests/new) and we'll add it to the plugin. You can download the latest version of the program for work with PO and MO [files Poedit](https://www.poedit.net/download.php).

= Recommended Plugins =

* [Updater](https://bestwebsoft.com/products/wordpress/plugins/updater/?k=b0536eca91f29f7603d42d53f5fd3990) - Automatically check and update WordPress website core with all installed plugins and themes to the latest versions.

== Installation ==

1. Upload the `bws-google-analytics` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin using the 'Plugins' menu in your WordPress admin panel.
3. Plugin settings are located in your WordPress admin panel in "Analytics" > "Settings".

[View a PDF version of Step-by-step Instruction on Analytics Installation](https://docs.google.com/document/d/1-hvn6WRvWnOqj5v5pLUk7Awyu87lq5B_dO-Tv-MC9JQ/)

== Frequently Asked Questions ==

= I want to collect statistic from my blog, what should I do? =

Follow the next steps to enable tracking and collect statistics from your Wordpress blog:

1. Sign in to your Google Analytics account. Click "Admin" in the menu bar at the top of any page.
2. In the Account column, select the account from the dropdown that you want to add the property to.
3. In the dropdown in the Property column, click Create new property.
4. Select Website.
5. Select a tracking method. Click either Universal Analytics (recommended) or Classic Analytics.
6. Enter the name of your WordPress blog.
7. Enter your website URL (for example, bestwebsoft.com).
8. Select an Industry Category.
9. Select the Reporting Time Zone.
10. Click Get Tracking ID.
11. Copy Tracking ID that looks like UA-xxxxx-y.
12. Open your Wordpress admin dashboard.
13. Navigate to the Analytics -> Settings tab.
14. Past the code to the Tracking ID field.
15. Check Add tracking Code To Your Blog checkbox (if not checked).
16. Click Save Changes button.

= I don't have Google Analytics account, can I still retrieve statistics using this plugin? =

No, you can’t. This plugin allows you to retrieve statistics from Google Analytics account. It doesn’t generate its own statistics.

= How can I add tracking code? =

After you have created a new web property you will get a tracking code. If you want to add tracking code to your blog you will need to copy Tracking ID that looks like UA-xxxxx-y, paste it to the Blog Tracking field and click the Enable Tracking button.

= I have added tracking code to my blog using this plugin. Now if I deactivate this plugin, will Google Analytics continue logging my blog? =

No, if you deactivate plugin tracking code will be deleted from your blog.

= Can I create a new Google Analytics account or a new web property from within the admin area using this plugin? =

No, you can’t. Google Analytics provides the developer with an access to the configuration data through the Management API, which is a read-only API for account and configuration data.

= What is the range of dates that is covered by the chart? =

Line chart displays stats for the last year, 6 months, 3 months, 1 month, 5 days and 1 day.

= Over what period of time I can get the statistics? =

You can select your desired time period in the "Time range" setting block on the plugin settings page. However, the time interval between start and finish dates should not exceed 1000 days.

= I have some problems with the plugin's work. What Information should I provide to receive proper support? =

Please make sure that the problem hasn't been discussed yet on our forum (<https://support.bestwebsoft.com>). If no, please provide the following data along with your problem's description:
- The link to the page where the problem occurs
- The name of the plugin and its version. If you are using a pro version - your order number.
- The version of your WordPress installation
- Copy and paste into the message your system status report. Please read more here: [Instruction on System Status](https://docs.google.com/document/d/1Wi2X8RdRGXk9kMszQy1xItJrpN0ncXgioH935MaBKtc/)

== Screenshots ==

1. Analytics Settings page.
2. Google Analytics Authentication.
3. Line Chart mode in the plugin Statistic page.
4. Table Chart mode in the plugin Statistic page.

== Changelog ==

= V1.7.8 - 01.02.2021 =
* NEW : The Spanish language file is added.
* Bugfix : We fixed the bug of additional page reload to get Client ID data.
* Update : We updated all functionality for WordPress 5.6.
* Update : The plugin settings page has been updated.
* Update : BWS Panel section was updated.

= V1.7.7 - 18.12.2019 =
* Update : Plugin was renamed.
* Bugfix : Vulnerabilities and security issues were fixed.

= V1.7.6 - 04.09.2019 =
* Update: The deactivation feedback has been changed. Misleading buttons have been removed.

= V1.7.5 - 23.05.2019 =
* Update : The plugin settings page has been updated.
* Bugfix : The bug with an hourly reauthorization was fixed.

= V1.7.4 - 25.12.2018 =
* Update : The plugin settings page has been updated.

= V1.7.3 - 30.01.2018 =
* Update : Google Analytics API Client library has been updated to version 2.2.1.

= V1.7.2 - 13.07.2017 =
* Update : We updated all functionality for WordPress 4.8.

= V1.7.1 - 14.04.2017 =
* Bugfix : Multiple Cross-Site Scripting (XSS) vulnerability was fixed.

= V1.7.0 - 04.11.2016 =
* Update : 'Line Chart' view mode has been updated.

= V1.6.9 - 17.08.2016 =
* Update : All functionality for WordPress 4.6 was updated.

= V1.6.8 - 21.07.2016 =
* Update : BWS panel section was updated.

= V1.6.7 - 16.05.2016 =
* Update : The structure of the plugin settings page has been changed.
* Update : The code refactoring has been made to speed up the work of the plugin.

= V1.6.6 - 04.12.2015 =
* Bugfix : The bug with plugin menu duplicating was fixed.

= V1.6.5 - 05.10.2015 =
* Update : We updated all functionality for WordPress 4.3.1.
* Update : Textdomain was changed.

= V1.6.4 - 11.06.2015 =
* Update: Input maxlength is added.
* Bugfix: We fixed the settings page display.
* Update: BWS plugins section was updated.

= V1.6.3 - 07.05.2015 =
* Update : We updated all functionality for WordPress 4.2.2.

= V1.6.2 - 26.03.2015 =
* New: An ability to add tracking code to the website without authorizing in the plugin was added.

= V1.6.1 - 06.01.2014 =
* Update: BWS plugins section was updated.

= V1.6 - 26.09.2014 =
* New : We added an option to save tracking code without adding it to blog.
* Update : We updated all functionality for WordPress 4.0.
* Bugfix : We fixed the script that adds tracking code and moved it to the bottom of a page to speed the page loading process.
* Bugfix : Security Exploit was fixed.

= V1.5 - 13.05.2014 =
* New : We added Ukrainian language.
* Update : We updated all functionality for WordPress 3.9.1.

= V1.4 - 12.03.2014 =
* Update: Screenshots were updated.
* Update: Readme file was updated.
* Bugfix: Plugin optimization was done.
* Update: BWS plugins section was updated.

= V1.3 - 06.03.2014 =
* Bugfix: Fixed fatal error that occured during the plugin activation.

= V1.2 - 28.02.2014 =
* Update: Updated UI.
* Bugfix: Fixed ajax functions issues.
* Update: Updated instructions.

= V1.1 - 20.02.2014 =
* Update: Updated UI.
* Bugfix: Fixed form validation issues.
* Update: Updated instructions.

= V1.0 - 13.02.2014 =
* NEW: Ability to retrieve basic statistical information from Google Analytics account was added.

== Upgrade Notice ==

= V1.7.8 =
* New languages added.
* Bugs fixed.

= V1.7.7 =
* The compatibility with new WordPress version updated.

= V1.7.6 =
* Usability improved.

= V1.7.5 =
* Functionality improved. Bugs fixed.

= V1.7.4 =
* Appearance improved.

= V1.7.3 =
* Update : We updated Google Analytics API Client library.

= V1.7.2 =
* The compatibility with new WordPress version updated.

= V1.7.1 =
* Bugs fixed.

= V1.7.0 =
* Functionality improved. Appearance improved.

= V1.6.9 =
* The compatibility with new WordPress version updated.

= V1.6.8 =
* Usability improved.

= V1.6.7 =
The structure of the plugin settings page has been changed. The code refactoring has been made to speed up the work of the plugin.

= V1.6.6 =
The bug with plugin menu duplicating was fixed.

= V1.6.5 =
We updated all functionality for WordPress 4.3.1. Textdomain was changed.

= V1.6.4 =
Input maxlength is added. We fixed the settings page display. BWS plugins section was updated.

= V1.6.3 =
We updated all functionality for WordPress 4.2.2.

= V1.6.2 =
An ability to add tracking code to the website without authorizing in the plugin was added.

= V1.6.1 =
BWS plugins section was updated.

= V1.6 =
We added an option to save tracking code without adding it to blog. We updated all functionality for WordPress 4.0. We fixed the script that adds tracking code and moved it to the bottom of a page to speed the page loading process. Security Exploit was fixed.

= V1.5 =
We added Ukrainian language. We updated all functionality for WordPress 3.9.1.

= V1.4 =
Plugin optimization was done. BWS plugins section was updated. Readme file was updated. Screenshots were updated.

= V1.3 =
Fixed fatal error that occured during the plugin activation.

= V1.2 =
Updated UI and instructions. Fixed ajax functions issues.

= V1.1 =
Updated UI and instructions. Fixed form validation issues.

= V1.0 =
Ability to retrieve basic statistical information from Google Analytics account was added.
