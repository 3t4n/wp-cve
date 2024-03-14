<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:19 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\CartManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Cart;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\CartItem;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Models\WoocommerceCart;

class CartManager extends CartManagerAbstract
{
    /**
     * Flag preload cart item
     * @var bool
     */
    private static $preLoaded = false;
    private static $cartOption1s = array();
    private static $cartImages = array();

    /**
     * CartManager constructor.
     */
    public function __construct()
    {
        // Add beeketing cart to woocommerce cart response (add, get WC cart)
        add_filter('woocommerce_add_to_cart_fragments', function($fragments) {
            $cookieKey = 'beeketing_cart_fragments_init';
            if (isset($_COOKIE[$cookieKey])) {
                $fragments['beeketing_cart'] = $this->get();
            } else {
                setcookie($cookieKey, 1, 0, '/');
            }
            return $fragments;
        }, 10, 1);
    }

    /**
     * Get cart
     * @param bool $setCookie
     * @return WoocommerceCart
     */
    public function get($setCookie = true)
    {
        // Init cart data
        global $woocommerce;

        if (!$woocommerce->cart) {
            $woocommerce->cart = new \WC_Cart();
        }

        /** @var \WC_Cart $cart */
        $cart = $woocommerce->cart;
        $cartItems = $cart->get_cart();
        $cartToken = isset($_COOKIE[CommonConstants::CART_TOKEN_KEY]) ? $_COOKIE[CommonConstants::CART_TOKEN_KEY] : '';

        // Get cart cookie uid
        $itemsKey = '';
        if ($cartItems) {
            foreach ($cartItems as $id => $itemArray) {
                $itemsKey .= $id . $itemArray['quantity'];
            }
        }

        // Base result
        $itemCount = count($cartItems);

        $result = new WoocommerceCart();
        $result->token = $cartToken;
        $result->item_count = $itemCount;
        $result->subtotal_price = $cart->subtotal;
        $result->total_price = (float)$cart->total;

        // Traverse cart items
        if ($cartItems) {
            // Get option1s
            $variationIds = array();
            foreach ($cartItems as $itemArray) {
                $variationIds[] = Helper::isWc3() ? $itemArray['data']->get_id() : $itemArray['data']->variation_id; // Check wc version
            }
            global $wpdb;
            $option1Results = $wpdb->get_results(
                "SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = '_beeketing_option1' AND post_id IN (" . implode(',', $variationIds) . ")"
            );
            foreach ($option1Results as $option1_result) {
                self::$cartOption1s[$option1_result->post_id] = $option1_result->meta_value;
            }

            $this->getCartImages($variationIds);

            self::$preLoaded = true;

            // Format cart items
            foreach ($cartItems as $id => $itemArray) {
                $formattedItem = $this->formatItem($id, $itemArray);
                $result->items[] = $formattedItem;
            }
        }

        return $result;
    }

