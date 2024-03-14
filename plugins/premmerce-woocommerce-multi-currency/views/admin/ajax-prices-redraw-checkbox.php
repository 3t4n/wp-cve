<?php defined('WPINC') || die; ?>


<div class="form-field">
    <p>
        <label><input id="ajax-prices_redraw" type="checkbox" value="1"
                      name="premmerce_multicurrency_ajax_prices_redraw" <?php checked(1,
                $val); ?> ><?php _e('I\'m using cache plugin', 'premmerce-woocommerce-multicurrency'); ?></label>
    </p>

    <p class="description"> <?php _e('Clear cache on your caching plugin settings page to make this option take effect.',
            'premmerce-woocommerce-multicurrency'); ?></p>
</div>