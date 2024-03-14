<input type="hidden" name="wp_screen_options[option]" value="report_options" />
<input type="hidden" name="wp_screen_options[value]" value="yes" />
<fieldset class="screen-options">
    <legend><?php _e('Options', 'wpil'); ?></legend>
    <input type="checkbox" name="report_options[show_categories]" id="show_categories" <?=$show_categories ? 'checked' : ''?>/>
    <label for="show_categories"><?php _e('Show categories', 'wpil'); ?></label>
    <input type="checkbox" name="report_options[show_type]" id="show_type" <?=$show_type ? 'checked' : ''?>/>
    <label for="show_type"><?php _e('Show post type', 'wpil'); ?></label>
    <input type="checkbox" name="report_options[show_date]" id="show_date" <?=$show_date ? 'checked' : ''?>/>
    <label for="show_date"><?php _e('Show the post publish date', 'wpil'); ?></label>
</fieldset>
<fieldset class="screen-options">
    <legend><?php _e('Pagination', 'wpil'); ?></legend>
    <label for="per_page"><?php _e('Posts per page', 'wpil'); ?></label>
    <input type="number" step="1" min="1" max="999" maxlength="3" name="report_options[per_page]" id="per_page" value="<?=esc_attr($per_page)?>" />
</fieldset>
<br>
<?=$button?>
<?php wp_nonce_field( 'screen-options-nonce', 'screenoptionnonce', false, false ); ?>
