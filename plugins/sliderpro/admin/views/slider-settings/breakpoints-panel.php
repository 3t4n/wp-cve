<div class="breakpoints">
    <?php
        if ( isset( $slider_settings['breakpoints'] ) ) {
            $breakpoints = $slider_settings['breakpoints'];

            foreach ( $breakpoints as $breakpoint_settings ) {
                include( SLIDERPRO_DIR_PATH . 'admin/views/slider/breakpoint.php' );
            }
        }
    ?>
</div>
<a class="button add-breakpoint" href="#"><?php _e( 'Add Breakpoint', 'sliderpro' ); ?></a>
<?php
    $hide_info = get_option( 'sliderpro_hide_inline_info' );

    if ( $hide_info != true ) {
?>
    <div class="inline-info breakpoints-info">
        <input type="checkbox" id="show-hide-breakpoint-info" class="show-hide-info">
        <label for="show-hide-breakpoint-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
        <label for="show-hide-breakpoint-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
        
        <div class="info-content">
            <p><?php _e( 'Breakpoints allow you to modify the look of the slider for different window sizes.', 'sliderpro' ); ?></p>
            <p><?php _e( 'Each breakpoint allows you to set the width of the window for which the breakpoint will apply, and then add several settings which will override the global settings.', 'sliderpro' ); ?></p>
            <p><a href="https://bqworks.net/slider-pro/screencasts/#working-with-breakpoints" target="_blank"><?php _e( 'See the video tutorial', 'sliderpro' ); ?> &rarr;</a></p>
        </div>
    </div>
<?php
    }
?>