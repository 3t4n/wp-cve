<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WRGRGM_Admin_Advance_Settings {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'advance_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save' ) );
        add_action( 'wp_ajax_marker_delete', array( $this , 'delete_marker' ) );
        add_action( 'wp_ajax_nopriv_marker_delete', array( $this , 'delete_marker' ) );
    }

    public function advance_meta_box() {

        add_meta_box(
            'wrg_rgm_adv_settings', 
            __( 'Advance Settings', 'wrg_rgm' ), 
            array( $this, 'render' ), 
            'wrg_rgm'
        );
    }

    public function delete_marker() {

        $uuid           = sanitize_text_field( $_POST['uuid'] );
        $marker_type    = sanitize_text_field( $_POST['marker_type'] );
        $post_id        = sanitize_text_field( $_POST['pid'] );

        $marker     = new Marker( $marker_type, $post_id );
        $response   = $marker->delete( $uuid );

        if ( $response ) {
            wp_send_json(array(
                'message' => __('Marker deleted successfully', 'wrg_rgm')
            ));
        }

        wp_send_json(array(
            'message' => __('Un-Authenticated request', 'wrg_rgm')
        ), 401);
    }

    public function render( $post ) {

        // delete_post_meta( $post->ID, '_rgm_simple_markers' );
        // delete_post_meta( $post->ID, '_rgm_adv_markers' );
        // delete_post_meta( $post->ID, '_rgm_advanced_markers' );

        $simple_marker      = new Marker( 'simple', $post->ID );
        $advanced_marker    = new Marker( 'advanced', $post->ID );
        $active_tab         = get_post_meta( $post->ID, '_rgm_adv_active_tab', true );
        
        wp_nonce_field( '_wrg_rgm_adv_settings_box', '_wrg_rgm_adv_settings_nonce' );

        $dataParams = array(
            'active_tab' => $active_tab,
            'markers' => $simple_marker->get_markers(),
            'advanced_markers' => $advanced_marker->get_markers()
        );
        ?>
        <div id="wrg-rgm__adv_settings" data-params="<?php echo htmlspecialchars(json_encode($dataParams), ENT_QUOTES, 'UTF-8') ?>"></div>
        <?php
    }

    public function save( $post_id ) {
        // Check if our nonce is set.
        if ( ! isset( $_POST['_wrg_rgm_adv_settings_nonce'] ) ) {
            return $post_id;
        }

        $nonce   = $_POST['_wrg_rgm_adv_settings_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, '_wrg_rgm_adv_settings_box' ) ) {
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
            return $post_id;
        }

        // echo "<pre>";
        // print_r($_POST);
        // exit;

        // Sanitize the user input.
        $active_tab     = sanitize_text_field( $_POST['active_tab'] );
        $unique_id      = sanitize_text_field( $_POST['uuid'] );
        $latitude       = sanitize_text_field( $_POST['location_lat'] );
        $longitude      = sanitize_text_field( $_POST['location_lng'] );
        $animation      = sanitize_text_field( $_POST['marker_animation'] );
        $custom_marker  = sanitize_text_field( $_POST['custom_marker'] );
        $marker_width   = sanitize_text_field( $_POST['cm_width'] );
        $marker_height  = "";
        $external_link  = sanitize_text_field( $_POST['external_link'] );

        // Update the meta field.
        update_post_meta( $post_id, '_rgm_adv_active_tab', $active_tab );

        if ( empty($latitude) || empty($longitude) ) {
            return $post_id;
        }


        // Get Marker Height based on Marker Width
        if ( ! empty( $custom_marker ) ) {
            list( $width, $height ) = @getimagesize( $custom_marker );

            if ( ! empty($width) && ! empty($height) ) {
                $marker_height = ( $height / $width ) * $marker_width;
            }
        }


        if ( $active_tab == 'marker' ) {

            $marker_data = array(
                'uuid' => $unique_id,
                'lat' => $latitude,
                'lng' => $longitude,
                'animation' => $animation,
                'custom_marker' => $custom_marker,
                'cm_width' => $marker_width,
                'cm_height' => $marker_height,
                'external_link' => $external_link,
            );

            $simple_marker = new Marker( 'simple', $post_id );
            $simple_marker->add( $marker_data );
        }
        else if ( $active_tab == 'advanced-marker' ) {

            $iw_title               = sanitize_text_field( $_POST['iw_title'] );
            $iw_description         = sanitize_text_field( $_POST['iw_description'] );
            $iw_thumbnail           = sanitize_text_field( $_POST['iw_thumbnail'] );
            $iw_thumbnail_position  = sanitize_text_field( $_POST['iw_thumbnail_position'] );
            $iw_default_open        = sanitize_text_field( $_POST['iw_default_open'] );

            $marker_data = array(
                'uuid' => $unique_id,
                'lat' => $latitude,
                'lng' => $longitude,
                'animation' => $animation,
                'custom_marker' => $custom_marker,
                'cm_width' => $marker_width,
                'cm_height' => $marker_height,
                'external_link' => $external_link,
                'iw_title' => $iw_title,
                'iw_description' => $iw_description,
                'iw_thumbnail' => $iw_thumbnail,
                'iw_thumbnail_position' => $iw_thumbnail_position,
                'iw_default_open' => $iw_default_open,
            );

            $simple_marker = new Marker( 'advanced', $post_id );
            $simple_marker->add( $marker_data );
        }
    }
}

new WRGRGM_Admin_Advance_Settings();