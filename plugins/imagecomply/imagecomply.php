<?php
/*
Plugin Name: ImageComply
Description: Automate ADA compliance with alt text generation for your images, enhancing accessibility and SEO for your WordPress media library.
Version: 1.5.4
Author:      Web Programming Solutions
Author URI:  https://webprogrammingsolutions.com/
*/

namespace ImageComply;

use WP_Query;


if (!defined('WPINC')) {
	die;
}

define('IMAGECOMPLY_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('IMAGECOMPLY_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define('IMAGECOMPLY_PLUGIN_MIME_TYPES', array('image/jpeg', 'image/pjpeg', 'image/webp', 'image/gif', 'image/avif', 'image/tiff', 'image/png', 'image/bmp'));

require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'includes/functions.php';
require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'includes/dashboard.php';
require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'includes/rest-routes.php';
require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'includes/media-library.php';

// Integrations
require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/woocommerce.php';

class ImageComply
{

	public function __construct()
	{
		add_action('admin_init', [$this, 'run_update_imagecomply_status']);
		add_action('admin_notices', [$this, 'imagecomply_notices']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_imagecomply_stylesheet']);
		add_action('add_attachment', [$this, 'add_attachment'], 10, 1);

		// add_action( 'wp_enqueue_scripts', [$this, 'imagecomply_enqueue_scripts'] );
		// add_action( 'wp_enqueue_scripts', [$this, 'imagecomply_add_defer_inline_script']);

		add_filter('wp_generate_attachment_metadata', [$this, 'generate_attachment_metadata'], 10, 3);
		add_filter('attachment_fields_to_save', [$this, 'attachment_fields_to_save'], 10, 2);
		// add_filter('cron_schedules', [$this, 'cron_schedules'], 10, 1);
	}


	public function imagecomply_notices()
	{
		// Check current screen
		$screen = get_current_screen();

		// Display only on the Media page
		if ('upload' === $screen->id) {

			$args = array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'post_mime_type' => array('image/jpeg', 'image/pjpeg', 'image/webp', 'image/gif', 'image/avif', 'image/tiff', 'image/png', 'image/bmp'),
				'posts_per_page' => -1,
				'fields'         => 'ids',     // Only get post IDs
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'relation' => 'OR',
						array(
							'key' => 'imagecomply_alt_text_status',
							'value' => 'incomplete',
							'compare' => '=' 
						),
						array(
							'key' => 'imagecomply_alt_text_status',
							'compare' => 'NOT EXISTS'
						),
					),
					array(
						'key' => '_wp_attachment_image_alt',
						'compare' => 'NOT EXISTS'
					)
				),
			);

			$imagecomply_noalttext = new WP_Query( $args );
			$count = $imagecomply_noalttext->found_posts;

			// $unoptimized_attachment_ids = get_posts($args);
			// $count = count($unoptimized_attachment_ids);

			if ($count > 0) {
				echo '<div class="notice notice-error is-dismissible">';
				echo '<p><strong>Urgent:</strong> Your media library contains <strong>' . esc_html($count) . '</strong> items missing alternate text. <a href="' . esc_attr(get_admin_url()) . 'admin.php?page=imagecomply">Resolve Now</a></p>';
				echo '</div>';
			}
		}
	}

	// public function cron_schedules($schedules)
	// {
	// 	if (!isset($schedules["imagecomply_cron"])) {
	// 		$schedules["imagecomply_cron"] = array(
	// 			'interval' => 1 * 60,
	// 			'display' => __('Once every minute')
	// 		);
	// 	}

	// 	return $schedules;
	// }

	public function enqueue_imagecomply_stylesheet($hook_suffix)
	{
		// echo $hook_suffix;
		$plugin_dir = plugin_dir_url(__FILE__);
		$ver = time();
		wp_enqueue_style('imagecomply-style', $plugin_dir . 'assets/css/imagecomply.min.css', array(), $ver);
		wp_enqueue_script('imagecomply-script', $plugin_dir . 'assets/js/imagecomply.js', array('jquery'), $ver, true);

		$post = get_post();

		$attachment = false;

		if (isset($post) && $post->post_type === 'attachment') {
			$imageComply = get_post_meta($post->ID, 'imagecomply', true);
			
			// Polylang translations return an object not a string, so skip unserializing
			if(is_string($imageComply)){
				$imageComply = unserialize($imageComply);
				// error_log("imagecomply - obj");
				// error_log(print_r($imageComply, true));
			}

			$attachment = array(
				'imagecomply' => $imageComply,
				'imagecomply_alt_text_status' => get_post_meta($post->ID, 'imagecomply_alt_text_status', true),
				// 'imagecomply_optimization_status' => get_post_meta($post->ID, 'imagecomply_optimization_status', true),
			);
		}


		// Localize the script for use with admin-ajax.php
		wp_localize_script('imagecomply-script', 'enqueue_vars', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'attachment' => $attachment,
			// 'nonce_1' => wp_create_nonce('enqueue_optimization_ajax_handler'),
			'nonce_2' => wp_create_nonce('enqueue_image_ajax_handler'),
		));

		if ($hook_suffix === 'toplevel_page_imagecomply') {
			
			$nonce_token = wp_create_nonce('update_settings');
			$license_key = get_option('imagecomply_license_key', '');
			$credits_res = Functions::get_credits($license_key);
			$plan_res = Functions::get_plan($license_key);

			wp_enqueue_script( 'imagecomply', plugin_dir_url( __FILE__ ) . 'templates/dashboard.js', array(), null, true );
			wp_localize_script('imagecomply', 'imagecomply_data', array( 
				'nonce_token' => $nonce_token,
				'ajax_url' => admin_url('admin-ajax.php'),
				'credits'	=> $credits_res ? $credits_res->credits : 0,
				'plan' => $plan_res ? $plan_res->plan : false,
				'invalid_license_key' => $license_key && $license_key !== '' && $credits_res && isset($credits_res->error) && $credits_res->error === 'Invalid licenseKey' ? true : false,
				'imagecomply_license_key' => $license_key,
				
				'imagecomply_generate_on_upload' => get_option('imagecomply_generate_on_upload', true),
				'imagecomply_medialibrary_show_status' => get_option('imagecomply_medialibrary_show_status', false),
				'imagecomply_medialibrary_show_alt_text' => get_option('imagecomply_medialibrary_show_alt_text', false),
				'imagecomply_optimize_on_upload' => get_option('imagecomply_optimize_on_upload', true),
				'imagecomply_alt_text_language' => get_option('imagecomply_alt_text_language', ''),
				'imagecomply_alt_text_keywords' => get_option('imagecomply_alt_text_keywords', ''),
				'imagecomply_alt_text_neg_keywords' => get_option('imagecomply_alt_text_neg_keywords', ''),

				'imagecomply_alt_text_in_progress' => get_option('imagecomply_alt_text_in_progress', true),
				'imagecomply_optimization_in_progress' => get_option('imagecomply_optimization_in_progress', true),
			));


			wp_enqueue_style('imagecomply-settings-style', $plugin_dir . 'assets/css/imagecomply-settings.min.css', array(), $ver);
			wp_enqueue_script('imagecomply-alpine-js', $plugin_dir . 'assets/js/alpine-js.min.js', array('imagecomply'), '1.0.0', true);

		}
	}

	public function add_attachment($post_id)
	{
		$imagecomply = get_post_meta($post_id, 'imagecomply', true);
		$unserialized = unserialize($imagecomply);

		$unserialized['mime'] = get_post_mime_type($post_id);

		$serialized = serialize($unserialized);

		update_post_meta($post_id, 'imagecomply', $serialized);
	}


	public function attachment_fields_to_save($post, $attachment)
	{
		$alt = get_post_meta($post['ID'], '_wp_attachment_image_alt', true);

		$serialized_data = get_post_meta($post['ID'], 'imagecomply', true);
		
		// Polylang translations return an object not a string, so skip unserializing
		if(is_string($serialized_data)){
			$data_array = unserialize($serialized_data);
		}

		$original_status = get_post_meta($post['ID'], 'imagecomply_alt_text_status', true);
		$status = $original_status;

		if ($alt !== $data_array['generated_alt']) {
			$status = 'complete-manual';
		} else {
			$status = 'complete';
		}

		if ($original_status !== $status) {
			update_post_meta($post['ID'], 'imagecomply_alt_text_status', $status);
		}

		return $post;
	}


	public function generate_attachment_metadata($metadata, $attachment_id, $context)
	{
		// Sanitize and validate attachment ID as an integer
		$attachment_id = absint($attachment_id);

		if (!get_post($attachment_id)) {
			return $metadata; // If the attachment ID is not valid, return early.
		}

		// Ensure $context is one of the expected values, otherwise return early.
		if (!in_array($context, array('create', 'update', 'edit'))) {
			return $metadata;
		}

		// $imagecomply_optimize_on_upload = get_option('imagecomply_optimize_on_upload', true);
		// $imagecomply_optimize_on_upload = ($imagecomply_optimize_on_upload && $imagecomply_optimize_on_upload !== 'false') ? true : false;

		// $optimization_status = get_post_meta($attachment_id, 'imagecomply_optimization_status', true);

		// if ($imagecomply_optimize_on_upload && $optimization_status !== 'queued') {
		// 	update_post_meta($attachment_id, 'imagecomply_optimization_status', 'queued');

		// 	Functions::optimize_image($attachment_id);
		// }

		if ($context !== 'create') {
			$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

			$imagecomply = get_post_meta($attachment_id, 'imagecomply', true);
			$unserialized = maybe_unserialize($imagecomply);

			// Sanitize and validate $alt and $unserialized['generated_alt']
			$alt = sanitize_text_field($alt);

			if ($alt !== $unserialized['generated_alt']) {
				update_post_meta($attachment_id, 'imagecomply_alt_text_status', 'complete-manual');
			}

			return $metadata;
		}

		$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

		if ($alt_text) {
			// Get imagecomply status and check if it's already queued or complete
			$status = get_post_meta($attachment_id, 'imagecomply_alt_text_status', true);

			if (!isset($status) || !is_string($status)) {
				update_post_meta($attachment_id, 'imagecomply_alt_text_status', 'complete-manual');
			}

			return $metadata;
		}

		$imagecomply_generate_on_upload = get_option('imagecomply_generate_on_upload', true);

		$imagecomply_generate_on_upload = ($imagecomply_generate_on_upload && $imagecomply_generate_on_upload !== 'false') ? true : false;

		$alt_text_status = get_post_meta($attachment_id, 'imagecomply_alt_text_status', true);

		if ($imagecomply_generate_on_upload && $alt_text_status !== 'queued') {
			Functions::generate_alt_text($attachment_id);
		}

		return $metadata;
	}

	public function run_update_imagecomply_status()
	{
		if (isset($_GET['reset_alt_text']) && $_GET['reset_alt_text'] === 'true') {
			$args = array(
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'posts_per_page' => -1,
			);

			$attachments = get_posts($args);

			foreach ($attachments as $attachment) {
				$attachment_id = $attachment->ID;
				update_post_meta($attachment_id, '_wp_attachment_image_alt', '');
			}

			$this->update_imagecomply_status_for_media_items();
		}

		if (isset($_GET['first_run']) && $_GET['first_run'] === 'true') {
			// Verify the nonce
			if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'update_imagecomply_status')) {
				// Run the update function
				$this->update_imagecomply_status_for_media_items();
			} else {
				echo '<p>Nonce verification failed.</p>';
			}
		}
	}


	public function update_imagecomply_status_for_media_items()
	{
		$media_query = new WP_Query(
			array(
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'post_status'    => 'inherit',
				'posts_per_page' => -1,
			)
		);

		if ($media_query->have_posts()) {
			while ($media_query->have_posts()) {
				$media_query->the_post();
				$attachment_id = get_the_ID();

				echo '<p>Attachment ID: ' . esc_html($attachment_id) . '</p>';

				$alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

				echo '<p>Updating ImageComply status for attachment ID: ' . esc_html($attachment_id) . '</p>';

				if (!empty($alt_text)) {
					update_post_meta($attachment_id, 'imagecomply_alt_text_status', 'complete-manual');
				} else {
					update_post_meta($attachment_id, 'imagecomply_alt_text_status', 'incomplete');
				}
			}

			wp_reset_postdata();
		}
	}
}

$imagecomply = new ImageComply();
