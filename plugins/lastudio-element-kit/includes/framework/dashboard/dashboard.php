<?php
/**
 * LaStudioKit Dashboard Module
 *
 * Version: 1.0.0
 */

namespace LaStudioKit_Dashboard;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Dashboard {

    /**
     * A reference to an instance of this class.
     *
     * @since  1.0.0
     * @access private
     * @var    object
     */
    private static $instance = null;

    /**
     * Module directory path.
     *
     * @since 1.0.0
     * @access protected
     * @var string.
     */
    protected $path;

    /**
     * Module directory URL.
     *
     * @since 1.0.0
     * @access protected
     * @var string.
     */
    protected $url;

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * [$dashboard_slug description]
     * @var string
     */
    public $dashboard_slug = 'lastudio-kit-dashboard';

    /**
     * [$module_manager description]
     * @var null
     */
    public $module_manager = null;

    /**
     * [$data_manager description]
     * @var null
     */
    public $data_manager = null;

    /**
     * [$notice_manager description]
     * @var null
     */
    public $notice_manager = null;

    /**
     * [$compat_manager description]
     * @var null
     */
    public $compat_manager = null;

    /**
     * [$subpage description]
     * @var null
     */
    private $page = null;

    /**
     * [$subpage description]
     * @var null
     */
    private $subpage = null;

    /**
     * [$default_args description]
     * @var [type]
     */
    public $default_args = array(
        'path'           => '',
        'url'            => '',
        'cx_ui_instance' => false,
        'plugin_data'    => array(
            'slug'         => false,
            'file'         => '',
            'version'      => '',
            'plugin_links' => array()
        ),
    );

    /**
     * [$args description]
     * @var array
     */
    public $args = array();

    /**
     * [$cx_ui_instance description]
     * @var boolean
     */
    public $cx_ui_instance = false;

    /**
     * [$plugin_slug description]
     * @var boolean
     */
    public $plugin_data = false;

    /**
     * [$assets_enqueued description]
     * @var boolean
     */
    protected $assets_enqueued = false;

    /**
     * [$registered_plugins description]
     * @var array
     */
    public $registered_plugins = array();

    /**
     * LaStudioKit_Dashboard constructor.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct() {

        $this->load_files();

        add_action( 'init', array( $this, 'init_managers' ), -998 );

        add_action( 'admin_menu', array( $this, 'register_page' ), -999 );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_assets' ) );
    }

    /**
     * [load_files description]
     * @return [type] [description]
     */
    public function load_files() {
        /**
         * Modules
         */
        require $this->path . 'inc/modules/manager.php';
        require $this->path . 'inc/modules/page-base.php';
        require $this->path . 'inc/modules/settings/module.php';

        require $this->path . 'inc/data-manager.php';
        require $this->path . 'inc/notice-manager.php';

        /**
         * Compatibility
         */
        require $this->path . 'inc/compatibility/manager.php';
        require $this->path . 'inc/compatibility/base-theme.php';
        require $this->path . 'inc/compatibility/themes/hello.php';
    }

    /**
     * [init_managers description]
     * @param  array  $args [description]
     * @return [type]       [description]
     */
    public function init_managers() {
        $this->module_manager  = new Modules\Manager();
        $this->notice_manager  = new Notice_Manager();
        $this->data_manager    = new Data_Manager();
        $this->compat_manager  = new Compatibility\Manager();
    }

    /**
     * [init description]
     * @return [type] [description]
     */
    public function init( $args = [] ) {

        $this->args = wp_parse_args( $args, $this->default_args );

        $this->path = ! empty( $this->args['path'] ) ? $this->args['path'] : false;
        $this->url  = ! empty( $this->args['url'] ) ? $this->args['url'] : false;

        if ( ! $this->path || ! $this->url || ! $this->args['cx_ui_instance'] ) {
            wp_die(
                'LaStudioKit_Dashboard not initialized. Module URL, Path, UI instance and plugin data should be passed into constructor',
                'LaStudioKit_Dashboard Error'
            );
        }

        $plugin_data = wp_parse_args( $this->args['plugin_data'], $this->default_args['plugin_data'] );

        $this->register_plugin( $this->args['plugin_data']['file'], $plugin_data );
    }

