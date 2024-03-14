(function ($) {
    $(function () {

        if (typeof localStorage.activateWebsiteArticleMonetizationByMagenetShowTutorial == 'undefined')
            localStorage.activateWebsiteArticleMonetizationByMagenetShowTutorial = true;

        $('#deactivate-website-article-monetization-by-magenet').click(function () {
            delete localStorage.activateWebsiteArticleMonetizationByMagenetShowTutorial;
        });
        
        var close_all_tutorial = function () {
            $('.magenet-article-tutorial-popup').dialog('close');
            return false;
        };

        var next_prev_popup_open = function (button, direction)
        {
            var id_popup = $(button).closest('.magenet-article-tutorial-popup').attr('id');

            if (id_popup) {
                var where = 1;
                if (direction == 'prev')
                    where = -1;
                var ids_array = id_popup.split('-');

                var id_other_number = parseInt(ids_array[2]) + 1 * where;
                var id_other_str = '#' + 'mn-a-' + id_other_number;

                $(id_other_str).dialog('open');
                $(id_other_str).find('a').blur();
            }
        };

        $('.magenet-article-tutorial-popup').dialog({
            autoOpen: false,
            width: 500,
            draggable: false,
            dialogClass: 'magenet-abp-dialog',
            show: {effect: "fade", duration: 300},
            hide: {effect: "fade", duration: 300}
        });

        $('.magenet-abp-dialog .btn_prev').click(function () {
            close_all_tutorial();
            next_prev_popup_open(this, 'prev');
        });

        $('.magenet-abp-dialog .btn_next').click(function () {
            close_all_tutorial();
            next_prev_popup_open(this, 'next');
        });

        $('.magenet-abp-dialog .tutorial-close').click(function () {
            close_all_tutorial();
        });

        $('.show-magenet-article-tutorial').click(function () {
            close_all_tutorial();
            $('#mn-a-1').dialog('open');
            $('#mn-a-1').find('a').blur();
        });

        if (localStorage.activateWebsiteArticleMonetizationByMagenetShowTutorial == 'true') {
            jQuery.post(ajaxurl, {'action': 'abp_action'}, function (response) {
                if (response == 1) {
                    localStorage.activateWebsiteArticleMonetizationByMagenetShowTutorial = false;
                    $('#mn-a-0').dialog('open');
                }
            });
        }
    });
})(jQuery);