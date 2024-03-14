<?php
/**
 * Compose Form Log.
 *
 * @package class-socialflow-post-account
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Generate all data to angular handler
 *
 * @since 2.7.4
 */
class SocialFlow_Post_Accounts {
	/**
	 * Id current post.
	 *
	 * @var int
	 */
	protected $post_id;
	/**
	 * Type current post.
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Init Post id and post type.
	 *
	 * @param int    $post_id Post current id.
	 * @param string $post_type Post current type.
	 */
	public function __construct( $post_id, $post_type ) {
		$this->post_id   = $post_id;
		$this->post_type = $post_type;
	}

	/**
	 * Retrieve only enabled accounts
	 */
	public function get_all_enabled() {
		global $socialflow;
		return $socialflow->accounts->get_enabled_accounts( $this->post_type );
	}

	/**
	 * Retrieve Get all grouped
	 */
	public function get_all_grouped() {
		$accounts = $this->get_all_enabled();
		return $this->get_grouped( $accounts );
	}

	/**
	 * Retrieve Get all saved accounts
	 */
	public function get_saved_grouped() {
		$accounts = $this->get_saved_enabled();
		return $this->get_grouped( $accounts );
	}

	/**
	 * Get service_user_ids
	 *
	 * @see api "Send Message to Multiple Queues"
	 */
	public function get_data_to_compose() {
		$grouped = $this->get_saved_grouped();
		$output  = array();
		foreach ( $grouped as $social_type => $accounts ) {
			$ids = array();
			foreach ( $accounts as $account ) {
				$ids[]       = $account->get( 'service_user_id' );
				$native_type = $account->get_native_type();
			}

			$output[ $social_type ] = array(
				'service_user_ids'   => $ids,
				'social_native_type' => $native_type,
			);
		}

		return $output;
	}

	/**
	 * Get Saved ids
	 */
	public function get_saved_ids() {
		global $socialflow;
		$data = get_post_meta( $this->post_id, 'sf_accounts', true );
		if ( $data ) {
			return $data;
		}

		return $socialflow->options->get( 'send', array() );
	}
	/**
	 * Get saved enabled
	 */
	public function get_saved_enabled() {
		$saved_ids   = $this->get_saved_ids();
		$all_enabled = $this->get_all_enabled();
		$accounts    = array();
		foreach ( $saved_ids as $account_id ) {
			if ( ! isset( $all_enabled[ $account_id ] ) ) {
				continue;
			}

			$accounts[ $account_id ] = $all_enabled[ $account_id ];
		}

		return $accounts;
	}

	/**
	 * Get ng data
	 */
	public function get_ng_data() {
		$grouped_accounts = $this->get_all_grouped();
		$saved_ids        = $this->get_saved_ids();
		$output           = array();
		if ( empty( $saved_ids ) ) {
			return $output;
		}

		foreach ( $grouped_accounts as  $accounts ) {
			foreach ( $accounts as $account ) {
				$id       = $account->get_id();
				$output[] = array(
					'id'              => $id,
					'service_user_id' => $account->get_client_id(),
					'type'            => $account->get_type(),
					'name'            => $account->get_display_name( false ),
					'valid'           => $account->is_valid(),
					'send'            => in_array( $id, $saved_ids, true ),
					'field_meta'      => array(
						'name' => "socialflow[accounts][$id]",
						'id'   => "sf_accounts_{$id}",
					),
				);
			}
		}

		return $output;
	}

	/**
	 * Save Enabled.
	 *
	 * @param array $data account data.
	 */
	public function save_enabled( $data ) {
		$accounts = array();
		foreach ( $data['accounts'] as $account_id => $value ) {
			$accounts[] = $account_id;
		}

		update_post_meta( $this->post_id, 'sf_accounts', $accounts );
	}

	/**
	 * Save Enabled.
	 *
	 * @param array $accounts user account.
	 */
	protected function get_grouped( $accounts ) {
		global $socialflow;
		return $socialflow->accounts->group_by_type( $accounts, true );
	}
}
