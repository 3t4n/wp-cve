=== Elementor Element Condition ===
Contributors: dudaster
Tags: page-builder, elementor, condition, conditional, conditional elements
Donate link: https://www.paypal.me/dudaster
Requires at least: 4.6
Tested up to: 4.9.8
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Some elements in Elementor should not be displayed or must be displayed under certain conditions. Implement conditional logic on Elementor elements.

You need Elementor plugin to use this plugin.

== Description ==

This plugin adds the ability to add conditions to sections and widgets in order to be shown or not.

You can add your own values in your function.php using add_filter().

This plugin is currently pretty basic, you can only use only 2 operands (variables) and one operator (the stuff beetwen to values in a condition). No brackets and no AND / OR operators for now.

The *Custom Fields* are preloaded in plugin, so all you need is to enter the slug and it will work. If you're not sure they work please check *Debug mode* and the variable should be replaced with the value.

The Condition field can be found in last section in *Style tab* of a widget.

For more details and examples check our official site https://www.eletemplator.com/

Note: This plugin is an addon of Elementor Page Builder (https://wordpress.org/plugins/elementor/) and will only work with Elementor Page Builder installed.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress


== Frequently Asked Questions ==

= I quit is too complicated! Is there a documentation for this? =

Indeed the plugin is more dev oriented, that means it has a learning curve to it. On the other side you can achieve pretty much everything you need with it. For questions and examples please refer to https://www.eletemplator.com/ and search for the plugin.

= Where can I find the Condition field? =

The Condition field can be found in last section in Style tab of a widget.

= Can I use complex conditions? =

No. For the moment you can only do a basic condition with 2 operands and 1 comparison operator.

== Screenshots ==

1. /assets/screenshot-1.png

== Changelog ==

= 1.0.5 =
* Now it set the value of variabile to 0 if is compared to a number.

= 1.0.4 =
* Fixed bugs.

= 1.0.3 =
* Fix notice errors.
* Works with var == null when variable doesn't have value.

= 1.0.2 =
* Fix sections with the latest Elementor version.
* Hide the widget wrapper.
* Debug mode will show a semi-opaque, bordered in red, content for editors and administrators.

= 1.0.1 =
* Added debug mode - seen only by admin and editor
* Fix by @hakkow to work with sections

= 1.0.0 =
* Initial Launch