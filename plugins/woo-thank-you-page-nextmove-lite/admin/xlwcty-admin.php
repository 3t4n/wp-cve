<?php
defined( 'ABSPATH' ) || exit;

class xlwcty_Admin {

	protected static $instance = null;
	protected static $default;
	protected $is_builder_page = false;

	public function __construct() {
		$this->setup_default();
		$this->includes();
		$this->hooks();
	}

	public static function setup_default() {
		self::$default = XLWCTY_Common::get_default_settings();
	}

	/**
	 * Include files
	 */
	public function includes() {
		/**
		 * Loading dependencies
		 */
		if ( file_exists( WP_PLUGIN_DIR . '/cmb2/init.php' ) && defined( 'CMB2_LOADED' ) ) {
			require_once WP_PLUGIN_DIR . '/cmb2/init.php';
		} else {
			include_once $this->get_admin_uri() . 'includes/cmb2/init.php';
		}

		include_once $this->get_admin_uri() . 'includes/cmb2-addons/switch/switch.php';
		include_once $this->get_admin_uri() . 'includes/cmb2-addons/conditional/cmb2-conditionals.php';

		/**
		 * Loading custom classes for product and option page.
		 */
		include_once $this->get_admin_uri() . 'includes/xlwcty-admin-cmb2-support.php';
		include_once $this->get_admin_uri() . 'includes/xlwcty-admin-wcthankyou-options.php';
	}

	/**
	 * Get Admin path
	 * @return string plugin admin path
	 */
	public function get_admin_uri() {
		return plugin_dir_path( XLWCTY_PLUGIN_FILE ) . '/admin/';
	}

