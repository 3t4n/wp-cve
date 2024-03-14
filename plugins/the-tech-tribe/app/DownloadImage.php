<?php
namespace TheTribalPlugin;

use WP_Error;
//use DownloadRemoteImage;

class DownloadImage
{
    /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct(){}
    
    public function download( $args = [] )
    {
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        return $this->uploadRemoteImage($args);
    }

    public function uploadRemoteImage($args = [])
    {
        $image_url = $args['file_url'] ?? '';
        $parent_id = $args['parent_post_id'] ?? 0;
        $alt_text  = $args['alt_text'] ?? '';

        $image = $image_url;

        $get = wp_remote_get( $image );

        $type = wp_remote_retrieve_header( $get, 'content-type' );

        if (!$type)
            return false;

        $mirror = wp_upload_bits( basename( $image ), '', wp_remote_retrieve_body( $get ) );

        $attachment = array(
            'post_title'=> basename( $image ),
            'post_mime_type' => $type
        );

        $attach_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );
        
        if($alt_text != ''){
            update_post_meta($attach_id, '_wp_attachment_image_alt', $alt_text);
        }

        $attach_data = wp_generate_attachment_metadata( $attach_id, $mirror['file'] );

        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }


    //https://developer.wordpress.org/reference/functions/media_sideload_image/
    public function mediaSideLoadImage($args = [])
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $file_url = $args['file_url'] ?? '';
        $post_id = $args['post_id'] ?? 0;
        $desc = $args['desc'] ?? null;
        $return = $args['return'] ?? 'id';

        return media_sideload_image($file_url, $post_id, $desc, $return);
    }
    
}