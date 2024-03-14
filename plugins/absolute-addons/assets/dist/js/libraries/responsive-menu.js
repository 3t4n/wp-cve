(function ( $, window, document ) {

    function HorizontalScrollList( el, options ) {

        //const ns = 'HSL';

        const defaults = {
            itemWrapSelector: '.items',
            backArrowSelector: '.scroll-backward a',
            forwardArrowSelector: '.scroll-forward a',
            currentItemClass: 'is-active',
            itemsSelector:'.item',
        };

        const settings = $.extend( {}, options, defaults );

        const self = this;
        self.elem = $(el);
        self.settings = settings;
        // set elements.
        self.itemWrap = self.elem.find(settings.itemWrapSelector);

        self.backArrow = self.elem.find(settings.backArrowSelector);
        self.forwardArrow = self.elem.find(settings.forwardArrowSelector);
        self.currentItem = self.elem.find('.'+settings.currentItemClass);
        self.items = self.elem.find(settings.itemsSelector);
    }

    const $helpers = {
        addClass: function (e, t) {
            "function" === typeof e.addClass ? e.addClass(t) : e.classList.add(t);
        },
        removeClass: function (e, t) {
            "function" === typeof e.removeClass ? e.removeClass(t) : e.classList.remove(t);
        },
        hide: function (e) {
            $helpers.addClass(e, "d-none");
        },
        show: function (e) {
            $helpers.removeClass(e, "d-none");
        },
        // hasClass: function (e, t) {
        //     e.classList.contains(t)
        // },
        // toggleClass: function (e, t) {
        //     e.classList.toggle(t)
        // },
        // setValue: function (e, t) {
        //     "string" == typeof e ? els = Array.prototype.slice.call(document.querySelectorAll(e)) : Array.isArray(e) ? els = e : els = Array.of(e),
        //         els.forEach(function (e) {
        //             e.value = t
        //         })
        // },
        // createElement: function (e, t) {
        //     t = t || {};
        //     var i = document.createElement(e);
        //     return t.text && (
        //         i.textContent = t.text, delete t.text
        //     ), t["class"] && (
        //         t["class"].split(" ").forEach(function (e) {
        //             i.classList.add(e)
        //         }), delete t["class"]
        //     ), Object.keys(t).forEach(function (e) {
        //         i.setAttribute(e, t[e])
        //     }),
        //         i
        // }
    };

    if (window.requestAnimationFrame) {
        HorizontalScrollList.requestAnimationFrameWithLeadingCall = function (e, i) {
            var n = false;
            return function () {
                var t = arguments;
                if (!n) {
                    n = true;
                    return e.apply(i, t);
                }
                window.requestAnimationFrame(function () {
                    e.apply(i, t);
                } );
            };
        };
    } else {
        HorizontalScrollList.requestAnimationFrameWithLeadingCall = function (t, e) {
            //return _.throttle(t, 16, e);
            return setTimeout( t, 16, e );
        };
    }

    HorizontalScrollList.requestAnimationFrame = function (t, e) {
        var i = function () {
            t.apply(e);
        };
        window.requestAnimationFrame ? window.requestAnimationFrame(i) : setTimeout(i);
    };

    HorizontalScrollList.prototype.destroy = function() {
        // turn off events.
    };

    HorizontalScrollList.prototype.initialize = function () {
        var self = this;
        self._render = HorizontalScrollList.requestAnimationFrameWithLeadingCall(self._render.bind(this));
        //self.categoryList = document.querySelector("ul.absp-nav-tab"); // wrapper > ul
        //self.backArrow = document.querySelector("a.scroll-backward"); // wrapper > scroll-back
        //self.forwardArrow = document.querySelector("a.scroll-forward");  // wrapper > scroll-forward
        //self.currentCategory = self.categoryList.querySelector("li.is-open");  // active items
        //self.allCategoryLinks = self.categoryList.querySelectorAll("li a"); // all items.
        self.bindEvents();
        self._render();
        self._ensureActiveItemInView();
    };

    HorizontalScrollList.prototype.bindEvents = function () {
        var self = this;
        $(window).on('resize', self._render.bind(self));
        self.itemWrap.on('scroll', self._render.bind(self));
        self.forwardArrow.on('click', self._forwardClicked.bind(self));
        self.backArrow.on('click', self._backClicked.bind(self));
        self.items.on('click', self._itemClicked.bind(self));
    };

    HorizontalScrollList.prototype._render = function () {
        var self = this;
        var t = 0 < self.itemWrap.scrollLeft(),
            e = self.itemWrap.scrollLeft() + self.itemWrap.outerWidth() >= self.itemWrap.get(0).scrollWidth;
        t ? $helpers.show(self.backArrow) : $helpers.hide(self.backArrow);
        e ? $helpers.hide(self.forwardArrow) : $helpers.show(self.forwardArrow);
    };

    HorizontalScrollList.prototype._forwardClicked = function (e) {
        e.preventDefault();
        this.itemWrap.scrollLeft( this.itemWrap.scrollLeft() + this.itemWrap.outerWidth() );
    };

    HorizontalScrollList.prototype._backClicked = function (e) {
        e.preventDefault();
        this.itemWrap.scrollLeft( this.itemWrap.scrollLeft() - this.itemWrap.outerWidth() );
    };

    HorizontalScrollList.prototype._ensureActiveItemInView = function () {
        const self = this;
        if ( self.currentItem.length ) {
            const x = ( self.currentItem.offset().left + self.currentItem.outerWidth() );
            const y = ( self.itemWrap.outerWidth() - self.forwardArrow.outerWidth() );
            if (  x > y ) {
                self.itemWrap.scrollLeft (self.currentItem.offset().left - self.forwardArrow.outerWidth() );
            }
        }
    };

    HorizontalScrollList.prototype._itemClicked = function ( event ) {
        const self = this;
        self.currentItem.removeClass( self.settings.currentItemClass);
        const target = $(event.currentTarget);
        self.currentItem = target.closest('li');
        self.currentItem.addClass( self.settings.currentItemClass);

        const currentItemOffsetLeft = target.offset().left;
        var e = currentItemOffsetLeft + target.outerWidth(),
            i = self.itemWrap.outerWidth() + self.itemWrap.scrollLeft() - self.forwardArrow.outerWidth(),
            n = e - i,
            r = i < e;
        if ( currentItemOffsetLeft - self.backArrow.outerWidth() < self.itemWrap.scrollLeft() ) {
            self.itemWrap.scrollLeft( currentItemOffsetLeft - self.forwardArrow.outerWidth() );
        }
        if ( r ) {
            self.itemWrap.scrollLeft( self.itemWrap.scrollLeft() + n );
        }
    };

    $.fn.HorizontalScrollList = function( options = {} ) {
        $(this).each( function() {
            const scrollList = new HorizontalScrollList( this, options );
            $(this).data('HorizontalScrollList', scrollList );
            scrollList.initialize();
        } );
    };

    $('.scroll-list').HorizontalScrollList();
    $('[href="#"]').on( 'click', e => e.preventDefault() );

    // console.log(HorizontalScrollList);
})(jQuery, window, document );
