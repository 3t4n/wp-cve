;(function ($) {
    'use strict';

    /*(function($) {

        var Defaults = $.fn.select2.amd.require('select2/defaults');

        $.extend(Defaults.defaults, {
            searchInputPlaceholder: ''
        });

        var SearchDropdown = $.fn.select2.amd.require('select2/dropdown/search');

        var _renderSearchDropdown = SearchDropdown.prototype.render;

        SearchDropdown.prototype.render = function(decorated) {

            // invoke parent method
            var $rendered = _renderSearchDropdown.apply(this, Array.prototype.slice.apply(arguments));

            this.$search.attr('placeholder', this.options.get('searchInputPlaceholder'));

            return $rendered;
        };

    })(window.jQuery);*/

    $(document).ready(function () {
        var cbcurrencyconverter_awn_options = {
            labels: {
                tip          : cbcurrencyconverter_setting.awn_options.tip,
                info         : cbcurrencyconverter_setting.awn_options.info,
                success      : cbcurrencyconverter_setting.awn_options.success,
                warning      : cbcurrencyconverter_setting.awn_options.warning,
                alert        : cbcurrencyconverter_setting.awn_options.alert,
                async        : cbcurrencyconverter_setting.awn_options.async,
                confirm      : cbcurrencyconverter_setting.awn_options.confirm,
                confirmOk    : cbcurrencyconverter_setting.awn_options.confirmOk,
                confirmCancel: cbcurrencyconverter_setting.awn_options.confirmCancel
            }
        };

        $('.setting-color-picker-wrapper').each(function (index, element) {
            //console.log(element);

            var $color_field_wrap = $(element);

            var $color_field      = $color_field_wrap.find('.setting-color-picker');
            var $color_field_fire = $color_field_wrap.find('.setting-color-picker-fire');

            var $current_color = $color_field_fire.data('current-color');



            // Simple example, see optional options for more configuration.
            const pickr = Pickr.create({
                el     : $color_field_fire[0],
                theme  : 'classic', // or 'monolith', or 'nano'
                default: $current_color,

                swatches: [
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {

                    // Main components
                    preview: true,
                    opacity: true,
                    hue    : true,

                    // Input / output Options
                    interaction: {
                        hex  : true,
                        rgba : false,
                        hsla : false,
                        hsva : false,
                        cmyk : false,
                        input: true,
                        clear: true,
                        save : true
                    }
                },
                i18n      : cbcurrencyconverter_setting.pickr_i18n
            });

            pickr.on('init', instance => {
                //console.log('Event: "init"', instance);
            }).on('hide', instance => {
                //console.log('Event: "hide"', instance);
            }).on('show', (color, instance) => {
                //console.log('Event: "show"', color, instance);
            }).on('save', (color, instance) => {
                //console.log(color.toHEXA().toString());
                //console.log(color);

                if (color !== null) {
                    $color_field_fire.data('current-color', color.toHEXA().toString());
                    $color_field.val(color.toHEXA().toString());
                } else {
                    $color_field_fire.data('current-color', '');
                    $color_field.val('');
                }


                //console.log(instance);
                //console.log(color.toHEXA());
                //console.log(color.toHEX);
            }).on('clear', instance => {
                //console.log('Event: "clear"', instance);


            }).on('change', (color, source, instance) => {
                //console.log('Event: "change"', color, source, instance);

            }).on('changestop', (source, instance) => {
                //console.log('Event: "changestop"', source, instance);
            }).on('cancel', instance => {
                //console.log('Event: "cancel"', instance);
            }).on('swatchselect', (color, instance) => {
                //console.log('Event: "swatchselect"', color, instance);
            });

        });


        //select2
        $('.selecttwo-select-wrapper').each(function (index, element) {
             var $element = $(element);

            $element.find('.selecttwo-select').select2({
                placeholder: cbcurrencyconverter_setting.please_select,
                allowClear : false,
                theme      : 'default select2-container--cbx',
                //dropdownParent: $(element),
                searchInputPlaceholder: cbcurrencyconverter_setting.search_text
            });
        });


        var $setting_page = $('#cbcurrencyconverter-setting');
        var $setting_nav  = $setting_page.find('.setting-tabs-nav');
        var activetab     = '';

        if (typeof (localStorage) !== 'undefined') {
            activetab = localStorage.getItem('cbcurrencyconverteractivetab');
        }

        //if url has section id as hash then set it as active or override the current local storage value
        if (window.location.hash) {
            if ($(window.location.hash).hasClass('global_setting_group')) {
                activetab = window.location.hash;
                if (typeof (localStorage) !== 'undefined') {
                    localStorage.setItem('cbcurrencyconverteractivetab', activetab);
                }
            }
        }

        /*if (activetab !== '' && $(activetab).length && $(activetab).hasClass('global_setting_group')) {
            //$('.global_setting_group').hide();
            //$(activetab).fadeIn();
        }


        if (activetab !== '' && $(activetab + '-tab').length) {
            // $setting_nav.find('a').removeClass('active');
            //$(activetab + '-tab').addClass('active');
        }*/


        function setting_nav_change($tab_id) {
            if($tab_id === null){
                return;
            }

            $tab_id = $tab_id.replace('#', '');

            $setting_nav.find('a').removeClass('active');
            $('#' + $tab_id + '-tab').addClass('active');


            var clicked_group = '#' + $tab_id;

            if (typeof (localStorage) !== 'undefined') {
                localStorage.setItem('cbcurrencyconverteractivetab', clicked_group);
            }
            $('.global_setting_group').hide();
            $(clicked_group).fadeIn();

            //load the
            if(clicked_group === '#cbcurrencyconverter_tools'){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbcurrencyconverter_setting.ajaxurl,
                    data: {
                        action: 'cbcurrencyconverter_settings_reset_load',
                        security: cbcurrencyconverter_setting.nonce
                    },
                    success: function (data, textStatus, XMLHttpRequest) {

                        $('#cbcurrencyconverter_resetinfo_wrap').html(data.html);
                    }//end of success
                });//end of ajax
            }
        }

        //click on inidividual nav
        $setting_nav.on('click', 'a', function (e) {
            e.preventDefault();

            var $this   = $(this);
            var $tab_id = $this.data('tabid');

            $('.setting-select-nav').val($tab_id);
            $('.setting-select-nav').trigger('change');

        });

        $('.setting-select-nav').on('change', function (e) {
            var $this   = $(this);
            var $tab_id = $this.val();

            setting_nav_change($tab_id);
        });


        //set default
        if(activetab === null){
            activetab = $('.setting-tabs-nav').find('a.active').attr('href');
        }


        if(activetab !== null){
            var activetab_whash = activetab.replace('#', '');

            $('.setting-select-nav').val(activetab_whash);
            $('.setting-select-nav').trigger('change');
        }
        else{
            //what to do if null or first time
        }


        $('.wpsa-browse').on('click', function (event) {
            event.preventDefault();

            var self = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title   : self.data('uploader_title'),
                button  : {
                    text: self.data('uploader_button_text')
                },
                multiple: false
            });

            file_frame.on('select', function () {
                var attachment = file_frame.state().get('selection').first().toJSON();

                self.prev('.wpsa-url').val(attachment.url);
            });

            // Finally, open the modal
            file_frame.open();
        });

        //make the subheading single row
        $('.setting_heading').each(function (index, element) {
            var $element        = $(element);
            var $element_parent = $element.parent('td');

            $element_parent.attr('colspan', 2);
            $element_parent.prev('th').remove();
            $element_parent.parent('tr').removeAttr('class');
            $element_parent.parent('tr').addClass('global_setting_heading_section');
        });


        $('.setting_subheading').each(function (index, element) {
            var $element        = $(element);
            var $element_parent = $element.parent('td');

            $element_parent.attr('colspan', 2);
            $element_parent.prev('th').remove();
            $element_parent.parent('tr').removeAttr('class');
            $element_parent.parent('tr').addClass('global_setting_subheading_section');
        });


        $('.global_setting_group').each(function (index, element) {
            var $element    = $(element);

            $element.find('.submit_setting').removeClass('button-primary').addClass('primary');

            var $form_table = $element.find('.form-table');
            $form_table.prev('h2').remove();

            var $i = 0;
            $form_table.find('tr').each(function (index2, element) {
                var $tr = $(element);

                if (!$tr.hasClass('global_setting_heading_section')) {
                    $tr.addClass('global_setting_common_section');
                    $tr.addClass('global_setting_common_section_' + $i);
                } else {
                    $i++;
                    $tr.addClass('global_setting_heading_section_' + $i);
                    $tr.attr('data-counter', $i);
                    $tr.attr('data-is-closed', 0);
                }
            });




            $form_table.on('click', '.setting_heading', function (evt) {
                evt.preventDefault();

                var $this      = $(this);
                var $parent    = $this.closest('.global_setting_heading_section');
                var $counter   = Number($parent.data('counter'));
                var $is_closed = Number($parent.data('is-closed'));

                if ($is_closed === 0) {
                    $parent.data('is-closed', 1);
                    $parent.addClass('global_setting_heading_section_closed');
                    $('.global_setting_common_section_' + $counter).hide();
                } else {
                    $parent.data('is-closed', 0);
                    $parent.removeClass('global_setting_heading_section_closed');
                    $('.global_setting_common_section_' + $counter).show();
                }
            });

            $('#global_setting_group_actions').show();
            $('#global_setting_group_actions').on('click', '.global_setting_group_action', function (event) {
                event.preventDefault();

                $form_table.find('.setting_heading').trigger('click');
            });
        });

        //var adjustment_photo;
        $('.checkbox_fields_sortable').sortable({
            vertical: true,
            handle: '.checkbox_field_handle',
            containerSelector: '.checkbox_fields',
            itemSelector: '.checkbox_field',
            placeholder: 'checkbox_field_placeholder'
        });

       /* $('.global_setting_group').on('click', '.checkbox', function () {
            var mainParent = $(this).closest('.checkbox-toggle-btn');
            if ($(mainParent).find('input.checkbox').is(':checked')) {
                $(mainParent).addClass('active');
            } else {
                $(mainParent).removeClass('active');
            }
        });*/



        //one click save setting for the current tab
        $('#save_settings').on('click', function (e) {
            e.preventDefault();

            var $setting_nav = $('.setting-tabs-nav');

            var $current_tab = $setting_nav.find('.active');
            var $tab_id      = $current_tab.data('tabid');
            $('#' + $tab_id).find('.submit_setting').trigger('click');
        });

        //reset click
        $('#reset_data_trigger').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            //var $busy = parseInt($this.data('busy'));

            var notifier = new AWN(cbcurrencyconverter_awn_options);

            var onCancel = () => {};

            var onOk = () => {
                //$this.hide();
                $this.attr('disabled', true);
                //window.location.href = $this.attr('href');

                $this.hide();

                let  $reset_tables = $('.reset_tables:checkbox:checked').map(function() {
                    return this.value;
                }).get();

                let  $reset_options = $('.reset_options:checkbox:checked').map(function() {
                    return this.value;
                }).get();

                //console.log($reset_tables);
                //console.log($reset_options);

                //send ajax request to reset plugin
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: cbcurrencyconverter_setting.ajaxurl,
                    data: {
                        action: 'cbcurrencyconverter_settings_reset',
                        security: cbcurrencyconverter_setting.nonce,
                        reset_tables : $reset_tables,
                        reset_options : $reset_options
                    },
                    success: function (data, textStatus, XMLHttpRequest) {
                        window.location.href = data.url;

                    }//end of success
                });//end of ajax
            };

            notifier.confirm(
                cbcurrencyconverter_setting.are_you_sure_delete_desc,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: cbcurrencyconverter_setting.are_you_sure_global
                    }
                }
            );
        });//end click #reset_data_trigger

       /* $('#setting_info_trig').on('click', function (e) {
            e.preventDefault();

            $('#cbcurrencyconverter_resetinfo').toggle();
        });*/

        //copy shortcode
        $('.shortcode_demo_btn').on('click', function (event) {
            event.preventDefault();

            var $this      = $(this);
            var $target    = $this.data('target-cp');
            var $copy_area = $($target);

            $copy_area.focus();
            $copy_area.select();

            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    $this.text(cbcurrencyconverter_setting.copy_success);
                    $this.addClass('copy_success');
                } else {
                    $this.text(cbcurrencyconverter_setting.copy_fail);
                    $this.addClass('copy_fail');
                }
            } catch (err) {
                $this.text(cbcurrencyconverter_setting.copy_fail);
                $this.addClass('copy_fail');
            }

        });//end copy shortcode

        // add tab functionality for custom rates
        $('.custom-currency-link').on('click', function (e) {
            e.preventDefault();

            var currency = $(this).data("currency");
            var related_content = '.custom_rates_content_' + currency;

            $('#custom_rates_ul li').removeClass('active');
            $(this).parent('li').addClass('active');
            $('.custom_rates_content').hide().removeClass('active');
            $(related_content).show().addClass('active');
        });



        $('#cbcurrencyconverter_calculator_enabled_from_currencies').on('change', function (e){
            var $values = $(this).select2('val');
            var $data = [];
            $values.forEach(function (value, index){
                $data.push({
                    id: value,
                    text: all_currencies[value]+' - '+value
                });
            });

            $('#cbcurrencyconverter_calculator_from_currency').select2('destroy').empty().select2({data : $data});
        });

        $('#cbcurrencyconverter_calculator_enabled_to_currencies').on('change', function (e){
            var $values = $(this).select2('val');
            var $data = [];
            $values.forEach(function (value, index){
                $data.push({
                    id: value,
                    text: all_currencies[value]+' - '+value
                });
            });

            $('#cbcurrencyconverter_calculator_to_currency').select2('destroy').empty().select2({data : $data});
        });


        /*$('div.widgets-sortables')
            .on('sortstop', function (event, ui) {
                // only if this widget has the proper identifier...do something
                if (ui.item.find('.selecttwo-select').length == 0)
                    return;




                var item_parent_id = ui.item.closest('.widget').attr('id');


                ui.item.find('.selecttwo-select').select2({
                    dropdownParent: $('#'+item_parent_id),
                    placeholder: cbcurrencyconverter_setting.please_select,
                    allowClear: false
                });
            });*/

        //reset setting options
        $('#reset_data_trigger').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $confirm_title = $this.data('confirm-title');
            var $confirm = $this.data('confirm');

            var notifier = new AWN(cbcurrencyconverter_awn_options);

            var onCancel = () => {
            };

            var onOk = () => {
                $this.hide();
                window.location.href = $this.attr('href');
            };

            notifier.confirm(
                $confirm,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: $confirm_title
                    }
                }
            );
        });//end click #reset_data_trigger

        //reset transient data
        $('#reset_transient_trigger').on('click', function (e) {
            e.preventDefault();

            var $this = $(this);
            var $confirm_title = $this.data('confirm-title');
            var $confirm = $this.data('confirm');

            var notifier = new AWN(cbcurrencyconverter_awn_options);

            var onCancel = () => {
            };

            var onOk = () => {
                $this.hide();
                window.location.href = $this.attr('href');
            };

            notifier.confirm(
                $confirm,
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: $confirm_title
                    }
                }
            );
        });//end click #reset_data_trigger


    });

})(jQuery);
//settings