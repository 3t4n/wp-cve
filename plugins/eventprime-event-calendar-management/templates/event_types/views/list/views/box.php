<?php
/**
 * View: Event Types List - Box View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/event_types/list/views/box.php
 *
 */
?>

<?php
$b = 1;
$event_type_box_color = $args->box_color;
foreach ( $args->event_types->terms as $event_type ) {
    if ($b > 4) {
        $b = 1;
    }
    switch ($b) {
        case 1 :
            $bg_color = ( ! empty( $event_type_box_color ) && isset( $event_type_box_color[0] ) ) ? $event_type_box_color[0] : '#A6E7CF';
            break;
        case 2 :
            $bg_color = ( ! empty( $event_type_box_color ) && isset( $event_type_box_color[1] ) ) ? $event_type_box_color[1] : '#DBEEC1';
            break;
        case 3 :
            $bg_color = ( ! empty( $event_type_box_color ) && isset( $event_type_box_color[2] ) ) ? $event_type_box_color[2] : '#FFD3B6';
            break;
        case 4 :
            $bg_color = ( ! empty( $event_type_box_color ) && isset( $event_type_box_color[3] ) ) ? $event_type_box_color[3] : '#FFA9A5';
            break;
        default:
            $bg_color = '#A6E7CF';
    }
    $light_bg_color = ep_hex2rgba( $bg_color, .5 );
    $bg_color = ep_hex2rgba( $bg_color, 1 );
    ?>
    <div class="ep-box-col-<?php echo absint( $args->cols ); ?> ep-box-column ep-box-px-0" data-id="<?php echo esc_attr( $event_type->id ); ?>" data-element_type="column">
        <div class="ep-column-wrap ep-column-populated" style="background-image: linear-gradient(190deg,<?= $bg_color;?>,<?= $light_bg_color;?>); background-color: transparent;">
            <div class="ep-box-widget-wrap" data-id="<?php echo esc_attr( $event_type->id );?>">
                <div class="ep-box-box-item">
                    <div class="ep-box-box-thumb">
                        <a href="<?php echo esc_url( $event_type->event_type_url ); ?>" class="ep-img-link">
                            <img src="<?php echo esc_url($event_type->image_url); ?>" alt="<?php esc_attr( $event_type->name ); ?>"> 
                        </a>
                    </div>
                    <div class="ep-event_type-content">
                        <div class="ep-box-title ep-box-box-title">
                            <a href="<?php echo esc_url( $event_type->event_type_url ); ?>">
                                <?php echo esc_html( $event_type->name ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php 
    $b++;
} ?>