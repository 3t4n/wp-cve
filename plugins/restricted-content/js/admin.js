jQuery(document).ready(function ($) {

    $(".rsc-ticket-type-select-input input").change(function () {
        jQuery('.rsc-ticket-type-select-input').each(function () {

            if (jQuery('input:radio', this).is(':checked')) {
                jQuery(this).addClass('rc-radio-selected');
            } else {
                jQuery(this).removeClass('rc-radio-selected');
            }
        });

        //jQuery(this).parent().addClass('rc-radio-selected');

    });

    $.fn.animateHighlight = function (element, highlightColor, duration, start_text) {
        var end_text = element.text();

        element.text(start_text || '✓');

        var highlightBg = highlightColor || '#8abf9b';
        var animateMs = duration || 500;
        var originalBg = this.css("background-color");

        if (!originalBg || originalBg == highlightBg)
            originalBg = "#FFFFFF";

        jQuery(this)
                .css("backgroundColor", highlightBg)
                .animate({backgroundColor: originalBg}, animateMs, null, function () {
                    jQuery(this).css("backgroundColor", originalBg);
                    element.text(end_text);
                });
    };

    window.rsc_copy_field_to_clipboard = function rsc_copy_field_to_clipboard(element) {
        element.select();
        document.execCommand("copy");
        element.blur();
    }

    $('.rsc_tooltip_hover').tooltip({
        content: function () {
            return $(this).prop('title');
        },
        show: null,
        close: function (event, ui) {
            ui.tooltip.hover(
                    function () {
                        $(this).stop(true).fadeTo(100, 1);
                    },
                    function () {
                        $(this).fadeOut("100", function () {
                            $(this).remove();
                        })
                    });
        },
        position: {
            my: 'left-40 top+35',
            at: 'center top',
            using: function (position, feedback) {
                console.log(feedback);
                $(this).css(position);
                $(this).addClass(feedback.vertical);
            }
        }
    });

    jQuery('.rsc-yes-no-wrap').each(function () {
        var rsc_save_wrap = this;

        if ($('.rsc-radio-yes', this).is(':checked')) {
            jQuery('.rsc-customized-yes-no', rsc_save_wrap).removeClass('rsc-no-active');
            jQuery('.rsc-customized-yes-no', rsc_save_wrap).addClass('rsc-yes-active');
        }

        jQuery('.rsc-customized-yes-no', this).click(function () {
            if (jQuery(this).hasClass('rsc-no-active')) {
                jQuery(this).removeClass('rsc-no-active');
                jQuery(this).addClass('rsc-yes-active');
                jQuery('.rsc-radio-yes', rsc_save_wrap).attr('checked', true);
                jQuery('.rsc-radio-no', rsc_save_wrap).attr('checked', false);
            } else {
                jQuery(this).removeClass('rsc-yes-active');
                jQuery(this).addClass('rsc-no-active');
                jQuery('.rsc-radio-yes', rsc_save_wrap).attr('checked', false);
                jQuery('.rsc-radio-no', rsc_save_wrap).attr('checked', true);
            }
        });

    });

    function rsc_prepopulate_chosen_multi_select(chosen, selected_box) {
        var results_data = chosen.chosen.results_data;
        var chosen_results_html = '';
        var li_class = '';

        for (var i = 0; i < results_data.length; i++) {
            var element = results_data[i];

            //element.disabled
            if (element.selected == true) {
                li_class = 'result-selected';
            } else {
                li_class = 'active-result';
            }

            chosen_results_html = chosen_results_html + '<li class="' + li_class + '" data-option-array-index="' + element.array_index + '">' + element.html + '</li>';
        }

        selected_box.parent().find('.chosen-results').html(chosen_results_html);
    }

    $('.rsc_outside_wrap select.chosen-select').each(function () {
        var selected_box = $(this);

        $(this).on('chosen:ready', function (e, chosen) {
            $('.chosen-drop').css('display', 'block');

            rsc_prepopulate_chosen_multi_select(chosen, selected_box);

            field_title = $(this).attr('data-placeholder');
            $(this).next('.chosen-container-multi').prepend('<span class="rsc-chosen-title">' + field_title + '</span');
        });
    });

    $(".rsc_outside_wrap select").chosen({disable_search_threshold: 5, allow_single_deselect: false});

    $(document).on('widget-updated', function (e, widget) {/* widget-added*/
        //rsc_chosen();
        widget.find('.rsc_metabox select').chosen({disable_search_threshold: 10, allow_single_deselect: false});

        widget.find('.rsc_metabox select').css('width', '25em');
        widget.find('.rsc_metabox select').css('display', 'block');

        widget.find('.rsc_metabox select').css('display', 'none');
        widget.find('.rsc_metabox .chosen-container').css('width', '100%');
        widget.find('.rsc_metabox .chosen-container').css('max-width', '25em');
        widget.find('.rsc_metabox .chosen-container').css('min-width', '1em');

        widget.find('.rsc_metabox select').trigger("chosen:updated");
        rsc_reopen_selected();
    });

    $(document).on('widget-added', function (e, widget) {
        widget.find('.rsc_metabox select').chosen({disable_search_threshold: 10, allow_single_deselect: false});

        widget.find('.rsc_metabox select').css('width', '25em');
        widget.find('.rsc_metabox select').css('display', 'block');

        widget.find('.rsc_metabox select').css('display', 'none');
        widget.find('.rsc_metabox .chosen-container').css('width', '100%');
        widget.find('.rsc_metabox .chosen-container').css('max-width', '25em');
        widget.find('.rsc_metabox .chosen-container').css('min-width', '1em');

        widget.find('.rsc_metabox select').trigger("chosen:updated");
    });

    function rsc_reopen_selected() {
        $(".rsc_content_availability").each(function (index) {
            rsc_content_availability_options(this);
            rsc_tickera_users_options(this);
            rsc_woo_users_options(this);
            rsc_edd_users_options(this);
        });
    }

    rsc_reopen_selected();

    $(document).on('change', '.rsc_content_availability', function (e) {
        rsc_content_availability_options(e.target);
        rsc_tickera_users_options(e.target);
        rsc_woo_users_options(e.target);
        rsc_edd_users_options(e.target);
    });

    $(document).on('change', '.rsc_tickera_radio', function (e) {
        rsc_tickera_users_options(e.target);
    });

    $(document).on('change', '.rsc_woo_radio', function (e) {
        rsc_woo_users_options(e.target);
    });

    $(document).on('change', '.rsc_edd_radio', function (e) {
        rsc_edd_users_options(e.target);
    });

    function rsc_edd_users_options(obj) {

        var rsc_edd_users = $(obj).parent().find('.rsc_edd_radio:checked').val();
        var sub_metabox = $(obj).parent().find('.rsc_sub_sub_metabox_' + rsc_edd_users);

        if (sub_metabox.length > 0) {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_product.rsc_sub_sub'));
            sub_metabox.addClass('rsc_show');
            sub_metabox.removeClass('rsc_hide')
        } else {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_product.rsc_sub_sub'));
        }
    }

    function rsc_woo_users_options(obj) {

        var rsc_woo_users = $(obj).parent().find('.rsc_woo_radio:checked').val();
        var sub_metabox = $(obj).parent().find('.rsc_sub_sub_metabox_' + rsc_woo_users);

        if (sub_metabox.length > 0) {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_product.rsc_sub_sub'));
            sub_metabox.addClass('rsc_show');
            sub_metabox.removeClass('rsc_hide')
        } else {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_product.rsc_sub_sub'));
        }
    }

    function rsc_tickera_users_options(obj) {

        var rsc_tickera_users = $(obj).parent().find('.rsc_tickera_radio:checked').val();

        var sub_metabox = $(obj).parent().find('.rsc_sub_sub_metabox_' + rsc_tickera_users);

        if (sub_metabox.length > 0) {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_ticket_type.rsc_sub_sub'));
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_event.rsc_sub_sub'));
            sub_metabox.addClass('rsc_show');
            sub_metabox.removeClass('rsc_hide')
        } else {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_ticket_type.rsc_sub_sub'));
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_sub_metabox_event.rsc_sub_sub'));
        }
    }

    function rsc_content_availability_options(obj) {
        var rsc_selected_content_availability = $(obj).val();
        if (rsc_selected_content_availability !== 'everyone') {

            var sub_metabox = $(obj).parent().find('.rsc_sub_metabox.rsc_sub_metabox_' + rsc_selected_content_availability);

            if (sub_metabox.length > 0) {
                rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_metabox'));
                sub_metabox.addClass('rsc_show');
                sub_metabox.removeClass('rsc_hide');
            } else {
                rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_metabox'));
            }
        } else {
            rsc_hide_all_sub_metaboxes($(obj).parent().find('.rsc_sub_metabox'));
        }
    }

    function rsc_hide_all_sub_metaboxes(element) {
        $(element).removeClass('rsc_show');
        $(element).addClass('rsc_hide');
    }

    function rsc_chosen() {
        $("#rsc_metabox select, .rsc_metabox select").chosen({disable_search_threshold: 10, allow_single_deselect: false});

        $("#rsc_metabox select, .rsc_metabox select").css('width', '25em');
        $("#rsc_metabox select, .rsc_metabox select").css('display', 'block');

        $("#rsc_metabox select, .rsc_metabox select").css('display', 'none');
        $("#rsc_metabox .chosen-container, .rsc_metabox .chosen-container").css('width', '100%');
        $("#rsc_metabox .chosen-container, .rsc_metabox .chosen-container").css('max-width', '25em');
        $("#rsc_metabox .chosen-container, .rsc_metabox .chosen-container").css('min-width', '1em');

        $("#rsc_metabox select, .rsc_metabox select").trigger("chosen:updated");
    }

    rsc_chosen();

    if (rsc_vars.tc_check_page == 'restricted_content_settings') {
        jQuery(".rsc-nav-tab-wrapper").sticky({
            topSpacing: 30,
            bottomSpacing: 50
        });
    }

    $('.rsc_tooltip').tooltip({
        content: function () {
            return $(this).prop('title');
        },
        show: null,
        close: function (event, ui) {
            ui.tooltip.hover(
                    function () {
                        $(this).stop(true).fadeTo(100, 1);
                    },
                    function () {
                        $(this).fadeOut("100", function () {
                            $(this).remove();
                        })
                    });
        }
    });

    $(document).on('change', '.has_conditional', function () {
        rsc_conditionals_init();
    });

    function rsc_conditionals_init( ) {
        $('.rsc_conditional').each(function (i, obj) {
            rsc_conditional($(this));
        });
    }

    function rsc_conditional(obj) {

        var field_name = $(obj).attr('data-condition-field_name');
        if (!$('.' + field_name).hasClass('has_conditional')) {
            $('.' + field_name).addClass('has_conditional');
        }

        var field_type = $(obj).attr('data-condition-field_type');
        var value = $(obj).attr('data-condition-value');
        var action = $(obj).attr('data-condition-action');
        if (field_type == 'radio') {
            var selected_value = $('.' + field_name + ':checked').val( );
        }

        if (field_type == 'text' || field_type == 'textarea' || field_type == 'select') {
            var selected_value = $('.' + field_name).val( );
        }

        if (value == selected_value) {
            if (action == 'hide') {
                $(obj).hide();
            }
            if (action == 'show') {
                $(obj).show(200);
            }
        } else {
            if (action == 'hide') {
                $(obj).show(200);
            }
            if (action == 'show') {
                $(obj).hide();
            }
        }
        rsc_chosen();
    }

    rsc_conditionals_init();

    $("#available-widgets select").chosen('destroy');

    $(document).on('change', '.rsc_woo_time_radio', function () {
      var element = $(this).parent().find('.rsc_woo_times');
      if($(this).val() == 'indefinitely' || $(this).val() == ''){
          $(element).hide();
        }else{
          $(element).show();
        }
    });

    function hide_show_woo_times(){
      $('.rsc_woo_time_radio:checked').each(function (i, obj) {
        var element = $(this).parent().find('.rsc_woo_times');
        if($(this).val() == 'indefinitely' || $(this).val() == ''){
          $(element).hide();
        }else{
          $(element).show();
        }
      });
    }

    hide_show_woo_times();

    $(document).on('change', '.rsc_edd_time_radio', function () {
      var element = $(this).parent().find('.rsc_edd_times');
      if($(this).val() == 'indefinitely' || $(this).val() == ''){
          $(element).hide();
        }else{
          $(element).show();
        }
    });

    function hide_show_edd_times(){
      $('.rsc_edd_time_radio:checked').each(function (i, obj) {
        var element = $(this).parent().find('.rsc_edd_times');
        if($(this).val() == 'indefinitely' || $(this).val() == ''){
          $(element).hide();
        }else{
          $(element).show();
        }
      });
    }

    hide_show_edd_times();


});
