<?php
/**
 * Class SB_Facebook_Data_Manager
 *
 * @since 4.1
 */
namespace CustomFacebookFeed;
use CustomFacebookFeed\Builder\CFF_Db;
use CustomFacebookFeed\CFF_Resizer;
use CustomFacebookFeed\SB_Facebook_Data_Encryption;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SB_Facebook_Data_Manager {

	/**
	 * Key and salt to use for remote encryption.
	 *
	 * @var string
	 *
	 * @since 4.1
	 */
	private $key_salt;

	/**
	 * Start manager
	 *
	 * @since 4.1
	 */
	public function init() {
		$this->hooks();
	}


	/**
	 * Hook into certain features of the plugin and AJAX calls
	 *
	 * @since 4.1
	 */
	public function hooks() {
		add_action( 'cff_before_display_facebook', array( $this, 'update_last_used' ) );
		add_action( 'cff_before_display_facebook', array( $this, 'check' ) );
		add_action( 'sb_facebook_twicedaily', array( $this, 'maybe_delete_old_data' ) );
	}

	/**
	 * To avoid a database update every page load, the check
	 * is done once a day
	 *
	 * @since 4.1
	 */
	public function update_last_used() {
		$statuses = $this->get_statuses();

		// if this hasn't been updated in the last hour
		if ( $statuses['last_used'] < cff_get_current_time() - 3600 ) {
			// update the last used time
			$statuses['last_used'] = cff_get_current_time();

			$this->update_statuses( $statuses );
		}

	}

	/**
	 * Check for plain text instagram data in posts table
	 *
	 * @since 4.1
	 */
	public function check() {
		$this->encrypt_json_in_cff_facebook_posts();
	}

	/**
	 * Delete unused data after a period
	 *
	 * @return bool
	 *
	 * @since 4.1
	 */
	public function maybe_delete_old_data() {
		$statuses = $this->get_statuses();

		$data_was_deleted = false;
		do_action('cff_before_delete_old_data', $statuses);

		if ( $statuses['last_used'] < cff_get_current_time() - (21 * DAY_IN_SECONDS) ) {

			$this->delete_caches();
			\cff_main()->cff_error_reporter->add_action_log( 'Deleted all platform data.' );

			$data_was_deleted = true;
		}

		if ( $statuses['last_used'] < cff_get_current_time() - (90 * DAY_IN_SECONDS) ) {
			CFF_Db::clear_cff_sources();

			\cff_main()->cff_error_reporter->add_action_log( 'Deleted all connected accounts.' );

			$data_was_deleted = true;
		}

		return $data_was_deleted;
	}

	/**
	 * Delete feed caches
	 *
	 * @param bool $include_backup
	 *
	 * @since 2.9.4/5.12.4
	 */
	public function delete_caches( $include_backup = true ) {
		cff_delete_cache();
		CFF_Resizer::delete_resizing_table_and_images();
    	\cff_main()->cff_error_reporter->add_action_log( 'Reset resizing tables.' );
    	//CFF_Resizer::create_resizing_table_and_uploads_folder();
		CFF_Db::clear_cff_feed_caches();
		CFF_Db::clear_cff_sources();
		$this->delete_transient_backup_data( true );
	}

	/**
	 * Update all parts of the database for FB platform guidelines
	 *
	 * @throws Exception
	 *
	 * @since 4.1
	 */
	public function update_db_for_dpa() {
		global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
		$table_name_option = $wpdb->prefix . "options";
		$sources_table_name = $wpdb->prefix . "cff_sources";
		$wpdb->query( "ALTER TABLE $sources_table_name MODIFY access_token varchar(1000) NOT NULL default ''" );

		$this->encrypt_json_in_cff_facebook_posts();
		$this->encrypt_sources_access_token();
		$this->encrypt_cff_backup_cache();
		$this->encrypt_cff_group_cache();
		$this->remove_access_token_from_feeds();
		$this->delete_transient_backup_data();
		$this->encrypt_cff_legacy_feed();
		$this->encrypt_oembed();
	}


	/**
	 * Encrypt a set of 50 posts if this has been attempted
	 * less than 30 times.
	 *
	 * @since 4.1
	 */
	public function encrypt_json_in_cff_facebook_posts() {
		$statuses = $this->get_statuses();
		// if this hasn't been updated in the last hour
		if ( $statuses['num_db_updates'] > 30 ) {
			return;
		}

		$statuses['num_db_updates'] = $statuses['num_db_updates'] + 1;
		$this->update_statuses( $statuses );

		global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
		$table_name = $wpdb->prefix . CFF_POSTS_TABLE;
		$feeds_posts_table_name = esc_sql( $wpdb->prefix . CFF_FEEDS_POSTS_TABLE );

		$plaintext_posts = array();

		if ( empty( $plaintext_posts ) ) {
			$statuses['num_db_updates'] = 31;
			$this->update_statuses( $statuses );
		}

		foreach ( $plaintext_posts as $post ) {
			$json_data = $encryption->encrypt( $post['json_data'] );
			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE $table_name as p
					INNER JOIN $feeds_posts_table_name AS f ON p.id = f.id
					SET p.json_data = %s
					WHERE p.id = %d;", $json_data, $post['id'] )  );
		}
	}


	/**
	 * Encrypt sources Access tokens
	 *
	 * @since 4.1
	 */
	public function encrypt_sources_access_token() {
		global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
		$sources_table_name = $wpdb->prefix . 'cff_sources';

		$sources_list = $wpdb->get_results( "SELECT * FROM $sources_table_name;", ARRAY_A );
		foreach ( $sources_list as $source ) {
			$access_token = $encryption->maybe_encrypt( $source['access_token'] );
			$info = $encryption->maybe_encrypt( $source['info'] );

			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE $sources_table_name as s
					SET s.access_token = %s,
						s.info = %s,
						s.last_updated = %s
					WHERE s.id = %d;", $access_token, $info, date( 'Y-m-d H:i:s' ), $source['id'] )  );
		}
	}

	/**
	 * Encrypt a Backup Cache Data
	 *
	 * @since 4.1
	*/
	public function encrypt_cff_backup_cache() {
		global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
		$feed_cache_table_name = $wpdb->prefix . 'cff_feed_caches';


		$feed_caches = $wpdb->get_results(
			"SELECT * FROM $feed_cache_table_name as p
					WHERE p.cache_value LIKE '%{%';
				", ARRAY_A );

		if ( empty( $feed_caches ) ) {
			$statuses['num_db_updates'] = 31;
			$this->update_statuses( $statuses );
		}

		foreach ( $feed_caches as $cache ) {
			$cache_value = $encryption->encrypt( $cache['cache_value'] );
			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE $feed_cache_table_name as p
					SET p.cache_value = %s
					WHERE p.id = %d;", $cache_value, $cache['id'] )  );
		}
	}

	/**
	 * Update Group Posts Persistent Cache
	 *
	 * @throws Exception
	 *
	 * @since 4.1
	 */
	public function encrypt_cff_group_cache(){
	    global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
	    $table_name = $wpdb->prefix . "options";
	    $persistent_groups = $wpdb->get_results( "
	        SELECT *
	        FROM  $table_name
	        WHERE `option_name` LIKE ('%!cff\_group\_%')
	      " );

	    foreach ($persistent_groups as $group) {
			$cache_value = $encryption->maybe_encrypt( $group->option_value );
			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE $table_name as gp
					SET gp.option_value = %s
					WHERE gp.option_id = %d;", $cache_value, $group->option_id )  );

	    }
	}

	public function encrypt_oembed() {
		$cff_oembed_data = get_option( 'cff_oembed_token' );
		$sbi_oembed_data = get_option( 'sbi_oembed_token' );

		if ( empty( $cff_oembed_data['access_token'] ) && empty( $sbi_oembed_data['access_token'] ) ) {
			return;
		}

		$encryption = new SB_Facebook_Data_Encryption();
		if ( isset( $cff_oembed_data['access_token'] ) && ! $encryption->decrypt( $cff_oembed_data['access_token'] ) ) {
			$cff_oembed_data['access_token'] = $encryption->encrypt( $cff_oembed_data['access_token'] );
		}

		if ( isset( $sbi_oembed_data['access_token'] ) && ! $encryption->decrypt( $sbi_oembed_data['access_token'] ) ) {
			$sbi_oembed_data['access_token'] = $encryption->encrypt( $sbi_oembed_data['access_token'] );
		}

		update_option( 'cff_oembed_token', $cff_oembed_data );
		update_option( 'sbi_oembed_token', $sbi_oembed_data );
	}

	/**
	 * Update Group Posts Persistent Cache
	 *
	 * @throws Exception
	 *
	 * @since 4.1
	 */
	public function encrypt_cff_legacy_feed(){
	    global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
	    $table_name = $wpdb->prefix . "options";
	    $legacyfeed = $wpdb->get_results( "
	        SELECT *
	        FROM  $table_name
	        WHERE `option_name` LIKE 'cff_legacy_feed_settings'
	      " );

	    foreach ($legacyfeed as $legacy) {
			$cache_value = $encryption->maybe_encrypt( $legacy->option_value );
			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE $table_name as gp
					SET gp.option_value = %s
					WHERE gp.option_id = %d;", $cache_value, $legacy->option_id )  );

	    }
	}

	/**
	 * Update Feeds Table & Remove the Access Token from the Settings
	 *
	 * @throws Exception
	 *
	 * @since 4.1
	 */
	public function remove_access_token_from_feeds() {
		global $wpdb;
		$feeds_table_name = $wpdb->prefix . 'cff_feeds';
		$feeds_list = $wpdb->get_results(
			"SELECT * FROM $feeds_table_name", ARRAY_A );

		foreach ( $feeds_list as $feed ) {
			$settings = json_decode( $feed['settings'], true );
			unset($settings['accesstoken']);

			$settings = json_encode( $settings );

			$updated = $wpdb->query( $wpdb->prepare(
				"UPDATE $feeds_table_name as f
					SET f.settings = %s
					WHERE f.id = %d;", $settings, $feed['id'] )  );
		}
	}

	/**
	 * Data manager statuses
	 *
	 * @return array
	 *
	 * @since 4.1
	 */
	public function get_statuses() {
		$cff_statuses_option = get_option( 'cff_statuses', array() );

		$return = isset( $cff_statuses_option['data_manager'] ) ? $cff_statuses_option['data_manager'] : $this->defaults();
		return $return;
	}


	/**
	 * Delete Backup data
	 *
	 * @since 4.1
	 */
	public function delete_transient_backup_data( $processDeleteGroup = false ){
		global $wpdb;
		$table_name = $wpdb->prefix . "options";
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_cff\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_!cff\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_cff\_ej\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_cff\_tle\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_cff\_album\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_timeout\_cff\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%\_transient\_timeout\_!cff\_%')
	        " );
	    $wpdb->query( "
	        DELETE
	        FROM $table_name
	        WHERE `option_name` LIKE ('%cff\_backup\_%')
	        " );

	    if( $processDeleteGroup === true){
			$wpdb->query( "
		        DELETE
		        FROM $table_name
		        WHERE `option_name` LIKE ('%!cff\_group\_%')
		        " );
		    $wpdb->query( "
		        DELETE
		        FROM $table_name
		        WHERE `option_name` LIKE 'cff_connected_accounts'
		        " );
		    $wpdb->query( "
		        DELETE
		        FROM $table_name
		        WHERE `option_name` LIKE 'cff_access_token'
		        " );
		    $wpdb->query( "
		        DELETE
		        FROM $table_name
		        WHERE `option_name` LIKE 'cff_oembed_token'
		        " );
	    }
	}

	/**
	 * Update data manager status
	 *
	 * @param array $statuses
	 *
	 * @since 4.1
	 */
	public function update_statuses( $statuses ) {
		$cff_statuses_option = get_option( 'cff_statuses', array() );
		$cff_statuses_option['data_manager'] = $statuses;

		update_option( 'cff_statuses', $cff_statuses_option );
	}

	/**
	 * Reset the data manager
	 *
	 * @since 4.1
	 */
	public function reset() {
		$cff_statuses_option = get_option( 'cff_statuses', array() );
		$cff_statuses_option['data_manager'] = $this->defaults();

		update_option( 'cff_statuses', $cff_statuses_option );
	}

	/**
	 * Default values for manager
	 *
	 * @return array
	 *
	 * @since 4.1
	 */
	public function defaults() {
		return array(
			'last_used' => cff_get_current_time() - DAY_IN_SECONDS,
			'num_db_updates' => 0
		);
	}
}
