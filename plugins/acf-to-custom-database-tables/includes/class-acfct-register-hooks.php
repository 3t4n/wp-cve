<?php

class Acf_ct_register_hooks
{
	use Acfct_formatters;

	static $field_key_to_group_key_map = [];
	static $lastAcfGroupKey = null;

	public function __construct()
	{
		add_filter('acf_ct/format_value', array($this, 'acf_ct_filter_format_value'));
		add_filter('acf/load_value', array($this, 'acf_ct_filter_load_value'), 9, 3);
		add_filter('acf/load_field', array($this, 'acf_ct_filter_load_field'));
		add_filter('acf/pre_load_value', array($this, 'acf_ct_filter_pre_load_value'), 10, 3);
		add_filter('plugin_action_links_' . ACF_CT_BASE_NAME, array($this, 'acf_ct_add_setting_link'));
		add_filter('acf/pre_update_value', array($this, 'acf_ct_filter_pre_update_value'), 10, 4);
		add_filter('manage_edit-acf-field-group_columns', array($this, 'acf_ct_field_group_columns'), 12, 1);

		add_action('acf/save_post', array($this, 'acf_ct_handle_save_post'), 5);
		add_action('admin_notices', array($this, 'acf_ct_show_database_update_notice'));
		add_action('acf/field_group/admin_head', array($this, 'acf_ct_field_group_custom_table_settings'), 10);
		add_action('acf/field_group/admin_footer', array($this, 'acf_ct_inject_custom_js'));
		add_action('admin_menu', array($this, 'acf_ct_register_menu'), 20);
		add_action('admin_enqueue_scripts', array($this, 'acf_ct_enqueue_script'));
		add_action('acf/render_field_settings', array($this, 'acf_ct_settings_fields'));
		add_action('before_delete_post', array($this, 'delete_custom_table_row'));
		add_action('manage_acf-field-group_posts_custom_column', array($this, 'acf_ct_field_group_column_html'), 10, 2);
	}

	/**
	 * Register link in plugin list
	 * @param $links
	 * @return array
	 */
	public function acf_ct_add_setting_link($links)
	{
		$links[] = '<a href="' . ACF_CT_ADMIN_PAGE . '">Manage Custom Tables</a>';
		return $links;
	}

	/**
	 * Format value before saving in DB
	 * @param $value
	 * @return string
	 */
	public function acf_ct_filter_format_value($value)
	{
		return $value;
	}

	public function acf_ct_filter_load_field($field)
	{
		/**
		 * Make fields read only if 'acf_ct_read_only_field' is true and current use is not a admin or
		 * 'acf_ct/edit_read_only_field' filter returns false
		 */

		if (array_key_exists('acf_ct_read_only_field', $field) && $field['acf_ct_read_only_field'] === 1) {

			$field_details = array(
				'key' => $field['key'],
				'name' => $field['name'],
			);

			$edit_read_only_field = current_user_can('administrator') === true;
			$edit_read_only_field = apply_filters('acf_ct/edit_read_only_field', $edit_read_only_field, $field_details);

			if ($edit_read_only_field === false) {
				$field['disabled'] = 1;
			}
		}
		return $field;
	}

	public function acf_ct_filter_load_value($value, $post_id, $field)
	{
		/**
		 * Load only in admin
		 */
		if (is_admin() === false) {
			return $value;
		}

		$acfGroupKey = $field['parent'];
		if (array_key_exists($acfGroupKey, self::$field_key_to_group_key_map)) {
		    $acfGroupKey = self::$field_key_to_group_key_map[$acfGroupKey];
        } else if (array_key_exists($field['key'], self::$field_key_to_group_key_map)) {
			$acfGroupKey = self::$field_key_to_group_key_map[$field['key']];
        }

		/**
		 * Set data from custom table
		 */
		$acfCtDBInstance = $this->get_acf_ct_table_class_instance($acfGroupKey, $post_id);

		if (!$acfCtDBInstance instanceof Acfct_table_data) {
			$acfCtDBInstance = $this->get_acf_ct_table_class_instance(self::$lastAcfGroupKey, $post_id);
        } else {
			self::$lastAcfGroupKey = $acfGroupKey;
        }

		if ($acfCtDBInstance instanceof Acfct_table_data) {
			self::$field_key_to_group_key_map[$field['key']] = $acfGroupKey;
			$db_value = $acfCtDBInstance->get($field['key'], $field['name']);
			if (is_null($db_value) === false) {
				return $db_value;
			}
		}
		return $value;
	}

