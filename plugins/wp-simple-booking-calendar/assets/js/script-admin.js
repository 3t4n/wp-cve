jQuery(function ($) {

    /*
     * Strips one query argument from a given URL string
     *
     */
    function remove_query_arg(key, sourceURL) {

        var rtn = sourceURL.split("?")[0],
            param,
            params_arr = [],
            queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";

        if (queryString !== "") {
            params_arr = queryString.split("&");
            for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                param = params_arr[i].split("=")[0];
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            }

            rtn = rtn + "?" + params_arr.join("&");

        }

        if (rtn.split("?")[1] == "") {
            rtn = rtn.split("?")[0];
        }

        return rtn;
    }


    /*
     * Adds an argument name, value pair to a given URL string
     *
     */
    function add_query_arg(key, value, sourceURL) {

        return sourceURL + '&' + key + '=' + value;

    }


    /**
     * Initialize colorpicker
     *
     */
    $('.wpsbc-colorpicker').wpColorPicker();

    /**
     * Initialize Chosen
     *
     */
    if (typeof $.fn.chosen != 'undefined') {

        $('.wpsbc-chosen').chosen();

    }

    /**
     * Links that have the inactive class should do nothing
     *
     */
    $(document).on('click', 'a.wpsbc-inactive, input[type=submit].wpsbc-inactive', function () {

        return false;

    });

    /**
     * Initialize the sortable function on the Calendar Legend List Table
     *
     */
    $('table.wpsbc_legend_items tbody').sortable({
        handle: '.wpsbc-move-legend-item',
        containment: '#wpcontent',
        placeholder: 'wpsbc-list-table-sort-placeholder',
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();

            $helper.children().each(function (index) {
                // Set helper cell sizes to match the original sizes
                $(this).width($originals.eq(index).width());
            });

            return $helper;
        },
        update: function (e, ui) {

            var legend_item_ids = [];

            $('table.wpsbc_legend_items tbody tr .wpsbc-move-legend-item').each(function () {
                legend_item_ids.push($(this).data('id'));
            })

            var data = {
                action: 'wpsbc_sort_legend_items',
                token: $('[name="wpsbc_token"]').val(),
                calendar_id: $('[name="calendar_id"]').val(),
                legend_item_ids: legend_item_ids
            }

            // Add table wrapper and overlay
            $('table.wpsbc_legend_items').wrap('<div class="wpsbc-wp-list-table-wrapper"></div>');
            $('table.wpsbc_legend_items').closest('.wpsbc-wp-list-table-wrapper').append('<div class="wpsbc-overlay"><div class="spinner"></div></div>');

            // Make sort ajax call
            $.post(ajaxurl, data, function (response) {

                response = JSON.parse(response);

                if (!response.success) {

                    window.location.replace(response.redirect_url_error);

                } else {

                    // Remove table wrapper and overlay
                    $('table.wpsbc_legend_items').siblings('.wpsbc-overlay').remove();
                    $('table.wpsbc_legend_items').unwrap('.wpsbc-wp-list-table-wrapper');

                }


            });

        }
    });

    $('table.wpsbc_legend_items tbody').disableSelection();


    /**
     * Handle show/hide of the second color option for Legend Item add/edit screen
     *
     */
    $(document).on('change', 'select[name="legend_item_type"]', function () {

        if ($(this).val() == 'single')
            $('#wpsbc-legend-item-color-2').closest('.wp-picker-container').hide();
        else
            $('#wpsbc-legend-item-color-2').closest('.wp-picker-container').show();

    });

    $(document).ready(function () {

        if ($('select[name="legend_item_type"]').length > 0)
            $('select[name="legend_item_type"]').trigger('change');

    });

    /**
     * Tab Navigation
     *
     */
    $('.wpsbc-nav-tab').on('click', function (e) {
        e.preventDefault();

        // Nav Tab activation
        $('.wpsbc-nav-tab').removeClass('wpsbc-active').removeClass('nav-tab-active');
        $(this).addClass('wpsbc-active').addClass('nav-tab-active');

        // Show tab
        $('.wpsbc-tab').removeClass('wpsbc-active');

        var nav_tab = $(this).attr('data-tab');
        $('.wpsbc-tab[data-tab="' + nav_tab + '"]').addClass('wpsbc-active');
        $('input[name=active_tab]').val(nav_tab);

        // Change http referrer
        $_wp_http_referer = $('input[name=_wp_http_referer]');

        var _wp_http_referer = $_wp_http_referer.val();
        _wp_http_referer = remove_query_arg('tab', _wp_http_referer);
        $_wp_http_referer.val(add_query_arg('tab', $(this).attr('data-tab'), _wp_http_referer));

    });

    /**
     * Calendar Title Translations Toggle
     */
    $(".wrap.wpsbc-wrap-edit-calendar #titlediv .titlewrap-toggle").click(function (e) {
        e.preventDefault();
        $(this).toggleClass('open');
        $(".titlewrap-translations").slideToggle();

    });

	/**
     * Toggle settings translations
     * 
     */
    $(".wpsbc-wrap").on('click', '.wpsbc-settings-field-show-translations', function (e) {
        e.preventDefault();
        $(this).parents('.wpsbc-settings-field-translation-wrapper').find(".wpsbc-settings-field-translations").slideToggle();
        $(this).toggleClass('open');
    })


    /**
     * Modifies the modal inner height to permit the scrollbar to function properly
     *
     */
    $(window).resize(function () {

        $('.wpsbc-modal-inner').outerHeight($('.wpsbc-modal.wpsbc-active').outerHeight() - $('.wpsbc-modal.wpsbc-active .wpsbc-modal-header').outerHeight() - $('.wpsbc-modal.wpsbc-active .wpsbc-modal-nav-tab-wrapper').outerHeight());

    });

    /**
     * Close modal window
     *
     */
    $(document).on('click', '.wpsbc-modal-close', function (e) {

        e.preventDefault();

        $(this).closest('.wpsbc-modal').find('.wpsbc-modal-inner').scrollTop(0);

        $(this).closest('.wpsbc-modal').removeClass('wpsbc-active');
        $(this).closest('.wpsbc-modal').siblings('.wpsbc-modal-overlay').removeClass('wpsbc-active');

        $(window).resize();

    });

    /**
     * Close modal on clicking the modal overlay
     *
     */
    $(document).on('click', '.wpsbc-modal-overlay.wpsbc-active', function (e) {

        $('.wpsbc-modal.wpsbc-active').find('.wpsbc-modal-close').click();

    });

    /**
     * Open Shortcode Generator modal
     *
     */
    $(document).on('click', '#wpsbc-shortcode-generator-button', function (e) {

        e.preventDefault();

        $('#wpsbc-modal-add-calendar-shortcode, #wpsbc-modal-add-calendar-shortcode-overlay').addClass('wpsbc-active');

        $(window).resize();

        $('.wpsbc-modal.wpsbc-active').click();

    });

    /**
     * Builds the shortcode for the Single Calendar and inserts it in the WordPress text editor
     *
     */
    $(document).on('click', '#wpsbc-insert-shortcode-single-calendar', function (e) {

        e.preventDefault();

        // Begin shortcode
        var shortcode = '[wpsbc ';

        $('#wpsbc-modal-add-calendar-shortcode.wpsbc-active .wpsbc-shortcode-generator-field-calendar').each(function () {

            shortcode += $(this).data('attribute') + '="' + $(this).val() + '" ';

        });

        // End shortcode
        shortcode = shortcode.trim();
        shortcode += ']';

        window.send_to_editor(shortcode);

        $(this).closest('.wpsbc-modal').find('.wpsbc-modal-close').first().trigger('click');

    });


    /**
     * Register and deregister website functionality
     *
     */
    $(document).on('click', '#wpsbc-register-website-button, #wpsbc-deregister-website-button', function (e) {

        e.preventDefault();

        window.location = add_query_arg('serial_key', $('[name="serial_key"]').val(), $(this).attr('href'));

    });

    $(document).on('click', '#wpsbc-check-for-updates-button', function (e) {

        if ($(this).attr('disabled') == 'disabled')
            e.preventDefault();

    });

    /**
     * Move the calendar from the sidebar to the main content and back in the calendar edit screen 
     * when resizing the window
     *
     */
    $(window).on('resize', function () {

        // Move the calendar from the sidebar to the main content
        if ($(window).innerWidth() < 850) {

            $('.wpsbc-container').closest('.postbox').detach().prependTo('#post-body-content');

        } else {

            $('.wpsbc-container').closest('.postbox').detach().prependTo('#postbox-container-1');

        }

    });

    $(window).trigger('resize');


    /**
     * iCalendar Export warning message
     * 
     */
    $("#ical-export-legend-items").change(function () {
        if ($(this).find('option:selected').length > 1) {
            $(this).siblings('.wpsbc-warning').show();
        } else {
            $(this).siblings('.wpsbc-warning').hide();
        }
    }).trigger('change');

    jQuery(window).on('load resize', function () {
        jQuery(".wpsbc-wrap-upgrade-to-premium ul li").wpsbc_adjust_height();
    })

});

/***** Adjust Height Function *****/
jQuery.fn.wpsbc_adjust_height = function () {
    var maxHeightFound = 0;
    this.css('min-height', '1px');

    if (this.is('a')) {
        this.removeClass('loaded');
    };

    this.each(function () {
        if (jQuery(this).outerHeight() > maxHeightFound) {
            maxHeightFound = jQuery(this).outerHeight();
        }
    });
    this.css('min-height', maxHeightFound);
    if (this.is('a')) {
        this.addClass('loaded');
    };
};
