=== Bulk edit publish date ===
Contributors: dahousecatz
Tags: bulk, bulk actions, publish date
Requires at least: 4.7
Tested up to: 4.9.8
Requires PHP: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a bulk action to all post types to allow setting the publish date to a specific date time.

== Description ==

Adds a bulk action to all post types to allow setting the publish date to a specific date time.

The bulk action is by default applied to all post types.

The plugin uses input types of date and time to allow users to set the desired publish date / time.

This is a very lightweight plugin as has as little code as possible, and is also designed to be developer friendly
with alter hooks allowing other plugins to make changes to how this plugin operates.

== Installation ==

The easiest way to install this plugin is to go to Add New in the Plugins section of your blog admin and search for
"Bulk edit publish date." On the far right side of the search results, click "Install."

If the automatic process above fails, follow these simple steps to do a manual install:

1. Extract the contents of the zip file into your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can I edit the post types that this bulk action will apply to? =

The post types that this should be applied to can be editing by using the bulk_edit_publish_date_post_types filter.

For example you could put this in your code to remove the bulk action from the post type "post":

    add_filter('bulk_edit_publish_date_post_types', 'my_plugin_bulk_edit_publish_date_post_types');
    function my_plugin_bulk_edit_publish_date_post_types($post_types) {
        unset($post_types['post']);
        return $post_types;
    }

= Can I edit the post data used to update the post before the post is saved? =

Yes, the filter bulk_edit_publish_date_post_update_data can be used to make any changes to the update data before the
post is saved.

= Can I edit the admin notice before it's displayed? =

Yes, the filter bulk_edit_publish_date_admin_notice can be used to make any changes to the admin notice before it's
output to the screen.

== Screenshots ==

1. Shows the set publish date bulk action in the drop down.
2. Shows setting the date using the html 5 date element type before the bulk action  is applied.

== Changelog ==

= 1.0 =
* First version released.

== Upgrade Notice ==

None yet.
