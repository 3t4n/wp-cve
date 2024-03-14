<?php

namespace IC\Plugin\CartLinkWooCommerce\Campaign;

use WP_Post;

/**
 * Modify campaigns lists.
 */
class CampaignList {
	const COLUMN_URL    = 'url';
	const COLUMN_STATUS = 'status';

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_filter( 'disable_months_dropdown', [ $this, 'disable_months_dropdown' ], 10, 2 );
		add_filter( 'bulk_actions-edit-' . RegisterPostType::POST_TYPE, [ $this, 'modify_bulk_actions' ] );
		add_filter( 'post_row_actions', [ $this, 'modify_post_row_actions' ], 10, 2 );

		add_filter( 'manage_' . RegisterPostType::POST_TYPE . '_posts_columns', [ $this, 'modify_table_columns' ] );
		add_action(
			'manage_' . RegisterPostType::POST_TYPE . '_posts_custom_column',
			[
				$this,
				'display_table_content',
			],
			10,
			2
		);
	}

	/**
	 * @param string $column_name .
	 * @param int    $post_id     .
	 *
	 * @return void
	 */
	public function display_table_content( string $column_name, int $post_id ): void {
		$campaign = new Campaign( $post_id );

		switch ( $column_name ) {
			case self::COLUMN_URL:
				add_filter( 'woocommerce_form_field', [ $this, 'add_field_button' ], 10, 2 );

				woocommerce_form_field(
					self::COLUMN_URL,
					[
						'type'              => 'text',
						'return'            => false,
						'default'           => $campaign->get_link(),
						'input_class'       => [
							'campaign-url',
							'js--copy-campaign-url',
						],
						'class'             => [
							'campaign-url-container',
							! $campaign->is_active() ? 'hidden' : '',
						],
						'custom_attributes' => [
							'readonly' => 'readonly',
						],
					]
				);

				remove_filter( 'woocommerce_form_field', [ $this, 'add_field_button' ] );
				break;

			case self::COLUMN_STATUS:
				$status = $campaign->is_active() ? 'enabled' : 'disabled';

				echo '<span data-id="' . esc_attr( $campaign->get_id() ) . '" data-status="' . esc_attr( $campaign->get_status() ) . '" class="woocommerce-input-toggle woocommerce-input-toggle--' . esc_attr( $status ) . ' js--campaign-change-status"></span>';

				break;
		}
	}

	public function add_field_button( $field, $key ) {
		if ( self::COLUMN_URL !== $key ) {
			return $field;
		}

		$button = '<button data-copied_text="' . __( 'Copied!', 'cart-link-for-woocommerce' ) . '" class="button button-copy-url js--copy-button">' . __( 'Copy', 'cart-link-for-woocommerce' ) . '</button>';

		return str_replace( '</p>', $button . '</p>', $field );
	}

	/**
	 * @param string[] $posts_columns .
	 *
	 * @return string[]
	 */
	public function modify_table_columns( $posts_columns ): array {
		unset( $posts_columns['date'] );

		$post_status = filter_input( INPUT_GET, 'post_status' );

		if ( $post_status !== 'trash' ) {
			$posts_columns['title']               = __( 'Campaign Title', 'cart-link-for-woocommerce' );
			$posts_columns[ self::COLUMN_STATUS ] = __( 'Enabled', 'cart-link-for-woocommerce' );
			$posts_columns[ self::COLUMN_URL ]    = __( 'Cart Link', 'cart-link-for-woocommerce' );
		}

		return $posts_columns;
	}

	/**
	 * @param bool   $status    .
	 * @param string $post_type .
	 *
	 * @return bool
	 */
	public function disable_months_dropdown( $status, string $post_type ): bool {
		if ( $post_type !== RegisterPostType::POST_TYPE ) {
			return $status;
		}

		return true;
	}

	/**
	 * @param array $actions .
	 *
	 * @return array
	 */
	public function modify_bulk_actions( $actions ): array {
		unset( $actions['edit'] );

		return $actions;
	}

	/**
	 * @param string[] $actions .
	 * @param WP_Post  $post    .
	 *
	 * @return string[]
	 */
	public function modify_post_row_actions( $actions, WP_Post $post ): array {
		if ( $post->post_type !== RegisterPostType::POST_TYPE ) {
			return $actions;
		}

		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}
}
