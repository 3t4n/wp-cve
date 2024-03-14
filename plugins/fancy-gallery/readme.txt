=== Gallery Manager ===
Contributors: dhoppe
Tags: gallery, galleries, image, images, picture, pictures, photo, photos, photo-album, photo-albums, fancybox, thickbox, lightbox, jquery, javascript, widget, cms, free, flickr				widget,Post,plugin,admin,posts,sidebar,comments,google,images,page,image,links
Requires at least: 5.5
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: trunk
Donate link: https://dennishoppe.de/en/wordpress-plugins/gallery-manager
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Supercharged image gallery management tool with touch-enabled, responsive, mobile-friendly image lightbox for WordPress!


== Description ==
Gallery Manager is *the* most innovative state of the art **WordPress Gallery Management tool** which enables you to **organize image galleries** easily in your WordPress backend. Furthermore this gallery plugin adds a beautiful awesome **javascript lightbox effect** (touch-enabled, responsive, optimized for both, mobile and desktop, web browsers) to all links pointing to an image anywhere on your website. This includes images in your posts, galleries, pages, sidebar widgets and anywhere else on your website.

= Lightbox =
All links pointing to an image will automatically opened in a **responsive lightbox**. You do not need to care about any tag attributes or link classes, the plugins takes care about that for you. When using the default [gallery] shortcode the images will get a **"previous", "next" and a slideshow button**. The gallery itself uses your themes gallery style and keeps your common identity untouched.

= Gallery management features =
* Organize image galleries separated from posts or pages
* **Centralized gallery management**. Enjoy a single location where you can see and manage all your galleries
* Automatically generated **index page with all galleries**
* Every gallery has its own page with unique URL
* Taxonomies to classify your galleries: **Categories, Tags, Events, Places, Dates, Persons, Photographers**.¹
* Both, tags and categories, are disjunct from your post tags and post categories
* Supports gallery **comments**²
* Supports **featured images** as gallery thumbnails²
* Supports **excerpts** for your uploaded galleries² (the same way you already know from regular posts)
* Excerpts can contain a text description and a random set of preview images
* Supports WordPress **user permissions** and capabilities¹
* Supports the **WordPress menu system** and enables you to add all components of your galleries to any menu
* **Import and export** directly via the official "[WordPress Importer](https://wordpress.org/plugins/wordpress-importer/)" by Automattic Inc.

= Lightbox features =
* Javascript lightbox support for **all linked images** on your website
* Has "Previous" and "Next" buttons
* Shows image title and description
* Lightbox is **touch-enabled, responsive and mobile-friendly**
* Supports **swipe function** for touch screens – works with every smart phone and tablet
* Awesome image **slideshow** function
* **Indicator thumbnail images** below the full size image
* Uses the **full screen** size for presenting your image

= General features =
* **SEO conform** URL structure for all kind of pages
* Supports **WPML** flawless
* Supports the WordPress theme template hierarchy and the parent-child-theme paradigm
* Supports **user defined HTML templates**
* Supports **RSS feeds** for the gallery index and for the comments of each gallery
* Custom **thumbnail sizes**¹
* **Fully compatible** with all existing themes with archive template
* WordPress filter to modify your galleries code and style
* Widget to display random images from your galleries in the sidebar
* Widget to display galleries as list in the sidebar
* Widget to display the gallery taxonomies as list or cloud in the sidebar
* Applies your themes gallery style
* **Completely translatable** - .pot file is included
* Includes a **bunch of filters** to give you the control of the behavior of this piece of code
* **Clean and intuitive** user interface
* Works great with **WordPress Multisite**
* Personal **one-on-one real-time support** by the developer¹
* No ads or branding anywhere - perfect white label solution¹

