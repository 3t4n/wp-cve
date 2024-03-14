<?php
/**
 * Plugin base class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor;

use Elementor\Controls_Manager;
use Elementor\Elements_Manager;

defined( 'ABSPATH' ) || die();

class Base {

	private static $instance = null;

	public $appsero = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', [ $this, 'i18n' ] );
	}

	public function i18n() {
		load_plugin_textdomain(
			'skt-addons-elementor',
			false,
			dirname( plugin_basename( SKT_ADDONS_ELEMENTOR__FILE__ ) ) . '/i18n/'
		);
	}

	public function init() {
		$this->include_files();

		// Register custom category
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

		// Register custom controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		add_action( 'init', [ $this, 'include_on_init' ] );
		
		do_action( 'sktaddonselementor_loaded' );
	}

	public function include_files() {
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'inc/functions-forms.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'inc/functions-template.php' );

		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/ajax-handler.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/template-query-manager.php' );

		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/breadcrumbs.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/icons-manager.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/widgets-manager.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/assets-manager.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/cache-manager.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/lazy-query-manager.php' );

		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/widgets-cache.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/assets-cache.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/wpml-manager.php' );

		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/smart-post-list.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'traits/post-grid-new.php' );
		
		if ( is_admin() ) {
			include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/dashboard.php' );
			include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/select2-handler.php' );
		}
	}

	public function include_on_init() {
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'inc/functions-extensions.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/extensions-manager.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'classes/credentials-manager.php' );
	}

	/**
	 * Add custom category.
	 *
	 * @param $elements_manager
	 */
	public function add_category( Elements_Manager $elements_manager ) {
		$elements_manager->add_category(
			'skt_addons_elementor_addons_category',
			[
				'title' => __( 'SKT Addons', 'skt-addons-elementor' ),
				'icon' => 'fa fa-rocket',
			]
		);
	}

	/**
	 * Register controls
	 *
	 * @param Controls_Manager $controls_Manager
	 */
	public function register_controls( Controls_Manager $controls_Manager ) {
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/foreground.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/select2.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/widget-list.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/text-stroke.php' );

		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/mask-image.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/image-selector.php' );
		include_once( SKT_ADDONS_ELEMENTOR_DIR_PATH . 'controls/lazy-select.php' );

		$Foreground = __NAMESPACE__ . '\Controls\Group_Control_Foreground';
		$controls_Manager->add_group_control( $Foreground::get_type(), new $Foreground() );

		$Select2 = __NAMESPACE__ . '\Controls\Select2';
		skt_addons_elementor()->controls_manager->register( new $Select2() );

		$Widget_List = __NAMESPACE__ . '\Controls\Widget_List';
		skt_addons_elementor()->controls_manager->register( new $Widget_List() );

		$Text_Stroke = __NAMESPACE__ . '\Controls\Group_Control_Text_Stroke';
		$controls_Manager->add_group_control( $Text_Stroke::get_type(), new $Text_Stroke() );

		$mask_image = __NAMESPACE__ . '\Controls\Group_Control_Mask_Image';
		skt_addons_elementor()->controls_manager->add_group_control( $mask_image::get_type(), new $mask_image() );

		$image_selector = __NAMESPACE__ . '\Controls\Image_Selector';
		skt_addons_elementor()->controls_manager->register( new $image_selector() );

		$lazy_select = __NAMESPACE__ . '\Controls\Lazy_Select';
		skt_addons_elementor()->controls_manager->register( new $lazy_select() );
	}
}