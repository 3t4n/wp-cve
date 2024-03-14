<?php if ( ! defined( 'WPINC' ) ) die; ?>

<p>
    <label for="<?= $widget->get_field_id('title') ?>"><?php _e('Title'); ?></label>
    <input id="<?= $widget->get_field_id('title'); ?>" class="widefat" type="text" name="<?=
    $widget->get_field_name('title'); ?>" value="<?= $title; ?>">
</p>
<p>
    <label for="<?= $widget->get_field_id('mode') ?>"><?php _e('Mode', 'premmerce-brands'); ?></label>
    <select id="<?= $widget->get_field_id('mode') ?>" name="<?=
    $widget->get_field_name('mode'); ?>" class="widefat premmerce-carousel-select">
        <option value="auto" <?php selected(($mode == 'auto') ? 1 : 0); ?>><?php _e('Auto', 'premmerce-brands'); ?></option>
        <option value="custom" <?php selected(($mode == 'custom') ? 1 : 0); ?>><?php _e('Custom', 'premmerce-brands'); ?></option>
    </select>
</p>
<div data-select="premmerce-filter-auto" <?= ($mode == 'auto') ?: 'style="display: none"'; ?>>
    <p>
        <input id="<?= $widget->get_field_id('only_photo') ?>" type="checkbox" <?php checked($onlyPhoto ? 1 : 0); ?> name="<?=
        $widget->get_field_name('only_photo'); ?>">
        <label for="<?= $widget->get_field_id('only_photo'); ?>"><?php _e('Only with photo', 'premmerce-brands'); ?></label>
    </p>
    <p>
        <label for="<?= $widget->get_field_id('limit') ?>"><?php _e('Limit', 'premmerce-brands'); ?></label>
        <input id="<?= $widget->get_field_id('limit'); ?>" class="tiny-text" type="number" min="0" name="<?=
        $widget->get_field_name('limit'); ?>" value="<?= $limit; ?>">
    </p>
</div>
<div data-select="premmerce-filter-custom" <?= ($mode == 'custom') ?: 'style="display: none"'; ?>>
    <p>
        <label for="<?= $widget->get_field_id('selected[]') ?>"><?php _e('Selected', 'premmerce-brands'); ?></label>
        <select style="width: 100%;" data-select="brands" id="<?= $widget->get_field_id('selected[]'); ?>" name="<?=
        $widget->get_field_name('selected[]'); ?>" multiple>
            <?php foreach ($brands as $brand) : ?>

                <option value="<?= $brand->term_id; ?>" <?php selected(in_array($brand->term_id, $selected) ? 1 : 0); ?>><?= $brand->name; ?></option>

            <?php endforeach; ?>
        </select>
    </p>
</div>
