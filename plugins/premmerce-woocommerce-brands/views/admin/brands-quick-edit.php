<?php if ( ! defined( 'WPINC' ) ) die; ?>

<fieldset class="inline-edit-col-right">
    <div class="inline-edit-col">
        <span class="title"><?php _e('Brands', 'premmerce-brands'); ?></span>
        <select name="product_brand" id="">
            <option value="0" selected><?php _e('Not specified', 'premmerce-brands'); ?></option>

            <?php foreach ($brands as $brand) : ?>
                <option value="<?= $brand->slug; ?>"><?= $brand->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</fieldset>
