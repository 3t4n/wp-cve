<?php
/**
 * The help page for the WP Tabs Free
 *
 * @package WP Tabs Free
 * @subpackage wp-tabs-free/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the WP Tabs Free
 */
class WP_Tabs_Help_Page {

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
		'tabs_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'tabs_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'wptabspro_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main Help page Instance
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
		wp_enqueue_style( 'sp-wp-tabs-help', WP_TABS_URL . 'admin/help-page/css/help-page.min.css', array(), WP_TABS_VERSION );
		wp_enqueue_style( 'sp-wp-tabs-help-fontello', WP_TABS_URL . 'admin/help-page/css/fontello.min.css', array(), WP_TABS_VERSION );

		wp_enqueue_script( 'sp-wp-tabs-help', WP_TABS_URL . 'admin/help-page/js/help-page.min.js', array(), WP_TABS_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			__( 'WP Tabs', 'wp-expand-tabs-free' ),
			__( 'Recommended', 'wp-expand-tabs-free' ),
			'manage_options',
			'edit.php?post_type=sp_wp_tabs&page=tabs_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			__( 'WP Tabs', 'wp-expand-tabs-free' ),
			__( 'Lite vs Pro', 'wp-expand-tabs-free' ),
			'manage_options',
			'edit.php?post_type=sp_wp_tabs&page=tabs_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sp_wp_tabs',
			__( 'WP Tabs Help', 'wp-expand-tabs-free' ),
			__( 'Get Help', 'wp-expand-tabs-free' ),
			'manage_options',
			'tabs_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Sptabs_ajax_help_page function.
	 *
	 * @return void
	 */
	public function sptabs_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'sptabs_plugins' );
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

