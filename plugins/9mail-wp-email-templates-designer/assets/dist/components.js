jQuery(document).ready(function ($) {
    'use strict';

    let viWecSampleId, viWecSampleStyle = 'basic';

    if (typeof viWecCacheProducts === 'undefined') var viWecCacheProducts = [];

    if (typeof viWecCachePosts === 'undefined') var viWecCachePosts = [];

    const i18n = viWecParams.i18n;

    ViWec.Components.init();

//Functions

    window.viWecFunctions = {
        propertyOnChange: function (element, value) {
            if (value) {
                element.closest('.emtmpl-toggle-display').show();
            } else {
                element.closest('.emtmpl-toggle-display').hide();
            }
        },

        changeSampleTemplate: function () {
            if (!(viWecSampleId && viWecSampleStyle)) return;
            if (!confirm(i18n.change_template_confirm)) return;

            if (!viWecParams.samples || !viWecParams.samples[viWecSampleId] || !viWecParams.samples[viWecSampleId][viWecSampleStyle] || !viWecParams.samples[viWecSampleId][viWecSampleStyle].data) {
                alert('This style is not exist');
                return;
            }
            this.doChangeSampleTemplate(viWecSampleId, viWecSampleStyle)
        },

        doChangeSampleTemplate(id, style) {
            ViWec.viWecDrawTemplate(JSON.parse(viWecParams.samples[id][style].data));

            let subject = viWecParams.subjects[id] || '';
            $('#title').val(subject);
            $('#title-prompt-text').addClass('screen-reader-text');
            $('select[name=emtmpl_settings_type]').val(id).trigger('change');
            viWecChange = true;
        }
    };


    const headerGroup = (key, name, style_section = false) => {
        if (!(key && name)) {
            return {};
        }
        return {
            key: key,
            inputType: SectionInput,
            name: false,
            section: style_section ? styleSection : contentSection,
            data: {header: name},
        };
    };

    const attrsGroupLabel = (label = '', style_section = false) => {
        return {label: label, inputType: 'groupLabel', section: style_section ? styleSection : contentSection};
    };

    const padding = (type, target = '', name = 'Top', col = 4) => {
        return {
            name: name,
            key: `padding-${type}`,
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: NumberInput,
            unit: 'px',
            data: {min: 0, max: 50, step: 1}
        };
    };

    const paddingLeft = (target = '', name = 'Left', col = 4) => {
        return padding('left', target, name, col);
    };
    const paddingTop = (target = '', name = 'Top', col = 4) => {
        return padding('top', target, name, col);
    };
    const paddingRight = (target = '', name = 'Right', col = 4) => {
        return padding('right', target, name, col);
    };

    const paddingBottom = (target = '', name = 'Bottom', col = 4) => {
        return padding('bottom', target, name, col);
    };
    const paddingGroup = (target) => {
        return [
            attrsGroupLabel('Padding (px)', true),
            paddingLeft(target),
            paddingTop(target),
            paddingRight(target),
            paddingBottom(target),
        ];
    };
    const borderColor = (target, name = 'Color', col = 8) => {
        return {
            name: name,
            key: "border-color",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: ColorInput,
        };
    };

    const borderStyle = (target, name = 'Style', col = 8) => {
        return {
            name: name,
            key: "border-style",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: SelectInput,
            data: {
                options: [
                    {id: 'solid', text: 'Solid'},
                    {id: 'dotted', text: 'Dotted'},
                    {id: 'dashed', text: 'Dashed'},
                ]
            }
        };
    };

    const borderWidth = (type, target = '', name = 'Top', col = 4) => {
        return {
            name: name,
            key: `border-${type}-width`,
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: NumberInput,
            unit: 'px',
            data: {min: 0, max: 50, step: 1}
        };
    };

    const borderTop = (target = '', name = 'Top', col = 4) => {
        return borderWidth('top', target, name, col);
    };

    const borderLeft = (target = '', name = 'Left', col = 4) => {
        return borderWidth('left', target, name, col);
    };

    const borderRight = (target = '', name = 'Right', col = 4) => {
        return borderWidth('right', target, name, col);
    };

    const borderBottom = (target = '', name = 'Bottom', col = 4) => {
        return borderWidth('bottom', target, name, col);
    };

    const borderRadius = (type, target = '', name = 'Bottom', col = 8) => {
        return {
            name: name,
            key: `border-${type}-radius`,
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: NumberInput,
            unit: 'px',
            data: {min: 0, max: 50, step: 1}
        };
    };

    const borderTopLeftRadius = (target = '', name = 'Top left', col = 8) => {
        return borderRadius('top-left', target, name, col);
    };

    const borderBottomLeftRadius = (target = '', name = 'Bottom left', col = 8) => {
        return borderRadius('bottom-left', target, name, col);
    };

    const borderTopRightRadius = (target = '', name = 'Top right', col = 8) => {
        return borderRadius('top-right', target, name, col);
    };

    const borderBottomRightRadius = (target = '', name = 'Bottom right', col = 8) => {
        return borderRadius('bottom-right', target, name, col);
    };

    const borderGroup = (target) => {
        return [
            borderColor(target, 'Border color'),
            borderStyle(target, 'Border style'),
            attrsGroupLabel('Border width(px)', true),
            borderLeft(target),
            borderTop(target),
            borderRight(target),
            borderBottom(target),
        ];
    };

    const fontSize = (target, name = i18n['font_size'], col = 8, defaultValue = '') => {
        return {
            name: name,
            key: "font-size",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            unit: 'px',
            inputType: NumberInput,
            default: defaultValue
        };
    };

    const fontWeight = (target, name = i18n['font_weight'], col = 8) => {
        return {
            name: name,
            key: "font-weight",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: SelectInput,
            data: {options: viWecFontWeightOptions}
        };
    };

    const lineHeight = (target, name = 'Line height (px)', col = 8) => {
        return {
            name: name,
            key: "line-height",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            unit: 'px',
            inputType: NumberInput
        };
    };

    const textColor = (target, name = 'Text color', col = 8) => {
        return {
            name: name,
            key: "color",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: ColorInput
        };
    };

    const bgColor = (target, name = 'Background color', col = 8) => {
        return {
            name: name,
            key: "background-color",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: ColorInput
        };
    };

    const fontFamily = (target, name = 'Font family', col = 8) => {
        return {
            name: name,
            key: "font-family",
            target: target,
            htmlAttr: target ? 'childStyle' : 'style',
            section: styleSection,
            col: col,
            inputType: SelectInput,
            data: {options: viWecFontFamilyOptions}
        };
    };
//Sample

    ViWec.Components.add({
        type: "sample_opt_1",
        category: 'sample',
        name: i18n.sample,
        setup: function () {
            let options = {};
            options.placeholder = {id: '', text: i18n.select_email_type};
            options.default = {id: 'default', text: 'Default template'};

            for (let i in viWecParams.emailTypes) {
                options[i] = [];
                for (let j in viWecParams.emailTypes[i]) {
                    if (j !== 'default') {
                        options[i].push({id: j, text: viWecParams.emailTypes[i][j]});
                    }
                }
            }

            let typeSelect = SelectGroupInput.init({key: 'emtmpl_samples', classes: 'emtmpl-samples-type', options: options});

            return $('<div class="emtmpl-sample-group"></div>').append(typeSelect);
        },

        onChange: function (element) {
            element.on('propertyChange', function (event, value, input) {
                if (!value) return;

                if ($(input).hasClass('emtmpl-samples-type')) {
                    viWecSampleId = value;

                    let options = [];

                    if (viWecParams.samples[value] !== undefined && Object.keys(viWecParams.samples[value]).length > 0) {
                        let samples = viWecParams.samples[value];
                        for (let id in samples) {
                            options.push({id: id, text: samples[id].name || ''})
                        }
                    }

                    let newStyleSelect = options.length > 1 ? SelectInput.init({key: 'emtmpl_samples', classes: 'emtmpl-samples-style', options: options}) : '';
                    let target = element.find('.emtmpl-samples-style');
                    if (target.length > 0) {
                        target.parent().replaceWith(newStyleSelect);
                    } else {
                        element.append(newStyleSelect);
                    }

                    if (typeof viWecParams.addNew !== 'undefined') {
                        return;
                    }

                    if (options.length > 0) {
                        viWecSampleStyle = options[0].id;
                        viWecFunctions.changeSampleTemplate();
                    }
                }

                if ($(input).hasClass('emtmpl-samples-style')) {
                    if (typeof viWecParams.addNew !== 'undefined') {
                        return;
                    }
                    viWecSampleStyle = value;
                    viWecFunctions.changeSampleTemplate();
                }

            });
        }
    });


//Layout

    ViWec.Components.add({
        type: "editColumn",
        category: 'hidden',
        inheritProp: ['padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid1cols",
        category: 'layout',
        name: i18n['1_column'],
        icon: '1col',
        cols: 1,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid2cols",
        category: 'layout',
        name: i18n['2_columns'],
        icon: '2cols',
        cols: 2,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid3cols",
        category: 'layout',
        name: i18n['3_columns'],
        icon: '3cols',
        cols: 3,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid4cols",
        category: 'layout',
        name: i18n['4_columns'],
        icon: '4cols',
        cols: 4,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

//Content

    ViWec.Components.add({
        type: "background",
        category: 'hidden',
        icon: '',
        html: ``,
        inheritProp: ['background']
    });

    ViWec.Components.add({
        type: "html/recover_content",
        name: 'Default content',//i18n['spacer'],
        icon: 'transfer',
        // category: 'recover',
        html: viWecTmpl('emtmpl-recover-email-content', {}),

        properties: [
            headerGroup('p', 'Paragraph', true),
            fontFamily('p'),
            fontSize('p'),
            fontWeight('p'),
            lineHeight('p'),
            textColor('p'),
            bgColor('p'),

            headerGroup('a', 'Link', true),
            fontFamily('a'),
            fontSize('a'),
            fontWeight('a'),
            lineHeight('a'),
            textColor('a'),
            bgColor('a'),
        ]
    });

    ViWec.Components.add({
        type: "html/text",
        name: i18n['text'],
        icon: 'text',
        html: `<div class="emtmpl-text-content" contenteditable="true">Text</div>`,
        properties: [
            {
                key: "text_editor_header",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: "Text Editor"},
            },
            {
                key: "text",
                htmlAttr: 'innerHTML',
                target: '.emtmpl-text-content',
                section: contentSection,
                inputType: TextEditor,
                renderShortcode: true
            },
        ],
        inheritProp: ['line_height', 'background', 'padding', 'border']
    });

    ViWec.Components.add({
        type: "html/image",
        name: i18n['image'],
        icon: 'image',
        html: `<img src="${viWecParams.placeholder}" class="emtmpl-image" style="max-width: 100%; ">`,
        properties: [
            {
                key: "image_header",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['image']},
            },
            {
                // name: "Select Image",
                key: "src",
                htmlAttr: "src",
                target: 'img',
                section: contentSection,
                col: 16,
                inputType: ImgInput,
                data: {text: i18n['select'], classes: 'emtmpl-open-bg-img'}
            },
            {
                name: "URL",
                key: "data-href",
                htmlAttr: "data-href",
                section: contentSection,
                col: 32,
                inputType: TextInput,
            },
            {
                name: "Alt",
                key: "data-alt",
                htmlAttr: "data-alt",
                section: contentSection,
                col: 32,
                inputType: TextInput,
            },
            {
                key: "image_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Size"},
            }, {
                name: "Width (px)",
                key: "width",
                htmlAttr: "childStyle",
                target: 'img',
                section: styleSection,
                col: 16,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 600, step: 1}
            }
        ],
        inheritProp: ['alignment', 'padding', 'background']//, 'border']
    });

    ViWec.Components.add({
        type: "html/button",
        name: i18n['button'],
        icon: 'button',
        html: `<a href="#" class="emtmpl-button emtmpl-background emtmpl-padding" 
                style="border-style:solid;display:inline-block;text-decoration: none;text-align: center;max-width: 100%;background-color: #dddddd; color:inherit;">
                    <span class="emtmpl-text-content">Button</span>
                </a>`,

        properties: [{
            key: "text_header",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Text"},
        }, {
            key: "text",
            htmlAttr: 'innerHTML',
            target: '.emtmpl-text-content',
            section: contentSection,
            col: 16,
            inputType: TextInput,
            renderShortcode: true,
            data: {shortcodeTool: true}
        }, {
            key: "link_button",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link"},
        }, {
            key: "href",
            htmlAttr: "href",
            target: 'a',
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true}
        },
            {
                key: "button_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Button"},
            },
            {
                name: "Border width",
                key: "border-width",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 10, step: 1}
            }, {
                name: "Border radius",
                key: "border-radius",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            }, {
                name: "Border color",
                key: "border-color",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Border style",
                key: "border-style",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                data: {
                    options: [
                        {id: 'solid', text: 'Solid'},
                        {id: 'dotted', text: 'Dotted'},
                        {id: 'dashed', text: 'Dashed'},
                    ]
                },
                inputType: SelectInput
            },
            {
                name: "Button color",
                key: "background-color",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Width (px)",
                key: "width",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 600}
            },

        ],
        inheritProp: ['text', 'alignment', 'margin']//, 'background']
    });

    ViWec.Components.add({
        type: "html/contact",
        name: i18n['contact'],
        icon: 'address-book',
        html: `<table class="emtmpl-contact" width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr><td><a href="${viWecParams.homeUrl}" class="emtmpl-home-link emtmpl-toggle-display" ><img class="emtmpl-home-icon" src="${viWecParams.infor_icons.home[0].id}" style='padding-right: 5px;'><span class="emtmpl-home-text">${viWecParams.homeUrl}</span></a></td></tr>
                <tr><td><a href="${viWecParams.adminEmail}" class="emtmpl-email-link emtmpl-toggle-display" ><img class="emtmpl-email-icon" src="${viWecParams.infor_icons.email[0].id}" style='padding-right: 5px;'><span class="emtmpl-email-text">${viWecParams.adminEmail}</span></a></td></tr>
                <tr><td><a href="#" class="emtmpl-phone-link emtmpl-toggle-display" ><img class="emtmpl-phone-icon" src="${viWecParams.infor_icons.phone[0].id}" style='padding-right: 5px;'><span class="emtmpl-phone-text">${viWecParams.adminPhone}</span></a></td></tr>
            </table>`,
        properties: [{
            key: "home",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Home"},
        }, {
            name: "Icon",
            key: "home",
            target: '.emtmpl-home-icon',
            htmlAttr: "src",
            section: contentSection,
            col: 16,
            inputType: SelectInput,
            data: {options: viWecParams.infor_icons.home}
        }, {
            name: "Text",
            key: "home_text",
            target: '.emtmpl-home-text',
            htmlAttr: "innerHTML",
            section: contentSection,
            renderShortcode: true,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, viewValue, input, component, property) {
                element.find('.emtmpl-home-text').html(viewValue);
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "URL",
            key: "home_link",
            target: '.emtmpl-home-link',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
        }, {
            key: "email",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Email"},
        }, {
            name: "Icon",
            key: "email",
            target: '.emtmpl-email-icon',
            htmlAttr: "src",
            section: contentSection,
            col: 16,
            inputType: SelectInput,
            data: {options: viWecParams.infor_icons.email}
        }, {
            name: "Email",
            key: "email_link",
            target: '.emtmpl-email-link',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, viewValue, input, component, property) {
                element.find('.emtmpl-email-text').html(viewValue);
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "phone",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Phone"},
        }, {
            name: "Icon",
            key: "phone",
            target: '.emtmpl-phone-icon',
            htmlAttr: "src",
            section: contentSection,
            col: 16,
            inputType: SelectInput,
            data: {options: viWecParams.infor_icons.phone}
        }, {
            name: "Number",
            key: "phone_text",
            target: '.emtmpl-phone-text',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            onChange: function (element, value, viewValue, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }],

        inheritProp: ['text', 'alignment', 'padding', 'background']//, 'border']
    });

    ViWec.Components.add({
        type: "html/menu",
        name: i18n['menu_bar'],
        icon: 'menu',
        html: `<div class="emtmpl-menu-bar" width="100%"  border="0" cellpadding="0" cellspacing="0" style="display: flex">
                    <div style="flex-grow: 1" class="emtmpl-toggle-display"><a href="#" class="emtmpl-menu-link-1">Item 1</a></div>
                    <div style="flex-grow: 1" class="emtmpl-toggle-display"><a href="#" class="emtmpl-menu-link-2">Item 2</a></div>
                    <div style="flex-grow: 1" class="emtmpl-toggle-display"><a href="#" class="emtmpl-menu-link-3">Item 3</a></div>
                    <div style="flex-grow: 1" class="emtmpl-toggle-display"><a href="#" class="emtmpl-menu-link-4">Item 4</a></div>
            </div>`,
        properties: [{
            key: "menu_bar_1",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 1"},
        }, {
            name: "Text",
            key: "link1",
            target: '.emtmpl-menu-link-1',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link1",
            target: '.emtmpl-menu-link-1',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "menu_bar_2",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 2"},
        }, {
            name: "Text",
            key: "link2",
            target: '.emtmpl-menu-link-2',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            // data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link2",
            target: '.emtmpl-menu-link-2',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "menu_bar_3",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 3"},
        }, {
            name: "Text",
            key: "link3",
            target: '.emtmpl-menu-link-3',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            // data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link3",
            target: '.emtmpl-menu-link-3',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "menu_bar_4",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 4"},
        }, {
            name: "Text",
            key: "link4",
            target: '.emtmpl-menu-link-4',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            // data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link4",
            target: '.emtmpl-menu-link-4',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "Direction",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Direction"},
        }, {
            // name: "Direction",
            key: "direction",
            target: '.emtmpl-menu-bar',
            htmlAttr: "data-direction",
            section: styleSection,
            col: 16,
            inputType: SelectInput,
            data: {options: [{id: 'horizontal', text: 'Horizontal'}, {id: 'vertical', text: 'Vertical'}]},
            onChange(element, value, input, component, property) {
                if (value === 'vertical') element.css('display', 'block');
                if (value === 'horizontal') element.css('display', 'flex');
                return element;
            }
        }],
        inheritProp: ['text', 'alignment', 'padding', 'background']//, 'border']
    });

    let socialProperties = [], socialFirstHtml = '';
    if (viWecParams.social_icons) {
        for (let social in viWecParams.social_icons) {
            let socialName = social[0].toUpperCase() + social.substring(1);

            socialProperties.push({
                    key: social,
                    inputType: SectionInput,
                    section: contentSection,
                    data: {header: socialName},
                },
                {
                    name: "Icon",
                    key: social,
                    target: `.emtmpl-${social}-icon`,
                    htmlAttr: "src",
                    section: contentSection,
                    col: 16,
                    inputType: SelectInput,
                    data: {options: viWecParams.social_icons[social]},
                },
                {
                    name: `${socialName} URL`,
                    key: `${social}_url`,
                    target: `.emtmpl-${social}-link`,
                    htmlAttr: "href",
                    section: contentSection,
                    col: 16,
                    inputType: TextInput,
                    data: {title: `https://your_${social}_url`},
                    onChange: function (element, value, input, component, property) {
                        viWecFunctions.propertyOnChange(element, value);
                        return element;
                    }
                });

            socialFirstHtml += `<span class="emtmpl-social-direction emtmpl-toggle-display"><a href="" class="emtmpl-${social}-link" ><img class="emtmpl-${social}-icon" width="32" src="${viWecParams.social_icons[social][7].id}"></a></span>`;
        }
    }

    socialProperties.push({
            key: "social_image",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Image"},
        },
        {
            name: "Direction",
            key: "direction",
            target: '.emtmpl-social-direction',
            htmlAttr: "data-direction",
            section: styleSection,
            col: 16,
            inputType: SelectInput,
            data: {options: [{id: 'horizontal', text: 'Horizontal'}, {id: 'vertical', text: 'Vertical'}]},
            onChange(element, value, input, component, property) {
                if (value === 'vertical') element.css('display', 'block');
                if (value === 'horizontal') element.css('display', 'inline-block');
                return element;
            }
        },
        {
            name: "Width",
            key: "data-width",
            htmlAttr: "data-width",
            section: styleSection,
            col: 16,
            inputType: NumberInput,
            data: {value: 32, max: 48},
            onChange(element, value, input, component, property) {
                if (value) element.find('img').width(value);

                return element;
            }
        });

    ViWec.Components.add({
        type: "html/social",
        name: i18n['socials'],
        icon: 'social',
        html: `<div class="emtmpl-social" border="0" cellpadding="0" cellspacing="0">${socialFirstHtml}</div>`,
        properties: socialProperties,
        inheritProp: ['alignment', 'padding', 'background']//, 'border'] //'text',
    });

    ViWec.Components.add({
        type: "html/divider",
        name: i18n['divider'],
        icon: 'divider',
        html: `<hr style="border-top: 1px solid; border-bottom:none; margin: 10px 0;">`,
        properties: [{
            key: "text_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Border"},
        }, {
            name: 'Color',
            key: "border-top-color",
            htmlAttr: "childStyle",
            target: 'hr',
            section: styleSection,
            col: 8,
            inputType: ColorInput,
        }, {
            name: 'Width',
            key: "border-top-width",
            htmlAttr: "childStyle",
            target: 'hr',
            section: styleSection,
            col: 8,
            unit: 'px',
            inputType: NumberInput,
            data: {min: 1, step: 1}
        }],
        inheritProp: ['padding', 'background']
    });

    ViWec.Components.add({
        type: "html/spacer",
        name: i18n['spacer'],
        icon: 'spacer',
        html: `<div class="emtmpl-spacer" style="padding-top: 18px" title="Spacer"></div>`,
        properties: [
            {
                key: "spacer",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Height"},
            },
            {
                key: "padding-top",
                htmlAttr: "childStyle",
                target: '.emtmpl-spacer',
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20},
            }
        ],
        // inheritProp: ['background']//
    });

    // ViWec.Components.add({
    //     type: "html/post_lock",
    //     name: 'Post',
    //     icon: 'post',
    //     classes: 'emtmpl-pro-version',
    //     html: '',
    // });
});





