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

if ( !class_exists('GS_wps_Settings_Config' ) ):
class GS_wps_Settings_Config {

    private $settings_api;

    function __construct() {
        $this->settings_api = new GS_wps_WeDevs_Settings_API;

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
		add_submenu_page( 'gsp-main', 'Woo Product Settings', 'Woo Product Slider', 'delete_posts', 'wooproduct-settings', array($this, 'plugin_page')); 
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' 	=> 'gs_wps_general',
                'title' => __( 'Woo Product Settings', 'gswps' )
            ),
            array(
                'id'    => 'gs_wps_style',
                'title' => __( 'Style Settings', 'gswps' )
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
            'gs_wps_general' => array(
                // Columns
                array(
                    'name'      => 'gs_wps_cols',
                    'label'     => __( 'Columns', 'gswps' ),
                    'desc'      => __( 'Select number of Product columns, Default 4 columns', 'gswps' ),
                    'type'      => 'select',
                    'default'   => '4',
                    'options'   => array(
                        '1'     => 'Single Column',
                        '2'     => '2 Columns',
                        '3'     => '3 Columns',
                        '4'     => '4 Columns',
                        '5'     => '5 Columns',
                        '6'     => '6 Columns'
                    )
                ),
                // Total Products
                array(
                    'name'  => 'gs_wps_products',
                    'label' => __( 'Total Products', 'gswps' ),
                    'desc'  => __( 'Select Total number of products in the slider. Default 10', 'gswps' ),
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 100,
                    'default' => 10
                ),
                 //Autoplay
                array(
                    'name'      => 'gs_wps_autoplay',
                    'label'     => __( 'Autoplay', 'gswps' ),
                    'desc'      => __( 'Enable / Disable Autoplay slider. Default On', 'gswps' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                //Stop on Hover
                array(
                    'name'      => 'gs_wps_stp_hover',
                    'label'     => __( 'Stop on Hover', 'gswps' ),
                    'desc'      => __( 'Enable / Disable, Stop on Hover slider. Default On', 'gswps' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                // Inifnity loop
                array(
                    'name'      => 'gs_wps_inf_loop',
                    'label'     => __( 'Infinity Loop', 'gswps' ),
                    'desc'      => __( 'Enable / Disable Infinity loop. Duplicates last & first Product to get loop illusion. Default On', 'gswps' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                 // Margin
                array(
                    'name'  => 'gs_wps_margin',
                    'label' => __( 'Margin', 'gswps' ),
                    'desc'  => __( 'Select margin-right(px) for each Product. Default 4px', 'gswps' ),
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 50,
                    'default' => 4
                ),
                  // Autoplay Speed
                array(
                    'name' => 'gs_wps_autop_speed',
                    'label' => __( 'Autoplay Speed', 'gswps' ),
                    'desc' => __( 'Set Autoplay Speed in Millisecond. Default 1000', 'gswps' ),
                    'type' => 'range',
                    'sanitize_callback' => 'intval',
                    'range_min' => 500,
                    'range_max' => 5000,
                    'range_step' => 100,
                    'default' => 1000
                ), 
                // Autoplay Timeout
                array(
                    'name' => 'gs_wps_autop_tmout',
                    'label' => __( 'Autoplay Timeout', 'gswps' ),
                    'desc' => __( 'Set Autoplay interval timeout in Millisecond. Default 2500', 'gswps' ),
                    'type' => 'range',
                    'sanitize_callback' => 'intval',
                    'range_min' => 500,
                    'range_max' => 10000,
                    'range_step' => 100,
                    'default' => 2500
                ), 

                // Navigation speed
                array(
                    'name' => 'gs_wps_nav_spd',
                    'label' => __( 'Navigation speed', 'gswps' ),
                    'desc' => __( 'Set Navigation speed in Millisecond. Default 1000', 'gswps' ),
                    'type' => 'range',
                    'sanitize_callback' => 'intval',
                    'range_min' => 500,
                    'range_max' => 5000,
                    'range_step' => 100,
                    'default' => 1000
                ),
                // Navigation
                array(
                    'name'      => 'gs_wps_nav_nxt',
                    'label'     => __( 'Navigation', 'gswps' ),
                    'desc'      => __( 'Enable / Disable Navigation (next / prev). Default On' , 'gswps' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                // Dots navigation
                array(
                    'name'      => 'gs_wps_dots_nav',
                    'label'     => __( 'Dots navigation', 'gswps' ),
                    'desc'      => __( 'Enable / Disable Dots Navigation. Default On', 'gswps' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                // DotEach
                array(
                    'name'      => 'gs_wps_dot_each',
                    'label'     => __( 'Dot for Each', 'gswps' ),
                    'desc'      => __( 'Enable / Disable Dots for each Product. Default On', 'gswps' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                )
            ),
            'gs_wps_style'   => array(
                // Title char limit
                array(
                    'name'  => 'gs_wps_prod_tit',
                    'label' => __( 'Product Title Control', 'gswps' ),
                    'desc'  => __( 'Define maximum number of characters in Product title. Default 15', 'gswps' ),
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 50,
                    'default' => 15
                ),
                // Title Color
                array(
                    'name'    => 'gs_wps_title',
                    'label'   => __( 'Product Name Color', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Product name</b>. Default #fff', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#fff'
                ),
                // Button Background
                array(
                    'name'    => 'gs_wps_btn',
                    'label'   => __( 'Button Background', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Add to Cart</b> button. Default #ed4e6e', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#ed4e6e'
                ),
                // Button Background Hover
                array(
                    'name'    => 'gs_wps_btn_hvr',
                    'label'   => __( 'Button Background Hover', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Add to Cart</b> button hover. Default #ed90a1', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#ed90a1'
                ),
                // Product Price Color
                array(
                    'name'    => 'gs_wps_prod_price',
                    'label'   => __( 'Product Price Color', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Product Price</b>. Default #fff', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#fff'
                ),
                // Nav Background
                array(
                    'name'    => 'gs_wps_nv_bg',
                    'label'   => __( 'Nav Background', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Nav Background</b>. Default #3783a7', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#3783a7'
                ),
                // Dot Nav active
                array(
                    'name'    => 'gs_wps_nv_hv',
                    'label'   => __( 'Nav on Hover', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Nav on Hover</b>. Default #0fb9da', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#0fb9da'
                ),
                // Dot Nav Background
                array(
                    'name'    => 'gs_wps_dot_nv_bg',
                    'label'   => __( 'Dot Nav Background', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Dot Nav Background</b>. Default #3783a7', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#3783a7'
                ),
                // Dot Nav active
                array(
                    'name'    => 'gs_wps_dot_nv_ac',
                    'label'   => __( 'Dot Nav Active', 'gswps' ),
                    'desc'    => __( 'Select color for <b>Dot Nav Active</b>. Default #0fb9da', 'gswps' ),
                    'type'    => 'color',
                    'default' => '#0fb9da'
                ),
                // Theme
                array(
                    'name'      => 'gs_wps_theme',
                    'label'     => __( 'Theme', 'gswps' ),
                    'desc'      => __( 'Select preferred theme for hover effect', 'gswps' ),
                    'type'      => 'select',
                    'default'   => 'gs-effect-1',
                    'options'   => array(
                        'gs-effect-1'   => 'Effect (Lite 1)',
                        'gs-effect-2'   => 'Effect (Lite 2)',
                        'gs-effect-3'   => 'Effect (Lite 3)',
                        'gs-effect-4'   => 'Effect (Lite 4)',
                        'gs-effect-5'   => 'Effect (Lite 5)',
                        'gs-effect-6'   => 'Theme 6 (Vertical)',
                        'gs-effect-7'   => 'Theme 7 (Zoom)',
                        'gs-effect-8'   => 'Theme 8 (Expand)',
                        'gs-effect-9'   => 'Theme 9 (Pair 1)',
                        'gs-effect-10'  => 'Theme 10 (Pair 2)'
                    )
                )



            )
        );

        return $settings_fields;
    }



    function plugin_page() {
        settings_errors();
        echo '<div class=" gs_wps_wrap" style="width: 845px; float: left;">';
        // echo '<div id="post-body-content">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';

        ?> 
            <div class="gswps-admin-sidebar" style="width: 277px; float: left; margin-top: 62px;">
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Support / Report a bug' ) ?></span></h3>
                    <div class="inside centered">
                        <p>Please feel free to let us know if you have any bugs to report. Your report / suggestion can make the plugin awesome!</p>
                        <p><a href="https://www.gsplugins.com/contact/" target="_blank" class="button button-primary">Get Support</a></p>
                    </div>
                </div>
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Buy me a coffee' ) ?></span></h3>
                    <div class="inside centered">
                        <p>If you like the plugin, please buy me a coffee to inspire me to develop further.</p>
                        <p><a href='https://www.paypal.com/donate/?hosted_button_id=K7K8YF4U3SCNQ' class="button button-primary" target="_blank">Donate</a></p>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><span><?php _e( 'Subscribe to NewsLetter' ) ?></span></h3>
                    <div class="inside centered">
                        <p>Sign up today & be the first to get notified on new plugin updates. Your information will never be shared with any third party.</p>
                            <!-- Begin MailChimp Signup Form -->
                        
                        <style type="text/css">
                            #mc_embed_signup{background:#fff; clear:left; font:13px "Open Sans",sans-serif; }
                            /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                               We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                        </style>
                        <div id="mc_embed_signup">
                        <form action="//gsamdani.us11.list-manage.com/subscribe/post?u=92f99db71044540329de15732&amp;id=2600f1ae0f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate style="padding: 0;">
                            <div id="mc_embed_signup_scroll">
                            
                            <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Enter your Email address" required style="width: 100%; border:1px solid #E2E1E1; text-align: center;">
                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                            <div style="position: absolute; left: -5000px;"><input type="text" name="b_92f99db71044540329de15732_2600f1ae0f" tabindex="-1" value=""></div>
                            <div class="clear" style="text-align: center; display: block;">
                                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button-primary" style="display: inline; margin: 0; background: #00a0d2; font-size: 13px;">
                            </div>
                            </div>
                        </form>
                        </div>
                        <!--End mc_embed_signup-->
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

$settings = new GS_wps_Settings_Config();