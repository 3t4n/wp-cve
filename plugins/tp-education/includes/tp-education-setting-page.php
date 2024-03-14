<?php
class TP_Education_Setting_Page
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;


    /**
     * Start up
     */
    public function __construct()
    {
        $this->options = get_option( 'tp_education_setting_option' );
        if( $this->options === false ){
            $this->options = array(
                'enable_class_post_type'        => true,
                'enable_course_post_type'       => true,
                'enable_event_post_type'        => true,
                'enable_excursion_post_type'    => true,
                'enable_team_post_type'         => true,
                'enable_testimonial_post_type'  => true,
                'enable_affiliation_post_type'  => true,
            );
            update_option( 'tp_education_setting_option', $this->options, true );       
        }
        add_action( 'admin_menu', array( $this, 'tp_education_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'tp_education_page_init' ) );
    }

    /**
     * Add options page
     */
    public function tp_education_add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            esc_html__( 'Settings Admin', 'tp-education' ), 
            esc_html__( 'TP Education Settings', 'tp-education' ),
            'manage_options', 
            'tp-education-admin', 
            array( $this, 'tp_education_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function tp_education_create_admin_page()
    {
        // Set class property
        $options = get_option( 'tp_education_setting_option' );
        if( ! empty( $options ) ){
            $this->options = $options;
        }
        ?>
        <div class="wrap">
            <h1><?php _e( 'TP Education Settings', 'tp-education' ); ?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'tp_education_option_group' );
                do_settings_sections( 'tp-education-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function tp_education_page_init()
    {        
        register_setting(
            'tp_education_option_group', // Option group
            'tp_education_setting_option', // Option name
            array( $this, 'tp_education_sanitize' ) // Sanitize
        );

        add_settings_section(
            'tp_education_settings_id', // ID
            esc_html__( 'TP Education Settings', 'tp-education' ), // Title
            array( $this, 'tp_education_print_section_info' ), // Callback
            'tp-education-setting-admin' // Page
        );  

        add_settings_field(
            'enable_class_post_type', // ID
            esc_html__( 'Class Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_class_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        );     

        add_settings_field(
            'enable_course_post_type', // ID
            esc_html__( 'Course Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_course_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        );   

        add_settings_field(
            'enable_event_post_type', // ID
            esc_html__( 'Event Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_event_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        );    

        add_settings_field(
            'enable_excursion_post_type', // ID
            esc_html__( 'Excursion Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_excursion_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        ); 

        add_settings_field(
            'enable_team_post_type', // ID
            esc_html__( 'Team Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_team_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        ); 

        add_settings_field(
            'enable_testimonial_post_type', // ID
            esc_html__( 'Testimonial Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_testimonial_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        ); 

        add_settings_field(
            'enable_affiliation_post_type', // ID
            esc_html__( 'Affiliation Post Type Enabled', 'tp-education' ), // Title 
            array( $this, 'enable_affiliation_post_type_callback' ), // Callback
            'tp-education-setting-admin', // Page
            'tp_education_settings_id' // Section           
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function tp_education_sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['enable_class_post_type'] ) )
            $new_input['enable_class_post_type'] = true;

        if( isset( $input['enable_course_post_type'] ) )
            $new_input['enable_course_post_type'] = true;
       
        if( isset( $input['enable_event_post_type'] ) )
            $new_input['enable_event_post_type'] = true;

        if( isset( $input['enable_excursion_post_type'] ) )
            $new_input['enable_excursion_post_type'] = true;

        if( isset( $input['enable_team_post_type'] ) )
            $new_input['enable_team_post_type'] = true;

        if( isset( $input['enable_testimonial_post_type'] ) )
            $new_input['enable_testimonial_post_type'] = true;

        if( isset( $input['enable_affiliation_post_type'] ) )
            $new_input['enable_affiliation_post_type'] = true;

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function tp_education_print_section_info()
    {
        _e( 'Please check the checkbox for the Post Types you need:', 'tp-education' );
    }

    /** 
     * Get the settings option class
     */
    public function enable_class_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_class_post_type" name="tp_education_setting_option[enable_class_post_type]" <?php isset( $this->options['enable_class_post_type'] ) ? checked( $this->options['enable_class_post_type'] ) : ''; ?> />
    <?php       
    }

    /** 
     * Get the settings option course
     */
    public function enable_course_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_course_post_type" name="tp_education_setting_option[enable_course_post_type]" <?php isset( $this->options['enable_course_post_type'] ) ? checked( $this->options['enable_course_post_type'] ) : ''; ?> />
    <?php       
    }

     /** 
     * Get the settings option course
     */
    public function enable_event_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_event_post_type" name="tp_education_setting_option[enable_event_post_type]" <?php isset( $this->options['enable_event_post_type'] ) ? checked( $this->options['enable_event_post_type'] ) : ''; ?> />
    <?php       
    }

    /** 
     * Get the settings option course
     */
    public function enable_excursion_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_excursion_post_type" name="tp_education_setting_option[enable_excursion_post_type]" <?php isset( $this->options['enable_excursion_post_type'] ) ? checked( $this->options['enable_excursion_post_type'] ) : ''; ?> />
    <?php       
    }

    /** 
     * Get the settings option course
     */
    public function enable_team_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_team_post_type" name="tp_education_setting_option[enable_team_post_type]" <?php isset( $this->options['enable_team_post_type'] ) ? checked( $this->options['enable_team_post_type'] ) : ''; ?> />
    <?php       
    }

    /** 
     * Get the settings option course
     */
    public function enable_testimonial_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_testimonial_post_type" name="tp_education_setting_option[enable_testimonial_post_type]" <?php isset( $this->options['enable_testimonial_post_type'] ) ? checked( $this->options['enable_testimonial_post_type'] ) : ''; ?> />
    <?php       
    }

    /** 
     * Get the settings option course
     */
    public function enable_affiliation_post_type_callback()
    {
    ?>
        <input type="checkbox" id="enable_affiliation_post_type" name="tp_education_setting_option[enable_affiliation_post_type]" <?php isset( $this->options['enable_affiliation_post_type'] ) ? checked( $this->options['enable_affiliation_post_type'] ) : ''; ?> />
    <?php       
    }

}

if( is_admin() )
    new TP_Education_Setting_Page();