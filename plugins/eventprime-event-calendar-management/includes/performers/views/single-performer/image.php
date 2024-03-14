<?php
/**
 * View: Single Performer - Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/performers/single-performer/image.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-2">
    <div class="ep-single-box-thumb">
        <div class="ep-single-figure-box">
            <img src="<?php echo esc_url( $args->performer->image_url ); ?>" alt="<?php echo esc_attr( $args->performer->name ); ?>" class="ep-no-image" >
        </div>
    </div>
</div>