	public function acf_ct_handle_save_post($post_id)
	{

		// Bail early if no data sent.
		if (empty($_POST['acf'])) {
			return;
		}

		$acfArr = wp_kses_post_deep($_POST['acf']);

		/**
		 * Get acf groups of current post
		 */
		$acf_groups = Acfct_utils::acf_get_field_groups_id($post_id);
		$formatted_post_id = Acfct_utils::format_post_id($post_id);

		if (empty($acf_groups)) {
			return;
		}

		foreach ($acf_groups as $acf_post_id) {
			$custom_table = Acfct_utils::get_custom_table_name($acf_post_id, true);

			if ($custom_table === false) {
				continue; //Custom table is not enabled
			}

			if (Acf_ct_database_manager::check_table_exists($custom_table) === false) {
				continue; //Custom table is not exists
			}

			$store_acf_values_in_post_meta = apply_filters('acf_ct/settings/store_acf_values_in_post_meta', true);

			$acfKeyMap = Acfct_utils::get_acf_keys($acf_post_id, true);
			$dbHandler = new Acfct_table_data($formatted_post_id, $custom_table);
			$columns = $dbHandler->getColumns();

			$group_parent_keys = array();
			$flat_post_array = array();
			foreach (wp_unslash($acfArr) as $key => $value) {
				$field_type = null;
				if (array_key_exists($key, $acfKeyMap)) {
					$field_type = $acfKeyMap[$key]['type'];
				}

				if (is_array($value) && $field_type === 'group') {
					/**
					 * Create group_field_key => group_key map
					 * This map is used to unset group values
					 */
					foreach ($value as $sub_key => $field) {
						$group_parent_keys[$sub_key] = $key;
					}

					/**
					 * Check group field types
					 * For flexible_content field type
					 * - Convert it into formatted value and unset it's key from group array
					 */
					foreach ($value as $group_field_key => $group_field) {
						$group_field_type = null;
						if (array_key_exists($group_field_key, $acfKeyMap)) {
							$group_field_type = $acfKeyMap[$group_field_key]['type'];
						}
						if ($group_field_type === 'flexible_content' || $group_field_type === 'repeater') {
							$flat_post_array[$group_field_key] = $this->get_formatted_value($group_field_type, $acfKeyMap, $group_field_key, $group_field);
							unset($value[$group_field_key]); //unset field from group
						}
					}

					/**
					 * Merge group fields with flat_array
					 */
					$flat_post_array = array_merge($flat_post_array, $value);

				} else {
					$flat_post_array[$key] = $this->get_formatted_value($field_type, $acfKeyMap, $key, $value);
				}
			}

			$dbArray = array();
			foreach ($flat_post_array as $key => $value) {

				if (array_key_exists($key, $acfKeyMap) === false) {
					continue;
				}

				$config = $acfKeyMap[$key];
				if (in_array($config['name'], $columns)) {
				    $dbValue = apply_filters('acf_ct/format_value', $value);
				    $dbValue = apply_filters('acf_ct/format_value/' . $custom_table, $dbValue);
					$dbValue = apply_filters('acf_ct/format_value/' . $custom_table . '/column=' . $config['name'], $dbValue);
					$dbArray[$config['name']] = $dbValue;

					if ($store_acf_values_in_post_meta == false) {
						unset($_POST['acf'][$key]);
					}

					/**
					 * Unset group wrappers
					 */
					if (array_key_exists($key, $group_parent_keys) && $store_acf_values_in_post_meta == false) {
						unset($_POST['acf'][$group_parent_keys[$key]]);
					}

				}
			}

			/**
			 * Save data only if acf values are updated
			 * @Note: To stop data from saving in post_meta, '_acf_changed' check added after unseting $_POST.
			 */
			if ($_POST['_acf_changed'] === "1" || $this->should_save_data()) {
				$dbArray = apply_filters('acf_ct/update_table_data', $dbArray, $formatted_post_id, $custom_table);
				$dbArray = apply_filters('acf_ct/update_table_data/name='.$custom_table, $dbArray, $formatted_post_id);
				$dbHandler->saveData($dbArray);
			}
		}
	}

