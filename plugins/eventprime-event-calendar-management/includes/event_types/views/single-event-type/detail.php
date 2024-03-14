<?php
/**
 * View: Single Event Type - Detail
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/single-event-type/detail.php
 *
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="ep-box-col-10">
    <div class="ep-single-box-info">
        <div class="ep-single-box-content">
            <div class="ep-single-box-title-info">
                <div class="ep-single-box-title ep-organizer-name ep-fs-3 ep-fw-bold" title="<?php echo esc_attr( $args->event_type->name ); ?>">
                    <?php echo esc_html( $args->event_type->name ); ?>
                </div>
                <div class="ep-single-age-group ep-pb-2"><?php 
                    if( ! empty( $args->event_type->em_age_group ) ) {
                        esc_html_e( 'Age Group', 'eventprime-event-calendar-management' );
                        echo ': ';
                        if( $args->event_type->em_age_group == 'parental_guidance' ) {
                            esc_html_e( 'All ages but parental guidance', 'eventprime-event-calendar-management' );
                        } elseif( $args->event_type->em_age_group == 'custom_group' ) {
                            if( isset( $args->event_type->em_custom_group ) ) {
                                echo esc_html( $args->event_type->em_custom_group );
                            }
                        }else{
                            esc_html_e( 'All','eventprime-event-calendar-management' );
                        }
                    }?>
                </div>
            </div>
            
            <div class="ep-single-box-summery ep-single-box-desc">
                <?php if ( isset( $args->event_type->description ) && $args->event_type->description !== '' ) {
                    echo wpautop( wp_kses_post( $args->event_type->description ) );
                } else{
                    esc_html_e( 'No description available', 'eventprime-event-calendar-management' );
                }?>
            </div>
        </div>
    </div>
</div>
