<?php
    /** @var $view MM_WPFS_Admin_EmailOptionsView */
    /** @var $emailOptions */
?>
<form <?php $view->formAttributes(); ?>>
    <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
    <input id="<?php $view->sendCopyToListHidden()->id(); ?>" name="<?php $view->sendCopyToListHidden()->name(); ?>" <?php $view->sendCopyToListHidden()->attributes(); ?>>
    <div class="wpfs-form__cols">
        <div class="wpfs-form__col">
            <div class="wpfs-form-group">
                <label for="" class="wpfs-form-label"><?php $view->fromAddress()->label(); ?></label>
                <div class="wpfs-form-check-list">
                    <div class="wpfs-form-check">
                        <?php
                            $isSenderAdmin = $emailOptions->adminEmail === $emailOptions->senderEmail;
                            $fromAddressAdmin = $view->fromAddress()->options()[0];
                        ?>
                        <input id="<?php $fromAddressAdmin->id(); ?>" name="<?php $fromAddressAdmin->name(); ?>" value="<?php $fromAddressAdmin->value(); ?>" <?php $fromAddressAdmin->attributes(); ?>  <?php echo $isSenderAdmin ? 'checked' : ''; ?>>
                        <label class="wpfs-form-check-label" for="<?php $fromAddressAdmin->id(); ?>"><?php $fromAddressAdmin->label(); ?></label>
                    </div>
                    <div class="wpfs-form-check">
                        <?php $fromAddressCustom = $view->fromAddress()->options()[1]; ?>
                        <input id="<?php $fromAddressCustom->id(); ?>" name="<?php $fromAddressCustom->name(); ?>" value="<?php $fromAddressCustom->value(); ?>" <?php $fromAddressCustom->attributes(); ?> <?php echo $isSenderAdmin ? '' : 'checked'; ?>>
                        <label class="wpfs-form-check-label" for="<?php $fromAddressCustom->id(); ?>"><?php $fromAddressCustom->label(); ?></label>
                        <input id="<?php $view->fromAddressCustom()->id(); ?>" name="<?php $view->fromAddressCustom()->name(); ?>" value="<?php echo $emailOptions->senderEmail; ?>" <?php $view->fromAddressCustom()->attributes(); ?> style="<?php echo $isSenderAdmin ? 'display: none;' : '' ?>">
                    </div>
                </div>
            </div>
            <div class="wpfs-form-group">
                <label for="" class="wpfs-form-label"><?php esc_html_e( 'Send copy of email notifications to', 'wp-full-stripe-admin' ); ?></label>
                <div class="wpfs-form-check-list">
                    <?php
                        $isCopyAdmin = array_search( $emailOptions->adminEmail, $emailOptions->bccEmails ) !== false;
                        $fromAddressAdmin = $view->fromAddress()->options()[0];
                    ?>
                    <div class="wpfs-form-check">
                        <input id="<?php $view->sendCopyToAdmin()->id(); ?>" name="<?php $view->sendCopyToAdmin()->name(); ?>" value="<?php $view->sendCopyToAdmin()->value(); ?>" <?php $view->sendCopyToAdmin()->attributes(); ?> <?php echo $isCopyAdmin ? 'checked' : ''; ?>>
                        <label class="wpfs-form-check-label" for="<?php $view->sendCopyToAdmin()->id(); ?>"><?php $view->sendCopyToAdmin()->label(); ?></label>
                    </div>
                </div>
                <?php
                    $bccAddresses = array_diff( $emailOptions->bccEmails, array( $emailOptions->adminEmail ));
                ?>
                <div class="wpfs-tags-input-wrapper js-tags-input">
                <?php foreach ( $bccAddresses as $bccAddress  ) { ?>
                    <div class="wpfs-tag wpfs-tag--removable">
                        <?php echo $bccAddress; ?>
                        <button class="wpfs-btn wpfs-btn-icon wpfs-btn-icon--12 wpfs-tag__remove js-remove-tag">
                            <span class="wpfs-icon-close"></span>
                        </button>
                    </div>
                <?php } ?>
                    <input id="<?php $view->sendCopyToList()->id(); ?>" name="<?php $view->sendCopyToList()->name(); ?>" value="<?php $view->sendCopyToList()->value(); ?>" <?php $view->sendCopyToList()->attributes(); ?> placeholder="<?php $view->sendCopyToList()->placeholder(); ?>">
                </div>
            </div>
            <div class="wpfs-form-actions">
                <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
            </div>
        </div>
        <div class="wpfs-form__col">
            <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                <div class="wpfs-inline-message__inner">
                    <div class="wpfs-inline-message__title"><?php esc_html_e( 'Configure plugin email notifications', 'wp-full-stripe-admin' ); ?></div>
                    <p><?php esc_html_e( 'These settings apply to plugin email notifications only.', 'wp-full-stripe-admin' ); ?></p>
                    <p>
                        <a class="wpfs-btn wpfs-btn-link" href="https://support.paymentsplugin.com/article/28-configuring-email-notifications" target="_blank"><?php esc_html_e( 'Learn more about Email notifications', 'wp-full-stripe-admin' ); ?></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>