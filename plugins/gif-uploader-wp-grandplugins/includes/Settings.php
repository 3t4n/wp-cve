<?php
namespace GPLSCore\GPLS_PLUGIN_WGR;

/**
 * Redirects To Checkout Class.
 */
class Settings {

	/**
	 * Core Object
	 *
	 * @var object
	 */
	public $core;

	/**
	 * Plugin Info
	 *
	 * @var object
	 */
	public static $plugin_info;

	/**
	 * Settings Name.
	 *
	 * @var string
	 */
	public static $settings_name;

	/**
	 * Settings Tab Key
	 *
	 * @var string
	 */
	protected $settings_tab_key;

	/**
	 * Settings Tab name
	 *
	 * @var array
	 */
	protected $settings_tab;


	/**
	 * Current Settings Active Tab.
	 *
	 * @var string
	 */
	protected $current_active_tab;

	/**
	 * Settings Array.
	 *
	 * @var array
	 */
	public static $settings;

	/**
	 * Settings Tab Fields
	 *
	 * @var Array
	 */
	protected $fields = array();


	/**
	 * Constructor.
	 *
	 * @param object $core Core Object.
	 * @param object $plugin_info Plugin Info Object.
	 */
	public function __construct( $core, $plugin_info ) {
		$this->core             = $core;
		self::$plugin_info      = $plugin_info;
		$this->settings_tab_key = self::$plugin_info['options_page'];
		self::$settings_name    = self::$plugin_info['name'] . '-main-settings-name';
		$this->hooks();
	}

	/**
	 * Filters and Actions Hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'settings_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), PHP_INT_MAX, 1 );
		add_action( 'plugin_action_links_' . self::$plugin_info['basename'], array( $this, 'settings_link' ), 5, 1 );
	}

	/**
	 * Settings Link.
	 *
	 * @param array $links Plugin Row Links.
	 * @return array
	 */
	public function settings_link( $links ) {
		$links[] = '<a href="' . esc_url( admin_url( 'upload.php?page=' . self::$plugin_info['options_page'] ) ) . '">' . esc_html__( 'GIF Editor' ) . '</a>';
		return $links;
	}

	/**
	 * Admin assets.
	 *
	 * @return void
	 */
	public function admin_scripts( $hook ) {
		global $post, $wp_styles;
		$screen_obj = get_current_screen();
		if ( // Media -> Add New
			( is_object( $screen_obj ) && ! empty( $screen_obj->action ) && ( 'add' === $screen_obj->action ) && ! empty( $screen_obj->base ) && 'media' === $screen_obj->base )
			||
			// Media -> Edit
			( is_object( $screen_obj ) && ! empty( $screen_obj->base ) && ( 'post' === $screen_obj->base ) && ! empty( $screen_obj->post_type ) && 'attachment' === $screen_obj->post_type )
			) {
			wp_enqueue_style( self::$plugin_info['name'] . '-settings-menu-bootstrap-style', $this->core->core_assets_lib( 'bootstrap', 'css' ), array(), self::$plugin_info['version'], 'all' );
			if ( ! wp_script_is( 'jquery' ) ) {
				wp_enqueue_script( 'jquery' );
			}
			wp_enqueue_script( self::$plugin_info['name'] . '-settings-menu-bootstrap-js', $this->core->core_assets_lib( 'bootstrap.bundle', 'js' ), array( 'jquery' ), self::$plugin_info['version'], true );
		}

		if ( $this->is_settings_page() ) {
			wp_enqueue_style( self::$plugin_info['name'] . '-settings-styles', self::$plugin_info['url'] . 'assets/dist/css/admin/admin-styles.min.css', array(), self::$plugin_info['version'], 'all' );
			wp_enqueue_style( self::$plugin_info['name'] . '-settings-menu-bootstrap-style', $this->core->core_assets_lib( 'bootstrap', 'css' ), array(), self::$plugin_info['version'], 'all' );
			wp_enqueue_editor();
			wp_enqueue_media();

			wp_enqueue_script( 'plupload-handlers' );
			if ( ! wp_script_is( 'jquery-ui-core' ) ) {
				wp_enqueue_script( 'jquery-ui-core' );
			}
			if ( ! wp_script_is( 'jquery-touch-punch' ) ) {
				wp_enqueue_script( 'jquery-touch-punch' );
			}
			if ( ! wp_script_is( 'jquery-ui-draggable' ) ) {
				wp_enqueue_script( 'jquery-ui-draggable' );
			}
			if ( ! wp_script_is( 'jquery-ui-droppable' ) ) {
				wp_enqueue_script( 'jquery-ui-droppable' );
			}
			if ( ! wp_script_is( 'jquery-ui-resizable' ) ) {
				wp_enqueue_script( 'jquery-ui-resizable' );
			}
			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

			wp_enqueue_script( self::$plugin_info['name'] . '-settigns-menu-bootstrap-action', $this->core->core_assets_lib( 'bootstrap.bundle', 'js' ), array(), self::$plugin_info['version'], true );
			wp_enqueue_script( self::$plugin_info['name'] . '-settings-actions', self::$plugin_info['url'] . 'assets/dist/js/admin/settings-actions.min.js', array( 'jquery', 'wp-i18n' ), self::$plugin_info['version'], true );

		}

		wp_localize_script(
			self::$plugin_info['name'] . '-settings-actions',
			str_replace( '-', '_', self::$plugin_info['name'] . '_localize_vars' ),
			array(
				'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
				'spinner'                => admin_url( 'images/spinner.gif' ),
				'nonce'                  => wp_create_nonce( self::$plugin_info['name'] . '-ajax-nonce' ),
				'createGIFAction'        => self::$plugin_info['name'] . '-gif-create',
				'saveGIFAction'          => self::$plugin_info['name'] . '-gif-save',
				'watermarkGIFAction'     => self::$plugin_info['name'] . '-gif-watermark',
				'watermarkGIFSaveAction' => self::$plugin_info['name'] . '-gif-watermark-save',
				'tab'                    => empty( $_GET['tab'] ) ? 'images' : sanitize_text_field( wp_unslash( $_GET['tab'] ) ),
				'labels'                 => array(
					'select_images'        => esc_html__( 'Select images', 'wp-gif-editor' ),
					'select_gif_frames'    => esc_html__( 'Choose GIF Frames', 'wp-gif-editor' ),
					'select_gif'           => esc_html__( 'Select GIF', 'wp-gif-editor' ),
					'choose_gif'           => esc_html__( 'Choose GIF', 'wp-gif-editor' ),
					'select_watermark'     => esc_html__( 'Select Watermark', 'wp-gif-editor' ),
					'choose_watermark'     => esc_html__( 'Choose Watermark', 'wp-gif-editor' ),
					'big_watermark_notice' => esc_html__( 'The selected watermark is bigger than the GIF', 'wp-gif-editor' ),
					'gif_save_failed'      => esc_html__( 'failed to save the GIF!', 'wp-gif-editor' ),
					'media_modal_pro'      => '<div style="text-align:center;background:#EEE;padding:20px;"><h5 style="display:flex;margin-bottom:0px;justify-content:center;align-items:center;">' . esc_html__( 'Create GIF from images, You can use up to 4 images. Upgrade to ', 'wp-gif-editor' ) . $this->core->pro_btn( '', 'Premium', '', '', true ) . '<span style="margin-left:5px;">' . esc_html__( '  for unlimited selected images', 'wp-gif-editor' ) . '</span></h5></div>',
				),
			)
		);
	}


