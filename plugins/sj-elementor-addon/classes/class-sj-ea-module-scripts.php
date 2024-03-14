<?php 
namespace Elementor;

if ( ! class_exists( 'SJEaModuleScripts' ) ) {
	
	/**
	* Responsible for setting up constants, classes and includes.
	*
	* @since 0.1
	*/
	final class SJEaModuleScripts {

		static public function init() {
			
			add_action( 'elementor/element/parse_css', __CLASS__ . '::add_dynamic_scripts', 10, 2 );
			add_action( 'wp_enqueue_scripts', __CLASS__ . '::add_editor_scripts' );

		}

		static public function add_dynamic_scripts( $css_obj, $element ) {
   
		    $module_name 	= $element->get_name();
		    $modules 		= array( 
		    					'sjea-image-separator',
		    				);

			
			if ( in_array( $module_name, $modules ) ) {
		    	
		    	$module_fun = str_replace('-', '_', $module_name);
		    	$module_fun = $module_fun . '_dynamic';
    	        $settings 	= $element->get_settings();
        		$node_id	= $element->get_id();

		    	$output = self::$module_fun( $node_id, $settings );

   		    	$css_obj->get_stylesheet()->add_raw_css( $output );
		    }


		    // $element_settings = $element->get_settings();

		    // if ( empty( $element_settings['custom_css'] ) ) {
		    //     return;
		    // }

		    // $css = trim( $element_settings['custom_css'] );

		    // if ( empty( $css ) ) {
		    //     return;
		    // }
		    // $css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		    // Add a css comment
		    //$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_title(), $element->get_unique_selector() ) . $css . '/* End custom CSS */';

		    //$post_css->get_stylesheet()->add_raw_css( $css );
		}

		/**
		 * Add modules scripts in editor.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function add_editor_scripts() {
			if( Plugin::instance()->editor->is_edit_mode() || Plugin::instance()->preview->is_preview_mode() ) {

				self::sjea_row_separator();
				self::sjea_image_separator();
			}
		}
		
		/**
		 * Add row separator scripts in editor.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function sjea_row_separator( $settings = '' ) {
       		$module_url = SJ_EA_URL . 'modules/sjea-row-separator/';
			$module_dir = SJ_EA_DIR . 'modules/sjea-row-separator/';

			wp_enqueue_style( 'sjea-row-separator-css', $module_url . 'css/sjea-row-separator.css', array(), SJ_EA_VERSION );
			
		}

		/**
		 * Add image separator scripts in editor.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function sjea_image_separator() {
       		$module_url = SJ_EA_URL . 'modules/sjea-image-separator/';
			$module_dir = SJ_EA_DIR . 'modules/sjea-image-separator/';

			wp_enqueue_style( 'sjea-image-separator-css', $module_url . 'css/sjea-image-separator.css', array(), SJ_EA_VERSION );
			
		}

		/**
		 * Add image separator dynamic scripts.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function sjea_image_separator_dynamic( $node_id, $settings, $only_css = false ) {
       		
			ob_start();
			include SJ_EA_DIR . 'modules/sjea-image-separator/includes/frontend.css.php';
			$output = ob_get_clean();

			if ( $only_css ) {
				self::generate_dynamic_css( $only_css, $output );
			}else{
				return $output;
			}
		}
		
		/**
		 * genrate css based on condition.
		 *
		 * @since 0.1 
		 * @return void
		 */
		static public function generate_dynamic_css( $only_css, $output ) {
			
			echo '<style>';
			echo $output;
			echo '</style>';
		}
	}

	SJEaModuleScripts::init();
}