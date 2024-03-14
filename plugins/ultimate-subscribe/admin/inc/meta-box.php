<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register a meta box using a class.
 */
class Ultimate_Subscribe_Meta_Box {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if (is_admin()) {
			add_action('load-post.php', array($this, 'init_metabox'));
			add_action('load-post-new.php', array($this, 'init_metabox'));
		}

	}

	/**
	 * Meta box initialization.
	 */
	public function init_metabox() {
		add_action('add_meta_boxes', array($this, 'add_metabox'));
		add_action('save_post', array($this, 'save_metabox'), 10, 2);

		add_action( 'ultimate_subscribe_process_forms', 'Ultimate_Subscribe_Forms_Details::save', 10, 2);
		add_action( 'ultimate_subscribe_process_forms', 'Ultimate_Subscribe_Forms_Settings::save', 15, 2);
		add_action( 'ultimate_subscribe_process_forms', 'Ultimate_Subscribe_Popup_Settings::save', 20, 2);
		
		
		
		
	}

	/**
	 * Adds the meta box.
	 */
	public function add_metabox($post_type) {
		global $post;
		add_meta_box( 'ultimate-subscribe-forms', __( 'Form Details', 'ultimate-subscribe' ), 'Ultimate_Subscribe_Forms_Details::output', 'u_subscribe_forms', 'advanced', 'low' );
		add_meta_box( 'ultimate-subscribe-settings', __( 'Form Settings', 'ultimate-subscribe' ), 'Ultimate_Subscribe_Forms_Settings::output', 'u_subscribe_forms', 'advanced', 'low' );
		add_meta_box( 'ultimate-subscribe-popup', __( 'Popup Settings', 'ultimate-subscribe' ), 'Ultimate_Subscribe_Popup_Settings::output', 'u_subscribe_forms', 'advanced', 'low' );
		add_meta_box( 'ultimate-subscribe-info', __( 'Form Info', 'ultimate-subscribe' ), 'Ultimate_Subscribe_Forms_Info::output', 'u_subscribe_forms', 'side', 'low' );
	}

	/**
	 * Renders the meta box.
	 */
	public function render_metabox($post) {
		
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @return null
	 */
	public function save_metabox($post_id, $post) {
		// Add nonce for security and authentication.
		$nonce_name   = isset($_POST['ultimate_subscribe_nonce']) ? $_POST['ultimate_subscribe_nonce'] : '';
		$nonce_action = 'ultimate_subscribe_save_meta_data_nonce';

		// Check if nonce is set.
		if (!isset($nonce_name)) {
			return;
		}

		// Check if nonce is valid.
		if (!wp_verify_nonce($nonce_name, $nonce_action)) {
			return;
		}

		// Check if user has permissions to save data.
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}

		// Check if not an autosave.
		if (wp_is_post_autosave($post_id)) {
			return;
		}

		// Check if not a revision.
		if (wp_is_post_revision($post_id)) {
			return;
		}

		if($post->post_type == 'u_subscribe_forms'){
			do_action( 'ultimate_subscribe_process_forms', $post_id, $post );
		}

	}
}

new Ultimate_Subscribe_Meta_Box();
