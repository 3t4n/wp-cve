/**
 * Front-end JS logic
 */

"use strict";

var startEditor = startEditor || {};

(function ($) {

    startEditor = $.extend(startEditor, {
        settings: {
            body: null,
            hElements: '*:not(a):not(p):not(em):not(form)' +
            ':not(input):not(strong):not(li):not(ul)' +
            ':not(h1):not(h2):not(h3):not(h4):not(h5):not(h6)' +
            ':not(textarea):not(span):not(time):not(small)',
            hoverElements: null
        },
        onClickDisableLinks: function (e) {
            if (startEditor.settings.body.hasClass('sq-element-selecting')) {
                e.preventDefault();
                return false;
            }
        },
        start: function () {
            startEditor.settings.hElements += ':not(.before-zone-exist):not(.zone-exist-wrap):not(.after-zone-exist)';
            startEditor.settings.body = $('body');
            startEditor.settings.hoverElements = $(startEditor.settings.hElements);
            startEditor.settings.hoverElements.on('click', startEditor.onClickAll);

            startEditor.settings.body.on('click', '.zone-before-action', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var zoneUuid = this.getAttribute('data-zone-id');

                if (zoneUuid) {
                    var event = new CustomEvent('add-zone-before', {detail: zoneUuid});
                    document.dispatchEvent(event);
                }
            });

            startEditor.settings.body.on('click', '.zone-after-action', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var zoneUuid = this.getAttribute('data-zone-id');

                if (zoneUuid) {
                    var event = new CustomEvent('add-zone-after', {detail: zoneUuid});
                    document.dispatchEvent(event);
                }
            });
        },
        init: function () {
            startEditor.settings.body.addClass('sq-element-selecting');
            startEditor.settings.body.on('click', '*', startEditor.onClickDisableLinks);
            startEditor.settings.hoverElements.on({
                mouseover: startEditor.onMouseEnter,
                mouseout: startEditor.onMouseLeave
            });

            return false;
        },
        onMouseEnter: function (e) {
            if (!startEditor.isHoverEl(e.target)) {
                return false;
            } else {
                $(".sq-el-hover").removeClass("sq-el-hover");
                $(e.target).addClass("sq-el-hover");
            }
        },
        onMouseLeave: function (e) {
            if (!startEditor.isHoverEl(e.target)) {
                return false;
            } else {
                $(this).removeClass("sq-el-hover");
            }
        },
        onClickAll: function (e) {
            if (!startEditor.isHoverEl(e.target)) {
                return;
            }
            e.stopPropagation();

            var event;
            if (document.createEvent) {
                event = document.createEvent("HTMLEvents");
                event.initEvent("select-zone-xpath", true, true);
            } else {
                event = document.createEventObject();
                event.eventType = "select-zone-xpath";
            }

            event.eventName = "select-zone-xpath";

            if (document.createEvent) {
                e.target.dispatchEvent(event);
            } else {
                e.target.fireEvent("on" + event.eventType, event);
            }

            startEditor.highlightElement(e.target);

        },
        highlightElement: function (target) {
            $('.selected-el').removeClass('selected-el');
            $(target).addClass('selected-el');
        },
        isHoverEl: function (e) {
            if (!startEditor.settings.body.hasClass('sq-element-selecting')) {
                return false;
            } else if ($(e).width() < 200) {
                return false;
            } else if ($(e).parents('.sq-popup').length) {
                return false;
            }

            return true;
        },
        stop: function () {
            startEditor.settings.body.removeClass('sq-element-selecting');

            return false;
        },
        xpath: function (el) {
            if (typeof el === "string") {
                return document.evaluate(el, document, null, 0, null)
            }

            if (!el || el.nodeType !== 1) return '';
            if (el.id) return "//*[@id='" + el.id + "']";

            var sames = [].filter.call(el.parentNode.children, function (x) {
                return x.tagName === el.tagName
            });

            return startEditor.xpath(el.parentNode) + '/' + el.tagName.toLowerCase() + (sames.length > 1 ? '[' + ([].indexOf.call(sames, el) + 1) + ']' : '')
        }
    });

})(jQuery);
