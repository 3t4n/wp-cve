<?php
/**
 * Plugin Name:       Enter Addons
 * Plugin URI:        https://themelooks.org/demo/enteraddons
 * Description:       Ultimate Template Builder for Elementor
 * Version:           2.1.4
 * Author:            ThemeLooks
 * Author URI:        https://themelooks.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       enteraddons
 * Domain Path:       /languages
 *
 */

/**
 * Define all constant
 *
 */

// Version constant
if( !defined( 'ENTERADDONS_VERSION' ) ) {
	define( 'ENTERADDONS_VERSION', '2.1.4' );
}
// Current phpversion
if( !defined( 'ENTERADDONS_CURRENT_PHPVERSION' ) ) {
	define( 'ENTERADDONS_CURRENT_PHPVERSION', PHP_VERSION );
}

if( !defined( 'ENTERADDONS_VERSION_TYPE' ) ) {
	define( 'ENTERADDONS_VERSION_TYPE', 'LITE' );
}
// Version plugin mode constant ( PRODUCTION, DEV )
if( !defined( 'ENTERADDONS_PLUGIN_MODE' ) ) {
	define( 'ENTERADDONS_PLUGIN_MODE', 'PRODUCTION' );
}
// API source
if( !defined( 'ENTERADDONS_API_SOURCE' ) ) {
	define( 'ENTERADDONS_API_SOURCE', 'enteraddons-api' );
}
// Dir FILE
if( !defined( 'ENTERADDONS_FILE' ) ) {
	define( 'ENTERADDONS_FILE', __FILE__ );
}
// Plugin dir path constant
if( !defined( 'ENTERADDONS_DIR_PATH' ) ) {
	define( 'ENTERADDONS_DIR_PATH', trailingslashit( plugin_dir_path( ENTERADDONS_FILE ) ) );
}
// Plugin dir url constant
if( !defined( 'ENTERADDONS_DIR_URL' ) ) {
	define( 'ENTERADDONS_DIR_URL', trailingslashit( plugin_dir_url( ENTERADDONS_FILE ) ) );
}
// Admin dir path
if( !defined( 'ENTERADDONS_DIR_ADMIN' ) ) {
	define( 'ENTERADDONS_DIR_ADMIN', trailingslashit( ENTERADDONS_DIR_PATH.'admin' ) );
}
// Core dir path
if( !defined( 'ENTERADDONS_DIR_CORE' ) ) {
	define( 'ENTERADDONS_DIR_CORE', trailingslashit( ENTERADDONS_DIR_PATH.'core' ) );
}
// Inc dir path
if( !defined( 'ENTERADDONS_DIR_INC' ) ) {
	define( 'ENTERADDONS_DIR_INC', trailingslashit( ENTERADDONS_DIR_PATH.'inc' ) );
}
// Widgets dir path
if( !defined( 'ENTERADDONS_DIR_WIDGETS' ) ) {
	define( 'ENTERADDONS_DIR_WIDGETS', trailingslashit( ENTERADDONS_DIR_PATH.'widgets' ) );
}
// Assets dir url
if( !defined( 'ENTERADDONS_DIR_ASSETS_URL' ) ) {
	define( 'ENTERADDONS_DIR_ASSETS_URL', trailingslashit( ENTERADDONS_DIR_URL.'assets' ) );
}
// Admin assets dir url
if( !defined( 'ENTERADDONS_DIR_ADMIN_ASSETS' ) ) {
	define( 'ENTERADDONS_DIR_ADMIN_ASSETS', trailingslashit( ENTERADDONS_DIR_URL.'admin/assets' ) );
}
// Core dir url
if( !defined( 'ENTERADDONS_DIR_CORE_URL' ) ) {
	define( 'ENTERADDONS_DIR_CORE_URL', trailingslashit( ENTERADDONS_DIR_URL.'core' ) );
}
// Widgets dir url
if( !defined( 'ENTERADDONS_DIR_WIDGETS_URL' ) ) {
	define( 'ENTERADDONS_DIR_WIDGETS_URL', trailingslashit( ENTERADDONS_DIR_URL.'widgets' ) );
}

// Option Key
if( !defined( 'ENTERADDONS_OPTION_KEY' ) ) {
	define( 'ENTERADDONS_OPTION_KEY', 'enteraddons_widgets_activation' );
}

// Include autoloader
require_once( ENTERADDONS_DIR_PATH.'vendor/autoload.php' );

/**
 * Enteraddons Final Class
 * 
 */

final class Enteraddons {

	private static $instance = null;

	/**
     * Notice Button.
     *
     * @var array
     */

	private static $ElementorNoticeBtn;

