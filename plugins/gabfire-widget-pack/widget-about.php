<?php
if ( !defined('ABSPATH')) exit;

class gabfire_about extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gab_about_widget', 'description' => 'Display an "about" widget' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'gab_about_widget' );
		parent::__construct( 'gab_about_widget', 'Gabfire: About', $widget_ops, $control_ops);	
	}
	
	public function widget($args, $instance) {
		extract( $args );
		$title	= $instance['title'];
		$avatar	= $instance['a_avatar'] ? '1' : '0';
		$a_align	= $instance['a_align'];
		$a_imgurl	= $instance['a_imgurl'];
		$text	= $instance['a_text'];
		$link 	= $instance['a_link'];
		$anchor	= $instance['a_anchor'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if($avatar) {
				echo '<div class="widget_avatar">' . get_avatar(get_bloginfo('admin_email'),'50') . '</div>';
			}	
			
			if($a_imgurl) {
				echo "<img src='$a_imgurl' alt='' class='$a_align' />";
			}
			
			echo  wpautop($text) . '<div class="clear clearfix"></div>';
				
			if($link) {
				echo '<span class="about_more"><a href="' . get_permalink($link) . '">'. $anchor . '</a></span>';
			}
			
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title']		= ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['a_avatar']	= $new_instance['a_avatar'] ? '1' : '0';
		$instance['a_text'] 	= ( ! empty( $new_instance['a_text'] ) ) ? sanitize_text_field( $new_instance['a_text'] ) : '';
		$instance['a_align'] 	= ( ! empty( $new_instance['a_align'] ) ) ? sanitize_text_field( $new_instance['a_align'] ) : '';
		$instance['a_imgurl'] 	= ( ! empty( $new_instance['a_imgurl'] ) ) ? sanitize_text_field( $new_instance['a_imgurl'] ) : '';
		$instance['a_link'] 	= ( ! empty( $new_instance['a_link'] ) ) ? sanitize_text_field( $new_instance['a_link'] ) : '';
		$instance['a_anchor'] 	= ( ! empty( $new_instance['a_anchor'] ) ) ? sanitize_text_field( $new_instance['a_anchor'] ) : ''; 
		return $new_instance;
	}
  
	function form($instance) {
		$defaults	= array( 'title' => 'About', 'a_avatar' => '1', 'a_align' => '', 'a_imgurl' => '', 'a_text' => '', 'a_link' => '', 'a_anchor' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
			
	<p>
		<label for="<?php echo $this->get_field_id( 'a_avatar' ); ?>"><?php _e('Site Admin\'s Avatar','gabfire-widget-pack'); ?></label> 
		<select id="<?php echo $this->get_field_id( 'a_avatar' ); ?>" name="<?php echo $this->get_field_name( 'a_avatar' ); ?>">
			<option value="1" <?php selected( $instance['a_avatar'], '1' ); ?>><?php _e('Enable','gabfire-widget-pack'); ?></option>
			<option value="0" <?php selected( $instance['a_avatar'], '0' ) ; ?>><?php _e('Disable','gabfire-widget-pack'); ?></option>	
		</select>
	</p>	
		
	<p>
		<label for="<?php echo $this->get_field_id('a_text'); ?>"><?php _e('About Text','gabfire-widget-pack'); ?></label>
		<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('a_text'); ?>" name="<?php echo $this->get_field_name('a_text'); ?>"><?php echo esc_attr($instance['a_text']); ?></textarea>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('a_imgurl'); ?>"><?php _e('URL to Display a Custom Image','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_imgurl'); ?>" name="<?php echo $this->get_field_name('a_imgurl'); ?>" type="text" value="<?php echo esc_attr($instance['a_imgurl']); ?>" />
	</p>	
	
	<p>
		<label for="<?php echo $this->get_field_id( 'a_align' ); ?>"><?php _e('If a Custom Image Defined - Align Image','gabfire-widget-pack'); ?></label><br />
		<select id="<?php echo $this->get_field_id( 'a_align' ); ?>" name="<?php echo $this->get_field_name( 'a_align' ); ?>" style="width:235px">
			<option value="alignleft" <?php if ( 'alignleft' == $instance['a_align'] ) echo 'selected="selected"'; ?>><?php _e('Left','gabfire-widget-pack'); ?></option>
			<option value="alignright" <?php if ( 'alignright' == $instance['a_align'] ) echo 'selected="selected"'; ?>><?php _e('Right','gabfire-widget-pack'); ?></option>
			<option value="aligncenter" <?php  if ( 'aligncenter' == $instance['a_align'] ) echo 'selected="selected"'; ?>><?php _e('Center','gabfire-widget-pack'); ?></option>            
		</select>
	</p>	

	<p>
		<label for="<?php echo $this->get_field_id('a_link'); ?>"><?php _e('Post or Page ID for link','gabfire-widget-pack'); ?></label>
		<input id="<?php echo $this->get_field_id('a_link'); ?>" name="<?php echo $this->get_field_name('a_link'); ?>" type="text" value="<?php echo esc_attr( $instance['a_link'] ); ?>" />
	</p>
		
	<p>
		<label for="<?php echo $this->get_field_id('a_anchor'); ?>"><?php _e('Link label','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('a_anchor'); ?>" name="<?php echo $this->get_field_name('a_anchor'); ?>" type="text" value="<?php echo esc_attr($instance['a_anchor']); ?>" />
	</p>
<?php
	}
}

function register_gabfire_about() {
	register_widget('gabfire_about');
}

add_action('widgets_init', 'register_gabfire_about');