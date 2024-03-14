=== Filr - Secure document library ===
Contributors: patrickposner
Tags: document library, file library, protect uploads, file protection, upload protection, upload directory protection, prevent direct access
Requires at least: 3.5
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.2.3.7
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

Filr - the secure document library

Create and manage secure document and file libraries has never been easier.

Filr creates protected areas on your file system and let you easily upload, manage and display documents files on your website.

You can decide if you want to share a unique direct download link to a document library or display a complete library of docs and files on your website.

== Shortcode ==

Filr includes a shortcode to output a document and file library with a search, filters and a pagination.

Use the shortcode: `[filr library="library-a"]` and show a list of your documents and files.

== Metadata ==

Easily display the name, the latest modification date, file type and download link in your library.

== Security ==

Check the security status within the settings and choose between different settings.

Filr includes a small status tool to show you if your server is missing any requirements for Filr to work.

== Customizable ==

Reorder the columns, modify colors, texts and include or exclude specific rows from your library shortcode.

You can also set an alternative text for empty libraries to give your users further explanations.

== Auto cleaner ==

To prevent your filesystem for any clutter, Filr has a built-in file cleaner.

It takes care that no unecessary files are left on your filesystem.
You never need to open your FTP tool again.

== Features ==

* create files and libraries and show them to your users
* Protect upload directories with .htaccess and index.php files
* Create unlimited uploads and get unique access links
* configure, style and customize the shortcode for your document library
* copy and paste your shortcodes from the "Libary" admin area
* These features are all available within the free version.*


== Pro Version ==

Filr Pro enhances capabilities with more advanced features. Encryption, expire download functionality, and multiple uploads with automatic zip compression and restrict access to files on a user or user role level.