	private function __construct() {
		//
		register_activation_hook( ENTERADDONS_FILE, [$this, 'activationTask'] );
		//
		if( self::enteraddons_check_elementor() == 'active' ) {
			add_action( 'plugins_loaded', [$this, 'initTask'] );
		}
		//
		add_action( 'admin_notices', [ __CLASS__, 'dependency_plugin_activation_notice' ] );
		//
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ __CLASS__, 'add_action_links' ] );

	}

	public static function getInstance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function activationTask() {
		Enteraddons\Classes\Activation::register_activation();
		Enteraddons\Classes\Helper::change_permissions_mode();
	}

	public function initTask() {
		$is_active = get_option(ENTERADDONS_OPTION_KEY);
		$this->includeFiles();		

		// Admin Init
		\Enteraddons\Admin\Admin::getInstance();
		// Header Footer Builder
		$extensions = !empty( $is_active['extensions'] ) ? $is_active['extensions'] : [];
		if( in_array( 'header-footer' , $extensions ) ) {
		\Enteraddons\HeaderFooterBuilder\Header_Footer_Builder::getInstance();
		}
		// Widgets Base
		Enteraddons\Core\Base\Widgets_Base::getInstance();
		// Enqueue Scripts
		new \Enteraddons\Inc\Enqueue();
		// Css Cache Manager
		\Enteraddons\Classes\Cache_Manager::init();
		// init Editor_Widgets_Assets
		new \Enteraddons\Classes\Editor_Widgets_Assets();
		// Ajax Handler
		new \Enteraddons\Classes\Ajax_Handler();
	}

	private function includeFiles() {
		// Core
		require_once( ENTERADDONS_DIR_CORE.'libs/editor/editor.php' );
		require_once( ENTERADDONS_DIR_CORE.'libs/custom-controls/custom-control.php' );
		require_once( ENTERADDONS_DIR_CORE.'libs/injecting-controls/Injecting_Controls.php' );
	}

	/**
	 * Admin Notices
	 *
	 * Dependency plugin ( Elementor ) activation notice
	 *
	 *
	 */
	public static function dependency_plugin_activation_notice() {

		$is_active = self::enteraddons_check_elementor();
		$btn = self::get_elementor_notice_button();
		if( $is_active != 'active' ):

			if( $is_active == 'notinstalled' ) {
				$getBtn = $btn['install'];
			} else {
				$getBtn = $btn['active'];
			}
		?>
		<div class="notice notice-warning is-dismissible" style="padding-bottom: 15px;background: #ffe2e6;">
			<?php
			printf( '<p style="padding: 13px 0"><strong style="font-size: 20px;">EnterAddons</strong> %1$s <strong>Elementor</strong> %2$s</p>', esc_html__( 'requires', 'enteraddons' ), esc_html__( 'to be installed and activated to work properly.', 'enteraddons' ) );

			echo '<a href="'.esc_url( $getBtn['url'] ).'" class="btn s-btn">'.esc_html__( $getBtn['label'] ).'</a>';
			?>
		</div>
		<?php
		endif;

	}

	/**
	 * add plugin settings and documentation link on the plugins page
	 * @param [array] $actions
	 */
	public static function add_action_links( $actions ) {
		$proLink = 'https://enteraddons.com/pricing/';
		$docLink = 'https://enteraddons.com/documentation/';
		$actions[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=enteraddons') ) .'">'.esc_html__( 'Settings', 'enteraddons' ).'</a>';
		$actions[] = '<a href="'. esc_url( $docLink ) .'" target="_blank">'.esc_html__( 'Documentation', 'enteraddons' ).'</a>';

		//
		if( !Enteraddons\Classes\Helper::is_pro_active() ) {
			$actions[] = '<a style="color:#E82A5C;font-weight: bold;" href="'. esc_url( $proLink ) .'" target="_blank">'.esc_html__( 'Go Pro', 'enteraddons' ).'</a>';
		}
		
		return $actions;
	}

	/**
	 * Admin Notices
	 *
	 * Sets Elementor plugin active and install url with button label  
	 *
	 * 
	 */
	private static function set_elementor_notice_button() {

		self::$ElementorNoticeBtn = [

			'install' => [
				'url'   => wp_nonce_url( 'update.php?action=install-plugin&plugin=elementor', 'install-plugin_elementor'),
				'label' => esc_html__( 'Install Elementor', 'enteraddons' )
			],
			'active' => [
				'url'   => wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php' ),
				'label' => esc_html__( 'Activate Elementor', 'enteraddons' )
			]

		];

	}

	/**
	 * Admin Notices
	 *
	 * Elementor plugin active and install url with button label 
	 *
	 * @return array
	 * 
	 */
	private static function get_elementor_notice_button() {
		self::set_elementor_notice_button();
		return self::$ElementorNoticeBtn;
	}

	/**
	 *
	 * Check elementor plugin activities
	 *
	 * @return string
	 *
	 */
	public static function enteraddons_check_elementor() {
		
		$elementorFile = WP_PLUGIN_DIR.'/elementor/elementor.php';
		$activatedPlugin = get_option('active_plugins');

		if( ! file_exists( $elementorFile ) ) {
			$has_elementor = 'notinstalled';
		} else if(  file_exists( $elementorFile ) &&  !in_array( 'elementor/elementor.php', $activatedPlugin) ) {
			$has_elementor = 'deactive';
		} else {
			$has_elementor = 'active';
		}
		return $has_elementor;
	}

}

/**
 * 
 * Init Enteraddons
 * 
 */
Enteraddons::getInstance();