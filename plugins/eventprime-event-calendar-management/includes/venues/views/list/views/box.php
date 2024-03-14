<?php
/**
 * View: Venues List - Box View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/venues/list/views/box.php
 *
 */
?>

<?php
$b = 1;
$venue_box_color = $args->box_color;
foreach ( $args->venues->terms as $venue ) {
    if ($b > 4) {
        $b = 1;
    }
    switch ($b) {
        case 1 :
            $bg_color = ( ! empty( $venue_box_color ) && isset( $venue_box_color[0] ) ) ? $venue_box_color[0] : '#A6E7CF';
            break;
        case 2 :
            $bg_color = ( ! empty( $venue_box_color ) && isset( $venue_box_color[1] ) ) ? $venue_box_color[1] : '#DBEEC1';
            break;
        case 3 :
            $bg_color = ( ! empty( $venue_box_color ) && isset( $venue_box_color[2] ) ) ? $venue_box_color[2] : '#FFD3B6';
            break;
        case 4 :
            $bg_color = ( ! empty( $venue_box_color ) && isset( $venue_box_color[3] ) ) ? $venue_box_color[3] : '#FFA9A5';
            break;
        default:
            $bg_color = '#A6E7CF';
    }
    $light_bg_color = ep_hex2rgba( $bg_color, .5 );
    $bg_color = ep_hex2rgba( $bg_color, 1 );
    ?>
    <div class="ep-box-col-<?php echo absint( $args->cols ); ?> ep-box-column ep-box-px-0" data-id="<?php echo esc_attr( $venue->id ); ?>" data-element_type="column">
        <div class="ep-column-wrap ep-column-populated" style="background-image: linear-gradient(190deg,<?= $bg_color;?>,<?= $light_bg_color;?>); background-color: transparent;">
            <div class="ep-box-widget-wrap" data-id="<?php echo esc_attr( $venue->id );?>">
                <div class="ep-box-box-item">
                    <div class="ep-box-box-thumb">
                        <a href="<?php echo esc_url( $venue->venue_url ); ?>" class="ep-img-link">
                            <img src="<?php echo esc_url($venue->image_url); ?>" alt="<?php echo esc_attr( $venue->name ); ?>">
                        </a>
                    </div>
                    <div class="ep-venue-content">
                        <div class="ep-box-title ep-box-box-title">
                            <a href="<?php echo esc_url( $venue->venue_url ); ?>">
                                <?php echo esc_html( $venue->name ); ?>
                            </a>
                        </div>
                        
                        <div class="ep-box-box-venue ep-card-venue  ep-text-small ep-text-truncate  ep-mb-1">
                            <?php if ( ! empty( $venue->em_address ) && ! empty( $venue->em_display_address_on_frontend ) ) {
                                echo wp_trim_words( $venue->em_address, 10 );
                            } else { ?>
                                <div class="ep-box-box-venue-empty">&nbsp;</div><?php
                            } ?>
                        </div>
                        <div class="ep-venue-seating-capacity ep-event-details ep-text-small ep-d-flex ep-justify-content-between">
                            <?php if ( !empty( $venue->em_type ) ) {?>
                                <div class="ep-event-attr-name ep-fw-bold"><?php echo esc_html__( 'Type', 'eventprime-event-calendar-management' ) .' : '. esc_html__( ep_get_venue_type_label( $venue->em_type ), 'eventprime-event-calendar-management'); ?></div><?php
                            }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php 
    $b++;
} ?>