<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get real preview + content analysis
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_do_real_preview()
{
	$docs = siteseo_get_docs_links();

	siteseo_check_ajax_referer('siteseo_real_preview_nonce');

	if (current_user_can('edit_posts') && is_admin()) {
		//Get cookies
		if (isset($_COOKIE)) {
			$cookies = [];

			foreach ($_COOKIE as $name => $value) {
				if ('PHPSESSID' !== $name) {
					$cookies[] = new WP_Http_Cookie(['name' => $name, 'value' => $value]);
				}
			}
		}

		//Get post id
		if (isset($_GET['post_id'])) {
			$siteseo_get_the_id = siteseo_opt_get('post_id');
		}

		if ('yes' == get_post_meta($siteseo_get_the_id, '_siteseo_redirections_enabled', true)) {
			$data['title'] = __('A redirect is active for this URL. Turn it off to get the Google preview and content analysis.', 'siteseo');
		} else {
			//Get cookies
			if (isset($_COOKIE)) {
				$cookies = [];

				foreach ($_COOKIE as $name => $value) {
					if ('PHPSESSID' !== $name) {
						$cookies[] = new WP_Http_Cookie(['name' => $name, 'value' => $value]);
					}
				}
			}

			//Get post type
			if (isset($_GET['post_type'])) {
				$siteseo_get_post_type = siteseo_opt_get('post_type');
			} else {
				$siteseo_get_post_type = null;
			}

			//Origin
			if (isset($_GET['origin'])) {
				$siteseo_origin = siteseo_opt_get('origin');
			}

			//Tax name
			if (isset($_GET['tax_name'])) {
				$siteseo_tax_name = siteseo_opt_get('tax_name');
			}

			//Init
			$title		= '';
			$meta_desc	= '';
			$link		= '';
			$data		= [];

			//Save Target KWs
			if (! isset($_GET['is_elementor'])) {
				if (isset($_GET['siteseo_analysis_target_kw'])) {
					delete_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw');
					update_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw', siteseo_opt_get('siteseo_analysis_target_kw') );
				}
			}

			//Fix Elementor
			if (isset($_GET['is_elementor']) && true == $_GET['is_elementor']) {
				$_GET['siteseo_analysis_target_kw'] = get_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw', true);
			}

			//DOM
			$dom					= new DOMDocument();
			$internalErrors			= libxml_use_internal_errors(true);
			$dom->preserveWhiteSpace = false;

			//Get source code
			$args = [
				'blocking'	=> true,
				'timeout'	 => 30,
				'sslverify'   => false,
			];

			if (isset($cookies) && ! empty($cookies)) {
				$args['cookies'] = $cookies;
			}
			$args = apply_filters('siteseo_real_preview_remote', $args);

			$data['title'] = $cookies;

			if ('post' == $siteseo_origin) { //Default: post type
				//Oxygen compatibility
				if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
					$link = get_permalink((int) $siteseo_get_the_id);
					$link = add_query_arg('no_admin_bar', 1, $link);

					$response = wp_remote_get($link, $args);
					if (200 !== wp_remote_retrieve_response_code($response)) {
						$link = get_permalink((int) $siteseo_get_the_id);
						$response = wp_remote_get($link, $args);
					}
				} else {
					$custom_args = ['no_admin_bar' => 1];

					//Useful for Page / Theme builders
					$custom_args = apply_filters('siteseo_real_preview_custom_args', $custom_args);

					$link = add_query_arg('no_admin_bar', 1, get_preview_post_link((int) $siteseo_get_the_id, $custom_args));

					$link = apply_filters('siteseo_get_dom_link', $link, $siteseo_get_the_id);

					$response = wp_remote_get($link, $args);
				}
			} else { //Term taxonomy
				$link = get_term_link((int) $siteseo_get_the_id, $siteseo_tax_name);
				$response = wp_remote_get($link, $args);
			}

			//Check for error
			if (is_wp_error($response) || '404' == wp_remote_retrieve_response_code($response)) {
				$data['title'] = __('To get your Google snippet preview, publish your post!', 'siteseo');
			} elseif (is_wp_error($response) || '401' == wp_remote_retrieve_response_code($response)) {
				$data['title']				   = sprintf(__('Your site is protected by an authentication. <a href="%s" target="_blank">Fix this</a> <span class="dashicons dashicons-external"></span>', 'siteseo'), $docs['google_preview']['authentification']);
			} else {
				$response = wp_remote_retrieve_body($response);

				if ($dom->loadHTML('<?xml encoding="utf-8" ?>' . $response)) {
					if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
						$data = get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data', true) ? get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data', true) : $data = [];

						if (! empty($data)) {
							$data = array_slice($data, 0, 3);
						}
					}

					$data['link_preview'] = $link;

					//Disable wptexturize
					add_filter('run_wptexturize', '__return_false');

					//Get post content (used for Words counter)
					$siteseo_get_the_content = get_post_field('post_content', $siteseo_get_the_id);
					$siteseo_get_the_content = apply_filters('siteseo_dom_analysis_get_post_content', $siteseo_get_the_content);

					//Cornerstone compatibility
					if (is_plugin_active('cornerstone/cornerstone.php')) {
						$siteseo_get_the_content = get_post_field('post_content', $siteseo_get_the_id);
					}

					//ThriveBuilder compatibility
					if (is_plugin_active('thrive-visual-editor/thrive-visual-editor.php') && empty($siteseo_get_the_content)) {
						$siteseo_get_the_content = get_post_meta($siteseo_get_the_id, 'tve_updated_post', true);
					}

					//Zion Builder compatibility
					if (is_plugin_active('zionbuilder/zionbuilder.php')) {
						$siteseo_get_the_content = $siteseo_get_the_content . get_post_meta($siteseo_get_the_id, '_zionbuilder_page_elements', true);
					}

					//BeTheme is activated
					$theme = wp_get_theme();
					if ('betheme' == $theme->template || 'Betheme' == $theme->parent_theme) {
						$siteseo_get_the_content = $siteseo_get_the_content . get_post_meta($siteseo_get_the_id, 'mfn-page-items-seo', true);
					}

					//Themify compatibility
					if (defined('THEMIFY_DIR') && method_exists('ThemifyBuilder_Data_Manager', '_get_all_builder_text_content')) {
						global $ThemifyBuilder;
						$builder_data = $ThemifyBuilder->get_builder_data($siteseo_get_the_id);
						$plain_text   = \ThemifyBuilder_Data_Manager::_get_all_builder_text_content($builder_data);
						$plain_text   = do_shortcode($plain_text);

						if ('' != $plain_text) {
							$siteseo_get_the_content = $plain_text;
						}
					}

					//Add WC product excerpt
					if ('product' == $siteseo_get_post_type) {
						$siteseo_get_the_content =  $siteseo_get_the_content . get_the_excerpt($siteseo_get_the_id);
					}

					$siteseo_get_the_content = apply_filters('siteseo_content_analysis_content', $siteseo_get_the_content, $siteseo_get_the_id);

					if (defined('WP_DEBUG') && WP_DEBUG === true) {
						$data['analyzed_content'] = $siteseo_get_the_content;
					}

					//Bricks compatibility
					if (defined('BRICKS_DB_EDITOR_MODE') && ('bricks' == $theme->template || 'Bricks' == $theme->parent_theme)) {
						$page_sections = get_post_meta($siteseo_get_the_id, BRICKS_DB_PAGE_CONTENT, true);
						$editor_mode   = get_post_meta($siteseo_get_the_id, BRICKS_DB_EDITOR_MODE, true);

						if (is_array($page_sections) && 'wordpress' !== $editor_mode) {
							$siteseo_get_the_content = Bricks\Frontend::render_data($page_sections);
						}
					}

					//Get Target Keywords
					if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
						$data['target_kws'] = strtolower(siteseo_opt_get('siteseo_analysis_target_kw'));
						$siteseo_analysis_target_kw = array_filter(explode(',', strtolower(get_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw', true))));

						$siteseo_analysis_target_kw = apply_filters( 'siteseo_content_analysis_target_keywords', $siteseo_analysis_target_kw, $siteseo_get_the_id );


						$data['target_kws_count'] = siteseo_get_service('CountTargetKeywordsUse')->getCountByKeywords($siteseo_analysis_target_kw, $siteseo_get_the_id);
					}

					$xpath = new DOMXPath($dom);

					//Title
					$list = $dom->getElementsByTagName('title');
					if ($list->length > 0) {
						$title		 = $list->item(0)->textContent;
						$data['title'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($title)));
						if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . $kw . ')\b#iu', $data['title'], $m)) {
									$data['meta_title']['matches'][$kw][] = $m[0];
								}
							}
						}
					}

					//Meta desc
					$meta_description = $xpath->query('//meta[@name="description"]/@content');

					foreach ($meta_description as $key=>$mdesc) {
						$data['meta_desc'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses(wp_strip_all_tags($mdesc->nodeValue))));
					}

					if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
						if (! empty($meta_description)) {
							foreach ($meta_description as $meta_desc) {
								foreach ($siteseo_analysis_target_kw as $kw) {
									if (preg_match_all('#\b(' . $kw . ')\b#iu', $meta_desc->nodeValue, $m)) {
										$data['meta_description']['matches'][$kw][] = $m[0];
									}
								}
							}
						}
					}

					//OG:title
					$og_title = $xpath->query('//meta[@property="og:title"]/@content');

					if (! empty($og_title)) {
						$data['og_title']['count'] = count($og_title);
						foreach ($og_title as $key=>$mogtitle) {
							$data['og_title']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mogtitle->nodeValue)));
						}
					}

					//OG:description
					$og_desc = $xpath->query('//meta[@property="og:description"]/@content');

					if (! empty($og_desc)) {
						$data['og_desc']['count'] = count($og_desc);
						foreach ($og_desc as $key=>$mog_desc) {
							$data['og_desc']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_desc->nodeValue)));
						}
					}

					//OG:image
					$og_img = $xpath->query('//meta[@property="og:image"]/@content');

					if (! empty($og_img)) {
						$data['og_img']['count'] = count($og_img);
						foreach ($og_img as $key=>$mog_img) {
							$data['og_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_img->nodeValue)));
						}
					}

					//OG:url
					$og_url = $xpath->query('//meta[@property="og:url"]/@content');

					if (! empty($og_url)) {
						$data['og_url']['count'] = count($og_url);
						foreach ($og_url as $key=>$mog_url) {
							$url						= esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_url->nodeValue)));
							$data['og_url']['values'][] = $url;
							$url						= wp_parse_url($url);
							$data['og_url']['host']	 = $url['host'];
						}
					}

					//OG:site_name
					$og_site_name = $xpath->query('//meta[@property="og:site_name"]/@content');

					if (! empty($og_site_name)) {
						$data['og_site_name']['count'] = count($og_site_name);
						foreach ($og_site_name as $key=>$mog_site_name) {
							$data['og_site_name']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_site_name->nodeValue)));
						}
					}

					//Twitter:title
					$tw_title = $xpath->query('//meta[@name="twitter:title"]/@content');

					if (! empty($tw_title)) {
						$data['tw_title']['count'] = count($tw_title);
						foreach ($tw_title as $key=>$mtw_title) {
							$data['tw_title']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_title->nodeValue)));
						}
					}

					//Twitter:description
					$tw_desc = $xpath->query('//meta[@name="twitter:description"]/@content');

					if (! empty($tw_desc)) {
						$data['tw_desc']['count'] = count($tw_desc);
						foreach ($tw_desc as $key=>$mtw_desc) {
							$data['tw_desc']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_desc->nodeValue)));
						}
					}

					//Twitter:image
					$tw_img = $xpath->query('//meta[@name="twitter:image"]/@content');

					if (! empty($tw_img)) {
						$data['tw_img']['count'] = count($tw_img);
						foreach ($tw_img as $key=>$mtw_img) {
							$data['tw_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_img->nodeValue)));
						}
					}

					//Twitter:image:src
					$tw_img = $xpath->query('//meta[@name="twitter:image:src"]/@content');

					if (! empty($tw_img)) {
						$count = null;
						if (! empty($data['tw_img']['count'])) {
							$count = $data['tw_img']['count'];
						}

						$data['tw_img']['count'] = count($tw_img) + $count;

						foreach ($tw_img as $key=>$mtw_img) {
							$data['tw_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_img->nodeValue)));
						}
					}

					//Canonical
					$canonical = $xpath->query('//link[@rel="canonical"]/@href');

					foreach ($canonical as $key=>$mcanonical) {
						$data['canonical'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mcanonical->nodeValue)));
					}

					foreach ($canonical as $key=>$mcanonical) {
						$data['all_canonical'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mcanonical->nodeValue)));
					}

					//h1
					$h1 = $xpath->query('//h1');
					if (! empty($h1)) {
						$data['h1']['nomatches']['count'] = count($h1);
						if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
							foreach ($h1 as $heading1) {
								foreach ($siteseo_analysis_target_kw as $kw) {
									if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading1->nodeValue, $m)) {
										$data['h1']['matches'][$kw][] = $m[0];
									}
								}
								$data['h1']['values'][] = esc_attr($heading1->nodeValue);
							}
						}
					}

					if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
						//h2
						$h2 = $xpath->query('//h2');
						if (! empty($h2)) {
							foreach ($h2 as $heading2) {
								foreach ($siteseo_analysis_target_kw as $kw) {
									if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading2->nodeValue, $m)) {
										$data['h2']['matches'][$kw][] = $m[0];
									}
								}
							}
						}

						//h3
						$h3 = $xpath->query('//h3');
						if (! empty($h3)) {
							foreach ($h3 as $heading3) {
								foreach ($siteseo_analysis_target_kw as $kw) {
									if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading3->nodeValue, $m)) {
										$data['h3']['matches'][$kw][] = $m[0];
									}
								}
							}
						}

						//Keywords density
						if (! is_plugin_active('oxygen/functions.php') && ! function_exists('ct_template_output')) { //disable for Oxygen
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . $kw . ')\b#iu', stripslashes_deep(wp_strip_all_tags($siteseo_get_the_content)), $m)) {
									$data['kws_density']['matches'][$kw][] = $m[0];
								}
							}
						}

						//Keywords in permalink
						$post	= get_post($siteseo_get_the_id);
						$kw_slug = urldecode($post->post_name);

						if (is_plugin_active('permalink-manager-pro/permalink-manager.php')) {
							global $permalink_manager_uris;
							$kw_slug = urldecode($permalink_manager_uris[$siteseo_get_the_id]);
						}

						$kw_slug = str_replace('-', ' ', $kw_slug);

						if (isset($kw_slug)) {
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . remove_accents($kw) . ')\b#iu', strip_tags($kw_slug), $m)) {
									$data['kws_permalink']['matches'][$kw][] = $m[0];
								}
							}
						}
					}

					//Images
					/*Standard images*/
					$imgs = $xpath->query('//img');

					if (! empty($imgs) && null != $imgs) {
						//init
						$img_without_alt = [];
						$img_with_alt = [];
						foreach ($imgs as $img) {
							if ($img->hasAttribute('src')) {
								if (! preg_match_all('#\b(avatar)\b#iu', $img->getAttribute('class'), $m)) {//Exclude avatars from analysis
									if ($img->hasAttribute('width') || $img->hasAttribute('height')) {
										if ($img->getAttribute('width') > 1 || $img->getAttribute('height') > 1) {//Ignore files with width and heigh <= 1
											if ('' === $img->getAttribute('alt') || ! $img->hasAttribute('alt')) {//if alt is empty or doesn't exist
												$img_without_alt[] .= $img->getAttribute('src');
											} else {
												$img_with_alt[] .= $img->getAttribute('src');
											}
										}
									} elseif ('' === $img->getAttribute('alt') || ! $img->hasAttribute('alt')) {//if alt is empty or doesn't exist
										$img_src = download_url($img->getAttribute('src'));
										if (false === is_wp_error($img_src)) {
											if (filesize($img_src) > 100) {//Ignore files under 100 bytes
												$img_without_alt[] .= $img->getAttribute('src');
											} else {
												$img_with_alt[] .= $img->getAttribute('src');
											}
											@unlink($img_src);
										}
									}
								}
							}
							$data['img']['images']['without_alt'] = $img_without_alt;
							$data['img']['images']['with_alt'] = $img_with_alt;
						}
					}

					//Meta robots
					$meta_robots = $xpath->query('//meta[@name="robots"]/@content');
					if (! empty($meta_robots)) {
						foreach ($meta_robots as $key=>$value) {
							$data['meta_robots'][$key][] = esc_attr($value->nodeValue);
						}
					}

					//nofollow links
					$nofollow_links = $xpath->query("//a[contains(@rel, 'nofollow') and not(contains(@rel, 'ugc'))]");
					if (! empty($nofollow_links)) {
						foreach ($nofollow_links as $key=>$link) {
							if (! preg_match_all('#\b(cancel-comment-reply-link)\b#iu', $link->getAttribute('id'), $m) && ! preg_match_all('#\b(comment-reply-link)\b#iu', $link->getAttribute('class'), $m)) {
								$data['nofollow_links'][$key][$link->getAttribute('href')] = esc_attr($link->nodeValue);
							}
						}
					}
				}

				//outbound links
				$site_url	   = wp_parse_url(get_home_url(), PHP_URL_HOST);
				$outbound_links = $xpath->query("//a[not(contains(@href, '" . $site_url . "'))]");
				if (! empty($outbound_links)) {
					foreach ($outbound_links as $key=>$link) {
						if (! empty(wp_parse_url($link->getAttribute('href'), PHP_URL_HOST))) {
							$data['outbound_links'][$key][$link->getAttribute('href')] = esc_attr($link->nodeValue);
						}
					}
				}

				//Internal links
				$permalink = get_permalink((int) $siteseo_get_the_id);
				$args	  = [
					's'		 => $permalink,
					'post_type' => 'any',
				];
				$internal_links = new WP_Query($args);

				if ($internal_links->have_posts()) {
					$data['internal_links']['count'] = $internal_links->found_posts;

					while ($internal_links->have_posts()) {
						$internal_links->the_post();
						$data['internal_links']['links'][get_the_ID()] = [get_the_permalink() => get_the_title()];
					}
				}
				wp_reset_postdata();

				//Internal links for Oxygen Builder
				if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
					$args	  = [
						'posts_per_page' => -1,
						'meta_query' => [
							[
								'key' => 'ct_builder_shortcodes',
								'value' => $permalink,
								'compare' => 'LIKE'
							]
						],
						'post_type' => 'any',
					];

					$internal_links = new WP_Query($args);

					if ($internal_links->have_posts()) {
						$data['internal_links']['count'] = $internal_links->found_posts;

						while ($internal_links->have_posts()) {
							$internal_links->the_post();
							$data['internal_links']['links'][get_the_ID()] = [get_the_permalink() => get_the_title()];
						}
					}
					wp_reset_postdata();
				}

				//Words Counter
				if (! is_plugin_active('oxygen/functions.php') && ! function_exists('ct_template_output')) { //disable for Oxygen
					if ('' != $siteseo_get_the_content) {
						$data['words_counter'] = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", normalize_whitespace(wp_strip_all_tags($siteseo_get_the_content)), $matches);

						if (! empty($matches[0])) {
							$words_counter_unique = count(array_unique($matches[0]));
						} else {
							$words_counter_unique = '0';
						}
						$data['words_counter_unique'] = $words_counter_unique;
					}
				}

				//Get schemas
				$json_ld = $xpath->query('//script[@type="application/ld+json"]');
				if (! empty($json_ld)) {
					foreach ($json_ld as $node) {
						$json = json_decode($node->nodeValue, true);
						if (isset($json['@type'])) {
							$data['json'][] = $json['@type'];
						}
					}
				}
			}

			libxml_use_internal_errors($internalErrors);
		}

		//Send data
		if (isset($data)) {
			//Oxygen builder
			if (get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data_oxygen', true)) {
				$data2 = get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data_oxygen', true);
				$data  = $data + $data2;
			}
			update_post_meta($siteseo_get_the_id, '_siteseo_analysis_data', $data);
		}

		//Re-enable QM
		remove_filter('user_has_cap', 'siteseo_disable_qm', 10, 3);

		//Return
		wp_send_json_success($data);
	}
}
add_action('wp_ajax_siteseo_do_real_preview', 'siteseo_do_real_preview');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Flush permalinks
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_flush_permalinks()
{
	siteseo_check_ajax_referer('siteseo_flush_permalinks_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'flush')) && is_admin()) {
		flush_rewrite_rules(false);
		exit();
	}
}
add_action('wp_ajax_siteseo_flush_permalinks', 'siteseo_flush_permalinks');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard toggle features
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_toggle_features()
{
	siteseo_check_ajax_referer('siteseo_toggle_features_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['feature']) && isset($_POST['feature_value'])) {
			$siteseo_toggle_options					= get_option('siteseo_toggle');
			$siteseo_toggle_options[siteseo_opt_post('feature')] = siteseo_opt_post('feature_value');
			update_option('siteseo_toggle', $siteseo_toggle_options, 'yes', false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_toggle_features', 'siteseo_toggle_features');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard drag and drop features
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_dnd_features()
{
	check_ajax_referer('siteseo_dnd_features_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['order']) && !empty($_POST['order'])) {
			$cards_order = get_option('siteseo_dashboard_option_name');

			$cards_order['cards_order'] = siteseo_opt_post('order');

			update_option('siteseo_dashboard_option_name', $cards_order);
		}
	}

	wp_send_json_success();
}
add_action('wp_ajax_siteseo_dnd_features', 'siteseo_dnd_features');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard News Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_news()
{
	siteseo_check_ajax_referer('siteseo_news_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['news_max_items'])) {
			$siteseo_dashboard_option_name = get_option('siteseo_dashboard_option_name');
			$siteseo_dashboard_option_name['news_max_items']  = intval(siteseo_opt_post('news_max_items'));
			update_option('siteseo_dashboard_option_name', $siteseo_dashboard_option_name, false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_news', 'siteseo_news');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard Display Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_display()
{
	siteseo_check_ajax_referer('siteseo_display_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		//Notifications Center
		if (isset($_POST['notifications_center'])) {
			$siteseo_advanced_option_name = get_option('siteseo_advanced_option_name');

			if ('1' == $_POST['notifications_center']) {
				$siteseo_advanced_option_name['appearance_notifications'] = siteseo_opt_post('notifications_center');
			} else {
				unset($siteseo_advanced_option_name['appearance_notifications']);
			}

			update_option('siteseo_advanced_option_name', $siteseo_advanced_option_name, false);
		}
		//News Panel
		if (isset($_POST['news_center'])) {
			$siteseo_advanced_option_name = get_option('siteseo_advanced_option_name');

			if ('1' == $_POST['news_center']) {
				$siteseo_advanced_option_name['appearance_news'] = siteseo_opt_post('news_center');
			} else {
				unset($siteseo_advanced_option_name['appearance_news']);
			}

			update_option('siteseo_advanced_option_name', $siteseo_advanced_option_name, false);
		}
		//Tools Panel
		if (isset($_POST['tools_center'])) {
			$siteseo_advanced_option_name = get_option('siteseo_advanced_option_name');

			if ('1' == $_POST['tools_center']) {
				$siteseo_advanced_option_name['appearance_seo_tools'] = siteseo_opt_post('tools_center');
			} else {
				unset($siteseo_advanced_option_name['appearance_seo_tools']);
			}

			update_option('siteseo_advanced_option_name', $siteseo_advanced_option_name, false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_display', 'siteseo_display');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard hide notices
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_hide_notices()
{
	siteseo_check_ajax_referer('siteseo_hide_notices_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['notice']) && isset($_POST['notice_value'])) {
			$siteseo_notices_options = get_option('siteseo_notices');
			$siteseo_notices_options[siteseo_opt_post('notice')] = siteseo_opt_post('notice_value');
			update_option('siteseo_notices', $siteseo_notices_options, 'yes', false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_hide_notices', 'siteseo_hide_notices');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Regenerate Video XML Sitemap
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_video_xml_sitemap_regenerate()
{
	siteseo_check_ajax_referer('siteseo_video_regenerate_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'migration')) && is_admin()) {
		if (isset($_POST['offset']) && isset($_POST['offset'])) {
			$offset = absint(siteseo_opt_post('offset'));
		}

		$cpt = ['any'];
		$sitemap_post_types_list = siteseo_get_service('SitemapOption')->getPostTypesList();
		if ($sitemap_post_types_list) {
			unset($cpt[0]);
			foreach ($sitemap_post_types_list as $cpt_key => $cpt_value) {
				foreach ($cpt_value as $_cpt_key => $_cpt_value) {
					if ('1' == $_cpt_value) {
						$cpt[] = $cpt_key;
					}
				}
			}

			$cpt = array_map(function($item) {
				return "'" . esc_sql($item) . "'";
			}, $cpt);

			$cpt_string = implode(",", $cpt);
		}

		global $wpdb;
		$total_count_posts = (int) $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM {$wpdb->posts} WHERE post_status IN ('pending', 'draft', 'publish', 'future') AND post_type IN ( %s ) ", $cpt_string));

		$increment = 1;
		global $post;

		if ($offset > $total_count_posts) {
			wp_reset_query();
			$count_items = $total_count_posts;
			$offset = 'done';
		} else {
			$args = [
				'posts_per_page' => $increment,
				'post_type'	  => $cpt,
				'post_status'	=> ['pending', 'draft', 'publish', 'future'],
				'offset'		 => $offset,
			];

			$video_query = get_posts($args);

			if ($video_query) {
				foreach ($video_query as $post) {
					siteseo_pro_video_xml_sitemap($post->ID, $post);
				}
			}
			$offset += $increment;
		}
		$data		   = [];

		$data['total'] = $total_count_posts;

		if ($offset >= $total_count_posts) {
			$data['count'] = $total_count_posts;
		} else {
			$data['count'] = $offset;
		}

		$data['offset'] = $offset;

		//Clear cache
		delete_transient( '_siteseo_sitemap_ids_video' );

		wp_send_json_success($data);
		exit();
	}
}
add_action('wp_ajax_siteseo_video_xml_sitemap_regenerate', 'siteseo_video_xml_sitemap_regenerate');

require_once __DIR__ . '/ajax-migrate/smart-crawl.php';
require_once __DIR__ . '/ajax-migrate/seopressor.php';
require_once __DIR__ . '/ajax-migrate/slim-seo.php';
require_once __DIR__ . '/ajax-migrate/platinum.php';
require_once __DIR__ . '/ajax-migrate/wpseo.php';
require_once __DIR__ . '/ajax-migrate/premium-seo-pack.php';
require_once __DIR__ . '/ajax-migrate/wp-meta-seo.php';
require_once __DIR__ . '/ajax-migrate/seo-ultimate.php';
require_once __DIR__ . '/ajax-migrate/squirrly.php';
require_once __DIR__ . '/ajax-migrate/seo-framework.php';
require_once __DIR__ . '/ajax-migrate/yoast.php';
require_once __DIR__ . '/export/csv.php';
