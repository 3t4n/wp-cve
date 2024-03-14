<?php
/*
Plugin Name: Proper Pagination
Plugin URI: http://www.nixonmcinnes.co.uk/2009/07/27/making-wordpress-navigation-more-usable-through-pagination-patterns/
Plugin Description: Renders proper pagination for a listings page.
Version: 1.3
Author: Steve Winton
Author URI: http://www.nixonmcinnes.co.uk/people/steve/
*/

class ProperPagination {
    
    /**
     * How many posts per page do we display?
     */
    var $posts_per_page = null;
    
    /**
     * How many posts were found by WP_Query?
     */
    var $found_posts = null;
    
    /**
     * Which page are we currently on?
     */
    var $page = null;
    
    /**
     * How many pages are there in total?
     */
    var $max_pages = null;
    
    /**
     * How many page links should we display at one time?
     *
     * Defaults to 10.
     */
    var $max_page_links = null;
    
    /**
     * Which page number should we start displaying page links from?
     */
    var $start;
    
    /**
     * Which page number should we stop displaying page links at?
     */
    var $end;
    
    /**
     * Which page are we currently at in the pagination loop?
     */
    var $current_page = 0;
    
    /**
     * Called when the plugin is first installed, adds the pp_max_pagelinks
     * option to wp_options, which is defaulted to 10.
     */
    function install() {
        if (get_option('pp_max_pagelinks') === false) {
            add_option('pp_max_pagelinks', '10', '', 'yes');
        }
    }
    
    /**
     * Invoked by the 'wp' hook once WP_Query has done its business, so we
     * can grab the number of found posts and initialize our other variables.
     */
    function init() {
        global $wp, $wp_query;
        
        // How many posts per page? Configured under general reading options
        $this->posts_per_page = (int)get_option('posts_per_page');
        
        // How many posts did WP_Query find?
        $this->found_posts = (int)$wp_query->found_posts;
        
        // Which page are we currently on?
        $this->page = isset($wp->query_vars['paged']) ? max(1, intval($wp->query_vars['paged'])) : 1;
        
        // How many pages in all are there?
        $this->max_pages = ceil($this->found_posts / $this->posts_per_page);
        
        // How many page links do we display at one time?
        $this->max_page_links = min((int)get_option('pp_max_pagelinks'), $this->max_pages);
        
        // Derive start and end values for the pagination links
        if ($this->max_pages <= $this->max_page_links) {
            // Start at the very beginning, end at the very end
            $this->start = 1;
            $this->end = $this->max_pages;
        } else {
            $this->start = max(1, $this->page - floor($this->max_page_links / 2));
            $this->end = min($this->max_pages, $this->start + $this->max_page_links - 1);
        }
        
        // Initialize the current page, incremented when the_pagination is first called
        // so start at (start - 1)
        $this->current_page = $this->start - 1;
    }
    
    /**
     * Determines whether the current ‘view’ has any pagination to display, i.e.
     * whether the content being browsed spans more than 1 page
     */
    function has_pagination() {
        return ($this->found_posts > $this->posts_per_page && (($this->current_page + 1) >= $this->start && $this->current_page < $this->end));
    }
    
    /**
     * Initiates the pagination context, should be called at the beginning of
     * each loop iteration
     */
    function the_pagination() {
        $this->current_page++;
    }
    
    /**
     * Resets the pagination context, so that the pagination loop can be
     * iterated over multiple times
     */
    function rewind_pagination() {
        $this->current_page = $this->start - 1;
    }
    
    /**
     * For use in the pagination loop, returns a boolean indicating whether the
     * current loop iteration is for the current page
     */
    function is_current_page() {
        return $this->current_page == $this->page;
    }
    
    /**
     * For use in the pagination loop, returns a boolean indicating whether
     * there is a previous page, e.g. when at page 1, there is no previous page
     */
    function has_previous_page() {
        return $this->page > 1;
    }
    
