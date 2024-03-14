var adbutler;
(function ($) {
    adbutler = {
        default_settings: {},
        settings: {},
        account_data: {},
        zone_list: [],
        zone_list_populated: false,
        init: function (settings) {
            adbutler.settings = $.extend({}, adbutler.default_settings, settings);
           
            if(adbutler.settings.adbutler_key =='')
            {                
                adbutler.badKey();
                return;
            }
            
            if ($('.adbutler_widget, .adbutler_settings').length > 0 ||($("[id^='customize-control-widget_adbutler-']").length > 0)) {
                adbutler.populate_zone_lists();
            }

            $('div.widgets-sortables')
                .on('sortstop', function (event, ui) {
                    // only if this widget has an adbutler_widget container in it (aka ours), do something
                    if (ui.item.find('.adbutler_widget').length == 0)
                        return;

                    // initialize events on the item
                    adbutler.populate_zone_lists(true);
                });

            var category_restrict_checkbox = $('#adbutler_restrict_to_pages');
            if (category_restrict_checkbox.length > 0) adbutler.handle_category_restrict(category_restrict_checkbox[0]);
        },
        badKey:function(){
            $('.adbutler_widget').replaceWith('<h3>No key configured</h3>');
            $('.adbutler_settings').remove();
        },
        handle_zone_select: function (selectEl) {
            var $select = $(selectEl),
                $widget = $select.parents('.adbutler_widget, .adbutler_settings'),
                $selected = $select.find('option:selected'),
                zone_id = $selected.val();
            adbutler.populate_widget_type_select($widget, zone_id);
            adbutler.store_selected_zone_defintion($widget, zone_id);
        },

        get_zone_definition: function (zone_id) {
            var def = false;
            $.each(adbutler.zone_list, function (pubid, pubobj) {
                $.each(pubobj.zones, function (k, v) {
                    if (v.zone_id == zone_id) {
                        def = v;
                        return false;
                    }
                });
                if (def)
                    return false;
            });
            return def;
        },

        populate_zone_lists: function (force) {
            force = (force === true);

            if (adbutler.zone_list_populated && force !== true)
                return;

            jQuery.ajax({
                dataType: 'jsonp',
                data: {
                    key: adbutler.settings.adbutler_key,
                    form: 'jsonp',
                    action: 'zones'                   
                },
                url: adbutler.settings.api_url + "?callback=?",
                jsonp: true
            })
                .done(adbutler.populate_callback_done)
                .fail(adbutler.populate_callback_fail)
                .always(function () {

                });
            adbutler.zone_list_populated = true;
        },


        update_all_lists: function () {
            if(adbutler.settings.adbutler_key =='')
            {
                adbutler.badKey();
                return;
            }
            // find the widgets
            $adbutler_widgets = $('.adbutler_widget, #adbutler-page-wrapper');

            // for each widget, find the zone selection drop down for population
            $adbutler_widgets.each(function (k, v) {
                var $widget = $(v),
                    $select = $widget.find('.adbutler_zone_select'),
                    selected_value = $select.data('default-zone');//$select.find(':selected').val();

                // remove any existing options (other than the default)               
                $select.empty().append('<option value="0">-- Select a zone --</option>');
                var o = [];
                // for each zone possibility, add an option to the drop down at the end of the list
                $.each(adbutler.zone_list, function (k, v) {
                    if(v.zones !== null){
                    o.push('<optgroup label="' + v.name + '">');
                    $.each(v.zones, function (k, v) {

                        o.push('<option value="' + v.zone_id + '"');
                        // if this zone was our previously selected zone, maintain that selection
                        if (v.zone_id == selected_value)
                            o.push(' selected');
                        o.push('>' + v.zone_name + ' (' + v.zone_size + ')</option>');
                    });
                    o.push('</optgroup>');
                    }
                });
                $select.append(o.join(''));
                adbutler.handle_zone_select($select[0]);
            });

        },
        populate_callback_done: function (data, status, xhr) {
            if (data.success.publisher_zones) {
                adbutler.zone_list = data.success.publisher_zones;
                adbutler.update_all_lists();
            }
            else if (data.failure) {
                console.log(data.failure)
            }
            else{
                $select = $('.adbutler_zone_select');
                $select.empty().append('<option value="0">No Zones Available</option>');
            }
               
        },
        populate_callback_fail: function (data, status, xhr) {
        },

        populate_widget_type_select: function (widget, zone_id) {
            if (zone_id == 0) {
                adbutler.toggle_tag_type_select(widget, 'AUTO');
            }
            else {
                var zone_def = adbutler.get_zone_definition(zone_id);
                adbutler.toggle_tag_type_select(widget, zone_def.responsive_type);
            }
        },
        toggle_tag_type_select: function (widget, responsive_type) {
            var $select_fixed = widget.find('.adbutler_type_fixed'),
                $select_responsive = widget.find('.adbutler_type_responsive');

            if (responsive_type == 'FIXED') {
                $select_responsive.find('select').prop('disabled', true);
                $select_responsive.hide();

                $select_fixed.find('select').prop('disabled', false);
                $select_fixed.show();
            }
            else if (responsive_type == 'AUTO' || responsive_type == 'INHERIT') {
                $select_fixed.find('select').prop('disabled', true);
                $select_fixed.hide();

                $select_responsive.find('select').prop('disabled', false);
                $select_responsive.show();
            }
            else {
            }
        },
        store_selected_zone_defintion: function (widget, zone_id) {
            if (zone_id == 0)
                return;
            var zone_def = adbutler.get_zone_definition(zone_id);
            var $size = widget.find('.size_hidden'),
                $name = widget.find('.name_hidden'),
                $responsive = widget.find('.responsive_hidden');
            $size.val(zone_def.zone_size);
            $name.val(zone_def.zone_name);
            $responsive.val(zone_def.responsive_type);
        },
        handle_post_feed_enable: function (checkbox) {
            // all form elements except the checkbox
            var $ele_not_checkbox = $('.adbutler-interval-ad-settings :input')
                .not('#adbutler_interval_ads_enable')
                .not('#adbutler_interval_ads_form :submit')
                .not('#nonce_check')

            if (checkbox.checked) {
                $ele_not_checkbox.each(function (i, e) {
                    $(e).prop('disabled', false);
                });
            } else {
                $ele_not_checkbox.each(function (i, e) {
                    $(e).prop('disabled', true);
                });
            }
        },
        handle_category_restrict: function (checkbox) {
            var $category_checkboxes_container = $('#adbutler-category-container');

            if (checkbox.checked) {
                $category_checkboxes_container.addClass('expanded');
                $category_checkboxes_container.removeClass('collapsed');
                $category_checkboxes_container.css('height', $('#adbutler-category-content').outerHeight(true) + 'px');
                $category_checkboxes_container.css('border-width', '1px');
            } else {
                $category_checkboxes_container.addClass('collapsed');
                $category_checkboxes_container.removeClass('expanded');
                $category_checkboxes_container.css('height', 0);
                $category_checkboxes_container.css('border-width', 0);
            }
        }
    };
}(jQuery));
jQuery(document).ready(adbutler.init(adbutlerParams));



