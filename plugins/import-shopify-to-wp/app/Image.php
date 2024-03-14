<?php

namespace S2WPImporter;

use S2WPImporter\Traits\ErrorTrait;
use WP_Http;

class Image
{
    use ErrorTrait;

    /**
     * @var int
     */
    protected $imageId = 0;

    /**
     * Insert an attachment from an URL address.
     *
     * @param string $url
     * @param int    $parent_post_id
     *
     * @return int|false    Attachment ID or false on error;
     */
    public function downloadAttachment($url, $parent_post_id = null)
    {
        if (!class_exists('\WP_Http')) {
            include_once(ABSPATH . WPINC . '/class-http.php');
        }

        $http = new WP_Http();
        $response = $http->request($url);
        if ((int)$response['response']['code'] !== 200) {
            $this->addSoftError('Failed to download the image. Response code is not valid: ' . $response['response']['code']);

            return false;
        }

        $upload = wp_upload_bits(
            basename(parse_url($url, PHP_URL_PATH)),
            null,
            $response['body']
        );

        if (!empty($upload['error'])) {
            $this->addSoftError('Upload error: ' . $upload['error']);

            return false;
        }

        $file_path = $upload['file'];
        $file_name = basename($file_path);
        $file_type = wp_check_filetype($file_name, null);
        $attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME));
        $wp_upload_dir = wp_upload_dir();

        $post_info = [
            'guid' => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title' => $attachment_title,
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        // Create the attachment
        $attach_id = wp_insert_attachment($post_info, $file_path, $parent_post_id);

        if (is_wp_error($attach_id)) {
            $this->addSoftError($attach_id->get_error_message());

            return false;
        }

        $this->setId($attach_id);

        // Include image.php
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }

    /**
     * @param int $newImageId
     */
    protected function setId($newImageId)
    {
        $this->imageId = is_numeric($newImageId) ? (int)$newImageId : 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->imageId;
    }

    /**
     * @return bool
     */
    public function hasId()
    {
        return !empty($this->imageId);
    }
}
