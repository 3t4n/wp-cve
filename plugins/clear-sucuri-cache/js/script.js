/**
 * Created by Phantom on 18.03.2016.
 */

(function ($) {
    $(document).ready(function () {
        //var clear_button = $('.sucuri-clear-clear>a, #sucuri-modal__button-submit');
        var clear_button = $('#wp-admin-bar-sucuri-clear-clear>a, a.sucuri-modal__button-submit');
        clear_button.on('click', function () {
            var rel = $(this).attr('rel');
            make_request( rel );
            return false;
        });

        toggle_modal();

        function make_request( rel ) {
            __shake = function (div) {
                var interval = 100;
                var distance = 10;
                var times = 4;

                $(div).css('position', 'relative');

                for (var iter = 0; iter < (times + 1); iter++) {
                    $(div).animate({
                        left: ((iter % 2 == 0 ? distance : distance * -1))
                    }, interval);
                }//for

                $(div).animate({left: 0}, interval);

            };
            var modal_input = $('.cfp-modal__input');
            var file = modal_input.val();
            if( file.length === 0 && rel === 'file') {
                __shake(modal_input);
                return false;
            }
            $.ajax({
                url: csc_ajaxurl.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'clear_sucuri_cache',
                    rel: rel,
                    file: file
                },
                beforeSend: function () {
                    _animate_buttons(false, true )
                },
                success: function (data) {
                    _animate_buttons(data)
                },
                error: function (data) {
                    _animate_buttons(data)
                }
            });
            function _animate_buttons( data, initial ) {
                var dashboardPageButton = $('.sucuri-clear-clear');
                //clear_button = clear_button.filter( '[rel="all"]' );
                if( initial == true ) {
                    clear_button.toggleClass('spin');
                    if (dashboardPageButton.length !== 0)
                        dashboardPageButton.find('.dashicons-networking').toggleClass('is-active spinner dashicons dashicons-networking');
                } else {
                    if( data.success ) {
                        clear_button.toggleClass('spin').addClass('success');
                        setTimeout(function () {
                            clear_button.removeClass('success')
                        }, 2000);
                        if (dashboardPageButton.length !== 0) {
                            dashboardPageButton.find('.spinner').toggleClass('is-active spinner dashicons dashicons-thumbs-up');
                            setTimeout(function () {
                                dashboardPageButton.find('.dashicons').toggleClass('dashicons-thumbs-up dashicons-networking')
                            }, 2000);
                        }
                    } else {
                        clear_button.toggleClass('spin').addClass('error');
                        setTimeout(function () {
                            clear_button.removeClass('error');
                            alert(data.msg)
                        }, 2000);
                        if (dashboardPageButton.length !== 0) {
                            dashboardPageButton.find('.spinner').toggleClass('is-active spinner dashicons dashicons-thumbs-down');
                            setTimeout(function () {
                                dashboardPageButton.find('.dashicons').toggleClass('dashicons-thumbs-down dashicons-networking')
                            }, 2000);
                        }
                    }
                }

            }
        }

        function toggle_modal() {
            var modal          = $('#sucuri-purger-modal');
            var trigger_button = $('.sucuri_clear_files_thickbox_trigger');
            var close_button = $('.cfp-modal__button-close');
            trigger_button.on('click',function(){
                _toggle_modal( $(this).find('a').attr('href'));
                return false;
            });
            close_button.on('click',function(e){
                e.preventDefault();
                modal.removeClass('md-show');
            });

            function _toggle_modal( target ) {
                $(target).toggleClass('md-show');
            }

        }



    });

})(jQuery);
