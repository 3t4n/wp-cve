=== WordPress File Sharing Plugin ===
Contributors: deepakkite, mrking2201, upfpro
Tags: file sharing, private files, file upload, frontend, file manager
Requires at least: 6.0
Tested up to: 6.4.3
Stable tag: 2.0.8
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Best WordPress File Sharing Plugin. Upload and share private files/folders with users securely. A dropbox/g-drive like system.

== Description ==

Best WordPress File Sharing plugin that allows admin and site users to upload, manage and share their files with restricted access via frontend.

Is this file sharing plugin for you?

* Do you want to upload and share files with your site users securely that no one else can access?
* Do you want your site users to upload and share files with you or each other in a secure way?

...then yes! User Private Files is perfect for your needs.

User Private Files is a file sharing plugin with frontend file manager that will add a file sharing functionality on your WordPress website just like Dropbox and Google Drive. Your site users will be able to login and upload their files and manage the access to those files. A perfect Frontend File Manager to upload and share private files.

**This is not just a plugin; it's an immersive experience.**

[Demo](https://demo.userprivatefiles.com/dashboard/)

https://www.youtube.com/watch?v=SAs9drmcvis

[All Documentation/Features Videos](https://userprivatefiles.com/documentation/install-plugin-frontend-file-manager-pro/)

The plugin is simple to use and comes with 2 different design shortcodes:

Modern design **“[upf_manager]”**

Classic design (old - limited functionality) has 2 shortcodes - one for uploading files and another for displaying the files that they uploaded and that are Shared with them. We recommend that you use them together on a single page:
Shortcode for uploading form is **“[upf_upload]”**
Shortcode for displaying files is **“[upf_display]”**

Users can add/remove other users from their files by their email addresses. Only the allowed users will be able to view/download those files and unauthentic users will see a permission denied error message (even if they have direct URL to the file).

Your users will be able to manage their files, add a title and note to the uploaded files. They can also delete their uploaded files. Site administrator can view all the files from the WP media library and will have the full control over them.

== Installation ==

You can install the Plugin in two ways.

= WordPress interface installation =

1. Go to plugins in the WordPress admin and click on “Add new”.
2. In the Search box enter “User Private Files” and press Enter.
3. Click on “Install” to install the plugin.
4. Activate the plugin.

= Manual installation =

1. Download and upload the plugin files to the /wp-content/plugins/user-private-files directory from the WordPress plugin repository.
2. Activate the plugin through the "Plugins" screen in WordPress admin area.

== Frequently Asked Questions ==

= Are the files secure? =

Yes, the files are uploaded to a different directory and the files will be accessible only by allowed users or admin. Public URL to a file will show a permission error.

= What are the shortcodes =

For Modern Design:
[upf_manager]

For Classic Design:
Shortcode for uploading form is “[upf_upload]”
Shortcode for displaying files is “[upf_display]”
We recommend that you use them on a single page.

= How do I share a file in WordPress? =

Install WordPress File Sharing plugin and then use the shortcode in a page. Upload a file on frontend and preview that file. Click on share icon to share it with other users.

= Does this plugin modify .htaccess file? =

Yes, the plugin needs to write a rewrite rule to the .htaccess file to allow/deny user files access. You can manually add the code by yourself as well.

= Will the user files appear in the backend? =

Yes, the site administrator can view/delete all the files uploaded by users in the backend in media library.

= How can users share their files? =

Follow this [Video documentation series](https://userprivatefiles.com/documentation/install-plugin-frontend-file-manager-pro/)

= Does the plugin send an email to the users? =

Yes, when adding a user by an email address or username to the file, the plugin sends an email to the target user. There is an option to customize subject and content as well.

= Files type Restrictions? =

Only the images and documnet files are allowed in classic design shortcode.
Image, pdf, doc, zip, audio, video, txt and CSV files are supported in the modern design shortcode.

== Screenshots ==
1. Front-end Dashboard
2. File Preview
3. Create New Folder
4. Upload Files
5. Rename a File
6. Move a File
7. Classic Design - Uploading a file
8. Classic Design - Allow other users by email
9. Classic Design - Shared with me files

== Changelog ==

= 2.0.8 =
* 2024-02-28
* Fixed - Invalid json response when saving the page with upf_display shortcode.

= 2.0.7 =
* 2023-12-08
* [Update] - Updated Font Awesome icons to version 6.

= 2.0.6 =
* 2023-10-12
* [Update] - Updated backend options.

= 2.0.5 =
* 2023-09-22
* [Security] - Added extra permission checks during file/folder requests.

= 2.0.4 =
* 2023-09-02
* [Security] - Vulnerability issue fixed for stored XSS type from the admin settings screen.
* Fixed - Video playback for classic layout.
* Tested with WP version 6.3.1.

= 2.0.3 =
* 2023-07-11
* Fixed emails not being sent issue.

= 2.0.2 =
* 2023-06-01
* Code Cleanup.
* Updated plugin title and description.

= 2.0.1 =
* 2023-04-24
* Huge Update - Current PRO version features included in the free version now.
* New modern full width design option with support for classic design.
* Folders/Directory system – Users can Create / Rename / Delete / Share folders
* Users can upload / move files within different folders
* Option for admin to share files with all users or users with a specific role
* Comments – Users can comment on shared files
* Trash folder functionality
* Email notification functionality
* Interactive Dropbox like design
* Search for files and folders
* Filter shared files by user email address
* And many more features from the PRO version

= 1.1.3 =
* 2022-07-09
* [Security] - Vulnerability issue fixed where not-allowed file types are being uploaded like php files.

= 1.1.2 =
* 2022-03-15
* [Security] - Improved security.

= 1.1.1 =
* 2022-03-13
* [Security] - security issue fixed where user emails might get exposed on the site. Thanks to WP plugin review team!
* [Security] - Improved security with nonce verification.

= 1.1.0 =
* 2022-02-03
* Fixed a reported conflict where uploaded file was not loading.
* Fixed a bug - larger files were getting wrong upload path.

= 1.0.9 =
* 2021-11-15
* Fixed a reported bug for editing uploaded files

= 1.0.8 =
* 2021-11-04
* Fixed the CSS conflict with some theme for file upload button

= 1.0.7 =
* 2021-10-20
* Fixed the popup not showing issue after file uploaded with some theme


== Upgrade Notice ==

== Features ==

* Allow your site users to upload their private files.

* Users can share their files with other users by using their email addresses or usernames.

* Only the allowed users will be able to view/download shared files and unauthentic users or guests will see a permission denied error if they have direct URL to the file.

* Users can remove the allowed users anytime they want.

* Easy to setup for administrator and easy to use for users.

* Front-end dashboard to allow users upload and manage their files.

* Full control over uploaded files to site administrator.

* 2 different designs - Classic and full-width modern design **(NEW)**

* Filter to group files by their type (Classic design only)

* Translation Ready

* Folders system - Users can Create/Rename/Delete and Share folders **(NEW)**

* Users can upload/move files within different folders **(NEW)**

* Admin can share files with single, all users or users with a specific role **(NEW)**

* Comments - Users can comment on shared files **(NEW)**

* Email notification when a file / folder is shared with someone **(NEW)**

* Backend settings to enable/disable email notification and change email subject/content **(NEW)**

* Trash folder functionality **(NEW)**

* Search for files and folders **(NEW)**

* Interactive Dropbox like design **(NEW)**

* Download file option

* Support for file types - image, pdf, doc, zip, audio, video, txt and CSV **(NEW)**

* Support image, pdf preview and video play **(NEW)**

* No page refresh - Fully AJAX

* Restore or Delete files & folders from Trash **(NEW)**

* Beautiful animation & icons

[PRO version Features](https://userprivatefiles.com/)

* Backend file manager for admin to manage files

* Premade folders from backend file manager **(NEW)**

* Backend file manager to view and control other users files without asking them to share with admin

* File preview support for docx, ppt, csv, excel files etc using google doc viewer **(NEW)**

* Google Drive Integration (Premium & Developer versions) **(NEW)**

* Display selected files/folders only by their IDs **(NEW)**

* Custom fields/columns in list view (Premium & Developer versions) **(NEW)**

* Backend option to hide Search, filters, New folder button for particular user roles

* Text Editor Addon to create or edit text files (Premium & Developer versions) **(NEW)**

* Option to Zip an entire folder and download **(NEW)**

* Preview support for zip files **(NEW)**

* Frontend notifications when a file / folder is shared with someone

* Frontend notification when a file is uploaded in shared folder with option to turn ON/OFF **(NEW)**

* Frontend notification when a folder is created in shared folder with option to turn ON/OFF **(NEW)**

* Email notification when a file/folder is uploaded/created in shared folder with optin to turn ON/OFF **(NEW)**

* Backend option to enable/disable frontend notifications

* Copy link to a folder or file and share with other users who have access

* Backend option to allow users to share with role specific & all users

* Backend option to enable/disable file uploading functionality based on roles

* Backend customizer to update colors, thumbnails, and toolbar options

* Grid/List views

* Login functionality when user is not logged in

* Backend options to customize login form

* Sort files/folder

* Collapsible side panels on frontend

* New permission level View and Upload **(NEW)**

* Additional shortcodes to display selected files, target folder uploading, and public files **(NEW)**

* Public Files and Folders section in file manager **(NEW)**

* Add privacy policy link in the right sidebar **(NEW)**

* Description support for folders

* Display total storage used and size of each file in the backend **(NEW)**

== Everything you need in a File Sharing Plugin ==
**Easy to use**
User Private Files is as easy as using your computer. The sleek design gives you the freedom to manage files and folders as you want.

**Compatible with Themes**
We are using Divi theme on our demo sites. It is very compatible with Divi theme. We have tested with some popular themes like DIVI, AVADA, BE, The7, Bridge, UnCode, Salient etc. and there are no issues so far.

**Compatible with Elementor & other Page-Builders**
WordPress File Sharing plugin is compatible with Elementor builder. While editing a page create a new shortcode block and paste the shortcode [upf_manager]. Save the page and preview. Same can be followed for other builders like – Muffin, WPBakery, Visual Composer, Beaver, Divi, SiteOrigin, etc.

**Files are stored in Media Library**
As an admin, you can access all files uploaded by users in the media library. Also, files uploaded by users can be found in /wp-content/uploads/upf-docs/.

**No Limit**
You can upload as many files as you want & as many folders or sub-folders can be created. There is no limit on number of files. This depends on your hosting account.

**No max file-size Limit**
User Private Files plugin do not limit on file size. It depends on your hosting account. If you are unable to upload large files, edit your php.ini or user.ini file and add this line :
upload_max_filesize = 128M
This will limit max file size to 128mb, change it as needed. Sometime it is managed by hosting provider, please contact your hosting provide and as them to increase upload_max_filesize.

**Email Notifications**
An option in backend to configure email template. When someone shares a file/folder with a user/users, they will receive an email notification.

**Frontend Notifications (PRO)**
Users will get a notification on frontend page when a file/folder is shared with them. They can read, delete, and refresh notifications. Clicking on a notification will open that file/folder.

**Copy Link (PRO)**
Users can copy the link to a file/folder and share it with other users. Pasting the link will open that file or folder if they have access to it.

**Share with single user, roles, or all users**
Share your files and folders with a single user, multiple users, a particular user role or all users at once.

**Secure frontend file manager**
Full width front-end file manager and all uploaded files are secure that only allowed users can access.

**Inbuilt login form and customizer (PRO)**
WordPress file sharing plugin comes with a login form which is fully customizable. You can set labels and fields for the login form via the customizer.

Checkout the [Demo](https://demo.userprivatefiles.com/dashboard/) to experience all features.
