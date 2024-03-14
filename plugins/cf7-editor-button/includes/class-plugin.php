<?php
namespace Ari_Cf7_Button;

use Ari\App\Plugin as Ari_Plugin;
use Ari\Utils\Request as Request;
use Ari_Cf7_Button\Helpers\Helper as Helper;
use Ari_Cf7_Button\Helpers\Settings as Settings;

class Plugin extends Ari_Plugin {
    public function init() {
        $this->load_translations();

        if ( is_admin() ) {
            add_filter( 'plugin_action_links_' . plugin_basename( ARICF7BUTTON_EXEC_FILE ) , function( $links ) { return $this->plugin_action_links( $links ); } );

            add_action( 'admin_enqueue_scripts', function() { $this->admin_enqueue_scripts(); } );
            add_action( 'admin_menu', function() { $this->admin_menu(); } );
            add_action( 'admin_init', function() { $this->admin_init(); } );
        }

        add_action( 'init', function() { $this->init_handler(); } );

        parent::init();
    }

    private function load_translations() {
        load_plugin_textdomain( 'contact-form-7-editor-button', false, ARICF7BUTTON_SLUG . '/languages' );
    }

    private function admin_enqueue_scripts() {
        $options = $this->options;

        wp_register_style( 'ari-cf7button-app', $options->assets_url . 'common/css/style.css', array(), $options->version );
    }

    private function admin_menu() {
        $settings_cap = 'manage_options';

        add_submenu_page(
            null,
            __( 'Btn', 'contact-form-7-editor-button' ),
            __( 'Btn', 'contact-form-7-editor-button' ),
            $settings_cap,
            'contact-form-7-editor-button',
            array( $this, 'display_settings' ),
            ''
        );
    }

    private function admin_init() {
        $no_header = (bool) Request::get_var( 'noheader' );

        if ( ! $no_header ) {
            $page = Request::get_var( 'page' );

            if ( $this->options->page_prefix && 0 === strpos( $page, $this->options->page_prefix ) ) {
                ob_start();

                add_action( 'admin_page_' . $page , function() {
                    ob_end_flush();
                }, 99 );
            }
        }
    }

    private function init_handler() {
        foreach ( array( 'post.php','post-new.php' ) as $hook ) {
            add_action( 'admin_head-' . $hook, function() { $this->register_js_settings(); } );
        }

        add_filter( 'mce_external_languages', function( $locales ) {
            return $this->register_editor_plugin_languages( $locales );
        });

        add_filter( 'mce_buttons', function( $buttons ) {
            array_push( $buttons, 'separator', 'ari_cf7_button' );

            return $buttons;
        });

        add_filter( 'mce_external_plugins', function( $plugins ) {
            $plugins['ari_cf7_button'] = $this->options->assets_url . 'button/button.js?v=' . $this->options->version;

            return $plugins;
        });
    }

    private function register_editor_plugin_languages( $locales ) {
        $locales['ari_cf7_button'] = $this->options->path . 'languages.php';

        return $locales;
    }

    private function register_js_settings() {
        $options = $this->options;

        $js_settings = array(
            'version' => $options->version,

            'ajax_url' => admin_url( 'admin-ajax.php?action=ari_cf7_button' ),
        );

        $settings = Settings::instance();
        $load_via_ajax = $settings->get_option( 'load_via_ajax' );

        if ( ! $load_via_ajax )
            $js_settings['forms'] = Helper::get_cf7_forms();

        printf(
            '<script>var ARI_CF7_BUTTON_SETTINGS = %s</script>',
            json_encode( $js_settings )
        );
    }

    protected function need_to_update() {
        $installed_version = get_option( ARICF7BUTTON_VERSION_OPTION );

        return ( $installed_version != $this->options->version );
    }

    protected function install() {
        $installer = new \Ari_Cf7_Button\Installer();

        return $installer->run();
    }

    private function plugin_action_links( $links ) {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=contact-form-7-editor-button' ) . '">' . __( 'Settings', 'contact-form-7-editor-button' ) . '</a>';
        $support_link = '<a href="http://www.ari-soft.com/Contact-Form-7-Editor-Button/" target="_blank">' . __( 'Support', 'contact-form-7-editor-button' ) . '</a>';

        $links[] = $settings_link;
        $links[] = $support_link;

        return $links;
    }
}
