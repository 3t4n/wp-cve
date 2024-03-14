<?php
    /** @var $view MM_WPFS_Admin_InlineFormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label for="<?php $view->cardFieldLanguage()->id(); ?>" class="wpfs-form-label"><?php $view->cardFieldLanguage()->label(); ?></label>
    <div class="wpfs-ui wpfs-form-select">
        <select id="<?php $view->cardFieldLanguage()->id(); ?>" name="<?php $view->cardFieldLanguage()->name(); ?>" <?php $view->cardFieldLanguage()->attributes(); ?>>
            <option value="<?php echo MM_WPFS::PREFERRED_LANGUAGE_AUTO; ?>"><?php esc_html_e( 'Auto', 'wp-full-stripe-admin' ); ?></option>
            <?php foreach ( $data->cardFieldLanguages as $language ) { ?>
            <option value="<?php echo $language['value']; ?>" <?php echo $language['value'] === $form->preferredLanguage ? 'selected': ''; ?>><?php echo $language['name']; ?></option>
            <?php } ?>
        </select>
    </div>
</div>
