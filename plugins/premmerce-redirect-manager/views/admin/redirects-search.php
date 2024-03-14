<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<p class="search-box">
    <label class="screen-reader-text" for="<?= esc_attr($inputId); ?>"><?= $text; ?>:</label>
    <input type="search" id="<?= esc_attr($inputId); ?>" name="s" value="<?php _admin_search_query(); ?>" />
    <?php submit_button($text, '', '', false, ['id' => 'search-submit']); ?>
</p>