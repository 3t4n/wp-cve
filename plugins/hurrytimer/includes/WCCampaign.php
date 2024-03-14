<?php

namespace Hurrytimer;

class WCCampaign {
    /**
     * Returns campaigns with WooCommerce enabled.
     *
     * @return array
     */
    public function get_wc_campaigns() {

        $args = [
            'post_type'         => HURRYT_POST_TYPE,
            'numberposts'       => -1,
            'post_status'       => 'publish',
            'meta_query'        => [
                [
                    'key'   => 'wc_enable',
                    'value' => C::YES,
                ]
            ],
        ];

        if(defined( 'ICL_SITEPRESS_VERSION' )){
            $args['suppress_filters'] = false;
        }
        $posts = get_posts($args);

        return array_map(function ($post) {
            return hurryt_get_campaign($post->ID);
        }, $posts);
    }

    /**
     * Run campaign for then given product page.
     *
     * @param $product_id
     */
    public function run($product_id) {
        $compaigns = $this->get_wc_campaigns();

        /**
         * @var Campaign $campaign
         */
        foreach ($compaigns as $campaign) {
            if (!$campaign->is_wc_enabled()) {
                continue;
            }
            if (
                $this->has_campaign($campaign, $product_id)
                && $this->met_conditions($campaign, $product_id)
            ) {
                do_action('hurryt_pre_render', $campaign);
                $callback = function () use ($campaign) {
                    $this->render_shortcode($campaign);
                };

                $position = $campaign->getWcPosition();
                

                // Compatibility with Astra theme v3.9.2 or later
                if(defined('ASTRA_THEME_VERSION') && version_compare(ASTRA_THEME_VERSION, '3.9.2', '>=')){
                switch($position) {
                    case C::WC_POSITION_ABOVE_ATC_BUTTON:
                        add_action('astra_woo_single_add_to_cart_before',$callback);
                        break;
                    case C::WC_POSITION_BELOW_ATC_BUTTON:
                        add_action('astra_woo_single_add_to_cart_after',$callback);
                        break;
                    case C::WC_POSITION_BELOW_PRICE:
                        add_action('astra_woo_single_price_after',$callback);
                        break;
                    case C::WC_POSITON_BELOW_REVIEW_RATING:
                        add_action('astra_woo_single_rating_after',$callback);
                        break;
                    case C::WC_POSITION_BELOW_TITLE:
                        add_action('astra_woo_single_title_after',$callback);
                        break;
                    case C::WC_POSITION_ABOVE_TITLE:
                        add_action('astra_woo_single_title_before',$callback);
                        break;
                    default:
                        add_action('woocommerce_single_product_summary', $callback,$position);
                        break;
                }
            }
            else{
                add_action('woocommerce_single_product_summary', $callback,$position);
            }
                
            }
        }
    }

    /**
     * Check if given product has an active campaign.
     *
     * @param Campaign $campaign
     * @param          $product_id
     *
     * @return bool
     */
    public function has_campaign($campaign, $product_id) {

        $cat_ids = $this->get_product_categories($product_id);
        $type      = $campaign->getWcProductsSelectionType();
        $selection = $campaign->getWcProductsSelection();
        switch ($type) {
            case C::WC_PS_TYPE_ALL:
                return true;

            case C::WC_PS_TYPE_INCLUDE_PRODUCTS:
                return in_array($product_id, $selection);

            case C::WC_PS_TYPE_EXCLUDE_PRODUCTS:
                return !in_array($product_id, $selection);

            case C::WC_PS_TYPE_INCLUDE_CATEGORIES:
                $callback = function ($id) use ($cat_ids) {
                    return in_array($id, $cat_ids);
                };
                $results  = array_filter($selection, $callback);

                return count($results) > 0;

            case C::WC_PS_TYPE_EXCLUDE_CATEGORIES:
                $results = array_filter($selection, function ($id) use ($cat_ids) {
                    return !in_array($id, $cat_ids);
                });

                return count($results) > 0;

            default:
                return false;
        }
    }

    /**
     * Returns categories ids for the given product.
     *
     * @param $product_id
     *
     * @return array|\WP_Error
     */
    public function get_product_categories($product_id) {
        return wp_get_object_terms($product_id, 'product_cat', [
            'fields' => 'ids',
        ]);
    }

    /**
     * Render shortcode for the given campaign ID.
     *
     * @param Campaign $campaign
     */
    public function render_shortcode($campaign) {
        $shortcode = '[hurrytimer id=' . $campaign->get_id() . ' ]';
        echo do_shortcode($shortcode);
    }

