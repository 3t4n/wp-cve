<?php
/* if ( ! defined( 'ABSPATH' ) ) exit;
define('WP_DEBUG', false); */
// Init Options Global
global $w2a_options;
?>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
    type="text/css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

<style type="text/css">
    div#app-demo-sec {
        margin: 0 auto;
        width: 320px;
        height: 650px;
        background: url(https://web2application.com/w2a/images/mobile-blank-iphone8-plus.png);
        background-size: 100%;
        background-repeat: no-repeat;
    }

    div#iframe_app_test {
        margin-top: 23%;
        margin-right: 6%;
        margin-left: 6%;
        height: 495px;
        overflow: hidden;
    }

    #app-demo-sec iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    div.scrollmenu {
        margin-right: 6%;
        margin-left: 6%;
        background-color:
            <?php echo $app_menu_background; ?>
        ;
        overflow: auto;
        white-space: nowrap;
    }

    div.scrollmenu a {
        display: inline-block;
        color:
            <?php echo $app_menu_link_color; ?>
        ;
        text-align: center;
        padding: 5px 14px;
        text-decoration: none;
        font-size: 13px;
    }

    div.scrollmenu a:hover {
        background-color: #777;
    }

    .my-section {
        background: #ffffff;
        padding: 10px;
    }

    .form-control {
        width: 200px;
    }

    .form-control2 {
        width: 300px;
    }
</style>

