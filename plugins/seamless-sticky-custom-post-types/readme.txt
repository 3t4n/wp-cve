=== Seamless Sticky Custom Post Types ===
Contributors: jaschaephraim
Donate link: http://jaschaephraim.com/
Tags: sticky, custom post type
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends the native sticky post functionality to custom post types in a way that is identical to default posts.

== Description ==

There are no settings or custom functions. Set custom post types to be sticky just as you would with regular posts, and those post id's will be included in the result of `get_option( 'sticky_posts' )`.

**PLEASE NOTE** In order to integrate as seamlessly as possible, this plugin will *not* alter any queries or filter any functions. If your sticky posts aren't appearing the way you want, there's probably a good reason in your query (Is `ignore_sticky_posts` set to `true`? Is `post_type` set to a different type?). This plugin may be most appropriate for developers who just want the native sticky post UI and no other superfluous features.

If you find this plugin useful, please rate it accordingly.

== Screenshots ==

1. The sticky post checkbox that appears for regular posts will be available for your custom post types.
2. As will the sticky post checkbox in quick edit.
