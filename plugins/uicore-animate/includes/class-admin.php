<?php

namespace UiCoreAnimate;

/**
 * Admin Pages Handler
 */
class Admin {

    /**
     * Constructor function to initialize hooks
     *
     * @return void
     */
    public function __construct() {
        //check if the Controller is active
        if ( Settings::get_option( 'uianim_disable' ) != 'true' ){
            add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'animate_elementor_wrapp' ], 1 );
        }

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        
    }

    /**
     * Add admin menu page
     *
     * @return void
     */
    public function admin_menu() {

          //Settings page ( only required if uicore framework is not active )
          if(!\class_exists('\UiCore\Helper')){
            $hook = add_submenu_page( 'options-general.php', 'UiCore Animate', 'UiCore Animate', 'manage_options', 'uicore-animate', [ $this, 'plugin_page' ] );
            // Add initialization hooks only when the plugin page is loaded
            add_action( 'load-' . $hook, [ $this, 'init_hooks' ] );
          }

        //Connect handle
        add_submenu_page(null, 'UiCore Connect', 'UiCore Connect', 'manage_options', 'uicore_connect_free', [$this, 'connect_page_callback']);
    }

    /**
     * Render plugin page
     *
     * @return void
     */
    public function plugin_page() {
        echo '<div id="uianim-settings"></div>';
    }

    /**
     * Add initialization hooks
     *
     * @return void
     */
    public function init_hooks() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        $data = 'var uianim = ' . wp_json_encode( [
            'disable' => Settings::get_option( 'uianim_disable' ),
            'style' => Settings::get_option( 'uianim_style' ),
            'scroll' => Settings::get_option( 'uianim_scroll' ),
            'animations_page'		    => Settings::get_option( 'animations_page' ),
            'animations_page_duration' 	=> Settings::get_option( 'animations_page_duration' ),
            'animations_page_color' 	=> Settings::get_option( 'animations_page_color' ),
            'rest' => \get_rest_url( null, 'uianim/v1/settings'),
            'root' => \get_site_url(),
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'connect' => get_option( 'uianim_connect', 'none' ),
        ] ) . ';';
        wp_enqueue_script( 'uicore_animate-settings');
        wp_enqueue_style( 'uicore_animate-settings');
        wp_add_inline_script('uicore_animate-settings', $data, 'before');
    }

    /**
     * Render animation wrapper for Elementor editor
     *
     * @return void
     */
    public function animate_elementor_wrapp() {
        wp_enqueue_script( 'uicore_animate-admin' );
        wp_enqueue_style( 'uicore_animate-admin' );
        echo '<div id="uianim-wrapp"><div id="uianim-app"></div></div>';
    }

    public function connect_page_callback()
    {
        if(isset($_GET['connect'])){
            

           update_option( 'uianim_connect', esc_attr( sanitize_text_field($_GET['connect']) ) );

            echo '
			<style>
				.ui-connected-wrapper {
					display: flex;
				    align-items: start;
				    justify-content: center;
				    height: 100vh;
					margin-top: 100px;
				}
				.ui-connected {
					display: flex;
					flex-direction: column;
					align-items: center;
					align-content: center;
					max-width: 420px;
					padding: 50px 80px;
					background: white;
					border-radius: 6px;
					border: 2px solid #D5DBE4;
					text-align: center;
				}
				.ui-connected h2 {
					font-size: 24px;
				}
			</style>
			<div class="ui-connected-wrapper">
				<div class="ui-connected">
					<h2>Your website is connected 🥳</h2>
					<p>Set off the fireworks! Your new website was successfully connected and ready to go.</p>
					<p><a href="'.esc_url( get_admin_url(null,'options-general.php?page=uicore-animate') ).'">Take me to Settings Page</a>.</p>
				</div>
			</div>';
        }

    }
}