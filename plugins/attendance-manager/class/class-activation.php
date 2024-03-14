<?php
/**
 *	Activation
 */
class ATTMGR_Activation {
	/** 
	 *	Load
	 */
	public static function load() {
		$mypluginurl  = dirname( plugin_dir_url( __FILE__ ) ).'/';
		$mypluginpath = dirname( plugin_dir_path( __FILE__ ) ).'/';
		$mypluginfile = $mypluginpath.ATTMGR::PLUGIN_FILE;

		add_filter( 'attmgr_schedule_table_name', array( 'ATTMGR_Activation', 'schedule_table' ) );
		register_activation_hook( $mypluginfile, array( 'ATTMGR_Activation', 'activation' ) );
		register_deactivation_hook( $mypluginfile, array( 'ATTMGR_Activation', 'deactivation' ) );
		register_uninstall_hook( $mypluginfile, array( 'ATTMGR_Activation', 'uninstall' ) );
	}

	/** 
	 *	Schedule table name
	 */
	public static function schedule_table( $table ) {
		global $wpdb;
		return $wpdb->prefix.ATTMGR::TABLEPREFIX.'schedule';
	}

	/** 
	 *	Activation
	 */
	public static function activation() {
		self::create_table();
		self::insert_specialpages();
	}

	/** 
	 *	Deactivation
	 */
	public static function deactivation() {
		wp_clear_scheduled_hook( ATTMGR::PLUGIN_ID.'_cron' );
	}

	/** 
	 *	Uninstall
	 */
	public static function uninstall() {
		global $wpdb;
		delete_metadata( 'user', 0, ATTMGR::PLUGIN_ID.'_ex_attr_staff', '', true );
		delete_metadata( 'user', 0, ATTMGR::PLUGIN_ID.'_mypage_id', '', true );
		delete_option( ATTMGR::PLUGIN_ID );
		delete_option( ATTMGR::PLUGIN_ID.'_version' );
	}

	/** 
	 *	Create table
	 */
	public static function create_table() {
		global $wpdb;

		$version = get_option( ATTMGR::PLUGIN_ID.'_version' );

		// CREATE TABLE
		$table = '';
		$table = apply_filters( 'attmgr_schedule_table_name', $table );
		if ( $table != $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) ) ) {
			require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
			$sql = <<<EOD
CREATE TABLE IF NOT EXISTS {$table} (
date date NOT NULL COMMENT 'Date',
starttime time DEFAULT NULL COMMENT 'Start time',
endtime time DEFAULT NULL COMMENT 'End time',
staff_id int NOT NULL COMMENT 'Staff ID',
absence int NOT NULL COMMENT 'Absence',
lateness time DEFAULT NULL COMMENT 'Lateness',
PRIMARY KEY (`date`,`staff_id`)
);
EOD;
			dbDelta( $sql );
			$version['db'] = ATTMGR::DB_VERSION;
		}
		$version['plugin'] = ATTMGR::PLUGIN_VERSION;
		update_option( ATTMGR::PLUGIN_ID.'_version', $version );
		return;
	}

	/** 
	 *	Create table
	 */
	public static function insert_specialpages() {
		$default_option = ATTMGR::default_option();
		$specialpages = $default_option['specialpages'];
		$param = array(
			'pages' => array(
				'staff_scheduler' => array(
					'post_title'     => __( 'Scheduler for staff', ATTMGR::TEXTDOMAIN ),
					'post_content'   => '[attmgr_staff_scheduler]',
					'post_name'      => $specialpages['staff_scheduler'],
					'post_status'   => 'publish',
				),
				'admin_scheduler' => array(
					'post_title'     => __( 'Scheduler for admin', ATTMGR::TEXTDOMAIN ),
					'post_content'   => '[attmgr_admin_scheduler]',
					'post_name'      => $specialpages['admin_scheduler'],
					'post_status'   => 'publish',
				),
				'daily_schedule' => array(
					'post_title'     => __( 'Today&#39;s staff', ATTMGR::TEXTDOMAIN ),
					'post_content'   => '[attmgr_daily guide="1week"]',
					'post_name'      => 'daily',
					'post_status'   => 'publish',
				),
				'weekly_schedule' => array(
					'post_title'     => __( 'Weekly schedule', ATTMGR::TEXTDOMAIN ),
					'post_content'   => '[attmgr_weekly_all]',
					'post_name'      => 'weekly',
					'post_status'   => 'publish',
				),
				'monthly_schedule' => array(
					'post_title'     => __( 'Monthly schedule', ATTMGR::TEXTDOMAIN ),
					'post_content'   => '[attmgr_monthly_all]',
					'post_name'      => 'monthly',
					'post_status'   => 'publish',
				),
			),
			'child_pages'  => array(

			)
		);
		self::insert_pages( $param );
	}

	/** 
	 *	Insert pages
	 */
	public static function insert_pages( $param ) {
		extract( $param );	// $pages, $child_pages
		foreach ( $pages as $name => $page ) {
			$p = get_page_by_path( $page['post_name'] );
			if ( empty( $p ) ) {
				$args = array_merge(
					$page, 
					array(
						'post_type'      => 'page',
						'comment_status' => 'closed',
						'ping_status'    => 'closed'
					)
				);
				$parent_id = wp_insert_post( $args );
			} else {
				$parent_id = $p->ID;
			}
			if ( isset( $child_pages[ $name ] ) ) {
				foreach ( $child_pages[ $name ] as $child ) {
					$c = get_page_by_path( $page['post_name'].'/'.$child['post_name'] );
					if ( empty( $c ) ) {
						$args = array_merge(
							$child, 
							array(
								'post_parent'    => $parent_id,
								'post_type'      => 'page',
								'comment_status' => 'closed',
								'ping_status'    => 'closed'
							)
						);
						$child_id = wp_insert_post( $args );
					}
				}
			}
		}
	}
}
?>
