<?php
/*
gMap_gpxload.php, V 1.00, altm, 28.11.2013
Author: ATLsoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
released under GNU General Public License
 */
 
// check for rights
if ( !defined('ABSPATH'))
    die('You are not allowed to call this page directly.');
	
global $wpdb;
// A list of permitted file extensions
$allowed = array('gpx', 'xml', 'kml', 'kmz');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error gpx only!"}';
		exit;
	}
	$upload_d = wp_upload_dir();
	$subdir = '/';
	$fn;
	$furl;
	if(isset($_POST['upload_path'])) {
		$str = preg_replace('/[^a-z0-9\._-]+/i', '-', $_FILES['upl']['name']);
		$subdir = "/".$_POST['upload_path']."/";
		$fn =   $upload_d['basedir'].$subdir.$str;
		$furl = $upload_d['baseurl'].$subdir.$str;
	} else {
		$fn =   $upload_d['basedir'].$subdir."gpxfile.gpx";
		$furl = $upload_d['baseurl'].$subdir."gpxfile.gpx";
	}
	
	if(move_uploaded_file($_FILES['upl']['tmp_name'], $fn)){
		// echo '{"status":"success"}';
		// echo  file_get_contents($fn);
		echo  $furl	;
		exit;
	}
	echo '{"unknown error"}'.$fn;
	exit;
}

add_action( 'widgets_init', 'GMap_Upload_gpx_widget' );
function GMap_Upload_gpx_widget() {
	register_widget( 'GMap_Gpx_Upload_Widget' );
}

class GMap_Gpx_Upload_Widget extends WP_Widget {

	function GMap_Gpx_Upload_Widget() {
         global $wpmu;
		/* Widget settings. */
		$widget_ops = array( 'classname' => GPX_GM_PLUGIN, 'description' => __('GPX Upload', GPX_GM_PLUGIN) );
		/* Create the widget. */
		$this->WP_Widget( 'Gpx_Upload-Widget', __('GPX Upload', GPX_GM_PLUGIN), $widget_ops);
	}

	/*
	 * Front-end display of widget.
	 */
	 function widget( $args, $instance ) {
		global $current_user, $post, $instance_gmap_gpx;
		extract( $args );
		$cu_caps = $current_user->caps;
		$cap = $instance[ 'access_level' ];
		
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

		if($instance_gmap_gpx != 0 && ($capablility <= $cap )){	
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) ) 
				$thetitle = $before_title . $title . $after_title;
			?>
			<div id="drop" class="gm_gpx_body">	
				<?php echo $thetitle; ?> 
			<form id="upload" method="post"  enctype="multipart/form-data">
			<ul>
				<!-- The file uploads will be shown here -->
			</ul>
			<div>
				<?php _e( 'Drop on the map', GPX_GM_PLUGIN ); ?>

				<a style="cursor:pointer;"><?php _e( 'or browse', GPX_GM_PLUGIN ); ?></a>
				<input type="file" name="upl" style="display:none;" />
				<input type="hidden" name="upload_path" value="<?php echo $instance['upload_path'];?>" />
			</div>


		</form>
		<!-- jQuery File Upload  -->
		<script src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>widgets/upload/assets/js/jquery.ui.widget.js"></script>
		<script src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>widgets/upload/assets/js/jquery.iframe-transport.js"></script>
		<script src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>widgets/upload/assets/js/jquery.fileupload.js"></script>
		</div>
<script  type="text/javascript">

