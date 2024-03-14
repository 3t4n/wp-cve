<?php

/**
 * SingleView
 *
 * @category Single_View
 * @package Page_Links
 * @version $Id$
 */

/**
 * SingleView Class
 *
 * @category Single_View
 * @package Page_Links
 */
class SH_PageLinks_SingleView {

    /**
     * PHP5 Constructor method
     * @return void
     */
    public function __construct() {
        global $sh_page_links;

        $sh_singleview_options = new SH_PageLinks_SingleView_Options();

        add_filter('wp_link_pages_args', array($this, 'view_single_page_args'),12);
        add_filter('the_content', array($this, 'add_all_pages'));
    }

    /**
     * Gernerates link for Single Page view.
     *
     * @global object $post
     * @global int $page
     *
     * @param array $val Arguments array from wp_link_pages_arg
     * @return string
     */
    public function view_single_page_args($val) {
        global $post, $page, $sh_page_links, $scrolling_paged;

        if ($scrolling_paged)
            return $val;

        $options = $sh_page_links->get_options();

        // Get singleview option...
        $show_globally = !empty($options['single_view']['view_single_link']) ? $options['single_view']['view_single_link'] : 0;

        $singlepage = $singlepage = !empty($_GET['singlepage']) ? 1 : 0;

        $newval = $val;
        $show_single_page = !empty($val['showsinglepage']) ? $val['showsinglepage'] : 0;

        if ($show_single_page == 1 || $show_globally && !empty($newval)) {
            $link_wrapper = $options['pagination_styles']['link_wrapper'];
            if ($link_wrapper) {
                $link_wrapper_open = "<{$link_wrapper}{$link_class}>";
                $link_wrapper_close = "</{$link_wrapper}>";
            }

            $newval["after"] = $this->add_single_page($options['pagination_styles']['seperator'],$link_wrapper_open,$link_wrapper_close) . $newval["after"];

            // also throw in an additional argument for other plugins
            $newval['single'] = $newval['after'];
        }

        if ($singlepage)
            $page = 0;

        return $newval;
    }
	
	/**
     * Separate the "Single Page" option, so extensions
	 * may call it.
     *
     * @global integer $multipage
     * @global object $post
     *
     * @return string
     */
	public function add_single_page($sep,$wrap_open,$wrap_close) {
		global $sh_page_links;
		$options = $sh_page_links->get_options();
		
		$singlepage = $singlepage = !empty($_GET['singlepage']) ? 1 : 0;

        if ($singlepage == 0) {
    		$url = add_query_arg('singlepage', 1);
    		$link_text = !empty($options['single_view']['text_single_link']) ? $options['single_view']['text_single_link'] : __("Single Page", SH_PAGE_LINKS_DOMAIN);
		} else {
            $url = get_permalink();
            $link_text = !empty($options['single_view']['text_multiple_link']) ? $options['single_view']['text_multiple_link'] : __("Multi-Page", SH_PAGE_LINKS_DOMAIN);
        }

		$link = 	" "
					. $sep 
					. " " 
					. "<a data-ajax=\"0\" href=\"{$url}\">"
                    . $wrap_open
                    . $link_text
                    . $wrap_close
                    . "</a>";
					
		return $link;
		
	}

    /**
     * Add new code to pages and posts, except where single view is already on.
     *
     * @global integer $multipage
     * @global object $post
     *
     * @param string $content
     * @return string
     */
    public function add_all_pages($content) {
		
        global $multipage, $post, $auto_paged, $singlepage, $scrolling_paged;

        $singlepage = !empty($_GET['singlepage']) ? 1 : 0;

        if ($singlepage == 1 && ($multipage ) && !is_front_page()) {
            remove_action('the_content', array($this, 'add_all_pages'));
            $content = apply_filters('the_content', $post->post_content);
            add_action('the_content', array($this, 'add_all_pages'));
        }

        return $content;
    }

}
