var TotalContest;
(function (TotalContest) {
    var Button = (function () {
        function Button(label, callback, cssClass) {
            var template = "<button class=\"button " + (cssClass || '') + "\" style=\"margin-right: 10px;\">" + (label || '') + "</button>";
            var button = jQuery(template);
            button.on('click', callback);
            return button;
        }
        return Button;
    }());
    var PointerSettings = (function () {
        function PointerSettings(args) {
            var template = "<h3>" + (args.title || '') + "</h3><p>" + (args.body || '') + "</p>";
            return jQuery.extend(true, {
                content: template,
                position: {
                    edge: 'left',
                    align: 'left'
                }
            }, args);
        }
        return PointerSettings;
    }());
    var Pointers = (function () {
        function Pointers(args) {
            var _this = this;
            this.pointers = jQuery();
            this.context = 'global';
            this.i18n = {
                done: 'Done',
                next: 'Next',
                previous: 'Previous',
            };
            this.current = false;
            if (args.context) {
                this.context = args.context;
            }
            if (args.i18n) {
                this.i18n = args.i18n;
            }
            if (args.items) {
                jQuery.each(args.items, function (selector, args) {
                    var instance = jQuery(selector)['pointer'](new PointerSettings(args)).data('wpPointer');
                    _this.pointers.push(instance);
                });
            }
        }
        Pointers.prototype.next = function () {
            this.open(this.current + 1);
        };
        Pointers.prototype.previous = function () {
            this.open(this.current - 1);
        };
        Pointers.prototype.start = function () {
            this.open(0);
        };
        Pointers.prototype.open = function (index) {
            if (this.pointers[this.current]) {
                this.pointers[this.current].close();
            }
            if (this.pointers[index]) {
                this.current = parseInt(index);
                var instance = this.pointers.get(this.current);
                instance.open();
                var position = instance.element.offset().top - instance.element.outerHeight() - 100;
                var buttons = instance.content.find('.wp-pointer-buttons');
                buttons.find('.close').on('click', jQuery.proxy(this.dismiss, this));
                if (this.current !== 0) {
                    buttons.append(new Button(this.i18n.previous, jQuery.proxy(this.previous, this)));
                }
                if (this.current < this.pointers.length - 1) {
                    buttons.append(new Button(this.i18n.next, jQuery.proxy(this.next, this), 'button-primary'));
                }
                if (this.current === this.pointers.length - 1) {
                    buttons.append(new Button(this.i18n.done, jQuery.proxy(this.dismiss, this), 'button-primary'));
                }
            }
        };
        Pointers.prototype.dismiss = function () {
            this.pointers[this.current].close();
            jQuery.get(window['ajaxurl'], { action: 'totalcontest_dismiss', type: 'pointer', object: this.context });
        };
        return Pointers;
    }());
    jQuery(function ($) {
        if (window['totalcontestPointers']) {
            var pointers = new Pointers(window['totalcontestPointers']);
            pointers.start();
        }
    });
})(TotalContest || (TotalContest = {}));

//# sourceMappingURL=maps/pointers.js.map
