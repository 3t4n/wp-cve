=== Add External Media ===
Contributors: leemon
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=oscarciutat%40gmail%2ecom
Tags: media, attachments, admin, external, oembed
Requires at least: 4.0
Tested up to: 5.3
Requires PHP: 5.3
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add external media to the media library

== Description ==
Add external media from a [supported oEmbed provider](http://codex.wordpress.org/Embeds) (YouTube, Vimeo, SoundCloud, Twitter, ...) to the media library

== Installation ==
1. Upload the extracted plugin folder and contained files to your /wp-content/plugins/ directory
2. Activate the plugin through the "Plugins" menu in WordPress

== Frequently Asked Questions ==
= Does this plugin import the actual external media content into the library? =
No, external media attachments just contain URL references to the original resources

= How can I show an external media attachment with the specified width and height? =
`$oembed = new WP_oEmbed();
$oembed_width = get_post_meta( $attachment->ID, '_oembed_width', true );
$oembed_height = get_post_meta( $attachment->ID, '_oembed_height', true );
echo $oembed->get_html( wp_get_attachment_url( $attachment->ID ), array( 'width' => $oembed_width, 'height' => $oembed_height ) );`
				
= Why are the width and height settings in some external media attachments being ignored? =
Some service providers, such as Twitter and Instagram, have a maximum and minimum allowed width and ignore the height setting completely

== Screenshots ==
1. Enter the url and size of the external media you'd like to add to the media library here

== Changelog ==
= 1.0.5 =
* Add WP 5.3 support

= 1.0.4 =
* Code refactoring
* Hooking the plugin's enqueue function into "wp_enqueue_media" instead of "admin_enqueue_scripts"

= 1.0.3 =
* Use language packs exclusively

= 1.0.2 =
* Language filenames updated

= 1.0.1 =
* Text domain changed to match the plugin's slug

= 1.0 =
* Initial release