	jQuery(function($){
	
    var ul = $('#upload ul');

    $('#drop a').click(function(){
        $(this).parent().find('input').click();
    });

    $('#upload').fileupload({

        dropZone: $('.gm_gpx_body'),

        success: function (data) {
            $('.working > div').remove();
			var div_id = "#places_wait_" + map_0.getDiv().id;	
			jQuery(div_id).remove();
			var mapLayer = new google.maps.KmlLayer({
				url: data
			});
			mapLayer.setMap(map_0);
			map_0.bbox = null;
		},
        add: function (e, data) {
			var position = $( ".gm_gpx_body" ).position(); 
			var lp = Math.round($( ".gm_gpx_body" ).width() / 2) + 'px';
			var tp = Math.round($( ".gm_gpx_body" ).height() / 2) + 'px';
			var mapId = map_0.getDiv().id;
			var divmap = "#" + mapId;
			var div_wait = "places_wait_" + mapId;	
			$(divmap).append('<div id="'+div_wait+'" class="gm_wait"></div>');
			$('#'+div_wait).css('background','url(' + pluri + 'img/wait.gif) center no-repeat');
			var tpl;
			$('.error').remove();
			if(data.files[0].size > 1024000){
				tpl = $('<li class="error"><?php _e( 'Upload Error: File is more than 1 MB', GPX_GM_PLUGIN ); ?></li>');
				data.context = tpl.appendTo(ul);
				return;
			} else
				tpl = $('<li class="working"><p></p><span></span></li>');

            tpl.find('p').text(data.files[0].name).append(': <i>' + formatFileSize(data.files[0].size) + '</i>');

            data.context = tpl.appendTo(ul);


            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
			var div_id = "#places_wait_" + map_0.getDiv().id;	
			jQuery(div_id).remove();
        }

    });


    // Prevent the default action when a file is dropped on the window
    // $(document).on('drop dragover', function (e) {
        // e.preventDefault();
    // });
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }
        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }
        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }
        return (bytes / 1000).toFixed(2) + ' KB';
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
		$instance['upload_path'] = strip_tags( $new_instance['upload_path'] );
		$instance['class'] = strip_tags( $new_instance['class'] );
		$instance['map_height'] = strip_tags( $new_instance['map_height'] );
		$instance['map_address'] = strip_tags( $new_instance['map_address'] );
		$instance['access_level'] = strip_tags( $new_instance['access_level'] );
		$instance['visible_if_solo'] = strip_tags( $new_instance['visible_if_solo'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	 function form( $instance ) {
		$defaults = array( 'title' => '', 'upload_path' => '', 'map_height' => '300', 'access_level' => '1', 'visible_if_solo' => '');
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = $instance[ 'title' ];
		$upload_path = $instance[ 'upload_path' ];
		$map_height = $instance[ 'map_height' ];
		$visible_if_solo = $instance[ 'visible_if_solo' ];
		$access_level = $instance[ 'access_level' ];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', GPX_GM_PLUGIN ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="access_level"><?php _e( 'User must be at least', GPX_GM_PLUGIN ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'access_level' ); ?>"  name="<?php echo $this->get_field_name( 'access_level' ); ?>" size="1">
		<?php
		echo '<option value="1"'; if ($access_level == "1") echo ' selected="selected"'; echo'>' .__( 'administrator', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="2"'; if ($access_level == "2") echo ' selected="selected"'; echo'>' .__( 'editor', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="3"'; if ($access_level == "3") echo ' selected="selected"'; echo'>' .__( 'author', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="4"'; if ($access_level == "4") echo ' selected="selected"'; echo'>' .__( 'contributor', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="5"'; if ($access_level == "5") echo ' selected="selected"'; echo'>' .__( 'subscriber', GPX_GM_PLUGIN ) . '</option>';
		echo '<option value="6"'; if ($access_level == "6") echo ' selected="selected"'; echo'>' .__( 'everybody', GPX_GM_PLUGIN ) . '</option>';
		?>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'upload_path' ); ?>"><?php _e( 'Upload Path:', GPX_GM_PLUGIN ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'upload_path' ); ?>" name="<?php echo $this->get_field_name( 'upload_path' ); ?>" type="text" value="<?php echo esc_attr( $upload_path ); ?>" />
		<?php _e( 'GPX Uploader. If a Map is active. The uploded gpx-file is displayed onto the map and will be stored.', GPX_GM_PLUGIN ); ?>
		</p>
		<?php 
	}
} // class Widget

?>