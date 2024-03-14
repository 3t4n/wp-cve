<?php
if(! defined('ABSPATH')){
	exit;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Export SiteSEO metadata to CSV
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_metadata_export() {
	siteseo_check_ajax_referer('siteseo_export_csv_metadata_nonce');

	if ( ! is_admin()) {
		wp_send_json_error();

		return;
	}

	if ( ! current_user_can(siteseo_capability('manage_options', 'migration'))) {
		wp_send_json_error();

		return;
	}

	if (isset($_POST['offset'])) {
		$offset = absint(siteseo_opt_post('offset'));
	}

	$post_export = '';
	if (isset($_POST['post_export'])) {
		$post_export = siteseo_opt_post('post_export');
	}

	$term_export = '';
	if (isset($_POST['term_export'])) {
		$term_export = siteseo_opt_post('term_export');
	}

	//Get post types
	$siteseo_get_post_types = [];
	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
		$siteseo_get_post_types[] = $siteseo_cpt_key;
	}

	//Get taxonomies
	$siteseo_get_taxonomies = [];
	foreach (siteseo_get_service('WordPressData')->getTaxonomies() as $siteseo_tax_key => $siteseo_tax_value) {
		$siteseo_get_taxonomies[] = $siteseo_tax_key;
	}

	global $wpdb;
	global $post;

	//Count posts
	$count_items = 0;
	$i	 = 1;
	$sql   = '(';
	$count = count($siteseo_get_post_types);
	foreach ($siteseo_get_post_types as $cpt) {
		$sql .= '(post_type = "' . $cpt . '")';

		if ($i < $count) {
			$sql .= ' OR ';
		}

		++$i;
	}
	$sql .= ')';

	$total_count_posts = (int) $wpdb->get_var("SELECT count(*)
	FROM {$wpdb->posts}
	WHERE $sql
	AND (post_status = 'publish' OR post_status = 'pending' OR post_status = 'draft' OR post_status = 'auto-draft' OR post_status = 'future' OR post_status = 'private' OR post_status = 'inherit' OR post_status = 'trash') ");

	//Count terms
	$total_count_terms = (int) $wpdb->get_var("SELECT count(*) FROM {$wpdb->terms}");

	$increment = 200;

	$csv = '';
	$csv = get_option('siteseo_metadata_csv');
	$download_url = '';

	$settings['id'] = [];
	$settings['post_title'] = [];
	$settings['url'] = [];
	$settings['meta_title'] = [];
	$settings['meta_desc'] = [];
	$settings['fb_title'] = [];
	$settings['fb_desc'] = [];
	$settings['fb_img'] = [];
	$settings['tw_title'] = [];
	$settings['tw_desc'] = [];
	$settings['tw_img'] = [];
	$settings['noindex'] = [];
	$settings['nofollow'] = [];
	$settings['noimageindex'] = [];
	$settings['noarchive'] = [];
	$settings['nosnippet'] = [];
	$settings['canonical_url'] = [];
	$settings['primary_cat'] = [];
	$settings['redirect_active'] = [];
	$settings['redirect_status'] = [];
	$settings['redirect_type'] = [];
	$settings['redirect_url'] = [];
	$settings['target_kw'] = [];

	$metas_key = [
		'meta_title' => '_siteseo_titles_title',
		'meta_desc' => '_siteseo_titles_desc',
		'fb_title' => '_siteseo_social_fb_title',
		'fb_desc' => '_siteseo_social_fb_desc',
		'fb_img' => '_siteseo_social_fb_img',
		'tw_title' => '_siteseo_social_twitter_title',
		'tw_desc' => '_siteseo_social_twitter_desc',
		'tw_img' => '_siteseo_social_twitter_img',
		'noindex' => '_siteseo_robots_index',
		'nofollow' => '_siteseo_robots_follow',
		'noimageindex' => '_siteseo_robots_imageindex',
		'noarchive' => '_siteseo_robots_archive',
		'nosnippet' => '_siteseo_robots_snippet',
		'canonical_url' => '_siteseo_robots_canonical',
		'primary_cat' => '_siteseo_robots_primary_cat',
		'redirect_active' => '_siteseo_redirections_enabled',
		'redirect_status' => '_siteseo_redirections_logged_status',
		'redirect_type' => '_siteseo_redirections_type',
		'redirect_url' => '_siteseo_redirections_value',
		'target_kw' => '_siteseo_analysis_target_kw',
	];

	//Posts
	if ('done' != $post_export) {
		if ($offset > $total_count_posts) {
			wp_reset_query();
			$count_items = $total_count_posts;
			//Reset offset once Posts export is done
			$offset = 0;
			update_option('siteseo_metadata_csv', $csv, false);
			$post_export = 'done';
		} else {
			$args = [
				'post_type'	  => $siteseo_get_post_types,
				'posts_per_page' => $increment,
				'offset'		 => $offset,
				'post_status'	=> 'any',
				'order'		  => 'DESC',
				'orderby'		=> 'date',
			];
			$args	   = apply_filters('siteseo_metadata_query_args', $args, $siteseo_get_post_types, $increment, $offset);
			$meta_query = get_posts($args);

			if ($meta_query) {
				// The Loop
				foreach ($meta_query as $post) {
					array_push($settings['id'], $post->ID);

					array_push($settings['post_title'], $post->post_title);

					array_push($settings['url'], get_permalink($post));

					foreach($metas_key as $key => $meta_key) {
						if (get_post_meta($post->ID, $meta_key, true)) {
							array_push($settings[$key], get_post_meta($post->ID, $meta_key, true));
						} else {
							array_push($settings[$key], '');
						}
					}

					$csv[] = array_merge(
						$settings['id'],
						$settings['post_title'],
						$settings['url'],
						$settings['meta_title'],
						$settings['meta_desc'],
						$settings['fb_title'],
						$settings['fb_desc'],
						$settings['fb_img'],
						$settings['tw_title'],
						$settings['tw_desc'],
						$settings['tw_img'],
						$settings['noindex'],
						$settings['nofollow'],
						$settings['noimageindex'],
						$settings['noarchive'],
						$settings['nosnippet'],
						$settings['canonical_url'],
						$settings['primary_cat'],
						$settings['redirect_active'],
						$settings['redirect_status'],
						$settings['redirect_type'],
						$settings['redirect_url'],
						$settings['target_kw']
					);

					//Clean arrays
					$settings['id'] = [];
					$settings['post_title'] = [];
					$settings['url'] = [];
					$settings['meta_title'] = [];
					$settings['meta_desc'] = [];
					$settings['fb_title'] = [];
					$settings['fb_desc'] = [];
					$settings['fb_img'] = [];
					$settings['tw_title'] = [];
					$settings['tw_desc'] = [];
					$settings['tw_img'] = [];
					$settings['noindex'] = [];
					$settings['nofollow'] = [];
					$settings['noimageindex'] = [];
					$settings['noarchive'] = [];
					$settings['nosnippet'] = [];
					$settings['canonical_url'] = [];
					$settings['primary_cat'] = [];
					$settings['redirect_active'] = [];
					$settings['redirect_status'] = [];
					$settings['redirect_type'] = [];
					$settings['redirect_url'] = [];
					$settings['target_kw'] = [];
				}
			}
			$offset += $increment;

			if ($offset >= $total_count_posts) {
				$count_items = $total_count_posts;
			} else {
				$count_items = $offset;
			}

			update_option('siteseo_metadata_csv', $csv, false);
		}
	} elseif ('done' != $term_export) {
		//Terms
		if ($offset > $total_count_terms) {
			$count_items = $total_count_terms + $total_count_posts;
			update_option('siteseo_metadata_csv', $csv, false);
			$post_export = 'done';
			$term_export = 'done';
		} else {
			$args = [
				'taxonomy'   => $siteseo_get_taxonomies,
				'number'	 => $increment,
				'offset'	 => $offset,
				'order'	  => 'DESC',
				'orderby'	=> 'date',
				'hide_empty' => false,
			];

			$args = apply_filters('siteseo_metadata_query_terms_args', $args, $siteseo_get_taxonomies, $increment, $offset);

			$meta_query = get_terms($args);

			if ($meta_query) {
				// The Loop
				foreach ($meta_query as $term) {
					array_push($settings['id'], $term->term_id);

					array_push($settings['post_title'], $term->name);

					array_push($settings['url'], get_term_link($term));

					foreach($metas_key as $key => $meta_key) {
						if (get_term_meta($term->term_id, $meta_key, true)) {
							array_push($settings[$key], get_term_meta($term->term_id, $meta_key, true));
						} else {
							array_push($settings[$key], '');
						}
					}

					$csv[] = array_merge(
						$settings['id'],
						$settings['post_title'],
						$settings['url'],
						$settings['meta_title'],
						$settings['meta_desc'],
						$settings['fb_title'],
						$settings['fb_desc'],
						$settings['fb_img'],
						$settings['tw_title'],
						$settings['tw_desc'],
						$settings['tw_img'],
						$settings['noindex'],
						$settings['nofollow'],
						$settings['noimageindex'],
						$settings['noarchive'],
						$settings['nosnippet'],
						$settings['canonical_url'],
						$settings['primary_cat'],
						$settings['redirect_active'],
						$settings['redirect_status'],
						$settings['redirect_type'],
						$settings['redirect_url'],
						$settings['target_kw']
					);

					//Clean arrays
					$settings['id'] = [];
					$settings['post_title'] = [];
					$settings['url'] = [];
					$settings['meta_title'] = [];
					$settings['meta_desc'] = [];
					$settings['fb_title'] = [];
					$settings['fb_desc'] = [];
					$settings['fb_img'] = [];
					$settings['tw_title'] = [];
					$settings['tw_desc'] = [];
					$settings['tw_img'] = [];
					$settings['noindex'] = [];
					$settings['nofollow'] = [];
					$settings['noimageindex'] = [];
					$settings['noarchive'] = [];
					$settings['nosnippet'] = [];
					$settings['canonical_url'] = [];
					$settings['primary_cat'] = [];
					$settings['redirect_active'] = [];
					$settings['redirect_status'] = [];
					$settings['redirect_type'] = [];
					$settings['redirect_url'] = [];
					$settings['target_kw'] = [];
				}
			}

			$offset += $increment;

			if ($offset >= $total_count_terms) {
				$count_items = $total_count_terms + $total_count_posts;
			} elseif ($offset === 200) {
				$count_items = $total_count_posts + 200;
			} else {
				$count_items += $offset;
			}
			$post_export = 'done';
			update_option('siteseo_metadata_csv', $csv, false);
		}
	} else {
		$post_export = 'done';
		$term_export = 'done';
	}

	//Create download URL
	if ('done' === $post_export && 'done' === $term_export) {
		$post_data['action'] = siteseo_opt_post('action');
		$post_data['offset'] = siteseo_opt_post('offset');
		$post_data['post_export'] = siteseo_opt_post('post_export');
		$post_data['term_export'] = siteseo_opt_post('term_export');
		$post_data['_ajax_nonce'] = siteseo_opt_post('_ajax_nonce');

		$args = array_merge($post_data, [
			'nonce' => wp_create_nonce('siteseo_csv_batch_export_nonce'),
			'page' => 'siteseo-import-export',
			'siteseo_action' => 'siteseo_download_batch_export',
		]);

		$download_url = add_query_arg($args, admin_url('admin.php'));

		$offset = 'done';
	}

	//Return data to JSON
	$data				   = [];

	$data['count']		  = $count_items;
	$data['total']		  = $total_count_posts + $total_count_terms;

	$data['offset']		 = $offset;
	$data['url']			= $download_url;
	$data['post_export']	= $post_export;
	$data['term_export']	= $term_export;
	wp_send_json_success($data);
}

add_action('wp_ajax_siteseo_metadata_export', 'siteseo_metadata_export');
