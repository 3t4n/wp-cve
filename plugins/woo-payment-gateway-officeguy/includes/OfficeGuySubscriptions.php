<?php
class OfficeGuySubscriptions
{
    /**
     * Disable other payments method
     * if user has SUMIT subscription products in the cart.
     */
    public static function UpdateAvailablePaymentMethods($AvailableGateways)
    {
        if (empty($AvailableGateways) || !isset($AvailableGateways['officeguy']) || is_admin() || count(OfficeGuySubscriptions::GetCartProductIDs()) == 0)
            return $AvailableGateways;

        if (OfficeGuySubscriptions::CartContainsOfficeGuySubscription() && OfficeGuyMultiVendor::HasVendorInCart())
            unset($AvailableGateways['officeguy']);
        else if (OfficeGuySubscriptions::CartContainsOfficeGuySubscription())
        {
            foreach ($AvailableGateways as $gateway => $object)
            {
                if ($gateway != 'officeguy')
                    unset($AvailableGateways[$gateway]);
            }
        }
        
        return $AvailableGateways;
    }

    public static function GetCartProductIDs()
    {
        $ProductIDs = array();
        if (is_wc_endpoint_url('order-pay'))
        {
            $OrderID = get_query_var('order-pay');
            $Order = wc_get_order($OrderID);
            foreach ($Order->get_items() as $Item)
                $ProductIDs[] = $Item->get_product_id();
        }
        elseif (WC()->cart != null)
        {
            $ProductIDs =  array_map(
                function ($Item)
                {
                    return $Item['product_id'];
                },
                WC()->cart->get_cart()
            );
        }
        return $ProductIDs;
    }

    public static function CartContainsOfficeGuySubscription()
    {
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();
        foreach ($ProductIDs as $ProductID)
        {
            if (get_post_meta($ProductID, 'OfficeGuySubscription', true) === 'yes')
                return true;
        }
        return false;
    }

    public static function CartContainsWooCommerceSubscription()
    {
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();
        foreach ($ProductIDs as $ProductID)
        {
            $Product = wc_get_product($ProductID);
            $Type = $Product->get_type();
            if ($Type == 'subscription' || $Type == 'variable-subscription')
                return true;
        }
        return false;
    }

    public static function CartContainsWooCommerceSubscriptionWithoutTrial()
    {
        $ProductIDs = OfficeGuySubscriptions::GetCartProductIDs();
        foreach ($ProductIDs as $ProductID)
        {
            $Product = wc_get_product($ProductID);
            $Type = $Product->get_type();
            if ($Type == 'subscription' || $Type == 'variable-subscription') {
                $TrialLength = WC_Subscriptions_Product::get_trial_length($Product);
                if ($TrialLength == 0)
                    return true;
            }
        }
        return false;
    }

    public static function AddProductType($ProductTypeOptions)
    {
        global $post;
        $Value = get_post_meta($post->ID, 'OfficeGuySubscription', true) ? get_post_meta($post->ID, 'OfficeGuySubscription', true) : 'no';
        $ProductTypeOptions['officeguy'] = array(
            'id' => 'OfficeGuySubscription',
            'wrapper_class' => null,
            'label' => __('SUMIT Recurring', 'officeguy'),
            'description' => __('Recurring product/service.', 'officeguy'),
            'default' => $Value
        );
        return $ProductTypeOptions;
    }

    public static function AddProductSettingsTab($Tabs)
    {
        $Tabs['officeguy'] = array(
            'label'    => __('SUMIT Recurring', 'officeguy'),
            'target' => 'officeguy_options',
            'class' => array('show_if_og_subscription')
        );
        return $Tabs;
    }

