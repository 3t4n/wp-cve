<?php

use Valued\WordPress\WooCommerce;

?>
<form method="POST" action="">
    <div class="wrap">
        <h2><?= $plugin->getName(); ?></h2>
        <?php
        if ($updated) {
            echo '<div class=updated><p>', _e('Your changes have been saved.', 'webwinkelkeur'), '</p></div>';
        }
        foreach ($errors as $error) {
            echo '<div class=error><p>', $error, '</p></div>';
        }
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="wwk-shop-id"><?php _e('Shop ID', 'webwinkelkeur'); ?></label></th>
                <td><input name="<?= $plugin->getOptionName('wwk_shop_id');?>" type="text" id="wwk-shop-id" value="<?= esc_html($config['wwk_shop_id']); ?>" class="regular-text" pattern="[0-9]+" required /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="wwk-api-key"><?php _e('API key', 'webwinkelkeur'); ?></label></th>
                <td><input name="<?= $plugin->getOptionName('wwk_api_key');?>" type="text" id="wwk-api-key" value="<?= esc_html($config['wwk_api_key']); ?>" class="regular-text" />
                <p class="description">
                <?php printf(
            __('You\'ll find this information after logging in on %s.', 'webwinkelkeur'),
            sprintf(
                '<a href="%s" target="_blank">%s</a>',
                "https://{$plugin->getDashboardDomain()}/?ref=wordpress",
                $plugin->getName()
            )
        ); ?>
                </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="webwinkelkeur-javascript"><?php _e('JavaScript integration', 'webwinkelkeur'); ?></label></th>
                <td>
                    <label>
                        <input type="hidden" name="<?= $plugin->getOptionName('javascript'); ?>" value="">
                        <input type="checkbox" id="webwinkelkeur-javascript" name="<?= $plugin->getOptionName('javascript');?>" value="1" <?= $config['javascript'] ? 'checked' : ''; ?> />
                        <?php printf(__('Yes, add the %s JavaScript to my website.', 'webwinkelkeur'), $plugin->getName()); ?>
                    </label>
                    <p class="description">
                    <?php printf(
            __('Use the JavaScript integration to add the sidebar and the tooltip to your website.<br>All settings for the sidebar and tooltip are located on the %s.', 'webwinkelkeur'),
            sprintf(
                '<a href="%s" target="_blank">%s</a>',
                "https://{$plugin->getDashboardDomain()}/integration",
                sprintf(__('%s Dashboard', 'webwinkelkeur'), $plugin->getName())
            )
        ); ?>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Invitations', 'webwinkelkeur'); ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" name="<?= $plugin->getOptionName('invite');?>" value="<?= WooCommerce::AFTER_EVERY_ORDER ?>" <?= $config['invite'] == WooCommerce::AFTER_EVERY_ORDER ? 'checked' : ''; ?> />
                            <?php _e('Yes, email after every order. (default)', 'webwinkelkeur'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="<?= $plugin->getOptionName('invite');?>" value="<?= WooCommerce::AFTER_FIRST_ORDER ?>" <?= $config['invite'] == WooCommerce::AFTER_FIRST_ORDER ? 'checked' : ''; ?> />
                            <?php _e('Yes, email after a customer\'s first order.', 'webwinkelkeur'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="<?= $plugin->getOptionName('invite');?>" value="<?= Woocommerce::POPUP_OPTION ?>" <?= $config['invite'] == Woocommerce::POPUP_OPTION ? 'checked' : ''; ?> />
                            <?php _e('Yes, pop-up after purchase at "thank you" page. Privacy first review option: order data will only be shared after permission from the customer.', 'webwinkelkeur'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="<?= $plugin->getOptionName('invite');?>" value="<?= WooCommerce::DO_NOT_SEND ?>" <?= $config['invite'] == WooCommerce::DO_NOT_SEND ? 'checked' : ''; ?> />
                            <?php _e('No, don\'t send invitations.', 'webwinkelkeur'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label><?php _e('Order status for invitations', 'webwinkelkeur'); ?></label>
                </th>
                <td>
                    <?php if (!$plugin->isWoocommerceActivated()): ?>
                        <p class="description"><?php _e('Install and activate WooCommerce to use this functionality.', 'webwinkelkeur'); ?></p>
                    <?php else: ?>
                        <fieldset>
                            <div class="webwinkelkeur-order-statuses">
                                <?php foreach (wc_get_order_statuses() as $key => $label): ?>
                                    <label>
                                        <input type="checkbox" name="<?= $plugin->getOptionName('order_statuses[]'); ?>"
                                               value="<?= $key; ?>" <?= in_array($key, $config['order_statuses']) ? 'checked' : ''; ?>>
                                        <?= $label; ?>
                                    </label> <br>
                                <?php endforeach; ?>
                            </div>
                            <p class="description">
                                <?php _e('The invitation is only sent when the order has the checked status.', 'webwinkelkeur'); ?>
                            </p>
                        </fieldset>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="hidden" name="<?= $plugin->getOptionName('limit_order_data'); ?>" value="">
                            <input type="checkbox" name="<?= $plugin->getOptionName('limit_order_data');?>" value="1" <?= $config['limit_order_data'] ? 'checked ' : ''; ?> />
                            <?= esc_html(sprintf(
                                __('Do not send my order information to %s (checking this option disables product reviews!).', 'webwinkelkeur'),
                                $plugin->getName()
        )); ?>
                            <p class="description">
                                <?= esc_html(sprintf(
            __('Please note: not all %s functionality will be available if you check this option!', 'webwinkelkeur'),
            $plugin->getName()
        )); ?>
                            </p>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="webwinkelkeur-invite-delay"><?php _e('Invitation delay', 'webwinkelkeur'); ?></label></th>
                <td><input name="<?= $plugin->getOptionName('invite_delay');?>" type="number" id="webwinkelkeur-invite-delay" value="<?= esc_html($config['invite_delay']); ?>" class="small-text" />
                <p class="description">
                <?php _e('The invitation will be send after the specified amount of days since the order has been shipped.', 'webwinkelkeur'); ?>
                </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Product reviews', 'webwinkelkeur'); ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" name="<?= $plugin->getOptionName('product_reviews'); ?>" value="1" <?php if ($config['product_reviews']) {
                                echo 'checked ';
                            } ?> />
                            <?= esc_html(
                                __('Import product reviews to WooCommerce.', 'webwinkelkeur')
                            ); ?>
                            <p class="description">
                                <?= esc_html(
                                    sprintf(__('Automatically display product reviews collected using %s on your WooCommerce shop.', 'webwinkelkeur'), $plugin->getName())
                                ); ?>
                            </p>
                        </label>
                    </fieldset>
                    <fieldset>
                        <label>
                            <input type="checkbox" name="<?= $plugin->getOptionName('product_reviews_multisite'); ?>" value="1" <?= $config['product_reviews_multisite'] ? 'checked' : ''; ?> />
                            <?= esc_html(
                                __('Sync to Multisite', 'webwinkelkeur')
                            ); ?>
                            <p class="description">
                                <?= esc_html(
                                    sprintf(__('Enable sync product reviews to multisite setup. Product will be matched by ID and SKU.', 'webwinkelkeur'), $plugin->getName())
                                ); ?>
                            </p>
                        </label>
                    </fieldset>
                    <fieldset>
                        <label>
                            <button class="button webwinkelkeur-sync-btn" type="button"
                                    <?= !$config['product_reviews'] ? 'disabled' : ''; ?>
                            >
                                <?= __('Sync manually', 'webwinkelkeur'); ?>
                            </button>
                            <button class="button webwinkelkeur-sync-btn" type="button" data-all="yes"
                                    <?= !$config['product_reviews'] ? 'disabled' : ''; ?>
                            >
                                <?= __('Sync all reviews manually', 'webwinkelkeur'); ?>
                            </button>
                            <div id='successful-sync' hidden></div>
                        </label> <br>
                        <p> <?= __('Last sync', 'webwinkelkeur'); ?>: <b><?= $plugin->woocommerce->getLastReviewSync(); ?></b>
                        </p>
                        <p> <?= __('Next sync', 'webwinkelkeur'); ?>: <b><?= $plugin->woocommerce->getNextReviewSync(); ?></b>
                        </p>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td>
                    <label>
                        GTIN/EAN key:
                        <select disabled hidden name="<?= htmlentities($plugin->getOptionName('custom_gtin')) ?>">
                            <option value=""><?= htmlentities($plugin->getActiveGtinPlugin() ? sprintf('%s (%s)',
                                    __('Automatic detection', 'webwinkelkeur'),
                                    explode('/', $plugin->getActiveGtinPlugin())[0] ?? ''
                                ) : 'Select key'); ?></option>
                            <optgroup class="webwinkelkeur-suggested-keys" label="<?= _e('Suggested keys', 'webwinkelkeur'); ?>">
                            </optgroup>
                            <optgroup class="webwinkelkeur-other-keys" label="<?= _e('Other keys', 'webwinkelkeur'); ?>">
                            </optgroup>
                        </select>
                    </label>
                    <span class="spinner is-active"></span>
                    <p class="description">
                        <?=
                        __('Tell this plugin where to find the product <strong>GTIN</strong> by selecting a custom key. For example: if you use a field called <strong>_productcode</strong> to store the <strong>GTIN</strong>, you should select  <strong>_product_code</strong>. Our plugin also supports certain 3rd party plugins. If we found a supported plugin, this box is set to <strong>Automatic detection</strong>, you can still choose to select another key.', 'webwinkelkeur')
                        ?>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="webwinkelkeur-javascript"><?php _e('Add rich snippet', 'webwinkelkeur'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" id="webwinkelkeur-rich-snippet" name="<?= $plugin->getOptionName('rich_snippet');?>" value="1" <?= $config['rich_snippet'] ? 'checked' : ''; ?> />
                        <?php _e('Yes, add a rich snippet to the footer of my website.', 'webwinkelkeur'); ?>
                    </label>
                    <p class="description">
                        <?php _e('This allows Google to show your rating in the search results. Use at your own risk.', 'webwinkelkeur'); ?>
                        <a target="_blank" href="https://support.google.com/webmasters/answer/99170?hl=nl"><?php _e('More information.', 'webwinkelkeur'); ?></a>
                    </p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
        <?php wp_nonce_field($plugin->getOptionName('options_nonce')); ?>
    </div>
</form>
<script>
    (function ($) {
        $.get(
            "admin-ajax.php",
            <?= json_encode([
                'action' => $plugin->woocommerce->getProductKeysAction(),
                'selected_key' => $config["custom_gtin"],
            ]); ?>
        ).done(function (response) {
            if (!response || response.status === undefined) {
                alert('Could not load product keys.');
                return;
            }
            var options = response.data;
            for (var property in options) {
               if (options.hasOwnProperty(property)) {
                   var option = new Option(
                       options[property].label,
                       options[property].option_value,
                       options[property].selected,
                       options[property].selected
                   );
                   $(`.${options[property].suggested ? "webwinkelkeur-suggested-keys" : "webwinkelkeur-other-keys"}`)
                       .append(option);
               }
            }
            $(".spinner").removeClass("is-active");
            $(<?= json_encode(sprintf('[name="%s"]', $plugin->getOptionName("custom_gtin"))); ?>)
                .prop({"disabled": false, "hidden": false})
        });

        function triggerManualSync(all) {
            var $success = $("#successful-sync");
            var $buttons = $('.webwinkelkeur-sync-btn');

            $success.show();
            $success[0].className = '';
            $success.text(<?= json_encode(__("Syncing product reviews...", 'webwinkelkeur')); ?>);

            $buttons.prop('disabled', true);

            $.post(
                "admin-ajax.php",
                {
                    action: <?= json_encode($plugin->woocommerce->getManualSyncAction()); ?>,
                    sync_all: all ? 'yes' : 'no',
                    _ajax_nonce:
                        <?= json_encode(wp_create_nonce($plugin->woocommerce->getManualSyncNonce())); ?>
                }
            ).done(function (response) {
                console.log(response);
                if (response && response.status !== undefined) {
                    $success.show().html(response.message);
                    $success[0].className =
                        response.status ? 'webwinkelkeur-success' : 'webwinkelkeur-error';
                } else {
                    alert('Something went wrong with syncing.');
                }
            }).always(function () {
                $buttons.prop('disabled', false);
            });
        }

        $(document).on('click', '.webwinkelkeur-sync-btn', function (ev) {
            ev.preventDefault();
            triggerManualSync(this.dataset.all === 'yes');
        });
    })(jQuery);
</script>
<style>
    .webwinkelkeur-order-statuses {
        display: flex;
        flex-wrap: wrap;
        max-width: 600px;
    }

    .webwinkelkeur-order-statuses label {
        width: 200px;
    }

    .webwinkelkeur-success {
        color: #0ED826;
    }

    .webwinkelkeur-error {
        color: red;
    }

    .spinner {
        float: none;
    }
</style>
