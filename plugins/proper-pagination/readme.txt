=== Plugin Name ===
Contributors: swinton
Tags: pagination, framework, usability, navigation
Requires at least: 2.7
Tested up to: 2.9
Stable tag: trunk

A flexible framework for creating semantic pagination controls for a listings page based on established usability patterns.

== Description ==

This plugin is primarily aimed at theme developers, and intends to ease the
creation of semantic, usable pagination controls for WordPress listings pages, such as
arhive, author, category, search, tag listings etc.

Inspired by [The Loop](http://codex.wordpress.org/The_Loop) and [WP_Query](http://codex.wordpress.org/Function_Reference/WP_Query), this plugin stays out of the theme
developer's way, allowing her to create the markup needed by providing the
following template tags:

* `pp_has_pagination` - determines whether the current 'view' has any pagination to display, i.e. whether the content being browsed spans more than 1 page
* `pp_the_pagination` - initiates the pagination context, should be called at the beginning of each loop iteration
* `pp_rewind_pagination` - resets the pagination context, so that the pagination loop can be iterated over multiple times
* `pp_is_current_page` - for use in the pagination loop, returns a boolean indicating whether the current loop iteration is for the current page
* `pp_has_previous_page` - for use in the pagination loop, returns a boolean indicating whether there is a previous page, e.g. when at page 1, there is no previous page
* `pp_has_next_page` - for use in the pagination loop, returns a boolean indicating whether there is a next page, e.g. when at page N of N, there is no next page
* `pp_the_page_permalink` - for use in the pagination loop, echos the permalink for the current page
* `pp_the_previous_page_permalink` - for use in the pagination loop, echos the permalink for the previous page
* `pp_the_next_page_permalink` - for use in the pagination loop, echos the permalink for the next page
* `pp_the_first_page_permalink` - for use in the pagination loop, echos the permalink for the first page
* `pp_the_last_page_permalink` - for use in the pagination loop, echos the permalink for the last page
* `pp_the_page_num` - for use in the pagination loop, echos the page number of the current page being iterated over

For example, the following arrangement of template tags would provide a rudimentary pagination control:

`<?php if (pp_has_pagination()) : ?>
    <div class="pagination">

        <!-- the previous page -->
        <?php pp_the_pagination(); if (pp_has_previous_page()) : ?>
            <a href="<?php pp_the_previous_page_permalink(); ?>" class="prev">newer stories</a>
        <?php else : ?>
            <span class="current prev">newer stories</span>
        <?php endif; pp_rewind_pagination(); ?>

        <!-- the page links -->
        <?php while(pp_has_pagination()) : pp_the_pagination(); ?>
            <?php if (pp_is_current_page()) : ?>
                <span class="current"><?php pp_the_page_num(); ?></span>
            <?php else : ?>
                <a href="<?php pp_the_page_permalink(); ?>"><?php pp_the_page_num(); ?></a>
            <?php endif; ?>
        <?php endwhile; pp_rewind_pagination(); ?>

        <!-- the next page -->
        <?php pp_the_pagination(); if (pp_has_next_page()) : ?>
            <a href="<?php pp_the_next_page_permalink(); ?>" class="next">older stories</a>
        <?php else : ?>
            <span class="current next">older stories</span>
        <?php endif; pp_rewind_pagination(); ?>

    </div>
<?php endif; ?>`


The template tags on offer by this plugin provide the theme developer with an
unlimited array of possibilities for marking up the pagination control in a
semantic manner.

For more information, see the original [blog post](http://www.nixonmcinnes.co.uk/2009/07/27/making-wordpress-navigation-more-usable-through-pagination-patterns/).

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `proper-pagination.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create your desired pagination in your theme using the installed template tags


== Changelog ==

= 1.3 =
* Fixed bug where the number of page links to display at one time was surpassing the total number of pages (occurred when the total number of pages was less than the configured number of max pagelinks)

= 1.2 =
* Fixed bug when calculating the number of page links to display, this would be wrong if the number of posts found was less than the configured pp_max_pagelinks option.

= 1.1 =
* Fixed bug on activation hook, when adding default option values.

= 1.0 =
* First release.
