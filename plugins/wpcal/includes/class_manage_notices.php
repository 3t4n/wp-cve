<?php
/**
 * WPCal.io
 * Copyright (c) 2021 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Manage_Notices {

	public static function init() {

	}

	public static function add_notice($data, $options = []) {
		$result = self::update_notice($data, $notice_id = 0, $options);
		return $result;
	}

	public static function update_notice($update_data, $notice_id = 0, $options = []) {
		global $wpdb;

		if ($notice_id) {
			$is_update = true;
		} else {
			$is_update = false;
		}

		$allowed_keys = [
			'slug',
			'slug_version',
			'status',
			'category',
			'title',
			'descr',
			'source',
			'type',
			'display_type',
			'notice_data',
			'display_in',
			'display_in_condition',
			'display_to',
			'display_user_ids',
			'dismiss_type',
			'dismiss_by',
			'dismissed_user_ids',
			'from_time_ts',
			'to_time_ts',
			'sub_notices',
			'must_revalidate',
		];

		$default_data = [
			'slug_version' => 0,
			'status' => 'pending',
			'source' => 'plugin',
			'display_type' => 'notice',
			'must_revalidate' => 0,
		];

		$data = array_merge($default_data, $update_data);

		$data = wpcal_get_allowed_fields($data, $allowed_keys);

		$validate_obj = new WPCal_Validate($data);
		$validation_rules = [
			'required' => [
				'slug',
				'category',
				'source',
				'type',
				'display_in',
				'dismiss_type',
				'dismiss_by',
			],
			// 'requiredWithIf' => [
			// ],
			'integer' => [
				'slug_version',
				'display_user_ids.*',
				'dismissed_user_ids.*',
			],
			// 'lengthMin' => [
			// 	['invitee_questions.questions.*.question', 1],
			// ],
			// 'lengthMax' => [
			// 	['locations.*.form.location', 500],
			// 	['locations.*.form.location_extra', 500]
			// ],
			'min' => [
				['slug_version', 0],
				['must_revalidate', 0],
			],
			'max' => [
				['slug_version', 65000],
				['must_revalidate', 1],

			],
			'in' => [
				['status', ['pending', 'started', 'completed', 'dismissed', 'error', 'replaced', 'revoked']],
				['source', ['server', 'cron_server', 'plugin']],
				['type', ['info', 'success', 'warning', 'error']],
				['display_type', ['notice']],
				['display_in', ['wp_admin_and_wpcal_admin', 'wp_admin', 'wpcal_admin']],
				['display_to', ['wp_admins', 'wp_admin', 'wpcal_admins', 'wpcal_admin']],
				['dismiss_type', ['not_dismissible', 'dismissible', 'sub_notice_dismissible']],
				['dismiss_by', ['any_one', 'individual']],
			],
			'dateFormat' => [
				['from_time_ts', 'U'],
				['to_time_ts', 'U'],
			],
			'array' => [
				'display_user_ids',
				'dismissed_user_ids',
			],
			'arrayLength' => [
				['service_admin_user_ids', 1],
			],
		];
		if (empty($data['display_user_ids'])) {
			$validation_rules['required'][] = 'display_to';
		}
		$validate_obj->rules($validation_rules);

		if (!$validate_obj->validate()) {
			$validation_errors = $validate_obj->errors(); //output should be an array
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}

		if (!empty($data['notice_data'])) {
			$data['notice_data'] = json_encode($data['notice_data']);
		} else {
			unset($data['notice_data']);
		}

		if (!empty($data['display_user_ids'])) {
			$data['display_user_ids'] = array_unique($data['display_user_ids']);
			sort($data['display_user_ids']);
			$data['display_user_ids'] = implode(',', $data['display_user_ids']);
		}
		if (!empty($data['dismissed_user_ids'])) {
			$data['dismissed_user_ids'] = array_unique($data['dismissed_user_ids']);
			sort($data['dismissed_user_ids']);
			$data['dismissed_user_ids'] = implode(',', $data['dismissed_user_ids']);
		}

		if (!empty($data['display_in_condition'])) {
			$data['display_in_condition'] = json_encode($data['display_in_condition']);
		} else {
			unset($data['display_in_condition']);
		}

		if (!empty($data['sub_notices'])) {
			$data['sub_notices'] = json_encode($data['sub_notices']);
		} else {
			unset($data['sub_notices']);
		}

		self::may_remove_old_notices($options['remove_old_notice_by'] ?? '', $data);

		$table_notices = $wpdb->prefix . 'wpcal_notices';

		$data['updated_ts'] = time();
		if (!$is_update) {
			$data['status'] = 'pending';
			$data['added_ts'] = time();
		}

		if ($is_update) {
			$result = $wpdb->update($table_notices, $data, ['id' => $notice_id]);
		} else {
			$result = $wpdb->insert($table_notices, $data);
			if ($result !== false) {
				$notice_id = $wpdb->insert_id;
				if (!$notice_id) {
					throw new WPCal_Exception('db_error_insert_id_missing');
				}
			}
		}

		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}

		return $notice_id;
	}

	public static function show_wp_admin_notices() {

		$display_in = 'wp_admin';
		if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'wpcal_admin') {
			$display_in = 'wpcal_admin';
		}
		$result = self::check_and_get_notices_may_print_for_current_user($display_in);
	}

	public static function check_and_get_notices_may_print_for_current_user($display_in) {
		$notices = self::get_notices_for_current_user($display_in);

		$final_notices = [];
		foreach ($notices as $_key => $notice) {
			$notice_obj = new WPCal_Notice($notice->id, $notice);
			if ($notice_obj->can_show()) {
				if ($notice_obj->get_status() == 'pending') {
					$notice_obj->update_status('started');
				}
				if ($display_in == 'wp_admin' || $display_in == 'wpcal_admin') { //currently all options here - will improved along with js notice communication to admin end client
					self::print_notice_for_wp_admin($notice_obj);
				} else {
					$final_notices[] = $notice;
				}
			}
		}
		return $final_notices;
	}

	public static function get_notices_for_current_user($display_in) {
		global $wpdb;
		$is_current_user_is_wpcal_admin = WPCal_Admins::is_current_user_is_wpcal_admin();
		$current_user_id = get_current_user_id();
		$current_time = time();

		$table_notices = $wpdb->prefix . 'wpcal_notices';
		$query = "SELECT * FROM `$table_notices` WHERE  1=1 AND `status` IN ('pending', 'started')";

		//display_in filter
		$query .= " AND `display_in` IN('$display_in', 'wp_admin_and_wpcal_admin')";

		//display_to filter
		$possible_display_tos = [];
		if ($display_in == 'wp_admin') { //we can assume the user having wp admin rights
			$possible_display_tos[] = 'wp_admins';
			$possible_display_tos[] = 'wp_admin';
			$possible_display_tos[] = 'wpcal_admins';
			$possible_display_tos[] = 'wpcal_admin';
		} elseif ($display_in == 'wpcal_admin') { //display_in is location
			$possible_display_tos[] = 'wpcal_admins';
			$possible_display_tos[] = 'wpcal_admin';
		}
		// if (empty($possible_display_tos)) {
		// 	//possible error
		// 	return [];
		// }

		if ($display_in == 'wpcal_admin' && !$is_current_user_is_wpcal_admin) {
			//need to show in wpcal_admin area, but current user is not wpcal_admin
			return [];
		}

		$possible_display_tos_sql_in = wpcal_implode_for_sql($possible_display_tos);
		$query .= " AND (`display_to` IN($possible_display_tos_sql_in) OR (`display_to` IS NULL AND FIND_IN_SET($current_user_id, `display_user_ids`) IS TRUE ) )";

		//display_tos 'wpcal_admins' and 'wpcal_admin' requires wpcal_admin active as logged in admin
		$query .= " AND (
			`display_to` IS NULL
			OR
			(
				`display_to` IN('wpcal_admins', 'wpcal_admin') AND
				'1'  = '" . (int) $is_current_user_is_wpcal_admin . "'
			)
			OR
			(
				`display_to` NOT IN('wpcal_admins', 'wpcal_admin')
			)
		)";

		$query .= " AND (`display_to` IN($possible_display_tos_sql_in) OR (`display_to` IS NULL AND FIND_IN_SET($current_user_id, `display_user_ids`) IS TRUE ) )";

		//display_user_id filter if exists
		$query .= " AND ( `display_user_ids` IS NULL" . ($current_user_id ? " || FIND_IN_SET($current_user_id, `display_user_ids`) " : "") . " )";

		//dismiss_type and dismissed_user_ids filter - sub_notice_dismissible will handled in php
		$query .= " AND (`dismiss_type` != 'dismissible' OR (`dismiss_type` = 'dismissible' AND FIND_IN_SET($current_user_id, `dismissed_user_ids`) IS NOT TRUE ) )"; //0 and NULL are NOT TRUE

		//time filter
		$query .= " AND (`from_time_ts` IS NULL OR `from_time_ts` <= '$current_time' )";
		$query .= " AND (`to_time_ts` IS NULL OR `to_time_ts` >= '$current_time' )";

		$query .= " ORDER BY `id` ASC LIMIT 10";

		$result = $wpdb->get_results($query);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		if (empty($result)) {
			return [];
		}
		return $result;
	}

	public static function get_notices($options) {
		global $wpdb;

		//assuming data stored in display_user_ids, dismissed_user_ids are always unique and sorted

		$table_notices = $wpdb->prefix . 'wpcal_notices';
		$query = "SELECT * FROM `$table_notices` WHERE  1=1 AND `status` IN ('pending', 'started')";

		if (!empty($options['category'])) {
			$query .= $wpdb->prepare(" AND `category` = %s", $options['category']);
		}

		if (!empty($options['slug'])) {
			$query .= $wpdb->prepare(" AND `slug` = %s", $options['slug']);
		}

		if (!empty($options['slug_version'])) {
			$query .= $wpdb->prepare(" AND `slug_version` = %s", $options['slug_version']);
		}

		if (!empty($options['status'])) {
			if (is_string($options['status'])) {
				$query .= $wpdb->prepare(" AND `status` = %s", $options['status']);
			} elseif (is_array($options['status'])) {
				$_statuses = esc_sql($options['status']);
				$_statuses_imploded_sql = wpcal_implode_for_sql($_statuses);
				$query .= " AND `status` IN($_statuses_imploded_sql)";
			}
		}

		if (!empty($options['source'])) {
			$query .= $wpdb->prepare(" AND `source` = %s", $options['source']);
		}

		if (isset($options['display_to'])) {
			if ($options['display_to'] === null) {
				$query .= " AND `display_to` IS NULL";
			} else {
				$query .= $wpdb->prepare(" AND `display_to` = %s", $options['display_to']);
			}
		}

		if (isset($options['display_user_ids'])) {
			if ($options['display_user_ids'] === null) {
				$query .= " AND `display_user_ids` IS NULL";
			} else {
				$query .= $wpdb->prepare(" AND `display_user_ids` = %s", $options['display_user_ids']);
			}
		}

		$query .= " ORDER BY `id` ASC";

		$result = $wpdb->get_results($query, OBJECT_K);
		if ($result === false) {
			throw new WPCal_Exception('db_error', '', $wpdb->last_error);
		}
		return $result;
	}

	public static function may_remove_old_notices($rule, $new_notice_data) {
		global $wpdb;

		//assuming data stored in display_user_ids, dismissed_user_ids are always unique and sorted

		$table_notices = $wpdb->prefix . 'wpcal_notices';

		if (empty($rule)) {
			return;
		}
		if ($rule == 'category_and_user') {

			$query = $wpdb->prepare("UPDATE `$table_notices` SET `status` = 'replaced', `updated_ts` = %s WHERE `status` IN('pending', 'started') AND `category` = %s", time(), $new_notice_data['category']);

			if (empty($new_notice_data['display_to']) || $new_notice_data['display_to'] === null) {
				$query .= " AND `display_to` IS NULL";
			} else {
				$query .= $wpdb->prepare(" AND `display_to` = %s", $new_notice_data['display_to']);
			}

			if (empty($new_notice_data['display_user_ids']) || $new_notice_data['display_user_ids'] === null) {
				$query .= " AND `display_user_ids` IS NULL";
			} else {
				$query .= $wpdb->prepare(" AND `display_user_ids` = %s", $new_notice_data['display_user_ids']);
			}

			return $wpdb->query($query);

		} elseif ($rule == 'category_and_slug') {

			$query = $wpdb->prepare("UPDATE `$table_notices` SET `status` = 'replaced', `updated_ts` = %s WHERE `status` IN('pending', 'started') AND `category` = %s AND `slug` = %s", time(), $new_notice_data['category'], $new_notice_data['slug']);
			return $wpdb->query($query);
		}
	}

	public static function print_notice_for_wp_admin($notice_obj) {
		static $is_dismiss_script_printed = false;

		$title = $notice_obj->get_title_final();
		$descr = $notice_obj->get_descr_final();

		$final_title = 'WPCal.io' . ($title ? ' - ' . $title : '');

		$is_dismissible = $notice_obj->get_dismiss_type() != 'not_dismissible';

		$notice_class = 'notice-' . $notice_obj->get_type();
		$dismiss_class = $is_dismissible ? 'wpcal_dismissible' : '';
		$notice_id = $notice_obj->get_id();

		$dismissable_html = $is_dismissible ? '<button type="button" class="notice-dismiss wpcal_notice_dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>' : '';

		printf('<div class="notice wpcal_notice %1$s %2$s" data-wpcal-notice-id="%5$s"><h3>%3$s</h3><p>%4$s</p>%6$s</div>', esc_attr($notice_class), esc_attr($dismiss_class), $final_title, $descr, $notice_id, $dismissable_html);

		if (!$is_dismiss_script_printed && $is_dismissible) {
			$ajax_request_action_end = WPCal_Admins::is_current_user_is_wpcal_admin() ? 'admin' : 'user'; //notice to non WPCal admins will use wpcal_process_user_ajax_request
			?>
			<style>
				.notice.wpcal_notice {
				border: 1px solid #567bf3;
				border-left-width: 3px;
				background-color: #fff;
				border-radius: 5px;
				box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
				margin: 10px 20px 10px 0;
				padding: 15px;
				/* font-family: "Rubik"; */
				}
				/* .notice.wpcal_notice strong, .notice.wpcal_notice h3 { font-family: "RubikMed"; font-weight:unset;} */
				.notice.wpcal_notice h3 {
				margin-top: 0;
				margin-bottom: 15px;
				font-size: 16px;
				display: block;
				}
				.notice.wpcal_notice p {
				margin: 0;
				padding: 0;
				}
				.notice.wpcal_notice ul {
				margin: 0;
				}
				.notice.wpcal_notice ul li {
				list-style: disc;
				margin-left: 20px;
				}
				.notice.wpcal_notice a {
				color: #567bf3;
				}
				.notice.wpcal_notice a.btn {
				background-color: #567bf3;
				color: #fff;
				display: inline-block;
				text-decoration: none;
				padding: 5px 10px;
				border-radius: 5px;
				margin-right: 5px;
				margin-top: 15px;
				}
				.notice.wpcal_notice,
				.notice.wpcal_notice.notice-info {
				border-color: #567bf3;
				box-shadow: inset 0 1000px 0 rgb(86 123 243 / 5%), 0 1px 1px rgb(0 0 0 / 10%);
				}
				.notice.wpcal_notice.notice-error {
				border-color: #e84653;
				box-shadow: inset 0 1000px 0 rgb(232 70 83 / 5%), 0 1px 1px rgb(0 0 0 / 10%);
				}
				.notice.wpcal_notice.notice-success {
				border-color: #66bb6a;
				box-shadow: inset 0 1000px 0 rgb(102 187 106 / 5%), 0 1px 1px rgb(0 0 0 / 10%);
				}
				.notice.wpcal_notice.notice-warning {
				border-color: #e19334;
				box-shadow: inset 0 1000px 0 rgb(225 147 52 / 5%), 0 1px 1px rgb(0 0 0 / 10%);
				}
				.wpcal_dismissible{padding-right: 38px !important; position: relative;}
			</style>
			<script type="text/javascript">
			jQuery(function(){
				jQuery('.wpcal_notice.wpcal_dismissible .wpcal_notice_dismiss').on('click', function(event){
					event.stopPropagation();
					let notice_element = jQuery(this).closest('.wpcal_notice.wpcal_dismissible');
					if(!notice_element){
						return;
					}
					let notice_id = notice_element.data('wpcal-notice-id');
					if(!notice_id){
						return;
					}
					let wpcal_request = {};
					wpcal_request['dismiss_notice_for_current_user'] = {
						notice_id: notice_id
					};
					let post_data = {
						action: 'wpcal_process_<?php echo $ajax_request_action_end; ?>_ajax_request',
						wpcal_request: wpcal_request
					};
					notice_element.remove();

					jQuery.ajax({
						type: "POST",
						dataType: "html",
						url: ajaxurl, //ajaxurl defined by WP in admin end only https://codex.wordpress.org/AJAX_in_Plugins
						data: post_data,
						success: function(msg){
							console.log(msg);
						}
					});
				});
			});
			</script>
			<?php

			$is_dismiss_script_printed = true;
		}
	}

	public static function dismiss_notice_for_current_user($notice_id) {
		$notice_obj = new WPCal_Notice($notice_id);
		return $notice_obj->process_dismiss();
		//if going to use this $notice_obj, make sure to call load() for updated data
	}

	private static function get_allowed_tags() {
		global $allowedtags;
		$notice_allowed_tags = [
			'a' => [
				'href' => true,
				'target' => true,
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'div' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'span' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'ul' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'ol' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'li' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'label' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'em' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'h1' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'h2' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'h3' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'h4' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'h5' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'h6' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
			'code' => [
				'title' => true,
				'style' => true,
				'class' => true,
			],
		];
		$final_allowed_tags = array_merge($allowedtags, $notice_allowed_tags);
		return $final_allowed_tags;
	}

	public static function sync_server_notices($notifications) {
		//$notifications only if notifications array key comes, this should be called it can be empty array
		global $wpdb;
		$allowed_tags = self::get_allowed_tags();

		$all_existing_server_notices = self::get_notices(['source' => 'server']);

		foreach ($notifications as $new_notice) {
			$new_notice['source'] = 'server';

			//santizing html
			$new_notice['title'] = !empty($new_notice['title']) ? wp_kses($new_notice['title'], $allowed_tags) : $new_notice['title'];
			$new_notice['descr'] = !empty($new_notice['descr']) ? wp_kses($new_notice['descr'], $allowed_tags) : $new_notice['descr'];

			try {
				$find_notice_conditions = [
					'source' => 'server',
					'category' => $new_notice['category'],
					'slug' => $new_notice['slug'],
				];
				$found_result = self::get_notices($find_notice_conditions);
				if (!empty($found_result)) {

					//this will update the only one row, other will be revoked
					$_found_result = $found_result;
					$found_notice = array_shift($_found_result);
					if (in_array($found_notice->status, ['started', 'pending'])) {
						if ($found_notice->slug_version < $new_notice['slug_version']) {
							//update the notice
							self::update_notice((array) $new_notice, $found_notice->id);
						}
						unset($all_existing_server_notices[$found_notice->id]);
					}

				} else {
					self::add_notice((array) $new_notice);
				}
			} catch (WPCal_Exception $e) {
				//lets not throw error
			}
		}

		$existing_service_notice_ids = array_keys($all_existing_server_notices);
		if (!empty($existing_service_notice_ids)) {
			$table_notices = $wpdb->prefix . 'wpcal_notices';
			$update_revoked_noticed_ids_imploded_sql = wpcal_implode_for_sql($existing_service_notice_ids);
			$revoke_query = "UPDATE `$table_notices` SET `status` = 'revoked' WHERE `id` IN($update_revoked_noticed_ids_imploded_sql)";
			$wpdb->query($revoke_query);
		}
	}
}
