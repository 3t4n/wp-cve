<?php

/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file: 
 * 
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 * Example: 
 */

?>
<div>
    <?php
        global $arfliteversion;
        wp_register_style( 'arflitedisplaycss_editor', ARFLITEURL . '/css/arflite_front.css', array(), $arfliteversion );
        wp_enqueue_style( 'arflitedisplaycss_editor' );

        $form_id = !empty( $settings->form_id ) ? $settings->form_id : '';

        $params = '';
        $params = ' is_beaverbuilder="true" ';

        if ( is_plugin_active( 'arforms/arforms.php' ) ) {
            $content = do_shortcode( '[ARForms id='.$form_id.' '.$params.' ]' );
        } else {
            $content = do_shortcode( '[ARForms id='.$form_id.' '.$params.' ]' );
        }

        echo $content; //phpcs:ignore
    ?>
</div>