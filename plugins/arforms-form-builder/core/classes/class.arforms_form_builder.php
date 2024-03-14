<?php

class arforms_form_builder{

    var $fields;
	var $forms;
	var $entries;
	var $entry_metas;
	var $autoresponder;
	var $ar;
	var $view;
	var $debug_log_setting;

    function __construct(){

        global $wpdb, $blog_id, $arflitedbversion, $tbl_arf_fields, $tbl_arf_forms, $tbl_arf_entries, $tbl_arf_entry_values, $tbl_arf_debug_log_setting, $tbl_arf_settings;
        
        $arforms_migration_flag = get_option('arforms_use_legacy_tables');

		if( !empty($arforms_migration_flag) ){

			if ( $blog_id && IS_WPMU ) {
				$prefix = $wpdb->get_blog_prefix( $blog_id );

				$this->fields      = "{$prefix}arflite_fields";
				$tbl_arf_fields = "{$prefix}arflite_fields";

				$this->forms       = "{$prefix}arflite_forms";
				$tbl_arf_forms = "{$prefix}arflite_forms";

				$this->entries     = "{$prefix}arflite_entries";
				$tbl_arf_entries = "{$prefix}arflite_entries";

				$this->entry_metas = "{$prefix}arflite_entry_values";
				$tbl_arf_entry_values = "{$prefix}arflite_entry_values";

				$this->debug_log_setting = "{$prefix}arf_debug_log_setting";
				$tbl_arf_debug_log_setting = "{$prefix}arf_debug_log_setting";
				
				$tbl_arf_settings = "{$prefix}arf_settings";

			} else {
				$this->fields      = $wpdb->prefix . 'arflite_fields';
				$tbl_arf_fields = $wpdb->prefix . 'arflite_fields';

				$this->forms       = $wpdb->prefix . 'arflite_forms';
				$tbl_arf_forms       = $wpdb->prefix . 'arflite_forms';

				$this->entries     = $wpdb->prefix . 'arflite_entries';
				$tbl_arf_entries     = $wpdb->prefix . 'arflite_entries';

				$this->entry_metas = $wpdb->prefix . 'arflite_entry_values';
				$tbl_arf_entry_values = $wpdb->prefix . 'arflite_entry_values';

				$this->debug_log_setting = $wpdb->prefix . 'arf_debug_log_setting';
				$tbl_arf_debug_log_setting = $wpdb->prefix . 'arf_debug_log_setting';

				$tbl_arf_settings = $wpdb->prefix . 'arf_settings';
			}

		} else {

			if ( $blog_id && IS_WPMU ) {
				$prefix = $wpdb->get_blog_prefix( $blog_id );

				$tbl_arf_fields       = "{$prefix}arf_fields";
				$tbl_arf_forms        = "{$prefix}arf_forms";
				$tbl_arf_entries     = "{$prefix}arf_entries";
				$tbl_arf_entry_values = "{$prefix}arf_entry_values";
				$tbl_arf_debug_log_setting = "{$prefix}arf_debug_log_setting";
				$tbl_arf_settings    = "{$prefix}arf_settings";

			} else {
				$tbl_arf_fields       = $wpdb->prefix . 'arf_fields';
				$tbl_arf_forms        = $wpdb->prefix . 'arf_forms';
				$tbl_arf_entries     = $wpdb->prefix . 'arf_entries';
				$tbl_arf_entry_values = $wpdb->prefix . 'arf_entry_values';
				$tbl_arf_debug_log_setting = $wpdb->prefix . 'arf_debug_log_setting';
				$tbl_arf_settings    = $wpdb->prefix . 'arf_settings';
			}

		}

		global $arfmigratecontroller;
		if( $this->arforms_is_pro_active() && !empty( $arfmigratecontroller ) ){
			remove_action( 'arf_migrate_lite_data', array( $arfmigratecontroller, 'arf_migrate_lite_data_callback' ) );
		}
		if( $this->arforms_is_pro_active() ){
			if( !class_exists('arfmigratecontroller') && file_exists( WP_PLUGIN_DIR.'/arforms/core/controllers/arfmigratecontroller.php' ) ){
				require_once WP_PLUGIN_DIR.'/arforms/core/controllers/arfmigratecontroller.php';
				$arfmigratecontroller = new arfmigratecontroller();
				add_filter( 'arf_modify_where_clause', array( $arfmigratecontroller, 'arf_add_lite_condition_in_clause'), 10, 2 );
				add_filter( 'arf_modify_where_placeholder', array( $arfmigratecontroller, 'arf_add_lite_condition_in_placeholder'), 10, 3 );
			}
		}

        register_activation_hook( ARFLITE_FORMPATH . '/arforms-form-builder.php', array( $this, 'arfliteinstall' ) );

		if( !$this->arforms_is_pro_active() ){
			add_action( 'admin_menu', array( $this, 'arforms_register_menu') );
		}

        add_action( 'admin_enqueue_scripts', array( $this, 'arforms_load_assets'), 12 );

		add_action( 'plugins_loaded', array( $this, 'arforms_remove_pro_actions') );
		
		register_deactivation_hook(ARFLITE_FORMPATH . '/arforms-form-builder.php', array( $this, 'deactivate_lite_version' ));

		add_action( 'arforms_quick_help_links', array( $this, 'arforms_render_quick_help_links') );

		add_action('wp_ajax_arforms_get_help_data', array( $this, 'arforms_get_help_data_func' ));

		add_action( 'arfafterinstall', array( $this, 'arforms_migrate_lite_to_pro' ));

    }

