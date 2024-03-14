=== Orderli ===
Contributors: Samiconductor
Tags: links, ordered
Requires at least: 2.5
Tested up to: 3.1
Stable tag: 1.2

Adds an order field to the link editing page to allow forcing wp_list_bookmarks and get_bookmarks to output links in the specified order.

== Description ==

Orderli adds an order input box to the links editing page. The order number lets you explicitly specify an order for the `wp_list_bookmarks` and `get_bookmarks` functions. Giving links an order number automatically overrides any sort order you provide with the `orderby` argument. The links are sorted in ascending or descending order depending on the `order` argument you provide.

On the other hand, giving links the same order number will apply whatever sort option you provided with `orderby`. For instance, leaving all links with an order number of zero will have no affect on ordering. This lets you create groups by giving sets of links the same order number. You can then sort within those groups with `orderyby` parameter.

== Installation ==

1. Upload `orderli.php` to your plugins directory.
1. Activate Orderli in the plugins menu.
1. Edit your links and provide the desired order number to each link.

== Changelog ==

= 1.2 =
* Fixed pointer to emtpy link_id property
* Added error checking on order input

= 1.1 =
* Fixed function redefinition error. PHP != javascript :\

= 1.0 =
* Sorts in ascending or descending order based on `order` argument

= 0.2 =
* Used mergesort instead of usort for stability

= 0.1 =
* Added order meta box to link editing page
* Option saved when link added/edited and used when get_bookmarks called

== Upgrade Notice ==

= 1.2 =
Fixes pointer to empty link_id property. Adds error checking to ensure inputted order is a number.

= 1.1 =
Fixed function redefinition error if `wp_list_bookmarks` or `get_bookmarks` used more than once on a page.