	/**
	 * @return bool
	 */
    public function should_save_data() {

        // For attachment ajax request  `_acf_changed` is always 0 so update.
        if (wp_doing_ajax() && isset($_POST['_acf_screen']) && $_POST['_acf_screen'] === 'attachment') {
            return true;
        }

        return false;
    }

	public function acf_ct_show_database_update_notice()
	{
		$change_list = false;
		$customTableName = null;
		if (get_post_type() === "acf-field-group" && isset($_GET['message'])) {
			$change_list = Acf_ct_database_manager::get_acf_fields_change_list(get_the_ID());
		}

		if ($change_list !== false && $change_list['should_update'] === true) {
			global $wpdb;
			$customTableName = Acfct_utils::get_custom_table_name(get_the_ID());
			$validate = Acf_ct_database_manager::validate_acf_group(get_the_ID());
			?>
			<?php if ($validate['valid'] === true): ?>
                <div class="notice notice-success is-dismissible">
                    <p style="font-size: 16px; font-weight: bold; margin-bottom: 10px;"><?php echo esc_html(ACF_CT_PLUGIN_NAME); ?></p>
                    <p style="margin-bottom: 10px;"><strong>Table Name</strong>: <?php echo esc_html($wpdb->prefix . $customTableName); ?></p>
                    <table style="border-spacing: 0;margin-bottom: 10px;">
						<?php
						/**
						 * Show change list for existing tables
						 */
						if ($change_list['created'] === false) {
							echo '<tr><td width="118px;"><strong>Changes</strong>:</td></tr>';

							if (empty($change_list['added']) === false) {
								echo '<tr>';
								echo '<td>New columns:</td>';
								echo '<td>' . Acfct_utils::get_span_tags_from_array($change_list['added']) . '</td>';
								echo '</tr>';
							}
							if (empty($change_list['updated']) === false) {
								echo '<tr>';
								echo '<td>Updated columns:</td>';
								echo '<td>' . Acfct_utils::get_span_tags_from_array($change_list['updated']) . '</td>';
								echo '</tr>';
							}
							if (empty($change_list['deleted']) === false) {
								echo '<tr>';
								echo '<td>Deleted columns:</td>';
								echo '<td>' . Acfct_utils::get_span_tags_from_array($change_list['deleted']) . '</td>';
								echo '</tr>';
							}
						}
						?>
                    </table>
                    <p>To apply these updates to your custom table, you need to run the update process on the <b>Manage Tables</b> screen.</p>
                    <p style="margin-top: 10px"><a class="acf-btn acf-btn-primary acf-btn-sm" href="<?php echo esc_url(ACF_CT_ADMIN_PAGE."&type=sql-view&acf_ct_post_id=".intval($_GET['post'])); ?>">Go to Manage Tables</a></p>
                </div>
			<?php
			/**
			 * Show invalid column names error
			 */
			else:
				?>
                <div class="notice notice-error is-dismissible">
                    <p><strong><?php echo esc_html(ACF_CT_PLUGIN_NAME); ?></strong></p>
					<?php
					if (empty($validate['invalid_columns']) === false) {
						if (count($validate['invalid_columns']) > 1) {
							echo '<p>MySQL reserved keywords ' . Acfct_utils::get_span_tags_from_array($validate['invalid_columns']) . ' are not allowed as a column name</p>';
						} else {
							echo '<p>MySQL reserved keyword ' . Acfct_utils::get_span_tags_from_array($validate['invalid_columns']) . ' is not allowed as a column name</p>';
						}
					}
					if (empty($validate['duplicate_columns']) === false) {
						$duplicate_columns = "";
						foreach ($validate['duplicate_columns'] as $column) {
							$duplicate_columns .= "<span class='highlight' style='padding: 3px; border-radius: 4px; font-weight: bold; background-color: #e6e7e8; margin-right: 4px;' >$column</span>";
						}
						echo '<p>Duplicate field names found: ' . wp_kses($duplicate_columns, ['span' => ['style'=>[], 'class'=>[]]]) . ' Please use unique name.</p>';
					}
					?>
                </div>
			<?php endif; ?>
		<?php }
	}

