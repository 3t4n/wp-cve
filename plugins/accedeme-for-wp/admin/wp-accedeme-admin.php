<?php

/**
 * Admin main page.
 *
 * This class defines all code necessary to display admin's main page.
 *
 * @package    wp_accedeme
 * @subpackage wp_accedeme/admin
 * @author     Accedeme
 */
class wp_accedeme_admin
{
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'accedeme_admin_menu_script' ) );
    }

    function accedeme_admin_menu_script(){
        add_options_page( 'WP Accedeme', 'WP Accedeme', 'manage_options', 'accedeme-plugin-option', array( $this, 'accedeme_options_menu_script' ) );
    }

    function accedeme_options_menu_script(){
        
        if ( !current_user_can('manage_options') ){
                
            wp_die( __('No tiene suficientes permisos para acceder a esta página.','wp-accedeme') );
            
        }	
        if( !defined( 'ABSPATH' ) ) exit;
    
        $imageUrl = ACCEDEME_URL .'/assets/images/logo_accedeme.png';
    
        $handle = 'accedeme_wp.css';
        $src = ACCEDEME_URL . '/assets/css/accedeme_wp.css';
    
        require_once ACCEDEME_DIR . 'includes/wp-accedeme-helpers.php';
        $helpers = new wp_accedeme_helpers();

		$website_key = $helpers->accedemeGetWebsiteKey();
    
        wp_enqueue_style( $handle, $src );
        ?>
        <div class="wrap">
    
            <h2><?php _e('WP Accedeme &raquo; Settings','wp-accedeme'); ?></h2>
            
            <div class="container-accede">
                <a id="logo-accedeme" href="<?php echo esc_attr( 'https://accedeme.com/login' ); ?>" target="_blank">
                    <img src="<?php echo esc_url( $imageUrl ); ?>" alt="Accedeme logo">
                </a>
                <?php 
                    if ( $website_key ) 
                    {
                        echo '<a id="btn_panel" href="'.esc_attr( 'https://accedeme.com/login' ).'" target="_blank">
                            <div>';
                        _e('Panel de control','wp-accedeme');
                        echo '</div>
                        </a>';
                    }  
                    else 
                    {
                        echo '<div id="reg_text">';
                        _e('Ya sólo queda registrar tu dominio en accedeme.com', 'wp-accedeme');
                        echo '</div>';
                        echo '<a id="btn_register" href="'.esc_attr( 'https://accedeme.com/register' ).'" target="_blank">
                        <div>';
                        _e('Registra tu dominio ahora','wp-accedeme');
                        echo '</div>
                        </a>';
                    }
                ?>
            </div>
        </div>
    <?php
    }
}