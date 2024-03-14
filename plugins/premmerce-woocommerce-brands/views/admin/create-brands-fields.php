<?php if ( ! defined( 'WPINC' ) ) die; ?>

<div class="form-field term-thumbnail-wrap">
    <label><?php _e('Thumbnail', 'woocommerce'); ?></label>
    <div data-type="brands_thumbnail" class="brands_thumbnail"><img width="60" height="60" src="<?= esc_url(wc_placeholder_img_src()); ?>"/></div>
    <div class="brands_thumbnail_buttons">
        <input type="hidden" data-type="brands_thumbnail_id" name="brands_thumbnail_id"/>
        <button type="button" data-type="upload_image" class="upload_image_button button"><?php _e('Upload/Add image', 'woocommerce'); ?></button>
        <button type="button" data-type="remove_image" class="remove_image_button button" style="display: none"><?php _e('Remove image', 'woocommerce'); ?></button>
    </div>

    <div field-name="choose-image" field-value="<?php _e("Choose an image", "woocommerce"); ?>"></div>
    <div field-name="use-image" field-value="<?php _e("Use image", "woocommerce"); ?>"></div>
    <div field-name="placeholder-img-src" field-value="<?= esc_js(wc_placeholder_img_src()); ?>"></div>

    <div class="clear"></div>
</div>
