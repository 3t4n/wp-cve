<?php
/** @var $backLinkUrl */
/** @var $view MM_WPFS_Admin_WordpressDashboardView */
/** @var $wpDashboardData */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-settings-wp-dashboard">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <form <?php $view->formAttributes(); ?>>
            <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php $view->decimalSeparator()->label(); ?></label>
                            <div class="wpfs-form-check-list">
                                <div class="wpfs-form-check">
                                    <?php $options = $view->decimalSeparator()->options(); ?>
                                    <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->decimalSeparator == $options[0]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                </div>
                                <div class="wpfs-form-check">
                                    <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->decimalSeparator == $options[1]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php $view->useSymbolNotCode()->label(); ?></label>
                            <div class="wpfs-form-check-list">
                                <div class="wpfs-form-check">
                                    <?php $options = $view->useSymbolNotCode()->options(); ?>
                                    <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->useSymbolNotCode == $options[0]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                </div>
                                <div class="wpfs-form-check">
                                    <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->useSymbolNotCode == $options[1]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php $view->currencySymbolAtFirstPosition()->label(); ?></label>
                            <div class="wpfs-form-check-list">
                                <div class="wpfs-form-check">
                                    <?php $options = $view->currencySymbolAtFirstPosition()->options(); ?>
                                    <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->currencySymbolAtFirstPosition == $options[0]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                </div>
                                <div class="wpfs-form-check">
                                    <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->currencySymbolAtFirstPosition == $options[1]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="wpfs-form-group">
                            <label class="wpfs-form-label"><?php $view->putSpaceBetweenSymbolAndAmount()->label(); ?></label>
                            <div class="wpfs-form-check-list">
                                <div class="wpfs-form-check">
                                    <?php $options = $view->putSpaceBetweenSymbolAndAmount()->options(); ?>
                                    <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $wpDashboardData->putSpaceBetweenSymbolAndAmount == $options[0]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
                                </div>
                                <div class="wpfs-form-check">
                                    <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $wpDashboardData->putSpaceBetweenSymbolAndAmount == $options[1]->value(false) ? 'checked' : ''; ?>>
                                    <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpfs-form-actions">
                        <button class="wpfs-btn wpfs-btn-primary wpfs-button-loader" type="submit"><?php esc_html_e( 'Save settings', 'wp-full-stripe-admin' ); ?></button>
                        <a href="<?php echo $backLinkUrl; ?>" class="wpfs-btn wpfs-btn-text"><?php esc_html_e( 'Cancel', 'wp-full-stripe-admin' ); ?></a>
                    </div>
                </div>
                <div class="wpfs-form__col">
                    <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                        <div class="wpfs-inline-message__inner">
                            <div class="wpfs-inline-message__title"><?php esc_html_e( 'What are these settings for?', 'wp-full-stripe-admin' ); ?></div>
                            <p><?php esc_html_e( 'Options on this page control how payment amounts, dates, etc. are localized on the WordPress dashboard pages of WP Full Pay.', 'wp-full-stripe-admin' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div id="wpfs-success-message-container"></div>
    </div>
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
