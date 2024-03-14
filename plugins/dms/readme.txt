=== Plugin Name ===
Contributors: reifsnyderb
Donate link: http://www.blitzenware.com
Tags: document management, records manager, customer file manager, document manager, project management, file sharing, enterprise document control, Distribution, Retrieval & storage, Versioning, Productivity, thumbnails
Requires at least: 4.5
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Complete document management system for documents and projects.  Upload, organize, group, distribute, share, and protect your documents.

== Description ==

Originally designed for use in a law office, the Document Management system plugin is a complete digital filing cabinet.  Files of any format can be uploaded, organized, and shared while access is controlled with a complete permissions system.


Support and Upgrades are available at http://www.blitzenware.com


Features Include:


*    Simple and Intuitive User Interface.
*    Storage for an unlimited number of documents that can be uploaded, managed, secured, and shared with DMS Pro.  (The free version is limited to 500 documents.)
*    Documents can be organized by projects.
*    File upload progress bar with DMS Pro.
*    Localization support is included.
*    Login required to access, manage, and download documents.
*    Documents can be accessed without login with the DMS Pro upgrade.
*    Thumbnail support for document images with the DMS Pro upgrade.
*    Documents can be categorized and searched for via name, owner, or a keyword/properties search system.
*    Up to ten properties fields can be set for the documents.  These properties fields can be used to search for documents.
*    Folders can be managed through a complete permissions system and the sub-folder nesting is unlimited.
*    Documents can be secured with a complete permissions system.
*    The permissions system includes user, group, and everyone permissions.
*    Permissions can be inherited, if so desired.
*    There is no limit to the nesting of sub-folders.
*    The document repository can be moved to increase security.


The "Pro" release is available at http://www.blitzenware.com and adds the following features:

*    Support for an unlimited number of documents.
*    User, document, and folder auditing.
*    Version control of documents.  Old versions will be stored and can be accessed if need be.
*    Documents can be checked-out and checked-in by users so as to lock them when they are being edited.
*    Public access to documents can be granted.
*    Document summaries displayed on the Properties Search page.
*    The title can be set by the administrator.
*    Users, or groups of users, can be notified by e-mail if a document changes in a folder.
*    Mass importation of documents by copying an entire tree of folders and/or documents up to the web server.
*    Documents can be accessed and/or imported from the Media Library.
*    Document comments can be displayed on the main screen to provide more information about the document.
*    Documents can be placed in lifecycles where they go through multiple stages of review and have permissions automatically set per each stage.

Planned additions to the "Pro" version:

*    Document Template System.
*    Subscriptions system for documents.  Users can be notified, by e-mail, of document changes.




== Installation ==

1. Upload the 'dms' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new page for the DMS through the 'Pages' menu in Wordpress.
4. Place the shortcode '[document_management_system]' on the page created in number 3, above.
5. In the Appearance/Menus screen add the DMS page to the menu of your choice.
6. Navigate to the DMS page.  If there are any messages, resolve them on the server.  They can usually be resolved by ensuring that the DMS Plugin can write to the locations in the message.  This is done by ensuring that the web server can write to these locations.
7. [VERY IMPORTANT] Click on the "Configure" Button at the bottom of the screen.  You can either make configuration changes here or exit this screen and the system will be ready for use.  This step is critical as some settings are automatically set and DMS module will not function without it.


== Frequently Asked Questions ==

= Does the Pro Version require the free DMS plugin? =
The both the free DMS plugin and the Pro upgrade must be installed for the Pro upgrade to work.

= How do I move the document repository? =
To move the document repository, do the following:
1.  On the server, move the current document respository folder to it's new location.  By default, prior to version 1.12, it was at wp-content/plugins/dms/repository
2.  On the Document Management System page, in Wordpress, click on the "Configuration" button at the bottom of the page.
3.  Under "Document Repository", "Document Storage Path", change the path to the new path.  The new path will vary for your installation.  One example is /var/www/wordpress-4.5/wp-content/uploads/dms_repository

