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

 
if ( !class_exists('GS_Twitter_Feed_Settings_Config' ) ):
class GS_Twitter_Feed_Settings_Config {

    private $settings_api;

    function __construct() {
        $this->settings_api = new GS_Twitter_WeDevs_Settings_API;

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

    // function admin_menu() {

    //     add_options_page( 'GS Twitter Feed Settings', 'GS Twitter Feed Settings', 'manage_options', 'twitter-feed-settings', array($this, 'gstwf_plugin_page') );
    // }

    function admin_menu() {
    
        add_menu_page( 'Twitter Feed Settings', 'Twitter Feed', 'delete_posts', 'twitter-feed-settings', array($this, 'gstwf_plugin_page'), 'dashicons-twitter', 5 ); 
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' 	=> 'gs_twitter_feed_settings',
                'title' => __( 'GS Twitter Feed Settings', 'gstwf' )
            ),
            array(
                'id'    => 'gs_twitter_user_card_settings',
                'title' => __( 'User Card ', 'gstwf' )
            ),
            array(
                'id'    => 'gs_twitter_collection_settings',
                'title' => __( 'Timeline Collection ', 'gstwf' )
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
            'gs_twitter_feed_settings' => array(
                // consumer_key
                array(
                    'name'      => 'gstfw_consumer_key',
                    'label'     => __( 'Twitter Application API Key ', 'gstwf' ),
                    'desc'      => __( 'Please create an app on Twitter through this link: <a href="https://dev.twitter.com/apps" target="_blank">https://dev.twitter.com/apps</a> and get API Key to add.', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => ''
                ),
                   // consumer_secret
                array(
                    'name'      => 'gstfw_consumer_secret',
                    'label'     => __( 'Twitter Application API Secret', 'gstwf' ),
                    'desc'      => __( 'Input Twitter Application API Secret.', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => ''
                ),
                // access_token
                array(
                    'name'      => 'gstfw_access_token',
                    'label'     => __( 'Account Access Token', 'gstwf' ),
                    'desc'      => __( 'Input Account Access Token.', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => ''
                ),
                // token_secret
                array(
                    'name'      => 'gstfw_access_token_secret',
                    'label'     => __( 'Account Access Token Secret ', 'gstwf' ),
                    'desc'      => __( 'Input Account Access Token Secret.', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => ''
                ),
                // user name
                array(
                    'name'      => 'gstfw_user_timeline',
                    'label'     => __( 'Twitter Feed User Name* ', 'gstwf' ),
                    'desc'      => __( 'Enter Twitter Username to fetch feeds. For example : wordpress', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => 'gsplugins'
                ),
                // hash tag
                array(
                    'name'      => 'gstfw_hash_tag',
                    'label'     => __( 'Twitter Hash Tag ', 'gstwf' ),
                    'desc'      => __( 'Enter Twitter Hash Tag to fetch feeds. For example : RichMovies', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => ''
                ),
                // cache 
                array(
                    'name'      => 'gstfw_cache_expire',
                    'label'     => __( 'Cache Period ', 'gstwf' ),
                    'desc'      => __( 'Set time in minutes for Cache Period. Default : 30 Minutes', 'gstwf' ),
                    'type'      => 'text',
                    'default'   => '30'
                ),
                // Number of tweets to display
                array(
                    'name'  => 'gstfw_twettes_count',
                    'label' => __( 'Number of Tweets', 'gstwf' ),
                    'desc'  => __( 'Set number of Tweets to display. Default : 6', 'gstwf' ),
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 100,
                    'default' => 6
                ),
                // theme
                array(
                    'name'  => 'gstfw_theme',
                    'label' => __( 'Style & Theming', 'gstwf' ),
                    'desc'  => __( 'Select preferred Style & Theme', 'gstwf' ),
                    'type'  => 'select',
                    'default'   => 'gstf_theme1',
                    'options'   => array(
                        'gstf_theme1'   => 'Theme 1 (Light)',
                        'gstf_theme2'   => 'Theme 2 (Dark)',                        
                        'gstf_theme3'   => 'Theme 3 : Vertical Slider UP (Pro)',
                        'gstf_theme4'   => 'Theme 4 : Vertical Slider Down (Pro)',
                        'gstf_theme5'   => 'Theme 5 : Carousel : 1 (Pro)',
                        'gstf_theme6'   => 'Theme 6 : Carousel : 2 (Pro)',
                        'gstf_theme7'   => 'Theme 7 : Ticker (Pro)',
                        'gstf_theme8'   => 'Theme 8 : Ticker RTL (Pro)',
                        'gstf_theme9'   => 'Theme 9 : Timeline View (Pro)',
                    )
                ),

                // date formet
                array(
                    'name'  => 'gstfw_date_formet',
                    'label' => __( 'Date Format', 'gstwf' ),
                    'desc'  => __( 'Select Date Formation', 'gstwf' ),
                    'type'  => 'select',
                    'default'   => 'full_date',
                    'options'   => array(
                        'full_date'     => 'Full Date and Time : (March 10, 2001, 5:16 pm)',
                        'date_only'     => 'Date only : (March 10, 2001)',
                        'elapsed_time'  => 'Elapsed Time : (12 hours ago)',  
                    )
                ),
                // Shots Link Target
                array(
                    'name'      => 'gs_twitter_link_tar',
                    'label'     => __( ' Link Target', 'gstwf' ),
                    'desc'      => __( 'Specify target to load the Links, Default New Tab ', 'gstwf' ),
                    'type'      => 'select',
                    'default'   => '_blank',
                    'options'   => array(
                        '_blank'    => 'New Tab',
                        '_self'     => 'Same Window'
                    )
                ),
                //display actions button
                array(
                    'name'      => 'gs_tweet_action_button',
                    'label'     => __( 'Twitter Action Icons ', 'gstwf' ),
                    'desc'      => __( 'Show / Hide Twitter Action Icons ', 'gstwf' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                //display follow button               
                array(
                    'name'      => 'gs_tweet_follow_button',
                    'label'     => __( 'Twitter Follow Button ', 'gstwf' ),
                    'desc'      => __( 'Show / Hide Twitter Follow Button ', 'gstwf' ),
                    'type'      => 'switch',
                    'switch_default' => 'ON'
                ),
                // //Disable Cache
                // array(
                //     'name'      => 'gs_tweet_dis_cache',
                //     'label'     => __( 'Disable Cache ', 'gstwf' ),
                //     'desc'      => __( 'Disable Cache ', 'gstwf' ),
                //     'type'      => 'checkbox',
                //     'default'   => 'off',
                // ),

                // display full length text
                array(
                    'name'      => 'gs_tweet_full_text',
                    'label'     => __( 'Display Full Text ', 'gstwf' ),
                    'desc'      => __( 'By default twitter display max 140 characters, but by enabling this option it will display full text', 'gstwf' ),
                    'type'      => 'switch',
                    'switch_default' => 'off'
                ),

                // Twitter Feed Custom CSS
                array(
                    'name'    => 'gs_twitter_custom_css',
                    'label'   => __( 'Twitter Feed Custom CSS', 'gstwf' ),
                    'desc'    => __( 'You can write your own custom css. Available at PRO version', 'gstwf' ),
                    'type'    => 'textarea'
                )    
            ),

            'gs_twitter_user_card_settings' => array(
                array(
                    'name'      => 'gs_twitter_setting_banner',
                    'label'     => __( '', 'gstwf' ),
                    'desc'      => __( '<p class="gs_tw_pro">Available at <a href="https://www.gsplugins.com/product/wordpress-twitter-feeds" target="_blank">PRO</a> version.</p>', 'gstwf' ),
                    'row_classes' => 'gs_tw_banner'
                ),
                // theme
                array(
                    'name'  => 'gstfw_user_card_theme',
                    'label' => __( 'Style & Theming', 'gstwf' ),
                    'desc'  => __( 'Select preferred Style & Theme', 'gstwf' ),
                    'type'  => 'select',
                    'default'   => 'gstfw_theme1',
                    'options'   => array(
                        'gstf_card_theme1'   => 'Theme 1',
                        'gstf_card_theme2'   => 'Theme 2',
                    )
                ),

                // Twitter  card background color
                array(
                    'name'    => 'gs_twitter_card_backgroundcolor',
                    'label'   => __( 'Twitter Card Background color', 'gstwf' ),
                    'desc'    => __( 'Twitter Card Background color', 'gstwf' ),
                    'type'    => 'color',
                    'default'   => '#fff',
                ) ,  

                // Twitter  card text color
                array(
                    'name'    => 'gs_twitter_card_textcolor',
                    'label'   => __( 'Twitter Card Font color', 'gstwf' ),
                    'desc'    => __( 'Twitter Card Font color', 'gstwf' ),
                    'type'    => 'color',
                    'default'   => '#000',
                ) ,  

            ),

            'gs_twitter_collection_settings' => array(
                array(
                    'name'      => 'gs_twitter_setting_banner',
                    'label'     => __( '', 'gstwf' ),
                    'desc'      => __( '<p class="gs_tw_pro">Available at <a href="https://www.gsplugins.com/product/wordpress-twitter-feeds" target="_blank">PRO</a> version.</p>', 'gstwf' ),
                    'row_classes' => 'gs_tw_banner'
                ),
                // Twitter user name
                array(
                    'name'    => 'gs_twitter_username',
                    'label'   => __( 'Twitter User', 'gstwf' ),
                    'desc'    => __( 'Enter Twitter User Name', 'gstwf' ),
                    'type'    => 'text'
                ) ,   
                // Twitter Collection id
                array(
                    'name'    => 'gs_twitter_collectionid',
                    'label'   => __( 'Twitter Collection ID', 'gstwf' ),
                    'desc'    => __( 'Enter Twitter Collection ID', 'gstwf' ),
                    'type'    => 'text'
                ) ,  
                // theme
                array(
                    'name'  => 'gstfw_collection_theme',
                    'label' => __( 'Style & Theming', 'gstwf' ),
                    'desc'  => __( 'Select Preffered Collection Theme', 'gstwf' ),
                    'type'  => 'select',
                    'default'   => 'grid',
                    'options'   => array(
                        'grid'      => 'Grid',
                        'timeline'  => 'Timeline'  
                    )                    
                ),
                array(
                    'name'  => 'gstfw_collection_theme_color',
                    'label' => __( ' Theming Color', 'gstwf' ),
                    'desc'  => __( 'Select Preffered Collection Theme color', 'gstwf' ),
                    'type'  => 'select',
                    'default'   => 'light',
                    'options'   => array(
                        'light'   => 'Light',
                        'dark'   => 'Dark'  
                    )
                ),
                // width
                array(
                    'name'  => 'gstfw_collection_width',
                    'label' => __( 'Container Width', 'gstwf' ),
                    'desc'  => __( 'Set the maximum width of the embedded Tweet', 'gstwf' ),
                    'type'  => 'text',
                    'default'   => '100%',
                    
                ),
                // width
                array(
                    'name'  => 'gstfw_collection_height',
                    'label' => __( 'Container Height', 'gstwf' ),
                    'desc'  => __( 'Set the maximum height of the embedded Tweet', 'gstwf' ),
                    'type'  => 'text',
                    'default'   => '600',  
                ),
                // Number of tweets to display
                array(
                    'name'  => 'gstfw_collection_count',
                    'label' => __( 'Number of Tweets', 'gstwf' ),
                    'desc'  => __( 'Height parameter has no effect when Tweet limit is set. Range: 1-20', 'gstwf' ),
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 20,
                    'default' => 6
                ),
                // Border Color
                array(
                    'name'  => 'gstfw_collection_border_color',
                    'label' => __( 'Border Color', 'gstwf' ),
                    'desc'  => __( 'Select Collection Border Color', 'gstwf' ),
                    'type'  => 'color',
                    'default'   => '#0073AA',   
                ),
                 // Link Color
                array(
                    'name'  => 'gstfw_collection_link_color',
                    'label' => __( 'Link Color', 'gstwf' ),
                    'desc'  => __( 'Select Collection Link Color', 'gstwf' ),
                    'type'  => 'color',
                    'default'   => '#0073AA',  
                ),
                // theme
                array(
                    'name'  => 'gstfw_collection_chrome',
                    'label' => __( ' Toggle Collection Elements', 'gstwf' ),
                    'desc'  => __( 'Space-separated list of values Ex : noheader nofooter noborders transparent noscrollbar', 'gstwf' ),
                    'type'  => 'text',
                    'default'   => 'undefined',  
                ),
            ),
        );

        return $settings_fields;
    }

    function gstwf_plugin_page__() {
        // settings_errors();
        echo '<div class="wrap gs_team_wrap" style="width: 845px; float: left;">';
        // echo '<div id="post-body-content">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    function gstwf_plugin_page() {
        // settings_errors();
        echo '<div class="wrap gs_twitter_wrap" style="width: 845px; float: left;">';
        // echo '<div id="post-body-content">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';

        ?> 
            <div class="gstwf-admin-sidebar" style="width: 277px; float: left; margin-top: 62px;">
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

$settings = new GS_Twitter_Feed_Settings_Config();