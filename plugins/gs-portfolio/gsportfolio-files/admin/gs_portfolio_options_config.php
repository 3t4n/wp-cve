<?php
/**
 * This page shows the procedural or functional example
 * OOP way example is given on the main plugin file.
 * @author Tareq Hasan <tareq@weDevs.com>
 */
 
/**
 * WordPress settings API demo class
 * @author Tareq Hasan
 */

 
if ( !class_exists('GS_portfolio_Settings_Config' ) ):
class GS_portfolio_Settings_Config {

    private $settings_api;

    function __construct() {
        $this->settings_api = new GS_portfolio_WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
	
		add_submenu_page( 'edit.php?post_type=gs-portfolio', 'Portfolio Settings', 'Portfolio Settings', 'delete_posts', 'portfolio-settings', array($this, 'plugin_page')); 
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' 	=> 'gs_p_general',
                'title' => __( 'Portfolio Settings', 'gsportfolio' )
            ),
            array(
                'id'    => 'gs_p_advance',
                'title' => __( 'Advance Settings', 'gsportfolio' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'gs_p_general' => array(
                // Columns
                array(
                    'name'      => 'gs_p_cols',
                    'label'     => __( 'Columns', 'gsportfolio' ),
                    'desc'      => __( 'Select number of Portfolio columns', 'gsportfolio' ),
                    'type'      => 'select',
                    'default'   => '4',
                    'options'   => array(
                        '6'    => '2 Columns',
                        '4'      => '3 Columns',
                        '3'      => '4 Columns'
                    )
                ),
                // Filter position
                array(
                    'name'      => 'gs_p_fil_p',
                    'label'     => __( 'Filter Category Position', 'gsportfolio' ),
                    'desc'      => __( 'Select Filter Category Position', 'gsportfolio' ),
                    'type'      => 'select',
                    'default'   => 'left',
                    'options'   => array(
                        'left'    => 'Left',
                        'center'  => 'Center',
                        'right'   => 'Right'
                    )
                ),
                // Portfolio Width
                // array(
                //     'name'  => 'gs_p_width',
                //     'label' => __( 'Portfolio Image Width (px)', 'gsportfolio' ),
                //     'desc'  => __( 'Increase / decrease Portfolio image size in width. Default 625', 'gsportfolio' ),
                //     'type'  => 'number',
                //     'min'   => 0,
                //     'max'   => 1000,
                //     'default' => 625
                // ), 
                // Portfolio height
                // array(
                //     'name'  => 'gs_p_height',
                //     'label' => __( 'Portfolio Image Height (px)', 'gsportfolio' ),
                //     'desc'  => __( 'Increase / decrease Portfolio image size in height. Default 400', 'gsportfolio' ),
                //     'type'  => 'number',
                //     'min'   => 0,
                //     'max'   => 1000,
                //     'default' => 400
                // ),
                // Portfolio Button Text
                array(
                    'name' => 'gs_p_button_txt',
                    'label' => __( 'Portfolio Button Text', 'gsportfolio' ),
                    'desc' => __( 'Change preferred text for external link\'s button ', 'gsportfolio' ),
                    'type' => 'text',
                    'default' => 'Portfolio Details'
                ),
                // Primary Text Color
                array(
                    'name'    => 'gs_p_pri_color',
                    'label'   => __( 'Primary Color', 'gsportfolio' ),
                    'desc'    => __( 'Select color for external link Background, Popup Navigation & close.', 'gsportfolio' ),
                    'type'    => 'color',
                    'default' => '#fa566f'
                ),

                // Hover effect
                array(
                    'name'      => 'gs_p_hover',
                    'label'     => __( 'Theme Effect', 'gsportfolio' ),
                    'desc'      => __( 'Select preferred hover effect', 'gsportfolio' ),
                    'type'      => 'select',
                    'default'   => 'effect-sadie',
                    'options'   => array(
                        'effect-sadie'      => 'Grid - Sadie',
                        'effect-julia'      => 'Grid - Julia',
                        'effect-kira'       => 'Grid - Kira',
                        'effect-winston'    => 'Grid - Winston',
                        'effect-zoe'        => 'Grid - Zoe',
                        'effect_horizontal_1'   => 'Horizontal - Square Right (Pro)',
                        'effect_horizontal_2'   => 'Horizontal - Square Left (Pro)',
                        'effect_horizontal_3'   => 'Horizontal - Circle Right (Pro)',
                        'effect_horizontal_4'   => 'Horizontal - Circle Left (Pro)',
                        'effect-hexgrid'    => 'HexGrid & Popup (Pro)',
                        'effect-slider'     => 'Slider & Popup (Pro)',
                        'effect-masonary'   => 'Masonry (Pro)',
                        'filter-effect-sadie'   => 'Filter & Sadie Hover (Pro)',
                        'filter-effect-julia'   => 'Filter & Julia Hover (Pro)',
                        'filter-effect-kira'    => 'Filter & Kira Hover (Pro)',
                        'filter-effect-winston' => 'Filter & Winston Hover (Pro)',
                        'filter-effect-zoe'     => 'Filter & Zoe Hover (Pro)',
                        'filter-selected-cats'     => 'Filter Selected Cats (Pro)',
                        'effect-grid-slide'     => 'Grid Slide (Pro)',
                        'effect-column-animated'    => 'Masonry Animated (Pro)',
                        'effect-column-3d'          => 'Theme 3D (Pro)',
                        'effect-timeline'           => 'Theme Timeline (Pro)',
                        // 'effect-mosaic'     => 'Mosaic', // next release
                    )
                ),
                // popup effect
                array(
                    'name'      => 'gs_p_popup',
                    'label'     => __( 'Popup Effect', 'gsportfolio' ),
                    'desc'      => __( 'Select preferred popup effect', 'gsportfolio' ),
                    'type'      => 'select',
                    'default'   => 'mfp-move-horizontal',
                    'options'   => array(
                        'mfp-move-from-top'     => 'From Top',
                        'mfp-move-horizontal'   => 'Horizontal',
                        'mfp-newspaper'         => 'Newspaper',
                        'mfp-3d-unfold'         => '3d Unfold',
                        'mfp-zoom-in'           => 'Zoom In',
                        'mfp-zoom-out'          => 'Zoom Out'
                    )
                ),
                // Portfolio Link Target
                array(
                    'name'      => 'gs_p_link_tar',
                    'label'     => __( 'Portfolio Link Target', 'gsportfolio' ),
                    'desc'      => __( 'Specify target to load the Links, Default New Tab ', 'gsportfolio' ),
                    'type'      => 'select',
                    'default'   => '_blank',
                    'options'   => array(
                        '_blank'    => 'New Tab',
                        '_self'     => 'Same Window'
                    )
                )
            ),
            'gs_p_advance' => array(
                array(
                    'name'      => 'gs_portf_setting_banner',
                    'label'     => __( '', 'gsportfolio' ),
                    'desc'      => __( '<p class="gsportf_pro">Available at <a href="https://www.gsplugins.com/product/gs-portfolio" target="_blank">PRO</a> version.</p>', 'gsportfolio' ),
                    'row_classes' => 'gspt_banner'
                ),
                // Portfolio Button Text
                array(
                    'name'      => 'gs_p_slug',
                    'label'     => __( 'Portfolio Slug', 'gsportfolio' ),
                    'desc'      => __( 'After updating GS Portfolio slug, Single Portfolio may NOT be found with 404 error. In this scenario go to Settings > Permalinks. It\'ll flush the URL. Clear cache if needed & refresh Single Portfolio page to display.', 'gsportfolio' ),
                    'type'      => 'text',
                    'default'   => 'portfolio-works'
                ),
                // Popup Link
                array(
                    'name'      => 'gsp_popup_link',
                    'label'     => __( 'Popup Link', 'gsportfolio' ),
                    'desc'      => __( 'Show or Hide Popup Link', 'gsportfolio' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                // Single Portfolio Link
                array(
                    'name'      => 'gsp_singlep_link',
                    'label'     => __( 'Single Portfolio Link', 'gsportfolio' ),
                    'desc'      => __( 'Show or Hide Single Portfolio Link', 'gsportfolio' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                // External Portfolio Link
                array(
                    'name'      => 'gsp_extp_link',
                    'label'     => __( 'External Portfolio Link', 'gsportfolio' ),
                    'desc'      => __( 'Show or Hide External Portfolio Link', 'gsportfolio' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
            )   
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class=" gs_portfolio_wrap" style="width: 845px; float: left;">';
        
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
        ?> 
            <div class="gswps-admin-sidebar" style="width: 277px; float: left; margin-top: 62px;">
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Support / Report a bug' ) ?></span></h3>
                    <div class="inside centered">
                        <p>Please feel free to let us know if you have any bugs to report. Your report / suggestion can make the plugin awesome!</p>
                        <p style="margin-bottom: 1px! important;"><a href="https://www.gsplugins.com/contact/" target="_blank" class="button button-primary">Get Support</a></p>
                    </div>
                </div>
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Buy me a coffee' ) ?></span></h3>
                    <div class="inside centered">
                        <p>If you like the plugin, please buy me a coffee to inspire me to develop further.</p>
                        <p style="margin-bottom: 1px! important;"><a href='https://www.paypal.com/donate/?hosted_button_id=K7K8YF4U3SCNQ' class="button button-primary" target="_blank">Donate</a></p>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Join GS Plugins on facebook' ) ?></span></h3>
                    <div class="inside centered">
                        <iframe src="//www.facebook.com/plugins/likebox.php?href=https://www.facebook.com/gsplugins&amp;width&amp;height=258&amp;colorscheme=dark&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false&amp;appId=723137171103956" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:220px;" allowTransparency="true"></iframe>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Follow GS Plugins on twitter' ) ?></span></h3>
                    <div class="inside centered">
                        <a href="https://twitter.com/gsplugins" target="_blank" class="button button-secondary">Follow @gsplugins<span class="dashicons dashicons-twitter" style="position: relative; top: 3px; margin-left: 3px; color: #0fb9da;"></span></a>
                    </div>
                </div>
            </div>
        <?php
    }


    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new GS_portfolio_Settings_Config();