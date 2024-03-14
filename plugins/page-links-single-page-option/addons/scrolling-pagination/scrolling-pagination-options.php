<?php
/**
 * Description
 *
 * @category
 * @package
 */
/**
 * Class skeleton
 *
 * @category
 * @package
 */
class SH_ScrollingPagination_Options
{
    /**
     * PHP5 Constructor function
     * @return void
     */
    public function __construct()
    {
        add_action("sh_page_links_options_option_fields", array($this, 'options_fields'), 13);
        add_action("sh_page_links_options_option_sections", array($this, 'options_sections'), 13);
		
		add_action('admin_menu', array($this, 'admin_menu'), 13);
    }
	
	public function admin_menu() {
		// Add new menu option
		add_submenu_page(
			'sh-page-links-options',
			__('Scrolling Pagination', SH_PAGE_LINKS_DOMAIN),
			__('Scrolling Pagination', SH_PAGE_LINKS_DOMAIN),
			'manage_options',
			'sh-page-links-options#scrolling_pagination',
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
            'scrolling_pagination' => array(
                'pages_to_scroll' => array(
                    'id'      => 'pages-to-scroll-count',
                    'title'   => __('Number of pages in page list.', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'integer',
                    'default' => '3',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'nextpagelink' => array(
                    'id'      => 'nextpagelink',
                    'title'   => __('Next Page Text', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => __('Next &rarr;'),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'nextpageclass' => array(
                    'id'      => 'nextpageclass',
                    'title'   => __('Next Page Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'previouspagelink' => array(
                    'id'      => 'previouspagelink',
                    'title'   => __('Previous Page Text', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => __('&larr; Previous'),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'previouspageclass' => array(
                    'id'      => 'previouspageclass',
                    'title'   => __('Previous Page Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				'firstpage' => array(
                    'id'      => 'firstpage',
                    'title'   => __('First Page Text', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => __('First'),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				'firstpageclass' => array(
                    'id'      => 'firstpageclass',
                    'title'   => __('First Page Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				'lastpage' => array(
                    'id'      => 'lastpage',
                    'title'   => __('Last Page Text', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => __('Last'),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				'lastpageclass' => array(
                    'id'      => 'lastpageclass',
                    'title'   => __('Last Page Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				'elipsis' => array(
                    'id'      => 'elipsis',
                    'title'   => __('Scroll Marker', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '...',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'heade2' => array(
                    'id'      => 'heade3',
                    'title'   => '<p class="p-header-2"><strong>'. __("Sample Anatomy", SH_PAGE_LINKS_DOMAIN) .'</strong></p>
                        <div id="sample_anatomyb" class="anatomy_holder"></div>
                        <div style="height:420px"></div>',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
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
            'scrolling_pagination' => array(
                'title' => __('Scrolling Pagination', SH_PAGE_LINKS_DOMAIN),
                'description' => __("<p>The Scrolling Pagination module allows uses to integrate custom-length, scrolling page lists and manage the \"nextpagelink\" and \"previouspagelink\" <a href=\"http://codex.wordpress.org/Function_Reference/wp_link_pages\" target=\"_blank\">wp_link_pages() parameters</a>. (The other parameters are managed by the Pagination Styles module on which this module is dependent.)</p><p>The latter two fields allow special characters like \"&amp;raquo\" (&raquo;), \"&amp;laquo;\" (&laquo;), \"&amp;larr;\" (&larr;), and \"&amp;rarr;\" (&rarr;). However, to use these characters, copy and paste or type the actual character, vs. its HTML entity code, into the latter two text fields. WordPress will handle the encoding automatically.</p>", SH_PAGE_LINKS_DOMAIN),
            ),
        );
        return array_merge((array)$sections, $new_sections);
    }
}
