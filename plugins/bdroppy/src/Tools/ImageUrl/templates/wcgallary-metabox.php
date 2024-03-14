<?php 
// Featured Image by URL metabox Template

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function bdroppy_get_gallary_slot( $image_url = '' ){
	ob_start();
	?>
	<div id="bdroppy_wcgallary__COUNT__" class="bdroppy_wcgallary">
		<div id="bdroppy_url_wrap__COUNT__" <?php if( $image_url != ''){ echo 'style="display: none;"'; } ?>>
			<input id="bdroppy_url__COUNT__" class="bdroppy_url" type="text" name="bdroppy_wcgallary[__COUNT__][url]" placeholder="<?php _e('Image URL', 'featured-image-by-url') ?>" data-id="__COUNT__" value="<?php echo $image_url; ?>"/>
			<a id="bdroppy_preview__COUNT__" class="bdroppy_preview button" data-id="__COUNT__">
				<?php _e( 'Preview', 'featured-image-by-url' ); ?>
			</a>
		</div>
		<div id="bdroppy_img_wrap__COUNT__" class="bdroppy_img_wrap" <?php if( $image_url == ''){ echo 'style="display: none;"'; } ?>>
			<span href="#" class="bdroppy_remove" data-id="__COUNT__"></span>
			<img id="bdroppy_img__COUNT__" class="bdroppy_img" data-id="__COUNT__" src="<?php echo $image_url; ?>" />
		</div>
	</div>
	<?php
	$gallery_image = ob_get_clean();
	return preg_replace('/\s+/', ' ', trim($gallery_image));
}

?>

<div id="bdroppy_wcgallary_metabox_content" >
	<?php

	$count = 1;
	if( !empty( $gallary_images ) ){
		foreach ($gallary_images as $gallary_image ) {
			echo str_replace( '__COUNT__', $count, bdroppy_get_gallary_slot( $gallary_image['url'] ) );
			$count++;
		}
	}
	echo str_replace( '__COUNT__', $count, bdroppy_get_gallary_slot() );
	$count++;
	?>
</div>
<div style="clear:both"></div>
<script>
	jQuery(document).ready(function($){

		var counter = <?php echo $count;?>;
		// Preview
		$(document).on("click", ".bdroppy_preview", function(e){
						
			e.preventDefault();
			counter = counter + 1;
			var new_element_str = '';
			var id = jQuery(this).data('id');
			imgUrl = $('#bdroppy_url'+id).val();
			
			if ( imgUrl != '' ){
				$("<img>", { // Url validation
						    src: imgUrl,
						    error: function() {alert('<?php _e('Error URL Image', 'featured-image-by-url') ?>')},
						    load: function() {
						    	$('#bdroppy_img_wrap'+id).show();
						    	$('#bdroppy_img'+id).attr('src',imgUrl);
						    	$('#bdroppy_remove'+id).show();
						    	$('#bdroppy_url'+id).hide();
						    	$('#bdroppy_preview'+id).hide();
						    	new_element_str = '<?php echo bdroppy_get_gallary_slot(); ?>';
						    	new_element_str = new_element_str.replace(/__COUNT__/g, counter );
						    	$('#bdroppy_wcgallary_metabox_content').append( new_element_str );
						    }
				});
			}
		});

		$(document).on("click", ".bdroppy_remove", function(e){
			var id2 = jQuery(this).data('id');

			e.preventDefault();
			$('#bdroppy_wcgallary'+id2).remove();
		});

	});
</script>