<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Slim SEO migration
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_slim_seo_migration() {
	siteseo_check_ajax_referer('siteseo_slim_seo_migrate_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'migration')) && is_admin()) {
		if (isset($_POST['offset']) && isset($_POST['offset'])) {
			$offset = absint(siteseo_opt_post('offset'));
		}

		global $wpdb;
		$total_count_posts = (int) $wpdb->get_var("SELECT count(*) FROM {$wpdb->posts}");
		$total_count_terms = (int) $wpdb->get_var("SELECT count(*) FROM {$wpdb->terms}");

		$increment = 200;
		global $post;

		if ($offset > $total_count_posts) {
			wp_reset_query();
			$count_items = $total_count_posts;

			$args = [
				//'number' => $increment,
				'hide_empty' => false,
				//'offset' => $offset,
				'fields' => 'ids',
			];
			$slim_seo_query_terms = get_terms($args);

			if ($slim_seo_query_terms) {
				foreach ($slim_seo_query_terms as $term_id) {
					if ('' != get_term_meta($term_id, 'slim_seo', true)) {
						$term_settings = get_term_meta($term_id, 'slim_seo', true);

						if ( ! empty($term_settings['title'])) { //Import title tag
							update_term_meta($term_id, '_siteseo_titles_title', $term_settings['title']);
						}
						if ( ! empty($term_settings['description'])) { //Import meta desc
							update_term_meta($term_id, '_siteseo_titles_desc', $term_settings['description']);
						}
						if ( ! empty($term_settings['noindex'])) { //Import Robots NoIndex
							update_term_meta($term_id, '_siteseo_robots_index', 'yes');
						}
						if ( ! empty($term_settings['facebook_image'])) { //Import FB image
							update_term_meta($term_id, '_siteseo_social_fb_img', $term_settings['facebook_image']);
						}
						if ( ! empty($term_settings['twitter_image'])) { //Import Tw image
							update_term_meta($term_id, '_siteseo_social_twitter_img', $term_settings['twitter_image']);
						}
					}
				}
			}
			$offset = 'done';
			wp_reset_query();
		} else {
			$args = [
				'posts_per_page' => $increment,
				'post_type'	  => 'any',
				'post_status'	=> 'any',
				'offset'		 => $offset,
			];

			$slim_seo_query = get_posts($args);

			if ($slim_seo_query) {
				foreach ($slim_seo_query as $post) {
					if ('' != get_post_meta($post->ID, 'slim_seo', true)) {
						$post_settings = get_post_meta($post->ID, 'slim_seo', true);

						if ( ! empty($post_settings['title'])) { //Import title tag
							update_post_meta($post->ID, '_siteseo_titles_title', $post_settings['title']);
						}
						if ( ! empty($post_settings['description'])) { //Import meta desc
							update_post_meta($post->ID, '_siteseo_titles_desc', $post_settings['description']);
						}
						if ( ! empty($post_settings['noindex'])) { //Import Robots NoIndex
							update_post_meta($post->ID, '_siteseo_robots_index', 'yes');
						}
						if ( ! empty($post_settings['facebook_image'])) { //Import FB image
							update_post_meta($post->ID, '_siteseo_social_fb_img', $post_settings['facebook_image']);
						}
						if ( ! empty($post_settings['twitter_image'])) { //Import Tw image
							update_post_meta($post->ID, '_siteseo_social_twitter_img', $post_settings['twitter_image']);
						}
					}
				}
			}
			$offset += $increment;

			if ($offset >= $total_count_posts) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}
		}
		$data		   = [];

		$data['count']		  = $count_items;
		$data['total']		  = $total_count_posts + $total_count_terms;

		$data['offset'] = $offset;
		wp_send_json_success($data);
		exit();
	}
}
add_action('wp_ajax_siteseo_slim_seo_migration', 'siteseo_slim_seo_migration');
