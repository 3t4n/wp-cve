=== Beautiful Link Preview ===
Contributors: zeitwesentech, jtwg
Tags: link,preview,facebook open graph,twitter cards
Donate link: https://go.zeitwesen.dev/wp-beautiful-link-preview-donate
Requires at least: 5.1
Tested up to: 6.4.3
Requires PHP: 7.0
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates previews of links with title, description and preview images similar to sharing links on social networks.

== Description ==

This plugin allows you to create previews of links similar to common social networks when sharing a link.

It works by fetching the link URL and examining either the **facebook Open Graph** properties or **Twitter card** tags including any preview image.
Any title, description or image is stored inside this Wordpress' own database and subsequently used from there to render the Beautiful Link Preview.

**Usage:**

Wordpress versions 5.5 or greater with the Gutenberg block editor enabled will have a Beautiful Link Preview block that can be added directly to posts.

For the Classic editor, or Wordpress versions earlier than 5.5, just add the following shortcode to any HTML block in your post or page:

    [beautiful_link_preview url="https://zeitwesentech.com"]


== Installation ==

1. Unpack the contents of this zip file and upload it to your wp-content/plugins folder
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable the plugin on its settings page.
4. Add the Gutenberg block to your post or page, or place the following shortcode to any HTML block in your post or page:
    [beautiful_link_preview url="https://zeitwesentech.com"]

Or just install it via Wordpress Admin -> Plugins -> Add New -> Type "Beautiful Link Preview" or search for Author: zeitwesentech

== Screenshots ==

1. Usage as Gutenberg block.
2. Usage of shortcode inside HTML block in editor.
3. This is how the previous shortcode looks.
4. Override the layout to use per shortcode.
5. Example of full and compact layout.
6. Admin Section - Introduction
7. Admin Section - Settings
8. Admin Section - Link Previews


== Changelog ==

= 1.5.0 =
* fixed error when installing plugin
* Tested with Wordpress 6.4.3

= 1.4.0 =
* Added Gutenberg block support, big thanks to @jtwg
* Tested with Wordpress 5.7.2

= 1.3.0 =
* Fix: Handle german umlauts
* Tested with Wordpress 5.5.2

= 1.2.1 =
* Added UserAgent when fetching content (thanks to @ecolasurdo for reporting this in support forum)
* Tested with Wordpress 5.5.1

= 1.1.0 =
* Minor fixes
* Tested with Wordpress 5.5

= 0.9.5 =
* Small fixes

= 0.9.0 =
* Initial BETA release
