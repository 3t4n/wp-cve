<?php

/*-----------------------------------------------------------------------------------*/
/* Only add js on new post type admin page */
/*-----------------------------------------------------------------------------------*/

function gpp_gallery_js_dom( $gpp_gallery_meta_box ) {

global $gpp_gallery_meta_box;

	if( ( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'gallery' ) || ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'gallery' ) ) {

		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){	';

		foreach( $gpp_gallery_meta_box['fields'] as $field ) {
			if( $field['type'] == 'image' ) {
				$id = $field['id'];

			echo "	$('#" . $id . "_button').click(function(e) {

						var file_frame;
						var hiddenids = '';
						// Create the media frame.
						file_frame = wp.media.frames.file_frame = wp.media({
							title: 'Create Gallery',
							button: {
								text: 'Insert Selected Images',
							},
							multiple: 'add'  // Set to true to allow multiple files to be selected
						});

						// open the modal
						file_frame.open();

						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							jQuery('#gallerythumbs').html('');
							jQuery('#gpp_gallery_hiddenids').val();
							var selection = file_frame.state().get('selection');
							selection.map( function( attachment ) {
								attachment = attachment.toJSON();
								var aaa = new String(attachment);
								hiddenids += attachment.id+',';
								jQuery('#gallerythumbs').append('<img style=\"cursor:pointer;height:60px;width:auto;margin:5px 5px 0 0;\" class=\'eachthumbs\' src=\"'+attachment.url+'\"/>');
							});

							jQuery('#gpp_gallery_hiddenids').val(hiddenids.substring(0, hiddenids.length - 1));
						});

						return false;
					});

					$('#gallerythumbs').on('click', '.eachthumbs',  function(){ // edit gallery
						var file_frame, selection;
						var hiddenids = '';

						var hiddenids = jQuery('#gpp_gallery_hiddenids').val();
						var gallerysc = '[gallery ids=' + hiddenids + ']';
						file_frame = wp.media.gallery.edit( gallerysc ); // need to replace [gallery] with actual shortcode
						file_frame.on( 'update', function(selection) {
							jQuery('#gallerythumbs').html('<img src=\"" . site_url() . "/wp-includes/images/wpspin.gif" . "\" />');
							var addedgallery = wp.media.gallery.shortcode( selection ).string();
							var idarray = addedgallery.split('=\"');
							datanew = idarray[1].substring(0, idarray[1].length - 2);

							jQuery.post(ajaxurl, {action: 'gpp_imageurl', ids: datanew, pid: $('#post_ID').val()}, function(response) {
								jQuery('#gpp_gallery_hiddenids').val(datanew);
								jQuery('#gallerythumbs').html(response);
							});

						});

						return false;
				});";

		  	} // end if
		} // end foreach

		echo '});
		</script>';

	}
}

add_action( 'admin_head', 'gpp_gallery_js_dom' );




/**
* Ajax
*/

add_action( 'wp_ajax_gpp_imageurl', 'gpp_imageurl_callback' );

function gpp_imageurl_callback() {
	$ids = $_POST[ 'ids' ];
	$pid = $_POST[ 'pid' ];
	global $post;
	update_post_meta( $pid, "gpp_gallery_hiddenids", $ids );

	$image_ids = explode( ",", $ids );
	$all_images = "";
	foreach( $image_ids as $image_id ){
		$image_attributes = wp_get_attachment_image_src( $image_id, 'large'); // returns an array
		$all_images .= "<img style=\"cursor:pointer;height:60px;width:auto;margin:5px 5px 0 0;\" class=\"eachthumbs\" src=\"".$image_attributes[0]."\"/>";
	}
	echo $all_images;
	die();
}



/*-----------------------------------------------------------------------------------*/
/* Add gallery css on new post type template page */
/*-----------------------------------------------------------------------------------*/

function gpp_gallery_stylesheet() {
    $gpp_gallery_style = GPP_GALLERY_PLUGIN_URL . '/css/style.css';
    $gpp_gallery_file = GPP_GALLERY_PLUGIN_DIR . '/css/style.css';
    if ( file_exists( $gpp_gallery_file ) ) {
        wp_register_style( 'gpp_gallery', $gpp_gallery_style );
        wp_enqueue_style( 'gpp_gallery' );
   }
}

add_action( 'wp_print_styles', 'gpp_gallery_stylesheet' );

/*-----------------------------------------------------------------------------------*/
/* Add css to determine the width of the page and slideshow */
/*-----------------------------------------------------------------------------------*/

/* function gpp_gallery_slideshow_width() {
  global $content_width;
	if ( ! isset( $content_width ) )
	    $content_width = 620;
	$caption_width = $content_width - 40;
	echo '<style type="text/css">';
	echo '.gpp_slideshow_wrapper {width: '.$content_width.'px}';
	echo '.flexslider .slides li .flex-caption {width: '.$caption_width.'px}';
	echo '</style>';
}

add_action('wp_head', 'gpp_gallery_slideshow_width');
*/

/*-----------------------------------------------------------------------------------*/
/* Add slideshow javascript to front end */
/*-----------------------------------------------------------------------------------*/

if ( !is_admin() )
	add_action( 'init', 'gpp_gallery_load_scripts' );

function gpp_gallery_load_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'flex-slider', plugins_url( 'js/jquery.flexslider-min.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'fader', plugins_url( 'js/jquery.fader.js', __FILE__ ), array( 'jquery' ) );
}

// Load Dom Ready Javascripts
add_action('wp_head', 'gpp_gallery_dom_ready_js');
function gpp_gallery_dom_ready_js() {

	$doc_ready_script = "";
	$args = array( 'numberposts' => -1, 'post_type' => 'gallery' );
	$gallery_posts = get_posts( $args );
		foreach( $gallery_posts as $post ) : setup_postdata( $post );
			$pid = $post->ID;
			$pids = $pid.',';
		endforeach;

	$gpp = get_option( 'gpp_gallery' );
	$gallery_time = $gpp['time'];
	$gallery_speed = $gpp['speed'];

	if( !$gallery_speed ) { $gallery_speed = "1000"; }
	if( !$gallery_time ) { $gallery_time = "3500"; }

	$gallery_captions = false;
	if( isset( $gpp['captions'] ) ) { $gallery_captions = $gpp['captions']; }

	$doc_ready_script .= '
	<script type="text/javascript">
		jQuery(document).ready(function($){';

	$doc_ready_script .= '
			var imgcount = 0;

			$(".flexslider").flexslider({

			     animation: "fade",
			     animationDuration: ' . $gallery_speed . ',
			     slideshowSpeed: ' . $gallery_time . ',
			     pauseOnHover: true,
			     controlNav: true,
			     directionNav: true,
			     manualControls: ".gpp_slideshow_thumbnails li a"

			});';

	if ( $gallery_captions == 1 )
		$doc_ready_script .= '$(".flexslider .slides li .flex-caption").fadeIn(1000);';

	$doc_ready_script .= '
		});
	</script>';

	echo $doc_ready_script;

}