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

class OSF_Field_Opal_Upload {

    /**
     * Current version number
     */
    const VERSION = '1.0.0';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    public static function init() {
        add_filter( 'cmb2_render_opal_upload', array( __CLASS__, 'render_upload' ), 10, 5 );
        add_filter( 'cmb2_sanitize_opal_upload', array( __CLASS__, 'sanitize_upload' ), 10, 4 );

        add_action( 'wp_ajax_opalrealestate_upload_images', array( __CLASS__, 'upload_image' ) );
        add_action( 'wp_ajax_nopriv_opalrealestate_upload_images', array( __CLASS__, 'upload_image' ) );

        add_action( 'wp_ajax_opalrealestate_upload_user_avatar', array( __CLASS__, 'upload_user_avatar' ) );
        add_action( 'wp_ajax_nopriv_opalrealestate_upload_user_avatar', array( __CLASS__, 'upload_user_avatar' ) );

        // add_action('wp_enqueue_scripts',  array("OpalRealEstate_Field_Opal_Upload",'setup_admin_scripts'), 999 );
        add_action( 'wp_ajax_opalrealestate_delete_property_image', array( __CLASS__, 'delete_property_image' ) );
        add_action( 'wp_ajax_nopriv_opalrealestate_delete_property_image', array( __CLASS__, 'delete_property_image' ) );
    }

    /**
     * Upload user avatar
     */
    public static function upload_user_avatar() {
        global $current_user;
        // Verify Nonce
        $user_id = get_current_user_id();
        $nonce   = $_REQUEST['nonce'];
        if (!wp_verify_nonce( $nonce, 'allow_uploaded' ) || !$user_id) {
            $ajax_response = array( 'success' => false, 'reason' => __( 'Security check failed!', 'ocbee-core' ) );
            echo json_encode( $ajax_response );
            die;
        }


        opalrealestate_clean_attachments( $user_id );

        do_action( 'opalrealestate_before_process_ajax_upload_user_avatar' );

        $submitted_file = $_FILES['property_upload_file'];
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

            $attach_id = wp_insert_attachment( $attachment_details, $uploaded_image['file'] );       // This function inserts an attachment into the media library
            update_post_meta( $attach_id, '_pending_to_use_', 1 );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_image['file'] );     // This function generates metadata for an image attachment. It also creates a thumbnail and other intermediate sizes of the image attachment based on the sizes defined
            wp_update_attachment_metadata( $attach_id, $attach_data );                                      // Update metadata for an attachment.


            $thumbnail_url = opalrealestate_get_upload_image_url( $attach_data );


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
     * Process upload images for properties
     */
    public static function upload_image() {

        // Verify Nonce
        $nonce = $_REQUEST['nonce'];
        if (!wp_verify_nonce( $nonce, 'allow_uploaded' )) {
            $ajax_response = array( 'success' => false, 'reason' => __( 'Security check failed!', 'ocbee-core' ) );
            echo json_encode( $ajax_response );
            die;
        }

        do_action( 'opalrealestate_before_process_ajax_upload_file' );

        $submitted_file = $_FILES['property_upload_file'];
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

            $ajax_response = array(
                'success'       => true,
                'url'           => $thumbnail_url,
                'attachment_id' => $attach_id,
            );

            update_post_meta( $attach_id, '_pending_to_use_', 1 );

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
    public static function render_upload($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object) {
        global $current_user;
        // Verify Nonce
        $user_id = get_current_user_id();

        self::setup_admin_scripts();

        if ($field->args( 'avatar' )) {

            $author_picture_id = get_user_meta( $user_id, OPALREALESTATE_USER_PROFILE_PREFIX . 'avatar_id', true );

            $user_custom_picture = $field_escaped_value;

            require_once( 'media-avatar-tpl.php' );
        } else {
            require_once( 'media-tpl.php' );
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public static function setup_admin_scripts() {
        wp_enqueue_script( 'plupload' );
        wp_enqueue_script( 'opal-upload', plugins_url( 'script.js', __FILE__ ), array(), self::VERSION );

        $prop_data = array(
            'ajaxURL'       => admin_url( 'admin-ajax.php' ),
            'uploadNonce'   => wp_create_nonce( 'allow_uploaded' ),
            'fileTypeTitle' => esc_html__( 'Valid file formats', 'ocbee-core' ),
            'message'       => esc_html__( 'Please enter only digits', 'ocbee-core' ),
        );
        wp_localize_script( 'opal-upload', 'opalrealestate', $prop_data );
    }

    /**
     * Optionally save the latitude/longitude values into two custom fields
     */
    public static function sanitize_upload($override_value, $value, $object_id, $field_args) {

        if (is_array( $value )) {

            foreach ($value as $key => $url) {
                delete_post_meta( $key, '_pending_to_use_', 1 );
            }
        }

        return $value;
    }

    /**
     * Delete property image
     */
    public static function delete_property_image() {

        global $current_user;

        wp_get_current_user();
        $user_id = $current_user->ID;

        $output         = new stdClass();
        $ouput->message = '';
        $ouput->status  = false;

        $post_data = get_post( $_POST['attachment_id'], ARRAY_A );
        $author_id = $post_data['post_author'];


        if (!isset( $_POST['property_id'], $_POST['attachment_id'] )) {


            $ouput->message = __( 'Could not delete this?', 'ocbee-core' );
            echo json_encode( $ouput );
            exit;

        }

        $attchment_id = intval( $_POST['attachment_id'] );
        $post_id      = intval( $_POST['property_id'] );


        if ($user_id == $author_id) {
            wp_delete_attachment( $attchment_id );
            $ouput->message = __( 'Deleted this done', 'ocbee-core' );
            $ouput->status  = true;
            echo json_encode( $ouput );
            exit;
        }


        $property_images = get_post_meta( $post_id, OPALREALESTATE_PROPERTY_PREFIX . 'gallery', true );


        if (!isset( $property_images[$attchment_id] )) {
            $ouput->message = __( 'Could not found this in the collection?', 'ocbee-core' );
            echo json_encode( $ouput );
            exit;
        }
        if (!opalrealestate_is_own_property( $post_id, $user_id )) {
            $ouput->message = __( 'The post is not owned by this user?', 'ocbee-core' );
            echo json_encode( $ouput );
            exit;
        }

        unset( $property_images[$attchment_id] );

        update_post_meta( $post_id, OPALREALESTATE_PROPERTY_PREFIX . 'gallery', $property_images );
        wp_delete_attachment( $attchment_id );

        $ouput->message = __( 'Deleted this done', 'ocbee-core' );
        $ouput->status  = true;

        echo json_encode( $ouput );
        exit;
    }
}

OSF_Field_Opal_Upload::init();
