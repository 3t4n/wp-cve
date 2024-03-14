<?php if ( ! defined( 'ABSPATH' ) ) exit; 
$PostId = $data->gallery_id;
?>

<?php 
wp_enqueue_style( "photo_gallery_index_style_3", PHOTO_GALLERY_BUILDER_ASSETS. "css/layout-design.css",'');
?>

<style>
/* ---- isotope ---- */

/* clear fix */
.grid_<?php echo esc_attr($PostId); ?> {
  content: '';
  display: block;
  clear: both;
}

/* ---- .grid-item ---- */

.grid-item_<?php echo esc_attr($PostId); ?> {
  float: left;
}

.grid-item_<?php echo esc_attr($PostId); ?> img {
  display: block;
  max-width: 100%;
}
</style>

	<?php
	if($data->js_config['type']=='custom-grid')
	{ ?>
		<div class="row">
	<?php }	else { ?>
		<div class="row grid" id="grid_<?php echo esc_attr($PostId); ?>">
	<?php } 

		foreach ( $data->images as $image ) {

			$image_object = get_post( $image['id'] );
			if ( is_wp_error( $image_object ) || get_post_type( $image_object ) != 'attachment' ) {
				continue;
			}

			
			// Create array with data in order to send it to image template
			$item_data = array(
				/* Item Elements */
				'title'            => Photo_Gallery_Helper::get_title( $image, $data->settings['wp_field_title'] ),
				'description'      => Photo_Gallery_Helper::get_description( $image, $data->settings['wp_field_caption'] ),
				'lightbox'         => $data->settings['pgb_lightbox'],

				/* What to show from elements */
				'hide_title'       => boolval( $data->settings['hide_title'] ) ? true : false,
				'hide_description' => boolval( $data->settings['hide_description'] ) ? true : false,
				
			);

			$item_data = apply_filters( 'photo_gallery_shortcode_item_data', $item_data, $image, $data->settings, $data->images );
			
			/*--image cropping--*/
			$id=$image['id'];
			$url = wp_get_attachment_image_src($id, 'pgb_image_grid', true);
			$url_masonary = wp_get_attachment_image_src($id, 'pgb_masonary', true);
			
			?>

			<?php
			if($data->js_config['type']=='custom-grid')
			{ ?>
				<div class="col-md-<?php echo esc_attr($data->settings['select_column']); ?> col-sm-6 col-12">
					<div class="layout-3" style="margin: <?php echo esc_attr($data->settings['gutter']) ?>px;" >
						<img src="<?php echo esc_url($url['0']); ?>">
			<?php }	else { ?>
				<div class="col-md-<?php echo esc_attr($data->settings['select_column']); ?> project col-sm-6 col-12 grid-item_<?php echo esc_attr($PostId); ?>">
					<div class="layout-3" style="margin: <?php echo esc_attr($data->settings['gutter']) ?>px;" >
						<img src="<?php echo esc_url($url_masonary['0']); ?>">
			<?php } ?>

					<div class="layout-content">
		                	
		            	<?php if ( ! $data->settings['hide_title'] ) { ?>
							 <h3 class='pgb-title title'>
	                      		<?php echo esc_html($item_data['title']); ?>
	                       	</h3>
		                    
		                <?php } ?>
		                <?php if ( ! $data->settings['hide_description'] ): ?>
							<p class="description"><?php echo wp_kses_post($item_data['description']); ?></p>
						<?php endif ?>

	           		</div>

	           		<div class="icon" style="background-color: <?php echo esc_attr($data->settings['contentBg']) ?>;">
	           		 	<span>
	           		 		<?php if($image['link'] != '') { ?>
		           		 		<a href="<?php echo esc_url($image['link']); ?>" <?php if($image['target'] == '1') { ?> target="_blank" <?php } ?>>
		                        	<i class="fa fa-link"></i>
		                 	    </a>
		                 	<?php } else { ?>
		                 		<a style="display: none;">
		                        	<i class="fa fa-link"></i>
		                 	    </a>
		                 	<?php } ?>
	                 	</span>
	           		 	<span>
	           		 		<?php if($item_data['lightbox'] == 1 ) { ?>
		                        	<a class="popup-box" href="<?php echo esc_url($item_data['image_full']); ?>" data-lightbox="image">
		                           		 <i class="fa fa-plus"></i>
			                        </a>		
								<?php } else { ?>
									<a class="popup-box" style="display: none;">
									 	<i class="fa fa-plus"></i>
									 </a>
								<?php } ?>
	           		 	</span>
	           		</div><!-- icon -->
	           		</div><!-- layout-3 -->
			</div><!-- col -->
		<?php } ?>
	</div><!-- row -->

	<script>

		jQuery(document).ready( function () {
    
            var $grid = jQuery('#grid_<?php echo esc_attr($PostId); ?>').isotope({
              itemSelector: '.grid-item_<?php echo esc_attr($PostId); ?>',
                percentPosition: true,
              masonry: {
                columnWidth:0
              }
                
            });
            
            $grid.imagesLoaded().progress( function() {
              $grid.isotope('layout');
            });

          }
        );

	</script>
