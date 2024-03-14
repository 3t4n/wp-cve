=== TagPages ===
Contributors: neoxx
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=AP3D3FJSUK3TY
Tags: taxonomy, taxonomies, tag, tags, tagging, page, pages, post-tag, post-tags, page-tag, page-tags, multisite, multi-site, network
Requires at least: 3.0
Tested up to: 4.9
Requires PHP: 5.3
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds post-tags functionality for pages.

== Description ==

This plugin is a [follow-up](https://www.bernhard-riedl.com/2010/08/01/tagpages-tags-functionality-for-pages-in-wordpress-30/) to the post which I wrote [a few years ago](https://www.bernhard-riedl.com/2007/10/06/wordpress-23-tagging-posts-and-pages/). - The idea was (and still is) to equip pages with [tags](https://en.support.wordpress.com/posts/tags/) and include their number in a combined posts and pages [tag-cloud](https://codex.wordpress.org/Function_Reference/wp_tag_cloud).

TagPages is fully compatible with [https/SSL/TLS-sites](https://codex.wordpress.org/Administration_Over_SSL) and WordPress multisite network.

**Plugin's website:** [https://www.bernhard-riedl.com/projects/](https://www.bernhard-riedl.com/projects/)

**Author's website:** [https://www.bernhard-riedl.com/](https://www.bernhard-riedl.com/)

== Installation ==

1. Copy the `tagpages` directory into your WordPress plugins directory (usually wp-content/plugins). Hint: You can also conduct this step within your Admin Menu.

2. In the WordPress Admin Menu go to the Plugins tab and activate the TagPages plugin.

3. Be happy and celebrate! (and maybe you want to add a link to [https://www.bernhard-riedl.com/projects/](http://www.bernhard-riedl.com/projects/))

== Frequently Asked Questions ==

= How can I display the chosen tags on my pages? =

You can use for example the built-in Theme Editor of WordPress to edit `page.php` (if such a template exists for your theme). WordPress provides two template functions which can be used out-of-the-box: [`the_tags`](https://codex.wordpress.org/Function_Reference/the_tags) and `get_the_tags`.

For further information about themes, please refer to the WordPress Codex Pages for [Theme Development](https://codex.wordpress.org/Theme_Development) or the user's manual of our theme.

= Will the tags I've created with TagPages be lost if I change the theme or disable TagPages? =

Not at all. - TagPages is based on the built-in WordPress taxonomy. - Even if you change the theme or deactivate TagPages, your tags and their relations to posts and pages will not be harmed. ;)

= Why do the Post Tags sections for posts and pages in the Admin Menu show the same tag count? =

The reason for that is that we combine the number of occurrences of tags used in posts and pages in the taxonomy `Post Tags`. Though, if you click on the number of a certain tag, WordPress will only show the related posts or pages of the selected tag.

= Does TagPages work for WordPress prior 3.0? =

Sorry folks, no it doesn't. - But you can have a look at [my post](https://www.bernhard-riedl.com/2007/10/06/wordpress-23-tagging-posts-and-pages/), which explains how to establish tags functionality for pages in WordPress 2.3 - 2.9.

== Screenshots ==

1. This screenshot illustrates editing a page in the Admin Menu.

2. The second picture shows the Pages section in the Admin Menu.

== Upgrade Notice ==

No upgrade notices so far...

== Changelog ==

= 1.64 =

* SSLified further links

= 1.63 =

* fixed some typos

= 1.62 =

* small security improvement

= 1.61 =

* SSLified links
* added assets/icons

= 1.60 =

* tested with PHP 5.4
* removed closing PHP tag before EOF
* removed reference sign on function calls
* cleaned-up code

= 1.50 =

* adapted to use the built-in [custom taxonomy column support for WordPress 3.5 and higher](https://core.trac.wordpress.org/ticket/21240)

= 1.42 =

* updated project-information

= 1.41 =

* adapted i18n strings
* updated German translation
* added Lithuanian translation, props [Vincentas Grinius](http://www.host1free.com)

= 1.40 =

* adapted query-handling on post-tags archive-pages

= 1.31 =

* small enhancements
* fixed WordPress deprecated warning

= 1.30 =

* enhanced compatibility with other custom taxonomy/post type plugins

= 1.20 =

* changed behaviour of Tags section for posts so only posts will be shown.
* implemented i18n for consistency - currently only one line ;)
* added German translation
* applied some minor internal changes

= 1.10 =

* all the fingers crossing didn't help. ;) Sorry, I screwed up and forgot to include some code, which prevented the tagged pages from showing up in the front-end (your theme)...
* added some meta-data to the front- and back-end

= 1.00 =

* initial release (fingers crossed)