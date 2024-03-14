<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class contains the basic functions responsible for front end views.
 * Class WFACP_View
 */
#[AllowDynamicProperties]

 final class WFACP_Template_loader {

	public static $is_checkout = false;
	private static $ins = null;
	/**
	 * @var WFACP_Template_Common
	 */
	protected $checkout_post = null;
	private $current_template;

	protected $template_type = [];
	protected $template_type_data = [];
	protected $templates = [];
	protected $template = '';
	protected $override_checkout_page_id = 0;
	protected $aero_post_data = [];
	private $installed_plugins = null;

	protected function __construct() {
		if ( WFACP_Common::is_theme_builder() ) {

			add_action( 'init', array( $this, 'is_wfacp_checkout_page' ), 1 );
			add_action( 'init', array( $this, 'maybe_setup_page' ), 20 );
		}
		add_action( 'wfacp_loaded', [ $this, 'add_default_template' ], 20 );
		$this->public_include();
		add_filter( 'template_redirect', array( $this, 'setup_preview' ), 99 );
		add_filter( 'template_include', array( $this, 'assign_template' ), 95 );
	}


	public function add_default_template( $force = false ) {

		if ( true === $force || ( ( ( isset( $_REQUEST['page'] ) && 'wfacp' === $_REQUEST['page'] ) || ( isset( $_REQUEST['action'] ) && 'wfacp_import_template' === $_REQUEST['action'] ) ) && isset( $_REQUEST['wfacp_id'] ) && $_REQUEST['wfacp_id'] > 0 ) ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			do_action( 'wfacp_register_template_types', $this );
			$designs = apply_filters( 'wfacp_register_templates', [], $this );

			if ( ! empty( $designs ) ) {
				foreach ( $designs as $d_key => $templates ) {

					if ( is_array( $templates ) ) {
						foreach ( $templates as $temp_key => $temp_val ) {
							$temp_val['template_type'] = $d_key;

							if ( isset( $temp_val['pro'] ) && 'yes' === $temp_val['pro'] ) {

								$temp_val['license_exist'] = 'no';
							}
							$this->register_template( $temp_key, $temp_val, $d_key );
						}
					}
				}
			}

		}

	}

	public function register_template_type( $data ) {

		if ( isset( $data['slug'] ) && '' != $data['slug'] && isset( $data['title'] ) && '' != $data['title'] ) {
			$slug  = sanitize_title( $data['slug'] );
			$title = esc_html( trim( $data['title'] ) );
			if ( ! isset( $this->template_type[ $slug ] ) ) {
				$this->template_type[ $slug ]      = trim( $title );
				$this->template_type_data[ $slug ] = $data;
			}
		}

	}

	public function register_template( $slug, $data, $type = 'pre_built' ) {
		if ( '' !== $slug && ! empty( $data ) ) {
			$this->templates[ $type ][ $slug ] = $data;
		}
	}

	public function remove_template_type( $type ) {
		if ( 'pre_built' == $type ) {
			return;
		}

		if ( isset( $this->template_type[ $type ] ) ) {
			unset( $this->template_type[ $type ] );
		}

	}

	public function remove_all_templates( $type ) {
		if ( 'pre_built' == $type ) {
			return;
		}

		if ( isset( $this->templates[ $type ] ) ) {
			unset( $this->templates[ $type ] );
		}

	}

	/**
	 * This function use for initialize template on public end
	 * @return
	 */
	private function public_include() {
		if ( WFACP_Common::is_edit_screen_open() || ! WFACP_Common::is_frontend_request() ) {
			return;
		}
		//allow setup data for front end checkout page
		add_action( 'wp', array( $this, 'is_wfacp_checkout_page' ), 5 );
		add_action( 'wp', array( $this, 'maybe_setup_page' ), 7 );
	}

	public static function get_instance() {
		if ( null == self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	private function page_located( $post ) {
		$status = false;

		if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
			if ( ! is_null( WC()->session ) ) {
				WC()->session->set( 'wfacp_is_override_checkout', 0 );
			}

			WFACP_Common::pc( 'checkout wfacp_page id found ->' . $post->ID . '. wfacp_checkout_page_found hooks is setup' );
			WFACP_Common::set_id( $post->ID );
			self::$is_checkout = true;
			if ( ! WFACP_Common::is_theme_builder() ) {
				// Do not act as checkout page when page is open in customizer or any builder
				add_filter( 'woocommerce_is_checkout', '__return_true' );
			}
			do_action( 'wfacp_checkout_page_found', $post->ID, $post );
			$this->checkout_post = $post;
			$status              = true;
		}

		return apply_filters( 'wfacp_page_located', $status, $post, $this );
	}


	public function is_wfacp_checkout_page() {

		if ( is_account_page() ) {
			return false;
		}
		do_action( 'wfacp_start_page_detection' );
		// Do not aero order pay page if is_order_pay function return false
		if ( is_checkout_pay_page() && false == WFACP_Core()->pay->is_order_pay() ) {
			return false;
		}

		if ( apply_filters( 'wfacp_skip_checkout_page_detection', false ) ) {
			return false;
		}

		/* remove divi theme customizer setting */
		remove_action( 'wp', 'et_divi_add_customizer_css' );

		if ( self::$is_checkout ) {

			return true;
		}

		global $post;
		if ( WFACP_Common::is_checkout_process() ) {
			$post_id = absint( $_REQUEST['_wfacp_post_id'] );
			$post    = get_post( $post_id );
		}

		$post = apply_filters( 'wfacp_post', $post );

		$is_located = $this->page_located( $post );

		if ( true == $is_located ) {
			return true;
		}

		if ( $post instanceof WP_Post && $post->post_type !== WFACP_Common::get_post_type_slug() && $post->ID === wc_get_page_id( 'checkout' ) && WFACP_Common::is_theme_builder() ) {
			// do not interupt when native checkout edit with elementor open and storecheckout enabled
			return;
		}
		if ( apply_filters( 'wfacp_do_not_check_for_global_checkout', false, $post, $this ) ) {

			do_action( 'wfacp_none_checkout_pages', $post );

			return;
		}

		if ( ( is_checkout() && ! ( is_order_received_page() ) ) ) {

			$this->override_checkout_page_id = WFACP_Common::get_checkout_page_id();
			// checking this is default checkout page

			if ( 0 === $this->override_checkout_page_id ) {
				do_action( 'wfacp_none_checkout_pages', $post );

				return false;
			}

			// get post return $current post data when you pass post_id=0;
			//this cause redirection issue
			$may_be_post = get_post( $this->override_checkout_page_id );
			if ( ! is_null( $may_be_post ) ) {

				$design_data = WFACP_Common::get_page_design( $may_be_post->ID );
				if ( ( ( 'embed_forms' == $design_data['selected_type'] && WFACP_Common::get_post_type_slug() == $may_be_post->post_type ) || ( WFACP_Common::get_post_type_slug() !== $may_be_post->post_type ) ) && apply_filters( 'wfacp_redirect_embed_global_checkout_url', true, $this->override_checkout_page_id, $may_be_post, $design_data ) ) {

					$global_embed_form_url = apply_filters( 'wfacp_global_embed_form_redirect_url', get_the_permalink( $this->override_checkout_page_id ), $this->override_checkout_page_id, $may_be_post );

					wp_redirect( $global_embed_form_url );
					exit;
				}


				if ( $may_be_post->post_status == 'publish' ) {
					//wfacp pages
					$this->override_checkout_page_id = apply_filters( 'wfacp_wpml_checkout_page_id', $this->override_checkout_page_id );
					do_action( 'wfacp_changed_default_woocommerce_page', $this->override_checkout_page_id );
					WFACP_Common::set_id( $this->override_checkout_page_id );

					$this->checkout_post = $may_be_post;
					if ( $this->override_checkout_page_id !== $may_be_post->ID ) {
						$this->checkout_post = get_post( $this->override_checkout_page_id );
					}
					self::$is_checkout = true;
					add_filter( 'woocommerce_is_checkout', '__return_true' );

					do_action( 'wfacp_checkout_page_found', $this->override_checkout_page_id );

					return true;
				}

			}
		}
		if ( ! is_null( $post ) ) {

			do_action( 'wfacp_none_checkout_pages', $post );
		}

		return false;
	}

	public function change_global_post_var_to_our_page_post( $content ) {
		global $post;
		if ( ! is_null( $this->aero_post_data ) ) {
			if ( ! empty( $this->aero_post_data->post_content ) ) {
				$post    = $this->aero_post_data;
				$content = $this->aero_post_data->post_content;
			}
		}

		return $content;

	}

	public function setup_preview() {


		add_filter( 'template_include', array( $this, 'maybe_load' ), 99 );
	}

	/**
	 * Finds out if its safe to initiate data setup for the current request.
	 * Checks for the environmental conditions and provide results.
	 * @return bool true on success| false otherwise
	 * @see WFACP_Template_loader::maybe_setup_page()
	 */
	public function is_valid_state_for_data_setup() {

		return self::$is_checkout;
	}


	/**
	 * @hooked over `init`:15
	 * This method try and sets up the data for all the existing pages.
	 * customizer-admin | customizer-preview | front-end-funnel | front-end-ajax-request-during-funnel
	 * For the given environments we have our offer ID setup at this point. So its safe and necessary to set the data.
	 * This method does:
	 * 1. Fetches and sets up the offer data based on the offer id provided
	 * 2. Finds the loads the appropriate template.
	 * 3. loads offer data to the template instance
	 * 4. Build offer data for the current offer
	 */
	public function maybe_setup_page() {

		if ( ! is_null( $this->current_template ) ) {
			return;

		}
		if ( ! $this->is_valid_state_for_data_setup() ) {

			return;
		}

		$id = WFACP_Common::get_id();
		/**
		 * @var $get_customizer_instance WFACP_Customizer
		 */
		WFACP_Common::pc( 'May be setup page stated' );

		$instances = $this->load_template( $id );
		if ( ! is_null( $instances ) ) {
			$this->current_template = $instances;
			WFACP_Common::pc( 'May be setup page Layout class is found -> ' . $instances->get_slug() );
			do_action( 'wfacp_template_class_found', $this->current_template, $this );
		} else {
			WFACP_Common::pc( 'May be setup page Layout class is not found ' );
		}

	}


	/**
	 * Locate Template using offer meta data also setup data
	 *
	 * @param $wfacp_id
	 *
	 * @return mixed|null
	 */
	public function load_template( $wfacp_id ) {

		if ( empty( $wfacp_id ) || $wfacp_id == 0 ) {
			return null;
		}
		if ( $this->current_template instanceof WFACP_Template_Common ) {
			return $this->current_template;
		}
		$template_file   = false;
		$template        = WFACP_Common::get_page_design( $wfacp_id );
		$selected_type   = $template['selected_type'];
		$this->template  = $template['selected'];
		$locate_template = $this->locate_template( $this->template, $selected_type );
		if ( false !== $locate_template ) {
			$template_file = $locate_template['path'];
		}

		$template_file = apply_filters( 'wfacp_template_class', $template_file, $locate_template, $this->templates, $this );
		if ( 'embed_forms' === $selected_type ) {
			$template_file = WFACP_BUILDER_DIR . '/customizer/templates/embed_forms_1/template.php';
		}

		if ( false == $template_file || ! file_exists( $template_file ) ) {
			return null;
		}
		include __DIR__ . '/class-wfacp-template.php';
		do_action( 'wfacp_template_load', $wfacp_id, $template );

		include WFACP_Core()->dir( 'builder/customizer/class-wfacp-pre-built.php' );
		// include abstract wrapper class for templates

		$this->current_template = include $template_file;
		if ( ! method_exists( $this->current_template, 'get_slug' ) ) {
			return null;
		}
		$this->current_template->set_selected_template( $locate_template );
		$this->current_template->set_wfacp_id( $wfacp_id );
		$this->current_template->set_data();
		/* Remove Block Woocommerce Checkout */
		$this->remove_wc_block_checkout_notices();

		do_action( 'wfacp_after_template_found', $this->current_template );

		return $this->current_template;
	}

	public function assign_template( $template ) {
		global $post;
		if ( is_null( $post ) || ( $post->post_type !== WFACP_Common::get_post_type_slug() && is_null( $this->current_template ) ) ) {
			return $template;
		}

		// In case above statement bypass and template instance not created then return
		if ( is_null( $this->current_template ) ) {
			return $template;
		}


		$wfacp_id = WFACP_Common::get_id();
		if ( 0 == $wfacp_id ) {
			return $template;
		}
		$my_template = get_post_meta( $wfacp_id, '_wp_page_template', true );
		if ( ( isset( $_REQUEST['elementor-preview'] ) && $_REQUEST['elementor-preview'] > 0 ) || ( isset( $_REQUEST['preview_id'] ) && $_REQUEST['preview_id'] > 0 ) ) {
			$wfacp_id             = WFACP_Common::get_id();
			$revision_my_template = $this->get_last_revision_page_template( $wfacp_id );
			if ( '' !== $revision_my_template ) {
				$my_template = $revision_my_template;
			}
		}

		if ( 'wfacp-canvas.php' == $my_template ) {
			$template = WFACP_Core()->dir( 'public/page-template/template-canvas.php' );
		} elseif ( 'wfacp-full-width.php' == $my_template ) {
			$template = WFACP_Core()->dir( 'public/page-template/template-default-boxed.php' );
		} else {
			add_filter( 'next_post_link', '__return_empty_string' );
			add_filter( 'previous_post_link', '__return_empty_string' );
			if ( false !== strpos( $template, 'single.php' ) ) {
				$page = locate_template( array( 'page.php' ) );
				if ( ! empty( $page ) ) {
					$template = $page;
				}
			}
		}

		return $template;
	}


	/**
	 * @hooked over `template_include`
	 * This method checks for the current running funnels and controller to setup data & offer validation
	 * It also loads and echo/prints current template if the offer demands to.
	 *
	 * @param $template
	 *
	 * @return mixed
	 */
	public function maybe_load( $template ) {
		if ( is_subclass_of( $this->current_template, 'WFACP_Template_Common' ) ) {
			do_action( 'wfacp_after_checkout_page_found', WFACP_Common::get_id() );
			if ( $this->current_template instanceof WFACP_Template_Custom_Page && $this->override_checkout_page_id > 0 ) {
				return $template;
			}
			if ( apply_filters( 'wfacp_load_template', true, $this->current_template, $this ) && true == $this->current_template->use_own_template() ) {
				$this->current_template->get_view( $template );
			}
		} else {
			do_action( 'wfacp_checkout_page_not_found' );
		}

		return $template;
	}

	public function get_template_type() {
		return $this->template_type;
	}

	public function get_template_type_data() {
		return $this->template_type_data;
	}

	public function locate_template( $slug, $template_type = 'pre_built', $data = false ) {
		if ( $template_type === 'pre_built' || empty( $template_type ) ) {
			$path = $this->get_template_path_by_template( $slug, $template_type );

			//handle_customizer
			return array(
				'path' => WFACP_BUILDER_DIR . '/customizer/templates/' . $path . '/template.php',
				'slug' => $slug,
			);
		}

		if ( $template_type === 'elementor' ) {
			return array(
				'path' => WFACP_BUILDER_DIR . '/elementor/template/template.php',
				'slug' => $slug,
			);
		}
		if ( $template_type === 'divi' ) {
			return array(
				'path' => WFACP_BUILDER_DIR . '/divi/template/template.php',
				'slug' => $slug,
			);
		} else if ( $template_type === 'gutenberg' ) {
			return array(
				'path'          => WFACP_BUILDER_DIR . '/gutenberg/template/template.php',
				'slug'          => 'gutenberg',
				'name'          => 'gutenberg',
				'template_type' => $template_type,
			);
		}
		if ( $template_type === 'oxy' ) {
			return array(
				'path'          => WFACP_BUILDER_DIR . '/oxygen/template/template.php',
				'slug'          => $slug,
				'name'          => 'oxygen',
				'template_type' => $template_type,
			);
		}


		return false;
	}

	public function get_template_path_by_template( $slug ) {
		$config = array(
			'shopcheckout'        => 'layout_9',
			'shopcheckout-multi'  => 'layout_9',
			'shopcheckout-three'  => 'layout_9',
			'classic'             => 'layout_1',
			'classic-step-2'      => 'layout_1',
			'classic-step-3'      => 'layout_1',
			'salesletter'         => 'layout_2',
			'salesletter-step-2'  => 'layout_2',
			'shopcheckout-step-3' => 'layout_9',
			'marketer'            => 'layout_4',
			'marketer-step-2'     => 'layout_4',
			'marketer-step-3'     => 'layout_4',
			'royale-step-2'       => 'layout_9',
			'royale-step-3'       => 'layout_9'
		);

		return $config[ $slug ];
	}

	/**
	 * @param string $is_single
	 *
	 * @return array
	 */
	public function get_templates( $builder = '' ) {
		if ( isset( $this->templates[ $builder ] ) ) {
			return $this->templates[ $builder ];
		}

		return $this->templates;
	}

	public function get_single_template( $template = '', $type = 'pre_built' ) {
		if ( empty( $template ) ) {
			return [];
		}
		if ( isset( $this->templates[ $type ] ) && isset( $this->templates[ $type ][ $template ] ) ) {
			return $this->templates[ $type ][ $template ];
		}

		return [];
	}


	public function get_template_ins() {
		return $this->current_template;
	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * To avoid cloning of current template class
	 */
	protected function __clone() {

	}

	/**
	 * Get Plugins list by page builder.
	 *
	 * @return array Required Plugins list.
	 * @since 1.1.4
	 *
	 */
	public function get_plugins_groupby_page_builders() {

		$divi_status  = $this->get_plugin_status( 'divi-builder/divi-builder.php' );
		$sling_status = $this->get_plugin_status( 'slingblocks/slingblocks.php' );

		$theme_status = 'not-installed';
		if ( $divi_status ) {
			if ( true === $this->is_divi_theme_installed() ) {
				if ( false === $this->is_divi_theme_enabled() ) {
					$theme_status = 'deactivated';
				} else {
					$theme_status = 'activated';
					$divi_status  = '';
				}
			}
		}
		$oxygen_status = $this->get_plugin_status( 'oxygen/functions.php' );
		$plugins       = array(
			'elementor' => array(
				'title'         => 'Elementor',
				'plugin-status' => $this->get_plugin_status( 'elementor/elementor.php' ),
				'plugins'       => array(
					array(
						'slug'   => 'elementor', // For download from wordpress.org.
						'init'   => 'elementor/elementor.php',
						'status' => $this->get_plugin_status( 'elementor/elementor.php' ),
					),
				),
			),
			'gutenberg' => array(
				'title'         => 'SlingBlocks',
				'plugin-status' => $sling_status,
				'plugins'       => array(
					array(
						'slug'   => 'slingblocks', // For download from wordpress.org.
						'init'   => 'slingblocks/slingblocks.php',
						'status' => $sling_status,
					),
				),
			),
			'divi'      => array(
				'title'         => 'Divi',
				'theme-status'  => $theme_status,
				'plugin-status' => $divi_status,
				'plugins'       => array(
					array(
						'slug'   => 'divi-builder', // For download from wordpress.org.
						'init'   => 'divi-builder/divi-builder.php',
						'status' => $divi_status,
					),
				),
			),
			'oxy'       => array(
				'title'         => 'Oxygen',
				'plugin-status' => $oxygen_status,
				'plugins'       => array(
					array(
						'slug'   => 'oxygen', // For download from wordpress.org.
						'init'   => 'oxygen/functions.php',
						'status' => $oxygen_status,
					),
				),
			),
		);

		$plugins['beaver-builder'] = array(
			'title'   => 'Beaver Builder',
			'plugins' => array(),
		);

		// Check if Pro Exist.
		if ( file_exists( WP_PLUGIN_DIR . '/' . 'bb-plugin/fl-builder.php' ) && ! is_plugin_active( 'beaver-builder-lite-version/fl-builder.php' ) ) {
			$plugins['beaver-builder']['plugin-status'] = $this->get_plugin_status( 'bb-plugin/fl-builder.php' );
			$plugins['beaver-builder']['plugins'][]     = array(
				'slug'   => 'bb-plugin',
				'init'   => 'bb-plugin/fl-builder.php',
				'status' => $this->get_plugin_status( 'bb-plugin/fl-builder.php' ),
			);
		} else {
			$plugins['beaver-builder']['plugin-status'] = $this->get_plugin_status( 'beaver-builder-lite-version/fl-builder.php' );
			$plugins['beaver-builder']['plugins'][]     = array(
				'slug'   => 'beaver-builder-lite-version', // For download from wordpress.org.
				'init'   => 'beaver-builder-lite-version/fl-builder.php',
				'status' => $this->get_plugin_status( 'beaver-builder-lite-version/fl-builder.php' ),
			);
		}
		$plugins['wp_editor']['plugins'][] = array(
			'slug'   => '',
			'status' => null,
		);

		return $plugins;
	}

	/**
	 * Get plugin status
	 *
	 * @param string $plugin_init_file Plguin init file.
	 *
	 * @return mixed
	 * @since 1.0.0
	 *
	 */
	public function get_plugin_status( $plugin_init_file ) {

		if ( null === $this->installed_plugins ) {
			$this->installed_plugins = get_plugins();
		}

		if ( ! isset( $this->installed_plugins[ $plugin_init_file ] ) ) {
			return 'install';
		} elseif ( ! is_plugin_active( $plugin_init_file ) ) {
			return 'activate';
		}

		return;
	}

	/**
	 * Check if Divi theme is install status.
	 *
	 * @return boolean
	 */
	public function is_divi_theme_installed() {
		foreach ( (array) wp_get_themes() as $theme ) {
			if ( 'Divi' === $theme->name || 'Divi' === $theme->parent_theme || 'Extra' === $theme->name || 'Extra' === $theme->parent_theme ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if divi theme enabled for post id.
	 *
	 * @param object $theme theme data.
	 *
	 * @return boolean
	 */
	public function is_divi_theme_enabled( $theme = false ) {

		if ( ! $theme ) {
			$theme = wp_get_theme();
		}

		if ( 'Divi' === $theme->name || 'Divi' === $theme->parent_theme || 'Extra' === $theme->name || 'Extra' === $theme->parent_theme ) {
			return true;
		}

		return false;
	}

	public function localize_page_builder_texts() {
		$pageBuildersTexts           = [];
		$get_all_opted_page_builders = [ 'gutenberg', 'elementor', 'divi', 'oxy' ];
		if ( empty( $get_all_opted_page_builders ) ) {
			return $pageBuildersTexts;
		}

		foreach ( $get_all_opted_page_builders as $builder ) {
			$page_builder    = $this->get_dependent_plugins_for_page_builder( $builder );
			$plugin_string   = sprintf( __( 'This template needs <strong>%s plugin</strong> activated.', 'funnel-builder'  ), esc_html( $page_builder['title'] ) );
			$button_text     = __( 'Activate', 'funnel-builder' );
			$cancel_btn      = __( 'Cancel', 'funnel-builder' );
			$no_install      = 'no';
			$title           = __( 'Import Template', 'funnel-builder'  );
			$show_cancel_btn = 'yes';
			$plugin_status   = isset( $page_builder['plugin-status'] ) ? $page_builder['plugin-status'] : '';
			$theme_status    = isset( $page_builder['theme-status'] ) ? $page_builder['theme-status'] : '';
			$string          = sprintf( __( ' Click the button to install and activate %s.', 'funnel-builder'  ), esc_html( $page_builder['title'] ) );
			$install         = sprintf( __( ' Install and activate %s.', 'funnel-builder'  ), esc_html( $page_builder['title'] ) );
			$builder_link    = '';
			/**
			 * If its a divi builder we need to handle few cases down there for best user experience
			 */
			/**
			 * If its a divi builder we need to handle few cases down there for best user experience
			 */
			if ( 'divi' === $builder ) {
				if ( 'activated' !== $theme_status && 'activate' === $plugin_status ) {
					$plugin_string .= $string;
				} else {
					$plugin_string .= $install;
					$button_text   = __( 'Install Divi Builder', 'funnel-builder'  );
					$no_install    = 'yes';
					$builder_link  = esc_url( 'https://www.elegantthemes.com/' );
				}
			} else if ( 'oxy' === $builder ) {
				if ( 'install' === $plugin_status ) {
					$plugin_string .= $string;
					$button_text   = __( 'Install Oxygen Builder', 'funnel-builder'  );
					$no_install    = 'yes';
					$builder_link  = esc_url( 'https://oxygenbuilder.com/' );
				} else {
					$plugin_string .= $install;
				}
			} else {
				$plugin_string .= $string;
			}

			$pageBuildersTexts[ $builder ] = array(
				'text'            => $plugin_string,
				'ButtonText'      => $button_text,
				'noInstall'       => $no_install,
				'title'           => $title,
				'show_cancel_btn' => $show_cancel_btn,
				'close_btn'       => $cancel_btn,
				'builder_link'    => $builder_link,
				'plugin_status'   => $plugin_status,
			);
		}

		return $pageBuildersTexts;
	}

	public function get_dependent_plugins_for_page_builder( $page_builder_slug = '', $default = 'elementor' ) {
		$plugins = $this->get_plugins_groupby_page_builders();

		if ( array_key_exists( $page_builder_slug, $plugins ) ) {
			return $plugins[ $page_builder_slug ];
		}

		return $plugins[ $default ];
	}

	/**
	 * Get Revision Page Template
	 *
	 * @param $id
	 *
	 * @return mixed|string
	 */
	private function get_last_revision_page_template( $id ) {

		global $wpdb;
		$data     = $wpdb->get_results( "select ID from {$wpdb->posts} where post_parent='{$id}' ORDER BY ID DESC  LIMIT 1", ARRAY_A );
		$template = '';
		if ( ! empty( $data ) ) {
			$revision_id = $data[0]['ID'];
			$template    = get_post_meta( $revision_id, '_wp_page_template', true );
		}

		return $template;

	}

	/**
	 * @return WP_Post;
	 */
	public function get_checkout_post() {
		return $this->checkout_post;
	}


	/*
	 * Remove Woocommerce Checkout Block Notices
	 */
	private function remove_wc_block_checkout_notices() {

		if(!class_exists('Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils')){
			return;
		}

		if ( Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils::is_cart_block_default() || Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils::is_checkout_block_default() ) {
			WFACP_Common::remove_actions( 'wc_get_template', 'Automattic\WooCommerce\Blocks\Domain\Services\Notices', 'get_notices_template' );
		}
	}

}

if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
	WFACP_Core::register( 'template_loader', 'WFACP_Template_loader' );
}

