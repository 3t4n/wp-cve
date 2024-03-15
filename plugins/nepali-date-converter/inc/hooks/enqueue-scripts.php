<?php
/**
 * enqueue css/js
 *
 */
if ( ! function_exists( 'ndc_widget_scripts' ) ) :

    function ndc_widget_scripts(){
        wp_enqueue_script('jquery');
    }
endif;