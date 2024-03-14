<?php
/**
 * Auto Pagination
 *
 * @category Auto_Pagination
 * @package Page_Links
*/

/**
 * Auto Pagination Options
 *
 * @category Auto_Pagination_Options
 * @package Auto_Pagination
*/

class SH_AutoPag_Options
{



    /**
     * PHP5 Constructor function
     * @return void
     */
    public function __construct()
    {
        add_action("sh_page_links_options_option_fields", array($this, 'options_fields'), 12);
        add_action("sh_page_links_options_option_sections", array($this, 'options_sections'), 12);
		
		add_action('admin_menu', array($this, 'admin_menu'), 12);
    }
	


	public function admin_menu() {
		// Add new menu option
		add_submenu_page(
			'sh-page-links-options',
			'Auto Pagination',
			'Auto Pagination',
			'manage_options',
			'sh-page-links-options#auto_pagination',
			array($this, 'show_menu_page')
		);
	}
	


	/**
     * Display options page
     * @return void
     */
    public function show_menu_page()
    {
        include_once SH_PAGE_LINKS_PATH . 'pages/page-plugin-options.php';
    }



    /**
     * Sets the options fields for the plugin
     *
     * @param array $options
     * @return array
     */
    public function options_fields($options = array())
    {
        $new_options = array(
            'auto_pagination' => array(
                'break_type' => array(
                    'id'      => 'break-type',
                    'title'   => __('Auto paginate by:', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'select',
                    'valid'   => 'integer',
                    'choices' => array(
                                    0 => __('paragraphs. ("I want a page break to occur after every X number of paragraphs.")', SH_PAGE_LINKS_DOMAIN),
                                    1 => __('pages. ("I want this long page/post to = X number of total pages.")', SH_PAGE_LINKS_DOMAIN),
                                    2 => __('words. ("I want a page break to occur after every X number of words.")', SH_PAGE_LINKS_DOMAIN)
                                    ),
                    'default' => 0,
                    'description' => '',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'paragraph_count' => array(
                    'id'      => 'paragraph-count',
                    'title'   => __('Number of paragraphs (min. 3), pages (min. 2), or words (min. 50).', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'integer',
                    'min'     => 2,
                    'default' => '3',
					/*'description' => __('If paragraphs, min. 3.', SH_PAGE_LINKS_DOMAIN),*/
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'inline_nextpage' => array(
                    'id'      => 'inline-nextpage',
                    'title'   => __('Ignore existing inline &lt;!--nextpage--&gt; tags?', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'checkbox',
                    'valid'   => 'boolean',
                    'default' => 1,
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
            ),
        );
        return array_merge((array)$options, $new_options);
    }

    
    /**
     * Defines Options page sections
     *
     * @param array $sections
     * @return array
     */
    public function options_sections($sections = array())
    {
        $new_sections = array(
            'auto_pagination' => array(
                'title' => __('Auto Pagination', SH_PAGE_LINKS_DOMAIN),
                'description' => "<p>" . __("The Auto Pagination module allows WordPress users to trade tedious in-line &lt;!--nextpage--&gt; tags for a site-wide management tool that paginates posts and pages quickly and uniformly. The module avoids splitting sentences and individual words, and to ensure it doesn't create widows, orphans, or trailing headers, it requires at least three paragraphs per page, two pages, and fifty words.", SH_PAGE_LINKS_DOMAIN) . "</p><p>" . __("By default, the module overrides any existing in-line &lt;!--nextpage--&gt; tags, but users can disable this function if they want to preserve legacy placement. Using the meta box that appears on the page/post editing screen, users can also customize the global \"Ignore existing inline &lt;!--nextpage--&gt; tags\" setting (below) on a page-by-page and post-by-post basis.", SH_PAGE_LINKS_DOMAIN) . "</p><p>" . __('Users can choose to paginate by paragraphs, pages, or words.', SH_PAGE_LINKS_DOMAIN) . "</p>",
            ),
        );
        return array_merge((array)$sections, $new_sections);
    }
}