	function arforms_migrate_lite_to_pro( $force = false ){

		if( true == $force || version_compare( $this->arforms_get_premium_version(), '6.1', '<' ) ){
			global $wpdb, $tbl_arf_forms, $tbl_arf_entry_values, $tbl_arf_fields, $tbl_arf_entries, $tbl_arf_debug_log_setting, $MdlDb, $armainhelper;

			$all_lite_forms = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_forms . '` WHERE status = %s AND arf_is_lite_form = %d', 'published', 1 ) ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm 

			if( !empty( $all_lite_forms ) ){
				if( !isset($is_prefix_suffix_enable)){
				    $is_prefix_suffix_enable = false;
				}
				if( !isset($is_checkbox_img_enable)){
				    $is_checkbox_img_enable = false;
				}
				if( !isset($is_radio_img_enable)){
				    $is_radio_img_enable = false;
				}
				if( !isset($is_font_awesome) ){
					$is_font_awesome = false;
				}
				if( !isset($is_tooltip) ){
					$is_tooltip = 0;
				}
				if( !isset($is_input_mask) ){
					$is_input_mask = 0;
				}
				if( !isset($checkbox_img_field_arr) ){
					$checkbox_img_field_arr = array();
				}
				if( !isset($radio_img_field_arr) ){
					$radio_img_field_arr = array();
				}

				foreach( $all_lite_forms as $lite_frm ){
					$loaded_field = array();
					$lite_form_id = $lite_frm->id;

					$new_key = $lite_frm->form_key;

					$new_form_key = $armainhelper->get_unique_key( $new_key, $tbl_arf_forms, 'form_key' );

					$lite_form_name = $lite_frm->name;
					$lite_form_desc = $lite_frm->description;
					$lite_form_template = 0;
					$lite_form_status = 'published';
					$lite_form_opts = maybe_unserialize( $lite_frm->options );

					$lite_form_css = maybe_unserialize( $lite_frm->form_css );

					$lite_form_css['arfsectionpaddingsetting_1'] = 20;
					$lite_form_css['arfsectionpaddingsetting_2'] = 0;
					$lite_form_css['arfsectionpaddingsetting_3'] = 20;
					$lite_form_css['arfsectionpaddingsetting_4'] = 20;

					$lite_temp_fields = maybe_unserialize( $lite_frm->temp_fields );

					$wpdb->insert(
						$tbl_arf_forms,
						array(
							'form_key' => $new_form_key,
							'name' => $lite_form_name,
							'description' => $lite_form_desc,
							'is_template' => 0,
							'status' => $lite_form_status,
							'options' => maybe_serialize( $lite_form_opts ),
							'created_date' => current_time('mysql', 1),
							'form_css' => maybe_serialize( $lite_form_css ),
							'temp_fields' => maybe_serialize( $lite_temp_fields ),
							'arforms_is_migrated_form' => 1,
							'arf_lite_form_id' => $lite_form_id
						)
					);

					$migrated_form_id = $wpdb->insert_id;

					$form_id = $migrated_form_id;

					$new_values = $lite_form_css;

					$use_saved = true;

				    $arfssl = (is_ssl()) ? 1 : 0;

					$res1  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 3 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm 
					$res2  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 1 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res3  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 4 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res4  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 5 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res5  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 6 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res6  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 8 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res7  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 9 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res11 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 10 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res14 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 14 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res15 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 15 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res16 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 16 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res17 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 17 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm
					$res18 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $MdlDb->autoresponder . " WHERE responder_id = %d", 18 ), 'ARRAY_A' ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $MdlDb->autoresponder is table name defined globally. False Positive alarm

				    $aweber_arr['enable'] = '';
				    $aweber_arr['is_global'] = 1;
				    $aweber_arr['type'] = '';
				    $aweber_arr['type_val'] = '';

					$aweber = maybe_serialize($aweber_arr);

				    $mailchimp_arr['enable'] = '';
				    $mailchimp_arr['is_global'] = 1;
				    $mailchimp_arr['type'] = '';
				    $mailchimp_arr['type_val'] = '';

					$mailchimp = maybe_serialize($mailchimp_arr);
					
				    $madmimi_arr['enable'] ='';
				    $madmimi_arr['is_global'] = 1;
				    $madmimi_arr['type'] = '';

					$madmimi = maybe_serialize($madmimi_arr);

				    $getresponse_arr['enable'] = '';
				    $getresponse_arr['is_global'] = 1;
				    $getresponse_arr['type'] = '';
				    $getresponse_arr['type_val'] = '';

					$getresponse = maybe_serialize($getresponse_arr);

				    $gvo_arr['enable'] = '';
				    $gvo_arr['is_global'] = 1;
				    $gvo_arr['type'] = '';
				    $gvo_arr['type_val'] = '';

					$gvo = maybe_serialize($gvo_arr);

				    $ebizac_arr['enable'] ='';
				    $ebizac_arr['is_global'] = 1;
				    $ebizac_arr['type'] = '';
				    $ebizac_arr['type_val'] = '';

					$ebizac = maybe_serialize($ebizac_arr);

				    $icontact_arr['enable'] ='';
				    $icontact_arr['is_global'] = 1;
				    $icontact_arr['type'] = '';
				    $icontact_arr['type_val'] = '';

					$icontact = maybe_serialize($icontact_arr);

				    $constant_contact_arr['enable'] = '';
				    $constant_contact_arr['is_global'] = 1;
				    $constant_contact_arr['type'] ='';
				    $constant_contact_arr['type_val'] = '';

					$constant_contact = maybe_serialize($constant_contact_arr);

				    $mailerlite_arr['enable'] ='';
				    $mailerlite_arr['is_global'] = 1;
				    $mailerlite_arr['type'] ='';

					$mailerlite = maybe_serialize($mailerlite_arr);

				    $hubspot_arr['enable'] ='';
				    $hubspot_arr['is_global'] = 1;
				    $hubspot_arr['type'] ='';

					$hubspot = maybe_serialize($hubspot_arr);

					
				    $convertkit_arr['enable'] = '';
				    $convertkit_arr['is_global'] = 1;
				    $convertkit_arr['type'] ='';

					$convertkit = maybe_serialize($convertkit_arr);

				    $sendinblue_arr['enable'] = '';
				    $sendinblue_arr['is_global'] = 1;
				    $sendinblue_arr['type'] = '';

					$sendinblue = maybe_serialize($sendinblue_arr);

				    $drip_arr['enable'] = '';
				    $drip_arr['is_global'] = 1;
				    $drip_arr['type'] = '';

					$drip = maybe_serialize( $drip_arr );

					$frm_id = $migrated_form_id;

					$wpdb->insert(
						$MdlDb->ar,
						array(
							'aweber' 			=> $aweber,
							'mailchimp' 		=> $mailchimp,
							'getresponse' 		=> $getresponse,
							'gvo' 				=> $gvo,
							'ebizac' 			=> $ebizac,
							'madmimi' 			=> $madmimi,
							'icontact' 			=> $icontact,
							'constant_contact' 	=> $constant_contact,
							'enable_ar' 		=> maybe_serialize( array() ),
							'frm_id' 			=> $frm_id
						)
					);

					$all_list_fields = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . $tbl_arf_fields . "` WHERE form_id = %d", $lite_form_id ) ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_fields is table name defined globally. False Positive alarm

					$old_fields_order = arf_json_decode( $lite_form_opts['arf_field_order'], true );

					$arf_field_id_update = array();

					if( !empty( $all_list_fields ) ){
						foreach( $all_list_fields as $field_data ){
							$lite_field_id = $field_data->id;

							$lite_field_key = $field_data->field_key;

							$new_field_key = $armainhelper->get_unique_key( $lite_field_key, $MdlDb->fields, 'field_key' );

							$lite_field_name = $field_data->name;
							$lite_field_type = $field_data->type;
							$loaded_field[] = $lite_field_type;
							$lite_field_opts = $field_data->options;
							$lite_field_required = $field_data->required;
							$lite_field_fopts = $field_data->field_options;
							
							$lite_field_created_date = current_time( 'mysql', 1 );
							$lite_field_opt_order = $field_data->option_order;

							if ((isset($lite_field_opts['enable_arf_prefix']) && $lite_field_opts['enable_arf_prefix'] == 1) || (isset($lite_field_opts['enable_arf_suffix']) && $lite_field_opts['enable_arf_suffix'] == 1)) {
								$is_font_awesome = 1;
								$is_prefix_suffix_enable = true;
							}
		
							if (isset($lite_field_opts['tooltip_text']) && $lite_field_opts['tooltip_text'] != '') {
								$is_tooltip = 1;
							}
		
							if($lite_field_type == 'checkbox' && (isset($lite_field_opts['use_image']) && $lite_field_opts['use_image'] == 1)) {
								$is_font_awesome = 1;
								$is_checkbox_img_enable = true;
								$checkbox_img_field_arr[] = $field_data;
							}
		
							if($lite_field_type == 'radio' && (isset($lite_field_opts['use_image']) && $lite_field_opts['use_image'] == 1)) {
								$is_font_awesome = 1;
								$is_radio_img_enable = true;
								$radio_img_field_arr[] = $field_data;
							}
		
							if ($lite_field_type == 'phone' && ( isset($lite_field_opts['phone_validation']) && $lite_field_opts['phone_validation'] != 'international' )) {
								$is_input_mask = 1;
							}
		
							if( $lite_field_type == 'phone' && ( isset($lite_field_opts['phonetype']) && $lite_field_opts['phonetype'] == 1 ) ){
								$is_input_mask = 1;
							}

							$wpdb->insert(
								$MdlDb->fields,
								array(
									'field_key' => $new_field_key,
									'name' => $lite_field_name,
									'type' => $lite_field_type,
									'options' => $lite_field_opts,
									'required' => $lite_field_required,
									'field_options' => $lite_field_fopts,
									'form_id' => $migrated_form_id,
									'created_date' => $lite_field_created_date,
									'option_order' => $lite_field_opt_order
								)
							);

							$new_field_id = $wpdb->insert_id;

							$arf_field_id_update[ $lite_field_id ] = $new_field_id;
						}
					}

					$updated_field_order = array();
					if( !empty( $arf_field_id_update ) && !empty( $old_fields_order ) ){

						foreach( $old_fields_order as $old_field_id => $field_ord ){

							foreach( $arf_field_id_update as $old_fid => $new_fid ){
								if( $old_field_id == $old_fid ){
									$updated_field_order[ $new_fid ] = $field_ord;
								}
							}
						}
					}

					if( !empty( $updated_field_order ) ){

						$lite_form_opts['arf_field_order'] = json_encode( $updated_field_order );

						$wpdb->update(
							$MdlDb->forms,
							array(
								'options' => maybe_serialize( $lite_form_opts )
							),
							array(
								'id' => $migrated_form_id
							)
						);
					}
					$css_common_filename = FORMPATH . '/core/css_create_common.php';
						$css_rtl_filename = FORMPATH . '/core/css_create_rtl.php';
					if( 'standard' == $lite_form_css['arfinputstyle'] || 'rounded' == $lite_form_css['arfinputstyle'] ){
						$filename = FORMPATH . '/core/css_create_main.php';

					    $wp_upload_dir = wp_upload_dir();
						
					    
						$target_path = $wp_upload_dir['basedir'] . '/arforms/maincss';

					    $css = $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";

					    $css .= "\n";

					    ob_start();

					    include $filename;
						include $css_common_filename;
						if( is_rtl() ){
							include $css_rtl_filename;
						}

					    $css .= ob_get_contents();

					    ob_end_clean();

					    $css .= "\n " . $warn;

					    $css_file = $target_path . '/maincss_' . $migrated_form_id . '.css';

					    WP_Filesystem();
					    global $wp_filesystem;
					    $css = str_replace('##', '#', $css);
					    $wp_filesystem->put_contents($css_file, $css, 0777);
					    wp_cache_delete($migrated_form_id, 'arfform');
					}

					if( 'material' == $lite_form_css['arfinputstyle'] ){
						$filename1 = FORMPATH . '/core/css_create_materialize.php';
					    $css1 = $warn1 = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";
					    $css1 .= "\n";
					    ob_start();
					    include $filename1;
						include $css_common_filename;
						if( is_rtl() ){
							include $css_rtl_filename;
						}
					    $css1 .= ob_get_contents();
					    ob_end_clean();
					    $css1 .= "\n " . $warn1;
					    $css_file1 = $target_path . '/maincss_materialize_' . $migrated_form_id . '.css';
					    WP_Filesystem();
					    global $wp_filesystem;
					    $css1 = str_replace('##', '#', $css1);
					    $wp_filesystem->put_contents($css_file1, $css1, 0777);
					    wp_cache_delete($migrated_form_id, 'arfform');
					}

					$all_form_entries = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `" . $tbl_arf_entries . "` WHERE form_id = %d", $lite_form_id ) ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_entries is table name defined globally. False Positive alarm

					$updated_entry_ids = array();
					if( !empty( $all_form_entries ) ){
						foreach( $all_form_entries as $form_entry ){
							$entry_id = $form_entry->id;
							$entry_key = $form_entry->entry_key;
							$new_entry_key = $armainhelper->get_unique_key( $entry_key, $MdlDb->entries, 'entry_key' );
							$entry_name = $form_entry->name;
							$entry_description = $form_entry->description;
							$entry_ip = $form_entry->ip_address;
							$entry_country  = $form_entry->country;
							$entry_browser_info = $form_entry->browser_info;
							$entry_form_id = $migrated_form_id;
							$entry_attachment_id = $form_entry->attachment_id;
							$entry_user_id = $form_entry->user_id;
							$entry_created_date = current_time( 'mysql', 1 );

							$wpdb->insert(
								$MdlDb->entries,
								array(
									'entry_key' => $new_entry_key,
									'name' => $entry_name,
									'description' => $entry_description,
									'ip_address' => $entry_ip,
									'country' => $entry_country,
									'browser_info' => $entry_browser_info,
									'form_id' => $entry_form_id,
									'attachment_id' => $entry_attachment_id,
									'user_id' => $entry_user_id,
									'created_date' => $entry_created_date,
								)
							);

							$new_entry_id = $wpdb->insert_id;

							$updated_entry_ids[ $entry_id ] = $new_entry_id;
						}
					}

					if( !empty( $updated_entry_ids ) ){
						foreach( $updated_entry_ids as $old_entry_id => $new_entry_id ){
							$entry_metas = $wpdb->get_results( $wpdb->prepare( "SELECT * from `" . $tbl_arf_entry_values . "` WHERE entry_id = %d", $old_entry_id ) ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_entry_values is table name defined globally. False Positive alarm

							if( !empty( $entry_metas ) ){
								foreach( $entry_metas as $entry_obj ){
									$entry_value = $entry_obj->entry_value;
									$entry_id = $entry_obj->entry_id;
									$entry_field_id = $entry_obj->field_id;

									if( $entry_field_id == 0 ){
										$new_entry_field = 0;
									} else {
										if( preg_match( '/\-[\d]+/', $entry_field_id ) ){
											$new_entry_field = '-'.$arf_field_id_update[ abs( $entry_field_id ) ];
										} else {
											$new_entry_field = $arf_field_id_update[ $entry_field_id ];
										}
									}

									$wpdb->insert(
										$MdlDb->entry_metas,
										array(
											'entry_value' => $entry_value,
											'field_id' => $new_entry_field,
											'entry_id' => $new_entry_id
										)
									);
								}
							}
						}
					}

				}
			}
			
		}

	}

	function arforms_get_help_data_func($param)	
	{
		global $wpdb,$ARFLiteMdlDb;

		if ( !isset( $_POST['_wpnonce_arflite'] ) || ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) ) {
			echo esc_attr( 'security_error' );
			die;
		}
		
		if ( !empty($_POST['action']) && $_POST['action'] == 'arforms_get_help_data' && !empty($_POST['arf_help_page']) ) {
			
			$help_page = sanitize_text_field( $_POST['arf_help_page'] );

			$arforms_remote_url = 'https://www.arformsplugin.com/';
			$arf_get_data_params = array(
				'method' => 'POST',
				'body' => array(
					'action' => 'get_documentation',
					'arf_page' => $help_page,
				),
				'timeout' => 45,
			);
			$arf_doc_res = wp_remote_post( $arforms_remote_url, $arf_get_data_params );
				
			if(!is_wp_error($arf_doc_res)){
				$arf_doc_content = ! empty( $arf_doc_res['body'] ) ? $arf_doc_res['body'] : esc_html__('No data found', 'arforms-form-builder');
			} else{
				$arf_doc_content = $arf_doc_res->get_error_message();
			}
			echo $arf_doc_content; //phpcs:ignore
			exit; 
		}
	}

	function arforms_render_quick_help_links( $page = '' ){
		global $arforms_general_settings;
		echo wp_nonce_field( 'arflite_wp_nonce', 'arflite_wp_nonce', 1, false ); //phpcs:ignore
		?>
			<div class="arf_help" onclick="return Show_HelpIcon();">
	
				<svg width="70" height="70" viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" class="arf_main_help_icon">
					<circle cx="35" cy="35" r="35" fill="#497FF9"/>
					<path d="M21.5695 48.433C22.0847 48.9482 22.6132 49.4235 23.1727 49.865C23.2915 49.9587 23.464 49.9471 23.5711 49.8401L29.9485 43.4628C30.0822 43.329 30.055 43.1129 29.8955 43.011C29.3236 42.6458 28.7838 42.2143 28.286 41.7166C27.8042 41.2348 27.3881 40.711 27.0278 40.1657C26.9244 40.0093 26.7098 39.985 26.5772 40.1176L20.2078 46.4869C20.0993 46.5954 20.0884 46.771 20.1845 46.8906C20.6146 47.4255 21.0724 47.9359 21.5695 48.433Z" fill="white"/>
					<path d="M44.2455 51.6012C44.4185 51.5046 44.4503 51.2635 44.3102 51.1235L37.4926 44.3059C37.4216 44.2349 37.3198 44.2075 37.2221 44.2307C35.7445 44.582 34.2126 44.5819 32.7423 44.2234C32.6442 44.1995 32.5418 44.2268 32.4704 44.2982L25.6613 51.1072C25.5214 51.2471 25.5529 51.4879 25.7256 51.5847C31.4611 54.7972 38.5022 54.8052 44.2455 51.6012Z" fill="white"/>
					<path d="M46.8072 49.8891C47.3752 49.4396 47.912 48.9561 48.4355 48.4325C48.9243 47.9437 49.3739 47.4418 49.7959 46.9153C49.8917 46.7957 49.8806 46.6203 49.7723 46.5119L43.4052 40.1449C43.2718 40.0115 43.0566 40.0382 42.954 40.1965C42.6049 40.7353 42.1916 41.2434 41.7189 41.7162C41.2133 42.2218 40.6652 42.661 40.0801 43.0286C39.9191 43.1298 39.8904 43.3466 40.0248 43.481L46.4084 49.8645C46.5156 49.9717 46.6883 49.9831 46.8072 49.8891Z" fill="white"/>
					<path d="M18.4499 44.3442C18.5469 44.5162 18.7872 44.5474 18.9269 44.4077L25.7274 37.6072C25.7994 37.5353 25.8265 37.4321 25.8017 37.3334C25.4272 35.8453 25.4122 34.2815 25.7706 32.7788C25.7939 32.6812 25.7665 32.5795 25.6956 32.5085L18.8777 25.6907C18.7377 25.5507 18.4966 25.5823 18.4 25.7552C15.1798 31.5164 15.2038 38.591 18.4499 44.3442Z" fill="white"/>
					<path d="M32.6257 25.8058C34.1714 25.4088 35.7995 25.4085 37.3379 25.7982C37.4365 25.8232 37.5396 25.7961 37.6115 25.7242L44.412 18.9238C44.5517 18.7841 44.5206 18.5437 44.3485 18.4466C38.5519 15.1763 31.4104 15.1845 25.6295 18.4713C25.4581 18.5688 25.4274 18.8086 25.5669 18.948L32.3511 25.7321C32.4232 25.8043 32.5269 25.8312 32.6257 25.8058Z" fill="white"/>
					<path d="M43.0136 29.8927C43.1155 30.0523 43.3317 30.0795 43.4654 29.9457L49.8428 23.5684C49.9499 23.4614 49.9615 23.2888 49.8677 23.17C49.0454 22.1277 48.0074 21.0775 46.8933 20.1818C46.7737 20.0857 46.5981 20.0967 46.4896 20.2051L40.1202 26.5745C39.9876 26.7071 40.0119 26.9217 40.1684 27.0251C41.316 27.7833 42.2704 28.7288 43.0136 29.8927Z" fill="white"/>
					<path d="M51.5885 25.7226C51.4918 25.5499 51.2509 25.5183 51.111 25.6583L44.302 32.4672C44.2306 32.5386 44.2035 32.6409 44.2275 32.7389C44.6011 34.2594 44.586 35.8555 44.1961 37.3761C44.1707 37.4751 44.1977 37.5789 44.2699 37.6512L51.0538 44.435C51.1933 44.5744 51.4332 44.5437 51.5306 44.3721C54.8011 38.6091 54.8255 31.5013 51.5885 25.7226Z" fill="white"/>
					<path d="M23.0869 20.207C21.9807 21.0935 20.9396 22.1512 20.1131 23.1956C20.0191 23.3145 20.0304 23.4873 20.1376 23.5944L26.5212 29.978C26.6557 30.1124 26.8725 30.0837 26.9736 29.9228C27.6884 28.7852 28.6936 27.7695 29.8057 27.0488C29.9641 26.9461 29.9908 26.7311 29.8574 26.5976L23.4903 20.2306C23.382 20.1223 23.2065 20.1111 23.0869 20.207Z" fill="white"/>
				</svg>

				<div class="arf_help_icon" style="display:none"; >
					<div class="arf_help_icon_display">
						<?php
						if( 'cross_selling_page' != $page ){
							$requested_page = !empty( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
						?>
							<svg width="52" height="52" viewBox="0 0 52 52" fill="none"  xmlns="http://www.w3.org/2000/svg" onclick="arf_help_doc_fun('<?php echo esc_attr($requested_page); ?>')" class="arf_help_icon_data arfhelptip" data-param="<?php echo esc_attr( $requested_page ); ?>" title="<?php echo  esc_attr__('Need Help ?', 'arforms-form-builder') ?>"> 
								<path d="M24.9129 12C25.6432 12 26.3735 12 27.0964 12C27.111 12.0584 27.1621 12.0511 27.2059 12.0511C27.7171 12.0877 28.221 12.1607 28.7176 12.263C34.5523 13.4319 38.9849 18.0927 39.8539 23.9517C39.8978 24.2658 39.8832 24.5945 40 24.9014C40 25.6465 40 26.399 40 27.1441C39.9124 27.1734 39.9489 27.2537 39.9416 27.3049C39.8905 27.8235 39.8174 28.3349 39.7079 28.8463C38.43 34.866 33.4861 39.3296 27.3958 39.9286C24.1681 40.2428 21.1667 39.5195 18.421 37.7662C13.5648 34.6541 11.1112 28.7879 12.2942 23.1554C13.5064 17.3914 18.1143 13.0447 23.9563 12.1534C24.2849 12.1023 24.6135 12.1242 24.9129 12ZM24.577 27.1295C24.577 27.2683 24.5697 27.3998 24.577 27.5386C24.6135 28.1596 24.8472 28.3934 25.4679 28.4445C25.6797 28.4591 25.8842 28.4372 26.0887 28.3861C26.4757 28.2838 26.6437 28.0573 26.6583 27.6555C26.6656 27.4802 26.6583 27.3122 26.6656 27.1368C26.6875 26.5816 26.8919 26.1141 27.3009 25.7342C27.4615 25.5881 27.6295 25.442 27.7975 25.3105C28.4182 24.8064 28.9659 24.2439 29.3456 23.5353C30.1781 21.9792 29.7034 20.3063 28.1407 19.5173C26.6802 18.7867 25.1612 18.7648 23.6715 19.4661C22.9924 19.7876 22.4812 20.299 22.2621 21.0514C22.0869 21.6432 22.2986 22.1618 22.8025 22.3883C23.3429 22.6367 23.708 22.5125 24.0951 21.9573C24.7012 21.0879 25.8185 20.8103 26.7751 21.2852C27.3009 21.5482 27.5346 22.0523 27.4031 22.6294C27.3228 22.9874 27.1183 23.2796 26.8627 23.528C26.6144 23.7836 26.3296 23.9955 26.0448 24.2074C25.059 24.9306 24.5551 25.9022 24.577 27.1295ZM27.0088 31.1475C27.0161 30.3731 26.3881 29.7303 25.6213 29.7157C24.8472 29.7083 24.2119 30.3293 24.1973 31.1037C24.19 31.9 24.818 32.5502 25.5921 32.5502C26.3589 32.5575 27.0015 31.9146 27.0088 31.1475Z" fill="white"/>
							</svg>
						<?php } ?>

						<?php $arforms_general_settings->arforms_render_pro_settings( 'support_help_icon' ); ?>
							
						<a href="https://www.facebook.com/groups/arplugins" target="_blank" class="arforms_help_link" >
							<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" title="<?php echo  esc_attr__('Facebook Community', 'arforms-form-builder') ?>"  class="arf_help_icon_data arfhelptip">
								<path d="M34.5741 31.3248C34.3082 29.5176 33.4033 27.8656 32.0236 26.6685C30.6439 25.4713 28.8808 24.8084 27.0541 24.8H25.3517C23.5251 24.8084 21.762 25.4713 20.3823 26.6685C19.0026 27.8656 18.0977 29.5176 17.8317 31.3248L17.0157 37.0304C16.9897 37.2148 17.0069 37.4028 17.066 37.5794C17.1252 37.756 17.2246 37.9164 17.3565 38.048C17.6765 38.368 19.6397 40 26.2045 40C32.7693 40 34.7277 38.3744 35.0525 38.048C35.1844 37.9164 35.2839 37.756 35.343 37.5794C35.4022 37.4028 35.4194 37.2148 35.3933 37.0304L34.5741 31.3248ZM19.0973 25.68C17.551 27.1055 16.5448 29.0216 16.2493 31.104L15.6573 35.2C10.9053 35.168 9.46533 33.44 9.22533 33.088C9.13256 32.9601 9.06635 32.8149 9.03063 32.661C8.9949 32.5071 8.99038 32.3476 9.01733 32.192L9.36933 30.208C9.55272 29.1711 9.98344 28.1937 10.625 27.3587C11.2665 26.5237 12.0999 25.8557 13.0545 25.4114C14.0092 24.9671 15.0569 24.7595 16.1088 24.8062C17.1607 24.853 18.1859 25.1527 19.0973 25.68ZM43.3853 32.192C43.4123 32.3476 43.4078 32.5071 43.372 32.661C43.3363 32.8149 43.2701 32.9601 43.1773 33.088C42.9373 33.44 41.4973 35.168 36.7453 35.2L36.1533 31.104C35.8578 29.0216 34.8517 27.1055 33.3053 25.68C34.2168 25.1527 35.2419 24.853 36.2939 24.8062C37.3458 24.7595 38.3935 24.9671 39.3481 25.4114C40.3028 25.8557 41.1362 26.5237 41.7777 27.3587C42.4192 28.1937 42.8499 29.1711 43.0333 30.208L43.3853 32.192ZM19.3693 22.16C18.9666 22.7311 18.4319 23.1967 17.8107 23.517C17.1895 23.8374 16.5002 24.003 15.8013 24C15.1041 24 14.4169 23.8343 13.7963 23.5166C13.1757 23.1989 12.6395 22.7383 12.2319 22.1727C11.8242 21.6071 11.5568 20.9527 11.4516 20.2635C11.3465 19.5743 11.4067 18.87 11.6271 18.2086C11.8476 17.5471 12.2221 16.9476 12.7197 16.4593C13.2174 15.971 13.8239 15.608 14.4894 15.4001C15.1549 15.1922 15.8602 15.1454 16.5473 15.2637C17.2344 15.3819 17.8836 15.6616 18.4413 16.08C18.2809 16.7073 18.2003 17.3524 18.2013 18C18.2025 19.4674 18.6066 20.9063 19.3693 22.16ZM41.0013 19.6C41.0017 20.1779 40.8882 20.7502 40.6673 21.2843C40.4463 21.8183 40.1222 22.3035 39.7135 22.7122C39.3049 23.1208 38.8197 23.4449 38.2856 23.6659C37.7516 23.8869 37.1793 24.0004 36.6013 24C35.9024 24.003 35.2131 23.8374 34.5919 23.517C33.9708 23.1967 33.4361 22.7311 33.0333 22.16C33.7961 20.9063 34.2001 19.4674 34.2013 18C34.2024 17.3524 34.1218 16.7073 33.9613 16.08C34.615 15.5897 35.3924 15.2911 36.2062 15.2177C37.02 15.1443 37.8382 15.299 38.5691 15.6645C39.2999 16.0299 39.9146 16.5916 40.3442 17.2867C40.7738 17.9818 41.0013 18.7828 41.0013 19.6Z" fill="white"/>
								<path d="M26.2013 24C29.515 24 32.2013 21.3137 32.2013 18C32.2013 14.6863 29.515 12 26.2013 12C22.8876 12 20.2013 14.6863 20.2013 18C20.2013 21.3137 22.8876 24 26.2013 24Z" fill="white"/>
							</svg>
						</a>

						<a href="https://www.youtube.com/@arforms" target="_blank" class="arforms_help_link" >
							<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" title="<?php echo  esc_attr__('YouTube Channel', 'arforms-form-builder') ?>"  class="arf_help_icon_data arfhelptip">
								<path d="M40.4606 24.814C40.3578 24.6599 40.4178 24.4886 40.4178 24.3173C40.4349 24.3173 40.452 24.3173 40.4606 24.3173C40.4606 24.4886 40.4606 24.6513 40.4606 24.814Z" fill="#F5B11D"/>
								<path d="M40.4606 24.3258C40.4435 24.3258 40.4264 24.3258 40.4178 24.3258C40.4178 24.2659 40.375 24.1973 40.4606 24.1631C40.4606 24.2145 40.4606 24.2659 40.4606 24.3258Z" fill="#F5B11D"/>
								<path d="M40 24.703C40 25.8508 40 26.9985 40 28.1463C39.9229 28.172 39.9486 28.2319 39.9486 28.2833C39.8972 29.688 39.7687 31.0842 39.6231 32.4803C39.4947 33.7737 38.287 35.0071 37.0107 35.1527C34.7409 35.4011 32.4626 35.6152 30.1757 35.718C27.0237 35.8636 23.8716 35.8465 20.7196 35.6666C18.8353 35.5553 16.9595 35.3668 15.0837 35.1613C13.7561 35.0156 12.5398 33.8765 12.3856 32.5488C12.1801 30.8101 12.0602 29.0628 12.0173 27.3154C11.9488 24.6345 12.0859 21.9536 12.3856 19.2898C12.5313 18.005 13.7389 16.7973 15.0066 16.6602C17.285 16.4118 19.5548 16.1977 21.8417 16.0949C24.9766 15.9493 28.1114 15.975 31.2463 16.1463C33.1478 16.2491 35.0407 16.4461 36.9336 16.6516C38.2613 16.7973 39.4861 17.9793 39.6231 19.3069C39.7687 20.7287 39.8972 22.1591 39.9486 23.5895C39.9486 23.6409 39.9229 23.7009 40 23.7266C40 23.8379 40 23.9493 40 24.0521C39.9058 24.0863 39.9572 24.1548 39.9572 24.2148C39.9572 24.3775 39.9058 24.5489 40 24.703ZM30.6639 25.9107C28.3084 24.3433 25.9958 22.8015 23.6661 21.2426C23.6661 24.369 23.6661 27.4525 23.6661 30.5703C26.013 29.0114 28.317 27.4782 30.6639 25.9107Z" fill="white"/>
							</svg>
						</a>
					
						<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" class="arf-close-svg arf_help_icon_data arfhelptip" title="<?php echo  esc_attr__('Close', 'arforms-form-builder') ?>" >
							<path d="M40.4606 24.814C40.3578 24.6599 40.4178 24.4886 40.4178 24.3173C40.4349 24.3173 40.452 24.3173 40.4606 24.3173C40.4606 24.4886 40.4606 24.6513 40.4606 24.814Z" fill="#F5B11D"/>
							<path d="M40.4606 24.3258C40.4435 24.3258 40.4264 24.3258 40.4178 24.3258C40.4178 24.2659 40.375 24.1973 40.4606 24.1631C40.4606 24.2145 40.4606 24.2659 40.4606 24.3258Z" fill="#F5B11D"/>
							<path d="M17.8776 34.9777C17.8392 34.923 17.779 34.9284 17.7242 34.9065C16.9902 34.6053 16.7602 33.6687 17.286 33.0662C17.3353 33.0114 17.3901 32.9566 17.4448 32.9018C19.7015 30.6452 21.9582 28.3885 24.2204 26.1319C24.3354 26.0169 24.33 25.9676 24.2204 25.858C21.9418 23.5904 19.6687 21.3119 17.401 19.0388C16.8752 18.513 16.8752 17.7626 17.3955 17.297C17.8337 16.9026 18.4856 16.8972 18.9347 17.2806C19.0059 17.3408 19.0716 17.412 19.1374 17.4778C21.3776 19.718 23.6179 21.9582 25.8527 24.2039C25.9842 24.3353 26.0389 24.3134 26.1594 24.1984C28.4271 21.9253 30.6947 19.6577 32.9624 17.3901C33.4554 16.8972 34.151 16.8753 34.633 17.3189C34.8192 17.4887 34.9014 17.7133 35 17.9379C35 18.1022 35 18.2665 35 18.4308C34.8247 18.9183 34.4249 19.225 34.0798 19.5701C31.9819 21.6734 29.8786 23.7767 27.7698 25.8745C27.6657 25.9785 27.6712 26.0223 27.7753 26.1264C29.9662 28.3064 32.1463 30.4973 34.3318 32.6773C34.6056 32.9511 34.8905 33.214 35 33.6084C35 33.7618 35 33.9151 35 34.063C34.9069 34.2438 34.8466 34.441 34.7042 34.5998C34.5399 34.7805 34.3318 34.8791 34.1181 34.9777C33.9209 34.9777 33.7183 34.9777 33.5211 34.9777C33.0665 34.7915 32.7707 34.4136 32.442 34.0849C30.3387 31.9816 28.2354 29.8838 26.1375 27.7751C26.028 27.6655 25.9787 27.6655 25.8691 27.7751C23.6672 29.9824 21.4598 32.1898 19.2524 34.3971C19.0114 34.6381 18.7759 34.8737 18.4472 34.9777C18.2555 34.9777 18.0638 34.9777 17.8776 34.9777Z" fill="white"/>
						</svg>
					</div>
				</div>
			</div>
				
			<div class="arf_sidebar_drawer_main_wrapper" >
				<div class="arf_sidebar_drawer_inner_wrapper" >
					<div class="arf_sidebar_drawer_content"  >
						<div class="arf_sidebar_drawer_close_container">
							<div class="arf_sidebar_drawer_close_btn"></div>
						</div>
						<div class="arf_sidebar_drawer_body">
							<div class="arf_sidebar_content_wrapper" >					
								<div class="arf_sidebar_content_header">
									<h1 class="arf_sidebar_content_heading"></h1>	
									<a href="https://www.arformsplugin.com/documentation/" target="_blank"  class="arf_readmore_link"><span class="arf_readmore">Read More</span></a>
								</div>
								<div class="arf_sidebar_content_body"></div>
							</div>
							<div id="arfviewformloader"><?php echo ARFLITE_LOADER_ICON; //phpcs:ignore ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php
	}
    
	/**
	 * Deactivate pro version when lite deactivate
	 *
	 * @return void
	 */
	public static function deactivate_lite_version()
	{
		$dependent = 'arforms/arforms.php';
		if (is_plugin_active($dependent) ) {
			add_action('update_option_active_plugins', array( 'arforms_form_builder', 'deactivate_pro_version' ));
		}
	}
	
	/**
         * Deactivate pro version when lite version deactivate
         *
         * @return void
         */
        public static function deactivate_pro_version()
        {
            $dependent = 'arforms/arforms.php';
            deactivate_plugins($dependent);
        }

	function arforms_remove_pro_actions(){
		if( !$this->arforms_is_pro_active() ){
			return;
		}

		global $maincontroller;
		if( !empty( $maincontroller ) ){
			remove_action( 'enqueue_block_editor_assets', array( $maincontroller, 'arf_enqueue_gutenberg_assets' ) );
		}
	}


    function arforms_load_assets(){

		if ( !empty( $_GET['page'] ) && preg_match( '/(ARForms*)/', sanitize_text_field($_GET['page']) ) ){
			
			wp_register_script( 'datatables', ARFLITEURL . '/datatables/media/js/datatables.js', array(), $this->arforms_get_assets_version() );
			wp_register_script( 'buttons-colvis', ARFLITEURL . '/datatables/media/js/buttons.colVis.js', array(), $this->arforms_get_assets_version() );

			wp_register_style( 'datatables', ARFLITEURL . '/datatables/media/css/datatables.css', array(), $this->arforms_get_assets_version() );

			wp_register_script( 'arforms-admin-common', ARFLITEURL . '/js/arforms_admin_common.js', array( 'jquery', 'wp-hooks' ), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arforms-admin-common' );
	
			wp_register_style( 'arforms-admin-common', ARFLITEURL . '/css/arforms_admin_common.css', array(), $this->arforms_get_assets_version() );
			wp_enqueue_style( 'arforms-admin-common' );
	
			wp_register_script( 'jquery-json', ARFLITEURL . '/js/jquery.json-2.4.js', array('jquery'), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'jquery-json' );
		}

		if( !empty( $_GET['page'] ) && 'ARForms-status' == $_GET['page'] ){

			wp_enqueue_script( 'datatables' );
			wp_enqueue_script( 'buttons-colvis' );
			wp_enqueue_style( 'datatables' );

			wp_register_script('tipso', ARFLITEURL . '/js/tipso.min.js', array('jquery'), $this->arforms_get_assets_version() );
            wp_enqueue_script('tipso');

			wp_register_style('tipso', ARFLITEURL . '/css/tipso.min.css', array(), $this->arforms_get_assets_version() );
            wp_enqueue_style('tipso');
		}


		
		if( !empty( $_GET['page'] ) && 'ARForms' == $_GET['page'] && !empty( $_GET['arfaction'] ) && ! $this->arforms_is_pro_active() ){
			
			wp_register_script( 'sortable', ARFLITEURL . '/js/sortable.min.js', array(), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'sortable' );

			wp_register_script( 'arforms-sortable', ARFLITEURL . '/js/arforms-sortable.js', array(), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arforms-sortable' );
		}
        
		if( !empty( $_GET['page'] ) && 'ARForms-settings' == $_GET['page'] ){
			
			wp_register_script( 'arflitedatatables', ARFLITEURL . '/datatables/media/js/datatables.js', array(), $this->arforms_get_assets_version() );
			wp_enqueue_script('arflitedatatables');
			wp_enqueue_script('buttons-colvis');
			wp_enqueue_style('datatables');
 
			wp_register_script('tipso', ARFLITEURL . '/js/tipso.min.js', array('jquery'), $this->arforms_get_assets_version() );
            wp_enqueue_script('tipso');

			wp_register_style('tipso', ARFLITEURL . '/css/tipso.min.css', array(), $this->arforms_get_assets_version() );
            wp_enqueue_script('tipso');

			wp_register_script( 'arforms-settings', ARFLITEURL . '/js/arforms_settings.js', array( 'jquery', 'wp-hooks' ), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arforms-settings' );

			wp_register_script('arforms_debug_log', ARFLITEURL . '/js/arforms_debug_log.js', array(), $this->arforms_get_assets_version());
        	wp_enqueue_script('arforms_debug_log');

			wp_register_script( 'bootstrap-moment-with-locales', ARFLITEURL . '/bootstrap/js/moment-with-locales.js', array(), $this->arforms_get_assets_version() );
			wp_register_script( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/js/bootstrap-datetimepicker.js', array(),$this->arforms_get_assets_version() );
			wp_register_style( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css', array(), $this->arforms_get_assets_version() );

			wp_enqueue_script( 'bootstrap-datetimepicker' );
			wp_enqueue_script( 'bootstrap-moment-with-locales' );
			wp_enqueue_style( 'bootstrap-datetimepicker' );
			
			
			$arforms_settings_i18n_data = array(
				'settings_i18n' => array(
					'settings_success_msg' => esc_html__( 'Settings saved successfully', 'arforms-form-builder' )
				)
			);

			wp_localize_script( 'arforms-settings', 'arforms_settings_i18n_data', $arforms_settings_i18n_data );
		} 

		if( !empty( $_GET['page'] ) && 'ARForms-entries' == $_GET['page'] ){
			
			wp_register_script( 'arforms-view', ARFLITEURL . '/js/arf_view.js', array( 'jquery', 'wp-hooks' ), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arforms-view' );

			if( $this->arforms_is_pro_active() ){

				wp_register_script( 'arforms-pro-view', ARFURL . '/js/arf_pro_view.js', array( 'jquery', 'wp-hooks' ), $this->arforms_get_assets_version() );
				wp_enqueue_script( 'arforms-pro-view' );
			}

		}

		if( !empty( $_GET['page'] ) && 'ARForms-import-export' == $_GET['page'] ){
			
			wp_register_script( 'arforms-import-export', ARFLITEURL . '/js/arf_import_export.js', array( 'jquery', 'wp-hooks' ), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arforms-import-export' );

		} 

		if( !empty( $_GET['page'] ) && 'ARForms' == $_GET['page'] ){
			
			wp_register_script( 'arforms-manage-forms', ARFLITEURL . '/js/arf_manage_form.js', array( 'jquery', 'wp-hooks' ), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arforms-manage-forms' );

			$traslated_text = "
					var __ARFLITE_NO_FORM_FOUND = '" . addslashes( __( 'There is no any form found', 'arforms-form-builder' ) ) . "';
				";
			wp_add_inline_script( 'arforms-manage-forms', $traslated_text );
			
		} 
		
		if( isset ( $_REQUEST['page'] ) && $_REQUEST['page'] == "ARForms-Growth-Tools" ){
			wp_register_style( 'arf_growth_plugin', ARFLITEURL . '/css/arflite_cross_selling.css', array(), $this->arforms_get_assets_version() );
			wp_enqueue_style( 'arf_growth_plugin' );

			wp_register_script( 'arf_growth_plugin', ARFLITEURL . '/js/arf_cross_selling.js', array('jquery'), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arf_growth_plugin' );

			wp_enqueue_style( 'arformslite_v3.0', ARFLITEURL . '/css/arformslite_v3.0.css', array(), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array(), $this->arforms_get_assets_version() );

			wp_register_script( 'tipso', ARFLITEURL . '/js/tipso.min.js', array(), $this->arforms_get_assets_version() );
			wp_enqueue_script( 'tipso' );
			wp_register_style( 'tipso', ARFLITEURL . '/css/tipso.min.css', array(), $this->arforms_get_assets_version() );
			wp_enqueue_style( 'tipso' );

		}

		$js_data = " 
			var __ARFLITE_NO_ENTRY_FOUND = '" . addslashes( __( 'There is no entry found', 'arforms-form-builder' ) ) . "'
			var __ARFLITE_NEXT_TEXT = '" . addslashes( __( 'Next', 'arforms-form-builder' ) ) . "';
			var __ARFLITE_LAST_TEXT = '" . addslashes( __( 'Last', 'arforms-form-builder' ) ) . "';
			var __ARFLITE_FIRST_TEXT = '" . addslashes( __( 'First', 'arforms-form-builder' ) ) . "';
			var __ARFLITE_PREVIOUS_TEXT = '" . addslashes( __( 'Previous', 'arforms-form-builder' ) ) . "';
		;";

		wp_add_inline_script( 'arforms-admin-common', $js_data );

    }

	function arforms_render_entries_tab(){

		if( $this->arforms_is_pro_active() ){ ?>

			<li class="form_entries btn_sld"> <a href="javascript:show_form_settimgs('form_entries','analytics','form_incomplete_entries');"><?php echo addslashes(esc_html__('Form Entries Data', 'arforms-form-builder')); //phpcs:ignore ?></a></li>
			<?php if( current_user_can('arfviewreports') ){ ?>
			<li class="analytics tab-unselected"> <a href="javascript:show_form_settimgs('analytics','form_entries','form_incomplete_entries');"><?php echo addslashes(esc_html__('Analytics / Chart', 'arforms-form-builder')); //phpcs:ignore ?></a></li>
			<?php } if( current_user_can('arfviewincompleteentries') ){ ?>
			<li class="form_incomplete_entries tab-unselected"><a href="javascript:show_form_settimgs('form_incomplete_entries','form_entries','analytics');"><?php echo addslashes( esc_html__( 'Partial Filled Form Entries', 'arforms-form-builder' ) ); //phpcs:ignore ?></a></li>
			<?php } 
		} else { ?>

			<li class="form_entries btn_sld"> <a href="javascript:void(0);"><?php echo esc_html__( 'Form Entries Data', 'arforms-form-builder' ); ?></a></li>
			<li class="analytics tab-unselected"> <a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Analytics / Chart', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice arflite_pro_notice_with_title">(Premium)</span></a></li>
			<li class="form_incomplete_entries tab-unselected"><a href="javascript:void(0);" class="arf_restricted_control"><?php echo esc_html__( 'Partial Filled Form Entries', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice arflite_pro_notice_with_title">(Premium)</span></a></li> 
		<?php }

	}
	

	function arforms_render_settings_tab( $general_setting_tab_selection, $autoresponder_tab_selection, $log_tab_selection, $status_tab_selection ){
		if( $this->arforms_is_pro_active() ){
			?>
			<li style="width:auto !important" class="general_settings <?php echo esc_html($general_setting_tab_selection); ?>">
				<a href="javascript:show_form_settimgs('general_settings','autoresponder_settings','logs_settings');"><?php echo addslashes(esc_html__('General Settings', 'arforms-form-builder')); //phpcs:ignore ?></a>
			</li>
			<li style="width:auto !important" class="autoresponder_settings <?php echo esc_html($autoresponder_tab_selection); ?>">
				<a href="javascript:show_form_settimgs('autoresponder_settings','general_settings','logs_settings');"><?php echo addslashes(esc_html__('Email Marketing Tools', 'arforms-form-builder')); //phpcs:ignore ?></a>
			</li>
			<li style="width:auto !important" class="logs_settings <?php echo esc_html($log_tab_selection); ?>">
				<a href="javascript:show_form_settimgs('logs_settings','general_settings','autoresponder_settings');"><?php echo addslashes(esc_html__('Debug Logs', 'arforms-form-builder')); //phpcs:ignore ?></a>
			</li>			
			<?php
		} else {
			?>
			<li class="arfsettingpagenavli general_settings <?php echo esc_html($general_setting_tab_selection); ?> ">
				<a href="javascript:arflite_show_form_settimgs('general_settings','autoresponder_settings','logs_settings');"><?php echo esc_html__( 'General Settings', 'arforms-form-builder' ); ?></a>
			</li>
			<li class="arfsettingpagenavli autoresponder_settings <?php echo esc_html($autoresponder_tab_selection); ?>">
				<a href="javascript:arflite_show_form_settimgs('autoresponder_settings','general_settings','logs_settings');"><?php echo esc_html__( 'Email Marketing Tools', 'arforms-form-builder' ); ?><span class="arflite_pro_version_notice arflite_pro_notice_with_title">(Premium)</span></a>
			</li>
			<li class="arfsettingpagenavli logs_settings <?php echo esc_html($log_tab_selection); ?>">
				<a href="javascript:arflite_show_form_settimgs('logs_settings','general_settings','autoresponder_settings');"><?php echo addslashes(esc_html__('Debug Logs', 'arforms-form-builder')); //phpcs:ignore ?></a>
			</li>
			<?php
		} ?>

	   <?php 
	}

	function arforms_get_assets_version(){
		return '1059_'.time();
	}

    public static function arfliteinstall( $old_db_version = false ) {

		global $ARFLiteMdlDb,$arflitemainhelper;

		$arf_db_version = get_option( 'arflite_db_version' );
		if ( $arf_db_version == '' || ! isset( $arf_db_version ) ) {
			arforms_form_builder::arfliteupgrade( $old_db_version );

			$nextEvent = strtotime( '+60 days' );

			wp_schedule_single_event( $nextEvent, 'arflite_display_ratenow_popup' );
		}

		$args  = array(
			'role'   => 'administrator',
			'fields' => 'id',
		);
		$users = get_users( $args );
		if ( count( $users ) > 0 ) {
			foreach ( $users as $key => $user_id ) {

				global $current_user;
				$arfroles = $arflitemainhelper->arflite_frm_capabilities();

				$userObj = new WP_User( $user_id );
				foreach ( $arfroles as $arfrole => $arfroledescription ) {
					$userObj->add_cap( $arfrole );
				}
				unset( $arfrole );
				unset( $arfroles );
				unset( $arfroledescription );
			}
		}
		add_option('arformslite_install_date',current_time('mysql'));
	}

    public static function arfliteupgrade( $old_db_version = false ) {

		global $wpdb, $arflitedbversion, $tbl_arf_fields, $tbl_arf_forms, $tbl_arf_entries, $tbl_arf_entry_values, $tbl_arf_debug_log_setting, $tbl_arf_settings;

		$old_db_version = (float) $old_db_version;

		if ( ! $old_db_version ) {
			$old_db_version = get_option( 'arflite_db_version' );
		}

		if ( $arflitedbversion != $old_db_version ) {

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			$charset_collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {

				if ( ! empty( $wpdb->charset ) ) {
					$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
				}

				if ( ! empty( $wpdb->collate ) ) {
					$charset_collate .= " COLLATE $wpdb->collate";
				}
			}

			$sql = "CREATE TABLE IF NOT EXISTS `{$tbl_arf_fields}` (

                id int(11) NOT NULL auto_increment,

                field_key varchar(25) default NULL,

                name text default NULL,

                type varchar(50) default NULL,

                options longtext default NULL,

                required int(1) default NULL,

                field_options longtext default NULL,

                form_id int(11) default NULL,

                created_date datetime NOT NULL,

				conditional_logic tinyint(1) default 0,
				
                enable_running_total longtext default NULL,

                option_order text default NULL,

				arf_field_order int(11) default NULL,

                PRIMARY KEY  (id),

                KEY form_id (form_id),

                UNIQUE KEY field_key (field_key)

              ) {$charset_collate};";

			dbDelta( $sql );

			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); }

			$sql = "CREATE TABLE IF NOT EXISTS {$tbl_arf_forms} (

                id int(11) NOT NULL auto_increment,

                form_key varchar(25) default NULL,

                name varchar(255) default NULL,

                description text default NULL,

                is_template boolean default 0,

                status varchar(25) default NULL,

                options longtext default NULL,

                created_date datetime NOT NULL,

				autoresponder_fname int(11),

        		autoresponder_lname int(11),

        		autoresponder_email int(11),

        		columns_list text default NULL,

        		form_css longtext default NULL,

                temp_fields longtext default NULL,

				arf_mapped_addon longtext default NULL,

				is_imported_from_lite tinyint(1) default 0,

				partial_grid_column_list text default NULL,

                arf_is_lite_form tinyint(1) default 0,

                arf_lite_form_id int(11) default NULL,

				arforms_update_form tinyint(1) default 0,

				arforms_is_migrated_form int(11) default 0,

                PRIMARY KEY  (id),

                UNIQUE KEY form_key (form_key)

              ) {$charset_collate};";

				dbDelta( $sql );

			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); }

			$sql = "CREATE TABLE IF NOT EXISTS {$tbl_arf_entries} (

                id int(11) NOT NULL auto_increment,

                entry_key varchar(25) default NULL,

                name varchar(255) default NULL,

                description text default NULL,

                ip_address varchar(255) default NULL,

		        country varchar(255) default NULL,

                browser_info text default NULL,

                form_id int(11) default NULL,

                attachment_id int(11) default NULL,

                user_id int(11) default NULL,

				is_incomplete_entry tinyint(1) default 0,

                created_date datetime NOT NULL,

                PRIMARY KEY  (id),

                KEY form_id (form_id),

                KEY attachment_id (attachment_id),

                KEY user_id (user_id),

                UNIQUE KEY entry_key (entry_key)

              ) {$charset_collate};";

			dbDelta( $sql );
			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); }

			$sql = "CREATE TABLE IF NOT EXISTS {$tbl_arf_entry_values} (

                id int(11) NOT NULL auto_increment,

                entry_value longtext default NULL,

                field_id int(11) NOT NULL,

                entry_id int(11) NOT NULL,

                created_date datetime NOT NULL,

                PRIMARY KEY  (id),

                KEY field_id (field_id),

                KEY entry_id (entry_id)

              ) {$charset_collate};";

			dbDelta( $sql );

			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) );
            }

			$sql = "CREATE TABLE IF NOT EXISTS {$tbl_arf_debug_log_setting} (

				`arf_debug_log_id` bigint(11) NOT NULL AUTO_INCREMENT,

				`arf_debug_log_ref_id` bigint(11) NOT NULL DEFAULT '0',

				`arf_debug_log_type` varchar(255) DEFAULT NULL,

				`arf_debug_log_event` varchar(255) DEFAULT NULL,

				`arf_debug_log_event_from` varchar(255) DEFAULT NULL,

				`arf_debug_log_raw_data` TEXT DEFAULT NULL,		

				`arf_debug_log_added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

				PRIMARY KEY (`arf_debug_log_id`)


				) {$charset_collate};";

			dbDelta( $sql );
			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); 
			}

			$sql = "CREATE TABLE IF NOT EXISTS `{$tbl_arf_settings}`(
				`setting_id` int(11) NOT NULL AUTO_INCREMENT,
				`setting_name` varchar(255) NOT NULL,
				`setting_value` TEXT DEFAULT NULL,
				`setting_type` varchar(255) DEFAULT NULL,
				`created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`setting_id`)
			) {$charset_collate}";
				
			dbDelta( $sql );
			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); 
			}
					

			update_option( 'arflite_db_version', $arflitedbversion );

			$target_path = ARFLITE_UPLOAD_DIR;

			wp_mkdir_p( $target_path );

			$target_path .= '/maincss';

			wp_mkdir_p( $target_path );

            if( ! arforms_form_builder::arforms_is_premium_available() ) {

                /* code added for the setting table  */
                $arforms_settings = arforms_form_builder::arflite_default_options();
				
                foreach( $arforms_settings as $setting_key=>$setting_val ){

                    global $tbl_arf_settings, $wpdb;

                    $res = $wpdb->query( $wpdb->prepare('insert into '. $tbl_arf_settings. '(setting_name, setting_value, setting_type) values (%s, %s, %s)', $setting_key, $setting_val, 'general_settings')); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm
                    
                }
                $arforms_log = arforms_form_builder::arf_log_default_options();
				
                foreach( $arforms_log as $log_key=>$log_val ){

                    global $tbl_arf_settings, $wpdb;

                    $res = $wpdb->query( $wpdb->prepare('insert into '. $tbl_arf_settings. '(setting_name, setting_value, setting_type) values (%s, %s, %s)', $log_key, $log_val, 'debug_log_settings')); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm
                    
                }

            } else {
                update_option( 'arflite_installed_after_pro_version', arforms_form_builder::arforms_get_premium_version() );

				$arflite_options = get_option( 'arf_options' );
				foreach( $arflite_options as $setting_key => $setting_val ){
					global $tbl_arf_settings, $wpdb;
					$wpdb->insert(
						$tbl_arf_settings,
						array(
							'setting_name' => $setting_key,
							'setting_value' => $setting_val,
							'setting_type' => 'general_settings'
						),
						array(
							'%s',
							'%s',
							'%s'
						)
					);	
				}
            }

			update_option( 'arforms_setting_table_exists', 1 );

			global $wpdb, $tbl_arf_forms;
			$wpdb->query( "ALTER TABLE {$tbl_arf_forms} AUTO_INCREMENT = 100" ); //phpcs:ignore
			if ( $wpdb->last_error !== '' ) {
				update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) );
            }

			global $arflitemaincontroller;
			$arflitemaincontroller->arflite_getwpversion();

			update_option( 'arf_form_entry_separator', sanitize_text_field( 'arf_comma' ) );

			update_option( 'arflite_plugin_activated', 1 );
		}

		do_action( 'arfliteafterinstall' );
	}

	function arforms_get_all_setting_data(){
		global $tbl_arf_settings, $wpdb, $arf_setting_data;

		if( empty( get_option('arforms_setting_table_exists') ) ){
			return '';
		}
							
		$arforms_all_general_settings = $wpdb->get_results( "SELECT * FROM {$tbl_arf_settings} ORDER BY setting_type ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm 

		$arf_setting_data = array();

		if( !empty( $arforms_all_general_settings )){

			foreach( $arforms_all_general_settings as $arf_setting_key=>$arf_setting_val ){

				$arf_setting_data[$arf_setting_val->setting_name] = $arf_setting_val->setting_value;
			}
		}
		return $arf_setting_data;
	}

	function arforms_global_option_data(){

		global $tbl_arf_settings, $wpdb;

		$arf_general_settings_data = array();

        // Get all the general settings
		$arf_cached_general_settings = wp_cache_get( 'arforms_all_general_settings' );

		if( false === $arf_cached_general_settings ){
			$arforms_all_general_settings = $wpdb->get_results( "SELECT setting_name,setting_value,setting_type FROM {$tbl_arf_settings} ORDER BY setting_type ASC" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm 
			wp_cache_set( 'arforms_all_general_settings', $arforms_all_general_settings ); 
		} else {
			$arforms_all_general_settings = $arf_cached_general_settings;
		}

		if( !empty( $arforms_all_general_settings ) ){
			foreach( $arforms_all_general_settings as $cs_key => $cs_value ){
				$general_type = $cs_value->setting_type;
				if( empty( $arf_general_settings_data[ $general_type ] ) ){
					$arf_general_settings_data[ $general_type ] = array();
				}

				$arf_general_settings_data[ $general_type ][ $cs_value->setting_name ] = $cs_value->setting_value;
			}
		}

		$global_data = $arf_general_settings_data;

		return $global_data;
	}

	function arforms_default_arr_options(){
		$settings_key = array(
			'arf_load_js_css'
		);

		return $settings_key;
	}

	public function arforms_get_settings( $setting_name, $setting_type ) {

		global $arformsmain;

		$arforms_general_setting_options  = $arformsmain->arforms_global_option_data();

		if( !is_array( $setting_name ) && isset( $arforms_general_setting_options[ $setting_type ][ $setting_name ] ) ){
			$return_setting_data = $arforms_general_setting_options[ $setting_type ][ $setting_name ];
			$return_setting_data = apply_filters( 'arforms_modified_get_settings',$return_setting_data,$setting_type,$setting_name);
			return $return_setting_data;

		} else if( is_array( $setting_name ) ){
			$return_data = array();
			foreach( $setting_name as $setting_name_key ){
				if( !empty( $arforms_general_setting_options[ $setting_type ][ $setting_name_key ] ) ){
					$return_setting_data = $arforms_general_setting_options[ $setting_type ][ $setting_name_key ];
					$return_setting_data = apply_filters( 'arforms_modified_get_settings',$return_setting_data,$setting_type,$setting_name_key);
					$return_data[ $setting_name_key ] = $return_setting_data;
				} else {
					$return_data[ $setting_name_key ] = '';
				}
			}
			return $return_data;
		} else {
			return '';
		}
    }

	function arforms_update_settings( $setting_name, $setting_value, $setting_type ){

		global $wpdb, $tbl_arf_settings;

		$check_settings = $wpdb->get_var( $wpdb->prepare( "SELECT setting_id FROM {$tbl_arf_settings} WHERE setting_name = %s AND setting_type = %s", $setting_name, $setting_type )); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm 

		if( empty( $check_settings ) ){
			$wpdb->insert(
				$tbl_arf_settings,
				array(
					'setting_name' => $setting_name,
					'setting_value' => $setting_value,
					'setting_type' => $setting_type
				)
			);
		} else {
			$wpdb->update(
				$tbl_arf_settings,
				array(
					'setting_value' => $setting_value,
				),
				array(
					'setting_name' => $setting_name,
					'setting_type' => $setting_type
				)
			);
		}

	}

	function arforms_is_valid_json( $data ){
		if (!empty($data)) { 
            return is_string($data) && is_array( json_decode( $data, true ) ) ? true : false; 
        } 
        return false; 
	}

	public static function arflite_default_options_keys(){
		$option_keys = array(
			'arfdisablehiddencaptcha' => 'hidden_captcha',
			'arfmainjquerycss' => 'jquery_css',
			'arfmainformsubmittype' => 'form_submit_type',
		);

		return $option_keys;
	}

    public static function arflite_default_options() {
		return array(
			'menu'                          => 'ARForms',
			'mu_menu'                       => 0,
			'use_html'                      => true,
			'jquery_css'	 				=> false,
			'arfmainjquerycss' 				=> false,
			'accordion_js'                  => false,
			'hidden_captcha'				=> false,
			'arfdisablehiddencaptcha'		=> false,
			're_theme'                      => 'light',
			'success_msg'                   => 'Form is successfully submitted. Thank you!',
			'pubkey'						=> '',
			'privkey'						=> '',
			're_lang'						=> 'en',
			're_msg'                        => 'Invalid reCaptcha. Please try again.',
			'blank_msg'                     => 'This field cannot be blank.',
			'unique_msg'                    => 'This value must be unique.',
			'invalid_msg'                   => 'Problem in submission. Errors are marked below.',
			'failed_msg'                    => 'We\'re sorry. Form is not submitted successfully.',
			'submit_value'                  => 'Submit',
			'admin_permission'              => 'You do not have permission to perform this action',
			'email_to'                      => '[admin_email]',
			'current_tab'                   => 'general_settings',
			'form_submit_type'		        => 1,
			'arfmainformsubmittype'         => 1,
			'reply_to_name'                 => get_option( 'blogname' ),
			'reply_to'                      => get_option( 'admin_email' ),
			'ar_admin_reply_to_email'       => get_option( 'admin_email' ),
			'user_nreplyto_email'           => get_option( 'admin_email' ),
			'reply_to_email'                => get_option( 'admin_email' ),
			'smtp_server'                   => 'wordpress',
			'smtp_host'                     => '',
			'smtp_port'                     => '',
			'smtp_username'                 => '',
			'smtp_password'                 => '',
			'smtp_encryption'               => 'none',
			'gmail_api_clientid'            => '',
			'gmail_api_clientsecret' 		=> '',
			'gmail_api_accesstoken' 		=> '',
			'gmail_api_connected_gmail' 	=> '',
			'decimal_separator'             => '.',
			'arf_global_css'				=> '',
			'arf_success_message_show_time' => 3,
			'arf_css_character_set'         => '',
			'is_smtp_authentication'        => 1,
			'arf_email_format'              => 'html',
			'arf_pre_dup_msg'               => __( 'You have already submitted this form before. You are not allowed to submit this form again.', 'arforms-form-builder' ),
			'arfmainformloadjscss'          => 0,
			'arf_load_js_css'               => json_encode( array() ),
			'anonymous_data'              => 0,
		);
	}

	public static function arf_log_default_options(){
		return array(
			'email_notification'            => 0,
		);
	}

    public static function arforms_is_premium_available(){

        $pro_version = get_option( 'arf_db_version' );

        return !(empty( $pro_version ));

    }

    public static function arforms_get_premium_version(){
        return get_option( 'arf_db_version' );
    }

    public static function arforms_is_pro_active(){
        return is_plugin_active( 'arforms/arforms.php' );
    }

    function arf_get_free_menu_position($start, $increment = 0.1) {
        foreach ($GLOBALS['menu'] as $key => $menu) {
            $menus_positions[] = $key;
        }

        if (!in_array($start, $menus_positions)) {
            return $start;
        } else {
            $start += $increment;
        }

        while (in_array($start, $menus_positions)) {
            $start += $increment;
        }
        return $start;
    }

    function arforms_register_menu(){

        $place = $this->arf_get_free_menu_position( 26.1, .1 );

        if ( current_user_can( 'arfviewforms' ) ) {

			global $arfliteformcontroller;

			add_menu_page( 'ARForms', 'ARForms', 'arfviewforms', 'ARForms', array( $this, 'arforms_router' ), ARFLITEIMAGESURL . '/main-icon-small2n.png', (string) $place );
		} elseif ( current_user_can( 'arfviewentries' ) ) {

			global $arfliterecordcontroller;

			add_menu_page( 'ARForms', 'ARForms', 'arfviewentries', 'ARForms', array( $this, 'arforms_router' ), ARFLITEIMAGESURL . '/main-icon-small2n.png', (string) $place );
		}

        add_submenu_page( '', '', '', 'administrator', 'ARForms-settings1', array( $this, 'list_entries' ) );

        add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Forms', 'arforms-form-builder' ), __( 'Manage Forms', 'arforms-form-builder' ), 'arfviewforms', 'ARForms', array( $this, 'arforms_router' ) );

		add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Add New Form', 'arforms-form-builder' ), '<span>' . __( 'Add New Form', 'arforms-form-builder' ) . '</span>', 'arfeditforms', 'ARForms&amp;arfaction=new&amp;isp=1', array( $this, 'arforms_router' ) );

		add_submenu_page( 'ARForms', 'ARForms' . ' | ' . __( 'Form Entries', 'arforms-form-builder' ), __( 'Form Entries', 'arforms-form-builder' ), 'arfviewentries', 'ARForms-entries', array( $this, 'arforms_router' ) );

		add_submenu_page( 'ARForms', 'ARForms | ' . __( 'General Settings', 'arforms-form-builder' ), __( 'General Settings', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-settings', array( $this, 'arforms_router' ) );

		add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Import Export', 'arforms-form-builder' ), __( 'Import / Export', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-import-export', array( $this, 'arforms_router' ) );

        add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Add-ons', 'arforms-form-builder' ), __( 'Addons', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-addons', array( $this, 'arforms_router' ) );

        add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Growth Plugins', 'arforms-form-builder' ), __( 'Growth Plugins', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Growth-Tools' , array( $this, 'arforms_router' ) );

        $arf_current_date = current_time('timestamp', true );
		$arf_sale_start_time = '1700503200';
		$arf_sale_end_time = '1701561600';

		if( $arf_current_date >= $arf_sale_start_time && $arf_current_date <= $arf_sale_end_time ){
			add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Black Friday Sale', 'arforms-form-builder' ), __( 'Black Friday Sale', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms&amp;upgrade-to-pro=yes' , array( $this, 'arforms_router' ) );
		} else {
			$page_hook = add_submenu_page( 'ARForms', 'ARForms | ' . __( 'Upgrade To Premium', 'arforms-form-builder' ), __( 'Upgrade To Premium', 'arforms-form-builder' ), 'arfchangesettings', 'arflite_upgrade_to_premium' , array( $this, 'arflite_upgrade_to_premium' ) );
			add_action( 'load-' . $page_hook, array( $this,'arf_upgrade_ob_start' ) );
		}

    }

    function arf_upgrade_ob_start(){
        wp_redirect( 'https://codecanyon.net/item/arforms-wordpress-form-builder-plugin/6023165', 301 );
		exit();
    }

    function arforms_router(){

        global $wpdb;

        $action = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

		$newformid = isset( $_REQUEST['newformid'] ) ? intval( $_REQUEST['newformid'] ) : 0;

        if( !empty( $_GET['page'] ) && 'ARForms' == $_GET['page'] ){

            if( $this->arforms_is_pro_active() ){
                global $arformcontroller;
                $arformcontroller->route();
            } else {
                global $arfliteformcontroller;
                $arfliteformcontroller->arfliteroute();
            }
        } else if( !empty( $_GET['page'] ) && 'ARForms-entries' == $_GET['page'] ){

            if( $this->arforms_is_pro_active() ){
                
            } else {
                global $arfliterecordcontroller;
                $arfliterecordcontroller->arfliteroute();
            }
        } else if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'ARForms-import-export' ) {
            if( $this->arforms_is_pro_active() ){

            } else {
                global $arflitesettingcontroller;
                $arflitesettingcontroller->arflite_import_export_form();
            }
        } elseif ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-addons' ) {
			if ( file_exists( ARFLITE_VIEWS_PATH . '/addon_lists.php' ) ) {
				include ARFLITE_VIEWS_PATH . '/addon_lists.php';
			}
		} 
		else if( isset($_REQUEST['page']) && $_REQUEST['page'] == 'ARForms-log'){
            if(file_exists(ARFLITE_VIEWS_PATH . '/arf_debug_log.php')){
                include(ARFLITE_VIEWS_PATH . '/arf_debug_log.php');
            }
        } elseif( isset ( $_REQUEST['page']) && $_REQUEST['page']=="ARForms-Growth-Tools") {
			require_once ARFLITE_VIEWS_PATH . '/arflite_cross_selling_content.php';
		} else if ( isset( $_REQUEST['page'] ) && 'ARForms-settings' == $_REQUEST['page'] ){
			require_once ARFLITE_VIEWS_PATH . '/arflite_settings_form.php';
		} else {
			$action = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

			global $arflitemainhelper, $arflitesettingcontroller;

			$cur_tab = isset( $_REQUEST['arfcurrenttab'] ) ? sanitize_text_field( $_REQUEST['arfcurrenttab'] ) : '';

			$action = $arflitemainhelper->arflite_get_param( $action );

			if ( $action == 'process-form' ) {
				return $arflitesettingcontroller->arfliteprocess_form( $cur_tab );
			} else {
				return $arflitesettingcontroller->arflitedisplay_form();
			}
		}

    }

	function arforms_wpkses_allowed_html(){
		$allowed_html_arr = array(
			'a' => array('title'=>array(), 'href'=>array(), 'target'=>array(), 'class'=>array(), 'id'=>array(), 'style'=>array()),
			'arftotal' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'b' => array(),
			'blockquote' => array(),
			'br' => array(),
			'button' => array('class'=>array(), 'id'=>array(), 'style'=>array(), 'title'=>array()),
			'canvas' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'center' => array(),
			'code' => array(),
			'dd' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'del' => array('datetime' => array(), 'title' => array()),
			'div' => array('class'=>array(), 'id'=>array(), 'style'=>array(), 'title'=>array()),
			'dl' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'dt' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'em' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'embed' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'font' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'frame' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'frameset' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'h1' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'h2' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'h3' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'h4' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'h5' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'hr' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'i' => array(),
			'iframe' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'img' => array('class'=>array(), 'id'=>array(), 'style'=>array(), 'src'=>array(), 'alt'=>array(), 'height'=>array(), 'width'=>array()),
			'label' => array('class'=>array(), 'id'=>array(), 'style'=>array(), 'for'=>array()),
			'li' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'link' => array('href'=>array(), 'type'=>array()),
			'meta' => array(),
			'object' => array(),
			'ol' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'p' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'pre' => array(),
			'q' => array('cite' => array(), 'title' => array()),
			'span' => array('class'=>array(), 'id'=>array(), 'style'=>array(), 'title'=>array()),
			'script' => array('src'=>array(), 'type'=>array()),
			'strike' => array(),
			'sub' => array(),
			'sup' => array(),
			'svg' => array(),
			'strong' => array(),
			'tfooter' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'tbody' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'thead' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'th' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'td' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'tr' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'table' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
			'u' => array(),
			'ul' => array('class'=>array(), 'id'=>array(), 'style'=>array()),
		);

		return $allowed_html_arr;	
	}

	function arforms_skip_sanitization_keys(){
		$skip_sanitization_keys = array(
			'smtp_password'
		);

		return $skip_sanitization_keys;
	}

	function arforms_sanitize_values( $data_array ){
		if( null == $data_array ){
			return $data_array;
		}

		if (is_array($data_array) ) {
			return array_map(array( $this, __FUNCTION__ ), $data_array);
		} else {
			if(preg_match( '/<[^<]+>/', $data_array ) ) {
				return wp_kses( $data_array, $this->arforms_wpkses_allowed_html() );
			} else {
				return $this->arforms_sanitize_single_value($data_array);
			}
		}
	}

	function arforms_sanitize_single_value( $value ){
		if( empty( $value ) || gettype( $value ) === 'boolean' ){
			return $value;
		}

		if( filter_var( $value, FILTER_VALIDATE_INT ) ){
			return intval( $value );
		} else if( filter_var( $value, FILTER_VALIDATE_EMAIL) ){
			return sanitize_email( $value );
		} else if( filter_var( $value, FILTER_VALIDATE_FLOAT ) ){
			return floatval( $value );
		} else if( filter_var( $value, FILTER_VALIDATE_URL ) ){
			return esc_url( $value );
		}


		return sanitize_text_field( $value );

	}

	function arforms_load_autoresponder_settings_view(){

		if( $this->arforms_is_pro_active() && version_compare( $this->arforms_get_premium_version() , '6.1', '>=') ){
			/** Load pro file */
			require_once VIEWS_PATH . '/arforms_autoresponder_settings.php';
		} else {
			require_once ARFLITE_VIEWS_PATH . '/arflite_autoresponder_settings.php';
		}

	}
	function arforms_load_log_settings_view(){
		require_once ARFLITE_VIEWS_PATH . '/arf_debug_log.php';
	}
	
}

global $arformsmain;
$arformsmain = new arforms_form_builder();  