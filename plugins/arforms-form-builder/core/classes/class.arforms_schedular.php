<?php

class arforms_schedular{

    function __construct(){

        add_action( 'arforms_merge_tables_159', array( $this, 'arforms_schedule_159_migrate_tables') );
        add_action( 'arforms_process_159_merge_tables', array( $this, 'arforms_do_migrate_159_tables') );

        add_action( 'arforms_merge_settings_159', array( $this, 'arforms_merge_settings_159_callback') );
        add_action( 'arforms_merge_159_settings', array( $this, 'arforms_merge_settings_159_process') );

        add_action( 'arforms_field_order_new_column_159', array( $this, 'arforms_field_order_new_column_159_callback') );
        add_action( 'arforms_field_order_column_159', array( $this, 'arforms_field_order_column_159_process') );
        
        add_action( 'arforms_merge_settings_160', array( $this, 'arforms_merge_settings_160_callback' ) );
        add_action( 'arforms_merge_160_settings', array( $this, 'arforms_merge_settings_159_process') );

    }

    function arforms_field_order_new_column_159_callback( $process_data ){
        $hook_status = get_option( 'arforms_field_order_new_column_159_callback_status' );

        if( empty( $hook_status ) ){
            if( !empty( $process_data['dependent_hook'] ) ){
                $dependent_hook_status_check = get_option( 'arforms_process_' . $process_data['dependent_hook'] . '_status' );
                
                if( empty( $dependent_hook_status_check ) || ( !empty( $dependent_hook_status_check ) && 2 != $dependent_hook_status_check ) ){
                    
                    $timestamp = current_time('timestamp') + 20; //schedule event after 20 seconds
                    wp_schedule_single_event( $timestamp, 'arforms_field_order_new_column_159', [ $process_data ] );

                    update_option( 'arforms_process_arforms_field_order_new_column_159_status', 0 );
                    update_option( 'arforms_field_order_new_column_159_schedular_callback', json_encode(
                        array(
                            'callback' => 'arforms_field_order_new_column_159',
                            'timestamp' => $timestamp,
                            'args' => [$process_data]
                        )
                    ) );

                } else {

                    update_option( 'arforms_field_order_new_column_159_callback_status', 1 );
                    /** Schedule Event to run */
                    $timestamp = current_time('timestamp') + 2;
                    wp_schedule_single_event( $timestamp, 'arforms_field_order_column_159', [ $process_data ] );

                    update_option( 'arforms_process_arforms_field_order_new_column_159_status', 0 );
                    update_option( 'arforms_field_order_new_column_159_schedular_callback', json_encode(
                        array(
                            'callback' => 'arforms_field_order_new_column_159',
                            'timestamp' => $timestamp,
                            'args' => [$process_data]
                        )
                    ) );
                }
                
            } else {
                update_option( 'arforms_field_order_new_column_159_callback_status', 1 );
                /** Schedule Event to run */
                $timestamp = current_time('timestamp') + 2;
                wp_schedule_single_event( $timestamp, 'arforms_field_order_column_159', [ $process_data ] );

                update_option( 'arforms_process_arforms_field_order_new_column_159_status', 0 );
                update_option( 'arforms_field_order_new_column_159_schedular_callback', json_encode(
                    array(
                        'callback' => 'arforms_field_order_new_column_159',
                        'timestamp' => $timestamp,
                        'args' => [$process_data]
                    )
                ) );
            }
        }
    }

