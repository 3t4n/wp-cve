=== WordPress Backup & Migration ===
Contributors: webtoffee
Donate link: https://www.webtoffee.com/plugins/
Tags:  WordPress migration, Website migration, WordPress backup, database backup, database restore, move, transfer, copy, migrate, backup, clone, restore, DB migration, migrator
Requires at least: 3.3
Tested up to: 6.4.3
Requires PHP: 5.6
Stable tag: 1.4.8
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Easily backup, restore, or migrate. Supports one-click backup and scheduled backup. Backup selected content to Amazon S3, Google Drive, FTP/SFTP, etc.

== Description ==

= Simple, Easy-to-use WordPress Migration Plugin =

This WordPress migrator plugin lets you migrate your WordPress site between any hosts or domains. You can backup your WordPress site automatically or manually on a single click and restore it to the target site with zero downtime.

You can backup to cloud (Amazon S3, Google Drive) and external storage locations FTP, SFTP. Other than full site backups, the plugin supports database backups, and lets you migrate only the selected files.

= WordPress Migrator & Backup Plugin - free version features =

&#128312;  <strong>Ease of use:</strong> Migrate all your Wordpress data (media files, plugins, themes, and database) with minimal clicks.

&#128312;  <strong>One-click backup and restore:</strong> Supports single-click backup and restore.

&#128312;<strong> No data size limit for migration:</strong> Backup and restore WordPress site's of any size effortlessly.

&#128312; <strong>Supports all hosts or operating system:</strong> There is no limitation on host and operating system for migration.

&#128312;  <strong>Automated scheduled backups:</strong> Schedule backups on a daily/weekly/monthly basis.

&#128312; <strong>Auto-replace website URLs:</strong> Supports auto-replace of URLs during restore. No longer need for search and replace!

&#128312;  <strong>Multiple backup locations:</strong> Supports FTP/SFTP, Google Drive, Amazon S3, and local storage.

&#128312;  <strong>Backup and migrate only selected data:</strong> You can choose what you need to migrate from your WordPress site. Supports database backup, backup of chosen files (plugins, themes, core files, uploads, etc.)

&#128312; <strong>Supports both MySQL and MySQLi</strong>

= Setup Guide - WordPress Migration & Backup =

You can checkout the <a rel="nofollow" href="https://www.webtoffee.com/wordpress-backup-migration-user-guide/">user guide</a> to easily setup the plugin or watch the below video.

