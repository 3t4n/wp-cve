<?php

namespace ShopWP\API\Items;

use ShopWP\Options;
use ShopWP\Utils;

if (!defined('ABSPATH')) {
    exit();
}

class Cart extends \ShopWP\API
{
    public function __construct($plugin_settings, $Storefront_Cart) {
        $this->plugin_settings = $plugin_settings;
        $this->Storefront_Cart = $Storefront_Cart;
    }

    public function handle_apply_discount($request) {

        $cartId = $request->get_param('cartId');
        $discountCodes = $request->get_param('discountCodes');
        $language = $request->get_param('language');
        $country = $request->get_param('country');

        if (empty(array_filter($discountCodes))) {
            $is_removing_discount = true;

        } else {
            $is_removing_discount = false;
        }

        $cartData = [
            'cartId' => !empty($cartId) ? $cartId : false,
            'discountCodes' => !empty($discountCodes) ? $discountCodes : false,
            'language' => !empty($language) ? strtoupper($language) : strtoupper($this->plugin_settings['general']['language_code']),
            'country' => !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code'])
        ];

        $response = $this->Storefront_Cart->api_apply_discount($cartData);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        $filtered = array_filter($response->discountCodes);

        if (!$is_removing_discount && $filtered) {

            $only_applicable = $this->only_applicable_discounts($filtered);

            if (empty($only_applicable)) {
                return wp_send_json_error(__('The discount code entered is either invalid or inactive. Please try a different code.', 'shopwp'));
            }
        }

        return wp_send_json_success($response);

    }

    public function only_applicable_discounts($codes) {

        if (empty($codes)) {
            return [];
        }

        return array_filter($codes, function($code) {
            return $code->applicable;
        });
    }

    public function find_lineitem_options_from_lineitem_id($lineitem_options, $lineitem_id) {

        if (empty($lineitem_options)) {
            return false;
        }

        return array_filter($lineitem_options, function($lineitem_option) use($lineitem_id) {
            return $lineitem_option['variantId'] === $lineitem_id;
        });

    }

    public function create_lineitems_from_cache($checkout_cache, $line_type) {

        $line_items_new = array_map(function($lineitem) use($checkout_cache, $line_type) {
            
            $lineitem_info = [
                'quantity' => $lineitem['quantity']
            ];

            $lineitem_info[$line_type] = $lineitem['variantId'];

            $options_found = $this->find_lineitem_options_from_lineitem_id($checkout_cache['lineItemOptions'], $lineitem['variantId']);

            if (!empty($options_found)) {

                $options_found = array_values($options_found); 

                if (!empty($options_found[0]['options']['subscription'])) {
                    $lineitem_info['sellingPlanId'] = base64_encode('gid://shopify/SellingPlan/' . $options_found[0]['options']['subscription']['sellingPlanId']);    
                }

                if (!empty($options_found[0]['options']['attributes'])) {
                    $lineitem_info['attributes'] = $options_found[0]['options']['attributes'];    
                }
                
            }

            return $lineitem_info;

        }, $checkout_cache['lineItems']);

        return $line_items_new;        
    }

