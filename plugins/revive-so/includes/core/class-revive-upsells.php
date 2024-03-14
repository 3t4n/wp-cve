<?php

/**
 * Class REVIVESO_Upsells
 *
 * Handles upsells and admin menu for Revive.so plugin.
 */
defined( 'ABSPATH' ) || exit;

class REVIVESO_Upsells {
	/**
	 * REVIVESO_Upsells constructor.
	 *
	 * Initializes the class and hooks necessary actions.
	 */
	function __construct() {
		add_filter( 'reviveso_admin_tabs', array( $this, 'upsell_tabs' ) );
		add_filter( 'reviveso_admin_settings', array( $this, 'upsell_settings' ) );

		add_action( 'reviveso_before_advanced_setting_block', array( $this, 'render_upsell_block' ) );
		add_action( 'reviveso_before_social_setting_block', array( $this, 'render_upsell_block' ) );

		add_action( 'reviveso_do_field_rewrite_info_upsell', array( $this, 'render_rewriting_upsell_block' ) );

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 30 );

		// Reviveso Revived Posts modal
		add_action( 'wp_ajax_reviveso_modal-revived_posts_upgrade', array( $this, 'get_modal_revived_posts_upgrade' ) );

		// Reviveso subtab upsells render
		add_action( 'reviveso_settings_pannel_start', array( $this, 'render_instant_indexing_upsell_block' ), 10, 2 );
	}

	/**
	 * Renders upsell block for settings tabs.
	 *
	 * @param  array  $items  The items to be rendered.
	 */
	public function render_upsell_block( $items ) {
		if ( ! empty( $items ) ) {
			return;
		}
		?>
		<div class="reviveso-settings-tab-upsell">
			<h3><?php
				esc_html_e( 'Revive.so PRO', 'revive-so' ); ?></h3>
			<p><?php
				esc_html_e( 'Revive.so PRO grants you even more control over your content allowing you to auto post your republished posts to social media and offer even more republishing customizations for your posts.', 'revive-so' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Renders upsell block specifically for rewriting.
	 *
	 * @param  array   $tab   The settings tab.
	 * @param  string  $item  The specific item.
	 */
	public function render_rewriting_upsell_block( $data ) {
		$link = 'https://revive.so/pricing/?utm_source=reviveso-lite&utm_medium=rewriting-tab&utm_campaign=upsell';
		?>
		<div class="reviveso_rewriting_info reviveso_rewriting_disabled">
			<p>
				<span class="reviveso_status_dot"></span><?php
				echo sprintf( esc_html__( 'Rewriting is not active, unlock rewriting with GPT 4 by purchasing %s Revive.so Pro %s', 'revive-so' ), '<a href="' . esc_url( $link ) . '">', '</a>' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Adds submenu page to admin menu.
	 */
	public function admin_menu() {
		$manage_options_cap = apply_filters( 'reviveso_manage_options_capability', 'manage_options' );
		global $submenu;
		if ( isset( $submenu['reviveso'] ) && in_array( 'reviveso-revived-posts', wp_list_pluck( $submenu['reviveso'], 2 ) ) ) {
			return;
		}
		add_submenu_page(
			'reviveso',
			__( 'Revived Posts', 'revive-so' ),
			__( 'Revived Posts', 'revive-so' ),
			$manage_options_cap,
			'reviveso-revived-posts-upsell',
			array( $this, 'return_none' )
		);
	}

	/**
	 * Callback function for submenu page.
	 */
	public function return_none() {
		return;
	}

	/**
	 * Show the albums modal to upgrade.
	 */
	public function get_modal_revived_posts_upgrade() {
		require REVIVESO_PATH . 'templates/modal/reviveso-modal-revived-posts-upgrade.php';
		wp_die();
	}

	/**
	 * Adds upsell tabs to the settings page.
	 *
	 * @param  array  $tabs  The tabs to be rendered.
	 *
	 * @return array
	 */
	public function upsell_tabs( $tabs ) {
		$tabs['advanced'] = array(
			'name'  => __( 'Advanced', 'revive-so' ),
			'badge' => __( 'PRO', 'revive-so' ),
		);

		$tabs['social'] = array(
			'name'  => __( 'Social', 'revive-so' ),
			'badge' => __( 'PRO', 'revive-so' ),
		);

		return $tabs;
	}

	public function upsell_settings( $settings ) {
		$settings['advanced'] = array();
		$settings['social']   = array();

		// Check if the constant is defined
		// & the version is less than or equal to 1.0.4
		if ( defined( 'REVIVE_PRO_VERSION' ) && version_compare( REVIVE_PRO_VERSION, '1.0.4', '<=' ) ) {
			return $settings;
		}
		$settings['general']['single'] = array(
			'title'       => __( 'Single', 'revive-so' ),
			'name'        => __( 'Single', 'revive-so' ),
			'fields'      => array(),
			'save_button' => false,
			'type'        => 'upsell',
			'badge'       => __( 'PRO', 'revive-so' ),
		);

		$settings['general']['notification'] = array(
			'title'       => __( 'Notification', 'revive-so' ),
			'name'        => __( 'Notification', 'revive-so' ),
			'fields'      => array(),
			'save_button' => false,
			'type'        => 'upsell',
			'badge'       => __( 'PRO', 'revive-so' ),
		);

		$settings['general']['instant_index'] = array(
		    'title' => __( 'Instant Indexing', 'revive-so' ),
		    'name'  => __( 'Instant Indexing', 'revive-so' ),
		    'fields' => array(),
		    'save_button' => false,
		    'type' => 'upsell',
		    'badge' => __( 'PRO', 'revive-so' ),
		);

		return $settings;
	}

	/**
	 * Renders upsell blocks for subtabs
	 *
	 * @param  array  $items  The items to be rendered.
	 */
	public function render_instant_indexing_upsell_block( $tab, $item ) {
		$upsells = array( 'single', 'notification', 'instant_index' );
		if ( empty( $tab['type'] ) || 'upsell' != $tab['type'] || ! in_array( $item, $upsells ) ) {
			return;
		}
		?>
		<div class="reviveso-settings-tab-upsell">
			<h3><?php
				esc_html_e( 'Revive.so PRO', 'revive-so' ); ?></h3>
			<p><?php
				esc_html_e( 'Revive.so PRO grants you even more control over your content allowing you to auto post your republished posts to social media and offer even more republishing customizations for your posts.', 'revive-so' ); ?></p>
		</div>
		<?php
	}
}