[youtube https://www.youtube.com/watch?v=hIaM_xeWa_8]

= Premium version features =

✅ Scheduled automatic backups at custom intervals
✅ Supports DropBox and OneDrive
✅ Select database tables to backup
✅ Select files to backup
✅ Supports multiple formats (ZIP, GZIP, and TAR)
✅ Multiple storage locations
✅ Email notifications for site’s backup status

Read more about the pro version of the plugin by checking out its [product page](https://www.webtoffee.com/product/wordpress-backup-and-migration/).

= DIFFERENT WORDPRESS MIGRATION CASES THAT CAN BE HANDLED WITH THE PLUGIN =

Following are some of the common use cases you can handle using this plugin.

* Move WordPress site to a new domain
* Transfer your WordPress site from your current host to a new one
* Copy your WordPress site from one domain to another
* Move WordPress from localhost to server
* Move your WordPress site from its subdomain to its root domain
* You need a WordPress clone of the live site for testing or development purposes
* You are creating a manual backup of a WordPress website
* You need to restore WordPress after the site crashed
* You need to do a WordPress restore to an earlier version

= HOW  WORDPRESS BACKUP AND MIGRATOR PLUGIN WORKS =

Migrating a WordPress site to anew domain or host consists of three parts – moving the files, moving the database, and reconfiguring (if needed). Our WordPress migration plugin automates this process.

You may follow the below steps for a WordPress full migration.

* Install the WordPress Backup & Migration plugin on your existing website.
* Generate(export) a migration file that includes all the files required as a zip file.
* Install WordPress Backup & Migration plugin on the target site.
* Import the zip file into your new location. The plugin will move all files including    theme files, plugin files, and replace the database.
* You will be logged out forcefully once the WP migration and restore is completed.


== Installation ==

WordPress Backup & Migration can be installed directly through your WordPress Plugins dashboard.

1. Click "Add New" and search for "WordPress Backup & Migration"
2. Install and Activate

WordPress Backup & Migration also can be installed by manually uploading the zip file of the plugin via FTP.

1. Download the zip file of the plugin from the WordPress plugin repository
2. Unzip the downloaded zip file
3. Upload the plugin folder into the 'wp-content/plugins/' directory of your WordPress site
4. Go the ‘Installed Plugins’ page on the WordPress dashboard. Activate WordPress Backup & Migration from the Plugins page

After the installation and activation of the plugin, the plugin menu will appear on the WordPress sidebar.

== Frequently Asked Questions ==

= Does the plugin support multisite? =

The plugin is capable of exporting and importing WordPress multisite.

= How to increase maximum upload file size while migrating with the plugin? =

[You can refer to this article]( https://www.webtoffee.com/increase-maximum-upload-file-size-in-wordpress-migrator/) for learning how to increase the maximum file upload size during migration.

= Does the plugin support all sizes of sites for migration? =

Yes. With the WordPress Backup and Migration plugin you can backup and migrate WordPress sites of all sizes.

= Does it work with all hosts? =

Yes. You can migrate your WordPress site between any hosts with the plugin.


== Screenshots ==

1. WordPress backup and schedule
2. Create a WordPress backup
3. WordPress backup in progress
4. Scheduled WordPress backup
5. Choose a backup location
6. Select the content for backup
7. Restore WordPress site
8. List of recent WordPress site backup files
9. Authenticate FTP/SFTP for backup
10. Authenticate Google drive for backup
11. Authenticate Amazon S3 for backup
12. WordPress backup logs
13. Advanced options for backup
14. Advanced options for restore

== Changelog ==

= 1.4.8  2024-03-11 =
* Security updates (reported by Joshua)
* Tested OK with WordPress 6.4.3

= 1.4.7  2023-12-19 =
* Security updates (reported by Joshua)
* Tested OK with WordPress 6.4.2

= 1.4.6  2023-12-05 =
* Tested OK with WordPress 6.4.1

= 1.4.5  2023-10-27 =
* [Fix] - Security updates (reported by Alex)

= 1.4.4  2023-10-17 =
* [Fix] - Security updates (reported by Abdi Pranata)

= 1.4.3  2023-08-30 =
* Tested OK with WordPress 6.3

= 1.4.2  2023-07-24 =
* [Fix] - Security updates (reported by Abdi Pranata)
* Tested OK with WordPress 6.2.2

= 1.4.1  2023-05-23 =
* [Fix] - Security updates (reported by Abdi Pranata)

= 1.4.0  2023-04-17 =
* Fix : FTP connection issue while backup.
* Tested OK with PHP 8.2

= 1.3.9  2023-03-30 =
* Tested OK with WordPress 6.2
* Fix : Google drive authentication issue solved.
* Fix : Unable to import from Google Drive
* Improvement: Code stability improved.

= 1.3.8 2022-09-01 =
* Tested OK with WordPress 6.0.2
* Improvement: Import file handling improved

= 1.3.7  2022-05-26 =
* Tested OK with WordPress 6.0

= 1.3.6 =
* Improvement: Code stability improved.
* Tested OK with WordPress 5.9.3

= 1.3.5 =
* Tested OK with WordPress 5.9.

= 1.3.4 =
* Code stability improved.
* Improved UI/UX
* Improvement: Error handling improved, internal memory optimization.

= 1.3.3 =
* Code stability improved.
* Improvement: Empty file handling in restore.
* Fix : Dropzone upload size issue and timeout issue solved.
* WP 5.8.2 tested OK

= 1.3.2 =
* Code stability improved.
* Improvement: SFTP and Amazon S3 file handling.
* Fix: Style correction

= 1.3.1 =
* Timeout issue solved
* Banner close button issue solved
* WP 5.8.1 tested OK.

= 1.3.0 =
* Improved UI/UX
* WordPress Cron Schedule for export
* SFTP cloud storage option added support
* Advanced options for Import/Export to speedup the operation
* WP 5.8 tested OK.

= 1.2.5 =
* Improvement: Code stability improved.
* Option to select all and deselect all Folders/Files on export page.

= 1.2.4 =
* Bug Fix :- Log table create issue solved.

= 1.2.3 =
* Dedicated logs for debugging failures.
* FTP improvement: Test FTP added , FTP profile name save issue solved.
* Improved UI for better user experience.

= 1.2.2 =
* WP 5.7.2 tested OK.
* Improvement: Error handling improved, internal memory optimization.

= 1.2.1 =
* WP 5.7.1 tested OK.
* Improvement: Error handling improved, Content Update.
* Bug Fix :- __PHP_Incomplete_Class export.

= 1.2.0 =
* WP 5.6.1 tested OK.
* Improvement: FTP, Google Drive, and Amazon S3 import/export added

= 1.1.7 =
* WP 5.4 tested OK.

= 1.1.6 =
* Blocked direct access to the backup zip file via http.

= 1.1.5 =
* Optimized access control.

= 1.1.4 =
* Improvement: Added filter wt_mgdp_exclude_extensions to exclude file types from export.

= 1.1.3 =
* Tested OK with WordPress 5.3

= 1.1.2 =
* UI improvement.
* Added filter wt_mgdp_exclude_files to exclude files/directories from export.

= 1.1.1 =
* Bug Fix: Update write permission failure on export.
= 1.1.0 =
* Tested OK with WordPress 5.2.
= 1.0.9 =
* Tested OK with WordPress 5.1.1.
= 1.0.8 =
* Tested OK with WordPress 5.0.2.
= 1.0.7 =
* Bug Fix: Flashing progress bar.
= 1.0.6 =
* Feedback content updates.
= 1.0.5 =
* Feedback content updates.
= 1.0.4 =
* Backup file url update.
= 1.0.3 =
* Backup file upload validation.
= 1.0.2 =
* German translation added.
= 1.0.1 =
* Content changes.
= 1.0.0 =
* Revamped version
= 0.0.2 =
* Initial commit
= 0.0.1 =
* Initial commit

== Upgrade notice ==

= 1.4.8 =
* Security updates (reported by Joshua)
* Tested OK with WordPress 6.4.3