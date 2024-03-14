<?php
/**
 * @package Pretty_Pinterest_Pins
 * @version 1.3.1
 */
/*
Plugin Name: Pretty Pinterest Pins
Description: Display your latest pins from Pinterest in your sidebar.
Author: Jodi Wilkinson
Plugin URI: http://wordpress.org/extend/plugins/pretty-pinterest-pins/
Version: 1.3.1
Author URI: http://jodiwilkinson.com
*/

defined('ABSPATH') or die("Cannot access pages directly.");
defined("DS") or define("DS", DIRECTORY_SEPARATOR);
add_action( 'widgets_init', create_function( '', 'register_widget("Pretty_Pinterest_Pins");' ) );

class Pretty_Pinterest_Pins extends WP_Widget{

	function Pretty_Pinterest_Pins(){
		parent::WP_Widget( $id = 'Pretty_Pinterest_Pins', $name = get_class($this), $options = array( 'description' => 'Display latest pins from your Pinterest.com Account' ) );
	}

	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Latest Pins on Pinterest', 'pinterest_username' => '', 'number_of_pins_to_show' => '3', 'show_pinterest_caption' => '1', 'show_follow_button' => '1', 'specific_board' => '') );
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
			$number_of_pins_to_show = esc_attr( $instance[ 'number_of_pins_to_show' ] );
			$pinterest_username = esc_attr( $instance[ 'pinterest_username' ] );
			$specific_board = esc_attr( $instance[ 'specific_board' ] );	
			$show_pinterest_caption = esc_attr( $instance[ 'show_pinterest_caption' ] );
			$show_follow_button = esc_attr( $instance[ 'show_follow_button' ] );			
		}		
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('pinterest_username'); ?>"><?php _e('Pinterest Username:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('pinterest_username'); ?>" name="<?php echo $this->get_field_name('pinterest_username'); ?>" type="text" value="<?php echo $pinterest_username; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('specific_board'); ?>"><?php _e('Specific Board (optional):'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('specific_board'); ?>" name="<?php echo $this->get_field_name('specific_board'); ?>" type="text" value="<?php echo $specific_board; ?>" />
		</p>		
		<p>
		<label for="<?php echo $this->get_field_id('number_of_pins_to_show'); ?>"><?php _e('Number of Pins To Show:'); ?></label>		
		<select name="<?php echo $this->get_field_name( 'number_of_pins_to_show' );?>">
		<?php 
		for ( $i = 1; $i <= 25; ++$i ){?>
			<option value="<?php echo $i;?>" <?php selected( $number_of_pins_to_show, $i );?>><?php echo $i;?></option>
		<?php
		}
		?>		
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('show_pinterest_caption'); ?>"><?php _e('Show Caption?:'); ?></label>
		<input type="checkbox" name="<?php echo $this->get_field_name('show_pinterest_caption')?>" value="1" <?php checked( $show_pinterest_caption, 1 ); ?> />	
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('show_follow_button'); ?>"><?php _e('Show "Follow Me" Button?:'); ?></label>
		<input type="checkbox" name="<?php echo $this->get_field_name('show_follow_button')?>" value="1" <?php checked( $show_follow_button, 1 ); ?> />	
		</p>
		
		<?php
	}

	function update($new_instance, $old_instance){
		$instance = wp_parse_args( $old_instance, $new_instance );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number_of_pins_to_show'] = strip_tags($new_instance['number_of_pins_to_show']);
		$instance['pinterest_username'] = strip_tags($new_instance['pinterest_username']);
		$instance['specific_board'] = strip_tags($new_instance['specific_board']);
		$instance['show_pinterest_caption'] = strip_tags($new_instance['show_pinterest_caption']);
		$instance['show_follow_button'] = strip_tags($new_instance['show_follow_button']);
		return $instance;
	}

	function widget($args, $instance){
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );		
		echo $before_widget;		
		$title = ( $title ) ? $title : 'Latest Pins on Pinterest';		
		echo $before_title . $title . $after_title;
		
		if( !empty( $instance['pinterest_username'] ) ) {		
		//determine how many pins they want to display and pull from rss feed
		if ( !empty( $instance['number_of_pins_to_show'] )  && is_int( $instance['number_of_pins_to_show'] ) ) {
			$number_of_pins_to_show = esc_attr ( $instance['number_of_pins_to_show'] );
		} else {
			$number_of_pins_to_show = 3;
		}
		if( !empty( $instance['specific_board'] ) ) {	
			$feed_url = 'https://pinterest.com/'.$instance['pinterest_username'].'/'.$instance['specific_board'].'.rss';
		} else {
			$feed_url = 'https://pinterest.com/'.$instance['pinterest_username'].'/feed.rss';	
		}
		
		//fetch rss
		$latest_pins = $this->pretty_pinterest_pins_get_rss_feed( $instance['pinterest_username'], $instance['number_of_pins_to_show'], $feed_url );
		?>
		<style>
		ul#pretty-pinterest-pins-widget{
			list-style-type: none;
		}
		
		ul#pretty-pinterest-pins-widget li.pretty-pinterest-pin, ul#pretty-pinterest-pins-widget li.pretty-pinterest-follow-me{
			position: relative;
			margin: 0px 0px 10px 0px;
			list-style-type: none;
			list-style-image: none;
			background: none;
		}
		
		ul#pretty-pinterest-pins-widget li.pretty-pinterest-pin div.pretty-pinterest-image{
			background-color: #FFFFFF;
			box-shadow: 0 1px 2px rgba(34, 25, 25, 0.4);
			font-size: 11px;
			text-align: center;
			margin: 0px;
			max-width: 100%;
			width: 150px;			
		}		
		
		ul#pretty-pinterest-pins-widget li.pretty-pinterest-pin div.pretty-pinterest-image img{
			width: 100%;
			text-align: left;
			margin: 0px;
		}		
		
		ul#pretty-pinterest-pins-widget li.pretty-pinterest-pin div.pretty-pinterest-image a{
			display: block;
			background: none;
			padding: 15px 15px 13px 15px;
			margin: 0px;
		}
		
		ul#pretty-pinterest-pins-widget li.pretty-pinterest-pin span{
			display: block;
			padding: 0px;
			margin: 0px;
			text-align: left;
			line-height: 16px;
			background-color: #F2F0F0;
		}
		
		ul#pretty-pinterest-pins-widget li.pretty-pinterest-pin span p{
			padding: 4px;
			margin: 0px;
			text-align: center;
			line-height: 14px;
			background-color: #F2F0F0;
			color: #333;
		}
		</style>		
		<ul id="pretty-pinterest-pins-widget">			
		<?php 
			if(!empty( $latest_pins ) ){
				foreach ( $latest_pins as $item ):
					$rss_pin_description = $item->get_description();			
					preg_match('/<img[^>]+>/i', $rss_pin_description, $pin_image); 
					$pin_caption = $this->trim_text( strip_tags( $rss_pin_description ), 400 );
					?>
				<li class="pretty-pinterest-pin">
					<div class="pretty-pinterest-image">
						<a href="<?php echo esc_url( $item->get_permalink() ); ?>" title="<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>"><?php echo $pin_image[0];?></a>
						<?php if ( $instance['show_pinterest_caption'] ){?>
						<span><p><?php echo strip_tags( $pin_caption ); ?></p></span>
						<?php }?>
					</div>
				</li>
				<?php endforeach; 
			}
			if( $instance['show_follow_button'] ){
			?>
			<li class="pretty-pinterest-follow-me"><a href="https://pinterest.com/<?php echo $instance['pinterest_username'];?>/" target="_blank"><img src="https://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" /></a></li>
			<?php
			}
			?>		
		</ul>				
		<?php		
		}		
		echo $after_widget;
	}
	
	function pretty_pinterest_pins_get_rss_feed( $pinterest_username, $number_of_pins_to_show, $feed_url ){				
		// Get a SimplePie feed object from the specified feed source.		
		$rss = fetch_feed( $feed_url );
		if (!is_wp_error( $rss ) ) : 
			// Figure out how many total items there are, but limit it to number specified
			$maxitems = $rss->get_item_quantity( $number_of_pins_to_show ); 
			$rss_items = $rss->get_items( 0, $maxitems ); 
		endif;		
		return $rss_items;
	}
	
	function trim_text( $text, $length ) {
		//strip html
		$text = strip_tags( $text );	  
		//no need to trim if its shorter than length
		if (strlen($text) <= $length) {
			return $text;
		}		
		$last_space = strrpos( substr( $text, 0, $length ), ' ');
		$trimmed_text = substr( $text, 0, $last_space );		
		$trimmed_text .= '...';	  
		return $trimmed_text;
	}


}
add_filter( 'wp_feed_cache_transient_lifetime', create_function('$rssLife', 'return 500;') );

?>