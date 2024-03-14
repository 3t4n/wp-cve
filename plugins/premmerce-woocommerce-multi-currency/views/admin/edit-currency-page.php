<?php defined('WPINC') || die; ?>


<div class="wrap">
    <h1><?php _e('Edit currency', 'premmerce-woocommerce-multicurrency'); ?></h1>
    <div class="form-wrap">
        <div id="thickbox-content">
            <form id="update-currency" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                  class="validate">
                <input type="hidden" name="action" value="update-currencies">
                <input type="hidden" name="currency_id" value="<?php echo $currencyData['id']; ?>">

                <?php
                wp_nonce_field('premmerce-currency-update');
                echo $formFields;
                ?>
                <div class="edit-tag-actions">

                    <?php
                    submit_button(__('Update'), 'primary', 'submitBtn', false);
                    if ((int) $mainCurrency === (int) $currencyData['id']) {
                        $button = '<span class="delete">' . __('Delete',
                                'premmerce-woocommerce-multicurrency') . '</span>';
                    } else {
                        $href = wp_nonce_url(sprintf('%s?action=%s&currency_id=%s', admin_url('admin-post.php'),
                            'delete-currency', $currencyData['id']), 'premmerce-currency-delete');
                        $button = '<a class="delete premmerce-currency-delete" href="' . $href . '">' . __('Delete',
                                'premmerce-woocommerce-multicurrency') . '</a>';
                    }
                    ?>
                    <span id="delete-link">
                        <?php echo $button; ?>
                    </span>
                </div>

            </form>
        </div>
    </div>
</div>