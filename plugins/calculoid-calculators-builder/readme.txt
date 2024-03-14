=== Plugin Name ===
Contributors: calculoid
Donate link: http://calculoid.com/
Tags: web calculator, builder, calculoid, embed, web form, pricing
Requires at least: 3.9
Tested up to: 5.3.2
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin makes it very easy to insert a calculator from Calculoid.com into your Wordpress website.

== Description ==

When you create some calculator from [Calculoid.com](http://calculoid.com) or you just want to use some calculator from [Calculoid library](http://app.calculoid.com/#/calculators), this plugin makes it very simple to embed it into your WP content.

Plugin utilizes [WordPress Shortcodes](http://codex.wordpress.org/Shortcode), so all you need to do is to insert this shortcode:

 [calculoid id="60204" show_title="0" show_description="0"  F300024="15" F310236="my domain" billing_firstname="John"]

 With the **id** parameter you specify which calculator you want to display. You can use this shortcode anywhere in WordPress. If **show_title** is set to 1, it will load calculator's title. If show_title is set to 0 (zero), calculator's title will not be loaded. **show_description** is the same. If set to 1, it will load description of the calculator, if set to 0, it will not load description. You can set default value of every calculator field, just add to shortcode FieldID="Value" or for Payment field use billing_fieldname="value". 

Plugin's configuration allows you to insert your **API key**, which you receive when you register to [Calculoid.com](http://calculoid.com). So if you want to embrace all features of your Calculoid membership, insert your API key.

The calculator does not use iframe, so it is part of your HTML content, which is great, because it inherits your CSS styles and the calculator fit in. The same font, same color and so on.

It won't slow down your website. The calculator is loaded asynchronously after the website is loaded. 

== Installation ==

1. Install plugin the usual way.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Insert your Calculoid API key at 'Settings' - 'Calculoid Admin' in WordPress administration
