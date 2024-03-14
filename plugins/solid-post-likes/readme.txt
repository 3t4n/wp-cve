=== Solid Post Likes  ===
Contributors: oacstudio
Tags: post likes, like button, like
Requires at least: 4.0
Tested up to: 6.2
Stable tag: 1.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 A like button for all post types. Solid and simple.

== Description ==

This plugin enables you to add a customizable like button to all post types. It supports all custom post types and all WooCommerce product types. Post comments are also supported.

This button uses the same button for like and dislike. It has been tested with all major page builders.

Scroll down for demo site link.

## Feature list

### Scope:

* Support for all post types.
* Enable/Disable likes per post type.
* Enable/Disable like text and counter.
* Like / Unlike feature on the same button.
* WooCommerce supported.
* Supports all WooCommerce product types (i.e. WooCommerce Subscriptions, WooCommerce Bookings)
* Post comments supported.
* Post comments on custom post types supported.

### Design:

* 29 icons available for like and dislike.
* Choose different icon for like and dislike.
* Uses Icomoon for Icons.
* Choose different text for like and dislike.
* Set any text for like and unlike.
* Set any icon for like and unlike.
* Control icon and text size.
* Control icon and text color.
* Set icon and text padding to position each element as needed.

### Shortcodes:

* Free placement of like button via shortcodes.
* Like button shortcode [oacsspl] accepts post_id as argument.
* Show user liked posts via shortcode.

### Developer:

* Use custom hook for posts.
* Use custom hook for WooCommerce likes.
* Caching support for all full page caching plugins.
* Ajax based like loading.
* Filter available for custom content before and after button.

### More:

* User post like are shown in backend user profile. Useful for admins.
* Disable Likes via post ID.
* Set or remove likes manually.
* Visitors likes can like as well.
* Shows on single posts only.
* Zero configuration required. Just activate and go.

### Compatibility:

* Compatible with all themes that use WordPress the_content (should be almost all!)
* Multisite compatible.
* WPML compatible.
* Tested with all major page builders.
* Ready for localization .mo / .po included.
* Works great on phones and tablets.
* All major browsers supported Chrome, Firefox, Safari, Opera, Edge, and Internet Explorer

### Known Incompatibilities:

* The Twenty Twenty-Three Default WordPress Theme does not work with the comment likes. Post likes work.

### Documentation and Support

More documentation:
[https://oacstudio.de/knowledgebase-category/solid-post-likes/installation](https://oacstudio.de/knowledgebase-category/solid-post-likes/installation)

Demo site: [https://spl-demo.oacstudio.de/](https://spl-demo.oacstudio.de/)

user: demo@oacstudio.de
pass: demo@oacstudio.de1


== Installation ==

1. Upload `simple-post-likes.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the new "oacs SPL" main menu item to configure.

== Frequently Asked Questions ==

= Is there a dislike / thumbs down button? =

No. This plugin uses the same button for like and unlike. You can however set different icons for the liked and unliked state.

= Can this be used as a voting tool? =

Not yet. The plugin cannot show a sorted post list based on all likes currently. But you can send us your feature request via:  https://oacstudio.de/ask-question/ or via the support form here.

= Is this a favorite tool? =

In a limited way. You can use these shortcodes to display a list of user liked posts: https://oacstudio.de/knowledgebase-category/solid-post-likes/shortcodes/

== Screenshots ==

1. General settings.
2. Like icon settings.

== Changelog ==

= 1.0.8 =
* Fix: Shortcode like list outputted in header in some cases.
* Fix: Add none to post type setting to allow shortcode usage only.

= 1.0.7 =
* Fix: Shortcode like button outputted in header in some cases.
* Fix: Attempt to read property "ID" on int notice on like list.

= 1.0.6 =
* Add priority to comment_text to fix deprecated message.
* Renamed CSS class to spl-is-active
* Likes can now be set to zero via the settings.
* Fix: Undefined Variable post.

= 1.0.5 =
* Update Carbon Fields.
* Fix individual comment like.

= 1.0.4 =
* Fix: array_key_exists on int error occuring on some systems.

= 1.0.3 =
* Fix: Undefined index: post_id
* Fix: Trying to access array offset on value of type int

= 1.0.2 =
* Add post_id as [oacsspl] shortcode argument. You can now use [oacsspl post_id=123] to show post likes of post with the ID = 123. Fallback post_id value is the current post ID.

= 1.0.1 =
* Fix [oacsspl] output by replacing escape function.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.3 =

Fixed two error notices.

= 1.0.2 =

[oacsspl] now accepts post_id as argument.

= 1.0.1 =

[oacsspl] output generated a string that prevented rendering the output due to usage of `esc_html`.

= 1.0 =

None yet. This is the beginning :).
