<?php
/*
 * Plugin Name: Azameo Retargeting
 * Plugin URI: http://dashboard.azameo.fr/register
 * Description: Azameo Retargeting and Facebook Ads is the world’s easiest ad platform for WooCommerce and Wordpress: Show your products and latest content to turn prospects into customers.
 * Author: Azameo
 * Version: 1.5.6
 * Author URI: http://www.azameo.fr/
 * Text Domain: azaflash-retargeting
 * Domain Path: /languages
 * WC tested up to: 4.5
*/

/**
 * Copyright (c) 2017 Azameo. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

if (!class_exists('AZAMEO_Plugin')) {
    class AZAMEO_Plugin
    {
        public static function init()
        {
            register_activation_hook(__FILE__, 'AZAMEO_Plugin::hook_activation');
            register_deactivation_hook(__FILE__, 'AZAMEO_Plugin::hook_deactivation');
            //this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
            add_action("admin_init", "AZAMEO_Plugin::action_admin_init");
            // Include conversion & navigation tag in the head
            add_action('wp_head', 'AZAMEO_Plugin::action_wp_head');
            //this action callback is triggered when wordpress is ready to add new items to menu.
            add_action("admin_menu", "AZAMEO_Plugin::action_admin_menu");
            //this action is executed in the footer
            add_action('wp_footer', 'AZAMEO_Plugin::action_wp_footer');
            // load languages files
            add_action('plugins_loaded', 'AZAMEO_Plugin::action_plugins_loaded');
            // update plugin action
            add_action('upgrader_process_complete', 'AZAMEO_PLUGIN::action_upgrade', 10, 2);// priority 10, nb args 2

            // Register the 3 API : feed, info & order
            add_action('rest_api_init', function () {
                register_rest_route(
                    'azameo/v2',
                    '/productsfeed',
                    array(
                        'methods' => WP_REST_Server::READABLE, //'GET'
                        'callback' => 'AZAMEO_Plugin::api_feed_json',
                        'permission_callback' => '__return_true',
                        'args' => array(
                            'next_id',
                        ),
                    )
                );
            });
            add_action('rest_api_init', function () {
                register_rest_route(
                    'azameo/v1',
                    '/productsfeed(?:/(?P<currency>\d+))?(?:/(?P<sudo>\d+))?',
                    array(
                        'methods' => WP_REST_Server::READABLE, //'GET'
                        'callback' => 'AZAMEO_Plugin::api_feed',
                        'permission_callback' => '__return_true',
                        'args' => array(
                            'currency',
                        ),
                    )
                );
            });
            add_action('rest_api_init', function () {
                register_rest_route(
                    'azameo/v1',
                    '/orders',
                    array(
                        'methods' => WP_REST_Server::READABLE, //'GET'
                        'callback' => 'AZAMEO_Plugin::api_orders',
                        'permission_callback' => '__return_true',
                        'args' => array(
                            "token"
                        ),
                    )
                );
            });
            add_action('rest_api_init', function () {
                register_rest_route(
                    'azameo/v1',
                    '/info',
                    array(
                        'methods' => WP_REST_Server::READABLE, //'GET'
                        'callback' => 'AZAMEO_Plugin::api_info',
                        'permission_callback' => '__return_true',
                        'args' => array(
                            "token"
                        ),
                    )
                );
            });

        }

        private static function get_trackername()
        {
            $azameo_code = get_option('azameo_code');
            if (strlen($azameo_code) != 0) {
                return $azameo_code;
            }
            $sys_url = get_bloginfo('url');
            $blog_url = parse_url($sys_url);
            $trackerName = str_replace(".", "", $blog_url["host"]);
            if (substr($trackerName, 0, 3) === "www") {
                $trackerName = substr($trackerName, 3);
            }
            return $trackerName;
        }

        private static function get_register_url()
        {
            //Site global info
            $sys_url = get_bloginfo('url');
            $sys_email = get_bloginfo('admin_email');
            $trackerName = AZAMEO_Plugin::get_trackername();
            $register_url = 'http://dashboard.azameo.fr/register?email=' . urlencode($sys_email) . '&siteweb=' . urlencode($sys_url) . '&trackername=' . urlencode($trackerName) . '&origin=wordpress';
            return $register_url;
        }

        public static function hook_activation()
        {
            $sys_url = get_bloginfo('url');
            $sys_email = get_bloginfo('admin_email');
            $sys_host = parse_url($sys_url)["host"];
            $trackerName = AZAMEO_Plugin::get_trackername();

            $is_woocommerce = AZAMEO_Plugin::is_woocommerce() ? "true" : "false";

            // Always refresh the token, even if present
            $token = AZAMEO_Plugin::generate_random_token();
            update_option("azameo_token", $token);
            $activation_url = 'https://shopify.azameo.com/wordpressinstall?email=' . urlencode($sys_email) . '&domain=' . urlencode($sys_host) . '&tracker_name=' . urlencode($trackerName) . '&woocommerce=' . $is_woocommerce . '&token=' . $token;

            file_get_contents($activation_url);
        }

        public static function hook_deactivation()
        {
            $sys_url = get_bloginfo('url');
            $sys_host = parse_url($sys_url)["host"];
            $activation_url = 'https://shopify.azameo.com/wordpressuninstall?domain=' . urlencode($sys_host);

            file_get_contents($activation_url);
        }

        public static function action_upgrade($upgrader_object, $options)
        {
            // The path to our plugin's main file
            $our_plugin = plugin_basename(__FILE__);
            // If an update has taken place and the updated type is plugins and the plugins element exists
            if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {
                // Iterate through the plugins being updated and check if ours is there
                foreach ($options['plugins'] as $plugin) {
                    if ($plugin == $our_plugin) {
                        // Set a transient to record that our plugin has just been updated
                        AZAMEO_Plugin::hook_activation();
                    }
                }
            }

        }

        //azameo navigation tag
        public static function action_wp_head()
        {
            $trackerName = AZAMEO_Plugin::get_trackername();

            $azameo_tracker = '<script type="text/javascript">window.azameoSite="' . $trackerName . '";</script>';

            $azameo_tracker .= '<script type="text/javascript">
    (function() {
    var azameo = document.createElement("script"); azameo.type = "text/javascript";	azameo.async = true;
    azameo.src = ("https:" == document.location.protocol ? "https://" : "http://") + "tag.azame.net/tag/script.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(azameo, s);
    })();
</script>
<noscript><link href="https://tag.azame.net/tag/style.css" rel="stylesheet" media="all" type="text/css"> </noscript>';

            echo $azameo_tracker;

            //Test if woocommerce is active
            if (AZAMEO_Plugin::is_woocommerce()) {
                if (is_order_received_page()) {
                    AZAMEO_Plugin::process_order_markdown();
                }
            }
        }

        //azameo conversion tag
        private static function process_order_markdown()
        {
            global $wp;

            if (!is_order_received_page()) {
                exit('no order');
            }

            $order = wc_get_order($wp->query_vars['order-received']);

            // bail out if not a valid instance
            if (!is_a($order, 'WC_Order')) {
                exit('no valid order');
            }

            $order_currency = $order->get_order_currency();
            $order_total = $order->get_total();
            $order_number = $order->get_order_number();
            $order_subtotal = $order->get_subtotal();


            $azameo_conversion_tracker = '<script type="text/javascript">
          window.azameoTagEvent = {
                   name : "checkout",
                   ref : "' . $order_number . '",
                   price : "' . $order_total . '",
                   tax : "0",
                   shipping: "0",
                   type : "cart",
                   sequence: "validation"
                   };
          window.azameoCart = window.azameoCart || [];
          if(window.azameoTag)
                   azameoTag.Conversion();
    </script>';

            echo $azameo_conversion_tracker;
        }

        /**
         * Include CF7 tracking
         */
        public static function action_wp_footer()
        {

            $azameo_lead_tracker = '<script type="text/javascript">
		document.addEventListener("wpcf7mailsent", function(event){
    		window.azameoTagEvent = {
                   name : "contactForm7",
                   category : "contact",
                   ref : "lead_" + new Date().getTime(),
                   type : "lead"
            };
          	if(window.azameoTag)
                   azameoTag.Conversion();
        },false);
    </script>';

            echo $azameo_lead_tracker;
        }

        /* WordPress Menus API. */
        public static function action_admin_menu()
        {
            //add a new menu item. This is a top level menu item i.e., this menu item can have sub menus
            add_menu_page(
                "Azameo Retargeting", //Required. Text in browser title bar when the page associated with this menu item is displayed.
                "Azameo", //Required. Text to be displayed in the menu.
                "manage_options", //Required. The required capability of users to access this menu item.
                "azameo-options", //Required. A unique identifier to identify this menu item.
                "AZAMEO_Plugin::menu_azameo", //Optional. This callback outputs the content of the page associated with this menu item.
                "", //Optional. The URL to the menu item icon.
                100 //Optional. Position of the menu item in the menu.
            );
        }

        public static function menu_azameo()
        {
            $register_url = AZAMEO_Plugin::get_register_url();
            echo '
    <style type="text/css">
        .tg  {border-collapse:separate;border-spacing:50px 0px;}
        .tg td{overflow:hidden;word-break:normal;}
        .tg th{font-weight:normal;padding:10px 5px;overflow:hidden;word-break:normal;}
        .tg .tg-baqh{text-align:center;vertical-align:top;background-color:#1e6abc;padding:15px;color:white;font-weight:bold;width: 150px}
        .tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}
        a{color:white;text-decoration: none;}
        button:hover{cursor: pointer}
    </style>

    <div style="background-color:white;padding:35px;margin: 25px 50px 0px 25px">
        <div id="icon-options-general" class="icon32"></div>
        <h1>' . __('Welcome to Azameo', 'azaflash-retargeting') . '</h1>
        <p>' . __("This plugin let you connect seamlessly with the Azameo advertising platform.", 'azaflash-retargeting') . '</p>
        <table class="tg">
          <tr>
            <th class="tg-amwm">' . __("New to Azameo?", 'azaflash-retargeting') . '</th>
            <th class="tg-amwm">' . __("Already a user?", 'azaflash-retargeting') . '</th>
          </tr>
          <tr>
            <td><a href="' . $register_url . '" target="_blank"><button class="tg-baqh">' . __("Register for free!", 'azaflash-retargeting') . '</button></a></td>
            <td><a href="https://dashboard.azameo.fr/login" target="_blank"><button class="tg-baqh">' . __("Login", 'azaflash-retargeting') . '</button></a></td>
          </tr>
        </table>';


            //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
            settings_fields("azameo_section");

            echo '</div>';
        }

        public static function action_admin_init()
        {
            //section name, display name, callback to print description of section, page to which section is attached.
            add_settings_section("azameo_section", "Azameo advanced settings", "display_azameo_options_content", "azameo-options");

            //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
            //last field section is optional.
            add_settings_field("azameo_code", "Azameo site ID", "AZAMEO_Plugin::settings_azameo_code", "azameo-options", "azameo_section");

            //section name, form element name, callback for sanitization
            register_setting("azameo_section", "azameo_code");
        }


        public static function action_plugins_loaded()
        {
            load_plugin_textdomain('azaflash-retargeting', FALSE, basename(dirname(__FILE__)) . '/languages/');
        }

        public static function settings_azameo_code()
        {
            $trackerName = AZAMEO_Plugin::get_trackername();
            echo '<input type="text" name="azameo_code" id="azameo_code" value="' . $trackerName . '" />';

        }


        /**
         * convert the product in an usefull way
         * Since 1.5.0
         * @param bool $currency
         * @return array
         */
        private static function convert_product($product, $currency)
        {
            $new_product = [];

            if ($product->get_sale_price() == null && $product->get_regular_price() == null) {
                $new_product["price"] = $product->get_price();
                $new_product["sale_price"] = $product->get_price();
            } else {
                if ($product->get_sale_price() == null || $product->get_sale_price() == '') {
                    $new_product["price"] = $product->get_regular_price();
                } else {
                    $new_product["price"] = $price = $product->get_sale_price();
                }

                if ($product->get_regular_price() == null || $product->get_regular_price() == '') {
                    $new_product["sale_price"] = $product->get_sale_price();
                } else {
                    $new_product["sale_price"] = $product->get_regular_price();
                }
            }
            $new_product["shipping"] = 0;
            if ($currency) {
                $new_product["price"] .= ' EUR';
                $new_product["sale_price"] .= ' EUR';
                $new_product["shipping"] .= ' EUR';
            }
            $new_product["date_created"] = $product->get_date_created();
            $new_product["id"] = $product->get_id();
            $new_product["name"] = $product->get_name();
            $new_product["link"] = get_permalink($product->get_id());
            // old versions of woocommerce don't have this function
            if (function_exists("wp_get_original_image_url")) {
                $new_product["image_link"] = wp_get_original_image_url($product->get_image_id());
            } else {
                $new_product["image_link"] = wp_get_attachment_image_url($product->get_image_id(), 'Full Size');
            }
            if ($product->get_short_description() == null || $product->get_short_description() == "") {
                $new_product["description"] = $new_product["name"];
            } else {
                $new_product["description"] = html_entity_decode(strip_tags($product->get_short_description()), ENT_QUOTES, 'UTF-8');
            }
            if ($product->get_manage_stock()) {
                if (($product->get_stock_status() == 'instock') || ($product->get_backorders() != "no")) {
                    $new_product["availability"] = 'in stock';
                } else {
                    $new_product["availability"] = 'out of stock';
                }
            } else {
                if ($product->get_stock_status() == 'instock') {
                    $new_product["availability"] = 'in stock';
                } else {
                    $new_product["availability"] = 'out of stock';
                }
//                      $new_product["availability"] = 'in stock';
            }
            return $new_product;
        }

        /**
         * Get the list of all products and process them for ease of display
         * Since 1.4.0
         * @param bool $currency
         * @return array
         */
        private static function get_list_products($currency)
        {
            if (!AZAMEO_Plugin::is_woocommerce()) {
                return [];
            }

            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1, // -1 all posts in 1 page
            );

            $loop = new WP_Query($args);
            $list_product = [];
            while ($loop->have_posts()) : $loop->the_post();
                $product = wc_get_product($loop->post->ID);
                $new_product = AZAMEO_Plugin::convert_product($product, $currency);

                // append to the end
                $list_product[] = $new_product;

            endwhile;
            return $list_product;
        }

        /**
         * Get the list of all products and process them for ease of display
         * Since 1.5.0
         * @param bool $currency
         * @param int $page
         * @param int $posts_per_page
         * @return array
         */
        private static function get_list_products_page($currency, $page, $posts_per_page)
        {
            if (!AZAMEO_Plugin::is_woocommerce()) {
                return [];
            }

            $args = array(
                'limit' => $posts_per_page,
                'page' => $page,
                'orderby' => "ID",
                'order' => "ASC",
                'paginate' => true,
            );

            $loop = wc_get_products($args);

            $list_product = [];

            foreach ($loop->products as $product) {
                $new_product = AZAMEO_Plugin::convert_product($product, $currency);
                // append to the end
                $list_product[] = $new_product;

            }
            $data = [];
            $data["products"] = $list_product;
            $data["max_num_pages"] = $loop->max_num_pages;
            return $data;
        }

        /**
         * Get the list of all products and process them for ease of display
         * Since 1.5.0
         * @param int $timestamp
         * @param int $limit
         * @return array
         */
        private static function get_list_products_timestamp($timestamp, $limit)
        {
            if (!AZAMEO_Plugin::is_woocommerce()) {
                return [];
            }

            $list_product = [];

            if ($timestamp != 0){
                //first get all product for this timstamp
                $args = array(
                    'date_created' => $timestamp,
                    'orderby' => "date_created",
                    'order' => "ASC",
                    'limit' => -1,
                    'status' => 'publish',
                );

                $loop = wc_get_products($args);


                if (count($loop) > 0) {
                    foreach ($loop as $product) {
                        $new_product = AZAMEO_Plugin::convert_product($product, false);
                        // append to the end
                        $list_product[] = $new_product;
                    }
                }
            }

            // then get at least one product from next timestamp, complete the nb of required product if needed
            $args = array(
                'limit' => max(1, $limit - count($list_product)),
                'date_created' => ">" . $timestamp,
                'orderby' => "date_created",
                'order' => "ASC",
                'status' => 'publish',
            );

            $loop = wc_get_products($args);

            if (count($loop) > 0) {
                foreach ($loop as $product) {
                    $new_product = AZAMEO_Plugin::convert_product($product, false);
                    // append to the end
                    $list_product[] = $new_product;

                }
            }

            // get max timestamp of all product
            if (count($list_product) > 0) {
                $max_timestamp = $list_product[count($list_product) - 1]["date_created"]->getTimestamp();
            } else {
                $max_timestamp = 0;
            }
            $data = [];
            $data["nb"] = count($list_product);
            $data["timestamp"] = $timestamp;
            $data["limit"] = $limit;
            $data["max_timestamp"] = $max_timestamp;
            $data["products"] = $list_product;
            return $data;
        }

        private static function is_woocommerce()
        {
            if (!function_exists('is_plugin_active')) {
                include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            if ( is_multisite() ) {
                if ( is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
                    $woo_installed = is_plugin_active_for_network('woocommerce/woocommerce.php') ? true : false;
                } else {
                    $woo_installed = is_plugin_active( 'woocommerce/woocommerce.php')  ? true : false;
                }
            } else {
                $woo_installed =  is_plugin_active( 'woocommerce/woocommerce.php') ? true : false;
            }
            return $woo_installed;
        }

        private static function generate_random_token($length = 20)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        private static function check_token($request)
        {
            $token = $request['token'];
            $db_token = get_option("azameo_token");
            if ($token !== $db_token) {
                $output = [
                    "error" => "no access",
                    "status" => "FAIL"
                ];
                if ($db_token == false) {
                    update_option("azameo_token", AZAMEO_Plugin::generate_random_token());
                    $db_token = get_option("azameo_token");
                    $output["token"] = $token;
                    $output["db_token"] = $db_token;
                }
                wp_send_json($output);
                exit();
            }
        }

        /**
         * When the API is called it will send the azameo plugin version, protected api, only azameo will call it to setup campaigns
         * Since 1.4.0
         * @param WP_REST_Request $request
         * @return string
         */
        public static function api_info(WP_REST_Request $request)
        {
            global $wp_version;
            AZAMEO_Plugin::check_token($request);
            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }
            $plugin_data = get_plugin_data(__FILE__);
            $is_woocommerce = AZAMEO_Plugin::is_woocommerce();
            $sys_url = get_bloginfo('url');
            $sys_email = get_bloginfo('admin_email');
            $output = [
                "version" => [
                    "azameo" => $plugin_data["Version"],
                    "wordpress" => $wp_version,
                ],
                "woocommerce" => $is_woocommerce,
                "url" => $sys_url,
                "email" => $sys_email
            ];
            if ($is_woocommerce) {
                global $woocommerce;
                $output["version"]["woocommerce"] = $woocommerce->version;
                $output["currency"] = get_woocommerce_currency();

            }
            wp_send_json($output);
        }


        /**
         * When the API is called it will list the lst orders, protected api, only azameo will call it to setup campaigns
         * Since 1.4.0
         * @param WP_REST_Request $request
         * @return string
         */
        public static function api_orders(WP_REST_Request $request)
        {
            AZAMEO_Plugin::check_token($request);
            if (!AZAMEO_Plugin::is_woocommerce()) {
                // No need to continue woocommerce is not installed
                wp_send_json([]);
            }
            $args = array(
                'limit' => 250,
                'return' => 'ids',
                'status' => 'completed'
            );
            $query = new WC_Order_Query($args);
            $orders = $query->get_orders();
            $list_order = [];

            foreach ($orders as $order_id) {
                $order = wc_get_order($order_id);
                $new_order = [];
                $new_order["billing"] = [];
                $new_order["billing"]["email"] = $order->get_billing_email();
                $new_order["billing"]["first_name"] = $order->get_billing_first_name();
                $new_order["billing"]["last_name"] = $order->get_billing_last_name();
                $new_order["billing"]["address_1"] = $order->get_billing_address_1();
                $new_order["billing"]["address_2"] = $order->get_billing_address_2();
                $new_order["billing"]["postcode"] = $order->get_billing_postcode();
                $new_order["billing"]["state"] = $order->get_billing_state();
                $new_order["billing"]["country"] = $order->get_billing_country();
                $new_order["billing"]["phone"] = $order->get_billing_phone();
                $new_order["date_add"] = $order->get_date_created();
                $new_order["date_upd"] = $order->get_date_modified();
                $new_order["id_order"] = $order->get_id();
                $new_order["id_cart"] = $order->get_cart_hash();
                $new_order["total_paid"] = $order->get_total();
                $new_order["total_products"] = $order->get_total() - $order->get_shipping_total();
                $new_order["total_shipping"] = $order->get_shipping_total();
                $new_order["currency"] = $order->get_currency();

                $items = $order->get_items();
                $new_order["products"] = [];
                foreach ($items as $item) {

                    $new_product = [];
                    $new_product["id_product"] = $item->get_product_id();
                    $new_product["product_id"] = $item->get_product_id() . '-' . $item->get_variation_id();
                    $new_product["name"] = $item->get_name();
                    $new_product["quantity"] = $item->get_quantity();
                    $new_product["price"] = $item->get_total() - $item->get_total_tax();
                    $new_product["price_with_tax"] = $item->get_total();

                    $new_order["products"][] = $new_product;
                }

                $list_order[] = $new_order;
            }

            wp_send_json($list_order);

        }

        /**
         * When the API is called (once by day max) build a XML file with products data
         * Since 1.2.2
         * @param WP_REST_Request $request
         * @return string
         */
        public static function api_feed(WP_REST_Request $request)
        {

            try {

                ($request['sudo'] === 'true') ? $sudo = true : $sudo = false; // forced xml file update
                ($request['currency'] === 'true') ? $currency = true : $currency = false; // to display (or not) currencies
                $file = wp_upload_dir()['basedir'] . '/xmlproductsfeed.xml';

                //if file never generate or if now is after the file modification authorization date, do the xml file
                if (!file_exists($file) || time() > strtotime('+1 day', filemtime($file)) || $sudo == true) {
                    $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<rss xmlns:g="http://base.google.com/ns/1.0">
    <channel>
        <title>' . get_bloginfo_rss("name") . '</title>
        <link>' . get_bloginfo_rss("wpurl") . '</link>';

                    $list_product = AZAMEO_Plugin::get_list_products($currency);
                    foreach ($list_product as $product) {

                        $xml .= '
        <item>
            <g:id>' . $product["id"] . '</g:id>
            <g:title><![CDATA[' . $product["name"] . ']]></g:title>
            <g:link>' . $product["link"] . '</g:link>
            <g:image_link>' . $product["image_link"] . '</g:image_link> 
            <g:condition>' . 'new' . '</g:condition>
            <g:availability>' . $product["availability"] . '</g:availability>
            <g:price>' . $product["price"] . '</g:price>
            <g:sale_price>' . $product["sale_price"] . '</g:sale_price>
            <g:description><![CDATA[' . $product["description"] . ']]></g:description>
            <g:mpn>mpm' . $product["id"] . '</g:mpn>
            <g:brand><![CDATA[' . get_bloginfo_rss('name') . ']]></g:brand>
            <g:shipping><g:price>' . $product["shipping"] . '</g:price></g:shipping>
        </item>';
                    }
                    $xml .= '
    </channel>
</rss>';

                    // save the content in a file for cache
                    $open = fopen($file, 'w');
                    fputs($open, $xml);
                    fclose($open);
                } else {
                    // The file exist and is not too old, use it
                    $open = fopen($file, 'r');
                    $xml = fread($open, filesize($file));
                    fclose($open);
                }

                // Change content type to xml
                header('Content-type: text/xml');
                // display the constructed xml
                echo $xml;
                exit();
            } catch (ErrorException $e) {
                return 'error : ' . $e;
            }

        }

        /**
         * When the API is called (once by day max) build a json with products data
         * Since 1.5.0
         * @param WP_REST_Request $request
         * @return string
         */
        public static function api_feed_json(WP_REST_Request $request)
        {

            try {
                header('Content-type: text/html');

                $timestamp = intval($request['timestamp']);
                $limit = intval($request['limit']);
                if (!$limit) {
                    $limit = 20;
                }
                $list_product = AZAMEO_Plugin::get_list_products_timestamp($timestamp, $limit);

                wp_send_json($list_product);

            } catch (ErrorException $e) {
                return 'error : ' . $e;
            }

        }

    }

    AZAMEO_Plugin::init();
}




