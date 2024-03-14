<form id="mailup-form-advanced-settings" class="mailup-form" name="mailup-form" method="get" novalidate="novalidate"
    action>
    <h2><?php _e('Other Settings', 'mailup'); ?>
    </h2>
    <table class="form-table advanced-settings">
        <tbody>
            <tr>
                <td><label><?php _e('Request confirmation by email', 'mailup'); ?>:</label>
                </td>
                <td><input name="email-comfirmation" id="email-comfirmation" type="checkbox" <?php if ($setting_mup->confirm) {
                    echo 'checked';
                } ?>>
                </td>
            </tr>
            <tr>
                <td><label><?php _e('Use placeholders instead of labels', 'mailup'); ?>:</label>
                </td>
                <td><input name="placeholders-no-labels" id="placeholders-no-labels" type="checkbox" <?php if ($setting_mup->placeholder) {
                    echo 'checked';
                } ?>>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="separator-with-border"></div>
    <h2><?php esc_attr_e('Messages', 'mailup'); ?>
    </h2>
    <span class="info"><?php _e('Edit default messages for subscription status.', 'mailup'); ?></span>
    <table class="form-table messages">
        <tbody>
            <tr>
                <td>
                    <label><?php _e('Successful registration', 'mailup'); ?>:</label>
                </td>
                <td>
                    <input name="success-message" id="success-message" type="text" class="long_input"
                        value="<?php echo $messages['success-message']; ?>" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label><?php _e('Generic Error', 'mailup'); ?>:</label>
                </td>
                <td>
                    <input name="generic-error-message" id="generic-error-message" type="text" class="long_input"
                        value="<?php echo $messages['generic-error-message']; ?>" required>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="separator-with-border"></div>
    <div class="custom-css">
        <h2><?php _e('Custom CSS', 'mailup'); ?>
        </h2>
        <p><label><?php _e('Insert here your custom CSS:', 'mailup'); ?></label>
        <p>
            <textarea name="custom-css" id="custom-css" class="full_text-area">
<?php if (empty($setting_mup->custom_css)) { ?>
     /* Main container*/
	#mpwp-container {

	}
    /* Form content container */
	#mupwp-form-content {

	}

    /* The <form> */
	#mupwp-form {

	}

    /* The form title */
	#mupwp-form-title {

	}

    /* The form description */
	#mupwp-form-description {

	}

    /* Fields container */
	#mupwp-form-fields {

	}

    /*Single field container*/
    #mupwp-form-fields .mupwp-form-field {

    }

    /* Terms and Conditions container */
	#mupwp-form-terms {

	}

    /* Single terms and condition container*/
	#mupwp-form-terms .mupwp-form-term {

	}

    /*Used for terms and conditions font size. Default = 0.85 rem*/
    #mpwp-container .label.terms.small-font {

    }

    /*Submit form, ajax loader and form messages container*/
    #mupwp-form-submit-container {

    }

    /*Submit button*/
    #mupwp-form-save {

    }
	<?php } else {
	    echo $setting_mup->custom_css;
	}
    ?></textarea>
    </div>
    <div class="separator-with-border"></div>
    <input type="submit" id="form-save-advanced-settings" name="save" value="<?php _e('Save'); ?>"
        class="button button-primary">
    <span class="spinner"></span>
    <span class="feedback"></span>
</form>
<?php include __DIR__.'/mailup-reset-tokens.php';
