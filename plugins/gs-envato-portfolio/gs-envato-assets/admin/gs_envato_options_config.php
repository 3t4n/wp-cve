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

 
if ( !class_exists('GS_envato_Settings_Config' ) ):
class GS_envato_Settings_Config {

    private $settings_api;

    function __construct() {
        $this->settings_api = new GS_envato_WeDevs_Settings_API;

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

       // add_submenu_page( 'gsp-main', 'Envato Settings', 'Envato Settings', 'delete_posts', 'envato-settings', array($this, 'gsenvato_plugin_page')); 
        add_menu_page( 'Envato Settings', 'Envato Settings', 'delete_posts', 'envato-settings', array($this, 'gsenvato_plugin_page'),'', 5); 
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' 	=> 'gs_envato_settings',
                'title' => __( 'Envato General Settings', 'gs-envato' )
            ),
              array(
                'id'    => 'gs_envato_style_settings',
                'title' => __( 'Style Settings', 'gs-envato' )
            ),
            array(
                'id'    => 'gs_envato_author_settings',
                'title' => __( 'Advance Settings', 'gs-envato' )
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
            'gs_envato_settings' => array(
                // User
                array(
                    'name'      => 'gs_envato_user',
                    'label'     => __( 'User', 'gs-envato' ),
                    'desc'      => __( 'Enter Envato Author  name', 'gs-envato' ),
                    'type'      => 'text',
                    'default'   => 'themeum'
                ),

                // theme
                array(
                    'name'  => 'gs_envato_theme',
                    'label' => __( 'Style & Theming', 'gs-envato' ),
                    'desc'  => __( 'Select preferred Style & Theme', 'gs-envato' ),
                    'type'  => 'select',
                    'default'   => 'gs_envato_theme1',
                    'options'   => array(
                        'gs_envato_theme1'   => 'Theme 01 : Grid (Lite)',
                        'gs_envato_theme2'   => 'Theme 02 : Grid Linked (PRO)',
                        'gs_envato_theme3'   => 'Theme 03 : Grid Hover (PRO)',
                        'gs_envato_theme4'   => 'Theme 04 : Horizontal – Square Right Info (PRO)',
                        'gs_envato_theme5'   => 'Theme 05 : Horizontal – Square Left Info (PRO)',
                        'gs_envato_theme6'   => 'Theme 06 : Gray (PRO)',
                        'gs_envato_theme7'   => 'Theme 07 : Popup (PRO)',
                        'gs_envato_theme8'   => 'Theme 08 : Slider (PRO)',
                        'gs_envato_theme9'   => 'Theme 09 : Rating Info (PRO)',
                        'gs_envato_theme10'  => 'Theme 10 : Gallery (PRO)'
                    )
                ),

                // Columns
                array(
                    'name'      => 'gs_envato_cols',
                    'label'     => __( 'Columns', 'gs-envato' ),
                    'desc'      => __( 'Select number of Columns', 'gs-envato' ),
                    'type'      => 'select',
                    'default'   => '4',
                    'options'   => array(
                        '6'    => '2 Columns',
                        '4'      => '3 Columns',
                        '3'      => '4 Columns',
                    )
                ),

                array(
                    'name'      => 'gs_referral_user',
                    'label'     => __( 'Referral user', 'gs-envato' ),
                    'desc'      => __( 'Referral user', 'gs-envato' ),
                    'type'      => 'text'   
                ),
                // Number of items to display
                array(
                    'name'  => 'gs_envato_items',
                    'label' => __( 'Number of items to display', 'gs-envato' ),
                    'desc'  => __( 'Set number of items to display. Default 10', 'gs-envato' ),
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 50,
                    'default' => 10
                ),
                // Market name
                array(
                    'name'  => 'gs_envato_market',
                    'label' => __( 'Select Envato Market', 'gs-envato' ),
                    'desc'  => __( 'Select Envato Market', 'gs-envato' ),
                    'type'  => 'select',
                    'default'   => 'themeforest',
                    'options'   => array(
                        'themeforest'   => 'Themeforest',
                        'codecanyon'   => 'Codecanyon'
                    )
                ),
                // orderBy
                array(
                    'name'  => 'gs_orderby',
                    'label' => __( 'OrderBy', 'gs-envato' ),
                    'desc'  => __( 'OrderBy Items. Default : Newest', 'gs-envato' ),
                    'type'  => 'select',
                    'default'   => 'newest',
                    'options'   => array(
                        'price'    => 'Price',
                        'newest'   => 'Newest',
                        'number_of_sell'   => 'Number of Sell',
                        'rating'   => 'Rating',
                    )
                ),
                // orderBy
                array(
                    'name'  => 'gs_sorting',
                    'label' => __( 'Sort', 'gs-envato' ),
                    'desc'  => __( 'Sort', 'gs-envato' ),
                    'type'  => 'select',
                    'default'   => 'ascending',
                    'options'   => array(
                        'descending'   => 'Descending',    
                        'ascending'   => 'Ascending'
                    )
                ),
                // Items Link Target
                array(
                    'name'      => 'gs_envato_link_tar',
                    'label'     => __( 'Items Link Target', 'gs-envato' ),
                    'desc'      => __( 'Specify target to load the Links, Default New Tab ', 'gs-envato' ),
                    'type'      => 'select',
                    'default'   => '_blank',
                    'options'   => array(
                        '_blank'    => 'New Tab',
                        '_self'     => 'Same Window'
                    )
                )   
            ),
            'gs_envato_style_settings' => array(
                array(
                    'name'      => 'gs_envato_setting_banner',
                    'label'     => __( '', 'gs-envato' ),
                    'desc'      => __( '<p class="gsenvato_pro">Available at <a href="https://www.gsplugins.com/product/wordpress-envato-plugin/" target="_blank">PRO</a> version.</p>', 'gs-envato' )
                ),
                // Font Size
                array(
                    'name'      => 'gs_envato_fz',
                    'label'     => __( 'Font Size', 'gs-envato' ),
                    'desc'      => __( 'Set Font Size for <b>Envato Item Name</b>', 'gs-envato' ),
                    'type'      => 'number',
                    'default'   => '16',
                    'options'   => array(
                        'min'   => 1,
                        'max'   => 30,
                        'default' => 16
                    )
                ),
                // Font weight
                array(
                    'name'      => 'gs_envato_fntw',
                    'label'     => __( 'Font Weight', 'gs-envato' ),
                    'desc'      => __( 'Select Font Weight for <b>Item Name</b>', 'gs-envato' ),
                    'type'      => 'select',
                    'default'   => '300',
                    'options'   => array(
                        '300'    => '300',
                        '500'    => '400',
                        '500'    => '500',
                        'normal'    => 'Normal',
                        'bold'      => 'Bold',
                        'lighter'   => 'Lighter'
                    )
                ),

                // Font style
                array(
                    'name'      => 'gs_envato_fnstyl',
                    'label'     => __( 'Font Style', 'gs-envato' ),
                    'desc'      => __( 'Select Font Style for <b>Item Name</b>', 'gs-envato' ),
                    'type'      => 'select',
                    'default'   => 'normal',
                    'options'   => array(
                        'normal'    => 'Normal',
                        'italic'      => 'Italic'
                    )
                ),

                // Font Color of item Name
                array(
                    'name'    => 'gs_envato_name_color',
                    'label'   => __( 'Font Color', 'gs-envato' ),
                    'desc'    => __( 'Select color for <b>Item Name</b>.', 'gs-envato' ),
                    'type'    => 'color',
                    'default' => '#141412'
                ),
                // envato Custom CSS
                array(
                    'name'    => 'gs_envato_custom_css',
                    'label'   => __( 'Envato Custom CSS', 'gs-envato' ),
                    'desc'    => __( 'You can write your own custom css', 'gs-envato' ),
                    'type'    => 'textarea'
                ) 
            ),
            'gs_envato_author_settings' => array(
                // array(
                //     'name'    => 'gs_envato_author_country',
                //     'label'   => __( 'Envato Author Country', 'gs-envato' ),
                //     'desc'    => __( 'Show or Hide Envato Author Country', 'gs-envato' ),
                //     'type'      => 'switch',
                //     'switch_default' => 'ON'
                // ),
                array(
                    'name'      => 'gs_envato_setting_banner',
                    'label'     => __( '', 'gs-envato' ),
                    'desc'      => __( '<p class="gsenvato_pro">Available at <a href="https://www.gsplugins.com/product/wordpress-envato-plugin/" target="_blank">PRO</a> version.</p>', 'gs-envato' )
                ),
                array(
                    'name'    => 'gs_envato_author_sales',
                    'label'   => __( 'Total  Sales', 'gs-envato' ),
                    'desc'    => __( 'Show or Hide Total  Sales', 'gs-envato' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ) ,

                array(
                    'name'    => 'gs_envato_author_followers',
                    'label'   => __( 'Profile Followers', 'gs-envato' ),
                    'desc'    => __( 'Show or Hide Followers Number', 'gs-envato' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ) ,
                array(
                    'name'    => 'gs_envato_author_market_item',
                    'label'   => __( 'MarketPlace Item With Link', 'gs-envato' ),
                    'desc'    => __( 'Show or Hide links of Marketplace', 'gs-envato' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                )   
            )
        );
        return $settings_fields;
    }

    function plugin_page() {
        // settings_errors();
        echo '<div class="wrap gs_team_wrap" style="width: 845px; float: left;">';
        // echo '<div id="post-body-content">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

        function gsenvato_plugin_page() {
        settings_errors();
        echo '<div class=" gs_envt_wrap" style="width: 845px; float: left;">';
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

$settings = new GS_envato_Settings_Config();