== Screenshots ==

1. The main screen with one folder and one file.  The "Administrator" button is only visible to administrators.  Note:  DMS Pro is required to display image thumbnails.
2. The screen for creating and editing user permissions groups.
3. The File Options Screen.  The "Comments" section and revision control features are from the Pro version.  The Folder Options Screen is similar and also has the permissions system.
4. This is the Properties Search Screen.  The "Keyword(s)" and "Area of Practice" fields can be added by an administrator.
5. The Import Document Screen.  You can set the initial version and searchable properties when you import a document.  Note:  DMS Pro is required to display the upload progress bar.
6. Document Auditing Screen on the Pro version.
7. User Auditing Screen on the Pro version.
8. The document Check-In screen on the Pro Version.

== Changelog ==

= 1.24 =
* Fixed security issue with configuration page.  Only administrators can access this page.
* Fixed security issue with diagnostic page.  Only administrators can access this page.

= 1.23 =
* Commented out a piece of debugging code that was accidentally left in in file_retrieve.php.

= 1.22 =
* Fixed a mysqli problem in i_pal_wordpress.php.

= 1.21 =
* Fixed a file retrieval error that resulted in an Internal Server Error 500 when retrieving a file.

= 1.20 =
* PHP 7.x compatiblity fixes.

= 1.17 =
* Added support for lifecycles in future versions of DMS Pro.
* Added error message in the event the wp-content/uploads directory is missing.
* Removed support for mysql_query in favor of mysqli_query.  (mysql_query was deprecated in PHP 5.5.0 and removed in PHP 7.0.0.)
* Fixed minor table outline display problem when folder is empty.
* Fixed warnings regarding EOL found by the WP Engine PHP Compatibility Checker.
* Deleted users are now deleted from both groups and permissions in the DMS.  The admin who deleted the documents is assigned as the owner of any documents formerly owned by the deleted user.
* Added support for permissions and folder subscriptions to be limited to administrators only in DMS Pro.

= 1.16 =
* Added support for the automatic user folder creation in DMS Pro 1.47.
* Minor bug fixes.

= 1.15 =
* Fixed a bug that sometimes prevented the DMS plugin from being propertly displayed.
* Additional localization changes.

= 1.14 =
* Fixed a bug that prevented direct links to documents without first going to the DMS page.
* Added additional support for localization.

= 1.13 =
* Bug Fix for DMS Pro 1.45

= 1.12 =
* Added filename fix to remove additional characters added to the end of a filename by some web browsers.
* In new installations, the document repository will be located in /wp-content/uploads/dms_repository.  For reasons unknown, the repository is sometimes deleted in it's default location in Version 1.11 (and earlier versions) during a version upgrade.

= 1.11 =
* Added configuration support for DMS Pro to display the comments on the main page.
* Minor bug fix on main screen.
* Eliminated duplicates in search.

= 1.10 =
* Fixed a bug where documents uploaded in Google Chrome sometimes do not download properly.

= 1.07 =
* Groups can be renamed.
* Fixed a bug in the Group Editor.

= 1.06 =
* Added installation instructions in WP Dashboard.
* Minor improvements to installation errors.

= 1.05 =
* Support for DMS Pro 1.31
* Direct linking is now supported to both folders and files if the proper permissions are set.
* Better support for Google Chrome with imported documents.

= 1.04 =
* Minor bug fixes.

= 1.03 =
* Bug fix -- Now checks for CSS theme files before attempting to load them.  May increase performance.

= 1.02 =
* Bug fix for PHP 7.
* Adds support for alternate database ports.
* Changes to configuration error messages.

= 1.01 =
* Adds support for mysqli (Prevents mysql messages in PHP 5.5 and required for PHP 7.)

= 1.00 =
* Allows for restoration of folders that have been deleted when they contain documents.
* Minor bug fixes.
* Support for DMS Pro 1.20

