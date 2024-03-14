=== WP Link Preview ===
Contributors: kgajera
Tags: link preview, facebook link preview, link teaser, link excerpt, share link, url preview, url teaser, url excerpt, share url
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display a preview for a URL similar to sharing a link on Facebook.

== Description ==
This plugin will add a button to your post/page editor which can be used to generate and embed a link preview for a given URL. The link preview consists of a title, description and image which will result in a similar look as sharing a link on Facebook.

The link preview can also be generated with shortcode: [wplinkpreview url=""]

== Installation ==
1. Uncompress the download package
1. Upload the folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png

== Changelog ==

= 1.4.1 =
* Remove 'user-agent' from HTTP request to fetch preview
= 1.4 =
* Fix character encoding issues
* Use 'og:url' when possible to display the source URL
* Validate favicon url to ensure its a valid absolute URL
= 1.3 =
* Ability to generate link preview using shortcode (ex. [wplinkpreview url="wordpress.org"])
= 1.2 =
* Batch input of URLs to generate links previews for
* Show loading message while link preview is being fetched
= 1.1 =
* Remove output of extra HTML when any or all meta data does not exist