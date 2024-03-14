<?php
/**
 * Created by PhpStorm.
 * User: thanhlam
 * Date: 15/01/2021
 * Time: 21:58
 */

namespace NineKolor\TelegramWC\Classes;


class WooCommerce
{

    public $pattern;
    public $order;
    public $order_id;
    public $status_access;

    function __construct($order_id)
    {
        $this->pattern = array();
        $this->status_access = array();
        $this->order =  wc_get_order( $order_id );
        $this->order_id =  $order_id;
        add_filter( 'nktgnfw_filter_code_template',array($this,'filterTemplate'), 10, 2 );

    }

    public function getBillingDetails($str)
    {
        $this->decodeShortcode($str);
        $pr = $this->getProducts();
        $str = str_replace(array_keys($pr),array_values($pr),$str);
        return str_replace(array_keys($this->pattern), array_values($this->pattern),$str);
    }

    private function decodeShortcode($str)
    {
        $re = '/\{.+?}/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        array_walk_recursive($matches, function ($item, $key) {
            $pattern = explode('-',preg_replace('/\{|\}/','',$item));
            if (count($pattern)>1){
                $this->pattern[$item] = (string)$this->order->data[$pattern[0]][$pattern[1]];
            }else{
                $res = preg_replace('/\{|\}/','',$item);
                $_result = $this->order->data[$res];
                if ($_result) {
                    $this->pattern[$item] = $_result;
                }else{
                    $this->pattern[$item] = $this->order->get_meta($res)?:'';
                }
            }
        });

        $this->pattern = apply_filters( 'nktgnfw_filter_code_template', $this->pattern,$this->order_id);
    }


    public function getProducts() {
        $items =  $this->order->get_items();
        $product = chr(10);
        if(!empty($items)) {
            foreach ($items as $item) {
                $product_item = $item->get_product();
                if ($product_item) {
                    $product .= ' -' . $item['name'] . '   x' . $item['quantity'] . '  ' . wc_price($item['total']) . chr(10);
                }
            }
        }
        $return['{products}'] = $product;
        $shop = $this->order->get_items( 'shipping' );
        if ($shop) {
            $shipping  = end($shop)->get_data();
            $return['{shipping_method_title}'] = $shipping['method_title'];
        }
        return $return;
    }

    public function filterTemplate($replace){
        $replace['{order_id}'] = $this->order_id;
        $replace['{order_status}'] = wc_get_order_status_name($this->order->get_status());
        $replace['{total}'] = wc_price($this->order->get_total());
        $replace['{order_date_created}'] = $this->order->get_date_created()->date(get_option('links_updated_date_format'));
        return $replace;
    }
}