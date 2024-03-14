<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Settings' ) ) {

	/**
	 * Define Soft_template_Core_Settings class
	 */
	class Soft_template_Core_Settings {

		private static $instance = null;

		public  $option_slug  = 'soft_template_core_settings';
		public  $page_slug    = 'soft-template-core';
		private $settings     = null;

		/**
		 * Available Widgets array
		 *
		 * @var array
		 */
		public $avaliable_widgets = array();

		/**
		 * Default Available Extensions
		 *
		 * @var array
		 */
		public $default_avaliable_ext = array(
			'sticky_section' => 'true',
			'column_order'   => 'true',
		);

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			foreach ( glob( soft_template_core()->plugin_path( 'includes/widgets/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->avaliable_widgets[ $slug] = $data['name'];
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

		}

		/**
		 * Initialize page builder module if reqired
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			if ( ! isset( $_REQUEST['page'] ) || $this->page_slug !== sanitize_key($_REQUEST['page']) ) {
				return;
			}

			if ( isset( $_REQUEST['tab'] ) && 'settings' !== sanitize_key($_REQUEST['tab']) ) {
				return;
			}

			$builder_data = soft_template_core()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		/**
		 * Show saved notice
		 *
		 * @return bool
		 */
		public function saved_notice() {

			if ( ! isset( $_REQUEST['page'] ) || $this->page_slug !== sanitize_key($_REQUEST['page']) ) {
				return false;
			}

			if ( ! isset( $_GET['core-settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'soft-template-core' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		/**
		 * Save settings
		 *
		 * @return void
		 */
		public function save() {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current       = get_option( $this->option_slug, array() );
			$data = $_REQUEST;

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {

				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );

			}

			update_option( $this->option_slug, $current );

			$redirect = add_query_arg(
				array( 'core-settings-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Update single option key in options array
		 *
		 * @return void
		 */
		public function save_key( $key, $value ) {

			$current = get_option( $this->option_slug, array() );
			$current[ $key ] = $value;
			update_option( $this->option_slug, $current );

		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->page_slug,
					'tab'  => 'settings',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->option_slug, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {
			//$default_active_widgets = [];

			foreach ( $this->avaliable_widgets as $slug => $name ) {

				$default_active_widgets[ $slug ] = 'true';
			}


			$this->builder->register_form(
				array(
					'soft_template_core_settings_form' => array(
						'type'   => 'form',
						'action' => add_query_arg(
							array(
								'softtemplate_action' => 'settings',
								'handle'     => 'save_settings',
							),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->builder->register_control(
				apply_filters(
					'soft-template-core/settings/general-fields',
					array(
						'facebook_access_tocken' => array(
							'type'        => 'text',
							'parent'      => 'soft_template_core_settings_form',
							'title'       => esc_html__( 'Facebook Access Tocken', 'soft-template-core' ),
							'description' => __( 'Add facebook access tocken. To generate access token, follow this link please - <a href="https://tools.creoworx.com/facebook/" target="_blank">https://tools.creoworx.com/facebook/</a>', 'soft-template-core' ),
							'placeholder' => esc_html__( 'Add facebook access tocken', 'soft-template-core' ),
							'value'       => $this->get( 'facebook_access_tocken' ),
						),					
						'pro_relations' => array(
							'type'        => 'select',
							'id'          => 'pro_relations',
							'name'        => 'pro_relations',
							'parent'      => 'soft_template_core_settings_form',
							'value'       => $this->get( 'pro_relations', 'show_both' ),
							'options'     => array(
								'softtemplate_override'      => 'Softtemplate Overrides',
								'pro_override'      => 'Pro Overrides',
								'show_both'         => 'Show Both, Softtemplate Before Pro',
								'show_both_reverse' => 'Show Both, Pro Before Softtemplate',
							),
							'title'       => esc_html__( 'Locations relations', 'soft-template-core' ),
							'description' => esc_html__( 'Define relations before Softtemplate and Pro templates attached to the same locations', 'soft-template-core' ),
						),
						'prevent_pro_locations' => array(
							'type'        => 'switcher',
							'parent'      => 'soft_template_core_settings_form',
							'title'       => esc_html__( 'Prevent Pro locations registration', 'soft-template-core' ),
							'description' => esc_html__( 'Prevent Elementor Pro locations registration from SofttemplateThemeCore. Enable this if your headers/footers disappear when SofttemplateThemeCore is active', 'soft-template-core' ),
							'value'       => $this->get( 'prevent_pro_locations' ),
						),
					)
				)
			);

			$this->builder->register_control(
				array(
					'softemplate_available_widgets' => array(
						'type'        => 'checkbox',
						'parent'      => 'soft_template_core_settings_form',
						'id'          => 'softemplate_available_widgets',
						'name'        => 'softemplate_available_widgets',
						'class'       => 'widgets_settings_form__checkbox-group',
						'title'       => esc_html__( 'Available Widgets', 'soft-template-core' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the header, footer, archive page', 'soft-template-core' ),
						'options'     => $this->avaliable_widgets,
						'value'       => $this->get( 'softemplate_available_widgets', $default_active_widgets ),
					),
				)
			);

			//var_dump( $this->get( 'softemplate_available_widgets', true ) );
			
			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'soft_template_core_settings_form',
						'class'  => 'cx-control dialog-save',
						'html'   => '<button type="submit" class="cx-button cx-button-primary-style">' . esc_html__( 'Save', 'soft-template-core' ) . '</button>',
					),
				)
			);

			$this->builder->render();

		}

	}
}
