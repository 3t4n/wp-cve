<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\Common;

class RunAutoupdates {

	/**
	 * @param string $file
	 */
	public function plugin( $file ) {
		$this->prepFilters();
		add_filter( 'auto_update_plugin', function ( $update, $item ) use ( $file ) {

			if ( !empty( $item->plugin ) ) {

				if ( is_string( $item->plugin ) ) {
					// This is the standard ("correct") method.
					$update = $item->plugin === $file;
				}
				elseif ( in_array( $item->slug, [ $file, explode( '/', $file )[ 0 ] ] ) ) {
					/**
					 * Avada/Fusion Builder/Core breaks the standard method. pr0.
					 *
					 * Some premium plugins don't populate the "plugin" field. This causes a problem
					 * later on when the upgrader process runs as it's expecting "plugin" field to be there.
					 * So we "help out" a little here by adding the field the object for the correct plugin file.
					 */
					$update = true;
					$item->plugin = $file; // then we correct their code to work with Autoupdates.
				}
			}
			elseif ( !empty( $item->slug ) ) {
				/**
				 * As above - some premium plugins break this, so we "fix" it.
				 */
				if ( in_array( $item->slug, [ $file, explode( '/', $file )[ 0 ] ] ) ) {
					$update = true;
					$item->plugin = $file;
				}
			}

			return $update;
		}, PHP_INT_MAX, 2 );
		wp_maybe_auto_update();
	}

	/**
	 * @param string $stylesheet
	 */
	public function theme( $stylesheet ) {
		$this->prepFilters();
		add_filter( 'auto_update_theme', function ( $update, $item ) use ( $stylesheet ) {
			return isset( $item->theme ) && $item->theme === $stylesheet;
		}, PHP_INT_MAX, 2 );
		wp_maybe_auto_update();
	}

	/**
	 * @param \stdClass $coreUpgrade
	 */
	public function core( $coreUpgrade ) {
		$this->prepFilters( false );
		add_filter( 'auto_update_core', function ( $update, $item ) use ( $coreUpgrade ) {
			return isset( $coreUpgrade->current ) && isset( $item->current )
				   && $coreUpgrade->current === $item->current;
		}, PHP_INT_MAX, 2 );
		wp_maybe_auto_update();
	}

	/**
	 * @param bool $bDisableDefaultCore - use to ensure default Core upgrades don't happen
	 */
	private function prepFilters( $bDisableDefaultCore = true ) {
		$aFilters = [
			'automatic_updates_is_vcs_checkout',
			'auto_update_plugin',
			'auto_update_theme',
			'auto_update_core',
			'automatic_updater_disabled',
			'send_core_update_notification_email',
			'automatic_updates_complete',
			'auto_plugin_update_send_email', // WP 5.5
			'auto_theme_update_send_email', // WP 5.5
		];
		foreach ( $aFilters as $sFilter ) {
			remove_all_filters( $sFilter );
		}

		add_filter( 'automatic_updater_disabled', '__return_false', PHP_INT_MAX );
		add_filter( 'send_core_update_notification_email', '__return_false', PHP_INT_MAX );
		add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', PHP_INT_MAX );
		add_filter( 'auto_plugin_update_send_email', '__return_false', PHP_INT_MAX );
		add_filter( 'auto_theme_update_send_email', '__return_false', PHP_INT_MAX );

		if ( $bDisableDefaultCore ) {
			add_filter( 'auto_update_core', '__return_false', PHP_INT_MAX );
		}
	}
}