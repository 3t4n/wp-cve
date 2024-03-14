(function ($) {
    NJBAAccordion = function (settings) {
        this.settings = settings;
        this.nodeClass = '.fl-node-' + settings.id;
        this._init();
    };
    NJBAAccordion.prototype = {
        settings: {},
        nodeClass: '',
        _init: function () {
            //$( this.nodeClass + ' .njba-accordion-button' ).css('height', $( this.nodeClass + ' .njba-accordion-button' ).outerHeight() + 'px');
            $(this.nodeClass + ' .njba-accordion-button').on('click', $.proxy(this._buttonClick, this));
            this._openDefaultItem();
            if (location.hash && location.hash.search('njba-accord') !== -1) {
                $(location.hash).find('.njba-accordion-button').trigger('click');
            }
        },
        _buttonClick: function (e) {
            const button = $(e.target).closest('.njba-accordion-button'),
                accordion = button.closest('.njba-accordion'),
                item = button.closest('.njba-accordion-item'),
                allContent = accordion.find('.njba-accordion-content'),
                allIcons = accordion.find('.njba-accordion-button i.njba-accordion-button-icon'),
                content = button.siblings('.njba-accordion-content'),
                icon = button.find('i.njba-accordion-button-icon');
            if (accordion.hasClass('njba-accordion-collapse')) {
                accordion.find('.njba-accordion-item-active').removeClass('njba-accordion-item-active');
                allContent.slideUp('normal');
            }
            if (content.is(':hidden')) {
                item.addClass('njba-accordion-item-active');
                content.slideDown('normal', this._slideDownComplete);
            } else {
                item.removeClass('njba-accordion-item-active');
                content.slideUp('normal', this._slideUpComplete);
            }
        },
        _slideUpComplete: function () {
            const content = $(this),
                accordion = content.closest('.njba-accordion');
            accordion.trigger('fl-builder.njba-accordion-toggle-complete');
        },
        _slideDownComplete: function () {
            const content = $(this),
                accordion = content.closest('.njba-accordion'),
                item = content.parent(),
                win = $(window);
            FLBuilderLayout.refreshGalleries(content);
            if (item.offset().top < win.scrollTop() + 100) {
                $('html, body').animate({
                    scrollTop: item.offset().top - 100
                }, 500, 'swing');
            }
            accordion.trigger('fl-builder.njba-accordion-toggle-complete');
        },
        _openDefaultItem: function () {
            if (typeof this.settings.defaultItem !== 'undefined') {
                const item = $.isNumeric(this.settings.defaultItem) ? (this.settings.defaultItem - 1) : null;
                if (item !== null) {
                    $(this.nodeClass + ' .njba-accordion-button').eq(item).trigger('click');
                }
            }
        }
    };
})(jQuery);
