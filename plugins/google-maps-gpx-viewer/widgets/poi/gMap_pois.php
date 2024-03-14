<?php
/*
gMap_pois.php, V 1.06, altm, 22.11.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
 */


add_action( 'widgets_init', 'Poi_Widget_gpx_load_widgets' );
function Poi_Widget_gpx_load_widgets() {
	register_widget( 'Poi_Widget' );
}

class Poi_Widget extends WP_Widget {

	/**
	 * PHP4 compatibility layer for calling the PHP5 constructor.
	 */
	function Poi_Widget() {
		return $this->__construct();
	}
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
	 		'Poi_Widget', // Base ID
			'Google Maps POIs', // Name
			array( 'description' => __( 'Google Maps POIs', GPX_GM_PLUGIN ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		global $placesSort, $current_user, $post, $instance_gmap_gpx;
		extract( $args );
		$post_ID =  $post->ID; 
		if (!get_post_meta( $post->ID, 'gmap_gpx_map_switch', true))
			return;
		$cu_caps = $current_user->caps;

		$cap = get_post_meta( $post->ID, 'poidb_access_key', true );	
		// $cap = $instance[ 'poidb_access_key' ];
		// echo "$cap-";
		
		$capablility = 6; // default no access
		if(array_key_exists ('administrator', $cu_caps) && $cu_caps['administrator'] == 1 )
			$capablility = 1;
		else if(array_key_exists ('editor', $cu_caps) && $cu_caps['editor'] == 1 )
			$capablility = 2;
		else if(array_key_exists ('author', $cu_caps) && $cu_caps['author'] == 1 )
			$capablility = 3;
		else if(array_key_exists ('contributor', $cu_caps) && $cu_caps['contributor'] == 1 )
			$capablility = 4;
		else if(array_key_exists ('subscriber', $cu_caps) && $cu_caps['subscriber'] == 1 )
			$capablility = 5;

		// echo "$cap-$capablility";	
		$access = false;
		if($instance_gmap_gpx == 0 && get_post_meta( $post->ID, 'gmap_gpx_map_switch', true ) == 'on'){
				global $wpdb, $gmap_gpx_table_name; 
				$res = $wpdb->get_results("SELECT * FROM $gmap_gpx_table_name WHERE post_id = '$post_ID'");
				if(count($res) > 0)
					$address = "";
				else
					$address = "Germany";
				echo $before_widget;
				$map_height = get_post_meta( $post->ID, 'gmap_gpx_map_height', true );
				if(is_int(is_numeric($map_height)))
					$map_height = 250;
				$map_title = apply_filters( 'widget_title', get_post_meta( $post->ID, 'gmap_gpx_map_title', true ) );
				if ( ! empty( $map_title ) ) 
					echo $before_title . $map_title . $after_title;
				$attr = array('lat'   => '', 'lon'    => '',	'z' => '', 'x' => 0, 'y' => 0,
								'maptype' => ''.get_option("gmap_v3_gpx_defMaptype", "TERRAIN").'',
								'address' => ''.$address.'', 'marker' => '', 'markerimage' => '', 'infowindow' => '', 'mtoggle' => '',
								'gpx' => '', 'kml' => '', 'elevation' => '', 'download' => '',
								'traffic' => 'no', 'bike' => 'no', 'fusion' => '', 'pano' => '', 'panotag' => '',  'places' => '',  'p_search' => '',
								'style' => 'width:auto; margin:0px; height:'.$map_height.'px; border:1px solid gray;'
							);	
				echo gmapv3($attr);
				echo $after_widget;
				$access = true;
			// }
		}
		else if($instance_gmap_gpx != 0)
			$access = true;


		if ($access && ($capablility <= $cap )) { 
			echo $before_widget;
			
			$title = apply_filters( 'widget_title', $instance['title'] );
			if ( ! empty( $title ) ) 
				$thetitle = $before_title . $title . $after_title;
				
			$marker_manager	= "".$instance[ 'marker_manager' ]."";
			
			?>
			<style type="text/css"> .red_border{padding:13px; border:2px solid red;}</style>			

			<?php echo $thetitle; ?>

 
				<div id="gpx_poi-widget">	
					<form id="wp_gpx_wdg_form" action="" method="get" onsubmit="return checkInput(this);">
					<p><input  type="text" name="item_name" id="item_name"  value="<?php _e( 'Place name', GPX_GM_PLUGIN ); ?>" onclick="if(this.value=='<?php _e( 'Place name', GPX_GM_PLUGIN ); ?>'){this.value=''; return;}" /></p>
					<input type="hidden" id="gmap_poi_act_map" value="0" />
					<p><input  type="text" name="city" id="city" value="<?php _e( 'Zip & City', GPX_GM_PLUGIN ); ?>" onclick="if(this.value=='<?php _e( 'Zip & City', GPX_GM_PLUGIN ); ?>'){this.value=''; jQuery('#poi_click').attr('value', 0); return;}" /></p>
					<p><input  type="text" name="street" id="street" value="<?php _e( 'Street & No.', GPX_GM_PLUGIN ); ?>"  onclick="if(this.value=='<?php _e( 'Street & No.', GPX_GM_PLUGIN ); ?>'){this.value=''; jQuery('#poi_click').attr('value', 0); return;}"/></p>
					<p><input  type="text" name="contact" id="contact"  value="<?php _e( 'Contact person, phone', GPX_GM_PLUGIN ); ?>" onclick="if(this.value=='<?php _e( 'Contact person, phone', GPX_GM_PLUGIN ); ?>'){this.value='';return;}" /></p>
					<p><input  type="text" name="item_url" id="item_url"  value="http://" /><br><?php _e( 'Website, Video or Audio URL', GPX_GM_PLUGIN ); ?></p>
					<p><select id="item_type" name="item_type"">
						<?php 
						// subject to change: use a iconlist select box instead of filenames...
						$iconsdir= PLUGIN_ROOT . "/img/gmapIcons/" ; 
						$iconsurl= plugins_url( GPX_GM_PLUGIN ) . "/img/gmapIcons/" ;
						$subdir =$instance['iconsdir'];
						if ($dhsub = opendir($iconsdir.$instance['iconsdir'])) {
							$iconUrls = Array();
							while (($file = readdir($dhsub)) !== false) {
								if(!is_dir($iconsdir.$subdir.'/'.$file)){
									$iconUrls[] = $iconsurl.$subdir.'/'.$file;
								}
							}							
							closedir($dhsub);
						asort($iconUrls);
							foreach ($iconUrls as $icon => $value) {
								$query = explode(".",  $value);
								$query = $query[count($query)-2];
								$query = explode("/",  $query);
								$title = $query[count($query)-1];
								?>
								<option id="<?php echo $title ?>" value="<?php echo $subdir.'/'.$title ?>"><?php echo  $title ?></option>
								<?php		
							}
						}					
						?></select><img id ="item_type_img" src="" alt="" style="float:right;"><br><?php _e( 'Icon select', GPX_GM_PLUGIN ); ?></p>
					<p><textarea  type="text" name="description" id="description" onclick="if(this.value=='<?php _e( 'Description', GPX_GM_PLUGIN ); ?>'){this.value='';return;}"><?php _e( 'Description', GPX_GM_PLUGIN ); ?></textarea></p>
					<p><input type="button" id="sendtbtn" name="sendtbtn" value="<?php _e( 'Save', GPX_GM_PLUGIN ); ?>" onclick="checkInput(this);" /> <input type="button" style="'visibility:hidden;" id="delbutton" name="delbutton" value="<?php _e( 'Delete', GPX_GM_PLUGIN ); ?>" onclick="deletePoi();" /></p>
					<p><small style="float:right; color:red;" id="result"><!-- To hold validation results --></small></p>
					<input type="hidden" name="post_id" value="<?php echo $post_ID; ?>" />
					<input type="hidden" name="lat" id="lat" value="" /><input type="hidden" name="lng" id="lng" value="" />
					<input type="hidden" name="new_lat" id="new_lat" value="" /><input type="hidden" name="new_lng" id="new_lng" value="" />
					<input type="hidden" name="poi_db_id" id="poi_db_id" value="" />
					<input type="hidden" name="download" id="download" value="1" />
					<input type="hidden" name="poi_click" id="poi_click" value="" />
					<input type="hidden" name="action" value="gmap_poi_action" />
					<input type="hidden" id="gmap_poi_action_map" value="" />
					
					</form> 
				</div>
	<?php 
	
		require_once ( WIDGET_ROOT . "/poi/gm_pois_admin.php"); // js admin functions
		echo $after_widget;

	?>

	<?php } else { 
	// client functions
	?>
	<script type="text/javascript">  
		function checkInput(ele){
			alert ('<?php _e( 'Registered users only!', GPX_GM_PLUGIN ); ?>');
		}
		jQuery("#delbutton").click(function() { 
			jQuery.ajax({
				type: "POST",
				url:  "<?php echo "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
				// data: input_data,
				success: function(msg){
					alert ('<?php _e( 'Registered users only!', GPX_GM_PLUGIN ); ?>');
				},
				complete:function (jqXHR, textStatus){
					/* enable for error check in loading gpx*/
					// if(textStatus != "success")
						// alert('Error: ' + jqXHR.responseText + ' + ' + textStatus);	 			
				}    
			});
			return false;
		
		});

	</script>
	<?php } ?>
	<?php			
	} // widget end

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['map_title'] = strip_tags( $new_instance['map_title'] );
		$instance['map_height'] = strip_tags( $new_instance['map_height'] );
		$instance['iconsdir'] = strip_tags( $new_instance['iconsdir'] );
		$instance['poidb_access_key'] = strip_tags( $new_instance['poidb_access_key'] );
		$instance['marker_manager'] = strip_tags( $new_instance['marker_manager'] );
		$instance['visible_if_solo'] = strip_tags( $new_instance['visible_if_solo'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => '', 'iconsdir' => 'all-mixed', 'map_title' => '', 'map_height' => '250', 'poidb_access_key' => '1', 'marker_manager' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance[ 'title' ];
		$map_title = $instance[ 'map_title' ];
		$map_height = $instance[ 'map_height' ];
		$selected_dir = $instance[ 'iconsdir' ];
		$poidb_access_key = $instance[ 'poidb_access_key' ];
		$marker_manager = $instance[ 'marker_manager' ];
		$visible_if_solo = $instance[ 'visible_if_solo' ];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', GPX_GM_PLUGIN ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'iconsdir' ); ?>"><?php _e( 'Icons folder to use', GPX_GM_PLUGIN ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'iconsdir' ); ?>"  name="<?php echo $this->get_field_name( 'iconsdir' ); ?>" size="1">
			<?php makeIconList($selected_dir); ?>
		</select>
		</p>	

		<a href="http://www.atlsoft.de/poi-database/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a>
		<?php 
	}
} // class Poi_Widget

 function makeIconList($selected_dir){
	$iconsdir= PLUGIN_ROOT . "/img/gmapIcons/" ; 
	$iconsurl= plugins_url( GPX_GM_PLUGIN ) . "/img/gmapIcons/" ;
	$iconDirs = Array();
	$iconUrls = Array();
 	if (is_dir($iconsdir)) {
		if ($dh = opendir($iconsdir)) {
			while (($subdir = readdir($dh)) !== false) {
				if (is_dir($iconsdir.$subdir) &&  $subdir != "." && $subdir != ".."){
					array_push($iconDirs, $subdir);
				}
			}
			closedir($dh);
			sort($iconDirs);
			foreach ($iconDirs as $entry => $subdir) {
				?>
				<option<?php if($subdir == $selected_dir) {echo ' selected="selected"';}	?> ><?php echo $subdir;?></option>
				<?php		

			}
		}
	} 
}
?>