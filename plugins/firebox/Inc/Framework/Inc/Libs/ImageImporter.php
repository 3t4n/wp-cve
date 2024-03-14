<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

/**
 * Sample usage:
 * 
 *  $image = [
 *      'url' => 'Attachment URL',
 *      'id'  => 'Attachment ID',
 *  ];
 *
 *  $downloadedImage = \FPFramework\Libs\Image_Importer::get_instance()->import($image);
 */

class ImageImporter
{
	/**
	 * Instance.
	 *
	 * @var  object
	 */
	private static $instance;

	/**
	 * Already imported images IDs
	 *
	 * @var  array
	 */
	private $already_imported_ids = [];

	public function __construct()
	{
		if (!function_exists('WP_Filesystem'))
		{
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();
	}

	public static function get_instance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Process the images, download them and impor them.
	 *
	 * @param   array  $attachments
	 * 
	 * @return  array
	 */
	public function process($attachments)
	{
		$downloaded_images = [];

		foreach ($attachments as $key => $attachment)
		{
			if (!$import = $this->import($attachment))
			{
				continue;
			}
			
			$downloaded_images[] = $this->import($attachment);
		}

		return $downloaded_images;
	}

	/**
	 * Returns the hash of the image
	 *
	 * @param   string  $attachment_url
	 * 
	 * @return  string
	 */
	public function get_hash_image($attachment_url)
	{
		return sha1($attachment_url);
	}

	/**
	 * Returns the saved image from the attachment.
	 *
	 * @param   string  $attachment
	 * 
	 * @return  string
	 */
	private function get_saved_image($attachment)
	{
		global $wpdb;

		// Is the attachment already processing?
		$post_id = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
					WHERE `meta_key` = \'_fpframework_templates_image_hash\'
						AND `meta_value` = %s
				;',
				$this->get_hash_image($attachment['url'])
			)
		);

		// Is the attachment already imported though XML?
		if (empty($post_id))
		{
			// Get file name without extension
			$filename = basename($attachment['url']);

			$post_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT post_id FROM {$wpdb->postmeta}
					WHERE meta_key = '_wp_attached_file'
					AND meta_value LIKE %s",
					'%/' . $filename . '%'
				)
			);
		}

		if ($post_id)
		{
			$new_attachment = [
				'id'  => $post_id,
				'url' => wp_get_attachment_url($post_id),
			];

			$this->already_imported_ids[] = $post_id;

			return [
				'status'     => true,
				'attachment' => $new_attachment,
			];
		}

		return [
			'status'     => false,
			'attachment' => $attachment,
		];
	}

	/**
	 * Import the image.
	 *
	 * @param   array  $attachment
	 * @return  array
	 */
	public function import($attachment)
	{
		$saved_image = $this->get_saved_image($attachment);

		if ($saved_image['status'])
		{
			return $saved_image['attachment'];
		}

		$file_content = wp_remote_retrieve_body(
			wp_safe_remote_get(
				$attachment['url'],
				array(
					'timeout'   => '60',
					'sslverify' => false
				)
			)
		);

		if (empty($file_content))
		{
			return $attachment;
		}

		// Extract the file name and extension from the URL.
		$filename = basename($attachment['url']);

		$upload = wp_upload_bits($filename, null, $file_content);

		// Cannot upload this file type
		if (isset($upload['error']))
		{
			return false;
		}

		$post = [
			'post_title' => $filename,
			'guid'       => $upload['url']
		];

		$info = wp_check_filetype($upload['file']);

		if ($info)
		{
			$post['post_mime_type'] = $info['type'];
		}
		else
		{
			return $attachment;
		}

		$post_id = wp_insert_attachment($post, $upload['file']);
		wp_update_attachment_metadata($post_id, wp_generate_attachment_metadata($post_id, $upload['file']));
		update_post_meta($post_id, '_fpframework_templates_image_hash', $this->get_hash_image($attachment['url']));

		$new_attachment = [
			'id'  => $post_id,
			'url' => $upload['url']
		];

		$this->already_imported_ids[] = $post_id;

		return $new_attachment;
	}

	/**
	 * Whether its an image URL.
	 *
	 * @param   string   $url
	 * 
	 * @return  boolean
	 */
	public function is_image_url($url = '')
	{
		if (empty($url))
		{
			return false;
		}

		if (\FPFramework\Helpers\Image::isValidImageURL($url))
		{
			return true;
		}

		return false;
	}
}