¹ Available in [Gallery Manager Pro](https://dennishoppe.de/en/wordpress-plugins/gallery-manager).<br>
² Your theme needs to support this too.

= Gallery shortcode =
Of course you can use "exclude" and "include" parameters in your [gallery] shortcode like you already know from the traditional gallery shortcode. The Gallery Manager plugin does not touch the mechanic of the default [gallery] shortcode.

= Settings =
You can find the settings page in your Dashboard -> Settings -> Galleries.

= Template files =
All plugin outputs can be changed via user defined HTML templates. Just put the templates you want to overwrite inside your theme folder (no matter if parent theme or child theme). You can find the default templates in the plugin folder in "templates/". You can find a list of the available template files in documentation of the pro version. *Please do not modify the original templates! You would lose all your modifications when updating the plugin!*

= Questions and support requests =
Please use the support forum on WordPress.org only for this free lite version of the plugin. For the pro version there is a separate support package available. *Please do not use the WordPress.org support forum for questions about the pro version* or questions about my services! Of course you can hire me for consulting, support, programming and customizations at any time.

= Pro Version =
Gallery Manager is available as [premium version](https://dennishoppe.de/en/wordpress-plugins/gallery-manager) too. In the premium version you can use all options and features which are restricted in the lite version.

Possibly even more important, buying the premium edition gives you access to me and my support team. You can email us your questions about usage of the plugin or your problems in setting it up and we will assist you in no time!

= Languages =
* This Plugin is available in English.
* Diese Erweiterung ist in Deutsch verfügbar. ([Ulrike Seddig](https://UlrikeSeddig.de/))

= Translate this plugin =
This plugin is community translated. You can help translate it or improve existing translations [on the official translation plattform](https://translate.wordpress.org/projects/wp-plugins/fancy-gallery/).

You can find the *Translation.pot* file in the *languages/* folder in the plugin directory. The textdomain of this plugin is "fancy-gallery".


== Installation ==

= Minimum Requirements =

* WordPress 6 or greater
* PHP version 7.4 or greater
* PHP 8 is strongly recommended

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you do not need to leave your web browser. To do an automatic install of Gallery Manager, log in to your WordPress dashboard, navigate to the plugins menu and click "Add New".

In the search field type "Gallery Manager" and click "Search plugins". Once you have found my gallery plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading my gallery plugin and uploading it to your web server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.


== Screenshots ==
01. Gallery single view
02. Gallery lightbox
03. Edit a gallery
04. Manage gallery categories
05. Edit a category
06. Edit an image
07. Integrate your galleries to your navigation menus
08. Change details of several images
09. The lightbox settings


== Changelog ==

= 1.6.58 =
* PHP 8.2 patches
* Renamed class folder to includes
* Formatted code to PSR-12
* Added attribute types to function definitions
* Renamed AJAX_Requests class to AJAXRequests
* Renamed Content_Filter class to ContentFilter
* Renamed Post_Type class to PostType
* Renamed Shortcode_Filter class to ShortcodeFilter
* Renamed Taxonomy_Fallbacks to TaxonomyFallbacks
* Renamed WebP_Support class to WebPSupport
* Renamed WP_Query_Extensions class to WPQueryExtensions
* Updated minimal PHP version to 7.4
* Specified translation filter in WPML integration
* Fixed Random Images Widget

= 1.6.57 =
* Some PHP 8.0 patches
* Changed green colored elements to dashboard palette color

= 1.6.56 =
* Small Dashboard CSS tweaks for WP 5.6+
* Fixed options page form action endpoint (removed options_saved parameter)

= 1.6.55 =
* Added one style sheet for each default theme

= 1.6.54 =
* Updated lightbox library to 2.36

= 1.6.53 =
* Fixed return type for taxonomy fallback function of get_the_categories()

= 1.6.52 =
* Fixed var typo in thumbnails class which throws PHP notices

= 1.6.51 =
* Added .webp support
* Improved frontend JavaScript

= 1.6.50 =
* Fixed image size dropdown options for sizes without width/height

= 1.6.49 =
* Changed preview columns count option field
* Fixed number of columns in previews

= 1.6.48 =
* Fixed empty gallery bug
* Switched translation method names to original translation names

= 1.6.47 =
* Changed pro banner design, text and size
* Added options page link to post type sub menu

= 1.6.46 =
* Updated font size in Pro banner
* Increased number of ad-free items
* Updated tested-up-to version

= 1.6.45 =
* Added warning on options page when libxml is missing

= 1.6.44 =
* Code cleanup in WPML class
* Fixed Pro Banner font size in block editor

= 1.6.43 =
* Made Pro banner compatible with Block Editor in WP5

= 1.6.42 =
* Updated lightbox JavaScript to 2.33
* Make Gutenberg disabled by default

= 1.6.41 =
* Changed min required PHP version to 5.5.2
* Fixed TinyMCE plugin: removed edit gallery button in the editor

= 1.6.40 =
* Removed upgrade banner for fresh installations
* Updated upsell text

= 1.6.39 =
* Removed gallery count limit
* Added upgrade banners to plugin pages
* Changed nav menu label for gallery taxonomies in the menu editor

= 1.6.38 =
* Fixed gallery_image_size select box width in edit gallery screen
* Changed DOMNodeList access via item() method for PHP version older than 5.6.3
* Added Fallback for older LibXML versions than 2.7.8
* Fixed metabox incompatibility with Gutenberg editor

= 1.6.37 =
* Replaced hyphens by under spaces in filter and hook names
* Added new filter for getGallery AJAX call
* Added $item property to the JavaScript gallery item which points to the original image link
* Added display:block to lightbox controls font icons to make them the same height in different browsers

= 1.6.36 =
* Changed CSS and JS handle names for the options page

= 1.6.35 =
* Small JS code cleanups
* language file string cleanups

= 1.6.34 =
* Added: Nonce fields for the options page

= 1.6.33 =
* Updated lightbox JavaScript to 2.32
* Improved Options performance

= 1.6.32 =
* Replaced jQuery toggle call on the options page with slideUp and slideDown

= 1.6.31 =
* Patched LEFT JOIN statement for attachment queries
* Fixed image editor in gallery editor
* Fixed wp.media styles in gallery editor

= 1.6.30 =
* restricted images to user owner
* restricted images to the ones which are not already in a gallery
* prevent the user from adding images multiple times

= 1.6.29 =
* Updated secure urls

= 1.6.28 =
* Updated lightbox library to 2.29
* Renamed Gallery_Post_Type class to Post_Type
* Renamed Gallery_Taxonomies class to Taxonomies

= 1.6.27 =
* Fixed typo in menu label

= 1.6.26 =
* changed gallery menu caption
* added gallery index to nav menu section

= 1.6.25 =
* Updated lightbox library to 2.27

= 1.6.24 =
* Updated lightbox library to 2.25.2
* Added jQuery lightbox events to lightbox container
* Increased z-index for title and description wrapper in the lightbox
* Fixed color for title and description wrapper in the lightbox
* Added uninstall hook

= 1.6.23 =
* Changed warning for PHP <5.5

= 1.6.22 =
* Added PHP version warning for PHP <5.5.38
* Implemented late static binding for all classes
* Fixed get_the_categories filter when calling it outside the loop
* Removed the "Archive:" prefix from post_type_archive_title function

= 1.6.21 =
* Added support for late static binding

= 1.6.20 =
* Fixed handling of hash links

= 1.6.19 =
* Updated lightbox library to 2.25.0

= 1.6.18 =
* Fixed base url issue when using domain mapping

= 1.6.17 =
* Updated lightbox library to 2.22.0

= 1.6.16 =
* Removed sortable and slidable boxes in options page
* Removed support for WP 4.3 and older
* Small code cleanups
* Updated lightbox library to 2.21.3

= 1.6.15 =
* Changed: filter priority for registering taxonomies (9)
* Changed: filter priority for registering post type (10)

= 1.6.14 =
* Fixed shortcode field with in gallery table in the dashboard
* Small code cleanups

= 1.6.13 =
* replaced core method "containsShortcode" by "hasShortcode" WP function

= 1.6.12 =
* Internationalized remove-image-warning in edit-gallery backend

= 1.6.11 =
* Updated lightbox library to 2.21.2

= 1.6.10 =
* Patched font icon implementation

= 1.6.9 =
* Updated lightbox library to 2.20
* Added font icon files for older browsers
* Refactored lightbox style sheet

= 1.6.8 =
* Changed: filter priority for registering post type
* Changed: filter priority for registering taxonomies
* Small code cleanups

= 1.6.7 =
* Changed gallery relation attribute to "data-gallery"
* Added: Image captions are shown in the lightbox now

= 1.6.6 =
* Fixed: Image titles for in-post-created galleries
* Added: Image alt-attribute as image title available

= 1.6.5 =
* Replaced index.php files by index.html files
* Improved the initial module loader
* Small code cleanups

= 1.6.4 =
* Fixed: limit in cloud widget
* Fixed: Widgets do not print a title wrapper when the title is empty

= 1.6.3 =
* Removed external auto update feature
* Patched: Widget use doing_action function now
* Added: Pointer cursor to images in the meta box
* Patched: defined static textdomain

= 1.6.2 =
* Patched: small style issues in the lightbox
* Fixed: problem with managing images without thumbnail version in the dashboard
* Fixed: frontend JavaScript will only be loaded if the lightbox is enabled
* Fixed: link hash meta box will only be shown if the lightbox is enabled
* Fixed: hash links will only be considered if the gallery id is numeric
* Added: Shortcode can be find on the post type dashboard page

= 1.6.1 =
* Fixed: Galleries can handle more than 40 images now

= 1.6 =
* The plugin was completely rewritten
