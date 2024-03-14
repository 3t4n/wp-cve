<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly


function cf7rzp_get_payment_more_info($post_id){

    $res = [];
    $post_id = sanitize_text_field(is_numeric($_GET['post_id'])?$_GET['post_id']:exit);

    $post = get_post($post_id);
    $status = $post->post_status;
    
    $post_meta = get_post_meta($post_id);
    $order_id = $post_meta['cf7rzp_order_id'][0];
    $item_id = $post_meta['item_id'][0];
    $item_name = $post_meta['item_name'][0];
    $item_price = $post_meta['item_price'][0];
    $form_id = $post_meta['cf7_id'][0];
    $gateway = $post_meta['gateway'][0];
    $mode = $post_meta['mode'][0];
    $rzp_order_id = $post_meta['rzp_order_id'][0];
    $rzp_payment_id = $post_meta['rzp_payment_id'][0];
    $failure_reason = $post_meta['failure_reason'][0];

    $res['order_id'] = $order_id;
    $res['item_id'] = $item_id;
    $res['item_name'] = $item_name;
    $res['item_price'] = $item_price;
    $res['form_name'] = get_the_title($form_id);
    $res['gateway'] = $gateway;
    $res['mode'] = $mode;
    $res['rzp_order_id'] = $rzp_order_id;
    $res['status'] = $status;
    $res['status_label'] = cf7rzp_get_payment_status_label($status);

    $dt = get_the_date( 'Y-m-d H:i:s', $post_id);
    $dt = new DateTime($dt, new DateTimeZone('UTC'));
    $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
    $res['created_at'] = $dt->format('F j, Y | h:i:s a');



    if($status == "cf7rzp_success")
        $res['rzp_payment_id'] = $rzp_payment_id;

    if($status == "cf7rzp_failure")
        $res['failure_reason'] = $failure_reason;

    echo json_encode($res);
    wp_die();
}
add_action( 'wp_ajax_nopriv_cf7rzp_get_payment_more_info', 'cf7rzp_get_payment_more_info' );
add_action( 'wp_ajax_cf7rzp_get_payment_more_info', 'cf7rzp_get_payment_more_info' );