	public function get_acf_ct_table_class_instance($acf_field_group_key, $post_id)
	{

		$key = 'acf_ct_loaded_' . $acf_field_group_key;

		/**
		 * if key exists in $globals that means db values fetched for $acf_field_group_key
		 */
		if ($this->is_table_data_fetched($acf_field_group_key)) {
		    return $GLOBALS[$key];
		}

		$acf_field_groups = Acfct_utils::get_acf_field_groups_meta($post_id);
		foreach ($acf_field_groups as $field_group) {
			$table_name = $field_group['table'];
			$acf_post_id = $field_group['id'];

			if ($this->is_table_data_fetched($field_group['key'])) {
			    continue;
            }

			$tableInstance = new Acfct_table_data(Acfct_utils::format_post_id($post_id), $table_name);
			$tableInstance->fetchValues($acf_post_id);
			$GLOBALS['acf_ct_loaded_' . $field_group['key']] = $tableInstance;
			/**
			 * For non-local json mode, field parent is field_group id.
			 */
			$GLOBALS['acf_ct_loaded_' . $field_group['id']] = $tableInstance;
		}

		// if null then that means group is not present. ACF field group is not present in the DB.
		return array_key_exists($key, $GLOBALS) ? $GLOBALS[$key] : null;
	}

	public function acf_ct_field_group_custom_table_settings()
	{
		add_meta_box('acf-field-group-custom-table-settings', ACF_CT_PLUGIN_NAME, function () {
			include_once ACF_CUSTOM_TABLE_PATH . 'includes/views/acf-field-group-settings.php';
		}, 'acf-field-group', 'normal');
	}

	/**
	 * Load custom js on acf setting page
	 */
	public function acf_ct_inject_custom_js()
	{
		?>
        <script>
            (function ($) {
                var acf_ct_custom_table_checkbox = $('#acf_field_group-acf_ct_enable');
                var acf_ct_custom_table_name = $('[data-name=acf_ct_table_name]');

                if (acf_ct_custom_table_checkbox.is(':checked') === false) {
                    acf_ct_custom_table_name.hide();
                }

                acf_ct_custom_table_checkbox.change(function () {
                    if (this.checked) {
                        acf_ct_custom_table_name.show();
                    } else {
                        acf_ct_custom_table_name.hide();
                    }
                });
            })(jQuery);
        </script>
		<?php
	}

	public function acf_ct_register_menu()
	{
		add_submenu_page(
			'edit.php?post_type=acf-field-group',
			ACF_CT_PLUGIN_NAME,
			'Custom Tables',
			'manage_options',
			'acf-custom-table',
			'acf_custom_table_admin_view_callback'
		);
		function acf_custom_table_admin_view_callback()
		{
			include_once ACF_CUSTOM_TABLE_PATH . '/includes/views/acf-custom-table-view.php';
		}

	}

	public function acf_ct_enqueue_script()
	{
		if (isset($_GET['page']) && isset($_GET['type']) && $_GET['page'] === 'acf-custom-table' && $_GET['type'] === 'sql-view') {
			wp_enqueue_script('acf_ct_js', ACF_CUSTOM_TABLE_URL . 'includes/views/js/acf-ct.js', array('jquery'), false, true);
		}
	}

