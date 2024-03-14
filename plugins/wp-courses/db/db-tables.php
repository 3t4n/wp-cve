<?php 


	/********** TRACKING **********/



	function wpc_create_tracking_table() {

	    global $wpdb;
	    add_option('wpc_tracking_table_version', "1.0");
	    $table_name = $wpdb->prefix . 'wpc_tracking';
	    $charset_collate = $wpdb->get_charset_collate();

	    $sql = "CREATE TABLE $table_name (
	        id bigint(20) NOT NULL AUTO_INCREMENT,
	        user_id bigint(20),
	        post_id bigint(20) NOT NULL,
	        course_id bigint(20),
	        viewed_timestamp bigint(20),
	       	completed_timestamp bigint(20),
	        completed tinyint(1),
	        primary key (id)
	    ) $charset_collate;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );

	    // ports old usermeta to new tracking table
	    wpc_port_postmeta_tracking_to_table();
	}

	register_activation_hook( __FILE__, 'wpc_create_tracking_table');

	function wpc_update_db_tracking_table_check() {
	    $ver = get_site_option( "wpc_tracking_table_version");
	    if($ver != "1.0") {
	        wpc_create_tracking_table();
	    }
	}

	add_action( 'plugins_loaded', 'wpc_update_db_tracking_table_check' );



	/********** CONNECTIONS **********/



	function wpc_create_connections_table() {
		global $wpdb;
		add_option( "wpc_connections_table_version", "1.0");
		$table_name = $wpdb->prefix . "wpc_connections";
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			post_from bigint(20),
			post_to bigint(20),
			connection_type varchar(255),
			menu_order int(11),
			PRIMARY KEY (id)
		) $charset_collate";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );

	    wpc_port_postmeta_to_connections_table();
	}
	register_activation_hook( __FILE__, 'wpc_create_connections_table' );

	function wpc_update_connections_table_check() {
	    $ver = get_site_option( "wpc_connections_table_version" );
	    if ( $ver != "1.0" ) {
	        wpc_create_connections_table();
	    }
	}
	add_action( 'init', 'wpc_update_connections_table_check' );



	/********** REQUIREMENTS **********/



	function wpc_create_requirements_table() {
	    global $wpdb;
	    add_option( "wpc_db_version", "1.0" );
	    $table_name = $wpdb->prefix . 'wpc_rules';
	    $charset_collate = $wpdb->get_charset_collate();

	    $sql = "CREATE TABLE $table_name (
	        id bigint(20) NOT NULL AUTO_INCREMENT,
	        post_id bigint(20) NOT NULL,
	        course_id bigint(20),
	        lesson_id bigint(20),
	        module_id bigint(20),
	        action varchar(255),
	        type varchar(255),
	        percent TINYINT,
	        times bigint(20),
	        PRIMARY KEY  (id)
	    ) $charset_collate;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );
	}
	register_activation_hook( __FILE__, 'wpc_create_requirements_table' );

	function wpc_update_db_check() {
	    $ver = get_site_option( "wpc_db_version" );
	    if ( $ver != "1.0" ) {
	        wpc_create_requirements_table();
	    }
	}
	add_action( 'plugins_loaded', 'wpc_update_db_check' );




	/********** QUIZZES **********/



	function wpcq_install() {
		global $wpdb;
	    add_option('wpc_quiz_table_version', "1.1");
		$table_name = $wpdb->prefix . 'wpc_quiz_results';
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			user_ID bigint(20) NOT NULL,
			quiz_ID bigint(20) NOT NULL,
			quiz_result text NOT NULL,
			score_percent mediumint(3) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	register_activation_hook( __FILE__, 'wpcq_install' );

	function wpcq_update_quiz_table_check() {
	    $ver = get_site_option( "wpc_quiz_table_version");
	    if($ver != "1.1") {
	    	update_option('wpc_quiz_table_version', "1.1");
	        wpcq_install();
	        wpcq_course_id_column();
	    }
	}

	add_action( 'plugins_loaded', 'wpcq_update_quiz_table_check' );

	function wpcq_course_id_column() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpc_quiz_results';
		$wpdb->query("ALTER TABLE $table_name ADD course_id bigint(20)");
	}
?>