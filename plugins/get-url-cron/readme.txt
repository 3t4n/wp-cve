=== Get URL Cron  ===
Contributors: berkux
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=APWXWK3DF2E22
Tags: admin,control,cron,wp cron,http,check,monitor,wordpress,shortcode
Requires at least: 3.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.4.8
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

With this plugin you can call URLs and WP-Shortcodes at defined times and intervals: Check if a website is there and has the required content. Define how often this is tried if it fails. Get statusmessage-mails.

== Description ==

View, create and check cronjobs doing http-URL- or Shortcode-requests.

With Get URL Cron you can 
* add, edit, delete cronjobs: request http-URLs or Wordpress-Shortcodes at defined times and with several intervals
* check the retrieved result on a required string or JSON-field (to be sure the http-URL- / Shortcode-request was ok)
* retry the http-URL- / Shortcode-request several times on failures
* display all cronjobs in the Wordpress-Installation (also those independent of Get URL Cron)
* manually execute Get URL Cron-cronjobs
* Log all requests: 1st Logentry is that the htpp-URL- / Shortcode-request is a try, 2nd Logentry is success or failirure of the request
* send mails for each http-URL- / Shortcode-request: start trying and result of request 

= Usage =
1. Go to 'Basic Settings' in the plugin menu to set basic settings (like E-Mailadress for Statusmessages) 
2. Go to 'Set CronJobs' to manage the cron events: Set URL or Wordpress-Shortcode, interval, startdate etc.
3. Store the defined CronJobs
4. Manually execute a Cronjob by clicking on "execute job"
5. Check plugin-menu 'Show CronJobs': There the scheduled CronJobs "geturlcron_event-" should be listed 
6. Check plugin-menu 'Show Logs': There should be at least one entry for the "try". And if the CronJob has been finished a entry for the result ("FAIL" or "OK")
7. If a E-Mailadress is defined, two E-Mails are sent for trying and result.  

== Frequently Asked Questions ==

= What's the use of the plugin? =
* Monitor websites / URLs on other Servers to check if the service is ok
* Cron-Execute Wordpress-Shortcodes 
* Generate Custom Post Types with the Plugin JSON Content Importer


== Screenshots ==

1. New cron events can be added, modified, deleted, and executed
2. Overview of all running Cronjobs
3. Basic settings for E-Mail-Notification, Timeout. Logfile and uninstall
4. Logfile: See what's going on - try and success / failure
                                                                       
== Installation ==
Basis installation: For detailed installation instructions, please read the [standard installation procedure for WordPress plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

Configure Get URL Cron: Select "Basic Settings" and set E-Mailadress for Statusmessages, Timeout for the http-URL- / Shortcode-requests and the expiration time of logfile-entries 

== Changelog ==
= 1.4.8 =
* Fixed security issue: Rio D. discovered a security issue. Thank you Rio! For utilize this you need Wordpress-Backend-Access and the affected Page is in the Wordpress-Adminarea only. Nevertheless: Update your JCI-Plugin, please!

= 1.4.7 =
* Improved display of CronJobs
* PHP8.1 fixes
* Minor Bugfixes 

= 1.4.6 =
* Display current Servertime on several pages
* Set DISABLE_WP_CRON to false if not set before
* Minor Preparations for PHP8-usage

= 1.4.5 =
* Bugfix: Translation settings
* Minor Improvement if no Cronjob is defined  

= 1.4.4 =
* Plugin ok for Translations: POT-File available, MO-File for German included 
* Set Cronjob, startdate: Placeholder shows current servertime
* Set Cronjob, interval: Additional intervals 5, 10 15 minutes and option "disable" 
* Bugfix: Chronological Sorting of Logfiles
* Plugin is ok with WP 5.8.3

= 1.4.3 =
* Bugfix: More than 15 Cronjobs now really possible... 
* Plugin is ok with WP 5.8.2

= 1.4.2 =
* Minor Bugfix: No more "PHP Notice"-Messages at Logfile-Display  
* Plugin is ok with WP 5.8

= 1.4.1 =
* "Basic Settings": You can increase the no of cronjobs 15+n  
* Plugin is ok with WP 5.7.1

= 1.4 =
* Bugfix displaying next execution time 
* Plugin is ok with WP 5.6 

= 1.3 =
* Plugin is ok with WP 5.4. and PHP 7.4 

= 1.2 =
Cronjob-Wordpress-Shortcode: Insert Shortcodes which will be executed

= 1.1 =
Relative Cronjob-URL: If a Cronjob-URL starts with "/" the domain is added ("home_url()")

= 1.0 =
Initial release on WordPress.org. Any comments and feature-requests are welcome!

== Upgrade Notice ==
Version 1.4 =
* Bugfix displaying next execution time 
* Plugin is ok with WP 5.6 
