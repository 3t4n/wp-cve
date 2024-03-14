<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Flexslider_Settings{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $data;
    private $option_key = 'wp_flexslider';
    private $slug = 'wp-flexslider';

    /**
     * Start up
     */
    public function __construct()
    {
        
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

        add_filter( 'plugin_action_links_' . WP_Flexslider::plugin_basename(), array( $this, 'add_action_links' ) );
    }

    /**
     * Setting url
     */
    public function add_action_links ( $links ) {

        $mylinks = array(
            wp_sprintf( '<a href="%s">%s</a>', admin_url( "options-general.php?page={$this->slug}" ), __('Settings', 'wp-flexslider') )
        );

        return array_merge( $links, $mylinks );
    }
    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            __('WP Flexslider', 'wp-flexslider'), 
            __('WP Flexslider', 'wp-flexslider'),
            'manage_options', 
            $this->slug, 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        $defaults = apply_filters( 'wp_flexslider_default_options', array(
            'animation' => 'slide',
            'autoplay'  => '', 
            'loop'      => '',
            'animation_speed'   => 600,
            'slideshow_speed'   => 7000,
            'direction_nav'     => 'true',
            'control_nav'       => 'true',
            'smooth_height'     => '',
            'set_default'       => '',
            'force_display'     => ''

        ) );
        // Set class property
        $this->data = wp_parse_args( get_option( $this->option_key, $defaults ), $defaults );
        ?>
        <div class="wrap">
            <h2><?php echo esc_html__( 'WP Flexslider', 'wp-flexslider' );?></h2>

            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( $this->option_key . '_settings' );   
                do_settings_sections( $this->slug );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {

        register_setting(
            "{$this->option_key}_settings", // Option group
            $this->option_key, // Option name
            array( $this, '_sanitize' ) // Sanitize
        );

        // General Settings
        add_settings_section(
            'general_settings_section', // ID
            esc_html__( 'General Settings', 'wp-flexslider' ), // Title
            '__return_empty_string', // Callback
            $this->slug // Page
        );     

        add_settings_field(
            'animation', 
            esc_html__( 'Animation', 'wp-flexslider' ), // Title
            array( $this, '_field_animation' ), 
            $this->slug, 
            'general_settings_section'
        );  

        // Autoplay
        add_settings_field(
            'autoplay', 
            esc_html__( 'Autoplay', 'wp-flexslider' ), // Title
            array( $this, '_field_autoplay' ), 
            $this->slug, 
            'general_settings_section'
        );

        // Loop
        add_settings_field(
            'loop', 
            esc_html__( 'Loop', 'wp-flexslider' ), // Title
            array( $this, '_field_loop' ), 
            $this->slug, 
            'general_settings_section'
        );

        // ANimation speed
        add_settings_field(
            'animation_speed', 
            esc_html__( 'Animation Speed', 'wp-flexslider' ), // Title
            array( $this, '_field_animation_speed' ), 
            $this->slug, 
            'general_settings_section'
        );

        // Slideshow speed
        add_settings_field(
            'slideshow_speed', 
            esc_html__( 'Slideshow Speed', 'wp-flexslider' ), // Title
            array( $this, '_field_slideshow_speed' ), 
            $this->slug, 
            'general_settings_section'
        );

        // Direction Nav
        add_settings_field(
            'direction_nav', 
            esc_html__( 'Direction Nav', 'wp-flexslider' ), // Title
            array( $this, '_field_direction_nav' ), 
            $this->slug, 
            'general_settings_section'
        );

        // Control Nav
        add_settings_field(
            'control_nav', 
            esc_html__( 'Control Nav', 'wp-flexslider' ), // Title
            array( $this, '_field_control_nav' ), 
            $this->slug, 
            'general_settings_section'
        );

        // Smooth height
        add_settings_field(
            'smooth_height', 
            esc_html__( 'Smooth height', 'wp-flexslider' ), // Title
            array( $this, '_field_smooth_height' ), 
            $this->slug, 
            'general_settings_section'
        );
        // Default
        add_settings_field(
            'set_default', 
            esc_html__( 'Flexslider as Default gallery', 'wp-flexslider' ), // Title
            array( $this, '_field_set_default' ), 
            $this->slug, 
            'general_settings_section'
        );
        // Force Display
        add_settings_field(
            'set_default', 
            esc_html__( 'Force Display', 'wp-flexslider' ), // Title
            array( $this, '_field_force_display' ), 
            $this->slug, 
            'general_settings_section'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function _sanitize( $input ){

        $new_input = array();
        if( isset( $input['support_posttypes'] ) )
            $new_input['support_posttypes'] = $input['support_posttypes'];

        if( isset( $input['template'] ) )
            $new_input['template'] = sanitize_text_field( $input['template'] );

        if( isset( $input['animation'] ) )
            $new_input['animation'] = sanitize_text_field( $input['animation'] );

        if( isset( $input['autoplay'] ) )
            $new_input['autoplay'] = sanitize_text_field( $input['autoplay'] );

        if( isset( $input['loop'] ) )
            $new_input['loop'] = sanitize_text_field( $input['loop'] );

        if( isset( $input['animation_speed'] ) )
            $new_input['animation_speed'] = intval( $input['animation_speed'] );

        if( isset( $input['slideshow_speed'] ) )
            $new_input['slideshow_speed'] = intval( $input['slideshow_speed'] );

        if( isset( $input['direction_nav'] ) )
            $new_input['direction_nav'] = sanitize_text_field( $input['direction_nav'] );

        if( isset( $input['control_nav'] ) )
            $new_input['control_nav'] = sanitize_text_field( $input['control_nav'] );

        if( isset( $input['smooth_height'] ) )
            $new_input['smooth_height'] = sanitize_text_field( $input['smooth_height'] );

        if( isset( $input['set_default'] ) )
            $new_input['set_default'] = sanitize_text_field( $input['set_default'] );

        if( isset( $input['force_display'] ) )
            $new_input['force_display'] = sanitize_text_field( $input['force_display'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info(){
        
    }
    /** 
     * Field animation
     */
    public function _field_animation(){

        $options = array(
            'slide' => esc_html__( 'Slide', 'wp-flexslider' ),
            'fade' => esc_html__( 'Fade', 'wp-flexslider' )
        );
        ?>
        <select name="<?php echo esc_attr( $this->option_key . '[animation]' );?>" id="animation">
            
            <?php
            foreach ($options as $key => $value) {
                printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $this->data['animation'], $key , false ), $value );    
            }
            ?>
        </select>
        <?php

        printf(
            '<p class="description">%s</p>',
            esc_html__( 'Select your default animation type, "fade" or "slide"', 'wp-flexslider' )
        );
        
    }


    /** 
     * loop
     */
    public function _field_loop() {
        
        ?>
        <label for="">
            <input type="checkbox" name="<?php echo esc_attr( $this->option_key . '[loop]' );?>" value="on" <?php checked( 'on', $this->data['loop'], true );?>/>
            <?php _e( 'Should the animation loop?', 'wp-flexslider' );?>    
        </label>
        <?php
    }
    /** 
     * autoplay
     */
    public function _field_autoplay() {
        
        ?>
        <label for="">
            <input type="checkbox" name="<?php echo esc_attr( $this->option_key . '[autoplay]' );?>" value="on" <?php checked( 'on', $this->data['autoplay'], true );?>/>
            <?php _e( 'Animate slider automatically', 'wp-flexslider' );?>    
        </label>
        <?php
    }

    /** 
     * Animation speed
     */
    public function _field_animation_speed() {

        printf(
            '<input type="number" step="100" id="animation_speed" name="' . $this->option_key . '[animation_speed]" value="%s" />',
            isset( $this->data['animation_speed'] ) ? esc_attr( $this->data['animation_speed']) : ''
        );

        printf(
            '<p class="description">%s</p>',
            esc_html__( 'Set the speed of animations, in milliseconds. Default 600 Miliseconds', 'wp-flexslider' )
        );
    }
    /** 
     * Slideshow speed
     */
    public function _field_slideshow_speed() {

        printf(
            '<input type="number" step="1000" id="slideshow_speed" name="' . $this->option_key . '[slideshow_speed]" value="%s" />',
            isset( $this->data['slideshow_speed'] ) ? esc_attr( $this->data['slideshow_speed']) : ''
        );

        printf(
            '<p class="description">%s</p>',
            esc_html__( 'Set the speed of the slideshow cycling, in milliseconds. Default 7000 Miliseconds', 'wp-flexslider' )
        );
    }
    /** 
     * Direction nav
     */
    public function _field_direction_nav() {

        $options = array(
            'true' => esc_html__( 'On', 'wp-flexslider' ),
            'false' => esc_html__( 'Off', 'wp-flexslider' )
        );
        ?>
        <select name="<?php echo esc_attr( $this->option_key . '[direction_nav]' );?>" id="direction_nav">
            
            <?php
            foreach ($options as $key => $value) {
                printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $this->data['direction_nav'], $key , false ), $value );    
            }
            ?>
        </select>
        <?php

        printf(
            '<p class="description">%s</p>',
            esc_html__( 'Create navigation for previous/next navigation?', 'wp-flexslider' )
        );
    }
    /** 
     * Direction nav
     */
    public function _field_control_nav() {
        if( !isset( $this->data['control_nav'] ) )
            $this->data['control_nav'] = 'true';

        $options = array(
            'true' => esc_html__( 'On', 'wp-flexslider' ),
            'false' => esc_html__( 'Off', 'wp-flexslider' )
        );
        ?>
        <select name="<?php echo esc_attr( $this->option_key . '[control_nav]' );?>" id="control_nav">
            
            <?php
            foreach ($options as $key => $value) {
                printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $this->data['control_nav'], $key , false ), $value );    
            }
            ?>
        </select>
        <?php

        printf(
            '<p class="description">%s</p>',
            esc_html__( 'Create navigation for paging control of each slide?', 'wp-flexslider' )
        );
    }
    public function _field_smooth_height() {
        
        ?>
        <label for="">
            <input type="checkbox" name="<?php echo esc_attr( $this->option_key . '[smooth_height]' );?>" value="on" <?php checked( 'on', $this->data['smooth_height'], true );?>/>
            <?php _e( 'Allow height of the slider to animate smoothly in horizontal mode.', 'wp-flexslider' );?>    
        </label>
        <?php
    }
    public function _field_set_default() {
        
        ?>
        <label for="">
            <input type="checkbox" name="<?php echo esc_attr( $this->option_key . '[set_default]' );?>" value="on" <?php checked( 'on', $this->data['set_default'], true );?>/>
            <?php _e( 'Set Flexslider as default type when creating new gallery', 'wp-flexslider' );?>    
        </label>
        <?php
    }
    public function _field_force_display() {
        
        ?>
        <label for="">
            <input type="checkbox" name="<?php echo esc_attr( $this->option_key . '[force_display]' );?>" value="on" <?php checked( 'on', $this->data['force_display'], true );?>/>
            <?php _e( 'Force display flexslider for all default galleries - No Conflict with Jetpack', 'wp-flexslider' );?>    
        </label>
        <?php
    }


}
if( is_admin() )
    $wp_flexslider_settings = new WP_Flexslider_Settings();