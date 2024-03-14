<?php

/**
 * Images.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$images = unserialize( $post_meta['images'][0] );

if ( 1 == count( $images ) ) : 
    $image_attributes = wp_get_attachment_image_src( $images[0], 'full' ); 
    if ( ! $image_attributes ) return false;
    ?>
    <div class="acadp-image">
        <a class="acadp-image-popup acadp-block acadp-cursor-zoom-in" href="<?php echo esc_url( $image_attributes[0] ); ?>">
            <img src="<?php echo esc_url( $image_attributes[0] ); ?>" class="acadp-w-full acadp-aspect-video acadp-object-cover" alt="" />
        </a>
    </div>
<?php else : ?>
    <div id="acadp-slider-wrapper" class="acadp-images acadp-flex acadp-flex-col acadp-gap-4">                       
        <!-- Slider for -->
        <div class="acadp-slider-for">
            <?php foreach ( $images as $index => $image ) : 
                $image_attributes = wp_get_attachment_image_src( $images[ $index ], 'full' ); 
                if ( ! $image_attributes ) continue;
                ?>
                <div class="acadp-slider-item acadp-cursor-zoom-in">
                    <img src="<?php echo esc_url( $image_attributes[0] ); ?>" class="acadp-w-full acadp-aspect-video acadp-object-cover" alt="" />
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Slider nav -->
        <div class="acadp-slider-nav -acadp-mx-2">
            <?php foreach ( $images as $index => $image ) : 
                $image_attributes = wp_get_attachment_image_src( $images[ $index ], 'medium' );
                if ( ! $image_attributes ) continue; 
                ?>
                <div class="acadp-slider-item acadp-mx-2 acadp-cursor-pointer">
                    <img src="<?php echo esc_url( $image_attributes[0] ); ?>" class="acadp-w-full acadp-aspect-square acadp-object-cover" alt="" />
                </div>
            <?php endforeach; ?>
        </div>        
    </div>
<?php endif;