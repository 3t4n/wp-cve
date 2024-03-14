<input type="hidden" name="wp_screen_options[option]" value="wpil_keyword_options" />
<input type="hidden" name="wp_screen_options[value]" value="yes" />
<fieldset class="screen-options">
    <legend>Options</legend>
    <input type="hidden" name="wpil_keyword_options[hide_select_links_column]" value="off"/>
    <input type="checkbox" name="wpil_keyword_options[hide_select_links_column]" id="hide_select_links_column" <?=$hide_select_links ? 'checked' : ''?>/>
    <label for="hide_select_links_column">Hide Possible Links Column?&nbsp;&nbsp;&nbsp;</label>
</fieldset>
<fieldset class="screen-options">
    <legend>Pagination</legend>
    <label for="per_page">Posts per page</label>
    <input type="number" step="1" min="1" max="999" maxlength="3" name="wpil_keyword_options[per_page]" id="per_page" value="<?=esc_attr($per_page)?>" />
</fieldset>
<br>
<?=$button?>
<?php wp_nonce_field( 'screen-options-nonce', 'screenoptionnonce', false, false ); ?>