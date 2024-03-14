<?php
/** @var $backLinkUrl */
/** @var $logDownloadUrl */
/** @var $levelView MM_WPFS_Admin_LogLevel_View */
/** @var $emptyView MM_WPFS_Admin_LogEmpty_View */
/** @var $logData */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-logs">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <form <?php $emptyView->formAttributes(); ?>>
                            <input id="<?php $emptyView->action()->id(); ?>" name="<?php $emptyView->action()->name(); ?>" value="<?php $emptyView->action()->value(); ?>" <?php $emptyView->action()->attributes(); ?>>
                            <div class="wpfs-form-block__title"><?php esc_html_e( 'Log entries', 'wp-full-stripe-admin' ); ?></div>
                            <div class="wpfs-form-group">
                                <?php
                                    $thereAreEntriesLabel = sprintf( __( 'There are %d entries in the log', 'wp-full-stripe-admin' ), number_format_i18n( $logData->logEntryCount ));
                                    $logIsEmptyLabel = __( 'The log is empty', 'wp-full-stripe-admin' );
                                ?>
                                <label class="wpfs-form-label"><?php esc_html_e( $logData->logEntryCount > 0 ? $thereAreEntriesLabel : $logIsEmptyLabel ) ; ?></label>
                            </div>
                            <div class="wpfs-form-group">
                                <a class="wpfs-btn wpfs-btn-primary js-download-log" href="<?php echo $logDownloadUrl ?>"><?php esc_html_e( 'Download log', 'wp-full-stripe-admin' ); ?></a>
                                <button class="wpfs-btn wpfs-btn-text js-empty-log" type="submit"><?php esc_html_e( 'Empty log', 'wp-full-stripe-admin' ); ?></button>
                            </div>
                        </form>
                    </div>
                    <div class="wpfs-form-block">
                        <form <?php $levelView->formAttributes(); ?>>
                            <input id="<?php $levelView->action()->id(); ?>" name="<?php $levelView->action()->name(); ?>" value="<?php $levelView->action()->value(); ?>" <?php $levelView->action()->attributes(); ?>>
                            <div class="wpfs-form-block__title"><?php esc_html_e( 'Log settings', 'wp-full-stripe-admin' ); ?></div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $levelView->logLevel()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <?php $options = $levelView->logLevel()->options(); ?>
                                    <?php foreach ( $options as $option ) { ?>
                                        <div class="wpfs-form-check">
                                            <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?> value="<?php $option->value(); ?>" <?php echo $logData->logLevel == $option->value(false) ? 'checked' : ''; ?>>
                                            <label class="wpfs-form-check-label" for="<?php $option->id(); ?>"><?php $option->label(); ?></label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $levelView->logChannel()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <?php $options = $levelView->logChannel()->options(); ?>
                                    <?php $option = $options[0]; ?>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?> value="<?php $option->value(); ?>" checked>
                                        <label class="wpfs-form-check-label" for="<?php $option->id(); ?>"><?php $option->label(); ?></label>
                                    </div>
                                    <?php $option = $options[1]; ?>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?> value="<?php $option->value(); ?>" <?php echo $logData->logToWebServer == $option->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $option->id(); ?>"><?php $option->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="wpfs-form-group">
                                <label class="wpfs-form-label"><?php $levelView->behavior()->label(); ?></label>
                                <div class="wpfs-form-check-list">
                                    <?php $options = $levelView->behavior()->options(); ?>
                                    <?php $option = $options[0]; ?>
                                    <div class="wpfs-form-check">
                                        <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?> value="<?php $option->value(); ?>" <?php echo $logData->catchUncaughtErrors == $option->value(false) ? 'checked' : ''; ?>>
                                        <label class="wpfs-form-check-label" for="<?php $option->id(); ?>"><?php $option->label(); ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="wpfs-form-group">
                                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <div id="wpfs-success-message-container"></div>
    </div>
    <div id="wpfs-dialog-container"></div>
    <?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>

<script type="text/template" id="wpfs-success-message">
    <div class="wpfs-floating-message__inner">
        <div class="wpfs-floating-message__message"><%- successMessage %></div>
        <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
            <span class="wpfs-icon-close"></span>
        </button>
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
<script type="text/template" id="wpfs-modal-empty-log">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><%- confirmationMessage %></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger js-empty-log-dialog"><?php _e( 'Empty log', 'wp-full-stripe-admin'); ?></button>
        <button class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Keep log entries', 'wp-full-stripe-admin' ); ?></button>
    </div>
</script>
<script type="text/template" id="wpfs-modal-empty-log-in-progress">
    <div class="wpfs-dialog-scrollable">
        <p class="wpfs-dialog-content-text"><?php _e('Deleting entries from the log...', 'wp-full-stripe-admin'); ?></p>
    </div>
    <div class="wpfs-dialog-content-actions">
        <button class="wpfs-btn wpfs-btn-danger wpfs-btn-danger--loader" disabled><?php _e('Empty log', 'wp-full-stripe-admin'); ?></button>
    </div>
</script>
