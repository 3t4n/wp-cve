=== WP Downloader ===
Contributors: szaleq
Tags: zip, zipper, download, downloader, plugin download, theme download, code, development
Requires at least: 3.5
Tested up to: 4.1
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows to download plugins and themes installed on your site as a zip package, ready to install on another site.

== Description ==

= Features =
* displays a 'Download' link for
	* all installed plugins (in action links on 'Plugins' page)
	* all installed themes (on 'Appearance' page, also in wordpress 3.8 or higher)

= Idea =
I wrote this plugin to simplify and improve code downloading when developing on a playground with contributors. With this plugin you can get complete package with other plugin or theme you are working on in one click. You can also use it to share your code with someone without downloading it via ftp, manually zipping and then sending it in e-mail or uploading the package back to your server or anything else... just tell him to click the link!

There are some other methods for this, there are advanced plugins which has such a feature to download other plugins, but I hope someone will find my code usefull as it is very simple in action and the simplest in use.

This plugin uses PclZip class integrated with WordPress.

Many thanks to [Viktor Szépe](http://www.online1.hu/webdesign/) for the idea of how to inject links on the appearance page in new wordpress UI (3.8 or higher).
[Hehe is the code](https://github.com/szepeviktor/wordpress-plugin-construction/tree/master/shared-hosting-aid/wp-downloader) used as an inspiration for the current version of WP Downloader.

== Installation ==

1. Unpack wp-downloader.zip and upload its content to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Plugin is working. You can see 'Download' links for every plugin and theme.

== Changelog ==

= 2.0 =
* Added hack to display download links on the new themes page (wordpress 3.8 or higher)
* Few improvements in the code

= 1.1 =
* Added 'download' link for currently selected theme

= 1.0 =
* Initial release
