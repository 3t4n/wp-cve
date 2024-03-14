<?php
global $wpdb, $arm_lite_newdbversion;

if ( version_compare( $arm_lite_newdbversion, '1.5', '<' ) ) {
	global $wpdb, $wp, $ARMemberLite;
	$pt_log_table = $ARMemberLite->tbl_arm_payment_log;
	$bt_log_table = $ARMemberLite->tbl_arm_bank_transfer_log;

	$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_coupon_on_each_subscriptions` TINYINT(1) NULL DEFAULT '0' AFTER `arm_coupon_discount_type`; ") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm

	$wpdb->query( $wpdb->prepare("ALTER TABLE `{$bt_log_table}` ADD `arm_coupon_on_each_subscriptions` TINYINT(1) NULL DEFAULT '0' AFTER `arm_coupon_discount_type`; ") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm

}

if ( version_compare( $arm_lite_newdbversion, '1.8', '<' ) ) {
	 global $arm_global_settings, $arm_member_forms;

	 $all_global_settings                                        = $arm_global_settings->arm_get_all_global_settings();
	 $all_global_settings['general_settings']['spam_protection'] = 1;
	 $new_global_settings_result                                 = $all_global_settings;
	 update_option( 'arm_global_settings', $new_global_settings_result );

	$old_preset_fields     = get_option( 'arm_preset_form_fields' );
	$old_preset_fields     = maybe_unserialize( maybe_unserialize( $old_preset_fields ) );
	$default_preset_fields = $arm_member_forms->arm_default_preset_user_fields();
	if ( isset( $default_preset_fields['country']['options'] ) && ! empty( $default_preset_fields['country']['options'] ) && isset( $old_preset_fields['default']['country'] ) ) {
		$old_preset_fields['default']['country']['options'] = $default_preset_fields['country']['options'];

		$updated_preset_fields = $old_preset_fields;
		update_option( 'arm_preset_form_fields', $updated_preset_fields );
	}
}
if ( version_compare( $arm_lite_newdbversion, '2.1', '<' ) ) {
	global $wpdb, $wp, $ARMemberLite;
	$pt_log_table            = $ARMemberLite->tbl_arm_payment_log;
	$bt_log_table            = $ARMemberLite->tbl_arm_bank_transfer_log;
	$arm_bank_table_log_flag = get_option( 'arm_bank_table_log_flag' );

	$arm_old_plan_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_old_plan_id') );
	if ( empty( $arm_old_plan_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `".$pt_log_table."` ADD `arm_old_plan_id` bigint(20) NOT NULL DEFAULT '0' AFTER `arm_plan_id`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}

	$arm_payment_cycle_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_payment_cycle') );
	if ( empty( $arm_payment_cycle_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_payment_cycle` INT(11) NOT NULL DEFAULT '0' AFTER `arm_payment_mode`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}

	$arm_bank_name_row = $wpdb->get_results(  $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_bank_name') );
	if ( empty( $arm_bank_name_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_bank_name` VARCHAR(255) DEFAULT NULL AFTER `arm_payment_cycle`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}
	$arm_account_name_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_account_name') );
	if ( empty( $arm_account_name_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_account_name` VARCHAR(255) DEFAULT NULL AFTER `arm_bank_name`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}
	$arm_additional_info_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_additional_info') );
	if ( empty( $arm_additional_info_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_additional_info` LONGTEXT AFTER `arm_account_name`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}
	$arm_first_name_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_first_name') );
	if ( empty( $arm_first_name_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_first_name` VARCHAR(255) DEFAULT NULL AFTER `arm_user_id`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}
	$arm_last_name_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_last_name') );
	if ( empty( $arm_last_name_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_last_name` VARCHAR(255) DEFAULT NULL AFTER `arm_first_name`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}

	$arm_payment_transfer_mode_row = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$pt_log_table,'arm_payment_transfer_mode') );
	if ( empty( $arm_payment_transfer_mode_row ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$pt_log_table}` ADD `arm_payment_transfer_mode` VARCHAR( 255 ) NULL AFTER `arm_additional_info`") );//phpcs:ignore --Reason: $pt_log_table is a table name. False Positive alarm
	}

	if ( empty( $arm_bank_table_log_flag ) ) {

		update_option( 'arm_bank_table_log_flag', '1' );


		$bt_payment_log = $wpdb->get_results( $wpdb->prepare('SELECT * FROM `' . $bt_log_table . '`'), ARRAY_A );//phpcs:ignore --Reason: $bt_log_table is a table name. False Positive alarm
		if ( count( $bt_payment_log ) > 0 ) {
			foreach ( $bt_payment_log as $bt_payment_log_data ) {
				$arm_first_name   = get_user_meta( $bt_payment_log_data['arm_user_id'], 'first_name', true );
				$arm_last_name    = get_user_meta( $bt_payment_log_data['arm_user_id'], 'last_name', true );
				$arm_payment_mode = ( ! empty( $bt_payment_log_data['arm_payment_mode'] ) ) ? $bt_payment_log_data['arm_payment_mode'] : 'one_time';
				$arm_payment_type = ( ! empty( $bt_payment_log_data['arm_payment_mode'] ) && $bt_payment_log_data['arm_payment_mode'] == 'manual_subscription' ) ? 'subscription' : 'one_time';
				$bt_insert_result = $wpdb->insert(
					$pt_log_table,
					array(
						'arm_invoice_id'                   => $bt_payment_log_data['arm_invoice_id'],
						'arm_user_id'                      => $bt_payment_log_data['arm_user_id'],
						'arm_first_name'                   => $arm_first_name,
						'arm_last_name'                    => $arm_last_name,
						'arm_plan_id'                      => $bt_payment_log_data['arm_plan_id'],
						'arm_old_plan_id'                  => $bt_payment_log_data['arm_old_plan_id'],
						'arm_payer_email'                  => $bt_payment_log_data['arm_payer_email'],
						'arm_transaction_id'               => $bt_payment_log_data['arm_transaction_id'],
						'arm_transaction_payment_type'     => $arm_payment_type,
						'arm_payment_mode'                 => $arm_payment_mode,
						'arm_payment_type'                 => $arm_payment_type,
						'arm_payment_gateway'              => 'bank_transfer',
						'arm_payment_cycle'                => $bt_payment_log_data['arm_payment_cycle'],
						'arm_bank_name'                    => $bt_payment_log_data['arm_bank_name'],
						'arm_account_name'                 => $bt_payment_log_data['arm_account_name'],
						'arm_additional_info'              => $bt_payment_log_data['arm_additional_info'],
						'arm_amount'                       => $bt_payment_log_data['arm_amount'],
						'arm_currency'                     => $bt_payment_log_data['arm_currency'],
						'arm_extra_vars'                   => $bt_payment_log_data['arm_extra_vars'],
						'arm_coupon_code'                  => $bt_payment_log_data['arm_coupon_code'],
						'arm_coupon_discount'              => $bt_payment_log_data['arm_coupon_discount'],
						'arm_coupon_discount_type'         => $bt_payment_log_data['arm_coupon_discount_type'],
						'arm_coupon_on_each_subscriptions' => $bt_payment_log_data['arm_coupon_on_each_subscriptions'],
						'arm_transaction_status'           => $bt_payment_log_data['arm_status'],
						'arm_is_trial'                     => $bt_payment_log_data['arm_is_trial'],
						'arm_display_log'                  => $bt_payment_log_data['arm_display_log'],
						'arm_payment_date'                 => $bt_payment_log_data['arm_created_date'],
						'arm_created_date'                 => $bt_payment_log_data['arm_created_date'],
					)
				);

			}
		}
	}
}

if ( version_compare( $arm_lite_newdbversion, '2.4', '<' ) ) {

	global $wpdb, $wp, $ARMemberLite;

	$arm_pt_log_table             = $ARMemberLite->tbl_arm_payment_log;
	$arm_entries_table            = $ARMemberLite->tbl_arm_entries;
	$arm_subscription_plans_table = $ARMemberLite->tbl_arm_subscription_plans;
	$arm_activity_table           = $ARMemberLite->tbl_arm_activity;
	$arm_membership_setup_table   = $ARMemberLite->tbl_arm_membership_setup;

	$arm_add_payment_log_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_pt_log_table,'arm_is_post_payment') );
	if ( empty( $arm_add_payment_log_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_pt_log_table}` ADD `arm_is_post_payment` TINYINT(1) NOT NULL DEFAULT '0' AFTER `arm_is_trial`") );//phpcs:ignore --Reason: $arm_pt_log_table is a table name. False Positive alarm
	}

	$arm_add_payment_log_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_pt_log_table,'arm_paid_post_id') );
	if ( empty( $arm_add_payment_log_col ) ) {
		$wpdb->query(  $wpdb->prepare("ALTER TABLE `{$arm_pt_log_table}` ADD `arm_paid_post_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `arm_is_post_payment`") );//phpcs:ignore --Reason: $arm_pt_log_table is a table name. False Positive alarm
	}

	$arm_add_entries_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_entries_table,'arm_is_post_entry') );
	if ( empty( $arm_add_entries_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_entries_table}` ADD `arm_is_post_entry` TINYINT(1) NOT NULL DEFAULT '0' AFTER `arm_plan_id`") );//phpcs:ignore --Reason: $arm_entries_table is a table name. False Positive alarm
	}

	$arm_add_entries_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_entries_table,'arm_paid_post_id') );
	if ( empty( $arm_add_entries_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_entries_table}` ADD `arm_paid_post_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `arm_is_post_entry`") );//phpcs:ignore --Reason: $arm_entries_table is a table name. False Positive alarm
	}

	$arm_add_subscription_plans = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_subscription_plans_table,'arm_subscription_plan_post_id') );
	if ( empty( $arm_add_subscription_plans ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_subscription_plans_table}` ADD `arm_subscription_plan_post_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `arm_subscription_plan_role`") );//phpcs:ignore --Reason: $arm_subscription_plans_table is a table name. False Positive alarm
	}

	$arm_add_activity_post_id = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_activity_table,'arm_paid_post_id') );
	if ( empty( $arm_add_activity_post_id ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_activity_table}` ADD `arm_paid_post_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `arm_item_id`") );//phpcs:ignore --Reason: $arm_activity_table is a table name. False Positive alarm
	}

	$arm_add_setup_type = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_membership_setup_table,'arm_setup_type') );
	if ( empty( $arm_add_setup_type ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_membership_setup_table}` ADD `arm_setup_type` TINYINT(1) NOT NULL DEFAULT '0' AFTER `arm_setup_name`") );//phpcs:ignore --Reason: $arm_membership_setup_table is a table name. False Positive alarm
	}
}

