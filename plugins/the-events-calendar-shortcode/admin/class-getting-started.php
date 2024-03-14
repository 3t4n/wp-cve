<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TECS_Getting_Started {
    const SLUG = 'the-events-calendar-shortcode-getting-started';

    public function __construct() {
        register_activation_hook( TECS_CORE_PLUGIN_FILE, [ $this, 'maybe_set_redirect' ] );

        add_action( 'plugins_loaded', [ $this, 'hooks' ] );
    }

    public function hooks() {
        if ( wp_doing_ajax() || wp_doing_cron() ) {
            return;
        }

        if ( ! current_user_can( tecs_get_capability() ) ) {
            return;
        }

        add_action( 'admin_menu', [ $this, 'register' ] );
        add_action( 'admin_head', [ $this, 'hide_menu' ] );
        add_action( 'admin_init', [ $this, 'redirect' ], 9999 );
        add_action( 'plugins_loaded', [ $this, 'maybe_hide_tec_install_notice' ], 9999 );
    }

    function is_tec_installed() {
        return class_exists( 'Tribe__Events__Main' ) and defined( 'Tribe__Events__Main::VERSION' );
    }

    function maybe_hide_tec_install_notice() {
        global $events_calendar_shortcode;
        if ( ! $this->is_tec_installed() ) {
            remove_action( 'admin_notices', [ $events_calendar_shortcode, 'show_tec_not_installed_message' ] );
        }
    }

    function maybe_set_redirect() {
        if ( isset( $_GET['activate-multi'] ) || is_network_admin() ) {
            return;
        }

        set_transient( 'tecs_activation_redirect', true, 30 );
    }

    function register() {
        add_dashboard_page(
            esc_html__( 'Welcome to The Events Calendar Shortcode & Block', 'the-events-calendar-shortcode' ),
            esc_html__( 'Welcome to The Events Calendar Shortcode & Block', 'the-events-calendar-shortcode' ),
            tecs_get_capability(),
            self::SLUG,
            [ $this, 'render_page' ]
        );
    }

    function hide_menu() {
        remove_submenu_page( 'index.php', self::SLUG );
    }

    function redirect() {
        if ( ! get_transient( 'tecs_activation_redirect' ) ) {
            return;
        }

        delete_transient( 'tecs_activation_redirect' );

        if ( get_option( 'tecs_activation_redirect', false ) ) {
            return;
        }

        if ( isset( $_GET['activate-multi'] ) || is_network_admin() ) {
            return;
        }

        if ( defined( 'TECS_VERSION' ) ) {
            return;
        }

        wp_safe_redirect( admin_url( 'index.php?page=' . self::SLUG ) );
        exit;
    }

    function render_page() {
        wp_enqueue_style( 'tecs-getting-started', plugins_url( 'static/css/admin.css', TECS_CORE_PLUGIN_FILE ), [], Events_Calendar_Shortcode::VERSION, 'all' );
        wp_enqueue_style( 'tecs-ecs-admin', plugins_url( 'static/css/ecs.css', TECS_CORE_PLUGIN_FILE ), [], Events_Calendar_Shortcode::VERSION, 'all' );
        if ( ! $this->is_tec_installed() && current_user_can( 'activate_plugins' ) ) {
        ?>
            <div class="error"><p><?php echo sprintf( esc_html( __( 'To begin using %s, please install the latest version of %s%s%s and add an event.', 'the-events-calendar-shortcode' ) ), 'The Events Calendar Shortcode', '<a target="_blank" href="' . esc_url( admin_url( 'plugin-install.php?s=the+events+calendar&tab=search&type=term' ) ) . '" title="' . esc_attr( __( 'The Events Calendar', 'tribe-events-ical-importer' ) ) . '">', 'The Events Calendar', '</a>' ) ?></p></div>
        <?php
        }

        include TECS_CORE_PLUGIN_DIR . '/templates/getting-started.php';
    }
}

new TECS_Getting_Started();