<?php

class Options_Handler
{
    /**
     * Constructor
     */
    public function __construct()
    {
        //Register admin scripts
        add_action( 'admin_enqueue_scripts', array( &$this, 'register_plugin_admin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'register_plugin_admin_styles' ) );

        //Create admin menu for settings page
        add_action( 'admin_menu', array( &$this, 'scrollup_options_admin_menu' ) );

        //Set up admin options in settings page
        add_action( 'admin_init', array( &$this, 'scrollup_options_init' ) );

        //Options Page
        include_once plugin_dir_path( __FILE__ ) . '/class-icon-handler.php';
    }

    /**
     * This function registers scripts on the backend
     */
    function register_plugin_admin_scripts()
    {
        $currentScreen = get_current_screen();
        if ( $currentScreen->id === "settings_page_smooth-scroll-up" ) {

            //Add JQuery support
            wp_enqueue_script( 'jquery' );

            //Add media support
            wp_enqueue_media();

            //Add jQuery UI support
            wp_enqueue_script( 'jquery-ui-slider' );
            wp_enqueue_script( 'jquery-ui-dialog' );

            wp_register_script(
                'smooth-scrollup-js', plugins_url( SMTH_SCRL_UP_PLUGIN_DIR . '/js/smooth-scroll-up.js' ), '', '', true
            );
            wp_enqueue_script( 'smooth-scrollup-js' );
        }
    }

    /**
     * This function registers scripts on the backend
     */
    function register_plugin_admin_styles()
    {

        $currentScreen = get_current_screen();
        if ($currentScreen->id === "settings_page_smooth-scroll-up" ) {

            //Add jQuery UI support
            wp_enqueue_style( 'wp-jquery-ui-dialog' );

            wp_register_style(
                'font-awesome', plugins_url( SMTH_SCRL_UP_PLUGIN_DIR . '/css/font-awesome.min.css' )
            );
            wp_enqueue_style( 'font-awesome' );

            wp_register_style(
                'scrollup-admin-css', plugins_url( SMTH_SCRL_UP_PLUGIN_DIR . '/css/scrollup-admin.css' )
            );
            wp_enqueue_style( 'scrollup-admin-css' );
        }
    }

    /**
     * This function adds the menu item in admin menu
     */
    function scrollup_options_admin_menu() {
        add_options_page(
            'Smooth Scroll Up',
            'Smooth Scroll Up',
            'manage_options',
            'smooth-scroll-up',
            array( &$this, 'scrollup_options_page_init' )
        );
    }

    /**
     * This function prints the options page
     */
    function scrollup_options_page_init() {

        $available_tabs = array(
            'basic'          => array(
                'slug'  => 'basic',
                'title' => __( 'Basic', 'smooth-scroll-up' )
            ),
            'display'     => array(
                'slug'  => 'display',
                'title' => __( 'Display', 'smooth-scroll-up' )
            ),
            'advanced'       => array(
                'slug'  => 'advanced',
                'title' => __( 'Advanced', 'smooth-scroll-up' )
            )
        );
        $available_tabs = apply_filters( 'scrollup_filter_settings_tabs', $available_tabs );
        ?>

        <form action='options.php' method='post'>

            <div class="wrap">

                <h1><?php echo __( 'Smooth Scroll Up Options', 'smooth-scroll-up' ); ?></h1>

                <?php
                foreach ( $available_tabs as $current_tab ) {
                    do_action( "scrollup_action_settings_{$current_tab['slug']}_section" );
                    do_action( "scrollup_action_settings_{$current_tab['slug']}_fields" );
                }

                settings_fields( 'scrollup_options_page' );
                do_settings_sections( 'scrollup_options_page' );
                submit_button();

                ?>
            </div>

        </form>

        <?php



    }

    /**
     * This function initializes the options page
     */
    function scrollup_options_init() {
        register_setting( 'scrollup_options_page', 'scrollup_settings' );

        //Options actions
        add_action( 'scrollup_action_settings_basic_section', array( $this, 'scrollup_options_page_sectionBasic' ), 99 );
        add_action( 'scrollup_action_settings_display_section', array( $this, 'scrollup_options_page_sectionDisplay' ), 99 );
        add_action( 'scrollup_action_settings_advanced_section', array( $this, 'scrollup_options_page_sectionAdvanced' ), 99 );
    }

    /**
     * This function prints the basic options page
     */
    function scrollup_basic_options_section_callback() {
        echo __( 'This section contains basic options for Smooth Scroll Up plugin', 'smooth-scroll-up' );
    }

    /**
     * This function prints the display options page
     */
    function scrollup_display_options_section_callback(){
        echo __( 'This section contains display options for Smooth Scroll Up plugin', 'smooth-scroll-up' );
    }

    /**
     * This function prints the advanced options page
     */
    function scrollup_advanced_options_section_callback(){
        echo __( 'This section contains advanced options for Smooth Scroll Up plugin', 'smooth-scroll-up' );
    }

    function scrollup_options_page_sectionBasic() {

        $settings_section = array(
            'scrollupBasicOptionsSection',
            __( 'Basic', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_basic_options_section_callback' ),
            'scrollup_options_page'
        );

        call_user_func_array( 'add_settings_section',$settings_section);

        add_settings_field(
            'scrollup_type',
            __( 'Type', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_type_render' ),
            'scrollup_options_page',
            'scrollupBasicOptionsSection'
        );

        add_settings_field(
            'scrollup_text',
            __( 'Text', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_text_render' ),
            'scrollup_options_page',
            'scrollupBasicOptionsSection'
        );

        add_settings_field(
            'scrollup_custom_icon',
            __( 'Icon', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_icon_render' ),
            'scrollup_options_page',
            'scrollupBasicOptionsSection'
        );

        add_settings_field(
            'scrollup_custom_icon_size',
            __( 'Icon Size', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_iconSize_render' ),
            'scrollup_options_page',
            'scrollupBasicOptionsSection'
        );

        add_settings_field(
            'scrollup_custom_image',
            __( 'Image', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_customImage_render' ),
            'scrollup_options_page',
            'scrollupBasicOptionsSection'
        );

        add_settings_field(
            'scrollup_position',
            __( 'Position', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_position_render' ),
            'scrollup_options_page',
            'scrollupBasicOptionsSection'
        );
    }

    function scrollup_type_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_type'] = ( isset($options['scrollup_type']) ? $options['scrollup_type'] : 'icon' );
        ?>
        <select id='scrollup_type' name='scrollup_settings[scrollup_type]'>
            <option value='image' <?php selected($options['scrollup_type'], 'image' ); ?>><?php _e( 'Image', 'smooth-scroll-up' ); ?></option>
            <option value='icon' <?php selected($options['scrollup_type'], 'icon' ); ?>><?php _e( 'Icon', 'smooth-scroll-up' ); ?></option>
            <option value='link' <?php selected($options['scrollup_type'], 'link' ); ?>><?php _e( 'Text link', 'smooth-scroll-up' ); ?></option>
            <option value='pill' <?php selected($options['scrollup_type'], 'pill' ); ?>><?php _e( 'Pill', 'smooth-scroll-up' ); ?></option>
            <option value='tab' <?php selected($options['scrollup_type'], 'tab' ); ?>><?php _e( 'Tab', 'smooth-scroll-up' ); ?></option>
        </select>

    <?php
    }

    function scrollup_text_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_text'] = ( isset($options['scrollup_text']) ? $options['scrollup_text'] : '' );
        ?>
        <div id='scrollup_custom_text_section'>
        <input type='text' name='scrollup_settings[scrollup_text]' placeholder='Scroll to top' value='<?php echo $options['scrollup_text']; ?>'>
        </div>
        <?php
    }

    function scrollup_customImage_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_custom_image'] = ( isset($options['scrollup_custom_image']) ? $options['scrollup_custom_image'] : plugins_url(SMTH_SCRL_UP_PLUGIN_DIR . '/img/scrollup.png' ));
        ?>
        <div id='scrollup_custom_image_section'>
        <input type='text' class='hidden' id='scrollup_upload_image' name='scrollup_settings[scrollup_custom_image]' value='<?php echo $options['scrollup_custom_image']; ?>'>
        <?php
        ?>
        <div><img src='' id='scrollup_upload_image_preview' /></div>
        <input type='button' class='button scrollup_upload_image_button' name='scrollup_upload_image_button' value='<?php _e( 'Select Image', 'smooth-scroll-up' ); ?>'>
        </div>
    <?php
    }

    function scrollup_icon_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_custom_icon'] = ( isset($options['scrollup_custom_icon']) ? $options['scrollup_custom_icon'] : 'fa-arrow-circle-up' );
        $options['scrollup_custom_icon_size'] = ( isset($options['scrollup_custom_icon_size']) ? $options['scrollup_custom_icon_size'] : 'fa-2x' );
        ?>
        <div class='scrollup_custom_icon_section'>
        <input type='text' class='hidden' id='scrollup_custom_icon' name='scrollup_settings[scrollup_custom_icon]' value='<?php echo $options['scrollup_custom_icon']; ?>'>
        <?php
        ?>
        <div><i id="scrollup_custom_icon_preview" class="fa <?php echo $options['scrollup_custom_icon']; ?> <?php echo $options['scrollup_custom_icon_size']; ?> "></i>
        </div>
        <input type='button' class='button' id='scrollup_custom_icon_button' name='scrollup_custom_icon_button' value='<?php _e( 'Select Icon', 'smooth-scroll-up' ); ?>'>
        </div>

        <div id="scrollup_custom_icon_dialog" class="scrollup-custom-icon-dialog">
            <?php
                $icons = Icons_Handler::get_icons();
                $current = '';
                echo '<ul>';
                foreach ( $icons as $icon ) {
                    printf(
                        '
                        <li class="scrollup-custom-icon-list-item"><a class="scrollup-custom-icon-list-icon" name="%s" href="#"><i class="fa %s %s"></i>&nbsp;&nbsp;%s</a></li>',
                        esc_attr($icon),
                        esc_attr($icon),
                        'fa-lg',
                        esc_attr($icon)
                    );
                }
                echo '</ul>';
            ?>
        </div>

    <?php
    }

    function scrollup_iconSize_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_custom_icon_size'] = ( isset($options['scrollup_custom_icon_size']) ? $options['scrollup_custom_icon_size'] : 'fa-2x' );
        ?>

        <div class='scrollup_custom_icon_section'>
        <select id='scrollup_custom_icon_size' name='scrollup_settings[scrollup_custom_icon_size]'>
            <option value='' <?php selected($options['scrollup_custom_icon_size'], '' ); ?>><?php _e( 'Tiny', 'smooth-scroll-up' ); ?></option>
            <option value='fa-lg' <?php selected($options['scrollup_custom_icon_size'], 'fa-lg' ); ?>><?php _e( 'Small', 'smooth-scroll-up' ); ?></option>
            <option value='fa-2x' <?php selected($options['scrollup_custom_icon_size'], 'fa-2x' ); ?>><?php _e( 'Normal', 'smooth-scroll-up' ); ?></option>
            <option value='fa-3x' <?php selected($options['scrollup_custom_icon_size'], 'fa-3x' ); ?>><?php _e( 'Large', 'smooth-scroll-up' ); ?></option>
            <option value='fa-4x' <?php selected($options['scrollup_custom_icon_size'], 'fa-4x' ); ?>><?php _e( 'Extra Large', 'smooth-scroll-up' ); ?></option>
            <option value='fa-5x' <?php selected($options['scrollup_custom_icon_size'], 'fa-5x' ); ?>><?php _e( 'Huge', 'smooth-scroll-up' ); ?></option>
        </select>
        </div>

    <?php
    }

    function scrollup_position_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_position'] = ( isset($options['scrollup_position']) ? $options['scrollup_position'] : 'right' );
        ?>
        <select name='scrollup_settings[scrollup_position]'>
            <option value='left' <?php selected($options['scrollup_position'], 'left' ); ?>><?php _e( 'Left', 'smooth-scroll-up' ); ?></option>
            <option value='right' <?php selected($options['scrollup_position'], 'right' ); ?>><?php _e( 'Right', 'smooth-scroll-up' ); ?></option>
            <option value='center' <?php selected($options['scrollup_position'], 'center' ); ?>><?php _e( 'Center', 'smooth-scroll-up' ); ?></option>
        </select>
    <?php
    }

    function scrollup_options_page_sectionDisplay() {

        $settings_section = array(
            'scrollup_display_options_section',
            __( 'Display', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_display_options_section_callback' ),
            'scrollup_options_page'
        );

        call_user_func_array( 'add_settings_section',$settings_section);

        add_settings_field(
            'scrollup_show',
            __( 'Display in homepage', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_display_render' ),
            'scrollup_options_page',
            'scrollup_display_options_section'
        );

        add_settings_field(
            'scrollup_specific_ids',
            __( 'Display/hide scroll up element from specific posts or pages', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_specific_ids_render' ),
            'scrollup_options_page',
            'scrollup_display_options_section'
        );

        add_settings_field(
            'scrollup_mobile',
            __( 'Display in mobile devices', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_mobile_render' ),
            'scrollup_options_page',
            'scrollup_display_options_section'
        );

        add_settings_field(
            'scrollup_animation',
            __( 'Display animation', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_animation_render' ),
            'scrollup_options_page',
            'scrollup_display_options_section'
        );

        add_settings_field(
            'scrollup_distance',
            __( 'Distance from top before displaying scroll up element', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_distance_render' ),
            'scrollup_options_page',
            'scrollup_display_options_section'
        );

        add_settings_field(
            'scrollup_speed',
            __( 'Scroll speed to top', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_speed_render' ),
            'scrollup_options_page',
            'scrollup_display_options_section'
        );
    }

    function scrollup_display_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_show'] = ( isset($options['scrollup_show']) ? $options['scrollup_show'] : '0' );
        ?>
        <select name='scrollup_settings[scrollup_show]'>
            <option value='0' <?php selected($options['scrollup_show'], '0' ); ?>><?php _e( 'No', 'smooth-scroll-up' ); ?></option>
            <option value='1' <?php selected($options['scrollup_show'], '1' ); ?>><?php _e( 'Yes', 'smooth-scroll-up' ); ?></option>
        </select>
    <?php
    }


    function scrollup_speed_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_speed'] = ( isset($options['scrollup_speed']) ? $options['scrollup_speed'] : '300' );
        ?>
        <input type='text' name='scrollup_settings[scrollup_speed]' placeholder='300' value='<?php echo $options['scrollup_speed']; ?>'>
        <?php
        echo '<span class="scrollup-help-text">ms</span>';
    }


    function scrollup_mobile_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_mobile'] = ( isset($options['scrollup_mobile']) ? $options['scrollup_mobile'] : '0' );
        ?>
        <select name='scrollup_settings[scrollup_mobile]'>
            <option value='0' <?php selected($options['scrollup_mobile'], '0' ); ?>><?php _e( 'No', 'smooth-scroll-up' ); ?></option>
            <option value='1' <?php selected($options['scrollup_mobile'], '1' ); ?>><?php _e( 'Yes', 'smooth-scroll-up' ); ?></option>
        </select>
    <?php
    }


    function scrollup_distance_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_distance'] = ( isset($options['scrollup_distance']) ? $options['scrollup_distance'] : '' );
        ?>
        <input type='text' name='scrollup_settings[scrollup_distance]' placeholder='300' value='<?php echo $options['scrollup_distance']; ?>'>
        <span class='scrollup-help-text'>px</span>
        <?php
        echo '<span class="scrollup-help-text">';
        echo sprintf('(' . __( 'if the number is negative, this distance will be applied from the bottom of the page', 'smooth-scroll-up' ) . ')' );
        echo '</span>';
    }

    function scrollup_specific_ids_render() {
        $options = get_option( 'scrollup_settings' );
        $options['scrollup_specific_ids_display_hide'] = ( isset($options['scrollup_specific_ids_display_hide']) ? $options['scrollup_specific_ids_display_hide'] : 'hide' );
        $options['scrollup_specific_ids'] = ( isset($options['scrollup_specific_ids']) ? $options['scrollup_specific_ids'] : '' );
        ?>
        <select name='scrollup_settings[scrollup_specific_ids_display_hide]'>
            <option value='hide' <?php selected($options['scrollup_specific_ids_display_hide'], 'hide' ); ?>><?php _e( 'Hide from', 'smooth-scroll-up' ); ?></option>
            <option value='display' <?php selected($options['scrollup_specific_ids_display_hide'], 'display' ); ?>><?php _e( 'Display only in', 'smooth-scroll-up' ); ?></option>
        </select>
        <input type='text' name='scrollup_settings[scrollup_specific_ids]' placeholder='1,2,5' value='<?php echo $options['scrollup_specific_ids']; ?>'>
        <?php
        echo '<span class="scrollup-help-text">';
        echo sprintf(__( 'Specify IDs of posts or pages (seperated by commas) and select to display or hide scroll up element', 'smooth-scroll-up' ));
        echo '</span>';
    }


    function scrollup_animation_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_animation'] = ( isset($options['scrollup_animation']) ? $options['scrollup_animation'] : 'none' );
        ?>
        <select name='scrollup_settings[scrollup_animation]'>
            <option value='none' <?php selected($options['scrollup_animation'], 'none' ); ?>><?php _e( 'None', 'smooth-scroll-up' ); ?></option>
            <option value='fade' <?php selected($options['scrollup_animation'], 'fade' ); ?>><?php _e( 'Fade', 'smooth-scroll-up' ); ?></option>
            <option value='slide' <?php selected($options['scrollup_animation'], 'slide' ); ?>><?php _e( 'Slide', 'smooth-scroll-up' ); ?></option>
        </select>

    <?php
    }

    function scrollup_options_page_sectionAdvanced() {

        $settings_section = array(
            'scrollup_advanced_options_section',
            __( 'Advanced', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_advanced_options_section_callback' ),
            'scrollup_options_page'
        );

        call_user_func_array( 'add_settings_section',$settings_section);

        add_settings_field(
            'scrollup_custom_css',
            __( 'Custom CSS Code', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_customcss_render' ),
            'scrollup_options_page',
            'scrollup_advanced_options_section'
        );

        add_settings_field(
            'scrollup_custom_js',
            __( 'Custom Javascript Code', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_customjs_render' ),
            'scrollup_options_page',
            'scrollup_advanced_options_section'
        );

        add_settings_field(
            'scrollup_attr',
            __( 'Onclick event', 'smooth-scroll-up' ),
            array(&$this, 'scrollup_attr_render' ),
            'scrollup_options_page',
            'scrollup_advanced_options_section'
        );
    }

    function scrollup_customcss_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_custom_css'] = ( isset($options['scrollup_custom_css']) ? $options['scrollup_custom_css'] : '' );
        ?>
        <textarea name='scrollup_settings[scrollup_custom_css]' placeholder='Add your CSS code here' rows="4" cols="50"><?php echo $options['scrollup_custom_css']; ?></textarea>
        <?php
    }

    function scrollup_customjs_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_custom_js'] = ( isset($options['scrollup_custom_js']) ? $options['scrollup_custom_js'] : '' );
        ?>
        <textarea name='scrollup_settings[scrollup_custom_js]' placeholder='Add your JS code here' rows="4" cols="50"><?php echo $options['scrollup_custom_js']; ?></textarea>
        <?php
    }

    function scrollup_attr_render() {

        $options = get_option( 'scrollup_settings' );
        $options['scrollup_attr'] = ( isset($options['scrollup_attr']) ? $options['scrollup_attr'] : '' );
        ?>
        <input type='text' name='scrollup_settings[scrollup_attr]' placeholder='exit()' value='<?php echo $options['scrollup_attr']; ?>'>
        <?php
        echo '<span class="scrollup-help-text">';
        echo sprintf(__( 'example: type %s in order to add an event %s', 'smooth-scroll-up' ), '<code>exit()</code>', '<code>exit()</code>' );
        echo '</span>';
    }

}