	public function acf_ct_settings_fields($field)
	{
		global $acf_ct_field_type_map; //used for this function only
		$parent_type = null;

		/**
		 * Check custom table enabled or not
		 */
		global $acf_ct_custom_table_name;
		if (is_null($acf_ct_custom_table_name)) {
			$acf_ct_custom_table_name = Acfct_utils::get_custom_table_name(get_the_ID());
		}

		/**
		 * Don't render setting if custom table not enabled
		 */
		if (!$acf_ct_custom_table_name) {
			return;
		}

		/**
		 * Initialize $acf_ct_field_type_map
		 */
		if (is_array($acf_ct_field_type_map) === false) {
			$acf_ct_field_type_map = array();
		}

		/**
		 * Get parent type of the current field
		 */
		if (array_key_exists($field['parent'], $acf_ct_field_type_map)) {
			$parent_type = $acf_ct_field_type_map[$field['parent']];
		}

		/**
		 * Set field type in the map
		 * id => field_type
		 */
		$acf_ct_field_type_map[$field['ID']] = $field['type'];

		/**
		 * List of types for which exclude column setting will not be shown
		 */
		$blacklisted_parent_types = array('flexible_content', 'repeater');
		$blacklisted_field_types = array('group', 'message', 'accordion', 'tab');

		/**
		 * Exclude column settings
		 */
		if (in_array($field['type'], $blacklisted_field_types) === false && in_array($parent_type, $blacklisted_parent_types) === false) {
			acf_render_field_setting($field, array(
				'label' => 'Exclude Column?',
				'instructions' => 'The column will not be created in custom table and data will be saved in post meta',
				'name' => 'acf_ct_exclude_column',
				'type' => 'true_false',
				'ui' => 1,
			), true);
		}

		/**
		 * Read only settings
		 */
		$blacklisted_read_only_parent_types = array('flexible_content', 'repeater');
		$blacklisted_read_only_field_types = array('group', 'message', 'accordion', 'tab', 'flexible_content',
			'repeater', 'gallery', 'file', 'wysiwyg', 'radio',
			'checkbox', 'button_group', 'link', 'relationship', 'taxonomy',
			'date_time_picker', 'time_picker', 'color_picker', 'image', 'true_false');

		if (in_array($field['type'], $blacklisted_read_only_field_types) === false && in_array($parent_type, $blacklisted_read_only_parent_types) === false) {
			acf_render_field_setting($field, array(
				'label' => 'Read Only',
				'instructions' => 'Only Administrator can edit this field. Other role users can only view the field.',
				'name' => 'acf_ct_read_only_field',
				'type' => 'true_false',
				'ui' => 1
			), true);
		}

	}

	/**
	 * Delete custom table row on post deleted
	 * @param $post_id
	 */
	public function delete_custom_table_row($post_id)
	{
		$custom_tables = Acfct_utils::get_custom_table_names_of_post($post_id);

		if (empty($custom_tables) === true) {
			return;
		}

		global $wpdb;
		/**
		 * Delete row of custom table where post_id = $post_id
		 */
		foreach ($custom_tables as $custom_table) {
			$delete_query = 'DELETE FROM ' . $custom_table . ' WHERE ' . ACF_CUSTOM_TABLE_POST_ID_COLUMN . ' = "' . $post_id . '"';
			$wpdb->query($delete_query);
		}
	}

	public function acf_ct_filter_pre_load_value($value, $post_id = false, $field = array())
	{

		$is_get_field_support_disabled = apply_filters('acf_ct/settings/disable_get_field_support', false);

		if (is_admin() === true || $is_get_field_support_disabled === true) {
			return null;
		}

		$selector = $field['name'];

		$columns = $this->get_columns_map($post_id);
		if (empty($columns) === true) {
			return null;
		}

		/**
		 * Check whether column exists for selector
		 */
		$column_meta = array_filter($columns, function ($column) use ($selector) {
			return $column['name'] === $selector;
		});

		/**
		 * return null if column not exists
		 */
		if (empty($column_meta)) {
			return null;
		}

		/**
		 * Fetch data from db
		 */
		$table = current($column_meta)['table'];
		$data = get_custom_table_fields($table, false, $post_id);

		if (is_array($data) && array_key_exists($selector, $data)) {
			return acf_ct_unserialize($data[$selector]);
		}

		return null;
	}

