<?php
/**
 * View: Single Venue - Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/single-venue/image.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-2">
    <div class="ep-single-box-thumb">
        <div class="ep-single-figure-box">
            <img src="<?php echo esc_url( $args->venue->image_url ); ?>" alt="<?php echo esc_attr( $args->venue->name ); ?>" class="ep-no-image" >
        </div>
    </div>
</div>