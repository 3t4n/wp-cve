<?php
/*
Plugin Name: Woocommerce Sidebar Category Accordion
Description: Easily turn your woocmmerce category widget into a collapsable accordion.
Version: 1.1.3
Author: Marc Randall
License: GPLv2 or later
*/

function wcsca_enqueue_styles() {
    wp_enqueue_style( 'style', plugin_dir_url(__FILE__) . 'css/wcsca.css' );
    wp_enqueue_script('script', plugin_dir_url(__FILE__) . '/js/wcsca.js', array(), '', true );

    if ( !wp_style_is( 'fontawesome', 'enqueued' )) {
        wp_register_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css', false, '4.6.1' );
        wp_enqueue_style( 'fontawesome' );
    } 
}
add_action( 'wp_enqueue_scripts', 'wcsca_enqueue_styles' );


function wcsca_register_settings() {
    add_option( 'wcsca_closed_icon', 'fas fa-chevron-down' );
    add_option( 'wcsca_open_icon', 'fas fa-chevron-up' );
    add_option( 'wcsca_font_size' );
    add_option( 'wcsca_padding' );
    register_setting( 'wcsca_options_group', 'wcsca_closed_icon', 'myplugin_callback' );
    register_setting( 'wcsca_options_group', 'wcsca_open_icon', 'myplugin_callback' );
    register_setting( 'wcsca_options_group', 'wcsca_icon_size', 'myplugin_callback' );
    register_setting( 'wcsca_options_group', 'wcsca_padding', 'myplugin_callback' );
 }
 add_action( 'admin_init', 'wcsca_register_settings' );


function wcsca_add_plugin_page_settings_link( $links ) {
    $links[] = '<a href="' .admin_url( 'options-general.php?page=wcsca' ) . '">' . __('Settings') . '</a>';
    return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wcsca_add_plugin_page_settings_link' );


function wcsca_register_options_page() {
    add_options_page( 'WCSCA Settings', 'Woo Sidebar Accordion', 'manage_options', 'wcsca', 'wcsca_options_page' );
}
add_action( 'admin_menu', 'wcsca_register_options_page' );

function wcsca_add_custom(){
    ?>
    <script>
        var wcscaOpenIcon = '<?php echo (get_option("wcsca_open_icon")) ? get_option("wcsca_open_icon") : "fas fa-chevron-up" ?>';
        var wcscaClosedIcon = '<?php echo (get_option("wcsca_closed_icon")) ? get_option("wcsca_closed_icon") : "fas fa-chevron-down" ?>';
    </script>
    <style>
    <?php if(get_option('wcsca_icon_size')) :?>
    .wcsca-icon { font-size: <?php echo get_option("wcsca_icon_size") ?>px; }
    <?php else: ?>
    .wcsca-icon { font-size: 1em; }
    <?php endif; ?>
    <?php if(get_option('wcsca_padding')) :?>
    .widget_product_categories ul li { 
        padding-top: <?php echo get_option('wcsca_padding') / 2 ?>px !important;
        padding-bottom: <?php echo get_option('wcsca_padding') / 2 ?>px !important;
    }
    
    <?php else: ?>
    .widget_product_categories ul li { 
        padding-top: .5em !important;
        padding-bottom: .5em !important;
    }
    <?php endif; ?>
    </style>
<?php }
add_action('wp_head', 'wcsca_add_custom');


function wcsca_options_page() {
?>
  <div class="wrap">
    <h1>Woo Sidebar Category Accordion</h1>
    <form method="post" action="options.php">
    <?php settings_fields( 'wcsca_options_group' ); ?>
    <h3>Custom Accordion Icons</h3>
    <p>For these we are using icons from the <a target="_blank" href="https://www.fontawesome.cin">Font Awesome</a> library.
    <br>To change icons, visit the site & find the icon you wish to use.
    <br><a target="_blank" href="https://fontawesome.com/icons/chevron-up?style=solid">Here's an example</a> of the default "Open" icon we've used. Find HTML snippet near the top of the page, find where it says class="fas fa-chevron-up" in the html, then copy just the classname, i.e: "fas fa-chevron-up" excluding the quotes, and paste below.</p>
    <table>
        <tr valign="top">
            <th scope="row"><label for="closed-icon">Closed Icon</label></th>
            <td><input type="text" id="closed-icon" name="wcsca_closed_icon" value='<?php echo sanitize_text_field(get_option('wcsca_closed_icon')); ?>' /></td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="open-icon">Open Icon</label></th>
            <td><input type="text" id="open-icon" name="wcsca_open_icon" value='<?php echo sanitize_text_field(get_option('wcsca_open_icon')); ?>' /></td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="icon-size">Icon Size</label></th>
            <td><input type="number" min="1" id="icon-size" name="wcsca_icon_size" placeholder="default" value='<?php echo sanitize_text_field(get_option('wcsca_icon_size')); ?>' /></td>
            <th scope="row"><label>px</label></th>
        </tr>
    </table>
    <hr>
    <h3>Vertical Padding</h3>
    <p>Alter the space between each link in the menu</p>
    <table>
        <tr valign="top">
            <td><input type="number" min="1" id="padding" placeholder="default" name="wcsca_padding" value='<?php echo sanitize_text_field(get_option('wcsca_padding')); ?>' /></td>
            <th scope="row"><label>px</label></th>
        </tr>
    </table>
    <?php submit_button(); ?>
    </form>
  </div>
<?php
}