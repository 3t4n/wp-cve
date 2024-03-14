<?php
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalrealestate
 * @author     Opal  Team <info@wpopal.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */

if (!defined( 'ABSPATH' )) {
    exit; // Exit if accessed directly
}

class OSF_Field_User_upload {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public static function init() {
        add_filter( 'cmb2_render_user_upload', array( __CLASS__, 'render_map' ), 10, 5 );
        add_filter( 'cmb2_sanitize_user_upload', array( __CLASS__, 'sanitize_map' ), 10, 4 );

        add_action( 'wp_ajax_opalrealestate_user_upload', array( __CLASS__, 'upload_image' ) );
        add_action( 'wp_ajax_nopriv_wp_ajax_opalrealestate_user_upload', array( __CLASS__, 'upload_image' ) );

    }

    /**
     * Process upload images for properties
     */
    public static function upload_image() {
        $ajax_response = array( 'success' => false, 'reason' => 'Image upload failed!' );

        // Verify Nonce
        $nonce = $_REQUEST['nonce'];
        if (!wp_verify_nonce( $nonce, 'allow_uploaded' )) {
            $ajax_response = array( 'success' => false, 'reason' => __( 'Security check failed!', 'ocbee-core' ) );
            echo json_encode( $ajax_response );
            die;
        }

        do_action( 'opalrealestate_before_process_ajax_user_upload_file' );

        $submitted_file = $_FILES['upload_file'];
        $uploaded_image = wp_handle_upload( $submitted_file, array( 'test_form' => false ) );   //Handle PHP uploads in WordPress, sanitizing file names, checking extensions for mime type, and moving the file to the appropriate directory within the uploads directory.

        if (isset( $uploaded_image['file'] )) {
            $file_name = basename( $submitted_file['name'] );
            $file_type = wp_check_filetype( $uploaded_image['file'] );   //Retrieve the file type from the file name.

            // Prepare an array of post data for the attachment.
            $attachment_details = array(
                'guid'           => $uploaded_image['url'],
                'post_mime_type' => $file_type['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );


            $attach_id   = wp_insert_attachment( $attachment_details, $uploaded_image['file'] );       // This function inserts an attachment into the media library
            $attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_image['file'] );     // This function generates metadata for an image attachment. It also creates a thumbnail and other intermediate sizes of the image attachment based on the sizes defined
            wp_update_attachment_metadata( $attach_id, $attach_data );                                      // Update metadata for an attachment.

            $thumbnail_url = opalrealestate_get_upload_image_url( $attach_data );
            update_post_meta( $attach_id, '_pending_to_use_', 1 );

            $ajax_response = array(
                'success'       => true,
                'url'           => $thumbnail_url,
                'attachment_id' => $attach_id,
            );

            echo json_encode( $ajax_response );
            die;

        } else {
            $ajax_response = array( 'success' => false, 'reason' => 'Image upload failed!' );
            echo json_encode( $ajax_response );
            die;
        }
    }


    /**
     * Render field
     */
    public static function render_map($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        self::setup_admin_scripts();

        if ($field->args( 'limit' ) == 1) {
            self::render_map_single( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object );

            return;
        }
        $files   = $field->value;
        $id      = rand();
        $post_id = 0;


        echo '<div class="opalrealestate-user-upload ' . apply_filters( 'opalrealestate_row_container_class', 'opal-row' ) . '"> '; ?>
        <div class="upload-container" data-button="select-images<?php echo $id; ?>"
             data-field="<?php echo $field->args( '_name' ); ?>">
            <?php if ($files): ?>
                <?php foreach ($files as $image_id => $url): ?>
                    <div class="upload-item">
                        <?php
                        echo '<div class="upload-thumbnail">';
                        echo wp_get_attachment_image( $image_id, 'thumbnail' );
                        echo '<a class="icon icon-delete" data-toggle="tooltip" title="' . __( 'Delete', 'ocbee-core' ) . '" data-post-id="' . intval( $post_id ) . '" data-attachment-id="' . intval( $image_id ) . '" href="javascript:;">';
                        echo '<i class="fa fa-trash-o"></i>';
                        echo '</a>';

                        echo '<input type="hidden" class="upload-image-id" name="' . $field->args( '_name' ) . '[' . intval( $image_id ) . ']" value="' . $url . '">';
                        echo '<span style="display: none;" class="icon icon-loader">';
                        echo '<i class="fa fa-spinner fa-spin"></i>';
                        echo '</span>';

                        echo '</div>';
                        ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <a id="select-images<?php echo $id; ?>" href="javascript:;" class="button-upload">
                <span>+</span> <?php // esc_html_e( 'Select Images', 'opalrealestate' ); ?></a>
        </div>
        <?php echo '</div>';
    }

    /**
     * Enqueue scripts and styles
     */
    public static function setup_admin_scripts() {
        wp_enqueue_script( 'opalrealestate-userupload', plugins_url( 'assets/script.js', __FILE__ ), array(), self::VERSION );
        wp_enqueue_style( 'opalrealestate-userupload', plugins_url( 'assets/style.css', __FILE__ ), array(), self::VERSION );

        $prop_data = array(
            'ajaxURL'       => admin_url( 'admin-ajax.php' ),
            'uploadNonce'   => wp_create_nonce( 'allow_uploaded' ),
            'fileTypeTitle' => esc_html__( 'Valid file formats', 'ocbee-core' ),
            'message'       => esc_html__( 'Please enter only digits', 'ocbee-core' ),
        );
        wp_localize_script( 'opalrealestate-userupload', 'opalrealestate', $prop_data );

    }

    public static function render_map_single($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        $file    = $field->value;
        $id      = rand();
        $post_id = $field_object_id;
        $files   = array();
        $id      = get_post_meta( $field_object_id, $field->args( '_name' ) . '_id', true );
        if ($id) {
            $files[$id] = $file;
        }
        if ($field->args( 'is_featured' ) == 1 && $field_object_id) {
            $post_thumbnail_id = get_post_thumbnail_id( $field_object_id );
            if ($post_thumbnail_id) {
                $files                     = array();
                $files[$post_thumbnail_id] = wp_get_attachment_url( $post_thumbnail_id );
            }

        }

        echo '<div class="opalrealestate-user-upload ' . apply_filters( 'opalrealestate_row_container_class', 'opal-row' ) . '"> '; ?>
        <div class="upload-container single-upload" data-button="select-images<?php echo $id; ?>"
             data-field="<?php echo $field->args( '_name' ); ?>" data-issingle="1">
            <?php if ($files): ?>
                <?php foreach ($files as $image_id => $url): ?>
                    <div class="upload-item">
                        <?php
                        echo '<div class="upload-thumbnail">';
                        echo wp_get_attachment_image( $image_id, 'thumbnail' );
                        echo '<a class="icon icon-delete" data-toggle="tooltip" title="' . __( 'Delete', 'ocbee-core' ) . '" data-post-id="' . intval( $post_id ) . '" data-attachment-id="' . intval( $image_id ) . '" href="javascript:;">';
                        echo '<i class="fa fa-trash-o"></i>';
                        echo '</a>';

                        echo '<input type="hidden" class="upload-image-id" name="' . $field->args( '_name' ) . '[' . intval( $image_id ) . ']" value="' . $url . '">';
                        echo '<span style="display: none;" class="icon icon-loader">';
                        echo '<i class="fa fa-spinner fa-spin"></i>';
                        echo '</span>';

                        echo '</div>';
                        ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <a id="select-images<?php echo $id; ?>" href="javascript:;" class="button-upload">
                <span>+</span> <?php // esc_html_e( 'Select Images', 'opalrealestate' ); ?></a>
        </div>
        <?php echo '</div>';
    }

    /**
     * Optionally save the latitude/longitude values into two custom fields
     */
    public static function sanitize_map($override_value, $value, $object_id, $field_args) {

        if (isset( $field_args['limit'] ) && $field_args['limit'] == 1) {
            if (is_array( $value )) {
                foreach ($value as $id => $url) {
                    delete_post_meta( $id, '_pending_to_use_', 1 );
                    update_post_meta( $object_id, $field_args['id'] . '_id', $id );
                    if (isset( $field_args['is_featured'] ) && $field_args['is_featured']) {
                        delete_post_thumbnail( $id );
                        set_post_thumbnail( $object_id, $id );
                    }

                    return $url;
                }
            }
        }

        if (is_array( $value )) {
            foreach ($value as $key => $url) {
                delete_post_meta( $key, '_pending_to_use_', 1 );
            }
        }

        return $value;
    }
}

OSF_Field_User_upload::init();
