<?php
if ( !defined('ABSPATH')) exit;

class gab_text_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gab_text_widget', 'description' => 'Display text widget with an icon' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'gab_text_widget' );
		parent::__construct( 'gab_text_widget', 'Gabfire: Text+ Widget', $widget_ops, $control_ops);	
	}
	
	public function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$icon	= $instance['icon'];
		$text	= $instance['a_text'];
		$link 	= $instance['a_link'];
		$anchor	= $instance['a_anchor'];
		$wstyle	= $instance['wstyle'];

		echo $before_widget;

			if ( $title ) {
				if ($wstyle == "small") {
					echo $before_title;
					echo '<span style="background: url(' . plugins_url( "images/24x/$icon.png" , __FILE__ ) . ') no-repeat left center;padding:3px 0 3px 30px;">';
						echo $title;
					echo '</span>';
					echo $after_title;
				} else { 
					echo '<div style="background: url(' . plugins_url( "images/40x/$icon.png" , __FILE__ ) . ') no-repeat left 10px;padding:3px 0 3px 50px;">';
					echo $before_title . $title . $after_title;
				}
				
			}
						
			echo wpautop($text);
				
			if($link) {
				echo '<span class="about_more"><a href="' . get_permalink($link) . '">'. $anchor . '</a></span>';
			}
			
			if ($wstyle !== "small") { echo "</div>"; }
					
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title']		= ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['icon']		= ( ! empty( $new_instance['icon'] ) ) ? sanitize_text_field( $new_instance['icon'] ) : '';
		$instance['a_text'] 	= ( ! empty( $new_instance['a_text'] ) ) ? sanitize_text_field( $new_instance['a_text'] ) : '';
		$instance['a_link'] 	= ( ! empty( $new_instance['a_link'] ) ) ? sanitize_text_field( $new_instance['a_link'] ) : '';
		$instance['a_anchor'] 	= ( ! empty( $new_instance['a_anchor'] ) ) ? sanitize_text_field( $new_instance['a_anchor'] ) : '';
		$instance['wstyle'] 	= ( ! empty( $new_instance['wstyle'] ) ) ? sanitize_text_field( $new_instance['wstyle'] ) : '';
		
		return $new_instance;
	}
 
	function form($instance) {
		$defaults	= array( 'title' => 'About', 'icon' => 'world', 'a_text' => '', 'a_link' => '', 'wstyle' => 'small', 'a_anchor' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e('Icon','gabfire-widget-pack'); ?></label> 
		<select id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>">
			
			<option value="appearance" <?php selected( $instance['icon'], 'appearance'  );?>><?php _e('Appearance','gabfire-widget-pack'); ?></option>
			<option value="arrow-right" <?php  selected( $instance['icon'], 'arrow-right' ); ?>><?php _e('Arrow Right','gabfire-widget-pack'); ?></option>
			<option value="checkmark" <?php  selected( $instance['icon'], 'checkmark' ); ?>><?php _e('Checkmark','gabfire-widget-pack'); ?></option>
			<option value="comment" <?php  selected( $instance['icon'], 'comment' ); ?>><?php _e('Comment','gabfire-widget-pack'); ?></option>
			<option value="contact" <?php  selected( $instance['icon'], 'contact' ); ?>><?php _e('Contact','gabfire-widget-pack'); ?></option>
			<option value="excellent" <?php  selected( $instance['icon'], 'excellent'); ?>><?php _e('Excellent','gabfire-widget-pack'); ?></option>
			<option value="home" <?php  selected( $instance['icon'], 'home' ); ?>><?php _e('Home','gabfire-widget-pack'); ?></option>
			<option value="info" <?php  selected( $instance['icon'], 'info'  ); ?>><?php _e('Info','gabfire-widget-pack'); ?></option>
			<option value="gear" <?php  selected( $instance['icon'], 'gear'  ); ?>><?php _e('Gear','gabfire-widget-pack'); ?></option>
			<option value="magnifier" <?php  selected( $instance['icon'], 'magnifier'  ); ?>><?php _e('Magnifier','gabfire-widget-pack'); ?></option>
			<option value="rss" <?php  selected( $instance['icon'], 'rss'  ); ?>><?php _e('RSS','gabfire-widget-pack'); ?></option>
			<option value="star" <?php  selected( $instance['icon'], 'star' ); ?>><?php _e('Star','gabfire-widget-pack'); ?></option>
			<option value="support" <?php  selected( $instance['icon'], 'support' ); ?>><?php _e('Support','gabfire-widget-pack'); ?></option>
			<option value="question" <?php selected( $instance['icon'], 'question'  ); ?>><?php _e('Question Mark','gabfire-widget-pack'); ?></option>
			<option value="tools" <?php  selected( $instance['icon'], 'tools' ); ?>><?php _e('Tools','gabfire-widget-pack'); ?></option>
			<option value="twitter" <?php  selected( $instance['icon'], 'twitter' ); ?>><?php _e('Twitter','gabfire-widget-pack'); ?></option>
			<option value="weather" <?php  selected( $instance['icon'], 'weather' ); ?>><?php _e('Weather','gabfire-widget-pack'); ?></option>
			<option value="world" <?php  selected( $instance['icon'], 'world' ); ?>><?php _e('World','gabfire-widget-pack'); ?></option>
		</select>
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_name( 'wstyle' ); ?>"><?php _e('Select an icon size','gabfire-widget-pack'); ?></label>
		<select id="<?php echo $this->get_field_id( 'wstyle' ); ?>" name="<?php echo $this->get_field_name( 'wstyle' ); ?>">
			<option value="small" <?php if ( 'small' == $instance['wstyle'] ) echo 'selected="selected"'; ?>><?php _e('Small','gabfire-widget-pack'); ?></option>
			<option value="big" <?php if ( 'big' == $instance['wstyle'] ) echo 'selected="selected"'; ?>><?php _e('Big','gabfire-widget-pack'); ?></option>    
		</select>
	</p>
			
		
	<p>
		<label for="<?php echo $this->get_field_id('a_text'); ?>"><?php _e('Text','gabfire-widget-pack'); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('a_text'); ?>" name="<?php echo $this->get_field_name('a_text'); ?>"><?php echo esc_attr($instance['a_text']); ?></textarea>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('a_link'); ?>"><?php _e('Post or Page ID to link','gabfire-widget-pack'); ?></label>
		<input id="<?php echo $this->get_field_id('a_link'); ?>" name="<?php echo $this->get_field_name('a_link'); ?>" type="text" value="<?php echo esc_attr( $instance['a_link'] ); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('a_anchor'); ?>"><?php _e('Link label','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_anchor'); ?>" name="<?php echo $this->get_field_name('a_anchor'); ?>" type="text" value="<?php echo esc_attr($instance['a_anchor']); ?>" />
	</p>
<?php
	}
}

function register_gab_text_widget() {
	register_widget('gab_text_widget');
}

add_action('widgets_init', 'register_gab_text_widget');