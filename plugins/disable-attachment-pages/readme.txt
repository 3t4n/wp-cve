=== Disable Attachment Pages ===
Contributors: palasthotel, greatestview
Donate link: https://palasthotel.de/
Tags: redirect, attachments, attachment, images
Requires at least: 4.0
Tested up to: 5.1
Stable tag: 1.1
License: GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Redirects attachment pages to the post, where they are placed, and hides backend option to link images to attachment page (if not default).

== Description ==
This plugin redirects attachment pages to the post, where they are placed (via 301). If there is no parent page, it redirects back to the WordPress home URL (via 302).

Further, when editing a post, the option to link images to their attachment page is hidden via CSS (except it is selected by default).

== Installation ==
1. Upload `disable-attachment-pages.zip` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You’re done! Try following an attachment link, the browser should redirect back to the post, where this link is placed.

== Changelog ==

= 1.1 =
* Added Gutenberg support.

= 1.0 =
* First release
