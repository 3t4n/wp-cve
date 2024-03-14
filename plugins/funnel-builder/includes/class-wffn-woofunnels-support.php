<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFFN_WooFunnels_Support' ) ) {
	class WFFN_WooFunnels_Support {

		public static $_instance = null;

		protected $encoded_basename = '';
		public function __construct() {

			add_filter( 'woofunnels_default_reason_' . WFFN_PLUGIN_BASENAME, function () {
				return 1;
			} );
			add_filter( 'woofunnels_default_reason_default', function () {
				return 1;
			} );
			$this->encoded_basename = sha1( WFFN_PLUGIN_BASENAME );

			add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 85 );
			if ( ! WFFN_Common::skip_automation_page() ) {
				add_action( 'admin_menu', array( $this, 'register_automations_menu' ), 901 );
			}
			add_action( 'admin_menu', array( $this, 'register_menu_for_pro' ), 999 );
			add_action( 'admin_menu', array( $this, 'add_menus' ), 81 );

		}

		/**
		 * @return null|WFFN_WooFunnels_Support
		 */
		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}


		public function woofunnels_page() {

			if ( null === filter_input( INPUT_GET, 'tab' ) ) {
				if ( class_exists( 'WFFN_Pro_Core' ) ) {
					WooFunnels_dashboard::$selected = 'licenses';
				} else {
					WooFunnels_dashboard::$selected = 'support';
				}
			}
			if ( class_exists( 'WFFN_Header' ) ) {
				$header_ins = new WFFN_Header();
				$header_ins->set_level_1_navigation_active( 'licenses' );
				$header_ins->set_level_2_side_navigation( WFFN_Header::level_2_navigation_licenses() );
				$header_ins->set_level_2_side_navigation_active( WooFunnels_dashboard::$selected );
				echo $header_ins->render();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
            <div class="woofunnels_licenses_wrapper">
				<?php WooFunnels_dashboard::load_page(); ?>
            </div>
			<?php

		}

		/**
		 * Adding WooCommerce sub-menu for global options
		 */
		public function add_menus() {

			$user = WFFN_Core()->role->user_access( 'menu', 'read' );

			if ( ! WooFunnels_dashboard::$is_core_menu && false !== $user ) {
				add_menu_page( 'WooFunnels', 'WooFunnels', $user, 'woofunnels', array(
					$this,
					'woofunnels_page',
				), '', 59 );
				add_submenu_page( 'woofunnels', __( 'Licenses', 'funnel-builder' ), __( 'License', 'funnel-builder' ), $user, 'woofunnels' );
				WooFunnels_dashboard::$is_core_menu = true;
			}
		}

		public function register_admin_menu() {
			WFFN_Core()->admin->register_admin_menu();
		}

		public function register_automations_menu() {
			WFFN_Core()->admin->add_automations_menu();
		}

		public function register_menu_for_pro() {

			if (  ! defined( 'WFFN_PRO_VERSION' ) ) {
				$link = add_query_arg( [
					'utm_source'   => 'WordPress',
					'utm_medium'   => 'Menu',
					'utm_campaign' => 'Lite+Plugin',
				], WFFN_Core()->admin->get_pro_link() );
				add_submenu_page( 'woofunnels', null, '<a href="' . $link . '" style="background-color:#1DA867; color:white;" target="_blank"><strong>' . __( 'Upgrade to Pro', 'funnel-builder' ) . '</strong></a>', 'manage_options', 'upgrade_pro', function () {
				}, 99 );

				/**
				 * Sale promotional menus, according to the timestamps
				 */


				$url  = "https://funnelkit.com/exclusive-offer/";
				$time = strtotime( gmdate( 'c' ) );
				if ( $time >= 1700456400 && $time < 1701493200 ) {
					$utm_campaign = 'CM' . gmdate( 'Y' );
					$title        = "Cyber Monday";
					if ( $time < 1701061200 ) {
						$utm_campaign = 'BF' . gmdate( 'Y' );
						$title        = "Black Friday";
					}
					$title .= " 🔥";
					$link  = add_query_arg( [
						'utm_source'   => 'WordPress',
						'utm_medium'   => 'Admin+Menu+FKA',
						'utm_campaign' => $utm_campaign
					], $url );
					add_submenu_page( 'woofunnels', '', '<a href="' . $link . '"  target="_blank">' . $title . '</a>', 'manage_options', 'upgrade_pro', function () {
					}, 100 );
				} elseif ( $time >= 1702270800 && $time < 1702357200 ) {
					$link  = add_query_arg( [
						'utm_source'   => 'WordPress',
						'utm_medium'   => 'Admin+Menu+FKA',
						'utm_campaign' => 'GM' . gmdate( 'Y' )
					], $url );
					$title = "Green Monday 🔥";
					add_submenu_page( 'woofunnels', '', '<a href="' . $link . '"  target="_blank">' . $title . '</a>', 'manage_options', 'upgrade_pro', function () {
					}, 100 );
				}


			}


		}


	}

	if ( class_exists( 'WFFN_WooFunnels_Support' ) ) {
		WFFN_Core::register( 'support', 'WFFN_WooFunnels_Support' );
	}
}

/**new WFFN_WooFunnels_Support();*/
