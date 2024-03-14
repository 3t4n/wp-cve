<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Core;

defined( 'ABSPATH' ) || exit();

/**
 * Core Class
 */
class Core {

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Plugin Info
	 *
	 * @var array
	 */
	protected $plugin_info;

	/**
	 * Core Path
	 *
	 * @var string
	 */
	public $core_path;

	/**
	 * Core URL
	 *
	 * @var string
	 */
	public $core_url;

	/**
	 * Core Assets PATH
	 *
	 * @var string
	 */
	public $core_assets_path;

	/**
	 * Core Assets URL
	 *
	 * @var string
	 */
	public $core_assets_url;

	/**
	 * Core Version.
	 *
	 * @var string
	 */
	private $version = '2.0';

	/**
	 * Singleton Init.
	 *
	 * @param array $plugin_info
	 * @return self
	 */
	public static function start( $plugin_info ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $plugin_info );
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @param array $plugin_info
	 */
	private function __construct( $plugin_info ) {
		$this->init( $plugin_info );
		$this->hooks();
	}

	/**
	 * Init constants and other variables.
	 *
	 * = Set the Plugin Update URL
	 *
	 * @return void
	 */
	private function init( $plugin_info ) {
		$this->plugin_info      = $plugin_info;
		$this->core_path        = plugin_dir_path( __FILE__ );
		$this->core_url         = plugin_dir_url( __FILE__ );
		$this->core_assets_path = $this->core_path . 'assets';
		$this->core_assets_url  = $this->core_url . 'assets';
	}

	/**
	 * Core Actions Hook.
	 *
	 * @return void
	 */
	public function core_actions( $action_type ) {
		if ( 'activated' === $action_type ) {
			$this->plugin_activated();
		} elseif ( 'deactivated' === $action_type ) {
			$this->plugin_deactivated();
		} elseif ( 'uninstall' === $action_type ) {
			$this->plugin_uninstalled();
		}
	}

	/**
	 * Core Hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 1000, 1 );
	}

	/**
	 * Core Admin Scripts.
	 *
	 * @param string $hook_prefix
	 *
	 * @return void
	 */
	public function admin_scripts( $hook_suffix ) {
		global $pagenow;

		if ( ! wp_style_is( 'gpls-core-plugins-general-admin-head-styles-' . $this->version ) ) {
			wp_enqueue_style( 'gpls-core-plugins-general-admin-head-styles-' . $this->version, $this->core_assets_file( 'admin-head', 'css', 'css' ), array(), 'all' );
		}
	}

	/**
	 * Get Core assets file
	 *
	 * @param string $asset_file    Assets File Name
	 * @param string $type          Assets File Folder Type [ js / css /images / etc.. ]
	 * @param string $suffix        Assets File Type [ js / css / png /jpg / etc ... ]
	 * @param string $prefix        [ .min ]
	 * @return string
	 */
	public function core_assets_file( $asset_file, $type, $suffix, $prefix = 'min' ) {
		return $this->core_assets_url . '/dist/' . $type . '/' . $asset_file . ( ! empty( $prefix ) ? ( '.' . $prefix ) : '' ) . '.' . $suffix;
	}

	/**
	 * Get Core assets lib file
	 *
	 * @param string $asset_file    Assets File Name
	 * @param string $suffix        Assets File Type [ js / css / png /jpg / etc ... ]
	 * @param string $prefix        [ .min ]
	 * @return string
	 */
	public function core_assets_lib( $asset_file, $suffix, $prefix = 'min' ) {
		return $this->core_assets_url . '/libs/' . $asset_file . ( ! empty( $prefix ) ? ( '.' . $prefix ) : '' ) . '.' . $suffix;
	}

	/**
	 * Plugin Activation Hub function
	 *
	 * @return void
	 */
	public function plugin_activated() {

		do_action( $this->plugin_info['name'] . '-core-activated', $this );
	}

	/**
	 * Plugin Deactivation Hub function
	 *
	 * @return void
	 */
	public function plugin_deactivated() {

		do_action( $this->plugin_info['name'] . '-core-deactivated', $this );
	}

	/**
	 * Uninstall the plugin hook.
	 *
	 * @return void
	 */
	public function plugin_uninstalled() {

		do_action( $this->plugin_info['name'] . '-core-uninstalled', $this );
	}

