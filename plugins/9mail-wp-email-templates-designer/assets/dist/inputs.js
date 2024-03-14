jQuery(document).ready(function ($) {
    'use strict';
    window.viWecInforIcons = [];
    for (let type in viWecParams.infor_icons) {
        for (let i in viWecParams.infor_icons[type]) {
            viWecInforIcons.push({
                icon: viWecParams.infor_icons[type][i].slug,
                text: viWecParams.infor_icons[type][i].text,
                value: `<img style="vertical-align: sub" src='${viWecParams.infor_icons[type][i].id}'>`
            });
        }
    }

    window.viWecSocialIcons = [];
    for (let type in viWecParams.social_icons) {
        for (let i in viWecParams.social_icons[type]) {
            viWecSocialIcons.push({
                icon: viWecParams.social_icons[type][i].slug,
                text: viWecParams.social_icons[type][i].text,
                value: `<img style="vertical-align: sub" src='${viWecParams.social_icons[type][i].id}'>`
            });
        }
    }

    window.viWecRgb2hex = (rgb) => {
        if (rgb) {
            let match = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);

            if (match && match.length === 4) {
                if (match.input.split(',').length === 3) {
                    let hex = '#';
                    hex += ("0" + parseInt(match[1], 10).toString(16)).slice(-2);
                    hex += ("0" + parseInt(match[2], 10).toString(16)).slice(-2);
                    hex += ("0" + parseInt(match[3], 10).toString(16)).slice(-2);
                    return hex;
                } else {
                    return '';
                }
            } else {
                return rgb;
            }
        }
    };

    window.Input = {
        init(name) {
        },

        onChange(event, node) {
            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [$(this).val(), this]);
            }
        },

        renderTemplate(name, data) {
            return viWecTmpl("emtmpl-input-" + name, data);
        },

        setValue(value) {
            $('input', this.element).val(value);
        },

        render(name, data, callback) {
            this.element = $(this.renderTemplate(name, data));

            //bind events
            if (this.events)
                for (let i in this.events) {
                    let ev = this.events[i][0];
                    let fun = this[this.events[i][1]];
                    let el = this.events[i][2];

                    this.element.on(ev, el, {element: this.element, input: this}, fun);
                }

            if (typeof callback == "function") {
                callback(data, this.element);
            }
            return this.element;
        }
    };

    window.TextInput = $.extend({}, Input, {

        events: [
            ["keyup", "onChange", "input"],
        ],

        init(data) {
            let textField = this.render("textinput", data);
            let cursorPos, ul = $(textField).find('.emtmpl-quick-shortcode-list');

            textField.on('focusout', 'input', function () {
                cursorPos = this.selectionStart;
            });

            textField.one('click', '.emtmpl-quick-shortcode', () => {
                ul.append(viWecShortcodeListValue);
            });

            textField.on('click', '.emtmpl-quick-shortcode', function () {
                $('.emtmpl-quick-shortcode-list').not(ul).hide();
                ul.toggle();
            });

            textField.on('click', 'li', function () {
                let sc = $(this).text();
                let currentText = textField.find('input').val(), newText;

                if (cursorPos !== '') {
                    let before = currentText.substr(0, cursorPos);
                    let after = currentText.substr(cursorPos);
                    newText = before + sc + after;
                } else {
                    newText = currentText + sc;
                }

                // newText = viWecReplaceShortcode(newText);
                textField.find('input').val(newText).keyup();
            });

            return textField;
        },
    });

    window.TextareaInput = $.extend({}, Input, {

        events: [
            ["keyup", "onChange", "textarea"],
        ],

        setValue(value) {
            $('textarea', this.element).val(value);
        },

        init(data) {
            return this.render("textareainput", data);
        },
    });

    window.CheckboxInput = $.extend({}, Input, {

        onChange(event, node) {
            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [this.checked, this]);
            }
        },
        setValue(value) {
            if (value === 'true') {
                $('input', this.element).prop('checked', true);
            }
        },
        events: [
            ["change", "onChange", "input"],
        ],

        init(data) {
            return this.render("checkboxinput", data);
        },
    });

    window.SelectInput = $.extend({}, Input, {
        events: [
            ["change", "onChange", "select"],
        ],

        setValue(value) {
            $('select', this.element).val(value);
        },

        init(data) {
            return this.render("select", data);
        },
    });

    window.SelectGroupInput = $.extend({}, Input, {
        events: [
            ["change", "onChange", "select"],
        ],

        setValue(value) {
            $('select', this.element).val(value);
        },

        init(data) {
            return this.render("select-group", data);
        },
    });

    window.Select2Input = $.extend({}, Input, {
        events: [
            ["change", "onChange", "select"],
        ],

        setValue(value) {
            value = value.split(',');
            $('select', this.element).val(value);
        },

        init(data) {
            return this.render("select2", data);
        },
    });

    window.LinkInput = $.extend({}, TextInput, {
        events: [
            ["change", "onChange", "input"],
        ],

        init(data) {
            return this.render("textinput", data);
        },

    });

    window.RangeInput = $.extend({}, Input, {

        events: [
            ["change", "onChange", "input"],
        ],

        init(data) {
            return this.render("rangeinput", data);
        },
        setValue(value) {
            $('input', this.element).val(parseInt(value));
        }
    });

    window.NumberInput = $.extend({}, Input, {

        events: [
            ["change keyup", "onChange", "input"],
        ],

        init(data) {
            return this.render("numberinput", data);
        },

        setValue(value) {
            $('input', this.element).val(parseInt(value));
        },

        onChange(event) {
            let value = event.target.max && parseInt(this.value) >= parseInt(event.target.max) ? parseInt(event.target.max) : this.value;

            $(event.target).val(value);
            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [value, this]);
            }
        },
    });

    window.DateInput = $.extend({}, Input, {

        events: [
            ["change keyup", "onChange", "input"],
        ],

        init(data) {
            return this.render("dateinput", data);
        },
    });

    window.CssUnitInput = $.extend({}, Input, {

        number: 0,
        unit: "px",

        events: [
            ["change", "onChange", "select"],
            ["change keyup mouseup", "onChange", "input"],
        ],

        onChange(event) {

            if (event.data && event.data.element) {
                let input = event.data.input;
                if (this.value != "") input[this.name] = this.value;// this.name = unit or number
                if (input['unit'] == "") input['unit'] = "px";//if unit is not set use default px

                let value = "";
                if (input.unit == "auto") {
                    $(event.data.element).addClass("auto");
                    value = input.unit;
                } else {
                    $(event.data.element).removeClass("auto");
                    value = input.number + input.unit;
                }

                event.data.element.trigger('propertyChange', [value, this]);
            }
        },

        setValue(value) {
            this.number = parseInt(value);
            this.unit = value.replace(this.number, '');

            if (this.unit == "auto") $(this.element).addClass("auto");

            $('input', this.element).val(this.number);
            $('select', this.element).val(this.unit);
        },

        init(data) {
            return this.render("cssunitinput", data);
        },
    });

    window.ColorInput = $.extend({}, Input, {
        events: [
            ["change", "onChange", "input"],
        ],

        setValue(value) {
            $('input', this.element).val(viWecRgb2hex(value));
            $('.emtmpl-color-picker', this.element).css('background-color', viWecRgb2hex(value));
        },

        init(data) {
            let node = this.render("colorinput", data, function (data, element) {
                $('.emtmpl-clear', element).on('click', function () {
                    $('.emtmpl-color-picker', element).css('background-color', 'transparent').val('').change();
                });
            });

            $('.emtmpl-color-picker', node).iris({
                change(ev, ui) {
                    $(this).val(ui.color.toString()).trigger('change');
                    $(this).css('background-color', ui.color.toString());
                }
            }).on('click', function (e) {
                let panel = $('#emtmpl-control-panel');
                let right = node.offset().left - panel.offset().left > 100 ? 0 : '';
                let bottom = (node.offset().top - panel.offset().top) / window.innerHeight > 0.6 ? '32px' : '';
                let css = {right: right, bottom: bottom};
                $('.iris-picker').hide();
                node.find('.iris-picker').css(css).show();
            });

            $('body').on('click', function (e) {
                if (!$(e.target).is('.emtmpl-color-picker, .iris-picker, .iris-picker-inner, .iris-square')) {
                    $('.iris-picker').hide();
                }
            });

            return node;
        },

        onChange(event, node) {
            if (event.data && event.data.element) {
                let thisValue = this.value ? this.value : 'transparent';
                event.data.element.trigger('propertyChange', [thisValue, this]);
            }
        },
    });

    window.TextEditor = $.extend({}, Input, {
        events: [
            ["change", "onChange", "textarea"],
        ],

        setValue(value) {
            $('textarea', this.element).val(value);
        },

        onChange(event, node) {
            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [$(this).val(), this]);
            }
        },
        init(data) {
            $(tinyMCE.editors).each(function () {
                tinyMCE.remove(this);
            });
            return this.render("texteditorinput", data);
        },
        subInit(element) {
            wp.editor.initialize('emtmpl-text-editor', {
                tinymce: {
                    mode: 'exact',
                    selector: '#emtmpl-text-editor',
                    content_style: 'body {background:#eeeeee; font-family: helvetica, arial, sans-serif;} #tinymce a{text-decoration: none;}',
                    theme: 'modern',
                    height: 'auto',
                    menubar: false,
                    statusbar: false,
                    relative_urls: false,
                    remove_script_host: false,
                    convert_urls: false,
                    plugins: ["link textcolor colorpicker image"],
                    toolbar:
                        [
                            'bold italic underline | alignleft aligncenter alignright alignjustify| link image',
                            'fontsizeselect forecolor backcolor',
                            'viWecInforIcons viWecSocialIcons shortcode fontselect'
                        ], //fontselect 'lineheightselect',

                    fontsize_formats: '10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 22px 24px 26px 28px 30px 35px 40px 50px 60px',
                    // font_formats: " Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Ubuntu=Ubuntu,sans-serif; Verdana=verdana,geneva; Oswald=oswald,arial,helvetica,sans-serif;",

                    setup: function (editor) {

                        editor.addButton('shortcode', {
                            type: 'listbox',
                            text: 'Shortcode',
                            icon: false,
                            onselect: function (e) {
                                editor.insertContent(this.value())
                            },
                            values: viWecShortcodeList,
                        });

                        editor.addButton('viWecInforIcons', {
                            type: 'listbox',
                            text: 'Information icons',
                            icon: false,
                            onselect: function (e) {
                                editor.insertContent(this.value())
                            },
                            values: viWecInforIcons,
                        });

                        editor.addButton('viWecSocialIcons', {
                            type: 'listbox',
                            text: 'Social icons',
                            icon: false,
                            onselect: function (e) {
                                editor.insertContent(this.value())
                            },
                            values: viWecSocialIcons,
                        });

                        editor.on('keyup mouseup change', function (e) {
                            $('#emtmpl-text-editor').val(editor.getContent()).change();
                        });
                    }
                },
                quicktags: true
            });

            let textEl = element;
            textEl.on('keyup', function () {
                $('iframe').contents().find('#tinymce[data-id="emtmpl-text-editor"]').html($(this).html());
            });
            // textEl.attr('contenteditable', true).focus();
        }
    });

    window.BgImgInput = $.extend({}, Input, {
        events: [
            ["click", "onChange", "button"],
            ["click", "clear", ".emtmpl-clear"]
        ],

        init(data) {
            return this.render("bgimginput", data);
        },

        onChange(event, node) {
            if (event.data && event.data.element) {
                let images = wp.media({
                    title: 'Select Image',
                    multiple: false
                }).open().on('select', function (e) {
                    let uploadedImages = images.state().get('selection').first();
                    let selectedImages = uploadedImages.toJSON();
                    event.data.element.trigger('propertyChange', [`url(${selectedImages.url})`, this]);
                })
            }
        },

        clear(event, node) {
            event.data.element.trigger('propertyChange', ['url()', this]);
        }
    });

    window.ImgInput = $.extend({}, Input, {
        events: [
            ["click", "onChange", "button"],
            ["click", "clear", ".emtmpl-clear"]
        ],
        init(data) {
            return this.render("bgimginput", data);
        },
        onChange(event, node) {
            if (event.data && event.data.element) {
                let images = wp.media({
                    title: 'Select Image',
                    multiple: false
                }).open().on('select', function (e) {
                    let uploadedImages = images.state().get('selection').first();
                    let selectedImages = uploadedImages.toJSON();
                    event.data.element.trigger('propertyChange', [selectedImages.url, this]);
                });
            }
        },
        clear(event, node) {
            event.data.element.trigger('propertyChange', ['', this]);
        }
    });

    window.FileUploadInput = $.extend({}, TextInput, {

        events: [
            ["blur", "onChange", "input"],
        ],

        init(data) {
            return this.render("textinput", data);
        },
    });

    window.RadioInput = $.extend({}, Input, {

        onChange(event, node) {
            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [this.value, this]);
            }
        },

        events: [
            ["change", "onChange", "input"],
        ],

        setValue(value) {
            $('input', this.element).removeAttr('checked');
            if (value)
                $("input[value=" + value + "]", this.element).attr("checked", "true").prop('checked', true);
        },

        init(data) {
            return this.render("radioinput", data);
        },
    });

    window.RadioButtonInput = $.extend({}, RadioInput, {
        events: [
            ["change", "onChange", "input"],
        ],

        init(data) {
            return this.render("radiobuttoninput", data);
        },
    });

    window.ToggleInput = $.extend({}, TextInput, {

        onChange(event, node) {
            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [this.checked ? this.getAttribute("data-value-on") : this.getAttribute("data-value-off"), this]);
            }
        },

        events: [
            ["change", "onChange", "input"],
        ],

        init(data) {
            return this.render("toggle", data);
        },
    });

    window.ValueTextInput = $.extend({}, TextInput, {

        events: [
            ["blur", "onChange", "input"],
        ],

        init(data) {
            return this.render("textinput", data);
        },
    });

    window.GridLayoutInput = $.extend({}, TextInput, {

        events: [
            ["blur", "onChange", "input"],
        ],

        init(data) {
            return this.render("textinput", data);
        },
    });

    window.ProductsInput = $.extend({}, TextInput, {

        events: [
            ["blur", "onChange", "input"],
        ],

        init(data) {
            return this.render("textinput", data);
        },
    });

    window.GridInput = $.extend({}, Input, {


        events: [
            ["change", "onChange", "select" /*'select'*/],
            ["click", "onChange", "button" /*'select'*/],
        ],


        setValue(value) {
            $('select', this.element).val(value);
        },

        init(data) {
            return this.render("grid", data);
        },

    });

    window.TextValueInput = $.extend({}, Input, {
        events: [
            ["blur", "onChange", "input"],
            ["click", "onChange", "button" /*'select'*/],
        ],

        init(data) {
            return this.render("textvalue", data);
        },
    });

    window.ButtonInput = $.extend({}, Input, {

        events: [
            ["click", "onChange", "button"],
        ],

        setValue(value) {
            $('button', this.element).val(value);
        },

        init(data) {
            return this.render("button", data);
        },

    });

    window.SectionInput = $.extend({}, Input, {
        events: [
            ["click", "onChange", "button" /*'select'*/],
        ],

        setValue(value) {
            return false;
        },

        init(data) {
            return this.render("sectioninput", data);
        },
    });

    window.ListInput = $.extend({}, Input, {
        events: [
            ["change", "onChange", "select"],
        ],

        setValue(value) {
            $('select', this.element).val(value);
        },

        init(data) {
            return this.render("listinput", data);
        },
    });

    window.AutocompleteInput = $.extend({}, Input, {

        events: [
            ["autocomplete.change", "onAutocompleteChange", "input"],
        ],

        onAutocompleteChange(event, value, text) {

            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [value, this]);
            }
        },

        init(data) {

            this.element = this.render("textinput", data);

            $('input', this.element).autocomplete(data.url);//using default parameters

            return this.element;
        }
    });

    window.AutocompleteList = $.extend({}, Input, {

        events: [
            ["autocompletelist.change", "onAutocompleteChange", "input"],
        ],

        onAutocompleteChange(event, value, text) {

            if (event.data && event.data.element) {
                event.data.element.trigger('propertyChange', [value, this]);
            }
        },

        setValue(value) {
            $('input', this.element).data("autocompleteList").setValue(value);
        },

        init(data) {

            this.element = this.render("textinput", data);

            $('input', this.element).autocompleteList(data);//using default parameters

            return this.element;
        }
    });
});