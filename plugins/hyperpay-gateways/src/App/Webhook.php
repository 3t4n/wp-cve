<?php

namespace Hyperpay\Gateways\App;

use WP_REST_Response;

class Webhook
{


  public static function getallheaders()
  {
    foreach ($_SERVER as $name => $value) {
      /* RFC2616 (HTTP/1.1) defines header fields as case-insensitive entities. */
      if (strtolower(substr($name, 0, 5)) == 'http_') {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers;
  }

  public static function hyperpay_rest_orders($request)
  {
    register_rest_route('hyperpay/v1/Hyperpay/Gateways/Brands', '/(?P<method>[a-zA-Z0-9_]+)', array(
      'methods' => 'POST',
      'callback' => [self::class, 'hyperpay_handel_order_status'],
      'permission_callback' => [self::class, 'hyperpay_auth_chech']

    ));
  }


  public static function hyperpay_handel_order_status($request)
  {

    $payment_method = "\\Hyperpay\\Gateways\\Brands\\" . $request['method'];
    $gateway = new $payment_method();

    $secret = $gateway->get_option('secret');
    $header = self::getallheaders();
    $initialization_vector = $header['X-Initialization-Vector'];
    $authentication_tag = $header['X-Authentication-Tag'];

    $key = hex2bin($secret);
    $iv = hex2bin($initialization_vector);
    $auth_tag = hex2bin($authentication_tag);
    $cipher_text = hex2bin($request->get_body());

    $result = openssl_decrypt($cipher_text, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $auth_tag);
    $result = json_decode($result, true);

    if ($result['type'] == 'test') {
      return new WP_REST_Response('test success', 200);
    }

    $order_id = $result['payload']['merchantTransactionId'];
    $order_id = \explode("I",$order_id)[0] ?? 0;

    $order = wc_get_order($order_id);

    if (!$order) {
      return new WP_REST_Response("order not Found",  404);
    }

    if ($order->get_payment_method() == 'hyperpay_zoodpay' && $result['payload']['result']['code'] == '100.396.103') {
      $order->update_status('on-hold');
      return new WP_REST_Response('updated to on-hold', 200);
    }

    if (!in_array($order->get_status(), ['on-hold', 'pending']) || $order->get_payment_method() != $gateway->id) {
      return new WP_REST_Response(__("Sorry, you are not allowed to do that."),  401);
    }

    if (preg_match($gateway->successCodePattern, $result['payload']['result']['code'])) {
      $uniqueId = $result['payload']['id'];
      $order_final_status = $result['payload']['paymentType'] == 'PA' ? 'on-hold' : $gateway->get_option('order_status');
      $order->add_order_note("Updated by webhook " . __("Transaction ID: ", "hyperpay-payments") . esc_html($uniqueId));
      if ($result['payload']['paymentType'] == 'CP')
        $order->add_order_note("Captured by webhook ");
      $order->update_status($order_final_status);
      $order->save();
    }


    return new WP_REST_Response('updated to success', 200);
  }


  public static function hyperpay_auth_chech($request)
  {
    if (!class_exists("\\Hyperpay\\Gateways\\Brands\\" . $request['method'])) {
      return false;
    }
    return true;
  }
}
