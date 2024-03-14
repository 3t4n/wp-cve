<?php
//namespace WPHR\HR_MANAGER\HR\Frontend;
//use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Accounting reimbursement plugin main class
 */
class WPHR_HR_Frontend {

    static $wphr;

	/**
	 * Version
	 *
	 * @var  string
	 */
	public $version = '1.0.1';

	/**
     * Initializes the class
     *
     * Checks for an existing instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
		
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

   	/**
     * Constructor for the class
     *
     * Sets up all the appropriate hooks and actions
     */
    public function __construct() {		
		
		/*if ( did_action( 'wphr_hrm_loaded' ) ) {
            return;
        }*/
        
        add_action( 'admin_notices', [ $this, 'notice' ] );

        // Localize our plugin
        add_action( 'init', [ $this, 'localization_setup' ] );
        
		
        // on plugin register hook
        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        // on WPHR loaded hook
		add_action( 'wphr_hrm_loaded', [ $this, 'wphr_hr_frontend_loaded' ] );
        
        do_action( 'wphr_hrm_loaded' );
        
        if(is_admin())
        {
			$this->activate();
		}
		else
		{
			WPHR\HR_MANAGER\HR\Frontend\Shortcodes::init();
		}
    }

    /**
     * Executes while Plugin Activation
     *
     * @since  1.0.0
     *
     * @return void
     */
    public static function activate() {
		
        include_once dirname(__FILE__) . '/includes/functions.php';
        include_once dirname(__FILE__) . '/includes/class-install.php';
    }

    /**
     * Initialize plugin for localization
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function localization_setup() {
		
        load_plugin_textdomain( 'wp-hr-frontend', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );
        WPHR\HR_MANAGER\HR\Frontend\Shortcodes::init();
    }

    /**
     * Executes if HR is installed
     *
     * @since  1.0.0
     *
     * @return void
     */
    public function wphr_hr_frontend_loaded() {
		
        // Define constants
        $this->define_constants();
	
        // Include required files
        $this->includes();

        // instantiate classes
        //$this->inistantiate();

        // Initialize the action hooks
        $this->init_actions();

        // Initialize the filters
        $this->init_filters();
    }

    /**
     * Define Add-on constants
     *
     * @since  1.0.0
     *
     * @return void
     */
    private function define_constants() {
        $this->define( 'WPHR_HR_FRONTEND_VERSION', $this->version );
        $this->define( 'WPHR_HR_FRONTEND_FILE', __FILE__ );
        $this->define( 'WPHR_HR_FRONTEND_PATH', dirname( WPHR_HR_FRONTEND_FILE ) );
        $this->define( 'WPHR_HR_FRONTEND_INCLUDES', WPHR_HR_FRONTEND_PATH . '/includes' );
        $this->define( 'WPHR_HR_FRONTEND_URL', plugins_url( '', WPHR_HR_FRONTEND_FILE ) );
        $this->define( 'WPHR_HR_FRONTEND_ASSETS', WPHR_HR_FRONTEND_URL . '/assets' );
        $this->define( 'WPHR_HR_FRONTEND_VIEWS', WPHR_HR_FRONTEND_PATH . '/views' );
    }

    /**
     * Define constant if not already set
     *
     * @param  string      $name
     * @param  string|bool $value
     *
     * @since  1.0.0
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include required files
     *
     * @since  1.0.0
     *
     * @return void
     */
    private function includes() {
        require_once WPHR_INCLUDES . '/admin/functions.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/functions.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/class-settings.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/class-scripts.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/class-frontend-employee-list-table.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/shortcodes/class-dashboard.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/shortcodes/class-employee-list.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/shortcodes/class-employee-profile.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/class-shortcodes.php';
        include_once WPHR_HR_FRONTEND_INCLUDES . '/class-form-handler.php';
    }

    /**
     * Initialize WordPress action hooks
     *
     * @since  1.0.0
     *
     * @return void
     */
    private function init_actions() {
		add_action( 'init', array( '\WPHR\HR_MANAGER\HR\Frontend\Shortcodes', 'init' ) );
        add_action( 'wp_footer', array( $this, 'frontend_js_templates' ) );
        new WPHR\HR_MANAGER\HRM\Form_Handler();        
    }

    /**
     * Initialize WordPress filter hooks
     *
     * @since  1.0.0
     *
     * @return void
     */
    function init_filters() {
       add_filter( 'wphr_hr_employee_tab_url', 'wphr_hr_frontend_employee_tab_url', 10, 3 );
       add_filter( 'wphr_hr_employee_list_url', 'wphr_hr_frontend_employee_list_url', 10, 2 );
    }

    /**
     * Print JS templates in footer
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function frontend_js_templates() {
        global $post;

        $emp_profile_page_id = wphr_hr_get_settings_options('emp_profile');
        $my_profile_page_id = wphr_hr_get_settings_options('my_profile');
        $has_shortcode = false;
        if( has_shortcode( $post->post_content, 'wp-hr-employee-profile' ) 
            || has_shortcode( $post->post_content, 'wp-hr-employee-list' )  ){
            $has_shortcode = true;
        }
        if ( $has_shortcode || $post->ID ===  absint( $emp_profile_page_id ) || $post->ID ===  absint( $my_profile_page_id ) ) {
			include WPHR_INCLUDES . '/admin/views/wphr-modal.php';
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/new-employee.php', 'wphr-new-employee' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/work-experience.php', 'wphr-employment-work-experience' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/education-form.php', 'wphr-employment-education' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/dependents.php', 'wphr-employment-dependent' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/employment-status.php', 'wphr-employment-status' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/compensation.php', 'wphr-employment-compensation' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/job-info.php', 'wphr-employment-jobinfo' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/performance-reviews.php', 'wphr-employment-performance-reviews' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/performance-comments.php', 'wphr-employment-performance-comments' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/performance-goals.php', 'wphr-employment-performance-goals' );
            wphr_get_js_template( WPHR_HRM_JS_TMPL . '/employee-terminate.php', 'wphr-employment-terminate' );
        }

    }

    /**
    * Initiate all classes
    *
    * @since 1.0.0
    *
    * @return void
    **/
    public function inistantiate( $void='' ) {
        if ( is_admin() && class_exists( '\WPHR\HR_MANAGER\License' ) ) {
            new \WPHR\HR_MANAGER\License( __FILE__, 'HR Frontend', $this->version, 'wphr' );
        }
    }

    /**
     * Placeholder for activation function
     *
     * @since  1.0.0
     *
     * @return  void
     */
    static function notice() {

        if ( ! class_exists( 'clsWP_HR' ) ) {
            echo '<div class="error">
                <p><strong>Error: WPHR Frontend </strong> requires<strong> <a href="https://wphrmanager.com/" target="_blank">WPHR Manager</a></strong> to be activated first</p>
            </div>';

        }
    }
}

WPHR_HR_Frontend::init();


