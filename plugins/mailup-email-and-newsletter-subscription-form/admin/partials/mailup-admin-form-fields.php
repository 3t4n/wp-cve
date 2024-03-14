<form id="mailup-form-fields" class="mailup-form" name="mailup-form" method="get" novalidate="novalidate" action>
    <?php require __DIR__.'/mailup-admin-fields.php'; ?>
    <br>
    <div class="separator-with-border"></div>
    <?php require __DIR__.'/mailup-admin-terms.php'; ?>
    <div class="separator-with-border"></div>

    <input type="submit" id="form-save" name="save" value="<?php _e('Save'); ?>" class="button button-primary">
    <span class="spinner"></span>
    <span class="feedback"></span>
</form>