				set_transient( 'sptabs_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
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
									<?php esc_html_e( 'Activate', 'wp-expand-tabs-free' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'wp-expand-tabs-free' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'wp-expand-tabs-free' ); ?>
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
							<strong><?php esc_html_e( 'Version:', 'wp-expand-tabs-free' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'wp-expand-tabs-free' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'wp-expand-tabs-free' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'wp-expand-tabs-free' ); ?></span>
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
		return wp_nonce_url( admin_url( 'edit.php?post_type=sp_wp_tabs&page=tabs_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sp_wp_tabs' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

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
	 * The WP Tabs Help Callback.
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
		<div class="sp-wp-tabs-help">
			<!-- Header section start -->
			<section class="sptabs__help header">
				<div class="sptabs-header-area-top">
					<p>You’re currently using <b>WP Tabs Lite</b>. To access additional features, consider <a target="_blank" href="https://wptabs.com/pricing/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="sptabs-header-area">
					<div class="sptabs-container">
						<div class="sptabs-header-logo">
							<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( WP_TABS_VERSION ); ?></span>
						</div>
					</div>
					<div class="sptabs-header-logo-shape">
						<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="sptabs-header-nav">
					<div class="sptabs-container">
						<div class="sptabs-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#get-start' ); ?>"><i class="sptabs-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#recommended' ); ?>" data-id="recommended-tab"><i class="sptabs-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="sptabs-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sp_wp_tabs&page=tabs_help#about-us' ); ?>" data-id="about-us-tab"><i class="sptabs-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="sptabs__help start-page" id="get-start-tab">
				<div class="sptabs-container">
					<div class="sptabs-start-page-wrap">
						<div class="sptabs-video-area">
							<h2 class='sptabs-section-title'>Welcome to WP Tabs!</h2>
							<span class='sptabs-normal-paragraph'>Thank you for installing WP Tabs! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/m7UmdIzoGhA?si=coxtD6y-ttX1d6I5" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='sptabs-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sp_wp_tabs' ); ?>">Create a Tab Group</a></li>
								<li><a target="_blank" class='sptabs-medium-btn' href="https://wptabs.com/wp-tabs-lite-version-demos/">Live Demo</a></li>
								<li><a target="_blank" class='sptabs-medium-btn arrow-btn' href="https://wptabs.com/">Explore WP Tabs <i class="sptabs-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="sptabs-start-page-sidebar">
							<div class="sptabs-start-page-sidebar-info-box">
								<div class="sptabs-info-box-title">
									<h4><i class="sptabs-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='sptabs-normal-paragraph'>Explore WP Tabs plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='sptabs-small-btn' href="https://docs.shapedplugin.com/docs/wp-tabs/overview/">Browse Now</a>
							</div>
							<div class="sptabs-start-page-sidebar-info-box">
								<div class="sptabs-info-box-title">
									<h4><i class="sptabs-icon-support"></i> Technical Support</h4>
								</div>
								<span class='sptabs-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='sptabs-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="sptabs-start-page-sidebar-info-box">
								<div class="sptabs-info-box-title">
									<h4><i class="sptabs-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='sptabs-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='sptabs-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="sptabs__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="sptabs-container">
					<div class="sptabs-call-to-action-top">
						<h2 class="sptabs-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://wptabs.com/pricing/?ref=1" class='sptabs-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="sptabs-lite-to-pro-wrap">
						<div class="sptabs-features">
							<ul>
								<li class='sptabs-header'>
									<span class='sptabs-title'>FEATURES</span>
									<span class='sptabs-free'>Lite</span>
									<span class='sptabs-pro'><i class='sptabs-icon-pro'></i> PRO</span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>All Free Version Features</span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Tabs Layout Presets (Horizontal Top, Bottom, Vertical Left, Right, and Tabs Carousel)</span>
									<span class='sptabs-free'><b>1</b></span>
									<span class='sptabs-pro'><b>5</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Flexible Tabs Position </span>
									<span class='sptabs-free'><b>2</b></span>
									<span class='sptabs-pro'><b>20</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Unlimited Multi-level or Nested Tabs <i class="sptabs-hot">Hot</i> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Supports HTML content, images, shortcodes, video, audio, forms, maps, iframe, slider, galleries, etc.</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>WooCommerce Additional Custom Tab <i class="sptabs-hot">Hot</i>  </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Create Tabs from Posts, Pages, Products, Custom Post Types, Taxonomies, etc.</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Add Tabs Subtitle</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Add Tabs Title Icon from Icon Library/Custom Image Icons</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Deep Linking/Custom Linking Tabs <i class="sptabs-new">New</i> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Make any Tab Item Inactive </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Tabs AutoPlay Activator Event</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Set Custom Tabs Number to be Opened on Page Load</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Enable Scrollable Tabs Variable Width and Loop <i class="sptabs-new">New</i> <i class="sptabs-hot">Hot</i> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Hide Tabs hash URL from Site URL/Browser URL</span>
									<span class='sptabs-free'><b>1</b></span>
									<span class='sptabs-pro'><b>18</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Stylize Tabs Icon, Icon Size, Color, Active Color, Position etc.</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Show Active Tab Indicator Arrow <i class="sptabs-new">new</i> </span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Tabs Title Gradient Background Color</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Active Tab Top Line Border</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Tabs Title and Description Border, Padding, etc.</span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Flat Underline Tabs</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Strip Tabs Description Content HTML Tags</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Tabs Custom Content Height</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Change Tabs to Accordion on Small Devices</span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Tabs Description Animation</span>
									<span class='sptabs-free'><b>2</b></span>
									<span class='sptabs-pro'><b>55+</b></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Import/Export Tabs Groups</span>
									<span class='sptabs-free sptabs-check-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Stylize your Tabs Typography with 1500+ Google Fonts</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
								<li class='sptabs-body'>
									<span class='sptabs-title'>Priority Top-notch Support</span>
									<span class='sptabs-free sptabs-close-icon'></span>
									<span class='sptabs-pro sptabs-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="sptabs-upgrade-to-pro">
							<h2 class='sptabs-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='sptabs-section-subtitle'>Already, <b>15000+</b> people are using WP Tabs on their websites to create beautiful showcase, why won’t you!</span>
							<div class="sptabs-upgrade-to-pro-btn">
								<div class="sptabs-action-btn">
									<a target="_blank" href="https://wptabs.com/pricing/?ref=1" class='sptabs-big-btn'>Upgrade to Pro Now!</a>
									<span class='sptabs-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://wptabs.com" class='sptabs-big-btn-border'>See All Features</a>
								<a target="_blank" href="https://wptabs.com/horizontal-tabs/" class='sptabs-big-btn-border sptabs-pro-live-demo'>Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="sptabs-testimonial">
						<div class="sptabs-testimonial-title-section">
							<span class='sptabs-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="sptabs-section-title">Our Users Love WP Tabs Pro!</h2>
						</div>
						<div class="sptabs-testimonial-wrap">
							<div class="sptabs-testimonial-area">
								<div class="sptabs-testimonial-content">
									<p>I’ve tried 3 other Gallery / Carousel plugins and this one is by far the easiest, lightweight and does exactly what I want! I had a minor glitch and support was very quick to fix it. Very happy and highly rec...</p>
								</div>
								<div class="sptabs-testimonial-info">
									<div class="sptabs-img">
										<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/joyce.png' ); ?>" alt="">
									</div>
									<div class="sptabs-info">
										<h3>Joyce van den Berg</h3>
										<div class="sptabs-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="sptabs-testimonial-area">
								<div class="sptabs-testimonial-content">
									<p>The plugin works great and is a great addition to my site. The support has been tremendously! Fast and easy! They even helped with JS errors from other plugins. THANKS!...</p>
								</div>
								<div class="sptabs-testimonial-info">
									<div class="sptabs-img">
										<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/sksposcho.png' ); ?>" alt="">
									</div>
									<div class="sptabs-info">
										<h3>Sksposcho</h3>
										<div class="sptabs-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="sptabs-testimonial-area">
								<div class="sptabs-testimonial-content">
									<p>The new WP-Tabs is a tab menu for people who love more features. First of all you have to be playful when creating the tabs. If – then the Pro Version includes the things that make creating them reall...</p>
								</div>
								<div class="sptabs-testimonial-info">
									<div class="sptabs-img">
										<img src="<?php echo esc_url( WP_TABS_URL . 'admin/help-page/img/wegerl.png' ); ?>" alt="">
									</div>
									<div class="sptabs-info">
										<h3>Wegerl</h3>
										<div class="sptabs-star">
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
			<section id="recommended-tab" class="sptabs-recommended-page">
				<div class="sptabs-container">
					<h2 class="sptabs-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="sptabs-wp-list-table plugin-install-php">
						<div class="sptabs-recommended-plugins" id="the-list">
							<?php
								$this->sptabs_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="sptabs__help about-page">
				<div class="sptabs-container">
					<div class="sptabs-about-box">
						<div class="sptabs-about-info">
							<h3>A Highly Customizable WordPress Tabs plugin from the WP Tabs Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we have been looking for the best way to display WordPress content in a clean, organized tabbed navigation. Unfortunately, we couldn't find any suitable plugin that met our needs. Hence, we set a simple goal: to develop a responsive and drag & drop tabs builder plugin for WordPress with many customization options.</p>
							<p>The WP Tabs plugin provides a convenient way to create visually appealing Tabs sections and WooCommerce custom tabs. Check it out now and experience the difference!</p>
							<div class="sptabs-about-btn">
								<a target="_blank" href="https://wptabs.com" class='sptabs-medium-btn'>Explore WP Tabs</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='sptabs-medium-btn sptabs-arrow-btn'>More About Us <i class="sptabs-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="sptabs-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="sptabs-our-plugin-list">
						<h3 class="sptabs-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="sptabs-our-plugin-list-wrap">
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://wordpresscarousel.com/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.png" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="sptabs-our-plugin-list-box">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Gallery Slider for WooCommerce</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.png" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>Category Slider for WooCommerce</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://wptabs.com/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="sptabs-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="sptabs-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="sptabs-footer">
				<div class="sptabs-footer-top">
					<p><span>Made With <i class="sptabs-icon-heart"></i> </span> By the <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a> Team</p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="sptabs-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="sptabs-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="sptabs-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="sptabs-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}
}

WP_Tabs_Help_Page::instance();
