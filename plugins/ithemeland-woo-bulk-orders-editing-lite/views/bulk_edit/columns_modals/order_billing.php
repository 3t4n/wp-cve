<div class="wobel-modal" id="wobel-modal-order-billing">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Order', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> <span id="wobel-modal-order-billing-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-col-full wobel-mb20">
                            <h3><?php esc_html_e('Billing', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                            <a href="javascript:;" data-target="#wobel-modal-order-billing" data-order-field="customer-user-id" data-customer-id="" class="wobel-modal-load-billing-address"><?php esc_html_e('Load billing address', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></a>
                        </div>
                        <div class="wobel-col-half">
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-first-name"><?php esc_html_e('First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-first-name" data-order-field="first-name">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-address-1"><?php esc_html_e('Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-address-1" data-order-field="address-1">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-city"><?php esc_html_e('City', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-city" data-order-field="city">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-country"><?php esc_html_e('Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <select id="order-billing-modal-country" class="wobel-order-country" data-state-target="#wobel-modal-order-billing-state" data-order-field="country">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                    <?php if (!empty($shipping_countries) && is_array($shipping_countries)) : ?>
                                        <?php foreach ($shipping_countries as $shipping_country_key => $shipping_country_label) : ?>
                                            <option value="<?php echo esc_attr($shipping_country_key); ?>"><?php echo esc_html($shipping_country_label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-email"><?php esc_html_e('Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-email" data-order-field="email">
                            </div>
                        </div>
                        <div class="wobel-col-half">
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-last-name"><?php esc_html_e('Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-last-name" data-order-field="last-name">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-address-2"><?php esc_html_e('Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-address-2" data-order-field="address-2">
                            </div>
                            <div class="wobel-mb10">
                                <label for="wobel-modal-order-billing-state"><?php esc_html_e('State', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <select id="wobel-modal-order-billing-state" data-order-field="state">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                </select>
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-postcode"><?php esc_html_e('Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-postcode" data-order-field="postcode">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-phone"><?php esc_html_e('Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-phone" data-order-field="phone">
                            </div>
                        </div>
                        <div class="wobel-col-full wobel-mb20">
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-company"><?php esc_html_e('Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-company" data-order-field="company">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-payment-method"><?php esc_html_e('Payment Method', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <select id="order-billing-modal-payment-method" data-order-field="payment-method">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                    <?php if (!empty($payment_methods) && is_array($payment_methods)) : ?>
                                        <?php foreach ($payment_methods as $payment_method_key => $payment_method_title) : ?>
                                            <option value="<?php echo esc_attr($payment_method_key); ?>"><?php echo esc_html($payment_method_title); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <option value="other"><?php esc_html_e('Other', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                </select>
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-billing-modal-transaction-id"><?php esc_html_e('Transaction ID', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-billing-modal-transaction-id" data-order-field="transaction-id">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" class="wobel-button wobel-button-blue wobel-modal-order-billing-save-changes-button" data-toggle="modal-close">
                        <?php esc_html_e('Save Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                    <button type="button" class="wobel-button wobel-button-gray wobel-float-right" data-toggle="modal-close">
                        <?php esc_html_e('Close', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>