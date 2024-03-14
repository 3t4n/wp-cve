<div class="wobel-modal" id="wobel-modal-image">
    <div class="wobel-modal-container">
        <div class="wobel-modal-box wobel-modal-box-sm">
            <div class="wobel-modal-content">
                <div class="wobel-modal-title">
                    <h2><?php esc_html_e('Image Edit', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?> - <span id="wobel-modal-image-item-title" class="wobel-modal-item-title"></span></h2>
                    <button type="button" class="wobel-modal-close" data-toggle="modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-modal-body">
                    <div class="wobel-wrap">
                        <div class="wobel-inline-image-edit">
                            <button type="button" class="wobel-inline-uploader wobel-open-uploader" data-target="inline-edit" data-type="single" data-id="" data-item-id="">
                                <i class="wobel-icon-pencil"></i>
                            </button>
                            <div class="wobel-inline-image-preview" data-image-preview-id=""></div>
                            <input type="hidden" id="" class="wobel-image-preview-hidden-input">
                        </div>
                    </div>
                </div>
                <div class="wobel-modal-footer">
                    <button type="button" data-item-id="" data-field="" data-button-type="save" data-content-type="image" class="wobel-button wobel-button-blue wobel-edit-action-with-button" data-toggle="modal-close" data-image-url="" data-image-id="">
                        <?php esc_html_e('Apply Changes', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                    <button type="button" class="wobel-button wobel-button-red wobel-edit-action-with-button" data-button-type="remove" data-item-id="" data-image-url="<?php echo esc_url(WOBEL_IMAGES_URL . "no-image.png"); ?>" data-field="" data-image-id="0" data-toggle="modal-close">
                        <?php esc_html_e('Remove Image', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>