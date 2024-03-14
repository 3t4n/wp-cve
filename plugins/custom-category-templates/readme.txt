=== Custom Category Templates ===
Contributors: shazdeh
Plugin Name: Custom Category Templates
Tags: template, category, theme, custom-template, category-template
Requires at least: 3.4.0
Tested up to: 4.4.2
Stable tag: 0.2.1

Define custom templates for category views.

== Description ==

Just like the way you can create custom page templates, this plugin enables you to build category archive templates by adding this bit to the top of your file:

<code>
<?php
/**
 * Category Template: Grid
 */
?>
</code>
and when you're adding or editing categories, you can choose the desired template file.

This plugin is maintained solely for backward compatibility. Try the new <a href="https://wordpress.org/plugins/custom-taxonomy-templates/">Custom Taxonomy Templates</a> instead, it supports all taxonomies and also uses the new term meta feature in WP 4.4.


== Installation ==

1. Upload the whole plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!


== Screenshots ==

1. Assigning custom templates to categories


== Changelog ==

= 0.2.1 =
* Fix PHP notices
* i18n support

= 0.2 =
* Implementation of new WP_Theme API
* Fixed a bug concerning body class output. Thanks @Sith Lord Goz!
* Added delete_option method