if ( !is_plugin_active( 'integrate-razorpay-contact-form-7-premium-addon/integrate-razorpay-contact-form-7-premium-addon.php' ) ) {
    add_filter( 'plugin_action_links_' . CF7RZP_DIR_NAME.'/integrate-razorpay-for-contact-form-7.php', 'cf7rzp_plugin_action_links' );
    add_filter('cf7rzp_admin_rzp_tab', 'cf7rzppa_admin_rzp_tabdemo');
    add_action( 'admin_menu', 'cf7rzppa_admin_menu', 20 );
}
function cf7rzp_plugin_action_links( $links ) {
	$premium_link = '<a href="https://cf7rzppa.codolin.com?utm_source=plugin_user&utm_medium=plugin&utm_campaign=upsell" target="_blank" style="font-weight: bold; color: #1da867;font-size:14px;">Get Premium</a>';
	array_unshift( $links, $premium_link );

	return $links;
}
function cf7rzppa_admin_rzp_tabdemo($rzp_tab_output) { 

    $rzp_tab_output_style .= "<style>
                            .cf7rzppa-demo-rzp-settings{
                                opacity:0.7 !important;position:relative;
                            }
                            .premium-link{
                                position: absolute;
                                display: flex;
                                top: 0;
                                justify-content: center;
                                align-items: center;
                                width: 100%;
                                height: 100%;
                                z-index: 999;
                                background: radial-gradient(#ffffffcf 20%, #ffffff00 50%);
                            }
                            .premium-link a{
                                align-items: center;
                                background-color: #fec228;
                                border-radius: 4px;
                                box-shadow: inset 0 -4px 0 #0003;
                                box-sizing: border-box;
                                color: #000;
                                display: inline-flex;
                                filter: drop-shadow(0 2px 4px rgba(0,0,0,.2));
                                font-family: Arial,sans-serif;
                                font-size: 16px;
                                justify-content: center;
                                line-height: 1.5;
                                min-height: 48px;
                                padding: 8px 1em;
                                text-decoration: none;
                            }
                            .premium-link a:hover{
                                background-color: #f2ae01;
                                color: #000;
                            }
                        </style>";

    $rzp_tab_output .=  $rzp_tab_output_style; 

    $rzp_tab_output .= "<tbody class='cf7rzppa-demo-rzp-settings'>
                            <tr><td>&nbsp;</td></tr>
                            <tr class='premium-link'><td><a href='https://cf7rzppa.codolin.com?utm_source=plugin_user&utm_medium=plugin&utm_campaign=upsell' target='_blank'>Unlock with Premium</a></td></tr>"; 

    $rzp_tab_output .= "<tr><td style='background: #e4e4e4;text-align: center;' colspan='3'><h2 style='color:green;font-weight:bold;'>Premium Settings</h2></td></tr>"; 

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>"; 
    
    $rzp_tab_output .= "<td><label><b>Enable Variable Pricing: </b></label></td>";
	$rzp_tab_output .= "<td><input name='' value='1' class='cf7rzppa_price_enable_checkbox' type='checkbox' CHECKED></td>
                        <td>[ Enabling will override the Item price ]</td>
                        </tr>&nbsp;";

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>";

    $rzp_tab_output .= "<tr>
                            <td>Variable Pricing Form Field: </td>
	                        <td><input type='text' name='' value='variable-price'> <br/>Example: variable-price</td>
                            <td>[ The Form field in contact form 7 that should be used for variable pricing. Allowed form fields: radio buttons | drop-down menu(single select) | checkboxes(single select) ]</td>
                        </tr>";

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>";

    $rzp_tab_output .= "<tr>
                            <td>Variable Pricing Value: </td>
	                        <td><input type='text' name='' value='100|200|300'> <br/>Example: 100|200|300</td>
                            <td>[ Enter variable price value seperated by pipline'|' symbol. <span>Price mapping to options is based on order it is entered.</span> ]</td>
                        </tr>";

    $rzp_tab_output .= "<tr><td>&nbsp;</td></tr>";

    $end_user_pricing_html = "
                            <tr><td colspan='3' style='border-top:1px solid;'>&nbsp;</td></tr>
                            <tr>
                                <td><b>Enable Enduser Pricing: </b></td>
                                <td><input name='' value='1' class='cf7rzppa_price_enable_checkbox' type='checkbox'></td>
                                <td>[ Enabling will override the Item price ]</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>Enduser Pricing Form Field: </td>
                                <td><input type='text' name='' value='enduser-price'> <br/>Example: enduser-price</td>
                                <td>[ The Form field in contact form 7 that should be used for enduser pricing. Allowed form fields: number | text ]</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            ";
    $rzp_tab_output .=  $end_user_pricing_html;     
    
    $form_data_collection_html = "
                            <tr><td colspan='3' style='border-top:1px solid;'>&nbsp;</td></tr>
                            <tr>
                                <td><b>Enable FormData Collection: </b></td>
                                <td><input name='' value='1' type='checkbox' CHECKED></td>
                                <td>[ Enabling will collect user submitted form data ]</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            "; 
    $rzp_tab_output .=  $form_data_collection_html; 

    $order_shortcode_html = "
                            <tr><td colspan='3' style='border-top:1px solid;'>&nbsp;</td></tr>
                            <tr>
                                <td><b>Enable Order Shortcode: </b></td>
                                <td><input name='' value='1' type='checkbox' CHECKED></td>
                                <td>[ Enabling will allow to use order related shortcodes in \"Thank You Page\" ]</td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td><b>[cf7rzppa-order]</b></td>
                                <td><b>Shortcode Usage in \"Thank You\" Page:</b></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan='3'>
                                    <ul style='list-style-type:square;padding-left:17px;'>
                                        <li><code>[cf7rzppa-order]</code> - By default display \"Order Id\" if no \"data\" attribute passed.</li>
                                        <li><code>[cf7rzppa-order data=\"order_id\"]</code> - Display \"Order Id\"</li>
                                        <li><code>[cf7rzppa-order data=\"item_id\"]</code> - Display order related \"Item Id\"</li>
                                        <li><code>[cf7rzppa-order data=\"item_name\"]</code> - Display order related \"Item Name\"</li>
                                        <li><code>[cf7rzppa-order data=\"item_price\"]</code> - Display order related \"Item Price\"</li>
                                    </ul>
                                </td>
                                <td></td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            ";
    $rzp_tab_output .=  $order_shortcode_html;   

    $export_csv_html = "
                        <tr><td colspan='3' style='border-top:1px solid;'>&nbsp;</td></tr>
                        <tr>
                            <td><b>Enable \"Export CSV\": </b></td>
                            <td><input name='' value='1' type='checkbox' CHECKED></td>
                            <td>[ Enabling will allow \"export/download\" of \"Order/Payment\" data and its associated \"User submitted form\" data as CSV file wrt this contact form. ]</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        ";
    $rzp_tab_output .=  $export_csv_html;  

    $custom_orderid_prefix_html = "
                        <tr><td colspan='3' style='border-top:1px solid;'>&nbsp;</td></tr>
                        <tr>
                            <td><b>Custom OrderId Prefix: </b></td>
                            <td><input name='' value='1' type='checkbox'></td>
                            <td>[ Enabling will override the default OrderId prefix. ]</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td>OrderId Prefix: </td>
                            <td><input type='text' name='' value=''> <br/>Example: YOURPRODUCTCODE_</td>
                            <td>[ Enter your custom OrderId Prefix that should replace default prefix: \"cf7rzp_\" ]</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        ";
    $rzp_tab_output .=  $custom_orderid_prefix_html;

    $order_success_redirect_html = "
                        <tr><td colspan='3' style='border-top:1px solid;'>&nbsp;</td></tr>
                        <tr>
                            <td><b>Order Success Redirect: </b></td>
                            <td><input name='' value='1' type='checkbox'></td>
                            <td>[ Enabling will override the default Return/Success Url. ]</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td>Order Success Redirect Url: </td>
                            <td><input type='text' name='' value=''> <br/>Example: http://example.com/thankyou.</td>
                            <td>[ If the customer makes succesful Razorpay Payment, where are they redirected to after.
                            It can be either Internal Thank You page url or External url. ]</td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        ";
    $rzp_tab_output .=  $order_success_redirect_html;

    $rzp_tab_output .= '</tbody></table>';

    return $rzp_tab_output;   
}

function cf7rzppa_admin_menu() {
	add_submenu_page(
        'wpcf7',
        'CF7RZP Premium',
        'CF7RZP Premium',
        'manage_options', 
        'cf7rzp-get-premium',
        'get_premium',
        5
    );
}