    function arforms_field_order_column_159_process( $process_args ){

        $hook_name = $process_args['hook'];
        $is_pro_check = !empty( $process_args['is_pro_check'] ) ? $process_args['is_pro_check'] : 0;
        $execute_with_pro = empty( $process_args['execute_with_pro'] ) ? $process_args['execute_with_pro'] : 0;
        $use_pro_data = !empty( $process_args['use_pro_data'] ) ? $process_args['use_pro_data'] : 0;

        $hook_order = $process_args['hook_order'];
        

        global $arformsmain, $wpdb, $arflitedbversion, $tbl_arf_fields, $tbl_arf_forms, $tbl_arf_entries;

        if( $is_pro_check == 1 ){
            /** Process the data */
            update_option( 'arforms_process_' . $hook_name . '_start_timestamp', current_time('timestamp') );
            update_option( 'arforms_process_' . $hook_name . '_status', 1 );

            $arf_is_field_order_exists = $wpdb->get_row( $wpdb->prepare( "SHOW COLUMNS FROM {$tbl_arf_fields} LIKE %s", 'field_order' ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_fields is table name defined globally. False Positive alarm

            if( false == $arf_is_field_order_exists ){
                $wpdb->query( "ALTER TABLE {$tbl_arf_fields} ADD COLUMN field_order int(11) DEFAULT NULL AFTER form_id" ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_fields is table name defined globally. False Positive alarm
            }

            $get_all_form_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . ' ORDER BY id ASC' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm

            $all_inner_field_data = array();
            if( !empty( $get_all_form_data )){

                foreach( $get_all_form_data as $key=>$val ){

                    $field_options = maybe_unserialize($val->options);
                    $inner_field_data_order = $field_options['arf_field_order'];
                    $all_inner_field_data[ $val->id ] =  json_decode($inner_field_data_order, true);
                }
            }

            $get_all_fields = $wpdb->get_results( $wpdb->prepare('SELECT * FROM ' . $tbl_arf_fields . ' ORDER BY id')); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Reason: $tbl_arf_fields is table name defined globally. False Positive alarm
            $add_inner_field_order = array();
            if( !empty( $get_all_fields )){
                foreach( $get_all_fields as $all_key=>$all_val ){
                    $field_id = $all_val->id;
                    $form_id = $all_val->form_id;
                    $add_inner_field_order[ $field_id ] = $form_id;
                    
                }
            }

            $new_field_order = array();
            foreach( $all_inner_field_data as $inner_field_key => $inner_field_val ){

                foreach( $inner_field_val as $inner_key=>$inner_val){

                    foreach( $add_inner_field_order as $key=>$val ){
                        if( $key == $inner_key ){
                            $new_field_order[ $key ] = $inner_val; 
                        }

                    }
                }   
            }

            foreach( $new_field_order as $arf_field_id => $arf_field_order){
                global $wpdb, $ARFLiteMdlDb;

                $wpdb->update(
                    $tbl_arf_fields,
                    array(
                        'field_order' => $arf_field_order
                    ),
                    array(
                        'id' => $arf_field_id
                    ),
                    array(
                        '%d'
                    ),
                    array(
                        '%d'
                    )
                );

                if ( $wpdb->last_error !== '' ) {
                    update_option( 'ARF_ERROR_' . time() . rand(), 'ERROR===>' . htmlspecialchars( $wpdb->last_result, ENT_QUOTES ) . 'QUERY===>' . htmlspecialchars( $wpdb->last_query, ENT_QUOTES ) ); 
                } 

            }

            if( !$arformsmain->arforms_is_premium_available() ){              

                $wpdb->query( "ALTER TABLE {$tbl_arf_forms} ADD COLUMN autoresponder_fname int(11) AFTER created_date, ADD COLUMN autoresponder_lname int(11) AFTER autoresponder_fname, ADD COLUMN autoresponder_email int(11) AFTER autoresponder_lname"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm 

                $wpdb->query( "ALTER TABLE {$tbl_arf_forms} ADD COLUMN arf_mapped_addon longtext default NULL AFTER temp_fields, ADD COLUMN is_imported_from_lite tinyint(1) default 0 AFTER arf_mapped_addon, ADD COLUMN partial_grid_column_list text default NULL AFTER is_imported_from_lite, ADD COLUMN arf_update_form tinyint(1) default 0 AFTER arf_lite_form_id, ADD COLUMN arforms_is_migrated_form INT(11) NOT NULL DEFAULT '0' AFTER 'arforms_update_form'" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_forms is table name defined globally. False Positive alarm 


                $wpdb->query( "ALTER TABLE {$tbl_arf_fields} ADD COLUMN conditional_logic tinyint(1) default 0 AFTER created_date, ADD COLUMN enable_running_total LONGTEXT default NULL after conditional_logic "); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_fields is table name defined globally. False Positive alarm 

                $wpdb->query( "ALTER TABLE {$tbl_arf_entries} ADD is_incomplete_entry tinyint(11) default 0 AFTER user_id"); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_entries is table name defined globally. False Positive alarm 
                

            }
            update_option( 'arforms_process_' . $hook_name . '_status', 2 );
            update_option( 'arforms_process_' . $hook_name . '_end_timestamp', current_time('timestamp') );
        }
    }

    function arforms_merge_settings_160_callback( $process_data ){

        $hook_status = get_option( 'arforms_merge_settings_160_callback_status' );
        $previous_hook_status = get_option( 'arforms_process_arforms_merge_settings_159_status' );

        global $wpdb, $tbl_arf_settings, $arformsmain;
        if( 2 == $previous_hook_status && empty( $hook_status ) ){

            $get_all_general_settings = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$tbl_arf_settings} WHERE setting_type = %s", 'general_settings' ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm

            if( empty( $get_all_general_settings ) ){
                update_option( 'arforms_merge_settings_160_callback_status', 1 );
                /** Schedule Event to run */
                $timestamp = current_time('timestamp') + 2;
                wp_schedule_single_event( $timestamp, 'arforms_merge_160_settings', [ $process_data ] );

                update_option( 'arforms_process_arforms_merge_settings_160_status', 0 );
                update_option( 'arforms_merge_settings_160_schedular_callback', json_encode(
                    array(
                        'callback' => 'arforms_merge_settings_160',
                        'timestamp' => $timestamp,
                        'args' => [$process_data]
                    )
                ) );
            } else {
                //arforms_field_order_new_column_159_callback_status
                update_option( 'arforms_process_arforms_merge_settings_160_status', 2 );
                update_option( 'arforms_process_arforms_merge_settings_160_start_timestamp', current_time('timestamp') );
                update_option( 'arforms_process_arforms_merge_settings_160_end_timestamp', current_time('timestamp') );
            }
        } else if( 2 > $previous_hook_status && empty( $hook_status ) ) {

            $get_all_general_settings = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$tbl_arf_settings} WHERE setting_type = %s", 'general_settings' ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Reason: $tbl_arf_settings is table name defined globally. False Positive alarm

            if( !empty( $get_all_general_settings ) ){
                //arforms_field_order_new_column_159_callback_status
                update_option( 'arforms_process_arforms_merge_settings_160_status', 2 );
                update_option( 'arforms_process_arforms_merge_settings_160_start_timestamp', current_time('timestamp') );
                update_option( 'arforms_process_arforms_merge_settings_160_end_timestamp', current_time('timestamp') );
            } else {                
                $timestamp = current_time('timestamp') + 20; //schedule event after 20 seconds
                wp_schedule_single_event( $timestamp, 'arforms_merge_settings_160', [ $process_data ] );
                
                update_option( 'arforms_process_arforms_merge_settings_160_status', 0 );
                update_option( 'arforms_merge_settings_160_schedular_callback', json_encode(
                    array(
                        'callback' => 'arforms_merge_settings_160',
                        'timestamp' => $timestamp,
                        'args' => [$process_data]
                    )
                ) );
            }
        }

    }

    function arforms_merge_settings_159_callback( $process_data ){

        $hook_status = get_option( 'arforms_merge_settings_159_callback_status' );

        if( empty( $hook_status) ){

            if( !empty( $process_data['dependent_hook'] ) ){
                $dependent_hook_status_check = get_option( 'arforms_process_' . $process_data['dependent_hook'] . '_status' );
                
                if( empty( $dependent_hook_status_check ) || ( !empty( $dependent_hook_status_check ) && 2 != $dependent_hook_status_check ) ){
                    
                    $timestamp = current_time('timestamp') + 20; //schedule event after 20 seconds
                    wp_schedule_single_event( $timestamp, 'arforms_merge_settings_159', [ $process_data ] );

                    update_option( 'arforms_process_arforms_merge_settings_159_status', 0 );
                    update_option( 'arforms_merge_settings_159_schedular_callback', json_encode(
                        array(
                            'callback' => 'arforms_merge_settings_159',
                            'timestamp' => $timestamp,
                            'args' => [$process_data]
                        )
                    ) );
                } else {

                    update_option( 'arforms_merge_settings_159_callback_status', 1 );
                    /** Schedule Event to run */
                    $timestamp = current_time('timestamp') + 2;
                    wp_schedule_single_event( $timestamp, 'arforms_merge_159_settings', [ $process_data ] );

                    update_option( 'arforms_process_arforms_merge_settings_159_status', 0 );
                    update_option( 'arforms_merge_settings_159_schedular_callback', json_encode(
                        array(
                            'callback' => 'arforms_merge_settings_159',
                            'timestamp' => $timestamp,
                            'args' => [$process_data]
                        )
                    ) );
                }
                
            } else {
                update_option( 'arforms_merge_settings_159_callback_status', 1 );
                /** Schedule Event to run */
                $timestamp = current_time('timestamp') + 2;
                wp_schedule_single_event( $timestamp, 'arforms_merge_159_settings', [ $process_data ] );

                update_option( 'arforms_process_arforms_merge_settings_159_status', 0 );
                update_option( 'arforms_merge_settings_159_schedular_callback', json_encode(
                    array(
                        'callback' => 'arforms_merge_settings_159',
                        'timestamp' => $timestamp,
                        'args' => [$process_data]
                    )
                ) );
            }

        }

    }

    function arforms_merge_settings_159_process( $process_args ){
        $hook_name = $process_args['hook'];
        $is_pro_check = !empty( $process_args['is_pro_check'] ) ? $process_args['is_pro_check'] : 0;
        $execute_with_pro = empty( $process_args['execute_with_pro'] ) ? $process_args['execute_with_pro'] : 0;
        $use_pro_data = !empty( $process_args['use_pro_data'] ) ? $process_args['use_pro_data'] : 0;

        $hook_order = $process_args['hook_order'];

        global $arformsmain, $wpdb, $arflitedbversion, $tbl_arf_settings;

        if( $is_pro_check == 1 ){
            /** Process the data */
            update_option( 'arforms_process_' . $hook_name . '_start_timestamp', current_time('timestamp') );
            update_option( 'arforms_process_' . $hook_name . '_status', 1 );
            
            if( $arformsmain->arforms_is_premium_available() ){
                /** Process to check if it's been allowed to process with pro */
                $arflite_options = get_option( 'arf_options' );
            } else {
                $arflite_options = get_option( 'arflite_options' );
            }

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

            update_option( 'arforms_process_' . $hook_name . '_status', 2 );
            update_option( 'arforms_process_' . $hook_name . '_end_timestamp', current_time('timestamp') );
        }
    }

    function arforms_schedule_159_migrate_tables( $process_data ){

        $hook_status = get_option( 'arforms_merge_tables_159_callback_status' );

        if( empty( $hook_status ) ){

            update_option( 'arforms_merge_tables_159_callback_status', 1 ); // set hook in processing

            $timestamp = current_time('timestamp') + 2;
            update_option( 'arforms_merge_tables_159_process', $timestamp );

            wp_schedule_single_event( $timestamp, 'arforms_process_159_merge_tables', [$process_data] );
            update_option( 'arforms_process_arforms_merge_tables_159_status', 0 );
            update_option( 'arforms_merge_tables_159_schedular_callback', json_encode(
                array(
                    'callback' => 'arforms_process_159_merge_tables',
                    'timestamp' => $timestamp,
                    'args' => [$process_data]
                )
            ) );

        }

    }

    function arforms_do_migrate_159_tables( $process_args ){
        $hook_name = $process_args['hook'];
        $is_pro_check = !empty( $process_args['is_pro_check'] ) ? $process_args['is_pro_check'] : 0;
        $execute_with_pro = empty( $process_args['execute_with_pro'] ) ? $process_args['execute_with_pro'] : 0;
        $use_pro_data = !empty( $process_args['use_pro_data'] ) ? $process_args['use_pro_data'] : 0;

        $hook_order = $process_args['hook_order'];

        global $arformsmain, $wpdb, $arflitedbversion;

        if( $is_pro_check == 1 ){
            
            if( $arformsmain->arforms_is_premium_available() ){
                /** Process the data */
                update_option( 'arforms_process_' . $hook_name . '_start_timestamp', current_time('timestamp') );
                update_option( 'arforms_process_' . $hook_name . '_status', 1 );
                
                $form_table_old = $wpdb->prefix.'arflite_forms'; 
                $field_table_old = $wpdb->prefix.'arflite_fields'; 
                $entry_table_old = $wpdb->prefix.'arflite_entries'; 
                $entry_values_table_old = $wpdb->prefix.'arflite_entry_values';

                $check_table = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM information_schema.tables WHERE table_schema=%s AND table_name=%s",DB_NAME,$form_table_old));

	            if( 1 == $check_table ){
                    $wpdb->query( "CREATE TABLE {$form_table_old}_159_backup LIKE {$form_table_old}"); //phpcs:ignore
                    $wpdb->query( "INSERT INTO {$form_table_old}_159_backup SELECT * FROM {$form_table_old}"); //phpcs:ignore
                }
                
                $check_table = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM information_schema.tables WHERE table_schema=%s AND table_name=%s",DB_NAME,$field_table_old));
                if( 1 == $check_table ){
                    $wpdb->query( "CREATE TABLE {$field_table_old}_159_backup LIKE {$field_table_old}"); //phpcs:ignore
                    $wpdb->query( "INSERT INTO {$field_table_old}_159_backup SELECT * FROM {$field_table_old}"); //phpcs:ignore
                }
                
                $check_table = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM information_schema.tables WHERE table_schema=%s AND table_name=%s",DB_NAME,$entry_table_old));
                if( 1 == $check_table ){
                    $wpdb->query( "CREATE TABLE {$entry_table_old}_159_backup LIKE {$entry_table_old}"); //phpcs:ignore
                    $wpdb->query( "INSERT INTO {$entry_table_old}_159_backup SELECT * FROM {$entry_table_old}"); //phpcs:ignore
                }
                

                $check_table = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM information_schema.tables WHERE table_schema=%s AND table_name=%s",DB_NAME,$entry_values_table_old));
                if( 1 == $check_table){
                    $wpdb->query( "CREATE TABLE {$entry_values_table_old}_159_backup LIKE {$entry_values_table_old}"); //phpcs:ignore
                    $wpdb->query( "INSERT INTO {$entry_values_table_old}_159_backup SELECT * FROM {$entry_values_table_old}"); //phpcs:ignore
                }

                delete_option( 'arforms_use_legacy_tables' );

                update_option( 'arforms_process_' . $hook_name . '_status', 2 );
                update_option( 'arforms_process_' . $hook_name . '_end_timestamp', current_time('timestamp') );

            } else {
                /** Process the data */
                update_option( 'arforms_process_' . $hook_name . '_start_timestamp', current_time('timestamp') );
                update_option( 'arforms_process_' . $hook_name . '_status', 1 );

                global $arformsmain;

                $form_table_old = $wpdb->prefix.'arflite_forms'; 
                $field_table_old = $wpdb->prefix.'arflite_fields'; 
                $entry_table_old = $wpdb->prefix.'arflite_entries'; 
                $entry_values_table_old = $wpdb->prefix.'arflite_entry_values';

                /** Backup old tables */
                $wpdb->query( "CREATE TABLE {$form_table_old}_159_backup LIKE {$form_table_old}"); //phpcs:ignore
                $wpdb->query( "INSERT INTO {$form_table_old}_159_backup SELECT * FROM {$form_table_old}"); //phpcs:ignore
                
                $wpdb->query( "CREATE TABLE {$field_table_old}_159_backup LIKE {$field_table_old}"); //phpcs:ignore
                $wpdb->query( "INSERT INTO {$field_table_old}_159_backup SELECT * FROM {$field_table_old}"); //phpcs:ignore
                 
                $wpdb->query( "CREATE TABLE {$entry_table_old}_159_backup LIKE {$entry_table_old}"); //phpcs:ignore
                $wpdb->query( "INSERT INTO {$entry_table_old}_159_backup SELECT * FROM {$entry_table_old}"); //phpcs:ignore
                
                $wpdb->query( "CREATE TABLE {$entry_values_table_old}_159_backup LIKE {$entry_values_table_old}"); //phpcs:ignore
                $wpdb->query( "INSERT INTO {$entry_values_table_old}_159_backup SELECT * FROM {$entry_values_table_old}"); //phpcs:ignore

                $form_table_new = $wpdb->prefix .'arf_forms'; //phpcs:ignore
                $wpdb->query( "ALTER TABLE {$form_table_old} RENAME {$form_table_new}"); //phpcs:ignore
                
                $field_table_new = $wpdb->prefix . 'arf_fields'; //phpcs:ignore
                $wpdb->query( "ALTER TABLE {$field_table_old} RENAME {$field_table_new}"); //phpcs:ignore
                
                $entry_table_new = $wpdb->prefix . 'arf_entries'; //phpcs:ignore
                $wpdb->query( "ALTER TABLE {$entry_table_old} RENAME {$entry_table_new}"); //phpcs:ignore

                $entry_values_table_new = $wpdb->prefix . 'arf_entry_values'; //phpcs:ignore
                $wpdb->query( "ALTER TABLE {$entry_values_table_old} RENAME {$entry_values_table_new}"); //phpcs:ignore

                delete_option( 'arforms_use_legacy_tables' );
                
                update_option( 'arforms_process_' . $hook_name . '_status', 2 );
                update_option( 'arforms_process_' . $hook_name . '_end_timestamp', current_time('timestamp') );

            }

        }


    }



}
global $arforms_schedular;
$arforms_schedular =new arforms_schedular();