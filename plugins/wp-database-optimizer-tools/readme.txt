=== WP-Database-Optimizer-Tools ===
Contributors: Moyo
Donate link: http://xtremenews.info/wordpress-plugins/wp-database-optimizer-tools/
Tags: mysql, database, backup, optimize
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 0.2

== Description ==

WP-Database-Optimizer helps you to optimize your database by performing some actions for example optimizing tables, deleting revisions and data that can slow that your database. Also 
you can perform backups.

WP Database Optimizer Tools es un plugin el cual ayuda a mejorar el rendimiento de nuestra base de datos, esto es de mucha ayuda cuando sabemos que tenemos muchas visitas y necesitamos aligerar el sitio para un mejor funcionamiento.

Con WP Database Optimizer Tools se puede hacer

    Limpiar las revisiones de post
    Limpiar los autodraft
    Limpiar spam
    Limpiar comentarios no aprovados
    Limpiar la trash de wordpress
    Optimizar las tablas en la base de datos
    Reparar las tablas en la base de datos
    Se puede hacer un backup de la base de datos ( mas adelante pondra opcion para enviar por email el backup)


== Installation ==
1. Extract the wp-Database-optimizer-tools/ folder file to /wp-content/plugins/
1. Activate the plugin at your blog's Admin -> Plugins screen
1. The plugin will attempt to create a directory /wp-content/backup-*/ inside your WordPress directory.
1. You may need to make /wp-content writable (at least temporarily) for it to create this directory. 
   For example:
   `$ cd /wordpress/`
   `$ chgrp www-data wp-content` (where "`www-data`" is the group your FTP client uses)
   `$ chmod g+w wp-content`

== Frequently Asked Questions ==

= How do I restore my database from a backup? =

Briefly, use phpMyAdmin, which is included with most hosting control panels. More details and links to further explanations are [here](http://codex.wordpress.org/Restoring_Your_Database_From_Backup).

= Why are only the core database files backed up by default? =

Because it's a fairly safe bet that the core WordPress files will be successfully backed up.  Plugins vary wildly in the amount of data that they store.  For instance, it's not uncommon for some statistics plugins to have tens of megabytes worth of visitor statistics.  These are not exactly essential items to restore after a catastrophic failure.  Most poeple can reasonably live without this data in their backups.

== Usage ==
1. Click the DB optmizer menu in your WordPress admin area.
2. Select any option to perform to your database
3. To backup your database select DB Backup, select the tables you want to backup and download the SQL file.

   *** SECURITY WARNING ***
   Your database backup contains sensitive information,
   and should not be left on the server for any extended
   period of time.  The "Save to server" delivery method is provided
   as a convenience only.  I will not accept any responsibility
   if other people obtain your backup file.
   *** SECURITY WARNING ***

== Changelog ==

= 0.1 = 
Plugin created

= 0.2 =
Fixed Bugs

== Screenshots ==
1. first screenshot 
2. second screenshot
3. third screenshot

== Upgrade Notice ==
None

== Past Contributors ==
None