    public static function ProductSettingsTab()
    {
        global $post;

        echo '<div id="officeguy_options" class="panel woocommerce_options_panel">';
        echo '<div class="options_group">';

        woocommerce_wp_select(array(
            'id' => '_duration_in_months',
            'label' => __('Interval in months', 'officeguy'),
            'desc_tip' => 'true',
            'description' => __('Interval in months affects the duration between each recurring payment. The interval is usually set for monthly charges, but can be used for other intervals as well', 'officeguy'),
            'options' => array(
                1 => OfficeGuySubscriptions::GetMonthsString(1),
                2 => OfficeGuySubscriptions::GetMonthsString(2),
                3 => OfficeGuySubscriptions::GetMonthsString(3),
                4 => OfficeGuySubscriptions::GetMonthsString(4),
                5 => OfficeGuySubscriptions::GetMonthsString(5),
                6 => OfficeGuySubscriptions::GetMonthsString(6),
                7 => OfficeGuySubscriptions::GetMonthsString(7),
                8 => OfficeGuySubscriptions::GetMonthsString(8),
                9 => OfficeGuySubscriptions::GetMonthsString(9),
                10 => OfficeGuySubscriptions::GetMonthsString(10),
                11 => OfficeGuySubscriptions::GetMonthsString(11),
                12 => OfficeGuySubscriptions::GetMonthsString(12)
            ),
            'value' => get_post_meta($post->ID, '_duration_in_months', true)
        ));

        woocommerce_wp_text_input(array(
            'id' => '_recurrences',
            'label' => __('Number of recurrences', 'officeguy'),
            'desc_tip' => 'true',
            'description' => __('Leave empty for non-expiring recurring payment', 'officeguy'),
            'type' => 'number'
        ));
        echo '</div>';
        echo '</div>';
    }

    public static function SaveProductFields($PostID)
    {
        // Save subscription checkbox
        update_post_meta($PostID, 'OfficeGuySubscription', isset($_POST['OfficeGuySubscription']) ? 'yes' : 'no');

        // Save duration in months
        if (isset($_POST['_duration_in_months']))
            update_post_meta($PostID, '_duration_in_months', sanitize_text_field($_POST['_duration_in_months']));

        // Save recurrences
        if (isset($_POST['_recurrences']))
            update_post_meta($PostID, '_recurrences', sanitize_text_field($_POST['_recurrences']));
    }

