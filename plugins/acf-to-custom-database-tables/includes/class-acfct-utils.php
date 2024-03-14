<?php


class Acfct_utils
{
	use ACFUtil;

	public static $blacklisted_acf_type = array('message', 'accordion', 'tab', 'flexible_content', 'repeater', 'gallery');
	public static $sub_field_containing_field = array('group', 'flexible_content', 'repeater');
	public static $array_output_fields = array('select', 'checkbox', 'link', 'relationship', 'google_map', 'taxonomy', 'post_object', 'page_link', 'user');

	/**
	 * Get Custom table name
	 * returns false if custom table is not enabled
	 * @param $acf_post_id
	 * @param bool $prepend_prefix
	 * @return bool | string : custom table name
	 */
	public static function get_custom_table_name($acf_post_id, $prepend_prefix = false)
	{

		if (!$acf_post_id) {
			return false;
		}

		$acf_group_setting = get_post($acf_post_id);

		if (!$acf_group_setting) {
			return false;
		}

		$custom_table_setting = unserialize($acf_group_setting->post_content);

		if (isset($custom_table_setting[ACF_CT_ENABLE]) && $custom_table_setting[ACF_CT_ENABLE] != "1") {
			return false;
		}

		$custom_table_name = $custom_table_setting[ACF_CT_TABLE_NAME];
		if (empty(trim($custom_table_name)) === false) {
			// prepend prefix
			global $wpdb;
			$prefix = ($prepend_prefix) ? $wpdb->prefix : '';

			return $prefix . $custom_table_name;
		}

		return false;
	}

	public static function get_acf_field_group_id($field_group_key)
	{
		global $wpdb;
		$sql = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_name='{$field_group_key}' AND post_type='acf-field-group'";
		$results = $wpdb->get_results($sql);
		if (is_array($results) && !empty($results)) {
			return (isset($results[0]->ID)) ? $results[0]->ID : null;
		}
		return null;
	}

	public static function acf_get_field_groups_id($post_id)
	{
		$acf_groups = acf_get_field_groups(self::get_field_groups_filter_array($post_id));
		$groups_id = array();
		foreach ($acf_groups as $group) {
			array_push($groups_id, $group['ID']);
		}
		return $groups_id;
	}

	/**
	 * Get ACF field group id and associated custom table name
	 *
	 * @param $post_id
	 * @param bool $with_prefix - table prefix
	 * @param bool $only_active_custom_tables
	 * @return array
	 */
	public static function get_acf_field_groups_meta($post_id, $with_prefix = true, $only_active_custom_tables = true)
	{
		$cache_key = "acf_ct:$post_id:acf_field_group_meta";

		/**
		 * Process if id is numeric
		 */
		$formatted_post_id = self::format_post_id($post_id);
		if (is_numeric($formatted_post_id) === false) {
			return array();
		}

		/**
		 * Check in cache
		 */
		$acf_field_group_meta = wp_cache_get($cache_key);
		if ($acf_field_group_meta !== false) {
			return $acf_field_group_meta;
		}

		$acf_groups = acf_get_field_groups(self::get_field_groups_filter_array($post_id));

		$acf_field_group_meta = array_reduce($acf_groups, function ($meta, $field_group) use ($with_prefix, $only_active_custom_tables) {

			if ((isset($field_group[ACF_CT_ENABLE]) && $field_group[ACF_CT_ENABLE] == 1) || $only_active_custom_tables === false) {

				$custom_table_name = trim($field_group[ACF_CT_TABLE_NAME]);

				if (empty($custom_table_name) === false) {
					global $wpdb;
					$prefix = ($with_prefix) ? $wpdb->prefix : '';

					array_push($meta, array(
						'id' => $field_group['ID'],
						'table' => $prefix . $custom_table_name,
						'active' => $field_group[ACF_CT_ENABLE],
						'key' => $field_group['key']
					));
				}
			}

			return $meta;
		}, array());

		/**
		 * Save in cache
		 */
		wp_cache_set($cache_key, $acf_field_group_meta);

		return $acf_field_group_meta;
	}

