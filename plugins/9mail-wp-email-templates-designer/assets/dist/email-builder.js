'use strict';

let styleSection = 'style',
    contentSection = 'content',
    viWecEditorArea = '#emtmpl-email-editor-content',
    viWecChange = true,

    viWecFontWeightOptions = [
        {id: 300, text: 300},
        {id: 400, text: 400},
        {id: 500, text: 500},
        {id: 600, text: 600},
        {id: 700, text: 700},
        {id: 800, text: 800},
        {id: 900, text: 900}
    ],

    viWecAlignmentOptions = [
        {value: "left", title: "Left", icon: "dashicons dashicons-editor-alignleft", checked: true,},
        {value: "center", title: "Center", icon: "dashicons dashicons-editor-aligncenter", checked: false,},
        {value: "right", title: "Right", icon: "dashicons dashicons-editor-alignright", checked: false,}
    ],

    viWecFontFamilyOptions = [
        {id: "'andale mono', monospace", text: 'Andale Mono'},
        {id: 'arial, helvetica, sans-serif', text: 'Arial'},
        {id: "'arial black', sans-serif", text: 'Arial Black'},
        {id: "'book antiqua', palatino, serif", text: 'Book Antiqua'},
        {id: "'comic sans ms', sans-serif", text: 'Comic Sans MS'},
        {id: "'courier new', courier, monospace", text: 'Courier New'},
        {id: 'georgia, palatino, serif', text: 'Georgia'},
        {id: 'helvetica, arial, sans-serif', text: 'Helvetica'},
        {id: 'impact, sans-serif', text: 'Impact'},
        {id: 'symbol', text: 'Symbol'},
        {id: 'tahoma, arial, helvetica, sans-serif', text: 'Tahoma'},
        {id: 'terminal, monaco, monospace', text: 'Terminal'},
        {id: "'times new roman', times, serif", text: 'Times New Roman'},
        {id: "'trebuchet ms', geneva, sans-serif", text: 'Trebuchet MS'},
        {id: 'verdana, geneva, sans-serif', text: 'Verdana'},
        {id: 'webdings', text: 'Webdings'},
        {id: "wingdings, 'zapf dingbats'", text: 'Wingdings'},
    ],

    viWecMapObj = viWecParams.shortcode_for_replace;


let viWecShortcodeList = viWecParams.shortcode.map((item) => {
    return {text: item, value: item};
});

if (viWecParams.sc_3rd_party_for_text_editor) {
    for (let sc in viWecParams.sc_3rd_party_for_text_editor) {
        viWecShortcodeList.push(viWecParams.sc_3rd_party_for_text_editor[sc]);
    }
}

let viWecShortcodeListValue = '';
if (viWecParams.shortcode) {
    for (let sc of viWecParams.shortcode) {
        viWecShortcodeListValue += '<li>' + sc + '</li>'
    }
}

if (viWecParams.sc_3rd_party) {
    for (let sc of viWecParams.sc_3rd_party) {
        viWecShortcodeListValue += '<li>' + sc + '</li>'
    }
}

if (ViWec === undefined) {
    var ViWec = {};
}

(function () {
    var cache = {};
    window.viWecTmpl = function viWecTmpl(str, data) {
        var fn = /^[-a-zA-Z0-9]+$/.test(str) ? cache[str] = cache[str] || viWecTmpl(document.getElementById(str).innerHTML) :
            new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};" + "with(obj){p.push('" +
                str.replace(/[\r\t\n]/g, " ")
                    .split("{%").join("\t")
                    .replace(/((^|%})[^\t]*)'/g, "$1\r")
                    .replace(/\t=(.*?)%}/g, "',$1,'")
                    .split("\t").join("');")
                    .split("%}").join("p.push('")
                    .split("\r").join("\\'")
                + "');}return p.join('');");
        // Provide some basic currying to the user
        return data ? fn(data) : fn;
    };
})();

