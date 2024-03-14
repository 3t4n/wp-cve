let all_emoji_containers = [];
var WPRA_Front = {
    'animate_emojis': function (container = null) {
        let containers;
        if (container == null) {
            containers = jQuery('.wpra-reactions-container');
        } else {
            containers = container;
        }

        containers.each(function () {
            let container = jQuery(this);
            container.data('animations', []);
            all_emoji_containers.push(container);
            let emojis = container.find('.wpra-reaction');
            // check if animation is set true
            let animation = container.data('animation');
            if (animation) {
                emojis.each(function () {
                    let emoji_id = jQuery(this).data('emoji_id');
                    WPRA_Front.load_lottie_emoji(container, jQuery(this), emoji_id, null);
                });
            }
        });
        WPRA_Front.checkIfPlayable();
    },
    'load_lottie_emoji': function (container, emoji_elm, emoji_id, onComplete = null, isAnimated = true) {
        let args = {
            container: emoji_elm.get(0),
            path: wpra.emojis_path + 'json/' + emoji_id + '.json?v=' + wpra.version,
            renderer: 'svg',
            loop: true,
            autoplay: false,
            name: emoji_id,
        };
        if (!isAnimated) {
            args['loop'] = false;
            args['autoplay'] = false;
        }

        let animation = bodymovin.loadAnimation(args);
        container.data('animations').push(animation);
        if (onComplete != null) {
            animation.addEventListener('DOMLoaded', onComplete);
            animation.play();
        }
    },
    'checkIfPlayable': function () {
        jQuery.each(all_emoji_containers, function (key, container) {
            let elem_top = container.offset().top;
            let isScrolled = jQuery(window).scrollTop() + jQuery(window).height() > elem_top;
            let isDisplayed = container.is(':visible');
            if (jQuery(container).data('behavior') == 'regular') {
                if (isScrolled && isDisplayed && !container.data('isPlaying')) {
                    container.data('isPlaying', true);
                    jQuery.each(container.data('animations'), function (key, emoji) {
                        emoji.play();
                    });
                }
                if ((!isScrolled || !isDisplayed) && container.data('isPlaying')) {
                    container.data('isPlaying', false);
                    jQuery.each(container.data('animations'), function (key, emoji) {
                        emoji.pause();
                    });
                }
            }
        });
    },
    'narrowContainerize': function ($plugin_container, for_button_reveal = false) {
        $plugin_container.addClass('wpra-narrow-container');
        let reaction_count = $plugin_container.find('.wpra-reaction').length;
        let reactions_width = for_button_reveal ? $(window).width() - 30 : $plugin_container.width();
        $plugin_container.find('.wpra-reaction').css('width', reactions_width / reaction_count + 'px');
        $plugin_container.find('.wpra-reaction').css('height', reactions_width / reaction_count + 'px');
        $plugin_container.addClass('wpra-rendered');
    },
    'adaptSocialButtons': function ($plugin_container) {
        if ($plugin_container.hasClass('wpra-regular') && ($plugin_container.outerWidth() < $plugin_container.find('.wpra-share-wrap').outerWidth())) {
            let count = $plugin_container.find('.share-btn').length;
            $plugin_container.find('.wpra-share-wrap').addClass('wpra-share-wrap-narrow share-arrange-' + count);
        }
    }
};

