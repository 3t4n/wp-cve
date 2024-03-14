<?php
/**
 * Class CFB_Shortcode
 */
if( !class_exists( 'CFB_Shortcode' ) ){

    class CFB_Shortcode 
	{
        /**
         * CFB_Shortcode constructor.
         */
        function __construct()  
		{            
			// Add shortcode for flipboxes
			add_shortcode( 'flipboxes',array($this,'cfb_shortcode'));
			// Register frontend assets for flipboxes
			add_action( 'wp_enqueue_scripts',array($this,'cfb_register_frontend_assets')); 
			add_action( 'admin_enqueue_scripts',array($this,'cfb_register_frontend_assets'));
        }
        
        /**
         * Function to handle flipboxes shortcode
         *
         * @param $atts
         * @return string
         */
        function cfb_shortcode($atts)
		{
            // Get attributes and set default values
            $atts = shortcode_atts(array(
                'id' => '',
            ), $atts, 'ccpw');

			$id = $atts['id'];

			$prefix   = "_cfb_";
			$flip_layout    = get_post_meta( $id, $prefix . 'flip_layout', true );
			$effect         = get_post_meta( $id, $prefix . 'effect', true );
			$height         = get_post_meta( $id, $prefix . 'height', true ) ?: 'default';
			$icon_size      = get_post_meta( $id, $prefix .'icon_size', true ) ?: "52px";
			$skincolor      = get_post_meta( $id, $prefix .'skin_color', true ) ?: "#f4bf64";
			$cols           = get_post_meta( $id, $prefix . 'column', true );
			$bootstrap      = get_post_meta( $id, $prefix . 'bootstrap', true );
			$fontawesome    = get_post_meta( $id, $prefix . 'font', true );
			$no_of_items    = get_post_meta( $id, $prefix . 'no_of_items', true ) ?: 9999;
			$entries        = get_post_meta( $id, $prefix .'flip_repeat_group', true );
            $link_target    = get_post_meta( $id, $prefix .'LinkTarget', true ) ?: false;
            $dynamic_target = $link_target ? '_self' : '_blank';

            global $post; 
			
			// Enqueue fontawesome and flexgrid if enabled
			if ($bootstrap === 'enable'){
				wp_enqueue_style( 'cfb-flexboxgrid-style');
			}
			if ($fontawesome === 'enable' && !in_array($flip_layout, array('with-image', 'layout-6'))){
				wp_enqueue_style( 'cfb-fontawesome');
			}
			// Enqueue other scripts and styles files					
			CFB_Functions::cfb_enqueue_scripts();
			
			// Check if entries exist and count is greater than -1
			if( is_array( $entries ) && count($entries) > -1 )
			{
				$i = 1;
				$flipbox_html = ''; 
				$flipbox_html .= '<div id="flipbox-widget-'.esc_attr($id).'" class="cfb_wrapper '.esc_attr($flip_layout).' flex-row" data-flipboxid="flipbox-widget-'.esc_attr($id).'">';
				foreach ( $entries as $entry ) 
				{
					if($i > $no_of_items){
						break;
					}
					require_once CFB_DIR_PATH . '/includes/cfb-layouts.php';  // Include layouts
					$cfb_layouts = new CFB_Layouts();

					// Generate new layout based on flip layout and entry
					$new_layout = $cfb_layouts->layout_handle($flip_layout, $atts, $entry , $i);
					$flipbox_html .= $new_layout;
					$i++;	

					
				}	// end of foreach
				$flipbox_html .= '</div>';
				return $flipbox_html;	
			} else {
				return __('No flipbox content added','c-flipboxes');
			}
        }

        /**
         * Function to register frontend assets
         */
        function cfb_register_frontend_assets() 
		{
			// Register custom js for flipboxes
			wp_register_script( 'cfb-custom-js', CFB_URL . 'assets/js/flipboxes-custom.min.js', array('jquery'), CFB_VERSION );
			
			// Register fontawesome css
			wp_register_style( 'cfb-fontawesome',CFB_URL . 'assets/css/font-awesome.min.css', array(), CFB_VERSION);

			// Register jquery flip js
			wp_register_script( 'cfb-jquery-flip', CFB_URL . 'assets/js/jquery.flip.min.js', array('jquery'), CFB_VERSION );
			
			// Register flexboxgrid style if enabled
			wp_register_style( 'cfb-flexboxgrid-style',CFB_URL . 'assets/css/flipboxes-flexboxgrid.min.css', array(), CFB_VERSION);
			// Register default styles
			wp_register_style( 'cfb-styles',CFB_URL . 'assets/css/flipboxes-styles.min.css', array(), CFB_VERSION);
			
			wp_register_script( 'cfb-imagesloader', CFB_URL . 'assets/js/jquery-imagesloader.min.js', array('jquery'), CFB_VERSION );
			
			global $post; 
			if(is_page()){
				if( is_a( $post, 'WP_Post' )&& has_shortcode( $post->post_content, 'flipboxes')){  								
					CFB_Functions::cfb_enqueue_scripts();					
				}
			}
			
			
		}
        

    }

}