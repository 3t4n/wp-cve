(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    jQuery(document).ready(function ($) {       //wrapper
        (function () {
            function mdhRegisterModal() {
                var _self    = this;
                _self.option = {
                    show_pass : false
                };

                var show_pass         = false;
                var pass_input        = document.getElementById('mdh-pass-input');
                var pass_input_toggle = jQuery('#mdh-pass-toggle');

                pass_input_toggle.on('click', function () {
                    if (show_pass) {
                        pass_input.type = 'password';
                        pass_input_toggle.removeClass('dashicons-hidden');
                    } else {
                        pass_input.type = 'text';
                        pass_input_toggle.addClass('dashicons-hidden');
                    }
                    show_pass = !show_pass;
                });

                _self.mdhModaloverlay = $('#mdhRegister-wrap');
                _self.mdhModal        = $('#mdhRegister');
                _self.modalClose      = $('#mdhRegister-close');
                _self.modalOpen       = $('.mdhRegister-btn');

                _self.init = function () {
                    _self.modalOpen.on('click', function () {
                        _self.open();
                    });

                    _self.mdhModal.on('click', function (e) {
                        e.stopPropagation();
                    });

                    _self.modalClose.on('click', function () {
                        _self.close();
                    });

                    _self.mdhModaloverlay.on('click', function () {
                        _self.close();
                    })

                };

                _self.open = function () {
                    _self.mdhModaloverlay.css({'z-index':'99998'});
                    _self.mdhModal.css({'z-index':'99999'});
                    _self.mdhModaloverlay.addClass('mdh-display');
                    _self.mdhModal.addClass('mdhRegister-show');
                };

                _self.close = function () {
                    _self.mdhModaloverlay.removeClass('mdh-display');
                    _self.mdhModal.removeClass('mdhRegister-show');
                };
            }

            var mdhPromote = new mdhRegisterModal();
            mdhPromote.init();
        })();
    });
})(jQuery);