<?php

namespace AliNext_Lite;;

/*
Controller name: Core
Controller description: Basic introspection methods
 */

class JSON_API_Core_Controller
{

    private $product_import_model;
    private $loader;

    public function __construct()
    {
		//todo: perhaps it would be better to move this call to some other controller
        Utils::clear_system_error_messages();

        $this->product_import_model = new ProductImport();
        $this->woocommerce_model = A2WL()->getDI()->get('AliNext_Lite\Woocommerce');
        $this->loader = new Aliexpress();
    }

    public function permissions($method)
    {
        global $a2wl_json_api;
        $protected_methods = array('add_product', 'upd_product', 'del_product', 'get_products', 'get_products_info', 'upd_order', 'get_settings', 'get_orders', 'update_tracking_data');
        if (in_array($method, $protected_methods)) {
            $a2wl_key = $a2wl_json_api->query->get('a2w-key');
            if (!empty($a2wl_key)) {
                // new auth method
                return $a2wl_json_api->query->is_valid_api_key($a2wl_key);
            } else {
                // old auth method
                if ($a2wl_json_api->query->cookie && wp_validate_auth_cookie($a2wl_json_api->query->cookie, 'logged_in')) {
                    return true;
                }
                return false;
            }
        }
        return true;
    }

    public function info()
    {
        global $a2wl_json_api;
        if (!empty($a2wl_json_api->query->controller)) {
            return $a2wl_json_api->controller_info($a2wl_json_api->query->controller);
        } else {
            $active_controllers = explode(',', get_setting('json_api_controllers'));
            $controllers = array_intersect($a2wl_json_api->get_controllers(), $active_controllers);
            return array(
                'json_api_version' => A2WL_JSON_API_VERSION,
                'controllers' => array_values($controllers),
            );
        }
    }

    public function add_product()
    {
        global $wpdb;
        global $a2wl_json_api;
        $result = array();

        if (empty($_REQUEST['id'])) {
            $a2wl_json_api->error("No ID specified. Include 'id' var in your request.");
        } else {
            $product_id = $_REQUEST['id'];
            $product = array('id' => $product_id);

            if (!empty($_REQUEST['url'])) {
                $product['url'] = $_REQUEST['url'];
            }
            if (!empty($_REQUEST['thumb'])) {
                $product['thumb'] = $_REQUEST['thumb'];
            }
            if (!empty($_REQUEST['price'])) {
                $product['price'] = str_replace(",", ".", $_REQUEST['price']);
            }
            if (!empty($_REQUEST['price_min'])) {
                $product['price_min'] = str_replace(",", ".", $_REQUEST['price_min']);
            }
            if (!empty($_REQUEST['price_max'])) {
                $product['price_max'] = str_replace(",", ".", $_REQUEST['price_max']);
            }
            if (!empty($_REQUEST['title'])) {
                $product['title'] = $_REQUEST['title'];
            }
            if (!empty($_REQUEST['currency'])) {
                $product['currency'] = $_REQUEST['currency'];
            }

            $imported = !!$this->woocommerce_model->get_product_id_by_external_id($product['id']) || !!$this->product_import_model->get_product($product['id']);
            // $post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_a2w_external_id' AND meta_value='%s' LIMIT 1", $product['id']));
            if (get_setting('allow_product_duplication') || !$imported) {
                $params = (a2wl_check_defined('A2WL_CHROME_EXT_IMPORT') && !empty($_POST['apd']))
                ? array('data' => array('apd' => json_decode(stripslashes($_POST['apd']))))
                : array();

                $result = $this->loader->load_product($product['id'], $params);
                if ($result['state'] !== 'error') {
                    $product = array_replace_recursive($product, $result['product']);
                    $product = PriceFormula::apply_formula($product);

                    $result = $this->product_import_model->add_product($product);
                    $result = array('status' => 'ok');
                } else {
                    $a2wl_json_api->error($result['message']);
                }
            } else {
                $a2wl_json_api->error('Product already imported.');
            }
        }

        return $result;
    }

    public function upd_product()
    {
        global $a2wl_json_api;

        $product_id = $_REQUEST['id'];
        if (empty($product_id)) {
            $a2wl_json_api->error("No ID specified. Include 'id' var in your request.");
        } else {

            $product = $this->product_import_model->get_product($product_id);
            if (!$product) {
                $product = array('id' => $product_id);
            }

            if (!empty($_REQUEST['url'])) {
                $product['url'] = $_REQUEST['url'];
            }
            if (!empty($_REQUEST['thumb'])) {
                $product['thumb'] = $_REQUEST['thumb'];
            }
            if (!empty($_REQUEST['price'])) {
                $product['price'] = str_replace(",", ".", $_REQUEST['price']);
            }
            if (!empty($_REQUEST['price_min'])) {
                $product['price_min'] = str_replace(",", ".", $_REQUEST['price_min']);
            }
            if (!empty($_REQUEST['price_max'])) {
                $product['price_max'] = str_replace(",", ".", $_REQUEST['price_max']);
            }
            if (!empty($_REQUEST['title'])) {
                $product['title'] = $_REQUEST['title'];
            }
            if (!empty($_REQUEST['currency'])) {
                $product['currency'] = $_REQUEST['currency'];
            }

            $this->product_import_model->upd_product($product);
        }

        return array();
    }

    public function del_product()
    {
        global $a2wl_json_api;

        $product_id = $_REQUEST['id'];
        if (empty($product_id)) {
            $a2wl_json_api->error("No ID specified. Include 'id' var in your request.");
        } else {
            $this->product_import_model->del_product($product_id);
        }
        return array();
    }

