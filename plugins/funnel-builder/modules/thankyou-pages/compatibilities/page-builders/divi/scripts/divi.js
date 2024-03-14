(function ($) {
    window.wfty_prepare_divi_css = function (data, utils, props) {
        var main_output = [];
        for (let m in data.margin_padding) {
            (function (key, selector) {
                var spacing = props[key];
                if (spacing != null && spacing !== '' && spacing.split("|")) {
                    var element_here = key.indexOf("_padding");
                    var ele = "padding";
                    if (element_here === -1) {
                        ele = "margin";
                    }
                    spacing = props[key].split("|");
                    var enable_edited = props[key + "_last_edited"];
                    var key_tablet = props[key + "_tablet"];
                    var key_phone = props[key + "_phone"];
                    var enable_responsive_active = enable_edited && enable_edited.startsWith("on");
                    main_output.push({
                        'selector': selector,
                        'declaration': ele + `-top: ${spacing[0]}  !important; ` + ele + `-right: ${spacing[1]} !important; ` + ele + `-bottom: ${spacing[2]}  !important; ` + ele + `-left: ${spacing[3]}  !important;`
                    });

                    if (key_tablet && enable_responsive_active && key_tablet && '' !== key_tablet) {
                        var spacing_tablet = key_tablet.split("|");
                        main_output.push({
                            'selector': selector,
                            'declaration': ele + `-top: ${spacing_tablet[0]} !important;` + ele + `-right: ${spacing_tablet[1]} !important; ` + ele + `-bottom: ${spacing_tablet[2]}  !important; ` + ele + `-left: ${spacing_tablet[3]}  !important;`,
                            'device': 'tablet',
                        });
                    }

                    if (key_phone && enable_responsive_active && key_phone && '' !== key_phone) {
                        var spacing_phone = key_phone.split("|");
                        main_output.push({
                            'selector': selector,
                            'declaration': ele + `-top: ${spacing_phone[0]} !important; ` + ele + `-right: ${spacing_phone[1]} !important; ` + ele + `-bottom: ${spacing_phone[2]} !important; ` + ele + `-left: ${spacing_phone[3]} !important;`,
                            'device': 'phone',
                        });
                    }
                }
            })(m, data.margin_padding[m]);
        }
        for (let n in data.normal_data) {
            (function (key, selector, css_prop) {
                main_output.push({
                    'selector': selector,
                    'declaration': `${css_prop}:${props[key]}` + '!important'
                });
                var device_enable = props[key + "_last_edited"] && props[key + "_last_edited"].startsWith('on');
                if (device_enable === true) {
                    main_output.push({
                        'selector': selector,
                        'declaration': `${css_prop}:${props[key + "_tablet"]}` + '!important',
                        'device': 'tablet',
                    });
                    main_output.push({
                        'selector': selector,
                        'declaration': `${css_prop}:${props[key + "_phone"]}` + '!important',
                        'device': 'phone',
                    });

                }
            })(n, data.normal_data[n]['selector'], data.normal_data[n]['property']);
        }
        for (let t in data.typography_data) {
            (function (key, selector) {
                var property = data.typography[key];
                main_output.push({'selector': selector, 'declaration': utils.setElementFont(props[property])});
            })(t, data.typography_data[t]);
        }
        for (let border_key in data.border_data) {
            let selector = data.border_data[border_key];
            (function (border_key, selector) {
                var border_type = props[border_key + '_border_type'];
                var width_top = props[border_key + '_border_width_top'];
                var width_bottom = props[border_key + '_border_width_bottom'];
                var width_left = props[border_key + '_border_width_left'];
                var width_right = props[border_key + '_border_width_right'];
                var border_color = props[border_key + '_border_color'];
                var radius_top_left = props[border_key + '_border_radius_top'];
                var radius_top_right = props[border_key + '_border_radius_bottom'];
                var radius_bottom_right = props[border_key + '_border_radius_left'];
                var radius_bottom_left = props[border_key + '_border_radius_right'];
                if ('none' === border_type) {
                    main_output.push({'selector': selector, 'declaration': 'border-style:none !important;'});
                    main_output.push({'selector': selector, 'declaration': 'border-radius:none !important;'});
                } else {
                    main_output.push({'selector': selector, 'declaration': `border-color:${border_color} !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-style:${border_type} !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-top-width:${width_top}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-bottom-width:${width_bottom}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-left-width:${width_left}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-right-width:${width_right}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-top-left-radius:${radius_top_left}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-top-right-radius:${radius_top_right}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-bottom-right-radius:${radius_bottom_right}px !important;`});
                    main_output.push({'selector': selector, 'declaration': `border-bottom-left-radius:${radius_bottom_left}px !important;`});
                }
            })(border_key, selector);
        }
        for (let shadow_key in data.box_shadow) {
            let selector = data.box_shadow[shadow_key];
            (function (border_key, selector) {
                var enabled = props[border_key + '_shadow_enable'];
                var type = props[border_key + '_shadow_type'];
                var horizontal = props[border_key + '_shadow_horizontal'];
                var vertical = props[border_key + '_shadow_vertical'];
                var blur = props[border_key + '_shadow_blur'];
                var spread = props[border_key + '_shadow_spread'];
                var color = props[border_key + '_shadow_color'];
                if ('on' == enabled) {
                    main_output.push({'selector': selector, 'declaration': `box-shadow:${horizontal}px ${vertical}px ${blur}px ${spread}px ${color} ${type} !important;`});
                } else {
                    main_output.push({'selector': selector, 'declaration': 'box-shadow:none !important;'});
                }
            })(shadow_key, selector);
        }
        return main_output;
    }

    $(document.body).on('keypress', '.wfty_divi_border textarea', function (e) {        // IE
        var keynum
        if (window.event) {
            keynum = e.keyCode;
        } else if (e.which) {
            keynum = e.which;
        }
        if (keynum === 13) {
            return false;
        }
    });
    $(document.body).on('click', '.et-fb-form__toggle', function () {
        let el = $(this);
        setTimeout((el) => {
            let siblings = el.children('.et-fb-form__group');
            console.log('Hello Toggle run', siblings.length);
            if (siblings.length === 0) {
                return;
            }
            siblings.each(function () {
                let wfty_border_width_top = $(this).find('.wfty_border_width_top');
                if (wfty_border_width_top.length > 0) {
                    $(this).addClass('wfty_divi_border wfty_divi_border_width_start wfty_border_width_top');
                }
                let wfty_border_width_bottom = $(this).find('.wfty_border_width_bottom');
                if (wfty_border_width_bottom.length > 0) {
                    $(this).addClass('wfty_divi_border wfty_border_width_bottom');
                }
                let wfty_border_width_left = $(this).find('.wfty_border_width_left');
                if (wfty_border_width_left.length > 0) {
                    $(this).addClass('wfty_divi_border wfty_border_width_left');
                }
                let wfty_border_width_right = $(this).find('.wfty_border_width_right');
                if (wfty_border_width_right.length > 0) {
                    $(this).addClass('wfty_divi_border wfty_divi_border_width_end wfty_border_width_right');
                }

                let heading = $(this).find('.wfty_heading_divi_builder');
                if (heading.length > 0) {
                    heading.remove();
                    let text = $(this).find('.et-fb-form__label-text');
                    if (text.length > 0) {
                        $(this).find('.et-fb-form__label').replaceWith("<h3 class='wfty_c_heading'>" + text.text() + "</h3>");
                    }
                }

                let subheading = $(this).find('.wfty_subheading_divi_builder');
                if (subheading.length > 0) {
                    subheading.remove();
                }
            })

        }, 50, el);

    });
})(jQuery);