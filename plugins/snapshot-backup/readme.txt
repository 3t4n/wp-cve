=== Snapshot Backup ===

Contributors: versluis
Donate link: http://wpguru.co.uk/say-thanks/
Tags: snapshot backup, backup, complete backup, full backup, archive wordpress, air check, ftp backup
Requires at least: 2.7
Tested up to: 3.3.1
Stable tag: 2.1.1

Creates a Snapshot Backup of your entire website and uploads it to an FTP repository.

== Description ==

Creates a Snapshot Backup of your entire website: that's your Database, current WP Core, all your Themes, Plugins and Uploads. The resulting single archive file is then uploaded to an FTP repository of your choice.

You can use one FTP repository for snapshots from various sites and group them using File Prefixes and Subdirectories to tell your snapshots apart.

If you don't have an FTP account you can download the file from your local server at the end of the backup.

== Installation ==

1. Upload the entire folder `snapshot-backup` to the `/wp-content/plugins/` directory. Please do not rename this folder.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enter your FTP details under Dashboard - Snapshot Backup
1. Hit the CREATE BACKUP button, grab a coffee and enjoy piece of mind

FTP Details are optional - if you don't have an FTP account you can leave the details blank and download your snapshot file manually.

== Snapshot Philosophy ==

Archiving dynamic websites isn't all that easy and we all tend to forget that because the web is such a fluid thing. The idea of Snapshot is that you may want to create an 'as is' version of your website for archive purposes. With each click you'll create a *time capsule* of sorts - this could be for legal, sentimental or security reasons.

Other solutions mirror your or snyc your installation. This is a great idea too, however if you only notice a week down the line that your site has been compromised then your synced copy most certainly is too. Snapshot makes it easy to go back to a clean version from x days/weeks/months ago.

== Upgrade Notice ==

If you're upgrading from Version 1.0, please note that the database temp files were accidentally saved in your WP root directory. Have a look for rougue .sql files there - feel free to delete them.

== Frequently Asked Questions ==

= When I change automation setting, the site seems to be unresponsive (though the browser appears to be doing stuff). =

I know, the plugin is eager to create the first automated backup right there and then. It just means WordPress is busy executing the script. It's the same phenomenon that happens when you create a manual Snapshot. Give it a minute and the site will come back to life. 

= When I hit "Create Snapshot Backup" my screen goes blank, but the Wordpress sidebar and header are still here. Is that normal? = 

This happens on older Firefox browsers, since Firefox 5 I haven't seen this problem anymore. While the script is active, your browser should appear to be "loading" and you will receive messages  like "All done - thank you" in a yellow box. Internet Explorer appears to be "busy" loading a page - they all behave slightly differently. Leave the script running and the site will come back to life.

= What's required server side for this plugin to run? =

Since I'm using shell commands to create the archive file, this Plugin only works only on Linux servers - NOT on Windows servers.

= Does this Plugin run on Windows Servers? =

I'm afraid not - you have to be on a Linux server for this to work. I've developed and tested it on CentOS / RHEL.

= I don't have another FTP account. Can I still use this plugin? =

Absolutely - there's a handy download link at the end of the backup procedure so you can save your file locally.
Simply ignore all error messages relating to FTP uploads.

= Are there any plans to integrate storage options other than FTP? =

Yes indeed, I'm planning to add support for cloud based services such as Dropbox and Amazon S3 in the near future. Watch this space!

= Can I do these backups automatically, say via a Cron Job or WP Cron? =

Yes you can! Since Version 2.0 of the plugin you can create regular automatic backups under Snapshot Backup - Automation.

Please note that this feature relies on the WP Cron function, which means you need traffic to trigger this function. If you want to help this along you can call your WordPress index.php file using a standard cron job at regular intervals. 

= How to I restore a snapshot? =

I'm working on an elegant solution for this, but for now you'll need to do this manually.

In a nutshell: 
Download the TAR archive from your repository, unTAR it using your favourite ZIPping tool and upload the contents back into your web hosting directory (overwriting any existing files). You'll also find an .SQL file under wp-content/uploads. That's your database file which needs to be uploaded to your MySQL server (say via phpMyAdmin or BigDump), replacing any existing tables in said database.

If on this occasion you're restoring a snapshot to another domain or subfolder in your existing domain, you will also have to change certain values in your database. We'll leave this for another time - search for Moving Wordpress for detailed instructions on how to do this.

There's a handy article on my website which explains this in more detail: 
http://wpguru.co.uk/2011/04/how-to-restore-your-snapsnot-via-ftp/

== Screenshots ==

1. The Snapshot Backup Admin Menu
2. FTP Details Screen
3. Success Screen: if you see this then your backup was successful.

== Changelog ==

= 2.1.1 =
Switched auto delete order: now old snapshots are deleted before a new one is written.
Corrected spelling mistake on Archiving (thanks to Jordan and Julia for pointing this out).

= 2.1 =
Removed horrible red box from main screen
Added support for custom FTP Port
Improved security: core files can no longer be called directly (thanks to Ed from ait-pro.com)
Fixed bug that spawned automation processes even when deactivated
Test FTP Button now saves as well as tests settings
Blank sub-directory no longer gives error message
Fixed leading slash display bug in Manage Snapshots menu
Added cute Menu Icon (thanks to Dirceau from fasticon.com)
Fixed empty database bug
Added Database File Size in status messages

= 2.0.2 =
Fixed error message related to header function

= 2.0.1 =
Some files for the 2.0 release hadn't copied to the WordPress repository.

= 2.0 =
New Menu Structure
Added Automation
Added Auto Delete function for rolling backups
Added Manage Snapshots feature to display a list of your Snapshots

= 1.6.1 =

Fixed Preflight Restrictions: 
When FTP Details were left blank, the plugin would not complete the backup.
FTP Subdirectory was checked twice which caused an error (thanks Kara!)

= 1.6 =

Created new folder structure as per Wordpress guidlines
Added uninstall.php to delete options from the database upon plugin removal
Removed typo in code to download recent snapshot (thanks Kara!)
Changed FTP upload to passive mode to avoid timeout issues (thanks Kara!)
Added pre-flight checklist for folder permissions and FTP Details

= 1.5 =

Fixed incompatibilities with certain servers in the database readout:
Some users experienced PHP Memory issues, others could not restore the database.
Certified Wordpress 3.1.1 compatibility
Replaced hard-coded paths with dynamic ones
Added permanent download option for most recent Snapshot
Added option to include additional directory - useful for people who have moved wp-content

= 1.4 =

Fixed spurious error message upon database readout

= 1.3 =

Added File Prefix option; if you have one FTP repository you can use it for multiple sites easily
Added File Size Display so you know how big your snapshot is
Eliminated whitespace on user input fields

= 1.2 =

Certified compatibility with Wordpress 3.1.
Password was visible in FTP settings form - it's fixed now.

= 1.1 =

Fixed a nasty bug which saved the database temp file in the wrong place. 

= 1.0 =

Initial Release
