<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
/**
 * Moove_Radioselect_Controller File Doc Comment
 *
 * @category  Moove_Radioselect_Controller
 * @package   moove-radio-select
 * @author    Moove Agency
 */

/**
 * Moove_Radioselect_Controller Class Doc Comment
 *
 * @category Class
 * @package  Moove_Radioselect_Controller
 * @author   Moove Agency
 */
class Moove_Radioselect_Controller {
	/**
	 * Construct function
	 */
	public function __construct() {

	}

	public function get_plugin_details( $plugin_slug = '' ) {
        $plugin_return = false;
        $wp_repo_plugins    = '';
        $wp_response        = '';
        $wp_version         = get_bloginfo('version');
        if ( $plugin_slug && $wp_version > 3.8 ) :
            $args = array(
                'author' => 'MooveAgency',
                'fields' => array(
                    'downloaded'        => true,
                    'active_installs'   => true,
                    'ratings'           => true
                )
            );
            $wp_response = wp_remote_post(
                'http://api.wordpress.org/plugins/info/1.0/', 
                array(
                    'body' => array(
                        'action'    => 'query_plugins',
                        'request'   => serialize( (object) $args )
                    )
                )
            );
            if ( ! is_wp_error( $wp_response ) ) :
                $wp_repo_response       = unserialize( wp_remote_retrieve_body( $wp_response ) );
                $wp_repo_plugins        = $wp_repo_response->plugins;
            endif;
            if ( $wp_repo_plugins ) :
                foreach ( $wp_repo_plugins as $plugin_details ) :
                    if ( $plugin_slug == $plugin_details->slug ) :
                        $plugin_return = $plugin_details;
                    endif;
                endforeach;
            endif;
        endif;
        return $plugin_return;
    }

}
$moove_radioselect_controller_provider = new Moove_Radioselect_Controller();
