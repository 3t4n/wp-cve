<?php
/**
 * Class for Custom Text Field support.
 *
 * @package WordPress
 */

// If check class exists.
if ( ! class_exists( 'WP_NGD_Widget_Class' ) ) {

	/**
	 * Declare class.
	 */
	class WP_NGD_Widget_Class {

		/**
		 * Calling construct.
		 */
		public function __construct() {
			
			// Add input fields(priority 5, 3 parameters)
			add_action('in_widget_form', array( $this, 'wp_ngd_widget_css_classes_in_widget_form' ), 5, 3 );
			
			// Callback function for options update (priority 5, 3 parameters)
			add_action('widget_update_callback', array( $this, 'wp_ngd_widget_css_classes_in_widget_form_update' ), 5, 3 );

			//add class names (default priority, one parameter)
			add_action('dynamic_sidebar_params', array( $this, 'wp_ngd_widget_css_classes_dynamic_sidebar_params' ) );
		}		

		/**
		 * Add input fields.
		 * Step 1: Register the form elements (The widget Control)
		 * @return array
		 */
		public function wp_ngd_widget_css_classes_in_widget_form( $t, $return, $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'float' => 'none') );
   
		    if ( ! isset( $instance['ngd_custom_text'] ) )
		        $instance['ngd_custom_text'] = null;
		    ?>     
		    <label class="ngd-title"><?php echo __( 'Widgets Wrapper Class :', 'wp-ngd-widget-css-classes' ); ?></label>
		    <input type="text" name="<?php echo $t->get_field_name('ngd_custom_text'); ?>" id="<?php echo $t->get_field_id('ngd_custom_text'); ?>" value="<?php echo isset( $instance['ngd_custom_text'] ) ? $instance['ngd_custom_text'] : ''; ?>" placeholder="<?php echo __( 'ClassName', 'wp-ngd-widget-css-classes' ); ?>" />
		    <?php
		    $retrun = null;
		    return array( $t, $return, $instance );
		}

		/**
		 * Step 2: Save the Widget input data:
		 * 
		 * @return array
		 */
		public function wp_ngd_widget_css_classes_in_widget_form_update( $instance, $new_instance, $old_instance){    

			if( isset( $new_instance['ngd_custom_text'] ) ) {
		    	$instance['ngd_custom_text'] = strip_tags( $new_instance['ngd_custom_text'] );
			}
		    return $instance;
		}

		/**
		 * Step 3: Display the value in widget output:
		 * 
		 * @return array
		 */
		public function wp_ngd_widget_css_classes_dynamic_sidebar_params( $params ){
		    global $wp_registered_widgets;
		    $widget_id = $params[0]['widget_id'];
		    $widget_obj = $wp_registered_widgets[$widget_id];
		    $widget_opt = get_option( $widget_obj['callback'][0]->option_name );
		    $widget_num = $widget_obj['params'][0]['number'];
		    
            if( isset( $widget_opt[$widget_num]['ngd_custom_text'] ) ){          
                $ngd_custom_text = $widget_opt[$widget_num]['ngd_custom_text'];
            }else{
			   $ngd_custom_text = '';
            }

            $params[0]['before_widget'] = preg_replace('/class="/', 'class="'.$ngd_custom_text.' ',  $params[0]['before_widget'], 1);
		    return $params;
		}
		
	}
}
