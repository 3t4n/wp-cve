/*!
 * Font Awesome Icon Picker
 * http://mjolnic.github.io/fontawesome-iconpicker/
 *
 * Originally written by (c) 2014 Javier Aguilar @mjolnic
 * Licensed under the MIT License
 * https://github.com/mjolnic/fontawesome-iconpicker/blob/master/LICENSE
 *
 */

(function(factory) {
        "use strict";
        if (typeof define === 'function' && define.amd) {
            define(['jquery'], factory);
        } else if (window.jQuery && !window.jQuery.fn.iconpicker) {
            factory(window.jQuery);
        }
    }
    (function($) {
        'use strict';

        var _helpers = {
            isEmpty: function(val) {
                return ((val === false) || (val === '') || (val === null) || (val === undefined));
            },
            isEmptyObject: function(val) {
                return (this.isEmpty(val) === true) || (val.length === 0);
            },
            isElement: function(selector) {
                return ($(selector).length > 0);
            },
            isString: function(val) {
                return ((typeof val === 'string') || (val instanceof String));
            },
            isArray: function(val) {
                return $.isArray(val);
            },
            inArray: function(val, arr) {
                return ($.inArray(val, arr) !== -1);
            },
            throwError: function(text) {
                throw "Font Awesome Icon Picker Exception: " + text;
            }
        };

        var Iconpicker = function(element, options) {
            this._id = Iconpicker._idCounter++;
            this.element = $(element).addClass('iconpicker-element');
            this._trigger('iconpickerCreate');
            this.options = $.extend({}, Iconpicker.defaultOptions, this.element.data(), options);
            this.options.templates = $.extend({}, Iconpicker.defaultOptions.templates, this.options.templates);
            this.options.originalPlacement = this.options.placement;

            // Iconpicker container element
            this.container = (_helpers.isElement(this.options.container) ? $(this.options.container) : false);
            if (this.container === false) {
                if (this.element.is('.dropdown-toggle')) {
                    this.container = $('~ .dropdown-menu:first', this.element);
                } else {
                    this.container = (this.element.is('input,textarea,button,.btn') ? this.element.parent() : this.element);
                }
            }
            this.container.addClass('iconpicker-container');

            if (this.isDropdownMenu()) {
                // if you try to click the dropdown, it is closed, because of that
                // we'll hide some picker controls
                this.options.templates.search = false;
                this.options.templates.buttons = false;
                this.options.placement = 'inline';
            }

            // Is the element an input? Should we search inside for any input?
            this.input = (this.element.is('input,textarea') ? this.element.addClass('iconpicker-input') : false);
            if (this.input === false) {
                this.input = (this.container.find(this.options.input));
                if (!this.input.is('input,textarea')) {
                    this.input = false;
                }
            }

            // Plugin as component ?
            this.component = this.isDropdownMenu() ? this.container.parent().find(this.options.component) : this.container.find(this.options.component);
            if (this.component.length === 0) {
                this.component = false;
            } else {
                this.component.find('i').addClass('iconpicker-component');
            }

            // Create popover and iconpicker HTML
            this._createPopover();
            this._createIconpicker();

            if (this.getAcceptButton().length === 0) {
                // disable this because we don't have accept buttons
                this.options.mustAccept = false;
            }

            // Avoid CSS issues with input-group-addon(s)
            if (this.isInputGroup()) {
                this.container.parent().append(this.popover);
            } else {
                this.container.append(this.popover);
            }

            // Bind events
            this._bindElementEvents();
            this._bindWindowEvents();

            // Refresh everything
            this.update(this.options.selected);

            if (this.isInline()) {
                this.show();
            }

            this._trigger('iconpickerCreated');
        };

        // Instance identifier counter
        Iconpicker._idCounter = 0;

        Iconpicker.defaultOptions = {
            title: false, // Popover title (optional) only if specified in the template
            selected: false, // use this value as the current item and ignore the original
            defaultValue: false, // use this value as the current item if input or element value is empty
            placement: 'bottom', // (has some issues with auto and CSS). auto, top, bottom, left, right
            collision: 'none', // If true, the popover will be repositioned to another position when collapses with the window borders
            animation: true, // fade in/out on show/hide ?
            //hide iconpicker automatically when a value is picked. it is ignored if mustAccept is not false and the accept button is visible
            hideOnSelect: false,
            showFooter: false,
            searchInFooter: false, // If true, the search will be added to the footer instead of the title
            mustAccept: false, // only applicable when there's an iconpicker-btn-accept button in the popover footer
            selectedCustomClass: 'bg-primary', // Appends this class when to the selected item
            icons: [], // list of icon classes (declared at the bottom of this script for maintainability)
            iconBaseClass: 'fa',
            iconClassPrefix: 'fa-',
            fullClassFormatter: function(val) {
                return 'fa ' + val;
            },
            input: 'input,.iconpicker-input', // children input selector
            inputSearch: false, // use the input as a search box too?
            container: false, //  Appends the popover to a specific element. If not set, the selected element or element parent is used
            component: '.input-group-addon,.iconpicker-component', // children component jQuery selector or object, relative to the container element
            // Plugin templates:
            templates: {
                popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
                    '<div class="popover-title"></div><div class="popover-content"></div></div>',
                footer: '<div class="popover-footer"></div>',
                buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
                    ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
                search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
                iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>',
            }
        };

        Iconpicker.batch = function(selector, method) {
            var args = Array.prototype.slice.call(arguments, 2);
            return $(selector).each(function() {
                var $inst = $(this).data('iconpicker');
                if (!!$inst) {
                    $inst[method].apply($inst, args);
                }
            });
        };

        Iconpicker.prototype = {
            constructor: Iconpicker,
            options: {},
            _id: 0, // instance identifier for bind/unbind events
            _trigger: function(name, opts) {
                //triggers an event bound to the element
                opts = opts || {};
                this.element.trigger($.extend({
                    type: name,
                    iconpickerInstance: this
                }, opts));
                //console.log(name + ' triggered for instance #' + this._id);
            },
            _createPopover: function() {
                this.popover = $(this.options.templates.popover);

                // title (header)
                var _title = this.popover.find('.popover-title');
                if (!!this.options.title) {
                    _title.append($('<div class="popover-title-text">' + this.options.title + '</div>'));
                }
                if (this.hasSeparatedSearchInput() && !this.options.searchInFooter) {
                    _title.append(this.options.templates.search);
                } else if (!this.options.title) {
                    _title.remove();
                }

                // footer
                if (this.options.showFooter && !_helpers.isEmpty(this.options.templates.footer)) {
                    var _footer = $(this.options.templates.footer);
                    if (this.hasSeparatedSearchInput() && this.options.searchInFooter) {
                        _footer.append($(this.options.templates.search));
                    }
                    if (!_helpers.isEmpty(this.options.templates.buttons)) {
                        _footer.append($(this.options.templates.buttons));
                    }
                    this.popover.append(_footer);
                }

                if (this.options.animation === true) {
                    this.popover.addClass('fade');
                }

                return this.popover;
            },
            _createIconpicker: function() {
                var _self = this;
                this.iconpicker = $(this.options.templates.iconpicker);

                var itemClickFn = function(e) {
                    var $this = $(this);

                    if ($this.is('i')) {
                        $this = $this.parent();
                    }

                    _self._trigger('iconpickerSelect', {
                        iconpickerItem: $this,
                        iconpickerValue: _self.iconpickerValue
                    });

                    if (_self.options.mustAccept === false) {
                        _self.update($this.data('iconpickerValue'));
                        _self._trigger('iconpickerSelected', {
                            iconpickerItem: this,
                            iconpickerValue: _self.iconpickerValue
                        });
                    } else {
                        _self.update($this.data('iconpickerValue'), true);
                    }

                    if (_self.options.hideOnSelect && (_self.options.mustAccept === false)) {
                        // only hide when the accept button is not present
                        _self.hide();
                    }
                    e.preventDefault();
                    return false;
                };

                for (var i in this.options.icons) {
                    var itemElement = $(this.options.templates.iconpickerItem);
                    itemElement.find('i')
                        .addClass( this.options.iconBaseClass + ' ' + this.options.iconClassPrefix + this.options.icons[i]);
                    itemElement.data('iconpickerValue', this.options.icons[i])
                        .on('click.iconpicker', itemClickFn);
                    this.iconpicker.find('.iconpicker-items').append(itemElement
                        .attr('title', '.' +  this.options.iconClassPrefix + this.options.icons[i]));
                }

                this.popover.find('.popover-content').append(this.iconpicker);

                this.component.append( '<i class="' + this.options.iconBaseClass + ' ' + this.input.val() + '"></i>' );

                return this.iconpicker;
            },
            _isEventInsideIconpicker: function(e) {
                var _t = $(e.target);

                if ((!_t.hasClass('iconpicker-element')  ||
                        (_t.hasClass('iconpicker-element') && !_t.is(this.element))) &&
                    (_t.parents('.iconpicker-popover').length === 0)) {
                    return false;
                }
                return true;
            },
            _bindElementEvents: function() {
                var _self = this;

                this.getSearchInput().on('keyup.iconpicker', function() {
                    _self.filter($(this).val().toLowerCase());
                });

                this.getAcceptButton().on('click.iconpicker', function() {
                    var _picked = _self.iconpicker.find('.iconpicker-selected').get(0);

                    _self.update(_self.iconpickerValue);

                    _self._trigger('iconpickerSelected', {
                        iconpickerItem: _picked,
                        iconpickerValue: _self.iconpickerValue
                    });
                    if (!_self.isInline()) {
                        _self.hide();
                    }
                });
                this.getCancelButton().on('click.iconpicker', function() {
                    if (!_self.isInline()) {
                        _self.hide();
                    }
                });

                this.element.on('focus.iconpicker', function(e) {
                    _self.show();
                    e.stopPropagation();
                });

                if (this.hasComponent()) {
                    this.component.on('click.iconpicker', function() {
                        _self.toggle();
                    });
                }

                if (this.hasInput()) {
                    // Bind input keyup event
                    this.input.on('keyup.iconpicker', function(e) {
                        if (!_helpers.inArray(e.keyCode, [38, 40, 37, 39, 16, 17, 18, 9, 8, 91, 93, 20, 46, 186, 190, 46, 78, 188, 44, 86])) {
                            _self.update();
                        } else {
                            _self._updateFormGroupStatus(_self.getValid(this.value) !== false);
                        }
                        if (_self.options.inputSearch === true) {
                            _self.filter($(this).val().toLowerCase());
                        }
                        //_self.hide();
                    });
                }

            },
            _bindWindowEvents: function() {
                var $doc = $(window.document);
                var _self = this;

                // Add a namespace to the document events so they can be identified
                // later for every instance separately
                var _eventNs = '.iconpicker.inst' + this._id;

                $(window).on('resize.iconpicker' + _eventNs + ' orientationchange.iconpicker' + _eventNs, function(e) {
                    // reposition popover
                    if (_self.popover.hasClass('in')) {
                        _self.updatePlacement();
                    }
                });


                if (!_self.isInline()) {
                    $doc.on('mouseup' + _eventNs, function(e) {
                        if ( !_self._isEventInsideIconpicker(e) && !_self.isInline()) {
                            _self.hide();
                        }
                    });
                }

                return false;
            },
            _unbindElementEvents: function() {
                this.popover.off('.iconpicker');
                this.element.off('.iconpicker');

                if (this.hasInput()) {
                    this.input.off('.iconpicker');
                }

                if (this.hasComponent()) {
                    this.component.off('.iconpicker');
                }

                if (this.hasContainer()) {
                    this.container.off('.iconpicker');
                }
            },
            _unbindWindowEvents: function() {
                // destroy window and window.document bound events
                $(window).off('.iconpicker.inst' + this._id);
                $(window.document).off('.iconpicker.inst' + this._id);
            },
            updatePlacement: function(placement, collision) {
                placement = placement || this.options.placement;
                this.options.placement = placement; // set new placement
                collision = collision || this.options.collision;
                collision = (collision === true ? 'flip' : collision);

                var _pos = {
                    // at: Defines which position (or side) on container element to align the
                    // popover element against: "horizontal vertical" alignment.
                    at: "right bottom",
                    // my: Defines which position (or side) on the popover being positioned to align
                    // with the container element: "horizontal vertical" alignment
                    my: "right top",
                    // of: Which element to position against.
                    of: (this.hasInput() && !this.isInputGroup()) ? this.input : this.container,
                    // collision: When the positioned element overflows the window (or within element)
                    // in some direction, move it to an alternative position.
                    collision: (collision === true ? 'flip' : collision),
                    // within: Element to position within, affecting collision detection.
                    within: window
                };

                // remove previous classes
                this.popover.removeClass('inline topLeftCorner topLeft top topRight topRightCorner ' +
                    'rightTop right rightBottom bottomRight bottomRightCorner ' +
                    'bottom bottomLeft bottomLeftCorner leftBottom left leftTop');

                if (typeof placement === 'object') {
                    // custom position ?
                    return this.popover.pos($.extend({}, _pos, placement));
                }

                switch (placement) {
                    case 'inline':
                        {
                            _pos = false;
                        }
                        break;
                    case 'topLeftCorner':
                        {
                            _pos.my = 'right bottom';
                            _pos.at = 'left top';
                        }
                        break;

                    case 'topLeft':
                        {
                            _pos.my = 'left bottom';
                            _pos.at = 'left top';
                        }
                        break;

                    case 'top':
                        {
                            _pos.my = 'center bottom';
                            _pos.at = 'center top';
                        }
                        break;

                    case 'topRight':
                        {
                            _pos.my = 'right bottom';
                            _pos.at = 'right top';
                        }
                        break;

                    case 'topRightCorner':
                        {
                            _pos.my = 'left bottom';
                            _pos.at = 'right top';
                        }
                        break;

                    case 'rightTop':
                        {
                            _pos.my = 'left bottom';
                            _pos.at = 'right center';
                        }
                        break;

                    case 'right':
                        {
                            _pos.my = 'left center';
                            _pos.at = 'right center';
                        }
                        break;

                    case 'rightBottom':
                        {
                            _pos.my = 'left top';
                            _pos.at = 'right center';
                        }
                        break;

                    case 'bottomRightCorner':
                        {
                            _pos.my = 'left top';
                            _pos.at = 'right bottom';
                        }
                        break;

                    case 'bottomRight':
                        {
                            _pos.my = 'right top';
                            _pos.at = 'right bottom';
                        }
                        break;
                    case 'bottom':
                        {
                            _pos.my = 'center top';
                            _pos.at = 'center bottom';
                        }
                        break;

                    case 'bottomLeft':
                        {
                            _pos.my = 'left top';
                            _pos.at = 'left bottom';
                        }
                        break;

                    case 'bottomLeftCorner':
                        {
                            _pos.my = 'right top';
                            _pos.at = 'left bottom';
                        }
                        break;

                    case 'leftBottom':
                        {
                            _pos.my = 'right top';
                            _pos.at = 'left center';
                        }
                        break;

                    case 'left':
                        {
                            _pos.my = 'right center';
                            _pos.at = 'left center';
                        }
                        break;

                    case 'leftTop':
                        {
                            _pos.my = 'right bottom';
                            _pos.at = 'left center';
                        }
                        break;

                    default:
                        {
                            return false;
                        }
                        break;

                }

                this.popover.css({
                    'display': (this.options.placement === 'inline') ? '' : 'block'
                });

                if (_pos !== false) {
                    this.popover.pos(_pos).css('maxWidth', $(window).width() - this.container.offset().left - 5);
                } else {
                    //reset position
                    this.popover.css({
                        'top': 'auto',
                        'right': 'auto',
                        'bottom': 'auto',
                        'left': 'auto',
                        'maxWidth': 'none'
                    });
                }
                this.popover.addClass(this.options.placement);

                return true;
            },
            _updateComponents: function() {
                // Update selected item
                this.iconpicker.find('.iconpicker-item.iconpicker-selected')
                    .removeClass('iconpicker-selected ' + this.options.selectedCustomClass);

                if (this.iconpickerValue) {
                    this.iconpicker.find('.' + this.options.fullClassFormatter(this.iconpickerValue).replace(/ /g, '.')).parent()
                        .addClass('iconpicker-selected ' + this.options.selectedCustomClass);
                }

                // Update component item
                if (this.hasComponent()) {
                    var icn = this.component.find('i');
                    if (icn.length > 0) {
                        icn.attr('class', this.options.fullClassFormatter(this.iconpickerValue));
                    } else {
                        this.component.html(this.getHtml());
                    }
                }

            },
            _updateFormGroupStatus: function(isValid) {
                if (this.hasInput()) {
                    if (isValid !== false) {
                        // Remove form-group error class if any
                        this.input.parents('.form-group:first').removeClass('has-error');
                    } else {
                        this.input.parents('.form-group:first').addClass('has-error');
                    }
                    return true;
                }
                return false;
            },
            getValid: function(val) {
                // here we must validate the value (you may change this validation
                // to suit your needs
                if (!_helpers.isString(val)) {
                    val = '';
                }

                var isEmpty = (val === '');

                // trim string
                val = $.trim(val);

                if (_helpers.inArray(val, this.options.icons) || isEmpty) {
                    return val;
                }
                return false;
            },
            /**
             * Sets the internal item value and updates everything, excepting the input or element.
             * For doing so, call setSourceValue() or update() instead
             */
            setValue: function(val) {
                // sanitize first
                var _val = this.getValid(val);
                if (_val !== false) {
                    this.iconpickerValue = _val;
                    this._trigger('iconpickerSetValue', {
                        iconpickerValue: _val
                    });
                    return this.iconpickerValue;
                } else {
                    this._trigger('iconpickerInvalid', {
                        iconpickerValue: val
                    });
                    return false;
                }
            },
            getHtml: function() {
                return '<i class="' + this.options.fullClassFormatter(this.iconpickerValue) + '"></i>';
            },
            /**
             * Calls setValue and if it's a valid item value, sets the input or element value
             */
            setSourceValue: function(val) {
                val = this.setValue(val);

                if ((val !== false) && (val !== '')) {
                    if (this.hasInput()) {
                        this.input.val( this.options.iconClassPrefix + this.iconpickerValue );
                    } else {
                        this.element.data('iconpickerValue', this.iconpickerValue);
                    }
                    this._trigger('iconpickerSetSourceValue', {
                        iconpickerValue: val
                    });
                }
                return val;
            },
            /**
             * Returns the input or element item value, without formatting, or defaultValue
             * if it's empty string, undefined, false or null
             * @param {type} defaultValue
             * @returns string|mixed
             */
            getSourceValue: function(defaultValue) {
                // returns the input or element value, as string
                defaultValue = defaultValue || this.options.defaultValue;
                var val = defaultValue;

                if (this.hasInput()) {
                    val = this.input.val();
                } else {
                    val = this.element.data('iconpickerValue');
                }
                if ((val === undefined) || (val === '') || (val === null) || (val === false)) {
                    // if not defined or empty, return default
                    val = defaultValue;
                }
                return val;
            },
            hasInput: function() {
                return (this.input !== false);
            },
            isInputSearch: function() {
                return (this.hasInput() && (this.options.inputSearch === true));
            },
            isInputGroup: function() {
                return this.container.is('.input-group');
            },
            isDropdownMenu: function() {
                return this.container.is('.dropdown-menu');
            },
            hasSeparatedSearchInput: function() {
                return (this.options.templates.search !== false) && (!this.isInputSearch());
            },
            hasComponent: function() {
                return (this.component !== false);
            },
            hasContainer: function() {
                return (this.container !== false);
            },
            getAcceptButton: function() {
                return this.popover.find('.iconpicker-btn-accept');
            },
            getCancelButton: function() {
                return this.popover.find('.iconpicker-btn-cancel');
            },
            getSearchInput: function() {
                return this.popover.find('.iconpicker-search');
            },
            filter: function(filterText) {
                if (_helpers.isEmpty(filterText)) {
                    this.iconpicker.find('.iconpicker-item').show();
                    return $(false);
                } else {
                    var found = [];
                    this.iconpicker.find('.iconpicker-item').each(function() {
                        var $this = $(this);
                        var text = $this.attr('title').toLowerCase();
                        var regex = false;
                        try {
                            regex = new RegExp(filterText, 'g');
                        } catch (e) {
                            regex = false;
                        }
                        if ((regex !== false) && text.match(regex)) {
                            found.push($this);
                            $this.show();
                        } else {
                            $this.hide();
                        }
                    });
                    return found;
                }
            },
            show: function() {
                if (this.popover.hasClass('in')) {
                    return false;
                }
                // hide other non-inline pickers
                $.iconpicker.batch($('.iconpicker-popover.in:not(.inline)').not(this.popover), 'hide');

                this._trigger('iconpickerShow');
                this.updatePlacement();
                this.popover.addClass('in');
                setTimeout($.proxy(function() {
                    this.popover.css('display', this.isInline() ? '' : 'block');
                    this._trigger('iconpickerShown');
                }, this), this.options.animation ? 300 : 1); // animation duration
            },
            hide: function() {
                if (!this.popover.hasClass('in')) {
                    return false;
                }
                this._trigger('iconpickerHide');
                this.popover.removeClass('in');
                setTimeout($.proxy(function() {
                    this.popover.css('display', 'none');
                    this.getSearchInput().val('');
                    this.filter(''); // clear filter
                    this._trigger('iconpickerHidden');
                }, this), this.options.animation ? 300 : 1);
            },
            toggle: function() {
                if (this.popover.is(":visible")) {
                    this.hide();
                } else {
                    this.show(true);
                }
            },
            update: function(val, updateOnlyInternal) {
                val = (val ? val :  this.getSourceValue(this.iconpickerValue));
                // reads the input or element value again and tries to update the plugin
                // fallback to the current selected item value
                this._trigger('iconpickerUpdate');

                if (updateOnlyInternal === true) {
                    val = this.setValue(val);
                } else {
                    val = this.setSourceValue(val);
                    this._updateFormGroupStatus(val !== false);
                }

                if (val !== false) {
                    this._updateComponents();
                }

                this._trigger('iconpickerUpdated');
                return val;
            },
            destroy: function() {
                this._trigger('iconpickerDestroy');

                // unbinds events and resets everything to the initial state,
                // including component mode
                this.element.removeData('iconpicker').removeData('iconpickerValue').removeClass('iconpicker-element');

                this._unbindElementEvents();
                this._unbindWindowEvents();

                $(this.popover).remove();

                this._trigger('iconpickerDestroyed');
            },
            disable: function() {
                if (this.hasInput()) {
                    this.input.prop('disabled', true);
                    return true;
                }
                return false;
            },
            enable: function() {
                if (this.hasInput()) {
                    this.input.prop('disabled', false);
                    return true;
                }
                return false;
            },
            isDisabled: function() {
                if (this.hasInput()) {
                    return (this.input.prop('disabled') === true);
                }
                return false;
            },
            isInline: function() {
                return (this.options.placement === 'inline') || (this.popover.hasClass('inline'));
            }
        };

        $.iconpicker = Iconpicker;

        // jQuery plugin
        $.fn.iconpicker = function(options) {
            return this.each(function() {
                var $this = $(this);
                if (!$this.data('iconpicker')) {
                    // create plugin instance (only if not exists) and expose the entire instance API
                    $this.data('iconpicker', new Iconpicker(this, ((typeof options === 'object') ? options : {})));
                }
            });
        };

        // List of all Font Awesome icons without class prefix
        Iconpicker.defaultOptions.icons = [
            'fa-500px','fa-adjust','fa-adn','fa-align-center','fa-align-justify','fa-align-left','fa-align-right','fa-amazon',
            'fa-ambulance','fa-anchor','fa-android','fa-angellist','fa-angle-double-down','fa-angle-double-left','fa-angle-double-right',
            'fa-angle-double-up','fa-angle-down','fa-angle-left','fa-angle-right','fa-angle-up','fa-apple','fa-archive','fa-area-chart',
            'fa-arrow-circle-down','fa-arrow-circle-left','fa-arrow-circle-o-down','fa-arrow-circle-o-left','fa-arrow-circle-o-right',
            'fa-arrow-circle-o-up','fa-arrow-circle-right','fa-arrow-circle-up','fa-arrow-down','fa-arrow-left','fa-arrow-right',
            'fa-arrow-up','fa-arrows','fa-arrows-alt','fa-arrows-h','fa-arrows-v','fa-asterisk','fa-at','fa-automobile','fa-backward',
            'fa-balance-scale','fa-ban','fa-bank','fa-bar-chart','fa-bar-chart-o','fa-barcode','fa-bars','fa-battery-0','fa-battery-1',
            'fa-battery-2','fa-battery-3','fa-battery-4','fa-battery-empty','fa-battery-full','fa-battery-half','fa-battery-quarter',
            'fa-battery-three-quarters','fa-bed','fa-beer','fa-behance','fa-behance-square','fa-bell','fa-bell-o','fa-bell-slash',
            'fa-bell-slash-o','fa-bicycle','fa-binoculars','fa-birthday-cake','fa-bitbucket','fa-bitbucket-square','fa-bitcoin',
            'fa-black-tie','fa-bold','fa-bolt','fa-bomb','fa-book','fa-bookmark','fa-bookmark-o','fa-briefcase','fa-btc','fa-bug',
            'fa-building','fa-building-o','fa-bullhorn','fa-bullseye','fa-bus','fa-buysellads','fa-cab','fa-calculator','fa-calendar',
            'fa-calendar-check-o','fa-calendar-minus-o','fa-calendar-o','fa-calendar-plus-o','fa-calendar-times-o','fa-camera',
            'fa-camera-retro','fa-car','fa-caret-down','fa-caret-left','fa-caret-right','fa-caret-square-o-down',
            'fa-caret-square-o-left','fa-caret-square-o-right','fa-caret-square-o-up','fa-caret-up','fa-cart-arrow-down','fa-cart-plus',
            'fa-cc','fa-cc-amex','fa-cc-diners-club','fa-cc-discover','fa-cc-jcb','fa-cc-mastercard','fa-cc-paypal','fa-cc-stripe',
            'fa-cc-visa','fa-certificate','fa-chain','fa-chain-broken','fa-check','fa-check-circle','fa-check-circle-o',
            'fa-check-square','fa-check-square-o','fa-chevron-circle-down','fa-chevron-circle-left','fa-chevron-circle-right',
            'fa-chevron-circle-up','fa-chevron-down','fa-chevron-left','fa-chevron-right','fa-chevron-up','fa-child','fa-chrome',
            'fa-circle','fa-circle-o','fa-circle-o-notch','fa-circle-thin','fa-clipboard','fa-clock-o','fa-clone','fa-close','fa-cloud',
            'fa-cloud-download','fa-cloud-upload','fa-cny','fa-code','fa-code-fork','fa-codepen','fa-coffee','fa-cog','fa-cogs',
            'fa-columns','fa-comment','fa-comment-o','fa-commenting','fa-commenting-o','fa-comments','fa-comments-o','fa-compass',
            'fa-compress','fa-connectdevelop','fa-contao','fa-copy','fa-copyright','fa-creative-commons','fa-credit-card','fa-crop',
            'fa-crosshairs','fa-css3','fa-cube','fa-cubes','fa-cut','fa-cutlery','fa-dashboard','fa-dashcube','fa-database','fa-dedent',
            'fa-delicious','fa-desktop','fa-deviantart','fa-diamond','fa-digg','fa-dollar','fa-dot-circle-o','fa-download','fa-dribbble',
            'fa-dropbox','fa-drupal','fa-edit','fa-eject','fa-ellipsis-h','fa-ellipsis-v','fa-empire','fa-envelope','fa-envelope-o',
            'fa-envelope-square','fa-eraser','fa-eur','fa-euro','fa-exchange','fa-exclamation','fa-exclamation-circle',
            'fa-exclamation-triangle','fa-expand','fa-expeditedssl','fa-external-link','fa-external-link-square','fa-eye','fa-eye-slash',
            'fa-eyedropper','fa-facebook','fa-facebook-f','fa-facebook-official','fa-facebook-square','fa-fast-backward','fa-fast-forward',
            'fa-fax','fa-feed','fa-female','fa-fighter-softtemplate','fa-file','fa-file-archive-o','fa-file-audio-o','fa-file-code-o',
            'fa-file-excel-o','fa-file-image-o','fa-file-movie-o','fa-file-o','fa-file-pdf-o','fa-file-photo-o','fa-file-picture-o',
            'fa-file-powerpoint-o','fa-file-sound-o','fa-file-text','fa-file-text-o','fa-file-video-o','fa-file-word-o','fa-file-zip-o',
            'fa-files-o','fa-film','fa-filter','fa-fire','fa-fire-extinguisher','fa-firefox','fa-flag','fa-flag-checkered','fa-flag-o',
            'fa-flash','fa-flask','fa-flickr','fa-floppy-o','fa-folder','fa-folder-o','fa-folder-open','fa-folder-open-o','fa-font',
            'fa-fonticons','fa-forumbee','fa-forward','fa-foursquare','fa-frown-o','fa-futbol-o','fa-gamepad','fa-gavel','fa-gbp','fa-ge',
            'fa-gear','fa-gears','fa-genderless','fa-get-pocket','fa-gg','fa-gg-circle','fa-gift','fa-git','fa-git-square','fa-github',
            'fa-github-alt','fa-github-square','fa-gittip','fa-glass','fa-globe','fa-google','fa-google-plus','fa-google-plus-square',
            'fa-google-wallet','fa-graduation-cap','fa-gratipay','fa-group','fa-h-square','fa-hacker-news','fa-hand-grab-o',
            'fa-hand-lizard-o','fa-hand-o-down','fa-hand-o-left','fa-hand-o-right','fa-hand-o-up','fa-hand-paper-o','fa-hand-peace-o',
            'fa-hand-pointer-o','fa-hand-scissors-o','fa-hand-spock-o','fa-hand-stop-o','fa-hdd-o','fa-header','fa-headphones',
            'fa-heart','fa-heart-o','fa-heartbeat','fa-history','fa-home','fa-hospital-o','fa-hotel','fa-hourglass','fa-hourglass-1',
            'fa-hourglass-2','fa-hourglass-3','fa-hourglass-end','fa-hourglass-half','fa-hourglass-o','fa-hourglass-start','fa-houzz',
            'fa-html5','fa-i-cursor','fa-ils','fa-image','fa-inbox','fa-indent','fa-industry','fa-info','fa-info-circle','fa-inr',
            'fa-instagram','fa-institution','fa-internet-explorer','fa-intersex','fa-ioxhost','fa-italic','fa-joomla','fa-jpy','fa-jsfiddle',
            'fa-key','fa-keyboard-o','fa-krw','fa-language','fa-laptop','fa-lastfm','fa-lastfm-square','fa-leaf','fa-leanpub','fa-legal',
            'fa-lemon-o','fa-level-down','fa-level-up','fa-life-bouy','fa-life-buoy','fa-life-ring','fa-life-saver','fa-lightbulb-o',
            'fa-line-chart','fa-link','fa-linkedin','fa-linkedin-square','fa-linux','fa-list','fa-list-alt','fa-list-ol','fa-list-ul',
            'fa-location-arrow','fa-lock','fa-long-arrow-down','fa-long-arrow-left','fa-long-arrow-right','fa-long-arrow-up','fa-magic',
            'fa-magnet','fa-mail-forward','fa-mail-reply','fa-mail-reply-all','fa-male','fa-map','fa-map-marker','fa-map-o','fa-map-pin',
            'fa-map-signs','fa-mars-double','fa-mars-stroke','fa-mars-stroke-h','fa-mars-stroke-v','fa-maxcdn','fa-meanpath','fa-medium',
            'fa-medkit','fa-mercury','fa-microphone','fa-microphone-slash','fa-minus','fa-minus-circle','fa-minus-square','fa-minus-square-o',
            'fa-mobile','fa-mobile-phone','fa-money','fa-moon-o','fa-mortar-board','fa-motorcycle','fa-mouse-pointer','fa-music','fa-navicon',
            'fa-neuter','fa-newspaper-o','fa-object-group','fa-object-ungroup','fa-odnoklassniki','fa-odnoklassniki-square','fa-opencart',
            'fa-openid','fa-opera','fa-optin-monster','fa-outdent','fa-pagelines','fa-paint-brush','fa-paper-plane','fa-paper-plane-o',
            'fa-paperclip','fa-paragraph','fa-paste','fa-pause','fa-paw','fa-paypal','fa-pencil','fa-pencil-square','fa-pencil-square-o',
            'fa-phone','fa-phone-square','fa-photo','fa-picture-o','fa-pie-chart','fa-pied-piper','fa-pied-piper-alt','fa-pinterest',
            'fa-pinterest-p','fa-pinterest-square','fa-plane','fa-play','fa-play-circle','fa-play-circle-o','fa-plug','fa-plus','fa-plus-circle',
            'fa-plus-square','fa-plus-square-o','fa-power-off','fa-print','fa-puzzle-piece','fa-qq','fa-qrcode','fa-question',
            'fa-question-circle','fa-quote-left','fa-quote-right','fa-ra','fa-random','fa-rebel','fa-recycle','fa-reddit','fa-reddit-square',
            'fa-refresh','fa-registered','fa-remove','fa-renren','fa-reorder','fa-repeat','fa-reply','fa-reply-all','fa-retweet','fa-rmb',
            'fa-road','fa-rocket','fa-rotate-left','fa-rotate-right','fa-rouble','fa-rss','fa-rss-square','fa-rub','fa-ruble','fa-rupee',
            'fa-safari','fa-save','fa-scissors','fa-search','fa-search-minus','fa-search-plus','fa-sellsy','fa-send','fa-send-o','fa-server',
            'fa-share','fa-share-alt','fa-share-alt-square','fa-share-square','fa-share-square-o','fa-shekel','fa-sheqel','fa-shield',
            'fa-ship','fa-shirtsinbulk','fa-shopping-cart','fa-sign-in','fa-sign-out','fa-signal','fa-simplybuilt','fa-sitemap','fa-skyatlas',
            'fa-skype','fa-slack','fa-sliders','fa-slideshare','fa-smile-o','fa-soccer-ball-o','fa-sort','fa-sort-alpha-asc',
            'fa-sort-alpha-desc','fa-sort-amount-asc','fa-sort-amount-desc','fa-sort-asc','fa-sort-desc','fa-sort-down','fa-sort-numeric-asc',
            'fa-sort-numeric-desc','fa-sort-up','fa-soundcloud','fa-space-shuttle','fa-spinner','fa-spoon','fa-spotify','fa-square',
            'fa-square-o','fa-stack-exchange','fa-stack-overflow','fa-steam','fa-steam-square','fa-star','fa-star-half','fa-star-half-empty',
            'fa-star-half-full','fa-star-half-o','fa-star-o','fa-step-backward','fa-step-forward','fa-stethoscope','fa-sticky-note',
            'fa-sticky-note-o','fa-stop','fa-street-view','fa-strikethrough','fa-stumbleupon','fa-stumbleupon-circle','fa-subscript',
            'fa-subway','fa-suitcase','fa-sun-o','fa-superscript','fa-support','fa-table','fa-tablet','fa-tachometer','fa-tag','fa-tags',
            'fa-tasks','fa-taxi','fa-television','fa-tencent-weibo','fa-terminal','fa-text-height','fa-text-width','fa-th','fa-th-large',
            'fa-th-list','fa-thumb-tack','fa-thumbs-down','fa-thumbs-o-down','fa-thumbs-o-up','fa-thumbs-up','fa-ticket','fa-times',
            'fa-times-circle','fa-times-circle-o','fa-tint','fa-toggle-down','fa-toggle-left','fa-toggle-off','fa-toggle-on','fa-toggle-right',
            'fa-toggle-up','fa-trademark','fa-train','fa-transgender','fa-transgender-alt','fa-trash','fa-trash-o','fa-tree','fa-trello',
            'fa-tripadvisor','fa-trophy','fa-truck','fa-try','fa-tty','fa-tumblr','fa-tumblr-square','fa-turkish-lira','fa-tv','fa-twitch',
            'fa-twitter','fa-twitter-square','fa-umbrella','fa-underline','fa-university','fa-unlink','fa-unlock','fa-unlock-alt','fa-unsorted',
            'fa-upload','fa-usd','fa-user','fa-user-md','fa-user-plus','fa-user-secret','fa-user-times','fa-users','fa-venus','fa-venus-double',
            'fa-venus-mars','fa-viacoin','fa-video-camera','fa-vimeo','fa-vimeo-square','fa-vine','fa-vk','fa-volume-down','fa-volume-off',
            'fa-volume-up','fa-warning','fa-wechat','fa-weibo','fa-weixin','fa-whatsapp','fa-wheelchair','fa-wifi','fa-wikipedia-w','fa-windows',
            'fa-won','fa-wordpress','fa-wrench','fa-xing','fa-xing-square','fa-y-combinator','fa-y-combinator-square','fa-yahoo','fa-yc',
            'fa-yc-square','fa-yelp','fa-yen','fa-youtube','fa-youtube-play','fa-youtube-square'
        ];
    }));