    /**
     * Change stock status.
     * TODO: Add support for evergreen campaigns.
     *
     * @param Campaign $campaign
     * @param int      $stock_status
     */
    public function change_stock_status($campaign, $stock_status) {
        if (!$campaign->is_wc_enabled()) {
            return;
        }

        $products = $this->get_campaign_products($campaign);
        foreach ($products as $product) {
            if (
                $stock_status === C::WC_OUT_OF_STOCK
                && apply_filters(
                    'hurryt_empty_stock',
                    true,
                    $product->get_id(),
                    $campaign->get_id()
                )
            ) {
                wc_update_product_stock(0); // TODO: test.
            }
            if ($product->managing_stock()) {
                wc_update_product_stock_status($product->get_id(), $stock_status);
            }
        }
    }

    /**
     * Get campaign's products ID.
     *
     * @param Campaign $campaign
     *
     * @return array
     */
    function get_campaign_products($campaign) {
        /**
         * Selected products/categories ids.
         */
        $object_ids = $campaign->getWcProductsSelection();
        /**
         * @see C
         */
        $type = $campaign->getWcProductsSelectionType();
        /**
         * @var array $args Products query default args.
         */
        $args = ['limit' => -1, 'status' => 'publish'];
        switch ($type) {
            case C::WC_PS_TYPE_ALL:
                return wc_get_products($args);
            case C::WC_PS_TYPE_EXCLUDE_PRODUCTS:
                return wc_get_products(array_merge($args, ['exclude' => $object_ids]));
            case C::WC_PS_TYPE_INCLUDE_PRODUCTS:
                return wc_get_products(array_merge($args, ['include' => $object_ids]));
            case C::WC_PS_TYPE_INCLUDE_CATEGORIES:
                $cat_slugs = get_terms([
                    'taxonomy' => 'product_cat',
                    'include'  => $object_ids,
                    'fields'   => 'slugs',
                ]);

                return wc_get_products(array_merge($args, ['category' => $cat_slugs]));
            case C::WC_PS_TYPE_EXCLUDE_CATEGORIES:
                $cat_slugs = get_terms([
                    'taxonomy' => 'product_cat',
                    'exclude'  => $object_ids,
                    'fields'   => 'slugs',
                ]);

                return wc_get_products(array_merge($args, ['category' => $cat_slugs]));
            default:
                return [];
        }
    }

    public function met_conditions($campaign, $object_id) {
        $groups = $campaign->getWcConditions();

        if (empty($groups)) {
            return true;
        }

        $product           = wc_get_product($object_id);
        $truthy_conditions = [];
        $truthy_groups     = [];
        foreach ($groups as $conditions) {
            foreach ($conditions as $condition) {

                switch ($condition['key']) {
                    case 'stock_status':
                        if ($condition['operator'] === '==') {
                            $truthy_conditions[] = $product->get_stock_status()
                                == $condition['value'];
                        }
                        if ($condition['operator'] === '!=') {
                            $truthy_conditions[] = $product->get_stock_status()
                                != $condition['value'];
                        }
                        break;
                    case 'stock_quantity':
                        if ($condition['operator'] === '==') {
                            $truthy_conditions[] = $product->get_stock_quantity()
                                == $condition['value'];
                        }
                        if ($condition['operator'] === '!=') {
                            $truthy_conditions[] = $product->get_stock_quantity()
                                != $condition['value'];
                        }
                        if ($condition['operator'] === '>') {
                            $truthy_conditions[] = $product->get_stock_quantity()
                                > $condition['value'];
                        }
                        if ($condition['operator'] === '<') {
                            $truthy_conditions[] = $product->get_stock_quantity()
                                < $condition['value'];
                        }
                        break;
                    case 'shipping_class':
                        if ($condition['operator'] === '==') {
                            $truthy_conditions[] = $product->get_shipping_class_id()
                                == $condition['value'];
                        }
                        if ($condition['operator'] === '!=') {
                            $truthy_conditions[] = $product->get_shipping_class_id()
                                != $condition['value'];
                        }
                        break;
                    case 'on_sale':
                        $truthy_conditions[] = filter_var($condition['value'], FILTER_VALIDATE_BOOLEAN) === $product->is_on_sale();

                        break;

                    default:

                        throw new \Exception('Rule [' . $condition['key'] . '] not supported.');
                }
            }
            $is_met = array_filter($truthy_conditions, function ($bool) {
                return $bool === true;
            });

            $truthy_groups[] = count($is_met) === count($conditions);
        }
        $met = array_filter($truthy_groups, function ($bool) {
            return $bool === true;
        });;

        return !empty($met);
    }
}
