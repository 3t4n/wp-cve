=== pCloud WP Backup ===
Contributors: ploudapp, the_root
Tags: backup, pCloud
Requires at least: 5.0
Tested up to: 6.3.1
Requires PHP: 7.1
Stable tag: 1.4.0
License: GPLv3 or later

The pCloud WP Backup plugin will help you backup everything on your blog with one click and store it in the cloud in the most secure way.

== Description ==
# pCloud WP Backup

The pCloud WP Backup plugin was created to help you backup everything on your blog with just one click and store it in the cloud in the most secure way possible.

Just create an account select a backup schedule, and we will take care of the rest.

* The backup is done directly in the cloud, so you will have access to all your files on:
Our Blog admin interface.
* All your other devices (laptops, smartphones, tablets etc.) or via https://www.pcloud.com 

## Why you should always have a backup of your website and all its assets

Backups are the ultimate insurance for your website. They ensure that if something were to happen, and you lost all data on it including backup files (which should be regularly updated), then at least one copy of everything would still exist so an emergency situation can easily be resolved with little downtime or disruption.

Just set and forget with pCloud WP Backup. The plugin will backup all your important files automatically, so you can focus on what really matters!

## Restoring backups
The plugin can not only create backups but if something goes wrong with your website it can restore a previous version in just one click

## Security
To guarantee your file's safety, pCloud WP Backup uses TLS/SSL encryption, applied when information is transferred from your website to the pCloud servers. At pCloud data security is our top priority, and we do our best to apply first class safety measures. With pCloud, your files are stored on at least three server locations in a highly secure data storage area. Optionally, you can subscribe for pCloud Crypto and have your most important files encrypted and password protected. We provide the so called client-side encryption, which, unlike server-side encryption, means that no one, except you will, have the keys for file decryption.


== Installation ==
Once installed, you will see a new menu \"pCloud Backup\", open the menu and use the \"Authenticate with pCloud\" link to authenticate with your pCloud account.
After successful authentication, you will be able to enjoy the full functionality of the plugin.

= Minimum Requirements =

* PHP 7.1 or higher
* [pCloud account](https://my.pcloud.com/#page=register&ref=1235)


== Frequently Asked Questions ==
= I am having issues with the plugin, it does not work as expected, what to do ? =

Click \"Backup Now\" and on the upper / right corner of the page you will see small \"debug\" button, click on it and the black info window will show much more debugging / useful info related to the backup process. You can send the content of that black window to: support@pcloud.com and ask for support from the pCloud team. 

= Why the manual ( Backup Now ) works, but the automatic / scheduled does not ? = 

If the manual backup mode works it means that the plugin is functioning correctly, on the other hand - to work correctly in automatic ( scheduled ) mode the website needs to have enough visitors, so they can kick-up the backup process or at least someone needs to visit the admin page of the blog.


== Screenshots ==
1. Here is a screenshot of the plugin in action

== Changelog ==

= 1.4.0 =
* WordPress higher version support.
* Fix for multiple unsuccesful upload attempts in single upload mode.

= 1.3.0 =
* Additional improvements.

= 1.2.0 =
* API calls related to archive files listing and account info are now moved to backend, due to security concerns of few users.
* Some timings and limits are increased.

= 1.1.1 =
* Added few missing variables to INSTALL / UNINSTALL process.

= 1.1.0 =
* We have implemented new archiving solution, because of several reported issues related to the zipping process.

= 1.0.4 =
* Increased number of failures for blogs with higher number of assets.
* The plugin will retry more than once in case of bad response from the pCloud server.

= 1.0.3 =
* Added an option to choose whether to include a database snapshot in the backup archive or not.
* Much more debugging is added in order to determine the Zipping process issues.

= 1.0.2 =
* First public release, tested and confirmed to be stable enough. 

= 1.0.0 =
* Initial version of the plugin.


== Upgrade Notice ==
Plugin is more stable now and expected to work faster for bigger archives.