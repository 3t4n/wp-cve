<div class="wobel-modal" id="wobel-modal-file">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-lg">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Select File', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> - <span id="wobel-modal-select-file-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-inline-select-files">
                            <div class="wcbe-modal-select-files-file-item">
                                <input type="text" class="wobel-inline-edit-file-url wobel-w60p" id="wobel-file-url" placeholder="File Url ..." value="">
                                <button type="button" class="wobel-button wobel-button-white wobel-open-uploader wobel-inline-edit-choose-file" data-type="single" data-target="inline-file-custom-field"><?php esc_html_e('Choose File', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
                                <input type="hidden" id="wobel-file-id" value="">
                                <button type="button" class="wobel-button wobel-button-white" id="wobel-modal-file-clear"><?php esc_html_e('Clear', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" id="wobel-modal-file-apply" data-item-id="" data-field="" data-content-type="file" class="wobel-button wobel-button-blue wobel-edit-action-with-button" data-toggle="modal-close">
                        <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>