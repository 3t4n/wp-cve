<?php
/**
 * A3 Responsive Slider Widget
 * 
 * @package		People Contact
 * @category	Widgets
 * @author		A3rev
 */

namespace A3Rev\RSlider;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget extends \WP_Widget {
	
	/** constructor */
	function __construct() {
		$widget_ops = array(
			'classname' => 'a3_rslider_widget',
			'description' => __( "Use this widget to add A3 responsive slider as a widget.", 'a3-responsive-slider' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('a3_rslider_widget', __( 'a3 Responsive Slider', 'a3-responsive-slider' ), $widget_ops);
	}
	
	/** @see WP_Widget */
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
		$slider_id = empty( $instance['slider_id'] ) ? 0 : $instance['slider_id'];
		
		echo $before_widget;
		if ( trim($title) != '' ) echo $before_title . $title . $after_title;
		
		$slider_data = get_post( $slider_id );
		$have_slider_id = get_post_meta( $slider_id, '_a3_slider_id' , true );
		if ( $slider_data && $have_slider_id > 0 ) {
		
			$slider_settings =  get_post_meta( $slider_id, '_a3_slider_settings', true );
			$slider_template = get_post_meta( $slider_id, '_a3_slider_template' , true );
			
			$slide_items = Data::get_all_images_from_slider_client( $slider_id );
			
			global $a3_rslider_template1_global_settings;
			
			$templateid = 'template1';
			
			$slider_template = 'template-1';
		
			global ${'a3_rslider_'.$templateid.'_dimensions_settings'}; // @codingStandardsIgnoreLine // phpcs:ignore
			
			$dimensions_settings = ${'a3_rslider_'.$templateid.'_dimensions_settings'};
			
			$rslider_custom_style = '';
			$rslider_custom_style .= 'width:100% !important;';
			
			$dimensions_settings['is_slider_responsive'] = 1;
			$dimensions_settings['slider_wide_responsive'] = 100;
			
			echo Display::dispay_slider( $slide_items, $slider_template, $dimensions_settings, $slider_settings, $rslider_custom_style );
		}
		
		echo $after_widget;
	}

	/** @see WP_Widget->update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['slider_id'] = strip_tags($new_instance['slider_id']);
		return $instance;
	}
	
	/** @see WP_Widget->form */
	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'slider_id' => 0 ) );
	    $title = strip_tags($instance['title']);
		$slider_id = strip_tags($instance['slider_id']);
?>
		<p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'a3-responsive-slider' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        
        <p>
        	<?php
			$list_sliders = get_posts( array(
				'posts_per_page'		=> -1,
				'orderby'				=> 'title',
				'order'					=> 'ASC',
				'post_type'				=> 'a3_slider',
				'post_status'			=> 'publish',
				'meta_query'			=> array( 
							array(
								'key'		=> '_a3_slider_id',
								'value'		=> 1,
								'compare'	=> '>=',
								'type'		=> 'NUMERIC',
							)
				),
			));
			?>
        	<label for="<?php echo $this->get_field_id('slider_id'); ?>"><?php _e( 'Select Slider', 'a3-responsive-slider' ); ?>:</label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id('slider_id') ); ?>" name="<?php echo esc_attr( $this->get_field_name('slider_id') ); ?>" >
            <?php
			if ( $list_sliders && count( $list_sliders ) > 0 ) {
				foreach ( $list_sliders as $slider ) {
			?>
            	<option value="<?php echo esc_attr( $slider->ID ); ?>" <?php selected( $slider->ID, $slider_id ); ?> ><?php echo $slider->post_title; ?></option>
            <?php	
				}
			}
			?>
            </select>
        </p>
<?php
	}

} 
