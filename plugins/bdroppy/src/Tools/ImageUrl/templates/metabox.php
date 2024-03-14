<?php 
// Featured Image by URL metabox Template

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
$image_url = '';
$image_alt = '';
if( isset( $image_meta['img_url'] ) && $image_meta['img_url'] != '' ){
	$image_url = esc_url( $image_meta['img_url'] );
}
if( isset( $image_meta['img_alt'] ) && $image_meta['img_alt'] != '' ){
	$image_alt = esc_attr( $image_meta['img_alt'] );
}
?>

<div id="bdroppy_metabox_content" >

	<input id="bdroppy_url" type="text" name="bdroppy_url" placeholder="<?php _e('Image URL', 'featured-image-by-url') ?>" value="<?php echo $image_url; ?>" />
	<a id="bdroppy_preview" class="button" >
		<?php _e('Preview', 'featured-image-by-url') ?>
	</a>
	
	<input id="bdroppy_alt" type="text" name="bdroppy_alt" placeholder="<?php _e('Alt text (Optional)', 'featured-image-by-url') ?>" value="<?php echo $image_alt; ?>" />

	<div >
		<span id="bdroppy_noimg"><?php _e('No image', 'featured-image-by-url'); ?></span>
			<img id="bdroppy_img" src="<?php echo $image_url; ?>" />
	</div>

	<a id="bdroppy_remove" class="button" style="margin-top:4px;"><?php _e('Remove Image', 'featured-image-by-url') ?></a>
</div>

<script>
	jQuery(document).ready(function($){

		<?php if ( ! $image_meta['img_url'] ): ?>
			$('#bdroppy_img').hide().attr('src','');
			$('#bdroppy_noimg').show();
			$('#bdroppy_alt').hide().val('');
			$('#bdroppy_remove').hide();
			$('#bdroppy_url').show().val('');
			$('#bdroppy_preview').show();
		<?php else: ?>
			$('#bdroppy_noimg').hide();
			$('#bdroppy_remove').show();
			$('#bdroppy_url').hide();
			$('#bdroppy_preview').hide();
		<?php endif; ?>

		// Preview Featured Image
		$('#bdroppy_preview').click(function(e){
			
			e.preventDefault();
			imgUrl = $('#bdroppy_url').val();
			
			if ( imgUrl != '' ){
				$("<img>", {
						    src: imgUrl,
						    error: function() {alert('<?php _e('Error URL Image', 'featured-image-by-url') ?>')},
						    load: function() {
						    	$('#bdroppy_img').show().attr('src',imgUrl);
						    	$('#bdroppy_noimg').hide();
						    	$('#bdroppy_alt').show();
						    	$('#bdroppy_remove').show();
						    	$('#bdroppy_url').hide();
						    	$('#bdroppy_preview').hide();
						    }
				});
			}
		});

		// Remove Featured Image
		$('#bdroppy_remove').click(function(e){

			e.preventDefault();
			$('#bdroppy_img').hide().attr('src','');
			$('#bdroppy_noimg').show();
	    	$('#bdroppy_alt').hide().val('');
	    	$('#bdroppy_remove').hide();
	    	$('#bdroppy_url').show().val('');
	    	$('#bdroppy_preview').show();

		});

	});

</script>