[youtube https://www.youtube.com/watch?v=n1zgHauBsgY]

=== PRO Features ===

* support for external files
* frontend uploader
* restrict file access by user (email) or user role
* filename Encryption
* expire uploads by the number of downloads
* expire uploads by a specific date
* upload multiple files with the uploader and automatically zip them
* more customizations for the shortcode
* use a custom directory name for your uploads
* folder management
* password-protected ZIP files

Paired with exceptional support directly from the developer, timely updates, new feature integrations and extensive documentation you can't go wrong with Filr Pro.

Get it now on [patrickposner.com](https://patrickposner.com/filr/)

**Documentation**

I regulary optimize the documentation and release extensive tutorials on how to use Filr in a multitude of use-cases.

Learn more on [patrickposner.com/filr/docs](https://patrickposner.com/filr/docs/)


== How to use ==

After installation and activation go to Filr -> Settings and configure your uploads folder and check the server settings.

When you are done, create your first file with "New File", give it a title, upload your file and after saving it you can copy the download link.

Otherwise assign it to a library and copy the shortcode to display it on your website.

== Support ==

The free support is exclusively limited to the wordpress.org support forum.

=== CODING STANDARDS MADE IN GERMANY ===

Filr is coded with modern PHP and WordPress standards in mind. It’s fully OOP coded. It’s highly extendable for developers through several actions and filter hooks.

Filr has your website performance in mind -  every script and style is minified and loaded conditionally.


=== MULTI-LANGUAGE ===

Filr is completely translatable with WPML and Polylang.
Simply use the language switcher and translate all settings.

== Installation ==

= Default Method =
1. Go to Settings > Plugins in your administrator panel.
1. Click `Add New`
1. Search for Qr
1. Click install.

= Easy Method =
1. Download the zip file.
1. Login to your `Dashboard`
1. Open your plugins bar and click `Add New`
1. Click the `upload tab`
1. Choose `filr-protection` from your downloads folder
1. Click `Install Now`
1. All done, now just activate the plugin
1. Go to Filr and create restricted media links.

= Old Method =
1. Upload `filr-protection` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Screenshots ==

1. Filr library shortcode
2. Filr files admin
3. Filr uploader
4. Filr create library

== Changelog ==

= 1.2.3.7 =

* SDK upgrade
* readme improvements

= 1.2.3.6 =

* WordPress 6.4 compatibility

= 1.2.3.5 =

* expired download 0 <= comparison
* improved expired download handling

= 1.2.3.4 =

* fixed security issue with file uploads
* fixed PHP notice with file link
* fixed decrease download count detection
* upgraded Freemius SDK

= 1.2.3.3 =

* WP 6.3 compatibility

= 1.2.3.2 =

* improved Freemius integration
* added filter to change filename

= 1.2.3.1 =

* Fixed frontend uploader upload to specific folder

= 1.2.3 =

* added filter filr_access_allowed to allow implementing custom validation for file access
* Freemius SDK update to 2.5.10

= 1.2.2.9 =

* updated uploader scripts
* fixed publish date in folders
* check for file_exists before getting file size

= 1.2.2.8 =

* clear filename on reload within frontend uploader
* fixed secure URLs with folders

= 1.2.2.7 =

* fixed size calculation from admin uploads

= 1.2.2.6 =

* auto-zip for frontend uploads
* fixed secure URL feature with frontend uploads
* improved visibilty for frontend uploads

= 1.2.2.5 =

* bugfix: reload with frontend upload
* auto-zip for frontend uploads to prevent missing meta on failed ajax requests
* Norwegian translation

= 1.2.2.4 =

 * removed unknown method from create post

= 1.2.2.3 =

* prevent recursion on zipping files in admin

= 1.2.2.2 =

* added filter to dynamically add custom rows in a library
* added dynamic version number
* improved accessibility with ARIA tags (frontend & backend)
* added option to set a custom error message for unallowed access
* cleaned up CSS defaults
* improved responsive design (tables and folders)
* improved frontend uploader capabilities (library condition, fixed user role parameter)
* changed "Rows" to "Columns" - typo
* updated translations

= 1.2.2.1 =

* full security audit
* code refactoring and cleanup
* introduced PHP type hints for all methods and attributes
* improved PHP doc blocks

= 1.2.2 =

* improved german translation
* added filter parameter for uploader only view
* filter for disallowed file types added
* action for expire date comparisons
* cleaned up the free version
* fixed translation for folder buttons

= 1.2.1 =

* added finish translation
* added french translation
* prevent error if files array is empty
* auto cleaner only with filter
* filr_allow_file_access hook for additional permission checkup
* fixed all folders in select menu
* Windows-environment support


= 1.2 =

* target blank option for external files
* user as row (uploaded by) (pro only)
* frontend uploader with file name, folder and library selection (pro only)
* restrict uploaded files by user email and/or role from frontend (pro only)
* notification email after file submission (pro only)
* automatically restrict file from frontend uploader to the user
* Added option to encrypt File ID in secure URLs

= 1.1 =

* mobile design
* secure download links (pro only)
* options for default sorting
* option to adjust fontsize for folder headline
* enhanced status with max_upload_limits, max_post_size
* setting deactivate search/pagination also applies to folders now
* updated language files
* WP 5.8 compatibility check

= 1.0.0 =

* support for external files (pro only)
* frontend file uploader (pro only)
* filter for replacing the entire directory used by Filr
* better error handling for large files
* prevent error notices when empty files are created

= 0.9.5 =

* better freemius integration
* fixed date sorting

= 0.9.4 =

* decrease remaining on download (ajax) (pro only)
* added file preview for images (pro only)
* added version number (pro only)
* improved markup for easier styling of rows
* improved styles for better theme compatibility
* updated translation
* better fail-safe bootup

= 0.9.3 =

* fixed published/modified date
* class_exists for ZIPArchive to prevent errors
* Added options to toggle search and pagination
* added folder management (pro only)
* fixed decrease download ajax (pro only)
* improved german translation

= 0.9.2 =

fixed restriction by mail
fixed missing min assets for search and sorting

= 0.9.1 =

* SDK bufix which results in fatal error

= 0.9 =

* option to use publish date instead of last modified date
* option to overwrite the download button label
* included password-protection for zip files
* improved user mail and user role restriction
* improved trialing

= 0.8 =
* New shortcode with datatable.just
* More performance improvements
* Modified and optimized strings and localisation
* Option to configure the order of columns
* Additional rows to activate and use (filetype and modification date)
* latest freemius SDK

= 0.7 =
* fixed conditional for expiration
* readme improvments

= 0.6 =
* CSS bugfixes
* readme improvements

= 0.5 =
* Initial release