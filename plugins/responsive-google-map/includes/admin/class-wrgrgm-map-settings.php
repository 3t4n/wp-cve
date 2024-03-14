<?php

use phpDocumentor\Reflection\Types\Boolean;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WRGRGM_Map_Settings {

    private static $instance;

    private function __construct() {

        add_action( 'add_meta_boxes', array( $this, 'setting_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save' ) );
    }

    public static function initialize() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function setting_meta_box() {

        add_meta_box(
            'wrg_rgm_settings', 
            __( 'Settings', 'wrg_rgm' ), 
            array( $this, 'render' ), 
            'wrg_rgm',
            'side'
        );
    }

    public function render( $post ) {

        $settings = get_post_meta( $post->ID, '_rgm_map_settings', true );

        $dataParams = array(
            'container_width' => ! empty($settings) ? $settings['container_width'] : '100%',
            'cw_suffix' => ! empty($settings) ? $settings['cw_suffix'] : '%',
            'container_height' => ! empty($settings) ? $settings['container_height'] : '400px',
            'ch_suffix' => ! empty($settings) ? $settings['ch_suffix'] : 'px',
            'map_lat' => ! empty($settings) ? $settings['map_lat'] : '',
            'map_lng' => ! empty($settings) ? $settings['map_lng'] : '',
            'map_type' => ! empty($settings) ? $settings['map_type'] : 'Roadmap',
            'zoom_level' => ! empty($settings) ? $settings['zoom_level'] : '6',
            'disable_zoom_control' => ! empty($settings) ? filter_var($settings['disable_zoom_control'], FILTER_VALIDATE_BOOLEAN) : false,
            'map_types' => array('Roadmap', 'Satellite', 'Hybrid', 'Terrain'),
            'map_style' => ! empty($settings) ? $settings['map_style'] : '',
            'mc_panel' => ! empty($settings) ? filter_var($settings['mc_panel'], FILTER_VALIDATE_BOOLEAN) : false,
            'ml_panel' => ! empty($settings) ? filter_var($settings['ml_panel'], FILTER_VALIDATE_BOOLEAN) : true,
            'zoom_panel' => ! empty($settings) ? filter_var($settings['zoom_panel'], FILTER_VALIDATE_BOOLEAN) : false,
            'ms_panel' => ! empty($settings) ? filter_var($settings['ms_panel'], FILTER_VALIDATE_BOOLEAN) : false,
        );

        $map_style_data = wrg_rgm()->map_styles->default_styles();

        wp_nonce_field( '_wrg_rgm_settings_nonce_action', '_wrg_rgm_settings_nonce' );
        ?>
        <div id="wrg-rgm__settings" data-map-styles="<?php echo htmlspecialchars(json_encode($map_style_data), ENT_QUOTES, 'UTF-8') ?>" data-settings="<?php echo htmlspecialchars(json_encode($dataParams), ENT_QUOTES, 'UTF-8') ?>"></div>
        <?php
    }

    public function save( $post_id ) {

        // Check if our nonce is set.
        if ( ! isset( $_POST['_wrg_rgm_settings_nonce'] ) ) {
            return $post_id;
        }

        $nonce   = $_POST['_wrg_rgm_settings_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, '_wrg_rgm_settings_nonce_action' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'wrg_rgm' != $_POST['post_type'] ) {
            return $post_id;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Sanitize the user input.
        $container_width    = sanitize_text_field( $_POST['container_width'] );
        $cw_suffix          = sanitize_text_field( $_POST['cw_suffix'] );
        $container_height   = sanitize_text_field( $_POST['container_height'] );
        $ch_suffix          = sanitize_text_field( $_POST['ch_suffix'] );
        $map_lat            = sanitize_text_field( $_POST['map_latitude'] );
        $map_lng            = sanitize_text_field( $_POST['map_longitude'] );
        $zoom_level         = sanitize_text_field( $_POST['zoom_level'] );
        $map_type           = sanitize_text_field( $_POST['map_type'] );
        $map_style          = sanitize_text_field( $_POST['map_style'] );

        // Panel Body State
        $mc_panel   = sanitize_text_field( $_POST['mc_panel'] );
        $ml_panel   = sanitize_text_field( $_POST['ml_panel'] );
        $zoom_panel = sanitize_text_field( $_POST['zoom_panel'] );
        $ms_panel   = sanitize_text_field( $_POST['ms_panel'] );

        $disable_zoom_control = 'false';
        
        if ( isset( $_POST['disable_zoom_control'] ) && ! empty($_POST['disable_zoom_control']) ) {
            $disable_zoom_control = sanitize_text_field( $_POST['disable_zoom_control'] );
        }

        // echo '<pre>';
        // print_r( $_POST );
        // exit;

        $settings_params = array(
            'container_width'   => $container_width,
            'cw_suffix'         => $cw_suffix,
            'container_height'  => $container_height,
            'ch_suffix'         => $ch_suffix,
            'map_lat'           => $map_lat,
            'map_lng'           => $map_lng,
            'zoom_level'        => $zoom_level,
            'map_type'          => $map_type,
            'map_style'         => $map_style,
            'disable_zoom_control' => $disable_zoom_control,
            'mc_panel'          => $mc_panel,
            'ml_panel'          => $ml_panel,
            'zoom_panel'        => $zoom_panel,
            'ms_panel'          => $ms_panel,
        );

        update_post_meta( $post_id, '_rgm_map_settings', $settings_params );
    }
}

WRGRGM_Map_Settings::initialize();