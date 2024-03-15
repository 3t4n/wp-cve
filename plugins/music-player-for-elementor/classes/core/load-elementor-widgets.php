<?php


/**
 * Class MPFE_Load_Elementor_Widgets
 *
 * Main MPFE_Load_Elementor_Widgets class
 * @since 1.2.0
 */
class MPFE_Load_Elementor_Widgets {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var MPFE_Load_Elementor_Widgets The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return MPFE_Load_Elementor_Widgets An instance of the class.
	 */
	public static function instance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /**
     * Registers required JS files
     * 
     * @since 1.0.0
     * @access public
    */
	public function mpfe_frontend_scripts(){
        wp_register_script(
            'mpfe-front',
            MPFE_DIR_URL . 'js/mpfe-front.js',
            array('jquery'),
            MPFE_VERSION,
            true
        );
	}

	public function mpfe_elementor_editor_scripts() {
		wp_enqueue_style(
			'mpfe-elementor-editor',
			MPFE_DIR_URL . 'css/elementor-editor.css', 
			array(), 
			MPFE_VERSION
		);
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once(MPFE_DIR_PATH . '/classes/widgets/slide-music-player-free.php');
		require_once(MPFE_DIR_PATH . '/classes/widgets/slide-compact-player.php');
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		/* Its is now safe to include Widgets files */
		$this->include_widgets_files();

		/* Register Widgets */
		\Elementor\Plugin::instance()->widgets_manager->register(new Widget_Slide_Music_Player_Free());
		\Elementor\Plugin::instance()->widgets_manager->register(new Widget_Slide_Compact_Player());
	}

	private function include_control_files() {
		require_once(MPFE_DIR_PATH . '/classes/controls/mpfe-audio-chooser.php');
	}
	  
	public function register_controls() {
		$this->include_control_files();
		
		/* Register controls - check if already registered */
		$audio_chooser_control_inst = new \MPFE_Audio_Chooser_Control();
		if (false == \Elementor\Plugin::$instance->controls_manager->get_control($audio_chooser_control_inst->get_type())) {
			\Elementor\Plugin::$instance->controls_manager->register($audio_chooser_control_inst);
		}
	}

	/**
	 * Add Elementor Widget Categories
	 *
	 * Add widget categories
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_elementor_widget_categories($elements_manager) {
		$elements_manager->add_category(
			'slide-widgets',
			[
				'title' => __('Slide Music', 'music-player-for-elementor'),
				'icon' => 'fas fa-music',
			]
		);
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register custom controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		// Register widget scripts
		add_action('elementor/frontend/after_register_scripts', array($this, 'mpfe_frontend_scripts'));

		//editor scripts
		add_action('elementor/editor/before_enqueue_scripts', array($this, 'mpfe_elementor_editor_scripts'));

		// Register widgets
		add_action('elementor/widgets/register', [ $this, 'register_widgets' ]);

		//Add categories
		add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));
	}
}

// Instantiate MPFE_Load_Elementor_Widgets Class
MPFE_Load_Elementor_Widgets::instance();