    public function get_products()
    {
        global $a2wl_json_api;

        $tmp_products = $this->product_import_model->get_product_list();

        if (isset($_REQUEST['html'])) {
            return array('products' => $tmp_products);
        } else {
            $result = array();
            foreach ($tmp_products as $id => $p) {
                $result[$id] = array('id' => $id);
            }
            return array('products' => $result);
        }
    }

    public function get_products_info()
    {
        global $a2wl_json_api;
        if (!empty($_REQUEST['id'])) {
            $products = array();
            $ids = array_map('trim', is_array($_REQUEST['id']) ? $_REQUEST['id'] : explode(",", $_REQUEST['id']));
            foreach ($ids as $id) {
                $product = array('id' => $id);
                $product['imported'] = !!$this->woocommerce_model->get_product_id_by_external_id($id) ||
                !!$this->product_import_model->get_product($id);
                $products[] = $product;
            }
            return array('products' => $products);
        } else {
            $a2wl_json_api->error("You should pass products id.");
        }
    }

    public function upd_order()
    {
        global $a2wl_json_api;

        $order_id = $_REQUEST['id'];
        if (empty($order_id)) {
            $a2wl_json_api->error("No ID specified. Include 'id' var in your request.");
        } else {

            $order = wc_get_order($order_id);

            if ($order === false) {
                $a2wl_json_api->error("Did not find the appropriate Woocommerce order.");
            } else {
                if (isset($_REQUEST['match_orders']) && !empty($_REQUEST['match_orders'])) {
                    $matchOrders = $_REQUEST['match_orders'];

                    $order_items = $order->get_items();
                    if (count($order_items)) {
                        foreach ($matchOrders as $matchOrder) {
                            $external_order_id = $matchOrder['orderId'];
                            $orderTotal = $matchOrder['orderTotal'];
                            if ($orderTotal) {
                                //todo: we can save order total in a future plugin release
                            }
                            $matchProductIds = array_unique($matchOrder['matchProductIds']);
                            foreach ($order_items as $item_id => $item) {

                                $a2wl_order_item = new WooCommerceOrderItem($item);

                                $external_id = $a2wl_order_item->get_external_product_id();

                                if (in_array($external_id, $matchProductIds)) {

                                    //save external order id if payment is done
                                    //  if ( strtolower( trim( $matchOrder['orderStatus'] ) ) === 'wait_seller_send_goods' ) {
                                    $a2wl_order_item->update_external_order($external_order_id, true);
                                    // }

                                }
                            }
                        }
                    }

                } else if (!empty($_REQUEST['external_id'])) {

                    /* todo: $_REQUEST['external_id'] deprecated and it will be removed in a future versions of the chrome extension
                     * use $_REQUEST['match_orders'] instead
                     */
                    $data = $_REQUEST['external_id'];

                    $current_data = $order->get_meta(Constants::old_order_external_order_id(), false);

                    if (is_array($data)) {
                        foreach ($data as $code_value) {
                            if (in_array($code_value, $current_data)) {
                                continue;
                            }

                            $code_value = trim($code_value);
                            if (!empty($code_value)) {
                                $order->add_meta_data(Constants::old_order_external_order_id(), $code_value);
                            }
                        }
                    } else {
                        $code_value = $data;
                        if (!in_array($code_value, $current_data)) {
                            $order->add_meta_data(Constants::old_order_external_order_id(), $code_value);
                        }
                    }
                }

                $placed_order_status = get_setting('placed_order_status');
                if ($placed_order_status !== "") {
                    $order->update_status($placed_order_status);
                }

                $order->save();
            }
        }

        return [];
    }

    public function get_orders()
    {
        global $a2wl_json_api;

        $result = $this->woocommerce_model->get_fulfilled_orders_data();

        return array('orders' => $result);

    }

    public function update_tracking_data()
    {
        global $a2wl_json_api;

        if (!isset($_REQUEST['order_array'])) {

            $a2wl_json_api->error("No orders array specified. Include 'orders' array var in your request.");

        } else {

            $orders = json_decode(stripslashes($_REQUEST['order_array']), true);

            foreach ($orders as $order) {

                $order_id = intval($order['order_id']);
                $ext_id = floatval($order['ext_order_id']);

                $tracking_codes = isset($order['tracking_data']['tracking_codes']) ? $order['tracking_data']['tracking_codes'] : array();
                $carrier_name = isset($order['tracking_data']['carrier_name']) ? $order['tracking_data']['carrier_name'] : '';
                $carrier_url = isset($order['tracking_data']['carrier_url']) ? $order['tracking_data']['carrier_url'] : '';
                $tracking_status = isset($order['tracking_data']['tracking_status']) ? $order['tracking_data']['tracking_status'] : '';

                $result = $this->woocommerce_model->save_tracking_code($order_id, $ext_id, $tracking_codes, $carrier_name, $carrier_url, $tracking_status);
            }

        }

        return array();

    }

    public function get_settings()
    {
        global $a2wl_json_api;

        $localizator = AliexpressLocalizator::getInstance();

        $settings = array('a2w_fulfillment_prefship' => get_setting('fulfillment_prefship', 'ePacket'),
            'a2w_aliship_shipto' => get_setting('aliship_shipto', 'US'),
            'a2w_import_language' => $localizator->language,
            'a2w_import_locale' => $localizator->getLangCode(),
            'a2w_local_currency' => $localizator->currency,
            'a2wl_chrome_ext_import' => a2wl_check_defined('A2WL_CHROME_EXT_IMPORT'),
        );

        return array('settings' => $settings);
    }

}
