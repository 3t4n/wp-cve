<?php
	/*
	Widget Name: NX Team For Page Builder
	Description: NX Team Members Caropusel Widget For Page Builder.
	Author: templatesNext
	Author URI:Author URI: http://www.TemplatesNext.org
	*/	

	class nx_team_widget extends WP_Widget {
		
		//function nx_team_widget() {
		function __construct() {	
			$widget_ops = array( 
				'classname' => 'widget-nx-team', 
				'description' => 'Team Carousel widget for pagebuilder',
				'panels_icon' => 'dashicons dashicons-screenoptions',
				'panels_groups' => array('tx')				
			);
        	parent::__construct( 'widget-nx-team', 'TX Team Members Carousel ( for PB )', $widget_ops );				
		}
	
		function form($instance) {
		$defaults = array( 
			'items' => '8', 
			'columns' => '4', 
			//'ids' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		//[tx_team type="product_categories" ids="" columns="4" items="8"]
	?>
	<div class="nx-widget-content">		
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Number Of Items', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" value="<?php echo $instance['items']; ?>" class="nx-widenumber nx-pb-input" type="number" min="1" max="16" step="1" />
		</p> 
		<p class="nx-pb-para">
			<label class="nx-pb-lebel"><?php _e('Number Of Columns', 'tx');?>:</label>
            <input id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>" value="<?php echo $instance['columns']; ?>" class="nx-widerange nx-pb-input" type="number" min="1" max="4" step="1" />
		</p>
	</div>		
	<?php	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['type'] = strip_tags( $new_instance['type'] );
			$instance['items'] = strip_tags( $new_instance['items'] );
			$instance['columns'] = strip_tags( $new_instance['columns'] );
			//$instance['ids'] = strip_tags( $new_instance['ids'] );
																		
			return $instance;
		}
		
		function widget($args, $instance) {
			
			extract( $args );
	
			$type = esc_attr($instance['type']);
			$items = esc_attr($instance['items']);
			$columns = esc_attr($instance['columns']);
			//$ids = $instance['ids'];
	
			$output = '';
			
			$output .= '<div>[tx_team type="'.$type.'" items="'.$items.'" columns="'.$columns.'"]</div>';
			
			echo $output;
	
		}
			
	}
	
	add_action( 'widgets_init', 'nx_load_team_widget' );
	
	function nx_load_team_widget() {
		register_widget('nx_team_widget');
	}
