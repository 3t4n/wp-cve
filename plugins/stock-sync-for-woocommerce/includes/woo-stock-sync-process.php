<?php

/**
 * Prevent direct access to the script.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woo_Stock_Sync_Process extends WP_Async_Request {
	protected $action = 'wss_background_process';

	/**
	 * Dispatch the async request
	 * 
	 * @return array|WP_Error
	 */
	public function dispatch() {
		$url  = add_query_arg( $this->get_query_args(), $this->get_query_url() );
		$args = $this->get_post_args();

		$salt = uniqid();
		$args['body']['salt'] = $salt;
		$args['body']['mac'] = md5( implode( '|', [ $salt, NONCE_SALT ] ) );

		unset( $args['cookies'] );

		if ( wss_process_model() === 'foreground' || ( isset( $GLOBALS['wss_foreground_process'] ) && $GLOBALS['wss_foreground_process'] ) ) {
			$rows = isset( $args['body']['bulk_changes'] ) ? $args['body']['bulk_changes'] : [];

			$this->handle( $rows );
		} else {
			return wp_remote_post( esc_url_raw( $url ), $args );
		}
	}

	/**
	 * Handle
	 */
	public function handle( $rows = null ) {
		$sites = woo_stock_sync_sites();

		// Cut off other sites (if this has been a primary inventory before and there are other sites defined)
		if ( wss_is_secondary() ) {
			$sites = [$sites[0]];
		}

		foreach ( $sites as $site ) {
			$this->push_multiple( $site, false, $rows );
		}
	}

	/**
	 * Push multiple changes to a site
	 */
	private function push_multiple( $site, $retry = false, $rows = null ) {
		if ( ! isset( $rows ) ) {
			$rows = $_POST['bulk_changes'];
		}

		$data = [];
		foreach ( $rows as $row ) {
			$data[] = [
				'product' => wc_get_product( $row['product_id'] ),
				'operation' => $row['operation'],
				'value' => $row['value'],
				'source_desc' => isset( $row['source_desc'] ) ? $row['source_desc'] : null,
				'source_url' => isset( $row['source_url'] ) ? $row['source_url'] : null,
				'log_id' => isset( $row['log_id'] ) ? $row['log_id'] : null,
			];
		}

		$api = new Woo_Stock_Sync_Api_Request();
		$result = $api->push_multiple( $data, $site );

		// Request failed altogether, retry
		if ( $result === false ) {
			if ( ! $retry ) {
				$this->push_multiple( $site, true, $rows );
			}
		}
	}

	/**
	 * Maybe handle
	 *
	 * Check for correct nonce and pass to handler.
	 */
	public function maybe_handle() {
		// Don't lock up other requests while processing
		session_write_close();

		// Ensure the request originated from a internal background dispatch
		// instead of 3rd party source.
		// Nonce cannot be used here because of how REST API determines current
		// user.
		$this->verify();

		$this->handle();

		wp_die();
	}

	/**
	 * Verify request
	 */
	private function verify() {
		$salt = isset( $_POST['salt'] ) ? $_POST['salt'] : '';
		$mac = md5( implode( '|', [ $salt, NONCE_SALT ] ) );

		if ( $mac !== $_POST['mac'] ) {
			wp_die( -1, 403 );
		}

		return true;
	}
}
