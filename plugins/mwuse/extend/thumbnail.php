<?php
function mtw_get_all_image_sizes() {
    global $_wp_additional_image_sizes;

    $default_image_sizes = array( 'thumbnail', 'medium', 'large' );

    foreach ( $default_image_sizes as $size ) {
        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : 0;
    }

    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
    }

    return $image_sizes;
}

function mtw_thumbnail_replacer($dom){

	global $post;
	$mtw_thumbs = mw_dom_getElementsByClass( $dom , "mtw-thumb" );
	global $_all_image_sizes; 
	$_all_image_sizes = mtw_get_all_image_sizes();

	if( $mtw_thumbs->length && !is_admin() )
	{	
			foreach ( $mtw_thumbs as $key => $mtw_thumb ) 
			{
				

				$parentNode = $mtw_thumb->parentNode;
				
				global $folderName;
				
				$data_replacement = TTR_MW_TEMPLATES_URL . $folderName . '/' . $mtw_thumb->getAttribute('data-replacement');			
				$mtw_thumb->setAttribute('data-replacement', $data_replacement);

				$thumb_filter = apply_filters( 'mtw_thumbnail', '', $key, $mtw_thumb );
				if( $thumb_filter != '' )
				{
					$thumb = $thumb_filter;
				}

				if( $mtw_thumb->getAttribute('data-custom') != ' ' && $custom_field = $mtw_thumb->getAttribute('data-custom') )
				{
					$custom_field = do_shortcode( trim( $custom_field ) );;
					$custom_field_meta = get_post_meta( $post->ID, $custom_field, true );
					$thumb = $custom_field_meta;					
				}

				if( $mtw_thumb->getAttribute('data-src_id') )	
				{
					$thumb = $mtw_thumb->getAttribute('data-src_id');
				}			

				if( empty($thumb) && is_object( $post ) )
				{
					$thumb = get_post_thumbnail_id($post->ID);
				}

				if( $thumb )
				{
					$image_id = $thumb;
					$_wp_attachment_metadata = get_post_meta( $image_id, '_wp_attachment_metadata', true );
					if( !$_wp_attachment_metadata )
					{
						continue;
					}
					$sizes = $_wp_attachment_metadata['sizes'];
					$file =  wp_upload_dir()['baseurl'] . '/' . $_wp_attachment_metadata['file'];
					
					$baseurl = str_replace( basename( $file ), "", $file);

					if( !isset( $_wp_attachment_metadata['sizes'] ) || !is_array( @$_wp_attachment_metadata['sizes'] ) )
					{
						$_wp_attachment_metadata['sizes'] = array();
					}
					
					foreach ( $_wp_attachment_metadata['sizes'] as $key => $image_size ) 
					{
						$_wp_attachment_metadata['sizes'][ $key ]['file'] = $baseurl . $_wp_attachment_metadata['sizes'][ $key ]['file'];
						$_wp_attachment_metadata['sizes'][ $key ]['area'] = $_wp_attachment_metadata['sizes'][ $key ]['width'] * $_wp_attachment_metadata['sizes'][ $key ]['height'];
						$_wp_attachment_metadata['sizes'][ $key ]['crop'] = isset( $_all_image_sizes[$key]['crop'] ) ? $_all_image_sizes[$key]['crop'] : 1 ;
					}
					$json_sizes = addslashes( json_encode( $_wp_attachment_metadata['sizes'] ) );

					$parentNode->setAttribute('data-src_id', $thumb);
					$parentNode->setAttribute('data-sizes', $json_sizes);
					$parentNode->setAttribute('data-originalsize', $file);
					$parentNode->setAttribute('data-originalsh', $_wp_attachment_metadata['height']);
					$parentNode->setAttribute('data-originalsw', $_wp_attachment_metadata['width']);

					$thumb = false;
				}
			}
	}
}
add_action( 'DOMDocument_loaded', 'mtw_thumbnail_replacer', 10, 1 );


