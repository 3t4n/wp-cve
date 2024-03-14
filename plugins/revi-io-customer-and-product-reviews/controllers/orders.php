<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class revi_orders
{
    var $REVI_API_URL;
    var $prefix;
    var $wpdb;
    var $revimodel;
    var $language_plugin;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->REVI_API_URL = REVI_API_URL;
        $this->revimodel = new revimodel();

        $this->language_plugin = '';
        if (function_exists('icl_object_id')) {
            $this->language_plugin = 'wpml';
        }
        if (function_exists('pll_count_posts')) {
            $this->language_plugin = 'polylang';
        }

        if (isset($_REQUEST['reset_data']) && $_REQUEST['reset_data'] == 1) {
            $this->resetDataOrders();
        }

        $sync_result =  '';
        $sync_result .= $this->sendAllOrders();
        $sync_result .= $this->sendOrdersUpdated();
        $sync_result .= $this->sendAllOrdersStatus(2); //Orders Status Valid
        $sync_result .= $this->sendAllOrdersStatus(0); //orders Status Canceled

        echo $sync_result;
    }

    private function sendAllOrders()
    {
        $sync_text = '';
        $orders = $this->revimodel->getOrders();

        if (empty($orders)) {
            return 'No orders in AllOrders<br>';
        }

        $valid_orders = []; // orders valid sent to Revi
        $orders_data = array();
        $orders_products = array();
        $i = 0;
        $total_price_without_taxes = 0;
        foreach ($orders as $order) {

            $wc_order = wc_get_order($order->id_order);
            if (empty($wc_order)) {
                continue;
            }

            $email = $wc_order->get_billing_email();
            if (empty($email)) {
                continue;
            }

            $iso_country = $wc_order->get_shipping_country();
            if (!empty($iso_country)) {
                $orders_data[$i]['iso_country'] = $iso_country;
            }

            $lang = get_post_meta($order->id_order, 'wpml_language', true);

            if (!empty($lang) && strlen($lang) >= 2) {
                $lang = substr($lang, 0, 2);
            } else if (!empty($iso_country) && strlen($iso_country) >= 2) {
                $lang = substr($iso_country, 0, 2);
            } else {
                //De momento el iso_code es el language default
                $lang = get_option('REVI_SELECTED_LANGUAGE');
            }


            $orders_data[$i]['status'] = 1;
            $orders_data[$i]['id_order'] = $order->id_order;
            $orders_data[$i]['lang'] = $lang;
            $orders_data[$i]['customer_name'] = $wc_order->get_billing_first_name();
            $orders_data[$i]['customer_lastname'] = $wc_order->get_billing_last_name();
            $orders_data[$i]['email'] = $wc_order->get_billing_email();
            $orders_data[$i]['currency'] = $wc_order->get_currency();
            $orders_data[$i]['shipping_cost'] = $wc_order->get_shipping_total();
            $orders_data[$i]['total_discount'] = $wc_order->get_discount_total();
            $orders_data[$i]['order_date'] = $wc_order->get_date_created()->date('Y-m-d H:i:s');
            $orders_data[$i]['date_status_upd'] = $wc_order->get_date_modified()->date('Y-m-d H:i:s');


            $total_paid = $wc_order->get_total();
            $total_tax = $wc_order->get_total_tax();
            $total_price_without_taxes = $total_paid - $total_tax;

            $orders_data[$i]['total_paid'] = $total_paid;
            $orders_data[$i]['taxes'] = $total_tax;

            if ($total_tax && $total_price_without_taxes) { //para que no sea dividido por infinito
                $orders_data[$i]['vat'] = round((($total_paid / $total_price_without_taxes) - 1) * 100, 0);
            } else {
                $orders_data[$i]['vat'] = 0;
            }
            if (is_infinite($orders_data[$i]['vat'])) {
                unset($orders_data[$i]['vat']);
            }

            $orders_products[$order->id_order] = $this->revimodel->getOrderProducts($order->id_order);
            $orders_data[$i]['total_products'] = count($orders_products[$order->id_order]);

            $valid_orders[] = $order->id_order;

            $i++;
        }

        // ENVIAMOS LOS ORDERS PRODUCTS
        $data = array(
            'orders_products' => json_encode($orders_products),
        );
        $result = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/ordersproducts', "POST", $data);
        $result = json_decode($result, true);

        if ($result['success']) {
            $sync_text .= count($orders_products) . ' orders products sync succesfully<br>';
        } else {
            $sync_text .= 'Sync Orders Products Failed | ' . count($orders_products) . ' orders not sync<br>';
        }


        // ENVIAMOS LOS ORDERS
        $data = array(
            'orders' => json_encode($orders_data),
        );

        $result = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/orders', "POST", $data, true);
        $result = json_decode($result, true);

        if ($result['success']) {
            $sync_text .= count($valid_orders) . ' orders sync succesfully<br>';

            //Insertamos en revi_orders
            foreach ($valid_orders as $valid_id_order) {
                $this->revimodel->addReviOrder($valid_id_order, '1');
            }
        } else {
            $sync_text .= 'Sync All orders Failed | ' . count($orders) . ' orders not sync<br>';
        }


        return $sync_text;
    }

    private function sendOrdersUpdated()
    {
        $sync_text = '';
        $orders = $this->revimodel->getOrdersUpdated();

        if (!empty($orders)) {
            foreach ($orders as $order) {

                $products_data = $this->revimodel->getOrderProducts($order->id_order);
                if (!empty($products_data)) {
                    // echo "ORDER: " . $order->id_order . " | ORDER Products: " . count($products_data) . "<br>";

                    // ENVIAMOS LOS ORDERS PRODUCTS
                    $data = array(
                        'id_order' => $order->id_order,
                        'order_products' => json_encode($products_data),
                        'delete_products' => true,
                    );
                    $result = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/orderproducts', "POST", $data);
                    $result = json_decode($result, true);

                    if ($result['success']) {
                        $sync_text .= count($orders) . ' orders updated products sync succesfully<br>';

                        //Insertamos en revi_orders
                        $this->revimodel->updateReviOrders($order->id_order, '1', date("Y-m-d H:i:s"));
                    } else {
                        $sync_text .= 'Sync orders updated Failed | ' . count($products_data) . ' orders not sync<br>';
                    }
                }
            }
        } else {
            $sync_text .= 'No orders updated<br>';
        }
        return $sync_text;
    }

    private function sendAllOrdersStatus($status)
    {
        $orders = $this->revimodel->getOrdersByStatus($status);

        if (!empty($orders)) {

            $orders_data = array();
            $i = 0;
            foreach ($orders as $order) {
                $orders_data[$i]['id_order'] = $order->id_order;
                $orders_data[$i]['status'] = $status;
                $orders_data[$i]['date_status_upd'] = $order->date_status_upd;

                $i++;
            }

            $data = array(
                'orders' => json_encode($orders_data),
            );

            $result = $this->revimodel->reviCURL($this->REVI_API_URL . 'wsapi/ordersstatus', "POST", $data, true);
            $result = json_decode($result, true);

            if ($result['success']) {
                $sync_text = 'Orders Status ' . $status . ' | ' . count($orders) . ' orders sync succesfully<br>';

                foreach ($orders as $order) {
                    $this->revimodel->updateReviOrders($order->id_order, $status);
                }
            } else {
                $sync_text = 'Sync Failed Status ' . $status . ' | ' . count($orders) . ' orders not sync<br>';
            }
        } else {

            $sync_text = 'No orders with STATUS = ' . $status . '<br>';
        }

        return $sync_text;
    }

    private function resetDataOrders()
    {
        global $wpdb;

        //DELETE TABLE
        $structure0 = "DELETE FROM `revi_orders`";
        $wpdb->query($structure0);

        echo "<br>Orders Revi Data Tables Deleted<br>";
    }
}
