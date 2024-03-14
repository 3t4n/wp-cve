<?php

?>
<p>
<?php wp_nonce_field('aforms_restricted_save', 'aforms_metabox_nonce'); ?>
<input type="hidden" name="aforms_restricted_hidden" value="a">
<input type="checkbox" name="aforms_restricted" value="do_restricted" <?= $output ? 'checked' : '' ?>>
<?= __('Show this page only after AForms form submission', 'aforms') ?>
</p>