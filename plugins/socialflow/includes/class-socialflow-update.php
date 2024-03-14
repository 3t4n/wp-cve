<?php
/**
 * Handle SocialFlow Plugin update
 *
 * @package SocialFlow
 * @since  2.1
 */

/**
 * SocialFlow_Update
 */
class SocialFlow_Update {

	/**
	 * Initialization is fired only when SF_VERSION doesn't match with one stored in db
	 * Handle different scenrious of versions mismatch
	 */
	public function __construct() {
		if ( $this->is_clean_install() ) {
			$this->clean_install();
		} elseif ( $this->is_version_1_1() ) {
			$this->update_1_1_to_2_1();
		} elseif ( $this->is_version_2_0() ) {
			$this->update_2_0_to_2_1();
		}
	}


	/**
	 * Clean install means we are not updating from any previous SF plugin
	 * Means user is not autorized and no version options present
	 *
	 * @return boolean
	 */
	public function is_clean_install() {
		global $socialflow;
		return ( ! $socialflow->is_authorized() && ! $socialflow->options->get( 'version' ) );
	}

	/**
	 * Handle new plugin install
	 * Version options is simply added to plugin options
	 *
	 * @return void
	 */
	public function clean_install() {
		global $socialflow;
		$socialflow->options->set( 'version', SF_VERSION );
		$socialflow->options->save();
	}

	/**
	 * Check if we are updating from previous author plugin
	 * After initializtion initial nag is set to 0
	 * but in version 1.1 there was no such option at all.
	 * Thats why application is authorized but initial_nag is set to 1 as a default option value.
	 *
	 * @return boolean
	 */
	public function is_version_1_1() {
		global $socialflow;
		return ( $socialflow->is_authorized() && 1 === $socialflow->options->get( 'initial_nag' ) );
	}

	/**
	 * Handle update from previous plugin developer
	 *
	 * @return void
	 */
	public function update_1_1_to_2_1() {
		global $socialflow;

		$socialflow->options->set( 'version', SF_VERSION );
		$socialflow->options->set( 'initial_nag', 0 );

		// Separate account enabled status from accounts array.
		$accounts = $socialflow->options->get( 'accounts' );
		if ( $accounts ) {
			$show = array();
			$send = $show;
			foreach ( $accounts as $key => $account ) {
				if ( isset( $account['status'] ) ) {
					if ( 'on' === $account['status'] ) {
						$send[] = $account['client_service_id'];
					}
					unset( $accounts[ $key ]['status'] );
				}

				// All publishing accounts are visibled by default.
				if ( 'publishing' === $account['service_type'] ) {
					$show[] = $account['client_service_id'];
				}
			}

			$socialflow->options->set( 'accounts', $accounts );
			$socialflow->options->set( 'send', $send );
			$socialflow->options->set( 'show', $show );
		}

		// Convert old send option to new compose_now option.
		$enable = (int) $socialflow->options->get( 'enable' );
		if ( false !== $enable ) {
			if ( 1 === $enable ) {
				$socialflow->options->set( 'compose_now', 1 );
			}

			$socialflow->options->delete( 'enable' );
		}

		$socialflow->options->save();
	}

	/**
	 * Check if we are updating from 2_0 version
	 *
	 * @return boolean
	 */
	public function is_version_2_0() {
		global $socialflow;
		return ( $socialflow->is_authorized() && 0 === $socialflow->options->get( 'initial_nag' ) );
	}

	/**
	 * Handle update from version 2_0.
	 */
	public function update_2_0_to_2_1() {
		global $socialflow;

		$socialflow->options->set( 'version', SF_VERSION );
		$socialflow->options->save();
	}
}
