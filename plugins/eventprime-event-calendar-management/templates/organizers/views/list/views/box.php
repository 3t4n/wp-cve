<?php
/**
 * View: Organizers List - Box View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/eventprime/organizers/list/views/box.php
 *
 */
?>


<?php
$b = 1;
$organizer_box_color = $args->box_color;
foreach ( $args->organizers->terms as $organizer ) {
    if ($b > 4) {
        $b = 1;
    }
    switch ($b) {
        case 1 :
            $bg_color = ( ! empty( $organizer_box_color ) && isset( $organizer_box_color[0] ) ) ? $organizer_box_color[0] : '#A6E7CF';
            break;
        case 2 :
            $bg_color = ( ! empty( $organizer_box_color ) && isset( $organizer_box_color[1] ) ) ? $organizer_box_color[1] : '#DBEEC1';
            break;
        case 3 :
            $bg_color = ( ! empty( $organizer_box_color ) && isset( $organizer_box_color[2] ) ) ? $organizer_box_color[2] : '#FFD3B6';
            break;
        case 4 :
            $bg_color = ( ! empty( $organizer_box_color ) && isset( $organizer_box_color[3] ) ) ? $organizer_box_color[3] : '#FFA9A5';
            break;
        default:
            $bg_color = '#A6E7CF';
    }
    $light_bg_color = ep_hex2rgba( $bg_color, .5 );
    $bg_color = ep_hex2rgba( $bg_color, 1 );
    ?>
    <div class="ep-box-col-<?php echo absint( $args->cols ); ?> ep-box-column ep-box-px-0" data-id="<?php echo esc_attr( $organizer->id ); ?>" data-element_type="column">
        <div class="ep-column-wrap ep-column-populated" style="background-image: linear-gradient(190deg,<?= esc_attr( $bg_color );?>,<?= esc_attr( $light_bg_color );?>); background-color: transparent;">
            <div class="ep-box-widget-wrap" data-id="<?php echo esc_attr( $organizer->id );?>">
                <div class="ep-box-box-item">
                    <div class="ep-box-box-thumb">
                        <a href="<?php echo esc_url( $organizer->organizer_url ); ?>" class="ep-img-link">
                            <img src="<?php echo esc_url( $organizer->image_url ); ?>" alt="<?php echo esc_html( $organizer->name ); ?>">
                        </a>
                    </div>
                    <div class="ep-organizer-content">
                        <div class="ep-box-title ep-box-box-title">
                            <a href="<?php echo esc_url( $organizer->organizer_url ); ?>">
                                <?php echo esc_html( $organizer->name ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><?php 
    $b++;
} ?>
