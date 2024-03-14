<div class="wobel-modal" id="wobel-modal-order-details">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Order', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> <span id="wobel-modal-order-details-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                    <div class="wobel-order-details-status" data-order-field="status"></div>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-col-half">
                            <h3><?php esc_html_e('Billing Details', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                            <div class="wobel-mb20">
                                <span data-order-field="billing-address-index"></span>
                            </div>
                            <div class="wobel-mb20">
                                <div><strong><?php esc_html_e('Email', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong></div>
                                <div data-order-field="billing-email"></div>
                            </div>
                            <div class="wobel-mb20">
                                <div><strong><?php esc_html_e('Phone', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong></div>
                                <div data-order-field="billing-phone"></div>
                            </div>
                            <div class="wobel-mb20">
                                <div><strong><?php esc_html_e('Payment Via', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong></div>
                                <span data-order-field="payment-via"></span>
                            </div>
                        </div>
                        <div class="wobel-col-half">
                            <h3><?php esc_html_e('Shipping Details', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                            <div class="wobel-mb20">
                                <span data-order-field="shipping-address-index"></span>
                            </div>
                            <div class="wobel-mb20">
                                <div><strong><?php esc_html_e('Shipping Method', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></strong></div>
                                <span data-order-field="shipping-method"></span>
                            </div>
                        </div>
                        <div class="wobel-order-details-items">
                            <table>
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Product', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                        <th><?php esc_html_e('Quantity', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                        <th><?php esc_html_e('Tax', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                        <th><?php esc_html_e('Total', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" class="wobel-button wobel-button-blue" data-toggle="modal-close">
                        <?php esc_html_e('Close', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>