<div class="wrap">

    <h2>
        <?php _e('Woocommerce Order', 'web2application'); ?>
    </h2>

    <table>
        <tr>
            <td valign="top">
                <div class="my-section">
                    <h3>
                        <?php _e('Woocommerce Order', 'web2application'); ?>
                        </h5>
                        <form method="post">
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><label>
                                                <?php _e('API Key', 'web2application'); ?>
                                            </label></th>
                                        <td><input name="api_key" type="text"
                                                value="<?php echo isset($_POST['api_key']) ? $_POST['api_key'] : ''; ?>"
                                                class="form-control" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label>
                                                <?php _e('Start date', 'web2application'); ?>
                                            </label></th>
                                        <td><input name="start_date" type="text"
                                                value="<?php echo isset($_POST['start_date']) ? $_POST['start_date'] : ''; ?>"
                                                class="form-control date-picker" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><label>
                                                <?php _e('End date', 'web2application'); ?>
                                            </label></th>
                                        <td><input name="end_date" type="text"
                                                value="<?php echo isset($_POST['end_date']) ? $_POST['end_date'] : ''; ?>"
                                                class="form-control date-picker" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit"><input type="submit" name="submit" id="submit"
                                    class="button button-primary" value="<?php _e('Search', 'web2application'); ?>" />
                            </p>
                        </form>
                </div><br><br>
            </td>
        </tr>
    </table>
    <br><br>
    <!--test code for orders nir-->

    <?php
    echo 'start nir code';
    // Set the desired date range
    $start_date = '2023-06-01';
    $end_date = '2023-06-30';

    // Convert date format for query
    $start_date = date('Y-m-d H:i:s', strtotime($start_date . ' 00:00:00'));
    $end_date = date('Y-m-d H:i:s', strtotime($end_date . ' 23:59:59'));

    // Prepare the query arguments
    $args = array(
        'post_type' => array('processing', 'completed'),
        'post_status' => 'wc-completed',
        'posts_per_page' => -1,
        'date_query' => array(
            'after' => $start_date,
            'before' => $end_date,
            'inclusive' => true,
        ),
    );

    // Get orders based on query arguments
    $orders = get_posts($args);

    // Process each order
    foreach ($orders as $order) {
        // Get order ID
        $order_id = $order->ID;

        // Get order items
        $order_items = wc_get_order_items($order_id);

        // Process each order item
        foreach ($order_items as $item_id => $item) {
            // Get product ID
            $product_id = $item->get_product_id();

            // Get product categories
            $product_categories = get_the_terms($product_id, 'product_cat');

            // Get product permalink
            $product_permalink = get_permalink($product_id);

            // Get product image URL
            $product_image_url = get_the_post_thumbnail_url($product_id, 'full');

            // Output the order ID, item ID, product ID, categories, permalink, and image URL
            echo 'Order ID: ' . $order_id . '<br>';
            echo 'Item ID: ' . $item_id . '<br>';
            echo 'Product ID: ' . $product_id . '<br>';

            // Output each category name
            if (!empty($product_categories)) {
                echo 'Categories: ';
                foreach ($product_categories as $category) {
                    echo $category->name . ', ';
                }
                echo '<br>';
            }

            echo 'Product Permalink: ' . $product_permalink . '<br>';
            echo 'Product Image URL: ' . $product_image_url . '<br>';

            echo '<br>';
        }
    }


    ?>
    <!--end test code for orders nir-->

    <?php
    if (isset($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {

        $start_date = $_POST['start_date']; // Start date in YYYY-MM-DD format
        $end_date = $_POST['end_date']; // End date in YYYY-MM-DD format
        echo $start_date . "<br>";
        echo $end_date . "<br>";

        if (strtotime($start_date) > strtotime($end_date)) {
            // Start date is larger than end date, switch their values
            $temp_date = $start_date;
            $start_date = $end_date;
            $end_date = $temp_date;
        }

        $args = array(
            'status' => array('processing', 'completed'),
            // Status of the orders you want to search for
            'date_created' => $start_date . '...' . $end_date,
            // Date range query
            'limit' => -1,
            // Retrieve all matching orders
        );
        $orders = wc_get_orders($args);

        if (!empty($orders)) {
            $all_orders = array();
            foreach ($orders as $order) {
                $order_id = $order->get_id();
                $order_date = $order->get_date_created()->date('Y-m-d H:i:s');
                $first_name = $order->get_billing_first_name();
                $last_name = $order->get_billing_last_name();
                $customer_name = $first_name . ' ' . $last_name;

                $order_temp_data = $order->get_data();
                $order_data = array(
                    'id' => $order->get_id(),
                    'order_date' => $order_date,
                    'status' => $order_temp_data['status'],
                    'currency' => $order_temp_data['currency'],
                    'discount_total' => $order_temp_data['discount_total'],
                    'discount_tax' => $order_temp_data['discount_tax'],
                    'shipping_total' => $order_temp_data['shipping_total'],
                    'shipping_tax' => $order_temp_data['shipping_tax'],
                    'cart_tax' => $order_temp_data['cart_tax'],
                    'total' => $order_temp_data['total'],
                    'total_tax' => $order_temp_data['total_tax'],
                    'payment_method' => $order_temp_data['payment_method'],
                    'payment_method_title' => $order_temp_data['payment_method_title'],
                    'billing' => array(
                        'first_name' => $order_temp_data['billing']['first_name'],
                        'last_name' => $order_temp_data['billing']['last_name'],
                        'company' => $order_temp_data['billing']['company'],
                        'address_1' => $order_temp_data['billing']['address_1'],
                        'address_2' => $order_temp_data['billing']['address_2'],
                        'city' => $order_temp_data['billing']['city'],
                        'state' => $order_temp_data['billing']['state'],
                        'postcode' => $order_temp_data['billing']['postcode'],
                        'country' => $order_temp_data['billing']['country'],
                        'email' => $order_temp_data['billing']['email'],
                        'phone' => $order_temp_data['billing']['phone'],
                    ),
                    'shipping' => array(
                        'first_name' => $order_temp_data['shipping']['first_name'],
                        'last_name' => $order_temp_data['shipping']['last_name'],
                        'company' => $order_temp_data['shipping']['company'],
                        'address_1' => $order_temp_data['shipping']['address_1'],
                        'address_2' => $order_temp_data['shipping']['address_2'],
                        'city' => $order_temp_data['shipping']['city'],
                        'state' => $order_temp_data['shipping']['state'],
                        'postcode' => $order_temp_data['shipping']['postcode'],
                        'country' => $order_temp_data['shipping']['country'],
                        'phone' => $order_temp_data['shipping']['phone'],
                    ),
                );

                $item_data = array();
                $order = wc_get_order($order_id);
                $items = $order->get_items();
                foreach ($items as $item) {


                    $item_data[] = array(
                        'product_id' => $item->get_product_id(),
                        'product_name' => $item->get_name(),
                        'quantity' => $item->get_quantity(),


                        'category_tree_product' => get_product_categories_with_ancestors($item->get_product_id()),
                        'product_permalink' => get_permalink($item->get_product_id()),
                        'product_thumbnail' => get_the_post_thumbnail_url($item->get_product_id(), 'full'),

                    );

                }
                $order_data['items'] = $item_data;

                $all_orders[$order_id] = $order_data;
            }
            echo '<pre style=" margin: 0; padding: 15px; background-color:#fff;">' . json_encode($all_orders, JSON_PRETTY_PRINT) . '</pre>';
        } else {
            echo '<div class="my-section">';
            _e('No orders found.', 'web2application');
            echo '</div>';
        }
    }

    //product main parent category				
    function get_product_categories_with_ancestors($product_id, $taxonomy = 'product_cat')
    {
        $categories = wp_get_post_terms($product_id, $taxonomy);

        foreach ($categories as &$category) {
            $ancestors = get_ancestors($category->term_id, $taxonomy);
            $ancestors = array_reverse($ancestors);

            $category->ancestors = array_map(function ($ancestor_id) use ($taxonomy) {
                return get_term($ancestor_id, $taxonomy);
            }, $ancestors);
        }

        return $categories;
    }
    //end product main parent category	
    
    ?>

</div>

<script>
    jQuery('.date-picker').datepicker({
        format: "yyyy-mm-dd",
        language: "ru",
        isRTL: true,
        autoclose: true,
        todayHighlight: true
    });
</script>