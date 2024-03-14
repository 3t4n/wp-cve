<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.tplugins.com
 * @since      1.0.0
 *
 */

add_action( 'admin_enqueue_scripts', 'tpspicp_enqueue_styles_admin' );
function tpspicp_enqueue_styles_admin() {
    wp_enqueue_style( 'tp-show-product-images-on-checkout-page-admin', plugin_dir_url( __FILE__ ) . 'css/tp-show-product-images-on-checkout-page-admin.css', array(), TPSPICP_VERSION, 'all' );
}

add_action( 'admin_enqueue_scripts', 'tpspicp_enqueue_scripts_admin' );
function tpspicp_enqueue_scripts_admin() {
    wp_enqueue_script( 'tp-show-product-images-on-checkout-page-admin', plugin_dir_url( __FILE__ ) . 'js/tp-show-product-images-on-checkout-page-admin.js', array( 'jquery','jquery-ui-core','jquery-ui-tabs' ), TPSPICP_VERSION, false );
}

// create custom plugin settings menu
add_action('admin_menu', 'tpspicp_plugin_create_menu');

function tpspicp_plugin_create_menu() {

	//create new top-level menu
	add_menu_page(TPSPICP_PLUGIN_MENU_NAME, TPSPICP_PLUGIN_MENU_NAME, 'administrator', __FILE__, 'tpspicp_plugin_settings_page' , plugins_url('/images/tp.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_tpspicp_plugin_settings' );
}


function register_tpspicp_plugin_settings() {
    //register our settings
    register_setting('tpspicp-plugin-settings-group','tpspicp_image_width');
    register_setting('tpspicp-plugin-settings-group','tpspicp_image_height');
    register_setting('tpspicp-plugin-settings-group','tpspicp_is_rtl');
    register_setting('tpspicp-plugin-settings-group','tpspicp_image_border_radius');
    register_setting('tpspicp-plugin-settings-group','tpspicp_label_sale');
    register_setting('tpspicp-plugin-settings-group','tpspicp_label_you_saved');
    register_setting('tpspicp-plugin-settings-group','tpspicp_show_total_discount_cart');
    register_setting('tpspicp-plugin-settings-group','tpspicp_show_regular_sale_price_cart');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    // register_setting('tpspicp-plugin-settings-group','tpspicp_image_size');
    
}

function tpspicp_plugin_settings_page() {

    $tpspicp_image_width         = get_option('tpspicp_image_width');
    $tpspicp_image_height        = get_option('tpspicp_image_height');
    $tpspicp_activate            = get_option('tpspicp_activate');
    $tpspicp_is_rtl              = get_option('tpspicp_is_rtl');
    $tpspicp_image_border_radius = get_option('tpspicp_image_border_radius');

    $tpspicp_show_total_discount_cart     = get_option('tpspicp_show_total_discount_cart');
    $tpspicp_show_regular_sale_price_cart = get_option('tpspicp_show_regular_sale_price_cart');

    $tpspicp_label_sale      = get_option('tpspicp_label_sale');
    $tpspicp_label_you_saved = get_option('tpspicp_label_you_saved');
   
    //$tpspicp_activate_check            = ($tpspicp_activate) ? 'checked="checked"' : '';
    $tpspicp_is_rtl_check              = ($tpspicp_is_rtl) ? 'checked="checked"' : '';
    //$tpspicp_image_border_radius_check = ($tpspicp_image_border_radius) ? 'checked="checked"' : '';
    $tpspicp_image_width               = ($tpspicp_image_width) ? $tpspicp_image_width : 50;
    $tpspicp_image_height              = ($tpspicp_image_height) ? $tpspicp_image_height : 50;

    $tpspicp_image_border_radius       = ($tpspicp_image_border_radius) ? $tpspicp_image_border_radius : 0;

    $tpspicp_label_sale = ($tpspicp_label_sale) ? $tpspicp_label_sale : 'SALE';
    $tpspicp_label_you_saved = ($tpspicp_label_you_saved) ? $tpspicp_label_you_saved : 'You Saved';

    $tpspicp_show_total_discount_cart_check = ($tpspicp_show_total_discount_cart) ? 'checked="checked"' : '';
    $tpspicp_show_regular_sale_price_cart_check = ($tpspicp_show_regular_sale_price_cart) ? 'checked="checked"' : '';

?>
<div class="wrap tpglobal-wrap tpspicp-wrap">

    <h1><?php echo TPSPICP_PLUGIN_NAME; ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'tpspicp-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'tpspicp-plugin-settings-group' ); ?>

        <div id="tpspicp-tabs" class="tpglobal-tabs">
            <ul>
                <li><a href="#tabs-1">Settings</a></li>
                <li><a href="#tabs-2">Labels</a></li>
            </ul>
            
            <div id="tabs-1" class="tpglobal-tabs-content">
                <div class="tpspicp_admin_settings_left">

                    <div class="tpspicp_admin_settings_row">

                        <h2>Image Size</h2>

                        <div class="tpglobal-2-rows-box">
                            <div class="tpglobal-2-rows">
                                <label for="tpspicp_image_width">Width (in px)</label>
                                <input type="number" name="tpspicp_image_width" value="<?php echo esc_html($tpspicp_image_width); ?>" >
                            </div>

                            <div class="tpglobal-2-rows">
                                    <label>Border Radius (in px)</label>
                                <input type="number" name="tpspicp_image_border_radius" value="<?php echo esc_html($tpspicp_image_border_radius); ?>" style="padding: 5px;">
                            </div><!-- tpspicp_admin_settings_row -->

                        </div>

                    </div><!-- tpspicp_admin_settings_row -->

                    <div class="tpspicp_admin_settings_row">
                        <label class="tpspicp-container-checkbox">Is RTL
                            <input type="checkbox" name="tpspicp_is_rtl" value="1" <?php echo esc_html($tpspicp_is_rtl_check); ?>>
                            <span class="checkmark"></span>
                        </label>
                        <span class="tpspicp_admin_settings_desc">Check this option if your site is RTL</span>
                    </div><!-- tpspicp_admin_settings_row -->

                    <div class="tpspicp_admin_settings_row">
                        <label class="tpspicp-container-checkbox">Show Total Discount on Cart/Checkout Page
                            <input type="checkbox" name="tpspicp_show_total_discount_cart" value="1" <?php echo esc_html($tpspicp_show_total_discount_cart_check); ?>>
                            <span class="checkmark"></span>
                        </label>
                        <span class="tpspicp_admin_settings_desc">Show total amount of money a customer saved (sale prices plus coupon discounts).</span>
                    </div><!-- tpspicp_admin_settings_row -->

                    <div class="tpspicp_admin_settings_row">
                        <label class="tpspicp-container-checkbox">Show Regular/Sale Price on Cart/Checkout Page
                            <input type="checkbox" name="tpspicp_show_regular_sale_price_cart" value="1" <?php echo esc_html($tpspicp_show_regular_sale_price_cart_check); ?>>
                            <span class="checkmark"></span>
                        </label>
                    </div><!-- tpspicp_admin_settings_row -->
                    
                </div><!-- tpspicp_admin_settings_left -->

                <div class="tpspicp_admin_settings_right">

                </div><!-- tpspicp_admin_settings_right -->

            </div><!-- tps_admin_section -->

            <div id="tabs-2" class="tpglobal-tabs-content">
                <div class="tpspicp_admin_settings_left">

                    <div class="tpspicp_admin_settings_row">
                        <label class="tpspicp-container-input">SALE</label>
                        <input type="test" name="tpspicp_label_sale" value="<?php echo esc_html($tpspicp_label_sale); ?>" >
                    </div>

                    <div class="tpspicp_admin_settings_row">
                        <label class="tpspicp-container-input">You Saved</label>
                        <input type="test" name="tpspicp_label_you_saved" value="<?php echo esc_html($tpspicp_label_you_saved); ?>" >
                    </div>

                </div>
            </div><!-- tps_admin_section -->
       
        </div>

        <div class="tpglobal-submit"><?php submit_button(); ?></div>

    </form>

</div>
<?php

}

function tpspicp_select_options($name,$options,$selected = false) {

    $select = '<select name="'.$name.'">';
    foreach ($options as $option) {
        if($selected && $selected == $option){
            $select .= '<option value="'.$option.'" selected>'.$option.'</option>';
        }
        else{
            $select .= '<option value="'.$option.'">'.$option.'</option>';
        }
    }
    $select .= '</select>';
    
    return $select;
    
}

function tpspicp_asso_select_options($name,$options,$selected = false) {

    $select = '<select name="'.$name.'">';
    foreach ($options as $key => $value) {
        if($selected && $selected == $key){
            $select .= '<option value="'.$key.'" selected>'.$value.'</option>';
        }
        else{
            $select .= '<option value="'.$key.'">'.$value.'</option>';
        }
    }
    $select .= '</select>';
    
    return $select;
    
}

function tpspicp_select_img_size($selected = false) {
    $image_sizes = get_intermediate_image_sizes();
    if($image_sizes){
        $select = '<select name="tpspicp_image_size">';
        foreach ($image_sizes as $image_size) {
            if($selected && $selected == $image_size){
                $select .= '<option value="'.$image_size.'" selected>'.$image_size.'</option>';
            }
            else{
                $select .= '<option value="'.$image_size.'">'.$image_size.'</option>';
            }
        }
        $select .= '</select>';
        
        return $select;
    }
}

