<?php

/**
 * Fired during admin init to check if plugin needs updating
 *
 * @link       http://linkpizza.com
 * @since      5.1.0
 *
 * @package    linkPizza_Manager
 * @subpackage linkPizza_Manager/includes
 */
class linkPizza_Manager_Updater {

	/**
	 * Checks if there is an upgrade required.
	 * Performs upgrade when the version of the installed plugin is below 5.1.0.
	 *
	 * @return boolean true if upgrade was succesful, false otherwise.
	 */
	public function pzz_upgrade_plugin() {
		$v             = 'pzz_version';
		$update_option = null;
		$version       = get_option( $v );

		// Upgrade to version 5.1.0.
		if ( '5.1.0' !== $version ) {
			$version = get_option( $v );
			if ( empty( $version ) || version_compare( $version, '5.1.0', '<' ) ) {
				// Callback function must return true on success.
				$update_option = $this->update_to_5_1_0();

				// Only update option if it was an success.
				if ( $update_option ) {
					pzz_write_log( 'update success' );
					update_option( $v, '5.1.0' );
				}
			}
		}

		// Return the result from the update, so we can test for success/fail/error.
		if ( $update_option ) {
			return $update_option;
		}

		return false;
	}

	/**
	 * Peforms the upgrade.
	 *
	 * @return boolean true if upgrade was succesful, false otherwise.
	 */
	public function update_to_5_1_0() {
		pzz_write_log( 'running update 5.1.0 script' );

		$this->update_posts( 1 );

		// Renamed disabled categories to tracking only categories.
		$pzz_disabled_categories = get_option( 'pzz_disabled_categories', false );
		if ( false !== $pzz_disabled_categories ) {
			add_option( 'pzz_tracking_only_categories', $pzz_disabled_categories, '', false );
		}

		return true;
	}

	/**
	 * Queries all posts and calls the update_post_meta method..
	 *
	 * @return void
	 */
	public function update_posts() {
		$posts = null;
		$args  = array();
		$query = new WP_QUERY( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				{
					$query->the_post();
					$this->upgrade_post_meta( get_post() );
				}
			}
		}
	}

	/**
	 * Converts disabled urls into disabled domains.
	 *
	 * @param WP_Post $post Post object.
	 * @return void
	 */
	public function upgrade_post_meta( $post ) {
		$linkpizza_disabled_urls = get_post_meta( $post->ID, '_linkpizza_disabled_urls', true );
		if ( ! empty( $linkpizza_disabled_urls ) ) {
			// If there is any data besides 'empty'.
			if ( 'empty' !== $linkpizza_disabled_urls ) {
				$linkpizza_disabled_domains = array();
				// Loop over urls in the field.
				foreach ( $linkpizza_disabled_urls as $url ) {
					// Parse host from url.
					$parsed_host = wp_parse_url( $url )['host'];
					if ( ! empty( $parsed_host ) ) {
						array_push( $linkpizza_disabled_domains, $parsed_host );
					}
				}
				// Only unique domains are needed.
				array_unique( $linkpizza_disabled_domains );
				// Add to post meta.
				add_post_meta( $post->ID, '_linkpizza_disabled_domains', $linkpizza_disabled_domains, true );
			}
			// If there is any data delete since it's already transformed above.
			delete_post_meta( $post->ID, '_linkpizza_disabled_urls' );
		}
	}
}

