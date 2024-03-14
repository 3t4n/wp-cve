=== WP Admin UI ===
Contributors: rainbowgeek
Donate link: https://wpadminui.net/
Tags: admin, ui, custom admin, admin menu, admin bar, metaboxes, widget, columns, profil, color schemes, dashboard, third party plugin
Requires at least: 4.5+
Tested up to: 5.2
Stable tag: 1.9.10
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Admin UI is a powerful plugin to customize almost everything in WordPress administration in just few clicks for specific roles.

== Description ==

<strong>WP Admin UI allow you to customize, optimize and manage:</strong>
<ul>
	<li><strong>Login screen:</strong> custom logo, custom background image, custom css, redirect users to a specific url after logout, disable login by email for users...</li>
	<li><strong>Global settings:</strong> custom admin styles, remove WordPress version/credits in footer, custom favicon, remove help tab, remove screen options tab, disable all WordPress notifications updates, define default view mode in view list, block WordPress admin for specific roles...</li>
	<li><strong>Dashboard:</strong> remove unwanted dashboard widgets, add your own custom widget, disable drag'n'drop widgets...</li>
	<li><strong>Admin menu:</strong> remove menus/submenus, rename menus/submenus, reorder them with drag and drop like a navigation menu...</li>
	<li><strong>Admin bar:</strong> remove unwanted items in admin bar, disable admin bar in front-end...</li>
	<li><strong>Editor:</strong> enable full tinyMCE by default, add buttons, disable WP formatting shortcuts, set a default image alignment, default image size...</li> 
	<li><strong>Media library:</strong> define JPG image quality, add EXIF column, URL column, filters...</li> 
	<li><strong>User Profil:</strong> 8 additionals color schemes...</li>
	<li>...</li>
</ul>

<strong>Import / Export settings</strong>
You manage dozens of websites? Avoid reconfigure everything, thanks to our import / export tool.

<strong>Role Manager</strong>
Apply each setting to specific roles for maximum customization.

<blockquote>

<h3>WP Admin UI : PRO version</h3>

Need more features? Try WP Admin UI Pro right now!

<ul>
	<li><strong>Metaboxes:</strong> remove unwanted metaboxes in custom post type, pages and posts...</li>
	<li><strong>Columns:</strong> remove unwanted columns in view posts, pages and custom post types list...</li>
	<li><strong>Third plugins:</strong> remove WP SEO ads, WPML ads, Gravity Forms, Akismet, WooThemes...</li>
	<li><strong>Themes:</strong> build your own custom admin theme without code</li>
	<li><strong>Mails:</strong> manage WP Mails, use SMTP, change From and Name, add CC, BCC, return path...</li>
	<li><strong>WooCommerce:</strong> customize WooCommerce backend, remove product data like type, tabs...</li>
	<li>and new pro features soon...</li>
</ul>

<a href="https://wpadminui.net/" target="_blank">Check out our +150 options</a>
<br>

</blockquote>

