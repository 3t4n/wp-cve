<?php
/**
 * SingleView
 *
 * @category Single_View
 * @package Page_Links
 * @version $Id$
 */

/**
 * SingleView Options Class
 *
 * @category Single_View_Options
 * @package Page_Links
 */
class SH_PageLinks_SingleView_Options
{
    public function __construct()
    {

        add_action("sh_page_links_options_option_fields", array($this, 'options_fields'));
        add_action("sh_page_links_options_option_sections", array($this, 'options_sections'));

    }

    /**
     * Sets the options fields for the plugin
     *
     * @param array $options
     * @return array
     */
    public function options_fields($options = array())
    {

        /*
         * Section
         *      |_ Option
         *              |_ Option Setting
         */

        if (class_exists('SH_PageLinks_PagStyles_Bootstrap') || class_exists('SH_PageLinks_AutoPag_Bootstrap') || class_exists('SH_PageLinks_ScrollingPagination_Bootstrap'))
            $enable_title = __("Enable PLP for these post types:", SH_PAGE_LINKS_DOMAIN);
        else
            $enable_title = __("Enable a 'Single Page' option for these post types:", SH_PAGE_LINKS_DOMAIN);

        $new_options = array(
            'single_view' => array(
                'view_single_link' => array(
                    'id'      => 'view-single-link',
                    'title'   => __('Globally enable a single-page option for all page link lists.', SH_PAGE_LINKS_DOMAIN),
                    //'description'   => __('Globally enable a single-page option for all page link lists.', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'checkbox',
                    'valid'   => 'boolean',
                    'default' => 1,
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                
                'text_single_link' => array(
                    'id'      => 'text-single-link',
                    'title'   => __('\'Single Page\' Link Text', SH_PAGE_LINKS_DOMAIN),
                    //'description'   => __('Globally enable a single-page option for all page link lists.', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => 'Single Page',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				
                'text_multiple_link' => array(
                    'id'      => 'text-multiple-link',
                    'title'   => __('\'Multi-Page\' Link Text', SH_PAGE_LINKS_DOMAIN),
                    //'description'   => __('Globally enable a single-page option for all page link lists.', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
					'valid'	  => 'formatted',
                    'default' => 'Multi-Page',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				
				'header' => array(
                    'id'      => 'header',
                    'title'   => '<div style="position:absolute; font-style:italic; font-weight: normal;">' . __('After enabling, users can also activate the single-page view by appending "?singlepage=1" to the end of any page or post (e.g., http://sampleurl.com/page-title?singlepage=1).', SH_PAGE_LINKS_DOMAIN) . '</div><div style="height:20px"></div>',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
                
                'enabled_posts' => array(
                    'id'      => 'enabled-posts',
                    'title'   => $enable_title,
                    //'description'   => __('Globally enable a single-page option for all page link lists.', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'multicheckcp',
                    'valid'   => 'array',
                    'default' => array('post', 'page'),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				
                'phpstatus' => array(
                    'id'      => 'php-status',
                    'title'   => __('System Status:', SH_PAGE_LINKS_DOMAIN),
                    //'description'   => __('Globally enable a single-page option for all page link lists.', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'phpstatus',
                    'valid'   => 'array',
                    'default' => '',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				
            ),
        );
		
        return array_merge($new_options, (array)$options);
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
            'single_view' => array(
                'title' => __('Single Page', SH_PAGE_LINKS_DOMAIN),
                'description' => __("<p>While the WordPress <a href=\"http://codex.wordpress.org/Styling_Page-Links\" target=\"_blank\">Page-Link tag</a> makes integrating page links rather effortless, it doesn't offer a native single-page option. Addressing this limitation, the basic Page-Links Plus plugin adds this option to WordPress page lists.</p><p>The Single Page module also serves as the basic framework for the <a href=\"http://pagelinksplus.com/\" target=\"_blank\">other Page-Links Plus modules</a>.</p>", SH_PAGE_LINKS_DOMAIN),
            ),
        );

        return array_merge($new_sections, (array)$sections);
    }
}