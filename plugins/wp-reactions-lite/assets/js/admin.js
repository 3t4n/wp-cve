let WPRA = {
    'getUrlVars': function () {
        let vars = {};
        window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });
        return vars;
    }
};

jQuery(document).ready(function ($) {

    const secure = $('.wpreactions').data('secure');
    let url_params = WPRA.getUrlVars();
    let params = {};
    params['behavior'] = url_params.behavior;
    params['post_id'] = new Date().getTime();
    let max_steps = $('.wpra-stepper-single').length;
    let current_step = 1;
    let is_shortcode_builder_page = $('body').hasClass('wp-reactions_page_wpra-shortcode-generator');

    function get_options() {
        let options = {};
        options['enable_share_buttons'] = $('input[name=enable_share_buttons]:checked').val();
        options['animation'] = $('input[name=animation]:checked').val();

        let social_platforms = {};

        let emojis = {};
        let picked = get_picked_emojis();

        if (picked.length > 0) {
            for (let i = 0; i < 7; i++) {
                emojis['reaction' + (i + 1)] = (typeof picked[i] == "undefined") ? -1 : picked[i];
            }

            options['emojis'] = emojis;
            options['picked_emojis'] = picked.join();
        }

        $('.social-picker input[type="checkbox"]').each(function () {
            let platform = $(this).attr('id').replace('social_platforms_', '');
            social_platforms[platform] = $(this).is(':checked') ? 'true' : 'false';
        });

        let social_labels = {};
        $('.social-picker input[type="text"]').each(function () {
            let label = $(this).val();
            let platform = $(this).attr('id').replace('social_labels_', '');
            social_labels[platform] = label;
        });

        options['social_labels'] = social_labels;
        options['social_platforms'] = social_platforms;

        options['show_title'] = $('input[name=show_title]:checked').val();
        options['title_text'] = $('#title_text').val();
        options['title_color'] = $('#title_color').val();
        options['title_size'] = $('#title_size').val();
        options['title_weight'] = $('#title_weight').val();

        options['display_where'] = $('input[name=display_where]:checked').val();
        options['content_position'] = $('input[name=content_position]:checked').val();
        options['size'] = $('input[name=size]:checked').val();
        options['align'] = $('input[name=align]:checked').val();

        options['bgcolor'] = $('#bgcolor').val();
        options['bgcolor_trans'] = $('#bgcolor_trans_true').is(':checked') ? 'true' : 'false';
        options['shadow'] = $('#shadow').is(':checked') ? 'true' : 'false';

        options['border_radius'] = $('#border_radius').val();
        options['border_width'] = $('#border_width').val();
        options['border_color'] = $('#border_color').val();
        options['border_style'] = $('#border_style').val();

        options['count_color'] = $('#count_color').val();
        options['count_text_color'] = $('#count_text_color').val();

        options['social'] = {
            'border_radius': $('#social_border_radius').val(),
            'border_color': $('#social_border_color').val(),
            'text_color': $('#social_text_color').val(),
            'bg_color': $('#social_bg_color').val(),
            'button_type': $('input[name=social_button_type]:checked').val(),
        };

        return options;
    }

    function change_social_button_style($elem) {
        if ($elem.is(':checked')) {
            $('#social_border_color').attr('disabled', false);
            $('#social_text_color').attr('disabled', false);
            $('#social_bg_color').attr('disabled', false);
        } else {
            $('#social_border_color').attr('disabled', true);
            $('#social_text_color').attr('disabled', true);
            $('#social_bg_color').attr('disabled', true);
        }
        $('.color-chooser').minicolors();
    }

    function get_preview(callbacks, options) {
        $.ajax({
            url: ajaxurl,
            dataType: 'text',
            type: 'post',
            data: {
                action: 'wpra_preview',
                options: JSON.stringify(options),
                checker: secure
            },
            beforeSend: function () {
                if ('beforeSend' in callbacks) {
                    callbacks['beforeSend']();
                }
            },
            success: function (data, textStatus, jQxhr) {
                if ('success' in callbacks) {
                    callbacks['success'](data, options);
                }
            },
            complete: function () {
                if ('complete' in callbacks) {
                    callbacks['complete']();
                }
            }
        });
    }

    function save_options(callbacks, options, single = 0) {
        $.ajax({
            url: ajaxurl,
            dataType: 'text',
            type: 'post',
            data: {
                action: 'wpra_save_options',
                options: JSON.stringify(options),
                single: single,
                checker: secure
            },
            beforeSend: function () {
                if ('beforeSend' in callbacks) {
                    callbacks['beforeSend']();
                }
            },
            success: function (data) {
                if ('success' in callbacks) {
                    callbacks['success'](data);
                }
            },
            complete: function () {
                if ('complete' in callbacks) {
                    callbacks['complete']();
                }
            }
        });
    }

    function stepChanged() {
        $(window).scrollTop(0);
        if (current_step > 0 && current_step < max_steps) {
            $('.floating-preview').show();
            $('.prev span').last().text(wpra.global_prev_step);
        } else {
            $('.floating-preview').hide();
            $('.prev span').last().text(wpra.global_go_back);
        }

        if (current_step == max_steps) {
            $('.next span').first().text(wpra.global_start_over);
            $('.next span').last().removeClass('dashicons-arrow-right-alt2').addClass('dashicons-update');
        } else {
            $('.next span').first().text(wpra.global_next_step);
            $('.next span').last().removeClass('dashicons-update').addClass('dashicons-arrow-right-alt2');
        }

        $('.body-item').hide();
        $('[data-body_id="' + current_step + '"]').show();

        if (current_step == max_steps) {
            get_preview(
                {
                    'beforeSend': function () {
                        $('#preview').html('');
                        $('#preview').html('<div class="wpra-spinner" style="width: 50px;height: 50px;"></div>');
                    },
                    'success': function (data) {
                        $('#preview').html(data);
                        $('.wpra-stepper-bar.is-current').removeClass("is-current").addClass("is-complete");
                        WPRA_Front.animate_emojis($('#preview .wpra-reactions-container'));
                        $('#preview .wpra-plugin-container').addClass('wpra-rendered');
                    },
                    'complete': function () {

                    }
                },
                get_options()
            );
        }

        if ($('input[name=enable_reveal_button]:checked').val() == 'true') {
            $('.option-reveal-button-styles').show();
            $('.enable_popup_share_opt').show();
        } else {
            $('.option-reveal-button-styles').hide();
            $('.enable_popup_share_opt').hide();
        }
    }

    function get_picked_emojis() {
        let picked = [];
        $('.picked-emojis .picked-emoji').each(function () {
            picked.push($(this).data('emoji_id'));
        });
        return picked;
    }

    $('.wpra-color-chooser').minicolors({});

    $(window).load(function () {
        $(".wpra-preloader").fadeOut();
    });

    let a_click_url = '';
    $('a').click(function (e) {

        a_click_url = $(this).attr('href');

        if (typeof a_click_url == 'undefined' || a_click_url.indexOf('#') != -1) {
            return;
        }

        let message = is_shortcode_builder_page
            ? 'Are your sure you want to leave the shortcode generator?'
            : 'Are you sure you want to leave without saving your work?';

        if ((current_step > 1 && current_step < max_steps) || is_shortcode_builder_page) {
            e.preventDefault();
            if (confirm(message)) {
                window.location.href = a_click_url;
            }
        }
    });

    $('#bgcolor_trans').change(function () {
        let is_trans = $(this).is(':checked');

        if (is_trans) {
            $('#bgcolor').attr('disabled', true);
        } else {
            $('#bgcolor').attr('disabled', false);
        }
    });

    $('.save-wpj-options').click(function () {
        save_options(
            {
                'beforeSend': function () {
                    $('.loading-overlay').addClass('active');
                    $('.loading-overlay .overlay-message').html(wpra.msg_options_updating);
                },
                'success': function () {
                    $('.loading-overlay .overlay-message').html(wpra.msg_options_updated);
                    location.href = wpra.global_lp;
                }
            },
            get_options()
        );
    });

    $('#social_style_buttons').click(function () {
        change_social_button_style($(this));
    });

    $('.floating-preview-close').click(function () {
        $('.floating-preview-holder').hide();
    });

    $('.floating-preview-holder').mouseleave(function () {
        $('.floating-preview-holder').hide();
    });

    $('.floating-preview-button').mouseenter(function () {
        let source = $(this).data('source');
        let options;
        if (source == 'shortcode') {
            options = params;
        } else if (source == 'global') {
            options = get_options();
        }
        $('.floating-preview-holder').show();
        get_preview(
            {
                'beforeSend': function () {
                    $('.floating-preview-result').html('');
                    $('.floating-preview-loading').show();
                },
                'success': function (data, options) {
                    if (options.behavior == 'button_reveal') {
                        $('.floating-preview-holder').css({'padding-top': '160px'});
                    }
                    if (options.behavior == 'button_reveal' && options.size == 'large') {
                        $('.floating-preview-holder').css({'width': '1100px'});
                    }

                    $('.floating-preview-result').html(data);
                    WPRA_Front.animate_emojis($('.floating-preview-result .wpra-reactions-container'));
                    $('.floating-preview-result .wpra-plugin-container').addClass('wpra-rendered');
                },
                'complete': function () {
                    $('.floating-preview-loading').hide();
                }
            },
            options
        );
    });

    $(".next").on("click", function () {
        if (current_step == max_steps) {
            window.location.href = wpra.global_lp;
            return;
        }
        let $bar = $(".wpra-stepper-bar");
        if ($bar.children(".is-current").length > 0) {
            $bar.children(".is-current").removeClass("is-current").addClass("is-complete").next().addClass("is-current");
        } else {
            $bar.children().first().addClass("is-current");
        }
        let tab_id = $bar.children(".is-current").data('tab_id');
        current_step = tab_id;
        stepChanged();
    });

    $(".prev").on("click", function () {
        if (current_step == 1) {
            window.location.href = wpra.global_lp;
            return;
        }
        if (current_step > 1) {
            let $bar = $(".wpra-stepper-bar");
            if ($bar.children(".is-current").length > 0) {
                $bar.children(".is-current").removeClass("is-current").prev().removeClass("is-complete").addClass("is-current");
            } else {
                $bar.children(".is-complete").last().removeClass("is-complete").addClass("is-current");
            }
            let tab_id = $bar.children(".is-current").data('tab_id');
            current_step = tab_id;
            stepChanged();
        }
    });

    $('.wpra-stepper-single').click(function () {
        let $bar = $(".wpra-stepper-bar");
        $bar.children(".is-current").removeClass("is-current");
        $bar.children(".is-complete").removeClass("is-complete");
        $(this).addClass("is-current");
        $bar.children().each(function () {
            if (!$(this).hasClass('is-current')) {
                $(this).addClass('is-complete');
            } else {
                return false;
            }
        });
        current_step = $(this).data('tab_id');
        stepChanged();
    });

    $('.wpra-tooltip').hover(
        function () {
            let $content = $(this).find('.wpra-tooltip-content-wrap');
            $content.css({"top": 0, "bottom": "auto"});
            $content.fadeIn();
            $content.addClass('active');
            let right = $content.css('left');
            if ($content.offset().top > ($(window).scrollTop() + $(window).height() - $content.outerHeight())) {
                $content.css({"bottom": 0, "top": "auto"});
            }
            if (($content.offset().left + $content.outerWidth()) > $(window).width()) {
                $content.css({"right": right, "left": "auto"});
            }
            if ($content.offset().left < 0) {
                let x = $content.outerWidth() + $content.offset().left - 10;
                $content.css({"transform": "translateX(-" + x + "px)", 'left': 0});
            }
        },
        function () {
            let $content = $(this).find('.wpra-tooltip-content-wrap');
            $content.hide();
            $content.removeClass('active');
        }
    );

    $('.wpra-restore-btn').click(function () {
        if (!confirm(wpra.msg_reset_confirm)) {
            return;
        }
        let btn = $(this);

        $.ajax({
            url: ajaxurl,
            dataType: 'JSON',
            type: 'post',
            data: {
                action: 'wpra_reset_options',
                checker: secure
            },
            beforeSend: function () {
                $('.loading-overlay').show();
                btn.attr('disabled', true);
                $('.loading-overlay .overlay-message').html(wpra.msg_resetting_options);
            },
            success: function (data, textStatus, jQxhr) {
                $('.loading-overlay .overlay-message').html(wpra.msg_reset_done);
                window.location.reload();
            },
            complete: function () {
            }
        });
    });

    $('input[name="global_behavior"]').change(function () {
        let sw = $(this);
        let pair = sw.data('pair');
        if (sw.is(':checked')) {
            $(pair).attr('checked', false);
        }

        let activation = $('input[name="global_behavior"]:checked').length > 0 ? 'true' : 'false';

        if (activation == 'false') {
            $('#customize').attr('disabled', true);
            $('.wpra-light-activation-status').removeClass('p-active');
        } else {
            $('#customize').attr('disabled', false);
            $('.wpra-light-activation-status').addClass('p-active');
        }

        save_options(
            {
                'beforeSend': function () {
                    sw.parents('.wpe-switch').before('<div class="wpra-spinner active"></div>');
                },
                'complete': function () {
                    sw.parents('.wpe-switch-wrap').find('.wpra-spinner').remove();
                }
            },
            {'activation': activation, 'behavior': sw.attr('id')},
            1
        );
    });

    $('.floating-menu-toggler').click(function (e) {
        e.stopPropagation();
        if ($('.floating-menu').hasClass('active')) {
            $('.floating-menu').removeClass('active');
        } else {
            $('.floating-menu').addClass('active');
        }
    });

    $('.floating-menu').click(function (e) {
        e.stopPropagation();
    });

    $(document).click(function () {
        $('.floating-menu').removeClass('active');
    });

    $(window).scroll(function () {
        if ($('.floating-menu').hasClass('active')) {
            $('.floating-menu').removeClass('active');
        }
    });

    $(window).scroll(function () {
        if ($(window).width() < 768) {
            let scrollTop = $(this).scrollTop();
            let adminBarHeight = $('#wpadminbar').outerHeight();
            let headerHeight = $('.wpra-options-header').outerHeight();
            if (scrollTop > adminBarHeight) {
                $('.wpra-options-header').css('top', 0);
                $('.floating-menu').css({'top': headerHeight});
            } else {
                $('.wpra-options-header').css('top', adminBarHeight - scrollTop);
                $('.floating-menu').css('top', headerHeight + adminBarHeight);
            }
        }
    });

    $('.wpra-behavior-preview').on('click', '.wpra-share-wrap .share-btn', function (e) {
        e.preventDefault();
        return false;
    });

    $('.floating-preview-holder').on('click', '.wpra-share-wrap .share-btn', function (e) {
        e.preventDefault();
        return false;
    });

    $('.picked-emojis').sortable();
    $(".picked-emojis").disableSelection();

    $('a[href="#toggle-feedback-form"]').click(function () {
        // $('#wpraFeedbackForm').modal('toggle');

        $('#wpraFeedbackForm').addClass('show');
        $('#wpraFeedbackForm').css({'display': 'block', 'padding-right': '17px'});
        $('body').addClass('modal-open');
        $('body').css({'padding-right': '17px'});
        $('body').append('<div class="modal-backdrop fade show"></div>');
    });

    $('.modal .close').click(function () {
        $(this).parents('.modal').removeClass('show');
        $(this).parents('.modal').css({'display': 'none', 'padding-right': '0'});
        $('body').removeClass('modal-open');
        $('body').css({'padding-right': '0'});
        $('body').find('.modal-backdrop').remove();
    });

    $('.wpra-submit-feedback').click(function () {
        let email = $('#feedback-email').val() == '' ? 'No Email' : $('#feedback-email').val();
        let message = $('#feedback-message').val();
        let rating = $('.rating-item.selected').data('label');

        if (typeof rating == 'undefined') {
            alert('Please select your rating first!');
            return;
        }

        let $btn = $(this);

        $.ajax({
            url: ajaxurl,
            dataType: 'JSON',
            type: 'post',
            data: {
                action: 'wpra_submit_feedback',
                email: email,
                message: message,
                rating: rating,
                checker: secure
            },
            beforeSend: function () {
                $btn.attr('disabled', true);
                $btn.append('<span class="wpra-spinner" style="width: 20px; height: 20px;"></span>')
            },
            success: function (data, textStatus, jQxhr) {
                if (data.status == 'success') {
                    $('#wpraFeedbackForm').find('.modal-footer').hide();
                    $('#wpraFeedbackForm').find('.modal-title').html(data.message_title);
                    $('#wpraFeedbackForm').find('.modal-body').html(data.message).css('color', '#007bff');
                }
            },
            complete: function () {
                $btn.attr('disabled', false);
            }
        });
    });

    $('.feedback-ratings .rating-item').hover(
        function () {
            let left = $(this).offset().left;
            let top = $(this).offset().top;
            let pos = {
                'top': top,
                'left': left + $(this).outerWidth() / 2
            };
            $('body').append('<div class="rating-label">' + $(this).data('label') + '</div>');
            $('.rating-label').css(pos);
            $('.rating-label').fadeIn();
            $(this).addClass('active');
        },
        function () {
            $('body').find('.rating-label').remove();
            $(this).removeClass('active');
        }
    );

    $('.feedback-ratings .rating-item').click(function () {
        $(this).siblings().removeClass('selected');
        $(this).addClass('selected');
    });

    $('.picked-emojis').on('mouseenter', '.picked-emoji', function () {
        $(this).append('<span class="emoji-picker-remover">&times;</span>');
    });

    $('.picked-emojis').on('mouseleave', '.picked-emoji', function () {
        $(this).find("span").remove();
    });

    $('.picked-emojis').on('click', '.emoji-picker-remover', function () {
        if (get_picked_emojis().length == 2) {
            alert('You need to have 2 emojis at least!');
            return;
        }
        let div = $('.emoji-picker-remover').closest('div[data-emoji_id]');
        let emoji_id = div.data('emoji_id');
        if (div.data('emoji_id') == emoji_id) {
            div.remove();
        }
    });

    $('.emoji-picker .emoji-pick').click(function () {
        let $pick = $(this);
        let emoji_id = $pick.data('emoji_id');
        let picked_emojis = get_picked_emojis();
        if ($pick.hasClass('active')) {
            $pick.removeClass('active');
            picked_emojis.splice(picked_emojis.indexOf(emoji_id), 1);
            $('.picked-emoji.emoji-lottie-holder[data-emoji_id=' + emoji_id + ']').remove();
        } else {
            if (picked_emojis.length == 7) {
                alert('You can select up to 7 emojis');
            } else {
                $pick.addClass('active');
                picked_emojis.push(emoji_id);
                $('.picked-emojis').append('<div class="picked-emoji emoji-lottie-holder" data-emoji_id="' + emoji_id + '"></div>');
                let $appended = $('.picked-emojis .picked-emoji').last();
                [8,9,10].indexOf(emoji_id) > -1 && $appended.css('padding', '17px 0'); // fixes emoji sizing

                bodymovin.loadAnimation({
                    container: $appended.get(0),
                    path: wpra.emojis_path + 'json/' + emoji_id + '.json?v=' + wpra.version,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    name: emoji_id
                });
            }
        }
    });

    $('.lottie-element').each(function () {
        let $elem = jQuery(this);
        $(this).html('');
        let emoji_id = $elem.data('emoji_id');

        bodymovin.loadAnimation({
            container: $elem.get(0),
            path: wpra.emojis_path + 'json/' + emoji_id + '.json?v=' + wpra.version,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            name: emoji_id
        });
    });

    $('.emoji-picker .emoji-pick').hover(
        function () {
            $(this).find('.emoji-lottie-holder').show();
            $(this).find('.emoji-svg-holder').hide();
            let emoji_id = $(this).data('emoji_id');
            if (typeof $(this).data('animation') == 'undefined') {
                let animation = bodymovin.loadAnimation({
                    container: $(this).find('.emoji-lottie-holder').get(0),
                    path: wpra.emojis_path + 'json/' + emoji_id + '.json?v=' + wpra.version,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    name: emoji_id
                });
                $(this).data('animation', animation);
            } else {
                $(this).data('animation').play();
            }
        },
        function () {
            if (typeof $(this).data('animation') != 'undefined') {
                $(this).data('animation').pause();
            }
            $(this).find('.emoji-lottie-holder').hide();
            $(this).find('.emoji-svg-holder').show();
        }
    );

    $('.wpra-js-link').click(function () {
        const href = $(this).data('href');
        window.open(href, '_blank');
    });
});
