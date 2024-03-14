<?php
    /** @var $view MM_WPFS_Admin_FormsAppearanceView */
    /** @var $formsAppearance */
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <input id="<?php $view->customCssHidden()->id(); ?>" name="<?php $view->customCssHidden()->name(); ?>" <?php $view->customCssHidden()->attributes(); ?>>
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <div class="wpfs-form-group">
                <label for="<?php $view->customCss()->id(); ?>" class="wpfs-form-label wpfs-form-label--actions">
                    <?php esc_html_e( 'Custom CSS styles', 'wp-full-stripe-admin' ); ?>
                </label>
                <textarea id="<?php $view->customCss()->id(); ?>" name="<?php $view->customCss()->name(); ?>" <?php $view->customCss()->attributes(); ?>>
                    <?php echo esc_html( $formsAppearance->customCss ); ?>
                </textarea>
            </div>
            <div class="wpfs-form-actions">
                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
            </div>
        </div>
        <div class="wpfs-form__col">
            <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                <div class="wpfs-inline-message__inner">
                    <div class="wpfs-inline-message__title"><?php esc_html_e( 'About custom CSS styles', 'wp-full-stripe-admin' ); ?></div>
                    <p><?php esc_html_e( "Add styling to your forms. Proceed only if you know what you're doing!", 'wp-full-stripe-admin' ); ?></p>
                    <p>
                        <a class="wpfs-btn wpfs-btn-link" href="https://support.paymentsplugin.com/article/45-customizing-forms-with-css" target="_blank"><?php esc_html_e( 'Learn more about custom CSS styles', 'wp-full-stripe-admin' ); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>
