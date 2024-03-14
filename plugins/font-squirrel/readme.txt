=== Font Squirrel (unofficial) ===
Contributors: Fab1en
Tags: fonts, font
Requires at least: 4.0
Tested up to: 4.0
Stable tag: 1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Bring fonts from fontsquirrel.com into WordPress. Fonts kits are downloaded into your content directory and linked with @font-face.

== Description ==

[Font Squirrel](http://www.fontsquirrel.com/) is your best resource for FREE, hand-picked, high-quality, commercial-use fonts.
They provide a large part of their fonts in a web-embedding-ready way that is supported by all major browsers (@font-face CSS method).

The provided formats are :
* TTF - Works in most browsers except IE and iPhone.
* EOT - IE only.
* WOFF - Compressed, emerging standard.
* SVG - iPhone/iPad

They provide an API to list, preview and download fonts very easily : this unofficial plugin is using this official API.

When you browse fonts and dispplay one with this plugin, the font is downloaded from fontsquirrel.com. If you then choose to install the
font, its css rule is put in the head section of you website pages so that you can use it on any page. The WordPress built-in editor 
(TinyMce) has a dropdown list that lets you choose the font you want for each part of your site.


== Installation ==

1. Upload `wp-fontsquirrel` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make sure the `wp-content` directory has **write permission** to be able to download fonts.

== Screenshots ==

1. This is the font listing screen : click on a category and browse pages of amazing fonts. When you've found the one you love, click 
on "Install".
2. This page will show you the full alphabet with the font, and sample text in various font sizes. Click on "Publish" to use it on your site.
3. Back into you article, TinyMce has a dropdown menu that lets you choose your font fmily and size for the full text, or just some words.

== Changelog ==

= 1.0 =
* First published version
