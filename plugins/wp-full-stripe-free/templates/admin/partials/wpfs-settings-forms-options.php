<?php
    /** @var $view MM_WPFS_Admin_FormsOptionsView */
    /** @var $formsOptions */

    $articleUrl = "https://support.paymentsplugin.com/article/47-pre-fill-form-fields-via-url-parameters";
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <div class="wpfs-form-block">
                <div class="wpfs-form-group">
                    <label class="wpfs-form-label"><?php esc_html_e( 'Prefill form fields', 'wp-full-stripe-admin' ); ?></label>
                    <div class="wpfs-form-check-list">
                        <div class="wpfs-form-check">
                            <input id="<?php $view->fillInEmailForLoggedInUsers()->id(); ?>" name="<?php $view->fillInEmailForLoggedInUsers()->name(); ?>" value="<?php $view->fillInEmailForLoggedInUsers()->value(); ?>" <?php $view->fillInEmailForLoggedInUsers()->attributes(); ?> <?php echo $formsOptions->fillInEmailForUsers == $view->fillInEmailForLoggedInUsers()->value(false) ? 'checked' : ''; ?>>
                            <label class="wpfs-form-check-label" for="<?php $view->fillInEmailForLoggedInUsers()->id(); ?>"><?php $view->fillInEmailForLoggedInUsers()->label(); ?></label>
                        </div>
                    </div>
                    <div class="wpfs-form-check-list">
                        <div class="wpfs-form-check">
                            <input id="<?php $view->setFormFieldsViaUrlParameters()->id(); ?>" name="<?php $view->setFormFieldsViaUrlParameters()->name(); ?>" value="<?php $view->setFormFieldsViaUrlParameters()->value(); ?>" <?php $view->setFormFieldsViaUrlParameters()->attributes(); ?> <?php echo $formsOptions->setFormFieldsViaUrlParameters == $view->setFormFieldsViaUrlParameters()->value(false) ? 'checked' : ''; ?>>
                            <label class="wpfs-form-check-label" for="<?php $view->setFormFieldsViaUrlParameters()->id(); ?>"><?php $view->setFormFieldsViaUrlParameters()->label(); ?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpfs-form__col">
            <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                <div class="wpfs-inline-message__inner">
                    <p><?php echo sprintf( __('For prefilling form fields in a more granular manner, please <a href="%s" target="_blank">refer to our knowledge base article</a>.', 'wp-full-stripe-admin' ), $articleUrl ); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <div class="wpfs-form-actions">
                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
            </div>
        </div>
    </div>
</form>
