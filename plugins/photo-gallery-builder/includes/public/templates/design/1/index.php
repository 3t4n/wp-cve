<?php if ( ! defined( 'ABSPATH' ) ) exit; 
$PostId = $data->gallery_id;

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
wp_enqueue_style( "photo_gallery_index_style_1", PHOTO_GALLERY_BUILDER_ASSETS. "css/layout-design.css",'');
?>


    <?php
    if($data->js_config['type']=='custom-grid')
    { ?>
        <div class="row">
    <?php } else { ?>
        <div class="row grid" id="grid_<?php echo esc_attr($PostId); ?>">
    <?php }
        
          foreach ( $data->images as $image ) {

            $image_object = get_post( $image['id'] );
            if ( is_wp_error( $image_object ) || get_post_type( $image_object ) != 'attachment' ) {
                continue;
            }

            
            // Create array with data in order to send it to image template
            $item_data = array(
                                
                'lightbox'         => $data->settings['pgb_lightbox']

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
                    <div  style="margin: <?php echo esc_attr($data->settings['gutter']) ?>px;" >
                        <?php if($item_data['lightbox'] == 1 ) { ?>
                                <a class="popup-box" href="<?php echo esc_url($item_data['image_full']); ?>" data-lightbox="image">
                                    <img src="<?php echo esc_url($url['0']); ?>" style="width:100%;">
                                </a>        
                        <?php } else { ?>
                             <img src="<?php echo esc_url($url['0']); ?>" style="width:100%;">
                        <?php } ?>
             <?php } else 
             { ?>
                <div class="col-md-<?php echo esc_attr($data->settings['select_column']); ?> project col-sm-6 col-12 grid-item_<?php echo esc_attr($PostId); ?>">
                    <div style="margin: <?php echo esc_attr($data->settings['gutter']) ?>px;" >
                        
                        <?php if($item_data['lightbox'] == 1 ) { ?>
                                <a class="popup-box" href="<?php echo esc_url($item_data['image_full']); ?>" data-lightbox="image">
                                    <img src="<?php echo esc_url($url_masonary['0']); ?>" style="width:100%;">
                                </a>        
                        <?php } else { ?>
                             <img src="<?php echo esc_url($url_masonary['0']); ?>" style="width:100%;">
                        <?php } ?>
             <?php } ?> 


         </div><!-- margin -->
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
