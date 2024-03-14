<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */
namespace Remove_Footer_Links\Inc\Hooks;
class Enqueue{

    public function __construct(){

    	  $this->hooks();

    }

    public function hooks(){

        add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        
    }

    public function front_scripts(){

        $prefix = '.min';
        $option_name = 'remove_footer_links';
        $option_values = get_option($option_name, remove_footer_links_default());

        $theme = wp_get_theme();
        $data = array(
            'auto_remove_links'       => $option_values['auto_remove_links'],
            'permalink' => home_url(),
            'theme' => array(
                'name' => $theme->get('name'),
                'theme_uri' => $theme->get('ThemeURI'),
                'author' => $theme->get('Author'),
                'author_uri' => $theme->get('AuthorURI'),
                'version' => $theme->get('Version'),
            ),
        );
       
        wp_enqueue_script( 'remove-footer-links-main-js', remove_footer_links_assets_url('js/main'.$prefix.'.js'), array('jquery'), '1.0.0', true );

        wp_localize_script( 'remove-footer-links-main-js', 'remove_footer_links_config', $data );
      
    }

    public function admin_scripts(){

        $prefix = '.min';

        if(isset($_GET['page']) && $_GET['page'] == 'remove-footer-links' ){
            wp_enqueue_style( 'remove-footer-links-admin', remove_footer_links_assets_url('css/admin'.$prefix.'.css'), array(), '1.0.0' );
        }

    }

}
