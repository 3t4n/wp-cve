<?php

namespace XCurrency\App\Providers\Admin;

use XCurrency\WpMVC\Contracts\Provider;
use XCurrency\WpMVC\View\View;

class MenuServiceProvider implements Provider {
    public function boot() {
        add_action( 'admin_menu', [ $this, 'action_admin_menu' ] );
        add_action( 'admin_head', [ $this, 'action_admin_head' ] );
        add_filter( 'plugin_action_links_x-currency/x-currency.php', [$this, 'plugin_action_links'] );
    }

    /**
     * Fires in head section for all admin pages.
     */
    public function action_admin_head() : void {
        ?>
        <style>
            #toplevel_page_x-currency .wp-menu-image::before {
                content: "\e900";
                font-family: 'x-currency' !important;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        </style>
        <?php
    }

    /**
     * Fires before the administration menu loads in the admin.
     */
    public function action_admin_menu() : void {
        $page_url = admin_url( 'admin.php?page=x-currency' );
        add_menu_page( esc_html__( 'X-Currency', 'x-currency' ), esc_html__( 'X-Currency', 'x-currency' ), 'manage_options', 'x-currency', function () { }, '', '58.7' );
        add_submenu_page( 'x-currency', esc_html__( 'Overview', 'x-currency' ), esc_html__( 'Overview', 'x-currency' ), 'manage_options', 'x-currency', [$this, 'content'] );
        add_submenu_page( 'x-currency', esc_html__( 'Currencies', 'x-currency' ), esc_html__( 'Currencies', 'x-currency' ), 'manage_options', esc_url( $page_url . '#/currencies' ) );
        add_submenu_page( 'x-currency', esc_html__( 'Switchers', 'x-currency' ), esc_html__( 'Switchers', 'x-currency' ), 'manage_options', esc_url( $page_url . '#/switchers' ) );
        add_submenu_page( 'x-currency', esc_html__( 'Global Settings', 'x-currency' ), esc_html__( 'Global Settings', 'x-currency' ), 'manage_options', esc_url( $page_url . '#/global-settings' ) );
        add_submenu_page( 'x-currency', esc_html__( 'Contact / Support', 'x-currency' ), esc_html__( 'Contact / Support', 'x-currency' ), 'manage_options', esc_url( $page_url . '#/contact-support' ) );
    }

    public function plugin_action_links( $links ) {
        $custom_links = [
            '<a href="https://doatkolom.com/contact" target="_blank" title="' . esc_attr__( 'Create support ticket', 'x-currency' ) . '">' . esc_html__( 'Get Support', 'x-currency' ) . '</a>',
            '<a href="https://demo.doatkolom.com/x-currency" target="_blank" title="' . esc_attr__( 'Demo', 'x-currency' ) . '">' . esc_html__( 'Demo', 'x-currency' ) . '</a>',
            '<a href="' . esc_url( admin_url( 'admin.php?page=x-currency' ) . '#/currencies' ) . '" title="' . esc_attr__( 'Settings', 'x-currency' ) . '">' . esc_html__( 'Settings', 'x-currency' ) . '</a>'
        ];

        foreach ( $custom_links as $link ) {
            array_unshift( $links, $link );
        }

        return $links;
    }

    public function content() {
        View::render( 'admin-screen' );
    }
}
