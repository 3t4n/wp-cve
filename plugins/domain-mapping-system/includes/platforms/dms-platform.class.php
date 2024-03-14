<?php

/**
 * Abstract platform related class.
 * Mainly designed to show rules for all the platforms
 *
 * @since 1.7
 */
abstract class DMS_Platform {

	/**
	 * Construct method
	 *
	 * @param  DMS  $DMS
	 */
	abstract public function __construct( $DMS );

	/**
	 * Check weather it is allowed to save mapping from the platform
	 *
	 * @return bool
	 */
	abstract public function allowMappingSave();

	/**
	 * Get platform name
	 *
	 * @return mixed
	 */
	abstract public function getName();

	/**
	 * Check weather save of the mapping could be processed
	 *
	 * @return mixed
	 */
	abstract public function isAllowedToSaveMapping();

	/**
	 * Get result/error messages related to platform
	 *
	 * @return mixed
	 */
	abstract public function getMessages();

	/**
	 * Add domain through platform
	 *
	 * @param $domain
	 *
	 * @return mixed
	 */
	abstract public function addDomain( $domain );

	/**
	 * Delete domain through platform
	 *
	 * @param $domain
	 *
	 * @return mixed
	 */
	abstract public function deleteDomain( $domain );

	/**
	 * Delete domains through platform
	 *
	 * @param $domains
	 *
	 * @return mixed
	 */
	abstract public function deleteDomains( $domains );

	/**
	 * Get platform form html
	 *
	 * @return void
	 */
	public function drawForm() {
		?>
		<?php
	}

	/**
	 * Get platform form html
	 *
	 * @return void
	 */
	public function printGeneralNotice() {
		?>
		<?php
	}

	/**
	 * Detect weather the mapping form can be accessible
	 *
	 * @return bool
	 */
	public function showMappingForm() {
		return true;
	}

	/**
	 * Decide weather to show config form
	 *
	 * @return bool
	 */
	public function showConfigForm() {
		return true;
	}

	/**
	 * Show navigation
	 *
	 * @return bool
	 */
	public function showNavigation() {
		return true;
	}

	/**
	 * Save external domains in our side
	 * 
	 * @param array $domains
	 */
	public function saveExternalDomains( $domains ) {
		$wpdb          = $this->dms->wpdb;
		// Retrieve highest order
		$highest_order = $wpdb->get_var( "SELECT MAX(`order`) FROM `" . $wpdb->prefix . "dms_mappings`" );
		$highest_order = ! empty( $highest_order ) ? ( (int) $highest_order + 1 ) : 1;
		// Loop through mappings
		foreach ( $domains as $domain ) {
			$mapping = $wpdb->get_row( $wpdb->prepare( "SELECT `host` FROM `" . $wpdb->prefix . "dms_mappings` m WHERE m.host=%s",
				$domain ) );
			if ( empty( $mapping ) ) {
				$update_values       = array( 'host' => $domain, 'main' => 0, 'order' => $highest_order );
				$update_where_values = array( '%s', '%d', '%d' );
				$ok                  = $wpdb->insert( $wpdb->prefix . 'dms_mappings', $update_values,
					$update_where_values );
				if ( empty( $ok ) ) {
					//TODO log or collect to show in notice bar but mark fetched flag in a way to not allow to fetch per each load just because of this
//					$add_failed[] = $domain;
					continue;
				}
				$highest_order ++;
			}
		}
	}
}