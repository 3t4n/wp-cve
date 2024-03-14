<div class="wobel-modal" id="wobel-modal-order-items">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Order Items', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> <span id="wobel-modal-order-items-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-modal-body-content">
                            <div class="wobel-order-items-loading">
                                <p><img src="<?php echo esc_url(WOBEL_IMAGES_URL . 'loading-2.gif'); ?>" width="34"></p>
                            </div>
                            <div class="wobel-col-full wobel-mb20">
                                <div class="wobel-order-items-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><?php esc_html_e('Product', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                                <th><?php esc_html_e('Quantity', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" class="wobel-button wobel-button-blue wobel-float-right" data-toggle="modal-close">
                        <?php esc_html_e('Close', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>