= 0.99 =
* User interface improvements.
* Minor bug fixes.
* Minor changes to support DMS Pro 1.10
* Added a diagnostics screen.

= 0.98 =
* Minor bug fixes
* Minor changes to support DMS Pro 1.01
* Added an automated database update system to ensure that the database schema is current when upgrading to newer releases.

= 0.97 =
* Resolved a time-zone issue.
* Resolved a database connection issue that is not present in all environments.

= 0.96 =
* Initial beta release after port from Xoops.


== Upgrade Notice ==

= 1.24 =
* Fixed security issue with configuration page.  Only administrators can access this page.
* Fixed security issue with diagnostic page.  Only administrators can access this page.

= 1.23 =
* Commented out a piece of debugging code that was accidentally left in in file_retrieve.php.

= 1.22 =
* Fixed a mysqli problem in i_pal_wordpress.php.

= 1.21 =
* Fixed a file retrieval error that resulted in an Internal Server Error 500 when retrieving a file.

= 1.20 =
* PHP 7.x compatiblity fixes.

= 1.17 =
* Added support for lifecycles in future versions of DMS Pro.
* Added error message in the event the wp-content/uploads directory is missing.
* Removed support for mysql_query in favor of mysqli_query.  (mysql_query was deprecated in PHP 5.5.0 and removed in PHP 7.0.0.)
* Fixed minor table outline display problem when folder is empty.
* Fixed warnings regarding EOL found by the WP Engine PHP Compatibility Checker.
* Deleted users are now deleted from both groups and permissions in the DMS.  The admin who deleted the documents is assigned as the owner of any documents formerly owned by the deleted user.
* Added support for permissions and folder subscriptions to be limited to administrators only in DMS Pro.

= 1.16 =
* Added support for the automatic user folder creation in DMS Pro 1.47.
* Minor bug fixes.

= 1.15 =
* Fixed a bug that sometimes prevented the DMS plugin from being propertly displayed.
* Additional localization changes.

= 1.14 =
* Fixed a bug that prevented direct links to documents without first going to the DMS page.
* Added additional support for localization.

= 1.13 =
* Bug Fix for DMS Pro 1.45

= 1.12 =
* Added filename fix to remove additional characters added to the end of a filename by some web browsers.
* In new installations, the document repository will be located in /wp-content/uploads/dms_repository.  For reasons unknown, the repository is sometimes deleted in it's default location in Version 1.11 (and earlier versions) during a version upgrade.

= 1.11 =
* Added configuration support for DMS Pro to display the comments on the main page.
* Minor bug fix on main screen.
* Eliminated duplicates in search.

= 1.10 =
* Fixed a bug where documents uploaded in Google Chrome sometimes do not download properly.

= 1.07 =
* Groups can be renamed.
* Fixed a bug in the Group Editor.

= 1.06 =
* Added installation instructions in WP Dashboard.

= 1.05 =
* Support for DMS Pro 1.31
* Direct linking is now supported to both folders and files if the proper permissions are set.
* Better support for Google Chrome with imported documents.

= 1.04 =
* Minor bug fixes.

= 1.03 =
* Bug fix -- Now checks for CSS theme files before attempting to load them.  May increase performance.

= 1.02 =
* Bug fix for PHP 7.
* Adds support for alternate database ports.
* Changes to configuration error messages.

= 1.01 =
* Support added for mysqli (Prevents mysql messages in PHP 5.5 and required for PHP 7.)

= 1.00 =
* Allows for restoration of folders that have been deleted when they contain documents.
* Minor bug fixes.
* Support for DMS Pro 1.20

= 0.99 =
* User interface improvements.
* Minor bug fixes.
* Minor changes to support DMS Pro 1.10
* Added a diagnostics screen.

= 0.98 =
* Minor bug fixes
* Minor changes to support DMS Pro 1.01
* Added an automated database update system to ensure that the database schema is current when upgrading to newer releases.

= 0.97 =
* Bug fixes.

= 0.96 =
* Initial beta release no upgrade available at this time.