    /**
     * For use in the pagination loop, returns a boolean indicating whether
     * there is a next page, e.g. when at page N of N, there is no next page
     */
    function has_next_page() {
        return $this->page < $this->max_pages;
    }
    
    /**
     * For use in the pagination loop, echos the permalink for the current page
     */
    function the_page_permalink() {
        echo get_pagenum_link($this->current_page);
    }
    
    /**
     * For use in the pagination loop, echos the permalink for the previous page
     */
    function the_previous_page_permalink() {
        if ($this->has_previous_page()) {
            echo get_pagenum_link($this->page - 1);
        }
    }
    
    /**
     * For use in the pagination loop, echos the permalink for the next page
     */
    function the_next_page_permalink() {
        if ($this->has_next_page()) {
            echo get_pagenum_link($this->page + 1);
        }
    }
    
    /**
     * For use in the pagination loop, echos the permalink for the first page
     */
    function the_first_page_permalink() {
        echo get_pagenum_link(1);
    }
    
    /**
     * For use in the pagination loop, echos the permalink for the last page
     */
    function the_last_page_permalink() {
        echo get_pagenum_link($this->max_pages);
    }
    
    /**
     * For use in the pagination loop, echos the number of the current page
     * being iterated over (1..N where N is the total number of pages)
     */
    function the_page_num() {
        echo $this->current_page;
    }
}

global $pp;
if (is_null($pp)) {
    // Instantiate the singleton ProperPagination instance!
    $pp = new ProperPagination();
    
    // Register template tags...
    
    // pp_has_pagination
    if (!function_exists('pp_has_pagination')) {
        function pp_has_pagination() {
            global $pp;
            
            return $pp->has_pagination();
        }
    }

    // pp_the_pagination
    if (!function_exists('pp_the_pagination')) {
        function pp_the_pagination() {
            global $pp;
            
            return $pp->the_pagination();
        }
    }

    // pp_rewind_pagination
    if (!function_exists('pp_rewind_pagination')) {
        function pp_rewind_pagination() {
            global $pp;
            
            return $pp->rewind_pagination();
        }
    }

    // pp_is_current_page
    if (!function_exists('pp_is_current_page')) {
        function pp_is_current_page() {
            global $pp;
            
            return $pp->is_current_page();
        }
    }

    // pp_has_previous_page
    if (!function_exists('pp_has_previous_page')) {
        function pp_has_previous_page() {
            global $pp;
            
            return $pp->has_previous_page();
        }
    }

    // pp_has_next_page
    if (!function_exists('pp_has_next_page')) {
        function pp_has_next_page() {
            global $pp;
            
            return $pp->has_next_page();
        }
    }

    // pp_the_page_permalink
    if (!function_exists('pp_the_page_permalink')) {
        function pp_the_page_permalink() {
            global $pp;
            
            return $pp->the_page_permalink();
        }
    }

    // pp_the_previous_page_permalink
    if (!function_exists('pp_the_previous_page_permalink')) {
        function pp_the_previous_page_permalink() {
            global $pp;
            
            return $pp->the_previous_page_permalink();
        }
    }

    // pp_the_next_page_permalink
    if (!function_exists('pp_the_next_page_permalink')) {
        function pp_the_next_page_permalink() {
            global $pp;
            
            return $pp->the_next_page_permalink();
        }
    }

    // pp_the_first_page_permalink
    if (!function_exists('pp_the_first_page_permalink')) {
        function pp_the_first_page_permalink() {
            global $pp;
            
            return $pp->the_first_page_permalink();
        }
    }

    // pp_the_last_page_permalink
    if (!function_exists('pp_the_last_page_permalink')) {
        function pp_the_last_page_permalink() {
            global $pp;
            
            return $pp->the_last_page_permalink();
        }
    }

    // pp_the_page_num
    if (!function_exists('pp_the_page_num')) {
        function pp_the_page_num() {
            global $pp;
            
            return $pp->the_page_num();
        }
    }

    add_action('wp', array(&$pp, 'init'));
}

register_activation_hook(__FILE__, array('ProperPagination', 'install'));
?>
