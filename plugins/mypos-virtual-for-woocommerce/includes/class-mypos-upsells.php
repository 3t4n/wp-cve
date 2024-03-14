<?php

defined( 'ABSPATH' ) || exit;

/**
 * MyPOS_Upsells class.
 */
class MyPOS_Upsells {

    /**
     * Custom endpoint name.
     *
     * @var string
     */
    public static string $endpoint = 'upsells';

    /**
     * MyPOS_Upsells constructor.
     */
    public function __construct() {
        // Register template endpoint.
        add_action('init', array(__CLASS__, 'add_endpoint'), 0);

        // Add query vars.
        add_filter('query_vars', array($this, 'add_query_vars'), 0);

        add_action('template_redirect', array($this, 'check_if_checkout_page'), 0);

        // Handle request.
        add_filter('template_include', array($this, 'upsell_template'), 0);
    }

    /**
     * Add endpoint.
     */
    public static function add_endpoint()
    {
        add_rewrite_endpoint(self::$endpoint, EP_PERMALINK);
        flush_rewrite_rules(false);
    }

    /**
     * Add query vars.
     *
     * @param array $vars Query variables.
     * @return string[]
     */
    public function add_query_vars(array $vars)
    {
        if (isset($vars[self::$endpoint])) {
            $vars[self::$endpoint] = true;
        }

        return $vars;
    }

    /**
     * Check if page is checkout and referer is not upsells then redirect to upsells page.
     */
    public function check_if_checkout_page()
    {
        if(is_page(get_option('woocommerce_checkout_page_id'))
            && !str_contains(wp_get_referer(), 'upsells')
            && !str_contains(wp_get_referer(), 'checkout')
            && !empty($this->getUpsellProductIds())) {
            wp_redirect(site_url('/upsells'));
        }
    }

    /**
     * Handle request.
     *
     * @param $template
     * @return mixed
     */
    public function upsell_template($template)
    {
        global $wp;

        if ($wp->query_vars['pagename'] === self::$endpoint) {
            mypos_get_template('upsells/upsells-page.php', [
                'products' => implode(',', $this->getUpsellProductIds())
            ]);
            exit;
        }

        return $template;
    }

    /**
     * retrieve upsell product ids for current cart products
     */
    public function getUpsellProductIds()
    {
        global $woocommerce;
        global $wpdb;

        $items = $woocommerce->cart->get_cart();
        $upsellProductIds = [];
        foreach($items as $item) {
            $upsells = $wpdb->get_results("SELECT * FROM wp_mypos_upsells WHERE base_products LIKE '%i:" . $item['data']->get_id() . ";%'");
            if (!empty($upsells)) {
                foreach ($upsells as $upsell) {
                    foreach (unserialize($upsell->recommended_products) as $productId) {
                        if (false !== get_post_status($productId) && !in_array($productId, $upsellProductIds, true)) {
                            $upsellProductIds[] = $productId;
                        }
                    }
                }
            }

        }

        return $upsellProductIds;
    }
}
new MyPOS_Upsells();
