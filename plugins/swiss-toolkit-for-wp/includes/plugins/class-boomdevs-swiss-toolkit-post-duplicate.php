<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

// Include the plugin's settings class
require_once BDSTFW_SWISS_TOOLKIT_PATH . 'includes/class-boomdevs-swiss-toolkit-settings.php';

/**
 * BDSTFW_Swiss_Toolkit_Post_Duplicate
 *
 * This class provides functionality for duplicating posts and pages
 * when the corresponding setting is enabled in the plugin settings.
 */
if (!class_exists('BDSTFW_Swiss_Toolkit_Post_Duplicate')) {
	class BDSTFW_Swiss_Toolkit_Post_Duplicate
	{
		/**
		 * The single instance of the class.
		 */
		protected static $instance;

		/**
		 * Constructor.
		 *
		 * Initializes the class and adds actions and filters for post/page duplication.
		 */
		public function __construct()
		{
			// Get the plugin settings
			$settings = BDSTFW_Swiss_Toolkit_Settings::get_settings();

			// Check if the post/page duplicator is enabled in settings
			if (isset($settings['boomdevs_swiss_Post_Page_duplicator']) && $settings['boomdevs_swiss_Post_Page_duplicator'] === '1') {
				// Add duplication functionality when enabled
				add_filter('post_row_actions', [$this, 'swiss_duplicate_post_link'], 10, 2);
				add_filter('page_row_actions', [$this, 'swiss_duplicate_post_link'], 10, 2);
				add_action('admin_notices', [$this, 'swiss_duplication_admin_notice']);
				add_action('wp_ajax_swiss_knife_duplicate_post', [$this, 'swiss_knife_duplicate_post']); // Executed when logged in
				add_action('wp_ajax_nopriv_swiss_knife_duplicate_post', [$this, 'swiss_knife_duplicate_post']); // Executed when logged out
			}
		}

		/**
		 * Returns single instance of the class
		 */
		public static function get_instance()
		{
			if (is_null(self::$instance)) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Add Duplicate Link to Post/Page Row Actions
		 *
		 * Adds a "Duplicate" link to the row actions for posts and pages if the current user has the capability to edit posts.
		 *
		 * @param array $actions An array of row action links.
		 * @param WP_Post $post The current post object.
		 *
		 * @return array Modified array of row action links.
		 */
		public function swiss_duplicate_post_link($actions, $post)
		{
			// Check if the current user has the capability to edit posts
			if (!current_user_can('edit_posts')) {
				return $actions;
			}

			// Generate a URL for duplicating the post with a nonce
			$url = wp_nonce_url(
				add_query_arg(
					[
						'action' => 'swiss_duplicate_post_as_draft',
						'post' => $post->ID,
					],
					'admin.php'
				),
				basename(__FILE__),
				'duplicate_nonce'
			);

			// Add the "Duplicate" action link to the row actions
			$actions['duplicate'] = '<a class="duplicate_post" data-postId="' . esc_attr($post->ID) . '" href="' . esc_url($url) . '" title="' . esc_attr(__('Duplicate this item', 'swiss-toolkit-for-wp')) . '" rel="permalink">' . esc_html(__('Duplicate', 'swiss-toolkit-for-wp')) . '</a>';

			return $actions;
		}

		/**
		 * Duplicate Post as Draft
		 *
		 * Duplicates the selected post as a draft with the same content and metadata.
		 * Redirects to the post list with a success message or displays an error message if the post cannot be duplicated.
		 */
		function swiss_knife_duplicate_post($original_id, $args = array())
		{
			// Verify nonce
			if (!isset($_POST['nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce']), 'generate_login_url'))) {
				$error = new WP_Error('404', esc_html__('Nonce verification faild. Please try again.', 'swiss-toolkit-for-wp'));
				wp_send_json_error($error);
			}

			// Get post by given post id
			$post_id = absint($_POST['postid']);
			$post = get_post($post_id);

			// Get the current user's ID as the new post author
			$current_user = wp_get_current_user();
			$new_post_author = $current_user->ID;

			// Rearrange post arguments
			$post = [
				'comment_status' => $post->comment_status,
				'ping_status' => $post->ping_status,
				'post_author' => $new_post_author,
				'post_content' => addslashes($post->post_content),
				'post_excerpt' => $post->post_excerpt,
				'post_name' => $post->post_name,
				'post_parent' => $post->post_parent,
				'post_password' => $post->post_password,
				'post_status' => 'draft',
				'post_title' => $post->post_title . ' - Copy',
				'post_type' => $post->post_type,
				'to_ping' => $post->to_ping,
				'menu_order' => $post->menu_order,
			];

			// Insert new post
			$new_post_id = wp_insert_post($post);

			// Initialize the database
			global $wpdb;
			$table = $wpdb->postmeta;
			$meta_data = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value FROM $table WHERE post_id = %d",
					$post_id,
				)
			);

			foreach ($meta_data as $meta) {
				$meta_key = $meta->meta_key;
				$meta_value = maybe_unserialize($meta->meta_value);
				add_post_meta($new_post_id, $meta_key, $meta_value);
			}

			$taxonomies = get_post_taxonomies($post_id);
			if ($taxonomies) {
				foreach ($taxonomies as $taxonomy) {
					wp_set_object_terms(
						$new_post_id,
						wp_get_object_terms(
							$post_id,
							$taxonomy,
							['fields' => 'ids']
						),
						$taxonomy
					);
				}
			}

			// Check if the post is inserted successfully and append array
			if (is_numeric($new_post_id)) {
				// Initialize upload directory for css file copy to newly created post
				$uploadsDirOld = esc_url_raw(wp_upload_dir()['basedir']);
				$uploadsDirNew = esc_url_raw(wp_upload_dir()['basedir']);

				// SeedProd Premium CSS files
				$oldCssFile = $uploadsDirOld . '/seedprod-css/style-' . $post_id . '.css';
				$newCssFile = $uploadsDirNew . '/seedprod-css/style-' . $new_post_id . '.css';

				if (file_exists($oldCssFile) && is_file($oldCssFile)) {
					@copy($oldCssFile, $newCssFile);
				}

				// Elementor cached CSS
				$oldCssFile = $uploadsDirOld . '/elementor/css/post-' . $post_id . '.css';
				$newCssFile = $uploadsDirNew . '/elementor/css/post-' . $new_post_id . '.css';
				if (file_exists($oldCssFile) && is_file($oldCssFile)) {
					$customCssContent = file_get_contents($oldCssFile);
					$customCssContent = str_replace('-' . $post_id, '-' . $new_post_id, $customCssContent);
					file_put_contents($newCssFile, $customCssContent);
					unset($customCssContent);
				}

				$data = array(
					'duplicate_id' => esc_attr($new_post_id),
				);
				wp_send_json($data);
			} else {
				$error = new WP_Error('404', esc_html__('Post duplication failed.', 'swiss-toolkit-for-wp'));
				wp_send_json_error($error);
			}
		}

		/**
		 * Display Admin Notice for Post Duplication
		 *
		 * Displays a success notice when a post has been successfully duplicated.
		 * The notice is shown on the "edit" screen in the WordPress admin area.
		 */
		public function swiss_duplication_admin_notice()
		{
			// Get the current screen
			$screen = get_current_screen();

			// Check if the current screen is the "edit" screen
			if ('edit' !== $screen->base) {
				return;
			}

			// Check if the "saved" parameter in the URL indicates successful post duplication
			if (isset($_GET['duplicated'])) {
				// Display a success notice with a dismiss button
				echo wp_kses('<div class="notice notice-success is-dismissible"><p>' . esc_html__('Post duplicated successfully.', 'swiss-toolkit-for-wp') . '</p></div>', array(
					'div' => array(
						'class' => true,
						'style' => true
					),
					'p' => array(
						'class' => true,
						'style' => true
					)
				));
			}
		}
	}

	// Initialize the BDSTFW_Swiss_Toolkit_Post_Duplicate class
	BDSTFW_Swiss_Toolkit_Post_Duplicate::get_instance();
}