if ( version_compare( $arm_lite_newdbversion, '3.4.2', '<' ) ) {
	global $ARMemberLite, $wpdb;

	$arm_updt_preset_field_option = 0;
	$get_preset_form_fields       = get_option( 'arm_preset_form_fields' );
	if ( isset( $get_preset_form_fields['default'] ) ) {
		if ( empty( $get_preset_form_fields['default']['user_login']['label'] ) ) {
			$get_preset_form_fields['default']['user_login']['label'] = esc_html__( 'Username', 'armember-membership' );
			$arm_updt_preset_field_option                             = 1;
		}
		if ( empty( $get_preset_form_fields['default']['user_email']['label'] ) ) {
			$get_preset_form_fields['default']['user_email']['label'] = esc_html__( 'Email Address', 'armember-membership' );
			$arm_updt_preset_field_option                             = 1;
		}
		if ( empty( $get_preset_form_fields['default']['user_pass']['label'] ) ) {
			$get_preset_form_fields['default']['user_pass']['label'] = esc_html__( 'Password', 'armember-membership' );
			$arm_updt_preset_field_option                            = 1;
		}

		if ( $arm_updt_preset_field_option == 1 ) {
			update_option( 'arm_preset_form_fields', $get_preset_form_fields );

			$arm_form_field_option_arr                        = array();
			$arm_form_field_option_arr[0]['form_id']          = 101;
			$arm_form_field_option_arr[0]['form_field_slug']  = 'user_login';
			$arm_form_field_option_arr[0]['form_field_label'] = esc_html__( 'Username', 'armember-membership' );

			$arm_form_field_option_arr[1]['form_id']          = 101;
			$arm_form_field_option_arr[1]['form_field_slug']  = 'user_pass';
			$arm_form_field_option_arr[1]['form_field_label'] = esc_html__( 'Password', 'armember-membership' );

			$arm_form_field_option_arr[2]['form_id']          = 102;
			$arm_form_field_option_arr[2]['form_field_slug']  = 'user_login';
			$arm_form_field_option_arr[2]['form_field_label'] = esc_html__( 'Username', 'armember-membership' );

			$arm_form_field_option_arr[3]['form_id']          = 102;
			$arm_form_field_option_arr[3]['form_field_slug']  = 'user_pass';
			$arm_form_field_option_arr[3]['form_field_label'] = esc_html__( 'Password', 'armember-membership' );

			$arm_form_field_option_arr[4]['form_id']          = 103;
			$arm_form_field_option_arr[4]['form_field_slug']  = 'user_login';
			$arm_form_field_option_arr[4]['form_field_label'] = esc_html__( 'Username OR Email Address', 'armember-membership' );

			foreach ( $arm_form_field_option_arr as $arm_form_field_option_val_arr ) {
				$arm_form_field_form_id = $arm_form_field_option_val_arr['form_id'];
				$arm_form_field_slug    = $arm_form_field_option_val_arr['form_field_slug'];
				$form_field_label       = $arm_form_field_option_val_arr['form_field_label'];

				$arm_check_form_user_login_arr = $wpdb->get_row( $wpdb->prepare('SELECT `arm_form_field_option` FROM `' . $ARMemberLite->tbl_arm_form_field . "` WHERE `arm_form_field_form_id`=%d AND `arm_form_field_slug`=%s ",$arm_form_field_form_id,$arm_form_field_slug), ARRAY_A );//phpcs:ignore --Reason: $tbl_arm_form_field is a table name .False Positive alarm

				$arm_form_field_option = maybe_unserialize( $arm_check_form_user_login_arr['arm_form_field_option'] );
				if ( ! empty( $arm_form_field_option ) && is_array( $arm_form_field_option ) ) {
					if ( empty( $arm_form_field_option['label'] ) ) {
						$arm_form_field_option['label'] = $form_field_label;

						$update_form_field_data = array( 'arm_form_field_option' => maybe_serialize( $arm_form_field_option ) );
						$form_update            = $wpdb->update(
							$ARMemberLite->tbl_arm_form_field,
							$update_form_field_data,
							array(
								'arm_form_field_form_id' => $arm_form_field_form_id,
								'arm_form_field_slug'    => $arm_form_field_slug,
							)
						);
					}
				}
			}
		}
	}
}

