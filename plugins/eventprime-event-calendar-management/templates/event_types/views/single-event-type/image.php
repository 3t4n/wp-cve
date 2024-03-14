<?php
/**
 * View: Single Event Type - Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/single-event-type/image.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-2">
    <div class="ep-single-box-thumb">
        <div class="ep-single-figure-box">
            <img src="<?php echo esc_url($args->event_type->image_url); ?>" alt="<?php echo esc_attr( $args->event_type->name ); ?>"  class="ep-no-image"/>
        </div>
    </div>
</div>