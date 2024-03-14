<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;

defined('ABSPATH') or die;

class FreeProduct extends Base
{
    public static $free_product_list = array();
    public static $instance = null;
    public static $customer_chose_variant = array();
    public $earn_campaign, $available_conditions = array();

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public static function getInstance(array $config = array())
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    function changeRewardsProductInCart($reward_id, $product_id, $variant_id)
    {
        self::$customer_chose_variant = self::$woocommerce_helper->getSession('wlr_customer_chose_variant', array());
        if (isset(self::$customer_chose_variant[$reward_id][$product_id])) {
            self::$customer_chose_variant[$reward_id][$product_id] = $variant_id;
        } else {
            self::$customer_chose_variant[$reward_id] = array(
                $product_id => $variant_id
            );
        }
        self::$woocommerce_helper->setSession('wlr_customer_chose_variant', self::$customer_chose_variant);
    }

    function removeFreeProductFromCart($code)
    {
        $free_product_list = $this->getFreeProductList($code);
        $reward = $this->getUserRewardByCoupon($code);
        foreach ($free_product_list as $product_id => $free_product) {
            $free_product['product'] = self::$woocommerce_helper->getProduct($product_id);
            $this->checkCartItems($free_product, $reward, array(), true);
        }

    }

    function getFreeProductList($code)
    {
        $user_reward = $this->getUserRewardByCoupon($code);
        if (!is_object($user_reward) || !isset($user_reward->discount_type) || $user_reward->discount_type != 'free_product'
            || !isset($user_reward->free_product) || !self::$woocommerce_helper->isJson($user_reward->free_product)) {
            return self::$free_product_list;
        }
        $free_product_ids = json_decode($user_reward->free_product);
        foreach ($free_product_ids as $free_pro) {
            if (isset($free_pro->value) && !empty($free_pro->value)) {
                if (!isset(self::$free_product_list[$free_pro->value]) || empty(self::$free_product_list[$free_pro->value])) {
                    $product_variant = self::$woocommerce_helper->get_variant_ids($free_pro->value);
                    self::$free_product_list[$free_pro->value] = array(
                        'product_id' => $free_pro->value,
                        'product_variants' => $product_variant,
                        'qty' => 1,
                        'user_reward_id' => $user_reward->id,
                        'customer_chose_variant' => $this->getCustomerChoseVariant($free_pro->value, $product_variant, $user_reward->id)
                    );
                } elseif (isset(self::$free_product_list[$free_pro->value]) && !empty(self::$free_product_list[$free_pro->value])) {
                    self::$free_product_list[$free_pro->value]['qty'] += 1;
                }
            }
        }
        return self::$free_product_list;
    }

    function getCustomerChoseVariant($product_id, $product_variant, $reward_id)
    {
        if (empty($product_variant)) {
            return 0;
        }
        self::$customer_chose_variant = self::$woocommerce_helper->getSession('wlr_customer_chose_variant', array());
        if (!isset(self::$customer_chose_variant[$reward_id][$product_id]) || empty(self::$customer_chose_variant[$reward_id][$product_id]) || !in_array(self::$customer_chose_variant[$reward_id][$product_id], $product_variant)) {
            self::$customer_chose_variant[$reward_id][$product_id] = isset($product_variant[0]) && !empty($product_variant[0]) ? $product_variant[0] : 0;
        }
        self::$woocommerce_helper->setSession('wlr_customer_chose_variant', self::$customer_chose_variant);
        return self::$customer_chose_variant[$reward_id][$product_id];
    }

    function checkCartItems($free_product, $user_reward, $items = array(), $is_need_remove = false)
    {
        if (empty($items)) {
            $items = self::$woocommerce_helper->getCartItems();
        }
        $status = false;
        foreach ($items as $key => $item) {
            $product_id = isset($item['product_id']) && !empty($item['product_id']) ? $item['product_id'] : 0;
            $variation_id = isset($item['variation_id']) && !empty($item['variation_id']) ? $item['variation_id'] : 0;
            if ($free_product['product_id'] != $product_id) {
                if ($variation_id <= 0 || ($free_product['product_id'] != $variation_id)) {
                    continue;
                }
            }
            $is_loyalty_free_product = isset($item['loyalty_free_product']) && !empty($item['loyalty_free_product']) ? $item['loyalty_free_product'] : 'no';
            $loyalty_user_reward_ids = isset($item['loyalty_user_reward_ids']) && !empty($item['loyalty_user_reward_ids']) ? $item['loyalty_user_reward_ids'] : array();
            if ($is_loyalty_free_product == 'yes' && !empty($loyalty_user_reward_ids) && in_array($user_reward->id, $loyalty_user_reward_ids)) {
                if ($is_need_remove) {
                    self::$woocommerce_helper->remove_cart_item($key);
                }
                $status = true;
            }
        }
        return $status;
    }

    function emptyFreeProductList()
    {
        self::$free_product_list = array();
    }

