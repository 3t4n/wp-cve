<?php
/**
 (c) copyright:  https://www.ezusy.com
 Author: Ezusy
**/

class ezusy_settings{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_style' ) );
    }


    public function load_admin_style() {
        //wp_enqueue_style( 'ezusy-admin-style', ezusy_URL_ASSETS . 'css/ezusy-admin.css', false, time() );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Ezusy', 
            'Ezusy', 
            'manage_options', 
            'ezusy-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }
    
    public static function get_option( $name ){
		$options = get_option( 'ezusy_settings_reviews' );
		
		if(is_array($options) && isset($options[$name])){
			return $options[$name];
		}else{
			return '';
		}
	}
		
    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'ezusy_settings_reviews' );
        ?>
        <div class="wrap">
            
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'ezusy_option_group' );
                do_settings_sections( 'ezusy-setting-admin' );
                echo '<h4 class="info"><strong style="color:red">Note</strong>: The Ezusy plugin does not support to show images on Quick view function.</h4>';
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'ezusy_option_group', // Option group
            'ezusy_settings_reviews', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Ezusy Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'ezusy-setting-admin' // Page
        );  
   
        foreach ($this->fields_settings() as $field) {
        	add_settings_field(
	            $field['name'], // name
	            $field['title'], // Title 
	            array( $this, $field['name'].'_callback' ), // Callback
	            'ezusy-setting-admin', // Page
	            'setting_section_id' // Section           
	        );  
        }
    }

	public function fields_settings(){
		return array(
			array(
        		'name' => 'list_name_variation',
        		'title' => 'Enter name or slug of the attribute to display image swatches'
        	),
        	array(
        		'name' => 'ez_width_images',
        		'title' => 'Enter the width of images'
        	)
		);
	}

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        $fields_setting = $this->fields_settings();
        
        $fields_setting[] = array(
    		'name' => 'priority_position_display',
    		'title' => 'Priority position'
    	);
    	
    	$fields_setting[] = array(
    		'name' => 'priority_position_display_widget',
    		'title' => 'Priority position'
    	);
    	
    	$fields_setting[] = array(
    		'name' => 'priority_position_display_widget_in_loop',
    		'title' => 'Priority position'
    	);
        
        foreach($fields_setting as $field){
	        if( isset( $input[$field['name']] ) ){
		        if(is_numeric($input[$field['name']])){
			        $new_input[$field['name']] = absint( $input[$field['name']] );
		        }else{
			        $new_input[$field['name']] = sanitize_text_field( $input[$field['name']] );
		        }
	        }
        }
		
		flush_rewrite_rules();
		
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info(){
        print 'Ezusy Settings:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function custom_tab_title_callback(){
	    $trace_fn = debug_backtrace();
		$name = str_replace('_callback', '', $trace_fn[0]["function"] );
    	
    	$this->input_field($name);
    } 
     
    public function position_display_callback(){
    	$trace_fn = debug_backtrace();
		$name = str_replace('_callback', '', $trace_fn[0]["function"] );
    	
    	$this->select_field($name);
    }
    
    public function position_display_widget_callback(){
	    $trace_fn = debug_backtrace();
		$name = str_replace('_callback', '', $trace_fn[0]["function"] );
    	
    	$this->select_field($name);
    }
    
    public function position_display_widget_in_loop_callback(){
    	$trace_fn = debug_backtrace();
		$name = str_replace('_callback', '', $trace_fn[0]["function"] );
    	
    	$this->select_field($name);
    }
    
    public function input_field($name){
	    $val = isset($this->options[$name])? $this->options[$name] : 'Reviews (%total_number%)';
	    
	    echo '<input type="text" name="ezusy_settings_reviews['.$name.'] aria-describedby="custom-tab-title" value="'.$val.'" class="regular-text">';
		echo '<p class="description" id="custom-tab-title">This text will show in reviews the tab on the product page. Use (%total_number%) to display the total number of reviews.</p>';
    }
    
    public function select_field($name){
	    $select = isset($this->options[$name])? $this->options[$name] : 1;
	    $priority_select = isset($this->options['priority_'.$name])?$this->options['priority_'.$name] : 10;

    	echo '<select id="position_display" class="ezusy_settings_reviews" name="ezusy_settings_reviews['.$name.']">';
    	foreach (ezusy_display_position_hook($name) as $key => $data) {
    		echo '<option value="'. $key .'" '. selected($select, $key) .'>'. $data['title'] .'</option>';
    	}
    	echo '</select>';
    	
    	echo '<span class="priority-label">Priority</span><select name="ezusy_settings_reviews[priority_'.$name.']">'; 
    	foreach([10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80] as $priority){
	    	echo '<option value="'. $priority .'" '. selected($priority_select, $priority) .'>'. $priority .'</option>';
    	}
    	echo '</select>';
    	
    	$p_display = 'none';
    	if($select == 10){
	    	$p_display = 'block';
    	}
        echo '<p class="custom_position_display_reviews" style="display:'.$p_display.';">'. $this->__($name) .'</p>';
    }

   
    /** 
     * Get the settings option array and print one of its values
     */
   
    
    public function list_name_variation_callback(){        
        $select = $this->options['list_name_variation'];

    	echo '<textarea id="list_name_variation" class="list_name_variation code" name="ezusy_settings_reviews[list_name_variation]" rows="4" cols="50" placeholder="Color, Couleur">'.$select.'</textarea>';
        echo '<p class="description"><a target="_blank" href="'.admin_url("edit.php?post_type=product&page=product_attributes" ).'">click here</a> to find name or slug of product attribute. We encourage you to use the slug.</p>';
    	
    }
    public function ez_width_images_callback(){        
        $select = $this->options['ez_width_images'];

    	echo '<input id="ez_width_images" class="ez_width_images code" name="ezusy_settings_reviews[ez_width_images]" type="number" placeholder="40" value="'.$select.'"> px';
    	
    }
    
   
}

if( is_admin() ){
   new ezusy_settings();
}
