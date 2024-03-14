<?php
/*
gMap_search.php, V 1.03, altm, 20.09.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
 */

add_action( 'widgets_init', 'Search_GMap_gpx_load_widgets' );
function Search_GMap_gpx_load_widgets() {
	register_widget( 'GMap_Gpx_Search_Widget' );
}

class GMap_Gpx_Search_Widget extends WP_Widget {

	function GMap_Gpx_Search_Widget() {
         global $wpmu;
		/* Widget settings. */
		$widget_ops = array( 'classname' => GPX_GM_PLUGIN, 'description' => __('Google Maps Search', GPX_GM_PLUGIN) );
		/* Create the widget. */
		$this->WP_Widget( 'Gpx_Search-widget', __('Google Maps Search', GPX_GM_PLUGIN), $widget_ops);
	}

	/*
	 * Front-end display of widget.
	 */
	 function widget( $args, $instance ) {
		global $placesSort, $user_ID, $post, $instance_gmap_gpx;
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) ) 
			$thetitle = $before_title . $title . $after_title;
		$map_address	= "".$instance[ 'map_address' ]."";
		if (!isset($map_address))
			$map_address = _e( 'map search', GPX_GM_PLUGIN ); //default

		if($instance_gmap_gpx != 0){	
		?>
		<div>	
			<?php echo $thetitle; ?> 
			<p><input style="width:60%" type="text" name="goto" id="goto"  value="<?php echo $map_address; ?>" /> <input id="searchlocation" type="submit" value="<?php _e( 'search', GPX_GM_PLUGIN ); ?>" /></p>
		</div>


	<script type="text/javascript">  
		jQuery("#searchlocation").click(function() { 
			var val = jQuery("#goto").value;
			if(val=='<?php _e( 'map search', GPX_GM_PLUGIN ); ?>'){
				return;
			}
			if(val!='')
				gotoGeoLocation(map_0);
			return false;
		});
		jQuery("#goto").keypress(function(e) { 
			if(this.value!='' && e.keyCode == 13)
				gotoGeoLocation(map_0);
			return;
		});
		jQuery("#goto").click(function() { 
			if(this.value=='<?php _e( 'map search', GPX_GM_PLUGIN ); ?>'){
				this.value='';
				return;
			}
		});
	</script>

	
	<?php			
			echo $after_widget;
		}
	}
	/*
	 * Sanitize widget form values as they are saved.
	 */
	 function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['class'] = strip_tags( $new_instance['class'] );
		$instance['map_height'] = strip_tags( $new_instance['map_height'] );
		$instance['map_address'] = strip_tags( $new_instance['map_address'] );
		$instance['visible_if_solo'] = strip_tags( $new_instance['visible_if_solo'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	 function form( $instance ) {
		$defaults = array( 'title' => '', 'class' => '', 'map_height' => '300', 'map_address' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance[ 'title' ];
		$class = $instance[ 'class' ];
		$map_height = $instance[ 'map_height' ];
		$map_address = $instance[ 'map_address' ];
		$visible_if_solo = $instance[ 'visible_if_solo' ];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', GPX_GM_PLUGIN ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'map_address' ); ?>"><?php _e( 'Start geocode', GPX_GM_PLUGIN ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'map_address' ); ?>" name="<?php echo $this->get_field_name( 'map_address' ); ?>" type="text" value="<?php echo esc_attr( $map_address ); ?>" />
		</p>
		<?php 
	}
} // class Widget

?>