    function addFreeProductToCart($code, $free_product_list, $discount = null)
    {
        $free_product_ids = array_keys($free_product_list);
        $items = self::$woocommerce_helper->getCartItems();
        $processed_items = array();
        $processed_free_product = array();
        foreach ($items as $item) {
            $product_id = isset($item['product_id']) && !empty($item['product_id']) ? $item['product_id'] : 0;
            $variation_id = isset($item['variation_id']) && !empty($item['variation_id']) ? $item['variation_id'] : 0;
            if ((in_array($product_id, $free_product_ids) || in_array($variation_id, $free_product_ids)) && isset($item['loyalty_free_product']) && $item['loyalty_free_product'] == 'yes') {
                $current_free_product = array();
                if (in_array($product_id, $free_product_ids)) {
                    $current_free_product = $free_product_list[$product_id];
                } elseif (in_array($variation_id, $free_product_ids)) {
                    $current_free_product = $free_product_list[$variation_id];
                }
                if (!empty($current_free_product) && isset($current_free_product['customer_chose_variant']) && $current_free_product['customer_chose_variant'] != $item['customer_chose_variant']) {
                    self::$woocommerce_helper->remove_cart_item($item['key']);
                    continue;
                } elseif (in_array($current_free_product['product_id'], $processed_free_product)) {
                    self::$woocommerce_helper->remove_cart_item($item['key']);
                    continue;
                } elseif (isset($current_free_product['qty']) && $current_free_product['qty'] >= 1) {
                    self::$woocommerce_helper->remove_cart_item($item['key']);
                    continue;
                }
                $processed_items[] = $item['key'];
                $processed_free_product[] = $current_free_product['product_id'];
            }
            if (isset($item['loyalty_free_product']) && $item['loyalty_free_product'] == 'yes' && !in_array($item['key'], $processed_items)) {
                self::$woocommerce_helper->remove_cart_item($item['key']);
            }
        }
        if (count($free_product_ids) != count($processed_free_product)) {
            $reward = $this->getUserRewardByCoupon($code);
            foreach ($free_product_list as $product_id => $free_product) {
                $free_product['product'] = self::$woocommerce_helper->getProduct($product_id);

                $is_cart_have_free_product = $this->isCartHaveLoyaltyFreeProduct($free_product, $code, $discount);
                if ($is_cart_have_free_product) {
                    $items = self::$woocommerce_helper->getCartItems();
                    foreach ($items as $item) {
                        $product_id = isset($item['product_id']) && !empty($item['product_id']) ? $item['product_id'] : 0;
                        $variation_id = isset($item['variation_id']) && !empty($item['variation_id']) ? $item['variation_id'] : 0;
                        if ($free_product['product_id'] != $product_id) {
                            if ($variation_id <= 0 || ($free_product['product_id'] != $variation_id)) {
                                continue;
                            }
                        }
                        //Need to check if any variant change, in cart
                        if (isset($item['loyalty_free_product']) && $item['loyalty_free_product'] == 'yes') {
                            if ($free_product['customer_chose_variant'] != $item['customer_chose_variant']) {
                                // self::$woocommerce_helper->remove_cart_item($item['key']);
                                $is_cart_have_free_product = false;
                            }
                        }
                    }
                }
                if (!$is_cart_have_free_product) {

                    $variation_id = 0;
                    $loyal_product_id = $product_id;
                    $product = $free_product['product'];
                    $parent_product = self::$woocommerce_helper->getParentProduct($product);
                    $free_product_id = is_object($product) ? $product->get_id() : $product->id;
                    $parent_product_id = is_object($parent_product) ? $parent_product->get_id() : $parent_product->id;
                    //case 1: we select variable product
                    if ($free_product_id == $parent_product_id) {
                        if (isset($free_product['customer_chose_variant']) && !empty($free_product['customer_chose_variant'])) {
                            $product = self::$woocommerce_helper->getProduct($free_product['customer_chose_variant']);
                            $parent_product = self::$woocommerce_helper->getParentProduct($product);
                            $loyal_product_id = $free_product['customer_chose_variant'];
                        }
                    }

                    //case 2: variant product
                    $variation = array();
                    if (!empty($parent_product) && $parent_product_id != $loyal_product_id) {
                        $variation_id = $loyal_product_id;
                        $loyal_product_id = $parent_product_id;
                        $variation = self::$woocommerce_helper->getProductAttributes($product);
                    }

                    $cart_item_data = array(
                        'loyalty_free_product' => 'yes',
                        'loyalty_product_id' => $free_product['product_id'],
                        'loyalty_user_reward_ids' => array($reward->id),
                        'loyalty_user_reward_id' => $reward->id,
                        'loyalty_qty' => isset($free_product['qty']) && !empty($free_product['qty']) ? $free_product['qty'] : 1,
                        'loyalty_variants' => isset($free_product['product_variants']) && !empty($free_product['product_variants']) ? $free_product['product_variants'] : array(),
                        'customer_chose_variant' => isset($free_product['customer_chose_variant']) && !empty($free_product['customer_chose_variant']) ? $free_product['customer_chose_variant'] : 0,
                    );
                    $variation['loyalty_free_product'] = __('Free', 'wp-loyalty-rules');
                    $cart_key = self::$woocommerce_helper->add_to_cart($loyal_product_id, $cart_item_data['loyalty_qty'], $variation_id, $variation, $cart_item_data);

                }
            }
        }

    }

    function isCartHaveLoyaltyFreeProduct($free_product, $code, $discount, $force = false)
    {
        if (!is_array($free_product) || empty($code)) return false;
        $reward = $this->getUserRewardByCoupon($code);
        return $this->checkCartItems($free_product, $reward);
    }
}