    /**
     * Register add/edit page
     *
     * @return void
     */
    public function register_page() {

        $branding_label = apply_filters('lastudio-kit/branding/name', esc_html__( 'LaStudio Kit', 'lastudio-kit' ));
        $branding_logo = apply_filters('lastudio-kit/branding/logo', "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0nMTYnIGhlaWdodD0nMTYnIHZpZXdCb3g9JzAgMCAyMDAgMjAwJyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnPjxnIGZpbGw9J25vbmUnIGZpbGwtcnVsZT0nZXZlbm9kZCc+PHJlY3QgZmlsbD0nI0VEMUYyNycgd2lkdGg9JzIwMCcgaGVpZ2h0PScyMDAnIHJ4PScxMDAnLz48cGF0aCBkPSdNODMuNzgzIDczTDI5IDEyNmg2NC40MmwxNS45OC0xNS4wODFINzYuNDY1TDExNiA3M0g4My43ODN6TTE1OSA3M2wtNTMgNTNoNTNWNzN6JyBmaWxsPScjRkZGJyBmaWxsLXJ1bGU9J25vbnplcm8nLz48L2c+PC9zdmc+");

        add_menu_page(
            $branding_label,
            $branding_label,
            'manage_options',
            $this->dashboard_slug . '-settings-page',
            '',
            $branding_logo,
            59
        );

//        add_submenu_page(
//            $this->dashboard_slug,
//            esc_html__( 'Dashboard', 'lastudio-kit' ),
//            esc_html__( 'Dashboard', 'lastudio-kit' ),
//            'manage_options',
//            $this->dashboard_slug
//        );

        do_action( 'lastudio-kit-dashboard/after-page-registration', $this );
    }

    /**
     * [render_dashboard description]
     * @return [type] [description]
     */
    public function render_dashboard() {
        include $this->get_view( 'common/dashboard' );
    }

    /**
     * [get_dashboard_version description]
     * @return [type] [description]
     */
    public function get_dashboard_path() {
        return $this->path;
    }

    /**
     * [get_dashboard_version description]
     * @return [type] [description]
     */
    public function get_dashboard_url() {
        return $this->url;
    }

    /**
     * [get_dashboard_version description]
     * @return [type] [description]
     */
    public function get_dashboard_version() {
        return $this->version;
    }

    /**
     * [get_registered_plugins description]
     * @return [type] [description]
     */
    public function get_registered_plugins() {
        return $this->registered_plugins;
    }

    /**
     * [get_registered_plugins description]
     * @return [type] [description]
     */
    public function register_plugin( $plugin_slug = false, $plugin_data = array() ) {

        if ( ! array_key_exists( $plugin_slug, $this->registered_plugins ) ) {
            $this->registered_plugins[ $plugin_slug ] = $plugin_data;
        }

        return false;
    }

    /**
     * Returns path to view file
     *
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    public function get_view( $path ) {
        return apply_filters( 'lastudio-kit-dashboard/get-view', $this->path . 'views/' . $path . '.php' );
    }

    /**
     * Returns wizard initial subpage
     *
     * @return string
     */
    public function get_initial_page() {
        return 'welcome-page';
    }

    /**
     * Check if dashboard page is currently displayiing
     *
     * @return boolean [description]
     */
    public function is_dashboard_page() {
        return ( ! empty( $_GET['page'] ) && false !== strpos( $_GET['page'], $this->dashboard_slug ) );
    }

    /**
     * Returns current subpage slug
     *
     * @return string
     */
    public function get_page() {

        if ( null === $this->page ) {

            $page = isset( $_GET['page'] ) && $this->dashboard_slug !== $_GET['page'] ? esc_attr( $_GET['page'] ) : $this->dashboard_slug . '-' . $this->get_initial_page();

            $this->page = str_replace( $this->dashboard_slug . '-', '', $page );
        }

        return $this->page;
    }

    /**
     * [get_subpage description]
     * @return [type] [description]
     */
    public function get_subpage() {

        if ( null === $this->subpage ) {

            $this->subpage = isset( $_GET['subpage'] ) && $this->is_dashboard_page() ? esc_attr( $_GET['subpage'] ) : false;
        }

        return $this->subpage;
    }

