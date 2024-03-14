<?php
/**
 * Dashboard manager
 *
 */
namespace MegaElementsAddonsForElementor;

defined( 'ABSPATH' ) || die();
/**
 * Widgets Dashboard Class
 * 
 * @package Mega_Elements
 */
class Dashboard {

    const PAGE_SLUG = 'mega-elements';

    const WIDGETS_NONCE = 'ewfe_save_dashboard';

    static $menu_slug = '';

    /**
     * Init class
     *
     * @return void
     */
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ], 21 );
        add_action( 'admin_menu', [ __CLASS__, 'update_menu_items' ], 99 );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_' . self::WIDGETS_NONCE, [ __CLASS__, 'save_data' ] );

        add_filter( 'plugin_action_links_' . plugin_basename( MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_FILE ), [ __CLASS__, 'add_action_links' ] );
        add_action( 'ewfe_save_dashboard_data', [ __CLASS__, 'save_widgets_data' ] );
        add_action( 'in_admin_header', [ __CLASS__, 'remove_all_notices' ], PHP_INT_MAX );
    }

    /**
     * is page check for settings page.
     *
     * @return boolean
     */
    public static function is_page() {
        return ( isset( $_GET['page'] ) && ( $_GET['page'] === self::PAGE_SLUG ) );
    }

    /**
     * Remove notices from page.
     *
     * @return void
     */
    public static function remove_all_notices() {
        if ( self::is_page() ) {
            remove_all_actions( 'admin_notices' );
            remove_all_actions( 'all_admin_notices' );
        }
    }

    /**
     * Add action links.
     *
     * @param [type] $links
     * @return void
     */
    public static function add_action_links( $links ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return $links;
        }

        $links = array_merge( [
            sprintf( '<a href="%s">%s</a>',
                meafe_get_dashboard_link(),
                esc_html__( 'Settings', 'mega-elements-addons-for-elementor' )
            )
        ], $links );
        return $links;
    }

    /**
     * Save active widgets data.
     *
     * @return void
     */
    public static function save_data() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! check_ajax_referer( self::WIDGETS_NONCE, 'nonce' ) ) {
            wp_send_json_error();
        }

        $posted_data    = ! empty( $_POST['data'] ) ? $_POST['data'] : array();
        $sanitized_data = array_map( 'sanitize_title', $posted_data );
        
        do_action( 'ewfe_save_dashboard_data', $sanitized_data );

        wp_send_json_success();
    }

    /**
     * Save Widgets data.
     *
     * @param [type] $data
     * @return void
     */
    public static function save_widgets_data( $data ) {
        $widgets = ! empty( $data ) ? $data : [];
        $inactive_widgets = array_values( array_diff( array_keys( self::get_real_widgets_map() ), $widgets ) );
        Widgets_Manager::save_inactive_widgets( $inactive_widgets );
    }

    /**
     * Enqueue Assets.
     *
     * @param [type] $hook
     * @return void
     */
    public static function enqueue_scripts( $hook ) {
        if ( self::$menu_slug !== $hook || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        wp_enqueue_style(
            'sweetalert',
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'assets/admin/dashboard/css/sweetalert.css',
            null,
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
        );

        wp_enqueue_script(
            'sweetalert',
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'assets/admin/dashboard/js/sweetalert.js',
            [ 'jquery' ],
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
            true
        );

        wp_enqueue_style(
            'meafe-dashboard',
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'assets/admin/dashboard/css/dashboard.css',
            null,
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION
        );

        wp_enqueue_script(
            'meafe-dashboard',
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_URL . 'assets/admin/dashboard/js/dashboard.js',
            [ 'jquery' ],
            MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_VERSION,
            true
        );

        wp_localize_script(
            'meafe-dashboard',
            'MegaElementsAddons',
            [
                'nonce' => wp_create_nonce( self::WIDGETS_NONCE ),
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'action' => self::WIDGETS_NONCE,
                'saveChangesLabel' => esc_html__( 'Save Changes', 'mega-elements-addons-for-elementor' ),
                'savedLabel' => esc_html__( 'Changes Saved', 'mega-elements-addons-for-elementor' ),
                'settings_success' => [
                    'title' => __('Settings Saved!', 'mega-elements-addons-for-elementor'),
                    'message' => __('Widgets display toggles settings has been saved successfully.', 'mega-elements-addons-for-elementor')
                ],
                'settings_fail' => [
                    'title' => __('Oops!', 'mega-elements-addons-for-elementor'),
                    'message' => __('Widgets display toggles settings could not be saved.', 'mega-elements-addons-for-elementor')
                ]
            ]
        );
    }

    /**
     * Get Widgets map
     *
     * @return void
     */
    private static function get_real_widgets_map() {
        $widgets_map = Widgets_Manager::get_widgets_map();
        unset( $widgets_map[ Widgets_Manager::get_base_widget_key() ] );
        return $widgets_map;
    }

    /**
     * Get Widgets.
     *
     * @return void
     */
    public static function get_widgets() {
        $widgets_map = self::get_real_widgets_map();

        uksort( $widgets_map, [ __CLASS__, 'sort_widgets' ] );
        return $widgets_map;
    }

    /**
     * Sort Widgets.
     *
     * @param [type] $k1
     * @param [type] $k2
     * @return void
     */
    public static function sort_widgets( $k1, $k2 ) {
        return strcasecmp( $k1, $k2 );
    }

    /**
     * Add menu.
     *
     * @return void
     */
    public static function add_menu() {
        self::$menu_slug = add_menu_page(
            __( 'Mega Elements - Addons for Elementor', 'mega-elements-addons-for-elementor' ),
            __( 'Mega Elements', 'mega-elements-addons-for-elementor' ),
            'manage_options',
            self::PAGE_SLUG,
            [ __CLASS__, 'render_main' ],
            \meafe_get_b64_icon(),
            58.5
        );

        $tabs = self::get_tabs();
        if ( is_array( $tabs ) ) {
            foreach ( $tabs as $key => $data ) {
                if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
                    continue;
                }

                add_submenu_page(
                    self::PAGE_SLUG,
                    sprintf( __( '%s - Mega Elements Addons for Elementor', 'mega-elements-addons-for-elementor' ), esc_html( $data['title'] ) ),
                    $data['title'],
                    'manage_options',
                    self::PAGE_SLUG . '#' . $key,
                    [ __CLASS__, 'render_main' ]
                );
            }
        }
    }

    /**
     * Update Menu items.
     *
     * @return void
     */
    public static function update_menu_items() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        global $submenu;
        $menu = $submenu[ self::PAGE_SLUG ];
        array_shift( $menu );
        $submenu[ self::PAGE_SLUG ] = $menu;
    }

    /**
     * Get Tabs.
     *
     * @return void
     */
    public static function get_tabs() {
        $tabs = [
            'mega-elements-general' => [
                'title' => esc_html__( 'General', 'mega-elements-addons-for-elementor' ),
                'renderer' => [ __CLASS__, 'render_home' ],
            ],
            'mega-elements-widgets' => [
                'title' => esc_html__( 'Widgets', 'mega-elements-addons-for-elementor' ),
                'renderer' => [ __CLASS__, 'render_widgets' ],
            ],
        ];

        return apply_filters( 'meafe_dashboard_get_tabs', $tabs );
    }

    /**
     * Load Temaplate.
     *
     * @param [type] $template
     * @return void
     */
    private static function load_template( $template ) {
        $file = MEGA_ELEMENTS_ADDONS_FOR_ELEMENTOR_PATH . 'templates/admin/dashboard-' . $template . '.php';
        if ( is_readable( $file ) ) {
            include( $file );
        }
    }

    /**
     * Render main screen.
     *
     * @return void
     */
    public static function render_main() {
        self::load_template( 'main' );
    }

    /**
     * Render Home Screen.
     *
     * @return void
     */
    public static function render_home() {
        self::load_template( 'home' );
    }

    /**
     * Render widgets screen.
     *
     * @return void
     */
    public static function render_widgets() {
        self::load_template( 'widgets' );
    }
}

// Init.
Dashboard::init();
