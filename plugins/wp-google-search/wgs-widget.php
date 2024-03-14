<?php

class WGS_Widget extends WP_Widget {


	function __construct() {	
		parent::__construct(false, $name = __('WP Google Search (WGS)','wp-google-search'));
		
	}


	function widget($args, $instance) {
		
		global $wgs;

		echo $args['before_widget'];

		$options = get_option( 'wgs_general_settings' );

		//if ($options['use_default_correction_css'] == 1)
		//	wp_enqueue_style( 'wgs', plugins_url('wgs.css', __FILE__) );

		
		$search_gcse_page_url = get_page_link( $options['search_gcse_page_id'] );

		if ($instance['hide_title'] != 1) { 
				
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $args['before_title'] . $title . $args['after_title'];
			
		}					

		if ( $options['support_overlay_display'] == 1 )
			$gcse_code = 'search';
		else
			$gcse_code = 'searchbox-only';

		$content  = '<div class="wgs_wrapper">';
		
		//You can use HTML5-valid div tags as long as you observe these guidelines: //20140423
		//The class attribute must be set to gcse-XXX
		//Any attributes must be prefixed with data-.
		//$content .= '<gcse:searchbox-only resultsUrl="' . $search_gcse_page_url . '"></gcse:searchbox-only>';
		//<div class="gcse-search">
		$content .= '<div class="gcse-' . $gcse_code . '" data-resultsUrl="' . $search_gcse_page_url . '"></div>';

		$content .= '</div>';

		echo apply_filters('wgs_widget_content', $content);

		echo $args['after_widget'];
		
	}


	function update($new_instance, $old_instance) {

		$instance = array();

		$instance['hide_title'] = ( ! empty( $new_instance['hide_title'] ) ) ? strip_tags( $new_instance['hide_title'] ) : 0;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['promote'] = ( ! empty( $new_instance['promote'] ) ) ? strip_tags( $new_instance['promote'] ) : 0;

		return $instance;				
		
	}


	function form($instance) {
	
		$instance = wp_parse_args( $instance, array(
			'hide_title' => 0, 
			'title' => __('Search', 'wp-google-search'),
			'promote' => 0
		) );

		global $wgs;
	
		?>

		<p><input class="checkbox" id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" type="checkbox" value="1" <?php echo checked( 1, esc_attr( $instance['hide_title']), false ); ?>" /><label for="<?php echo $this->get_field_id('hide_title') . '">' . ' ' . __('Hide title','wp-google-search') ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title','wp-google-search').':'; ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></label></p>


		<?php				
		
	}
}

function wgs_widget_init() {
	register_widget( 'WGS_Widget' );
}
add_action( 'widgets_init', 'wgs_widget_init' );