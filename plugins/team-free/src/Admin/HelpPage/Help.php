<?php
/**
 * The help page for the Team Free
 *
 * @package Team Free
 * @subpackage team-free/admin
 */

namespace ShapedPlugin\WPTeam\Admin\HelpPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the Team Free
 */
class Help {

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
		'team-free'                      => 'main.php',
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
		'team_help',
	);


	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'team_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'spf_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main help page Instance
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
		wp_enqueue_style( 'sp-team-help', SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/css/help-page.min.css', array(), SPT_PLUGIN_VERSION );
		wp_enqueue_style( 'sp-team-help-fontello', SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/css/fontello.min.css', array(), SPT_PLUGIN_VERSION );

		wp_enqueue_script( 'sp-team-help', SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/js/help-page.min.js', array(), SPT_PLUGIN_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sptp_member',
			__( 'WP Team', 'team-free' ),
			__( 'Recommended', 'team-free' ),
			'manage_options',
			'edit.php?post_type=sptp_member&page=team_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=sptp_member',
			__( 'WP Team', 'team-free' ),
			__( 'Lite vs Pro', 'team-free' ),
			'manage_options',
			'edit.php?post_type=sptp_member&page=team_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=sptp_member',
			__( 'Team Help', 'team-free' ),
			__( 'Get Help', 'team-free' ),
			'manage_options',
			'team_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Spwpteam_plugins_info_api_help_page function.
	 *
	 * @return void
	 */
	public function spwpteam_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'spwpteam_plugins' );
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

				set_transient( 'spwpteam_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
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

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=745&amp;height=550' );
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
									<?php esc_html_e( 'Activate', 'team-free' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'team-free' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_attr( 'More information about' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'team-free' ); ?>
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
							<strong><?php esc_html_e( 'Version:', 'team-free' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						$active_installs = number_format_i18n( $plugin['active_installs'] );
						?>
						<div class="column-downloaded">
						<?php echo esc_html( $active_installs ) . esc_html__( '+ Active Installations', 'team-free' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'team-free' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) . ' ago' ); ?></span>
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
		return wp_nonce_url( admin_url( 'edit.php?post_type=sptp_member&page=team_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'sptp_member' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

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
	 * The team Help Callback.
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
		<div class="sp-team-help">
			<!-- Header section start -->
			<section class="spwpteam__help header">
				<div class="spwpteam-header-area-top">
					<p>You’re currently using <b>WP Team Lite</b>. To access additional features, consider <a target="_blank" href="https://getwpteam.com/pricing/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="spwpteam-header-area">
					<div class="spwpteam-container">
						<div class="spwpteam-header-logo">
							<img src="<?php echo esc_url( SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SPT_PLUGIN_VERSION ); ?></span>
						</div>
					</div>
					<div class="spwpteam-header-logo-shape">
						<img src="<?php echo esc_url( SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="spwpteam-header-nav">
					<div class="spwpteam-container">
						<div class="spwpteam-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sptp_member&page=team_help#get-start' ); ?>"><i class="spwpteam-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sptp_member&page=team_help#recommended' ); ?>" data-id="recommended-tab"><i class="spwpteam-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sptp_member&page=team_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="spwpteam-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=sptp_member&page=team_help#about-us' ); ?>" data-id="about-us-tab"><i class="spwpteam-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="spwpteam__help start-page" id="get-start-tab">
				<div class="spwpteam-container">
					<div class="spwpteam-start-page-wrap">
						<div class="spwpteam-video-area">
							<h2 class='spwpteam-section-title'>Welcome to WP Team!</h2>
							<span class='spwpteam-normal-paragraph'>Thank you for installing WP Team! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/E1PwdV-czeU?si=35rPQTqFmEEbKrbn" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='spwpteam-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=sptp_generator' ); ?>">Create a Team</a></li>
								<li><a target="_blank" class='spwpteam-medium-btn' href="https://getwpteam.com/wp-team-lite-version-demo/">Live Demo</a></li>
								<li><a target="_blank" class='spwpteam-medium-btn arrow-btn' href="https://getwpteam.com/">Explore WP Team <i class="spwpteam-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="spwpteam-start-page-sidebar">
							<div class="spwpteam-start-page-sidebar-info-box">
								<div class="spwpteam-info-box-title">
									<h4><i class="spwpteam-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='spwpteam-normal-paragraph'>Explore WP Team plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='spwpteam-small-btn' href="https://getwpteam.com/docs/">Browse Now</a>
							</div>
							<div class="spwpteam-start-page-sidebar-info-box">
								<div class="spwpteam-info-box-title">
									<h4><i class="spwpteam-icon-support"></i> Technical Support</h4>
								</div>
								<span class='spwpteam-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='spwpteam-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="spwpteam-start-page-sidebar-info-box">
								<div class="spwpteam-info-box-title">
									<h4><i class="spwpteam-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='spwpteam-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='spwpteam-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="spwpteam__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="spwpteam-container">
					<div class="spwpteam-call-to-action-top">
						<h2 class="spwpteam-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://getwpteam.com/pricing/?ref=1" class='spwpteam-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="spwpteam-lite-to-pro-wrap">
						<div class="spwpteam-features">
							<ul>
								<li class='spwpteam-header'>
									<span class='spwpteam-title'>FEATURES</span>
									<span class='spwpteam-free'>Lite</span>
									<span class='spwpteam-pro'><i class='spwpteam-icon-pro'></i> PRO</span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>All Free Version Features</span>
									<span class='spwpteam-free spwpteam-check-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Team Layouts (Carousel, Grid, Isotope, List, Mosaic, Inline, Table, Accordion, Thumbs Pager, etc.) <i class="spwpteam-hot">Hot</i></span>
									<span class='spwpteam-free'><b>3</b></span>
									<span class='spwpteam-pro'><b>9</b></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Add Unlimited Team Member Groups</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Information Fields (Email, Location, Degree, Experience, Skill level, Photo Gallery, etc.) <i class="spwpteam-new">New</i></span>
									<span class='spwpteam-free'><b>5</b></span>
									<span class='spwpteam-pro'><b>24+</b></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Add Unlimited Team Member Custom Information</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Social Profiles (X, LinkedIn, Facebook, etc.)</span>
									<span class='spwpteam-free'><b>15</b></span>
									<span class='spwpteam-pro'><b>40+</b></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Filter Team Members by Newest, Categories or Groups, Specific, and Exclude.</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Members Ajax Live Filter and Search Options <i class="spwpteam-new">New</i></span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Schema Markup Supported and Equalize Members' Height</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Content Position (Below, Above, Right, Left, Overlay, Caption, etc)</span>
									<span class='spwpteam-free'><b>2</b></span>
									<span class='spwpteam-pro'><b>8</b></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Choose Overlay Content Type, Position, Visibility, Background, etc.</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Show/Hide Meta Icon and Member Felds Drag and Drop Sorting</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Bio Character Limit and Read More Button</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Skill Bars Custom Color (Progress Bar Color, Tooltip Color)</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Customizable Social Profiles (Position, Margin, and Social Icon Shape)</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Multiple Ajax Paginations (Number, Load More, Infinite Scroll, and Normal)</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Display Call To Action (CTA) Button (Title, Button Label, Button Link) <i class="spwpteam-new">New</i></span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Image Custom Sizing and Retina Ready Supported</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Image Shapes</span>
									<span class='spwpteam-free'><b>1</b></span>
									<span class='spwpteam-pro'><b>3</b></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Image Border, Background, Zoom In, Zoom Out Effects</span>
									<span class='spwpteam-free spwpteam-check-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Image Inner Padding, Image Flip, Lazy Load, and Grayscale Effects</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Button/Drop Down Isotope/Shuffle Filter Type</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Multi-level Members Filtering (Groups, Position, and Location) <i class="spwpteam-new">New</i> <i class="spwpteam-hot">Hot</i></span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Isotope/Shuffle Filter Button Color, Alignment, etc.</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>3 Detail Page link Types (Modal, Drawer, and Single Page)</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Single and Multiple popup view with the navigation button</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>4 Modal layouts (Classic Modal, Slide-ins Left, Center, Right) <i class="spwpteam-hot">Hot</i></span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Single Page Layouts</span>
									<span class='spwpteam-free'><b>1</b></span>
									<span class='spwpteam-pro'><b>2</b></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Member Single Page Image Dimension, Location Clickable, Download PDF Button, etc.</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Modal Background, Member Name Clickable, Member Detail PDF Download</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Sortable Member Detail Page Fields</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Template Overriding or Modification and Required Filter and Action Hooks</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Powerful Carousel Settings (Autoplay, Loop, Auto Height, Navigation, Pagination, etc.)</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Multi-row Team Carousels</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Stylize your Team Showcase Typography with 1500+ Google Fonts</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Export or Import Team Members (CSV) and Team Showcase (Shortcodes)</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
								<li class='spwpteam-body'>
									<span class='spwpteam-title'>Priority Top-notch Support</span>
									<span class='spwpteam-free spwpteam-close-icon'></span>
									<span class='spwpteam-pro spwpteam-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="spwpteam-upgrade-to-pro">
							<h2 class='spwpteam-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='spwpteam-section-subtitle'>Already, <b>15,000+</b> people are using WP Team on their websites to create beautiful showcase, why won’t you!</span>
							<div class="spwpteam-upgrade-to-pro-btn">
								<div class="spwpteam-action-btn">
									<a target="_blank" href="https://getwpteam.com/pricing/?ref=1" class='spwpteam-big-btn'>Upgrade to Pro Now!</a>
									<span class='spwpteam-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://getwpteam.com/" class='spwpteam-big-btn-border'>See All Features</a>
								<a target="_blank" class="spwpteam-big-btn-border spwpteam-pro-live-btn" href="https://getwpteam.com/carousel/">Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="spwpteam-testimonial">
						<div class="spwpteam-testimonial-title-section">
							<span class='spwpteam-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="spwpteam-section-title">Our Users Love WP Team Pro!</h2>
						</div>
						<div class="spwpteam-testimonial-wrap">
							<div class="spwpteam-testimonial-area">
								<div class="spwpteam-testimonial-content">
									<p>The best filterable grid plugin I found to edit the text for longer details, so that the text doesn’t just run like a ribbon in a row. Nice minimalist design, various options can be activated and sorting by drag an...</p>
								</div>
								<div class="spwpteam-testimonial-info">
									<div class="spwpteam-img">
										<img src="<?php echo esc_url( SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/img/regina.png' ); ?>" alt="">
									</div>
									<div class="spwpteam-info">
										<h3>Regina Jungk</h3>
										<div class="spwpteam-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwpteam-testimonial-area">
								<div class="spwpteam-testimonial-content">
									<p>I had an issue where a WP Team feature was behaving slightly wrong, due to an incompatibility with my theme. The support team responded quickly to my question, and eventually solved the issu...</p>
								</div>
								<div class="spwpteam-testimonial-info">
									<div class="spwpteam-img">
										<img src="<?php echo esc_url( SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/img/aaron.png' ); ?>" alt="">
									</div>
									<div class="spwpteam-info">
										<h3>Aaron Brown</h3>
										<div class="spwpteam-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="spwpteam-testimonial-area">
								<div class="spwpteam-testimonial-content">
									<p>I’m using a pro version to display 300+ team members. The plugin works really great, and has loads of features, that help the formatting and filtering. When I had some questions and doubts, the suppor...</p>
								</div>
								<div class="spwpteam-testimonial-info">
									<div class="spwpteam-img">
										<img src="<?php echo esc_url( SPT_PLUGIN_ROOT . 'src/Admin/HelpPage/img/voo.png' ); ?>" alt="">
									</div>
									<div class="spwpteam-info">
										<h3>Voo Voo Internet Marketing</h3>
										<div class="spwpteam-star">
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
			<section id="recommended-tab" class="spwpteam-recommended-page">
				<div class="spwpteam-container">
					<h2 class="spwpteam-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="spwpteam-wp-list-table plugin-install-php">
						<div class="spwpteam-recommended-plugins" id="the-list">
							<?php
								$this->spwpteam_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="spwpteam__help about-page">
				<div class="spwpteam-container">
					<div class="spwpteam-about-box">
						<div class="spwpteam-about-info">
							<h3>The Most Versatile and Industry-leading WordPress Team  Showcase plugin by the ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we searched for the best way to display the team members who are at the heart of your company or organization. Unfortunately, we couldn't find a suitable plugin that met our needs. Therefore, we set a simple goal: to develop a powerful WordPress team showcase plugin that will allow you to highlight your team's talent and expertise!</p>
							<p>We aim to provide the easiest and most convenient way to create unlimited, visually appealing team member showcases for your WordPress websites. Explore it now, and you will surely love the experience!</p>
							<div class="spwpteam-about-btn">
								<a target="_blank" href="https://getwpteam.com/" class='spwpteam-medium-btn'>Explore WP Team</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='spwpteam-medium-btn spwpteam-arrow-btn'>More About Us <i class="spwpteam-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="spwpteam-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="spwpteam-our-plugin-list">
						<h3 class="spwpteam-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="spwpteam-our-plugin-list-wrap">
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://wordpresscarousel.com/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.png" alt="">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="spwpteam-our-plugin-list-box">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Gallery Slider for WooCommerce</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.png" alt="">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.png" alt="">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-category-slider-pro/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="">
								<h4>Category Slider for WooCommerce</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://wptabs.com/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://shapedplugin.com/plugin/woocommerce-quick-view-pro/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="spwpteam-our-plugin-list-box" href="https://shapedplugin.com/plugin/smart-brands-for-woocommerce/">
								<i class="spwpteam-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="spwpteam-footer">
				<div class="spwpteam-footer-top">
					<p><span>Made With <i class="spwpteam-icon-heart"></i> </span> By the Team <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a></p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="spwpteam-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="spwpteam-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="spwpteam-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="spwpteam-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}

}
