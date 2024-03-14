<?php
/**
 * Regenerate Download Permissions for Orders
 *
 * Plugin Name: Regenerate Download Permissions for Orders
 * Description: With this plugin you can regenerate download permissions for all orders of a product so that those with old orders could download new file .
 * Version:     1.0.0
 * Author:      Hamed Fuladi
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

function rdpfwo_regen_woo_downloadable_product_permissions( $order_id ){

    $data_store = WC_Data_Store::load( 'customer-download' );
    $data_store->delete_by_order_id( $order_id );
    wc_downloadable_product_permissions( $order_id, true );

}
function rdpfwo_get_orders_ids_by_product_id( $product_id ) {
    global $wpdb;

    $orders_statuses = "'wc-completed', 'wc-processing', 'wc-on-hold'";
    return $wpdb->get_col( "
        SELECT DISTINCT woi.order_id
        FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
             {$wpdb->prefix}woocommerce_order_items as woi, 
             {$wpdb->prefix}posts as p
        WHERE  woi.order_item_id = woim.order_item_id
        AND woi.order_id = p.ID
        AND p.post_status IN ( $orders_statuses )
        AND woim.meta_key IN ( '_product_id', '_variation_id' )
        AND woim.meta_value LIKE '$product_id'
        ORDER BY woi.order_item_id DESC"
    );
}
function rdpfwo_regen_dl_permissions_all_users(){
    $product_id = intval($_POST["pId"]);
    $orders = rdpfwo_get_orders_ids_by_product_id($product_id);
    $counter = 0;
    foreach ($orders as $order_id){
        rdpfwo_regen_woo_downloadable_product_permissions($order_id);
        $counter = $counter + 1;
    }
    update_post_meta($product_id,"regen_dl_permissions",current_time('Y/m/d H:i:m',true));
    echo esc_html("Download Permissions for "). esc_html($counter).esc_html(" order has been regenerated!");
    die();
}

add_action( 'wp_ajax_rdpfwo_regen_dl_permissions_all_users', 'rdpfwo_regen_dl_permissions_all_users' );

function rdpfwo_regen_dl_permissions_all_users_metabox_callback(){
    ?>
    <style>
        #regen_dl_permissions_box .inside> *{
            margin-bottom: 10px!important;
        }
        #regen_dl_permissions_all_users.working{
            border:unset;
            background: #868686;
        }
    </style>
    <input type="button" id="regen_dl_permissions_all_users" class="button button-primary button-large" value="Regenerate download permissions" style=" margin: auto; display: block; ">
    <div class="regen_dl_text"></div>
    <div class="regen_dl_last">Last update : <span style="direction: ltr;float: right"><?php echo esc_html(get_post_meta(get_the_ID(),"regen_dl_permissions","true")) ?></span></div>
    <script>
        jQuery(document).ready(function($) {
            var data = {
                'action': 'rdpfwo_regen_dl_permissions_all_users',
                'pId': <?php echo esc_html(get_the_ID())?>
            };
            document.getElementById("regen_dl_permissions_all_users").addEventListener("click",function (){
                document.querySelector('.regen_dl_text').innerHTML = ' <b>Your request is prosecing!</b>';
                if (!(document.getElementById("regen_dl_permissions_all_users").classList.contains("working"))){
                    document.getElementById("regen_dl_permissions_all_users").classList.add("working");
                    jQuery.post(ajaxurl, data, function(response) {
                        document.querySelector('.regen_dl_text').innerText = response;
                        document.getElementById("regen_dl_permissions_all_users").classList.remove("working");
                    });
                }
            })
        });
    </script>
    <?php
}
function rdpfwo_regen_dl_permissions_all_users_metabox() {
    add_meta_box( 'regen_dl_permissions_box', "Download Permissions", 'rdpfwo_regen_dl_permissions_all_users_metabox_callback', 'product' ,'side');
}
add_action( 'add_meta_boxes', 'rdpfwo_regen_dl_permissions_all_users_metabox' );