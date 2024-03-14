<?php
/**
 * Admin ajax functionality.
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    Codexin\ImageMetadataSettings
 * @subpackage Codexin\ImageMetadataSettings/admin
 */

namespace Codexin\ImageMetadataSettings\Admin;

/**
 * Admin ajax functionality.
 */
class Admin_Ajax {
	/**
	 * Image metadata ajax action
	 *
	 * @return jsonstring
	 */
	public function image_metadata() {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			die();
		}

		if (
			isset($_POST['cx_nonce'], $_POST['type'], $_POST['value'], $_POST['image_id'])
		) {
			$ajax_nonce = sanitize_text_field(wp_unslash($_POST['cx_nonce']));
			if (! current_user_can('manage_options') && ! wp_verify_nonce($ajax_nonce, 'ajax-nonce')) {
				return false;
			}
	
			$attachment_id = intval($_POST['image_id']);
	
			// Check if the attachment ID is valid and if the user has the capability to edit the post
			if (!(is_numeric($attachment_id) && $attachment_id > 0 && current_user_can('edit_post', $attachment_id))) {
				return false;
			}
	
			$type = sanitize_text_field(wp_unslash($_POST['type']));
			$value = wp_unslash($_POST['value']);
			$update_value = '';
			$sanitized_value = ('alt' === $type) ? sanitize_text_field($value) : wp_kses($value, $this->content_allow_html());
	
			if ('title' === $type) {
				$updated_data = array(
					'ID' => $attachment_id,
					'post_title' => $sanitized_value
				);
				wp_update_post($updated_data, true);
				$update_value = get_the_title($attachment_id);
			} elseif ('alt' === $type) {
				update_post_meta($attachment_id, '_wp_attachment_image_alt', $sanitized_value);
				$update_value = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
			} else {
				$postarr = array(
					'ID' => $attachment_id
				);
	
				if ('description' === $type) {
					$postarr['post_content'] = $sanitized_value;
					$post_field = 'post_content';
				} elseif ('caption' === $type) {
					$postarr['post_excerpt'] = $sanitized_value;
					$post_field = 'post_excerpt';
				}
	
				if (isset($postarr['post_content']) || isset($postarr['post_excerpt'])) {
					wp_update_post($postarr);
					$attachment = get_post($attachment_id);
					$update_value = $attachment->{$post_field};
				}
			}
	
			wp_send_json($update_value);
			wp_die();
		}
	}
	
	

	/**
	 * Bulk edit option.
	 */
	public function attachment_save_bulk_edit() {
		if (! current_user_can('manage_options') && $_SERVER['REQUEST_METHOD'] !== 'POST') {
			die();
		}
		$required_fields = array('cx_nonce', 'title', 'alt', 'caption', 'description', 'text_change');
		foreach ($required_fields as $field) {
			if (!isset($_POST[$field])) {
				return false;
			}
		}
		$ajax_nonce = sanitize_text_field(wp_unslash($_POST['cx_nonce']));
		if (!wp_verify_nonce($ajax_nonce, 'ajax-nonce')) {
			return false;
		}
		if (empty($_POST['post_ids'])) {
			die();
		}
		$data = array();
		foreach (array('alt', 'title', 'caption', 'description', 'post_ids', 'text_change') as $field) {
			if ($field === 'title' || $field === 'caption' || $field === 'description') {
				$data[$field] = wp_kses(wp_unslash($_POST[$field]), $this->content_allow_html());
			} else {
				$data[$field] = sanitize_text_field(wp_unslash($_POST[$field]));
			}
		}
		$post_ids = explode(',', $data['post_ids']);
		$text_change = trim($data['text_change']);
		$my_post = array();
		foreach (array('title' => 'post_title', 'caption' => 'post_excerpt', 'description' => 'post_content') as $key => $value) {
			if (ucwords($text_change) !== ucwords(trim($data[$key]))) {
				$my_post[$value] = $data[$key];
			}
		}
		foreach ($post_ids as $id) {
			if (!(is_numeric($id) && $id > 0 && current_user_can('edit_post', $id))) {
				return false;
			}
			if (ucwords($text_change) !== ucwords(trim($data['alt']))) {
				update_post_meta($id, '_wp_attachment_image_alt', $data['alt']);
			}
			$my_post['ID'] = $id;
			if (count($my_post) > 1) {
				wp_update_post($my_post);
			}
		}
		$return = array(
			'message' => 'Saved',
			'status' => true,
		);
		wp_send_json($return);
	}
	
	/**
	 * Content Validation.
	 *
	 * @return array
	 */
	public function content_allow_html() {
		return array(
			'a'          => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
			'br'         => array(),
			'em'         => array(),
			'strong'     => array(),
			'blockquote' => array(),
			'del'        => array(
				'datetime' => array(),
			),
			'ins'        => array(
				'datetime' => array(),
			),
			'img'        => array(
				'src' => array(),
				'alt' => array(),
			),
			'ul'         => array(),
			'ol'         => array(),
			'li'         => array(),
			'code'       => array(),
		);
	}

}