	public function hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'xlwcty_post_xlwcty_load_assets' ), 19 );
		add_action( 'admin_enqueue_scripts', array( $this, 'xlwcty_insert_default_options' ), 19 );

		/**
		 * Running product meta info setup
		 */
		add_filter( 'cmb2_init', array( $this, 'xlwcty_add_options_countdown_metabox' ) );

		/**
		 * Running product meta info setup
		 */
		add_filter( 'cmb2_init', array( $this, 'xlwcty_add_options_menu_order_metabox' ) );
		add_filter( 'cmb2_init', array( $this, 'xlwcty_add_cmb2_multiselect' ) );
		add_filter( 'cmb2_init', array( $this, 'xlwcty_add_cmb2_post_select' ) );

		/**
		 * Loading js and css
		 */
		/**
		 * Remove Plugin update transient
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'xlwcty_remove_plugin_update_transient' ), 10 );
		add_action( 'admin_footer', array( $this, 'xlwcty_enqueue_admin_assets' ), 20 );

		/**
		 * Loading cmb2 assets
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'cmb2_load_toggle_button_assets' ), 20 );

		/**
		 * Allowing conditionals to work on custom page
		 */
		add_filter( 'xl_cmb2_add_conditional_script_page', array( 'XLWCTY_Admin_CMB2_Support', 'xlwcty_push_support_form_cmb_conditionals' ) );
		/**
		 * Handle tabs ordering
		 */
		add_filter( 'xlwcty_cmb2_modify_field_tabs', array( $this, 'xlwcty_admin_reorder_tabs' ), 99 );
		/**
		 * Adds HTML field to cmb2 config
		 */
		add_action( 'cmb2_render_xlwcty_html_content_field', array( $this, 'xlwcty_html_content_fields' ), 10, 5 );
		add_action( 'cmb2_render_xlwcty_multiselect', array( $this, 'xlwcty_multiselect' ), 10, 5 );
		add_action( 'cmb2_render_xlwcty_post_select', array( $this, 'xlwcty_post_select' ), 10, 5 );

		/**
		 * Keeping meta box open
		 */
		add_filter( 'postbox_classes_product_xlwcty_product_option_tabs', array( $this, 'xlwcty_metabox_always_open' ) );
		/**
		 * Pushing Deactivation For XL Core
		 */
		add_filter( 'plugin_action_links_' . XLWCTY_PLUGIN_BASENAME, array( $this, 'xlwcty_plugin_actions' ) );
		/**
		 * Adding New Tab in WooCommerce Settings API
		 */
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'modify_woocommerce_settings' ), 98 );
		/**
		 * Removing/deque-ing WC settings javascript on some of our pages to escape conflict
		 */
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'deque_wc_settings_javascript' ), 98 );
		/**
		 * Adding Customer HTML On setting page for WooCommerce
		 */
		add_action( 'woocommerce_settings_' . XLWCTY_Common::get_wc_settings_tab_slug(), array( $this, 'xlwcty_woocommerce_options_page' ) );
		/**
		 * Modifying Publish meta box for our posts
		 */
		add_action( 'post_submitbox_misc_actions', array( $this, 'xlwcty_post_publish_box' ) );
		/**
		 * Adding `Return To` Notice Out Post Pages
		 */
		add_action( 'edit_form_top', array( $this, 'xlwcty_edit_form_top' ) );

		/**
		 * Modifying Post update messages
		 */
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		/**
		 * Hooks to check if activation and deactivation request for post.
		 */
		add_action( 'admin_init', array( $this, 'maybe_activate_post' ) );
		add_action( 'admin_init', array( $this, 'maybe_deactivate_post' ) );
		add_action( 'save_post_' . XLWCTY_Common::get_thank_you_page_post_type_slug(), array( $this, 'save_menu_order' ), 10, 2 );
		add_action( 'wp_print_scripts', array( $this, 'xlwcty_wp_print_scripts' ), 999 );
		add_action( 'admin_menu', array( $this, 'xlwcty_wc_admin_menu' ), 10 );
		add_action( 'admin_menu', array( $this, 'xlwcty_builder' ), 10 );

		/**
		 * Add text for  help popup
		 */
		add_action( 'admin_footer', array( $this, 'xlwcty_add_mergetag_text' ) );
		add_action( 'edit_form_after_title', array( $this, 'xlwcty_add_editor_button' ) );
		add_filter( 'plugin_row_meta', array( $this, 'xlwcty_plugin_row_actions' ), 10, 2 );
		add_action( 'admin_head', array( $this, 'xlwcty_admin_head' ), 99 );
		add_action( 'admin_footer', array( $this, 'xlwcty_admin_footer' ), 99 );
		add_action( 'admin_head', array( $this, 'remove_nav_pages' ), 99 );

		/**
		 * added when admin menu editor plugin rebuilt the menu, so hide our menus from there
		 */
		add_action( 'admin_menu_editor-menu_replaced', array( $this, 'remove_nav_pages' ), 99 );
		add_filter( 'redirect_post_location', array( $this, 'maybe_redirect_after_new_post' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'maybe_builder_page' ) );
		add_action( 'admin_init', array( $this, 'add_metaboxes_newpost' ) );
		add_filter( 'theme_' . XLWCTY_Common::get_thank_you_page_post_type_slug() . '_templates', array( $this, 'allow_page_templates_on_thankyou_post_types' ), 10, 4 );
		/**
		 * CMB2 AFTER SAVE METADATA HOOK
		 */
		add_action( 'cmb2_save_post_fields_xlwcty_global_settings', array( $this, 'handle_icl_on_settings_save' ), 999 );
		add_action( 'cmb2_save_post_fields_xlwcty_builder_settings', array( $this, 'clear_transients' ), 999 );
		add_Action( 'admin_notices', array( $this, 'maybe_show_paypal_notice' ), 10 );
		add_action( 'current_screen', array( $this, 'xlwcty_save_component_screen' ), 99 );
		add_action( 'xlwcty_after_save_component', array( $this, 'set_local_storage' ) );
		add_action( 'delete_post', array( $this, 'clear_transients_on_delete' ), 10 );

		/**
		 * Altering WP admin footer text on NextMove pages
		 */
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 99 );
		add_filter( 'admin_notices', array( $this, 'maybe_show_advanced_update_notification' ), 999 );

		/** Validating & removing scripts on page load */
		add_action( 'admin_print_styles', array( $this, 'removing_scripts_finale_campaign_load' ), - 1 );
		add_action( 'admin_print_scripts', array( $this, 'removing_scripts_finale_campaign_load' ), - 1 );
		add_action( 'admin_print_footer_scripts', array( $this, 'removing_scripts_finale_campaign_load' ), - 1 );

		add_action( 'admin_init', array( $this, 'hide_plugins_update_notices' ) );

		add_action( 'in_admin_header', array( $this, 'maybe_remove_all_notices_on_page' ) );

	}

	/**
	 * Return an instance of this class.
	 * @return    object    A single instance of this class.
	 * @since     1.0.0
	 */
	public static function get_instance() {
		if ( ! is_super_admin() ) {
			return;
		}
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Hooked over Activation
	 * Checks and insert plugin options(data)  in wp_options
	 */
	public static function handle_activation() {
		$default_config = self::$default;
		/**
		 * Handle optIn option
		 */
		if ( 'no' == get_option( 'xl_is_opted', 'no' ) ) {
			delete_option( 'xl_is_opted' );
		}

		$defaults = array(
			array(
				'components'       => array(
					array(
						'component' => '_xlwcty_order',
					),
					array(
						'component' => '_xlwcty_customer_information',
					),
					array(
						'component' => '_xlwcty_order_details',
					),
					array(
						'component' => '_xlwcty_additional_info',
					),
				),
				'layout'           => 'basic',
				'component_layout' => array(
					'basic'      => array(
						'first' => array(
							array(
								'slug'      => '_xlwcty_order',
								'component' => '_xlwcty_order',
								'name'      => __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_customer_information',
								'component' => '_xlwcty_customer_information',
								'name'      => __( 'Customer Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_order_details',
								'component' => '_xlwcty_order_details',
								'name'      => __( 'Order Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_additional_info',
								'component' => '_xlwcty_additional_info',
								'name'      => __( 'Additional Information', 'woo-thank-you-page-nextmove-lite' ),
							),
						),
					),
					'two_column' => array(
						'first'  => array(
							array(
								'slug'      => '_xlwcty_order',
								'component' => '_xlwcty_order',
								'name'      => __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_customer_information',
								'component' => '_xlwcty_customer_information',
								'name'      => __( 'Customer Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_order_details',
								'component' => '_xlwcty_order_details',
								'name'      => __( 'Order Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_additional_info',
								'component' => '_xlwcty_additional_info',
								'name'      => __( 'Additional Information', 'woo-thank-you-page-nextmove-lite' ),
							),
						),
						'second' => array(),
						'third'  => array(),
					),
					'mobile'     => array(
						'first' => array(
							array(
								'slug'      => '_xlwcty_order',
								'component' => '_xlwcty_order',
								'name'      => __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_customer_information',
								'component' => '_xlwcty_customer_information',
								'name'      => __( 'Customer Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_order_details',
								'component' => '_xlwcty_order_details',
								'name'      => __( 'Order Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_additional_info',
								'component' => '_xlwcty_additional_info',
								'name'      => __( 'Additional Information', 'woo-thank-you-page-nextmove-lite' ),
							),
						),
					),
				),
			),
		);

		$is_default_added = get_option( 'xlwcty_default_posts', 'no' );

		if ( 'no' == $is_default_added ) {
			foreach ( $defaults as $default_setup ) {
				$id = wp_insert_post( array(
					'post_type'    => XLWCTY_Common::get_thank_you_page_post_type_slug(),
					'post_title'   => __( 'Thank You', 'woo-thank-you-page-nextmove-lite' ),
					'post_name'    => sanitize_title( __( 'Thank You', 'woo-thank-you-page-nextmove-lite' ) ),
					'post_status'  => 'publish',
					'menu_order'   => '1',
					'post_content' => '[xlwcty_load]',
				) );
				foreach ( $default_setup['components'] as $component_data ) {
					update_post_meta( $id, $component_data['component'] . '_enable', '1' );
				}
				if ( $default_setup['layout'] ) {
					update_post_meta( $id, '_xlwcty_builder_template', $default_setup['layout'] );
				}
				if ( $default_setup['component_layout'] ) {
					update_post_meta( $id, '_xlwcty_builder_layout', wp_json_encode( $default_setup['component_layout'] ) );
				}
				update_post_meta( $id, '_xlwcty_menu_order', 1 );
				update_post_meta( $id, '_wp_page_template', 'default' );
			}
			update_option( 'xlwcty_default_posts', 'yes', false );
		}

		wp_schedule_single_event( time() + 2, 'xlwcty_installed' );
	}

	public function xlwcty_insert_default_options() {
		global $post;
		if ( $post instanceof WP_Post && ( XLWCTY_Common::is_load_admin_assets( 'builder' ) || XLWCTY_Common::is_load_admin_assets( 'single' ) ) ) {
			$is_default = get_post_meta( $post->ID, '_xlwcty_is_default', true );
			if ( empty( $is_default ) ) {
				$default_component_layout = array(
					'basic'      => array(
						'first' => array(
							array(
								'slug'      => '_xlwcty_order',
								'component' => '_xlwcty_order',
								'name'      => __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_customer_information',
								'component' => '_xlwcty_customer_information',
								'name'      => __( 'Customer Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_order_details',
								'component' => '_xlwcty_order_details',
								'name'      => __( 'Order Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_additional_info',
								'component' => '_xlwcty_additional_info',
								'name'      => __( 'Additional Information', 'woo-thank-you-page-nextmove-lite' ),
							),
						),
					),
					'two_column' => array(
						'first'  => array(
							array(
								'slug'      => '_xlwcty_order',
								'component' => '_xlwcty_order',
								'name'      => __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_customer_information',
								'component' => '_xlwcty_customer_information',
								'name'      => __( 'Customer Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_order_details',
								'component' => '_xlwcty_order_details',
								'name'      => __( 'Order Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_additional_info',
								'component' => '_xlwcty_additional_info',
								'name'      => __( 'Additional Information', 'woo-thank-you-page-nextmove-lite' ),
							),
						),
						'second' => array(),
						'third'  => array(),
					),
					'mobile'     => array(
						'first' => array(
							array(
								'slug'      => '_xlwcty_order',
								'component' => '_xlwcty_order',
								'name'      => __( 'Order Confirmation', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_customer_information',
								'component' => '_xlwcty_customer_information',
								'name'      => __( 'Customer Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_order_details',
								'component' => '_xlwcty_order_details',
								'name'      => __( 'Order Details', 'woo-thank-you-page-nextmove-lite' ),
							),
							array(
								'slug'      => '_xlwcty_additional_info',
								'component' => '_xlwcty_additional_info',
								'name'      => __( 'Additional Information', 'woo-thank-you-page-nextmove-lite' ),
							),
						),
					),
				);
				update_post_meta( $post->ID, '_xlwcty_builder_template', 'basic' );
				update_post_meta( $post->ID, '_xlwcty_builder_layout', wp_json_encode( $default_component_layout ) );
				update_post_meta( $post->ID, '_xlwcty_order_details_enable', '1' );
				update_post_meta( $post->ID, '_xlwcty_customer_information_enable', '1' );
				update_post_meta( $post->ID, '_xlwcty_order_enable', '1' );
				update_post_meta( $post->ID, '_xlwcty_is_default', 'yes' );
			}
		}
	}

	/**
	 * Sorter function to sort array by internal key called priority
	 *
	 * @param type $a
	 * @param type $b
	 *
	 * @return int
	 */
	public static function _sort_by_priority( $a, $b ) {
		if ( $a['position'] == $b['position'] ) {
			return 0;
		}

		return ( $a['position'] < $b['position'] ) ? - 1 : 1;
	}

	public static function add_metaboxes() {
		/**
		 * Add Rules metabox
		 */
		add_meta_box( 'xlwcty_rules', __( 'Rules', 'woo-thank-you-page-nextmove-lite' ), array(
			__CLASS__,
			'rules_metabox',
		), XLWCTY_Common::get_thank_you_page_post_type_slug(), 'normal', 'high' );

		/**
		 * Add custom thank you page selection metabox.
		 */
		add_meta_box( 'xlwcty-custom-page', __( 'Select Custom Page', 'woo-thank-you-page-nextmove-lite' ), array(
			__CLASS__,
			'xlwcty_select_custom_thank_you_page',
		), XLWCTY_Common::get_thank_you_page_post_type_slug(), 'side', 'core' );

		/**
		 * Add shortcode metabox
		 */
		add_meta_box( 'xlwcty_shortcodes', __( 'Available Shortcodes', 'woo-thank-you-page-nextmove-lite' ), array(
			__CLASS__,
			'available_shortcodes_metabox',
		), XLWCTY_Common::get_thank_you_page_post_type_slug(), 'normal', 'high' );
	}

	public static function add_metaboxes_newpost() {
		if ( ! isset( $_GET['post'] ) && empty( $_GET['post'] ) ) {
			add_meta_box( 'xlwcty_new_post', 'Getting Started', array( __CLASS__, 'new_post_metabox' ), XLWCTY_Common::get_thank_you_page_post_type_slug(), 'normal', 'high' );
		}
	}

	public static function rules_metabox() {
		include_once plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/views/metabox-rules.php';
	}

	public static function xlwcty_select_custom_thank_you_page() {
		?>
        <p><strong><?php echo __( 'Redirect to Custom Page', 'woo-thank-you-page-nextmove-lite' ); ?></strong></p>
        <select name="xlwcty_custom_thank_you_page" id="xlwcty_custom_thank_you_page">
            <option value=""><?php echo esc_attr( __( 'Use current page', 'woo-thank-you-page-nextmove-lite' ) ); ?></option>

			<?php
			$custom_pages = get_option( 'xlwcty_custom_thank_you_pages', array() );
			$post_id      = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : '';
			$pages        = get_pages();

			foreach ( $pages as $page ) {
				$selected = ( isset( $custom_pages[ $post_id ] ) && $page->ID == $custom_pages[ $post_id ] ) ? 'selected' : '';
				$option   = '<option value="' . $page->ID . '" ' . $selected . '>';
				$option   .= $page->post_title;
				$option   .= '</option>';

				echo $option;
			}
			?>
        </select>
        <p><?php echo __( 'Choose a page above if you want to redirect buyer to a custom thank you page. This page will show once rules are validated.', 'woo-thank-you-page-nextmove-lite' ); ?></p>
        <script>
            jQuery(document).ready(function ($) {
                $('#xlwcty_custom_thank_you_page').select2();
            });
        </script>
		<?php
	}

	public static function available_shortcodes_metabox() {
		if ( ! isset( $_REQUEST['post'] ) ) {
			return;
		}
		$item_id            = $_REQUEST['post'];
		$data               = XLWCTY_Common::get_item_data( $item_id );
		$premium_components = XLWCTY_Common::get_premium_components();
		echo '<div class="xlwcty_tb_content" id="xlwcty_merge_tags_invenotry_bar_help">';
		echo '<table class="table widefat xlwcty_available_shortcodes">';
		echo '<thead>';
		echo '<tr>';
		echo '<th><strong>' . __( 'Component', 'woo-thank-you-page-nextmove-lite' ) . '</strong></th>';
		echo '<th><strong>' . __( 'Shortcode', 'woo-thank-you-page-nextmove-lite' ) . '</strong></th>';
		echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
		echo '<tr>';
		echo '<td>' . __( 'All Components', 'woo-thank-you-page-nextmove-lite' ) . '</td>';
		echo '<td><input type="text" style="width: 75%;" onClick="this.select()" readonly value="[xlwcty_load]"><br> <span class="desc">' . __( 'This shortcode is used to display the whole thank you page layout according to the selected template', 'woo-thank-you-page-nextmove-lite' ) . '</span></td>';
		echo '</tr>';
		if ( isset( $data['builder_template'] ) && '' !== $data['builder_template'] ) {
			$builder_layout = ( isset( $data['builder_layout'] ) ) ? $data['builder_layout'] : false;
			if ( false !== $builder_layout ) {
				$builder_layout = json_decode( $builder_layout, true );

				if ( isset( $builder_layout[ $data['builder_template'] ] ) ) {
					foreach ( $builder_layout[ $data['builder_template'] ] as $section ) {
						if ( is_array( $section ) && count( $section ) > 0 ) {
							foreach ( $section as $component ) {
								// for premium components
								if ( in_array( $component['slug'], $premium_components, true ) ) {
									continue;
								}

								$slug = substr( $component['slug'], 1 );
								echo '<tr>';
								echo '<td>' . $component['name'] . '</td>';
								echo '<td><input type="text" style="width: 75%;" onClick="this.select()" readonly value="[' . $slug . ']"></td>';
								echo '</tr>';
							}
						}
					}
				}
			}
		}
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
	}

	public static function new_post_metabox() {
		ob_start();
		?>
        <h3><?php _e( "Congrats! You're on your way to creating a new Thank You Page. Follow these 4 simple steps:", 'woo-thank-you-page-nextmove-lite' ); ?></h3>
        <ul>
            <li>
                <strong><?php _e( 'Step 1', 'woo-thank-you-page-nextmove-lite' ); ?></strong>: <?php _e( 'Enter the page title above to name your Thank you page.', 'woo-thank-you-page-nextmove-lite' ); ?>
            </li>
            <li>
                <strong><?php _e( 'Step 2', 'woo-thank-you-page-nextmove-lite' ); ?></strong>: <?php _e( 'Select the Rules below. The page will open when set rules were matched.', 'woo-thank-you-page-nextmove-lite' ); ?>
            </li>
            <li>
                <strong><?php _e( 'Step 3', 'woo-thank-you-page-nextmove-lite' ); ?></strong>: <?php _e( 'Input the page priority on the right sidebar.', 'woo-thank-you-page-nextmove-lite' ); ?>
            </li>
            <li>
                <strong><?php _e( 'Step 4', 'woo-thank-you-page-nextmove-lite' ); ?></strong>: <?php _e( 'Press the Publish button on the top right sidebar. You will be automatically redirected to a components UI.', 'woo-thank-you-page-nextmove-lite' ); ?>
            </li>
            <li>
                <strong><?php _e( 'Step 5', 'woo-thank-you-page-nextmove-lite' ); ?></strong>: <?php _e( "Choose the components you'd like to display, edit and arrange them in order.", 'woo-thank-you-page-nextmove-lite' ); ?>
            </li>
            <li>
                <strong><?php _e( 'Step 6', 'woo-thank-you-page-nextmove-lite' ); ?></strong>: <?php _e( "Hit Preview and you're all set to go live!", 'woo-thank-you-page-nextmove-lite' ); ?>
            </li>
        </ul>
		<?php
	}

	public function xlwcty_add_options_countdown_metabox() {
		XLWCTY_Admin_Post_Options::prepere_default_config();
		XLWCTY_Admin_Post_Options::setup_fields();
	}

	public function xlwcty_add_options_menu_order_metabox() {
		XLWCTY_Admin_Post_Options::menu_order_metabox_fields();
	}

	/**
	 * Render options for woocommerce custom option page
	 */
	public function xlwcty_woocommerce_options_page() {
		if ( 'blank' === get_option( 'xlp_is_opted', 'blank' ) ) {
			include_once( 'views/optin-temp.php' );
		} else {
			require_once( $this->get_admin_uri() . 'includes/xlwcty-post-table.php' );
			$unlock_pro_link = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'plugin-resource',
				'utm_term'     => 'buy_now_unlock_pro',
			), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
			?>
            <style>
                body {
                    position: relative;
                    height: auto;
                }
            </style>
            <div class="wrap cmb2-options-page xlwcty_global_option">
				<?php
				if ( filter_input( INPUT_GET, 'section' ) == 'settings' && filter_input( INPUT_GET, 'tab' ) == 'xl-thank-you' ) {
					$this->xlwcty_admin_page_settings_render();
				} else {
					if ( 'yes' == filter_input( INPUT_GET, 'activated' ) ) {
						flush_rewrite_rules();
					}
					?>
                    <h1 class="wp-heading-inline"><?php echo __( 'Thank You Pages', 'woo-thank-you-page-nextmove-lite' ); ?></h1>
                    <a href="<?php echo admin_url( 'post-new.php?post_type=' . XLWCTY_Common::get_thank_you_page_post_type_slug() ); ?>"
                       class="page-title-action"><?php _e( 'Add New', 'woo-thank-you-page-nextmove-lite' ); ?></a>
                    <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=xl-thank-you&section=settings' ); ?>"
                       class="page-title-action xlwcty-a-blue"><?php _e( 'Settings', 'woo-thank-you-page-nextmove-lite' ); ?></a>

                    <div class="clearfix"></div>
					<?php XLWCTY_Admin_CMB2_Support::render_trigger_nav(); ?>

                    <div id="poststuff">
                        <div class="inside">
                            <div class="inside">
                                <div class="xlwcty_options_page_col2_wrap">
                                    <div class="xlwcty_options_page_left_wrap">
										<?php
										$table       = new XLWCTY_Post_Table();
										$table->data = XLWCTY_Common::get_post_table_data( XLWCTY_Admin_CMB2_Support::get_current_trigger() );
										$table->prepare_items();
										$table->display();
										?>
                                        <div class="postbox xlwcty_side_content" style="margin-top=20px;">
                                            <div class="inside">
                                                <h3><i class="dashicons dashicons-plus-alt" style="color: #d54e21;font-size: 18px;"></i>Get More Out of NextMove</h3>
                                                <p>Don't miss out these features:</p>
                                                <ul class="icon_cross">
                                                    <li><strong>Personalized & Dynamic Coupons</strong> – Reveal personalized coupon code to nudge your buyers to buy again.</li>
                                                    <li><strong>Smart Bribe</strong> – Unlock a discount coupon in return for a share. Best way to go viral.</li>
                                                    <li><strong>Recently Viewed & Recommended Products</strong> – Help buyers discover products they'll like.</li>
                                                    <li><strong>Social Share</strong> – Encourage buyers to share their recent purchase with their friends on social channels. Reach the right audience.
                                                    </li>
                                                </ul>
                                                <p>And the list goes on ...</p>
                                                <center><a class="button-primary" href=<?php echo $unlock_pro_link; ?>>Unlock all the Awesome Features now</a></center>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="xlwcty_options_page_right_wrap">
										<?php do_action( 'xlwcty_options_page_right_content' ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
			<?php
		}
	}

	public function xlwcty_admin_page_settings_render() {
		$xlwcty_faq = array(
			__( 'I was unable to set up Thank You Page?', 'woo-thank-you-page-nextmove-lite' )                                        => __( 'Kindly visit our <a href="https://xlplugins.com/documentation/nextmove-woocommerce-thank-you-page/getting-started/">documentation</a> to know more about how to setup thank you pages. ', 'woo-thank-you-page-nextmove-lite' ),
			__( 'Thank You page is not coming on frontend?', 'woo-thank-you-page-nextmove-lite' )                                     => __( 'Visit this step-by-step <a href="https://xlplugins.com/documentation/nextmove-woocommerce-thank-you-page/troubleshooting-guides/thank-you-page-didnt-show/">documentation guide</a> to uncover the reasons for why Thank You Page may not be showing up.. ', 'woo-thank-you-page-nextmove-lite' ),
			__( 'Who is Next Move ideal for?', 'woo-thank-you-page-nextmove-lite' )                                                   => __( 'Next Move is for WooCommerce store owners who want to generate repeat orders on autopilot. They understand the real potential of Thank You pages in the funnel but never had the right tools to exploit it.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'Does NextMove Modify existing Thank You page or creates a new Thank You page?', 'woo-thank-you-page-nextmove-lite' ) => __( 'NextMove generates a new thank you page and adds components as defined by you.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'What is referred to as a component?', 'woo-thank-you-page-nextmove-lite' )                                           => __( 'Each section on the WooCommerce Thank You page such as Order Confirmation, Items, Billing Information, Text, Image, HTML, Videos etc are referred to as a component.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'What kind of components can I add to my Thank You Pages?', 'woo-thank-you-page-nextmove-lite' )                      => __( "There are 17 different components or what we call the building blocks in Thank You pages. See the pricing table to refer. You can choose and add whatever components you want on your Thank You Pages. It's as zippy and hassle-free as it sounds!", 'woo-thank-you-page-nextmove-lite' ),
			__( "Can I customize the components to match my site's themes?", 'woo-thank-you-page-nextmove-lite' )                     => __( "Yes, all the components of Next Move can be customized. Match the skin colors to your store's theme, add borders, change the font size and more. Nothing is rigid here.", 'woo-thank-you-page-nextmove-lite' ),
			__( 'Can I turn off individual components?', 'woo-thank-you-page-nextmove-lite' )                                         => __( 'Absolutely! You can turn off different components based your choice and requirements.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'Can I set up multiple Thank You Pages based on product or categories?', 'woo-thank-you-page-nextmove-lite' )         => __( 'Yes, You can set up as many Thank you pages as you like. You can dive deep and show Thank you pages to customers based on their order value, items purchased, payment gateways, shipping methods used, country of visitor and other parameters. Sky is the limit. Quite literally.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'What is Rule Builder?', 'woo-thank-you-page-nextmove-lite' )                                                         => __( 'Rule builder allows you set up different rules and only when conditions are met a particular thank you page is shown.<br/>Say you want to show specific thank you page based on different items purchased. You can set up the rule to show different pages when rules are met.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'Do I need to make an order every time to preview the page?', 'woo-thank-you-page-nextmove-lite' )                    => __( 'No, We have the feature to choose the preview order and look at how Thank You page would look. Once you are satisfied with the look, you can switch it.', 'woo-thank-you-page-nextmove-lite' ),
			__( 'Can I build Thank You page in sandbox mode until I finalized the page?', 'woo-thank-you-page-nextmove-lite' )        => __( "Yes, There is a global setting in which you can choose to switch NextMove Mode to sandbox mode while building pages. Don't forget to switch to Live mode when done :)", 'woo-thank-you-page-nextmove-lite' ),
			__( 'I run my website in a different language. Can I change the text?', 'woo-thank-you-page-nextmove-lite' )              => __( 'Yes, you can easily create Thank You Pages in your language. We are also compatible with WPML.', 'woo-thank-you-page-nextmove-lite' ),
		);
		?>
        <div class="notice">
            <p><?php _e( 'Back to <a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) . '">' . XLWCTY_FULL_NAME . '</a> listing.', 'woo-thank-you-page-nextmove-lite' ); ?></p>
        </div>
        <div class="wrap xlwcty_global_option">
            <h1 class="wp-heading-inline">Settings</h1>
            <div class="wrap cmb2-options-page xlwcty_global_option">
                <div class="xlwcty-help-half-left">
                    <div class="inside">
						<?php cmb2_metabox_form( 'xlwcty_global_settings', 'xlwcty_global_settings' ); ?>
                    </div>
                </div>
                <div class="xlwcty-help-half-right xlwcty-debug-wrap">
                    <div id="poststuff" class="wrap">
                        <h3> <?php _e( 'Troubleshoot Your Page', 'woo-thank-you-page-nextmove-lite' ); ?></h3>
                        <div class="postbox-container">
                            <div class="postbox xlwcty_debug_option">
                                <div class="inside">
									<?php cmb2_metabox_form( 'xlwcty_debug_settings', 'xlwcty_debug_settings' ); ?>
                                </div>
                            </div>
                            <div class="accordion-container">
                                <h3> <?php _e( 'FAQs', 'woo-thank-you-page-nextmove-lite' ); ?></h3>
                                <ul class="outer-border">
									<?php
									$index = 0;
									foreach ( $xlwcty_faq as $key => $val ) {
										?>
                                        <li class="control-section accordion-section" id="">
                                            <h4 class="accordion-section-title hndle" tabindex="<?php echo $index; ?>">
												<?php echo $key; ?>
                                            </h4>
                                            <div class="accordion-section-content ">
												<?php echo $val; ?>
                                            </div><!-- .accordion-section-content -->
                                        </li><!-- .accordion-section -->
										<?php
										$index ++;
									}
									?>
                                </ul><!-- .outer-border -->
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	/**
	 * Loading additional assets for toggle/switch button
	 */
	public function cmb2_load_toggle_button_assets() {

		if ( XLWCTY_Common::is_load_admin_assets( 'all' ) ) {
			wp_enqueue_style( 'cmb2_switch-css', $this->get_admin_url() . '/includes/cmb2-addons/switch/switch_metafield.css', array(), XLWCTY_VERSION );
			//CMB2 Switch Styling
			wp_enqueue_script( 'cmb2_switch-js', $this->get_admin_url() . '/includes/cmb2-addons/switch/switch_metafield.js', array(), XLWCTY_VERSION, true );
		}
	}

	/**
	 * Get Admin path
	 * @return string plugin admin path
	 */
	public function get_admin_url() {
		return plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin';
	}

	/**
	 * Hooked over `admin_enqueue_scripts`
	 * Enqueue scripts and css to wp-admin
	 */
	public function xlwcty_enqueue_admin_assets() {
		if ( XLWCTY_Common::is_load_admin_assets( 'all' ) ) {
			if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
				wp_enqueue_style( 'xlwcty-mCustomScrollbar-css', $this->get_admin_url() . '/assets/css/jquery.mCustomScrollbar.min.css', array(), XLWCTY_VERSION );
			}
			wp_enqueue_style( 'xlwcty_admin-css', $this->get_admin_url() . '/assets/css/xlwcty-admin-style.css', array(), XLWCTY_VERSION );
			if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
				wp_enqueue_style( 'xlwcty_admin-builder-css', $this->get_admin_url() . '/assets/css/xlwcty-admin-builder.css', array(), XLWCTY_VERSION );
				if ( is_rtl() ) {
					wp_enqueue_style( 'xlwcty_admin-builder-css-rtl', $this->get_admin_url() . '/assets/css/xlwcty-admin-builder-rtl.css', array(), XLWCTY_VERSION );
				}
			}

			if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
				wp_enqueue_style( 'xlwcty-swal-css', $this->get_admin_url() . '/assets/css/sweetalert.css', array(), XLWCTY_VERSION );
				wp_enqueue_script( 'xlwcty-swal-js', $this->get_admin_url() . '/assets/js/sweetalert2.min.js', array(), XLWCTY_VERSION );
			}
			wp_register_script( 'jquery-masked-input', $this->get_admin_url() . '/assets/js/jquery.maskedinput.min.js', array( 'jquery' ), XLWCTY_VERSION );

			if ( XLWCTY_Common::is_load_admin_assets( 'single' ) ) {
				wp_enqueue_script( 'jquery-masked-input' );
			}
			wp_enqueue_script( 'xlwcty_admin-js', $this->get_admin_url() . '/assets/js/xlwcty-admin.js', array(
				'jquery',
				'cmb2-scripts',
				'xlwcty-cmb2-conditionals',
				'wp-util',
			), XLWCTY_VERSION, true );
			wp_register_script( 'xlwcty-modal', $this->get_admin_url() . '/assets/js/xlwcty-modal.min.js', array( 'jquery' ), XLWCTY_VERSION );
			wp_register_style( 'xlwcty-modal', $this->get_admin_url() . '/assets/css/xlwcty-modal.css', array(), XLWCTY_VERSION );

			wp_enqueue_script( 'xlwcty-modal' );
			wp_enqueue_style( 'xlwcty-modal' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'xlwcty-modernizr', '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-droppable' );
			wp_enqueue_script( 'accordion' );
			if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
				wp_enqueue_script( 'xlwcty-mCustomScrollbar-js', $this->get_admin_url() . '/assets/js/jquery.mCustomScrollbar.min.js', array(), XLWCTY_VERSION );
			}

			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_localized_texts', array(
				'no_orders'        => array(
					'title' => __( 'Oops!', 'woo-thank-you-page-nextmove-lite' ),
					'text'  => __( 'We do not have any orders to generate preview.', 'woo-thank-you-page-nextmove-lite' ),
				),
				'no_component'     => array(
					'title' => __( 'Oops!', 'woo-thank-you-page-nextmove-lite' ),
					'text'  => __( 'Please chose at least one component.', 'woo-thank-you-page-nextmove-lite' ),
				),
				'changes'          => array(
					'title'             => __( 'Changes have been made!', 'woo-thank-you-page-nextmove-lite' ),
					'text'              => __( 'You need to save changes before generating preview.', 'woo-thank-you-page-nextmove-lite' ),
					'confirmButtonText' => __( 'Yes, Save It!', 'woo-thank-you-page-nextmove-lite' ),
				),
				'no_orders'        => array(
					'title' => __( 'Oops!', 'woo-thank-you-page-nextmove-lite' ),
					'text'  => __( 'We do not have any orders to generate preview.', 'woo-thank-you-page-nextmove-lite' ),
				),
				'saving'           => array(
					'title' => __( 'Saving Your Data', 'woo-thank-you-page-nextmove-lite' ),
					'text'  => '',
				),
				'reset_permalinks' => array(
					'title' => __( 'Warning', 'woo-thank-you-page-nextmove-lite' ),
					'text'  => __( 'You would need to reset permalinks to generate Preview. Go to Admin -> Settings -> Permalinks and Reset. ', 'woo-thank-you-page-nextmove-lite' ),
				),
			) );

			wp_localize_script( 'xlwcty_admin-js', 'builder_page_url', array( admin_url( 'admin.php?page=xlwcty_builder' ) ) );
			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_site_url', array( site_url() ) );
			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_admin_permalink', array( admin_url( 'options-permalink.php' ) ) );
			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_nonces', array(
				'xlwcty_get_pages_for_order' => wp_create_nonce( 'xlwcty_get_pages_for_order' ),
			) );

			$go_pro_link = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'modals-click',
				'utm_campaign' => 'optin-modals',
				'utm_term'     => 'go-pro{current_slug}',
			), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
			wp_localize_script( 'xlwcty_admin-js', 'buy_pro_helper', array(
				'buy_now_link'        => $go_pro_link,
				'call_to_action_text' => __( 'Upgrade To PRO &nbsp;<i class="dashicons dashicons-arrow-right-alt"></i>', 'woo-thank-you-page-nextmove-lite' ),
				'protabs'             => array(),
				'proacc'              => array(
					'_xlwcty_recently_viewed_product',
					'_xlwcty_related_product',
					'_xlwcty_specific_product',
					'_xlwcty_cross_sell_product',
					'_xlwcty_upsell_product',
					'_xlwcty_coupon',
					'_xlwcty_social_coupons',
					'_xlwcty_share_order',
					'_xlwcty_social_sharing',
					'_xlwcty_birth_date',
				),
				'popups'              => array(
					'_xlwcty_recently_viewed_product' => array(
						'title'   => __( 'Recently Viewed is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'While browsing your store, shoppers may have come across products they liked but did not buy. Show recently viewed items, to remind them about what they left behind.',
					),
					'_xlwcty_related_product'         => array(
						'title'   => __( 'Related Products is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Related products are items related to what your buyers bought.  Help them discover products that they are likely to buy based on recent purchase.',
					),
					'_xlwcty_specific_product'        => array(
						'title'   => __( 'Specific Products is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'You can now add specific products to the list to show to your buyers. These may be the ones you think people will like because they are best sellers or most reviewed.',
					),
					'_xlwcty_cross_sell_product'      => array(
						'title'   => __( 'Cross Sell Products is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Cross-sells are products that are complementary/better quality products. Use the prime real estate on Thank You page to make buyers spend more.',
					),
					'_xlwcty_upsell_product'          => array(
						'title'   => __( 'UpSell Products is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Upsells are products that are complementary/better quality products. Use the prime real estate on Thank You page to make buyers spend more.',
					),
					'_xlwcty_coupon'                  => array(
						'title'   => __( 'Coupons is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Nudge your customers to get the items they like by unlocking a personalized time-bound coupon code. This gives them a reason to make their next purchase soon.',
					),
					'_xlwcty_social_coupons'          => array(
						'title'   => __( 'Smart Bribe is a PRO Feature ', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Use Smart Bribe to reward your buyers with a discount coupon for promoting your store to their personal audience of friends. It\'s an audience you\'d have to spend to otherwise reach out to.',
					),
					'_xlwcty_share_order'             => array(
						'title'   => __( 'Social Share is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Encourage people to share their recent purchase with their friends on social channels. Since they\'ve just bought, they trust you and are more likely to agree to your request.',
					),
					'_xlwcty_social_sharing'          => array(
						'title'   => __( 'Join Us is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Encourage your buyers to become your fans and followers on popular social networks. Having social plugins on Thank You pages means building followers on automation.',
					),
					'_xlwcty_birth_date'              => array(
						'title'   => __( 'Birth Date is a PRO Feature', 'woo-thank-you-page-nextmove-lite' ),
						'content' => 'Birth date is a powerful feature introduced on popular demand. It asks your buyers their birthday and follows up with them with a special discount code.',
					),
				),
			) );

			$this->xlwcty_admin_head_temp_preview();
			global $template_previews;
			global $template_previews_template;
			global $template_previews_default;

			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_layout_preview', array( $template_previews ) );
			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_layout_preview_template', array( $template_previews_template ) );

			wp_localize_script( 'xlwcty_admin-js', 'xlwcty_layout_preview_default', array( $template_previews_default ) );
			$g_settings = XLWCTY_Core()->data->get_option();
			global $xlwcty_components_message;
			if ( is_array( $g_settings ) && count( $g_settings ) > 0 ) {
				$xlwcty_components_message = array();
				if ( empty( $g_settingsp['google_map_api'] ) ) {
					$xlwcty_components_message['_xlwcty_google_map'] = $g_settings['google_map_error_txt'];
				}
				if ( count( $xlwcty_components_message ) > 0 ) {
					wp_localize_script( 'xlwcty_admin-js', 'xlwcty_components_message', $xlwcty_components_message );
				}
			}
		}

		$page = filter_input( INPUT_GET, 'page' );
		if ( empty( $page ) ) {
			return;
		}
		if ( in_array( $page, [ 'xl-cart', 'xl-checkout', 'xl-automations', 'xl-payments' ], true ) ) {
			wp_enqueue_style( 'xlwcty_admin-css', $this->get_admin_url() . '/assets/css/xl-submenu-pages.min.css', array(), XLWCTY_VERSION );
			wp_enqueue_script( 'xl-addon-installer', $this->get_admin_url() . '/assets/js/xl-addon-installer.js', array( 'jquery' ), XLWCTY_VERSION, true );
			$xl_installer_data['nonce'] = wp_create_nonce( 'xl_addon_installation_nonce' );
			wp_localize_script( 'xl-addon-installer', 'xl_installer_data', array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => $xl_installer_data['nonce'],  // Add nonce to xl_installer_data
			) );
		}

		if ( 'wc-settings' === $page && 'xl-thank-you' === filter_input( INPUT_GET, 'tab' ) && 'blank' === get_option( 'xlp_is_opted', 'blank' ) ) {
			wp_enqueue_style( 'xlo-optin-css', $this->get_admin_url() . '/assets/css/xlo-optin.css', XLWCTY_VERSION );
			wp_enqueue_script( 'xlo-optin-js', $this->get_admin_url() . '/assets/js/xlo-optin.js', XLWCTY_VERSION );
			$xlo_optin_nonce = wp_create_nonce( 'xlo_optin_nonce' );
			wp_localize_script( 'xlo-optin-js', 'xlo_optin_vars', array(
				'nonce' => $xlo_optin_nonce,
			) );

			echo "<style>#xlo-wrap .xlo-actions .xlo_loader { background: url('" . admin_url( 'images/spinner.gif' ) . "') no-repeat rgba(238, 238, 238, 0.5); }</style>";
		}
	}

	public function xlwcty_admin_head_temp_preview() {
		global $template_previews;
		global $template_previews_template;
		global $template_previews_default;

		if ( filter_input( INPUT_GET, 'id' ) == null ) {
			return;
		}
		$template_previews = array();
		$template          = get_post_meta( $_GET['id'], '_xlwcty_builder_template', true );
		$layout            = get_post_meta( $_GET['id'], '_xlwcty_builder_layout', true );

		$template_previews_template = $template != '' ? $template : 'basic';
		//basic default components
		$template_previews['basic']['first'] = array();
		//two_column default components
		$template_previews['two_column']['first']  = array();
		$template_previews['two_column']['second'] = array();
		$template_previews['two_column']['third']  = array();
		$template_previews['mobile']['first']      = array();
		$template_previews_default                 = $template_previews;
		if ( $layout != '' ) {
			$template_data = json_decode( $layout, true );
			if ( $template_data ) {
				$template_previews = wp_parse_args( $template_data, $template_previews );
				foreach ( $template_previews['basic']['first'] as $key => $val ) {
					if ( isset( $val['$$hashKey'] ) ) {
						unset( $val['$$hashKey'] );
						$template_previews['basic']['first'][ $key ] = $val;
					}
				}

				foreach ( $template_previews['two_column']['first'] as $key => $val ) {
					if ( isset( $val['$$hashKey'] ) ) {
						unset( $val['$$hashKey'] );
						$template_previews['two_column']['first'][ $key ] = $val;
					}
				}
				foreach ( $template_previews['two_column']['second'] as $key => $val ) {
					if ( isset( $val['$$hashKey'] ) ) {
						unset( $val['$$hashKey'] );
						$template_previews['two_column']['second'][ $key ] = $val;
					}
				}
			}
		}
	}

	/**
	 * Hooked over `admin_enqueue_scripts`
	 * Force remove Plugin update transient
	 */
	public function xlwcty_remove_plugin_update_transient() {
		if ( isset( $_GET['remove_update_transient'] ) && $_GET['remove_update_transient'] == '1' ) {
			delete_option( '_site_transient_update_plugins' );
		}
	}

	public function xlwcty_admin_page_debug_render() {
		include_once plugin_dir_path( XLWCTY_PLUGIN_FILE ) . '/admin/views/page-help.php';
	}

	/**
	 * Hooked over `xlwcty_cmb2_modify_field_tabs`
	 * Sorts Tabs for settings
	 *
	 * @param $tabs Array of tabs
	 *
	 * @return mixed Sorted array
	 */
	public function xlwcty_admin_reorder_tabs( $tabs ) {
		usort( $tabs, array( $this, '_sort_by_priority' ) );

		return $tabs;
	}

	/**
	 * Hooked over `cmb2_render_xlwcty_html_content_field`
	 * Render Html for `xlwcty_html_content` Field
	 *
	 * @param $field CMB@ Field object
	 * @param $escaped_value Value
	 * @param $object_id object ID
	 * @param $object_type Obeect Type
	 * @param $field_type_object Field Tpe Object
	 */
	public function xlwcty_html_content_fields( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {

		$cmbtypes                 = new CMB2_Types( $field );
		$switch                   = '';
		$conditional_value        = ( isset( $field->args['attributes']['data-conditional-value'] ) ? 'data-conditional-value="' . esc_attr( $field->args['attributes']['data-conditional-value'] ) . '"' : '' );
		$conditional_id           = ( isset( $field->args['attributes']['data-conditional-id'] ) ? ' data-conditional-id="' . esc_attr( $field->args['attributes']['data-conditional-id'] ) . '"' : '' );
		$xlwcty_conditional_value = ( isset( $field->args['attributes']['data-xlwcty-conditional-value'] ) ? 'data-xlwcty-conditional-value="' . esc_attr( $field->args['attributes']['data-xlwcty-conditional-value'] ) . '"' : '' );
		$xlwcty_conditional_id    = ( isset( $field->args['attributes']['data-xlwcty-conditional-id'] ) ? ' data-xlwcty-conditional-id="' . esc_attr( $field->args['attributes']['data-xlwcty-conditional-id'] ) . '"' : '' );
		$switch                   = '<div ' . $conditional_value . $conditional_id . $xlwcty_conditional_value . $xlwcty_conditional_id . $cmbtypes->concat_attrs( $field->args['attributes'] ) . ' class="cmb2-xlwcty_html" id="' . $field->args['id'] . '">';

		$switch .= ( $field->args['content'] );
		$switch .= '</div>';
		echo $switch;
	}

	/**
	 * Hooked over `postbox_classes_product_xlwcty_product_option_tabs`
	 * Always open for meta boxes
	 * removing closed class
	 *
	 * @param $classes classes
	 *
	 * @return mixed array of classes
	 */
	public function xlwcty_metabox_always_open( $classes ) {
		if ( ( $key = array_search( 'closed', $classes ) ) !== false ) {
			unset( $classes[ $key ] );
		}

		return $classes;
	}

	/**
	 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' WordPress hook to add deactivate popup support
	 *
	 * @param array $links array of existing links
	 *
	 * @return array modified array
	 */
	public function xlwcty_plugin_actions( $links ) {
		$go_pro_link         = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'text-click',
			'utm_campaign' => 'plugin-actions',
			'utm_term'     => 'go-pro',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$links['settings']   = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() ) . '" class="edit">Settings</a>';
		$links['deactivate'] .= '<i class="xl-slug" data-slug="' . XLWCTY_PLUGIN_BASENAME . '"></i>';
		$links['go_pro']     = '<a style="font-weight: 700; color:#39b54a" href="' . $go_pro_link . '" class="go_pro_a">' . __( 'Go Pro', 'woo-thank-you-page-nextmove-lite' ) . '</a>';

		return $links;
	}

	/*     * ******** Functions For Rules Functionality Starts ************************************* */

	/**
	 * Hooked to `woocommerce_settings_tabs_array`
	 * Adding new tab in woocommerce settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function modify_woocommerce_settings( $settings ) {
		$settings[ XLWCTY_Common::get_wc_settings_tab_slug() ] = __( 'NextMove Lite: XLPlugins', 'woo-thank-you-page-nextmove-lite' );

		return $settings;
	}

	/**
	 * Loading assets for Rules functionality
	 *
	 * @param $handle : handle current page
	 */
	public function xlwcty_post_xlwcty_load_assets( $handle ) {
		wp_enqueue_style( 'xlwcty-admin-all', $this->get_admin_url() . '/assets/css/xlwcty-admin-all.css', array(), XLWCTY_VERSION );

		if ( XLWCTY_Common::is_load_admin_assets( 'single' ) ) {
			wp_enqueue_script( 'select2' );
		}

		if ( XLWCTY_Common::is_load_admin_assets( 'all' ) ) {
			if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
				wp_enqueue_style( 'xlwcty_faicons', plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'assets/fonts/fa.css', array(), XLWCTY_VERSION );
			}
			wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css' );
			wp_enqueue_style( 'xlwcty-admin-app', $this->get_admin_url() . '/assets/css/xlwcty-admin-app.css', array(), XLWCTY_VERSION );
			wp_enqueue_style( 'xl-chosen-css', $this->get_admin_url() . '/assets/css/chosen.css', array(), XLWCTY_VERSION );
			wp_enqueue_style( 'xl-confirm-css', $this->get_admin_url() . '/assets/css/jquery-confirm.min.css', array(), XLWCTY_VERSION );

			wp_register_script( 'xl-chosen', $this->get_admin_url() . '/assets/js/chosen/chosen.jquery.min.js', array( 'jquery' ), XLWCTY_VERSION );
			wp_register_script( 'xl-ajax-chosen', $this->get_admin_url() . '/assets/js/chosen/ajax-chosen.jquery.min.js', array( 'jquery', 'xl-chosen' ), XLWCTY_VERSION );

			if ( is_rtl() ) {
				wp_register_script( 'xl-chosen-rtl', $this->get_admin_url() . '/assets/js/chosen/chosen-rtl.min.js', array(), XLWCTY_VERSION );
				wp_enqueue_script( 'xl-chosen-rtl' );
			}
			wp_enqueue_script( 'xl-ajax-chosen' );
			wp_enqueue_script( 'xlwcty-admin-app', $this->get_admin_url() . '/assets/js/xlwcty-admin-app.min.js', array(
				'jquery',
				'jquery-ui-datepicker',
				'underscore',
				'backbone',
				'xl-ajax-chosen',
			), XLWCTY_VERSION );
			wp_enqueue_script( 'xl-confirm-js', $this->get_admin_url() . '/assets/js/jquery-confirm.min.js', array( 'jquery' ), XLWCTY_VERSION );

			wp_register_script( 'xlwcty-angular-js', $this->get_admin_url() . '/assets/js/angular.min.js', array( 'jquery' ), XLWCTY_VERSION );
			wp_register_script( 'xlwcty-angular-app-js', $this->get_admin_url() . '/assets/js/angular-app.js', array( 'xlwcty-angular-js' ), XLWCTY_VERSION );
			wp_enqueue_script( 'xlwcty-angular-js' );
			wp_enqueue_script( 'xlwcty-angular-app-js' );
			$data = array(
				'ajax_nonce'            => wp_create_nonce( 'xlwctyaction-admin' ),
				'plugin_url'            => plugin_dir_url( XLWCTY_PLUGIN_FILE ),
				'ajax_url'              => admin_url( 'admin-ajax.php' ),
				'admin_url'             => admin_url(),
				'ajax_chosen'           => wp_create_nonce( 'json-search' ),
				'search_products_nonce' => wp_create_nonce( 'search-products' ),
				'text_or'               => __( 'or', 'woo-thank-you-page-nextmove-lite' ),
				'text_apply_when'       => __( 'Open this page when these conditions are matched', 'woo-thank-you-page-nextmove-lite' ),
				'remove_text'           => __( 'Remove', 'woo-thank-you-page-nextmove-lite' ),
			);
			wp_localize_script( 'xlwcty-admin-app', 'xlwctyParams', $data );
		}

		if ( $this->is_builder_page ) {
			add_filter( 'wp_default_editor', function ( $editor ) {
				return 'html';
			}, 999 );
		}
	}

	public function xlwcty_post_publish_box() {
		global $post;
		if ( XLWCTY_Common::get_thank_you_page_post_type_slug() !== $post->post_type ) {
			return;
		}
		$trigger_status = 'Activated';
		if ( $post->post_status === XLWCTY_SHORT_SLUG . 'disabled' ) {
			$trigger_status = 'Deactivated';
		}
		if ( $post->post_date ) {
			$date_format  = get_option( 'date_format' );
			$date_format  = $date_format ? $date_format : 'M d, Y';
			$publich_date = date( $date_format, strtotime( $post->post_date ) );
		}
		if ( $post->post_status !== 'auto-draft' ) {
			?>
            <div class="misc-pub-section misc-pub-post-status xlwcty_always_show">
                Status: <span id="post-status-display"><?php echo $trigger_status; ?></span>
            </div>
			<?php
		}
		if ( $post->post_date ) {
			?>
            <div class="misc-pub-section curtime misc-pub-curtime xlwcty_always_show">
                <span id="timestamp">Added on: <b><?php echo $publich_date; ?></b></span>
            </div>
			<?php
		}
	}

	public function xlwcty_edit_form_top() {
		global $post;

		if ( XLWCTY_Common::get_thank_you_page_post_type_slug() !== $post->post_type ) {
			return;
		}
		?>
        <div class="notice">
            <p><?php _e( 'Back to <a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) . '">' . XLWCTY_FULL_NAME . '</a> listing.', 'woo-thank-you-page-nextmove-lite' ); ?></p>
        </div>
		<?php
	}

	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages[ XLWCTY_Common::get_thank_you_page_post_type_slug() ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => sprintf( __( 'Thankyou Pages updated.', 'woo-thank-you-page-nextmove-lite' ), admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) ),
			2  => __( 'Custom field updated.', 'woo-thank-you-page-nextmove-lite' ),
			3  => __( 'Custom field deleted.', 'woo-thank-you-page-nextmove-lite' ),
			4  => sprintf( __( 'Thankyou Pages updated. ', 'woo-thank-you-page-nextmove-lite' ), admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Trigger restored to revision from %s', 'woo-thank-you-page-nextmove-lite' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( __( 'Thankyou Pages updated. ', 'woo-thank-you-page-nextmove-lite' ), admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) ),
			7  => sprintf( __( 'Trigger saved. ', 'woo-thank-you-page-nextmove-lite' ), admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) ),
			8  => sprintf( __( 'Thankyou Pages updated. ', 'woo-thank-you-page-nextmove-lite' ), admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) ),
			9  => sprintf( __( 'Trigger scheduled for: <strong>%1$s</strong>.', 'woo-thank-you-page-nextmove-lite' ), date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ) ),
			10 => __( 'Trigger draft updated.', 'woo-thank-you-page-nextmove-lite' ),
			11 => sprintf( __( 'Thankyou Pages updated. ', 'woo-thank-you-page-nextmove-lite' ), admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '' ) ),
		);

		return $messages;
	}

	public function maybe_activate_post() {
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'xlwcty-post-activate' ) {
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'xlwcty-post-activate' ) ) {

				$postID  = filter_input( INPUT_GET, 'postid' );
				$section = filter_input( INPUT_GET, 'trigger' );
				if ( $postID ) {
					wp_update_post( array(
						'ID'          => $postID,
						'post_status' => 'publish',
					) );
					$redirect_url = admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '&section=' . $section );
					if ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {
						$redirect_url = add_query_arg( array(
							'paged' => $_GET['paged'],
						), $redirect_url );
					}

					wp_safe_redirect( $redirect_url );
				}
			} else {
				die( __( 'Unable to Activate', 'woo-thank-you-page-nextmove-lite' ) );
			}
		}
	}

	public function maybe_deactivate_post() {
		if ( isset( $_GET['action'] ) && $_GET['action'] === 'xlwcty-post-deactivate' ) {

			if ( wp_verify_nonce( $_GET['_wpnonce'], 'xlwcty-post-deactivate' ) ) {

				$postID  = filter_input( INPUT_GET, 'postid' );
				$section = filter_input( INPUT_GET, 'trigger' );
				if ( $postID ) {

					wp_update_post( array(
						'ID'          => $postID,
						'post_status' => XLWCTY_SHORT_SLUG . 'disabled',
					) );
					$redirect_url = admin_url( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() . '&section=' . $section );
					if ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {
						$redirect_url = add_query_arg( array(
							'paged' => $_GET['paged'],
						), $redirect_url );
					}

					wp_safe_redirect( $redirect_url );
				}
			} else {
				die( __( 'Unable to Deactivate', 'woo-thank-you-page-nextmove-lite' ) );
			}
		}
	}

	public function save_menu_order( $post_id, $post = null ) {
		//Check it's not an auto save routine
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		//Perform permission checks! For example:
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( class_exists( 'XL_Transient' ) ) {
			$xl_transient_obj = XL_Transient::get_instance();
		}

		//Check your nonce!
		//If calling wp_update_post, unhook this function so it doesn't loop infinitely
		remove_action( 'save_post_' . XLWCTY_Common::get_thank_you_page_post_type_slug(), array( $this, 'save_menu_order' ) );
		if ( $post !== null ) {
			if ( $post && $post->post_type === XLWCTY_Common::get_thank_you_page_post_type_slug() ) {
				/**
				 * Save external thank you page id in option.
				 */
				if ( isset( $_POST['xlwcty_custom_thank_you_page'] ) ) {
					$custom_pages             = get_option( 'xlwcty_custom_thank_you_pages', array() );
					$custom_pages[ $post_id ] = $_POST['xlwcty_custom_thank_you_page'];
					update_option( 'xlwcty_custom_thank_you_pages', $custom_pages );
				}

				/**
				 * Update menu order and post content for thank you page
				 */
				if ( isset( $_POST['_xlwcty_menu_order'] ) ) {
					if ( empty( $post->post_content ) ) {
						/**
						 * Check for Elementor plugin
						 */
						$check_elementor_builder = get_post_meta( $post_id, '_elementor_edit_mode', true );
						if ( empty( $check_elementor_builder ) ) {
							wp_update_post( array(
								'ID'           => $post_id,
								'menu_order'   => $_POST['_xlwcty_menu_order'],
								'post_content' => '[xlwcty_load]',
							) );
						} else {
							wp_update_post( array(
								'ID'         => $post_id,
								'menu_order' => $_POST['_xlwcty_menu_order'],
							) );
						}
					} else {
						wp_update_post( array(
							'ID'         => $post_id,
							'menu_order' => $_POST['_xlwcty_menu_order'],
						) );
					}
				}

				if ( class_exists( 'XL_Transient' ) ) {
					$xl_transient_obj->delete_all_transients( 'nextmove' );
				}

				// flushing object cache
				wp_cache_flush();
			}
		}
		// re-hook this function
		add_action( 'save_post_' . XLWCTY_Common::get_thank_you_page_post_type_slug(), array( $this, 'save_menu_order' ) );
	}

	/**
	 * dequeue script from single thank_you_page page
	 * @global type $wp_scripts
	 */
	public function xlwcty_wp_print_scripts() {
		global $wp_scripts;
		if ( XLWCTY_Common::is_load_admin_assets( 'listing' ) ) {
			?>
            <style>
                .wrap.woocommerce p.submit, p.submit {
                    display: none
                }

                #xlwcty_MB_ajaxContent ol {
                    font-weight: bold
                }
            </style>
			<?php
		}
	}

	public function xlwcty_wc_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'Thank You Page', 'woo-thank-you-page-nextmove-lite' ), __( 'Thank You Page', 'woo-thank-you-page-nextmove-lite' ), 'manage_woocommerce', 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug(), false );
	}

	public function xlwcty_add_mergetag_text() {

		if ( XLWCTY_Common::is_load_admin_assets( 'all' ) ) {
			?>
            <div style="display:none;" class="xlwcty_tb_content" id="xlwcty_merge_tags_invenotry_bar_help">
                <h3>Order Merge Tags</h3>
                <table class="table widefat">
                    <thead>
                    <tr>
                        <td> Name</td>
                        <td> Syntax</td>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( XLWCTY_Dynamic_Merge_Tags::get_all_tags() as $tag ) : ?>
                        <tr>
                            <td>
								<?php echo $tag['name']; ?>
                            </td>
                            <td>
                                <input type="text" style="width: 75%;" onClick="this.select()" readonly
                                       value='<?php echo '{{' . $tag['tag'] . '}}'; ?>'/>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    <tr>
                        <td>Order Meta</td>
                        <td>
                            <input type="text" style="width: 75%;" onClick="this.select()" readonly
                                   value='{{order_meta key="order_meta_key" label="Meta Key Label: "}}'/>
                            <p>
                                'key' is a required field.<br/>
                                'label' is an optional field. If given then will only display if the key has value.
                            </p>
                        </td>
                    </tr>
					<?php
					/** If JILT exists and allows user registration for guest users */
					if ( function_exists( 'wc_jilt' ) && wc_jilt()->get_integration()->allow_post_checkout_registration() ) {
						?>
                        <tr>
                            <td>Jilt User Registration</td>
                            <td>
                                <input type="text" style="width: 75%;" onClick="this.select()" readonly value='{{jilt_post_registration_html}}'/>
                                <p>
                                    This will output the Jilt registration html for guest user.<br/>
                                </p>
                            </td>
                        </tr>
						<?php
					}
					?>
                    </tbody>
                </table>
                <h3>Basic Merge Tags</h3>
                <table class="table widefat">
                    <thead>
                    <tr>
                        <td> Name</td>
                        <td> Syntax</td>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( XLWCTY_Static_Merge_Tags::get_all_tags() as $tag ) : ?>
                        <tr>
                            <td>
								<?php echo $tag['name']; ?>
                            </td>
                            <td>
                                <input type="text" style="width: 75%;" onClick="this.select()" readonly
                                       value='<?php echo '{{' . $tag['tag'] . '}}'; ?>'/>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
			<?php
		}
	}

	public function xlwcty_plugin_row_actions( $links, $file ) {
		if ( $file == XLWCTY_PLUGIN_BASENAME ) {
			$links[] = '<a href="' . add_query_arg( array(
					'utm_source'   => 'nextmove-lite',
					'utm_campaign' => 'plugin-action',
					'utm_medium'   => 'text-click',
					'utm_term'     => 'Docs',
				), 'https://xlplugins.com/documentation/nextmove-woocommerce-thank-you-page/' ) . '">' . esc_html__( 'Docs', 'woo-thank-you-page-nextmove-lite' ) . '</a>';
			$links[] = '<a href="' . admin_url( 'admin.php?page=xlplugins&tab=support' ) . '">' . esc_html__( 'Support', 'woo-thank-you-page-nextmove-lite' ) . '</a>';
			$links[] = '<a href="https://wordpress.org/support/view/plugin-reviews/woo-thank-you-page-nextmove-lite/" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> Vote!</a>';
		}

		return $links;
	}

	public function xlwcty_admin_components_fields() {
		return include __DIR__ . '/includes/cmb2-wcthankyou-meta-config.php';
	}

	/**
	 * Used on single thank you page to display build component button
	 */
	public function xlwcty_add_editor_button() {
		if ( XLWCTY_Common::is_load_admin_assets( 'single' ) && filter_input( INPUT_GET, 'post' ) !== null ) {
			?>
            <div class="xlwcty-builder-button-wrap">
                <button id="xlwcty-builder-button-primary" class="xlwcty-builder-button button button-primary button-hero xlwcsy_nav_editor">
                    <i class=""></i>
					<?php _e( '<span>XL</span> Manage Components', 'woo-thank-you-page-nextmove-lite' ); ?>
                </button>
            </div>
			<?php
		}
	}

	public function xlwcty_builder() {
		$page_title = __( 'Thank You Page Builder', 'woo-thank-you-page-nextmove-lite' );
		add_menu_page( $page_title, $page_title, 'manage_woocommerce', 'xlwcty_builder', array( $this, 'xlwcty_admin_page_builder_render' ) );
	}

	public function xlwcty_admin_page_builder_render() {
		if ( ! isset( $_GET['id'] ) ) {
			wp_safe_redirect( 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug() );
			exit;
		}
		?>
        <div id="poststuff" class="wrap xlwcty_builder_wide_wrap">
            <div class="xlwcty_builder_right_wrap" ng-app="xlwcty-builder" ng-controller="xlwcty_builder_pre">
                <div class="postbox xlwcty_inner_height">
                    <div class="inside">
                        <div style="display:none" class="xlwcty_freeze_screen"></div>
						<?php echo XLWCTY_Admin_Post_Options::xlwcty_builder_html(); ?>
                    </div>
                </div>
            </div>
            <div class="postbox-container xlwcty_builder_left_wrap">
                <div class="xlwcty_builder_left_wrap_gap">
                    <div id="xlwcty_metabox_customizer_settings">
                        <div class="postbox xlwcty_inner_height">
                            <div class="inside">
                                <div class="xlwcty_metabox_head"><span>2</span> Edit Component</div>
                                <div class="xlwcty_h20"></div>
                                <div style="display:none" class="xlwcty_freeze_screen"></div>
								<?php cmb2_metabox_form( 'xlwcty_builder_settings', $_GET['id'] ); ?>
                            </div>
                        </div>
                    </div>
                    <div id="xlwcty_metabox_field_settings">
                        <div class="postbox xlwcty_inner_height">
							<?php
							$first_display = get_option( 'xlwcty_scroll_components', false );
							if ( $first_display === false ) {
								?>
                                <div class="xlwcty_below_indication">
                                    <div class="xlwcty_b">
                                        <div class="xlwcty_h">Scroll Down to see all Components</div>
                                        <img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/scroll-down.gif'; ?>" width="100"/>
                                    </div>
                                </div>
								<?php
								update_option( 'xlwcty_scroll_components', date( 'Y-m-d H:i:s' ), false );
							}
							?>
                            <div class="inside">
                                <div class="xlwcty_metabox_head"><span>1</span> Select Components</div>
                                <div class="xlwcty_clear_20"></div>
								<?php echo XLWCTY_Admin_Post_Options::xlwcty_button_html(); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clear"></div>
        </div>
		<?php
	}

	public function xlwcty_add_cmb2_multiselect() {
		include_once $this->get_admin_uri() . 'includes/cmb2-addons/multiselect/CMB2_Type_MultiSelect.php';
	}

	public function xlwcty_add_cmb2_post_select() {
		include_once $this->get_admin_uri() . 'includes/cmb2-addons/post-select/CMB2_Type_PostSelect.php';
	}

	/**
	 * Hooked over `cmb2_render_xlwcty_multiselect`
	 * Render Html for `xlwcty_multiselect` Field
	 *
	 * @param $field CMB@ Field object
	 * @param $escaped_value Value
	 * @param $object_id object ID
	 * @param $object_type Object Type
	 * @param $field_type_object Field Type Object
	 */
	public function xlwcty_multiselect( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		$field_obj = new CMB2_Type_XLWCTY_MultiSelect( $field_type_object );
		echo $field_obj->render();
	}

	/**
	 * Hooked over `cmb2_render_xlwcty_post_select`
	 * Render Html for `xlwcty_xlwcty_post_select` Field
	 *
	 * @param $field CMB@ Field object
	 * @param $escaped_value Value
	 * @param $object_id object ID
	 * @param $object_type Object Type
	 * @param $field_type_object Field Type Object
	 */
	public function xlwcty_post_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		$field_obj = new CMB2_Type_XLWCTY_PostSelect( $field_type_object );
		echo $field_obj->render();
	}

	public function xlwcty_admin_head() {
		if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
			add_filter( 'screen_options_show_screen', '__return_false' );
			$this->get_xlwcty_loading_screen();
		}
	}

	public function xlwcty_admin_footer() {
		if ( XLWCTY_Common::is_load_admin_assets( 'builder' ) ) {
			$this->get_xlwcty_sticky_html();
		}
	}

	public function get_xlwcty_sticky_html() {
		if ( isset( $_GET['id'] ) ) {
			$thank_you_page_title = get_the_title( $_GET['id'] );
			$mode_html            = '<span class="xlwcty_plugin_mode_wrap"> <strong>Mode:</strong> ';
			$last_order           = $this->get_last_order( $_GET['id'] );
			$default_settings     = XLWCTY_Core()->data->get_option();
			if ( isset( $default_settings['xlwcty_preview_mode'] ) && $default_settings['xlwcty_preview_mode'] == 'sandbox' ) {
				$mode_html .= '<span class="xlwcty_plugin_mode_sandbox">Sandbox</span>';
			} else {
				$mode_html .= '<span class="xlwcty_plugin_mode_live">Live</span>';
			}
			//$mode_html .= '</span> (<a target="_blank" href="' . admin_url( "admin.php?page=wc-settings&tab=xl-thank-you&section=settings" ) . '">Change</a>)';
			ob_start();
			?>
            <div class="xlwcty_admin_sticky_bar">
                <div class="xlwcty_table">
                    <div class="xlwcty_table_cell">
                        <div class="xlwcty_fl_lt">
                            <a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=xl-thank-you' ); ?>" class="button"><i class="dashicons dashicons-arrow-left-alt" style="
	margin-top: 2px;
"></i></a>
							<?php
							echo $thank_you_page_title ? '<div class="xlwcty_pagename"><strong>Now Editing:</strong> ' . $thank_you_page_title . '</div>' : '';
							echo $mode_html;
							?>
                        </div>
                        <div class="xlwcty_fl_rt">
							<?php
							$templates = wp_get_theme()->get_page_templates( null, 'page' );
							if ( is_array( $templates ) && count( $templates ) > 0 ) {
								$choosed_template = get_post_meta( $_GET['id'], '_wp_page_template', true );
								?>
                                <form target="_blank" id="choose_template_form" action="<?php echo get_the_permalink( $_GET['id'] ); ?>">
                                    <label>Template &nbsp;</label>
                                    <select id="_xlwcty_choose_template" data-placeholder="<?php echo __( 'Choose a Template' ); ?>">
										<?php
										echo "<option value='default'>Default</option>";
										foreach ( $templates as $key => $val ) {
											$selected = '';
											if ( $choosed_template == $key ) {
												$selected = 'selected';
											}
											echo "<option value='{$key}'  {$selected}>" . $val . '</option>';
										}
										?>
                                    </select>
                                </form>
								<?php
							}
							$order_preview_id = get_post_meta( $_GET['id'], '_xlwcty_chosen_order_preview', true );
							$permalink_str    = get_option( 'permalink_structure' );
							$preview_action   = get_the_permalink( $_GET['id'] );
							if ( $permalink_str == '' ) {
								$preview_action = site_url();
							}
							?>

                            <form target="_blank" id="order_preview_form" action="<?php echo $preview_action; ?>">
                                <label>Order &nbsp;</label>
								<?php if ( $permalink_str == '' ) { ?>
                                    <input type="hidden" name="xlwcty_thankyou" value="thank-you"/>
									<?php
								}
								?>
								<?php
								if ( class_exists( 'SitePress' ) ) {
									$post_language_information = wpml_get_language_information( null, $_GET['id'] );
									if ( is_array( $post_language_information ) && count( $post_language_information ) > 0 ) {
										echo '<input type="hidden" name="lang" value="' . $post_language_information['language_code'] . '"/>';
									}
								}
								?>
                                <select class="preview_order_id" data-pre-data='<?php echo wp_json_encode( $this->get_last_order( $_GET['id'], true ) ); ?>'
                                        data-placeholder="<?php echo __( 'Choose an Order' ); ?>">
									<?php
									if ( count( $last_order ) > 0 ) {
										$get_chosen_order = get_post_meta( $_GET['id'], '_xlwcty_chosen_order_preview', true );

										foreach ( $last_order as $key => $val ) {
											$string = '';
											if ( $get_chosen_order == $val['value'] ) {
												$string = 'selected="selected"';
											}
											echo '<option ' . $string . " value='" . $val['key'] . '||' . $val['value'] . "'>" . $val ['label'] . '</option>';
										}
									}
									?>
                                </select>
                                <input type="hidden" name="mode" value="preview">
                                <input type="hidden" name="key" value=""/>
                                <input type="hidden" name="order_id" value=""/>
                                <input type="submit" class="button button-danger" value="PREVIEW">
                            </form>

                            <a class="button button-primary builder_fields_save">SAVE</a>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			echo ob_get_clean();
		}
	}

	public function get_xlwcty_loading_screen() {
		if ( isset( $_GET['id'] ) ) {
			?>
            <div style="position:fixed;top:0;right:0;bottom:0;left:0;background-color:#fff;z-index:999999;  width: 100vw; height: 100vh;" class="xlwcty_screen_load">
                <div class="xlwcty_screen_wrap">
                    <div class="xlwcty_load_pattern"></div>
                    <div class="xlicon"></div>
					<?php if ( isset( $_COOKIE['xlwcty_preview_data'] ) && $_COOKIE['xlwcty_preview_data'] !== '' ) { ?>
                        <div class="xlwcty_load_info"><?php _e( "Your preview will open in new window automatically.<br/>If it doesn't click on Preview button again or allow Pop-ups for your domain.", 'woo-thank-you-page-nextmove-lite' ); ?></div>
                        <a style="visibility:hidden;" target="_blank" href="<?php echo $_COOKIE['xlwcty_preview_data']; ?>"></a>
					<?php } ?>
                </div>
            </div>
			<?php
			echo ob_get_clean();
		}
	}

	public function get_last_order( $id, $is_data = false ) {
		$data     = array();
		$pre_data = array();
		$args     = array(
			'status' => XLWCTY_Core()->data->get_option( 'allowed_order_statuses' ),
			'limit'  => 10,
		);

		$orders = wc_get_orders( $args );

		$get_chosen_order = get_post_meta( $id, '_xlwcty_chosen_order_preview', true );
		if ( ! empty( $get_chosen_order ) && $get_chosen_order > 0 ) {
			$selected_order = wc_get_order( $get_chosen_order );

			if ( $selected_order instanceof WC_Order ) {
				$order_key    = XLWCTY_Compatibility::get_order_data( $selected_order, 'order_key' );
				$order_status = wc_get_order_status_name( $selected_order->get_status() );
				$label        = '#' . XLWCTY_Compatibility::get_order_id( $selected_order ) . ' (' . $order_status . ') ' . XLWCTY_Compatibility::get_order_data( $selected_order, 'billing_email' ) . '';
				$data[]       = array(
					'key'   => $order_key,
					'label' => $label,
					'value' => XLWCTY_Compatibility::get_order_id( $selected_order ),
				);
			}
		}

		if ( is_array( $orders ) && count( $orders ) > 0 ) {
			foreach ( $orders as $order ) {

				if ( XLWCTY_Compatibility::get_order_id( $order ) == $get_chosen_order ) {
					continue;
				}
				$order_key    = XLWCTY_Compatibility::get_order_data( $order, 'order_key' );
				$order_status = wc_get_order_status_name( $order->get_status() );

				$label  = '#' . XLWCTY_Compatibility::get_order_id( $order ) . ' (' . $order_status . ') ' . XLWCTY_Compatibility::get_order_data( $order, 'billing_email' ) . '';
				$data[] = array(
					'key'   => $order_key,
					'label' => $label,
					'value' => XLWCTY_Compatibility::get_order_id( $order ),
				);
				if ( $is_data ) {
					$pre_data[] = array(
						'text'  => $label,
						'value' => XLWCTY_Compatibility::get_order_id( $order ),
					);
				}
			}
		}
		if ( $is_data ) {
			return $pre_data;
		}

		return $data;
	}

	public function remove_nav_pages() {
		global $menu;
		$new_menu = array();
		if ( is_array( $menu ) && count( $menu ) > 0 ) {
			foreach ( $menu as $key => $single_menu ) {
				if ( $single_menu[2] === 'xlwcty_builder' || $single_menu[2] === 'xlwcty_settings_admin_menu' ) {
					continue;
				}
				$new_menu[ $key ] = $single_menu;
			}
			$menu = $new_menu;
		}
	}

	public function maybe_redirect_after_new_post( $location, $post ) {
		if ( isset( $_COOKIE[ 'xlwcty_is_new_post_' . $post ] ) ) {
			return admin_url( 'admin.php?page=xlwcty_builder&id=' . $post );
		}

		return $location;
	}

	public function maybe_builder_page() {

		if ( filter_input( INPUT_GET, 'page' ) == 'xlwcty_builder' && filter_input( INPUT_GET, 'id' ) !== null ) {
			$this->is_builder_page = true;
		}
	}

	public function deque_wc_settings_javascript( $array ) {

		if ( XLWCTY_Common::is_load_admin_assets( 'settings' ) || XLWCTY_Common::is_load_admin_assets( 'debug' ) ) {
			wp_dequeue_script( 'woocommerce_settings' );
		}

		return $array;
	}


	/**
	 * @hooked over 'admin_footer'
	 * Shows notice and checks for permalink state for the thank you page we have created
	 */
	public function maybe_show_notice_for_page_not_found() {

		$args = array(
			'post_type'        => XLWCTY_Common::get_thank_you_page_post_type_slug(),
			'post_status'      => 'publish',
			'nopaging'         => true,
			'meta_key'         => '_xlwcty_menu_order',
			'orderby'          => 'meta_value_num',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
			'showposts'        => 1,
		);

		$get_posts = get_posts( $args );

		if ( $get_posts && is_array( $get_posts ) && count( $get_posts ) > 0 ) {
			$get_link = get_permalink( $get_posts[0] );
			$remote   = wp_remote_head( $get_link );
			$response = wp_remote_retrieve_response_code( $remote );

			if ( $response == 404 ) {
				$this->show_permalink_notice();
			}
		}

	}


	/**
	 * @hooked over theme_{POST_TYPE}_templates
	 * Tells WordPress that our post type has same templates as posttype page has
	 *
	 * @param array $post_templates current templates found for the post types
	 * @param $object
	 * @param $post current post
	 * @param $post_type current post type
	 *
	 * @return array
	 */
	public function allow_page_templates_on_thankyou_post_types( $post_templates, $object, $post, $post_type ) {
		$all_templates = wp_get_theme()->get_post_templates();

		return isset( $all_templates['page'] ) ? $all_templates['page'] : array();
	}


	/**
	 * Renders notice for the wrong permalink structure
	 */
	public function show_permalink_notice() {
		?>
        <div id="message" class="notice notice-error">
            <p>
				<?php _e( sprintf( '<strong>Urgent Action Required: </strong>Reset the permalinks to make NextMove Thank You Page <i>visible</i>. Go to Settings-> Permalinks. <a class="button button-primary" href="%s"> Reset Permalinks</a> ', XLWCTY_FULL_NAME, admin_url( 'options-permalink.php' ) ), '' ); ?>
            </p>
        </div>
		<?php
	}


	/**
	 * Hooked over `cmb2_save_post_fields_xlwcty_global_settings`
	 * Let this function tell WPML to take the string in the string translation that is just saved.
	 *
	 * @param $post_id
	 */
	public function handle_icl_on_settings_save( $post_id ) {
		if ( function_exists( 'icl_register_string' ) && isset( $_POST['google_map_error_txt'] ) ) {

			icl_register_string( 'admin_texts_xlwcty_global_settings', $_POST['google_map_error_txt'] );

		}
	}


	public function maybe_show_paypal_notice() {
		$sections = array();
		if ( 'wc-settings' === filter_input( INPUT_GET, 'page' ) && 'xl-thank-you' === filter_input( INPUT_GET, 'tab' ) && 'settings' !== filter_input( INPUT_GET, 'section' ) ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
			foreach ( $payment_gateways as $k => $gateway ) {
				if ( 'yes' === $gateway->enabled ) {
					$sections[ $k ] = esc_html( $gateway->get_title() );

				}
			}
			if ( $sections && count( $sections ) > 0 ) {

				$get_paypal_state    = array_search( 'paypal', array_keys( $sections ) );
				$get_paypalpro_state = array_search( 'paypal_pro', array_keys( $sections ) );

				$get_all_allowed_stasuses = XLWCTY_Core()->data->get_option( 'allowed_order_statuses' );
				if ( ( $get_paypal_state > - 1 || - 1 < $get_paypalpro_state ) && false == in_array( 'wc-pending', $get_all_allowed_stasuses ) ) {
					?>
                    <div class="notice notice-error is-dismissible">
                        <p> <?php printf( '<strong>NextMove Notice</strong>: It seems you have PayPal payment gateway enabled. You need to select \'Pending Payment\' order status in NextMove settings to show \'NextMove Thank You\' page for orders process through PayPal.<br/><br/> <a href="%s" class="button">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=xl-thank-you&section=settings' ), __( 'Check Settings', 'woo-thank-you-page-nextmove-lite' ) ); ?>
                        </p></div>
					<?php
				}
			}
		}

	}

	public function xlwcty_save_component_screen( $current_screen ) {
		if ( is_object( $current_screen ) && isset( $current_screen->base ) && ( 'toplevel_page_xlwcty_builder' == $current_screen->base ) && isset( $_POST['submit-cmb'] ) ) {
			$xl_transient_obj = XL_Transient::get_instance();
			$xl_transient_obj->delete_all_transients( 'nextmove' );
			wp_cache_flush();

			do_action( 'xlwcty_after_save_component', $_POST );
		}
	}

	public function set_local_storage( $post_data ) {
		if ( is_array( $post_data ) && count( $post_data ) > 0 ) {
			?>
            <script>
                if (typeof (Storage) !== "undefined") {
                    var aa = new Date();
                    localStorage.setItem('xlwcty_local_storage', aa.getTime());
                }
            </script>
			<?php
		}
	}

	/**
	 * Change WordPress admin footer text on all NextMove pages
	 *
	 * @param $footer_text
	 *
	 * @return string
	 */
	public function admin_footer_text( $footer_text ) {
		if ( XLWCTY_Common::is_load_admin_assets( 'all' ) ) {
			$footer_text = 'If you like <strong>' . XLWCTY_FULL_NAME . '</strong>, please leave us a <a href="https://wordpress.org/support/plugin/woo-thank-you-page-nextmove-lite/reviews/?rate=5#new-post" target="_blank" class="wc-rating-link" data-rated="Thanks :)">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. A huge thanks in advance!';
		}

		return $footer_text;
	}

	/**
	 * Check the screen and check if plugins update available to show notification to the admin to update the plugin
	 */
	public function maybe_show_advanced_update_notification() {
		$screen = get_current_screen();

		if ( ! is_object( $screen ) ) {
			return;
		}

		if ( ! ( 'plugins.php' == $screen->parent_file || 'index.php' == $screen->parent_file || 'xl-thank-you' == filter_input( INPUT_GET, 'tab' ) ) ) {
			return;
		}

		// option to permanently hide the update notice
		$hide_notice = get_option( 'xlplugin_nextmove_lite_hide_update_notice', 'no' );

		if ( 'yes' === $hide_notice ) {
			return;
		}

		$plugins = get_site_transient( 'update_plugins' );
		if ( ! isset( $plugins->response ) || ! is_array( $plugins->response ) ) {
			return;
		}

		$plugins = array_keys( $plugins->response );

		if ( ! is_array( $plugins ) || count( $plugins ) <= 0 || ! in_array( XLWCTY_PLUGIN_BASENAME, $plugins ) ) {
			return;
		}

		?>
        <div class="xlplugin-notice-message notice notice-warning is-dismissible">
            <a class="xlplugin-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'xlplugin-nextmove-lite-update-notice', 'hide' ), 'xlplugin_nextmove_lite_update_notice_nonce', '_xlplugin_nextmove_lite_update_notice_nonce' ) ); ?>">
				<?php esc_html_e( 'Dismiss', 'woo-thank-you-page-nextmove-lite' ); ?>
            </a>
            <p>
				<?php
				_e( sprintf( 'Attention: There is an update available of <strong>%s</strong> plugin. &nbsp;<a href="%s" class="">Go to updates</a>', XLWCTY_FULL_NAME, admin_url( 'plugins.php?s=nextmove&plugin_status=all' ) ), 'woo-thank-you-page-nextmove-lite' );
				?>
            </p>
        </div>
        <style>
            div.xlplugin-notice-message {
                overflow: hidden;
                position: relative;
            }

            .xlplugin-notice-message a.xlplugin-message-close {
                position: static;
                float: right;
                padding: 0px 15px 5px 28px;
                margin-top: -10px;
                line-height: 14px;
                text-decoration: none;
            }

            .xlplugin-notice-message a.xlplugin-message-close:before {
                position: relative;
                top: 18px;
                left: -20px;
                transition: all .1s ease-in-out;
            }
        </style>
		<?php
	}

	/**
	 * Set option to hide xl plugin nextmove lite update notice
	 */
	public static function hide_plugins_update_notices() {

		if ( ! isset( $_GET['xlplugin-nextmove-lite-update-notice'] ) || ! isset( $_GET['_xlplugin_nextmove_lite_update_notice_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( $_GET['_xlplugin_nextmove_lite_update_notice_nonce'] ), 'xlplugin_nextmove_lite_update_notice_nonce' ) ) {
			wp_die( __( 'Action failed. Please refresh the page and retry.', 'woo-thank-you-page-nextmove-lite' ) );
		}

		update_option( 'xlplugin_nextmove_lite_hide_update_notice', 'yes' );

		$redirect_link = add_query_arg( array(
			'page' => 'wc-settings',
			'tab'  => 'xl-thank-you',
		), admin_url( 'admin.php' ) );

		wp_redirect( $redirect_link );
		exit;
	}

	/**
	 * @hooked over `cmb2 after field save`
	 *
	 * @param $post_id
	 */
	public function clear_transients( $post_id ) {
		if ( class_exists( 'XL_Transient' ) ) {
			$xl_transient_obj = XL_Transient::get_instance();
			$xl_transient_obj->delete_all_transients( 'nextmove' );
		}

		// flushing object cache
		wp_cache_flush();
	}

	/**
	 * @hooked over `delete_post`
	 *
	 * @param $post_id
	 */
	public function clear_transients_on_delete( $post_id ) {

		$get_post_type = get_post_type( $post_id );

		if ( XLWCTY_Common::get_thank_you_page_post_type_slug() === $get_post_type ) {
			if ( class_exists( 'XL_Transient' ) ) {
				$xl_transient_obj = XL_Transient::get_instance();
				$xl_transient_obj->delete_all_transients( 'nextmove' );
			}
			/**
			 * Flush on deletion
			 */
			wp_cache_flush();
		}
	}

	/**
	 * Remove CMB2 any style or script that have cmb2 name in the src
	 */
	public function removing_scripts_finale_campaign_load() {
		global $wp_scripts, $wp_styles;

		if ( false === XLWCTY_Common::is_load_admin_assets( 'single' ) ) {
			return;
		}

		$mod_wp_scripts = $wp_scripts;
		$assets         = $wp_scripts;

		if ( 'admin_print_styles' == current_action() ) {
			$mod_wp_scripts = $wp_styles;
			$assets         = $wp_styles;
		}

		if ( is_object( $assets ) && isset( $assets->registered ) && count( $assets->registered ) > 0 ) {
			foreach ( $assets->registered as $handle => $script_obj ) {
				if ( ! isset( $script_obj->src ) || empty( $script_obj->src ) ) {
					continue;
				}
				$src = $script_obj->src;

				/** Remove scripts of massive autonami plugin */
				if ( strpos( $src, 'wp-marketing-automations/modules/abandoned-cart/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** Remove scripts of massive VC addons plugin */
				if ( strpos( $src, 'mpc-massive/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** Remove scripts of wp editor plugin */
				if ( strpos( $src, 'wp-editor/' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** If script doesn't belong to a theplus_elementor_addon continue */
				if ( strpos( $src, 'plus-options/cmb2-conditionals.js' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** wp bakery vc-tab-min js conflict with thankyou rule js **/

				if ( strpos( $src, 'js_composer/assets/lib/vc_tabs/vc-tabs.min.js' ) !== false ) {
					unset( $mod_wp_scripts->registered[ $handle ] );
				}

				/** If no cmb2 in src continue */
				if ( strpos( $src, 'cmb2' ) === false ) {
					continue;
				}

				/** If script doesn't belong to a theme continue */
				if ( strpos( $src, 'themes/' ) === false ) {
					continue;
				}

				/** Unset cmb2 script */
				unset( $mod_wp_scripts->registered[ $handle ] );

			}
		}

		if ( 'admin_print_styles' === current_action() ) {
			$wp_styles = $mod_wp_scripts;
		} else {
			$wp_scripts = $mod_wp_scripts;
		}

	}

	public function maybe_remove_all_notices_on_page() {
		$page = filter_input( INPUT_GET, 'page' );
		if ( empty( $page ) ) {
			return;
		}
		if ( in_array( $page, [ 'xl-cart', 'xl-checkout', 'xl-automations', 'xl-payments' ], true ) ) {
			remove_all_actions( 'admin_notices' );
		}
	}
}

new xlwcty_Admin();

