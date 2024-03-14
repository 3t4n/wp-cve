<?php
if ( !defined('ABSPATH')) exit;

class gabfire_search extends WP_Widget {
 
	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_search_widget', 'description' => 'Display Search Form' );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'gabfire_search_widget' );
		parent::__construct( 'gabfire_search_widget', 'Gabfire: Search', $widget_ops, $control_ops);
	}
 
	public function widget($args, $instance) {	  
		extract( $args );
		$title	= $instance['title'];
		$label	= $instance['label'];
		$s_style	= $instance['s_style'] ? '1' : '0';
		$bgcol	= $instance['bgcol'];
		$bordercol	= $instance['bordercol'];

		echo $before_widget;
			
				if ( $title ) {
					echo $before_title . $title . $after_title;
				}
			
				if($s_style == 1) { 
				
				?>
					<form class="gabfire_search_style1" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<fieldset style="<?php if ( $bgcol ) { echo 'background:' . esc_html( $bgcol ); echo ';';} if ( $bordercol ) { echo 'border:1px solid ' . esc_html( $bordercol ); echo ';';} ?>">
							<input type="text" style="width:80%;<?php if ( $bgcol ) { echo 'background:' . esc_attr( $bgcol ); } ?>" class="gab_search_text" name="s" value="<?php echo esc_attr( $label ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $label ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $label ); ?>';}" />
							<input type="submit" class="submit_style1" alt="<?php echo esc_attr( $label ); ?>" />
							<div class="clearfix"></div>
						</fieldset>
					</form>				
				<?php } else { ?>
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="gabfire_search_style2" style="background:url(<?php echo GABFIRE_WIDGETS_URL; ?>/images/bgr_search_box.png) no-repeat;">
						<fieldset>
							<input type="image" class="submit_style2" src="<?php echo GABFIRE_WIDGETS_URL; ?>/images/bgr_search_box-submit.png" alt="<?php echo esc_attr( $label ); ?>" />
							<input type="text" class="gab_search_text" name="s" value="<?php echo esc_attr( $label ); ?>" onfocus="if (this.value == '<?php echo esc_attr( $label ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo esc_attr( $label ); ?>';}" />
						</fieldset>
					</form>
				<?php 
				}
		echo "<div class='clear'></div>$after_widget"; 
	}

	function update($new_instance, $old_instance) {
		$instance['title'] 		= ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['label'] 		= ( ! empty( $new_instance['label'] ) ) ? sanitize_text_field( $new_instance['label'] ) : '';
		$instance['s_style']	= $new_instance['s_style'] ? '1' : '0';
		$instance['bgcol'] = ( ! empty( $new_instance['bgcol'] ) ) ? sanitize_text_field( $new_instance['bgcol'] ) : '';
		$instance['bordercol'] = ( ! empty( $new_instance['bordercol'] ) ) ? sanitize_text_field( $new_instance['bordercol'] ) : '';
		return $new_instance;
	}
 
	function form($instance) {
		$defaults = array(
			'title' => 'Search in Site',
			'label' => 'Search...',
			'bgcol' => '#efefef',
			's_style' => '1',
			'bordercol' => '#eee'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('label'); ?>"><?php _e('Search Label','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" type="text" value="<?php echo esc_attr($instance['label']); ?>" />
		</p>
		
		<p>
			<select id="<?php echo $this->get_field_id( 's_style' ); ?>" name="<?php echo $this->get_field_name( 's_style' ); ?>">
				<option value="1" <?php if ( '1' == $instance['s_style'] ) echo 'selected="selected"'; ?>><?php _e('Search Style 1','gabfire-widget-pack'); ?></option>
				<option value="0" <?php if ( '0' == $instance['s_style'] ) echo 'selected="selected"'; ?>><?php _e('Search Style 2','gabfire-widget-pack'); ?></option>	
			</select>
		</p>
		<p><small><?php _e('Search style 2 requires 300px width to display correct','gabfire-widget-pack'); ?></small></p>
		<p>
			<label for="<?php echo $this->get_field_id('bgcol'); ?>"><?php _e('Background color for input field','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bgcol'); ?>" name="<?php echo $this->get_field_name('bgcol'); ?>" type="text" value="<?php echo esc_attr($instance['bgcol']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('bordercol'); ?>"><?php _e('Border color for input field','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('bordercol'); ?>" name="<?php echo $this->get_field_name('bordercol'); ?>" type="text" value="<?php echo esc_attr($instance['bordercol']); ?>" />
		</p>

<?php
	}
}

function register_gabfire_search() {
	register_widget('gabfire_search');
}
add_action('widgets_init', 'register_gabfire_search');

function gab_unregister_widgets() {
	unregister_widget( 'WP_Widget_Search' );
}
add_action('widgets_init', 'gab_unregister_widgets');