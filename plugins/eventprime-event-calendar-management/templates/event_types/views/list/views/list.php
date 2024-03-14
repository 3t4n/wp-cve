<?php
/**
 * View: Event Types List - List View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/list/views/list.php
 *
 */
?>
<?php foreach ( $args->event_types->terms as $event_type ) {?>
    <div class="ep-box-col-12 ep-list-view-main ep-mb-4">
        <div class="ep-box-row ep-bg-white ep-border ep-rounded ep-text-small ep-overflow-hidden">
            <div class="ep-box-col-3 ep-p-0 ep-bg-light ep-border-right ep-position-relative">
                <a href="<?php echo esc_url( $event_type->event_type_url ); ?>" class="ep-img-link">
                    <img src="<?php echo esc_url( $event_type->image_url ); ?>" alt="<?php esc_attr( $event_type->name ); ?>"> 
                </a>
            </div>
            <div class="ep-box-col-6 ep-p-4 ep-text-small">
                <div class="ep-box-list-items">
                    <div class="ep-box-title ep-box-list-title">
                        <a class="ep-color-hover" data-event_type-id="<?php echo esc_attr( $event_type->id ); ?>" href="<?php echo esc_url( $event_type->event_type_url ); ?>" target="_self" rel="noopener">
                            <?php echo esc_html( $event_type->name ); ?>
                        </a>
                    </div>
                    <?php if ( ! empty( $event_type->description ) ) { ?>
                        <div class="ep-eventtype-description ep-content-truncate ep-content-truncate-line-3">
                            <?php echo wpautop( wp_kses_post( $event_type->description ) ); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="ep-box-col-3 ep-px-0 ep-pt-4 ep-border-left ep-position-relative">
                <ul class="ep-box-social-links">
                </ul>
                <div class="ep-align-self-end ep-position-absolute ep-p-2 ep-bg-white ep-box-w-100"  style="bottom:0">
                    <a class="ep-view-details-button" data-event-id="<?php echo esc_attr( $event_type->id ); ?>" href="<?php echo esc_url( $event_type->event_type_url ); ?>">
                        <div class="ep-btn ep-btn-dark ep-box-w-100 ep-mb-2 ep-py-2">
                            <span class="ep-fw-bold ep-text-small">
                                <?php echo esc_html_e('View Detail', 'eventprime-event-calendar-management'); ?>								
                            </span>
                        </div>
                    </a>              
                </div>
            </div>
        </div>
    </div><?php 
} ?>