<?php

/**
 * Fired during plugin activation
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.3
 * @package    Courtres
 * @subpackage Courtres/includes
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres_Activator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.3
	 */
	public static function activate() {
		// create role and capabilities
		$cap = 'place_reservation';
		add_role(
			'player',
			__( 'Player', 'court-reservation' ),
			array( $cap => true )
		);

		$role = get_role( 'administrator' );
		$role->add_cap( $cap, true );

		// create tables
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		// courts table
		$table_courts = $wpdb->prefix . 'courtres_courts';
		// 23.05.2019, astoian - check if tables existe, then do not creates
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_courts ) ) === $table_courts ) {
			error_log( 'Table exists' . ': ' . print_r( $table_courts, true ) );
		} else {
			$sql = "CREATE TABLE $table_courts (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                open smallint(2) NOT NULL CHECK (open<=23),
                close smallint(2) NOT NULL CHECK (close<=23),
                days smallint(1) NOT NULL CHECK (days>0),
                CHECK (open<close),
                UNIQUE KEY id (id)
            ) $charset_collate;";
			dbDelta( $sql );
		}

		// reservations table
		$table_name = $wpdb->prefix . 'courtres_reservations';
		// 23.05.2019, astoian - check if tables existe, then do not creates
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) {
			error_log( 'Table exists' . ': ' . print_r( $table_name, true ) );
		} else {
			$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            courtid mediumint(9) NOT NULL,
            type varchar(63) NOT NULL,
            userid mediumint(9) NOT NULL,
            partnerid mediumint(9),
                  partnerid2 mediumint(9),
                  partnerid3 mediumint(9),
            date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            time smallint(2) NOT NULL,
                  minute smallint(2) DEFAULT 0 NOT NULL,
                  gid varchar(50) NULL DEFAULT '',
            FOREIGN KEY (courtid) REFERENCES {$table_courts}(id) ON DELETE CASCADE,
            UNIQUE KEY id (id)
          ) $charset_collate;";
			dbDelta( $sql );
		}

		// events table
		$table_name = $wpdb->prefix . 'courtres_events';
		// 23.05.2019, astoian - check if tables exists, then do not creates
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) {
			error_log( 'Table exists' . ': ' . print_r( $table_name, true ) );
			$res = $wpdb->get_results( "SHOW COLUMNS FROM $table_name", OBJECT_K );
			if ( ! array_key_exists( 'event_date', $res ) ) {
				$wpdb->query( "ALTER TABLE $table_name ADD event_date date NULL DEFAULT NULL" );
				error_log( 'Added new column event_date in table ' . print_r( $table_name, true ) );
			};
			if ( ! array_key_exists( 'weekly_repeat', $res ) ) {
				$wpdb->query( "ALTER TABLE $table_name ADD weekly_repeat boolean NULL DEFAULT NULL" );
				error_log( 'Added new column weekly_repeat in table ' . print_r( $table_name, true ) );
			};
			// from 1.5.0 >
			if ( ! array_key_exists( 'start_ts', $res ) ) {
				$wpdb->query( "ALTER TABLE $table_name ADD start_ts bigint unsigned" );
				error_log( 'Added new column start_ts in table ' . print_r( $table_name, true ) );
			};
			if ( ! array_key_exists( 'end_ts', $res ) ) {
				$wpdb->query( "ALTER TABLE $table_name ADD end_ts bigint unsigned" );
				error_log( 'Added new column end_ts in table ' . print_r( $table_name, true ) );
			};
			if ( ! array_key_exists( 'type', $res ) ) {
				$wpdb->query( "ALTER TABLE $table_name ADD type varchar(56)" );
				error_log( 'Added new column type in table ' . print_r( $table_name, true ) );
			};
			// < from 1.5.0
		} else {
			$sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            courtid mediumint(9) NOT NULL,
            name varchar(255) NOT NULL,
            dow smallint(1) NOT NULL CHECK(dow<8),
            event_date date NULL DEFAULT NULL,
            weekly_repeat boolean NULL DEFAULT NULL,
            start smallint(2) NOT NULL CHECK (start<=23),
            end smallint(2) NOT NULL CHECK (end<=23),
            repeatone datetime NULL DEFAULT NULL,
            start_ts bigint unsigned,
            end_ts bigint unsigned,
            FOREIGN KEY (courtid) REFERENCES {$table_courts}(id) ON DELETE CASCADE,
            UNIQUE KEY id (id)
          ) $charset_collate;";
			dbDelta( $sql );
		}

		// settings table
		$table_name = $wpdb->prefix . 'courtres_settings';
		// 23.05.2019, astoian - check if tables existe, then do not creates
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) === $table_name ) {
			error_log( 'Table exists' . ': ' . print_r( $table_name, true ) );
		} else {
			$sql = "CREATE TABLE $table_name (
                 option_id mediumint(9) NOT NULL AUTO_INCREMENT,
                 option_name varchar(255) NOT NULL,
                 option_value longtext DEFAULT '',
                 UNIQUE KEY option_id (option_id)
             ) $charset_collate;";
			dbDelta( $sql );
		}

		// from 1.5.0 >
		// create reservation to players table
		$table_reserv_players = $wpdb->prefix . 'courtres_reserv_players';
		$table_reserv         = $wpdb->prefix . 'courtres_reservations';
		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_reserv_players ) ) === $table_reserv_players ) {
			error_log( 'Table exists' . ': ' . print_r( $table_reserv_players, true ) );
		} else {
			$sql = "CREATE TABLE $table_reserv_players (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                reservation_gid VARCHAR(50) NOT NULL,
                player_id BIGINT(20) UNSIGNED NOT NULL,
                is_author BOOLEAN DEFAULT 0
            ) $charset_collate;";
			dbDelta( $sql );
		}

		/*
		  Piramid Feature */
		// create piramids table
		Courtres_Entity_Piramid::create_table();
		Courtres_Entity_Piramid::add_dbtable_column(
			array(
				'name'  => 'design',
				'type'  => 'text',
				'after' => 'modified_dt',
			)
		);

		// create piramids-players relations table
		Courtres_Entity_Piramids_Players::create_table();

		// create piramid-challenges relations table
		Courtres_Entity_Challenges::create_table();

		// < from 1.5.0
	}

	/**
	 * set defaulst if not set
	 *
	 * @since    1.4.1
	 */
	public static function defaults() {
		// db - tables
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

				$table_name_courtres_events = $wpdb->prefix . 'courtres_events';
		$table_name_courtres_settings       = $wpdb->prefix . 'courtres_settings';
		$table_reserv_players               = $wpdb->prefix . 'courtres_reserv_players';
		$table_reserv                       = $wpdb->prefix . 'courtres_reservations';

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		if ( ! defined( 'WP_FS_POSSIBLE_RESERVATIONS_TYPES' ) ) {
			define(
				'WP_FS_POSSIBLE_RESERVATIONS_TYPES',
				array(
					__( 'Single', 'court-reservation' ),
					__( 'Double', 'court-reservation' ),
					__( 'Championship', 'court-reservation' ),
					__( 'Training', 'court-reservation' ),
					__( 'Competition', 'court-reservation' ),
				)
			);
		}

		// get version
		$option_courtres_version = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'courtres_version'" );

		// set version
		// note that there are no version before 1.4.1
		if ( ! isset( $option_courtres_version ) ) {
			$is_version_before_1_4_1 = true;
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'courtres_version',
					'option_value' => '1.5.1',
				),
				array( '%s', '%s' )
			);
		} else {
			$is_version_before_1_4_1 = false;
			$res                     = $wpdb->update(
				$table_name_courtres_settings,
				array(
					'option_value' => '1.5.1',
				),
				array( 'option_name' => 'courtres_version' ),
				array(
					'%s',
				)
			);
		}

		// get version after updating (creating)
		$option_courtres_version = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'courtres_version'" );

		// version_compare($option_courtres_version->option_value, '1.4.1', '<')
		// there are no version before 1.4.1
		if ( $is_version_before_1_4_1 ) {
			// set additional defaults if the version is not the same
			// events table alter if version is not the same
			// 23.05.2019, astoian - check if tables existe, then do not creates
			if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name_courtres_events ) ) === $table_name_courtres_events ) {
				error_log( 'Table exists' . ': ' . print_r( $table_name_courtres_events, true ) );
				// +RA 2020-05-09, add 'event_date' column if not exists
				$res = $wpdb->get_results( "SHOW COLUMNS FROM $table_name_courtres_events", OBJECT_K );
				if ( ! array_key_exists( 'event_date', $res ) ) {
					$wpdb->query( "ALTER TABLE $table_name_courtres_events ADD event_date date NULL DEFAULT NULL" );
					error_log( 'Added new column event_date in table ' . print_r( $table_name_courtres_events, true ) );
				};
				if ( ! array_key_exists( 'weekly_repeat', $res ) ) {
					$wpdb->query( "ALTER TABLE $table_name_courtres_events ADD weekly_repeat boolean NULL DEFAULT NULL" );
					error_log( 'Added new column weekly_repeat in table ' . print_r( $table_name_courtres_events, true ) );
				};
				if ( ! array_key_exists( 'start_ts', $res ) ) {
					$wpdb->query( "ALTER TABLE $table_name_courtres_events ADD start_ts bigint unsigned" );
					error_log( 'Added new column start_ts in table ' . print_r( $table_name_courtres_events, true ) );
				};
				if ( ! array_key_exists( 'end_ts', $res ) ) {
					$wpdb->query( "ALTER TABLE $table_name_courtres_events ADD end_ts bigint unsigned" );
					error_log( 'Added new column end_ts in table ' . print_r( $table_name_courtres_events, true ) );
				};
				if ( ! array_key_exists( 'type', $res ) ) {
					$wpdb->query( "ALTER TABLE $table_name_courtres_events ADD type varchar(56)" );
					error_log( 'Added new column type in table ' . print_r( $table_name_courtres_events, true ) );
				};
								$events = $wpdb->get_results( "SELECT * FROM $table_name_courtres_events as events" );
				for ( $i = 0; $i < sizeof( $events ); $i++ ) {
					$event = $events[ $i ];
					if ( $event->repeatone === null ) {
						$last_sundy = date( 'Y-m-d', strtotime( 'last sunday' ) );
						// $plus_days = (int) ($event->dow) - 1;
						$start_date = strtotime( $last_sundy . '+ ' . $event->dow . ' days' );
						$res        = $wpdb->update(
							$table_name_courtres_events,
							array(
								'event_date'    => date_i18n( 'Y-m-d', $start_date ),
								'weekly_repeat' => 1,
							),
							array( 'id' => (int) $event->id ),
							array(
								'%s',
								'%d',
							)
						);
						error_log(
							$res === false ? 'Updating error - repeatone===NULL.' : 'Successfully changed! repeatone===NULL'
						);
					} else {
						$repeatone = strtotime( $event->repeatone );
						$res       = $wpdb->update(
							$table_name_courtres_events,
							array(
								'event_date'    => date_i18n( 'Y-m-d', $repeatone ),
								'weekly_repeat' => 0,
							),
							array( 'id' => (int) $event->id ),
							array(
								'%s',
								'%d',
							)
						);
						error_log(
							$res === false ? 'Updating error - repeatone===1.' : 'Successfully changed! repeatone===1'
						);
					}
				}
			}
		}

		$option_several_reserve_person = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'several_reserve_person'" );
		if ( ! isset( $option_several_reserve_person ) ) {
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'several_reserve_person',
					'option_value' => '1',
				),
				array( '%s', '%s' )
			);
		}

		$cr_option_dateformats = "d.m. = German\r\nm.d. = USA";
		$cr_option_dateformat  = 'd.m.';

		// error_log('cr_possible_reservations_types: ' . print_r(WP_FS_POSSIBLE_RESERVATIONS_TYPES, true));
		// error_log('single: ' .get_locale() . " - " . __('Single', 'court-reservation'));

		// List of possible reservations types
		$option_reservation_types = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'reservation_types'" );
		if ( ! isset( $option_reservation_types ) ) {
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'reservation_types',
					'option_value' => serialize( WP_FS_POSSIBLE_RESERVATIONS_TYPES ),
				),
				array( '%s', '%s' )
			);
		}

		// available_reservation_types list
		$option_available_reservation_types = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'available_reservation_types'" );
		if ( ! isset( $option_available_reservation_types ) ) {
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'available_reservation_types',
					'option_value' => serialize( WP_FS_POSSIBLE_RESERVATIONS_TYPES ),
				),
				array( '%s', '%s' )
			);
		}

		// List of dateformats
		$option_dateformats = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'option_dateformats'" );
		if ( ! isset( $option_dateformats ) ) {
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'option_dateformats',
					'option_value' => $cr_option_dateformats,
				),
				array( '%s', '%s' )
			);
		}

		// dateformat default
		$option_dateformat = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'option_dateformat'" );
		if ( ! isset( $option_dateformat ) ) {
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'option_dateformat',
					'option_value' => $cr_option_dateformat,
				),
				array( '%s', '%s' )
			);
		}

		$option_several_reserve_person = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'several_reserve_person'" );
		if ( ! isset( $option_several_reserve_person ) ) {
			$wpdb->insert(
				$table_name_courtres_settings,
				array(
					'option_name'  => 'several_reserve_person',
					'option_value' => '1',
				),
				array( '%s', '%s' )
			);
		}

		// Changes in v1.5.0 >
		if ( $option_courtres_version->option_value == '1.5.1' ) {

			$check_name = 'm0b18_courtres_events_chk_4';
			$res        = $wpdb->query( sprintf( "SELECT * FROM information_schema.table_constraints WHERE `TABLE_NAME`='%s' AND `CONSTRAINT_TYPE`='CHECK' AND `CONSTRAINT_NAME`='%s'", $table_name_courtres_events, $check_name ) );
			if ( $res ) {
				$wpdb->query( "ALTER TABLE $table_name_courtres_events DROP CHECK $check_name" );
				error_log( 'v1.5.1 > CHECK (start<end) in table ' . print_r( $table_name_courtres_events, true ) . ' dropped' );
			}

			// create reservation to players table
			if ( ! ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_reserv_players ) ) === $table_reserv_players ) ) {
				$sql = "CREATE TABLE $table_reserv_players (
                    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    reservation_gid VARCHAR(50) NOT NULL,
                    player_id BIGINT(20) UNSIGNED NOT NULL,
                    is_author BOOLEAN DEFAULT 0
                ) $charset_collate;";
				dbDelta( $sql );
				error_log( 'v1.5.1 > Table created' . ': ' . print_r( $table_reserv_players, true ) );
			}

			// change reservation notifying
			$email_templates_default_settings = Courtres::get_default_settings( 'email_template' );
			$option_email_template            = $wpdb->get_row( "SELECT * FROM {$table_name_courtres_settings} WHERE `option_name` = 'option_email_template'" );
			$option_is_email_template_updated = $wpdb->get_row( "SELECT * FROM {$table_name_courtres_settings} WHERE `option_name` = 'option_is_email_template_updated'" );

			if ( isset( $option_email_template ) && ! $option_is_email_template_updated ) {
				add_action(
					'admin_notices',
					function() {
						// show notice globally exclude Court Reservation Plugin Setting tab
						// because in the Court Reservation Plugin Setting tab is used own notice that can disappear after saving
						global $pagenow;
						if ( ! ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == 'courtres' && isset( $_GET['tab'] ) && $_GET['tab'] == '1' ) ) { ?>
							<div id="message" class="notice notice-warning is-dismissible">
								<p>
								<?php echo esc_html__( 'Court Reservation Plugin: You need to update ', 'courtres' ); ?>
					<a href="<?php echo esc_url(admin_url( 'admin.php?page=courtres&tab=1' )); ?>">
									<?php echo esc_html__( 'your template for E-Mail notifications', 'court-reservation' ); ?>
					</a>.
								</p>
			</div> 
							<?php
						}
					}
				);
			}

			// migrate partnerid, partnerid2, partnerid3 columns from courtres_reservations table to new courtres_reserv_players table
			$option_is_partners_migrated = $wpdb->get_row( "SELECT * FROM $table_name_courtres_settings WHERE option_name = 'option_is_partners_migrated'" );
			if ( ! isset( $option_is_partners_migrated ) || ! $option_is_partners_migrated ) {
				Courtres_Base::migrate_partners();
				$wpdb->insert(
					$table_name_courtres_settings,
					array(
						'option_name'  => 'option_is_partners_migrated',
						'option_value' => true,
					),
					array( '%s', '%d' )
				);
				error_log( 'v1.5.1 > Partners from old reservations are migrated' );
			}
		}
		// < Changes in v1.5.0
	}

}
