<div class="hjqs-ln-body">
    <div class="hjqs-ln-notice notice notice-warning">
        <h4><?php echo __('Important information' ,'hjqs-legal-notice') ?></h4>
        <p><?php echo __("The generator is for informational purposes only and is not a legal document. We cannot be held responsible for any missing text in your legal notices.",'hjqs-legal-notice') ?></p>
    </div>
    <div class="hjqs-ln-cards">
        <div class="hjqs-ln-card">
            <h2 class="title"><?php echo __("1. Complete the form", 'hjqs-legal-notice') ?></h2>
            <p><?php echo __("Fill out the form with all the required information about your business or website. This may include your name, address, phone number, email address, etc. Be sure to complete all fields in order to generate a complete and accurate legal content.", 'hjqs-legal-notice') ?></p>
        </div>
        <div class="hjqs-ln-card">
            <h2 class="title"><?php echo __("2. Insert the shortcode", 'hjqs-legal-notice') ?></h2>
            <p><?php echo __("You must insert the corresponding shortcode on the page where you want to display the content. To do this, open the page in question in the WordPress content editor and place a shortcode module. Then, insert the appropriate shortcode.", 'hjqs-legal-notice') ?></p>
        </div>
        <div class="hjqs-ln-card">
            <h2 class="title"><?php echo __("3. Customize the content", 'hjqs-legal-notice') ?></h2>
            <p><?php echo __("You can use the text editor to customize the content of the legal notices by modifying the basic text. You can also use formatting tools such as bold, underline, list buttons, etc. to highlight certain elements or add links to other pages.", 'hjqs-legal-notice') ?></p>
        </div>
    </div>

    <?php do_action('hjqs_legal_notice_before_form'); ?>

    <div class="hjqs-ln-form">
        <form id="<?php echo $form->get_slug(); ?>" method="post" action="options.php" class="<?php echo $form->get_slug(); ?>">
            <div class="hjqs-ln-submit">
	            <?php do_action('hjqs_legal_notice_before_actions_form'); ?>
                <button type="button" onclick="return clearForm(this, '<?php _e("Are you sure you want to clear the form data?", 'hjqs-legal-notice') ?>');" class="hjqs-button-error hide-if-no-js" data-nonce="<?php echo wp_create_nonce('hjqs_ln_clear'); ?>"><?php _e('Clear') ?><span class="spinner hjqs-ln-spinner"></span></button>
                <button type="button" onclick="previewForm(this)" class="button hide-if-no-js"><?php _e('Show'); ?></button>
                <?php submit_button(); ?>
	            <?php do_action('hjqs_legal_notice_after_actions_form'); ?>
            </div>
            <?php
            settings_fields( $form->get_slug() );
            do_settings_sections( $form->get_slug() );
            do_action( 'hjqs_legal_notice_extends_fields' );
            ?>
        </form>

	    <?php do_action('hjqs_legal_notice_after_form'); ?>
        <div class="hjqs-ln-modal">
            <div class="hjqs-ln-modal-content">
                <div class="hjqs-ln-modal-header">
                    <h1><?php _e('Preview'); ?></h1>
                    <button type="button" onclick="closePreview(this)" class="button hjqs-ln-modal-close"><?php _e('Close'); ?></button>
                </div>
                <div class="hjqs-ln-modal-body"></div>
            </div>
        </div>
    </div>
</div>

