<?php
/**
 * Starter Sites Admin class
 *
 * Collection of admin hooks
 *
 * @package Xpro_Elementor_Starter_Sites
 * @since 1.0.0
 */

use Elementor\Plugin;
use XproElementorAddonsPro\Libs\Xpro_Elementor_License;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Xpro_Elementor_Starter_Sites_Admin {

	/**
	 * The Current theme name
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $theme_name The Current theme name.
	 */
	public $theme_name = '';
	/**
	 * Store logs and errors
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $logs Store logs and errors.
	 */
	public $logs = array();
	/**
	 * Store errors
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $errors Store errors.
	 */
	public $errors = array();
	/**
	 * Current added Menu hook_suffix
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $logs Store logs and errors.
	 */
	public $hook_suffix;
	/**
	 * Array of remap
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $remap Array of steps.
	 */
	public $remap = array();
	/**
	 * Current step
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $step Current step.
	 */
	protected $step = '';
	/**
	 * Array of steps
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $steps Array of steps.
	 */
	protected $steps = array();
	/**
	 * Demo lists
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $demo_lists Array of demo lists.
	 */
	protected $demo_lists = array();
	/**
	 * Demo lists
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $demo_lists Array of demo lists.
	 */
	protected $is_pro_active = false;
	/**
	 * Array of delayed post for late process
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array $delay_posts Array of delayed post for late process.
	 */
	private $delay_posts = array();
	/**
	 * Slug of the import page
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $logs Store logs and errors.
	 */
	private $current_template_type;

	/**
	 * Slug of the import page
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string $logs Store logs and errors.
	 */
	private $current_template_url;

	/**
	 * Total requests
	 *
	 * @since    1.3.3
	 * @access   public
	 * @var      int $total_request Store total request for progress bar.
	 */
	private $total_request;
	private $current_request = 0;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Main Xpro_Elementor_Starter_Sites_Admin Instance
	 * Initialize the class and set its properties.
	 *
	 * @return object $instance Xpro_Elementor_Starter_Sites_Admin Instance
	 * @since    1.0.0
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication.
		static $instance = null;

		// Only run these methods if they haven't been ran previously.
		if ( null == $instance ) {
			$instance = new self();

			/*page slug using theme name */
			$instance->theme_name = sanitize_key( get_option( 'template' ) );

		}

		// Always return the instance.
		return $instance;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook_suffix current hook.
	 *
	 * @return void
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {

		if ( ! is_array( $this->hook_suffix ) || ! in_array( $hook_suffix, $this->hook_suffix, true ) ) {
			return;
		}
		wp_enqueue_style(
			'xpro-icons',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'css/xpro-icons.min.css',
			null,
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_style(
			'xpro-elementor-starter-sites',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/css/starter-sites.css',
			array( 'wp-admin' ),
			XPRO_ELEMENTOR_ADDONS_VERSION
		);
		wp_enqueue_media();
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $hook_suffix current hook.
	 *
	 * @return void
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {

		if ( ! is_array( $this->hook_suffix ) || ! in_array( $hook_suffix, $this->hook_suffix, true ) ) {
			return;
		}

		// Isotope Js.
		wp_enqueue_script(
			'isotope',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/isotope.pkgd.min.js',
			array( 'jquery' ),
			'3.0.6',
			true
		);

		// sweetalert2 Js.
		wp_enqueue_script(
			'sweetalert2',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/sweetalert2.all.min.js',
			array( 'jquery' ),
			'3.0.6',
			true
		);

		wp_enqueue_script(
			'xpro-elementor-starter-sites',
			XPRO_ELEMENTOR_ADDONS_ASSETS . 'admin/js/starter-sites.js',
			array( 'jquery', 'masonry' ),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);

		wp_localize_script(
			'xpro-elementor-starter-sites',
			'xpro_elementor_starter_sites_object',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'wpnonce' => wp_create_nonce( 'xpro_elementor_starter_sites_nonce' ),
				'text'    => array(
					'failed'        => esc_html__( 'Failed', 'xpro-elementor-addons' ),
					'error'         => esc_html__( 'Error', 'xpro-elementor-addons' ),
					'skip'          => esc_html__( 'Skipping', 'xpro-elementor-addons' ),
					'confirmImport' => array(
						'title'             => esc_html__( 'Starter Sites! Just a step away', 'xpro-elementor-addons' ),
						'html'              => sprintf(
						/* translators: 1: message 1, 2: message 2., 3: message 3., 4: message 4. */
							__( 'Importing demo data is the easiest way to setup your theme. It will allow you to quickly edit everything instead of creating content from scratch. Also, read following points before importing the demo: %1$s %2$s %3$s %4$s', 'xpro-elementor-addons' ),
							'<ol><li class="warning">' . __( 'It is highly recommended to import demo on fresh WordPress installation to exactly replicate the theme demo. You can reset it from Reset Wizard at the top', 'xpro-elementor-addons' ) . '</li>',
							'<li>' . __( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified.', 'xpro-elementor-addons' ) . '</li>',
							'<li>' . __( 'It will install the plugins required for demo and activate them. Also posts, pages, images, widgets, & other data will get imported.', 'xpro-elementor-addons' ) . '</li>',
							'<li class="xs-plugin-info">' . __( 'The demo will install following plugin(s):', 'xpro-elementor-addons' ) . 'xs_replace_plugins' . '</li>' .
							'<li>' . __( 'Please click on the Import button and wait, it will take some time to import the data.', 'xpro-elementor-addons' ) . '</li></ol>'
						),
						'confirmButtonText' => esc_html__( 'Yes, Import', 'xpro-elementor-addons' ),
						'cancelButtonText'  => esc_html__( 'Cancel', 'xpro-elementor-addons' ),
						'no_plugins'        => esc_html__( 'No plugins will be installed.', 'xpro-elementor-addons' ),
					),
					'confirmReset'  => array(
						'title'             => esc_html__( 'Are you sure?', 'xpro-elementor-addons' ),
						'text'              => __( 'Do you really want to proceed? This action will remove all your site data.', 'xpro-elementor-addons' ),
						'confirmButtonText' => esc_html__( 'Yes, Reset', 'xpro-elementor-addons' ),
						'cancelButtonText'  => esc_html__( 'Cancel', 'xpro-elementor-addons' ),
						'resetting'         => esc_html__( 'Resetting! WordPress set to default.', 'xpro-elementor-addons' ),
					),
					'resetSuccess'  => array(
						'title'             => esc_html__( 'Reset Successfully', 'xpro-elementor-addons' ),
						'confirmButtonText' => esc_html__( 'Ok', 'xpro-elementor-addons' ),
					),
					'failedImport'  => array(
						'code'        => __( 'Error Code:', 'xpro-elementor-addons' ),
						'text'        => __( 'Contact theme author or try again', 'xpro-elementor-addons' ),
						'pluginError' => __( 'Your WordPress could not install the plugin correctly. You have to install the following plugin manually:', 'xpro-elementor-addons' ),
						'plugin'      => __( 'Plugin:', 'xpro-elementor-addons' ),
						'slug'        => __( 'Slug:', 'xpro-elementor-addons' ),
					),
					'successImport' => array(
						'confirmButtonText' => esc_html__( 'Visit Site', 'xpro-elementor-addons' ),
						'cancelButtonText'  => esc_html__( 'Close', 'xpro-elementor-addons' ),
					),
				),
			)
		);
	}

	/**
	 * Add admin menus
	 *
	 * @access public
	 */
	public function import_menu() {

		$this->hook_suffix[] = add_submenu_page(
			Xpro_Elementor_Addons::PAGE_SLUG,
			esc_html__( 'Starter Sites', 'xpro-elementor-addons' ),
			esc_html__( 'Starter Sites', 'xpro-elementor-addons' ),
			'manage_options',
			'xpro-elementor-addons-starter-sites',
			array( $this, 'demo_import_screen' ),
			2
		);

		$this->hook_suffix = apply_filters( 'xpro_elementor_starter_sites_menu_hook_suffix', $this->hook_suffix );
	}

	/**
	 * Show the setup
	 *
	 * @access public
	 * @return void
	 */
	public function demo_import_screen() {
		do_action( 'xpro_elementor_starter_sites_before_demo_import_screen' );

		$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

		$screen = get_current_screen();
		if ( ! in_array( $screen->base, xpro_elementor_starter_sites_admin()->hook_suffix, true ) ) {
			return;
		}
		$current_url = xpro_elementor_starter_sites_current_url();
		$reset_url   = wp_nonce_url(
			add_query_arg( 'xs_reset_wordpress', 'true', $current_url ),
			'xs_reset_wordpress',
			'xs_reset_wordpress_nonce'
		);

		?>

        <div class="xpro-elementor-sites-wrapper">

            <div class="xs-header">
                <div class="xs-header-left">
                    <h2 class="xs-header-title"><?php echo __( 'Xpro Starter Sites', 'xpro-elementor-addons' ); ?></h2>
                    <p class="xs-header-description"><?php echo __( 'This will help you to configure your new website like theme demo.', 'xpro-elementor-addons' ); ?></p>
                </div>
                <div class="xs-header-right">
					<?php echo wp_nonce_field( 'xpro-elementor-starter-sites-reset', 'xpro-elementor-starter-sites-reset', true, false ); ?>
                    <a href="<?php echo esc_url( $reset_url ); ?>" class="button button-primary xs-wp-reset disable"><?php esc_html_e( 'Reset Wizard', 'xpro-elementor-addons' ); ?></a>
                </div>
            </div>

            <div class="xs-body">
                <div class="xs-content">
                    <div class="xs-content-blocker hidden">
                        <div class="xs-notification-title"><p><?php echo esc_html__( 'Process initialize please do not refresh this page!', 'xpro-elementor-addons' ); ?></p></div>
                        <div id="xs-demo-popup"></div>
                    </div>
					<?php $this->init_demo_import(); ?>
                </div>
            </div>


        </div>


		<?php

		do_action( 'xpro_elementor_starter_sites_after_demo_import_screen' );

	}

	/**
	 * 1st step of demo import view
	 * Upload Zip file
	 * Demo List
	 */
	public function init_demo_import() {

		global $pagenow;
		$total_demo = 0;

		$this->demo_lists    = apply_filters( 'xpro_elementor_starter_sites_demo_lists', array() );
		$this->is_pro_active = apply_filters( 'xpro_elementor_starter_sites_is_pro_active', $this->is_pro_active );
		$demo_lists          = $this->demo_lists;

		$total_demo = is_array( $demo_lists ) ? count( $demo_lists ) : 0;
		if ( $total_demo >= 1 ) {
			$this->demo_list( $demo_lists, $total_demo );
		}

		$this->demo_import_form( $total_demo );
	}

	/**
	 * List Demo List
	 *
	 * @return void
	 */
	public function demo_list( $demo_lists, $total_demo ) {
		?>
        <div class="xs-filter-header">
            <div class="xs-filter-categories-wrapper">
                <div class="xs-filter-category-select">
                    <span class="xs-filter-category-select-content"><?php esc_html_e( 'All Categories', 'xpro-elementor-addons' ); ?></span>
                    <i class="xi xi-chevron-down"></i>
                </div>
                <!--Category List-->
                <ul class="xs-import-available-categories-lists xs-filter-group" data-filter-group="primary">
                    <li class="xs-filter-btn-active xs-filter-btn" data-filter="*">
						<?php esc_html_e( 'All Categories', 'xpro-elementor-addons' ); ?>
                    </li>
					<?php
					$categories        = array_column( $demo_lists, 'categories' );
					$unique_categories = array();
					if ( is_array( $categories ) && ! empty( $categories ) ) {
						foreach ( $categories as $demo_index => $demo_cats ) {
							foreach ( $demo_cats as $cat_index => $single_cat ) {
								if ( in_array( $single_cat, $unique_categories, true ) ) {
									continue;
								}
								$unique_categories[] = $single_cat;
								?>
                                <li class="xs-filter-btn" data-filter=".<?php echo strtolower( esc_attr( $single_cat ) ); ?>">
									<?php echo ucfirst( esc_html( $single_cat ) ); ?>
                                </li>
								<?php
							}
						}
					}
					?>
                </ul>
            </div>
            <button class="xs-upload-zip-button xs-form-file-import">
				<?php esc_html_e( 'Upload Zip', 'xpro-elementor-addons' ); ?>
                <i class="xi xi-cloud-upload"></i>
            </button>
        </div>
        <div class="xs-filter-content" id="xs-filter-content">
            <div class="xs-content-inner-actions">
                <ul class="xs-import-fp-lists xs-filter-group" data-filter-group="pricing">
                    <li class="xs-fp-filter xs-filter-btn xs-filter-btn-active" data-filter="*">All</li>
                    <li class="xs-fp-filter xs-filter-btn" data-filter=".xs-fp-filter-free">Free</li>
                    <li class="xs-fp-filter xs-filter-btn" data-filter=".xs-fp-filter-pro">Pro</li>
                </ul>
                <div class="xs-total-themes-wrap">
                    <h4 class="xs-total-themes-content">
                        <span class="xs-count"><?php echo esc_html( $total_demo ); ?></span>
						<?php echo __( ' Themes Found', 'xpro-elementor-addons' ); ?>
                    </h4>
                </div>
                <div class="xs-search-control">
                    <input id="xs-filter-search-input" class="xs-search-filter" type="text" placeholder="<?php esc_attr_e( 'Search...', 'xpro-elementor-addons' ); ?>">
                </div>
            </div>
            <div class="xs-filter-content-wrapper">
				<?php
				foreach ( $demo_lists as $key => $demo_list ) {

					/*Check for required fields*/
					if ( ! isset( $demo_list['title'] ) || ! isset( $demo_list['screenshot_url'] ) || ! isset( $demo_list['demo_url'] ) ) {
						continue;
					}

					$template_url = isset( $demo_list['template_url'] ) ? $demo_list['template_url'] : '';
					if ( is_array( $template_url ) ) {
						$data_template      = 'data-template_url="' . esc_attr( wp_json_encode( $template_url ) ) . '"';
						$data_template_type = 'data-template_type="array"';
					} elseif ( $template_url ) {
						$data_template = 'data-template_url="' . esc_attr( $template_url ) . '"';
						if ( is_file( $template_url ) && filesize( $template_url ) > 0 ) {
							$data_template_type = 'data-template_type="file"';
						} else {
							$data_template_type = 'data-template_type="url"';
						}
					} else {
						$data_template      = 'data-template_url="' . esc_attr( wp_json_encode( $template_url ) ) . '"';
						$data_template_type = 'data-template_type="array"';
					}
					?>
                    <div aria-label="<?php echo esc_attr( $demo_list['title'] ); ?>" class="xs-item
												<?php
					echo isset( $demo_list['categories'] ) ? esc_attr( implode( ' ', $demo_list['categories'] ) ) : '';
					echo isset( $demo_list['type'] ) ? ' ' . esc_attr( $demo_list['type'] ) : '';
					echo $this->is_pro( $demo_list ) ? ' xs-fp-filter-pro' : ' xs-fp-filter-free';
					echo $this->is_template_available( $demo_list ) ? '' : ' xs-pro-item'
					?>
					" <?php echo $this->is_template_available( $demo_list ) ? $data_template . ' ' . $data_template_type : ''; ?>>
						<?php wp_nonce_field( 'xpro-elementor-starter-sites' ); ?>
                        <div class="xs-item-preview">
                            <div class="xs-item-screenshot">
                                <img src="<?php echo esc_url( $demo_list['screenshot_url'] ); ?>" width="340" height="385" loading="lazy" alt="screenshot">
                                <div class="xpro-item-overlay">
                                    <a class="button xs-item-demo-link" href="<?php echo esc_url( $demo_list['demo_url'] ); ?>" target="_blank">
										<?php esc_html_e( 'Preview', 'xpro-elementor-addons' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="xs-item-footer">
                            <div class="xs-item-footer_meta">
                                <h3 class="theme-name"><?php echo esc_html( $demo_list['title'] ); ?></h3>
                                <div class="xs-item-footer-actions">
									<?php echo $this->template_button( $demo_list ); ?>
                                </div>
								<?php
								$keywords = isset( $demo_list['keywords'] ) ? $demo_list['keywords'] : array();
								if ( ! empty( $keywords ) ) {
									echo '<ul class="xs-keywords hidden">';
									foreach ( $keywords as $cat_index => $single_keywords ) {
										?>
                                        <li class="<?php echo strtolower( esc_attr( $single_keywords ) ); ?>"><?php echo ucfirst( esc_html( $single_keywords ) ); ?></li>
										<?php
									}
									echo '</ul>';
								}
								?>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
	}

	/**
	 * Check if template is available to import
	 *
	 * @param array $item current array of demo list.
	 *
	 * @return boolean
	 * @since    1.0.0
	 */
	public function is_pro( $item ) {

		$is_pro = false;
		if ( isset( $item['is_pro'] ) && $item['is_pro'] ) {
			$is_pro = true;
		}

		return (bool) apply_filters( 'xpro_elementor_starter_sites_is_pro', $is_pro, $item );
	}

	/**
	 * Check if template is available to import
	 *
	 * @param array $item current array of demo list.
	 *
	 * @return boolean
	 * @since    1.0.0
	 */
	public function is_template_available( $item ) {
		$is_available = false;

		if ( did_action( 'xpro_elementor_addons_pro_loaded' ) && class_exists( '\XproElementorAddonsPro\Libs\Xpro_Elementor_License' ) && 'valid' == Xpro_Elementor_License::$license_activate ) {
			$this->is_pro_active = true;
		}

		/*if pro active everything is available*/
		if ( $this->is_pro_active ) {
			$is_available = true;
		} elseif ( ! isset( $item['is_pro'] ) ) {/*if is_pro not set the $item is available*/
			$is_available = true;/*template available since */
		} elseif ( isset( $item['is_pro'] ) && ! $item['is_pro'] ) {/*if is_pro not set but it is false, it will be free and avialable*/
			$is_available = true;
		}

		return (bool) apply_filters( 'xpro_elementor_starter_sites_is_template_available', $is_available, $item );
	}

	/**
	 * Return Template Button
	 *
	 * @param array $item current array of demo list.
	 *
	 * @return string
	 * @since    1.0.0
	 */
	public function template_button( $item ) {
		/*Start buffering*/
		ob_start();

		if ( $this->is_template_available( $item ) ) {
			$plugins = isset( $item['plugins'] ) && is_array( $item['plugins'] ) ? ' data-plugins="' . esc_attr( wp_json_encode( $item['plugins'] ) ) . '"' : '';
			?>
            <a class="button xs-demo-import xs-item-import" href="javascript:void(0)" aria-label="<?php esc_attr_e( 'Import', 'xpro-elementor-addons' ); ?>" <?php echo $plugins; ?>>
                <i class="xi xi-refresh"></i><?php esc_html_e( 'Import', 'xpro-elementor-addons' ); ?>
            </a>
			<?php
		} else {
			?>
            <a class="button xs-get-pro-btn" href="<?php echo esc_url( 'https://elementor.wpxpro.com/buy/' ); ?>" target="_blank" aria-label="<?php esc_attr_e( 'View Pro', 'xpro-elementor-addons' ); ?>">
				<?php esc_html_e( 'Buy Pro', 'xpro-elementor-addons' ); ?>
            </a>
			<?php
		}

		/*removes the buffer (without printing it), and returns its content.*/
		$render_button = ob_get_clean();

		$render_button = apply_filters( 'xpro_elementor_starter_sites_template_import_button', $render_button, $item );

		return $render_button;
	}

	/**
	 * List Demo Form
	 *
	 * @return void
	 */

	public function demo_import_form( $total_demo = 0 ) {
		?>
        <div class="xs-form <?php echo $total_demo > 0 ? 'hidden' : ''; ?>">
            <form action="" method="post" enctype="multipart/form-data" id="xs-upload-zip-form">
                <h3 class="media-title"><?php esc_html_e( 'Upload a zip file containing demo content', 'xpro-elementor-addons' ); ?> </h3>
                <div class="input-file"><input type="file" name="xs-upload-zip-archive" id="xs-upload-zip-archive" size="50"/></div>
                <p>
					<?php
					wp_nonce_field( 'xpro-elementor-starter-sites' );
					printf( __( 'Maximum upload file size: %s', 'xpro-elementor-addons' ), size_format( wp_max_upload_size() ) );
					?>
                </p>
                <div id='xs-empty-file' class="error hidden">
                    <p>
						<?php esc_html_e( 'Select File and Try Again!', 'xpro-elementor-addons' ); ?>
                    </p>
                </div>
                <p class="xs-form-import-actions step">
                    <button class="button-upload-demo" type="submit">
						<?php esc_html_e( 'Upload Demo Zip', 'xpro-elementor-addons' ); ?>
                    </button>
                    <button type="button" class="button-cancel-upload">
						<?php esc_html_e( 'Not Right Now', 'xpro-elementor-addons' ); ?>
                    </button>
                </p>
                <div id='xs-ajax-install-result'></div>
            </form>
        </div>
		<?php
	}

	/**
	 * Handle the demo content upload and called to process
	 * Ajax callback
	 *
	 * @return void
	 */
	public function upload_zip() {
		if ( isset( $_FILES['xs-upload-zip-archive']['name'] ) && ! empty( $_FILES['xs-upload-zip-archive']['name'] ) ) {
			/*check for security*/
			if ( ! current_user_can( 'upload_files' ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
					)
				);
			}
			check_admin_referer( 'xpro-elementor-starter-sites' );

			/*file process*/
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
			$upload_zip_archive = $_FILES['xs-upload-zip-archive'];
			$unzipfile          = unzip_file( $upload_zip_archive['tmp_name'], XPRO_ELEMENTOR_ADDONS_TEMP );
			if ( is_wp_error( $unzipfile ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Error on unzipping, Please try again', 'xpro-elementor-addons' ),
					)
				);
			}
			/*(check) Zip should contain json file and uploads folder only*/
			$dirlist = $wp_filesystem->dirlist( XPRO_ELEMENTOR_ADDONS_TEMP );
			foreach ( (array) $dirlist as $filename => $fileinfo ) {
				if ( 'd' == $fileinfo['type'] ) {
					if ( 'uploads' == $filename ) {
						continue;
					} else {
						$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
						wp_send_json_error(
							array(
								'message' => esc_html__( 'Invalid Zip File', 'xpro-elementor-addons' ),
							)
						);
					}
				} else {
					$filetype = wp_check_filetype( $filename, $this->mime_types() );
					if ( empty( $filetype['ext'] ) || 'json' != $filetype['ext'] ) {
						$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
						wp_send_json_error(
							array(
								'message' => esc_html__( 'Invalid Zip File', 'xpro-elementor-addons' ),
							)
						);
					}
				}
			}
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Success', 'xpro-elementor-addons' ),
				)
			);
		}
		wp_send_json_error(
			array(
				'message' => esc_html__( 'No Zip File Found', 'xpro-elementor-addons' ),
			)
		);
	}

	/**
	 * Adding new mime types.
	 *
	 * @access public
	 *
	 * @param array $mimes existing mime types.
	 *
	 * @return array
	 * @since    1.0.0
	 */
	public function mime_types( $mimes = array() ) {
		$add_mimes = array(
			'json' => 'application/json',
			'svg'  => 'image/svg+xml',
			'svgz' => 'image/svg+xml',
		);

		return array_merge( $mimes, $add_mimes );
	}

	/**
	 * Download Zip file/ Move it to temp import folder
	 * Ajax callback
	 *
	 * @return mixed
	 */
	public function demo_download_and_unzip() {

		/*check for security*/
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
				)
			);
		}
		check_admin_referer( 'xpro-elementor-starter-sites' );

		/*get file and what should do*/
		$demo_file      = is_array( $_POST['demo_file'] ) ? (array) $_POST['demo_file'] : sanitize_text_field( $_POST['demo_file'] );
		$demo_file_type = sanitize_text_field( $_POST['demo_file_type'] );

		/*from file*/
		if ( 'file' == $demo_file_type ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;
			$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
			$unzipfile = unzip_file( $demo_file, XPRO_ELEMENTOR_ADDONS_TEMP );
			if ( is_wp_error( $unzipfile ) ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Error on unzipping, Please try again', 'xpro-elementor-addons' ),
					)
				);
			}
			/*(check) Zip should contain json file and uploads folder only*/
			$dirlist = $wp_filesystem->dirlist( XPRO_ELEMENTOR_ADDONS_TEMP );
			foreach ( (array) $dirlist as $filename => $fileinfo ) {
				if ( 'd' == $fileinfo['type'] ) {
					if ( 'uploads' == $filename ) {
						continue;
					} else {
						$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
						wp_send_json_error(
							array(
								'message' => esc_html__( 'Invalid Zip File', 'xpro-elementor-addons' ),
							)
						);
					}
				} else {
					$filetype = wp_check_filetype( $filename, $this->mime_types() );
					if ( empty( $filetype['ext'] ) || 'json' != $filetype['ext'] ) {
						$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
						wp_send_json_error(
							array(
								'message' => esc_html__( 'Invalid Zip File', 'xpro-elementor-addons' ),
							)
						);
					}
				}
			}
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Success', 'xpro-elementor-addons' ),
				)
			);
		} elseif ( 'url' == $demo_file_type ) {

			/*finally fetch the file from remote*/
			$response = wp_remote_get( $demo_file );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
				global $wp_filesystem;
				$file_permission = 0777;
				if ( ! file_exists( XPRO_ELEMENTOR_ADDONS_TEMP_ZIP ) ) {
					$wp_filesystem->mkdir( XPRO_ELEMENTOR_ADDONS_TEMP_ZIP, $file_permission, true );
				}
				$temp_import_zip = trailingslashit( XPRO_ELEMENTOR_ADDONS_TEMP_ZIP ) . 'temp-import.zip';
				$wp_filesystem->put_contents( $temp_import_zip, $response['body'], 0777 );

			} else {
				/*required to download file failed.*/
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__( 'Remote server did not respond, Contact to Theme Author', 'xpro-elementor-addons' ),
					)
				);
			}

			$file_size = filesize( $temp_import_zip );

			/*if file size is 0*/
			if ( 0 == $file_size ) {
				$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP_ZIP, true );
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__( 'Zero size file downloaded, Contact to Theme Author', 'xpro-elementor-addons' ),
					)
				);
			}

			/*if file is too large*/
			if ( ! empty( $max_size ) && $file_size > $max_size ) {
				$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP_ZIP, true );
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => sprintf( esc_html__( 'Remote file is too large, limit is %s', 'xpro-elementor-addons' ), size_format( $max_size ) ),
					)
				);
			}

			/*if we are here then unzip file*/
			$unzipfile = unzip_file( $temp_import_zip, XPRO_ELEMENTOR_ADDONS_TEMP );
			if ( is_wp_error( $unzipfile ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__( 'Error on unzipping, Please try again', 'xpro-elementor-addons' ),
					)
				);
			}
			$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP_ZIP, true );

			/*(check) Zip should contain json file and uploads folder only*/
			$dirlist = $wp_filesystem->dirlist( XPRO_ELEMENTOR_ADDONS_TEMP );
			foreach ( (array) $dirlist as $filename => $fileinfo ) {
				if ( 'd' == $fileinfo['type'] ) {
					if ( 'uploads' == $filename ) {
						continue;
					} else {
						$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
						wp_send_json_error(
							array(
								'message' => esc_html__( 'Invalid Zip File', 'xpro-elementor-addons' ),
							)
						);
					}
				} else {
					$filetype = wp_check_filetype( $filename, $this->mime_types() );
					if ( empty( $filetype['ext'] ) || 'json' != $filetype['ext'] ) {
						$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );
						wp_send_json_error(
							array(
								'message' => esc_html__( 'Invalid Zip File', 'xpro-elementor-addons' ),
							)
						);
					}
				}
			}
			wp_send_json_success(
				array(
					'message' => esc_html__( 'Success', 'xpro-elementor-addons' ),
				)
			);

		} else {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'File not allowed', 'xpro-elementor-addons' ),
				)
			);
		}
	}

	/**
	 * 2nd step Plugin Installation step View
	 * return void || boolean
	 */
	public function plugin_screen() {

		/*check for security*/
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
				)
			);
		}

		check_admin_referer( 'xpro-elementor-starter-sites' );

		$this->reset_transient();

		do_action( 'xpro_elementor_starter_sites_before_plugin_screen' );
		?>
        <div class="xs-notification-title">
            <p><?php esc_html_e( 'Installing required plugins to your site...', 'xpro-elementor-addons' ); ?></p>
        </div>
        <ul class="xs-plugins-wrap hidden">
			<?php
			$recommended_plugins = isset( $_POST['recommendedPlugins'] ) ? (array) $_POST['recommendedPlugins'] : array();
			if ( count( $recommended_plugins ) ) {
				foreach ( $recommended_plugins as $index => $recommended_plugin ) {
					?>
                    <li data-slug="<?php echo esc_attr( $recommended_plugin['slug'] ); ?>"
                        data-main_file="<?php echo esc_attr( isset( $recommended_plugin['main_file'] ) ? $recommended_plugin['main_file'] : $recommended_plugin['slug'] . '.php' ); ?>">
						<?php echo esc_html( $recommended_plugin['name'] ); ?>
                    </li>
					<?php
				}
			} else {
				?>
                <li id="xs-no-recommended-plugins"><?php esc_html_e( 'No Recommended Plugins', 'xpro-elementor-addons' ); ?></li>
				<?php
			}
			?>
        </ul>
		<?php
		do_action( 'xpro_elementor_starter_sites_after_plugin_screen' );
		exit;
	}

	public function reset_transient() {
		delete_transient( 'content.json' );
		delete_transient( 'widgets.json' );
		delete_transient( 'options.json' );
		delete_transient( 'settings.json' );
		delete_transient( 'delayed_posts' );
		delete_transient( 'imported_term_ids' );
		delete_transient( 'imported_post_ids' );
		delete_transient( 'post_orphans' );
		delete_transient( 'adi_elementor_data_posts' );
	}

	/**
	 * 3rd Step Step for content, widget, setting import
	 * Page setup
	 */
	public function content_screen() {

		/*check for security*/
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
				)
			);
		}
		check_admin_referer( 'xpro-elementor-starter-sites' );

		if ( isset( $_POST['template_url'] ) ) {
			$this->current_template_url  = is_array( $_POST['template_url'] ) ? (array) $_POST['template_url'] : sanitize_text_field( $_POST['template_url'] );
			$this->current_template_type = sanitize_text_field( $_POST['template_type'] );
		}

		do_action( 'xpro_elementor_starter_sites_before_content_screen' );

		?>
        <div class="xs-notification-title">
            <p>
				<?php esc_html_e( 'Inserting demo content to your new WordPress site...' ); ?>
            </p>
        </div>

        <table class="xs-pages hidden">
            <thead>
            <tr>
                <th class="check"></th>
                <th class="item"><?php esc_html_e( 'Item', 'xpro-elementor-addons' ); ?></th>
                <th class="description"><?php esc_html_e( 'Description', 'xpro-elementor-addons' ); ?></th>
                <th class="status"><?php esc_html_e( 'Status', 'xpro-elementor-addons' ); ?></th>
            </tr>
            </thead>
            <tbody>
			<?php
			$setup_content_steps = $this->xpro_elementor_starter_sites_setup_content_steps();
			foreach ( $setup_content_steps as $slug => $default ) {
				?>
                <tr class="xs-available-content" data-content="<?php echo esc_attr( $slug ); ?>">
                    <td>
                        <input type="checkbox" name="import_content[<?php echo esc_attr( $slug ); ?>]" class="xs-available-content" id="import_content_<?php echo esc_attr( $slug ); ?>" value="1" <?php echo ( ! isset( $default['checked'] ) || $default['checked'] ) ? ' checked' : ''; ?>>
                    </td>
                    <td>
                        <label for="import_content_<?php echo esc_attr( $slug ); ?>">
							<?php echo esc_html( $default['title'] ); ?>
                        </label>
                    </td>
                    <td class="description">
						<?php echo esc_html( $default['description'] ); ?>
                    </td>
                    <td class="status">
						<span>
							<?php echo esc_html( $default['pending'] ); ?>
						</span>
                    </td>
                </tr>
			<?php } ?>
            </tbody>
        </table>
		<?php
		do_action( 'xpro_elementor_starter_sites_after_content_screen' );

		exit;
	}

	private function xpro_elementor_starter_sites_setup_content_steps() {

		$total_content = 0;

		$content = array();

		/*check if there is files*/
		$content_data = $this->get_main_content_json();
		foreach ( $content_data as $post_type => $post_data ) {
			if ( count( $post_data ) ) {
				$total_content        += count( $post_data );
				$first                 = current( $post_data );
				$post_type_title       = ! empty( $first['type_title'] ) ? $first['type_title'] : ucwords( $post_type ) . 's';
				$content[ $post_type ] = array(
					'title'            => $post_type_title,
					'description'      => sprintf( esc_html__( 'This will create default %s as seen in the demo.', 'xpro-elementor-addons' ), $post_type_title ),
					'pending'          => esc_html__( 'Pending.', 'xpro-elementor-addons' ),
					'installing'       => esc_html__( 'Installing.', 'xpro-elementor-addons' ),
					'success'          => esc_html__( 'Success.', 'xpro-elementor-addons' ),
					'install_callback' => array( $this, 'import_content_post_type_data' ),
					'checked'          => $this->is_possible_upgrade() ? 0 : 1,
					// dont check if already have content installed.
				);
			}
		}
		/*
		array adjustment
		TODO : Remove it after adjustment on Starter Sites
		*/
		/*Put post 3nd last*/
		$post = isset( $content['post'] ) ? $content['post'] : array();
		if ( $post ) {
			unset( $content['post'] );
			$content['post'] = $post;
		}
		/*Put page 2nd last*/
		$page = isset( $content['page'] ) ? $content['page'] : array();
		if ( $page ) {
			unset( $content['page'] );
			$content['page'] = $page;

		}
		/*Put nav last*/
		$nav = isset( $content['nav_menu_item'] ) ? $content['nav_menu_item'] : array();
		if ( $nav ) {
			unset( $content['nav_menu_item'] );
			$content['nav_menu_item'] = $nav;
		}
		/*check if there is files*/
		$widget_data = $this->get_widgets_json();
		if ( ! empty( $widget_data ) ) {
			$total_content += 1;

			$content['widgets'] = array(
				'title'            => esc_html__( 'Widgets', 'xpro-elementor-addons' ),
				'description'      => esc_html__( 'Insert default sidebar widgets as seen in the demo.', 'xpro-elementor-addons' ),
				'pending'          => esc_html__( 'Pending.', 'xpro-elementor-addons' ),
				'installing'       => esc_html__( 'Installing Default Widgets.', 'xpro-elementor-addons' ),
				'success'          => esc_html__( 'Success.', 'xpro-elementor-addons' ),
				'install_callback' => array( $this, 'import_content_widgets_data' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				// dont check if already have content installed.
			);
		}

		$options_data = $this->get_theme_options_json();
		if ( ! empty( $options_data ) ) {
			$total_content     += 1;
			$content['options'] = array(
				'title'            => esc_html__( 'Options', 'xpro-elementor-addons' ),
				'description'      => esc_html__( 'Configure default settings.', 'xpro-elementor-addons' ),
				'pending'          => esc_html__( 'Pending.', 'xpro-elementor-addons' ),
				'installing'       => esc_html__( 'Installing Default Options.', 'xpro-elementor-addons' ),
				'success'          => esc_html__( 'Success.', 'xpro-elementor-addons' ),
				'install_callback' => array( $this, 'import_menu_and_options' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				// dont check if already have content installed.
			);
		}

		$options_data = $this->get_theme_settings_json();

		if ( ! empty( $options_data ) ) {
			$total_content      += 1;
			$content['settings'] = array(
				'title'            => esc_html__( 'Settings', 'xpro-elementor-addons' ),
				'description'      => esc_html__( 'Configure default settings.', 'xpro-elementor-addons' ),
				'pending'          => esc_html__( 'Pending.', 'xpro-elementor-addons' ),
				'installing'       => esc_html__( 'Installing Default Settings.', 'xpro-elementor-addons' ),
				'success'          => esc_html__( 'Success.', 'xpro-elementor-addons' ),
				'install_callback' => array( $this, 'import_elementor_site_settings' ),
				'checked'          => $this->is_possible_upgrade() ? 0 : 1,
				// dont check if already have content installed.
			);
		}

		$content = apply_filters( $this->theme_name . '_theme_view_setup_step_content', $content );

		$this->total_request = $total_content;

		return $content;

	}

	public function get_main_content_json() {
		return $this->get_json_data_from_file( 'content.json' );
	}

	/**
	 * 3rd steps helper functions
	 * Get json from json file
	 *
	 * @param string $file file url.
	 *
	 * @return mixed
	 */
	public function get_json_data_from_file( $file ) {

		if ( get_transient( $file ) ) {
			return get_transient( $file );
		}

		if ( 'array' == $this->current_template_type ) {
			$type = strtok( $file, '.' );
			if ( isset( $this->current_template_url[ $type ] ) ) {
				$request  = wp_remote_get( $this->current_template_url[ $type ] );
				$response = wp_remote_retrieve_body( $request );
				$result   = json_decode( $response, true );
				set_transient( $file, $result, 1000 );

				return $result;
			}

			return array();
		}

		if ( is_file( XPRO_ELEMENTOR_ADDONS_TEMP . basename( $file ) ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;
			$file_name = XPRO_ELEMENTOR_ADDONS_TEMP . basename( $file );
			if ( file_exists( $file_name ) ) {
				$result = json_decode( $wp_filesystem->get_contents( $file_name ), true );
				set_transient( $file, $result, 1000 );

				return $result;
			}
		}

		return array();
	}

	/*
	 * return array
	 */

	/**
	 * Determine if the user already has theme content installed.
	 *
	 * @access public
	 */
	public function is_possible_upgrade() {
		return false;
	}

	public function get_widgets_json() {
		return $this->get_json_data_from_file( 'widgets.json' );
	}

	/**
	 * import ajax content
	 * return JSON
	 */

	public function get_theme_options_json() {
		return $this->get_json_data_from_file( 'options.json' );
	}

	/**
	 * import ajax content
	 * return JSON
	 */

	public function get_theme_settings_json() {
		return $this->get_json_data_from_file( 'settings.json' );
	}

	/**
	 * callback function to importing post type
	 * all post type is imported from here
	 * return mix
	 * */

	public function import_content() {

		/*check for security*/
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
				)
			);
		}
		if ( isset( $_POST['template_url'] ) ) {
			$this->current_template_url  = is_array( $_POST['template_url'] ) ? (array) $_POST['template_url'] : sanitize_text_field( $_POST['template_url'] );
			$this->current_template_type = sanitize_text_field( $_POST['template_type'] );
		}

		$content_slug = isset( $_POST['content'] ) ? sanitize_title( $_POST['content'] ) : '';

		$content = $this->xpro_elementor_starter_sites_setup_content_steps();

		/*check for security again*/
		if ( ! check_ajax_referer( 'xpro_elementor_starter_sites_nonce', 'wpnonce' ) || empty( $content_slug ) || ! isset( $content[ $content_slug ] ) ) {
			wp_send_json_error(
				array(
					'error'   => 1,
					'message' => esc_html__( 'No content Found', 'xpro-elementor-addons' ),
				)
			);
		}

		$json         = false;
		$this_content = $content[ $content_slug ];

		if ( isset( $_POST['proceed'] ) ) {

			/*install the content*/
			$this->log( esc_html__( 'Starting import for ', 'xpro-elementor-addons' ) . $content_slug );

			/*init delayed posts from transient.*/
			$this->delay_posts = get_transient( 'delayed_posts' );
			if ( ! is_array( $this->delay_posts ) ) {
				$this->delay_posts = array();
			}

			if ( ! empty( $this_content['install_callback'] ) ) {
				/**
				 * install_callback includes following functions
				 * import_content_post_type_data
				 * import_content_widgets_data
				 * import_menu_and_options
				 * */
				if ( $result = call_user_func( $this_content['install_callback'] ) ) {

					$this->log( esc_html__( 'Finish writing ', 'xpro-elementor-addons' ) . count( $this->delay_posts, COUNT_RECURSIVE ) . esc_html__( ' delayed posts to transient ', 'xpro-elementor-addons' ) );
					set_transient( 'delayed_posts', $this->delay_posts, 60 * 60 * 24 );

					/*
					if there is retry, retry it
					or finish it*/
					if ( is_array( $result ) && isset( $result['retry'] ) ) {
						/*we split the stuff up again.*/
						$json = array(
							'url'           => admin_url( 'admin-ajax.php' ),
							'action'        => 'import_content',
							'proceed'       => 'true',
							'retry'         => time(),
							'retry_count'   => $result['retry_count'],
							'content'       => $content_slug,
							'_wpnonce'      => wp_create_nonce( 'xpro_elementor_starter_sites_nonce' ),
							'message'       => $this_content['installing'],
							'logs'          => $this->logs,
							'errors'        => $this->errors,
							'template_url'  => $this->current_template_url,
							'template_type' => $this->current_template_type,

						);
					} else {
						$json = array(
							'done'    => 1,
							'message' => $this_content['success'],
							'debug'   => $result,
							'logs'    => $this->logs,
							'errors'  => $this->errors,
						);
					}
				}
			}
		} else {

			$json = array(
				'url'           => admin_url( 'admin-ajax.php' ),
				'action'        => 'import_content',
				'proceed'       => 'true',
				'content'       => $content_slug,
				'_wpnonce'      => wp_create_nonce( 'xpro_elementor_starter_sites_nonce' ),
				'message'       => $this_content['installing'],
				'logs'          => $this->logs,
				'errors'        => $this->errors,
				'template_url'  => $this->current_template_url,
				'template_type' => $this->current_template_type,
			);
		}

		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); /*used for checking if duplicates happen, move to next plugin*/
			wp_send_json( $json );
		} else {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Error', 'xpro-elementor-addons' ),
					'logs'    => $this->logs,
					'errors'  => $this->errors,
				)
			);
		}
		exit;
	}

	/*
	set and get transient imported_term_ids
	return mix*/

	public function log( $message ) {
		$this->logs[] = $message;
	}

	/*
	set and get imported_post_ids
	return mix*/

	public function strlen_diff( $a, $b ) {
		return strlen( $b ) - strlen( $a );
	}

	/*
	set and get post_orphans/post parent
	if parent is not already imported the child will be orphan
	return mix*/

	public function import_menu_and_options() {

		$this->current_request = $this->current_request + 1;

		/*final wrap up of delayed posts.*/
		$this->process_delayed_posts( true );

		// Assign the static front page and the blog page.
		$front_page = xpro_get_page_by_title( 'Home' );
		$blog_page  = xpro_get_page_by_title( 'Blog' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page->ID );
		update_option( 'page_for_posts', $blog_page->ID );

		/*it includes options and menu data*/
		$theme_options = $this->get_theme_options_json();

		xpro_elementor_starter_sites_elementor()->process_elementor_posts();

		$this->update_elementor_post_images_meta();

		/*options data*/
		$custom_options = $theme_options['options'] ? $theme_options['options'] : array();

		/*menu data*/
		$menu_ids = $theme_options['menu'] ? $theme_options['menu'] : array();

		/*we also want to update the widget area manager options.*/
		if ( ! empty( $custom_options ) && is_array( $custom_options ) ) {
			foreach ( $custom_options as $option => $value ) {
				/*replace old entries with updated imported entries.*/
				$value = $this->replace_old_id_to_new( $value, $option );
				/*we have to update widget page numbers with imported page numbers.*/
				if (
					preg_match( '#(wam__position_)(\d+)_#', $option, $matches ) ||
					preg_match( '#(wam__area_)(\d+)_#', $option, $matches )
				) {
					$new_page_id = $this->imported_post_id( $matches[2] );
					if ( $new_page_id ) {
						// we have a new page id for this one. import the new setting value.
						$option = str_replace( $matches[1] . $matches[2] . '_', $matches[1] . $new_page_id . '_', $option );
					}
				} elseif ( $value && ! empty( $value['custom_logo'] ) ) {
					$new_logo_id = $this->imported_post_id( $value['custom_logo'] );
					if ( $new_logo_id ) {
						$value['custom_logo'] = $new_logo_id;
					}
				}
				if(get_option($option) && !empty($value)){
					xpro_elementor_starter_sites_update_option( $option, $value );
				}
			}
		}

		/* Options Menu Start*/
		$save = array();
		if ( ! empty( $menu_ids ) && is_array( $menu_ids ) ) {
			foreach ( $menu_ids as $menu_id => $term_id ) {
				$new_term_id = $this->imported_term_id( $term_id );
				if ( $new_term_id ) {
					$save[ $menu_id ] = $new_term_id;
				}
			}
			if ( ! empty( $save ) && $save ) {
				set_theme_mod( 'nav_menu_locations', array_map( 'absint', $save ) );
			}
		}

		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		xpro_elementor_starter_sites_update_option( 'rewrite_rules', false );
		$wp_rewrite->flush_rules( true );

		wp_send_json_success( true );

	}

	public function import_elementor_site_settings() {

		$site_settings = $this->get_theme_settings_json();

		if ( ! empty( $site_settings['settings'] ) ) {
			$kit = Plugin::$instance->kits_manager->get_active_kit();

			if ( ! $kit->get_id() ) {
				Plugin::$instance->kits_manager->create_new_kit( 'Default Kit', $site_settings['settings'], true );
			}
		} else {
			Plugin::$instance->kits_manager->create_default();
		}

		return true;

	}

	public function update_elementor_post_images_meta() {

		$attachment_data = get_option( 'xpro_starter_sites_attachment_data_temp' );

		$args            = array(
			'post_type'      => 'any',
			'posts_per_page' => '-1',
			'meta_key'       => '_elementor_version',
		);

		$elementor_pages = new WP_Query( $args );

		// Check that we have query results.
		if ( $elementor_pages->have_posts() ) {

			// Start looping over the query results.
			while ( $elementor_pages->have_posts() ) {

				$elementor_pages->the_post();

				// Elementor Data
				$data = get_post_meta( get_the_ID(), '_elementor_data', true );
				if ( ! empty( $data ) ) {
					foreach ( $attachment_data as $attachment ) {
						$data = $this->update_image_id_and_url( $data, $attachment['remote_URL'], $attachment['current_URL'] );
					}
					update_metadata( 'post', get_the_ID(), '_elementor_data', $data );
				}

				// Elementor Page Settings
				$page_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
				if ( ! empty( $page_settings ) ) {
					foreach ( $attachment_data as $attachment ) {
						$page_settings = $this->update_image_id_and_url( $page_settings, $attachment['remote_URL'], $attachment['current_URL'] );
					}
					update_metadata( 'post', get_the_ID(), '_elementor_page_settings', $page_settings );
				}

			}
		}

		delete_option( 'xpro_starter_sites_attachment_data_temp' );

		// Clear Elementor Cache
		Plugin::$instance->files_manager->clear_cache();

	}

	public function update_image_id_and_url( $array, $find, $replace ) {
		if ( is_array( $array ) ) {
			foreach ( $array as $Key => $Val ) {
				if ( is_array($array[$Key]) && ($Key !== 'value' && $Key !== 'image') ) {
					$array[ $Key ] = $this->update_image_id_and_url( $array[ $Key ], $find, $replace );
				} else {
					if ( ($Key === 'value' || $Key === 'image') && is_array( $Val ) && isset($array[ $Key ]['url']) && $array[ $Key ]['url'] === $find  ) {
						$array[ $Key ]['id']  = attachment_url_to_postid( $replace );
						$array[ $Key ]['url'] = $replace;
						break;
					}else if($Key === 'url' && ($Key !== 'value' && $Key !== 'image') && $Val === $find){
						$array[ $Key ] = $replace;
					}
				}
			}
		}
		return $array;
	}

	/*set delayed post for later process*/

	public function complete_screen() {

		/*check for security*/
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Sorry, you are not allowed to install demo on this site.', 'xpro-elementor-addons' ),
				)
			);
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;
		$wp_filesystem->rmdir( XPRO_ELEMENTOR_ADDONS_TEMP, true );

		set_theme_mod( 'xpro_elementor_starter_sites_setup_complete', time() );

		$this->reset_transient();

		$message  = '<div class="xs-notification-title" data-site-url="' . esc_url( home_url() ) . '">';
		$message .= '<p>' . esc_html__( 'Your Website is Ready!', 'xpro-elementor-addons' ) . '</p>';
		$message .= '<p>' . sprintf( esc_html__( 'Congratulations! All Data is imported successfully. From %1$s WordPress dashboard%2$s you can make changes and modify any of the default content to suit your needs.', 'xpro-elementor-addons' ), '<a href="' . esc_url( admin_url() ) . '">', '</a>' ) . '</p>';
		$message .= '</div>';

		apply_filters( 'xpro_elementor_starter_sites_complete_message', $message );

		do_action( 'xpro_elementor_starter_sites_before_complete_screen' );
		echo $message;
		do_action( 'xpro_elementor_starter_sites_after_complete_screen' );
		exit;
	}

	public function get_theme_status( $theme_slug ) {

		if ( ! file_exists( ABSPATH . 'wp-content/themes/' . $theme_slug ) ) {
			return 'not_installed';
		} elseif ( get_template() == $theme_slug ) {
			return 'active';
		} else {
			return 'inactive';
		}

	}

	public function import_theme( $theme_slug ) {

		$status = array(
			'install' => 'theme',
			'slug'    => $theme_slug,
		);

		if ( ! current_user_can( 'install_themes' ) ) {
			wp_send_json_error(
				array( 'message' => esc_html__( 'Sorry, you are not allowed to install theme on this site.', 'xpro-elementor-addons' ), ) );
			wp_die();
		}

		$api = themes_api(
			'theme_information',
			array(
				'slug'   => $theme_slug,
				'fields' => array( 'sections' => false ),
			)
		);

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( $api->get_error_message() );
			wp_die();
		}

		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Theme_Upgrader( $skin );

		$upgrader->install( $api->download_link );

		$status['themeName'] = wp_get_theme( $theme_slug )->get( 'Name' );

		if ( current_user_can( 'switch_themes' ) ) {
			if ( is_multisite() ) {
				$status['activateUrl'] = add_query_arg(
					array(
						'action'   => 'enable',
						'_wpnonce' => wp_create_nonce( 'enable-theme_' . $theme_slug ),
						'theme'    => $theme_slug,
					),
					network_admin_url( 'themes.php' )
				);
			} else {
				$status['activateUrl'] = add_query_arg(
					array(
						'action'     => 'activate',
						'_wpnonce'   => wp_create_nonce( 'switch-theme_' . $theme_slug ),
						'stylesheet' => $theme_slug,
					),
					admin_url( 'themes.php' )
				);
			}
		}

		if ( ! is_multisite() && current_user_can( 'edit_theme_options' ) && current_user_can( 'customize' ) ) {
			$status['customizeUrl'] = add_query_arg(
				array(
					'return' => rawurlencode( network_admin_url( 'theme-install.php', 'relative' ) ),
				),
				wp_customize_url( $theme_slug )
			);
		}

		if ( current_user_can( 'switch_themes' ) ) {
			switch_theme( $theme_slug );
		}

		wp_send_json_success( true );
	}
	/*
	Important Function
	Import single Post/Content
	*/

	function install_plugin() {

		/*check for security*/
		if ( ! current_user_can( 'install_plugins' ) ) {
			$status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'xpro-elementor-addons' );
			wp_send_json_error( $status );
		}

		//Installing Xpro Theme
		$theme_slug = 'xpro';

		$status = $this->get_theme_status( $theme_slug );

		if ( 'not_installed' == $status ) {

			$this->import_theme( $theme_slug );

		} elseif ( 'inactive' == $status ) {

			if ( current_user_can( 'switch_themes' ) ) {
				switch_theme( $theme_slug );
			}
		}

		if ( empty( $_POST['plugin'] ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error(
				array(
					'slug'         => '',
					'errorCode'    => 'no_plugin_specified',
					'errorMessage' => __( 'No plugin specified.', 'xpro-elementor-addons' ),
				)
			);
		}

		$slug   = sanitize_key( wp_unslash( $_POST['slug'] ) );
		$plugin = plugin_basename( sanitize_text_field( wp_unslash( $_POST['plugin'] ) ) );

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		if ( is_plugin_active_for_network( $plugin ) || is_plugin_active( $plugin ) ) {
			// Plugin is activated
			wp_send_json_success();

		}

		$status = array(
			'install' => 'plugin',
			'slug'    => sanitize_key( wp_unslash( $_POST['slug'] ) )
		);

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		// Looks like a plugin is installed, but not active.
		if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
			$plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$status['plugin']     = $plugin;
			$status['pluginName'] = $plugin_data['Name'];

			if ( current_user_can( 'activate_plugin', $plugin ) && is_plugin_inactive( $plugin ) ) {
				$result = activate_plugin( $plugin );

				if ( is_wp_error( $result ) ) {
					$status['errorCode']    = $result->get_error_code();
					$status['errorMessage'] = $result->get_error_message();
					wp_send_json_error( $status );
				}

				wp_send_json_success( $status );
			}
		}

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => sanitize_key( wp_unslash( $_POST['slug'] ) ),
				'fields' => array(
					'sections' => false,
				),
			)
		);

		if ( is_wp_error( $api ) ) {
			$status['errorMessage'] = $api->get_error_message();
			wp_send_json_error( $status );
		}

		$status['pluginName'] = $api->name;

		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );
		$result   = $upgrader->install( $api->download_link );

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$status['debug'] = $skin->get_upgrade_messages();
		}

		if ( is_wp_error( $result ) ) {
			$status['errorCode']    = $result->get_error_code();
			$status['errorMessage'] = $result->get_error_message();
			wp_send_json_error( $status );
		} elseif ( is_wp_error( $skin->result ) ) {
			$status['errorCode']    = $skin->result->get_error_code();
			$status['errorMessage'] = $skin->result->get_error_message();
			wp_send_json_error( $status );
		} elseif ( $skin->get_errors()->get_error_code() ) {
			$status['errorMessage'] = $skin->get_error_messages();
			wp_send_json_error( $status );
		} elseif ( is_null( $result ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;

			$status['errorCode']    = 'unable_to_connect_to_filesystem';
			$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'xpro-elementor-addons' );

			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}

			wp_send_json_error( $status );
		}

		$install_status = install_plugin_install_status( $api );

		if ( current_user_can( 'activate_plugin', $install_status['file'] ) && is_plugin_inactive( $install_status['file'] ) ) {
			$result = activate_plugin( $install_status['file'] );

			if ( is_wp_error( $result ) ) {
				$status['errorCode']    = $result->get_error_code();
				$status['errorMessage'] = $result->get_error_message();
				wp_send_json_error( $status );
			}
		}

		wp_send_json_success( $status );
	}

	private function import_content_post_type_data() {

		$post_type = ! empty( $_POST['content'] ) ? sanitize_text_field( $_POST['content'] ) : false;

		$all_data = $this->get_main_content_json();

		if ( ! $post_type || ! isset( $all_data[ $post_type ] ) ) {
			return false;
		}

		/*Import 10 posts at a time*/
		$limit = 10 + ( isset( $_REQUEST['retry_count'] ) ? (int) $_REQUEST['retry_count'] : 0 );

		$limit = apply_filters( 'xpro_elementor_starter_sites_limit_at_time', $limit );
		$x     = 0;

		foreach ( $all_data[ $post_type ] as $post_data ) {

			$this->process_import_single_post( $post_type, $post_data );

			if ( $x ++ > $limit ) {
				return array(
					'retry'       => 1,
					'retry_count' => $limit,
				);
			}
		}

		/*processed delayed posts*/
		$this->process_delayed_posts();

		/*process child posts*/
		$this->processpost_orphans();

		return true;

	}

	/*
	* since 1.2.3
	* helper function to parse url, shortcode, post ids form provided content
	 * * currently uses on meta and post content
	 * */

	private function process_import_single_post( $post_type, $post_data, $delayed = 0 ) {

		$this->current_request = $this->current_request + 1;

		$this->log( esc_html__( 'Processing ', 'xpro-elementor-addons' ) . $post_type . ' ' . $post_data['post_id'] );
		$original_post_data = $post_data;

		/*if there is not post type return false*/
		if ( ! post_type_exists( $post_type ) ) {
			return false;
		}

		/*if it is already imported return*/
		if ( $this->imported_post_id( $post_data['post_id'] ) ) {
			return true; /*already done*/
		}

		/*set post_name id for empty post name/title*/
		if ( empty( $post_data['post_title'] ) && empty( $post_data['post_name'] ) ) {
			$post_data['post_name'] = $post_data['post_id'];
		}

		/*set post_type on $post_data*/
		$post_data['post_type'] = $post_type;

		/*post_orphans/post parent management */
		$post_parent = isset( $post_data['post_parent'] ) ? absint( $post_data['post_parent'] ) : false;
		if ( $post_parent ) {
			/*if we already know the parent, map it to the new local imported ID*/
			if ( $this->imported_post_id( $post_parent ) ) {
				$post_data['post_parent'] = $this->imported_post_id( $post_parent );
			} else {
				/*if there is not parent imported, child will be orphans*/
				$this->post_orphans( absint( $post_data['post_id'] ), $post_parent );
				$post_data['post_parent'] = 0;
			}
		}

		$foundid = 0;

		/*
		check if already exists by post_name and post_title*/
		/*don't use post_exists because it will dupe up on media with same name but different slug*/
		if ( ! empty( $post_data['post_title'] ) && ! empty( $post_data['post_name'] ) ) {
			global $wpdb;
			$sql   = "
                        SELECT ID, post_name, post_parent, post_type
                        FROM $wpdb->posts
                        WHERE post_name = %s
                        AND post_title = %s
                        AND post_type = %s
					";
			$pages = $wpdb->get_results(
				$wpdb->prepare(
					$sql,
					array(
						$post_data['post_name'],
						$post_data['post_title'],
						$post_type,
					)
				),
				OBJECT_K
			);

			foreach ( (array) $pages as $page ) {
				if ( $page->post_name == $post_data['post_name'] ) {
					$foundid = $page->ID;
				}
			}
		}

		/*
		 * todo it may not required
		 * backwards compat with old import format.*/
		if ( isset( $post_data['meta'] ) ) {
			foreach ( $post_data['meta'] as $key => $meta ) {
				if ( is_array( $meta ) && count( $meta ) == 1 ) {
					$single_meta = current( $meta );
					if ( ! is_array( $single_meta ) ) {
						$post_data['meta'][ $key ] = $single_meta;
					}
				}
			}
		}

		/*finally process*/
		switch ( $post_type ) {

			/*case attachment*/
			case 'attachment':
				/*import media via url*/
				if ( isset( $post_data['guid'] ) && ! empty( $post_data['guid'] ) ) {

					/*check if this has already been imported.*/
					$old_guid = $post_data['guid'];
					if ( $this->imported_post_id( $old_guid ) ) {
						return true; /*already done*/
					}

					// ignore post parent, we haven't imported those yet.
					// $file_data = wp_remote_get($post_data['guid']);
					$remote_url = $post_data['guid'];

					$post_data['upload_date'] = date( 'Y/m', strtotime( $post_data['post_date_gmt'] ) );

					if ( isset( $post_data['meta'] ) ) {
						foreach ( $post_data['meta'] as $key => $meta ) {
							if ( '_wp_attached_file' == $key ) {
								foreach ( (array) $meta as $meta_val ) {
									if ( preg_match( '%^[0-9]{4}/[0-9]{2}%', $meta_val, $matches ) ) {
										$post_data['upload_date'] = $matches[0];
									}
								}
							}
						}
					}

					/*upload the file*/
					$upload = $this->import_image_and_file( $remote_url, $post_data );

					/*if error on upload*/
					if ( ! is_array( $upload ) || is_wp_error( $upload ) ) {
						/*todo: error*/
						return false;
					}

					/*check file type, if file type not found return false*/
					if ( $info = wp_check_filetype( $upload['file'] ) ) {
						$post_data['post_mime_type'] = $info['type'];
					} else {
						return false;
					}

					/*set guid file url*/
					$post_data['guid'] = $upload['url'];

					/*
					 * insert attachment
					 *https://developer.wordpress.org/reference/functions/wp_insert_attachment/
					 * */
					$attach_id = wp_insert_attachment( $post_data, $upload['file'] );
					if ( $attach_id ) {

						/*update meta*/
						if ( ! empty( $post_data['meta'] ) ) {
							foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
								if ( '_wp_attached_file' != $meta_key && ! empty( $meta_val ) ) {
									update_post_meta( $attach_id, $meta_key, $meta_val );
								}
							}
						}
						/* Update metadata for an attachment.*/
						wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $upload['file'] ) );

						/*remap resized image URLs, works by stripping the extension and remapping the URL stub.*/
						if ( preg_match( '!^image/!', $info['type'] ) ) {
							$parts = pathinfo( $remote_url );
							$name  = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

							$parts_new = pathinfo( $upload['url'] );
							$name_new  = basename( $parts_new['basename'], ".{$parts_new['extension']}" );

							$this->imported_post_id( $parts['dirname'] . '/' . $name, $parts_new['dirname'] . '/' . $name_new );
						}
						$this->imported_post_id( $post_data['post_id'], $attach_id );

						$old_data = ! empty( get_option( 'xpro_starter_sites_attachment_data_temp' ) ) ? get_option( 'xpro_starter_sites_attachment_data_temp' ) : array();
						$this->remap[ $post_data['post_id'] ]['remote_URL']  = $remote_url;
						$this->remap[ $post_data['post_id'] ]['current_URL'] = $upload['url'];
						update_option( 'xpro_starter_sites_attachment_data_temp', array_merge( $old_data, $this->remap ) );

					}
				}
				break;

			/*TODO*/
			case 'elementor_library':
				if ( empty( $elementor_library_id ) ) {
					break;
				}
				if ( ! empty( $post_data['meta'] ) && is_array( $post_data['meta'] ) ) {

					/*fix for double json encoded stuff*/
					foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
						if ( is_string( $meta_val ) && strlen( $meta_val ) && '[' == $meta_val[0] ) {
							$test_json = @json_decode( $meta_val, true );
							if ( is_array( $test_json ) ) {
								$post_data['meta'][ $meta_key ] = $test_json;
							}
						}
					}

					array_walk_recursive(
						$post_data['meta'],
						array(
							xpro_elementor_starter_sites_elementor(),
							'elementor_id_import',
						)
					);
				}
				/*do further filter if you need*/
				$post_data['post_id'] = apply_filters( 'xpro_elementor_starter_sites_post_data', $post_data );
				$post_data['ID']      = $elementor_library_id;

				/*finally insert post data*/
				$post_id = wp_update_post( $post_data, true );
				if ( ! is_wp_error( $post_id ) ) {

					/*set id on imported_post_id*/
					$this->imported_post_id( $post_data['post_id'], $post_id );

					/*add/update post meta*/
					if ( ! empty( $post_data['meta'] ) ) {
						foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
							/*if the post has a featured image, take note of this in case of remap*/
							if ( '_thumbnail_id' == $meta_key ) {
								/*find this inserted id and use that instead.*/
								$inserted_id = $this->imported_post_id( intval( $meta_val ) );
								if ( $inserted_id ) {
									$meta_val = $inserted_id;
								}
							}
							/*update meta*/
							update_post_meta( $post_id, $meta_key, $meta_val );
						}
					}
				}
				//not needed
				update_option( 'elementor_active_kit', $post_id );
				if (
					defined( 'ELEMENTOR_VERSION' )
					||
					defined( 'ELEMENTOR_PRO_VERSION' )
				) {
					Plugin::$instance->files_manager->clear_cache();
				}
				break;

			default:
				/*Process Post Meta*/
				if ( ! empty( $post_data['meta'] ) && is_array( $post_data['meta'] ) ) {

					/*fix for double json encoded stuff*/
					foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
						if ( is_string( $meta_val ) && strlen( $meta_val ) && '[' == $meta_val[0] ) {
							$test_json = @json_decode( $meta_val, true );
							if ( is_array( $test_json ) ) {
								$post_data['meta'][ $meta_key ] = $test_json;
							}
						}
					}

					/*
					replace menu data
					work out what we're replacing. a tax, page, term etc..*/

					if ( isset( $post_data['meta']['_menu_item_menu_item_parent'] ) && 0 != $post_data['meta']['_menu_item_menu_item_parent'] ) {
						$new_parent_id = $this->imported_post_id( $post_data['meta']['_menu_item_menu_item_parent'] );
						if ( ! $new_parent_id ) {
							if ( $delayed ) {
								/*already delayed, unable to find this meta value, skip inserting it*/
								$this->error( esc_html__( 'Unable to find replacement. Continue anyway.... content will most likely break..', 'xpro-elementor-addons' ) );
							} else {
								/*not found , delay it*/
								$this->error( esc_html__( 'Unable to find replacement. Delaying....', 'xpro-elementor-addons' ) );
								$this->delay_post_process( $post_type, $original_post_data );

								return false;
							}
						}
						$post_data['meta']['_menu_item_menu_item_parent'] = $new_parent_id;
					}

					/*if _menu_item_type*/
					if ( isset( $post_data['meta']['_menu_item_type'] ) ) {

						switch ( $post_data['meta']['_menu_item_type'] ) {
							case 'post_type':
								if ( ! empty( $post_data['meta']['_menu_item_object_id'] ) ) {
									$new_parent_id = $this->imported_post_id( $post_data['meta']['_menu_item_object_id'] );
									if ( ! $new_parent_id ) {
										if ( $delayed ) {
											/*already delayed, unable to find this meta value, skip inserting it*/
											$this->error( esc_html__( 'Unable to find replacement. Continue anyway.... content will most likely break..', 'xpro-elementor-addons' ) );
										} else {
											/*not found , delay it*/
											$this->error( esc_html__( 'Unable to find replacement. Delaying....', 'xpro-elementor-addons' ) );
											$this->delay_post_process( $post_type, $original_post_data );

											return false;
										}
									}
									$post_data['meta']['_menu_item_object_id'] = $new_parent_id;
								}
								break;

							case 'taxonomy':
								if ( ! empty( $post_data['meta']['_menu_item_object_id'] ) ) {
									$new_parent_id = $this->imported_term_id( $post_data['meta']['_menu_item_object_id'] );
									if ( ! $new_parent_id ) {
										if ( $delayed ) {
											/*already delayed, unable to find this meta value, skip inserting it*/
											$this->error( esc_html__( 'Unable to find replacement. Continue anyway.... content will most likely break..', 'xpro-elementor-addons' ) );
										} else {
											/*not found , delay it*/
											$this->error( esc_html__( 'Unable to find replacement. Delaying....', 'xpro-elementor-addons' ) );
											$this->delay_post_process( $post_type, $original_post_data );

											return false;
										}
									}
									$post_data['meta']['_menu_item_object_id'] = $new_parent_id;
								}
								break;
						}
					}
				}

				/*
				post content parser
				for shortcode post id replacement*/
				$post_data['post_content'] = $this->parse_shortcode_meta_content( $post_data['post_content'] );

				$replace_tax_id_keys = array(
					'taxonomies',
				);
				foreach ( $replace_tax_id_keys as $replace_key ) {
					if ( preg_match_all( '# ' . $replace_key . '="(\d+)"#', $post_data['post_content'], $matches ) ) {
						foreach ( $matches[0] as $match_id => $string ) {
							$new_id = $this->imported_term_id( $matches[1][ $match_id ] );
							if ( $new_id ) {
								$post_data['post_content'] = str_replace( $string, ' ' . $replace_key . '="' . $new_id . '"', $post_data['post_content'] );
							} else {
								$this->error( esc_html__( 'Unable to find TAXONOMY replacement for ', 'xpro-elementor-addons' ) . $replace_key . '="' . $matches[1][ $match_id ] . esc_html__( 'in content.', 'xpro-elementor-addons' ) );
								if ( $delayed ) {
									/*already delayed, unable to find this meta value, skip inserting it*/
									$this->error( esc_html__( 'Unable to find replacement. Continue anyway.... content will most likely break..', 'xpro-elementor-addons' ) );
								} else {
									/*not found , delay it*/
									$this->delay_post_process( $post_type, $original_post_data );

									return false;
								}
							}
						}
					}
				}

				/*do further filter if you need*/
				$post_data = apply_filters( 'xpro_elementor_starter_sites_post_data', $post_data );

				/*finally insert post data*/

				if ( $foundid > 0 ) {
					$post_data['ID'] = $foundid;
					/*finally insert post data*/
					$post_id = wp_update_post( $post_data, true );
				} else {
					$post_id = wp_insert_post( $post_data, true );
				}
				if ( ! is_wp_error( $post_id ) ) {

					/*set id on imported_post_id*/
					$this->imported_post_id( $post_data['post_id'], $post_id );

					/*add/update post meta*/
					if ( ! empty( $post_data['meta'] ) ) {
						foreach ( $post_data['meta'] as $meta_key => $meta_val ) {
							/*if the post has a featured image, take note of this in case of remap*/
							if ( '_thumbnail_id' == $meta_key ) {
								/*find this inserted id and use that instead.*/
								$inserted_id = $this->imported_post_id( intval( $meta_val ) );
								if ( $inserted_id ) {
									$meta_val = $inserted_id;
								}
							} elseif ( '_elementor_data' == $meta_key ) {
								xpro_elementor_starter_sites_elementor()->elementor_data_posts( $post_id, $meta_val );
							}
							/*update meta*/
							update_post_meta( $post_id, $meta_key, $meta_val );
						}
					}

					if ( ! empty( $post_data['terms'] ) ) {
						$terms_to_set = array();
						foreach ( $post_data['terms'] as $term_slug => $terms ) {
							foreach ( $terms as $term ) {
								$taxonomy = $term['taxonomy'];
								if ( taxonomy_exists( $taxonomy ) ) {
									$term_exists = term_exists( $term['slug'], $taxonomy );
									$term_id     = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
									if ( ! $term_id ) {
										if ( ! empty( $term['parent'] ) ) {
											/*see if we have imported this yet?*/
											$term['parent'] = $this->imported_term_id( $term['parent'] );
										}
										$term_id_tax_id = wp_insert_term( $term['name'], $taxonomy, $term );
										if ( ! is_wp_error( $term_id_tax_id ) ) {
											$term_id = $term_id_tax_id['term_id'];
										} else {
											// todo - error
											continue;
										}
									}
									/*set term_id on imported_term_id*/
									$this->imported_term_id( $term['term_id'], $term_id );

									/*add the term meta.*/
									if ( $term_id && ! empty( $term['meta'] ) && is_array( $term['meta'] ) ) {

										$replace_post_ids = apply_filters(
											'xpro_elementor_starter_sites_replace_post_ids',
											array(
												'image_id',
												'thumbnail_id',
												'attachment_id',
												'author_picture',
											)
										);
										foreach ( $term['meta'] as $meta_key => $meta_val ) {
											// we have to replace certain meta_key/meta_val
											// e.g. thumbnail id from woocommerce product categories.

											if ( in_array( $meta_key, $replace_post_ids, true ) ) {

												if ( $new_meta_val = $this->imported_post_id( $meta_val ) ) {
													/*use this new id.*/
													$meta_val = $new_meta_val;
												}
											}
											update_term_meta( $term_id, $meta_key, $meta_val );
										}
									}
									$terms_to_set[ $taxonomy ][] = intval( $term_id );
								}
							}
						}
						foreach ( $terms_to_set as $tax => $ids ) {
							wp_set_post_terms( $post_id, $ids, $tax );
						}

						if ( ( isset( $post_data['meta']['_elementor_data'] ) && ! empty( $post_data['meta']['_elementor_data'] ) ) || ( isset( $post_data['meta']['_elementor_css'] ) && ! empty( $post_data['meta']['_elementor_css'] ) ) ) {
							xpro_elementor_starter_sites_elementor()->elementor_post( $post_id );
						}

						$post               = get_post( $post_id );
						$content            = $post->post_content;
						$post->post_content = $content;
						wp_update_post( $post );
					}
				}
				break;
		}

		return true;
	}
	/*Shortcode/Meta/Post Ids fixed end*/

	/*update parent page id for child page*/

	public function imported_post_id( $original_id = false, $new_id = false ) {
		if ( is_array( $original_id ) || is_object( $original_id ) ) {
			return false;
		}
		$post_ids = get_transient( 'imported_post_ids' );
		if ( ! is_array( $post_ids ) ) {
			$post_ids = array();
		}
		if ( $new_id ) {
			if ( ! isset( $post_ids[ $original_id ] ) ) {
				$this->log( esc_html__( 'Insert old ID ', 'xpro-elementor-addons' ) . $original_id . esc_html__( ' as new ID: ', 'xpro-elementor-addons' ) . $new_id );
			} elseif ( $post_ids[ $original_id ] != $new_id ) {
				$this->error( esc_html__( 'Replacement OLD ID ', 'xpro-elementor-addons' ) . $original_id . ' overwritten by new ID: ' . $new_id );
			}
			$post_ids[ $original_id ] = $new_id;
			set_transient( 'imported_post_ids', $post_ids, 60 * 60 * 24 );
		} elseif ( $original_id && isset( $post_ids[ $original_id ] ) ) {
			return $post_ids[ $original_id ];
		} elseif ( false == $original_id ) {
			return $post_ids;
		}

		return false;
	}

	/*
	Process delayed post
	*/

	public function error( $message ) {
		$this->logs[] = esc_html__( 'ERROR!!!! ', 'xpro-elementor-addons' ) . $message;
	}

	/**Get file from url , download it and add to local*/

	private function post_orphans( $original_id = false, $missing_parent_id = false ) {
		$post_ids = get_transient( 'post_orphans' );
		if ( ! is_array( $post_ids ) ) {
			$post_ids = array();
		}
		if ( $missing_parent_id ) {
			$post_ids[ $original_id ] = $missing_parent_id;
			set_transient( 'post_orphans', $post_ids, 60 * 60 * 24 );
		} elseif ( $original_id && isset( $post_ids[ $original_id ] ) ) {
			return $post_ids[ $original_id ];
		} elseif ( false == $original_id ) {
			return $post_ids;
		}

		return false;
	}

	/**
	Replace necessary ID by Local imported ID
	*/

	private function import_image_and_file( $url, $post ) {

		/*extract the file name and extension from the url*/
		$file_name  = basename( $url );
		$local_file = XPRO_ELEMENTOR_ADDONS_TEMP_UPLOADS . $file_name;
		$upload     = false;

		/**
		if file is already on local, return file information
		It means media is on local, while exporting media*/
		if ( is_file( $local_file ) && filesize( $local_file ) > 0 ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
			global $wp_filesystem;
			$file_data = $wp_filesystem->get_contents( $local_file );
			$upload    = wp_upload_bits( $file_name, 0, $file_data, $post['upload_date'] );
			if ( $upload['error'] ) {
				return new WP_Error( 'upload_dir_error', $upload['error'] );
			}
		}

		/*if there is no file on local or error on local file need to fetch it*/
		if ( ! $upload || $upload['error'] ) {

			/*get placeholder file in the upload dir with a unique, sanitized filename*/
			$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
			if ( $upload['error'] ) {
				return new WP_Error( 'upload_dir_error', $upload['error'] );
			}

			$max_size = (int) apply_filters( 'import_attachment_size_limit', 0 );

			/*finally fetch the file from remote*/
			$response = wp_remote_get( $url );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
				$headers = $response['headers'];
				WP_Filesystem();
				global $wp_filesystem;
				$wp_filesystem->put_contents( $upload['file'], $response['body'] );
			} else {
				/*required to download file failed.*/
				wp_delete_file( $upload['file'] );

				return new WP_Error( 'import_file_error', esc_html__( 'Remote server did not respond', 'xpro-elementor-addons' ) );
			}

			$file_size = filesize( $upload['file'] );

			/*check for size*/
			if ( isset( $headers['content-length'] ) && $file_size != $headers['content-length'] ) {
				wp_delete_file( $upload['file'] );

				return new WP_Error( 'import_file_error', esc_html__( 'Remote file is incorrect size', 'xpro-elementor-addons' ) );
			}

			/*if file size is 0*/
			if ( 0 == $file_size ) {
				wp_delete_file( $upload['file'] );

				return new WP_Error( 'import_file_error', esc_html__( 'Zero size file downloaded', 'xpro-elementor-addons' ) );
			}

			/*if file is too large*/
			if ( ! empty( $max_size ) && $file_size > $max_size ) {
				wp_delete_file( $upload['file'] );

				return new WP_Error( 'import_file_error', sprintf( esc_html__( 'Remote file is too large, limit is %s', 'xpro-elementor-addons' ), size_format( $max_size ) ) );
			}
		}

		/*keep track of the old and new urls so we can substitute them later*/
		$this->imported_post_id( $url, $upload['url'] );
		//      $this->log( esc_html__( 'Remote URL ', 'xpro-elementor-addons' ) . $url . esc_html__( ' New URL: ', 'xpro-elementor-addons' ) . $upload['url'] );
		$this->imported_post_id( $post['guid'], $upload['url'] );

		/*keep track of the destination if the remote url is redirected somewhere else*/
		if ( isset( $headers['x-final-location'] ) && $headers['x-final-location'] != $url ) {
			$this->imported_post_id( $headers['x-final-location'], $upload['url'] );
		}

		return $upload;
	}

	/**
	 * Callback function to importing widgets data
	 * all widgets data is imported from here
	 * return mix
	 * */

	private function delay_post_process( $post_type, $post_data ) {
		if ( ! isset( $this->delay_posts[ $post_type ] ) ) {
			$this->delay_posts[ $post_type ] = array();
		}
		$this->delay_posts[ $post_type ][ $post_data['post_id'] ] = $post_data;
	}

	/**check if string is json*/

	public function imported_term_id( $original_term_id, $new_term_id = false ) {
		$terms = get_transient( 'imported_term_ids' );
		if ( ! is_array( $terms ) ) {
			$terms = array();
		}
		if ( $new_term_id ) {
			if ( ! isset( $terms[ $original_term_id ] ) ) {
				$this->log( esc_html__( 'Insert old TERM ID ', 'xpro-elementor-addons' ) . $original_term_id . esc_html__( ' as new TERM ID: ', 'xpro-elementor-addons' ) . $new_term_id );
			} elseif ( $terms[ $original_term_id ] != $new_term_id ) {
				$this->error( 'Replacement OLD TERM ID ' . $original_term_id . ' overwritten by new TERM ID: ' . $new_term_id );
			}
			$terms[ $original_term_id ] = $new_term_id;
			set_transient( 'imported_term_ids', $terms, 60 * 60 * 24 );
		} elseif ( $original_term_id && isset( $terms[ $original_term_id ] ) ) {
			return $terms[ $original_term_id ];
		}

		return false;
	}

	/**
	 * callback function to importing menus and options data
	 * all menus and import data is imported from here
	 * return mix
	 * */

	public function parse_shortcode_meta_content( $content ) {
		/*we have to format the post content. rewriting images and gallery stuff*/
		$replace = $this->imported_post_id();

		/*filters urls for replace*/
		$urls_replace = array();
		foreach ( $replace as $key => $val ) {
			if ( $key && $val && ! is_numeric( $key ) && ! is_numeric( $val ) ) {
				$urls_replace[ $key ] = $val;
			}
		}
		/*replace image/file urls*/
		if ( $urls_replace ) {
			uksort( $urls_replace, array( &$this, 'strlen_diff' ) );
			foreach ( $urls_replace as $from_url => $to_url ) {
				$content = str_replace( $from_url, $to_url, $content );
			}
		}

		/*gallery fixed*/
		if ( preg_match_all( '#\[gallery[^\]]*\]#', $content, $matches ) ) {
			foreach ( $matches[0] as $match_id => $string ) {
				if ( preg_match( '#ids="([^"]+)"#', $string, $ids_matches ) ) {
					$ids = explode( ',', $ids_matches[1] );
					foreach ( $ids as $key => $val ) {
						$new_id = $val ? $this->imported_post_id( $val ) : false;
						if ( ! $new_id ) {
							unset( $ids[ $key ] );
						} else {
							$ids[ $key ] = $new_id;
						}
					}
					$new_ids = implode( ',', $ids );
					$content = str_replace( $ids_matches[0], 'ids="' . $new_ids . '"', $content );
				}
			}
		}

		/*contact form 7 id fixes.*/
		if ( preg_match_all( '#\[contact-form-7[^\]]*\]#', $content, $matches ) ) {
			foreach ( $matches[0] as $match_id => $string ) {
				if ( preg_match( '#id="(\d+)"#', $string, $id_match ) ) {
					$new_id = $this->imported_post_id( $id_match[1] );
					if ( $new_id ) {
						$content = str_replace( $id_match[0], 'id="' . $new_id . '"', $content );
					} else {
						/*no imported ID found. remove this entry.*/
						$content = str_replace( $matches[0], '(insert contact form here)', $content );
					}
				}
			}
		}

		return $content;
	}

	private function process_delayed_posts( $last_delay = false ) {

		$this->log( esc_html__( 'Processing ', 'xpro-elementor-addons' ) . count( $this->delay_posts, COUNT_RECURSIVE ) . esc_html__( 'delayed posts', 'xpro-elementor-addons' ) );
		for ( $x = 1; $x < 4; $x ++ ) {
			foreach ( $this->delay_posts as $delayed_post_type => $delayed_post_data_s ) {
				foreach ( $delayed_post_data_s as $delayed_post_id => $delayed_post_data ) {

					/*already processed*/
					if ( $this->imported_post_id( $delayed_post_data['post_id'] ) ) {
						$this->log( $x . esc_html__( '- Successfully processed ', 'xpro-elementor-addons' ) . $delayed_post_type . esc_html__( ' ID ', 'xpro-elementor-addons' ) . $delayed_post_data['post_id'] . esc_html__( ' previously.', 'xpro-elementor-addons' ) );

						/*already processed, remove it from delay_posts*/
						unset( $this->delay_posts[ $delayed_post_type ][ $delayed_post_id ] );
						$this->log( esc_html__( ' ( ', 'xpro-elementor-addons' ) . count( $this->delay_posts, COUNT_RECURSIVE ) . esc_html__( ' delayed posts remain ) ', 'xpro-elementor-addons' ) );
					} /*Process it*/
                    elseif ( $this->process_import_single_post( $delayed_post_type, $delayed_post_data, $last_delay ) ) {
						$this->log( $x . esc_html__( ' - Successfully found delayed replacement for ', 'xpro-elementor-addons' ) . $delayed_post_type . esc_html__( ' ID ', 'xpro-elementor-addons' ) . $delayed_post_data['post_id'] );

						/*successfully processed, remove it from delay_posts*/
						unset( $this->delay_posts[ $delayed_post_type ][ $delayed_post_id ] );
						$this->log( esc_html__( ' ( ', 'xpro-elementor-addons' ) . count( $this->delay_posts, COUNT_RECURSIVE ) . esc_html__( ' delayed posts remain ) ', 'xpro-elementor-addons' ) );
					} else {
						$this->log( $x . esc_html__( ' - Not found delayed replacement for ', 'xpro-elementor-addons' ) . $delayed_post_type . esc_html__( ' ID ', 'xpro-elementor-addons' ) . $delayed_post_data['post_id'] );
					}
				}
			}
		}
	}

	private function processpost_orphans() {

		/*get post orphans to find it parent*/
		$orphans = $this->post_orphans();
		foreach ( $orphans as $original_post_id => $original_post_parent_id ) {
			if ( $original_post_parent_id ) {
				if ( $this->imported_post_id( $original_post_id ) && $this->imported_post_id( $original_post_parent_id ) ) {
					$post_data                = array();
					$post_data['ID']          = $this->imported_post_id( $original_post_id );
					$post_data['post_parent'] = $this->imported_post_id( $original_post_parent_id );
					wp_update_post( $post_data );
					$this->post_orphans( $original_post_id, 0 ); /*ignore future*/
				}
			}
		}
	}

	private function import_content_widgets_data() {
		$this->current_request = $this->current_request + 1;

		$import_widget_data      = $this->get_widgets_json();
		$import_widget_positions = $import_widget_data['widget_positions'];
		$import_widget_options   = $import_widget_data['widget_options'];

		/* get sidebars_widgets */
		$widget_positions = get_option( 'sidebars_widgets' );
		if ( ! is_array( $widget_positions ) ) {
			$widget_positions = array();
		}

		foreach ( $import_widget_options as $widget_name => $widget_options ) {

			/*replace $widget_options elements with updated imported entries.*/
			foreach ( $widget_options as $widget_option_id => $widget_option ) {
				$widget_options[ $widget_option_id ] = $this->replace_old_id_to_new( $widget_option, $widget_option_id );
			}
			$existing_options = get_option( 'widget_' . $widget_name, array() );
			if ( ! is_array( $existing_options ) ) {
				$existing_options = array();
			}
			$new_options = $widget_options + $existing_options;

			$new_options = apply_filters( 'xpro_elementor_starter_sites_new_options', $new_options );

			xpro_elementor_starter_sites_update_option( 'widget_' . $widget_name, $new_options );
		}

		$sidebars_widgets = array_merge( $widget_positions, $import_widget_positions );
		$sidebars_widgets = apply_filters( 'xpro_elementor_starter_sites_sidebars_widgets', $sidebars_widgets, $this );
		xpro_elementor_starter_sites_update_option( 'sidebars_widgets', $sidebars_widgets );

		return true;

	}

	private function replace_old_id_to_new( $option_value, $index_key = false ) {

		/*Post IDS*/
		$replace_post_ids = apply_filters(
			'xpro_elementor_starter_sites_replace_post_ids',
			array(
				'page_id',
				'post_id',
				'image_id',
				'attachment_id',
				'selectpage',
				'page_on_front',
				'page_for_posts',
				'first_page_id',
				'second_page_id',
				'woocommerce_shop_page_id',
				'woocommerce_cart_page_id',
				'woocommerce_checkout_page_id',
				'woocommerce_pay_page_id',
				'woocommerce_thanks_page_id',
				'woocommerce_myaccount_page_id',
				'woocommerce_edit_address_page_id',
				'woocommerce_view_order_page_id',
				'woocommerce_terms_page_id',
			)
		);

		/*Terms IDS*/
		$replace_term_ids = apply_filters(
			'xpro_elementor_starter_sites_replace_term_ids',
			array(
				'cat_id',
				'nav_menu',
				'online-shop-feature-product-cat',
				'online_shop_featured_cats',
				'online_shop_wc_product_cat',
				'online_shop_wc_product_tag',
			)
		);

		/*replace terms in keys*/

		if ( is_array( $option_value ) ) {
			foreach ( $option_value as $key => $replace_old_value ) {

				if ( is_array( $replace_old_value ) && ! is_null( $replace_old_value ) ) {
					$option_value[ $key ] = $this->replace_old_id_to_new( $replace_old_value );
				} elseif ( $this->isJson( $replace_old_value ) && is_string( $replace_old_value ) && ! is_null( $replace_old_value ) ) {
					$value_array = json_decode( $replace_old_value, true );
					if ( is_array( $value_array ) ) {
						$option_value[ $key ] = wp_json_encode( $this->replace_old_id_to_new( $value_array ) );
					} else {
						if ( in_array( $key, $replace_post_ids, true ) && 0 != $key ) {
							$new_id = $this->imported_post_id( $replace_old_value );
							if ( $new_id ) {
								$option_value[ $key ] = $new_id;
							}
						} elseif ( in_array( $key, $replace_term_ids, true ) && 0 != $key ) {
							$new_id = $this->imported_term_id( $replace_old_value );
							if ( $new_id ) {
								$option_value[ $key ] = $new_id;
							}
						} else {
							$option_value[ $key ] = $replace_old_value;
						}
					}
				} else {

					if ( in_array( $key, $replace_post_ids, true ) && 0 != $key ) {

						$new_id = $this->imported_post_id( $replace_old_value );
						if ( ! $new_id ) {
							/**/
						} else {
							$option_value[ $key ] = $new_id;
						}
					} elseif ( in_array( $key, $replace_term_ids, true ) && 0 != $key ) {
						$new_id = $this->imported_term_id( $replace_old_value );
						if ( $new_id ) {
							$option_value[ $key ] = $new_id;
						}
					} else {
						$option_value[ $key ] = $replace_old_value;
					}
				}
			}
		} elseif ( is_numeric( $option_value ) && $index_key ) {

			if ( in_array( $index_key, $replace_post_ids, true ) && 0 != $index_key ) {

				$new_id = $this->imported_post_id( $option_value );
				if ( ! $new_id ) {
					/**/
				} else {
					$option_value = $new_id;
				}
			} elseif ( in_array( $index_key, $replace_term_ids, true ) && 0 != $index_key ) {
				$new_id = $this->imported_term_id( $option_value );
				if ( $new_id ) {
					$option_value = $new_id;
				}
			}
		}

		return $option_value;
	}


	/**
	 * callback function for wp_ajax_install_plugin
	 * Install plugin
	 * */

	function isJson( $string ) {
		$test_json = @json_decode( $string, true );
		if ( is_array( $test_json ) ) {
			return true;
		}

		return false;
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function xpro_elementor_starter_sites_admin() {
	return Xpro_Elementor_Starter_Sites_Admin::instance();
}
