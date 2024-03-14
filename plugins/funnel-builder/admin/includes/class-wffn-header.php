<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

#[AllowDynamicProperties]
final class WFFN_Header {

	public $data = [];

	/**
	 * Constructor.
	 * @since  1.0.0
	 */
	public function __construct() {

		$this->data['back_link']                            = '';
		$this->data['back_link_label']                      = __( 'Funnels', 'funnel-builder' );
		$this->data['level_1_navigation_active']            = '';
		$this->data['level_2_title']                        = '';
		$this->data['level_2_post_title']                   = '';
		$this->data['level_2_right_wrap_type']              = 'menu';
		$this->data['level_2_right_side_navigation']        = [];
		$this->data['level_2_navigation_pos']               = 'left';
		$this->data['level_2_right_side_navigation_active'] = '';
		$this->data['page_back_link']                       = '';
		$this->data['page_heading']                         = '';
		$this->data['page_heading_meta']                    = '';
	}

	public function set_back_link( $enable = 0, $link = '' ) {
		if ( 0 === $enable || empty( $link ) ) {
			return;
		}
		$this->data['back_link'] = $link;
	}

	public function set_level_1_navigation_active( $active = '' ) {
		if ( empty( $active ) ) {
			return;
		}
		if ( 'funnels' === $active && isset( $_GET['funnel_id'] ) && absint( $_GET['funnel_id'] ) === WFFN_Common::get_store_checkout_id() ) {
			$active = 'store-checkout';
		}
		$this->data['level_1_navigation_active'] = $active;
	}

