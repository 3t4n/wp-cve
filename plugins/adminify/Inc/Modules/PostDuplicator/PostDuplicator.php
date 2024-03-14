<?php

namespace WPAdminify\Inc\Modules\PostDuplicator;

use WPAdminify\Inc\Admin\AdminSettings;
// no direct access allowed
if (!defined('ABSPATH')) {
	exit;
}

/**
 * WP Adminify
 *
 * @package Duplicate Post/Page/Custom Posts
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */


if (!defined('ABSPATH')) {
	exit; // If this file is called directly, abort.
}

class PostDuplicator
{

	private $clone_title;
	public $options = [];

	public function __construct()
	{
		$this->clone_title = AdminSettings::get_instance()->get('jltwp_adminify_wl_plugin_menu_label');

		$this->options = (array) AdminSettings::get_instance()->get();

		// Check Access for User roles
		$restrict_for = !empty($this->options['adminify_clone_post_user_roles']) ? $this->options['adminify_clone_post_user_roles'] : '';
		if ($restrict_for) {
			return;
		}
		add_action('admin_init', [$this, 'adminify_post_duplicator_init']);
	}

	public function adminify_post_duplicator_init()
	{
		$post_types = [];
		if (!empty($this->options['adminify_clone_post_posts'])) {
			$post_types = $this->options['adminify_clone_post_posts'];
		}
		$all_post_types = get_post_types();

		if (empty($post_types) || empty(array_intersect($all_post_types, $post_types))) {
			return;
		}

		add_action('admin_action_adminify_duplicate', [$this, 'adminify_duplicate_post_as_draft']);
		foreach ($post_types as $key => $post_type) {
			if (in_array($post_type, $all_post_types)) {
				add_filter($post_type . '_row_actions', [$this, 'jltma_duplicator_row_actions'], 10, 2);
			}
		}
	}

	/*
	 * Add the duplicate link to action list for post_row_actions
	 */
	public function jltma_duplicator_row_actions($actions, $post)
	{
		if (current_user_can('edit_posts')) {
			$adminify_duplicate_link = admin_url('admin.php?action=adminify_duplicate&post=' . $post->ID);
			$adminify_duplicate_link = wp_nonce_url($adminify_duplicate_link, 'jltma_post_duplicator_nonce');

			$clone_title                   = !empty($this->clone_title) ? $this->clone_title : 'Adminify';
			$clone_title                   = preg_replace('/wp/i', '', $clone_title) . ' Clone';
			$duplicate_title               = 'Duplicate this item ' . $post->post_title;
			$actions['adminify_duplicate'] = sprintf(__('<a href="%1$s" title="%2$s" rel="permalink">%3$s</a>', 'adminify'), $adminify_duplicate_link, $duplicate_title, $clone_title);
		}
		return $actions;
	}


	/*
	 * Function creates post duplicate as a draft and redirects then to the edit post screen
	 */
	public function adminify_duplicate_post_as_draft()
	{
		global $wpdb;

		if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'adminify_duplicate' == $_REQUEST['action']))) {
			wp_die('No post to duplicate has been supplied!');
		}

		/*
		 * Nonce verification
		 * Return if nonce is not valid
		 */
		if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'jltma_post_duplicator_nonce')) {
			return;
		}

		/*
		 * get the original post id
		 */
		$post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
		/*
		 * and all the original post data then
		 */
		$post = get_post($post_id);

		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if post data exists, create the post duplicate
		 */
		if (isset($post) && $post != null) {

			/*
			 * new post data array
			 */
			$args = [
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_author'    => $new_post_author,
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			];

			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post($args);

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'slugs']);
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}

			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos) != 0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;

					// Do not copy these values
					if ($meta_key == '_wp_old_slug') {
						continue;
					}
					if ($meta_key == '_elementor_css') {
						continue;
					}

					// Delete already added meta data triggered by wp_insert_post hook
					if ($meta_key == '_elementor_template_type') {
						delete_post_meta($new_post_id, '_elementor_template_type');
					}

					$meta_value      = $meta_info->meta_value;
					$sql_query_sel[] = $wpdb->prepare('SELECT %d, %s, %s', $new_post_id, $meta_key, $meta_value);
				}
				$sql_query .= implode(' UNION ALL ', $sql_query_sel);
				$wpdb->query($sql_query);
			}

			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			$redirect_url = admin_url('edit.php?post_type=' . $post->post_type);
			wp_safe_redirect($redirect_url);

			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . absint($post_id));
		}
	}
}
