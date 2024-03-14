jQuery(document).ready(function ($) {
    "use strict";
    // Export Functionality.
    $('.lpc-export-button').click(function (e) {
        e.preventDefault();
        window.location.href = lpcExportURL.exportURL;  // This URL will be localized (see PHP part below).
    });
    // Import Functionality.
    $('.lpc-import-button').click(function (e) {
        e.preventDefault();

        var fileInput = $('.lpc-import-file');
        var file = fileInput[0].files[0];

        if (!file) {
            alert('Please choose a file to import.');
            return;
        }

        var formData = new FormData();
        formData.append('lpc-import-file', file);
        formData.append('action', 'lpc_import_customizer_settings');
        formData.append('nonce', lpcAjax.nonce);

        $.ajax({
            url: lpcAjax.url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                }
            }
        });
    });

    /**
     * Slider Custom Control
     */

    // Set our slider defaults and initialise the slider
    $('.slider-custom-control').each(function () {
        var sliderValue = $(this).find('.customize-control-slider-value').val();
        var newSlider = $(this).find('.slider');
        var sliderMinValue = parseFloat(newSlider.attr('slider-min-value'));
        var sliderMaxValue = parseFloat(newSlider.attr('slider-max-value'));
        var sliderStepValue = parseFloat(newSlider.attr('slider-step-value'));

        newSlider.slider({
            value: sliderValue,
            min: sliderMinValue,
            max: sliderMaxValue,
            step: sliderStepValue,
            change: function (e, ui) {
                // Important! When slider stops moving make sure to trigger change event so Customizer knows it has to save the field
                $(this).parent().find('.customize-control-slider-value').trigger('change');
            }
        });
    });

    // Change the value of the input field as the slider is moved
    $('.slider').on('slide', function (event, ui) {
        $(this).parent().find('.customize-control-slider-value').val(ui.value);
    });

    // Reset slider and input field back to the default value
    $('.slider-reset').on('click', function () {
        var resetValue = $(this).attr('slider-reset-value');
        $(this).parent().find('.customize-control-slider-value').val(resetValue);
        $(this).parent().find('.slider').slider('value', resetValue);
    });

    // Update slider if the input field loses focus as it's most likely changed
    $('.customize-control-slider-value').blur(function () {
        var resetValue = $(this).val();
        var slider = $(this).parent().find('.slider');
        var sliderMinValue = parseInt(slider.attr('slider-min-value'));
        var sliderMaxValue = parseInt(slider.attr('slider-max-value'));

        // Make sure our manual input value doesn't exceed the minimum & maxmium values
        if (resetValue < sliderMinValue) {
            resetValue = sliderMinValue;
            $(this).val(resetValue);
        }
        if (resetValue > sliderMaxValue) {
            resetValue = sliderMaxValue;
            $(this).val(resetValue);
        }
        $(this).parent().find('.slider').slider('value', resetValue);
    });

    /**
     * WP ColorPicker Alpha Color Picker Control
     *
     */

    // Manually initialise the wpColorPicker controls so we can add the color picker palette
    $('.wpcolorpicker-alpha-color-picker').each(function (i, obj) {
        var colorPickerInput = $(this);
        var paletteColors = _wpCustomizeSettings.controls[$(this).attr('id')].colorpickerpalette;
        var options = {
            palettes: paletteColors,
            change: function (event, ui) {
                // Set 1 ms timeout so input field is changed before change event is triggered
                // See: https://github.com/Automattic/Iris/issues/55#issuecomment-303716820
                setTimeout(function () {
                    // Important! Make sure to trigger change event so Customizer knows it has to save the field
                    colorPickerInput.trigger('change');
                }, 1);
            }
        };
        $(obj).wpColorPicker(options);
    });

    /**
     * Limit Controls
     */

    // Pro feature link.
    // Define the URL for the link
    var lpcproFeatureLink = "https://awplife.com/wordpress-plugins/customizer-login-page-premium/";

    // Function to create a new link element
    function createProFeatureLink() {
        return $('<a>', {
            href: lpcproFeatureLink,
            class: 'pro-feature-link',
            target: '_blank',
            css: {
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                zIndex: 2,
                opacity: 0,
                cursor: 'pointer'
            }
        });
    }

    // For presets.
    $('#customize-control-lpc_preset_select_control .grid-container label.radio-button-label').click(function (event) {
        var isProFeature = !$(this).find('input[type="radio"]').is('[value="default"], [value="chirp"]');
        if (isProFeature) {
            event.preventDefault(); // Prevent the radio button from being selected
        }
    });
    $('#customize-control-lpc_preset_select_control .grid-container label.radio-button-label .lpc-pro-link').on('click', function (e) {
        e.preventDefault(); // Prevent default action
        var url = $(this).attr('href');
        // You can add any additional logic here, like opening the link in a new tab
        window.open(url, '_blank');
    });
    // Append the link to the controls
    $('#customize-control-lpc_opts-lpc-logo-position-x-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-background-gcolor1-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-background-gcolor2-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-background-gcol1percent-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-background-gcol2percent-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-background-gangle-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-image-repeat-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-image-size-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-image-position-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-background-video-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-video-loop-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-video-mute-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-video-size-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-bg-video-position-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-position-left-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-box-shadow-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-border-radius-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-position-left-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-bg-image-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-bg-image-repeat-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-bg-image-position-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-bg-image-size-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-box-shadow-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-border-radius-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-bg-image-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-border-radius-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-box-shadow-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-position-top-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-inner-form-position-left-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-inputs-font-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-inputs-eye-position-right-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-inputs-eye-position-top-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-inputs-remember-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-inputs-font-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-inputs-labels-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-Username-label-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-Password-label-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-rememberme-text-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-button-text-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-button-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-button-border-radius-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-button-box-shadow-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-lostpass-text-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-lostpass-box-label-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-lostpass-text-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-lostpass-position-x-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-form-lostpass-label-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-backtolink-text-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-backtolink-position-x-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-enable-login-message-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-messages-login-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-messages-logout-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-messages-lostpassword-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-messages-register-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-width-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-text-color-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-text-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-font-size-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-background-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-border-width-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-border-color-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-padding-tb-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-padding-lr-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-border-radius-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-margin-top-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-margin-bottom-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-box-shadow-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-msg-style-box-position-x-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-login-empty-username-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-login-empty-password-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-login-invalid-username-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-login-incorrect-password-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-login-invalid-email-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-register-empty-username-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-register-empty-email-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-register-invalid-username-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-register-username-exists-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-register-invalid-email-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-register-email-exists-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-errors-lost-empty-username-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-lost-invalid-email-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-width-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-text-color-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-text-align-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-font-size-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-background-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-border-width-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-border-color-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-padding-tb-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-padding-lr-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-border-radius-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-margin-top-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-margin-bottom-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-box-shadow-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-error-style-box-position-x-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-customcss-control').append(createProFeatureLink());
    $('#customize-control-lpc_opts-lpc-customjs-control').append(createProFeatureLink());
    $('#customize-control-lpc-export-import-control').append(createProFeatureLink());

});