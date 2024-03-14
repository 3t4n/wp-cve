<div class="wobel-float-side-modal" id="wobel-float-side-modal-meta-fields">
    <div class="wobel-float-side-modal-container">
        <div class="wobel-float-side-modal-box">
            <div class="wobel-float-side-modal-content">
                <div class="wobel-float-side-modal-title">
                    <h2><?php esc_html_e('Meta Fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></h2>
                    <button type="button" class="wobel-float-side-modal-close" data-toggle="float-side-modal-close">
                        <i class="wobel-icon-x"></i>
                    </button>
                </div>
                <div class="wobel-float-side-modal-body" style="height: calc(100% - 45px);">
                    <div class="wobel-wrap">
                        <div class="wobel-tab-middle-content">
                            <div class="wobel-alert wobel-alert-default">
                                <span><?php esc_html_e('You can add new orders meta fields in two ways: 1- Individually 2- Get from other order.', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                            </div>
                            <div class="wobel-alert wobel-alert-danger">
                                <span class="wobel-lh36">This option is not available in Free Version, Please upgrade to Pro Version</span>
                                <a href="<?php echo esc_url(WOBEL_UPGRADE_URL); ?>"><?php echo esc_html(WOBEL_UPGRADE_TEXT); ?></a>
                            </div>
                            <div class="wobel-meta-fields-left">
                                <div class="wobel-meta-fields-manual">
                                    <label for="wobel-meta-fields-manual_key_name"><?php esc_html_e('Manually', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <div class="wobel-meta-fields-manual-field">
                                        <input type="text" id="wobel-meta-fields-manual_key_name" placeholder="<?php esc_html_e('Enter Meta Key ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" disabled="disabled">
                                        <button type="button" class="wobel-button wobel-button-square wobel-button-blue" disabled="disabled">
                                            <i class="wobel-icon-plus1 wobel-m0"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="wobel-meta-fields-automatic">
                                    <label for="wobel-add-meta-fields-order-id"><?php esc_html_e('Automatically From Order', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></label>
                                    <div class="wobel-meta-fields-automatic-field">
                                        <input type="text" id="wobel-add-meta-fields-order-id" placeholder="<?php esc_html_e('Enter Order ID ...', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?>" disabled="disabled">
                                        <button type="button" class="wobel-button wobel-button-square wobel-button-blue" disabled="disabled">
                                            <i class="wobel-icon-plus1 wobel-m0"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                <div class="wobel-meta-fields-right" id="wobel-meta-fields-items">
                                    <p class="wobel-meta-fields-empty-text" <?php echo (!empty($meta_fields)) ? 'style="display:none";' : ''; ?>><?php esc_html_e("Please add your meta key manually", 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?><br> <?php esc_html_e("OR", 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?><br><?php esc_html_e("From another order", 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></p>
                                    <?php if (!empty($meta_fields)) : ?>
                                        <?php foreach ($meta_fields as $meta_field) : ?>
                                            <?php include WOBEL_VIEWS_DIR . 'meta_field/meta_field_item.php'; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="droppable-helper"></div>
                                </div>
                                <div class="wobel-meta-fields-buttons">
                                    <div class="wobel-meta-fields-buttons-left">
                                        <button type="button" disabled="disabled" class="wobel-button wobel-button-lg wobel-button-blue">
                                            <?php $img = WOBEL_IMAGES_URL . 'save.svg'; ?>
                                            <img src="<?php echo esc_url($img); ?>" alt="">
                                            <span><?php esc_html_e('Save Fields', 'ithemeland-woocommerce-bulk-orders-editing-lite'); ?></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>