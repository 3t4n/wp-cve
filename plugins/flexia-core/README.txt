=== Flexia Core ===

Contributors: re_enter_rupok, codetic, wpdevteam
Tags: flexia, shortcodes, bootstrap-4, metabox, customizer, widgets, Google Analytics, Facebook Pixel
Requires at least: 4.0
Tested up to: 5.5
Stable tag: 1.4.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Core plugin for [Flexia](https://wordpress.org/themes/flexia/) theme. Controls all the plugin territory functionality for Flexia. 

== Description ==

Core plugin for [Flexia](https://wordpress.org/themes/flexia/) theme. Controls all the plugin territory functionality for Flexia. 

## Requirements

This is a specialized plugin for the theme [Flexia](https://wordpress.org/themes/flexia/) that is available for free on [WordPress.org](https://wordpress.org/themes/flexia/). But since plugin works independently, it will work with any standard theme if you deactivate Flexia. 

## Features

The plan is to keep all the plugin territory functionality of Flexia within this plugin and it's a continuous process. It will provide the shortcodes, metabox and extra Customizer options.

* Shortcodes
* Extra Customizer Options
* Metaboxes


## Customizer Options

* **Custom JavaScripts**
  * Header Script
  * Footer Script
  * Google Analytics

You can add custom JavaScripts to your site header and footer through Customizer and edit your scripts with CodeMirror editor. You can place any custom JavaScript, Google Analytics, Facebook Pixel or any kind of embed script. Extremely helpful if you need to place any custom javascript or jQuery code to header or footer. This plugin gives you the ability to place different scripts to header or footer separately. Uses Customizer so you can edit the code live and see the changes on the fly.

* CodeMirror Editor
* Ability to add custom scripts to wp header.
* Ability to add custom scripts to wp footer.
* Ability to add multiple scripts.
* Ability to add Google Alalytics code.
* Ability to add any embed code.

Go to **Customize > Custom JavaScripts** to add your scripts.

## Custom Metaboxes (Page and Post Settings)

* Post Header style control
* Additional Body Classes option
* Show/Hide Page/Post Title
* Show/Hide Post Meta
* Show/Hide Post Navigation
* Show/Hide Post Social Sharing
* And lots more control for individual posts

## Shortcodes

* [Bootstrap 4 Grid](#grid)
  * Container
  * Container Fluid
  * Row
  * Columns (1-12)

## Shortcode usage


### Grid
	[row]
		[column md="6"]
			...
		[/column]
		[column md="6"]
			...
		[/column]
	[/row]

Add container (different max-width for various screen size)

	[container]
		[row]
			[column md="6"]
				...
			[/column]
			[column md="6"]
				...
			[/column]
		[/row]
	[/container]

Add container-fluid (100% width container)

	[container-fluid]
		[container]
			[row]
				[column md="6"]
					...
				[/column]
				[column md="6"]
					...
				[/column]
			[/row]
		[/container]
	[/container-fluid]


All shortcodes containes number of parameteres. [See the Github Repo for detailed documentation](https://github.com/rupok/flexia-core). 

== Installation ==

1. Upload `flexia-core.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does it work standalone? =

It's a specialized plugin for Flexia theme, you need to install Flexia to get most of the features. But since plugin works independently, it will work with any standard theme if you deactivate Flexia. 



== Screenshots ==


== Changelog ==

= 1.4.2 =
* Removed: Customizer controls moved to Theme
* Removed: Metabox controls moved to Theme

= 1.4.1 =
* Admin notice removed

= 1.4.0 =
* Compatiblity with Flexia v1.1.0
* Compatiblity with WordPress 5.3
* Few minor bugfix and improvements

= 1.3.1 =
* Compatiblity with Flexia v1.0.0

= 1.3.0 =
* More Page Settings options added
* More Post Settings options added
* Documentation, support links added
* Few bugfix and improvements

= 1.2.3 =
* Google Analytics option added to Customizer
* Few bugfix and improvements

= 1.2.2 =
* Custom JavaScripts backward compatibility added for WordPress < 4.9

= 1.2.1 =
* Post Settings improved with lot more options
* Important information added to options panel
* Few minor bugfix and improvements

= 1.2.0 =
* Page Settings added
* Additional Body class option added to Page Settings
* Page Title show/hide option added to Page Settings

= 1.1.0 =
* Bootstrap 4 Grid shortcodes added
* Custom JavaScripts option added to Customizer
* Lots of improvements

= 1.0.0 =
* Initial release