function mtw_replace_src_id()
{
	?>
	<script type="text/javascript">
	function mtw_stripslashes (str) {
	  //       discuss at: http://locutus.io/php/stripslashes/
	  //      original by: Kevin van Zonneveld (http://kvz.io)
	  //      improved by: Ates Goral (http://magnetiq.com)
	  //      improved by: marrtins
	  //      improved by: rezna
	  //         fixed by: Mick@el
	  //      bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
	  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
	  //         input by: Rick Waldron
	  //         input by: Brant Messenger (http://www.brantmessenger.com/)
	  // reimplemented by: Brett Zamir (http://brett-zamir.me)
	  //        example 1: stripslashes('Kevin\'s code')
	  //        returns 1: "Kevin's code"
	  //        example 2: stripslashes('Kevin\\\'s code')
	  //        returns 2: "Kevin\'s code"
	  return (str + '')
	    .replace(/\\(.?)/g, function (s, n1) {
	      switch (n1) {
	        case '\\':
	          return '\\'
	        case '0':
	          return '\u0000'
	        case '':
	          return ''
	        default:
	          return n1
	      }
	    })
	}

	jQuery(document).ready(function($) {

		function mtw_check_this_thumb( el )
		{

		}

		var mtw_check_best_thumb_size_timer;
		function mtw_check_best_thumbs_size()
		{

			$('[data-src_id]').each(function(index, el) {
				
				target_element = $(el);
				if( target_element[0].nodeName == 'SPAN' )
	    		{
	    			target_element = target_element.parent();
	    		}


				mtw_thumb = {};
				mtw_thumb['src_id'] = $(el).data('src_id');
				mtw_thumb['width'] = target_element.width();
				mtw_thumb['height'] = target_element.height();

				if( $(el).find('.mtw-thumb').attr('data-fixedheight') == 'true' )
				{
					mtw_thumb['crop'] = "1";
				}
				else
				{
					mtw_thumb['crop'] = false;
				}

				if( $(el).data('sizes') != undefined )
				{
					thumb_sizes = jQuery.parseJSON( mtw_stripslashes( $(el).data('sizes') ) );				
					find_image = false;

					var max_area = 9999999999999999999999999999;
					$.each(thumb_sizes, function(i2, val) {

						if( mtw_thumb['crop'] == val['crop']  && val['width'] >= mtw_thumb['width'] &&  val['height'] >= mtw_thumb['height'] && max_area > val['area']  )
						{						
							max_area = val['area'];

							target_element.css('background-image', 'url(' + val['file'] + ')' );
							if( mtw_thumb['crop'] == false && $(el).find('.mtw-thumb').hasClass('mtw-thumb-only-src') == false )
							{
				    			target_element.height( mtw_thumb['width'] * ( val['height'] / val['width'] )  );
				    		}
				    		find_image = true;
						}
					});

					if( find_image == false ) 
					{
						target_element.css('background-image', 'url(' + $(el).data('originalsize') + ')' );
						if( mtw_thumb['crop'] == false && $(el).find('.mtw-thumb').hasClass('mtw-thumb-only-src') == false )
						{
							target_element.height( mtw_thumb['width'] * ( $(el).data('originalsh') / $(el).data('originalsw') )  );
						}
					}
					if( $(el).find('.mtw-thumb').hasClass('mtw-thumb-only-src') == false )
					{
						target_element.css('background-position', 'center center' );
						target_element.css('background-size', 'cover' );
					}
					if( target_element.hasClass('size_browser_width') )
					{
						target_element.parent().height( target_element.height() )
					}
				}
			});

			$('.mtw-thumb').each(function(index, el) {
				if( !$(el).parent().data('src_id') )
				{
					target_element = $(el).parent();
					if( target_element[0].nodeName == 'SPAN' )
		    		{
		    			target_element = target_element.parent();
		    		}

					if( $(el).hasClass('empty_display_none') )
					{
						$(el).parent().hide();
						if( $(el).parent().parent().hasClass('browser_width') )
						{
							$(el).parent().parent().hide();
						}
					}
					if( $(el).hasClass('empty_remplacement') )
					{
						data_replacement = $(el).attr('data-replacement');
						target_element.css('background-image', 'url(' + data_replacement + ')' );
					}
				}
			});	

			$(document).trigger('mtw_size_influence');
		}

		var mtw_check_best_thumb_size_timer;
		$(window).on('mw_force_images_resize resize mtw_muse_bp_change', function(event) {
			mtw_check_best_thumbs_size();

			/*if( event.type == 'load' )
			{
				mtw_check_best_thumb_size();
				clearTimeout( mtw_check_best_thumb_size_timer );
				mtw_check_best_thumb_size_timer = setTimeout( mtw_check_best_thumb_size, 250 );
			}
			else
			{
				clearTimeout( mtw_check_best_thumb_size_timer );
				mtw_check_best_thumb_size_timer = setTimeout( mtw_check_best_thumb_size, 100 );
			}*/
		});		
	});
	</script>
	<?php
}
add_action( 'wp_footer' , 'mtw_replace_src_id' );


