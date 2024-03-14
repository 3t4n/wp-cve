=== Library Viewer ===
Contributors: pexlechris
Plugin Name: Library Viewer
Plugin URI: https://www.pexlechris.dev/library-viewer
Author: Pexle Chris
Author URI: https://www.pexlechris.dev
Tags: FTP, file manager, file list, download manager
Version: 2.0.6.3
Stable tag: 2.0.6.3
Requires at least: 3.0.0
Tested up to: 6.3.1
Requires PHP: 5.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a File & Folder Viewer of FTP folder: yoursite.com/library .
So using the shortcode [library-viewer], you can print the containing folders & files of your library in front-end.

Copyrights & License:
Copyright 2021 Pexle Chris(email: info@pexlechris.dev)

Library Viewer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

Library Viewer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA


== Description ==

Spoiler:
[LIBRARY VIEWER FILE MANAGER ADD-ON](https://www.pexlechris.dev/library-viewer/fm-wp) has been released! Check it ;-)

With Library Viewer, you can display the containing files and the containing folders of a "specific folder" of your (FTP) server to your users in the front-end.

The **significant difference** from other similar plugins is that:
1. You can allow users to **view that the files exist**, but **cannot open them if they are not logged in** (or if they are not administrators, or authors etc...).
2. You can allow users to view files in a **custom viewer or redirect them** through a RESTful web service of your choice(examples exists below).

[DEMO](https://www.pexlechris.dev/library-viewer/demo-wp)

For this plugin (the free version), the "specific folder" is the folder
"library" of your httpdocs(yoursite.com/library).
If you want to display other folder (and its files) that isn't contained in yoursite.com/library , you need to use the path parameter of [Library Viewer Pro](https://www.pexlechris.dev/library-viewer/pro-wp).

This plugin adds the [library-viewer] shortcode in your WordPress site!
So the only thing that you must do to display the folders and files in the front-end is to add this shortcode in a post, page, widget etc.

The [library-viewer] shortcode get **optional parameters** that extend the functionality of plugin:

== Parameters Documentation ==
&nbsp;&nbsp;[PARAMETERS DOCUMENTATION AND USE CASES](https://www.pexlechris.dev/library-viewer/parameters-wp)

&nbsp;&nbsp;**PARAMETERS OF LIBRARY VIEWER**

* **have_file_access** (have_file_access parameter determines which user have access to view the files.)
* **my_doc_viewer** (my_doc_viewer parameter determines in which viewer the file will be opened.)
* **login_page** (login_page parameter defines the login page that user will be redirected -if need it-, to log in.)

&nbsp;&nbsp;**PARAMETERS OF [LIBRARY VIEWER PRO](https://www.pexlechris.dev/library-viewer/pro-wp)**

* **path** (path parameter allow us to choose what folder we want to display in the Library in the front-end. When we say, folder, we mean folder's contents i.e. containing folders and files of this folder.)
* **waiting_seconds** (waiting_seconds parameter sets the seconds of user is waiting the redirection to login and see the file (0: for instant redirect).)
* **breadcrumb** (breadcrumb parameter determines if breadcrumb will be displayed in the Library in front-end or not.)
* **hidden_folders** (hidden_folders determines which folders will not be displayed and will not be accessible by Library in the front-end.)
* **shown_folders** (shown_folders parameter determines which folders will be displayed and will be accessible by Library in the front-end.)
* **hidden_files** (hidden_files determines which files will not be displayed and will not be accessible by Library in the front-end.)
* **shown_files** (shown_files parameter determines which files will be displayed and will be accessible by Library in the front-end.)
* **url_suffix** (url_suffix allow you to add a suffix in the URL, so you can use the [library-viewer] shortcode more than one time in the same page.)

&nbsp;&nbsp;**PARAMETERS OF [LIBRARY VIEWER FILE MANAGER ADD-ON](https://www.pexlechris.dev/library-viewer/fm-wp)**

* **delete_folder** (delete_folder parameter determines which user can delete a folder.)
* **delete_file** (delete_file parameter determines which user can delete a file.)
* **rename_folder** (rename_folder parameter determines which user can rename a folder.)
* **rename_file** (rename_folder parameter determines which user can rename a file.)
* **create_folder** (create_folder parameter determines which user can create a folder.)
* **upload_file** (upload_file parameter determines which user can upload a file.)
* **unzip_file** (unzip_file parameter determines which user can unzip a zip file.)
* **download_folder** (download_folder parameter determines which user can download a folder as a zip file.)
* **download_file** (download_file parameter determines which user can download a file.)

&nbsp;
[PARAMETERS DOCUMENTATION AND USE CASES](https://www.pexlechris.dev/library-viewer/parameters-wp)

&nbsp;

== Hooks Documentation ==

From 2.0.0 version and then, there are many hooks that you can customize the functionality of this plugin.
You can read more in [HOOKS DOCUMENTATION](https://www.pexlechris.dev/library-viewer/hooks-wp)
Read also [how to add PHP hooks in your WordPress Site in my blog](https://www.pexlechris.dev/how-to-add-php-hooks-in-your-wordpress-site)



== Other Details ==

* The algorithm does not show in the front-end folders that contains in their name the string "hidden-folder".
  Also does not show .php , .ini files and files that contains in their name the string "hidden-file".
  So if you don't want to display an existing folder or file, you can rename it appropriately!
  In addition, With **Library Viewer Pro**, you can **set the names that you don't (and you do) want to be displayed** in the front-end using appropriate shortcode parameters.
* If you want to add text above the front-end folders or below the front-end files, view more in the FAQ below.
* In addition, with the **[LIBRARY VIEWER PRO](https://www.pexlechris.dev/library-viewer/pro-wp)** you can,
 - customize the URLs of library viewer, with the hooks and the parameters.
 - you can display folders & files of a directory (in FTP) of your choice, **not only library directory** (yoursite.com/library)
 - you can hide the breadcrumb with just a shortcode parameter.
 - you can show/hide the folders and files of your choice.
* Also, with the **[LIBRARY VIEWER FILE MANAGER ADD-ON](https://www.pexlechris.dev/library-viewer/fm-wp)** you can,
 - give the ability to your users to have their own library and to upload and edit files.
 - use the library as file manager for your admins (you may need also Library Viewer Pro, and to restrct the WP page from other users)



== Screenshots ==

1. library folder must be located in the root of your FTP server
2. Not all files and folders are displaying in the front-end Library Viewer because of their special names (hidden-folder, hidden-file, .php etc.)
3. The string-value of the $text_at_beginning variable is displayed between the breadcrumb and the folders, the string-value of the $text_at_end variable is displayed below the folders & files.
4. With Library Viewer File Manager Add-On, you can give the ability to some of your users to manage the library from the front-end.

== Frequently Asked Questions ==

 = Can I forbid the direct access in the files of the library? I want only via library files can be accessible. =
 With Library Viewer Pro, you can! See this support topic: [wordpress.org/support/topic/executable-pdf-file](https://wordpress.org/support/topic/executable-pdf-file/)


 = How can I deny users to execute php files in folders of my library? =
 You need to add the following code in the .htaccess file of the folder that you want to deny users execute php files
 `
 <Files *.php>
 deny from all
 </Files>
 `


 = Can I hide an existing folder or file of FTP folder from the front-end library? =
 Yes. Please read carefully the section "Other Details" of plugin.


 = Are there shortcode examples? =
 You can test your own use cases in the [DEMO](https://www.pexlechris.dev/library-viewer/demo-wp)


 = Which Page Builders are compatible with Library Viewer? =
 Library Viewer have been tested with TinyMCE (Classic Editor), Gutenberg, WPBakery, Visual Composer, Elementor and works fine!
 Generally can be used, everywhere that shortcodes are accepted...


 = Library Viewer does not work properly and/or I get some ERRORS. Why? =
 - Check your permalinks PLAIN PERMALINKS ARE NOT SUPPORTED. Please change your permalink from /wp-admin/options-permalink.php to something else.
 - Check the folders' and files' read permissions (safe choice is to use 644)
 - If you use the plugin **Remove Uppercase Ascents** and a CSS code like *.library-viewer--folder{text-transform: uppercase;}* maybe this cause the problem. The solution in this case is to use instead this CSS code: .library-viewer--folder h3 a{text-transform: uppercase;}
- Check if the file or folder has special characters in its name. Some are not supported as names of folders and files such as %.
 In this case, contact me via [email](mailto:info@pexlechris.dev) or via [support forum](https://wordpress.org/support/plugin/library-viewer/) to find a solution!
 - If you use the plugin **Remove Uppercase Ascents** and a CSS code like *.library-viewer--folder{text-transform: uppercase;}* maybe this cause the problem. The solution in this case is to use instead this CSS code: .library-viewer--folder h3 a{text-transform: uppercase;}
 - For other problems, you can open a support ticket in [support forum](https://wordpress.org/support/plugin/library-viewer/)


 = Can I add my custom text inside a folder of front-end library viewer? =
 Yes. If you want to add text above the front-end folders or below the front-end files, you can create via FTP a file with name "include.php" in the FTP folder that you want texts to be shown in front-end.
 HTML tags are allowed!
 Your texts must be values of php variables ($text_at_beginning , $text_at_end respectively) as you can see below:
 `
 <?php
 $text_at_beginning = "My text above front-end folders";
 $text_at_end = "My text below front-end files";
 ?>
 `
 &nbsp;
 Also, you can use the hooks `lv_folder_text_at_beginning` and `lv_folder_text_at_end` respectively for this scope.


 = How to upload files and create new folders? =
 You can do this via FTP/cPanel or you can buy the **[Library Viewer File Manager Add-on](https://www.pexlechris.dev/library-viewer/fm-wp)** to manage the folder from the front-end.


 = Is Library Viewer' file viewer supports all mime types (file extensions)?
 From 1.1.2, the Library Viewer' file viewer supports all mime types that wordpress supports.
 These that included in the function: wp_get_mime_types()
 If you want to add support for mime types that are not included, use the WP filter: lv_mime_types to include them.
 Read more in [HOOKS DOCUMENTATION](https://www.pexlechris.dev/library-viewer/hooks-wp#lv_mime_types)
 Read also [how to add PHP hooks in your WordPress Site in my blog](https://www.pexlechris.dev/how-to-add-php-hooks-in-your-wordpress-site)


 = I want all files to be downloaded. Is that possible?
 Yes, you need to use the Library Viewer' file viewer (my_doc_viewer="library-viewer") and to add the following hook in your functions.php
 `
 add_filter('lv_mime_types', function(){
	return array();
 });
 `
 Read [how to add PHP hooks in your WordPress Site in my blog](https://www.pexlechris.dev/how-to-add-php-hooks-in-your-wordpress-site)


 = Can I change the colors or the fonts that plugin uses? =
 Yes. But only with plain CSS at the moment. So you can add your custom css from WP customizer (from Additional CSS)


 = I have a proposal for a new functionality of this plugin. Can I suggest it to you? =
 Yes. I need new ideas to improve my plugin. Send it to me via <a href="mailto:info@pexlechris.dev">email</a> or via [support forum](https://wordpress.org/support/plugin/library-viewer/)


== Installation ==

1. Download the plugin from [Official WP Plugin Repository](https://wordpress.org/plugins/library-viewer/)
2. Upload Plugin from your WP Dashboard ( Plugins>Add New>Upload Plugin ) the library-viewer.zip file.
3. Activate the plugin through the 'Plugins' menu in WordPress Dashboard
4. Add to a new or existing page/post (or widget etc.) the shortcodes [library-viewer] with the parameters of your choice.
5. Create the folder library and put files and folders there.



== Changelog ==
 = 2.0.6.3 =
* [Bug Fix]: Fix bug of Library Viewer Pro, if path parameter contains spaces.

 = 2.0.6.2 =
* [Bug Fix]: Fix of not playing mp4/mp3 files in some cases.

 = 2.0.6.1 =
* Tested up to WP 6.2
* Potential vulnerability fixed: This could allow a malicious actor to redirect users from one site to the other due to the redirect URL not being validated. Users could be tricked to visiting a legitimate site to then be redirected to a malicious site and cause a phishing incident.
* Potential vulnerability fixed: The plugin did not validate and escape some of its shortcode attributes before outputting them back in a page/post where the shortcode is embed, which could allow users with the contributor role and above to perform Stored Cross-Site Scripting attacks.
* Thanks [Mika](https://www.buymeacoffee.com/mikadminfr) for reporting issues


 = 2.0.6 =
* Tested up to WP 6.1.1
* Required PHP: 5.6
* [Bug Fix]: Fix of logout conflict in some cases.


 = 2.0.5 =
* [New]: 2 new globals values added in the File class. file_folder_real_path, file_folder_abs_path
* [New]: html attribute `library-viewer-name` has been added in the div with class `library-viewer--container`
* [Enhancement]: Better message if a shortcode used more than 1 times in the same page.
* [Bug Fix]: Fix a minor php warning when viewing a file with plugin's file viewer
* [Bug Fix]: Compatibility fixed with Library Viewer Pro


 = 2.0.4 =
* Tested up to WP 5.9.2
* [Bug Fix]: Compatibility fixed with Library Viewer File Manager Add-On
* [Bug Fix]: Load textdomain in order to be able to get translations from wordpress.org

 = 2.0.3 =
* Tested up to WP 5.8.1
* [New]: `lv_filter_global_{$parameter}` filter introduced. With this filter, you can filter the parameters BEFORE the rest globals' initialization.
* [New]: `lv_breadcrumb_html` filter introduced. With this filter, you can filter the html of whole breadcrumb.
* [New]: If `library` folder doesn't exist, will be created automatically when the shortcode will called in the front-end.
* [Bug Fix]: In the $globals array that was passed in the hooks, value `current_viewer` was not existed. Now exists.
* [Enhancement]: /languages/library-viewer.pot language template file has been created.
* [Enhancement]: On the filter `lv_file_anchor_html`, the variable $file_anchor_href has been also added in the array $file (2nd parameter). View hook' documentation for more info.
* [Enhancement]: `.library-viewer--folder h3{margin-top: 0; display: inline-block;}` css has been added.
* [Enhancement]: $file_abs_path is added in the $all_files parameter ( $all_files['file_abs_path'] ) in the parameters of hooks: lv_containing_files, lv_file_icon_html, lv_file_html, lv_before_file, lv_after_file.
* [Enhancement]: File icon <span> element has been moved into the <a> html element.
* [Deprecated]: The filter `lv_shortcode_class_name` has been replaced by `lv_shortcode_class_names`. This is an advanced hook...
* [Deprecated]: The filter `lv_file_viewer_class_name` has been replaced by `lv_file_viewer_class_names`. This is an advanced hook...

 = 2.0.2 =
* [Deprecated]: `breadcrumb` value has been removed from Library Viewer globals parameter of all hooks. From now, there is only in Library Viewer Pro's hooks
* [Bug Fix]: Fix compatibility with Library Viewer Pro 2.0.1

 = 2.0.1 =
* [Bug Fix]: Fix bug of Library Viewer Pro. Files weren't opened...

 = 2.0.0 =
* Tested up to WP 5.7
* [Enhancement]: Add compatibility for symbols #, ? for file names and folder names of your library
* [Enhancement]: Security update: Hidden folders (that have in their name the string 'hidden-folder') and hidden-files (that have in their name the string 'hidden-ile'), now,
  are not accessible, if you know the full path of the hidden folder/file.
* [Enhancement]: Now the file link is being encoded and then is appended to the `my_doc_viewer` parameter. If you don't want to be encoded use `lv_my_doc_viewer_file_encoded` filter.
* [Deprecated]: `library-viewer--current-breadcrumb-item` class removed from breadcrumb current item. Replaced with the CSS rule `.library-viewer--breadcrumb-item:last-of-type`
Hooks:
* [Deprecated]: `LV__folder_was_viewed` action replaced with `lv_folder_was_viewed` action.
* [Deprecated]: `LV__array_replace_to__in_foldernames` filter replaced with `lv_folder_fake_path_symbols` filter.
* [Deprecated]: `LV__array_replace_from__in_foldernames` filter replaced with `lv_folder_real_path_symbols` filter.
* [Deprecated]: `LV__array_replace_to__in_filenames` filter replaced with `lv_file_fake_path_symbols` filter.
* [Deprecated]: `LV__array_replace_from__in_filenames` filter replaced with `lv_file_real_path_symbols` filter.
* [Deprecated]: `LV__folder_html` filter replaced with `lv_folder_html` filter.
* [Deprecated]: `LV__file_html` filter replaced with `lv_file_html` filter.
* [Deprecated]: `LV__file_was_viewed` filter replaced with `lv_file_was_viewed` filter.
* [New]: `lv_file_identifier` filter introduced. With this you can change the '/LV/' that is the part of URL of a file.
* [New]: `lv_before_breadcrumb_start` action introduced.
* [New]: `lv_after_breadcrumb_start` action introduced.
* [New]: `lv_breadcrumb_folder_delimiter_html` action introduced. You can change the delimiter of folders of breadcrumb.
* [New]: `lv_breadcrumb_items` action introduced. With this filter, you can alter the breadcrumb items, for example the folder name and folder fake link.
* [New]: `lv_before_breadcrumb_end` action introduced.
* [New]: `lv_after_breadcrumb_end` action introduced.
* [New]: `lv_empty_folder_html` filter introduced. If the current folder contains neither files nor folders, an equivalent message will be displayed an with filter. With this filter you can change it.
* [New]: `lv_folder_text_at_beginning` filter introduced. This filter allow us to add or change the text at beginning of the folder, i.e. the text before the first containing folder.
* [New]: `lv_containing_folders` filter introduced. Containing folders of current folder filter.
* [New]: `lv_folder_icon_html` filter introduced. Used to filter the html of folder icon.
* [New]: `lv_folder_html` filter introduced. Used to filter the html output of printed folder.
* [New]: `lv_before_folder` action introduced.
* [New]: `lv_after_folder` action introduced.
* [New]: `lv_containing_files` filter introduced. Containing files of current folder filter.
* [New]: `lv_file_icon_html` filter introduced. Used to set a file icon using php.
* [New]: `lv_file_html` filter introduced. Used to filter the html output of printed file.
* [New]: `lv_before_file` action introduced.
* [New]: `lv_after_file` action introduced.
* [New]: `lv_folder_text_at_end` filter introduced. This filter allow us to add or change the text at end of the folder, i.e. the text after the last containing file.
* [New]: `lv_folder_was_viewed` action introduced. Do some actions if a folder was accessed/viewed.
* [New]: `lv_file_was_viewed` action introduced. Do some actions if a file was accessed/viewed.
* [New]: Filter `lv_my_doc_viewer_file_encoded` introduced. With this filter you can determine if the file will be appended to `my_doc_viewer` as encoded or not default is true (encoded).
* [New]: Filter `lv_mime_types` introduced. If you want to add support for mime types that are not included, use this filter.

 = 1.2.3 =
* Tested up to WP 5.6
* [Enhancement]: In filter `LV__folder_html` introduced the $attributes parameter
* [New]: filter `LV__file_html` introduced

 = 1.2.2 =
* Tested up to WP 5.5.3
* [Enhancement]: Change Library Viewer Pro URL in plugins' page on dashboard

 = 1.2.1 =
* [Bug Fix]: False Positive: shortcode [library-viewer] seams to be used more than 1 times in the same page, but not

 = 1.2.0 =
* Tested up to WP 5.5.1
* [New]: LV__folder_was_viewed wordpress action was added in the code
* [Enhancement]: From 1.2.0, the shortcode settings are saved in database, not in files. Also, the folder /wp-content/uploads/library-viewer will be deleted!  
* [Bug Fix]: Now Library Viewer' shortcode is supported in the homepage too
* [New]: library-viewer has been added to the available values that my_doc_viewer can get
* [Bug Fix in PRO]: The shortcode [library-viewer] cannot be used more than 1 times in the same page. This feature is available in Library Viewer Pro

 = 1.1.2 =
* LV__mime_types wordpress filter was added in the code
* LV__file_was_viewed wordpress action was added in the code
* Tested up to WP 5.4.2

 = 1.1.1 =
* Some errors has been fixed!

 = 1.1.0 =
* now is possible to restrict users from open files by a **capability** using the have_file_access parameter
* php die() replaced by wp_die() for more pretty messages
* enhancement in code
* delete folder library-viewer of your uploads folder on uninstall
* now you can more easily add an icon in the front of a file using CSS

 = 1.0.7 =
*	Library Viewer has been tested up to WP 5.3.2
*	PHP Notices fixed


= 1.0.6 =
*	Folders icons NOW are printed by css background-image attribute
*	Compatibility with sites that exist in a subdirectory fixed


 = 1.0.5 =
*	SECURITY PATCH (Please update NOW)


 = 1.0.3 =
*	Library Viewer has been tested up to WP 5.2.3
*	readme file was translated in Greek
*	Compatibility with Visual Composer have been tested and works fine
*	Instruction to fix the conflict with Remove Uppercase Ascents Plugin added in FAQ
*	Go Back button have been added in error messages
 
 
 = 1.0.2 =
 
*	Library Viewer has been tested up to WP 5.2.2
*	Link notice for Library Viewer Pro has been added in the backend (WP Plugins Page) 
*	Plugin URI has been fixed
*	A screenshot has been added in the Official WP Page of Library Viewer Plugin
*	Minor typo fixes in the readme file and Official WP Page of Library Viewer Plugin


 = 1.0.1 =
 
*	Compatibility have added for most common special characters(**+** , **&** , **'** , **.**)
*	Redirect waiting time to login is now 5 seconds (if you want to change this you need to buy the [Library Viewer Pro](https://www.pexlechris.dev/library-viewer/pro-wp))
*	The ability of encryption of the real path of your folder (with hash technique) moved to [Library Viewer Pro](https://www.pexlechris.dev/library-viewer/pro-wp)


 = 1.0.0 =
*	Initial Release.