    /**
     * [get_admin_url description]
     * @return [type] [description]
     */
    public function get_dashboard_page_url( $page = null, $subpage = null, $args = array() ) {

        $page = $this->dashboard_slug . '-' . $page;

        $page_args = array(
            'page'    => $page,
            'subpage' => $subpage,
        );

        if ( ! empty( $args ) ) {
            $page_args = array_merge( $page_args, $args );
        }

        return add_query_arg( $page_args, admin_url( 'admin.php' ) );
    }

    /**
     * [init_ui_instance description]
     * @param  boolean $ui_callback [description]
     * @return [type]               [description]
     */
    public function init_ui_instance( $ui_callback = false ) {

        if ( $ui_callback && is_object( $ui_callback ) && 'CX_Vue_UI' === get_class( $ui_callback ) ) {
            $this->cx_ui_instance = $ui_callback;
        }

        if ( ! $ui_callback || ! is_callable( $ui_callback ) ) {
            return;
        }

        $this->cx_ui_instance = call_user_func( $ui_callback );
    }

    /**
     * [enqueue_dashboard_assets description]
     * @param  [type] $hook [description]
     * @return [type]       [description]
     */
    public function enqueue_dashboard_assets( $hook ) {

        if ( ! $this->is_dashboard_page() ) {
            return false;
        }

        if ( $this->assets_enqueued ) {
            return false;
        }

        $this->enqueue_assets();

        $this->assets_enqueued = true;
    }

    /**
     * Enqueue builder assets
     *
     * @return void
     */
    public function enqueue_assets() {

        $this->init_ui_instance( $this->args['cx_ui_instance'] );

        $this->cx_ui_instance->enqueue_assets();

        wp_enqueue_script(
            'lastudio-kit-dashboard-class-script',
            $this->url . 'assets/js/dashboard-class.js',
            array( 'cx-vue-ui' ),
            $this->version,
            true
        );

        do_action( 'lastudio-kit-dashboard/before-enqueue-assets', $this, $this->get_page() );

        $direction_suffix = is_rtl() ? '-rtl' : '';

        wp_enqueue_style(
            'lastudio-kit-dashboard-admin-css',
            $this->url . 'assets/css/dashboard-admin' . $direction_suffix . '.css',
            false,
            $this->version
        );

        wp_enqueue_script(
            'lastudio-kit-dashboard-script',
            $this->url . 'assets/js/dashboard.js',
            array( 'cx-vue-ui' ),
            $this->version,
            true
        );

        do_action( 'lastudio-kit-dashboard/after-enqueue-assets', $this, $this->get_page() );

        wp_set_script_translations( 'lastudio-kit-dashboard-script', 'lastudio-kit' );

        wp_localize_script(
            'lastudio-kit-dashboard-script',
            'LaStudioKitDashboardConfig',
            apply_filters( 'lastudio-kit-dashboard/js-page-config',
                array(
                    'pageModule'           => false,
                    'subPageModule'        => false,
                    'themeInfo'            => $this->data_manager->get_theme_info(),
                    'ajaxUrl'              => esc_url( admin_url( 'admin-ajax.php' ) ),
                    'nonce'                => wp_create_nonce( $this->dashboard_slug ),
                    'pageModuleConfig'     => $this->data_manager->get_dashboard_page_config( $this->get_page(), $this->get_subpage() ),
                    'helpCenterConfig'     => $this->data_manager->get_dashboard_config( 'helpCenter' ),
                    'avaliableBanners'     => $this->data_manager->get_dashboard_config( 'banners' ),
                    'noticeList'           => $this->notice_manager->get_registered_notices(),
                    'serviceActionOptions' => [],
                ),
                $this->get_page(),
                $this->get_subpage()
            )
        );

        add_action( 'admin_footer', array( $this, 'print_vue_templates' ), 0 );
    }

    /**
     * Print components templates
     *
     * @return void
     */
    public function print_vue_templates() {

        $templates = apply_filters(
            'lastudio-kit-dashboard/js-page-templates',
            [],
            $this->get_page(),
            $this->get_subpage()
        );

        if(empty($templates)){
            return;
        }

        foreach ( $templates as $name => $path ) {

            ob_start();
            include $path;
            $content = ob_get_clean();

            printf(
                '<script type="text/x-template" id="lastudio-kit-dashboard-%1$s">%2$s</script>',
                $name,
                $content
            );
        }
    }

    /**
     * Returns the instance.
     *
     * @since  1.0.0
     * @access public
     * @return Dashboard
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}