    public function handle_update_cart_attributes($request) {

        $cartId = $request->get_param('cartId');
        $attributes = $request->get_param('attributes');
        $language = $request->get_param('language');
        $country = $request->get_param('country');

        $language = !empty($language) ? strtoupper($language) : strtoupper($this->plugin_settings['general']['language_code']);
        $country = !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code']);

        $cart_data = [
            'cartId'        => $cartId,
            'language'      => $language,
            'country'       => $country,
            'attributes'    => !empty($attributes) ? $attributes : []
        ];

        if (empty($cartId)) {
            return wp_send_json_error(__('No cart id found. Please make sure you\'re passing in a valid cart id when updating cart attributes', 'shopwp'));
        }

        $response = $this->Storefront_Cart->api_update_cart_attributes($cart_data);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response);
                
    }

    public function handle_update_buyer_identity($request) {

        $cartId = $request->get_param('cartId');
        $buyerIdentity = $request->get_param('buyerIdentity');

        $cart_data = [
            'cartId'            => $cartId,
            'buyerIdentity'     => $buyerIdentity
        ];

        if (empty($cartId)) {
            return wp_send_json_error(__('No cart id found. Please make sure you\'re passing in a valid cart id when updating buyer identity', 'shopwp'));
        }

        $response = $this->Storefront_Cart->api_update_buyer_identity($cart_data);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response);
                
    }

    public function update_buyer_identity_cache($cart_id,$new_buyer_identity) {
        Options::update('shopwp_buyer_identity_' . Utils::hash($cart_id), $new_buyer_identity);
    }

    public function get_existing_buyer_identity($cart_id) {
        return Options::get('shopwp_buyer_identity_' . Utils::hash($cart_id));
    }

    public function handle_create_cart($request) {
        
        return \wp_send_json_error(__('Sorry, the free version of ShopWP is no longer supported. Please upgrade to ShopWP Pro to continue using this plugin.', 'shopwp'));
        
        $lines = $request->get_param('lines');
        $language = $request->get_param('language');
        $country = $request->get_param('country');
        
        $note = $request->get_param('note');
        $attributes = $request->get_param('attributes');
        $discountCodes = $request->get_param('discountCodes');
        $buyerIdentity = $request->get_param('buyerIdentity');

        $language = !empty($language) ? strtoupper($language) : strtoupper($this->plugin_settings['general']['language_code']);
        $country = !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code']);

        $cart_data = [
            'lines' => $this->sanitize_lineitems($lines),
            'language' => strtoupper($language),
            'country' => strtoupper($country),
            'note'=> !empty($note) ? $note : '',
            'attributes'=> !empty($attributes) ? $attributes : [],
            'discountCodes'=> !empty($discountCodes) ? $discountCodes : [],
            'buyerIdentity'=> !empty($buyerIdentity) ? $buyerIdentity : ['countryCode' => strtoupper($country)],
        ];

        $response = $this->Storefront_Cart->api_create_cart($cart_data);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }
        
        $this->update_buyer_identity_cache($response->id, $cart_data['buyerIdentity']);

        return wp_send_json_success($response);
    }

    public function add_shopwp_info_to_lines($lines = []) {

        if (empty($lines)) {
            return [];
        }

        return array_map(function($line) {

            $page = get_page_by_title($line->node->merchandise->product->title, 'OBJECT', 'wps_products');

            if (empty($page)) {
                $line->node->{"shopwp"} = false;

            } else {
                $product_id = get_post_meta($page->ID, 'product_id', true);

                $line->node->{"shopwp"} = [
                    'postId'    => $page->ID,
                    'productId' => $product_id,
                    'url'       => $page->guid
                ];
            }

            return $line;

        }, $lines);
    }

    public function has_buyer_identity_changed($passed, $existing) {
        if ($passed['country'] !== $existing['countryCode']) {
            return true;
        }

        return false;
    }

    public function handle_get_cart($request) {

        $cart_id = $request->get_param('id');

        $cartData = [];

        $cartData['id'] = $cart_id;
        $cartData['language'] = strtoupper($this->plugin_settings['general']['language_code']);
        $cartData['country'] = strtoupper($this->plugin_settings['general']['country_code']);

        return wp_send_json_error(__('Sorry, the free version of ShopWP is no longer supported. Please upgrade to ShopWP Pro to continue using this plugin.', 'shopwp'));

        // $existing_identity = $this->get_existing_buyer_identity($cart_id);

        // if (!empty($existing_identity)) {

        //     if ($this->has_buyer_identity_changed($cartData, $existing_identity)) {

        //         $new_identity = [
        //             'countryCode' => $cartData['country']
        //         ];

        //         $cart_updated = $this->Storefront_Cart->api_update_buyer_identity([
        //             'cartId'            => $cartData['id'],
        //             'buyerIdentity'     => $new_identity
        //         ]);

        //         if (is_wp_error($cart_updated)) {
        //             return wp_send_json_error($cart_updated);
        //         }

        //         if (empty($cart_updated)) {
        //             return wp_send_json_error(__('No cart data found. Try clearing your browser cache and reloading the page', 'shopwp'));
        //         }

        //         if (!empty($cart_updated->lines->edges)) {
        //             $cart_updated->lines->edges = $this->add_shopwp_info_to_lines($cart_updated->lines->edges);
        //         }

        //         $this->update_buyer_identity_cache($cart_updated->id, $new_identity);

        //         return wp_send_json_success($cart_updated);
                
        //     }
        // }

        $cart = $this->Storefront_Cart->api_get_cart($cartData);

        if (is_wp_error($cart)) {
            return wp_send_json_error($cart);
        }

        if (empty($cart)) {
            return wp_send_json_error(__('No cart data found. Try clearing your browser cache and reloading the page', 'shopwp'));
        }

        if (!empty($cart->lines->edges)) {
            $cart->lines->edges = $this->add_shopwp_info_to_lines($cart->lines->edges);
        }

        return wp_send_json_success($cart);
    }    

    public function sanitize_lineitems($lines) {
        
        if (empty($lines)) {
            return [];
        }

        return array_map(function($line) {

            $line_data = [
                'quantity' => $line['quantity']
            ];

            if (isset($line['variantId']) && !isset($line['merchandiseId'])) {
                $line_data['merchandiseId'] = \base64_decode($line['variantId']);

            } else {

                if (is_string($line['merchandiseId']) && Utils::str_contains($line['merchandiseId'], 'gid://shopify/ProductVariant/')) {
                    $line_data['merchandiseId'] = $line['merchandiseId'];
                } else {
                    $line_data['merchandiseId'] = 'gid://shopify/ProductVariant/' . $line['merchandiseId'];
                }
            }

            if (!empty($line['attributes'])) {
                $line_data['attributes'] = $line['attributes'];
            }

            if (!empty($line['sellingPlanId'])) {
                $line_data['sellingPlanId'] = 'gid://shopify/SellingPlan/' . $line['sellingPlanId'];
            }

            return $line_data;

        }, $lines);
    }

    public function handle_add_lineitems($request) {
        
        $cartId = $request->get_param('cartId');
        $lines = $request->get_param('lines');
        $language = $request->get_param('language');
        $country = $request->get_param('country');

        $cartData = [
            'cartId' => !empty($cartId) ? $cartId : false,
            'lines' => $this->sanitize_lineitems($lines),
            'language' => !empty($language) ? strtoupper($language) : strtoupper($this->plugin_settings['general']['language_code']),
            'country' => !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code'])
        ];

        $response = $this->Storefront_Cart->api_add_lineitems($cartData);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response);
    }

    public function handle_remove_lineitems($request) {

        $cart_id = $request->get_param('cartId');
        $lineIds = $request->get_param('lineIds');
        $language = $request->get_param('language');
        $country = $request->get_param('country');

        $cartData = [
            'cartId' => $cart_id,
            'lineIds' => $lineIds,
            'language' => !empty($language) ? strtoupper($language) : strtoupper($this->plugin_settings['general']['language_code']),
            'country' => !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code'])
        ];

        $response = $this->Storefront_Cart->api_remove_lineitems($cartData);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response);
    }

    public function handle_update_lineitems($request) {

        $lang = $request->get_param('language');
        $country = $request->get_param('country');

        $cartData = [
            'cartId' => $request->get_param('cartId'),
            'lines' => $request->get_param('lines'),
            'language' => !empty($lang) ? strtoupper($lang) : strtoupper($this->plugin_settings['general']['language_code']),
            'country' => !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code'])
        ];

        $response = $this->Storefront_Cart->api_update_lineitems($cartData);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response);
    }

    public function handle_update_note($request) {

        $lang = $request->get_param('language');
        $country = $request->get_param('country');

        $cartData = [
            'cartId' => $request->get_param('cartId'),
            'note' => $request->get_param('note'),
            'language' => !empty($lang) ? strtoupper($lang) : strtoupper($this->plugin_settings['general']['language_code']),
            'country' => !empty($country) ? strtoupper($country) : strtoupper($this->plugin_settings['general']['country_code'])
        ];

        $response = $this->Storefront_Cart->api_update_note($cartData);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response);
    }    
    

    public function register_routes() {
        $this->api_route('/cart/create', 'POST', [$this, 'handle_create_cart']);
        $this->api_route('/cart/get', 'POST', [$this, 'handle_get_cart']);
        $this->api_route('/cart/discount', 'POST', [$this, 'handle_apply_discount']);
        $this->api_route('/cart/lineitems/add', 'POST', [$this, 'handle_add_lineitems']);
        $this->api_route('/cart/lineitems/remove', 'POST', [$this, 'handle_remove_lineitems']);
        $this->api_route('/cart/lineitems/update', 'POST', [$this, 'handle_update_lineitems']);
        $this->api_route('/cart/note/update', 'POST', [$this, 'handle_update_note']);
        $this->api_route('/cart/attributes/update', 'POST', [$this, 'handle_update_cart_attributes']);
        $this->api_route('/cart/buyer/update', 'POST', [$this, 'handle_update_buyer_identity']);
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

}
