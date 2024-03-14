<?php
if (!defined('ABSPATH')) {
    exit;
}



$product_tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($product_tabs)) :
    global $product;
    $currenttabs = get_post_meta($product->get_ID(), '_responsive_tabs_woo_layouts');
    if (empty($currenttabs) || $currenttabs == '') :
        $currenttabs = get_option('responsive_tabs_woocommerce_default');
    endif;
    if ((int) $currenttabs) :
        $tabs = [];
        $i = 0;
        foreach ($product_tabs as $key => $product_tab) :
            $tabs[$i] = $key;
            $i++;
        endforeach;

        echo '<div class="woocommerce-tabs wc-tabs-wrapper">';
        $render = new \TABS_RES_PLUGINS\Modules\Shortcode();
        $render->render($currenttabs, 'woocommerce', $product_tabs, $tabs);
        echo '</div>';
        do_action('woocommerce_product_after_tabs');
    else :
?>
        <div class="woocommerce-tabs wc-tabs-wrapper">
            <ul class="tabs wc-tabs" role="tablist">
                <?php foreach ($product_tabs as $key => $product_tab) : ?>
                    <li class="<?php echo esc_attr($key); ?>_tab" id="tab-title-<?php echo esc_attr($key); ?>" role="tab" aria-controls="tab-<?php echo esc_attr($key); ?>">
                        <a href="#tab-<?php echo esc_attr($key); ?>">
                            <?php echo wp_kses_post(apply_filters('woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key)); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php foreach ($product_tabs as $key => $product_tab) : ?>
                <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr($key); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr($key); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr($key); ?>">
                    <?php
                    if (isset($product_tab['callback'])) {
                        call_user_func($product_tab['callback'], $key, $product_tab);
                    }
                    ?>
                </div>
            <?php endforeach; ?>

            <?php do_action('woocommerce_product_after_tabs'); ?>
        </div>
<?php
    endif;

endif;
