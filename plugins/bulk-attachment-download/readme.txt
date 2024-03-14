=== Bulk Attachment Download ===
Contributors: janwyl, freemius
Tags: bulk, download, media, images, attachments
Requires at least: 4.6.1
Tested up to: 6.4
Stable tag: 1.3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Bulk download selected media or attachment files from your Media Library as a zip file.
Options include: a) Include / exclude intermediate image sizes; b) Keep / collapse the uploads folder structure; c) encrypt and password-protect the downloadable zip files.

== Description ==

A 'Download' option is added to both List and Grid modes in the Media Library.
Choose the attachments you want to export, click the button, and a zip file of those attachments is created that you can then download.

= Selecting for download in List mode =

In List mode the 'Download' option appears in the Bulk Actions dropdown. Select the attachments by checking the checkboxes, choose 'Download' in the dropdown, then click 'Apply'.

= Selecting for download in Grid mode =

In Grid mode, first click the 'Bulk Select' button. Then click on the attachments you want to download and hit the 'Download' button.

= Download options =

Before the zip file is created, you'll see a) how many files will be downloaded, and b) how big the uncompressed files are.
By default, you are also given the option to:

* Include or exclude image intermediate sizes.
* Include in your download the folder structure you use in your uploads folder (e.g. year/month) or have all files downloaded in a single folder.

You can set a maximum (uncompressed) file size to be downloaded in the plugin settings, found in Settings > Media.

By default, zip files are automatically removed in 1 - 2 hours, or you can delete them yourself.

If you want to keep the download files inaccessible to others, you can use the 'Make downloads secure' option in Settings > Media.
This creates a .htaccess file in the folder where the download zip files are kept, preventing direct access.

You can also choose to encrypt and password-protect the zip files. However please note that in most cases the standard Windows zip facility
will not be able to open the files - you will need to use something like 7-Zip instead.

However there's no point in using this feature or the .htaccess one unless you also have some means of preventing direct access to the attachments
themselves in the Uploads folder.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= Where do I find my downloads? =

Click on 'Bulk downloads' under 'Media'.

= In List mode, how can I increase the number of attachments I can download in one go? =

To increase the number of attachments you can see on the screen at once,
click on 'Screen Options' at the top right of the Media Library and increase the 'Number of items per page'.

= What's the maximum number of attachments I can download at once? =

It depends. The theoretical absolute maximum is 999. That's the maximum you can set for 'Number of items per page' in the Media Library (see above).
But there may be other constraints depending on your host setup, such as max script execution time, file size limits and memory limits.
Whether you reach those constraints will depend on the number and size of files you are trying to download in one go.
If you see error messages or your zip file is incomplete or corrupted, try several smaller downloads instead of one big one.

= How long does it take for the zip file to be created? =

That depends on the number and size of files you are downloading, and also on your host setup.
Try downloading smaller numbers of files to get a feel for how long it takes before attempting a large download.

= Who can create downloads? =

Permissions are set so that:
* Anyone who has the capability 'upload_files' can create downloads.
* A user can download an attachment if that user has permission to edit the attachment.
* Only users who have the capability 'manage_options' can download, edit, or delete a download that another user has created.

That means that if the Wordpress default roles and capabilities are being used:
* Administrators and editors can download any attachments.
* Authors and contributors can download only those attachments they uploaded.
* Only administrators can download, edit or delete a download created by another user.

= Why can't I open the password-protected zip files? =

Probably because you are using the standard Windows zip facility, which will not work. Try 7-Zip instead.

= What filters are available? =

* `jabd_max_files_size`. Max download file size limit is set in the plugin settings in Settings > Media,
but if you wanted to set the file size per user you could use this filter.
* `jabd_display_passwords`. Whether or not to store and display passwords is set in the the plugin settings in Settings > Media,
but you can also use this filter for more granular control, for example if you wanted only to display for certain users.
* `jabd_zip_password`. Use this filter to amend a zip file password. One scenario might be to use different fixed passwords for different users or user groups,
as only a single default password can be set using the plugin settings.
* `jabd_file_path_rel_to_uploads`. Used to amend the path of an attachment relative to the uploads folder.
* `jabd_include_original_file`. Used to include or exclude the original file. Example to exclude the original :
```
add_filter( 'jabd_include_original_file', 'mytheme_remove_original_file', 10, 2 );
function mytheme_remove_original_file( $include, $image_post ) {
	return false;
}
```
NB if you exclude the original and don't select intermediate files sizes, you have nothing to download !

