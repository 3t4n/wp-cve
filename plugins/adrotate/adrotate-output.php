<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2024 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a registered trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from its use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_ad
 Purpose:   Show requested ad
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad($banner_id, $opt = null) {
	global $wpdb, $adrotate_config;

	$output = '';

	if($banner_id) {
		$options = wp_parse_args($opt, array());
		$available = true;

		$banner = $wpdb->get_row($wpdb->prepare("SELECT `id`, `title`, `bannercode`, `tracker`, `image` FROM `{$wpdb->prefix}adrotate` WHERE `id` = %d AND (`type` = 'active' OR `type` = '2days' OR `type` = '7days');", $banner_id), ARRAY_A);

		if($banner) {
			if(adrotate_filter_schedule($banner)) {
				$available = false;
			}
		} else {
			$available = false;
		}


		if($available) {
			$image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner['image']);

			$output .= '<div class="a-single a-'.$banner['id'].'">';
			$output .= adrotate_ad_output($banner['id'], 0, $banner['title'], $banner['bannercode'], $banner['tracker'], $image);
			$output .= '</div>';

			if($adrotate_config['stats'] == 1 AND $banner['tracker'] == "Y") {
				adrotate_count_impression($banner['id'], 0, 0);
			}
		} else {
			$output .= adrotate_error('ad_expired', array($banner_id));
		}
		unset($banner);
	} else {
		$output .= adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_group
 Purpose:   Fetch ads in specified group(s) and show a random ad
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_group($group_ids, $opt = null) {
	global $wpdb, $adrotate_config;

	$output = $group_select = '';
	if($group_ids) {
		$options = wp_parse_args($opt, array());

		$now = current_time('timestamp');

		$group_array = (preg_match('/,/is', $group_ids)) ? explode(",", $group_ids) : array($group_ids);
		$group_array = array_filter($group_array);

		foreach($group_array as $key => $value) {
			$group_select .= " `{$wpdb->prefix}adrotate_linkmeta`.`group` = ".$wpdb->prepare('%d', $value)." OR";
		}
		$group_select = rtrim($group_select, " OR");

		$group = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `id` = %d;", $group_array[0]));

		if($group) {
			// Get all ads in all selected groups
			$ads = $wpdb->get_results(
				"SELECT
					`{$wpdb->prefix}adrotate`.`id`,
					`{$wpdb->prefix}adrotate`.`title`,
					`{$wpdb->prefix}adrotate`.`bannercode`,
					`{$wpdb->prefix}adrotate`.`image`,
					`{$wpdb->prefix}adrotate`.`tracker`,
					`{$wpdb->prefix}adrotate_linkmeta`.`group`
				FROM
					`{$wpdb->prefix}adrotate`,
					`{$wpdb->prefix}adrotate_linkmeta`
				WHERE
					({$group_select})
					AND `{$wpdb->prefix}adrotate_linkmeta`.`user` = 0
					AND `{$wpdb->prefix}adrotate`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`ad`
					AND (`{$wpdb->prefix}adrotate`.`type` = 'active'
						OR `{$wpdb->prefix}adrotate`.`type` = '2days'
						OR `{$wpdb->prefix}adrotate`.`type` = '7days')
				GROUP BY `{$wpdb->prefix}adrotate`.`id`
				ORDER BY `{$wpdb->prefix}adrotate`.`id`;"
			, ARRAY_A);

			if($ads) {
				foreach($ads as $key => $ad) {
					if(adrotate_filter_schedule($ad)) {
						unset($ads[$key]);
					}
				}

				$array_count = count($ads);
				if($array_count > 0) {
					$before = $after = '';
					$before = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_before, ENT_QUOTES)));
					$after = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_after, ENT_QUOTES)));

					$output .= '<div class="g g-'.$group->id.'">';

					// Kill dynamic mode for mobile users
					if($adrotate_config['mobile_dynamic_mode'] == 'Y' AND $group->modus == 1 AND wp_is_mobile()) {
						$group->modus = 0;
					}

					if($group->modus == 1) { // Dynamic ads
						$i = 1;

						// Limit group to save resources
						$amount = ($group->adspeed >= 10000) ? 10 : 20;

						// Randomize and trim output
						$ads = adrotate_shuffle($ads);
						$ads = array_slice($ads, 0, $amount, 1);

						foreach($ads as $key => $banner) {
							$image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner['image']);

							$output .= '<div class="g-dyn a-'.$banner['id'].' c-'.$i.'">';
							$output .= $before.adrotate_ad_output($banner['id'], $group->id, $banner['title'], $banner['bannercode'], $banner['tracker'], $image).$after;
							$output .= '</div>';
							$i++;
						}
					} else if($group->modus == 2) { // Block of ads
						$block_count = $group->gridcolumns * $group->gridrows;
						if($array_count < $block_count) $block_count = $array_count;
						$columns = 1;

						for($i=1;$i<=$block_count;$i++) {
							$array_key = array_rand($ads, 1);

							$image = str_replace('%folder%', $adrotate_config['banner_folder'], $ads[$array_key]['image']);

							$output .= '<div class="g-col b-'.$group->id.' a-'.$ads[$array_key]['id'].'">';
							$output .= $before.adrotate_ad_output($ads[$array_key]['id'], $group->id, $ads[$array_key]['title'], $ads[$array_key]['bannercode'], $ads[$array_key]['tracker'], $image).$after;
							$output .= '</div>';

							if($columns == $group->gridcolumns AND $i != $block_count) {
								$output .= '</div><div class="g g-'.$group->id.'">';
								$columns = 1;
							} else {
								$columns++;
							}

							if($adrotate_config['stats'] == 1 AND $ads[$array_key]['tracker'] == "Y") {
								adrotate_count_impression($ads[$array_key]['id'], $group->id, 0);
							}

							unset($ads[$array_key]);
						}
					} else { // Default (single ad)
						$array_key = array_rand($ads, 1);

						$image = str_replace('%folder%', $adrotate_config['banner_folder'], $ads[$array_key]['image']);

						$output .= '<div class="g-single a-'.$ads[$array_key]['id'].'">';
						$output .= $before.adrotate_ad_output($ads[$array_key]['id'], $group->id, $ads[$array_key]['title'], $ads[$array_key]['bannercode'], $ads[$array_key]['tracker'], $image).$after;
						$output .= '</div>';

						if($adrotate_config['stats'] == 1 AND $ads[$array_key]['tracker'] == "Y") {
							adrotate_count_impression($ads[$array_key]['id'], $group->id, 0);
						}
					}

					$output .= '</div>';

					unset($selected);
				} else {
					$output .= adrotate_error('ad_expired');
				}
			} else {
				$output .= adrotate_error('ad_unqualified');
			}
		} else {
			$output .= adrotate_error('group_not_found', array($group_array[0]));
		}
	} else {
		$output .= adrotate_error('group_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_group_post_inject
 Purpose:   Prepare group for post injection
 Since:		5.10
-------------------------------------------------------------*/
function adrotate_group_post_inject($group_id) {
	global $wpdb, $adrotate_config;

	// Grab settings to use from first group
	$group = $wpdb->get_row($wpdb->prepare("SELECT `id`, `wrapper_before`, `wrapper_after` FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `id` = %d;", $group_id));

	// Get all ads in group
	$ads = $wpdb->get_results(
		"SELECT
			`{$wpdb->prefix}adrotate`.`id`, `title`, `bannercode`, `image`, `tracker`
		FROM
			`{$wpdb->prefix}adrotate`,
			`{$wpdb->prefix}adrotate_linkmeta`
		WHERE
			`{$wpdb->prefix}adrotate_linkmeta`.`group` = {$group_id}
			AND `{$wpdb->prefix}adrotate_linkmeta`.`user` = 0
			AND `{$wpdb->prefix}adrotate`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`ad`
			AND (`{$wpdb->prefix}adrotate`.`type` = 'active'
				OR `{$wpdb->prefix}adrotate`.`type` = '2days'
				OR `{$wpdb->prefix}adrotate`.`type` = '7days')
		GROUP BY `{$wpdb->prefix}adrotate`.`id`
		ORDER BY `{$wpdb->prefix}adrotate`.`id`;");

	if($ads) {
		foreach($ads as $ad) {
			$selected[$ad->id] = $ad;
			$selected = adrotate_filter_schedule($selected, $ad);
		}

		$array_count = count($selected);
		if($array_count > 0) {
			$output = $before = $after = '';
			$banner_id = array_rand($selected, 1);
			$image = str_replace('%folder%', $adrotate_config['banner_folder'], $selected[$banner_id]->image);
			$before = str_replace('%id%', $group_id, stripslashes(html_entity_decode($group->wrapper_before, ENT_QUOTES)));
			$after = str_replace('%id%', $group_id, stripslashes(html_entity_decode($group->wrapper_after, ENT_QUOTES)));

			$output .= '<div class="g g-'.$group->id.'">';
			$output .= '<div class="g-single a-'.$selected[$banner_id]->id.'">';
			$output .= $before.adrotate_ad_output($selected[$banner_id]->id, $group->id, $selected[$banner_id]->title, $selected[$banner_id]->bannercode, $selected[$banner_id]->tracker, $image).$after;
			$output .= '</div>';

			if($adrotate_config['stats'] == 1 AND ($selected[$banner_id]->tracker == "Y")) {
				adrotate_count_impression($selected[$banner_id]->id, $group->id);
			}

			$output .= '</div>';

			unset($selected, $banner_id);

			return $output;
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_shortcode
 Purpose:   Prepare function requests for calls on shortcodes
 Since:		0.7
-------------------------------------------------------------*/
function adrotate_shortcode($atts, $content = null) {
	global $adrotate_config;

	$banner_id = (!empty($atts['banner'])) ? trim($atts['banner'], "\r\t ") : 0;
	$group_ids = (!empty($atts['group'])) ? trim($atts['group'], "\r\t ") : 0;
	if(!empty($atts['fallback'])) $fallback	= 0; // Not supported in free version
	if(!empty($atts['weight']))	$weight	= 0; // Not supported in free version
	if(!empty($atts['site'])) $site = 0; // Not supported in free version
	if(!empty($atts['wrapper'])) $wrapper = 0; // Not supported in free version

	$output = '';
	if($adrotate_config['w3caching'] == "Y") {
		$output .= '<!-- mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
		if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0)) { // Show one Ad
			$output .= 'echo adrotate_ad('.$banner_id.');';
		}
		if($banner_id == 0 AND $group_ids > 0) { // Show group
			$output .= 'echo adrotate_group('.$group_ids.');';
		}
		$output .= '<!-- /mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
	} else if($adrotate_config['borlabscache'] == "Y" AND function_exists('BorlabsCacheHelper')) {
		if(BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();

			$output .= '<!--[borlabs cache start: '.$borlabsphrase.']--> ';
			if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0)) { // Show one Ad
				$output .= 'echo adrotate_ad('.$banner_id.');';
			}
			if($banner_id == 0 AND $group_ids > 0) { // Show group
				$output .= 'echo adrotate_group('.$group_ids.');';
			}
			$output .= ' <!--[borlabs cache end: '.$borlabsphrase.']-->';

			unset($borlabsphrase);
		}
	} else {
		if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0)) { // Show one Ad
			$output .= adrotate_ad($banner_id);
		}

		if($banner_id == 0 AND $group_ids > 0) { // Show group
			$output .= adrotate_group($group_ids);
		}
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_inject_posts_cache_wrapper
 Purpose:   Wrap post injection return with caching code?
 Since:		5.10
-------------------------------------------------------------*/
function adrotate_inject_posts_cache_wrapper($group_id) {
	global $adrotate_config;

	if($adrotate_config['w3caching'] == 'Y') {
		$advert_output = '<!-- mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
		$advert_output .= 'echo adrotate_group('.$group_id.');';
		$advert_output .= '<!-- /mfunc '.W3TC_DYNAMIC_SECURITY.' -->';
	} else if($adrotate_config['borlabscache'] == "Y" AND function_exists('BorlabsCacheHelper')) {
		if(BorlabsCacheHelper()->willFragmentCachingPerform()) {
			$borlabsphrase = BorlabsCacheHelper()->getFragmentCachingPhrase();

			$advert_output = '<!--[borlabs cache start: '.$borlabsphrase.']-->';
			$advert_output .= 'echo adrotate_group('.$group_id.');';
			$advert_output .= '<!--[borlabs cache end: '.$borlabsphrase.']-->';

			unset($borlabsphrase);
		}
	} else {
		$advert_output = adrotate_group($group_id);
	}

	return $advert_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_inject_posts
 Purpose:   Add an advert to a single page or post
-------------------------------------------------------------*/
function adrotate_inject_posts($post_content) {
	global $wpdb, $post;

	$categories_top = $categories_bottom = $categories_inside = array();
	if(is_page()) {
		// Inject ads into pages
		$groups = $wpdb->get_results("SELECT `id`, `page`, `page_loc`, `page_par` FROM `{$wpdb->prefix}adrotate_groups` WHERE `page_loc` > 0 AND  `page_loc` < 5;");

		foreach($groups as $group) {
			$pages_more = explode(",", $group->page);

			if(count($pages_more) > 0) {
				if(in_array($post->ID, $pages_more)) {
					if($group->page_loc == 1 OR $group->page_loc == 3) {
						$categories_top[$group->id] = $group->page_par;
					}
					if($group->page_loc == 2 OR $group->page_loc == 3) {
						$categories_bottom[$group->id] = $group->page_par;
					}
					if($group->page_loc == 4) {
						$categories_inside[$group->id] = $group->page_par;
					}
					unset($pages_more, $group);
				}
			}
		}
	}

	if(is_single()) {
		// Inject ads into posts in specified category
		$groups = $wpdb->get_results("SELECT `id`, `cat`, `cat_loc`, `cat_par` FROM `{$wpdb->prefix}adrotate_groups` WHERE `cat_loc` > 0 AND `cat_loc` < 5;");
		$wp_categories = wp_get_post_categories($post->ID, array('taxonomy' => 'category', 'fields' => 'ids'));

		foreach($groups as $group) {
			$categories_more = array_intersect($wp_categories, explode(",", $group->cat));

			if(count($categories_more) > 0) {
				if(has_category($categories_more, $post->ID)) {
					if(($group->cat_loc == 1 OR $group->cat_loc == 3)) {
						$categories_top[$group->id] = $group->cat_par;
					}
					if($group->cat_loc == 2 OR $group->cat_loc == 3) {
						$categories_bottom[$group->id] = $group->cat_par;
					}
					if($group->cat_loc == 4) {
						$categories_inside[$group->id] = $group->cat_par;
					}
					unset($categories_more, $group);
				}
			}
		}
	}

	// Advert in front of content
	if(count($categories_top) > 0) {
		$post_content = adrotate_inject_posts_cache_wrapper(array_rand($categories_top)).$post_content;
	}

	// Advert behind the content
	if(count($categories_bottom) > 0) {
		$post_content = $post_content.adrotate_inject_posts_cache_wrapper(array_rand($categories_bottom));
	}

	// Adverts inside the content
	if(count($categories_inside) > 0) {
		// Setup
		$categories_inside = adrotate_shuffle($categories_inside);
	    $post_content_exploded = explode('</p>', $post_content);
		$post_content_count = ceil(count($post_content_exploded));
		$inserted = array();

		// Determine after which paragraphs ads should show
		foreach($categories_inside as $group_id => $group_paragraph) {
			if($group_paragraph == 99) {
				$group_paragraph = $post_content_count / 2; // Middle of content
			}

			$group_paragraph = intval($group_paragraph);

			// Create $inserted with paragraphs numbers and link the group to it. This list is leading from this point on.
			if(!array_key_exists($group_paragraph, $inserted)) {
				$inserted[$group_paragraph] = $group_id;
			}
			unset($group_id, $group_paragraph);
		}

		// Inject ads behind paragraphs based on $inserted created above, IF a group_id is set higher than 0
		foreach($post_content_exploded as $index => $paragraph) {
			$insert_here = $index + 1; // Deal with array offset
			if(array_key_exists($insert_here, $inserted)) {
				if($inserted[$insert_here] > 0) {
					$post_content_exploded[$index] .= adrotate_inject_posts_cache_wrapper($inserted[$insert_here]);
					$inserted[$insert_here] = 0;
				}
			}
			unset($index, $paragraph, $insert_here);
		}

		// Re-assemble post_content and clean up
	    $post_content = implode('', $post_content_exploded);
		unset($post_content_exploded, $post_content_count, $inserted);
	}

	unset($groups, $categories_top, $categories_bottom, $categories_inside);

	return $post_content;
}

/*-------------------------------------------------------------
 Name:      adrotate_preview
 Purpose:   Show preview of selected advert (Dashboard)
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_preview($banner_id) {
	global $wpdb;

	if($banner_id) {
		$now = current_time('timestamp');

		$banner = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}adrotate` WHERE `id` = %d;", $banner_id));

		if($banner) {
			$image = str_replace('%folder%', '/banners/', $banner->image);
			$output = adrotate_ad_output($banner->id, 0, $banner->title, $banner->bannercode, $banner->tracker, $image);
		} else {
			$output = adrotate_error('ad_expired');
		}
	} else {
		$output = adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_ad_output
 Purpose:   Prepare the output for viewing
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad_output($id, $group, $name, $bannercode, $tracker, $image) {
	global $blog_id, $adrotate_config;

	$banner_output = $bannercode;
	$banner_output = stripslashes(htmlspecialchars_decode($banner_output, ENT_QUOTES));

	if($adrotate_config['stats'] > 0 AND $tracker == "Y") {
		if(empty($blog_id) or $blog_id == '') {
			$blog_id = 0;
		}

		if($adrotate_config['stats'] == 1) { // Internal tracker
			preg_match_all('/<a[^>](?:.*?)>/i', $banner_output, $matches, PREG_SET_ORDER);
			if(isset($matches[0])) {
				$banner_output = str_ireplace('<a ', '<a data-track="'.adrotate_hash($id, $group, $blog_id).'" ', $banner_output);
				foreach($matches[0] as $value) {
					if(preg_match('/<a[^>]+class=\"(.+?)\"[^>]*>/i', $value, $regs)) {
					    $result = $regs[1]." gofollow";
						$banner_output = str_ireplace('class="'.$regs[1].'"', 'class="'.$result.'"', $banner_output);
					} else {
						$banner_output = str_ireplace('<a ', '<a class="gofollow" ', $banner_output);
					}
					unset($value, $regs, $result);
				}
			}
		}
	}

	$image = apply_filters('adrotate_apply_photon', $image);

	$banner_output = str_replace('%title%', $name, $banner_output);
	$banner_output = str_replace('%random%', rand(100000,999999), $banner_output);
	$banner_output = str_replace('%asset%', $image, $banner_output); // Replaces %image%
	$banner_output = str_replace('%image%', $image, $banner_output); // Depreciated, remove in AdRotate 5.0
	$banner_output = str_replace('%id%', $id, $banner_output);
	$banner_output = do_shortcode($banner_output);

	return $banner_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_header
 Purpose:   Add required CSS to wp_head (action)
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_header() {
	if(!function_exists('get_plugins')) require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$plugins = get_plugins();
	$plugin_version = $plugins['adrotate/adrotate.php']['Version'];

	$output = "\n<!-- This site is using AdRotate v".$plugin_version." to display their advertisements - https://ajdg.solutions/ -->\n";
	echo $output;

	adrotate_custom_css();
}

/*-------------------------------------------------------------
 Name:      adrotate_custom_css
 Purpose:   Add group CSS to adrotate_header()
 Since:		5.1.2
-------------------------------------------------------------*/
function adrotate_custom_css() {
	global $adrotate_config;

	$generated_css = get_option('adrotate_group_css');

	$output = "<!-- AdRotate CSS -->\n";
	$output .= "<style type=\"text/css\" media=\"screen\">\n";
	$output .= "\t.g { margin:0px; padding:0px; overflow:hidden; line-height:1; zoom:1; }\n";
	$output .= "\t.g img { height:auto; }\n";
	$output .= "\t.g-col { position:relative; float:left; }\n";
	$output .= "\t.g-col:first-child { margin-left: 0; }\n";
	$output .= "\t.g-col:last-child { margin-right: 0; }\n";
	if($generated_css) {
		foreach($generated_css as $group_id => $css) {
			if(strlen($css) > 0) {
				$output .= $css;
			}
		}
		unset($generated_css);
	}
	$output .= "\t@media only screen and (max-width: 480px) {\n";
	$output .= "\t\t.g-col, .g-dyn, .g-single { width:100%; margin-left:0; margin-right:0; }\n";
	$output .= "\t}\n";
	if($adrotate_config['widgetpadding'] == "Y") {
		$output .= ".adrotate_widgets, .ajdg_bnnrwidgets, .ajdg_grpwidgets { overflow:hidden; padding:0; }\n";
	}
	$output .= "</style>\n";
	$output .= "<!-- /AdRotate CSS -->\n\n";

	echo $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_scripts
 Purpose:   Add required scripts to wp_enqueue_scripts (action)
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_scripts() {
	global $adrotate_config;

	$in_footer = ($adrotate_config['jsfooter'] == "Y") ? true : false;

	if($adrotate_config['jquery'] == 'Y') {
		wp_enqueue_script('jquery', false, false, null, $in_footer);
	}

	if(get_option('adrotate_dynamic_required') > 0) {
		wp_enqueue_script('adrotate-dyngroup', plugins_url('/library/jquery.adrotate.dyngroup.js', __FILE__), false, null, $in_footer);
	}

	if($adrotate_config['stats'] == 1) {
		wp_enqueue_script('adrotate-clicktracker', plugins_url('/library/clicker.js', __FILE__), false, null, $in_footer);
		wp_localize_script('adrotate-clicktracker', 'click_object', array('ajax_url' => admin_url('admin-ajax.php')));
		wp_localize_script('adrotate-dyngroup', 'impression_object', array('ajax_url' => admin_url( 'admin-ajax.php')));
	}

	if(!$in_footer) {
		add_action('wp_head', 'adrotate_custom_javascript');
	} else {
		add_action('wp_footer', 'adrotate_custom_javascript', 100);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_custom_javascript
 Purpose:   Add required JavaScript to adrotate_scripts()
 Since:		3.10.5
-------------------------------------------------------------*/
function adrotate_custom_javascript() {
	global $wpdb, $adrotate_config;

	$groups = $wpdb->get_results("SELECT `id`, `adspeed` FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `modus` = 1 ORDER BY `id` ASC;");
	if($groups) {
		$output = "<!-- AdRotate JS -->\n";
		$output .= "<script type=\"text/javascript\">\n";
		$output .= "jQuery(document).ready(function(){\n";
		$output .= "if(jQuery.fn.gslider) {\n";
		foreach($groups as $group) {
			$output .= "\tjQuery('.g-".$group->id."').gslider({ groupid: ".$group->id.", speed: ".$group->adspeed." });\n";
		}
		$output .= "}\n";
		$output .= "});\n";
		$output .= "</script>\n";
		$output .= "<!-- /AdRotate JS -->\n\n";
		unset($groups);
		echo $output;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_nonce_error
 Purpose:   Display a formatted error if Nonce fails
 Since:		3.7.4.2
-------------------------------------------------------------*/
function adrotate_nonce_error() {
	echo '	<h2 style="text-align: center;">'.__('Oh no! Something went wrong!', 'adrotate').'</h2>';
	echo '	<p style="text-align: center;">'.__('WordPress was unable to verify the authenticity of the url you have clicked. Verify if the url used is valid or log in via your browser.', 'adrotate').'</p>';
	echo '	<p style="text-align: center;">'.__('If you have received the url you want to visit via email, you are being tricked!', 'adrotate').'</p>';
	echo '	<p style="text-align: center;">'.__('Contact support if the issue persists:', 'adrotate').' <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/" title="AdRotate Support" target="_blank">AJdG Solutions Support</a>.</p>';
}

/*-------------------------------------------------------------
 Name:      adrotate_error
 Purpose:   Show errors for problems in using AdRotate, should they occur
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_error($action, $arg = null) {
	switch($action) {
		// Ads
		case "ad_expired" :
			$result = '<!-- '.__('Error, Advert is not available at this time due to schedule/geolocation restrictions!', 'adrotate').' -->';
			return $result;
		break;

		case "ad_unqualified" :
			$result = '<!-- '.__('Either there are no banners, they are disabled or none qualified for this location!', 'adrotate').' -->';
			return $result;
		break;

		case "ad_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no Advert ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		// Groups
		case "group_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no group ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		case "group_not_found" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, group does not exist! Check your syntax!', 'adrotate').' (ID: '.$arg[0].')</span>';
			return $result;
		break;

		// Database
		case "db_error" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('There was an error locating the database tables for AdRotate. Please deactivate and re-activate AdRotate from the plugin page!!', 'adrotate').'<br />'.__('If this does not solve the issue please seek support on the', 'adrotate').' <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/">support forums</a></span>';
			return $result;
		break;

		// Possible XSS or malformed URL
		case "error_loading_item" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('There was an error loading the page. Please try again by reloading the page via the menu on the left.', 'adrotate').'<br />'.__('If the issue persists please seek help on the', 'adrotate').' <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/">support forums</a></span>';
			return $result;
		break;

		// Misc
		default:
			$result = '<span style="font-weight: bold; color: #f00;">'.__('An unknown error occured.', 'adrotate').' (ID: '.$arg[0].')</span>';
			return $result;
		break;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard_error
 Purpose:   Show errors for problems in using AdRotate
 Since:		3.19.1
-------------------------------------------------------------*/
function adrotate_dashboard_error() {
	global $adrotate_config;

	// Adverts
	$status = get_option('adrotate_advert_status');
	$adrotate_notifications	= get_option("adrotate_notifications");

	if($adrotate_notifications['notification_dash'] == "Y") {
		if($status['expired'] > 0 AND $adrotate_notifications['notification_dash_expired'] == "Y") {
			$error['advert_expired'] = sprintf(_n('One advert is expired.', '%1$s adverts expired!', $status['expired'], 'adrotate'), $status['expired']).' <a href="'.admin_url('admin.php?page=adrotate').'">'.__('Check adverts', 'adrotate').'</a>!';
		}
		if($status['expiressoon'] > 0 AND $adrotate_notifications['notification_dash_soon'] == "Y") {
			$error['advert_soon'] = sprintf(_n('One advert expires soon.', '%1$s adverts are almost expiring!', $status['expiressoon'], 'adrotate'), $status['expiressoon']).' <a href="'.admin_url('admin.php?page=adrotate').'">'.__('Check adverts', 'adrotate').'</a>!';
		}
	}
	if($status['error'] > 0) {
		$error['advert_config'] = sprintf(_n('One advert with configuration errors.', '%1$s adverts have configuration errors!', $status['error'], 'adrotate'), $status['error']).' <a href="'.admin_url('admin.php?page=adrotate').'">'.__('Check adverts', 'adrotate').'</a>!';
	}

	// Caching
	if($adrotate_config['w3caching'] == "Y" AND !is_plugin_active('w3-total-cache/w3-total-cache.php')) {
		$error['w3tc_not_active'] = __('You have enabled caching support but W3 Total Cache is not active on your site!', 'adrotate').' <a href="'.admin_url('/admin.php?page=adrotate-settings&tab=misc').'">'.__('Disable W3 Total Cache Support', 'adrotate').'</a>.';
	}
	if($adrotate_config['w3caching'] == "Y" AND !defined('W3TC_DYNAMIC_SECURITY')) {
		$error['w3tc_no_hash'] = __('You have enable caching support but the W3TC_DYNAMIC_SECURITY definition is not set.', 'adrotate').' <a href="'.admin_url('/admin.php?page=adrotate-settings&tab=misc').'">'.__('How to configure W3 Total Cache', 'adrotate').'</a>.';
	}

	if($adrotate_config['borlabscache'] == "Y" AND !is_plugin_active('borlabs-cache/borlabs-cache.php')) {
		$error['borlabs_not_active'] = __('You have enable caching support but Borlabs Cache is not active on your site!', 'adrotate').' <a href="'.admin_url('/admin.php?page=adrotate-settings&tab=misc').'">'.__('Disable Borlabs Cache Support', 'adrotate').'</a>.';
	}
	if($adrotate_config['borlabscache'] == "Y" AND is_plugin_active('borlabs-cache/borlabs-cache.php')) {
		$borlabs_config = get_option('BorlabsCacheConfigInactive');
		if($borlabs_config['cacheActivated'] == 'yes' AND strlen($borlabs_config['fragmentCaching']) < 1) {
			$error['borlabs_fragment_error'] = __('You have enabled Borlabs Cache support but Fragment caching is not enabled!', 'adrotate').' <a href="'.admin_url('/admin.php?page=borlabs-cache-fragments').'">'.__('Enable Fragment Caching', 'adrotate').'</a>.';
		}
		unset($borlabs_config);
	}

	// Misc
	if(!is_writable(WP_CONTENT_DIR."/".$adrotate_config['banner_folder'])) {
		$error['banners_folder'] = __('Your AdRotate Banner folder is not writable or does not exist.', 'adrotate').' <a href="https://ajdg.solutions/support/adrotate-manuals/manage-banner-images/" target="_blank">'.__('Set up your banner folder', 'adrotate').'</a>.';
	}
	if(is_dir(WP_PLUGIN_DIR."/adrotate-pro/")) {
		$error['adrotate_exists'] = __('You have AdRotate Professional installed. Please switch to AdRotate Pro! You can delete this plugin after AdRotate Pro is activated.', 'adrotate').' <a href="'.admin_url('/plugins.php?s=adrotate&plugin_status=all').'">'.__('Switch plugins', 'adrotate').'</a>.';
	}
	if(basename(__DIR__) != 'adrotate' AND basename(__DIR__) != 'adrotate-pro') {
		$error['adrotate_folder_names'] = __('Something is wrong with your installation of AdRotate. Either the plugin is installed twice or your current installation has the wrong folder name. Please install the plugin properly!', 'adrotate').' <a href="https://ajdg.solutions/support/adrotate-manuals/installing-adrotate-on-your-website/" target="_blank">'.__('Installation instructions', 'adrotate').'</a>.';
	}

	$error = (isset($error) AND is_array($error)) ? $error : false;

	return $error;
}

/*-------------------------------------------------------------
 Name:      adrotate_notifications_dashboard
 Purpose:   Notify user of expired banners in the dashboard
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_notifications_dashboard() {
	global $current_user;

	if(current_user_can('adrotate_ad_manage')) {
		$displayname = (strlen($current_user->user_firstname) > 0) ? $current_user->user_firstname : $current_user->display_name;
		$page = (isset($_GET['page'])) ? $_GET['page'] : '';

		// These only show on AdRotate pages
		if(strpos($page, 'adrotate') !== false) {
			if(isset($_GET['hide']) AND $_GET['hide'] == 0) update_option('adrotate_hide_getpro', current_time('timestamp') + (31 * DAY_IN_SECONDS));
			if(isset($_GET['hide']) AND $_GET['hide'] == 1) update_option('adrotate_hide_review', 1);
			if(isset($_GET['hide']) AND $_GET['hide'] == 2) update_option('adrotate_hide_birthday', current_time('timestamp') + (10 * MONTH_IN_SECONDS));

			// Get AdRotate Pro
			$getpro_banner = get_option('adrotate_hide_getpro');
			if($getpro_banner < current_time('timestamp')) {
				echo '<div class="ajdg-notification notice">';
				echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
				echo '	<div class="ajdg-notification-message">Hello <strong>'.$displayname.'</strong>. Have you considered upgrading to <strong>AdRotate Professional</strong> yet?<br />Get extra features like Geo Targeting, Scheduling, mobile adverts, access to premium support and much more starting at only &euro;39 EUR.<br />';
				if(adrotate_is_classicpress()) {
					echo ' Use coupon code <strong>GOTCLASSICPRESS</strong> and get a 20% special ClassicPress discount on any <strong>AdRotate Professional</strong> license! Thank you for your consideration!</div>';
				} else {
					echo ' Use coupon code <strong>GETADROTATEPRO</strong> and get a 10% discount on any <strong>AdRotate Professional</strong> license! Thank you for your consideration!</div>';
				}
				echo '	<div class="ajdg-notification-cta">';
				echo '		<a href="'.admin_url('admin.php?page=adrotate-pro').'" class="ajdg-notification-act button-primary">Get AdRotate Pro</a>';
				echo '		<a href="'.admin_url('admin.php?page=adrotate').'&hide=0" class="ajdg-notification-dismiss">Maybe later</a>';
				echo '	</div>';
				echo '</div>';
			}

			// Write a review
			$review_banner = get_option('adrotate_hide_review');
			if($review_banner != 1 AND $review_banner < (current_time('timestamp') - (8 * DAY_IN_SECONDS))) {
				echo '<div class="ajdg-notification notice">';
				echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
				echo '	<div class="ajdg-notification-message">Hello <strong>'.$displayname.'</strong>! You have been using <strong>AdRotate</strong> for a few days. If you like AdRotate, please share <strong>your experience</strong> and help promote AdRotate.<br />Tell your followers that you use AdRotate. A <a href="https://twitter.com/intent/tweet?hashtags=wordpress%2Cplugin%2Cadvertising&related=arnandegans%2Cwordpress&text=I%20am%20using%20AdRotate%20for%20@WordPress.%20Check%20it%20out.&url=https%3A%2F%2Fwordpress.org/plugins/adrotate/" target="_blank" class="ajdg-notification-act goosebox">Tweet</a> or <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwordpress.org%2Fplugins%2Fadrotate%2F&amp;src=adrotate" target="_blank" class="ajdg-notification-act goosebox">Facebook Share</a> helps a lot and is super awesome!<br />If you have questions, complaints or something else that does not belong in a review, please use the <a href="'.admin_url('admin.php?page=adrotate-support').'">support forum</a>!</div>';
				echo '	<div class="ajdg-notification-cta">';
				echo '		<a href="https://wordpress.org/support/view/plugin-reviews/adrotate?rate=5#postform" class="ajdg-notification-act button-primary">Write Review</a>';
				echo '		<a href="'.admin_url('admin.php?page=adrotate').'&hide=1" class="ajdg-notification-dismiss">Maybe later</a>';
				echo '	</div>';
				echo '</div>';
			}

			// Birthday
			$birthday_banner = get_option('adrotate_hide_birthday');
			if($birthday_banner < current_time('timestamp') AND date('M', current_time('timestamp')) == 'Feb') {
				echo '<div class="ajdg-notification notice">';
				echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/birthday.png', __FILE__).'\');"><span></span></div>';
				echo '	<div class="ajdg-notification-message">Hey <strong>'.$displayname.'</strong>! Did you know it is Arnan his birtyday this month? February 9th to be exact. Wish him a happy birthday via Telegram!<br />Who is Arnan? He made AdRotate for you - Check out his <a href="http://www.arnan.me/?mtm_campaign=adrotate&mtm_keyword=birthday_banner" target="_blank">website</a> or <a href="http://www.arnan.me/donate.html?mtm_campaign=adrotate&mtm_keyword=birthday_banner" target="_blank">send a gift</a>.</div>';
				echo '	<div class="ajdg-notification-cta">';
				echo '		<a href="https://t.me/arnandegans" target="_blank" class="ajdg-notification-act button-primary goosebox"><i class="icn-tg"></i>Wish Happy Birthday</a>';
				echo '		<a href="'.admin_url('admin.php?page=adrotate').'&hide=2" class="ajdg-notification-dismiss">Not now</a>';
				echo '	</div>';
				echo '</div>';
			}
		}

		// Advert notifications, errors, important stuff
		$adrotate_has_error = adrotate_dashboard_error();
		if($adrotate_has_error) {
			echo '<div class="ajdg-notification notice" style="">';
			echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
			echo '	<div class="ajdg-notification-message"><strong>AdRotate</strong> has detected '._n('one issue that requires', 'several issues that require', count($adrotate_has_error), 'adrotate').' '.__('your attention:', 'adrotate').'<br />';
			foreach($adrotate_has_error as $error => $message) {
				echo '&raquo; '.$message.'<br />';
			}
			echo '	</div>';
			echo '</div>';
		}
	}

	if(current_user_can('update_plugins')) {
		// Finish update
		// Keep for manual updates
		$adrotate_db_version = get_option("adrotate_db_version");
		$adrotate_version = get_option("adrotate_version");
		if($adrotate_db_version['current'] < ADROTATE_DB_VERSION OR $adrotate_version['current'] < ADROTATE_VERSION) {
			$plugins = get_plugins();
			$plugin_version = $plugins['adrotate/adrotate.php']['Version'];

			echo '<div class="ajdg-notification notice" style="">';
			echo '	<div class="ajdg-notification-logo" style="background-image: url(\''.plugins_url('/images/notification.png', __FILE__).'\');"><span></span></div>';
			echo '	<div class="ajdg-notification-message">Thanks for updating <strong>'.$displayname.'</strong>! You have almost completed updating <strong>AdRotate</strong> to version <strong>'.$plugin_version.'</strong>!<br />To complete the update <strong>click the button on the right</strong>. This may take a few seconds to complete!<br />For an overview of what has changed take a look at the <a href="https://ajdg.solutions/support/adrotate-development/?mtm_campaign=adrotate&mtm_keyword=finish_update_notification" target="_blank">development page</a> and usually there is an article on <a href="https://ajdg.solutions/blog/" target="_blank">the blog</a> with more information as well.</div>';
			echo '	<div class="ajdg-notification-cta">';
			echo '		<a href="'.wp_nonce_url('admin.php?page=adrotate-settings&tab=maintenance&action=update-db', 'nonce', 'adrotate-nonce').'" class="ajdg-notification-act button-primary update-button">Finish update</a>';
			echo '	</div>';
			echo '</div>';
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_welcome_pointer
 Purpose:   Show dashboard pointers
 Since:		3.9.14
-------------------------------------------------------------*/
function adrotate_welcome_pointer() {
	$plugins = get_plugins();
	$plugin_version = $plugins['adrotate/adrotate.php']['Version'];

	$pointer_content = '<h3>AdRotate Banner Manager '.$plugin_version.'</h3>';
    $pointer_content .= '<p>'.__('Thank you for choosing AdRotate. Everything related to AdRotate Banner Manager is in this menu. If you need help getting started take a look at the', 'adrotate').' <a href="https://ajdg.solutions/support/adrotate-manuals/" target="_blank">'.__('manuals', 'adrotate').'</a> '.__('and', 'adrotate').' <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/" target="_blank">'.__('forums', 'adrotate').'</a>. '.__('These links are also available on the support page.', 'adrotate').'</p>';
    $pointer_content .= '<p><strong>'.__('Ad blockers', 'adrotate').'</strong><br />'.__('Please whitelist your website in your content blocker so your adverts and dashboard show up correctly. Take a look at this manual to', 'adrotate').' <a href="https://ajdg.solutions/support/adrotate-manuals/configure-adblockers-for-your-own-website/" target="_blank">'.__('see how that works', 'adrotate').'</a>.</p>';
?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#toplevel_page_adrotate').pointer({
				content:'<?php echo $pointer_content; ?>',
				position:{ edge:'left', align:'middle' },
				close: function() { $.post(ajaxurl, { 'pointer':'adrotate_banner_manager', 'action':'dismiss-wp-pointer' }); }
			}).pointer("open");
		});
	</script>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_action_links
 Purpose:	Plugin page link
 Since:		4.11
-------------------------------------------------------------*/
function adrotate_action_links($links) {
	$custom_actions = array();
	$custom_actions['adrotate-pro'] = sprintf('<a href="%s" target="_blank">%s</a>', 'https://ajdg.solutions/cart/?add-to-cart=1124&mtm_campaign=adrotate&mtm_keyword=action_links', '<strong>Get AdRotate Pro</strong>');
	$custom_actions['adrotate-help'] = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=adrotate-support'), 'Support');

	return array_merge($custom_actions, $links);
}

/*-------------------------------------------------------------
 Name:      adrotate_credits
 Purpose:   Promotional stuff shown throughout the plugin
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_credits() {
	echo '<table class="widefat" style="margin-top: 2em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th width="70%"><strong>'.__('Get more features with AdRotate Professional', 'adrotate').'</strong></th>';
	echo '	<th><strong>'.__('Choose your license', 'adrotate').' - <a href="https://ajdg.solutions/product-category/adrotate-pro/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank">'.__('Compare Licenses', 'adrotate').' &raquo;</a></strong></th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr>';

	echo '<td>
		<a href="https://ajdg.solutions/plugins/adrotate-for-wordpress/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank"><img src="'.plugins_url('/images/logo-60x60.png', __FILE__).'" class="alignleft pro-image" /></a><p>'.__('<strong>AdRotate Professional</strong> has a lot more to offer for even better advertising management and premium support. Enjoy features like <strong>Geo Targeting</strong>, <strong>Schedules</strong>, more advanced <strong>Post Injection</strong> and much more. Check out the feature comparison tab on any of the product pages to see what AdRotate Pro has to offer for you! When you upgrade to <strong>AdRotate Professional</strong> make sure you use coupon <strong>GETADROTATEPRO</strong> on checkout for 10 percent off on any license.', 'adrotate').' <a href="https://ajdg.solutions/product-category/adrotate-pro/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank">'.__('Compare Licenses', 'adrotate').' &raquo;</a></p>
	</td>';

	echo '<td>
		<p><a href="https://ajdg.solutions/product/adrotate-pro-single/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank"><strong>'.__('Single License', 'adrotate').' (&euro; 39.00)</strong></a><br /><em>'.__('Use on ONE WordPress installation.', 'adrotate').' <a href="https://ajdg.solutions/?add-to-cart=1124&mtm_campaign=adrotate&mtm_keyword=credits" target="_blank">'.__('Buy now', 'adrotate').' &raquo;</a></em></p>

		<p><a href="https://ajdg.solutions/product/adrotate-pro-duo/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank"><strong>'.__('Duo License', 'adrotate').' (&euro; 49.00)</strong></a><br /><em>'.__('Use on TWO WordPress installations.', 'adrotate').' <a href="https://ajdg.solutions/?add-to-cart=1126&mtm_campaign=adrotate&mtm_keyword=credits" target="_blank">'.__('Buy now', 'adrotate').' &raquo;</a></em></p>
	</td>';

	echo '</tr>';

	echo '</tbody>';
	echo '</table>';
	echo '<table class="widefat" style="margin-top: 2em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th width="50%"><strong>'.__('Do you have a question?', 'adrotate').'</strong></th>';
	echo '	<th><strong>'.__('Support AdRotate Banner Manager', 'adrotate').'</strong></th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr>';
	echo '<td>
		<a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/" title="Getting help with AdRotate"><img src="'.plugins_url('/images/icon-support.png', __FILE__).'" alt="AdRotate Logo" width="60" height="60" align="left" style="padding:5px;" /></a><p>'.__('If you need help, or have questions about AdRotate, the best and fastest way to get your answer is via the AdRotate support forum. Usually I answer questions the same day, often with a solution in the first answer.').'</p>

		<p><a href="https://ajdg.solutions/support/adrotate-manuals/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank" class="button-primary">'.__('AdRotate Manuals').'</a> <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank" class="button-primary">'.__('Support Forums').'</a> <a href="https://ajdg.solutions/product/support-ticket/?mtm_campaign=adrotate&mtm_keyword=credits" target="_blank" class="button-secondary">'.__('Buy Support Ticket').'</a></p>
	</td>';

	echo '<td>
		<a href="https://wordpress.org/support/view/plugin-reviews/adrotate?rate=5#postform" title="Review AdRotate for WordPress"><img src="'.plugins_url('/images/icon-contact.png', __FILE__).'" alt="AdRotate Logo" width="60" height="60" align="left" style="padding:5px;" /></a><p>'.__('Arnan needs your help. Please consider writing a review or sharing AdRotate in Social media if you find the plugin useful. Writing a review and sharing AdRotate on social media costs you nothing but doing so is super helpful.').'</p>

		<p><a href="https://twitter.com/intent/tweet?hashtags=wordpress%2Cplugin%2Cadvertising%2Cadrotate&related=arnandegans%2Cwordpress&text=I%20am%20using%20AdRotate%20for%20WordPress%20by%20@arnandegans.%20Check%20it%20out.&url=https%3A%2F%2Fwordpress.org/plugins/adrotate/" target="_blank" class="button-primary goosebox"><i class="icn-t"></i>'.__('Post Tweet').'</a> <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwordpress.org%2Fplugins%2Fadrotate%2F&hashtag=#adrotate" target="_blank" class="button-primary goosebox"><i class="icn-fb"></i>'.__('Share on Facebook').'</a> <a class="button-primary" target="_blank" href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/reviews/?rate=5#new-post">'.__('Write review on WordPress.org').'</a></p>
	</td>';

	echo '</tr>';

	echo '</tbody>';
	echo '</table>';
	echo adrotate_trademark();
}

/*-------------------------------------------------------------
 Name:      adrotate_trademark
 Purpose:   Trademark notice
 Since:		3.9.14
-------------------------------------------------------------*/
function adrotate_trademark() {
	return '<center><small>AdRotate<sup>&reg;</sup> is a registered trademark.</small></center>';
}
?>
