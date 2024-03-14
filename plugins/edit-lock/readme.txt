=== Edit Lock ===
Contributors: doublejar
Tags: wp-admin, posts, security, disable post edit
Requires at least: 5.4
Tested up to: 6.1
Stable tag: 1.0.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Disable page editing on selected pages, to protect the pages from accidental or unwanted changes that might break your site. By locking pages and posts, these crucial pages cannot be edited or deleted by users. An exception can be added for administrators to modify pages irregardless of locking status.

== Description ==

When you build a custom-made website, either for your client or yourself, there often are pages which contains HTML codes which may break if edited by casual users.

This plugin allows you to disable editing on selected pages, to protect the pages from accidental or unwanted changes. By locking pages and posts, these crucial pages cannot be edited or deleted by users. An exception can be added for administrators to modify pages irregardless of locking status.

Features:

* Locks any pages, posts, and even media files
* Custom post types are also supported
* Works with Gutenberg and Classic Editor
* Two locking mechanisms available
* Allows admin users to modify pages without unlocking or lock for everyone

Locking mechanisms:

* Lock mode -- Disable editing or deleting locked posts.
* Warn mode -- Warn users when editing locked posts. Quick edit and deletion are disabled.

== Frequently Asked Questions ==

= How to lock a post? =

If `lock toggle` option is enabled, you could lock/unlock a post when browsing posts on the admin page. Hover a post and click the lock icon below the post title.

If the lock toggle is not enabled, you could lock a post by specifying its ID on the settings page. Enter one ID per row into the `Locked posts` field.

= Are locked posts accessible by visitors on the site? =

Yes, locking posts only disables editing and still allows them to be viewed.

== Screenshots ==

1. Page listing on WP-Admin showing a locked page.
2. An error message is displayed when trying to edit a locked post.
3. In case of `warn mode`, a warning popup message is displayed when trying to edit a locked post, similar to when you are editing plugin or theme file for the first time.
4. Edit lock settings page.

== Changelog ==

= 1.0.3 =
* Tested compatibility with WordPress 6.1

= 1.0 =
* First release

