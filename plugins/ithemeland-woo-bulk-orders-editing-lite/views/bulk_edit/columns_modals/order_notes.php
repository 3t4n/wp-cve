<div class="wobel-modal" id="wobel-modal-order-notes">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Order', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> <span id="wobel-modal-order-notes-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-col-7">
                            <h3><?php esc_html_e('Notes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h3>
                            <div id="wobel-modal-order-notes-items"></div>
                            <div class="wobel-modal-order-notes-add-note">
                                <label><?php esc_html_e('New Note', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                <textarea id="wobel-modal-order-notes-content" placeholder="<?php esc_html_e('Note', 'ithemeland-woocommerce-bulk-orders-editing-lite') . ' ...'; ?>"></textarea>
                                <select id="wobel-modal-order-notes-type">
                                    <option value="private"><?php esc_html_e('Private note', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                    <option value="customer"><?php esc_html_e('Note to customer', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                                </select>
                                <button type="button" class="wobel-button wobel-button-blue" id="wobel-modal-order-notes-add" data-order-id=""><?php esc_html_e('Add Note', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
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