<?php
/**
 * Pagination Styles
 *
 * @category PagStyles
 * @package Page_Links
 */

/**
 * Pagination Styles Options Class
 *
 * @category
 * @package
 */
class SH_PagStyles_Options
{
    public function __construct()
    {	
        add_action("sh_page_links_options_option_fields", array($this, 'options_fields'), 11);
        add_action("sh_page_links_options_option_sections", array($this, 'options_sections'), 11);
		
		add_action('admin_menu', array($this, 'admin_menu'), 11);
    }
	
	public function admin_menu() {
		// Add new menu option
		add_submenu_page(
			'sh-page-links-options',
			__('Pagination Controls', SH_PAGE_LINKS_DOMAIN),
			__('Pagination Controls', SH_PAGE_LINKS_DOMAIN),
			'manage_options',
			'sh-page-links-options#pagination_styles',
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
            'pagination_styles' => array(
                'use_ajax' => array(
                    'id'      => 'use-ajax',
                    'title'   => __('Use Ajax for Pagination', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'select',
                    'valid'   => 'integer',
                    'choices' => array(
                                    0 => __("Disabled", SH_PAGE_LINKS_DOMAIN),
                                    1 => __("Vertical", SH_PAGE_LINKS_DOMAIN),
                                    2 => __("Horizontal", SH_PAGE_LINKS_DOMAIN)
                                    ),
                    'default' => 0,
                    'description' => __('If you select "vertical," your content will paginate as the viewer scrolls down the page. If you select "horizontal," your content will paginate asynchronously using standard pagination links.', SH_PAGE_LINKS_DOMAIN) . "<BR /><BR />" . sprintf( __('PLEASE NOTE: (1) If you use Ajax for pagination, be sure to define both a "%s" (eg., "div") and a "%s" (eg., "post-pagination") below. (2) Horizontal ajax pagination only works if your site uses a "Post name" %s permalink structure. You can read more about using custom permalinks in the %s and adjust your site\'s settings %s.', SH_PAGE_LINKS_DOMAIN), '<a href="#wrapper-tag">'. __('Page Links Wrapper Element', SH_PAGE_LINKS_DOMAIN) .'</a>', '<a href="#wrapper-id">'. __('Page Links Wrapper ID', SH_PAGE_LINKS_DOMAIN) .'</a>', '(%postname%)', '<a href="http://codex.wordpress.org/Using_Permalinks" target="_blank">WordPress Codex</a>', '<a href="options-permalink.php">'. __('here', SH_PAGE_LINKS_DOMAIN) . '</a>'),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'before' => array(
                    'id'      => 'before-content',
                    'title'   => __('Before', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '<p>' . __('Pages:'),
                    'description' => __('HTML element and/or text to insert before all the page links. Defaults to "&lt;p&gt;Pages:".',
                                        SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
                'after' => array(
                    'id'      => 'after-content',
                    'title'   => __('After', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '</p>',
                    'description' => __('"before" HTML tag closure and/or text to insert after all the page links. Defaults to "&lt;/p&gt;".',
                                        SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
                'link_before' => array(
                    'id'          => 'link-before',
                    'title'       => __('Before Link', SH_PAGE_LINKS_DOMAIN),
                    'type'        => 'text',
                    'valid'       => 'formatted',
                    'default'     => '',
                    'description' => __('Text to insert before, that is, to the left of, the Scrolling Pagination module\'s "nextpagelink" and "previouspagelink". Defaults to (blank).', SH_PAGE_LINKS_DOMAIN),
                    'callback'    => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
                'link_after' => array(
                    'id'          => 'link-after',
                    'title'       => __('After Link', SH_PAGE_LINKS_DOMAIN),
                    'type'        => 'text',
                    'valid'       => 'formatted',
                    'default'     => '',
                    'description' => __('Text to insert after, that is, to the right of, the Scrolling Pagination module\'s "nextpagelink" and "previouspagelink". Defaults to (blank).', SH_PAGE_LINKS_DOMAIN),
                    'callback'    => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
                'pagelink' => array(
                    'id'          => 'pagelink',
                    'title'       => __('Number Format', SH_PAGE_LINKS_DOMAIN),
                    'type'        => 'text',
                    'valid'       => 'formatted',
                    'default'     => '%page%',
                    'description' => __('An improvement on the native WP parameter, customize page number links using numbers, words, or a combination of both. You can use the following types of variables: %page% (page number) or %title% (post/page title). Defaults to "%page%".',
                                        SH_PAGE_LINKS_DOMAIN),
                    'callback'    => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
				
				'header' => array(
                    'id'      => 'header',
                    'title'   => '<p class="p-header-2"><strong>' . __('Styles', SH_PAGE_LINKS_DOMAIN) .'</strong></p>',
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
				'echo-tag' => array(
                    'id'      => 'echo-tag',
                    'title'   => __('Echo', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'html',
                    'default' => '1',
                    'description' => __('Echo (1) or return (0) the page list. Defaults to "1" (echo).', SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),  
				'seperator' => array(
                    'id'      => 'seperator',
                    'title'   => __('Pagination Separator', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '|',
                    'description' => __('Pagination separator. Defaults to " | ".', SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),                 
				'wrapper_tag' => array(
                    'id'      => 'wrapper-tag',
                    'title'   => __('Page Links Wrapper Element', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'html',
                    'default' => '',
                    'description' => __('Adds an HTML element around all pagination links. (See "Sample Anatomy" below.) Defaults to (blank).</div>', SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb'),
                ),
                'wrapper_class' => array(
                    'id'      => 'wrapper-class',
                    'title'   => __('Page Links Wrapper Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => '',
                    'description' => __('Assigns a single CSS class to the page links wrapper element. Defaults to (blank).', SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
				'wrapper_id' => array(
                    'id'      => 'wrapper-id',
                    'title'   => __('Page Links Wrapper ID', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'formatted',
                    'default' => 'post-pagination',
                    'description' => __('Assigns a CSS ID to the page links wrapper element. Defaults to "post-pagination".', SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'link_wrapper' => array(
                    'id'      => 'link-wrapper',
                    'title'   => __('Inner Link Wrapper Element', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'html',
                    'default' => '',
                    'description' => __('Wraps each pagination link text in an HTML element. (See "Sample Anatomy" below.) The single-page option and the scrolling ellipsis ("...") are unaffected. Defaults to (blank).',
                                        SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'link_wrapper_class' => array(
                    'id'      => 'link-wrapper-class',
                    'title'   => __('Inner Link Wrapper Element Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'html-class',
                    'default' => '',
                    'description' => __('Assigns a single CSS class to the inner link wrapper element. The single-page option and the scrolling ellipsis ("...") are unaffected. Defaults to (blank).',SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'link_wrapper_outter' => array(
                    'id'      => 'link-wrapper-outter',
                    'title'   => __('Outter Link Wrapper Element', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'html',
                    'default' => '',
                    'description' => __('Wraps each pagination link in an HTML element. (See "Sample Anatomy" below.) The single-page option and the scrolling ellipsis ("...") are unaffected. Defaults to (blank).',
                                        SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                'link_wrapper_outter_class' => array(
                    'id'      => 'link-wrapper-outter-class',
                    'title'   => __('Outter Link Wrapper Element Class', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'text',
                    'valid'   => 'html-class',
                    'default' => '',
                    'description' => __('Assigns a single CSS class to the outter link wrapper element. The single-page option and the scrolling ellipsis ("...") are unaffected. Defaults to (blank).',SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),
                /*'archive_pages' => array(
                    'id'      => 'archive-pages',
                    'title'   => __('Enable Page-Links Plus for archive pages?', SH_PAGE_LINKS_DOMAIN),
                    'type'    => 'checkbox',
                    'valid'   => 'boolean',
                    'default' => 1,
                    'description' => __('If selected, Page-Links Plus will control the appearance and functionality of page links that appear on archive pages (categories, tags, authors, dates, &c.).', SH_PAGE_LINKS_DOMAIN),
                    'callback' => array('SH_PageLinks_Options', 'settings_field_cb')
                ),*/
				'heade2' => array(
                    'id'      => 'heade2',
                    'title'   => '<p class="p-header-2"><strong>'. __("Sample Anatomy", SH_PAGE_LINKS_DOMAIN) .'</strong></p>
						<div id="sample_anatomy" class="anatomy_holder" style=""></div>
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
            'pagination_styles' => array(
                'title' => __('Pagination Controls', SH_PAGE_LINKS_DOMAIN),
                'description' => __('<p>Integrate HTML elements and CSS classes and id\'s and manage all <a href="http://codex.wordpress.org/Function_Reference/wp_link_pages" target="_blank">wp_link_pages() parameters</a> save "nextpagelink" and "previouspagelink." (These are managed by the Scrolling Pagination module.)</p><p>"before," "after," "Page Links Wrapper Element," and "Navigation Link Wrapper Element" will accept all <a href="http://en.wikipedia.org/wiki/HTML_element" target="_blank">HTML document body elements</a> save &lt;script&gt; ... &lt;/script&gt;.</p> <p>The parameter fields allow special characters like "&amp;raquo" (&raquo;), "&amp;laquo;" (&laquo;), "&amp;larr;" (&larr;), and "&amp;rarr;" (&rarr;) and letter forms like "&amp;uuml;" (&uuml;), "&amp;aacute;" (&aacute;), and "&amp;ccedil;" (&ccedil;). However, to use these characters, copy and paste or type the actual character, vs. its HTML entity code, into the text fields. WordPress will handle the encoding automatically.</p><p class="p-header"><strong>Parameters</strong></p>', SH_PAGE_LINKS_DOMAIN),
            ),
        );
        return array_merge((array)$sections, $new_sections);
    }
}