    /**
     * Get cart images
     * @param array $postIds
     */
    private function getCartImages($postIds)
    {
        global $wpdb;

        // Get all images id
        $imageResult = $wpdb->get_results(
            "
            SELECT post_id, meta_key, meta_value
            FROM $wpdb->postmeta
            WHERE post_id IN (" . implode(',', $postIds) . ") AND meta_key IN ('_thumbnail_id')
            "
        );

        $imagesRelation = array();
        $imagesId = array();
        foreach ($imageResult as $item) {
            $imagesId[] = $item->meta_value;
            $imagesRelation[$item->meta_value] = $item->post_id;
        }

        $imagesId = array_filter(array_unique($imagesId));
        if ($imagesId) {
            $result = $wpdb->get_results(
                "
                SELECT p.ID, p.post_parent, pm.meta_key, pm.meta_value
                FROM $wpdb->postmeta pm JOIN $wpdb->posts p ON pm.post_id = p.ID
                WHERE pm.meta_key IN ('_wp_attached_file', '_wp_attachment_metadata')
                  AND p.post_type = 'attachment'
                  AND p.ID IN (" . implode(',', $imagesId) . ")
                "
            );

            $imagesConverted = array();
            foreach ($result as $item) {
                $imagesConverted[$item->ID]['post_parent'] = $item->post_parent;
                $imagesConverted[$item->ID][$item->meta_key] = $item->meta_value;
            }

            foreach ($imagesConverted as $image_id => $image_converted) {
                // Get medium image
                $file = null;
                if (isset($image_converted['_wp_attachment_metadata'])) {
                    $image = $image_converted['_wp_attachment_metadata'];
                    $image = unserialize($image);
                    $sizes = array('medium', 'shop_catalog', 'thumbnail', 'shop_thumbnail');
                    foreach ($sizes as $size) {
                        if (isset($image['sizes'][$size]['file'])) {
                            $file = $image['sizes'][$size]['file'];
                            $image = $image['file'];
                            $file = preg_replace('/[^\/]+$/', $file, $image);
                            break;
                        }
                    }
                }

                // Fall back to main image
                if (!$file) {
                    $file = $image_converted['_wp_attached_file'];
                }

                // Get upload directory.
                $url = null;
                if (preg_match_all('/^http(s)?:\/\//', $file) == 1) { // If image use cdn
                    $url = $file;
                } else { // Local image
                    if (function_exists('wp_get_upload_dir') && ($uploads = wp_get_upload_dir()) && false === $uploads['error']) {
                        // Check that the upload base exists in the file location.
                        if (0 === strpos($file, $uploads['basedir'])) {
                            // Replace file location with url location.
                            $url = str_replace($uploads['basedir'], $uploads['baseurl'], $file);
                        } else {
                            // It's a newly-uploaded file, therefore $file is relative to the basedir.
                            $url = $uploads['baseurl'] . "/$file";
                        }
                    }
                }

                // Ignore image
                if (!$url) {
                    continue;
                }

                $post_parent = isset($imagesRelation[$image_id]) ? $imagesRelation[$image_id] : $image_converted['post_parent'];
                self::$cartImages[$post_parent] = $url;
            }
        }
    }

    /**
     * Format item
     *
     * @param $id
     * @param $itemArray
     * @return CartItem
     */
    private function formatItem($id, $itemArray)
    {
        if (Helper::isWc3()) {
            $product = $itemArray['data'];
            $variationId = $itemArray['data']->get_id();
            $price = $itemArray['data']->get_price();
            $sku = $itemArray['data']->get_sku();
            $imageId = $itemArray['data']->get_image_id();
            $productPermalink = $product->get_permalink();
            $postTitle = $product->get_title();
        } else {
            $product = $itemArray['data']->post;
            $variationId = $itemArray['data']->variation_id;
            $price = $itemArray['data']->price;
            $sku = $itemArray['data']->sku;
            $imageId = get_post_thumbnail_id($product->ID);
            $productPermalink = get_permalink($product);
            $postTitle = $product->post_title;
        }

        $title = html_entity_decode($postTitle);
        if (self::$preLoaded) {
            $option1 = isset(self::$cartOption1s[$variationId]) ? self::$cartOption1s[$variationId] : '';
        } else {
            $option1 = get_post_meta($variationId, '_beeketing_option1', true);
        }
        $variantTitle = $option1 ?: $title;

        if (isset(self::$cartImages[$variationId])) {
            $image = self::$cartImages[$variationId];
        } else {
            $imageData = wp_get_attachment_image_src($imageId, 'thumbnail');
            $image = isset($imageData[0]) ? $imageData[0] : '';
        }

        $item = new CartItem();
        $item->id = $id;
        $item->variant_id = (int)($itemArray['variation_id'] ?: $itemArray['product_id']);
        $item->variant_title = $variantTitle;
        $item->product_id = (int)$itemArray['product_id'];
        $item->title = $variantTitle;
        $item->product_title = $title;
        $item->price = (float)$price;
        $item->line_price = (float)$itemArray['line_total'];
        $item->quantity = (int)$itemArray['quantity'];
        $item->sku = $sku;
        $item->handle = Helper::getUrlHandle($productPermalink);
        $item->image = $image;
        $item->url = $productPermalink;

        return $item;
    }