function mtw_pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}
add_action('wp_head','mtw_pluginname_ajaxurl');


function mtw_get_best_thumb_size()
{

	$images = json_decode( stripslashes( $_POST['data'] ), true );
	$return = array();
	foreach ($images as $key => $image) 
	{
		$image_id = $image['src_id'];
		$width = $image['width'];
		$height = $image['height'];
		$crop = $image['crop'];

		$mtw_featured_transient = get_transient( 'mtw_featured_transient_'.$image_id );

		if ( !$mtw_featured_transient ) {

			$_wp_attachment_metadata = get_post_meta( $image_id, '_wp_attachment_metadata', true );
			$src = wp_get_attachment_image_src( $image_id, array( $width, $height, $crop ) )[0];
			$basename = basename($src);
			$sizes = $_wp_attachment_metadata['sizes'];
			$new_sizes = array();
			foreach ($sizes as $size) 
			{
				$new_sizes[$size['width']+$size['height']] = $size;
			}
			
			krsort($new_sizes);

			$mtw_featured_transient = array(
				'_wp_attachment_metadata' => $_wp_attachment_metadata,
				'src' => $src,
				'basename' => $basename,
				'new_sizes' => $new_sizes
				);

			set_transient( 'mtw_featured_transient_'.$image_id, $mtw_featured_transient, 60 * 60 * 24 );
		}
		else
		{
			$_wp_attachment_metadata = $mtw_featured_transient['_wp_attachment_metadata'];
			$src = $mtw_featured_transient['src'];
			$basename = $mtw_featured_transient['basename'];
			$new_sizes = $mtw_featured_transient['new_sizes'];
		}

		//$new_sizes = get_transient( 'image_size_'.$image_id );

		foreach ( $new_sizes as $size ) 
		{
			if( $size['width'] >= $width && $size['height'] >= $height )
			{
				$good_size = $size;
			}
		}
		if( $good_size )
		{
			$meta = $good_size;
			$src = str_replace($basename, $good_size['file'], $src);
		}
		else
		{
			$meta = $_wp_attachment_metadata;
		}

		
		$return[$key] = array(
			'src' => $src,
			'meta' => $meta
			);
		$good_size = false;
	}
	die( json_encode( $return ) );

}
add_action( 'wp_ajax_mtw_get_best_thumb_size', 'mtw_get_best_thumb_size' );
add_action( 'wp_ajax_nopriv_mtw_get_best_thumb_size', 'mtw_get_best_thumb_size' );


function mtw_get_best_thumb_size_ajax_fast_return()
{
	if( isset($_POST['action']) && $_POST['action'] == 'mtw_get_best_thumb_size' )
	{
		mtw_get_best_thumb_size();
		exit();
	}
}
add_action( 'init', 'mtw_get_best_thumb_size_ajax_fast_return' );


?>