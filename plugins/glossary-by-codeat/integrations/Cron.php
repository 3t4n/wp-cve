<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Integrations;

use Glossary\Engine;

/**
 * The various Cron of this plugin
 */
class Cron extends Engine\Base {

	/**
	 * Initialize the class.#
	 *
	 * @return bool
	 */
	public function initialize() {
		/*
		 * Load CronPlus
		 */
		$args     = array(
			'recurrence' => 'daily',
			'name'       => 'glossary_terms_counter',
			'cb'         => 'gl_update_counter',
		);
		$cronplus = new \CronPlus( $args );
		$cronplus->schedule_event();

		\add_action( 'admin_init', array( $this, 'count_terms' ) );

		return true;
	}

	/**
	 * Check if the page and user is admin to do the update
	 *
	 * @return bool
	 */
	public function can_update_counter() {
		$content = new Engine\Is_Methods;

		return \is_admin() && $content->is_ajax() || !$content->is_ajax() && \current_user_can( 'manage_options' );
	}

	/**
	 * Force a manual update of count terms for the caching
	 *
	 * @return void
	 */
	public function count_terms() {
		if ( !$this->can_update_counter() ) {
			return;
		}

		if ( empty( $_GET[ 'gl_count_terms' ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Checking only if empty.
			return;
		}

		\gl_update_counter();
	}

}
