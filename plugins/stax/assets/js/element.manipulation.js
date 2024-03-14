var ElementManipulation = ElementManipulation || {};

(function ($) {

    // USE STRICT
    "use strict";

    ElementManipulation.inst = {
        data: {
            selectedElement: null,
            copyElement: {
                id: null,
                type: null
            },
        },

        init: function () {
            $window.on('update-selected-element', function (e) {
                ElementManipulation.inst.data.selectedElement = e.detail;
            });

            ElementManipulation.inst.disabledLinkClicking();
            ElementManipulation.inst.removeEdit();
            ElementManipulation.inst.initClickEdits();
            ElementManipulation.inst.initContextMenu();
            ElementManipulation.inst.initShortcuts();
        },

        disabledLinkClicking: function () {
            $document.on('click', 'a, button, input', function (e) {
                e.stopPropagation();
                e.preventDefault();
            });
        },

        removeEdit: function () {
            $document.on('click', '*', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (!$(this).is('.hb-header-content-wrap.hb-hcontent-frame, .hb-section-content-wrap.hb-hcontent-frame, .hb-inner-content, .hb-element, .hb-element *, .editColumn, .editHeader, .editContainer, .context-menu-list, .context-menu-item, .context-menu-item > span')) {
                    ElementManipulation.inst.dispatchEvent('item-deselect');
                }
            });
        },

        initClickEdits: function () {
            $document.on('click', '.hb-header-content-wrap.hb-hcontent-frame, .hb-section-content-wrap.hb-hcontent-frame, .editContainer', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let target = $(this).closest('.hb-zone-row');

                if (target)
                    ElementManipulation.inst.dispatchEvent('edit', {
                        target: target[0],
                        type: ElementManipulation.inst.getItemType(target)
                    });
            });

            $document.on('click', '.hb-inner-content, .editColumn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let target = $(this).closest('.sq-column');

                if (target)
                    ElementManipulation.inst.dispatchEvent('edit', {
                        target: target[0],
                        type: ElementManipulation.inst.getItemType(target)
                    });
            });

            $document.on('click', '.hb-element, .hb-element *:not(.hb-inner-content, .editColumn, .hb-header-content-wrap.hb-hcontent-frame, .hb-section-content-wrap.hb-hcontent-frame, .editContainer)', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let target;

                if ($(this).hasClass('hb-element')) {
                    target = $(this);
                } else {
                    target = $(this).closest('.hb-element');
                }

                if (target)
                    ElementManipulation.inst.dispatchEvent('edit', {
                        target: target[0],
                        type: ElementManipulation.inst.getItemType(target)
                    });
            });

            $document.on('click', '.add-new-element', function (e) {
                e.preventDefault();
                e.stopPropagation();

                ElementManipulation.inst.dispatchEvent('add-new-element', null);
            });
        },

        initContextMenu: function () {
            let elements = {
                element: '.hb-element',
                column: '.sq-column',
                container: '.hb-zone-row'
            };

            for (let i in elements) {
                $.contextMenu({
                    selector: elements[i],
                    build: function ($trigger, e) {
                        return {
                            callback: function (key, options) {
                                let target = $(this)[0];

                                let type = ElementManipulation.inst.getItemType(target);

                                if (type)
                                    if (key === 'edit') {
                                        ElementManipulation.inst.dispatchEvent('edit', {
                                            target: target,
                                            type: type
                                        });
                                    } else if (key === 'duplicate') {
                                        ElementManipulation.inst.dispatchEvent('duplicate', {
                                            target: target,
                                            type: type
                                        });
                                    } else if (key === 'delete') {
                                        ElementManipulation.inst.dispatchEvent('delete', {
                                            target: target,
                                            type: type
                                        });
                                    } else if (key === 'layers') {
                                        ElementManipulation.inst.dispatchEvent('show-layers', null);
                                    } else if (key === 'copy') {
                                        ElementManipulation.inst.data.copyElement = {
                                            id: $(this).data('element-id'),
                                            type: type
                                        };
                                    } else if (key === 'paste') {
                                        let item = ElementManipulation.inst.data.copyElement;

                                        if (!item.id || !item.type)
                                            return false;

                                        ElementManipulation.inst.dispatchEvent('paste', {
                                            id: item.id,
                                            type: item.type,
                                            target: $(this).data('element-id')
                                        });
                                    } /*else if (key === 'paste-style') {
                                        let item = ElementManipulation.inst.data.copyElement;

                                        if (!item.id || !item.type)
                                            return false;

                                        ElementManipulation.inst.dispatchEvent('paste-style', {
                                            id: item.id,
                                            type: item.type,
                                            target: $(this).data('element-id')
                                        });
                                    } else if (key === 'reset-style') {
                                        ElementManipulation.inst.dispatchEvent('reset-style', {
                                            id: $(this).data('element-id'),
                                            type: type
                                        });
                                    }*/
                            },
                            items: {
                                'edit': {
                                    name: 'Edit ' + $trigger.data('element-context'),
                                    icon: 'edit'
                                },
                                'duplicate': {
                                    name: 'Duplicate <span class="context-shortcut">^+D</span>',
                                    isHtmlName: true,
                                    icon: 'duplicate'
                                },
                                'separator_1': '---------',
                                'copy': {
                                    name: 'Copy <span class="context-shortcut">^+C</span>',
                                    isHtmlName: true
                                },
                                'paste': {
                                    name: 'Paste <span class="context-shortcut">^+V</span>',
                                    isHtmlName: true,
                                    disabled: function (key, opt) {
                                        let item = ElementManipulation.inst.data.copyElement;

                                        if (!item.id && !item.type)
                                            return true;

                                        switch (i) {
                                            case 'column':
                                                if (item.type === 'element')
                                                    return false;
                                                break;
                                            case 'container':
                                                if (item.type === 'column')
                                                    return false;
                                                break;
                                            default:
                                        }

                                        return true;
                                    },
                                    visible: function (key, opt) {
                                        return (i !== 'element');
                                    }
                                },
                                /*'paste-style': {
                                    name: 'Paste Style <span class="context-shortcut">^+⇧+V</span>',
                                    isHtmlName: true,
                                    disabled: function (key, opt) {
                                        let item = ElementManipulation.inst.data.copyElement;

                                        if (!item.id && !item.type)
                                            return true;

                                        switch (i) {
                                            case 'element':
                                                if (item.type === 'element')
                                                    return false;
                                                break;
                                            case 'column':
                                                if (item.type === 'column')
                                                    return false;
                                                break;
                                            case 'container':
                                                if (item.type === 'container')
                                                    return false;
                                                break;
                                            default:
                                        }

                                        return true;
                                    },
                                },
                                'reset-style': {
                                    name: 'Reset Style <span class="context-shortcut">^+⇧+X</span>',
                                    isHtmlName: true
                                },*/
                                'separator_2': '---------',
                                'layers': {
                                    name: 'Layers',
                                    icon: 'layers'
                                },
                                'separator_3': '---------',
                                'delete': {
                                    name: 'Delete <span class="context-shortcut">⌫</span>',
                                    icon: 'delete',
                                    isHtmlName: true
                                },
                            }
                        };
                    }
                });
            }
        },

        initShortcuts: function () {
            $html
                .keyup(function (e) {
                    let target = ElementManipulation.inst.getSelectedElement();
                    if (target) {
                        let type = ElementManipulation.inst.getItemType(target);

                        if (e.key === 'Delete')
                            ElementManipulation.inst.dispatchEvent('delete', {
                                target: target,
                                type: type
                            });
                    }
                })
                .keydown(function (e) {
                    let target = ElementManipulation.inst.getSelectedElement();
                    if (target) {
                        let type = ElementManipulation.inst.getItemType(target);

                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'd') {
                            ElementManipulation.inst.dispatchEvent('duplicate', {
                                target: target,
                                type: type
                            });

                            event.preventDefault();
                            return false;
                        }

                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'c') {
                            ElementManipulation.inst.data.copyElement = {
                                id: $(target).data('element-id'),
                                type: type
                            };

                            event.preventDefault();
                            return false;
                        }

                        if ((e.ctrlKey || e.metaKey) && !e.shiftKey && e.key.toLowerCase() === 'v') {
                            let item = ElementManipulation.inst.data.copyElement;

                            if (!item.id || !item.type) {
                                event.preventDefault();
                                return false;
                            }

                            ElementManipulation.inst.dispatchEvent('paste', {
                                id: item.id,
                                type: item.type,
                                target: $(target).data('element-id')
                            });

                            event.preventDefault();
                            return false;
                        }

                        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 'v') {
                            ElementManipulation.inst.dispatchEvent('paste-style', target);
                            event.preventDefault();
                            return false;
                        }

                        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key.toLowerCase() === 'x') {
                            ElementManipulation.inst.dispatchEvent('reset-style', {
                                id: $(target).data('element-id'),
                                type: type
                            });

                            event.preventDefault();
                            return false;
                        }
                    }
                });
        },

        getSelectedElement: function () {
            if (ElementManipulation.inst.data.selectedElement) {
                let el = $('[data-element-id="' + ElementManipulation.inst.data.selectedElement + '"]');
                if (el.length)
                    return el[0];
            }

            return null;
        },

        getItemType: function (target) {
            let type = '';
            let elementType = $(target).data('item-type');

            if (elementType === 'header' || elementType === 'section') {
                type = 'container';
            } else {
                type = elementType;
            }

            return type;
        },

        dispatchEvent: function (name, data) {
            if (typeof (Event) === 'function') {
                window.dispatchEvent(new CustomEvent('iframe-events', {
                    'detail': {
                        data: data,
                        name: name
                    }
                }));
            } else {
                let evt = document.createEvent('CustomEvent', {
                    'detail': {
                        data: data,
                        name: name
                    }
                });
                evt.initEvent('iframe-events', true, false);
                window.dispatchEvent(evt);
            }
        }
    };

    let $window = $(window),
        $document = $(document),
        $html = $('html'),
        $body = $('body');

    $document.ready(ElementManipulation.inst.init);

})(jQuery);