/*!
 * .pos() plugin based on jQuery UI Position 1.10.4, renamed to avoid
 * problems with jQuery native .position(), and added some triggers when position
 * collapses
 *
 * http://jqueryui.com
 *
 * Copyright 2014 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://api.jqueryui.com/position/
 */
(function($, undefined) {

    $.ui = $.ui || {};

    var cachedScrollbarWidth,
        max = Math.max,
        abs = Math.abs,
        round = Math.round,
        rhorizontal = /left|center|right/,
        rvertical = /top|center|bottom/,
        roffset = /[\+\-]\d+(\.[\d]+)?%?/,
        rposition = /^\w+/,
        rpercent = /%$/,
        _position = $.fn.pos;

    function getOffsets(offsets, width, height) {
        return [
            parseFloat(offsets[0]) * (rpercent.test(offsets[0]) ? width / 100 : 1),
            parseFloat(offsets[1]) * (rpercent.test(offsets[1]) ? height / 100 : 1)
        ];
    }

    function parseCss(element, property) {
        return parseInt($.css(element, property), 10) || 0;
    }

    function getDimensions(elem) {
        var raw = elem[0];
        if (raw.nodeType === 9) {
            return {
                width: elem.width(),
                height: elem.height(),
                offset: {
                    top: 0,
                    left: 0
                }
            };
        }
        if ($.isWindow(raw)) {
            return {
                width: elem.width(),
                height: elem.height(),
                offset: {
                    top: elem.scrollTop(),
                    left: elem.scrollLeft()
                }
            };
        }
        if (raw.preventDefault) {
            return {
                width: 0,
                height: 0,
                offset: {
                    top: raw.pageY,
                    left: raw.pageX
                }
            };
        }
        return {
            width: elem.outerWidth(),
            height: elem.outerHeight(),
            offset: elem.offset()
        };
    }

    $.pos = {
        scrollbarWidth: function() {
            if (cachedScrollbarWidth !== undefined) {
                return cachedScrollbarWidth;
            }
            var w1, w2,
                div = $("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                innerDiv = div.children()[0];

            $("body").append(div);
            w1 = innerDiv.offsetWidth;
            div.css("overflow", "scroll");

            w2 = innerDiv.offsetWidth;

            if (w1 === w2) {
                w2 = div[0].clientWidth;
            }

            div.remove();

            return (cachedScrollbarWidth = w1 - w2);
        },
        getScrollInfo: function(within) {
            var overflowX = within.isWindow || within.isDocument ? "" :
                within.element.css("overflow-x"),
                overflowY = within.isWindow || within.isDocument ? "" :
                within.element.css("overflow-y"),
                hasOverflowX = overflowX === "scroll" ||
                (overflowX === "auto" && within.width < within.element[0].scrollWidth),
                hasOverflowY = overflowY === "scroll" ||
                (overflowY === "auto" && within.height < within.element[0].scrollHeight);
            return {
                width: hasOverflowY ? $.pos.scrollbarWidth() : 0,
                height: hasOverflowX ? $.pos.scrollbarWidth() : 0
            };
        },
        getWithinInfo: function(element) {
            var withinElement = $(element || window),
                isWindow = $.isWindow(withinElement[0]),
                isDocument = !!withinElement[0] && withinElement[0].nodeType === 9;
            return {
                element: withinElement,
                isWindow: isWindow,
                isDocument: isDocument,
                offset: withinElement.offset() || {
                    left: 0,
                    top: 0
                },
                scrollLeft: withinElement.scrollLeft(),
                scrollTop: withinElement.scrollTop(),
                width: isWindow ? withinElement.width() : withinElement.outerWidth(),
                height: isWindow ? withinElement.height() : withinElement.outerHeight()
            };
        }
    };

    $.fn.pos = function(options) {
        if (!options || !options.of) {
            return _position.apply(this, arguments);
        }

        // make a copy, we don't want to modify arguments
        options = $.extend({}, options);

        var atOffset, targetWidth, targetHeight, targetOffset, basePosition, dimensions,
            target = $(options.of),
            within = $.pos.getWithinInfo(options.within),
            scrollInfo = $.pos.getScrollInfo(within),
            collision = (options.collision || "flip").split(" "),
            offsets = {};

        dimensions = getDimensions(target);
        if (target[0].preventDefault) {
            // force left top to allow flipping
            options.at = "left top";
        }
        targetWidth = dimensions.width;
        targetHeight = dimensions.height;
        targetOffset = dimensions.offset;
        // clone to reuse original targetOffset later
        basePosition = $.extend({}, targetOffset);

        // force my and at to have valid horizontal and vertical positions
        // if a value is missing or invalid, it will be converted to center
        $.each(["my", "at"], function() {
            var pos = (options[this] || "").split(" "),
                horizontalOffset,
                verticalOffset;

            if (pos.length === 1) {
                pos = rhorizontal.test(pos[0]) ?
                    pos.concat(["center"]) :
                    rvertical.test(pos[0]) ? ["center"].concat(pos) : ["center", "center"];
            }
            pos[0] = rhorizontal.test(pos[0]) ? pos[0] : "center";
            pos[1] = rvertical.test(pos[1]) ? pos[1] : "center";

            // calculate offsets
            horizontalOffset = roffset.exec(pos[0]);
            verticalOffset = roffset.exec(pos[1]);
            offsets[this] = [
                horizontalOffset ? horizontalOffset[0] : 0,
                verticalOffset ? verticalOffset[0] : 0
            ];

            // reduce to just the positions without the offsets
            options[this] = [
                rposition.exec(pos[0])[0],
                rposition.exec(pos[1])[0]
            ];
        });

        // normalize collision option
        if (collision.length === 1) {
            collision[1] = collision[0];
        }

        if (options.at[0] === "right") {
            basePosition.left += targetWidth;
        } else if (options.at[0] === "center") {
            basePosition.left += targetWidth / 2;
        }

        if (options.at[1] === "bottom") {
            basePosition.top += targetHeight;
        } else if (options.at[1] === "center") {
            basePosition.top += targetHeight / 2;
        }

        atOffset = getOffsets(offsets.at, targetWidth, targetHeight);
        basePosition.left += atOffset[0];
        basePosition.top += atOffset[1];

        return this.each(function() {
            var collisionPosition, using,
                elem = $(this),
                elemWidth = elem.outerWidth(),
                elemHeight = elem.outerHeight(),
                marginLeft = parseCss(this, "marginLeft"),
                marginTop = parseCss(this, "marginTop"),
                collisionWidth = elemWidth + marginLeft + parseCss(this, "marginRight") + scrollInfo.width,
                collisionHeight = elemHeight + marginTop + parseCss(this, "marginBottom") + scrollInfo.height,
                position = $.extend({}, basePosition),
                myOffset = getOffsets(offsets.my, elem.outerWidth(), elem.outerHeight());

            if (options.my[0] === "right") {
                position.left -= elemWidth;
            } else if (options.my[0] === "center") {
                position.left -= elemWidth / 2;
            }

            if (options.my[1] === "bottom") {
                position.top -= elemHeight;
            } else if (options.my[1] === "center") {
                position.top -= elemHeight / 2;
            }

            position.left += myOffset[0];
            position.top += myOffset[1];

            // if the browser doesn't support fractions, then round for consistent results
            if (!$.support.offsetFractions) {
                position.left = round(position.left);
                position.top = round(position.top);
            }

            collisionPosition = {
                marginLeft: marginLeft,
                marginTop: marginTop
            };

            $.each(["left", "top"], function(i, dir) {
                if ($.ui.pos[collision[i]]) {
                    $.ui.pos[collision[i]][dir](position, {
                        targetWidth: targetWidth,
                        targetHeight: targetHeight,
                        elemWidth: elemWidth,
                        elemHeight: elemHeight,
                        collisionPosition: collisionPosition,
                        collisionWidth: collisionWidth,
                        collisionHeight: collisionHeight,
                        offset: [atOffset[0] + myOffset[0], atOffset[1] + myOffset[1]],
                        my: options.my,
                        at: options.at,
                        within: within,
                        elem: elem
                    });
                }
            });

            if (options.using) {
                // adds feedback as second argument to using callback, if present
                using = function(props) {
                    var left = targetOffset.left - position.left,
                        right = left + targetWidth - elemWidth,
                        top = targetOffset.top - position.top,
                        bottom = top + targetHeight - elemHeight,
                        feedback = {
                            target: {
                                element: target,
                                left: targetOffset.left,
                                top: targetOffset.top,
                                width: targetWidth,
                                height: targetHeight
                            },
                            element: {
                                element: elem,
                                left: position.left,
                                top: position.top,
                                width: elemWidth,
                                height: elemHeight
                            },
                            horizontal: right < 0 ? "left" : left > 0 ? "right" : "center",
                            vertical: bottom < 0 ? "top" : top > 0 ? "bottom" : "middle"
                        };
                    if (targetWidth < elemWidth && abs(left + right) < targetWidth) {
                        feedback.horizontal = "center";
                    }
                    if (targetHeight < elemHeight && abs(top + bottom) < targetHeight) {
                        feedback.vertical = "middle";
                    }
                    if (max(abs(left), abs(right)) > max(abs(top), abs(bottom))) {
                        feedback.important = "horizontal";
                    } else {
                        feedback.important = "vertical";
                    }
                    options.using.call(this, props, feedback);
                };
            }

            elem.offset($.extend(position, {
                using: using
            }));
        });
    };

    $.ui.pos = {
        _trigger: function(position, data, name, triggered) {
            if (data.elem) {
                data.elem.trigger({
                    'type': name,
                    'position': position,
                    'positionData': data,
                    'triggered': triggered
                });
            }
        },
        fit: {
            left: function(position, data) {
                $.ui.pos._trigger(position, data, 'posCollide', 'fitLeft');
                var within = data.within,
                    withinOffset = within.isWindow ? within.scrollLeft : within.offset.left,
                    outerWidth = within.width,
                    collisionPosLeft = position.left - data.collisionPosition.marginLeft,
                    overLeft = withinOffset - collisionPosLeft,
                    overRight = collisionPosLeft + data.collisionWidth - outerWidth - withinOffset,
                    newOverRight;

                // element is wider than within
                if (data.collisionWidth > outerWidth) {
                    // element is initially over the left side of within
                    if (overLeft > 0 && overRight <= 0) {
                        newOverRight = position.left + overLeft + data.collisionWidth - outerWidth - withinOffset;
                        position.left += overLeft - newOverRight;
                        // element is initially over right side of within
                    } else if (overRight > 0 && overLeft <= 0) {
                        position.left = withinOffset;
                        // element is initially over both left and right sides of within
                    } else {
                        if (overLeft > overRight) {
                            position.left = withinOffset + outerWidth - data.collisionWidth;
                        } else {
                            position.left = withinOffset;
                        }
                    }
                    // too far left -> align with left edge
                } else if (overLeft > 0) {
                    position.left += overLeft;
                    // too far right -> align with right edge
                } else if (overRight > 0) {
                    position.left -= overRight;
                    // adjust based on position and margin
                } else {
                    position.left = max(position.left - collisionPosLeft, position.left);
                }
                $.ui.pos._trigger(position, data, 'posCollided', 'fitLeft');
            },
            top: function(position, data) {
                $.ui.pos._trigger(position, data, 'posCollide', 'fitTop');
                var within = data.within,
                    withinOffset = within.isWindow ? within.scrollTop : within.offset.top,
                    outerHeight = data.within.height,
                    collisionPosTop = position.top - data.collisionPosition.marginTop,
                    overTop = withinOffset - collisionPosTop,
                    overBottom = collisionPosTop + data.collisionHeight - outerHeight - withinOffset,
                    newOverBottom;

                // element is taller than within
                if (data.collisionHeight > outerHeight) {
                    // element is initially over the top of within
                    if (overTop > 0 && overBottom <= 0) {
                        newOverBottom = position.top + overTop + data.collisionHeight - outerHeight - withinOffset;
                        position.top += overTop - newOverBottom;
                        // element is initially over bottom of within
                    } else if (overBottom > 0 && overTop <= 0) {
                        position.top = withinOffset;
                        // element is initially over both top and bottom of within
                    } else {
                        if (overTop > overBottom) {
                            position.top = withinOffset + outerHeight - data.collisionHeight;
                        } else {
                            position.top = withinOffset;
                        }
                    }
                    // too far up -> align with top
                } else if (overTop > 0) {
                    position.top += overTop;
                    // too far down -> align with bottom edge
                } else if (overBottom > 0) {
                    position.top -= overBottom;
                    // adjust based on position and margin
                } else {
                    position.top = max(position.top - collisionPosTop, position.top);
                }
                $.ui.pos._trigger(position, data, 'posCollided', 'fitTop');
            }
        },
        flip: {
            left: function(position, data) {
                $.ui.pos._trigger(position, data, 'posCollide', 'flipLeft');
                var within = data.within,
                    withinOffset = within.offset.left + within.scrollLeft,
                    outerWidth = within.width,
                    offsetLeft = within.isWindow ? within.scrollLeft : within.offset.left,
                    collisionPosLeft = position.left - data.collisionPosition.marginLeft,
                    overLeft = collisionPosLeft - offsetLeft,
                    overRight = collisionPosLeft + data.collisionWidth - outerWidth - offsetLeft,
                    myOffset = data.my[0] === "left" ?
                    -data.elemWidth :
                    data.my[0] === "right" ?
                    data.elemWidth :
                    0,
                    atOffset = data.at[0] === "left" ?
                    data.targetWidth :
                    data.at[0] === "right" ?
                    -data.targetWidth :
                    0,
                    offset = -2 * data.offset[0],
                    newOverRight,
                    newOverLeft;

                if (overLeft < 0) {
                    newOverRight = position.left + myOffset + atOffset + offset + data.collisionWidth - outerWidth - withinOffset;
                    if (newOverRight < 0 || newOverRight < abs(overLeft)) {
                        position.left += myOffset + atOffset + offset;
                    }
                } else if (overRight > 0) {
                    newOverLeft = position.left - data.collisionPosition.marginLeft + myOffset + atOffset + offset - offsetLeft;
                    if (newOverLeft > 0 || abs(newOverLeft) < overRight) {
                        position.left += myOffset + atOffset + offset;
                    }
                }
                $.ui.pos._trigger(position, data, 'posCollided', 'flipLeft');
            },
            top: function(position, data) {
                $.ui.pos._trigger(position, data, 'posCollide', 'flipTop');
                var within = data.within,
                    withinOffset = within.offset.top + within.scrollTop,
                    outerHeight = within.height,
                    offsetTop = within.isWindow ? within.scrollTop : within.offset.top,
                    collisionPosTop = position.top - data.collisionPosition.marginTop,
                    overTop = collisionPosTop - offsetTop,
                    overBottom = collisionPosTop + data.collisionHeight - outerHeight - offsetTop,
                    top = data.my[1] === "top",
                    myOffset = top ?
                    -data.elemHeight :
                    data.my[1] === "bottom" ?
                    data.elemHeight :
                    0,
                    atOffset = data.at[1] === "top" ?
                    data.targetHeight :
                    data.at[1] === "bottom" ?
                    -data.targetHeight :
                    0,
                    offset = -2 * data.offset[1],
                    newOverTop,
                    newOverBottom;
                if (overTop < 0) {
                    newOverBottom = position.top + myOffset + atOffset + offset + data.collisionHeight - outerHeight - withinOffset;
                    if ((position.top + myOffset + atOffset + offset) > overTop && (newOverBottom < 0 || newOverBottom < abs(overTop))) {
                        position.top += myOffset + atOffset + offset;
                    }
                } else if (overBottom > 0) {
                    newOverTop = position.top - data.collisionPosition.marginTop + myOffset + atOffset + offset - offsetTop;
                    if ((position.top + myOffset + atOffset + offset) > overBottom && (newOverTop > 0 || abs(newOverTop) < overBottom)) {
                        position.top += myOffset + atOffset + offset;
                    }
                }
                $.ui.pos._trigger(position, data, 'posCollided', 'flipTop');
            }
        },
        flipfit: {
            left: function() {
                $.ui.pos.flip.left.apply(this, arguments);
                $.ui.pos.fit.left.apply(this, arguments);
            },
            top: function() {
                $.ui.pos.flip.top.apply(this, arguments);
                $.ui.pos.fit.top.apply(this, arguments);
            }
        }
    };

    // fraction support test
    (function() {
        var testElement, testElementParent, testElementStyle, offsetLeft, i,
            body = document.getElementsByTagName("body")[0],
            div = document.createElement("div");

        //Create a "fake body" for testing based on method used in jQuery.support
        testElement = document.createElement(body ? "div" : "body");
        testElementStyle = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        };
        if (body) {
            $.extend(testElementStyle, {
                position: "absolute",
                left: "-1000px",
                top: "-1000px"
            });
        }
        for (i in testElementStyle) {
            testElement.style[i] = testElementStyle[i];
        }
        testElement.appendChild(div);
        testElementParent = body || document.documentElement;
        testElementParent.insertBefore(testElement, testElementParent.firstChild);

        div.style.cssText = "position: absolute; left: 10.7432222px;";

        offsetLeft = $(div).offset().left;
        $.support.offsetFractions = offsetLeft > 10 && offsetLeft < 11;

        testElement.innerHTML = "";
        testElementParent.removeChild(testElement);
    })();

}(jQuery));

