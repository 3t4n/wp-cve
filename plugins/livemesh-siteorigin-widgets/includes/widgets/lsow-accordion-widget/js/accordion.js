jQuery(function ($) {

    // Don't do any of this if there are no accordions present here
    if ($('.lsow-accordion').length) {

        $('.lsow-accordion').each(function () {

            var accordion = $(this);

            new LSOW_Accordion(accordion);

        });
    }

});

var LSOW_Accordion = function (accordion) {

    // toggle elems
    this.panels = accordion.find('.lsow-panel');

    this.toggle = false;

    this.expanded = false;

    if (accordion.data('toggle') == true)
        this.toggle = true;

    if (accordion.data('expanded') == true)
        this.expanded = true;

    this.current = null;

    // init events
    this.initEvents();
};

LSOW_Accordion.prototype.show = function (panel) {

    if (this.toggle) {
        if (panel.hasClass('lsow-active')) {
            this.close(panel);
        }
        else {
            this.open(panel);
        }
    }
    else {
        // if the panel is already open, close it else open it after closing existing one
        if (panel.hasClass('lsow-active')) {
            this.close(panel);
            this.current = null;
        }
        else {
            this.close(this.current);
            this.open(panel);
            this.current = panel;
        }
    }

};

LSOW_Accordion.prototype.close = function (panel) {

    if (panel !== null) {
        panel.children('.lsow-panel-content').slideUp(300);
        panel.removeClass('lsow-active');
    }

};

LSOW_Accordion.prototype.open = function (panel) {

    if (panel !== null) {
        panel.children('.lsow-panel-content').slideDown(300);
        panel.addClass('lsow-active');
    }

};


LSOW_Accordion.prototype.initEvents = function () {

    var self = this;

    if (this.expanded && this.toggle) {

        // Display all panels
        this.panels.each(function () {

            var panel = jQuery(this);

            self.show(panel);

        });
    }

    this.panels.find('.lsow-panel-title').click(function (event) {

        event.preventDefault();

        var $panel = jQuery(this).parent();

        // Do not disturb existing location URL if you are going to close an accordion panel that is currently open
        if (!$panel.hasClass('lsow-active')) {

            var target = $panel.attr("id");

            history.pushState ? history.pushState(null, null, "#" + target) : window.location.hash = "#" + target;

        }
        else {
            var target = $panel.attr("id");

            if (window.location.hash == '#' + target)
                history.pushState ? history.pushState(null, null, '#') : window.location.hash = "#";
        }

        self.show($panel);

    });
};

