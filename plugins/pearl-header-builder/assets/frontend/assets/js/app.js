(function ($) {
    $(document).ready(function () {
        stm_switcher();
        js_active_trigger();
        js_active_switch();
        close_mobile_header();
    });

    function js_active_switch() {
        $('.js_active_switcher .js_active_switcher__a').on('click', function (e) {
            e.preventDefault();
            $(this).closest('.js_active_switcher').find('.js_active_switcher__a').removeClass('active');
            $(this).addClass('active');
        })
    }

    function js_active_trigger() {
        var opened = false;
        var dataToggle = '';
        var $element = '';
        var $this = '';
        $('.js_trigger__click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            $this = $(this);

            dataToggle = $(this).attr('data-toggle');
            if (typeof dataToggle == 'undefined') dataToggle = true;

            $element = $(this).closest('.js_trigger').find('.js_trigger__unit');
            var element = $(this).attr('data-element');
            if (typeof element !== 'undefined') $element = $(element);

            if (dataToggle && dataToggle !== 'false') {
                $element.slideToggle('fast');
            } else {
                $element.toggleClass('active');
            }

            $(this).toggleClass('active');
            opened = $(this).hasClass('active') ? true : false;
        });
    }

    function stm_switcher() {
        $('.stm-switcher__trigger').on("click", function () {
            $(this).closest('.stm-switcher').find('.stm-switcher__list').toggleClass('active');
            $(this).toggleClass('active');
        });

        $('.stm-switcher__option').on("click", function () {
            var stm_switch = $(this).data('switch');

            /*Change in list*/
            $(this).closest('.stm-switcher').parent().find('.js-switcher').addClass('js-switcher__hidden');
            $(this).closest('.stm-switcher').parent().find('.js-switcher_' + stm_switch).removeClass('js-switcher__hidden');

            /*Change trigger text*/
            $(this).closest('.stm-switcher').find('.stm-switcher__text').text($(this).text());

            /*Close dropdown*/
            $(this).closest('.stm-switcher__list').removeClass('active');
            $(this).closest('.stm-switcher').find('.stm-switcher__trigger').removeClass('active');
        });
    }

    function close_mobile_header() {
        $('.stm-header__overlay').on('click', function(e){
            $('.stm-header__overlay, .stm-header, .stm_mobile__switcher').removeClass('active');
        });

        $('.stm-navigation li.menu-item-has-children > a').each(function () {
            var href = $(this).attr("href");
            if (href == "#") {
                $(this).parent().addClass('href_empty');
            }

            $(this).append('<span class="stm_mobile__dropdown"></span>');
        });

        $('body').find('.stm_mobile__dropdown').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest('li').toggleClass('active');
            $(this).closest('li').children('.sub-menu').toggle();
        });
    }

})(jQuery);