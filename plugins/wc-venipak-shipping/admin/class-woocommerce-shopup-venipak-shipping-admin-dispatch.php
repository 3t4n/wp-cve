<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://shopup.lt/
 * @since      1.0.0
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Shopup_Venipak_Shipping
 * @subpackage Woocommerce_Shopup_Venipak_Shipping/admin
 * @author     ShopUp <info@shopup.lt>
 */
class Woocommerce_Shopup_Venipak_Shipping_Admin_Dispatch {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     *
     *
     * @since    1.0.0
     */
    private $settings;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_username;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_password;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_userid;

    /**
     *
     *
     * @since    1.15.0
     */
    private $venipak_manifest;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_first_pack_number;

    private $venipak_return_service;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_name;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_company_code;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_country;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_city;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_address;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_post_code;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_contact_person;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_contact_tel;

    /**
     *
     *
     * @since    1.0.0
     */
    private $venipak_sender_contact_email;

    /**
     *
     *
     * @since    1.5.6
     */
    private $venipak_is_status_change_disabled;

    /**
     *
     *
     * @since    1.5.8
     */
    private $shopup_venipak_shipping_field_maxpackproducts;

    /**
     *
     *
     * @since    1.13.0
     */
    private $shopup_venipak_shipping_field_forcedispatch;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $settings ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = $settings;
        $this->venipak_userid = $settings->get_option_by_key('shopup_venipak_shipping_field_userid');
        $this->venipak_username = $settings->get_option_by_key('shopup_venipak_shipping_field_username');
        $this->venipak_password = $settings->get_option_by_key('shopup_venipak_shipping_field_password');
        $this->venipak_manifest = $settings->get_option_by_key('shopup_venipak_shipping_field_manifest') ?: '001';
        $this->venipak_first_pack_number = $settings->get_option_by_key('shopup_venipak_shipping_field_firstpacknumber');
        $this->venipak_return_service = intval($settings->get_option_by_key('shopup_venipak_shipping_field_return_service'));
        $this->venipak_sender_name = $settings->get_option_by_key('shopup_venipak_shipping_field_sendername');
        $this->venipak_sender_company_code = $settings->get_option_by_key('shopup_venipak_shipping_field_sendercompanycode');
        $this->venipak_sender_country = $settings->get_option_by_key('shopup_venipak_shipping_field_sendercountry');
        $this->venipak_sender_city = $settings->get_option_by_key('shopup_venipak_shipping_field_sendercity');
        $this->venipak_sender_address = $settings->get_option_by_key('shopup_venipak_shipping_field_senderaddress');
        $this->venipak_sender_post_code = $settings->get_option_by_key('shopup_venipak_shipping_field_senderpostcode');
        $this->venipak_sender_contact_person = $settings->get_option_by_key('shopup_venipak_shipping_field_sendercontactperson');
        $this->venipak_sender_contact_tel = $settings->get_option_by_key('shopup_venipak_shipping_field_sendercontacttel');
        $this->venipak_sender_contact_email = $settings->get_option_by_key('shopup_venipak_shipping_field_sendercontactemail');
        $this->venipak_is_status_change_disabled = $settings->get_option_by_key('shopup_venipak_shipping_field_isstatuschangedisabled');
        $this->shopup_venipak_shipping_field_maxpackproducts = intval($settings->get_option_by_key('shopup_venipak_shipping_field_maxpackproducts'));
        $this->shopup_venipak_shipping_field_forcedispatch = $settings->get_option_by_key('shopup_venipak_shipping_field_forcedispatch');

    }

    /**
     *
     *
     * @since    1.0.0
     */
    public function add_venipak_shipping_bulk_action_process( $redirect_to, $action, $post_ids ) {
        if ( $action === 'shopup_venipak_shipping_dispatch' ) {
            foreach ($post_ids as $order_id) {
                $this->venipak_shipping_dispatch_order([$order_id], false, false);
            }
        }
        return $redirect_to;
    }

    /**
     *
     *
     * @since    1.0.0
     */
    public function add_venipak_shipping_dispatch_force() {
        $order_id = intval( $_POST['order_id'] );
        $order = wc_get_order($order_id);
        $order_data = $order->get_meta('venipak_shipping_order_data', true);
        $venipak_shipping_order_data = json_decode($order_data, true);
        $venipak_shipping_order_data['status'] = '';
        $venipak_shipping_order_data['pack_numbers'] = [];
        $order->update_meta_data('venipak_shipping_order_data', json_encode($venipak_shipping_order_data));
        $order->save();
        $this->add_venipak_shipping_dispatch();
    }

    /**
     *
     *
     * @since    1.0.0
     */
    public function add_venipak_shipping_dispatch() {
        $order_id = intval( $_POST['order_id'] );
        $packs = $_POST['packs'];
        $is_global = array_key_exists('is_global', $_POST) ? $_POST['is_global'] : false;
        $result = $this->venipak_shipping_dispatch_order([$order_id], $packs, $is_global);

        if (!$result) wp_die();

        if ($result['status'] === 'ok') {
            $pack_numbers = $result['data']['pack_numbers'];
            $pack_numbers_string = implode(', ', $pack_numbers);
            $content = '<div class="venipak-shipping-pack"><a class="button button-primary" title="' . $pack_numbers_string . '" target="_blank" href="' . admin_url('admin-ajax.php') . '?action=woocommerce_shopup_venipak_shipping_get_label_pdf&order_id=' . $order_id . '">' . sprintf( __( 'Labels (%s)', 'woocommerce-shopup-venipak-shipping' ), sizeof($pack_numbers)) . '</a> <a class="button button-primary" target="_blank" href="' . admin_url('admin-ajax.php') . '?action=woocommerce_shopup_venipak_shipping_get_manifest_pdf&order_id=' . $order_id . '">' . __( 'Manifest', 'woocommerce-shopup-venipak-shipping' ) . '</a></div>';
            echo $content;
        } else {
            echo $result['data'];
        }
        wp_die();
    }

    /**
     *
     *
     * @since    1.0.0
     */
    public function venipak_shipping_dispatch_order($order_ids, $packs, $is_global) {
        $url = 'https://go.venipak.lt/import/send.php';
        $xml = $this->getImportXML($order_ids, $packs, $is_global);
        if (!$xml) return null;
        $body = array('user' => $this->venipak_username, 'pass' => $this->venipak_password, 'xml_text' => $xml);
        $args = array(
            'body' => $body,
            'timeout'     => 45,
            'headers' => array(
                'Referer' => 'https://woocommerce.com/',
            ),
        );
        $response = wp_remote_post( $url, $args );
        $result_xml_string = wp_remote_retrieve_body( $response );
        if (strpos($result_xml_string, 'type="ok"') !== false) {
            foreach ($order_ids as $order_id) {
                $order = wc_get_order( $order_id );
                if (!$this->venipak_is_status_change_disabled)  {
                    $order->update_status('completed', __( 'Order dispatched to Venipak', 'woocommerce-shopup-venipak-shipping' ));
                }

                $venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data', true), true);
                $venipak_shipping_order_data['status'] = 'sent';
                $venipak_shipping_order_data['error_message'] = '';
                $order->update_meta_data('venipak_shipping_order_data', json_encode($venipak_shipping_order_data));
                $order->save();
            }
            return array('status' => 'ok', 'data' => $venipak_shipping_order_data);
        } else {
            $order = wc_get_order( $order_ids[0] );
            $venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data', true), true);
            $venipak_shipping_order_data['status'] = 'error';
            $venipak_shipping_order_data['error_message'] = strip_tags($result_xml_string);
            $order->update_meta_data('venipak_shipping_order_data', json_encode($venipak_shipping_order_data));
            $order->save();
            return array('status' => 'error', 'data' => $venipak_shipping_order_data['error_message']);
        }
    }

    /**
     *
     *
     * @since    1.0.0
     */
    public function getImportXML($order_ids, $packs, $is_global_force) {
        date_default_timezone_set('Europe/Vilnius');
        $client_id = $this->venipak_userid;
        $last_pack_nr = intval($this->venipak_first_pack_number);
        $venipak_return_service =  $this->venipak_return_service;
        $document = new \DOMDocument( '1.0', 'utf-8' );
        // descriotion
        $description = $document->createElement( 'description' );
        $description_attr = $document->createAttribute('type');
        $description_attr->value = "1";
        $description->appendChild($description_attr);
        $document->appendChild($description);
        //manifest
        $manifest = $document->createElement( 'manifest' );
        $manifest_attr = $document->createAttribute('title');
        $manifest_value = $client_id . date('ymd') . $this->venipak_manifest;
        $manifest_attr->value = $manifest_value;
        $manifest->appendChild($manifest_attr);
        $description->appendChild($manifest);
        $xml_not_empty = false;
        $pickup_collection = venipak_fetch_pickups();



        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            $shipping_method = @array_shift($order->get_shipping_methods());
            $shipping_method_id = $shipping_method['method_id'];
            if (!$this->shopup_venipak_shipping_field_forcedispatch && $shipping_method_id !== 'shopup_venipak_shipping_courier_method' && $shipping_method_id !== 'shopup_venipak_shipping_pickup_method') continue;

            $payment_method = $order->get_payment_method();
            $venipak_shipping_order_data = json_decode($order->get_meta('venipak_shipping_order_data'), true);
            $venipak_pickup_point = false;

            if (!$venipak_shipping_order_data) {
                $venipak_shipping_order_data = [];
            } else {
                if ($venipak_shipping_order_data['status'] === 'sent') { continue; };
            }

            $pickup_point_data = $order->get_meta('venipak_pickup_point');
            if (is_numeric($pickup_point_data)) {
                foreach ($pickup_collection as $key => $value) {
                    if ($value['id'] == $pickup_point_data) {
                        $venipak_pickup_point = $value;
                        break;
                    }
                }
            }

            $order_products = [];
            $min_age = 0;
            $shipment_description_text = '';
            $prev_title = 'no-repeat';
            foreach ( $order->get_items() as $item_id => $product_item ) {
                $product = $product_item->get_product();
                if (!$product) continue;
                if ($prev_title !== $product->get_title()) {
                    $shipment_description_text .= $product->get_title() . PHP_EOL;
                    $prev_title = $product->get_title();
                }
                $product_min_age = $product->get_meta('shopup_venipak_shipping_min_age');
                if ($product_min_age && $product_min_age > $min_age) {
                    $min_age = $product_min_age;
                }
                $product_quantity = $product_item->get_quantity();
                for ($i = 0; $i < $product_quantity; $i++) {
                    $order_products[] = $product;
                }
            }

            $shipment = $document->createElement( 'shipment' );
            $manifest->appendChild($shipment);

            $sender = $document->createElement( 'sender' );
            $shipment->appendChild($sender);

            $sender_name = $document->createElement( 'name', $this->venipak_sender_name );
            $sender_company_code = $document->createElement( 'company_code',  $this->venipak_sender_company_code );
            $sender_country = $document->createElement( 'country', $this->venipak_sender_country );
            $sender_city = $document->createElement( 'city', $this->venipak_sender_city );
            $sender_address = $document->createElement( 'address', $this->venipak_sender_address );
            $sender_post_code = $document->createElement( 'post_code', preg_replace('/\D/', '', $this->venipak_sender_post_code));
            $contact_person = $document->createElement( 'contact_person', $this->venipak_sender_contact_person );
            $contact_tel = $document->createElement( 'contact_tel', $this->venipak_sender_contact_tel );
            $contact_email = $document->createElement( 'contact_email', $this->venipak_sender_contact_email );

            $sender->appendChild($sender_name);
            $sender->appendChild($sender_company_code);
            $sender->appendChild($sender_country);
            $sender->appendChild($sender_city);
            $sender->appendChild($sender_address);
            $sender->appendChild($sender_post_code);
            $sender->appendChild($contact_person);
            $sender->appendChild($contact_tel);
            $sender->appendChild($contact_email);

            $consignee = $document->createElement( 'consignee' );
            $shipment->appendChild($consignee);

            if ($venipak_pickup_point) {
                $name = $document->createElement( 'name', $venipak_pickup_point['name'] );
                $company_code = $document->createElement( 'company_code',  $venipak_pickup_point['code'] );
                $consignee->appendChild($company_code);
                $country = $document->createElement( 'country', $venipak_pickup_point['country'] );
                $city = $document->createElement( 'city', $venipak_pickup_point['city'] );
                $address = $document->createElement( 'address', $venipak_pickup_point['address'] );
                $post_code = $document->createElement( 'post_code', preg_replace('/\D/', '', $venipak_pickup_point['zip']));
            } else {
                $billing_company = $order->get_billing_company();
                $name_value = $billing_company ? $billing_company : $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name();
                $name = $document->createElement( 'name', $name_value );
                $country_value = $order->get_shipping_country() ? $order->get_shipping_country() : $order->get_billing_country();
                $country = $document->createElement( 'country', $country_value );
                $city = $document->createElement( 'city', $order->get_shipping_city() );
                $address = $document->createElement( 'address', $order->get_shipping_address_1() . ' - ' . $order->get_shipping_address_2() );
                $post_code = $document->createElement( 'post_code', preg_replace('/\D/', '', $order->get_shipping_postcode()) );

                $comment_door_code = $document->createElement( 'comment_door_code', $order->get_meta('venipak_door_code') );
                $comment_office_no = $document->createElement( 'comment_office_no', $order->get_meta('venipak_office_no') );

                $venipak_delivery_time = $min_age  > 0 ? 'nwd14_17' : $order->get_meta('venipak_delivery_time');
                $delivery_type = $document->createElement( 'delivery_type', $venipak_delivery_time );
            }

            $consignee->appendChild($name);
            $consignee->appendChild($country);
            $consignee->appendChild($city);
            $consignee->appendChild($address);
            $consignee->appendChild($post_code);

            $contact_person = $document->createElement( 'contact_person', $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() );
            $consignee->appendChild($contact_person);
            $contact_tel_data = $order->get_billing_phone();
            $contact_tel = $document->createElement( 'contact_tel', $contact_tel_data );
            $consignee->appendChild($contact_tel);
            $contact_email = $document->createElement( 'contact_email', $order->get_billing_email() );
            $consignee->appendChild($contact_email);

            $attribute = $document->createElement( 'attribute' );
            $shipment->appendChild($attribute);

            if (!$venipak_pickup_point) {
                $attribute->appendChild($comment_door_code);
                $attribute->appendChild($delivery_type);
                $attribute->appendChild($comment_office_no);
                $comment_call = $document->createElement( 'comment_call', '1' );
                $attribute->appendChild($comment_call);
            }

            $shipment_code = $document->createElement( 'shipment_code', $order->get_id() );
            $attribute->appendChild($shipment_code);
            $doc_no = $document->createElement( 'doc_no', $order->get_id() );
            $attribute->appendChild($doc_no);

            if ($payment_method === 'cod') {
                $cod = $document->createElement( 'cod', $order->get_total() );
                $attribute->appendChild($cod);
                $cod_type = $document->createElement( 'cod_type', 'EUR' );
                $attribute->appendChild($cod_type);
            }

            $comment_text = $document->createElement( 'comment_text', $order->get_customer_note() );
            $attribute->appendChild($comment_text);

            if ($min_age > 0) {
                $min_age_node = $document->createElement( 'min_age', $min_age );
                $attribute->appendChild($min_age_node);
            }

            if ($venipak_return_service > 0) {
                $return_service = $document->createElement('return_service', $venipak_return_service);
                $return_consignee = $document->createElement('return_consignee');
                    $return_doc_consignee_name = $document->createElement('name', $this->venipak_sender_name );
                    $return_doc_consignee_company_code = $document->createElement( 'company_code',  $this->venipak_sender_company_code );
                    $return_doc_consignee_country = $document->createElement( 'country', $this->venipak_sender_country );
                    $return_doc_consignee_city = $document->createElement( 'city', $this->venipak_sender_city );
                    $return_doc_consignee_address = $document->createElement( 'address', $this->venipak_sender_address );
                    $return_doc_consignee_post_code = $document->createElement( 'post_code', preg_replace('/\D/', '', $this->venipak_sender_post_code));
                    $return_doc_consignee_contact_person = $document->createElement( 'contact_person', $this->venipak_sender_contact_person );
                    $return_doc_consignee_contact_tel = $document->createElement( 'contact_tel', $this->venipak_sender_contact_tel );

                    $return_consignee->appendChild($return_doc_consignee_name);
                    $return_consignee->appendChild($return_doc_consignee_company_code);
                    $return_consignee->appendChild($return_doc_consignee_country);
                    $return_consignee->appendChild($return_doc_consignee_city);
                    $return_consignee->appendChild($return_doc_consignee_address);
                    $return_consignee->appendChild($return_doc_consignee_post_code);
                    $return_consignee->appendChild($return_doc_consignee_contact_person);
                    $return_consignee->appendChild($return_doc_consignee_contact_tel);
                    $return_consignee->appendChild($return_doc_consignee_name);
                $shipment->appendChild($return_consignee);   
                $attribute->appendChild($return_service);           
            }

            $local_delivery_countries = array("LT", "EE", "LV");
            $is_global = $is_global_force || !in_array($this->venipak_sender_country, $local_delivery_countries);
            if ($is_global) {
                $global = $document->createElement( 'global' );
                $attribute->appendChild($global);
                $global_delivery = $document->createElement( 'global_delivery', 'global' );
                $global->appendChild($global_delivery);
                $shipment_description = $document->createElement( 'shipment_description', $shipment_description_text );
                $global->appendChild($shipment_description);
                $value = $document->createElement( 'value',  $order->get_total());
                $global->appendChild($value);
            }
            $pack_numbers = [];
            $total_weight = 0;
            if ($packs) {
                for ($i = 0; $i < sizeof($packs); $i++) {
                    $pack = $document->createElement( 'pack' );
                    $last_pack_nr = intval($last_pack_nr) + 1;
                    $pack_nr = $this->settings->format_pack_number($last_pack_nr);
                    $pack_no = $document->createElement( 'pack_no', $pack_nr );
                    $pack->appendChild($pack_no);
                    $packs[$i]['weight'] = max($packs[$i]['weight'], 0.1);
                    if ($is_global) {
                        $width = $document->createElement( 'width', $packs[$i]['width'] );
                        $pack->appendChild($width);
                        $height = $document->createElement( 'height', $packs[$i]['height'] );
                        $pack->appendChild($height);
                        $length = $document->createElement( 'length', $packs[$i]['length'] );
                        $pack->appendChild($length);
                    } else {
                        $volume = $document->createElement( 'volume', $packs[$i]['length'] * $packs[$i]['height'] * $packs[$i]['width'] );
                        $pack->appendChild($volume);
                    }
                    if ($is_global || $packs[$i]['weight'] > 0) {
                        $weight = $document->createElement( 'weight', $packs[$i]['weight'] );
                        $pack->appendChild($weight);
                    }
                    if ($packs[$i]['width'] > 0 && $packs[$i]['height'] > 0 && $packs[$i]['length'] > 0) {
                        $volume = $document->createElement( 'volume', $packs[$i]['width'] * $packs[$i]['length'] * $packs[$i]['height'] );
                        $pack->appendChild($volume);
                    }
                    if ($is_global || $packs[$i]['description'] != '') {
                        $description = $document->createElement( 'description', htmlspecialchars($packs[$i]['description']) );
                        $pack->appendChild($description);
                    }
                    $shipment->appendChild($pack);
                    $pack_numbers[] = $pack_nr;
                    $total_weight += $packs[$i]['weight'];
                }
            } else {
                $pack_count = $venipak_pickup_point ? 1 : ceil(sizeof($order_products) / $this->shopup_venipak_shipping_field_maxpackproducts);
                $venipak_products_per_pack = $this->shopup_venipak_shipping_field_maxpackproducts;

                for ($i = 0; $i < $pack_count; $i++) {
                    $last_pack_nr = intval($last_pack_nr) + 1;
                    $pack_nr = $this->settings->format_pack_number($last_pack_nr);
                    $pack = $document->createElement( 'pack' );
                    $pack_no = $document->createElement( 'pack_no', $pack_nr );
                    $pack->appendChild($pack_no);

                    $range_from = $i * $venipak_products_per_pack;
                    $range_to = $range_from + $venipak_products_per_pack;
                    $pack_weight = 0;
                    $pack_description = '';
                    $prev_title = 'no-repeat';
                    for ($y = $range_from; $y < $range_to; $y++) {
                        if (!array_key_exists($y, $order_products)) break;
                        $pack_weight += $this->get_product_weight($order_products[$y]);
                        if ($prev_title !== $order_products[$y]->get_title()) {
                            $pack_description .= $order_products[$y]->get_title() . PHP_EOL;
                            $prev_title = $order_products[$y]->get_title();
                        }
                    }
                    $pack_weight = max(wc_get_weight($pack_weight, 'kg'), 0.1);
                    $weight = $document->createElement( 'weight', $pack_weight );
                    $pack->appendChild($weight);
                    $description = $document->createElement( 'description', htmlspecialchars($pack_description) );
                    $pack->appendChild($description);
                    $shipment->appendChild($pack);
                    $pack_numbers[] = $pack_nr;
                    $total_weight += $pack_weight;
                }
            }
            $venipak_shipping_order_data['manifest'] = $manifest_value;
            $venipak_shipping_order_data['pack_numbers'] = $pack_numbers;
            $venipak_shipping_order_data['weight'] = $total_weight;
            $venipak_shipping_order_data['products_count'] = sizeof($order_products);
            $order->update_meta_data('venipak_shipping_order_data', json_encode($venipak_shipping_order_data) );
            $order->save();
            $xml_not_empty = true;
        }
        $this->settings->update_last_pack_number($last_pack_nr);
        $this->venipak_first_pack_number = $last_pack_nr;
        return $xml_not_empty ? $document->saveXML() : null;
    }

    public function get_product_weight($product) {
        if (!$product->get_virtual()) {
            $weight = $product->get_weight();
            if ($weight) {
                return $weight;
            }
        }

        return 0;
    }
}