* `jabd_include_intermediate_image_size`. Used to include or exclude intermediate image size. Example to include only medium and large sizes :
```
add_filter( 'jabd_include_intermediate_image_size', 'mytheme_amend_int_sizes', 10, 3 );
function mytheme_amend_int_sizes( $include, $int_size, $image_post ) {
	return in_array( $int_size, ['medium', 'large'] );
}
```

== Screenshots ==

1. 'Download' option added to the 'Bulk Actions' dropdown.
2. By default, downloads are stored for 1 - 2 hours before being deleted (although auto-deletion can be disabled).


== Changelog ==

= 1.3.8 =

Release date: 10 December 2023

* Enhancement: Add filters to allow management of which images sizes to include in download.
* Enhancement: Modify popup to allow download directly without navigation to all downloads screen.

= 1.3.7 =

Release date: 6 July 2023

* Maintenance: Update Freemius SDK

= 1.3.6 =

Release date: 7 November 2022

* Bug fix: Fix PHP deprecation warning converting false to array
* Maintenance: Update Freemius SDK

= 1.3.5 =

Release date: 1 March 2022

* Maintenance: Update Freemius SDK

= 1.3.4 =

Release date: 24 January 2022

* Bug fix: Fix sprintf format typo causing PHP8.0 error

= 1.3.3 =

Release date: 24 January 2022

* Maintenance: Update Freemius SDK
* Maintenance: Fix deprecated warning for PHP8.0

= 1.3.2 =

Release date: 18 May 2021

* Bug fix: Workaround for Gutenberg plugin issue [#31753](https://github.com/WordPress/gutenberg/issues/31753)

= 1.3.1 =

Release date: 4 January 2021

* Enhancement: Enable bulk downloading for the Media Library Grid mode.
* Maintenance: Refactoring code into a plugin class.

= 1.3.0 =

Release date: 6 December 2020

* Enhancement: Add option to disable auto-deletion of downloads.
* Enhancement: Add option to encrypt and password-protect zip files. props to [@Victor](https://github.com/vfontjr), [@Walter](http://www.joneswebdesigns.com)
* Maintenance: Change zip files location to within Uploads directory
* Maintenance: Update Freemius SDK
* Bug fix: Make compatibile with Formidable Forms Pro

= 1.2.4 =

Release date: 12 September 2020

* Enhancement: Guidance added to Settings page and more notices added to help users.
* Maintenance: Update Freemius SDK
* Maintenance: Tidy up translation strings

= 1.2.3 =

Release date: 19 October 2017

* Bug Fix: Prevent "Can't use method return value" error for php < 5.5.

= 1.2.2 =

Release date: 22 July 2017

* Maintenance: Fix poor placement of file info data when popup is scrollable.
* Maintenance: Update Admin Notice Manager class to latest version.
* Maintenance: Add in request for rating.

= 1.2.1 =

Release date: 15 July 2017

* Maintenance: Delete 'jabd_version' option on uninstall.
* Maintenance: Add in [Freemius](https://freemius.com/wordpress/insights/) functionality for feedback.

= 1.2.0 =

Release date: 25 June 2017

* Maintenance: Change the permissions for downloads so that they match the permissions for managing attachments generally.
* Maintenance: Remove the filter jabd_user_can_download.
* Bug fix: All download post statuses (including private) are now deleted automatically by cron.

= 1.1.4 =

Release date: 12 June 2017

* Bug fix: Properly include admin notice manager class.

= 1.1.3 =

Release date: 12 June 2017

* Bug fix: Remove warning when when adding new post.
* Maintenance: Refactor admin notices.
* Maintenance: Improve activate / deactivate / uninstall security.

= 1.1.2 =

Release date: 1 June 2017

* Maintenance: Added missing translation strings.
* Bug fix: Disable download button on download posts in Bin.

= 1.1.1 =

Release date: 19 January 2017

* Maintenance: Add dismissable reminders that a) bulk download function is only available in list mode and
b) media items per page can be changed using Screen Options.

= 1.1.0 =

Release date: 19 December 2016

* Enhancement: Give option to include intermediate sizes.
* Enhancement: Give option to have all files in single folder in zip instead of replicating structure within uploads folder.
* Enhancement: Setting to limit uncompressed download size. Also display file count and size info before download.
* Maintenance: Fix undefined index notice on saving settings.
* Maintenance: Remove options to 'view' or 'preview' a download as this just triggers a download.

= 1.0.2 =

Release date: 7 December 2016

* Bug fix: rewrite rules now flushed on activation to prevent 404 on attempted download 

= 1.0.1 =

* Initial version on Wordpress.org plugin repository
