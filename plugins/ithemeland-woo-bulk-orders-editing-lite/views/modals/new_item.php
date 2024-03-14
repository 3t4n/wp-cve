<div class="wobel-modal" id="wobel-modal-new-item">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2 id="wobel-new-item-title"></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-form-group">
                            <label class="wobel-label-big" for="wobel-new-item-count" id="wobel-new-item-description"></label>
                            <input type="number" class="wobel-input-numeric-sm wobel-m0" id="wobel-new-item-count" value="1" placeholder="<?php esc_html_e('Number ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>">
                        </div>
                        <div id="wobel-new-item-extra-fields">
                            <?php if (!empty($new_item_extra_fields)) : ?>
                                <?php foreach ($new_item_extra_fields as $extra_field) : ?>
                                    <div class="wobel-form-group">
                                        <?php echo sprintf("%s", $extra_field['label']); ?>
                                        <?php echo sprintf("%s", $extra_field['field']); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" class="wobel-button wobel-button-blue" id="wobel-create-new-item"><?php esc_html_e('Create', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>