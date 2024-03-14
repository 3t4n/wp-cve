<?php if ( ! defined( 'ABSPATH' ) ) exit; 
$PostId = $data->gallery_id;
?>

<?php 
wp_enqueue_style( "photo_gallery_index_style_5", PHOTO_GALLERY_BUILDER_ASSETS. "css/layout-design.css",'');
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
		<div class="row" style>
	<?php }	else { ?>
		<div class="row grid" id="grid_<?php echo esc_attr($PostId); ?>">
	<?php }
		    foreach ( $data->images as $image ): ?>
			<?php 
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
			
			if($data->js_config['type']=='custom-grid')
			{ ?>
				<div class="col-md-<?php echo esc_attr($data->settings['select_column']); ?> col-sm-6 col-12">
					<div class="layout-5" style="margin: <?php echo esc_attr($data->settings['gutter']) ?>px;">
						<div class="layout-inner">
							<div class="layout-image">

							<?php if($item_data['lightbox'] == 1 ) { ?>

								<a class="popup-box" href="<?php echo esc_url($item_data['image_full']); ?>" data-lightbox="image">
		                            <img src="<?php echo esc_url($url['0']); ?>" style="width:100%;">
			                    </a>
	
	                 		<?php } else { ?>
	                 			
									<img src="<?php echo esc_url($url['0']); ?>" style="width:100%;">

	                 		<?php } ?>

								
			<?php }	else { ?>
				<div class="col-md-<?php echo esc_attr($data->settings['select_column']); ?> project col-sm-6 col-12 grid-item_<?php echo esc_attr($PostId); ?>">
					<div class="layout-5" style="margin: <?php echo esc_attr($data->settings['gutter']) ?>px;" >
						<div class="layout-inner">
							<div class="layout-image">

		                 	<?php if($item_data['lightbox'] == 1 ) { ?>

								<a class="popup-box" href="<?php echo esc_url($item_data['image_full']); ?>" data-lightbox="image">
		                            <img src="<?php echo esc_url($url_masonary['0']); ?>" style="width:100%;">
			                    </a>
	
	                 		<?php } else { ?>
	                 			
									<img src="<?php echo esc_url($url_masonary['0']); ?>" style="width:100%;">

	                 		<?php } ?>
									
			<?php } ?>
							
							</div><!-- ld-pf-image -->
							<div class="layout-content" 
								<?php if( ($item_data['title'] || $item_data['description']) && (! $data->settings['hide_title'] || ! $data->settings['hide_description'] )) { ?> 
											style="
												padding: 10px !important; 
												background-color: <?php echo esc_attr($data->settings['contentBg']) ?>;" 
											<?php  } ?> >
								<?php if ( ! $data->settings['hide_title'] ): ?>
									
									<?php if($image['link'] != '') { ?>
										<a href="<?php echo esc_url($image['link']); ?>" <?php if($image['target'] == '1') { ?> target="_blank" <?php } ?>>
											<h2 class='pgb-title' style="margin: 0px !important;"><?php echo esc_html($item_data['title']); ?></h2>
										</a>
									<?php } else { ?>

										<h2 class='pgb-title' style="margin: 0px !important;"><?php echo esc_html($item_data['title']); ?></h2>

									<?php } ?>

								<?php endif ?>
								<?php if ( ! $data->settings['hide_description'] ): ?>
									<p class="description"><?php echo wp_kses_post($item_data['description']); ?></p>
								<?php endif ?>
							</div>
							

						</div><!-- ld-pf-inner -->
					</div><!-- layout -->
				</div><!-- col -->	
				
				

		<?php endforeach; ?>
		

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
