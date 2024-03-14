<?php

namespace ShopWP\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;
use ShopWP\Utils\Server;

class Media_Uploader extends \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_media_uploader';

    protected $DB_Settings_Syncing;
    protected $DB_Images;
    protected $compatible_charset;

    public function __construct($DB_Settings_Syncing, $DB_Images)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Images = $DB_Images;
        // $this->Media_Multiple         = $Media_Multiple;

        parent::__construct($DB_Settings_Syncing);
    }

    /*

    Entry point. Initial call before processing starts.

    $media_items come from api/items/class-media-uploader.php

    */
    public function process($images, $params)
    {
        if ($this->expired_from_server_issues($images, __METHOD__, __LINE__)) {
            return;
        }

        $this->dispatch_items($images);
    }

    public function get_existing_attachments()
    {
        $existing_attachments = \wp_cache_get('shopwp_existing_attachments');

        if ($existing_attachments) {
            return $existing_attachments;
        }

        $all_images_meta = $this->DB_Images->get_all_plugin_attachments_meta();

        if (!empty($all_images_meta)) {
            \wp_cache_add('shopwp_existing_attachments', $all_images_meta);
        }

        return $all_images_meta;
    }

    /*

	Performs actions required for each item in the queue

	*/
    public function task($image)
    {
        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        $existing_attachments = $this->get_existing_attachments();

        $process_media_results = $this->process_media(
            $image,
            $existing_attachments
        );

        // Removes items from queue
        return false;
    }

    protected function complete()
    {
        \wp_cache_delete('shopwp_existing_attachments');

        parent::complete();
    }

    /*

   Entry point for processing media

   */
    public function process_media($media_item, $existing_attachments)
    {
        $file_info = $this->download_media_file(
            $media_item,
            $existing_attachments
        );

        $result = $this->upload_media_file($file_info);

        return $result;
    }

    /*

   Finds the required file info

   */
    public function get_file_info($file)
    {
        if (function_exists('mime_content_type')) {
            $mime_type = \mime_content_type($file);

            if ($mime_type === 'image/png') {
                $ext = '.png';
            } elseif (
                $mime_type === 'image/jpeg' ||
                $mime_type === 'image/pjpeg'
            ) {
                $ext = '.jpg';
            } elseif ($mime_type === 'image/gif') {
                $ext = '.gif';
            }

            return [
                'type' => $mime_type,
                'ext' => $ext,
            ];
        } else {
            return false;
        }
    }

    /*
   
   Renames the temp download file
   
   */
    public function rename_temp_file($temp_file_path, $mime_and_extension)
    {
        if (empty($temp_file_path)) {
            return false;
        }

        $exploded = explode('.tmp', $temp_file_path);

        return $exploded[0] . $mime_and_extension['ext'];
    }

    public function hash_media($media_obj)
    {
        return base64_encode(serialize($media_obj));
    }

    public function attachment_exists($existing_attachments, $image)
    {
        if (!is_array($existing_attachments)) {
            return [];
        }

        if (empty($image)) {
            return [];
        }

        return array_filter($existing_attachments, function (
            $existing_attachment
        ) use ($image) {
            return Utils::str_contains(
                $existing_attachment->meta_value,
                $image->src
            );
        });
    }

    public function get_attachment_id_from_existing($existing_attachment)
    {
        $reset_indexes = array_values($existing_attachment);

        $existing_attachments = maybe_unserialize(
            $reset_indexes[0]->meta_value
        );

        if (!empty($existing_attachments)) {
            return $existing_attachments['attachment_id'];
        }

        return false;
    }

    public function download_multiple_media_files(
        $images,
        $existing_attachments
    ) {
        $results = [];

        foreach ($images as $image) {
            $results[] = $this->download_media_file(
                $image,
                $existing_attachments
            );
        }

        return $results;
    }

    public function build_download_results(
        $temp_file_path,
        $temp_file_path_mod,
        $mime_and_extension,
        $image
    ) {
        return [
            'image' => $temp_file_path_mod,
            'mime' => $mime_and_extension['type'],
            'post_id' => $image->post_id,
            'basename' => basename($temp_file_path_mod),
            'size' => filesize($temp_file_path),
            'ext' => $mime_and_extension['ext'],
            'alt' => $image->alt,
            'orig_src' => $image->src,
            'existing' => false,
        ];
    }

    /*
   
   Downloads a chunk of media
   
   */
    public function download_media_file($image, $existing_attachments)
    {
        $result = false;

        if (empty($image) || empty($image->src)) {
            return;
        }

        $existing_attachment = $this->attachment_exists(
            $existing_attachments,
            $image
        );

        if (!empty($existing_attachment)) {
            $existing_attachment_id = $this->get_attachment_id_from_existing(
                $existing_attachment
            );

            if ($existing_attachment_id) {
                $result = [
                    'existing' => true,
                    'post_id' => $image->post_id,
                    'attachment_id' => $existing_attachment_id,
                    'orig_src' => $image->src,
                ];

                // Updates the front-end file status
                $this->DB_Settings_Syncing->update_recently_syncd_media_ref(
                    $image->src
                );

                // continue;
                return $result;
            }
        }

        /*
      
      Downloads image to a local temporary file using the WordPress HTTP API.

      */
        $temp_file_path = \download_url($image->src);

        if (\is_wp_error($temp_file_path)) {
            $result = $temp_file_path;

            $download_error = $temp_file_path->get_error_message();

            @unlink($temp_file_path);

            // continue;
            return $result;
        }

        $mime_and_extension = $this->get_file_info($temp_file_path);

        $temp_file_path_mod = $this->rename_temp_file(
            $temp_file_path,
            $mime_and_extension
        );

        $result = $this->build_download_results(
            $temp_file_path,
            $temp_file_path_mod,
            $mime_and_extension,
            $image
        );

        if ($temp_file_path_mod) {
            rename($temp_file_path, $temp_file_path_mod);
        }

        $this->DB_Settings_Syncing->update_recently_syncd_media_ref(
            $image->src
        );

        if ($temp_file_path) {
            @unlink($temp_file_path);
        }

        return $result;
    }

    /*
   
   This tells WordPress to not look for the POST form
   fields that would normally be present. Default is true.
   Since the file is being downloaded from a remote server,
   there will be no form fields.

   */
    public function sideload_overrides()
    {
        return [
            'test_form' => false,
            'test_upload' => false,
            'test_type' => false,
            'test_size' => true,
        ];
    }

    /*

   Responsible for updating each file to the Media Library

   */
    public function upload_media_file($file)
    {
        if (is_wp_error($file)) {
            $this->DB_Settings_Syncing->increment_current_amount('media');

            return [
                'src' => false,
                'attachment_id' => false,
            ];
        }

        if ($file['existing']) {
            $set_feat_result = \set_post_thumbnail(
                $file['post_id'],
                $file['attachment_id']
            );
            $this->DB_Settings_Syncing->increment_current_amount('media');

            return [
                'src' => $file['orig_src'],
                'attachment_id' => $file['attachment_id'],
            ];
        }

        $file_array = [
            'name' => $file['basename'], //isolates and outputs the file name from its absolute path
            'type' => $file['mime'], //yes, thats sloppy, see my text further down on this topic
            'tmp_name' => $file['image'], //this field passes the actual path to the image
            'error' => 0,
            'size' => $file['size'], //returns image filesize in bytes
        ];

        $file_attributes = \wp_handle_sideload(
            $file_array,
            $this->sideload_overrides()
        );

        $attachment = [
            'guid' => $file_attributes['url'],
            'post_mime_type' => $file['mime'],
            'post_title' => get_the_title($file['post_id']),
            'post_status' => 'inherit',
        ];

        $attachment_id = \wp_insert_attachment(
            $attachment,
            $file_attributes['file'],
            $file['post_id'],
            true
        );

        if (is_wp_error($attachment_id)) {
            $this->DB_Settings_Syncing->increment_current_amount('media');

            return [
                'src' => $file['orig_src'],
                'attachment_id' => $attachment_id,
            ];
        }

        // Generate the metadata for the attachment, and update the database record.
        $attach_data = \wp_generate_attachment_metadata(
            $attachment_id,
            $file_attributes['file']
        );

        // Important
        $attach_data['shopwp'] = true;
        $attach_data['orig_src'] = $file['orig_src'];
        $attach_data['attachment_id'] = $attachment_id;

        $update_meta_result = \wp_update_attachment_metadata(
            $attachment_id,
            $attach_data
        );

        $update_alt_result = \update_post_meta(
            $attachment_id,
            '_wp_attachment_image_alt',
            $file['alt']
        );

        $set_feat_result = \set_post_thumbnail(
            $file['post_id'],
            $attachment_id
        );

        $this->DB_Settings_Syncing->increment_current_amount('media');

        return [
            'src' => $file['orig_src'],
            'attachment_id' => $attachment_id,
        ];
    }
}
