<div class="wobel-modal" id="wobel-modal-text-editor">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-lg">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Content Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> - <span id="wobel-modal-text-editor-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <?php wp_editor("", 'wobel-text-editor'); ?>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" data-field="" data-item-id="" data-content-type="textarea" id="wobel-text-editor-apply" class="wobel-button wobel-button-blue wobel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>