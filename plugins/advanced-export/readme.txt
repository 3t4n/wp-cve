=== Advanced Export: Export WordPress Site Data Including Widget, Customizer & Media Files ===

Contributors: addonspress, codersantosh, acmeit
Donate link: https://addonspress.com/
Tags: export, advanced export, demo export, theme export, widget export, customizer export
Requires at least: 4.5
Tested up to: 6.0
Requires PHP: 5.6.20
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Advanced Export is a developer friendly WordPress plugin which gives flexibility to export site data in a zip format.

== Description ==

Advanced Export is one of the best and powerful data exporter plugin. It has number of features which make more manageable and convenient to WordPress user to exact their WordPress site data and again re-use in another website.

Exported Zip can be imported by using plugin [Advanced Import](https://wordpress.org/plugins/advanced-import/)

It is designed specially for theme developer who want to provide demo data to their customer but it can be also use for migration purpose too.

Some listed features of Advanced Export are given below :

* Export widget
* Export option
* Export media,
* Export pages,
* Export post
* Export custom post type
* Export actual media files

== Dashboard Location ==

Dashboard -> Tool -> Advanced Export

== Installation ==

There are two ways to install any Advanced Export Plugin:

1.Upload zip file from Dashboard->Plugin->Add New "upload plugin".
2.Extract Advanced Export and placed it to the "/wp-content/plugins/" directory.
    - Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==

= Is Advanced Export is free plugin ? =

Yes, it is free plugin.

= I have exported zip using Advanced Export plugin, now how to import on other sites ? =

After exported zip, you can import it using [Advanced Import](https://wordpress.org/plugins/advanced-import/) plugin

= All of the options are not exported by the plugin, how can I include them? =

By default all options on options table does not exported by this plugin, since it contain a lot of information and all information does not needed.
But you can use following hook to include all options:

`add_action('advanced_export_all_options','prefix_add_all_options');
function prefix_add_all_options(){
    return true;
}`

It is not recommended to use this hook unless you are migrating your site.

= Some option table are not exported, what is happening? =

You can include needed options by using `advanced_export_include_options` filter hook

`add_action('advanced_export_include_options','prefix_include_my_options');
 function prefix_include_my_options( $included_options ){
     $my_options = array(
         'blogname',
         'blogdescription',
         'posts_per_page',
         'date_format',
         'time_format',
         'show_on_front',
         'thumbnail_size_w',
         'thumbnail_size_h',
         'thumbnail_crop',
         'medium_size_w',
         'medium_size_h',
         'medium_large_size_w',
         'medium_large_size_h',
         'avatar_default',
         'large_size_w',
         'large_size_h',
         'page_for_posts',
         'page_on_front',
         'woocommerce_shop_page_id',
         'woocommerce_cart_page_id',
         'woocommerce_checkout_page_id',
         'woocommerce_myaccount_page_id',
         'page_on_front',
         'show_on_front',
         'page_for_posts',
     );
     return array_unique (array_merge( $included_options, $my_options));
 }`

= Can you list all the hooks on the plugin? =

Here are some important list of filter hooks:

- advanced_export_page_slug
- advanced_export_capability
- advanced_export_ignore_post_types
- advanced_export_include_options
- advanced_export_all_options

Here are some important list of action hooks:

- advanced_export_before_create_data_files
- advanced_export_form

== Screenshots ==

1. Export Main Screen

== Changelog ==

= 1.0.7 - 2022-05-26 =
* Updated : WordPress version

= 1.0.6 - 2022-02-04 =
* Updated : WordPress version

= 1.0.5 - 2022-01-05 =
* Updated : WordPress version

= 1.0.4 - 2021-04-22 =
* Updated : PHPCS

= 1.0.3 - 2020-06-22 =
* Updated : Export post types order

= 1.0.2 - 2020-03-04 =
* Updated : Permission of ZIP
* Updated : Readme

= 1.0.1 - 2019-09-29 =
* Updated : Some information

= 1.0.0 =
* Initial release.
