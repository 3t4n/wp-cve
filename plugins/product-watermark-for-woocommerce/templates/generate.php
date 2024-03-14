<h2><?php _e('2. Create images with watermarks', 'product-watermark-for-woocommerce') ?></h2>
<h3><?php _e('Replace images live', 'product-watermark-for-woocommerce') ?></h3>
<p>
    <label>
        <input type="checkbox" name="br-image_watermark-options[enable_live]" value="1"<?php if( ! empty($options['enable_live']) ) echo ' checked'; ?>>
        <?php _e('Add watermark to image when it will be loaded first time on shop page or product page.', 'product-watermark-for-woocommerce') ?>
    </label>
    <ul>
        <li><strong>+</strong> New images on products will be watermarked without any actions</li>
        <li><strong>-</strong> Slow down page load where images is not watermarked</li>
    </ul>
</p>
<h3><?php _e('Replace images via AJAX', 'product-watermark-for-woocommerce') ?></h3>
<div class="berocket_image_watermark_restore">
    <p><?php _e('You can restore images or add watermarks to all images on products.', 'product-watermark-for-woocommerce') ?></p>
    <p class="notice notice-error"><?php _e('With enabled option "Replace images live" all images will be replaced again', 'product-watermark-for-woocommerce') ?></p>
    <ol>
        <li><?php _e('Save settings to apply all changes to new watermarks', 'product-watermark-for-woocommerce') ?></li>
        <li><?php _e('Click "Regenerate" to replace old watermarks on watermarked images', 'product-watermark-for-woocommerce') ?></li>
        <li><?php _e('Create watermarked images(Click "Create" button and wait until end)', 'product-watermark-for-woocommerce') ?></li>
    </ol>
    <p class="notice notice-error"><?php _e('Do not close this page until end', 'product-watermark-for-woocommerce') ?></p>
    <a class="button br_create_restore_image global" data-generation="create" data-image_list="create"><?php _e('Create', 'product-watermark-for-woocommerce') ?></a>
    <a class="button br_create_restore_image global" data-generation="restore" data-image_list="restore"><?php _e('Restore', 'product-watermark-for-woocommerce') ?></a>
    <a class="button br_create_restore_image global" data-generation="create" data-image_list="restore"><?php _e('Regenerate', 'product-watermark-for-woocommerce') ?></a>
    <span class="berocket_watermark_spin" style="display:none;">
        <i class="fa fa-spinner fa-pulse fa-4x fa-fw"></i>
        <a class="button br_create_restore_image_stop" style="color:red;" data-generation="restore"><?php _e('Stop', 'product-watermark-for-woocommerce') ?></a>
    </span>
    <span class="berocket_watermark_ready" style="display:none;"><i class="fa fa-check fa-4x fa-fw"></i></span>
    <span class="berocket_watermark_error" style="display:none;"><i class="fa fa-times fa-4x fa-fw"></i></span>
    <div class="berocket_watermark_load" style="display:none;"><div class="berocket_line"></div><div class="berocket_watermark_action"></div></div>
    <div class="berocket_watermark_error_messages"></div>
</div>