	public function acf_ct_filter_pre_update_value($check, $value, $post_id, $field)
	{

		$is_update_field_support_disabled = apply_filters('acf_ct/settings/disable_update_field_support', false);

		if ((is_admin() === true && $_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['action'] === 'editpost') || $is_update_field_support_disabled === true) {
			return null;
		}

		$selector = $field['name'];

		$columns = $this->get_columns_map($post_id);
		if (empty($columns) === true) {
			return null;
		}

		/**
		 * Check whether column exists for selector
		 */
		$column_meta = array_filter($columns, function ($column) use ($selector) {
			return $column['name'] === $selector;
		});

		/**
		 * return null if column not exists
		 */
		if (empty($column_meta)) {
			return null;
		}

		$table = current($column_meta)['table'];

		return update_custom_table_field($table, [$selector => $value], $post_id);
	}

	/**
	 * Add column in field group table
	 *
	 * @param $columns
	 * @return mixed
	 */
	public function acf_ct_field_group_columns($columns)
	{
		$columns['acf-ct-table-name'] = __('Custom Table');

		return $columns;
	}

	/**
	 * Render field group column data
	 *
	 * @param $column
	 * @param $post_id
	 */
	public function acf_ct_field_group_column_html($column, $post_id)
	{
		if ($column === 'acf-ct-table-name') {
			$tableName = Acfct_utils::get_custom_table_name($post_id, true);

			if (empty($tableName)) {
				echo "-";
				return;
			}

			$url = Acfct_utils::get_manage_table_page_url($post_id);
			$status = Acf_ct_database_manager::get_acf_fields_change_list($post_id);
			$label = false;

			if (is_array($status)) {
				if (array_key_exists('created', $status) && $status['created'] === true) {
					$label = 'Create Table';
				} else if (array_key_exists('should_update', $status) && $status['should_update'] === true) {
					$label = 'Update Table';
				}
			}

			echo '<a href="' . esc_url($url) . '">'. esc_html($tableName) . '</a>';

			if ($label !== false) {
				echo ' - <i style="color: #9e9999;">' . esc_html($label) . '</i>';
			}

		}
	}

	protected function get_columns_map($post_id)
	{

		/**
		 * Get all field groups associated with $post_id
		 */
		$acf_field_groups = Acfct_utils::get_acf_field_groups_meta($post_id);

		if (empty($acf_field_groups)) {
			return array();
		}

		/**
		 * Create map of columns and their table name
		 */
		$columns = array();

		/*
		 * Check for cache
		 */
		$columns_meta_cache_key = "acf_ct:$post_id:columns_meta";
		$columns_meta = wp_cache_get($columns_meta_cache_key);

		if ($columns_meta === false) {

			foreach ($acf_field_groups as $field_group) {
				$fields = Acfct_utils::get_acf_keys($field_group['id']);
				$table_name = $field_group['table'];

				$columns_meta = array_reduce($fields, function ($column_data, $field) use ($table_name) {
					$column_data[$field['key']] = array(
						'name' => $field['name'],
						'key' => $field['key'],
						'table' => $table_name
					);
					return $column_data;
				}, array());
				$columns = array_merge($columns, $columns_meta);
			}

			wp_cache_set($columns_meta_cache_key, $columns); //save in cache
		} else {
			$columns = $columns_meta; //cached value
		}

		return $columns;
	}

	protected function is_table_data_fetched($acfFieldGroupKey)
    {
		$key = 'acf_ct_loaded_' . $acfFieldGroupKey;

		return array_key_exists($key, $GLOBALS) && $GLOBALS[$key] instanceof Acfct_table_data;
    }
}

new Acf_ct_register_hooks();
