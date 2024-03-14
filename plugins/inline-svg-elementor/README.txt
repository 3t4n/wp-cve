=== Elementor Inline SVG ===
Contributors: namogo
Tags: elementor, inline, svg
Requires at least: 4.5
Tested up to: 4.8.1
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Elementor Inline SVG

== Description ==

An Elementor widget that lets you add an SVG file as markup instead of an html image tag.

##How it works

Elementor Inline SVG works much like the core Image widget from Elementor, except that it expects SVG files and outputs the file's contents as markup in your page. This eliminates the extra HTTP request that otherwise occurs when using the <img> tag and allows for styling and interacting with the SVG elements through CSS and Javascript. 

##Options

1. Max-width control that lets you specify a maximum width in pixels or percentage [responsive]
2. Custom aspect ratio to change the default aspect ration of the contents [responsive]
3. Alignment to left, center or right



== Installation ==

1. Make sure Elementor is installed and active on your website
2. Activate the plugin through the 'Plugins' menu in WordPress
2. Edit a page (or post) using Elementor
3. Find and use the new "Inline SVG" widget

== Changelog ==

= 1.2.0 =
* Tweak — Better sizing management
* Fixed — Typo on render attribute
* Added — Handle css styling

= 1.1.0 =
* Added — Controls to modify default and hover color of shape elements
* Fixed — Connection refused error on DomDocument load function

= 1.0.0 =
* Fixed — Compatibility with the latest version of Elementor
* Added — Ability to add link to svg
* Tweak — Reorganised controls

= 0.0.3 =
* Added controls to allow the SVG to have a custom aspect ratio instead of maintaining the default one
* Removed default placeholder image for SVG file control
* Added required PHP version 5.0 due DOMElement library
* Fixed — Bug where you needed to refresh the editor to see the SVG

= 0.0.2 =
* "Width" control is now responsive
* SVG can now be aligned to left, right or center
* Updated required Elementor version to 1.4.1

= 0.0.1 =
* First version

== Upgrade Notice ==

= 0.0.2 =
Width is now responsive and a new alignment control