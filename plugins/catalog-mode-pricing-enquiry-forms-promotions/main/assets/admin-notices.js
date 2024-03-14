"use strict";
jQuery(document).ready(function($) {

    setTimeout(function() {
        init_shipped_notice();
    }, 50);

    function init_shipped_notice() {

        $('.wmodes-notice').each(function() {

            var obj = $(this);

            var notice_id = 'lite';

            if (obj.attr('data-wmodes_id')) {

                notice_id = obj.attr('data-wmodes_id');
            }

            obj.find('.notice-dismiss,.wmodes-btn-secondary').each(function() {

                var btn = $(this);

                var remind_me = 'no';

                if (btn.attr('data-wmodes_remind')) {

                    remind_me = btn.attr('data-wmodes_remind');
                }

                btn.on('click', function(event) {

                    var btn_is_wp = true;


                    if (obj.attr('data-wmodes_id'))
                        if (btn.hasClass('wmodes-btn-secondary')) {

                            event.preventDefault();
                            btn_is_wp = false;

                        }

                    $.post(ajaxurl, {
                        action: 'wmodes_dismiss_notice',
                        dismiss_notice_id: notice_id,
                        mayme_later: remind_me,
                    });

                    if (!btn_is_wp) {

                        obj.animate({ height: '0px', opacity: 0 }, 300, 'swing', function() {

                            obj.remove();
                        });
                    }

                });
            });

        });
    }

});