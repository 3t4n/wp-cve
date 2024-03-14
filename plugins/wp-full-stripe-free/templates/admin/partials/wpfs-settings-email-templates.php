<?php
    /** @var $view MM_WPFS_Admin_EmailTemplatesView */
    /** @var $emailTemplates */
?>
<form class="wpfs-form" <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <input id="<?php $view->emailTemplatesHidden()->id(); ?>" name="<?php $view->emailTemplatesHidden()->name(); ?>" <?php $view->emailTemplatesHidden()->attributes(); ?>>
    <div class="wpfs-form__cols wpfs-form__cols--templates">
        <div class="wpfs-form__col">
            <div class="wpfs-list-title"><?php esc_html_e( 'Available templates:', 'wp-full-stripe-admin' ); ?></div>
            <div class="wpfs-list wpfs-list--sm">
            <?php foreach ( $emailTemplates->templates as $template ) { ?>
                <a class="wpfs-list__item js-email-template" href="" data-template-id="<?php echo $template->id; ?>" data-template-name="<?php echo $template->caption; ?>">
                    <div class="wpfs-list__text">
                        <div class="wpfs-list__title"><?php echo esc_html( $template->caption ); ?></div>
                    </div>
                </a>
            <?php } ?>
            </div>
        </div>
        <div class="wpfs-form__col">
            <div id="wpfs-email-template-container"></div>
            <div class="wpfs-form-block">
                <div class="wpfs-form-actions">
                    <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="wpfs-dialog-container"></div>
<script type="text/template" id="wpfs-email-template">
    <div class="wpfs-form-block__title"><%- name %></div>
    <div class="wpfs-form-group">
        <label for="" class="wpfs-form-label wpfs-form-label--actions">
            <?php esc_html_e( 'Subject', 'wp-full-stripe-admin' ); ?>
            <div class="wpfs-form-label__actions">
                <a class="wpfs-btn wpfs-btn-link js-insert-token-subject" href="#"><?php esc_html_e( 'Insert token', 'wp-full-stripe-admin' ); ?></a>
            </div>
        </label>
        <input id="wpfs-email-template-subject" class="wpfs-form-control js-subject-position-tracking js-token-target-subject" type="text" value="<%- subject %>">
    </div>
    <div id="wpfs-insert-token-dialog" class="wpfs-dialog-content js-insert-token-dialog" title="<?php esc_html_e( 'Insert token', 'wp-full-stripe-admin' ); ?>">
        <div class="wpfs-dialog-token-list">
            <div class="wpfs-form-group">
                <input class="wpfs-form-control js-token-autocomplete" type="text" placeholder="<?php esc_html_e( 'Search token', 'wp-full-stripe-admin' ); ?>">
            </div>
        </div>
    </div>
    <div class="wpfs-form-group">
        <label for="" class="wpfs-form-label wpfs-form-label--actions">
            <?php esc_html_e( 'Body', 'wp-full-stripe-admin' ); ?>
            <div class="wpfs-form-label__actions">
                <% if (isTestSendingSupported) { %><a class="wpfs-btn wpfs-btn-link js-send-email-test" href="#"><?php esc_html_e( 'Send a test', 'wp-full-stripe-admin' ); ?></a><% } %>
                <a class="wpfs-btn wpfs-btn-link js-reset-template" href="#"><?php esc_html_e( 'Reset template', 'wp-full-stripe-admin' ); ?></a>
                <a class="wpfs-btn wpfs-btn-link js-insert-token-body" href="#"><?php esc_html_e( 'Insert token', 'wp-full-stripe-admin' ); ?></a>
            </div>
        </label>
        <div class="wpfs-preview-iframe">
            <div class="wpfs-preview-iframe__head"></div>
            <textarea id="wpfs-email-template-body" class="js-body-position-tracking js-token-target-body"><%- body %></textarea>
        </div>
    </div>
</script>
<script type="text/template" id="wpfs-modal-reset-template">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-reset-template-dialog"><?php esc_html_e( 'Reset template', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e( 'Keep template', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-reset-template-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php esc_html_e('Restoring the default template bundled with the plugin', 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php esc_html_e('Reset template', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-send-email-test">
    <form data-wpfs-form-type="<?php echo MM_WPFS::FORM_TYPE_ADMIN_SEND_TEST_EMAIL; ?>" class="wpfs-send-test-email-form">
        <div class="wpfs-dialog-scrollable">
            <div class="wpfs-tags-input-wrapper js-tags-input">
                <input id="wpfs-test-email-addresses--<?php echo MM_WPFS::FORM_TYPE_ADMIN_SEND_TEST_EMAIL ?>" class="wpfs-tags-input" type="text" name="wpfs-test-email-addresses" placeholder="<?php esc_html_e('Add email addresses', 'wp-full-stripe-admin'); ?>">
            </div>
            <div class="wpfs-typo-body wpfs-typo-body--sm"><?php esc_html_e('Use space to separate email addresses', 'wp-full-stripe-admin'); ?></div>
        </div>
        <div class="wpfs-dialog-content-actions">
            <button class="wpfs-btn wpfs-btn-primary js-send-email-test-dialog"><?php esc_html_e('Send test', 'wp-full-stripe-admin'); ?></button>
            <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php esc_html_e('Discard', 'wp-full-stripe-admin'); ?></button>
        </div>
    </form>
</script>
<script type="text/template" id="wpfs-modal-send-email-test-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php esc_html_e('Sending email to the specified addresses', 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php esc_html_e('Send test', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-dialog-error">
    <div class="wpfs-dialog-scrollable">
        <div class="wpfs-inline-message wpfs-inline-message--error">
            <div class="wpfs-inline-message__inner">
                <strong><%- errorMessage %></strong>
            </div>
        </div>
    </div>
    <div class="wpfs-dialog-content-actions">
        <a class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Close', 'wp-full-stripe-admin' ); ?></a>
    </div>
</script>
