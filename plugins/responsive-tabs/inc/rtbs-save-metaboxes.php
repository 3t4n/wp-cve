<?php

/* save metaboxes */
add_action('save_post', 'dmb_rtbs_tabs_meta_box_save');
function dmb_rtbs_tabs_meta_box_save($post_id)
{

	if (
		!isset($_POST['dmb_rtbs_meta_box_nonce']) ||
		!wp_verify_nonce(sanitize_key($_POST['dmb_rtbs_meta_box_nonce']), 'dmb_rtbs_meta_box_nonce')
	)
		return;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (!current_user_can('edit_post', $post_id))
		return;

	if (!isset($_POST['tab_titles'])) {
		return;
	}

	// get previous data
	$previous_data = get_post_meta($post_id, '_rtbs_tabs_head', false);

	// process updated data
	$updated_data = array();
	$tab_titles = array_map('wp_kses_post', wp_unslash($_POST['tab_titles'])); // sanitize array

	for ($i = 0; $i < count($tab_titles); $i++) {

		// content should not save if no tab title
		if (isset($tab_titles[$i]) && !empty($tab_titles[$i])) {

			// tab title
			$tab_title = wp_kses_post(wp_unslash($tab_titles[$i]));
			$updated_data[$i]['_rtbs_title'] = $tab_title;

			// tab content
			if (isset($_POST['tab_contents'][$i])) {
				$tab_content = wp_kses_post(wp_unslash($_POST['tab_contents'][$i]));
				$updated_data[$i]['_rtbs_content'] = $tab_content;
			}
		}
	}

	// update data (title and content)
	if (!empty($updated_data) && ($updated_data != $previous_data)) {
		update_post_meta($post_id, '_rtbs_tabs_head', $updated_data);
	} else if (empty($updated_data) && $previous_data) {
		delete_post_meta($post_id, '_rtbs_tabs_head', $previous_data);
	}

	// color setting
	if (isset($_POST['tabs_color']) && !empty($_POST['tabs_color'])) {
		$color = sanitize_text_field(wp_unslash($_POST['tabs_color']));
		update_post_meta($post_id, '_rtbs_tabs_bg_color', $color);
	}

	// breakpoint setting
	if (isset($_POST['tabs_breakpoint']) && !empty($_POST['tabs_breakpoint'])) {
		$breakpoint = sanitize_text_field(absint($_POST['tabs_breakpoint']));
		update_post_meta($post_id, '_rtbs_breakpoint', $breakpoint);
	}

	// force font setting
	if (isset($_POST['tabs_force_font']) && !empty($_POST['tabs_force_font'])) {
		$force_font = sanitize_text_field(wp_unslash($_POST['tabs_force_font']));
		update_post_meta($post_id, '_rtbs_original_font', $force_font);
	}

	// inactive tab background setting
	if (isset($_POST['tabs_tbgs']) && !empty($_POST['tabs_tbgs'])) {
		$inactive_tab_bg = sanitize_text_field(wp_unslash($_POST['tabs_tbgs']));
		update_post_meta($post_id, '_rtbs_tbg', $inactive_tab_bg);
	}
}