    public static function ProductSettingsTabScript()
    { ?>
        <style>
            body.rtl #woocommerce-product-data ul.wc-tabs li.officeguy_options a:before {
                float: right;
            }
        </style>
        <script>
            jQuery(document).ready(function($) {
                $('input#OfficeGuySubscription')
                    .on("change.officeguy", function() {
                        if ($('input#_virtual:visible:checked').length == 0 && $(this).prop("checked")) {
                            $('input#_virtual').prop("checked", true);
                            $('.shipping_tab').hide();
                        }
                        $('.show_if_og_subscription').toggle($(this).prop("checked"));
                    })
                    .trigger('change.officeguy');

                $('input#_virtual').on("change", function() {
                    if ($('input#OfficeGuySubscription:checked').length > 0 && $(this).is(":visible") && !$(this).prop("checked")) {
                        alert('<?php _e('Subscription product should also be set as a virtual product.', 'officeguy'); ?>');
                        $('input#OfficeGuySubscription:checked').prop("checked", false);
                        $('.show_if_og_subscription').hide();
                    }
                });
            });
        </script>
<?php
    }

    public static function GetMonthsString($Months)
    {
        if ($Months == 1)
            return __('Month', 'officeguy');
        elseif ($Months == 2)
            return __('2 months', 'officeguy');
        elseif ($Months == 6)
            return __('6 months', 'officeguy');
        elseif ($Months % 12 == 0)
        {
            $Years = $Months / 12;
            if ($Years == 1)
                return __('Year', 'officeguy');
            elseif ($Years == 2)
                return __('2 Years', 'officeguy');
            else
                return $Years . __('Years', 'officeguy');
        }
        else
            return $Months . ' ' . __('months', 'officeguy');
    }

    public static function GetProductPriceString($ProductID, $Price)
    {
        $Subscription = get_post_meta($ProductID, 'OfficeGuySubscription', true) === 'yes';
        $Duration = get_post_meta($ProductID, '_duration_in_months', true);
        $Recurrences = get_post_meta($ProductID, '_recurrences', true);

        if (!$Subscription || !$Duration)
            return $Price;

        $SubscriptionText = ' / ' . OfficeGuySubscriptions::GetMonthsString($Duration);
        if ($Subscription && $Recurrences)
            $SubscriptionText .= ' ' . __('for ', 'officeguy') . OfficeGuySubscriptions::GetMonthsString(intval($Duration) * intval($Recurrences));

        return $Price . '<span class="og-subscription">' . $SubscriptionText . '</span>';
    }

    public static function ProductPagePriceString($Price, $Product)
    {
        return OfficeGuySubscriptions::GetProductPriceString($Product->get_id(), $Price);
    }

    public static function CartPagePriceString($Price, $Product)
    {
        return OfficeGuySubscriptions::GetProductPriceString($Product['product_id'], $Price);
    }

    public static function OrderPagePriceString($Subtotal, $Product, $Order)
    {
        $ProductID = $Product['product_id'];
        $Subscription = get_post_meta($ProductID, 'OfficeGuySubscription', true) === 'yes';
        $Duration = get_post_meta($ProductID, '_duration_in_months', true);
        $Recurrences = get_post_meta($ProductID, '_recurrences', true);

        if (!$Subscription || !$Duration)
            return $Subtotal;

        $SubscriptionText = ' / ' . OfficeGuySubscriptions::GetMonthsString($Duration);
        if ($Subscription && $Recurrences)
            $SubscriptionText .= ' ' . __('for ', 'officeguy') . OfficeGuySubscriptions::GetMonthsString(intval($Duration) * intval($Recurrences));

        return $Subtotal . '<span class="og-subscription">' . $SubscriptionText . '</span>';
    }

    public static function AdminOrderPageProduct($ItemID, $Product, $Order)
    {
        $ProductID = $Product['product_id'];
        $Subscription = get_post_meta($ProductID, 'OfficeGuySubscription', true) === 'yes';
        $Duration = get_post_meta($ProductID, '_duration_in_months', true);
        $Recurrences = get_post_meta($ProductID, '_recurrences', true);

        if (!$Subscription || !$Duration)
            return;

        $SubscriptionText = ' / ' . OfficeGuySubscriptions::GetMonthsString($Duration);
        if ($Subscription && $Recurrences)
            $SubscriptionText .= ' ' . __('for ', 'officeguy') . OfficeGuySubscriptions::GetMonthsString(intval($Duration) * intval($Recurrences));

        echo '<span class="og-subscription">' . $SubscriptionText . '</span>';
    }

    public static function AddAdminScripts($Hook) 
    {
        global $post;
        if ($Hook == 'post-new.php' || $Hook == 'post.php') 
        {
            if ('product' === $post->post_type)
                wp_enqueue_script('officeguy-front', PLUGIN_DIR . 'includes/js/officeguy.js', array('jquery'));
        }
    }
}

add_filter('woocommerce_available_payment_gateways', 'OfficeGuySubscriptions::UpdateAvailablePaymentMethods');
add_filter('product_type_options', 'OfficeGuySubscriptions::AddProductType');
add_filter('woocommerce_product_data_tabs', 'OfficeGuySubscriptions::AddProductSettingsTab');
add_filter('woocommerce_product_data_panels', 'OfficeGuySubscriptions::ProductSettingsTab');
add_action('woocommerce_process_product_meta', 'OfficeGuySubscriptions::SaveProductFields');
add_action('admin_head', 'OfficeGuySubscriptions::ProductSettingsTabScript');
add_filter('woocommerce_get_price_html', 'OfficeGuySubscriptions::ProductPagePriceString', 10, 2);
add_filter('woocommerce_cart_item_subtotal', 'OfficeGuySubscriptions::CartPagePriceString', 10, 2);
add_filter('woocommerce_order_formatted_line_subtotal', 'OfficeGuySubscriptions::OrderPagePriceString', 10, 3);
add_filter('woocommerce_before_order_itemmeta', 'OfficeGuySubscriptions::AdminOrderPageProduct', 10, 3);
add_action('admin_enqueue_scripts', 'OfficeGuySubscriptions::AddAdminScripts', 10, 1);

?>