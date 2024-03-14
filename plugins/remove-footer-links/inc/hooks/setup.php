<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
namespace Remove_Footer_Links\Inc\Hooks;
class Setup{

    public function __construct(){

    	$this->hooks();
        
    }
    
    public function hooks(){

        add_action( 'activated_plugin', array( $this, 'redirect_setting' ), 10, 1 );
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'plugin_action_links_' . REMOVE_FOOTER_LINKS_BASENAME, array( $this, 'action_link' ));
        
    }

    public function init() {
        load_plugin_textdomain( 'remove-footer-links', false, plugin_basename( REMOVE_FOOTER_LINKS_PATH ) . '/lang' ); 
    }

    public function redirect_setting( $plugin ){
       
        if( $plugin == 'remove-footer-links/remove-footer-links.php' ) {
            wp_redirect( admin_url( 'options-general.php?page=remove-footer-links&notice=welcome' ) );
            die();
        }

    }  
    
    public function action_link( $links ){
        
        $links = array_merge(
            array(
                '<a href="'.esc_url(admin_url('/options-general.php?page=remove-footer-links')).'">'.__('Settings', 'remove-footer-links').'</a>',
            ),
            array(
                '<a href="'.esc_url( 'https://wordpress.org/support/plugin/remove-footer-links/' ).'">'.__('Forum', 'remove-footer-links').'</a>',
            ),
            $links
        );

        return $links;
        
    }

}
