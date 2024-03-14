// Create a function to pick up the link click
jQuery(function ($) {
    //AJAX Activation
    $(document).on('click', 'a.activate-now', function (event) {
        event.preventDefault(); // prevent default behaviour of link click

        var t = $(this),
            data = {
                action: 'activate_yith_essential_kit_module',
                slug: t.data('slug')
            },
            module = t.parents('.plugin-card'),
            notice = $('.yith-jetpack-message');
        module.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
        $.get(ajaxurl, data, function (response) {
            if (response.status) {
                module.unblock();
                t.replaceWith(response.button);
                notice.html(response.message).addClass('show activated').removeClass('deactivated');
                setTimeout(function () {
                    notice.removeClass('show');
                }, 4000);
            }
        });

    });
    //Ajax Deactivation
    $(document).on('click', 'a.deactivate-now', function (event) {
        event.preventDefault(); // prevent default behaviour of link click

        var t = $(this),
            data = {
                action: 'deactivate_yith_essential_kit_module',
                slug: t.data('slug')
            },
            module = t.parents('.plugin-card'),
            notice = $('.yith-jetpack-message');
        module.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
        $.get(ajaxurl, data, function (response) {
            if (response.status) {
                module.unblock();
                t.replaceWith(response.button);
                notice.html(response.message).addClass('show deactivated').removeClass('activated');
                setTimeout(function () {
                    notice.removeClass('show');
                }, 4000);
            }
        });


    });

    //Ajax Install
    $(document).on('click', 'a.install-now', function (event) {
        event.preventDefault(); // prevent default behaviour of link click

        var t = $(this),
            data = {
                action: 'install_yith_essential_kit_module',
                slug: t.data('slug')
            },
            module = t.parents('.plugin-card'),
            notice = $('.yith-jetpack-message');
        module.block({
            message: null,
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
        $.get(ajaxurl, data, function (response) {
            if (response.status) {
                module.unblock();
                t.replaceWith(response.button);
                notice.html(response.message).addClass('show activated').removeClass('deactivated');
                setTimeout(function () {
                    notice.removeClass('show');
                }, 4000);
            }
        });


    });
});