	/**
	 * Pro Button.
	 *
	 * @param string $pro_link
	 * @param string $btn_title
	 * @param string $additional_classes
	 * @param string $additional_css
	 * @return void
	 */
	public function pro_btn( $pro_link = '', $btn_title = 'Pro', $additional_classes = '', $additional_css = '', $return = false ) {

		if ( empty( $pro_link ) && empty( $this->plugin_info['pro_link'] ) ) {
			return;
		}
		$pro_link = empty( $pro_link ) ? $this->plugin_info['pro_link'] : $pro_link;

		if ( $return ) {
			ob_start();
		}
		?>
		<a target="_blank" class="ms-2 btn gpls-permium-btn-wave btn-primary <?php echo esc_attr( $additional_classes ); ?>" href="<?php echo esc_url_raw( $pro_link ); ?>" style="<?php echo esc_attr( $additional_css ); ?>">
			<span class="pro-title" style="position:relative;z-index:10;color:#FFF;"><?php printf( esc_html__( '%s ➤' ), $btn_title ); ?></span>
			<span class="wave"></span>
		</a>
		<?php
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Default Footer Section
	 *
	 * @return void
	 */
	public function default_footer_section() {
		?>
		<style>
		#wpfooter {display: block !important;}
		.wrap.woocommerce {position: relative;}
		.gpls-contact {position: absolute; bottom: 0px; right: 20px; max-width: 350px; z-index: 1000;}
		.gpls-contact .link { color: #acde86!important; }
		.gpls-contact .text { background-color: #176875!important; }
		</style>
		<div class="gpls-contact">
		  <p class="p-3 bg-light text-center text text-white"><?php esc_html_e( 'in case you want to report a bug, submit a new feature or request a custom plugin, Please' ); ?> <a class="link" target="_blank" href="https://grandplugins.com/contact-us"> <?php esc_html_e( 'contact us' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Review Link.
	 *
	 * @param string $review_link
	 * @return void
	 */
	public function review_notice( $review_link = '', $is_dismissible = true, $small = false ) {
		if ( empty( $review_link ) && empty( $this->plugin_info['review_link'] ) ) {
			return;
		}
		$review_link = ! empty( $review_link ) ? $review_link : $this->plugin_info['review_link'];
		?>
		<p class="notice notice-success <?php echo esc_attr( $small ? 'p-2' : 'p-4' ); ?> <?php echo esc_attr( $is_dismissible ? 'is-dismissible' : '' ); ?>">
			<?php esc_html_e( 'We would love your feedback. leaving ' ); ?>
			<a class="text-decoration-none" href="<?php echo esc_url_raw( $review_link ); ?>" target="_blank">
				<u><?php esc_html_e( 'a review is much appreciated' ); ?></u>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
			<?php esc_html_e( ':) Thanks!' ); ?>
		</p>
		<?php
	}

	/**
	 * New Keyword.
	 *
	 * @param string $title
	 * @param boolean $return
	 *
	 * @return string|void
	 */
	public function new_keyword( $title = 'new', $return = true ) {
		if ( $return ) {
			ob_start();
		}
		?>
		<span class="<?php echo esc_attr( $this->plugin_info['classes_general'] . '-new-keyword' ); ?> ms-1"><?php esc_html_e( 'New' ); ?></span>
		<?php
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Plugins Sidebar.
	 *
	 * @return void
	 */
	public function plugins_sidebar( $exclude = '' ) {
		?>
		<div class="gpls-core-recommended-section">
			<h6 class="shadow-sm border p-3 shadow-sm border rounded"><?php esc_html_e( 'Check our other plugins' ); ?></h6>
			<div class="section-body bg-light p-3 shadow-sm border rounded">
				<ul class="plugins-list list-group">
					<?php if ( 'woo-cart-limiter' !== $exclude ) : ?>
					<li class="plugin-list-item list-group-item border rounded">
						<h6 class="border rounded p-1 mb-2 text-center"><?php esc_html_e( 'WooCommerce Cart Limiter' ); ?></h6>
						<p><?php esc_html_e( 'Control your website cart, limit cart totals, products count and quantity, limit products based on other products in cart, set minimum and maxmium quantity limits and more...' ); ?></p>
						<div class="row border p-1 rounded gx-0">
							<div class="col d-flex justify-content-center border-end">
								<a class="btn btn-primary text-decoration-underline" target="_blank" href="https://grandplugins.com/product/woo-cart-limiter/?utm_source=free&utm_medium=sidebar"><strong><?php esc_html_e( 'Pro' ); ?></strong></a>
							</div>
							<div class="col d-flex justify-content-center">
								<a class="btn btn-success text-decoration-underline" target="_blank" href="https://wordpress.org/plugins/cart-limiter/"><?php esc_html_e( 'Free' ); ?></a>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if ( 'woo-coming-soon' !== $exclude ) : ?>
					<li class="plugin-list-item list-group-item border rounded">
						<h6 class="border rounded p-1 mb-2 text-center"><?php esc_html_e( 'WooCommerce Coming Soon Products' ); ?></h6>
						<p><?php esc_html_e( 'Set your products to coming soon mode with countdown timer.' ); ?></p>
						<div class="row border p-1 rounded gx-0">
							<div class="col d-flex justify-content-center border-end">
								<a class="btn btn-primary text-decoration-underline" target="_blank" href="https://grandplugins.com/product/woo-coming-soon-products/?utm_source=free&utm_medium=sidebar"><strong><?php esc_html_e( 'Pro' ); ?></strong></a>
							</div>
							<div class="col d-flex justify-content-center">
								<a class="btn btn-success text-decoration-underline" target="_blank" href="https://wordpress.org/plugins/coming-soon-products-for-woocommerce/"><?php esc_html_e( 'Free' ); ?></a>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if ( 'wp-watermark-images' !== $exclude ) : ?>
					<li class="plugin-list-item list-group-item border rounded">
						<h6 class="border rounded p-1 mb-2 text-center"><?php esc_html_e( 'WP Watermark Images' ); ?></h6>
						<p><?php esc_html_e( 'protect your images by watermarking them with text and image watermarks using the most advanced watermarking plugin' ); ?></p>
						<div class="row border p-1 rounded gx-0">
							<div class="col d-flex justify-content-center border-end">
								<a class="btn btn-primary text-decoration-underline" target="_blank" href="https://grandplugins.com/product/wp-images-watermark/?utm_source=free&utm_medium=sidebar"><strong><?php esc_html_e( 'Pro' ); ?></strong></a>
							</div>
							<div class="col d-flex justify-content-center">
								<a class="btn btn-success text-decoration-underline" target="_blank" href="https://wordpress.org/plugins/watermark-images-for-wp-and-woo-grandpluginswp/"><?php esc_html_e( 'Free' ); ?></a>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if ( 'woo-quick-view' !== $exclude ) : ?>
					<li class="plugin-list-item list-group-item border rounded">
						<h6 class="border rounded p-1 mb-2 text-center"><?php esc_html_e( 'WooCommerce Quick View and Buy Now' ); ?></h6>
						<p><?php esc_html_e( 'Increase your website conversion rate, encourage your visitors to buy from your website using quick view and buy now buttons with direct checkout.' ); ?></p>
						<div class="row border p-1 rounded gx-0">
							<div class="col d-flex justify-content-center border-end">
								<a class="btn btn-primary text-decoration-underline" target="_blank" href="https://grandplugins.com/product/quick-view-and-buy-now-for-woocommerce/?utm_source=free&utm_medium=sidebar"><strong><?php esc_html_e( 'Pro' ); ?></strong></a>
							</div>
							<div class="col d-flex justify-content-center">
								<a class="btn btn-success text-decoration-underline" target="_blank" href="https://wordpress.org/plugins/quick-view-and-buy-now-for-woocommerce/"><?php esc_html_e( 'Free' ); ?></a>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if ( 'paypal-subscriptions' !== $exclude ) : ?>
					<li class="plugin-list-item list-group-item border rounded">
						<h6 class="border rounded p-1 mb-2 text-center"><?php esc_html_e( 'Paypal Subscriptions' ); ?></h6>
						<p><?php esc_html_e( 'Get full integration between paypal subscriptions with WordPress. offer premium content based on recurring subscriptions and convert WooCommerce products into paypal subscriptions.' ); ?></p>
						<div class="row border p-1 rounded gx-0">
							<div class="col d-flex justify-content-center border-end">
								<a class="btn btn-primary text-decoration-underline" target="_blank" href="https://grandplugins.com/product/wp-paypal-subscriptions/?utm_source=free&utm_medium=sidebar"><strong><?php esc_html_e( 'Pro' ); ?></strong></a>
							</div>
							<div class="col d-flex justify-content-center">
								<a class="btn btn-success text-decoration-underline" target="_blank" href="https://wordpress.org/plugins/gpls-paypal-subscriptions/"><?php esc_html_e( 'Free' ); ?></a>
							</div>
						</div>
					</li>
					<?php endif; ?>
					<?php if ( 'image-sizes-controller' !== $exclude ) : ?>
					<li class="plugin-list-item list-group-item border rounded">
						<h6 class="border rounded p-1 mb-2 text-center"><?php esc_html_e( 'Image Sizes Controller' ); ?></h6>
						<p><?php esc_html_e( 'Control your website image sizes, create custom image sizes and disable generating unneeded sizes' ); ?></p>
						<div class="row border p-1 rounded gx-0">
							<div class="col d-flex justify-content-center border-end">
								<a class="btn btn-primary text-decoration-underline" target="_blank" href="https://grandplugins.com/product/image-sizes-controller/?utm_source=free&utm_medium=sidebar"><strong><?php esc_html_e( 'Pro' ); ?></strong></a>
							</div>
							<div class="col d-flex justify-content-center">
								<a class="btn btn-success text-decoration-underline" target="_blank" href="https://wordpress.org/plugins/image-sizes-controller/"><?php esc_html_e( 'Free' ); ?></a>
							</div>
						</div>
					</li>
					<?php endif; ?>
				</ul>
				<a class="btn btn-primary d-block mt-3" target="_blank" href="https://grandplugins.com/product-category/plugin/?utm_source=free&utm_medium=sidebar"><?php esc_html_e( 'Browse All Plugins' ); ?></a>
			</div>
		</div>
		<?php
	}
}
