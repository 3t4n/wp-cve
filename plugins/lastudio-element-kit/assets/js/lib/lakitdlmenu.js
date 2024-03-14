( function( $, window, undefined ) {
    'use strict';

    // global
    const  $body = $( 'body' );

    $.LakitDLMenu = function( options, element ) {
        this.$el = $( element );
        this._init( options );
    };

    // the options
    $.LakitDLMenu.defaults = {
        // classes for the animation effects
        animationClasses : { classin : 'lakitdl-animate-in-1', classout : 'lakitdl-animate-out-1' },
        // callback: click a link that has a sub menu
        onLevelClick : function( el, name ) { return false; },
        backLabel: '',
        triggerIcon: '>',
        // Change to "true" to use the active item as back link label.
        useActiveItemAsBackLabel: false,
        // Change to "true" to add a navigable link to the active item to its child
        // menu.
        useActiveItemAsLink: false,
        // On close reset the menu to root
        resetOnClose: true,
        supportAnimations: true
    };

    $.LakitDLMenu.prototype = {
        _init : function( options ) {

            // options
            this.options = $.extend( true, {}, $.LakitDLMenu.defaults, options );
            // cache some elements and initialize some variables
            this._config();

            // animation end event name
            this.animEndEventName = 'animationend.lakitdlmenu';
            // transition end event name
            this.transEndEventName = 'transitionend.lakitdlmenu';
            // support for css animations and css transitions
            this.supportAnimations = this.options.supportAnimations

            this._initEvents();

        },
        _config : function() {

            const e_id = this.$el.closest('.elementor-element').data('id');

            this.open = false;
            this.$menu = this.$el.children( '.lakit-nav.lakitdl-menu' );
            this.$menuitems = this.$menu.find( '.menu-item:not(.lakitdl-back)' );
            this.$el.find( '.lakitdl-submenu' ).prepend( '<div class="menu-item lakit-nav__item lakit-nav-id-'+ e_id +' lakitdl-back"><button type="button" class="menu-item-link menu-item-link-sub">' + this.options.backLabel + '</button></div>' );
            this.$el.find( '.lakitdl-submenu').parent().append('<button type="button" class="trigger-dlmenu">'+ this.options.triggerIcon +'</button>');
            this.$back = this.$menu.find( '.menu-item.lakitdl-back' );
            this.$itemtrigger = this.$menuitems.find('.trigger-dlmenu');

            // Set the label text for the back link.
            if (this.options.useActiveItemAsBackLabel) {
                this.$back.each(function() {
                    let $this = $(this),
                        parentLabel = $this.parents('.menu-item:first').find('a:first').text();

                    $this.find('a').html(parentLabel);
                });
            }
            // If the active item should also be a clickable link, create one and put
            // it at the top of our menu.
            if (this.options.useActiveItemAsLink) {
                this.$el.find( '.lakit-nav__sub.lakitdl-submenu' ).prepend(function() {
                    const parentli = $(this).parents('.menu-item:not(.lakitdl-back):first').find('a:first');
                    return '<div class="menu-item lakit-nav__item lakitdl-parent"><a class="menu-item-link" href="' + parentli.attr('href') + '">' + parentli.text() + '</a></div>';
                });
            }

        },
        _initEvents : function() {

            const self = this;
            const e_id = this.$el.closest('.elementor-element').data('id');

            this.$itemtrigger.on('click.lakitdlmenu', function (event){
                const $item = $(this).parent(),
                    $submenu = $item.children( '.lakit-nav__sub.lakitdl-submenu' );
                if( $submenu.length > 0 && !$item.hasClass('lakitdl-subviewopen')){

                    const $clone = $('<div/>').addClass('lakit-nav--clone lakit-nav lakit-nav-'+e_id+' lakit-nav-id-'+e_id).css('opacity', 0).append($submenu.clone());
                    $clone.insertAfter( self.$menu );

                    const onAnimationEndFn = function() {
                        self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classout ).addClass( 'lakitdl-subview' );
                        $item.addClass( 'lakitdl-subviewopen' ).parents( '.lakitdl-subviewopen:first' ).removeClass( 'lakitdl-subviewopen' ).addClass( 'lakitdl-subview' );
                        $clone.remove();
                    }

                    $clone.addClass( self.options.animationClasses.classin );
                    self.$menu.addClass( self.options.animationClasses.classout );
                    if( self.supportAnimations ) {
                        self.$menu.on( self.animEndEventName, onAnimationEndFn );
                    }
                    else {
                        setTimeout( function() {
                            onAnimationEndFn.call();
                        }, 300);
                    }
                    self.options.onLevelClick( $item, $item.children( 'a:first' ).text() );
                }
            });

            this.$back.on( 'click.lakitdlmenu', function( event ) {

                const $this = $( this ),
                    $submenu = $this.parents( '.lakit-nav__sub.lakitdl-submenu:first' ),
                    $item = $submenu.parent();


                const $clone = $('<div/>').addClass('lakit-nav--clone lakit-nav lakit-nav-'+e_id+' lakit-nav-id-'+e_id).append($submenu.clone());
                $clone.insertAfter( self.$menu );

                const onAnimationEndFn = function() {
                    self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classin );
                    $clone.remove();
                };

                setTimeout( function() {
                    $clone.addClass( self.options.animationClasses.classout );
                    self.$menu.addClass( self.options.animationClasses.classin );
                    if( self.supportAnimations ) {
                        self.$menu.on( self.animEndEventName, onAnimationEndFn );
                    }
                    else {
                        setTimeout(function (){
                            onAnimationEndFn.call();
                        }, 300)
                    }

                    $item.removeClass( 'lakitdl-subviewopen' );

                    var $subview = $this.parents( '.lakitdl-subview:first' );
                    if( $subview.is( '.menu-item' ) ) {
                        $subview.addClass( 'lakitdl-subviewopen' );
                    }
                    $subview.removeClass( 'lakitdl-subview' );
                } );

                return false;

            } );
        },
        // resets the menu to its original state (first level of options)
        _resetMenu : function() {
            this.$menu.removeClass( 'lakitdl-subview' );
            this.$menuitems.removeClass( 'lakitdl-subview lakitdl-subviewopen' );
        },

        destroyMenu : function (){
            this.$el.removeClass('lakitdl-menuwrapper');
            this.$menu.removeClass('lakitdl-menu lakitdl-menuopen lakitdl-subview');
            this.$menuitems.removeClass('lakitdl-subview lakitdl-subviewopen')
            this.$back.remove();
            this.$itemtrigger.remove();
            this.$el.find('>.lakit-nav__sub').remove();
            this.$menu.off(this.animEndEventName).off(this.transEndEventName);
            this.$el.removeData('lakitdlmenu')
        }
    };

    $.fn.lakitdlmenu = function( options ) {
        this.each(function() {
            let instance = $.data( this, 'lakitdlmenu' );
            if ( instance ) {
                instance._init();
            }
            else {
                instance = $.data( this, 'lakitdlmenu', new $.LakitDLMenu( options, this ) );
            }
        })
        return this;
    };

} )( jQuery, window );