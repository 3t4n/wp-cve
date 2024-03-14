/* Credit for tab styles - http://tympanus.net/codrops/2014/09/02/tab-styles-inspiration/ */

jQuery(function ($) {

    // Don't do any of this if there are no tabs present here
    if ($('.lsow-tabs').length) {

        $('.lsow-tabs').each(function () {

            var $tabs = $(this);

            new LSOW_Tabs($tabs);

        });
    }

});

var LSOW_Tabs = function ($tabsElement) {

    this.tabs = $tabsElement;

    // tabs elems
    this.tabNavs = $tabsElement.find('.lsow-tab');

    // content items
    this.items = $tabsElement.find('.lsow-tab-pane');

    // show first tab item
    this.show(0);

    // init events
    this.initEvents();

    // make the tab responsive
    this.makeResponsive();
};

LSOW_Tabs.prototype.show = function (index) {

    // Clear out existing tab
    this.tabNavs.removeClass('lsow-active');
    this.items.removeClass('lsow-active');

    this.tabNavs.eq(index).addClass('lsow-active');
    this.items.eq(index).addClass('lsow-active');
};

LSOW_Tabs.prototype.initEvents = function () {

    var self = this;

    this.tabNavs.click(function (event) {

        event.preventDefault();

        var $anchor = jQuery(this).children('a').eq(0);

        var target = $anchor.attr('href').split('#').pop();

        self.show(self.tabNavs.index(jQuery(this)));

        history.pushState ? history.pushState(null, null, "#" + target) : window.location.hash = "#" + target;
    });

};

LSOW_Tabs.prototype.makeResponsive = function () {

    var self = this;

    /* Trigger mobile layout based on an user chosen browser window resolution */
    var mediaQuery = window.matchMedia('(max-width: ' + self.tabs.data('mobile-width') + 'px)');
    if (mediaQuery.matches) {
        self.tabs.addClass('lsow-mobile-layout');
    }
    mediaQuery.addListener(function (mediaQuery) {
        if (mediaQuery.matches)
            self.tabs.addClass('lsow-mobile-layout');
        else
            self.tabs.removeClass('lsow-mobile-layout');
    });

    /* Close/open the mobile menu when a tab is clicked and when menu button is clicked */
    this.tabNavs.click(function (event) {
        event.preventDefault();
        self.tabs.toggleClass('lsow-mobile-open');
    });

    this.tabs.find('.lsow-tab-mobile-menu').click(function (event) {
        event.preventDefault();
        self.tabs.toggleClass('lsow-mobile-open');
    });
};
