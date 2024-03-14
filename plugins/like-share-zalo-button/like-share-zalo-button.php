<?php
/*
Plugin Name: Like Share Zalo Button
Plugin URI: http://phankimi.com/plugin/
Description: Displays Zalo like share button on WordPress content
Author: Kimi
Author URI: http://phankimi.com
Version: 1.0.1
Text Domain: like-share-zalo-button
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
?>
<?php 
if ( ! defined ( 'WPINC' ) ) {
    die;
}

add_action( 'wp_enqueue_scripts', 'lszb_enqueue_script' );
function lszb_enqueue_script() {
    wp_enqueue_script( 'zalosdk', 'https://sp.zalo.me/plugins/sdk.js', array('jquery'), null, true );
}

// Setup field for zalo setting page
add_action('admin_init', 'lszb_admin_init');
function lszb_admin_init() {
    register_setting('zalo_options', 'zalo_data_layout');

}
add_action('admin_menu', 'lszb_option_page_reg');
function lszb_option_page_reg() {
    add_options_page( 'Zalo Options', 'Zalo Options', 'manage_options', 'zalo-option', 'lszb_option_page' );
}
function lszb_option_page() { ?>
    
    <div class="wrap">    
        <h1>Zalo Settings</h1>
         <form action="options.php" method="post">
        <?php settings_fields('zalo_options'); ?>
            
             <table class="form-table">
                <tr>
                    <td colspan="2"><img src="<?php echo plugin_dir_url(__FILE__) . 'images/data-layout-demo.jpg'; ?>"></td>
                </tr>
                 <tr valign="top">
                     <th scope="row">Zalo button style</th>
                     <td><input type="number" min="1" max="5" name="zalo_data_layout" value="<?php echo esc_attr( get_option('zalo_data_layout') ); ?>" /></td>
                 </tr>
             </table>

             <?php submit_button(); ?>
        
         </form>
    </div>
    
    
<?php }


// insert Zalo button below post content
add_filter( 'the_content', 'lszb_zalo_social_button' );
function lszb_zalo_social_button( $content ) {
    if( is_single() && ! empty ( $GLOBALS['post'] ) ) {
        if( $GLOBALS['post']->ID == get_the_ID() ) {
            $data_link = get_permalink();
            $data_layout = get_option('zalo_data_layout');
         
            $content .= '<div class="zalo-share-button" data-href="' . $data_link . '" data-oaid="579745863508352884" data-layout="'. $data_layout .'" data-color="blue" data-customize=false></div>';
            return $content;
        }
    } else {
        return $content;
    }
}

// clear options when deactive plugin 
register_deactivation_hook( __FILE__, 'lszb_deactivation' );
function lszb_deactivation() {
    delete_option( 'zalo_data_layout' );
}