    /**
     * Add items to cart
     * @param $arg
     * @return Cart|Item
     * @throws \Exception
     */
    public function post($arg)
    {
        $type = $arg['type'];
        $productId = $arg['product_id'];
        $variantId = $arg['variant_id'];
        $quantity = $arg['quantity'];
        $attributes = $arg['attributes'];

        if ($type === 'multi') {
            // Add multi
            if (is_array($productId)) {
                foreach ($productId as $index => $id) {
                    $vid = isset($variantId[$index]) ? $variantId[$index] : null;
                    $qty = isset($quantity[$index]) ? $quantity[$index] : 1;
                    $attr = $attributes && isset($attributes[$index]) ? $attributes[$index] : array();

                    $this->addItemToCart($id, $vid, $qty, $attr);
                }
            }
            $data = $this->get();
            $refreshFragments = $this->getRefreshedFragments();
            $data->fragments = $refreshFragments['fragments'];
            $data->cart_hash = $refreshFragments['cart_hash'];

            return $data;
        }

        // Add single
        return $this->addItemToCart($productId, $variantId, $quantity, $attributes);
    }

    /**
     * Add cart
     *
     * @param $productId
     * @param $variantId
     * @param $quantity
     * @param $params
     * @return Item
     * @throws \Exception
     */
    public function addItemToCart($productId, $variantId, $quantity, $params)
    {
        global $woocommerce;
        $woocommerce->session->set_customer_session_cookie(true);
        $cartItemKey = $woocommerce->cart->add_to_cart($productId, $quantity, $variantId, $params);

        $cartItems = $woocommerce->cart->get_cart();

        // Traverse cart items
        foreach ($cartItems as $id => $itemArray) {
            if ($cartItemKey == $id) {
                return $this->formatItem($id, $itemArray);
            }
        }

        throw new \Exception('Failed to add item to cart');
    }

    /**
     * Update cart items quantity
     * @param $arg
     * @return Cart
     */
    public function put($arg)
    {
        $id = $arg['id'];
        foreach ($id as $itemId => $quantity) {
            $this->updateCartItemQuantity($itemId, $quantity);
        }

        return $this->get();
    }

    /**
     * Update cart
     *
     * @param $itemId
     * @param $quantity
     * @return bool
     */
    public function updateCartItemQuantity($itemId, $quantity)
    {
        global $woocommerce;
        $cartItemKey = sanitize_text_field($itemId);
        if ($cartItem = $woocommerce->cart->get_cart_item($cartItemKey)) {
            if ($quantity) {
                $woocommerce->cart->set_quantity($cartItemKey, $quantity);
            } else {
                $woocommerce->cart->remove_cart_item($cartItemKey);
            }
            return true;
        }

        return false;
    }

    /**
     * Get a refreshed cart fragment, including the mini cart HTML.
     */
    public static function getRefreshedFragments()
    {
        ob_start();

        woocommerce_mini_cart();
        $miniCart = ob_get_clean();
        $cartSession = WC()->cart->get_cart_for_session();
        $cartHash = $cartSession ? md5(json_encode($cartSession)) : '';
        return [
            'fragments' => apply_filters(
                'woocommerce_add_to_cart_fragments',
                [
                    'div.widget_shopping_cart_content' =>
                        '<div class="widget_shopping_cart_content">' . $miniCart . '</div>',
                ]
            ),
            'cart_hash' => apply_filters('woocommerce_add_to_cart_hash', $cartHash, $cartSession),
        ];
    }

}
