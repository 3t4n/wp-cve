<div class="wobel-modal" id="wobel-modal-numeric-calculator">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Calculator', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> - <span id="wobel-modal-numeric-calculator-item-title" class="wobel-modal-product-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <select id="wobel-numeric-calculator-operator" title="<?php esc_html_e('Select Operator', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                            <option value="+">+</option>
                            <option value="-">-</option>
                            <option value="replace"><?php esc_html_e('replace', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></option>
                        </select>
                        <input type="number" placeholder="<?php esc_html_e('Enter Value ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" id="wobel-numeric-calculator-value" title="<?php esc_html_e('Value', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-field-type="" data-toggle="modal-close" class="wobel-button wobel-button-blue wobel-edit-action-numeric-calculator">
                        <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>