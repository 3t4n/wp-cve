<?php
/*
Plugin Name: Woocommerce Add Custom Text On Single Product Page
Plugin URI:
Description: This plugin will help to write and show custom text on woocommerce single product page.
Version: 3.0.0
Author: Therichpost
Author URI: https://therichpost.com
*/

define( 'Woo_ACT', '3.0.0' );
define( 'Woo_ACT' , plugin_dir_path( __FILE__ ));

//##Backend Settings##//

function register_woo_act_submenu_page() {
    add_submenu_page( 'woocommerce', 'Single Product Submenu Page', 'Single Product Page', 'manage_options', 'single-product-page', 'woo_act_callback' ); 
    //call register settings function
	add_action( 'admin_init', 'register_woo_act_settings' );
}
add_action('admin_menu', 'register_woo_act_submenu_page',99);

function register_woo_act_settings() {
	//register our settings
	register_setting( 'register-woo-act-settings-group', 'act_text' );
	
}

function woo_act_callback() { ?>
    <div class="wrap">
<h1>Single Product Page Text</h1>
<p>Write the text which you want to show on single product page.</p>

<form method="post" action="options.php">
    <?php settings_fields( 'register-woo-act-settings-group' ); ?>
    <?php do_settings_sections( 'register-woo-act-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Add Content</th>
        <td><?php
				$content = get_option('act_text');
				$editor_id = 'act_text';

				wp_editor( $content, $editor_id );
				?></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php }

function woo_act_frontend(){
  global $product;
  echo get_option( 'act_text' );
}
add_action( 'woocommerce_single_product_summary','woo_act_frontend',25);
// Frontend


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'woo_add_action_links' );

function woo_add_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'admin.php?page=single-product-page' ) . '" rel="nofollow">Settings</a>',
 );
return array_merge( $links, $mylinks );
}
