<?php
/**
 * View: Single Organizer - Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/organizers/single-organizer/image.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-2">
    <div class="ep-single-box-thumb">
        <div class="ep-single-figure-box"><?php
            if( ! empty( $args->organizer->image_url ) ) {?>
                <img src="<?php echo esc_url( $args->organizer->image_url ); ?>" alt="<?php echo esc_attr( $args->organizer->name ); ?>" class="ep-no-image" ><?php
            }?>
        </div>
    </div>
</div>