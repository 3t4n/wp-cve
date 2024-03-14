<?php
/**
 * Scrolling Pagination
 *
 * @category ScrollingPag
 * @package Page_Links
*/
/**
 * Scrolling Pagination Functions
 *
 * @category ScrollingPag_Functions
 * @package ScrollingPag
 */
class SH_ScrollingPagination_Functions {
    /**
     * PHP 5 Constructor function
     *
     * @return void
     */
    public function __construct() {
        add_filter('generate_pagination', array($this, 'generate_scrolling_pagination'), 10, 4);
    }
    
    /**
     * Generates the scrolling pagination list.
     *
     * @param string $content
     * @param integer $page Current page
     * @param integer $pages
     * @param array $args
     *
     * @return string
     */
	 
    public function generate_scrolling_pagination($content, $page, $pages, $args, $spanid = "plp_inital_pagination") {
        global $sh_page_links, $auto_paged, $singlepage, $sh_autopag_functions, $scrolling_paged;
		
        $scrolling_paged = 0;
        if (!$auto_paged || ($pages < 1))
            return $content;
        $scrolling_paged = 1;
        $options = $sh_page_links->get_options();
        $scrolling_options = $options['scrolling_pagination'];
		$pages_per_scroll = (int) $scrolling_options['pages_to_scroll'];
        $r = $this->get_wp_link_pages_defaults();
        $r = wp_parse_args($scrolling_options, $r);
		$scrolls = $pages / $pages_per_scroll;
        if ($scrolls < 1)
            return $content;
        $content = "";
	
        //
        // generate our scrolling navigation...
        // Start with prev/next links
        $page = ($page == 0) ? 1 : $page;
        $prev_link = "";
        $next_link = "";
        if (!$singlepage) {
			
			$first_num = 1;
			$first_num = ' '
						. $r['link_before_outter']
						. sh_wp_link_page($first_num, $r['firstpageclass'])
						. $r['link_before']
						. $r['firstpage']
						. $r['link_after']
                        . '</a>'
						. $r['link_after_outter']
						. ' '
						. $args['seperator'];
			
			$prev_link = "";
            if ($page > 1) {
                $prev_num = $page - 1;
				$prev_link = ' '
						. $r['link_before_outter']
						. sh_wp_link_page($prev_num, $r['previouspageclass'])
						. $r['link_before']
                        . $r['previouspagelink']
                        . $r['link_after'] 
						. '</a>'
						. $r['link_after_outter']
						. ' '
						. $args['seperator'];
            }
			
			$next_link = "";
            if ($page < $pages) {
                $next_num = $page + 1;
				$next_link = ' '
						. $r['link_before_outter']
						. sh_wp_link_page($next_num, $r['nextpageclass'])
						. $r['link_before']
                        . $r['nextpagelink']
                        . $r['link_after'] 
						. '</a>'
						. $r['link_after_outter']
						. ' '
						. $args['seperator'];
            }
			
			$last_num = $pages;
            $last_num = ' '
						. $r['link_before_outter']
						. sh_wp_link_page($last_num, $r['lastpageclass'])
						. $r['link_before']
						. $r['lastpage']
						. $r['link_after']
                        . '</a>'
						. $r['link_after_outter'];
						
        }
        $output = "";
		
		// Set defaults...
		$link_wrapper_open = "";
		$link_wrapper_close = "";
		if (!empty($args)) {
			$link_wrapper = $args['link_wrapper'];
			$link_class = "";
			$link_classes[0] = '';
			$link_classes[1] = $args['link_wrapper_class'];
			$link_class = " class=\"" . trim(implode(" ", $link_classes)) . "\"";
			if ($link_wrapper) {
				$link_wrapper_open = "<{$link_wrapper}{$link_class}>";
				$link_wrapper_close = "</{$link_wrapper}>";
			}
		}
		
		//Calculating start depending of limit
		if ($pages_per_scroll == 1) {
			$start = $page;
			$end = $page;
		} else {
			$start = $page - floor($pages_per_scroll/2);
			$end = $page + floor($pages_per_scroll/2);
		}
		//If even we want to preffer show next than previous page
		if ($pages_per_scroll % 2 == 0) {
			$start++;
		}
		while ($start < 1) {
			$end++;
			if ($end > $pages) {
				$start = 1;
				$end = $pages;
				break;
			}
			$start++;
		}
		while ($end > $pages) {
			$start--;
			if ($start < 1) {
				$start = 1;
				$end = $pages;
				break;
			}
			$end--;
		}
		
		//Adding ellipsis to start
		if ($start > 1)
			$output .= 	" "
						. $r['elipsis']
						. " "
						. $args['seperator'];
		
		
		$title = get_the_title();
		for ($i = $start; $i <= ($end); $i++) {
			
            $link_html = _wp_link_page($i);
			
            $current_class = ($i == $page) ? 'current' : '';
            if ( ($options['pagination_styles']['use_ajax']!=0) || ( $i != $page || $singlepage ) ) {
			
			 	$link_html_formatted = " "
				 		. $r['link_before_outter']
						. $link_html
                        . $link_wrapper_open
						. str_replace(array('%page%','%title%'), array($i,$title), $args['pagelink'])
						. $link_wrapper_close
                        . "</a>"
						. $r['link_after_outter']
						. " "
						. $args['seperator'];
					
			} else {
				$link_html_formatted = 	$r['link_before_outter']
										. '<span class="plp-active-page">'
										. str_replace(array('%page%','%title%'), array($i,$title), $args['pagelink'])
										. "</span> "
										. $r['link_after_outter']
										. $args['seperator'];
				
			}
            $output .= $link_html_formatted;
        }
		
		//Adding ellipsis to end
		if ($end < $pages)
			$output .= 	" "
						. $r['elipsis']
						. " "
						. $args['seperator'];
        $content = '<span id="'. $spanid .'">'. $first_num . $prev_link . $output . $next_link . $last_num .'</span>';
        return $content;
    }
    /**
     * Retrieves array of wp_link_pages arguments using
     * wp_link_pages_args filter.
     *
     * @return array
     */
    public function get_wp_link_pages_defaults() {
        $defaults = array(
            'before' => '<div class="page-link">',
            'after' => '</div>',
            'link_before' => '',
            'link_after' => '',
            'next_or_number' => 'number',
            'nextpagelink' => __('Next', SH_PAGE_LINKS_DOMAIN) . ' &rarr;',
            'previouspagelink' => '&larr; '. __('Previous', SH_PAGE_LINKS_DOMAIN),
            'pagelink' => '%page%',
        );
        $r = wp_parse_args(array(), $defaults);
        $r = apply_filters('wp_link_pages_args', $r);
        return $r;
    }
}
