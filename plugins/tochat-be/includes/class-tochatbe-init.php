<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Init {

    public function init() {

        add_action( 'init', array( $this, 'install_updates' ), 1 );

        require_once TOCHATBE_PLUGIN_PATH . 'includes/tochatbe-functions.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/tochatbe-agent-functions.php';
        
        if ( is_admin() ) {
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-init.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-agent-post.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-log-table.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-mod-meta-box.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-notice.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-dashboard-widget.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-export-csv.php';
            require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-support-widget.php';

            if ( class_exists( 'WooCommerce' ) ) {
                require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/class-tochatbe-admin-woo-order-chat.php';
            }
        }

        require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-shortcodes.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-agent.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-log.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-enqueue-scripts.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-widget.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/class-tochatbe-share-agent.php';

        require_once TOCHATBE_PLUGIN_PATH . 'includes/tochatbe-hook-functions.php';
        require_once TOCHATBE_PLUGIN_PATH . 'includes/tochatbe-hooks.php';

    }

    public function install_updates() {
        if ( TOCHATBE_PLUGIN_VER == get_option( 'tochatbe_plugin_version' ) ) {
            return;
        }

        tochatbe_plugin_installation();
    }

}

new TOCHATBE_Init;