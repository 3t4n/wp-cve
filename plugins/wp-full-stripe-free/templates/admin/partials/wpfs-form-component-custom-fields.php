<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <div class="wpfs-field-list">
        <div id="wpfs-custom-fields" class="wpfs-field-list__list js-sortable">
        <?php for ( $idx = 1; $idx <= count( $data->customFieldLabels ); $idx++ ) { ?>
            <div class="wpfs-field-list__item" data-field-type="text-field" data-custom-field-name="<?php echo $data->customFieldLabels[ $idx-1 ]; ?>">
                <div class="wpfs-icon-expand-vertical-left-right wpfs-field-list__icon"></div>
                <div class="wpfs-field-list__info">
                    <div class="wpfs-field-list__title"><?php echo $data->customFieldLabels[ $idx-1 ]; ?></div>
                    <div class="wpfs-field-list__meta"><?php esc_html_e( 'Text field', 'wp-full-stripe-admin' ); ?></div>
                </div>
                <div class="wpfs-field-list__actions">
                    <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-delete-custom-field">
                        <span class="wpfs-icon-trash"></span>
                    </button>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
    <button class="wpfs-field-list__add js-add-custom-field" id="wpfs-add-custom-field">
        <div class="wpfs-icon-add-circle wpfs-field-list__icon"></div>
        <?php esc_html_e( 'Add field', 'wp-full-stripe-admin' ); ?>
    </button>
</div>
<div class="wpfs-form-group" id="wpfs-custom-fields-required">
    <label class="wpfs-form-label"><?php $view->makeCustomFieldsRequired()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->makeCustomFieldsRequired()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->customInputRequired == $options[0]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->customInputRequired == $options[1]->value(false) ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
        </div>
    </div>
</div>
<input id="<?php $view->customFields()->id(); ?>" name="<?php $view->customFields()->name(); ?>" value="" <?php $view->customFields()->attributes(); ?>>
<script type="text/template" id="wpfs-modal-add-custom-field">
    <form data-wpfs-form-type="<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_CUSTOM_FIELD; ?>" class="wpfs-custom-field-form">
        <div class="wpfs-dialog-scrollable">
            <div class="wpfs-form-group">
                <label class="wpfs-form-label"><?php esc_html_e('Field type', 'wp-full-stripe-admin'); ?></label>
                <span>Text</span>
            </div>
            <div class="wpfs-form-group">
                <label for="wpfs-custom-field-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_CUSTOM_FIELD; ?>" class="wpfs-form-label"><?php esc_html_e('Field label', 'wp-full-stripe-admin'); ?></label>
                <input id="wpfs-custom-field-label--<?php echo MM_WPFS::FORM_TYPE_ADMIN_ADD_CUSTOM_FIELD; ?>" class="wpfs-form-control" type="text" name="wpfs-custom-field-label">
            </div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <button class="wpfs-btn wpfs-btn-primary js-add-custom-field-dialog" type="submit"><?php esc_html_e('Add custom field', 'wp-full-stripe-admin'); ?></button>
            <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e('Discard', 'wp-full-stripe-admin'); ?></button>
        </div>
    </form>
</script>
<script type="text/template" id="wpfs-modal-delete-custom-field">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-delete-custom-field-dialog"><?php _e( 'Delete field', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep field', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-custom-field-template">
    <div class="wpfs-icon-expand-vertical-left-right wpfs-field-list__icon"></div>
    <div class="wpfs-field-list__info">
        <div class="wpfs-field-list__title"><%- name %></div>
        <div class="wpfs-field-list__meta"><%- typeLabel %></div>
    </div>
    <div class="wpfs-field-list__actions">
        <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-delete-custom-field">
            <span class="wpfs-icon-trash"></span>
        </button>
    </div>
</script>