	/**
	 * Get all custom tables associated with post
	 * @param $post_id
	 * @return mixed
	 */
	public static function get_custom_table_names_of_post($post_id)
	{
		$acf_post_ids = Acfct_utils::acf_get_field_groups_id($post_id);

		return array_reduce($acf_post_ids, function ($custom_table_names, $acf_post_id) {
			$table_name = Acfct_utils::get_custom_table_name($acf_post_id, true);
			if ($table_name !== false) {
				$custom_table_names[$acf_post_id] = $table_name;
			}
			return $custom_table_names;
		}, array());
	}

	public static function maybe_prefix_table_name($table_name)
	{
		global $wpdb;
		$prefix = $wpdb->prefix;
		if (strpos($table_name, $prefix) === 0) {
			return $table_name;
		}
		return $prefix . $table_name;
	}

	/**
	 * Eg: [a,b,c] ==> <span>a</span><span>b</span>
	 * @param $keywords
	 * @return string
	 */
	public static function get_span_tags_from_array($keywords)
	{
		$tags = array_map(function ($keyword) {
			return "<span class='highlight' style='display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: 400; background-color: #efefef; color: #504b4b; margin-right: 4px;'>" . esc_html($keyword) . "</span>";
		}, $keywords);
		return implode("", $tags);
	}

	/**
	 * Set Page Header Title in UI
	 * @param $title
	 * @param bool $show_back_button
	 */
	public static function set_page_title($title, $show_back_button = true)
	{
		echo "<div class='acf-ct-heading-row'><h1>";
		if ($show_back_button === true) {
			echo '<a style="position: relative; top: 5px; margin-right: 8px;" href="' . esc_url(ACF_CT_ADMIN_PAGE) . '" class="dashicons dashicons-arrow-left-alt"></a> ';
		}
		echo esc_html($title);
		echo "</h1>";
		if (ACF_CT_FREE_PLUGIN === true){
			echo '<a class="cta" href="https://acf-custom-tables.abhisheksatre.com/pro/?ref=plugin-head" target="_blank"><span class="dashicons dashicons-cart" style="margin-right: 4px;"></span> Upgrade to PRO</a>';
		}
		echo "</div>";
	}

	private static function should_skip_field($field)
	{
		/**
		 * check whether field type is black listed
		 */
		if (in_array($field['type'], self::$blacklisted_acf_type)) {
			return true;
		}
		/**
		 * check whether field is excluded from a custom table
		 */
		if (array_key_exists('acf_ct_exclude_column', $field)) {
			return $field['acf_ct_exclude_column'] === 1;
		}
		return false;
	}

	public static function get_field_groups_filter_array($post_id)
	{

		$filter = array();

		if (strpos($post_id, 'user_') === 0) {
			global $current_screen;
			$user_form = 'all';

			if ($current_screen !== null) {
				$user_form = ($current_screen->id === 'user') ? 'add' : 'edit';
			}

			$filter = array(
				'user_id' => str_replace('user_', '', $post_id),
				'user_form' => $user_form
			);
		} else if (strpos($post_id, 'term_') === 0) {
			global $current_screen;
			$taxonomy = 'all';

			if ($current_screen !== null) {
				$taxonomy = $current_screen->taxonomy;
			} else if (is_category()) {
				$taxonomy = 'category';
			} else if (is_tax()) {
				$taxonomy = 'post_tag';
			}

			$filter = array('taxonomy' => $taxonomy);
		} else if (
			acf_is_screen( 'attachment' ) ||
			(isset($_POST['_acf_screen']) && $_POST['_acf_screen'] === 'attachment')
		) {
			$filter = array(
				'attachment_id' => $post_id,
				'attachment' => $post_id,
			);
		} else {
			$filter = array('post_id' => $post_id);
		}

		return $filter;
	}

	public static function format_post_id($post_id)
	{

		//post
		if (is_numeric($post_id) === true) {
			return $post_id;
		} //options
		else if ($post_id === 'options') {
			return 'options';
		}

		//user, taxonomy - Eg: user_1
		return preg_replace('/[^0-9]/', '', $post_id);
	}

	public static function get_manage_table_page_url($acf_post_id)
	{
		return ACF_CT_ADMIN_PAGE . "&type=sql-view&acf_ct_post_id=$acf_post_id";
	}
}
