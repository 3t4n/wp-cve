<?php
/**
 * NS Category Widget Admin.
 *
 * @package NS_Category_Widget
 */

use Nilambar\Optioner\Optioner;

/**
 * NS Category Widget Admin Class.
 *
 * @since 1.0.0
 */
class NS_Category_Widget_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		$plugin = NS_Category_Widget::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		$this->options = $plugin->get_options_array();

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Add admin notice.
		add_action( 'admin_init', array( $this, 'setup_custom_notice' ) );

		if ( true === rest_sanitize_boolean( $this->options['nscw_field_enable_ns_category_widget'] ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'nscw_scripts_enqueue' ) );
			add_action( 'wp_ajax_populate_categories', array( $this, 'ajax_populate_categories' ) );
			add_action( 'wp_ajax_nopriv_populate_categories', array( $this, 'ajax_populate_categories' ) );
		}

		add_action( 'optioner_admin_init', array( $this, 'register_options' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_settings_assets' ) );

		add_action( 'wp_ajax_nopriv_nscw_nsbl_get_posts', array( $this, 'get_posts_ajax_callback' ) );
		add_action( 'wp_ajax_nscw_nsbl_get_posts', array( $this, 'get_posts_ajax_callback' ) );
	}

	public function register_options() {
		$obj = new Optioner();

		$obj->set_page(
			array(
				'page_title'    => esc_html__( 'NS Category Widget', 'ns-category-widget' ),
				'page_subtitle' => sprintf( esc_html__( 'Version: %s', 'ns-category-widget' ), NS_CATEGORY_WIDGET_VERSION ),
				'menu_title'    => esc_html__( 'NS Category Widget', 'ns-category-widget' ),
				'capability'    => 'manage_options',
				'menu_slug'     => 'ns-category-widget',
				'option_slug'   => 'nscw_plugin_options',
			)
		);

		$obj->set_quick_links(
			array(
				array(
					'text' => 'Plugin Page',
					'url'  => 'https://www.nilambar.net/2013/12/ns-category-widget-wordpress-plugin.html',
					'type' => 'primary',
				),
				array(
					'text' => 'Get Support',
					'url'  => 'https://wordpress.org/support/plugin/ns-category-widget/#new-post',
					'type' => 'secondary',
				),
			)
		);

		// Tab: nscw_settings_tab.
		$obj->add_tab(
			array(
				'id'    => 'nscw_settings_tab',
				'title' => esc_html__( 'Settings', 'ns-category-widget' ),
			)
		);

		// Field: nscw_field_enable_ns_category_widget.
		$obj->add_field(
			'nscw_settings_tab',
			array(
				'id'        => 'nscw_field_enable_ns_category_widget',
				'type'      => 'toggle',
				'title'     => esc_html__( 'Enable NS Category Widget', 'ns-category-widget' ),
				'default'   => true,
			)
		);

		// Field: nscw_field_enable_tree_script.
		$obj->add_field(
			'nscw_settings_tab',
			array(
				'id'        => 'nscw_field_enable_tree_script',
				'type'      => 'toggle',
				'title'     => esc_html__( 'Enable Tree Script', 'ns-category-widget' ),
				'default'   => true,
			)
		);

		// Field: nscw_field_enable_tree_style.
		$obj->add_field(
			'nscw_settings_tab',
			array(
				'id'        => 'nscw_field_enable_tree_style',
				'type'      => 'toggle',
				'title'     => esc_html__( 'Enable Tree Style', 'ns-category-widget' ),
				'default'   => true,
			)
		);

		// Sidebar.
		$obj->set_sidebar(
			array(
				'render_callback' => array( $this, 'render_sidebar' ),
			)
		);

		// Run now.
		$obj->run();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Enqueue widget scripts.
	 *
	 * @since 1.0.0
	 */
	function nscw_scripts_enqueue( $hook ) {
		if ( 'widgets.php' !== $hook ) {
		    return;
		}
		wp_register_script( 'nscw-widget-script', NS_CATEGORY_WIDGET_URL . '/admin/assets/js/nscw-widget.js', array( 'jquery'), NS_CATEGORY_WIDGET_VERSION );
		wp_localize_script( 'nscw-widget-script', 'ns_category_widget_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'nscw-widget-script' );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=' . $this->plugin_slug ) ) . '">' . esc_html__( 'Settings', 'ns-category-widget' ) . '</a>',
			),
			$links
		);

	}

	/**
	 * Ajax function to populate categories in widget settings.
	 *
	 * @since 1.0.0
	 */
	function ajax_populate_categories() {
		$output = array();

		$output['status'] = 0;

		$taxonomy = $_POST['taxonomy'];
		$name     = $_POST['name'];
		$id       = $_POST['id'];

		$cat_args = array(
			'orderby'         => 'slug',
			'taxonomy'        => $taxonomy,
			'echo'            => '0',
			'hide_empty'      => 0,
			'name'            => $name,
			'id'              => $id,
			'class'           => 'nscw-cat-list',
			'show_option_all' => __( 'Show All','ns-category-widget' ),
	    );

		$output['html'] = wp_dropdown_categories( apply_filters( 'widget_categories_dropdown_args', $cat_args ) );

		$output['status'] = 1;

		wp_send_json( $output );
	}

	/**
	 * Render sidebar.
	 *
	 * @since 3.1.1
	 */
	public function render_sidebar( $object ) {
		$object->render_sidebar_box(
			array(
				'title'   => 'Help &amp; Support',
				'icon'    => 'dashicons-editor-help',
				'content' => '<h4>Questions, bugs or great ideas?</h4>
				<p><a href="https://wordpress.org/support/plugin/ns-category-widget/#new-post" target="_blank">Visit our plugin support page</a></p>
				<h4>Wanna help make this plugin better?</h4>
				<p><a href="https://wordpress.org/support/plugin/ns-category-widget/reviews/#new-post" target="_blank">Review and rate this plugin on WordPress.org</a></p>',
			),
			$object
		);

		$object->render_sidebar_box(
			array(
				'title'   => 'Recommended Plugins',
				'content' => $this->get_recommended_plugins_content(),
			),
			$object
		);

		$object->render_sidebar_box(
			array(
				'title'   => 'Recent Blog Posts',
				'content' => '<div class="ns-blog-list"></div>',
			),
			$object
		);
	}

	public function load_settings_assets( $hook ) {
		if ( 'settings_page_ns-category-widget' !== $hook ) {
			return;
		}

		wp_enqueue_script( 'ns-category-widget-settings', NS_CATEGORY_WIDGET_URL . '/admin/assets/js/settings.js', array( 'jquery' ), NS_CATEGORY_WIDGET_VERSION, true );
	}

	public function get_posts_ajax_callback() {
		$output = array();

		$posts = $this->get_blog_feed_items();

		if ( ! empty( $posts ) ) {
			$output = $posts;
		}

		if ( ! empty( $output ) ) {
			wp_send_json_success( $output, 200 );
		} else {
			wp_send_json_error( $output, 404 );
		}
	}

	public function get_blog_feed_items() {
		$output = array();

		$rss = fetch_feed( 'https://www.nilambar.net/category/wordpress/feed' );

		$maxitems = 0;

		$rss_items = array();

		if ( ! is_wp_error( $rss ) ) {
			$maxitems  = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
		}

		if ( ! empty( $rss_items ) ) {
			foreach ( $rss_items as $item ) {
				$feed_item = array();

				$feed_item['title'] = $item->get_title();
				$feed_item['url']   = $item->get_permalink();

				$output[] = $feed_item;
			}
		}

		return $output;
	}

	public function setup_custom_notice() {
		// Setup notice.
		\Nilambar\AdminNotice\Notice::init(
			array(
				'slug' => NS_CATEGORY_WIDGET_SLUG,
				'name' => esc_html__( 'NS Category Widget', 'ns-category-widget' ),
			)
		);
	}

	public function get_recommended_plugins_content() {
		return '<ol>
			<li><a href="https://wpconcern.com/plugins/woocommerce-product-tabs/" target="_blank">WooCommerce Product Tabs</a></li>
			<li><a href="https://wpconcern.com/plugins/nifty-coming-soon-and-under-construction-page/" target="_blank">Coming Soon & Maintenance Mode Page</a></li>
			<li><a href="https://wpconcern.com/plugins/post-grid-elementor-addon/" target="_blank">Post Grid Elementor Addon</a></li>
			<li><a href="https://wpconcern.com/plugins/advanced-google-recaptcha/" target="_blank">Advanced Google reCAPTCHA</a></li>
			<li><a href="https://wpconcern.com/plugins/majestic-before-after-image/" target="_blank">Majestic Before After Image</a></li>
			<li><a href="https://wpconcern.com/plugins/admin-customizer/" target="_blank">Admin Customizer</a></li>
			<li><a href="https://wordpress.org/plugins/prime-addons-for-elementor/" target="_blank">Prime Addons for Elementor</a></li>
		</ol>';
	}
}