[youtube https://www.youtube.com/watch?v=2oHeaDLzVDw]

<h3>Translation</h3>

<ul>
	<li>English</li>
	<li>French</li>
	<li><a href="https://www.wpadminui.net/contact-us/">Add yours!</a>
</ul>

== Installation ==

1. Upload 'wp-admin-ui' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on WP Admin UI and apply settings.

== Frequently Asked Questions ==
Check our FAQ on <a href="https://www.wpadminui.net/support/faq/" target="_blank">WP Admin UI website</a>

== Screenshots ==
1. WP Admin UI Dashboard
2. WP Admin UI Login screen settings
3. WP Admin UI Global settings
4. WP Admin UI Dashboard settings
5. WP Admin UI Admin menu settings
6. WP Admin UI Admin bar settings
7. WP Admin UI Editor settings
8. WP Admin UI Media Library settings
9. WP Admin UI Profil settings
10. WP Admin UI Role Manager
11. WP Admin UI Import / Export 

== Changelog ==
= 1.9.10 =
* FIX Notice: login_headertitle
= 1.9.9 =
* INFO Improve sanitization of textarea
* INFO Update updater
= 1.9.8.3 =
* FIX Login URL detection
= 1.9.8.2 =
* INFO Improve login URL detection (thanks to @nubess)
= 1.9.8.1 =
* FIX mb_convert_encoding
= 1.9.8 =
* FIX Login URL (thanks to @nubess)
= 1.9.7.1 =
* INFO Improve translations
* FIX Improve security
= 1.9.7 =
* FIX Text-domain
* FIX Some translations
* FIX Remove print_r
* FIX Notice function_exists
= 1.9.6 =
* FIX WooCommerce tabs
= 1.9.5 =
* FIX Menu detection
= 1.9.4 =
* FIX Rename / hide / order admin menu
* FIX Compatibility with WooCommerce
* FIX Plugin licence updater
= 1.9.3 =
* NEW Add fix return path in Mails settings (PRO)
* INFO Check WP 4.8 compatibility
* INFO Add missing links in adminbar
* INFO Add some notices for user
* INFO Show translated user role name if available in Role manager
* FIX Editor fatal error (Call to undefined function wpui_admin_editor_media_insert())
= 1.9.2 =
* INFO Update updater
= 1.9.1 =
* FIX Remove print_r in backend
= 1.9 =
* NEW Remove All In One SEO Pro notices
* NEW Remove Downloadable Product checkboxe in product backend page
* NEW Remove Virtual Product checkboxe in product backend page
* NEW Remove Simple Product in product backend page
* NEW Remove Grouped Product in product backend page
* NEW Remove External Product in product backend page
* NEW Remove Variable Product in product backend page
* NEW Remove General Tab in product backend page
* NEW Remove Inventory Tab in product backend page
* NEW Remove Shipping Tab in product backend page
* NEW Remove Linked Product Tab in product backend page
* NEW Remove Attribute Tab in product backend page
* NEW Remove Variations Tab in product backend page
* NEW Remove Advanced Tab in product backend page
* FIX Admin Ajax blocked by option
= 1.8.1 =
* FIX Admin menu regression
= 1.8 =
* NEW Add Reset settings button in Import/Export/Reset option page
* NEW Add Reset Admin Menus button
* NEW Change From email address in mails sent by WordPress (PRO)
* NEW Change From name in mails sent by WordPress (PRO)
* NEW Add CC e-mails to all WP Mails (PRO)
* NEW Add BCC e-mails to all WP Mails (PRO)
* NEW Send WP Mails in HTML (default Text) (PRO)
* NEW Enable SMTP instead of PHP Mail (PRO)
* FIX Import/Export tool
* FIX Remove WP Admin UI in Admin bar
= 1.7 =
* NEW Rename admin menu
* NEW Remane admin submenu
* FIX Parse error: syntax error, unexpected end of file in wp-content/plugins/wp-admin-ui/inc/functions/options-dashboard.php
on line 212
* FIX Warning Invalid argument supplied for foreach() wp-content/plugins/wp-admin-ui/inc/functions/options-admin-menu.php:26
* FIX Warning: Illegal string offset 'wpui_admin_menu' in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2624
* FIX Notice: Uninitialized string offset: 0 in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2624
* FIX Notice: Uninitialized string offset: 2 in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2624
* FIX Warning: Illegal string offset 'wpui_admin_menu' in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2647
* FIX Notice: Uninitialized string offset: 0 in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2647
* FIX Notice: Uninitialized string offset: 2 in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2647
* FIX Notice: Undefined offset in wp-content/plugins/wp-admin-ui/inc/admin/admin.php on line 2647
* FIX Notice: Undefined variable current_tab in wp-content/plugins/wp-admin-ui/inc/admin/admin.php:90
* FIX Notice: Undefined variable current_tab in wp-content/plugins/wp-admin-ui/inc/admin/admin.php:180
= 1.6.1 =
* FIX array_flip() error if no options saved in Admin Menu page
= 1.6.0 =
* NEW Add custom logo in WP Admin Bar
* NEW Add Facebook field in user profil
* NEW Add Twitter field in user profil
* NEW Add Instagram field in user profil
* NEW Add LinkedIn field in user profil
* NEW Enable field label visibility in Gravity Forms (PRO)
* NEW Disable Just in Time messages from Jetpack (PRO)
* NEW Disable Emojis support in front/back-end
* NEW Disable JSON REST API in front
* NEW Disable XML-RPC in front
* NEW Change Akismet delay before deleting SPAM comments (PRO)
* INFO Improve media library UI
* INFO Improve UI/UX for number type fields
* FIX Remove Yoast WP SEO from Admin Bar
= 1.5.0 =
* NEW Sanitize filenames when upload files in WordPress Media Library
* NEW Set a default image size in Editor
* NEW Set a default image type link in Editor
* NEW Set a default image alignment in Editor
* NEW Show Page Template column in page list view
* NEW Block WordPress admin for specific user roles
* NEW Disable login by email for users
* NEW Disable WP formatting shortcuts in Editor
* NEW Add Number of Users in At a glance Dashboard Widget
* NEW Add custom avatar for comments
* NEW Define default view mode in view list (posts and custom post types)
* NEW Add Width x Height column in media library
* NEW Add EXIF Metadata column in media library (ISO, Shutter speed, camera, aperture, timestamp, copyright, focal...)
* FIX Columns in media library
* FIX Thumbnail column in post list view
= 1.4.0 =
* NEW Add custom dashboard widget
* NEW Add thumbnail column in list view (posts, pages, custom post types)
* NEW Add URL column to media library
* NEW Add ID column in Media Library
* NEW Redirect users to a specific URL after logout
* NEW Redirect users to a specific URL after registration
* NEW Disable shake effect if wrong login
* NEW Display all custom post types in At a glance dashboard widget
* NEW Allow SVG file in media library
* NEW Display Dashboard in a single column
* NEW Disable drag and drop for dashboard widgets (block disabling widgets from Screen options too)
* INFO Improve Roles page UI
* INFO Improve input and textarea size
* INFO Improve UX for Admin menus
* FIX JS/CSS concatenation, File editor, file modifications
= 1.3.0 =
* NEW All settings can now be apply to Administrators too!
* NEW Admin bar: Remove Howdy
* NEW Admin bar: Remove WP Admin UI
* NEW Plugins Remove WP SEO (Yoast) admin notices (PRO Only)
* NEW WordPress 4.5 compatibility
* FIX WP Admin UI in Admin bar, missing items and check if PRO version is enabled
= 1.2.0 =
* NEW Admin Themes (PRO Only)
= 1.1.0 =
* FIX Fatal error: Call to undefined function in Admin Bar Options
= 1.0.0 =
* Stable release
= 0.9.3 =
* NEW Remove WP SEO in admin bar
* INFO Improve UX for custom logo
* INFO Smart setting on DISALLOW FILE EDIT
* INFO Smart setting on DISALLOW_FILE_MODS
* INFO Smart setting on CONCATENATE_SCRIPTS
* INFO Smart setting on ICL_DONT_PROMOTE
* FIX Notice DISALLOW FILE EDIT
* FIX Notice DISALLOW_FILE_MODS
* FIX Notice CONCATENATE_SCRIPTS
* FIX Notice ICL_DONT_PROMOTE
* FIX Warning: array_filter() expects parameter 1 to be array, string given for Admin Menu
* FIX Warning: Illegal string offset 'wpui_admin_menu'
* FIX Notice Uninitialized string offset: 0
* FIX Warning: array_keys() expects parameter 1 to be array, string given in for Admin Menu
* FIX Warning: array_flip() expects parameter 1 to be array, null given in for Admin Menu
* FIX Warning: array_intersect_key(): Argument #1 is not an array in for Admin Menu
* FIX Warning: Invalid argument supplied for foreach() for Admin Menu
* FIX CSS for custom logo
= 0.9.2 =
* INFO Update screenshots
* FIX Move WP Admin UI menu after Settings
* FIX Remove ? in labels
* FIX Translation in Import / Export page
= 0.9.1 =
* NEW Add Remove Customize in Admin Bar
* NEW Add Remove Search in Admin Bar (Front End)
* INFO Remember last tab in Global settings
* INFO WP Admin UI Quick Access page redesign
* INFO Add WP Admin UI in admin bar
* INFO Improve UX/UI in Role Manager, input, textarea...
* INFO Add notices if metaboxes, dashboard wigets or columns aren't initialized
* INFO Add disabled state on Refresh button in Metaboxes, Columns and Dashboard pages
* FIX Login options settings
* FIX Improve security
* FIX Loading Pro options
* FIX Pointers
* FIX Warning: function_exists() expects parameter 1 to be string, array given in /wp-admin-ui/inc/functions/options.php on line 45
* FIX Warning: function_exists() expects parameter 1 to be string, array given in /wp-admin-ui-pro/inc/functions/options.php on line 20
* FIX Notice "Please enable WP Admin UI in order to use WP Admin UI PRO" now visible to admins only
* FIX Warning array_unique invalid argument supplied for foreach()
* FIX Items per page in list view 
* FIX Options login title
* FIX Admin Bar options fixed in Front End
* FIX Ajax detection for metaboxes
* FIX Ajax detection for columns
* FIX Ajax detection for dashboard
* FIX Notice array to string conversion in metaboxes page
* FIX Warning on foreach in metaboxes page
* FIX Save detected columns
* FIX Media library columns now detected
* FIX Missing Revisions/Comments metaboxes
= 0.9 = 
* NEW Disable Open Sans loading from Google
* NEW Number of items per page in list view
* NEW Disable file editor for themes and plugins
* NEW Disable Plugin and Theme Update, and Installation
* NEW Disable all updates
* NEW Disable core updates
* NEW Disable core development updates
* NEW Disable minor core updates
* NEW Disable major core updates
* NEW Enable automatic updates on Versioning Control System (GIT/SVN)
* NEW Disable automatic updates for all plugins
* NEW Disable automatic updates for all themes
* NEW Disable automatic updates for all translations
* NEW Disable update emails notifications
* NEW Disable JS concatenation
* NEW Remove Welcome Panel
* NEW Detect automatically dashboard widgets
* NEW Display all settings in menu
* NEW Add "p" quicktags in Text Editor
* NEW Add "hr" quicktags in Text Editor
* NEW Add "pre" quicktags in Text Editor
* NEW Detect automatically metaboxes from Custom Post Types
* NEW Detect automatically columns from Custom Post Types in list view
* NEW Add IDs column in list view
* NEW Define JPG image quality
* NEW Role manager
* NEW WPUI Main settings screen
* FIX load_plugin_textdomain
* FIX Keyboard label
* FIX WP SEO label
* FIX Color schemes
= 0.8.1 = 
* NEW Define Number of items per page in list view (posts, pages, custom post types)
* INFO Smart settings for Media library filters
= 0.8 = 
* NEW Media library filters
* NEW Detect automatically all admin menus, reorder them via drag and drop, show/hide menus/submenus
* FIX Save Profil settings
= 0.7 = 
* NEW Import/Exports settings in JSON between different sites
= 0.6.1 =
* FIX  Warning: in_array() [function.in-array]: Wrong datatype for second argument 
= 0.6 =
* FIX Warning: Cannot modify header information - headers already sent... in wpadminui-core.php
= 0.5 =
* First beta release.