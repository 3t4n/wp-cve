<?php
/**
 * The help page for the woo-product-slider
 *
 * @package woo-product-slider
 * @subpackage woo-product-slider/admin
 */

namespace ShapedPlugin\WooProductSlider\Admin\HelpPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the woo-product-slider
 */
class Help_Page {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'logo-carousel-free'             => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'wps_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help Page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'wps_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'spwps_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Help page Instance
	 *
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-woo-product-slider-help', SP_WPS_URL . 'Admin/HelpPage/css/help-page.min.css', array(), SP_WPS_VERSION );
		wp_enqueue_style( 'sp-woo-product-slider-fontello', SP_WPS_URL . 'Admin/HelpPage/css/fontello.min.css', array(), SP_WPS_VERSION );

		wp_enqueue_script( 'sp-woo-product-slider-help', SP_WPS_URL . 'Admin/HelpPage/js/help-page.min.js', array(), SP_WPS_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wps_shortcodes',
			__( 'Product Slider', 'woo-product-slider' ),
			__( 'Recommended', 'woo-product-slider' ),
			'manage_options',
			'edit.php?post_type=sp_wps_shortcodes&page=wps_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wps_shortcodes',
			__( 'Product Slider', 'woo-product-slider' ),
			__( 'Lite vs Pro', 'woo-product-slider' ),
			'manage_options',
			'edit.php?post_type=sp_wps_shortcodes&page=wps_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wps_shortcodes',
			__( 'Woo Product Slider Help', 'woo-product-slider' ),
			__( 'Get Help', 'woo-product-slider' ),
			'manage_options',
			'wps_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Spwps_ajax_help_page function.
	 *
	 * @return void
	 */
	public function spwps_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'spwps_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							);
						}
					}
				}

				set_transient( 'spwps_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$image_type  = 'png';
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				switch ( $plugin_slug ) {
					case 'styble':
						$image_type = 'jpg';
						break;
					case 'location-weather':
					case 'gallery-slider-for-woocommerce':
						$image_type = 'gif';
						break;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'woo-product-slider' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'woo-product-slider' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'woo-product-slider' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'woo-product-slider' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'woo-product-slider' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'woo-product-slider' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'woo-product-slider' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_wps_shortcodes&page=wps_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_wps_shortcodes' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The Woo Product Slider Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-woo-product-slider-help">
			<!-- Header section start -->
			<section class="spwps__help header">
				<div class="spwps-header-area-top">
					<p>You’re currently using <b>Woo Product Slider Lite</b>. To access additional features, consider <a target="_blank" href="https://wooproductslider.io/pricing?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="spwps-header-area">
					<div class="spwps-container">
						<div class="spwps-header-logo">
							<img src="<?php echo esc_url( SP_WPS_URL . 'Admin/HelpPage/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SP_WPS_VERSION ); ?></span>
						</div>
					</div>
					<div class="spwps-header-logo-shape">
						<img src="<?php echo esc_url( SP_WPS_URL . 'Admin/HelpPage/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="spwps-header-nav">
					<div class="spwps-container">
						<div class="spwps-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wps_shortcodes&page=wps_help#get-start' ); ?>"><i class="spwps-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wps_shortcodes&page=wps_help#recommended' ); ?>" data-id="recommended-tab"><i class="spwps-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wps_shortcodes&page=wps_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="spwps-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wps_shortcodes&page=wps_help#about-us' ); ?>" data-id="about-us-tab"><i class="spwps-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="spwps__help start-page" id="get-start-tab">
				<div class="spwps-container">
					<div class="spwps-start-page-wrap">
						<div class="spwps-video-area">
							<h2 class='spwps-section-title-help'>Welcome to Woo Product Slider!</h2>
							<span class='spwps-normal-paragraph'>Thank you for installing Woo Product Slider! This video will help you get started with the plugin. Enjoy!</span>

							</iframe>
								<iframe width="700" height="405" src="https://www.youtube.com/embed/lqe8SKiG_Ns?si=Drz2FotlsSVRGXP3" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='spwps-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sp_wps_shortcodes' ); ?>">Create a Product Slider</a></li>
								<li><a target="_blank" class='spwps-medium-btn' href="https://wooproductslider.io/lite-version-demo/">Live Demo</a></li>
								<li><a target="_blank" class='spwps-medium-btn arrow-btn' href="https://wooproductslider.io/">Explore Woo Product Slider <i class="spwps-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="spwps-start-page-sidebar">
							<div class="spwps-start-page-sidebar-info-box">
								<div class="spwps-info-box-title">
									<h4><i class="spwps-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='spwps-normal-paragraph'>Explore Woo Product Slider plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='spwps-small-btn' href="https://docs.shapedplugin.com/docs/woocommerce-product-slider/overview/">Browse Now</a>
							</div>
							<div class="spwps-start-page-sidebar-info-box">
								<div class="spwps-info-box-title">
									<h4><i class="spwps-icon-support"></i> Technical Support</h4>
								</div>
								<span class='spwps-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='spwps-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="spwps-start-page-sidebar-info-box">
								<div class="spwps-info-box-title">
									<h4><i class="spwps-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='spwps-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='spwps-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="spwps__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="spwps-container">
					<div class="spwps-call-to-action-top">
						<h2 class="spwps-section-title-help">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://wooproductslider.io/pricing/?ref=1" class='spwps-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="spwps-lite-to-pro-wrap">
						<div class="spwps-features">
							<ul>
								<li class='spwps-header'>
									<span class='spwps-title'>FEATURES</span>
									<span class='spwps-free'>Lite</span>
									<span class='spwps-pro'><i class='spwps-icon-pro'></i> PRO</span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>All Free Version Features</span>
									<span class='spwps-free spwps-check-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Beautiful Layout Presets (Slider, Grid, Masonry, and Table)</span>
									<span class='spwps-free'><b>2</b></span>
									<span class='spwps-pro'><b>4</b></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Pre-designed Professional Templates</span>
									<span class='spwps-free'><b>3</b></span>
									<span class='spwps-pro'><b>28+</b></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Even and Masonry Grid Style</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Create Product Custom Card/Template <i class="spwps-new">New</i> <i class="spwps-hot">Hot</i></span>
									<span class='spwps-free'><b>1</b></span>
									<span class='spwps-pro'><b>5+</b></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Advanced Product Filtering Types (Category, Tag, Brands, Best Selling, Related, Top Rated, Upsells, Cross-sells, etc.)</span>
									<span class='spwps-free'>2</span>
									<span class='spwps-pro'>17+</span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Show Products from a Specific Data Type (Simple, Group, External/Affiliate, and Variable)</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Show Variation as Individual Product <i class="spwps-new">New</i></span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Show On Sale, Free, and Hidden Products</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Hide Out of Stock Products</span>
									<span class='spwps-free spwps-check-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Hide Product Without Thumbnail from the Product Sliders</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Order Product by Price and Ticker Slider Mode</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Product Content Position (Bottom, Top, Right, Left, Overlay) <i class="spwps-new">New</i></span>
									<span class='spwps-free'><b>1</b></span>
									<span class='spwps-pro'><b>5</b></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Product Item Border, Radius, BoxShadow, Background, Inner Padding, etc.</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Configure Overlay Content Position, Visibility, Color Type, etc.</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Ajax Product Search and Equalize Product Height</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Multiple Ajax Paginations (Number, Load More, Infinite) and Show Per Page /Click</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Product Name Length Limit Type (Word, Character, Line)</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Show/Hide Product Description (Full, Short, Limit, Read More, etc.)</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Show Hide Product Category, Review Count, Quantities, Wishlist, and Compare</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Show Product Price, Rating, Add To Cart, Brands, Quick View</span>
									<span class='spwps-free spwps-check-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Strip All HTML Tags from the Description Content</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Product Image Custom Dimensions and Retina Ready Supported</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Enable Product Image Flipping and Lazy Load</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Powerful Lightbox Options for Product Image</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Zoom In, Zoom Out, and Grayscale Modes for Product Images</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Vertical Carousel Orientation, Fade effect, Slide to Scroll, Multi-row Carousel</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Powerful Carousel Settings (AutoPlay, AutoPlay Delay, Direction, Navigation, Pagination, etc.)</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Upsells and Crosssells Products Slider to Boost sales <i class="spwps-hot">Hot</i></span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Create Engaging Related Product Slider <i class="spwps-hot">Hot</i></span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Replace the Shop/Product, Category, Tag, and Search Pages default layout with the Customized Product Sliders/Grids/Tables</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Template Modification from the Theme Directory</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Export or Import Product Sliders/Grids/Tables</span>
									<span class='spwps-free spwps-check-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Stylize your Product Slider/Grid Typography with 1500+ Google Fonts</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
								<li class='spwps-body'>
									<span class='spwps-title'>Priority Top-notch Support</span>
									<span class='spwps-free spwps-close-icon'></span>
									<span class='spwps-pro spwps-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="spwps-upgrade-to-pro">
							<h2 class='spwps-section-title-help'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='spwps-section-subtitle'>Already, <b>30,000+</b> people are using Woo Product Slider on their websites to create beautiful showcase, why won’t you!</span>
							<div class="spwps-upgrade-to-pro-btn">
								<div class="spwps-action-btn">
									<a target="_blank" href="https://wooproductslider.io/pricing/?ref=1" class='spwps-big-btn'>Upgrade to Pro Now!</a>
									<span class='spwps-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://wooproductslider.io/" class='spwps-big-btn-border'>See All Features</a>
								<a target="_blank" href="https://wooproductslider.io/products-carousel-slider/" class='spwps-big-btn-border spwps-live-pro-demo'>Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="spwps-testimonial">
						<div class="spwps-testimonial-title-section">
							<span class='spwps-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="spwps-section-title-help">Our Users Love Woo Product Slider Pro!</h2>
						</div>
						<div class="spwps-testimonial-wrap">
							<div class="spwps-testimonial-area">
								<div class="spwps-testimonial-content">
									<p>Self explaining, easy to understand, well organized: all thumbs up for this wonderful plugin. Treat yourself with the pro version, it makes life easier – at a reasonable cost. Not to be underestimat...</p>
								</div>
								<div class="spwps-testimonial-info">
									<div class="spwps-img">
										<img src="<?php echo esc_url( SP_WPS_URL . 'Admin/HelpPage/img/stefan.png' ); ?>" alt="">
									</div>
									<div class="spwps-info">
										<h3>Stefan</h3>
										<div class="spwps-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwps-testimonial-area">
								<div class="spwps-testimonial-content">
									<p>We purchased the pro version because we needed the extra features and it’s wonderful. So many creative options! Needed support at the outset over what turned out to be a plugin conflict, and su...</p>
								</div>
								<div class="spwps-testimonial-info">
									<div class="spwps-img">
										<img src="<?php echo esc_url( SP_WPS_URL . 'Admin/HelpPage/img/global.png' ); ?>" alt="">
									</div>
									<div class="spwps-info">
										<h3>Global Exposures</h3>
										<div class="spwps-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwps-testimonial-area">
								<div class="spwps-testimonial-content">
									<p>Very happy with the pro version of WooCommerce Slider Pro and with the prompt response from the support team. Highly recommended. The definition of excellent independent software.</p>
								</div>
								<div class="spwps-testimonial-info">
									<div class="spwps-img">
										<img src="<?php echo esc_url( SP_WPS_URL . 'Admin/HelpPage/img/patboran.png' ); ?>" alt="">
									</div>
									<div class="spwps-info">
										<h3>Patboran</h3>
										<div class="spwps-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="spwps-recommended-page">
				<div class="spwps-container">
					<h2 class="spwps-section-title-help">Enhance your Website with our Free Robust Plugins</h2>
					<div class="spwps-wp-list-table plugin-install-php">
						<div class="spwps-recommended-plugins" id="the-list">
							<?php
								$this->spwps_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="spwps__help about-page">
				<div class="spwps-container">
					<div class="spwps-about-box">
						<div class="spwps-about-info">
							<h3>The Best WooCommerce Product Slider plugin by the Woo Product Slider Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we are committed to helping WooCommerce store owners increase their sales with the help of various easy sales booster plugins. However, we understand the importance of highlighting specific products in strategic positions of the shop to make it easier for customers to find them.</p>
							<p>Our plugin provides a simple and convenient solution to display unlimited, visually captivating product sliders, grids, masonry, and tables for WooCommerce shops. We're confident you'll find it a game-changer!</p>
							<div class="spwps-about-btn">
								<a target="_blank" href="https://wooproductslider.io/" class='spwps-medium-btn'>Explore Woo Product Slider</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='spwps-medium-btn spwps-arrow-btn'>More About Us <i class="spwps-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="spwps-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="spwps-our-plugin-list">
						<h3 class="spwps-section-title-help">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="spwps-our-plugin-list-wrap">
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://wordpresscarousel.com/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.png" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="spwps-our-plugin-list-box">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Gallery Slider for WooCommerce</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.png" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>Category Slider for WooCommerce</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://wptabs.com/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="spwps-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="spwps-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="spwps-footer">
				<div class="spwps-footer-top">
					<p><span>Made With <i class="spwps-icon-heart"></i> </span> By the <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a> Team</p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="spwps-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="spwps-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="spwps-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="spwps-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}

}
