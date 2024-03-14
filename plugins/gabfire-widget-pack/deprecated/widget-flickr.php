<?php
if ( !defined('ABSPATH')) exit;

class gabfire_flickrrss extends WP_Widget {

	function gabfire_flickrrss() {
		$widget_ops = array( 'classname' => 'gabfire_flickr_widget', 'description' => 'Display flickr photos on your site' );
		$control_ops = array( 'width' => 330, 'height' => 350, 'id_base' => 'gabfire_flickr_widget' );
		parent::__construct( 'gabfire_flickr_widget', 'Gabfire: Flickr Images', $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title 			= apply_filters('widget_title', $instance['title'] );
		$photo_source 	= $instance['photo_source'];
		$flickr_id 		= $instance['flickr_id'];
		$flickr_tag 	= $instance['flickr_tag'];
		$display 		= $instance['display'];
		$text 			= $instance['text'];
		$size 			= $instance['size'];
		$photo_number 	= $instance['photo_number'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if ( $text ) {
				echo wpautop($text);
			}			

			echo '
				<script type="text/javascript" src="//www.flickr.com/badge_code_v2.gne?count='; 
				if ( $photo_number ) {
					printf( '%1$s', esc_attr( $photo_number ) ); echo '&amp;display=';
				}
				if ( $display )  {
					printf( '%1$s', esc_attr( $display ) ); echo '&amp;layout=x&amp;';
				}
				
				if ( $instance['photo_source'] == 'user' ) { 
					printf( 'source=user&amp;user=%1$s', esc_attr( $flickr_id ) );
				}
				elseif ( $instance['photo_source'] == 'group' ) {
					printf( 'source=group&amp;group=%1$s', esc_attr( $flickr_id ) );
				}
				if  ( $instance['photo_source'] == 'all_tag' ) {
					printf( 'source=all_tag&amp;tag=%1$s', esc_attr( $flickr_tag ) ); 
				}

				echo '&amp;size=';

				if ( $size )  {
					printf( '%1$s', esc_attr( $size ) ); echo '"></script>';
				}
				
			echo '<div class="clear clearfix"></div>';
			
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['text']         = strip_tags( $new_instance['text'] );
		$instance['photo_number'] = (int)( $new_instance['photo_number'] );
		$instance['photo_source'] = strip_tags( $new_instance['photo_source'] );
		$instance['flickr_id']    = strip_tags( $new_instance['flickr_id'] );
		$instance['flickr_tag']   = strip_tags( $new_instance['flickr_tag'] );
		$instance['display']      = strip_tags( $new_instance['display'] );
		$instance['size']         = strip_tags( $new_instance['size'] );
		
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'title' => 'Flickr Photo Stream',
			'flickr_id' => '',
			'text' => '',
			'photo_source' => 'all_tag',
			'display' => 'latest',
			'photo_number' => '6',
			'size' => 's',
			'flickr_tag' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		if (isset($items)) 
			$items  = (int) $items;
		else 
			$items = 0;
			
		if (isset($items) && $items < 1 || 10 < $items )
		$items  = 10;
		?>
		
		<div class="controlpanel">
		
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text to display before Flickr Images','gabfire-widget-pack'); ?></label>
				<textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($instance['text']); ?></textarea>
			</p>
				
			
			<p>
				<label for="<?php echo $this->get_field_id( 'photo_source' ); ?>"><?php _e('Image Source','gabfire-widget-pack'); ?></label> 
				<select id="<?php echo $this->get_field_id( 'photo_source' ); ?>" name="<?php echo $this->get_field_name( 'photo_source' ); ?>">
					<option value="user" <?php if ( 'user' == $instance['photo_source'] ) echo 'selected="selected"'; ?>><?php _e('User','gabfire-widget-pack'); ?></option>
					<option value="group" <?php if ( 'group' == $instance['photo_source'] ) echo 'selected="selected"'; ?>><?php _e('Group','gabfire-widget-pack'); ?></option>
					<option value="all_tag" <?php  if ( 'all_tag' == $instance['photo_source'] ) echo 'selected="selected"'; ?>><?php _e('All Users Photos (based on tags)','gabfire-widget-pack'); ?></option>			
				</select>
			</p>
			
			<div rel="flickr_id">
				<p>
					<label for="<?php echo $this->get_field_id( 'flickr_id' ); ?>"><?php _e('User or Group ID','gabfire-widget-pack'); ?> <a href="//idgettr.com/"><?php _e('Get your Flickr ID','gabfire-widget-pack'); ?></a></label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'flickr_id' ); ?>" name="<?php echo $this->get_field_name( 'flickr_id' ); ?>" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" class="widefat" />
				</p>
			</div>
			
			<div rel="flickr_tag">
				<p>
					<label for="<?php echo $this->get_field_id( 'flickr_tag' ); ?>"><?php _e('Tags (separate with comma) (only if "All Users Photos" selected)','gabfire-widget-pack'); ?></label>
					<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'flickr_tag' ); ?>" name="<?php echo $this->get_field_name( 'flickr_tag' ); ?>" value="<?php echo esc_attr( $instance['flickr_tag'] ); ?>" class="widefat" />
				</p>
			</div>

			<p>
				<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e('Display Latest or Random Photos','gabfire-widget-pack'); ?></label> 
				<select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
					<option value="latest" <?php selected( $instance['display'], 'latest' ); ?>><?php _e('Latest','gabfire-widget-pack'); ?></option>
					<option value="random" <?php selected( $instance['display'], 'random' ); ?>><?php _e('Random','gabfire-widget-pack'); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_name( 'photo_number' ); ?>"><?php _e('How many items would you like to display?','gabfire-widget-pack'); ?></label>
				<select id="<?php echo $this->get_field_id( 'photo_number' ); ?>" name="<?php echo $this->get_field_name( 'photo_number' ); ?>">			
				<?php
					for ( $i = 1; $i <= 10; ++$i )
					echo "<option value='$i' " . selected( $instance['photo_number'], $i, false ) . ">$i</option>";
				?>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e('Photo Size','gabfire-widget-pack'); ?></label> 
				<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
					<option value="s" <?php selected( $instance['size'], 's' ); ?>><?php _e('Small','gabfire-widget-pack'); ?></option>
					<option value="t" <?php selected( $instance['size'], 't' ); ?>><?php _e('Thumbnail','gabfire-widget-pack'); ?></option>
					<option value="m" <?php  selected( $instance['size'], 'm' ); ?>><?php _e('Medium','gabfire-widget-pack'); ?></option>
				</select>
			</p>
		</div>
		
	<?php
	}
}

function register_gabfire_flickrrss() {
	register_widget('gabfire_flickrrss');
}

add_action('widgets_init', 'register_gabfire_flickrrss');