<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
namespace Remove_Footer_Links\Inc\classes;
abstract class Setting{
    /**
     * Holds the values to be used in the fields callbacks
     */
    protected $options;

    /**
     * Start up
     */
    public function __construct(){

        $this->fields();
        $this->loader();
    }

    public function fields(){

        $fields = array(
            'heading' => esc_html__( 'Page Heading', 'remove-footer-links' ),
            'title' => esc_html__( 'Section Title', 'remove-footer-links' ),
            'menu' => esc_html__( 'Menu Name', 'remove-footer-links' ),
            'slug' => 'remove-footer-links',
            'settings' => array(
                array(
                    'name' => 'remove_footer_links',
                    'option' => 'remove_footer_links',
                    'title' => esc_html__( 'Title', 'remove-footer-links' ),
                    'description' => esc_html__( 'Description', 'remove-footer-links' ),
                    'fields' => array(
                        array(
                            'name' => 'setting_field_name',
                            'default' => esc_html__( 'Default Value.', 'remove-footer-links' ),
                            'title' => esc_html__( 'Field Name', 'remove-footer-links' ),
                            'type' => 'text',
                        )
                    )
                )
            )
        );

        $this->fields = $fields;

    }

    public function loader(){
        add_action( 'admin_menu', array( $this, 'page' ) );
        add_action( 'admin_init', array( $this, 'register' ) );
    }

    /**
     * Add options page
     */
    public function page()
    {
        // This page will be under "Settings"
        add_options_page(
            $this->fields['title'],
            $this->fields['menu'],
            'manage_options', 
            $this->fields['slug'],
            array( $this, 'render_html' )
        );
        
    }

    /**
     * Options page callback
     */
    public function render_html(){
        
        $data = $this->fields;
        $heading = isset($data['heading']) ? $data['heading'] : '';
        ?>
        <div class="wrap ecp-wrap">
            <h1><?php echo esc_html($heading); ?></h1>
            <form method="post" action="options.php">
            <?php
                
                $settings = isset($data['settings']) ? $data['settings'] : array();
                if($settings){

                    foreach($settings as $group){

                        $group_name = isset($group['name']) ? $group['name'] : '';
                        $option_name = isset($group['option']) ? $group['option'] : '';
                        $this->options = get_option( $option_name );
                        settings_fields( $group_name );
                        do_settings_sections( $option_name );

                    }

                }

                submit_button();
            ?>
            </form>
        </div>
        <?php

    }

    /**
     * Register and add settings
     */
    public function register(){

        global $remove_footer_links;

        $data = $this->fields;
        $settings = isset($data['settings']) ? $data['settings'] : array();

        if($settings){

            foreach($settings as $group){
                
                $group_name = isset($group['name']) ? $group['name'] : '';
                $option_name = isset($group['option']) ? $group['option'] : '';
                $sections = isset($group['sections']) ? $group['sections'] : array();

                register_setting(
                    $group_name, // Option group
                    $option_name, // Option name
                    array(  // Args
                        'sanitize_callback' => array ( $this, 'sanitize' ) // Sanitize
                    )
                );

                $section_title = isset($group['title']) ? $group['title'] : '';
                $section_description = isset($group['description']) ? $group['description'] : '';
                $remove_footer_links['section_description'] = $section_description;
                add_settings_section(
                    $group_name, // Name
                    $section_title, // Title
                    array( $this, 'description' ), // Callback
                    $group_name, // Page
                );

                $fields = isset($group['fields']) ? $group['fields'] : array();
                if($fields){
            
                    foreach($fields as $field){

                        $field_name = isset($field['name']) ? $field['name'] : '';
                        $field_title = isset($field['title']) ? $field['title'] : '';
                        add_settings_field(
                            $field_name, // ID
                            $field_title, // Title 
                            array( $this, 'field_callback' ), // Callback
                            $group_name, // Page
                            $group_name, // Section          
                            array( $field, $option_name, $group_name ), //args
                        );

                    }

                }

            }

        }
    
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){

        $new_input = array();

        $data = $this->fields;
        $settings = isset($data['settings']) ? $data['settings'] : array();
    
        if($settings){
    
            foreach($settings as $group){
                   
                $group_name = isset($group['name']) ? $group['name'] : '';
                $option_name = isset($group['option']) ? $group['option'] : '';
                $sections = isset($group['sections']) ? $group['sections'] : array(); 
                $fields = isset($group['fields']) ? $group['fields'] : array();
                if($fields){
                
                    foreach($fields as $field){
    
                        $type = isset($field['type']) ? $field['type'] : '';
                        $name = isset($field['name']) ? $field['name'] : '';
                        $value = isset($input[$name]) ? $input[$name] : '';
                        switch($type){
                            case 'number':
                                $new_input[$name]=absint($value);
                                break;
                            case 'toggle':
                                $new_input[$name]=absint($value);
                                break;
                            case 'checkbox':
                                $new_input[$name]=absint($value);
                                break;
                            default:
                                $new_input[$name]=sanitize_text_field($value);
                                break;
                        }
    
                    }
    
                }
    
            }
    
        }

        return $new_input;
        
    }

    /** 
     * Print the Section text
     */
    public function description(){
       
        global $remove_footer_links;
        $description = isset($remove_footer_links['description']) ? $remove_footer_links['description'] : '';
        if($description){
            echo esc_html($description);
        }

    }

    public function field_callback( $args ){

        $field = isset($args[0]) ? $args[0] : array();
        $option_name = isset($args[1]) ? $args[1] : '';
        $group_name = isset($args[1]) ? $args[1] : '';
        
        $type = isset( $field['type']) ? $field['type'] : 'text';
        $name = isset( $field['name']) ? $field['name'] : '';
        $default = isset( $field['default']) ? $field['default'] : '';
        $value = isset($this->options[$name]) ? $this->options[$name] : $default;
        switch($type){
            case 'checkbox': 
                $value = absint($value);
                printf(
                    '<input type="%s" id="%s" name="%s" value="1" %s />',
                    $type, $option_name.'_'.$name, $option_name.'['.$name.']', checked($value, 1, false)
                );
                break;
            case 'toggle': 
                $value = absint($value);
                printf(
                    '<label class="ecp-switch"><input type="checkbox" id="%s" name="%s" value="1" %s /><span class="ecp-slider"></span></div>',
                    $option_name.'_'.$name, $option_name.'['.$name.']', checked($value, 1, false)
                    );
                break;
            default: 
                printf(
                    '<input class="widefat" type="text" id="%s" name="%s" value="%s" />',
                    $option_name.'_'.$name, $option_name.'['.$name.']', $value
                );
                break;
        }

    }

}