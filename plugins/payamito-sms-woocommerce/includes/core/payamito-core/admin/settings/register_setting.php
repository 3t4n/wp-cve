<?php

namespace payamito\admin;

use KIANFR;

if ( ! class_exists( "register_setting" ) ) {
	class register_setting
	{

		private static $instance;
		protected      $prefix = 'payamito';

		private function __construct()
		{
			$this->prefix = 'payamito';
			$this->load_framwork();
			$this->create_options();
			$this->get_schedule();

			do_action( "payamito_init_admin" );
		}

		public function get_schedule()
		{
			$schedules = wp_get_schedules();
			$options   = [];

			foreach ( $schedules as $index => $schedule ) {
				$options[ $index ] = $schedule['display'];
			}

			return $options;
		}

		private function load_framwork()
		{
			require_once PAYAMITO_ADMIN . 'lib/codestar-framework/codestar-framework.php';

			do_action( 'payamito_loaded_codestar_framework' );

			require_once PAYAMITO_ADMIN . 'settings/payamito_show_other_plugins.php';
		}

		private function create_options()
		{
			if ( class_exists( 'KIANFR' ) ) {
				KIANFR::createOptions( $this->prefix, [
					'framework_title'    => 'پیامیتو',
					'menu_title'         => 'پیامیتو',
					'menu_slug'          => 'payamito',
					'theme'              => 'light',
					'menu_icon'          => 'dashicons-bell',
					'menu_position'      => '2',
					'show_sub_menu'      => false,
					'show_reset_section' => false,
					'show_reset_all'     => false,
					'show_all_options'   => false,
					'sticky_header'      => false,
					'footer_text'        => '',
				] );

				KIANFR::createSection( $this->prefix, $this->default_section() );
				$this->init_custom_section();
				KIANFR::createSection( $this->prefix, $this->log_section() );
				KIANFR::createSection( $this->prefix, $this->payamito_logs_view() );
				// another plugins section
				KIANFR::createSection( $this->prefix, $this->show_other_plugins() );
			}
		}

		/**
		 * default options menu
		 * other addons can not edite default optopns
		 *
		 * @return array
		 * @since             1.1.0
		 * @author            payamito
		 */
		private function default_section()
		{
			return [
				'title'  => esc_html__( 'General Options', 'payamito' ),
				'fields' => [
					[
						'id'    => 'username',
						'type'  => 'text',
						'title' => esc_html__( 'Username', 'payamito' ),
						'help'  => esc_html__( 'Username in payamito website', 'payamito' ),
					],
					[
						'id'    => 'password',
						'type'  => 'text',
						'title' => esc_html__( 'Password', 'payamito' ),
						'help'  => esc_html__( 'Password in payamito website', 'payamito' ),

					],

					[
						'id'    => "Exclusive_line",
						'type'  => 'switcher',
						'title' => esc_html__( 'Do you have a dedicated SMS line?', 'payamito' ),
					],
					[
						'id'         => 'SMS_line_number',
						'type'       => 'text',
						'title'      => esc_html__( 'SMS line number', 'payamito' ),
						'help'       => esc_html__( 'SMS line number. If you do not know, call support', 'payamito' ),
						'dependency' => [ "Exclusive_line", '==', 'true' ],

					],

					//@since 2.0.0
					[
						'type'     => 'callback',
						'function' => [ $this, 'connected_test' ],
						'title'    => esc_html__( 'Connection Test', 'payamito' ),
					],
				],
			];
		}

		public function log_section()
		{
			return [
				'title'  => esc_html__( 'Logs configuration', 'payamito' ),
				'fields' => [
					[
						'id'    => "log_active",
						'type'  => 'switcher',
						'title' => esc_html__( 'Clearing logs', 'payamito' ),
					],
					[
						'type'       => 'notice',
						'style'      => 'danger',
						'dependency' => [ "log_active", '==', 'true' ],
						'content'    => esc_html__( 'Keep in mind by activating the clearing of logs. The first cleaning period is done after saving the settings', 'payamito' ),
					],
					[
						'id'         => 'log_recurrence',
						'type'       => 'select',
						'dependency' => [ "log_active", '==', 'true' ],
						'title'      => esc_html__( 'Recurrence ', 'payamito' ),
						'options'    => $this->get_schedule(),
					],
					[
						'id'         => 'log_order',
						'type'       => 'select',
						'dependency' => [ "log_active", '==', 'true' ],
						'title'      => esc_html__( 'ORDER ', 'payamito' ),
						'options'    => [
							'date_asc'  => __( "Date(ASC)", 'payamito' ),
							'date_desc' => __( "Date(DESC)", 'payamito' ),
						],
					],
					[
						'id'         => "log_logic_active",
						'type'       => 'switcher',
						'dependency' => [ "log_active", '==', 'true' ],
						'title'      => esc_html__( 'Active Logic', 'payamito' ),
					],

					[
						'id'         => 'log_logic',
						'max'        => 1,
						'dependency' => [ "log_logic_active|log_active", '==|==', 'true|true' ],
						'type'       => 'repeater',
						'title'      => esc_html__( 'Logic', 'payamito' ),
						'fields'     => [
							[
								'id'    => 1,
								'type'  => 'select',
								'title' => esc_html__( 'Logic', 'payamito' ),

								'options' => [
									'0' => esc_html__( 'Is not', 'payamito' ),
									'1' => esc_html__( 'Is', 'payamito' ),
								],
							],
							[
								'id'    => 0,
								'type'  => 'select',
								'title' => esc_html__( 'Param', 'payamito' ),

								'options' => $this->log_logic_options(),
							],
						],
					],
				],
			];
		}

		public function payamito_logs_view()
		{
			return [
				'id'    => 'payamito_logs_view',
				'title' => esc_html__( 'Logs view', 'payamito' ),
			];
		}

		public function show_other_plugins()
		{
			return [
				'title'  => esc_html__( 'another plugins', 'payamito' ),
				'fields' => [
					[
						'title' => '',
						'type'  => 'payamito_show_other_plugins',
					],
				],
			];
		}

		public function log_logic_options()
		{
			$options = [
				esc_html__( 'Status', 'payamito' ) => [
					'status_success' => esc_html__( 'Success', 'payamito' ),
					'status_failed'  => esc_html__( 'Failed', 'payamito' ),
				],

			];

			return $options;
		}

		public function crediet_payamito()
		{
			$response = payamito_code_to_message( payamito_get_crediet() );
			if ( is_numeric( $response ) ) {
				$crediet = sprintf( __( "Crediet: %s SMS", "payamito" ), $response );
			} else {
				$crediet = sprintf( __( "%s", "payamito" ), $response );
			}
			printf( "<h1 style=
			'text-align: center;
			background-color: #efa602;
			padding: 10px;
			color: white;
			border-radius: 5px;'>%s</h1>", $crediet );
		}

		/**
		 * add custom menu
		 * other addons can edite this function
		 *
		 * @return void
		 * @since             1.1.0
		 * @author            payamito
		 * @support           code star framework field types
		 * https://codestarframework.com/documentation/#/fields
		 */
		public function init_custom_section()
		{
			if(isset($_GET['page']) && $_GET['page'] == 'payamito' || wp_doing_ajax() ){
				$sections = apply_filters( 'payamito_add_section', [] );
			if ( ! is_array( $sections ) || is_null( $sections ) ) {
				return;
			}
			foreach ( $sections as $section ) {
				if ( empty( $section['title'] || empty( $section['fields'] ) ) ) {
					continue;
				}
				KIANFR::createSection( $this->prefix, $section );
			}
			}	
			
		}

		/**
		 * Returns an instance of this class.
		 *
		 * @return $this
		 * @since  1.0
		 * @access static
		 */
		public static function instance()
		{
			$class = static::class;

			if ( ! isset( self::$instance[ $class ] ) ) {
				self::$instance[ $class ] = new $class();
			}

			return self::$instance[ $class ];
		}

		/**
		 * connection test
		 *
		 * @return void
		 * @since             2.0.0
		 * @author            payamito
		 */
		public function connected_test()
		{
			printf( "<button type='button' id='payamito_test_connected' class='btn btn-dark'>%s</button>", __( "? connected", "payamito" ) );
		}
	}
}
