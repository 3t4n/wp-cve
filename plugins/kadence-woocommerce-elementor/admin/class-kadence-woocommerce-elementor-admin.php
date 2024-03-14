<?php

defined( 'ABSPATH' ) or exit;

/**
 * Admin setup
 *
 */
class Kadence_Woocommerce_Elementor_Admin {

	/**
	* @var null
	*/
	private static $instance = null;


	/**
	* Instance Control
	*/
	public static function get_instance() {
		if ( is_null(  self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		// Add settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab'), 50 );
		// Add settings
		add_action( 'woocommerce_settings_tabs_kadence_template_builder', array( $this, 'settings' ) );
		// Save Settings
		add_action( 'woocommerce_update_options_kadence_template_builder', array( $this, 'update_settings' ) );

		//Built Custom Post types
		add_action( 'init', array( $this, 'elementor_woo_posttypes' ) );
		// Add Menu Item
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 50 );

		// Add Menu Item
		add_action( 'admin_menu', array( $this, 'register_template_admin_menu' ), 50 );

		// Make sure it's not public.
		add_action( 'template_redirect', array( $this, 'block_template_frontend' ) );

		// Use fullwidth template when editing
		add_filter( 'single_template', array( $this, 'load_fullwidth_template' ) );

		add_action( 'woocommerce_admin_field_kt_woo_ele_pro', array( $this, 'print_pro_upgrade_notice' ) );
	}

	public static function add_settings_tab( $settings_tabs ) {
		$settings_tabs['kadence_template_builder'] = __( 'Template Builder', 'kadence-woocommerce-elementor' );
		
		return $settings_tabs;
	}

	public static function settings() {
		woocommerce_admin_fields( self::get_settings() );
	}

	public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}

	public static function get_settings() {
		$settings = array(
			'section_title' => array(
				'name'     => __( 'Single Product Default Template', 'kadence-woocommerce-elementor' ),
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'wc_settings_kadence_single_template_builder_title'
			),
			'kt_woo_ele_single' => array(
				'name' => __( 'Choose a default single template', 'kadence-woocommerce-elementor' ),
				'type' => 'select',
				'default'  => 'default',
				'css'      => 'min-width: 350px;',
				'class'    => 'wc-enhanced-select',
				'id'   => 'kt_woo_ele_single_template_default',
				'options'  =>  self::get_posts_with_default( array( 'post_type' => 'ele-product-template', 'numberposts' => -1 ) )
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id' => 'wc_settings_kadence_single_template_builder_end'
			),
			'kt_woo_ele_pro' => array(
				'id'    => 'kt_woo_ele_pro_upgrade',
				'type'  => 'kt_woo_ele_pro',
			),
		);
		return apply_filters( 'wc_settings_kadence_template_builder', $settings );
	}
	public function print_pro_upgrade_notice( $options ) { ?>
		<div style="
	background: white;
    max-width: 500px;
    border: 2px solid #00b5e2;
    padding: 20px;">
			<h3 style="margin: 0;
    margin-bottom: 20px;">
				<?php _e('Kadence WooCommerce Elementor Pro', 'kadence-woocommerce-elementor'); ?>
			</h3>
				<p class="description" style="margin-bottom:15px;"><?php echo __('With Pro you can use elementor to control your shop and archive pages, as well as the checkout page.', 'kadence-woocommerce-elementor');?></p>
				<a href="https://www.kadencethemes.com/product/kadence-woocommerce-elementor/" style="
    padding: 6px 10px;
    background: #00b5e2;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    ">
						<?php _e('Learn More', 'kadence-woocommerce-elementor'); ?>
				</a>
		</div>
		<?php
	}
	public static function get_posts_with_default( $query_args ) {
		$args = wp_parse_args( $query_args, array(
			'post_type'   => 'post',
			'numberposts' => -1,
		) );

		$posts = get_posts( $args );

		$post_options = array();
		$post_options['default'] = __('Woocommerce Default', 'kadence-woo-template-builder');
		if ( $posts ) {
			foreach ( $posts as $post ) {
				$post_options[$post->ID] = $post->post_title;
			}
		}

		return $post_options;
	}
	/**
	 * Register Post type for woo elementor templates
	 */
	public function elementor_woo_posttypes() {

		$labels = array(
			'name'               => __( 'Woo Product Template', 'kadence-woocommerce-elementor' ),
			'singular_name'      => __( 'Elementor Product Template', 'kadence-woocommerce-elementor' ),
			'menu_name'          => __( 'Elementor Product Template', 'kadence-woocommerce-elementor' ),
			'name_admin_bar'     => __( 'Elementor Product Template', 'kadence-woocommerce-elementor' ),
			'add_new'            => __( 'Add New', 'kadence-woocommerce-elementor' ),
			'add_new_item'       => __( 'Add New Product Template', 'kadence-woocommerce-elementor' ),
			'new_item'           => __( 'New Product Template', 'kadence-woocommerce-elementor' ),
			'edit_item'          => __( 'Edit Product Template', 'kadence-woocommerce-elementor' ),
			'view_item'          => __( 'View Product Template', 'kadence-woocommerce-elementor' ),
			'all_items'          => __( 'All Elementor Product Templates', 'kadence-woocommerce-elementor' ),
			'search_items'       => __( 'Search Product Templates', 'kadence-woocommerce-elementor' ),
			'parent_item_colon'  => __( 'Parent Product Templates:', 'kadence-woocommerce-elementor' ),
			'not_found'          => __( 'No Product Templates found.', 'kadence-woocommerce-elementor' ),
			'not_found_in_trash' => __( 'No Product Templates found in Trash.', 'kadence-woocommerce-elementor' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'revisions', 'elementor' ),
		);

		register_post_type( 'ele-product-template', $args );
	}

	/**
	 * Register the admin menu for settings.
	 */
	public function register_admin_menu() {
		add_submenu_page(
			'woocommerce',
			__( 'Template Builder', 'kadence-woocommerce-elementor' ),
			__( 'Template Builder', 'kadence-woocommerce-elementor' ),
			'edit_pages',
			'admin.php?page=wc-settings&tab=kadence_template_builder'
		);
	}
	/**
	 * Register the admin menu for producy template.
	 */
	public function register_template_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=product',
			__( 'Product Templates', 'kadence-woocommerce-elementor' ),
			__( 'Product Templates', 'kadence-woocommerce-elementor' ),
			'edit_pages',
			'edit.php?post_type=ele-product-template'
		);
	}

	/**
	 * Don't display the elementor templates on the frontend for non edit_posts capable users.
	 *
	 * @since  1.0.0
	 */
	public function block_template_frontend() {
		if ( is_singular( 'ele-product-template' ) && ! current_user_can( 'edit_posts' ) ) {
			wp_redirect( site_url(), 301 );
			die;
		}
	}

	/**
	 * Single template function which will choose our template
	 *
	 *
	 * @param  String $single_template Single template.
	 */
	function load_fullwidth_template( $single_template ) {

		global $post;

		if ( 'ele-product-template' == $post->post_type ) {

			$elementor_2_0_header_footer = ELEMENTOR_PATH . '/modules/page-templates/templates/header-footer.php';

			if ( file_exists( $elementor_2_0_header_footer ) ) {
				return $elementor_2_0_header_footer;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/header-footer.php';
			}
		}

		return $single_template;
	}

}

Kadence_Woocommerce_Elementor_Admin::get_instance();
