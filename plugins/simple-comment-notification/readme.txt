=== Simple Comment Notification ===
Contributors: Beherit
Tags: author, comment, comments, email, notification, reply
Donate link: https://beherit.pl/en/donations/
Requires at least: 4.6
Tested up to: 5.4
Stable tag: 1.2.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Sends an simply email notification to the comment author, when someone replies to his comment.

== Description ==

Sends an simply email notification to the comment author, when someone replies to his comment. No configuration, support WordPress translation process.

== Installation ==

In most cases you can install automatically from plugins page in admin panel.

However, if you want to install it manually, follow these steps:

1. Download the plugin and unzip the archive.
2. Upload the entire `simple-comment-notification` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the Plugins menu in WordPress.

== Frequently Asked Questions ==

= Subscription =

It's possible to send email notification only if user checked subscription checkbox. To enable this functionality you must add such code (e.g. in functions.php in the active theme):

`add_filter('scn_enable_subscription', '__return_true');`

Optionally, you can also change default checkbox value to true:

`add_filter('scn_subscribe_value', '__return_true');`

= Autoresponder =

Plugin have a hidden functionality that sends autoresponder to the author of the comment. To turn it on you must add such code (e.g. in functions.php in the active theme):

`add_filter('scn_autoresponder_to_author', '__return_true');`

Optionally, you can also turn off default plugin functionality:

`add_filter('scn_notify_parent_author', '__return_false');`

The title and content of the autoresponder can be personalized:

`function custom_scn_autoresponder_subject($subject, $comment_object) {
	return $subject;
}
add_filter('scn_autoresponder_subject', 'custom_scn_autoresponder_subject', 10, 2);`

`function custom_scn_autoresponder_body($body, $comment_object) {
	return $body;
}
add_filter('scn_autoresponder_body', 'custom_scn_autoresponder_body', 10, 2);`

You can even set who will not receive this notifications by changing minimum required user capabilities:

`function custom_scn_autoresponder_cap() {
	return 'edit_posts';
}
add_filter('scn_autoresponder_cap', 'custom_scn_autoresponder_cap');`

== Changelog ==
= 1.2.4 (2020-04-08) =
* Fixed typos.
= 1.2.2 (2020-04-08) =
* Minor bugfix.
= 1.2 (2020-04-08) =
* Added subscription functionality (disabled by default).
= 1.1 (2017-03-12) =
* Fix the URL to a new comment.
* Sending autoresponder to the author of the comment (disabled by default).
= 1.0.2 (2016-03-12) =
* Add POT file and remove language files to allow WordPress.org language packs to take effect.
= 1.0 (2016-03-06) =
* First public version.