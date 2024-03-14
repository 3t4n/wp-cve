<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpglob.com/
 * @since      1.0.0
 *
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/public
 * @author     WP Glob <info@wpglob.com>
 */
class Auto_Scroll_For_Reading_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	private $name_prefix = 'wpg_';
	private $name_prefix_front_page = 'wpg-';
	private $setting_options = array();

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Scroll_For_Reading_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Scroll_For_Reading_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/auto-scroll-for-reading-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Scroll_For_Reading_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Scroll_For_Reading_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/auto-scroll-for-reading-public.js', array( 'jquery' ), $this->version, false );

        wp_localize_script( $this->plugin_name, 'WPGAutoscrollObj', array(
            'playIcon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M8 5v14l11-7z"/></svg>',
            'pauseIcon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>',
            'stopIcon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 6h12v12H6z"/></svg>',
            'fastForwardIcon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M4 18l8.5-6L4 6v12zm9-12v12l8.5-6L13 6z"/></svg>',
            'boltIcon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M11 21h-1l1-7H7.5c-.58 0-.57-.32-.38-.66.19-.34.05-.08.07-.12C8.48 10.94 10.42 7.54 13 3h1l-1 7h3.5c.49 0 .56.33.47.51l-.07.15C12.96 17.55 11 21 11 21z"/></svg>',
            'flashOnIcon' => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M7 2v11h3v9l7-12h-4l4-8z"/></svg>',
            'toTopIcon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-short" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/></svg>',
            'buttonHoverTitle' => __('Click to scroll the page', $this->plugin_name),
        ) );
	}

	public function wpg_generate_shortcode(){
        add_shortcode( 'wpg_autoscrol', array($this, 'wpg_autoscroll_generate') );	
		
    }
	public function wpg_shortcodes_show_all(){
		echo do_shortcode('[wpg_autoscrol]');
    }

	public function wpg_autoscroll_generate(){
		$unique_id = uniqid();

        $this->unique_id = $unique_id;
		$content = array();
		$this->setting_options = Auto_Scroll_Data::get_validated_data_from_array( 'options' );

		$content[] = $this->get_encoded_options();
		$content[] = $this->get_styles();
		$content = implode( '', $content );
		return $content;
	}

	public function get_encoded_options(){
        
        $content = array();
        $options = array();
		
		// Get settings
        

		// Auto scroll position
		$wpg_auto_scroll_button_position = $this->setting_options[$this->name_prefix .'auto_scroll_button_position'];
		// Auto scroll color
		$wpg_auto_scroll_button_color    = $this->setting_options[$this->name_prefix .'auto_scroll_button_color'];
		// Auto scroll rescroll delay
		$wpg_auto_scroll_rescroll_delay  = $this->setting_options[$this->name_prefix .'auto_scroll_rescroll_delay'];
		// Auto scroll autoplay
		$wpg_auto_scroll_autoplay        = $this->setting_options[$this->name_prefix .'auto_scroll_autoplay'];
		// Auto scroll autoplay delay
		$wpg_auto_scroll_autoplay_delay  = $this->setting_options[$this->name_prefix .'auto_scroll_autoplay_delay'];
		// Auto scroll hover title
		$wpg_auto_scroll_hover_title  = $this->setting_options[$this->name_prefix .'auto_scroll_hover_title'];
		// Go to top automatically
		$wpg_auto_scroll_go_to_top_automatically  = $this->setting_options[$this->name_prefix .'auto_scroll_go_to_top_automatically'];
		// Go to top automatically delay
		$wpg_auto_scroll_go_to_top_automatically_delay  = $this->setting_options[$this->name_prefix .'auto_scroll_go_to_top_automatically_delay'];
		// Default Speed
		$wpg_auto_scroll_default_speed	= $this->setting_options[$this->name_prefix .'auto_scroll_default_speed'];

        $options[ $this->name_prefix . 'auto_scroll_button_position'] = $wpg_auto_scroll_button_position;
       	$options[ $this->name_prefix . 'auto_scroll_button_color']    = $wpg_auto_scroll_button_color;
       	$options[ $this->name_prefix . 'auto_scroll_rescroll_delay']  = $wpg_auto_scroll_rescroll_delay;
       	$options[ $this->name_prefix . 'auto_scroll_autoplay']  	  = $wpg_auto_scroll_autoplay;
       	$options[ $this->name_prefix . 'auto_scroll_autoplay_delay']  = $wpg_auto_scroll_autoplay_delay;
       	$options[ $this->name_prefix . 'auto_scroll_hover_title']  = $wpg_auto_scroll_hover_title;
       	$options[ $this->name_prefix . 'auto_scroll_go_to_top_automatically']  = $wpg_auto_scroll_go_to_top_automatically;
       	$options[ $this->name_prefix . 'auto_scroll_go_to_top_automatically_delay']  = $wpg_auto_scroll_go_to_top_automatically_delay;
       	$options[ $this->name_prefix . 'auto_scroll_default_speed']  = $wpg_auto_scroll_default_speed;
        $content[] = '<script type="text/javascript">';    
				$content[] = "
							if(typeof wpgAutoScrollOptions === 'undefined'){
								var wpgAutoScrollOptions = [];
							}
						
						wpgAutoScrollOptions['wpg_auto_scroll_options']  = '" . base64_encode( json_encode( $options ) ) . "';";        
        $content[] = '</script>';
        $content = implode( '', $content );
    	return $content;
    }

	public function get_styles(){
		
		$content = array();
		$wpg_button_position = "right: 20px";
        if( $this->setting_options[ $this->name_prefix . 'auto_scroll_button_position' ] != '' ){
			if($this->setting_options[ $this->name_prefix . 'auto_scroll_button_position' ] == 'left'){
				$wpg_button_position = "left: 20px";
			}
        }
		
        if( $this->setting_options[ $this->name_prefix . 'auto_scroll_button_color' ] != '' ){
			$wpg_button_color = $this->setting_options[ $this->name_prefix . 'auto_scroll_button_color' ];
        }
        $content[] = '<style type="text/css">';    

        $content[] = '#'.$this->name_prefix_front_page.'autoscroll-buttons-wrap .'.$this->name_prefix_front_page.'autoscroll-button{
							background-color: '.$wpg_button_color.';
							box-shadow: 0px 0px 5px '.$wpg_button_color.';
					  }

					  #'.$this->name_prefix_front_page.'autoscroll-buttons-wrap {
						'.$wpg_button_position.';
				  	  }
					  
					  ';
		$content[] = '.'.$this->name_prefix_front_page.'hover-title{
					  	background-color: '.$wpg_button_color.';	
					  }';

		$content[] = '.'.$this->name_prefix_front_page.'bottom-triangle{
					  	border-top-color: '.$wpg_button_color.';	
					  }';			  				  
             	
    	$content[] = '</style>';

    	$content = implode( '', $content );

    	return $content;
    }

}