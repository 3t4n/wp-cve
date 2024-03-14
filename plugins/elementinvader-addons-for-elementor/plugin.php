<?php
namespace ElementinvaderAddonsForElementor;
use ElementinvaderAddonsForElementor\Widgets;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class EliPlugin {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
		add_action( 'elementor/widgets/register', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/frontend/after_register_scripts', function()
		{
			wp_register_script( 'elementinvader_addons_for_elementor-main', plugins_url( '/assets/js/main.js', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ), [ 'jquery' ], false, true );
		} );
	}
        
	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
		$this->register_modules();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {
            
                require_once ( ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH."/core/Elementinvader_Base.php");
                
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'modules/forms/ajax-handler.php');
                
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/contact-form.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/map.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/menu.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/newsletter.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/blog-search.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/blog-grid.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/slider.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/pageloader.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/current-date.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/logo.php');
                require_once(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'widgets/blog-post-counter.php');
				do_action('eli/includes');
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliContact_Form');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliMap');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliMenu');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliNewsletter');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliBlog_Search');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliBlog_Grid');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliSlider');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliPageLoader');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliCurrentDate');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliLogo');
            $this->addtp_register('ElementinvaderAddonsForElementor\Widgets\EliBlog_Post_Counter');

			do_action('eli/register_widget');
	}
	
	public function addtp_register($class = ''){
		if(class_exists($class))
		{
			$object = new $class();
			\Elementor\Plugin::instance()->widgets_manager->register( $object );
		};
	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_modules() {
		
	}
}

add_action( 'elementor/init', function() {
	new EliPlugin();
	do_action('eli/init');
});



