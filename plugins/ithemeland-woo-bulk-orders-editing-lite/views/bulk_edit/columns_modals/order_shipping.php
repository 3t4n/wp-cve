<div class="wobel-modal" id="wobel-modal-order-shipping">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Order', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> <span id="wobel-modal-order-shipping-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-col-full wobel-mb20">
                            <h3><?php esc_html_e('Shipping', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                            <a href="javascript:;" class="wobel-modal-load-shipping-address" data-target="#wobel-modal-order-shipping" data-order-field="customer-user-id" data-customer-id=""><?php esc_html_e('Load shipping address', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></a>
                            <span> | </span>
                            <a href="javascript:;" href="javascript:;" class="wobel-modal-load-billing-address" data-target="#wobel-modal-order-shipping" data-order-field="customer-user-id" data-customer-id=""><?php esc_html_e('Copy billing address', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></a>
                        </div>
                        <div class="wobel-col-half">
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-first-name"><?php esc_html_e('First Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-first-name" data-order-field="first-name">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-address-1"><?php esc_html_e('Address 1', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-address-1" data-order-field="address-1">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-city"><?php esc_html_e('City', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-city" data-order-field="city">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-country"><?php esc_html_e('Country', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <select type="text" id="order-shipping-modal-country" class="wobel-order-country" data-state-target="#wobel-modal-order-shipping-state" data-order-field="country">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                    <?php if (!empty($shipping_countries) && is_array($shipping_countries)) : ?>
                                        <?php foreach ($shipping_countries as $shipping_country_key => $shipping_country_label) : ?>
                                            <option value="<?php echo esc_attr($shipping_country_key); ?>"><?php echo esc_html($shipping_country_label); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="wobel-col-half">
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-last-name"><?php esc_html_e('Last Name', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-last-name" data-order-field="last-name">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-address-2"><?php esc_html_e('Address 2', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-address-2" data-order-field="address-2">
                            </div>
                            <div class="wobel-mb10">
                                <label for="wobel-modal-order-shipping-state"><?php esc_html_e('State', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <select id="wobel-modal-order-shipping-state" data-order-field="state">
                                    <option value=""><?php esc_html_e('Select', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                </select>
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-postcode"><?php esc_html_e('Postcode', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-postcode" data-order-field="postcode">
                            </div>
                        </div>
                        <div class="wobel-col-full wobel-mb20">
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-company"><?php esc_html_e('Company', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <input type="text" id="order-shipping-modal-company" data-order-field="company">
                            </div>
                            <div class="wobel-mb10">
                                <label for="order-shipping-modal-customer-note"><?php esc_html_e('Customer Note', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <textarea id="order-shipping-modal-customer-note" data-order-field="customer-note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" class="wobel-button wobel-button-blue wobel-modal-order-shipping-save-changes-button" data-toggle="modal-close">
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