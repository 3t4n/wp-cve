<?php

class MPG_AdvancedSettingsController{

    public static function render(){

        require_once(realpath(__DIR__) . '/../views/advanced-settings/index.php');

    }

    public static function mpg_set_branding_position(){

        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try{

            $position = isset($_POST['branding_position']) ? sanitize_text_field($_POST['branding_position']) : 'left';

            update_option('mpg_branding_position', $position);

            echo json_encode([
                'success' => true
            ]);
            wp_die();

        }catch(Exception $e){
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            wp_die();
        }

    }
    public static function mpg_get_branding_position(){

        check_ajax_referer( MPG_BASENAME, 'securityNonce' );

        try{

            echo json_encode([
                'success' => true,
                'data' => get_option('mpg_branding_position')
            ]);
            wp_die();

        }catch(Exception $e){
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            wp_die();
        }

    }
}