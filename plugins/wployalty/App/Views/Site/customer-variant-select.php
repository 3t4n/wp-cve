<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined('ABSPATH') or die;
if (isset($available_products) && !empty($available_products)):
    ?>
    <div class="wlr-select-free-variant-product-toggle"><?php esc_html_e('Change Variant', 'wp-loyalty-rules') ?></div>
    <div class="wlr-select-variant-product">
        <?php
        foreach ($available_products as $available_product) { //parent_id
            if (isset($customer_chose_variant) && $available_product != $customer_chose_variant) {
                $product_variation = new WC_Product_Variation($available_product);
                // get variation featured image
                $variation_image = $product_variation->get_image(array(50, 50));
                ?>
                <div class="wlr_free_product_variants">
                    <span class="wlr_change_product" data-pid="<?php echo esc_attr($available_product); ?>"
                          data-rule_id="<?php echo isset($loyalty_user_reward_id) && $loyalty_user_reward_id ? esc_attr($loyalty_user_reward_id) : 0; ?>"
                          data-parent_id="<?php echo isset($parent_product_id) && !empty($parent_product_id) ? esc_attr($parent_product_id) : 0; ?>">
                        <span class="wlr_variation_image"><?php echo $variation_image; ?></span>
                        <span class="wlr-product-name"><?php echo get_the_title($available_product); ?></span>
                    </span>
                </div>
                <?php
            }
        }
        ?>
    </div>
<?php endif; ?>

