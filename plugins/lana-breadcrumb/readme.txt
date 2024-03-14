=== Lana Breadcrumb ===
Contributors: lanacodes
Tags: breadcrumb, bootstrap breadcrumb, seo breadcrumb
Requires at least: 4.0
Tested up to: 6.0
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Indicate the current page's location within a navigational hierarchy

== Description ==

Bootstrap based breadcrumb.

Indicate the current page's location within a navigational hierarchy.

= Video =

[youtube https://www.youtube.com/watch?v=1FlBedJXKSw]

= How to use with function: =

`<?php
if( function_exists( 'lana_breadcrumb' ) ) {
    echo lana_breadcrumb();
}
?>`

= Available shortcodes: =

`[lana_breadcrumb]`

= Lana Codes =
[Lana Breadcrumb](http://lana.codes/lana-product/lana-breadcrumb/)

== Installation ==

= Requires =
* WordPress at least 4.0
* PHP at least 5.3

= Instalation steps =

1. Upload the plugin files to the `/wp-content/plugins/lana-breadcrumb` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

= How to use it =
* in `Appearance > Widgets`, add the 'Lana - Breadcrumb' widget to the sidebar, for example, add it to the Header Sidebar.
* in `Appearance > Editor`, add the `lana_breadcrumb()` function to the theme file, for example, add to the header.php file.
* in `Pages > Edit` selected page, add the `[lana_breadcrumb]` shortcode to the page content.

== Frequently Asked Questions ==

Do you have questions or issues with Lana Breadcrumb?
Use these support channels appropriately.

= Lana Codes =
[Support](http://lana.codes/contact/)

= WordPress Forum =
[Support Forum](http://wordpress.org/support/plugin/lana-breadcrumb)

== Screenshots ==

1. screenshot-1.jpg

== Changelog ==

= 1.1.0 =
* add filter
* change front page breadcrumb
* reformat code

= 1.0.6 =
* change breadcrumb elements order

= 1.0.5 =
* add home to breadcrumb
* change breadcrumb to lana-breadcrumb in style

= 1.0.4 =
* bugfix page ancestors sorting

= 1.0.3 =
* add text domain to plugin header

= 1.0.2 =
* Tested in WordPress 4.8 (compatible)
* Change website to lana.codes

= 1.0.1 =
* typo in Plugin URI

= 1.0.0 =
* Added Lana Breadcrumb

== Upgrade Notice ==

= 1.1.0 =
This version added filter. Upgrade recommended.

= 1.0.6 =
This version changed breadcrumb elements order. Upgrade recommended.

= 1.0.5 =
This version added home to the breadcrumb. Upgrade recommended.

= 1.0.4 =
This version fixes page ancestors sorting bug. Upgrade recommended.

= 1.0.3 =
This version added text domain to the plugin header. Upgrade recommended.

= 1.0.2 =
Nothing has changed in this version. Tested in WordPress 4.8 and compatible.

= 1.0.1 =
This version fixes typo in Plugin URI.