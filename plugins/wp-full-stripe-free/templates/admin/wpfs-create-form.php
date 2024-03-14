<?php
    /** @var $view MM_WPFS_Admin_CreateFormView */
?>
<div class="wrap">
    <div class="wpfs-page wpfs-page-payment-forms">
        <?php include('partials/wpfs-header-with-back-link.php'); ?>
        <?php include('partials/wpfs-announcement.php'); ?>

        <form <?php $view->formAttributes(); ?>>
            <input id="<?php $view->action()->id(); ?>" name="<?php $view->action()->name(); ?>" value="<?php $view->action()->value(); ?>" <?php $view->action()->attributes(); ?>>
            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <div class="wpfs-form-block__title"><?php
                            /* translators: Section title on the Create form page */ _e('Basic info', 'wp-full-stripe-admin'); ?></div>
                    </div>
                </div>
            </div>

            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <div class="wpfs-form-group">
                            <label for="" class="wpfs-form-label"><?php $view->displayName()->label(); ?></label>
                            <input id="<?php $view->displayName()->id(); ?>" name="<?php $view->displayName()->name(); ?>" type="text" class="wpfs-form-control js-to-pascal-case" value="" data-to-pascal-case="#<?php $view->name()->id(); ?>">
                        </div>
                        <div class="wpfs-form-group">
                            <label for="" class="wpfs-form-label"><?php $view->name()->label(); ?></label>
                            <input id="<?php $view->name()->id(); ?>" name="<?php $view->name()->name(); ?>" class="wpfs-form-control" type="text" value="">
                        </div>
                    </div>
                </div>
                <div class="wpfs-form__col">
                    <div class="wpfs-inline-message wpfs-inline-message--info wpfs-inline-message--w448">
                        <div class="wpfs-inline-message__inner">
                            <p><?php
                                /* translators: Description of what the form display name is */
                                _e( "<strong>Display name</strong> shows up in the form list, and helps you identify the form.", 'wp-full-stripe-admin' ); ?></p>
                            <p><?php
                                /* translators: Description of what the form identifier is */
                                _e( "<strong>Identifier</strong> is used to insert the form into pages via a shortcode. Use alphanumerical characters, underscores and dashes, without spaces.", 'wp-full-stripe-admin' );?></p>
                            <p>
                                <a class="wpfs-btn wpfs-btn-link" target="_blank" href="https://support.paymentsplugin.com/article/27-how-to-use-form-shortcodes"><?php _e( "Learn more about form shortcodes", 'wp-full-stripe-admin' ); ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wpfs-form__cols">
                <div class="wpfs-form__col">
                    <div class="wpfs-form-block">
                        <div class="wpfs-form-block__title"><?php $view->type()->label(); ?></div>
                        <?php foreach ( $view->type()->options() as $option ) {
                            /* @var $option MM_WPFS_Control */
                            ?>
                        <div class="wpfs-form-check wpfs-form-check--block">
                            <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?>/>
                            <label class="wpfs-form-check-label" for="<?php $option->id(); ?>">
                                <span class="wpfs-form-check-label__title"><?php $option->label(); ?></span>
                                <span class="wpfs-form-check-label__desc"><?php echo $option->metadata()['description']; ?></span>
                                <span class="<?php echo $option->metadata()['iconClass']; ?> wpfs-form-check-label__illu"></span>
                            </label>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="wpfs-form-block">
                        <div class="wpfs-form-block__title"><?php $view->layout()->label(); ?></div>
                        <?php foreach ( $view->layout()->options() as $option ) {
                            /* @var $option MM_WPFS_Control */
                            ?>
                            <div class="wpfs-form-check wpfs-form-check--block">
                                <input id="<?php $option->id(); ?>" name="<?php $option->name(); ?>" <?php $option->attributes(); ?>/>
                                <label class="wpfs-form-check-label" for="<?php $option->id(); ?>">
                                    <span class="wpfs-form-check-label__title"><?php $option->label(); ?></span>
                                    <span class="wpfs-form-check-label__desc"><?php echo $option->metadata()['description']; ?></span>
                                    <span class="<?php echo $option->metadata()['iconClass']; ?> wpfs-form-check-label__illu"></span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="wpfs-form-actions">
                        <button type="submit" class="wpfs-btn wpfs-btn-primary wpfs-button-loader"><?php _e( 'Create & edit form', 'wp-full-stripe-admin' ); ?></button>
                        <a href="<?php /** @noinspection PhpUndefinedVariableInspection */ echo $backLinkUrl; ?>" class="wpfs-btn wpfs-btn-text js-close-this-dialog"><?php _e( 'Discard', 'wp-full-stripe-admin' ); ?></a>
                    </div>
                </div>
            </div>
        </form>
        <div id="wpfs-success-message-container"></div>
    </div>
    <script type="text/template" id="wpfs-success-message">
        <div class="wpfs-floating-message__inner">
            <div class="wpfs-floating-message__message"><%- successMessage %></div>
            <button class="wpfs-btn wpfs-btn-icon js-hide-flash-message">
                <span class="wpfs-icon-close"></span>
            </button>
        </div>
    </script>
	<?php include( 'partials/wpfs-demo-mode.php' ); ?>
</div>
