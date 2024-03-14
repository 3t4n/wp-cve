<?php
/*
//  ------------------------------------------------------------------------ //
//                     Document Management System                            //
//                   Written By:  Brian E. Reifsnyder                        //
//                                                                           //
//                See License.txt for copyright information.                 //
// ------------------------------------------------------------------------- //
*/




    function dms_install_db()
        {
        global $wpdb;
        //global $dms_db_version;


        //$db_prefix = $wpdb->prefix;
        $db_prefix = DMS_DB_PREFIX;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();
        add_option( 'dms_db_version', DMS_VERSION );

        // TABLE:  dms_auto_folder_creation
        $table_name = $db_prefix . 'dms_auto_folder_creation';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            parent_obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            group_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            user_perms tinyint(2) NOT NULL DEFAULT '0',
            group_perms tinyint(2) NOT NULL DEFAULT '0',
            everyone_perms tinyint(2) NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        // TABLE:  dms_config
        $table_name = $db_prefix . 'dms_config';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(30) NOT NULL default '',
            data varchar(255) NOT NULL default '',
            PRIMARY KEY  (id)
            ) $charset_collate;";

        dbDelta( $sql );

        // TABLE:  dms_object_properties_sb
        $table_name = $db_prefix . 'dms_object_properties_sb';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            property_num tinyint(2) UNSIGNED NOT NULL DEFAULT '99',
            disp_order tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
            select_box_option varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_file_sys_counters
        $table_name = $db_prefix . 'dms_file_sys_counters';

        $sql = "CREATE TABLE $table_name (
            row_id tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT,
            layer_1 bigint(5) UNSIGNED NOT NULL DEFAULT '1',
            layer_2 bigint(5) UNSIGNED NOT NULL DEFAULT '1',
            layer_3 bigint(5) UNSIGNED NOT NULL DEFAULT '1',
            file bigint(5) UNSIGNED NOT NULL DEFAULT '1',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_objects
        $table_name = $db_prefix . 'dms_objects';

        $sql = "CREATE TABLE $table_name (
            obj_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            ptr_obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            template_obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            obj_type tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
            obj_name varchar(255) NOT NULL DEFAULT '',
            obj_status tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
            obj_owner bigint(14) NOT NULL DEFAULT '0',
            obj_checked_out_user_id bigint(14) NOT NULL DEFAULT '0',
            current_version_row_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            lifecycle_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            lifecycle_stage bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            time_stamp_create varchar(12) NOT NULL DEFAULT '0',
            time_stamp_delete varchar(12) NOT NULL DEFAULT '0',
            time_stamp_expire varchar(12) NOT NULL DEFAULT '0',
            misc_text varchar(255) NOT NULL DEFAULT '',
            file_type varchar(100) NOT NULL DEFAULT 'unchecked',
            num_views smallint(8) NOT NULL DEFAULT '0',
            PRIMARY KEY  (obj_id),
            INDEX (obj_owner)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_object_perms
        $table_name = $db_prefix . 'dms_object_perms';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            ptr_obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            group_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            user_perms tinyint(2) NOT NULL DEFAULT '0',
            group_perms tinyint(2) NOT NULL DEFAULT '0',
            everyone_perms tinyint(2) NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id),
            INDEX (ptr_obj_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_object_versions
        $table_name = $db_prefix . 'dms_object_versions';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            major_version smallint(5) UNSIGNED NOT NULL DEFAULT '0',
            minor_version smallint(5) UNSIGNED NOT NULL DEFAULT '0',
            sub_minor_version smallint(5) UNSIGNED NOT NULL DEFAULT '0',
            init_version_flag tinyint(2) NOT NULL DEFAULT '0',
            file_path varchar(255) NOT NULL DEFAULT '',
            file_name varchar(255) NOT NULL DEFAULT '',
            file_type varchar(100) NOT NULL DEFAULT '',
            file_size varchar(10) NOT NULL DEFAULT '',
            time_stamp varchar(12) NOT NULL DEFAULT '0',
            file_location smallint(5) UNSIGNED NOT NULL DEFAULT '0',
            alt_file_location_path varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_object_version_comments
        $table_name = $db_prefix . 'dms_object_version_comments';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            dov_row_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            comment text,
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


    //         comment text,  # NOT NULL DEFAULT '',


        //  TABLE:  dms_object_properties
        $table_name = $db_prefix . 'dms_object_properties';

        $sql = "CREATE TABLE $table_name (
            obj_id bigint(14) unsigned NOT NULL,
            property_0 varchar(255) NOT NULL DEFAULT '',
            property_1 varchar(255) NOT NULL DEFAULT '',
            property_2 varchar(255) NOT NULL DEFAULT '',
            property_3 varchar(255) NOT NULL DEFAULT '',
            property_4 varchar(255) NOT NULL DEFAULT '',
            property_5 varchar(255) NOT NULL DEFAULT '',
            property_6 varchar(255) NOT NULL DEFAULT '',
            property_7 varchar(255) NOT NULL DEFAULT '',
            property_8 varchar(255) NOT NULL DEFAULT '',
            property_9 varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (obj_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_object_misc
        $table_name = $db_prefix . 'dms_object_misc';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            data_type tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
            data varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id),
            INDEX (obj_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_routing_data
        $table_name = $db_prefix . 'dms_routing_data';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            source_user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            time_stamp varchar(12) NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_exp_folders
        $table_name = $db_prefix . 'dms_exp_folders';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            folder_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_active_folder
        $table_name = $db_prefix . 'dms_active_folder';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            folder_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_lifecycles
        $table_name = $db_prefix . 'dms_lifecycles';

        $sql = "CREATE TABLE $table_name (
            lifecycle_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            obj_id bigint(14) unsigned NOT NULL DEFAULT '0',
            lifecycle_name varchar(255) NOT NULL DEFAULT '',
            lifecycle_descript varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (lifecycle_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_lifecycle_stages
        $table_name = $db_prefix . 'dms_lifecycle_stages';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            lifecycle_id bigint (14) UNSIGNED NOT NULL,
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            lifecycle_stage tinyint(2) UNSIGNED NOT NULL DEFAULT '0' ,
            new_obj_location bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            lifecycle_stage_name varchar(255) NOT NULL DEFAULT '',
            opt_obj_copy_location bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            flags smallint(8) NOT NULL DEFAULT '0',
            perms_group_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_audit_log
        $table_name = $db_prefix . 'dms_audit_log';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            time_stamp varchar(12) NOT NULL DEFAULT '0',
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            descript varchar(75) NOT NULL DEFAULT '',
            obj_name varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_subscriptions
        $table_name = $db_prefix . 'dms_subscriptions';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_groups
        $table_name = $db_prefix . 'dms_groups';

        $sql = "CREATE TABLE $table_name (
            group_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            group_name varchar(50) NOT NULL DEFAULT '',
            group_description TEXT,
            group_type varchar(10) NOT NULL DEFAULT 'PERMS',
            PRIMARY KEY  (group_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_groups_users_link
        $table_name = $db_prefix . 'dms_groups_users_link';

        $sql = "CREATE TABLE dms_groups_users_link (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            group_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_notify
        $table_name = $db_prefix . 'dms_notify';

        $sql = "CREATE TABLE $table_name (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            group_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );



        //  TABLE:  dms_user_doc_history
        $table_name = $db_prefix . 'dms_user_doc_history';

        $sql = "CREATE TABLE dms_user_doc_history (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            obj_id bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            time_stamp varchar(12) NOT NULL DEFAULT '0',
            obj_name varchar(30) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_user_prefs
        $table_name = $db_prefix . 'dms_user_prefs';

        $sql = "CREATE TABLE dms_user_prefs (
            row_id bigint(14) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(14) unsigned NOT NULL DEFAULT '0',
            pref_name varchar(30) NOT NULL DEFAULT '',
            data varchar(30) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );


        //  TABLE:  dms_help_system
        $table_name = $db_prefix . 'dms_help_system';

        $sql = "CREATE TABLE dms_help_system (
            row_id bigint(14) UNSIGNED NOT NULL AUTO_INCREMENT,
            help_id varchar(30) NOT NULL DEFAULT '',
            obj_id_ptr bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );

/*
        //  TABLE:  dms_job_services
        $table_name = $db_prefix . 'dms_job_services';

        $sql = "CREATE TABLE dms_job_services (
            row_id bigint(14) unsigned NOT NULL AUTO_INCREMENT,
            job_name varchar(50) NOT NULL DEFAULT '',
            job_type smallint(8) NOT NULL DEFAULT '0',
            next_run_time varchar(12) NOT NULL DEFAULT '0',
            flags smallint(8) NOT NULL DEFAULT '0',
            sched_day smallint(8) NOT NULL DEFAULT '0',
            sched_hour smallint(8) NOT NULL DEFAULT '0',
            sched_minute smallint(8) NOT NULL DEFAULT '0',
            obj_id_a bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            obj_id_b bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            obj_id_c bigint(14) UNSIGNED NOT NULL DEFAULT '0',
            text varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (row_id)
            ) $charset_collate;";

        dbDelta( $sql );
*/
        }
/*
}

$dms_install = new c_dms_install;
*/






function dms_install_data()
{
	global $wpdb;
	//global $dms_db_version;

    $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM " . DMS_DB_PREFIX . "dms_config");
    if ($num_rows > 0) return;

    //  TABLE:  dms_config
	$table_name = DMS_DB_PREFIX . 'dms_config';

	$wpdb->insert(
		$table_name,
		array(
			'name' => "version",
			'data' => DMS_VERSION
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "time_stamp",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "admin_only_perms",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "auto_folder_creation",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "doc_path",
			'data' => ABSPATH . "wp-content/uploads/dms_repository"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "init_config_lock",
			'data' => "unlocked"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "dms_title",
			'data' => "Document Management System"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "disp_num_views",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "admin_display",
			'data' => "1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "default_interface",
			'data' => "2"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "frame_width",
			'data' => "100%"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "frame_height",
			'data' => "550"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "max_file_sys_counter",
			'data' => "1000"
		)
	);
/*
	$wpdb->insert(
		$table_name,
		array(
			'name' => "adn_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "adn_mask",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "adn_mask_char",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "adn_prop_field",
			'data' => "-1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "adv_enable",
			'data' => "1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "adv_mask",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "adv_mask_char",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "extern_doc_access",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "pdftk_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "pdftk_path",
			'data' => ""
		)
	);
*/
	$wpdb->insert(
		$table_name,
		array(
			'name' => "full_text_search",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "full_text_search_cdo",
			'data' => "0"
		)
	);




	$wpdb->insert(
		$table_name,
		array(
			'name' => "global_thumbnail_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "global_thumbnail_width",
			'data' => "75"
		)
	);






	$wpdb->insert(
		$table_name,
		array(
			'name' => "search_limit",
			'data' => "100"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "search_summary_flag",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "search_summary_c_before",
			'data' => "100"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "search_summary_c_after",
			'data' => "300"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "search_results_per_page",
			'data' => "10"
		)
	);
/*
	$wpdb->insert(
		$table_name,
		array(
			'name' => "swish-e path",
			'data' => ""
		)
	);
*/
	$wpdb->insert(
		$table_name,
		array(
			'name' => "template_root_obj_id",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "updates_root_obj_id",
			'data' => "0"
		)
	);


	$wpdb->insert(
		$table_name,
		array(
			'name' => "routing_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "routing_auto_inbox",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "routing_email_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "routing_email_subject",
			'data' => "A document has been routed to your DMS inbox"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "routing_email_from",
			'data' => ""
		)
	);


	$wpdb->insert(
		$table_name,
		array(
			'name' => "document_email_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "document_email_subject",
			'data' => "A document has been sent to you from the DMS"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "document_email_from",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "sub_email_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "sub_email_subject",
			'data' => "A document has been accessed in the DMS."
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "sub_email_from",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "notify_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "admin_only_manage_notify",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "notify_email_subject",
			'data' => "DMS Notification"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "notify_email_from",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_0_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_1_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_2_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_3_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_4_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_5_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_6_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_7_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_8_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "property_9_name",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "class_content",
			'data' => "dms_content"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "class_header",
			'data' => "dms_header"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "class_subheader",
			'data' => "dms_subheader"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "class_narrow_header",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "class_narrow_content",
			'data' => ""
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "purge_enable",
			'data' => "1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "purge_level",
			'data' => "2"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "purge_delay",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "purge_limit",
			'data' => "2"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "doc_display_limit",
			'data' => "100"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "misc_text_disp_template",
			'data' => "1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "misc_text_disp_lc_stage",
			'data' => "1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "inherit_perms",
			'data' => "0"
		)
	);


	$wpdb->insert(
		$table_name,
		array(
			'name' => "everyone_perms",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "group_source",
			'data' => "PORTAL"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "lifecycle_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "lifecycle_name_preserve",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "lifecycle_del_previous",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "lifecycle_alpha_move",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "comments_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "comments_main_screen",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "checkinout_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "prop_perms_enable",
			'data' => "1"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "doc_name_sync",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "doc_hist_block_rows",
			'data' => "10"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "OS",
			'data' => "unknown"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "write_job_server_config",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "doc_expiration_enable",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "wordpress_page",
			'data' => "0"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "pi_upload_max_filesize",
			'data' => "128M"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "pi_post_max_size",
			'data' => "128M"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "pi_max_et",
			'data' => "300"
		)
	);

	$wpdb->insert(
		$table_name,
		array(
			'name' => "root_folder",
			'data' => "0"
		)
	);





    //  TABLE:  dms_file_sys_counters
    $table_name = DMS_DB_PREFIX . "dms_file_sys_counters";
	$wpdb->insert(
		$table_name,
		array(
			'layer_1' => 1,
			'layer_2' => 1,
			'layer_3' => 1,
			'file' => 1
            )
        );
}







?>
