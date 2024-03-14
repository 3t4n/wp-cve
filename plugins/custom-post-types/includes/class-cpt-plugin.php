<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Plugin extends CPT_Component {
	/**
	 * @return void
	 */
	public function init_hooks() {
		register_activation_hook( CPT_PLUGIN_FILE, array( $this, 'do_activation' ) );
		register_deactivation_hook( CPT_PLUGIN_FILE, array( $this, 'do_deactivation' ) );
		add_filter( 'plugin_action_links', array( $this, 'plugin_links' ), PHP_INT_MAX, 2 );
		add_action( 'init', array( $this, 'init_pro_version' ) );
		$this->apply_updates();
	}

	/**
	 * @param $action
	 *
	 * @return void
	 */
	private function send_analytics( $action ) {
		$request_url = add_query_arg(
			array(
				'id'     => 92,
				'action' => $action,
				'domain' => md5( get_home_url() ),
				'v'      => CPT_VERSION,
			),
			'https://totalpress.org/wp-json/totalpress/v1/plugin-growth'
		);
		wp_remote_get( $request_url, array( 'blocking' => false ) );
	}

	/**
	 * @return void
	 */
	public function do_activation() {
		$this->send_analytics( 'activate' );
	}

	/**
	 * @return void
	 */
	public function do_deactivation() {
		$this->send_analytics( 'deactivate' );
	}

	/**
	 * @param $actions
	 * @param $plugin_file
	 *
	 * @return mixed
	 */
	public function plugin_links( $actions, $plugin_file ) {
		if ( 'custom-post-types/custom-post-types.php' == $plugin_file ) { //phpcs:ignore Universal.Operators.StrictComparisons
			$actions[] = sprintf(
				'<a href="%1$s" target="_blank" aria-label="%2$s"> %2$s </a>',
				CPT_PLUGIN_SUPPORT_URL,
				__( 'Support', 'custom-post-types' )
			);
			if ( ! cpt_utils()->is_pro_version_active() ) {
				$actions[] = sprintf(
					'<a href="%1$s" target="_blank" aria-label="%2$s" style="font-weight: bold;"> %2$s </a>',
					CPT_PLUGIN_URL,
					__( 'Get PRO', 'custom-post-types' )
				);
			}
			$actions['deactivate'] = preg_replace(
				'/href="[^"\']*"/',
				'href="?#TB_inline&inlineId=cpt-feedback-modal" class="thickbox" name="' . sprintf( _x( 'Deactivate %s', 'plugin' ), CPT_NAME ) . ' - ' . __( 'Send your feedback', 'custom-post-types' ) . '"',
				$actions['deactivate']
			);
		}
		return $actions;
	}

	/**
	 * @return void
	 */
	private function apply_updates() {
		$installed_version = get_option( cpt_utils()->get_option_name( 'version' ), null );

		if ( version_compare( $installed_version, CPT_VERSION, '=' ) ) {
			return;
		}

		// if ( version_compare( $installed_version, CPT_VERSION, '<' ) ) {
		// Apply updates
		// }

		update_option( cpt_utils()->get_option_name( 'version' ), CPT_VERSION );

		if ( ! empty( $installed_version ) ) {
			$this->send_analytics( 'updated' );
			update_option( cpt_utils()->get_option_name( 'updated_time' ), time() );
		} else {
			update_option( cpt_utils()->get_option_name( 'installation_time' ), time() );
		}
	}

	/**
	 * @return void
	 */
	public function init_pro_version() {
		if (
			in_array( 'custom-post-types-pro/custom-post-types-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) &&
			(
				! defined( 'CPT_PRO_VERSION' ) ||
				version_compare( CPT_PRO_VERSION, CPT_PRO_MIN_VERSION, '<' )
			)
		) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			deactivate_plugins( 'custom-post-types-pro/custom-post-types-pro.php' );
			add_filter(
				'cpt_admin_notices_register',
				function ( $args ) {
					array_unshift(
						$args,
						array(
							'id'          => 'required-min-pro-version',
							'title'       => cpt_utils()->get_notices_title(),
							'message'     => __( 'PRO version update required.', 'custom-post-types' ) . '<br>' . sprintf(
								'<strong>' . CPT_NAME . ' PRO</strong> ' . __( 'version %s or higher', 'custom-post-types' ) . '.',
								'<u>' . CPT_PRO_MIN_VERSION . '</u>'
							),
							'type'        => 'error',
							'dismissible' => false,
							'admin_only'  => 'true',
							'buttons'     => false,
						)
					);
					return $args;
				},
				-1
			);
		} else {
			do_action( 'cpt_plugin_loaded' );
		}
	}
}
