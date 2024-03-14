<?php
defined('ABSPATH') || exit;
?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-return-to-shop">
        <p class="return-to-shop">
            <a class="button wc-backward"
               href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
				<?php esc_html_e('Return to shop', 'shopengine-gutenberg-addon'); ?>
            </a>
        </p>
    </div>
</div>
