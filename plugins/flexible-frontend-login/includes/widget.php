<?php
/* Adds a widget for frontend login */
add_action( 'widgets_init', 'paw_ffl_register_widget' );

// register widget
function paw_ffl_register_widget() {
	register_widget( 'paw_ffl_widget' );
}

class paw_ffl_widget extends WP_Widget {

	// processing widget
	function paw_ffl_widget() {
		
		$widget_ops = array(
			'classname' => 'paw_ffl_widget_class',
			'description' => __( 'Display login popup link with options' )
		);
		
		$this->WP_Widget( 'paw_ffl_widget', 'Login Popup Widget', $widget_ops ); 
	}
	
	// create the widget settings form
	function form( $instance ) {
		$defaults = array(
			'title' => __('Flexible Frontend Login'),
			'vertical' => 'bottom',
			'horizontal' => 'left'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = $instance['title'];
		$vertical = $instance['vertical'];
		$horizontal = $instance['horizontal'];
		
		$html  = "<p><b>";
		$html .= __('Title');
		$html .= ": </b></p><p><input type='text' class='widefat' name='";
		$html .= $this->get_field_name( 'title' );
		$html .= "' value='$title' />";
		$html .= "</p>";
		$html .= "<p><b>";
		$html .= __('Pop up top or bottom');
		$html .= "</b></p><p>";
		$html .= "<input type='radio' class='widefat' name='";
		$html .= $this->get_field_name( 'vertical' );
		$html .= "' ";
		$html .= checked( $vertical, 'bottom', false );
		$html .= "value='bottom'";
		$html .= " /> ";
		$html .= __('bottom');
		$html .= "   <input type='radio' class='widefat' name='";
		$html .= $this->get_field_name( 'vertical' );
		$html .= "' ";
		$html .= checked( $vertical, 'top', false );
		$html .= "value='top'";
		$html .= " /> ";
		$html .= __('top');
		$html .= "</p>";
		$html .= "<p><b>";
		$html .= __('Pop up left or right');
		$html .= "</b></p><p>";
		$html .= "<input type='radio' class='widefat' name='";
		$html .= $this->get_field_name( 'horizontal' );
		$html .= "' ";
		$html .= checked( $horizontal, 'left', false );
		$html .= "value='left'";
		$html .= " /> ";
		$html .= __('left');
		$html .= "   <input type='radio' class='widefat' name='";
		$html .= $this->get_field_name( 'horizontal' );
		$html .= "' ";
		$html .= checked( $horizontal, 'right', false );
		$html .= "value='right'";
		$html .= " /> ";
		$html .= __('right');
		$html .= "</p>";
		echo $html;
	}	


	// save widget settings
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['vertical'] = ( $new_instance['vertical'] );
		$instance['horizontal'] = ( $new_instance['horizontal'] );	
		
		return $instance;
		}
	
	
	// display the widget
	function widget( $args, $instance ) {
		extract( $args );
		
		echo $before_widget;
		
		// load widget settings
		$title = apply_filters( 'widget_title', $instance['title'] ); 
		$vertical = empty( $instance['vertical'] ) ? 'bottom' : $instance['vertical'];
		$horizontal = empty( $instance['horizontal'] ) ? 'left' : $instance['horizontal'];
		
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}			
		paw_flexible_frontend_login( $vertical, $horizontal );
	}

}


?>