jQuery(document).ready(function ($) {

    $('.wpra-plugin-container').each(function () {
        let $plugin_container = $(this);
        // if it is regular reactions check if it needs narrowing
        // if share buttons does not fit container then narrow them
        if ($plugin_container.outerWidth() < $plugin_container.find('.wpra-share-wrap').outerWidth()) {
            $plugin_container.find('.wpra-share-wrap').addClass('wpra-share-wrap-narrow');
        }
        // if classic reactions does not fit to its container then narrow it
        if ($plugin_container.find('.wpra-reactions').outerWidth() > $plugin_container.parent().width()) {
            $plugin_container.addClass('wpra-narrow-container');
            let reaction_count = $plugin_container.find('.wpra-reaction').length;
            let reactions_width = $plugin_container.width();
            $plugin_container.find('.wpra-reaction').css('width', reactions_width / reaction_count + 'px');
            $plugin_container.find('.wpra-reaction').css('height', reactions_width / reaction_count + 'px');
            $plugin_container.addClass('wpra-rendered');
        } else {
            $plugin_container.addClass('wpra-rendered');
        }
    });

    $(document).on('click', '.wpra-reaction', function () {

        if ($(this).hasClass('active')) {
            return;
        }

        let container = $(this).parents('.wpra-reactions-container');
        let wrapper = container.parent();
        let reacted_to = $(this).attr('class').split(" ")[0];
        let post_id = container.attr('data-post_id');
        let containers = $('[data-post_id=' + post_id + ']');
        let emoji_id = $(this).data('emoji_id');

        if (container.data('show_count')) {
            let current_data_count = parseInt($(this).attr('data-count'));
            let active = containers.find('.active');
            let active_count = parseInt(active.attr('data-count'));

            let revert_count = 0;
            if (active_count > 1) {
                revert_count = active_count - 1;
            }
            if (revert_count == 0) {
                active.find('.wpra-arrow-badge').hide();
            } else {
                active.find('.wpra-arrow-badge').show();
            }
            if (active_count < 1000) {
                active.find('.wpra-arrow-badge .count-num').html(revert_count);
            }
            active.attr('data-count', revert_count);

            if (isNaN(current_data_count)) {
                current_data_count = 0;
            }

            containers.find('.wpra-reaction').removeClass("active");
            containers.find('.' + reacted_to).addClass("active");
            containers.find('.' + reacted_to).find('.wpra-plus-one').html(current_data_count + 1);

            containers.find('.wpra-reaction').find('.wpra-plus-one').removeClass("triggered");
            containers.find('.' + reacted_to).find('.wpra-plus-one').addClass("triggered");

            if (current_data_count < 1000) {
                containers.find('.active .wpra-arrow-badge .count-num').html(current_data_count + 1);
            }
            containers.find('.active').attr("data-count", current_data_count + 1);
            containers.find('.active .wpra-arrow-badge').show();

            if (active_count > 0) {
                containers.find('.active .wpra-arrow-badge').removeClass('hide-count');
            }
        } else {
            containers.find('.wpra-reaction').removeClass("active");
            containers.find('.' + reacted_to).addClass("active");
        }

        if (container.data('enable_share')) {
            container.find('.wpra-share-wrap').css('display', 'flex');
            if (wrapper.outerWidth() < container.outerWidth()) {
                container.find('.wpra-share-wrap').addClass('wpra-share-wrap-narrow');
            }
        }

        $.ajax({
            url: wpra.ajaxurl,
            dataType: 'text',
            type: 'post',
            data: {
                action: 'wpra_react',
                reacted_to: reacted_to,
                emoji_id: emoji_id,
                post_id: post_id,
                checker: container.data('secure')
            }
        });

        let reveal_wrap = container.parents('.wpra-button-reveal-wrap');

        if (reveal_wrap.length > 0) {
            let $reacted_emoji = reveal_wrap.find('.wpra-reacted-emoji');
            let $reactions_wrap = reveal_wrap.find('.wpra-reactions-wrap');
            let $reveal_toggle = reveal_wrap.find('.wpra-reveal-toggle');
            let clicked_text = $reveal_toggle.data('text_clicked');

            $reacted_emoji.html('');
            $reactions_wrap.hide();
            reveal_wrap.data('user_reacted', true);

            // if share popup enabled then change button text and class
            if ($reveal_toggle.data('enable_share_popup')) {
                $reveal_toggle.text(clicked_text);
                $reveal_toggle.addClass('share-popup-toggle');
            }
        }

    });

    function wpraIsMobile() {
        return $(window).width() < 768;
    }

    $(document).on('click', '.share-btn', function () {
        let platform = $(this).data('platform');
        let platform_url = (wpraIsMobile() && typeof wpra.social_platforms[platform]['url']['mobile'] != "undefined")
            ? wpra.social_platforms[platform]['url']['mobile']
            : wpra.social_platforms[platform]['url']['desktop'];
        window.open(platform_url + location.href, '_blank', 'width=626, height=436');
    });

    WPRA_Front.animate_emojis();

    $(window).scroll(function () {
        WPRA_Front.checkIfPlayable();
    });

});

