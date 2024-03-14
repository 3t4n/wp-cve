<?php
/*
Plugin Name: Velocity
Plugin URI: https://connekthq.com/plugins/velocity/
Description: Speed up your website by lazy loading your media embeds.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: https://connekthq.com
Version: 1.2.1
License: GPL
Copyright: Darren Cooney & Connekt Media
	    
	    
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly		


/*
*  velocity_install
*  Install the plugin
*
*  @since 1.0
*/

register_activation_hook( __FILE__, 'velocity_install' );
function velocity_install() {   
   // Nothing
}



if( !class_exists('velocity') ):
   class velocity{	  
      
      public $counter = 0;
       
   	function __construct(){		      	
      	
   		define('VELOCITY_VERSION', '1.2.1');
   		define('VELOCITY_RELEASE_DATE', 'January 4, 2019');
   		define('VELOCITY_NAME', 'Velocity');
   		define('VELOCITY_TAGLINE', 'Improve website performance by lazy loading and customizing your media embeds');
   		define('VELOCITY_PATH', plugin_dir_path(__FILE__));
   		define('VELOCITY_URL', plugins_url('', __FILE__));
   		define('VELOCITY_PLACEHOLDER', plugins_url('/core/img/placeholder.gif', __FILE__));
   		 
         add_action( 'admin_menu', array(&$this, 'velocity_admin_menu')); // Admin Menu
   		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'velocity_action_links') );
         add_action( 'wp_enqueue_scripts', array(&$this, 'velocity_enqueue_scripts')); // Scripts         
         add_action( 'admin_enqueue_scripts', array(&$this, 'velocity_enqueue_admin_scripts' )); // Admin scripts
         add_action( 'admin_head', array(&$this, 'velocity_admin_vars' )); // Localized Variables	  
         add_action( 'wp_ajax_velocity_get_image', array(&$this, 'velocity_get_image')); // Get image w/ Ajax
         add_action( 'wp_ajax_velocity_get_service_image', array(&$this, 'velocity_get_service_image')); // Get image w/ Ajax
         add_filter( 'admin_footer_text', array(&$this, 'velocity_filter_admin_footer_text')); // Admin menu text
         add_action( 'wp_ajax_velocity_save_options', 'velocity_save_options' ); // Ajax Save Options
         add_shortcode( 'velocity', array(&$this, 'velocity_shortcode')); // Shortcode
         
   		load_plugin_textdomain( 'velocity', false, dirname(plugin_basename( __FILE__ )).'/lang/'); //load text domain   		
         // includes WP admin core
   		$this->velocity_before_theme();
   	   
   	}  
   	
   	
   	/*
   	*  velocity_before_theme
   	*  Load these files before the theme loads
   	*
   	*  @since 1.0.0
   	*/
   	
   	public function velocity_before_theme(){
   		if( is_admin()){
   			include_once('admin/builder/builder.php');
   		}		
   		
         include_once('core/classes/class.metadata.php'); // Metadata
         include_once('core/classes/class.style.php'); // Styles
      }
      
   	
   	
   	//The CTA shortcode   
   	public function velocity_shortcode( $atts) {
      	
      	$this->counter++;
      	
   		$atts = shortcode_atts( array(
      		'type'      => 'youtube',
      		'playlist'  =>  false,
      		'id'        =>  null,
      		'options'   =>  '',
      		'img'       =>  null,
      		'alt'       =>  __('Play', 'velocity'),
      		'color'     => '',
      		'bkg_color' => '',
      		'event' => ''
      	), $atts, 'velocity' );
         
            
         // Set vars
         $media_id = esc_attr($atts['id']);
         $options = esc_attr($atts['options']);
         $type = esc_attr($atts['type']);
         $soundcloud_type = '';
         $playlist = esc_attr($atts['playlist']);
         $img = $atts['img'];
         $alt = esc_attr($atts['alt']);
         $btn = false;
         $btn_color = esc_attr($atts['color']); 
         $btn_bkg_color = esc_attr($atts['bkg_color']); 
         $event = esc_attr($atts['event']);     
         
         if(!empty($btn_color) && !empty($btn_bkg_color)){
            $btn = true;
         }         
         
         // Soundcloud tracks/playlists
         if($type === 'soundcloud'){	      
		      if($playlist === 'true'){
	         	$soundcloud_type = ' data-soundcloud-type="playlists"';
	         }else{
		         $soundcloud_type = ' data-soundcloud-type="tracks"';
	         }
         }              
                 
         // Default placeholder image       
         if(!isset($img) || empty($img)){
            $default = get_option('velocity_placeholder');
            if(isset($default) && !empty($default)){
               $img = $default;
            }else{
               $img = VELOCITY_PLACEHOLDER;
            }
         }  
                  
         
         if(!empty($type)){    
			
				$return  = '<div class="velocity-embed">';
							
   	         $return .= '<a href="#" data-video-type="'. $type .'" data-video-id="'. $media_id .'" data-video-options="'. $options .'"'. $soundcloud_type .' data-event="'. $event .'">';
   	         
      	         // Set thumbnail (vimeo, youtube, twitch, soundcloud)
      	         $return .= '<img class="velocity-img aligncenter" src="'. esc_url($img) .'" alt="'. $alt .'" />';
      	         
      	         
      	         // Set Metadata
      	         switch($type){
	      	         case 'youtube': 
	      	         	$return .= Velocity_Metadata::getYoutubeData($media_id);
	      	         	break;
	      	         case 'vimeo': 
	      	         	$return .= Velocity_Metadata::getVimeoData($media_id);
	      	         	break;
	      	         case 'twitch': 
	      	         	$return .= Velocity_Metadata::getTwitchData($media_id);
	      	         	break;
      	         }
      	         
      	         if($btn){
      	            $return .= '<span class="velocity-play-btn" style="background-color: '. $btn_bkg_color .'">';
      	               $return .= '<span class="velocity-arrow" style="border-left-color: '. $btn_color .';"></span>';
      	            $return .= '</span>'; 
      	         }
   	         
   	         $return .= '</a>';   
   	               
   	         $return .= '<span class="velocity-target"></span>';
	         
	         $return .= '</div>';
	         
	         $return .= Velocity_Style::renderStyles($btn, $this->counter);
	         
         }
         
         return $return;	
      }
      
      
      /*
      *  velocity_get_image
      *  Get image src from id and size.
      *
      *  @since 1.0.0
      */
      function velocity_get_image(){
         if (current_user_can( 'edit_posts' ) && current_user_can('edit_pages')){
      	
      		$nonce = sanitize_text_field($_GET['nonce']);
      		$id = sanitize_text_field($_GET['id']);
      		$size = sanitize_text_field($_GET['size']);
      		
      		// Check our nonce, if they don't match then bounce!
      		if (! wp_verify_nonce( $nonce, 'velocity_nonce' ))
      			die('Error - unable to verify nonce, please try again.');			
      		
      		$img = wp_get_attachment_image_src( $id, $size ); // Get image path
            $img = $img[0];
      		
      		echo $img;      		
            
            wp_die();
         }
      }
      
      
      /*
      *  velocity_get_service_image
      *  Get image src from id and size.
      *
      *  @since 1.0.0
      */
      function velocity_get_service_image(){
         if (current_user_can( 'edit_posts' ) && current_user_can('edit_pages')){
      	
      		$nonce = sanitize_text_field($_GET['nonce']);
      		$url = sanitize_text_field($_GET['url']);
      		
      		// Check our nonce, if they don't match then bounce!
      		if (! wp_verify_nonce( $nonce, 'velocity_nonce' ))
      			die('Error - unable to verify nonce, please try again.');			
      		      		
      		if(!isset($url))
      		   exit;
      		     
            
            $curl = curl_init($url);
            //don't fetch the actual page, you only want to check the connection is ok
            curl_setopt($curl, CURLOPT_NOBODY, true);
            
            //do request
            $result = curl_exec($curl);            
            $ret = false;
            
            //if request did not fail
            if ($result !== false) {
               
               //if request was ok, check response code
               $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);                    
               if ($statusCode == 200) {
                  echo $url;  
               }
            }
            
            die();
         }
      }
   	
   	
   	
   	/*
   	*  velocity_enqueue_scripts
   	*  Enqueue our scripts
   	*
   	*  @since 1.0
   	*/
   
   	public function velocity_enqueue_scripts(){   		
         $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min'; // Use minified libraries if SCRIPT_DEBUG is turned off
   		wp_enqueue_script( 'velocity', plugins_url( '/core/js/velocity'.$suffix.'.js', __FILE__ ), array('jquery'),  VELOCITY_VERSION, true );  
   	}	
   	
   	
   	
   	/*
      *  velocity_admin_menu
      *  Create admin menu item
      *
      *  @since 1.0.0
      */
   	public function velocity_admin_menu() {         
         add_submenu_page( 
            'options-general.php', 
            'Velocity', 
            'Velocity', 
            'edit_theme_options', 
            'velocity', 
            'velocity_settings_callback'
         );
      }         
      
      
      
      /*
      *  velocity_enqueue_admin_scripts
      *  Enqueue admin scripts
      *
      *  @since 1.0.0
      */
      public function velocity_enqueue_admin_scripts(){               
         wp_enqueue_style( 'velocity-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
         wp_enqueue_style( 'velocity-admin', VELOCITY_URL. '/admin/css/admin.css');
   		wp_enqueue_script( 'velocity-builder', VELOCITY_URL. '/admin/builder/js/builder.js', array('jquery'),  '1.0', true );   
      }   
      
      
      
      /*
      *  velocity_admin_vars
      *  Create admin variables for Velocity
      *
      *  @since 1.0.0
      */
      function velocity_admin_vars() { ?>
         <script type='text/javascript'>
         /* <![CDATA[ */
         var velocity_localize = <?php echo json_encode( array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'velocity_nonce' => wp_create_nonce( 'velocity_nonce' ),
            'pluginurl' => VELOCITY_URL,
            'image_select' => __('Select Image', 'velocity'),
         )); ?>
         /* ]]> */
         </script>
      <?php }   
	      
	      
	   
	   /*
		*  velocity_filter_admin_footer_text
		*  Filter the WP Admin footer text only on Velocity
		*
		*  @since 1.1
		*/
		
		function velocity_filter_admin_footer_text( $text ) {	
			
			$screen = get_current_screen();
			if($screen->base === 'settings_page_velocity'){			
				echo '<strong>Velocity</strong> is made with <span style="color: #e25555;">♥</span> by <a href="https://connekthq.com" target="_blank" style="font-weight: 500;">Connekt</a> | <a href="https://wordpress.org/support/plugin/velocity/reviews/" target="_blank" style="font-weight: 500;">Leave a Review</a>';
			}
		}  
	      
	      
	      
	   /*
   	*  velocity_action_links
   	*  Add plugin action links to WP plugin screen
   	*
   	*  @since 1.0
   	*/   
      
      function velocity_action_links( $links ) {
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=velocity') .'">'.__('Settings', 'velocity').'</a>';
         $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=velocity&vb=true') .'">'.__('Builder', 'velocity').'</a>';
         return $links;
      }    
         
   }     
   	
   	
   // Settings screen	
   function velocity_settings_callback(){   
      include_once( VELOCITY_PATH . 'admin/views/settings.php');   
   }
      
      
   // AJAX Save options!
   function velocity_save_options(){
      if (current_user_can( 'edit_theme_options' )){
		
   		$nonce = sanitize_text_field($_POST["nonce"]);
   		$image_url = sanitize_text_field($_POST["image_url"]);
   		
   		// Check our nonce, if they don't match then bounce!
   		if (! wp_verify_nonce( $nonce, 'velocity_nonce' ))
   			die('Error - unable to verify nonce, please try again.');			
   		
   		update_option('velocity_placeholder', $image_url);
   		
         echo __('Settings Updated!', 'framework');
         
         die();
      }
   }
   
   
   // get image dimensions
   function velocity_get_image_dimensions( $name ) {
   	global $_wp_additional_image_sizes;
   
   	if ( isset( $_wp_additional_image_sizes[$name] ) )
   		return $_wp_additional_image_sizes[$name];
   
   	return false;
   }
   	
   	
   	
   /*
   *  velocity
   *  The main function responsible for returning our plugin class
   *
   *  @since 1.0
   */	
   
   function velocity(){
   	global $velocity;
   
   	if( !isset($velocity) ){
   		$velocity = new velocity();
   	}
   
   	return $velocity;
   }
   
   // initialize
   velocity();

endif; // class_exists check