jQuery(document).ready(function ($) {
    window.viWecNoticeBox = (text, color, time = 3000) => {
        color = color || 'white';
        let box = $('#emtmpl-notice-box');
        box.text(text).css({'color': color, 'bottom': 0});
        setTimeout(function () {
            box.css({'bottom': '-50px'});
        }, time);
    };

    $.fn.handleRow = function () {
        if (this.find('.emtmpl-layout-handle-outer').length === 0) {
            this.append(viWecTmpl('emtmpl-input-handle-outer', {}));
        }

        this.on('click', '.emtmpl-delete-row-btn', () => {
            this.remove();
            ViWec.Builder.clearTab();
            ViWec.Builder.activeTab('components');
        });

        this.on('click', '.emtmpl-duplicate-row-btn', () => {
            let clone = this.clone();
            clone.find('.emtmpl-column-sortable').columnSortAble();
            clone.handleRow().handleColumn();
            this.after(clone);
        });

        this.on('click', '.emtmpl-edit-outer-row-btn', () => {
            ViWec.Builder.removeFocus();
            this.addClass('emtmpl-block-focus');
            ViWec.Builder.selectedEl = this.find('.emtmpl-layout-row');
            ViWec.Builder.loadLayoutControl();
        });

        this.on('click', '.emtmpl-copy-row-btn', function () {
            let row = $(this).closest('.emtmpl-block');
            row = row.prop('outerHTML');
            localStorage.setItem('emtmplCopyRow', row);
            viWecNoticeBox('Copied');
        });

        this.on('click', '.emtmpl-paste-row-btn', function () {
            let row = localStorage.getItem('emtmplCopyRow');
            if (row) {
                row = $(row);
                row.find('.emtmpl-column-sortable').columnSortAble();
                row.handleRow().handleColumn();
                $(this).closest('.emtmpl-block').after(row);
            }
        });

        return this;
    };

    $.fn.handleElement = function () {
        this.append(`<div class="emtmpl-element-handle">
                <span class="dashicons dashicons-welcome-add-page emtmpl-copy-element-btn" title="Copy"></span>
                <span class="dashicons dashicons-admin-page emtmpl-duplicate-element-btn" title="Duplicate"></span>
                <span class="dashicons dashicons-no-alt emtmpl-delete-element-btn" title="Delete"></span></div>`);
    };

    $.fn.columnSortAble = function () {
        $(this).sortable({
            cursor: 'move',
            cursorAt: {left: 40, top: 18},
            placeholder: 'emtmpl-placeholder',
            connectWith: ".emtmpl-column-sortable",
            thisColumn: '',
            accept: '.emtmpl-content-draggable',
            start: function (ev, ui) {
                // ui.placeholder.height(30);
                ui.helper.addClass('emtmpl-is-dragging');
                this.thisColumn = ui.helper.closest('.emtmpl-column');
            },
            stop: function (ev, ui) {
                let style = ui.item.get(0).style;
                style.position = style.top = style.left = style.right = style.bottom = style.height = style.width = '';
                ui.item.removeClass('emtmpl-is-dragging');
                if (ui.item.offsetParent().find('.emtmpl-element').length) {
                    ui.item.offsetParent().removeClass('emtmpl-column-placeholder');
                }
                if (!(this.thisColumn.find('.emtmpl-element').length)) {
                    this.thisColumn.addClass('emtmpl-column-placeholder');
                }
                ui.item.click();
                viWecChange = true;
            }
        });
    };


    $.fn.handleColumn = function () {
        this.on('click', (e) => {
            if (this.hasClass('emtmpl-column-placeholder') || this.find('.emtmpl-column-placeholder').length) {
                ViWec.Builder.removeFocus();
                ViWec.Builder.selectedEl = this.find('.emtmpl-column-sortable').addClass('emtmpl-block-focus');
                ViWec.Builder.loadLayoutControl('editColumn');
            }
        });

        this.on('click', '.emtmpl-column-edit', () => {
            ViWec.Builder.removeFocus();
            ViWec.Builder.selectedEl = this.find('.emtmpl-column-sortable').addClass('emtmpl-block-focus');
            ViWec.Builder.loadLayoutControl('editColumn');
        });

        this.on('click', '.emtmpl-column-paste', function () {
            let item = localStorage.getItem('emtmplCopy');
            if (item) {
                item = $(item);
                $(this).closest('.emtmpl-column').find('.emtmpl-column-sortable').append(item);
            }
        });

        return this;
    };

    ViWec.viWecReplaceShortcode = (text) => {
        if (!text || typeof text !== 'string') return text;

        var re = new RegExp(Object.keys(viWecMapObj).join("|"), "gm");
        text = text.replace(re, function (matched) {
            return viWecMapObj[matched];
        });

        return text;
    };

    ViWec.Components = {
        _categories: {},
        _components: {
            baseProp: {}
        },

        init() {
            // this.registerCategory('sample', 'Sample');
            this.registerCategory('layout', 'Layout');
            this.registerCategory('content', 'Basic content');
        },

        registerCategory(id, name) {
            if (!this._categories[id]) this._categories[id] = {name: name, elements: []};
        },

        get: function (type) {
            return this._components[type];
        },

        add(data) {
            let categoryType = data.category || 'content';
            if (this._categories[categoryType]) this._categories[categoryType].elements.push(data.type);

            if (data.inheritProp) {
                let inheritProperties = [];
                for (let property of data.inheritProp) {
                    if (this._components.baseProp[property]) {
                        inheritProperties = [...inheritProperties, ...this._components.baseProp[property].properties];
                    }
                }

                if (!data.properties) data.properties = [];
                data.properties = [...data.properties, ...inheritProperties];
            }

            this._components[data.type] = data;
        },

        addBaseProp(data) {
            this._components.baseProp[data.type] = data;
        },

        render: function (type) {
            let component = this._components[type], section, attributesArea = $('#emtmpl-attributes-list');

            if (!component) return;

            //set to viewer
            var bindOnChangeToViewer = function (component, property, element) {

                return property.input.on('propertyChange', function (event, value, input) {
                    viWecChange = true;

                    let viewValue = ViWec.viWecReplaceShortcode(value);

                    if (property.outputValue) value = property.outputValue;

                    if (property.htmlAttr) {
                        if (["style", 'childStyle'].indexOf(property.htmlAttr) > -1) {
                            let unit = property.unit ? property.unit : '';
                            element = ViWec.StyleManager.setStyle(element, property.key, value, unit);
                        } else if (property.htmlAttr === "innerHTML") {
                            if (property.renderShortcode) {
                                let clone = element.clone();
                                element = element.html(value).hide();

                                let virElement = element.parent().find('.emtmpl-text-view');
                                if (virElement.length === 0) {
                                    clone = clone.removeClass().html(viewValue).addClass('emtmpl-text-view');
                                    element.after(clone);
                                } else {
                                    virElement.html(viewValue);
                                }
                            } else {
                                element.html(value);
                            }
                        } else {
                            element = element.attr(property.htmlAttr, value);
                        }
                    }

                    if (typeof component.onChange === 'function') {
                        element = component.onChange(element, property, value, input);
                    }
                    if (typeof property.onChange === 'function') {
                        element = property.onChange(element, value, viewValue, input, component, property);
                    }

                    return element;
                });
            };

            let currentKey = '';

            //render control
            if (component.name) attributesArea.append(`<div id="emtmpl-component-name">Component: ${component.name}</div>`);

            for (let i in component.properties) {
                var property = component.properties[i];
                var element = ViWec.Builder.selectedEl;

                if (property.visible === false || property.target && !element.find(property.target).length) continue;
                if (property.target && element.find(property.target).length) element = element.find(property.target);

                if (property.data) {
                    property.data["key"] = property.key;
                    if (property.name) property.data["header"] = property.name;
                    // if (property.defaultValue) property.data["value"] = property.defaultValue;
                } else {
                    property.data = {"key": property.key};
                    if (property.name) property.data["header"] = property.name;
                    // if (property.defaultValue) property.data["value"] = property.defaultValue;
                }

                if (!property.inputType) continue;

                if (property.inputType.hasOwnProperty('init')) {
                    property.input = property.inputType.init(property.data);
                }

                if (property.init) {
                    property.inputType.setValue(property.init(element.get(0)));
                } else if (property.htmlAttr) {
                    let value;
                    if (property.htmlAttr === "style") {
                        value = ViWec.StyleManager.getStyle(element, property);
                    } else if (property.htmlAttr === "childStyle") {
                        value = ViWec.StyleManager.getStyle(element, property);
                    } else if (property.htmlAttr === "innerHTML") {
                        value = element.html();
                    } else {
                        value = element.attr(property.htmlAttr);
                    }

                    if (!value && property.default) {
                        value = property.default;
                    }

                    if (value) {
                        property.inputType.setValue(value); //set to control
                    }
                }

                if (property.input) {
                    bindOnChangeToViewer(component, property, element);
                }

                section = property.section ? property.section : '';
                if (section) {

                    if (attributesArea.find(`.emtmpl-${section}`).length === 0) {
                        attributesArea.append(`<div class="emtmpl-${section} vi-ui accordion styled fluid">
                                                <div class="title active">
                                                    <i class="dropdown icon"></i>
                                                    ${section.replace(/^./, section[0].toUpperCase())}
                                                </div>
                                                <div class="content active ${section}-properties">
                                            </div></div>`);
                    }

                    if (property.inputType === SectionInput) {
                        attributesArea.find(`.emtmpl-${section} .${section}-properties`).append(viWecTmpl("emtmpl-input-sectioninput", property.data));
                        currentKey = property.key ? property.key : currentKey;
                    } else if (property.label) {
                        attributesArea.find(`.emtmpl-${section} .${currentKey}`).append(`<label class="emtmpl-group-name" for="input-model">${property.label}</label>`);
                    } else {
                        if (!property.hidden) {
                            let row = $(viWecTmpl('emtmpl-property', property));
                            row.find('.input').append(property.input);
                            if (typeof property.setup === 'function') row = property.setup(row); //Add custom events

                            attributesArea.find(`.emtmpl-${section} .${currentKey}`).append(row);
                            if (typeof property.inputType.subInit === 'function') {
                                property.inputType.subInit(element);
                            }
                        }
                    }

                    if (property.inputType.afterInit) {
                        property.inputType.afterInit(property.input);
                    }
                }
            }

            $('.vi-ui.accordion').accordion();

            if (component.init) component.init(ViWec.Builder.selectedEl.get(0));
        }
    };


    ViWec.Blocks = {
        _blocks: {},

        get: function (type) {
            return this._blocks[type];
        },

        add: function (type, data) {
            data.type = type;
            this._blocks[type] = data;
        },
    };


    ViWec.Builder = {
        component: {},
        dragMoveMutation: false,
        isPreview: false,
        designerMode: false,
        copyStorage: '',

        init: function (callback) {
            var self = this;

            self.selectedEl = null;
            self.initCallback = callback;
            self.dragElement = null;

            self.loadControlGroups();
            self.initDragDrop();
            self.initHandleBox();
            self.loadContentControl();
            self.loadBackgroundControl();
            self.initQuickAddLayout();
            self.globalEvent();
        },

        /* controls */
        loadControlGroups: function () {
            let componentsList = $("#emtmpl-components-list"), item = {}, component = {};
            componentsList.empty();

            for (let group in ViWec.Components._categories) {
                componentsList.append(`<div class="vi-ui accordion styled fluid">
                                <div class="title active">
                                    <i class="dropdown icon"></i>
                                   ${ViWec.Components._categories[group].name}
                                </div>
                                <div class="content active" data-section="${group}">
                                    <ul></ul>
                                </div>
                            </div>`);

                let componentsSubList = componentsList.find('div[data-section="' + group + '"] ul');
                let components = ViWec.Components._categories[group].elements;
                group = group === 'layout' ? 'layout' : 'content';

                for (let i in components) {
                    let componentType = components[i], controlBtn;
                    component = ViWec.Components.get(componentType);

                    if (component) {
                        if (typeof component.setup === 'function') {
                            item = component.setup();
                            if (typeof component.onChange === 'function') component.onChange(item);
                        } else {
                            let classes = component.classes || '',
                                unLock = classes.includes('emtmpl-pro-version'),
                                dragAble = unLock ? '' : `emtmpl-${group}-draggable`,
                                unlockNotice = unLock ? "<div class='emtmpl-unlock-notice'><a target='_blank' href='https://1.envato.market/BZZv1'>Unlock this feature</a></div>" : '',
                                lockIcon = unLock ? "<div class='dashicons dashicons-lock'></div>" : '',
                                info = component.info || '';

                            controlBtn = `<div class="emtmpl-control-btn ${dragAble} ${classes}" data-type="${componentType}" data-drag-type="component">
                                            ${lockIcon} ${unlockNotice} ${info}
                                            <div class="emtmpl-control-icon">
                                                <i class="emtmpl-ctrl-icon-${component.icon}"></i>
                                            </div>
                                            <div class="emtmpl-ctrl-title">${component.name}</div>`;

                            item = $(`<li  data-section="${group}">
                                    ${controlBtn}
                                    </div></li>`);
                        }

                        componentsSubList.append(item);
                        if (group === 'layout') {
                            $('#emtmpl-quick-add-layout .emtmpl-layout-list').append(controlBtn);
                        }
                    }
                }
            }

            $('.vi-ui.accordion').accordion();
        },

        activeTab: (tab) => {
            $('#emtmpl-control-panel .item, #emtmpl-control-panel .tab').removeClass('active');
            $(`#emtmpl-control-panel [data-tab=${tab}]`).addClass('active');
        },

        clearTab: () => {
            $('#emtmpl-control-panel #emtmpl-attributes-list').empty();
        },

        loadLayoutControl: function (dataType) {
            this.clearTab();
            this.activeTab('editor');
            let type = dataType || this.selectedEl.data('type');
            ViWec.Components.render(type);
        },

        loadContentControl: function () {
            let self = this, body = $('#emtmpl-email-editor-wrapper');
            body.on('click', '.emtmpl-element', function (e) {
                self.removeFocus();
                $(this).addClass('emtmpl-element-focus');
                self.clearTab();
                self.activeTab('editor');
                let type = $(this).data('type');
                self.selectedEl = $(this);
                ViWec.Components.render(type);
            });
        },

        loadBackgroundControl: function () {
            let self = this;
            $('.emtmpl-edit-bgcolor-btn span').on('click', function (e) {
                self.clearTab();
                self.activeTab('editor');
                self.selectedEl = $('#emtmpl-email-editor-wrapper');
                ViWec.Components.render('background');
            });
        },

        initHandleBox: function () {
            let self = this, body = $('#emtmpl-email-editor-wrapper');

            body.on('click', '.emtmpl-delete-element-btn', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let $this = $(this);
                let thisColumn = $this.closest('.emtmpl-column');
                $this.closest('.emtmpl-element').remove();
                self.clearTab();
                self.activeTab('components');
                if (thisColumn.find('.emtmpl-element').length === 0) {
                    thisColumn.addClass('emtmpl-column-placeholder');
                }
            });

            body.on('click', '.emtmpl-duplicate-element-btn', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let currentEl = $(this).closest('.emtmpl-element');
                currentEl.after(currentEl.clone());
                self.removeFocus();
            });

            body.on('click', '.emtmpl-copy-element-btn', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let copiedEl = $(this).closest('.emtmpl-element');
                copiedEl = copiedEl.prop('outerHTML');
                localStorage.setItem('emtmplCopy', copiedEl);
            });

            body.on('click', function (e) {
                if ($(e.target).is('.emtmpl-layout-row')) {
                    self.removeFocus();
                    self.selectedEl = $(e.target);
                    self.selectedEl.closest('.emtmpl-block').addClass('emtmpl-block-focus');
                    self.loadLayoutControl();
                }
            })
        },

        removeFocus: function () {
            $('body .emtmpl-element-focus').removeClass('emtmpl-element-focus');
            $('body .emtmpl-block-focus').removeClass('emtmpl-block-focus');
            this.clearTab();
            this.activeTab('components');
        },

        /* drag and drop */
        initDragDrop: function () {
            let self = this;
            $('.emtmpl-layout-draggable').draggable({
                cursor: 'move',
                cursorAt: {left: 40, top: 15},
                helper: function () {
                    let type = $(this).data('type'), colsQty;
                    self.component = ViWec.Components.get(type);
                    colsQty = self.component.cols;
                    return viWecTmpl('emtmpl-block', {type: type, colsQty: colsQty});
                },
                start: function (e, ui) {
                    ui.helper.addClass('emtmpl-is-dragging');
                },
                stop: function (e, ui) {
                    ui.helper.handleRow();
                    ui.helper.find('.emtmpl-column').each(function (i, _this) {
                        $(_this).handleColumn();
                    });
                    ui.helper.removeClass('emtmpl-is-dragging');
                    viWecChange = true;
                },
                connectToSortable: viWecEditorArea
            });

            $('.emtmpl-sortable').sortable({
                cursor: 'move',
                placeholder: 'emtmpl-placeholder',
                handle: '.dashicons-move',
                cancel: '',
                cursorAt: {left: 40, top: 18},
                start: function (e, ui) {
                    // ui.placeholder.height(30);
                    ui.helper.addClass('emtmpl-is-dragging');
                },
                stop: function (ev, ui) {
                    ui.item.css({'width': 'auto', 'height': 'auto', 'z-index': 'unset'});
                    ui.item.find('.emtmpl-column-sortable').columnSortAble();
                    ui.item.removeClass('emtmpl-is-dragging');
                    viWecChange = true;
                }
            });

            $('.emtmpl-content-draggable').draggable({
                cursor: 'move',
                cursorAt: {left: 40, top: 15},
                helper: function () {
                    let $this = jQuery(this), html;

                    if ($this.data("drag-type") === "component") {
                        self.component = ViWec.Components.get($this.data("type"));
                    } else {
                        self.component = ViWec.Blocks.get($this.data("type"));
                    }

                    if (self.component.dragHtml) {
                        html = self.component.dragHtml;
                    } else {
                        html = self.component.html;
                    }

                    if ($(viWecEditorArea).children().length === 0) {
                        let row = $(viWecTmpl('emtmpl-block', {type: 'layout/grid1cols', colsQty: 1}));
                        row.handleRow().handleColumn();
                        row.find('.emtmpl-column-sortable').columnSortAble();
                        $('.emtmpl-sortable').append(row);
                    }

                    return `<div class='emtmpl-element' style="font-size:15px;border-radius: 0; overflow: hidden;line-height: 22px;" data-type="${$this.data('type')}">${html}</div>`;
                },
                start: function (ev, ui) {
                    ui.helper.addClass('emtmpl-is-dragging');
                },
                drag: function (ev, ui) {
                },
                stop: function (ev, ui) {
                    ui.helper.handleElement();
                    ui.helper.removeClass('emtmpl-is-dragging');
                    ui.helper.css('z-index', '');
                    ui.helper.click();
                    viWecChange = true;
                    $('#emtmpl-element-search input.emtmpl-search').val('').trigger('keyup');
                    $('#emtmpl-attributes-list input').trigger('keyup');
                },
                connectToSortable: '.emtmpl-column-sortable'
            });
        },
        initQuickAddLayout: function () {
            $('#emtmpl-quick-add-layout .emtmpl-control-btn').on('click', function () {
                let type = $(this).data('type'), colsQty, row;
                self.component = ViWec.Components.get(type);
                colsQty = self.component.cols;
                row = $(viWecTmpl('emtmpl-block', {type: type, colsQty: colsQty}));
                row.handleRow().handleColumn();
                row.find('.emtmpl-column-sortable').columnSortAble();
                $('.emtmpl-sortable').append(row);
                $(this).closest('.emtmpl-layout-list').toggle();
            });
        },

        globalEvent: function () {
            let $this = this, body = $('body');

            body.on('click', function (e) {
                if ($(e.target).is('#wpwrap') || $(e.target).is('#emtmpl-email-editor-wrapper')) {
                    $this.removeFocus();
                    $('.emtmpl-layout-list').hide();
                }
            }).on('keyup', function (e) {
                if (e.which !== 46) return;
                if ($(e.target).closest('#emtmpl-control-panel').length) return;
                $('.emtmpl-element-focus .emtmpl-delete-element-btn').trigger('click');
                $('.emtmpl-block-focus .emtmpl-delete-row-btn').trigger('click');
            });
        }
    };

    ViWec.StyleManager = {

        setStyle: function (element, styleProp, value, unit) {
            return element.css(styleProp, value + unit);
        },

        _getCssStyle: function (element, property, key = null) {
            let styleProp = key ? key : property.key;

            if (styleProp === 'width' && property.unit && property.unit === '%') {
                let child = parseInt(element.css('width'));
                let parent = parseInt(element.parent().css('width'));
                if (parent > 0) {
                    return Math.round((child / parent) * 100) + '%';
                }
            } else {
                let el = element.get(0), css;
                if (el) {
                    if (el.style && el.style.length > 0 && el.style[styleProp])//check inline
                        css = el.style[styleProp];
                    else if (el.currentStyle)	//check defined css
                        css = el.currentStyle[styleProp];
                    else if (window.getComputedStyle) {
                        css = document.defaultView.getDefaultComputedStyle ?
                            document.defaultView.getDefaultComputedStyle(el, null).getPropertyValue(styleProp) :
                            window.getComputedStyle(el, null).getPropertyValue(styleProp);
                    }
                    if (css === 'transparent') css = '';
                    return css;
                }
            }
        },

        getStyle: function (element, property, key) {
            return this._getCssStyle(element, property, key);
        }
    };

});

