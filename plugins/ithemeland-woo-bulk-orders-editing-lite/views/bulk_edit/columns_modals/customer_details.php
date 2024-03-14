<div class="wobel-modal" id="wobel-modal-customer-details">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><span id="wobel-modal-customer-details-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-customer-details-items">
                            <div class="wobel-mb20">
                                <span class="dashicons dashicons-location"></span>
                                <span data-customer-field="address-1"></span>
                                <span data-customer-field="address-2"></span>
                                <span data-customer-field="city"></span>
                                <span data-customer-field="country"></span>
                            </div>
                            <div class="wobel-mb20">
                                <span class="dashicons dashicons-smartphone"></span>
                                <span data-customer-field="phone"></span>
                            </div>
                            <div>
                                <span class="dashicons dashicons-email-alt"></span>
                                <span data-customer-field="email"></span>
                            </div>
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