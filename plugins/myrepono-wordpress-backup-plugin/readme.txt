=== myRepono WordPress Backup Plugin ===
Plugin URI: http://myRepono.com/wordpress-backup-plugin/
Author: myRepono (ionix Limited)
Author URI: http://myRepono.com/
Contributors: ionix
Donate link: http://myRepono.com/wordpress-backup-plugin/
Tags: backup, wordpress backup, wp backup, website backup, mysql backup, database backup, back-up, restore, restoration, recover, recovery, remote, online, offsite, website, web, site, web site, file, database, db, mysql, sql, automated, automatic, myrepono
Requires at least: 2.8
Tested up to: 4.6
Stable tag: trunk


Automate your WordPress, website & database backups using the myRepono remote website backup service.



== Description ==

Automate your WordPress, website & database backups using the [myRepono WordPress Backup Plugin & Service](http://myRepono.com/wordpress-backup-plugin/ "myRepono WordPress Backup Plugin & Service").

[myRepono](http://myRepono.com/wordpress-backup-plugin/ "myRepono Website &amp; Database Backup Service") is an online website backup service which enables you to securely backup your WordPress web site files and mySQL database tables using an online and web-based management system.  The myRepono online website backup service allows you to automate the process of backing up your entire WordPress web site and database, including all post, comments and user data, and your WordPress PHP, template and plugin files.  

We provide an easy-to-install WordPress plugin which automates the myRepono API set-up and configuration process, enabling you to setup automated and remote website backups in a matter of minutes.  Comprehensive backup management and restoration tools are provided via myRepono.com, giving you an independent backup management and restoration system if your WordPress installation is unavailable.

myRepono is a commercial backup service which uses a pay-as-you-go balance system.  Users receive $5 USD free credit to help them get started, and with prices starting at 2 cents per day that's enough free credit to backup most WordPress installations for several months!


Features & Benefits:

* Backup unlimited WordPress installations, including all files and database data.
* Backup unlimited individual files or complete folders using web-based file management tools.
* Backup individual mySQL tables or complete mySQL databases.
* Files are encrypted, transmitted and stored securely using up to 256-Bit encryption.
* Restore files individually or on mass from your backups at any time.
* Backups are compressed to as little as 10% of their original size.
* All management is controlled with web-based tool which can be accessed with any web-enabled device.
* Backup your site every hour, day, week, month, it's up to you.
* Store as many or as few backups as you like at competitive data storage rates.
* View or download backups online using simple file management tools.
* With a single account you can backup an unlimited number of websites.
* Pay-as-you-go pricing from $0.02 USD per day, with $5 USD free trial!
* Fast and friendly online technical support available to assist you with all your questions.
* Compatible with 99% of web servers and hosting companies, and WordPress Multisite/Network compatible.



== Installation ==

= Automatic Installation =

You can install the myRepono WordPress Backup Plugin using the WordPress plugin installation tool.

Simply log-in to your WordPress administration panel and go to the 'Plugins' section.  When viewing the 'Plugins' section select the 'Add New' option to add a new plugin.

The 'Add New Plugin' section allows you to search for plugins, simply search for 'myRepono' and the 'myRepono WordPress Backup Plugin' will be shown along with an 'Install' option.  Simply select the 'Install' option and WordPress will automatically install the plugin.

Once installed, a 'myRepono' option will be shown under your WordPress administration panel menu, just below the 'Settings' option.  Select the 'myRepono' option and follow the on-screen instructions to complete the setup process.


= Manual Installation =

1. Create a directory called `myrepono-wordpress-backup-plugin` in your `/wp-content/plugins/` directory.

2. Upload 'readme.txt' file and all `*.php` files to the `/wp-content/plugins/myrepono-wordpress-backup-plugin/` directory.

3. Upload `img`, `css` and `js` directories to the `/wp-content/plugins/myrepono-wordpress-backup-plugin/` directory.

4. Upload `api` directory to the `/wp-content/plugins/myrepono-wordpress-backup-plugin/` directory.

5. Ensure `data` directory exists in `/wp-content/plugins/myrepono-wordpress-backup-plugin/api/` directory.

6. If using a Unix/Linux web server, ensure `/wp-content/plugins/myrepono-wordpress-backup-plugin/api/data/` directory is writable (e.g. permissions/chmod to `755` or `777`).

7. Activate the myRepono WordPress Backup Plugin through the 'Plugins' menu in WordPress.

8. Go to 'myRepono' section listed in your WordPress administration panel menu.



== Frequently Asked Questions ==

= What is myRepono and how much does it cost? =

myRepono is a remote website backup service which enables you to backup your WordPress and website files and databases.  myRepono is a commercial backup service which uses a pay-as-you-go balance system.  Users receive $5 USD free credit to help them get started, and with prices starting at 2 cents per day that's enough free credit to backup most WordPress installations for several months!

You can store up to 750MB of backups, and backup up to 75MB per day and only pay $0.02 USD per day!  No payment details are required until you choose to top-up your balance.

= Where can I find information about the myRepono WordPress Backup Plugin? =

Plugin Information: http://myRepono.com/wordpress-backup-plugin/

= Where can I find documentation for the myRepono WordPress Backup Plugin? =

FAQ & Documentation: http://myRepono.com/faq/

= Is support available for this plugin? =

Yes, we provide comprehensive online support free of charge via our online helpdesk at: https://myRepono.com/contact/

= Plugin Requirements =

In addition to the standard WordPress requirements, the myRepono WordPress Backup plugin requires that your PHP `allow_url_fopen` configuration option is set to `on`, or that the PHP curl and optionally the OpenSSL extension libraries are installed.  The myRepono WordPress Backup Plugin includes a CURL Extension Emulation Library which the plugin will use if alternate HTTP/HTTPS connection methods fail, therefore PHP CURL support may not be required.  The myRepono WordPress Backup Plugin can only be used on web accessible servers which the myRepono.com system can connect to, the plugin will notify you if your are not using a web accessible server.

The myRepono WordPress Backup Plugin is frequently and thoroughly tested with WordPress versions, 2.8, 2.9, 3.0, 3.1, 3.2, 3.3.x, 3.4.x, 3.5.x, 3.6.x, 3.7.x, 3.8.x, 3.9.x, 4.0.x, 4.1.x, 4.2.x and 4.3.x.

= Is the myRepono WordPress Backup Plugin compatible with a WordPress Multisite/Network installation? =

Yes, the myRepono WordPress Backup Plugin is compatible with WordPress Multisite/Network installations, and will enable you to backup your complete WordPress Multisite/Network installation with only a single installation of the myRepono WordPress Backup Plugin (or API).
 
myRepono will backup your WordPress files and databases without any interaction with WordPress - the myRepono WordPress Backup Plugin is essentially an interface for myRepono which automates the API setup process and which gives you a basic management system as part of your WordPress administration panel.
 
This means myRepono can backup a WordPress multisite/networks installation in the same way as a standard single-site installation - to the myRepono API it's just files and databases.
 
The key limitation to this is that since a WordPress multisite/network installation requires changes to the server's Apache configuration, those changes would not be automatically restored by myRepono unless they were managed using a .htaccess file or an Apache configuration file which can be backed-up by your myRepono API.



== Screenshots ==

1. Automate your WordPress, website & database backups using the myRepono remote website backup service.

2. Manage your backup file/directory selections and file exclusion rules.

3. Manage your backup profiles each with different settings and schedules.



== Changelog ==

= 2.0.12 =

Minor code changes to address PHP 7 compatibility issues.

= 2.0.11 =

The plugin will now detect if a connection to the myRepono system has failed and will automatically disable connection attempts for 3 minutes - this is to protect against disruption to the WordPress administration panel if the myRepono system becomes unavailable.  A 'Create .htaccess File' option has been added to the 'Plugin' section to allow for easy creation of a '.htaccess' file to override restrictions which may be affecting access to the API file.  Header tags have been updated to aid screenreader usage.  Minor changes have been made to data tables to improve appearance in WordPress 4.3+.

= 2.0.10 =

Minor code adjustments.

= 2.0.9 =

Backup notes may now be added and edited via the 'Backups' section of the plugin when the 'Manage Backups' permission is enabled.

= 2.0.8 =

Support for new 'Backup Notes' feature has been added to the plugin enabling you to view any backup notes you have added.  Minor adjustments to data table styles have been applied.

= 2.0.7 =

Minor adjustment to URL detection.

= 2.0.6 =

Minor adjustment to improve appearance in WordPress version 3.8.

= 2.0.5 =

Minor adjustment to improve reliability of plugin URL detection - traditional method appears to be disrupted by some third-party plugins causing the 'Connect Plugin' process to fail.

= 2.0.4 =

HTTP/HTTPS connection timeouts adjusted to 30 seconds to reduce loading delays caused by connection timeouts.  Minor change to resolve edge-case PHP warning.

= 2.0.3 =

New set-up guidance text added to help explain 'Connect Plugin' process.  Admin menu has been changed to avoid conflicts resulting in missing menu.  Plugin security adjusted to resolve edge-case admin panel access issues.

= 2.0.2 =

Minor adjustment to resolve editor/author administration panel login issues.

= 2.0.1 =

Minor adjustments to address notice shown with WordPress debug mode enabled.

= 2.0.0 =

Version 2.0.0 is a complete re-write of the myRepono WordPress Backup Plugin, significant new features include:

- A 'Connect Plugin' system which streamlines the plugin set-up process and enables each plugin to be assigned unique permissions which control what features are available, the 'Connect Plugin' system also significantly enhances the security of the plugin.
- 'Files' and 'Databases' sections have been added enabling you to manage your file and database selections from your WordPress administration panel.
- A 'Settings' section has been added enabling you to manage all your domain settings and domain profiles from your WordPress administration panel.
- The 'Backup Queue' and 'Backup Now!' features have been added, enabling you to provoke new backups and monitor your backup progress from your WordPress administration panel.

Plugin version 2.0.0 has been tested with WordPress versions, 2.8, 2.9, 3.0, 3.1, 3.2, 3.3, 3.4, 3.4.1 and 3.5-alpha-21989 (2012-09-25).  Please contact us immediately if you experience any installation issues: https://myRepono.com/contact/new/

Upgrading from version 1.x.x:

Due to new security measures the new plugin can not be setup automatically using your existing plugin configuration - we sincerely apologise for any inconvenience this may cause!  However, when completing the setup process you will be able to connect this plugin to your existing domain configuration so your backups and domain configuration will continue as normal and without disruption!  To begin, simply upgrade your plugin and then follow the 'Connect Plugin' process, then when confirming your plugin permissions you will be shown an 'Existing Domain Configurations' notification which will allow you to select your existing domain configuration to connect with your plugin.

= 1.1.7 =

Release of plugin version 1.1.7 coincides with API version 1.7 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.7.

= 1.1.6 =

Minor adjustments to improve Windows compatibility.  Release of plugin version 1.1.6 coincides with API version 1.6 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.6.

= 1.1.5 =

The plugin's methods for connecting to the myRepono.com systems have been revised to address issues with incorrectly configured servers.

= 1.1.4 =

New stored backups setting options allowing storage of up to 10,000 backups per domain.  Release of plugin version 1.1.4 coincides with API version 1.5 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.5.

= 1.1.3 =

Plugin will now pause domain configuration when 'Reset Default Configuration' option is used, this prevents failed backup errors when API is replaced when logging back in to the plugin.  Release of plugin version 1.1.3 coincides with API version 1.4 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.4.

= 1.1.2 =

NOTE: If you have a customised myRepono API or a 'myrepono_config.php' file, please ensure you make a backup before upgrading.  These files are located in your `/wp-content/plugins/myrepono-wordpress-backup-plugin/api/` directory.

The myRepono WordPress Backup Plugin now allows you to hide the myRepono backup status box which is shown in the header of the WordPress administration panel, the backup status box will also adapt it's position based on your WordPress version.  The myRepono WordPress Backup Plugin has also been updated to maintain a copy of the 'myrepono_config.php' API configuration file between plugin upgrades (this file only exists if you have customised your myRepono API configuration).  Note, the 'myrepono_config.php' file will be removed from your API directory (/wp-content/plugins/myrepono-wordpress-backup-plugin/api/) when upgrading to plugin v1.1.2 and therefore if the file exists it must be manually restored, the file will not be removed during future plugin upgrades. 

= 1.1.1 =

Plugin will now automatically detect if the associated domain configuration has been removed via myRepono.com and will automatically default to an existing domain when possible.  Additional minor changes to optimise CSS.

= 1.1.0 =

Removal of WordPress is_rtl function requirement which meant WordPress v3.0+ was required.  Additional adjustments to backup listings and backup status module to improve usability.

= 1.0.9 =

Support for CURL Extension Emulation Library added enabling WordPress Backup Plugin and myRepono API to make outgoing HTTP/HTTPS connections when standard CURL support is not available, plugin/API will now attempt a range of connection methods if default methods fail.  Further changes to API/account set-up process to improve usability and customer understanding.

= 1.0.8 =

Resolved additional error when updating myRepono WordPress Backup Plugin.  API is not automatically installed after plugin update, 1.0.6 update addressed this when user visited plugin but not if user did not access plugin section - this update will re-install the API without requiring the user to visit the plugin section of your WordPress administration panel.  The plugin will also notify admin when the API is automatically re-installed.

= 1.0.7 =

Minor usability improvements and interface adjustments.

= 1.0.6 =

CRITICAL UPDATE RESOLVING ERROR WHEN UPDATING MYREPONO WORDPRESS BACKUP PLUGIN
When updating the myRepono WordPress Backup Plugin your myRepono Backup API is removed which will in-turn cause your backups to fail, this version of the plugin will automatically re-install the API and will notify you if it is unable to do so.

= 1.0.5 =

Domain selection feature added enabling users to view backups for multiple domains (added under same myRepono.com account), with a single WordPress plugin.  Additional adjustments to API installation process in preparation for next API version, and new re-authentication system added for log-in when account password has changed.

= 1.0.4 =

General usability improvements and interface adjustments.

= 1.0.3 =

Minor adjustments to data caching.

= 1.0.2 =

Minor layout and content adjustments.

= 1.0.1 =

Minor adjustments to API installations.

= 1.0.0 =

First official release of plugin.



== Upgrade Notice ==

= 2.0.12 =

Minor code changes to address PHP 7 compatibility issues.

= 2.0.11 =

The plugin will now detect if a connection to the myRepono system has failed and will automatically disable connection attempts for 3 minutes - this is to protect against disruption to the WordPress administration panel if the myRepono system becomes unavailable.  A 'Create .htaccess File' option has been added to the 'Plugin' section to allow for easy creation of a '.htaccess' file to override restrictions which may be affecting access to the API file.  Header tags have been updated to aid screenreader usage.  Minor changes have been made to data tables to improve appearance in WordPress 4.3+.

= 2.0.10 =

Minor code adjustments.

= 2.0.9 =

Backup notes may now be added and edited via the 'Backups' section of the plugin when the 'Manage Backups' permission is enabled.

= 2.0.8 =

Support for new 'Backup Notes' feature has been added to the plugin enabling you to view any backup notes you have added.  Minor adjustments to data table styles have been applied.

= 2.0.7 =

Minor adjustment to URL detection.

= 2.0.6 =

Minor adjustment to improve appearance in WordPress version 3.8.

= 2.0.5 =

Minor adjustment to improve reliability of plugin URL detection - traditional method appears to be disrupted by some third-party plugins causing the 'Connect Plugin' process to fail.

= 2.0.4 =

HTTP/HTTPS connection timeouts adjusted to 30 seconds to reduce loading delays caused by connection timeouts.  Minor change to resolve edge-case PHP warning.

= 2.0.3 =

New set-up guidance text added to help explain 'Connect Plugin' process.  Admin menu has been changed to avoid conflicts resulting in missing menu.  Plugin security adjusted to resolve edge-case admin panel access issues.

= 2.0.2 =

Minor adjustment to resolve editor/author administration panel login issues.

= 2.0.1 =

Minor adjustments to address notice shown with WordPress debug mode enabled.

= 2.0.0 =

Version 2.0.0 is a complete re-write of the myRepono WordPress Backup Plugin, significant new features include:

- A 'Connect Plugin' system which streamlines the plugin set-up process and enables each plugin to be assigned unique permissions which control what features are available, the 'Connect Plugin' system also significantly enhances the security of the plugin.
- 'Files' and 'Databases' sections have been added enabling you to manage your file and database selections from your WordPress administration panel.
- A 'Settings' section has been added enabling you to manage all your domain settings and domain profiles from your WordPress administration panel.
- The 'Backup Queue' and 'Backup Now!' features have been added, enabling you to provoke new backups and monitor your backup progress from your WordPress administration panel.

Plugin version 2.0.0 has been tested with WordPress versions, 2.8, 2.9, 3.0, 3.1, 3.2, 3.3, 3.4, 3.4.1 and 3.5-alpha-21989 (2012-09-25).  Please contact us immediately if you experience any installation issues: https://myRepono.com/contact/new/

Upgrading from version 1.x.x:

Due to new security measures the new plugin can not be setup automatically using your existing plugin configuration - we sincerely apologise for any inconvenience this may cause!  However, when completing the setup process you will be able to connect this plugin to your existing domain configuration so your backups and domain configuration will continue as normal and without disruption!  To begin, simply upgrade your plugin and then follow the 'Connect Plugin' process, then when confirming your plugin permissions you will be shown an 'Existing Domain Configurations' notification which will allow you to select your existing domain configuration to connect with your plugin.

= 1.1.7 =

Release of plugin version 1.1.7 coincides with API version 1.7 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.7.

= 1.1.6 =

Minor adjustments to improve Windows compatibility.  Release of plugin version 1.1.6 coincides with API version 1.6 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.6.

= 1.1.5 =

The plugin's methods for connecting to the myRepono.com systems have been revised to address issues with incorrectly configured servers.

= 1.1.4 =

New stored backups setting options allowing storage of up to 10,000 backups per domain.  Release of plugin version 1.1.4 coincides with API version 1.5 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.5.

= 1.1.3 =

Plugin will now pause domain configuration when 'Reset Default Configuration' option is used, this prevents failed backup errors when API is replaced when logging back in to the plugin.  Release of plugin version 1.1.3 coincides with API version 1.4 release, all users should upgrade their myRepono WordPress Backup Plugin immediately to automatically upgrade their myRepono API to version 1.4.

= 1.1.2 =

NOTE: If you have a customised myRepono API or a 'myrepono_config.php' file, please ensure you make a backup before upgrading.  These files are located in your `/wp-content/plugins/myrepono-wordpress-backup-plugin/api/` directory.

The myRepono WordPress Backup Plugin now allows you to hide the myRepono backup status box which is shown in the header of the WordPress administration panel, the backup status box will also adapt it's position based on your WordPress version.  The myRepono WordPress Backup Plugin has also been updated to maintain a copy of the 'myrepono_config.php' API configuration file between plugin upgrades (this file only exists if you have customised your myRepono API configuration).  Note, the 'myrepono_config.php' file will be removed from your API directory (/wp-content/plugins/myrepono-wordpress-backup-plugin/api/) when upgrading to plugin v1.1.2 and therefore if the file exists it must be manually restored, the file will not be removed during future plugin upgrades. 

= 1.1.1 =

Plugin will now automatically detect if the associated domain configuration has been removed via myRepono.com and will automatically default to an existing domain when possible.  Additional minor changes to optimise CSS.

= 1.1.0 =

Removal of WordPress is_rtl function requirement which meant WordPress v3.0+ was required.  Additional adjustments to backup listings and backup status module to improve usability.

= 1.0.9 =

Support for CURL Extension Emulation Library added enabling WordPress Backup Plugin and myRepono API to make outgoing HTTP/HTTPS connections when standard CURL support is not available, plugin/API will now attempt a range of connection methods if default methods fail.  Further changes to API/account set-up process to improve usability and customer understanding.

= 1.0.8 =

Resolved additional error when updating myRepono WordPress Backup Plugin.  API is not automatically installed after plugin update, 1.0.6 update addressed this when user visited plugin but not if user did not access plugin section - this update will re-install the API without requiring the user to visit the plugin section of your WordPress administration panel.  The plugin will also notify admin when the API is automatically re-installed.

= 1.0.7 =

Minor usability improvements and interface adjustments.

= 1.0.6 =

CRITICAL UPDATE RESOLVING ERROR WHEN UPDATING MYREPONO WORDPRESS BACKUP PLUGIN
When updating the myRepono WordPress Backup Plugin your myRepono Backup API is removed which will in-turn cause your backups to fail, this version of the plugin will automatically re-install the API and will notify you if it is unable to do so.

= 1.0.5 =

Domain selection feature added enabling users to view backups for multiple domains (added under same myRepono.com account), with a single WordPress plugin.  Additional adjustments to API installation process in preparation for next API version, and new re-authentication system added for log-in when account password has changed.

= 1.0.4 =

General usability improvements and interface adjustments.

= 1.0.3 =

Minor adjustments to data caching.

= 1.0.2 =

Minor layout and content adjustments.

= 1.0.1 =

Minor adjustments to API installations.

= 1.0.0 =

First official release of plugin.


