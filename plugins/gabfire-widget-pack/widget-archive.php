<?php
if ( !defined('ABSPATH')) exit;

class gabfire_archive extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_archive_widget', 'description' => 'Search in Archive');
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gabfire_archive_widget' );
		parent::__construct( 'gabfire_archive_widget', 'Gabfire: Archive Search', $widget_ops, $control_ops);
	}	
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$date	= $instance['date'];
		$month	= $instance['month'];
		$cat	= $instance['cat'];
		$google	= $instance['google'];
		$google_df	= $instance['google_df'];
		$bgcol	= $instance['bgcol'];
		
		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			?>
				<div style="background:<?php echo esc_attr( $bgcol ); ?>">
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>"  method="get" > 		
						<label><?php echo esc_attr( $date ); ?></label>
						<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> 
						<option value=""><?php echo esc_attr( $month ); ?></option> 
						<?php wp_get_archives('type=monthly&format=option&nwsp_post_count=1'); ?> </select>
					</form>
					
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>"  method="get" > 
						<label><?php echo esc_attr( $cat ); ?></label>
						<?php wp_dropdown_categories('show_option_none='. __('Click to Select','gabfire-widget-pack') .'&orderby=Name&hierarchical=1&nwsp_count=1'); ?>
					</form>
					
					<script type="text/javascript"><!--
						var dropdown = document.getElementById("cat");
						function onCatChange() {
							if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
								location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
							}
						}
						dropdown.onchange = onCatChange;
					--></script>
							
					<form method="get" action="//www.google.com/search">
						<label><?php echo esc_attr( $google ); ?></label>
						<input name="q" class="google" value="<?php echo esc_attr( $google_df ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" /> 
						<input type="hidden" name="sitesearch" value="<?php echo esc_url( home_url( '/' ) ); ?>" />
					</form>		
				</div>
			<?php 		
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['date'] = ( ! empty( $new_instance['date'] ) ) ? sanitize_text_field( $new_instance['date'] ) : '';
		$instance['month'] = ( ! empty( $new_instance['month'] ) ) ? sanitize_text_field( $new_instance['month'] ) : '';
		$instance['cat'] = ( ! empty( $new_instance['cat'] ) ) ? sanitize_text_field( $new_instance['cat'] ) : '';
		$instance['google'] = ( ! empty( $new_instance['google'] ) ) ? sanitize_text_field( $new_instance['google'] ) : '';
		$instance['google_df'] = ( ! empty( $new_instance['google_df'] ) ) ? sanitize_text_field( $new_instance['google_df'] ) : '';
		$instance['bgcol'] = ( ! empty( $new_instance['bgcol'] ) ) ? sanitize_text_field( $new_instance['bgcol'] ) : '';
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Search in Archive',
			'date' => 'Select a Month',
			'month' => 'Click to Select',
			'bgcol' => 'transparent',
			'cat' => 'Select a Category',
			'google_df' => 'Write keyword and hit return',
			'google' => 'Search with Google'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
			<input class="widefat"  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Label for Search by Date','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="text" value="<?php echo esc_attr($instance['date']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('month'); ?>"><?php _e('Date selectbox placeholder text','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('month'); ?>" name="<?php echo $this->get_field_name('month'); ?>" type="text" value="<?php echo esc_attr($instance['month']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Category selectbox placeholder text','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo esc_attr($instance['cat']); ?>" />
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id('google'); ?>"><?php _e('Google search label','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('google'); ?>" name="<?php echo $this->get_field_name('google'); ?>" type="text" value="<?php echo esc_attr($instance['google']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('google_df'); ?>"><?php _e('Google field input placeholder text','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('google_df'); ?>" name="<?php echo $this->get_field_name('google_df'); ?>" type="text" value="<?php echo esc_attr($instance['google_df']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('bgcol'); ?>"><?php _e('Background color (eg #fff or white)','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcol'); ?>" name="<?php echo $this->get_field_name('bgcol'); ?>" type="text" value="<?php echo esc_attr($instance['bgcol']); ?>" />
		</p>
		
	<?php
	}
}

function register_widgetname() {
	register_widget('gabfire_archive');
}

add_action('widgets_init', 'register_widgetname');