if ( version_compare( $arm_lite_newdbversion, '3.4.4', '<' ) ) {

	global $wpdb, $wp, $ARMemberLite;

	$arm_pt_log_table             = $ARMemberLite->tbl_arm_payment_log;
	$arm_entries_table            = $ARMemberLite->tbl_arm_entries;
	$arm_subscription_plans_table = $ARMemberLite->tbl_arm_subscription_plans;
	$arm_activity_table           = $ARMemberLite->tbl_arm_activity;

	// Add the arm_subscription_plan_gift_status for the Gift
	$arm_add_subscription_plan_gift_status_column = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s", DB_NAME,$arm_subscription_plans_table,'arm_subscription_plan_gift_status') );
	if ( empty( $arm_add_subscription_plan_gift_status_column ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_subscription_plans_table}` ADD `arm_subscription_plan_gift_status` INT(1) NOT NULL DEFAULT '0' AFTER `arm_subscription_plan_post_id`") );//phpcs:ignore --Reason $arm_subscription_plans_table is a table name . False Positive Alarm
	}

	// Add the arm_gift_plan_id for the Gift
	$arm_add_activity_column = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s", DB_NAME, $arm_activity_table,'arm_gift_plan_id') );
	if ( empty( $arm_add_activity_column ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_activity_table}` ADD `arm_gift_plan_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `arm_paid_post_id`") );//phpcs:ignore --Reason $arm_activity_table is a table name . False Positive Alarm
	}

	// Add column arm_is_gift_payment for gift.
	$arm_add_payment_log_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s", DB_NAME,$arm_pt_log_table,'arm_is_gift_payment') );
	if ( empty( $arm_add_payment_log_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_pt_log_table}` ADD `arm_is_gift_payment` TINYINT(1) NOT NULL DEFAULT '0' AFTER `arm_paid_post_id`") );//phpcs:ignore --Reason $arm_pt_log_table is a table name . False Positive Alarm
	}

	$arm_add_entries_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s", DB_NAME,$arm_entries_table,'arm_is_gift_entry') );
	if ( empty( $arm_add_entries_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_entries_table}` ADD `arm_is_gift_entry` TINYINT(1) NOT NULL DEFAULT '0' AFTER `arm_paid_post_id`") );//phpcs:ignore --Reason $arm_entries_table is a table name . False Positive Alarm
	}

	// update setup default style with old colors.
	
	$setup_data    = $wpdb->get_results( $wpdb->prepare('SELECT *  FROM `' . $ARMemberLite->tbl_arm_membership_setup . '`'), ARRAY_A );//phpcs:ignore --Reason: $tbl_arm_membership_setup is a table name. False Positive Alarm	
	if ( ! empty( $setup_data ) ) {
		$default_setup_style = array(
			'content_width'                  => '800',
			'plan_skin'                      => 'skin1',
			'hide_current_plans'             => 0,
			'plan_selection_area'            => 'before',
			'font_family'                    => 'Helvetica',
			'title_font_size'                => 20,
			'title_font_bold'                => 0,
			'title_font_italic'              => '',
			'title_font_decoration'          => '',
			'description_font_size'          => 16,
			'description_font_bold'          => 0,
			'description_font_italic'        => '',
			'description_font_decoration'    => '',
			'price_font_size'                => 30,
			'price_font_bold'                => 0,
			'price_font_italic'              => '',
			'price_font_decoration'          => '',
			'summary_font_size'              => 16,
			'summary_font_bold'              => 0,
			'summary_font_italic'            => '',
			'summary_font_decoration'        => '',
			'plan_title_font_color'          => '#616161',
			'plan_desc_font_color'           => '#616161',
			'price_font_color'               => '#616161',
			'summary_font_color'             => '#616161',
			'bg_active_color'                => '#23b7e5',
			'selected_plan_title_font_color' => '#23b7e5',
			'selected_plan_desc_font_color'  => '#616161',
			'selected_price_font_color'      => '#FFFFFF',
		);
		foreach ( $setup_data as $setup_data_key => $setup_data_value ) {
			$arm_setup_module = maybe_unserialize( $setup_data_value['arm_setup_modules'] );
			if ( is_array( $arm_setup_module ) && ! empty( $arm_setup_module ) ) {
				$arm_setup_module['style'] = $default_setup_style;
				$arm_setup_id              = $setup_data_value['arm_setup_id'];
				$db_data                   = array( 'arm_setup_modules' => maybe_serialize( $arm_setup_module ) );
				$field_update              = $wpdb->update( $ARMemberLite->tbl_arm_membership_setup, $db_data, array( 'arm_setup_id' => $arm_setup_id ) );
			}
		}
	}
}

if ( version_compare( $arm_lite_newdbversion, '3.4.9', '<' ) ) {

	global $wpdb, $ARMemberLite;
	$arm_members_table = $ARMemberLite->tbl_arm_members;

	$arm_add_arm_user_plan_ids_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_members_table,'arm_user_plan_ids') );
	if ( empty( $arm_add_arm_user_plan_ids_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_members_table}` ADD `arm_user_plan_ids` TEXT NULL AFTER `arm_secondary_status`") );//phpcs:ignore --Reason: $arm_members_table is a table name. False Positive Alarm
	}

	$arm_add_arm_user_suspended_plan_ids_col = $wpdb->get_results( $wpdb->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=%s AND TABLE_NAME = %s AND column_name = %s",DB_NAME,$arm_members_table,'arm_user_suspended_plan_ids') );
	if ( empty( $arm_add_arm_user_suspended_plan_ids_col ) ) {
		$wpdb->query( $wpdb->prepare("ALTER TABLE `{$arm_members_table}` ADD `arm_user_suspended_plan_ids` TEXT NULL AFTER `arm_user_plan_ids`") );//phpcs:ignore --Reason: $arm_members_table is a table name. False Positive Alarm
	}
}

if ( version_compare( $arm_lite_newdbversion, '4.0.11', '<' ) ) {
	update_option('arm_lite_is_wizard_complete',1);

	global $ARMemberLite, $wpdb;
    
    $armember_check_db_permission = $ARMemberLite->armember_check_db_permission();
    if(!empty($armember_check_db_permission))
    {
        $arm_members_table = $ARMemberLite->tbl_arm_members;
        $arm_tbl_arm_payment_log = $ARMemberLite->tbl_arm_payment_log;
        
        //Add the arm-user-id INDEX for the Members table
        $arm_members_add_index_arm_user_id = $wpdb->get_results($wpdb->prepare("SHOW INDEX FROM ".$arm_members_table." where Key_name=%s' ",'arm-user-id')); //phpcs:ignore --Reason: $arm_members_table is a table name
        if(empty($arm_members_add_index_arm_user_id))
        {
            $wpdb->query("ALTER TABLE `{$arm_members_table}` ADD INDEX `arm-user-id` (`arm_user_id`)"); //phpcs:ignore
        }

        //Add the arm-user-id INDEX for the Payment table
        $arm_payment_log_add_index_arm_user_id = $wpdb->get_results($wpdb->prepare("SHOW INDEX FROM ".$arm_tbl_arm_payment_log." where Key_name=%s ",'arm-user-id')); //phpcs:ignore --Reason: $arm_tbl_arm_payment_log is a table name
        if(empty($arm_payment_log_add_index_arm_user_id))
        {
            $wpdb->query("ALTER TABLE `{$arm_tbl_arm_payment_log}` ADD INDEX `arm-user-id` (`arm_user_id`)");//phpcs:ignore
        }

        //Add the arm-plan-id INDEX for the Payment table
        $arm_payment_log_add_index_arm_plan_id = $wpdb->get_results($wpdb->prepare("SHOW INDEX FROM ".$arm_tbl_arm_payment_log." where Key_name=%s ",'arm-plan-id')); //phpcs:ignore --Reason: $arm_tbl_arm_payment_log is a table name
        if(empty($arm_payment_log_add_index_arm_plan_id))
        {
            $wpdb->query("ALTER TABLE `{$arm_tbl_arm_payment_log}` ADD INDEX `arm-plan-id` (`arm_plan_id`)");//phpcs:ignore
        }

        //Add the arm-display-log INDEX for the Payment table
        $arm_payment_log_add_index_arm_display_log = $wpdb->get_results($wpdb->prepare("SHOW INDEX FROM ".$arm_tbl_arm_payment_log." where Key_name=%s ",'arm-display-log')); //phpcs:ignore --Reason: $arm_tbl_arm_payment_log is a table name
        if(empty($arm_payment_log_add_index_arm_display_log))
        {
            $wpdb->query("ALTER TABLE `{$arm_tbl_arm_payment_log}` ADD INDEX `arm-display-log` (`arm_display_log`)"); //phpcs:ignore
        }
    }
}
if (version_compare($arm_lite_newdbversion, '4.0.17', '<')) {

    //Add Capabilities to administrator users
    global $ARMemberLite, $wpdb;
    $cap_obj = $ARMemberLite->arm_slugs;

    $capabilities_field_name = $wpdb->prefix.'capabilities';
    $qargs = array(
            'meta_query' => array(
                    array(
                            'key' => $capabilities_field_name,
                            'value' => 'arm_manage_members',
                            'compare' => 'LIKE',
                        ),
                ),
        );

    $usersQuery = new WP_User_Query($qargs);
    $users = $usersQuery->get_results();

    if (count($users) > 0) {
        foreach ($users as $key => $user) {
            $userObj = new WP_User($user->ID);
            // Add Capabilities for Manage Subscriptions Page
            $subscription_cap = isset($cap_obj->arm_manage_subscriptions) ? $cap_obj->arm_manage_subscriptions : 'arm_manage_subscriptions';
            $userObj->add_cap($subscription_cap);            
        }
    }

	//Black friday update
	update_option('arm_lite_display_bf_offers', 1);
}

if (version_compare($arm_lite_newdbversion, '4.0.18', '<')) {

    //Add Capabilities to administrator users
    global $ARMemberLite, $wpdb;
    $cap_obj = $ARMemberLite->arm_slugs;

    $capabilities_field_name = $wpdb->prefix.'capabilities';
    $qargs = array(
            'meta_query' => array(
                    array(
                            'key' => $capabilities_field_name,
                            'value' => 'arm_manage_members',
                            'compare' => 'LIKE',
                        ),
                ),
        );

    $usersQuery = new WP_User_Query($qargs);
    $users = $usersQuery->get_results();

    if (count($users) > 0) {
        foreach ($users as $key => $user) {
            $userObj = new WP_User($user->ID);
            // Add Capabilities for Manage Subscriptions Page
            $arm_growth_plugins_cap = isset($cap_obj->arm_growth_plugins) ? $cap_obj->arm_growth_plugins : 'arm_growth_plugins';
            $userObj->add_cap($arm_growth_plugins_cap);            
        }
    }
}

if(version_compare($arm_lite_newdbversion,'4.0.26','<'))
{
    global $wp, $wpdb, $ARMemberLite;
    $arm_tbl_arm_forms = $ARMemberLite->tbl_arm_forms;
    $arm_tbl_arm_form_field = $ARMemberLite->tbl_arm_form_field;


    $current_user_pass_field_options = array(
        'id' => 'current_user_pass',
        'label' => esc_html__('Current Password', 'armember-membership'),
        'placeholder' => 'Current Password',
        'type' => 'current_user_pass',
        'options' => array('strength_meter' => 0, 'strong_password' => 0, 'minlength' => 0, 'maxlength' => '', 'special' => 0, 'numeric' => 0, 'uppercase' => 0, 'lowercase' => 0),
        'meta_key' => 'current_user_pass',
        'required' => 1,
        'blank_message' => esc_html__('Current password can not be left blank.', 'armember-membership'),
        'invalid_message' => esc_html__('Please enter valid current password.', 'armember-membership'),
        'default_field' => 1,
        'ref_field_id' => 0,
        'enable_repeat_field' => 0,
    );

    $arm_fetch_change_password_forms = $wpdb->prepare( "SELECT arm_form_id FROM $arm_tbl_arm_forms WHERE arm_form_slug LIKE %s",'%change-password%' );
    $arm_change_password_ids = $wpdb->get_results($arm_fetch_change_password_forms);//phpcs:ignore

    foreach($arm_change_password_ids as $arm_forms_data)
    {
        $arm_form_id = $arm_forms_data->arm_form_id;
	
		$arm_check_current_pass_field_exists = $wpdb->prepare("SELECT `arm_form_field_id` FROM $arm_tbl_arm_form_field WHERE arm_form_field_form_id=%d AND arm_form_field_slug=%s",$arm_form_id, 'current_user_pass');  //phpcs:ignore
		
		$arm_check_current_pass_field_exists_arr = $wpdb->get_row($arm_check_current_pass_field_exists);  //phpcs:ignore
		
		if(empty($arm_check_current_pass_field_exists_arr))
        {
	
			$get_form_fields_data = $wpdb->prepare("SELECT `arm_form_field_id`,`arm_form_field_slug` FROM $arm_tbl_arm_form_field WHERE arm_form_field_form_id=%d",$arm_form_id);//phpcs:ignore
			$arm_change_password_forms_field_ids = $wpdb->get_results($get_form_fields_data);//phpcs:ignore
			foreach($arm_change_password_forms_field_ids as $arm_change_password_forms_field_id)
			{
				$arm_form_field_id = $arm_change_password_forms_field_id->arm_form_field_id;
				$arm_form_field_slug = !empty($arm_change_password_forms_field_id->arm_form_field_slug) ?$arm_change_password_forms_field_id->arm_form_field_slug :'';
				$arm_form_field_order = 4;
				if(!empty($arm_form_field_slug) && $arm_form_field_slug =='user_pass')
				{
					$arm_form_field_order = 2;
				}
				else if(!empty($arm_form_field_slug) && $arm_form_field_slug =='repeat_pass')
				{
					$arm_form_field_order = 3;
				}

				if(!empty($arm_form_field_slug) && $arm_form_field_slug !='current_user_pass')
				{
					$wpdb->update($arm_tbl_arm_form_field,array('arm_form_field_order'=>$arm_form_field_order),array('arm_form_field_id'=>$arm_form_field_id));
				}

            }
			$arm_change_password_forms_field_id_arr = array(
				'arm_form_field_form_id' => $arm_form_id,
				'arm_form_field_order' => 1,
				'arm_form_field_slug' => $current_user_pass_field_options['meta_key'],
				'arm_form_field_created_date' => current_time('mysql'),
				'arm_form_field_option' => maybe_serialize($current_user_pass_field_options)
			);

			$wpdb->insert($arm_tbl_arm_form_field,$arm_change_password_forms_field_id_arr);
		}
	}
}

$arm_lite_newdbversion = '4.0.27';
update_option( 'arm_lite_new_version_installed', 1 );
update_option( 'armlite_version', $arm_lite_newdbversion );

$arm_lite_version_updated_date_key = 'arm_lite_version_updated_date_' . $arm_lite_newdbversion;
$arm_lite_version_updated_date     = current_time( 'mysql' );
update_option( $arm_lite_version_updated_date_key, $arm_lite_version_updated_date );