	public static function left_navigation() {
		$header_menu = [
			'dashboard'      => [
				'name' => __( 'Dashboard', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf' ),
			],
			'funnels'        => [
				'name' => __( 'Funnels', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf&path=/funnels' ),
			],
			'store-checkout' => [
				'name' => __( 'Store Checkout', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf&path=/store-checkout' ),
			],
			'analytics'      => [
				'name' => __( 'Analytics', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf&path=/analytics' ),
			],
			'templates'      => [
				'name' => __( 'Templates', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf&path=/templates' ),
			],
		];

		if ( 'activated' !== WFFN_Common::get_plugin_status( 'cart-for-woocommerce/plugin.php' ) ) {
			$header_menu['cart'] = [
				'name' => __( 'Cart', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf&path=/cart' ),
			];
		}

		if ( ! WFFN_Common::skip_automation_page() ) {
			$header_menu['automations'] = [
				'name' => __( 'Automations', 'funnel-builder' ),
				'link' => admin_url( 'admin.php?page=bwf&path=/automations' ),
			];
		}


		return apply_filters( 'wffn_header_menu', $header_menu );
	}

	private static function prepare_web_url( $link ) {
		return add_query_arg( [
			'utm_source'   => 'website',
			'utm_medium'   => 'text',
			'utm_campaign' => 'header',
		], $link );
	}

	public static function right_navigation() {

		return [
			'menu_react' => [
				'settings' => [
					'name'   => __( 'Settings', 'funnel-builder' ),
					'icon'   => 'settings',
					'link'   => admin_url( 'admin.php?page=bwf&path=/settings' ),
					'desc'   => '',
					'target' => '_blank'
				],
				'setup'    => [
					'name'     => __( 'Setup & Help', 'funnel-builder' ),
					'icon'     => 'help-circle',
					'link'     => admin_url( 'admin.php?page=bwf&path=/setup' ),
					'desc'     => '',
					'position' => 'bottom left',
					'target'   => '_blank'
				]
			],
			'menu'       => [
				'settings'   => [
					'name'   => __( 'Settings', 'funnel-builder' ),
					'icon'   => 'settings',
					'link'   => admin_url( 'admin.php?page=bwf&path=/settings' ),
					'desc'   => '',
					'target' => '_blank'
				],
				'setup_help' => [
					'support' => [
						'name'   => __( 'Get Help', 'funnel-builder' ),
						'desc'   => __( 'Contact support team', 'funnel-builder' ),
						'icon'   => 'support',
						'link'   => self::prepare_web_url( 'https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Admin+Menu+Support&utm_campaign=Lite+Plugin' ),
						'target' => '_blank'
					],
					'help'    => [
						'name'   => __( 'Read Docs', 'funnel-builder' ),
						'desc'   => __( 'Get help along the way', 'funnel-builder' ),
						'icon'   => 'help',
						'link'   => self::prepare_web_url( 'https://funnelkit.com/documentation/?utm_source=WordPress&utm_medium=Admin+Menu+Doc&utm_campaign=Lite+Plugin' ),
						'target' => '_blank'
					]
				]
			]
		];
	}

	/**
	 * For React Menu Render
	 */
	public function get_render_data() {
		return [
			'logo'      => esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/menu/funnelkit-logo.svg' ),
			'logo_link' => admin_url( 'admin.php?page=bwf' ),
			'left_nav'  => self::left_navigation(),
			'right_nav' => self::right_navigation(),
			'data'      => $this->data,
			'pluginDir' => WFFN_PLUGIN_FILE
		];
	}


	public function render() {
		ob_start();
		?>
        <style>
            div#wpcontent {
                padding: 0;
                overflow-x: hidden !important;
                min-height: calc(100vh - 32px);
            }
        </style>
        <div class="bwfan_header">
            <div class="bwfan_header_l1">
                <div class="bwfan_header_l">
                    <div>
                        <a class="bwf-breadcrumb-svg-icon" href="<?php echo esc_url( admin_url( 'admin.php?page=bwf' ) ); ?>">
                            <img src="<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/menu/funnelkit-logo.svg' ) ?>" alt="Funnel Builder"/>
                        </a>
                    </div>
                    <div class="bwfan_navigation">
						<?php
						$navigation = self::left_navigation();
						$this->output_navigation( $navigation, $this->data['level_1_navigation_active'] );
						?>
                    </div>
                </div>
                <div class="bwfan_header_r">
					<?php $navigation = self::right_navigation();
					?>
					<?php $this->outputSettingsMenu( $navigation['menu'] ); ?>
                    <div class="bwfan_navigation bwf-header-ellipses-wrap">
						<?php
						$this->outputEllipsisMenu( $navigation['menu']['setup_help'] );
						?>
                    </div>
                </div>
            </div>
			<?php if ( ! empty( $this->data['page_back_link'] ) ) : ?>
                <div class="bwfan_header_backlink">
                    <a href="<?php echo esc_url( $this->data['page_back_link'] ); ?>" class="bwf-a-no-underline bwfan_backlink">
                        <svg fill="none" xmlns="http://www.w3.org/2000/svg" width="20" height="14" viewBox="0 0 20 14">
                            <path d="M19.3771 6.22958H2.04183L7.22782 1.06353C7.4714 0.819954 7.4714 0.425641 7.22782 0.182684C6.98424 -0.0608946 6.58993 -0.0608946 6.34697 0.182684L0.179888 6.3492C-0.0599626 6.58905 -0.0599626 6.99019 0.179888 7.23005L6.34702 13.3972C6.5906 13.6408 6.98491 13.6408 7.22787 13.3972C7.47145 13.1536 7.47145 12.7593 7.22787 12.5163L2.04183 7.47544H19.3771C19.721 7.47544 20 7.19634 20 6.85251C20 6.50868 19.721 6.22958 19.3771 6.22958Z" fill="#353030"></path>
                        </svg>
						<?php esc_html_e( 'Back', 'funnel-builder' ) ?>
                    </a>
                </div>
			<?php endif ?>
            <div class="bwfan_page_header">
				<?php
				if ( ! empty( $this->data['page_heading'] ) ) {
					?>
                    <span class="bwfan_page_title" data-prefix="<?php echo esc_attr( 'Edit ' ); ?>"><?php echo esc_html( $this->data['page_heading'] ); ?></span>
					<?php
				} else {
					esc_html_e( 'Funnels', 'funnel-builder' );
				}
				if ( isset( $_GET['bwf_exp_ref'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$experiment     = new BWFABT_Experiment( wffn_clean( $_GET['bwf_exp_ref'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$get_control_id = $experiment->get_control();


					$is_control = ( intval( $get_control_id ) === intval( $this->edit_id() ) ) ? 'clr-green' : 'clr-sky-blue'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

					?><span>
                    <span class="bwfan-tag-rounded bwfan_ml_12 <?php echo esc_attr( $is_control ); ?>"><?php echo ( 'clr-green' === $is_control ) ? __( 'Original', 'woofunnels-funnel-builder' ) : __( 'Variant', 'woofunnels-funnel-builder' ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    </span><?php
				}
				if ( ! empty( $this->data['page_heading_meta'] ) ) {

					echo $this->data['page_heading_meta']; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
            </div>
            <div class="bwfan_header_l2">
                <div class="bwfan_header_l">
					<?php

					if ( ! empty( $this->data['back_link'] ) ) {
						echo '<span class="bwfan_header_l2_back"><a href="' . esc_url( $this->data['back_link'] ) . '">' . $this->data['back_link_label'] . '</a></span>';//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					echo '<span class="bwfan_header_l2_wrap">';
					if ( ! empty( $this->data['level_2_title'] ) ) {
						echo '<span id="bwfan_automation_name">' . $this->data['level_2_title'] . '</span>';//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					if ( ! empty( $this->data['level_2_post_title'] ) ) {
						echo $this->data['level_2_post_title'];//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					echo '</span>';
					if ( ( 'menu' === $this->data['level_2_right_wrap_type'] || 'both' === $this->data['level_2_right_wrap_type'] ) && 'right' !== $this->data['level_2_navigation_pos'] ) {
						$navigation = $this->data['level_2_right_side_navigation'];
						$active     = $this->data['level_2_right_side_navigation_active'];

						echo '<div class="bwfan_navigation">';
						$this->output_navigation( $navigation, $active );
						echo '</div>';
					}
					?>
                </div>
                <div class="bwfan_header_r">
					<?php
					if ( 'right' === $this->data['level_2_navigation_pos'] ) {

						$navigation = $this->data['level_2_right_side_navigation'];
						$active     = $this->data['level_2_right_side_navigation_active'];

						echo '<div class="bwfan_navigation">';
						$this->output_navigation( $navigation, $active );
						echo '</div>';
					}
					if ( ( 'html' === $this->data['level_2_right_wrap_type'] || 'both' === $this->data['level_2_right_wrap_type'] ) && ! empty( $this->data['level_2_right_html'] ) ) {
						echo $this->data['level_2_right_html'];//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
                </div>
            </div>
            <hr class="wp-header-end"/>
        </div>
		<?php
		return ob_get_clean();
	}

	public function edit_id() {
		$step_id = 0;
		/**
		 * Register nodes for experiments
		 */
		if ( 0 < filter_input( INPUT_GET, 'edit', FILTER_UNSAFE_RAW ) ) {
			$step_id = filter_input( INPUT_GET, 'edit', FILTER_UNSAFE_RAW );
		} elseif ( 0 < filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW ) ) {
			$step_id = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
		} elseif ( 0 < filter_input( INPUT_GET, 'wfob_id', FILTER_UNSAFE_RAW ) ) {
			$step_id = filter_input( INPUT_GET, 'wfob_id', FILTER_UNSAFE_RAW );
		}

		return $step_id;
	}

	public function output_navigation( $navigation, $active_slug = '' ) {

		if ( 'string' === gettype( $navigation ) ) {
			echo $navigation;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			return;
		}

		if ( ! is_array( $navigation ) || 0 === count( $navigation ) ) {
			return;
		}
		foreach ( $navigation as $key => $item ) {
			$active = ( ! empty( $active_slug ) && $key === $active_slug ) ? 'bwfan_navigation_active' : '';

			$menu_title = isset( $item['name'] ) ? $item['name'] : $item['title'];

			$icon = '';

			$target = ( isset( $item['target'] ) && ! empty( $item['target'] ) ) ? ' target="' . $item['target'] . '"' : '';

			echo '<span><a href="' . esc_url( $item['link'] ) . '" class="' . esc_attr( $active ) . '"' . $target . '>' . $icon . $menu_title . '</a></span>';//phpcs:ignore
		}
		$active = null;
	}

	public function outputSettingsMenu( $navigation ) {
		if ( ! is_array( $navigation ) || 0 === count( $navigation ) ) {
			return;
		}
		?>
        <div class="bwfan_navigation bwf-header-ellipses-wrap nav-right">

            <a href="<?php echo esc_url( admin_url( 'admin.php?page=bwf&path=/settings' ) ); ?>" type="button" title="Quick Actions" aria-expanded="false" class="components-button bwf-ellipsis-menu__toggle has-text has-icon">
				<?php echo file_get_contents( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/menu/settings.svg' ) ?>
            </a>
        </div>
		<?php
	}


	public function outputEllipsisMenu( $navigation ) {
		if ( ! is_array( $navigation ) || 0 === count( $navigation ) ) {
			return;
		}
		?>
        <div class="bwf-ellipsis-menu bwf-ellipsis--alter-alter">
            <div class="components-dropdown">
                <button type="button" title="Quick Actions" aria-expanded="false" class="components-button bwf-ellipsis-menu__toggle has-text has-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 17.7617C12.5393 17.7617 12.9766 17.3245 12.9766 16.7852C12.9766 16.2458 12.5393 15.8086 12 15.8086C11.4607 15.8086 11.0234 16.2458 11.0234 16.7852C11.0234 17.3245 11.4607 17.7617 12 17.7617Z" fill="#353030"></path>
                        <path d="M12 2C6.47328 2 2 6.47254 2 12C2 17.5267 6.47254 22 12 22C17.5267 22 22 17.5275 22 12C22 6.47328 17.5275 2 12 2ZM12 20.4375C7.33684 20.4375 3.5625 16.6638 3.5625 12C3.5625 7.33684 7.33621 3.5625 12 3.5625C16.6632 3.5625 20.4375 7.33621 20.4375 12C20.4375 16.6632 16.6638 20.4375 12 20.4375Z" fill="#353030"></path>
                        <path d="M12 7.01953C10.2769 7.01953 8.875 8.42141 8.875 10.1445C8.875 10.576 9.22477 10.9258 9.65625 10.9258C10.0877 10.9258 10.4375 10.576 10.4375 10.1445C10.4375 9.28297 11.1384 8.58203 12 8.58203C12.8616 8.58203 13.5625 9.28297 13.5625 10.1445C13.5625 11.0061 12.8616 11.707 12 11.707C11.5685 11.707 11.2188 12.0568 11.2188 12.4883V14.4414C11.2188 14.8729 11.5685 15.2227 12 15.2227C12.4315 15.2227 12.7812 14.8729 12.7812 14.4414V13.1707C14.1276 12.8229 15.125 11.598 15.125 10.1445C15.125 8.42141 13.7231 7.01953 12 7.01953Z" fill="#353030"></path>
                    </svg>
                </button>
                <div tabindex="-1">
                    <div class="components-popover components-dropdown__content bwf-ellipsis-menu__popover components-animate__appear is-from-top is-from-right is-without-arrow" data-x-axis="left" data-y-axis="bottom">
                        <div class="components-popover__content" tabindex="-1">
                            <div style="position: relative;">
                                <div role="menu" aria-orientation="vertical" class="bwf-ellipsis-menu__content">
									<?php
									foreach ( $navigation as $item ) {
										?>
                                        <a href="<?php echo esc_url( $item['link'] ); ?>" target="<?php echo esc_attr( $item['target'] ) ?>" role="menuitem" tabindex="0" class="bwf-ellipsis-menu__item">
                                            <div class="components-flex css-1ahbsz-Flex eboqfv50">
                                                <div class="components-flex__item css-1s295sp-Item eboqfv51">
													<?php
													$icon = ( isset( $item['icon'] ) && ! empty( $item['icon'] ) ) ? file_get_contents( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/assets/img/menu/' . $item['icon'] . '.svg' ) : '';
													echo $icon;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													?>
                                                </div>
                                                <div class="components-flex__block css-yr442k-Item-Block eboqfv52">
                                                    <div class="bwf_display_block">
                                                        <div class="menu-item-title"><?php echo esc_html( $item['name'] ); ?></div>
                                                        <div class="menu-item-desc"><?php echo esc_html( $item['desc'] ) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
										<?php
									}
									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php


	}

	public function set_level_2_title( $title = '' ) {
		if ( empty( $title ) ) {
			return;
		}
		$this->data['level_2_title'] = $title;
	}

	public function set_level_2_post_title( $html = '' ) {
		if ( empty( $html ) ) {
			return;
		}
		$this->data['level_2_post_title'] = $html;
	}

	public function set_level_2_side_type( $type = 'menu' ) {
		if ( empty( $type ) ) {
			return;
		}
		$this->data['level_2_right_wrap_type'] = $type;
	}

	public function set_level_2_side_navigation( $navigation = [] ) {
		if ( empty( $navigation ) ) {
			return;
		}
		$this->data['level_2_right_side_navigation'] = $navigation;
	}

	public function set_level_2_navigation_pos( $positions = 'left' ) {
		if ( empty( $positions ) ) {
			return;
		}
		$this->data['level_2_navigation_pos'] = $positions;
	}

	public function set_level_2_side_navigation_active( $active = '' ) {
		if ( empty( $active ) ) {
			return;
		}
		$this->data['level_2_right_side_navigation_active'] = $active;
	}

	public function set_level_2_right_html( $html = '' ) {
		if ( empty( $html ) ) {
			return;
		}
		$this->data['level_2_right_html'] = $html;
	}

	public function set_page_heading( $mixstring = '' ) {
		if ( empty( $mixstring ) ) {
			return;
		}
		$this->data['page_heading'] = $mixstring;
	}

	public function set_page_heading_meta( $html = '' ) {
		if ( empty( $html ) ) {
			return;
		}
		$this->data['page_heading_meta'] = $html;
	}

	public function set_page_back_link( $url = '' ) {
		if ( empty( $url ) ) {
			return;
		}
		$this->data['page_back_link'] = $url;
	}


	public static function level_2_navigation_single_funnel( $funnel_id ) {
		return [
			'steps'     => [
				'name' => __( 'Steps', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=bwf&path=/funnels/$funnel_id/funnel" ),
			],
			'contacts'  => [
				'name' => __( 'Contacts', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=bwf&path=/funnels/$funnel_id/contacts" ),
			],
			'analytics' => [
				'name'         => __( 'Analytics', 'funnel-builder' ),
				'link'         => admin_url( "admin.php?page=bwf&path=/funnels/$funnel_id/analytics" ),
				'isProFeature' => true,
				'showOnClick'  => true,
			],
			'settings'  => [
				'name' => __( 'Settings', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=bwf&path=/funnels/$funnel_id/settings" ),
			]
		];
	}

	public static function level_2_navigation_licenses() {
		return [
			'licenses' => [
				'name' => __( 'Licenses', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=woofunnels&tab=licenses" ),
			],
			'support'  => [
				'name' => __( 'Support', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=woofunnels&tab=support" ),
			],
			'tools'    => [
				'name' => __( 'Tools', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=woofunnels&tab=tools" ),
			],
			'logs'     => [
				'name' => __( 'Logs', 'funnel-builder' ),
				'link' => admin_url( "admin.php?page=woofunnels&tab=logs" ),
			]
		];
	}


}
