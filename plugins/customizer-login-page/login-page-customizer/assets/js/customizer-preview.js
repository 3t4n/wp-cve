(function (wp, $) {
    "use strict";

    if (!wp || !wp.customize) return;

    var lpc = wp.customize,
        lpcPastPreview;

    lpc.lpcCustomizerPreview = {
        init: function () {
            // Presets Partial.
            $('body.login').append('<span class="lpc-partial lpc-presets-partial customize-partial-edit-shortcut" data-title="Predefined Templates"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-presets"><span class="dashicons dashicons-welcome-widgets-menus"></span></button></span>');
            // Background Partial.
            $('body.login').append('<span class="lpc-partial lpc-background-partial customize-partial-edit-shortcut" data-title="Background Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-background"><span class="dashicons dashicons-images-alt2"></span></button></span>');
            // Logo Partial.
            $('#login h1').append('<span class="lpc-partial lpc-logo-partial customize-partial-edit-shortcut" data-title="Logo Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-logo"><span class="dashicons dashicons-edit"></span></button></span>');
            // Outer form Partial.
            $('#login').append('<span class="lpc-partial lpc-outer-form-partial customize-partial-edit-shortcut" data-title="Outer Form Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-form"><span class="dashicons dashicons-edit"></span></button></span>');
            // Inner form Partial.
            $('#loginform').append('<span class="lpc-partial lpc-inner-form-partial customize-partial-edit-shortcut" data-title="Inner Form Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-inner-form"><span class="dashicons dashicons-edit"></span></button></span>');
            // Inputs Partial.
            $('#loginform .user-pass-wrap').append('<span class="lpc-partial lpc-inputs-partial customize-partial-edit-shortcut" data-title="Form Inputs Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-form-inputs"><span class="dashicons dashicons-edit"></span></button></span>');
            // Button Partial.
            $('#loginform .submit').append('<span class="lpc-partial lpc-button-partial customize-partial-edit-shortcut" data-title="Button Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-button"><span class="dashicons dashicons-edit"></span></button></span>');
            // Lostpass Partial.
            $('#login p#nav').append('<span class="lpc-partial lpc-lostpass-partial customize-partial-edit-shortcut" data-title="Lost Password settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-lostpass"><span class="dashicons dashicons-edit"></span></button></span>');
            // Lostpass Partial.
            $('#login p#backtoblog').append('<span class="lpc-partial lpc-backto-partial customize-partial-edit-shortcut" data-title="Back to link settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-backtolink"><span class="dashicons dashicons-edit"></span></button></span>');
            // Error Partial.
            $('#login #login_error').append('<span class="lpc-partial lpc-error-partial customize-partial-edit-shortcut" data-title="Error Box Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-error-style"><span class="dashicons dashicons-edit"></span></button></span>');
            // Error Partial.
            $('#login p.message').append('<span class="lpc-partial lpc-msg-partial customize-partial-edit-shortcut" data-title="Msg Box Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-msg-style"><span class="dashicons dashicons-edit"></span></button></span>');
            // Footer Partial.
            $('.lpc-copyright').append('<span class="lpc-partial lpc-footer-partial customize-partial-edit-shortcut" data-title="Footer Settings"><button class="customize-partial-edit-shortcut-button" data-customizer-event="lpc-section-footer"><span class="dashicons dashicons-edit"></span></button></span>');

            // Handle click events on the custom pencil icon.
            $(document).on('click', '.lpc-partial.customize-partial-edit-shortcut', function (e) {
                var $el = $(this),
                    $event = $el.children().data('customizer-event'),
                    $customizer = parent.document;

                // Find the section.
                var section = $('#accordion-section-' + $event, $customizer);
                var subsection = $('#sub-accordion-section-' + $event, $customizer);

                // If the section is already open, return early to prevent it from closing.
                if (subsection.hasClass('open')) {
                    return;
                }

                // Otherwise, trigger a click on the section to open it.
                if (section.length) {
                    section.find('.accordion-section-title').trigger('click');
                }
            });
        }
    };

    // Extend the Customizer Preview class.
    lpcPastPreview = lpc.Preview;
    lpc.Preview = lpcPastPreview.extend({
        initialize: function (params, options) {
            lpc.lpcCustomizerPreview.preview = this;
            lpcPastPreview.prototype.initialize.call(this, params, options);
        }
    });

    // Initialize when document is ready.
    $(function () {
        lpc.lpcCustomizerPreview.init();
    });
})(window.wp, jQuery);