	/**
	 * Settings Menu Page Func.
	 *
	 * @return void
	 */
	public function settings_menu_page() {
		add_media_page(
			esc_html__( 'GIF Editor', 'wp-gif-editor' ),
			esc_html__( 'GIF Editor', 'wp-gif-editor' ),
			'upload_files',
			self::$plugin_info['options_page'],
			array( $this, 'gif_settings_page' )
		);
	}

	/**
	 * Is settings page.
	 *
	 * @return boolean
	 */
	public function is_settings_page( $tab = '' ) {
		if ( ! empty( $_GET['page'] ) && self::$plugin_info['options_page'] === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) {
			if ( ! empty( $tab ) ) {
				if ( ! empty( $_GET['tab'] ) && ( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) === $tab ) ) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * GIF Settings Page.
	 *
	 * @return void
	 */
	/**
	 * GIF Settings Page.
	 *
	 * @return void
	 */
	public function gif_settings_page() {
		$tab = empty( $_GET['tab'] ) ? 'settings' : ( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) );
		?>
		<div class="wrap">
			<h2 class="nav-tab-wrapper wp-clearfix">

				<a class="nav-tab <?php echo esc_attr( ( 'settings' === $tab ) ? 'nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url() . 'upload.php?page=' . self::$plugin_info['options_page'] . '&tab=settings' ); ?>"><?php esc_html_e( 'Settings', 'wp-gif-editor' ); ?></a>
				<a class="nav-tab <?php echo esc_attr( ( 'images' === $tab ) ? 'nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url() . 'upload.php?page=' . self::$plugin_info['options_page'] . '&tab=images' ); ?>"><?php esc_html_e( 'Create GIF', 'wp-gif-editor' ); ?></a>
				<a class="nav-tab <?php echo esc_attr( ( 'watermark' === $tab ) ? 'nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url() . 'upload.php?page=' . self::$plugin_info['options_page'] . '&tab=watermark' ); ?>"><?php esc_html_e( 'Watermark', 'wp-gif-editor' ); ?></a>
			</h2>
			<div class="container-fluid gif-creator-wrapper w-100 p-5">
				<div class="row">
					<?php $this->settings_template(); ?>
				</div>
			</div>
			<div class="position-fixed" style="top: 20px; z-index: 1000; left: 50%; transform: translateX(-50%);">
				<div class="<?php echo esc_attr( self::$plugin_info['classes_prefix'] . '-gif-notices' ); ?> toast hide alert" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" >
					<div class="toast-header justify-content-end">
						<button type="button" class="ms-2 mb-1 btn-close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="toast-body">
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Settings Template Selector.
	 *
	 * @return void
	 */
	public function settings_template() {
		$template_name = 'gif-settings.php';
		if ( ! empty( $_GET['tab'] ) ) {
			$tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
			switch ( $tab ) {
				case 'settings':
					$template_name = 'gif-settings.php';
					break;
				case 'images':
					$template_name = 'gif-create-images.php';
					break;
				case 'watermark':
					$template_name = 'gif-watermark.php';
					break;
			}
		}
		$plugin_info  = self::$plugin_info;
		$core         = $this->core;
		$settings_obj = $this;
		require_once self::$plugin_info['path'] . 'templates/' . $template_name;
	}

}
