/**
 * @preserve Galleria Fullscreen Theme 2012-01-25
 * http://galleria.aino.se
 *
 * Copyright (c) 2011, Aino
 * Copyright (c) 2012, Petri Damstén
 * Licensed under the MIT license.
 *
 * Modified to work with Wordpress galleria-fs by Petri Damstén
 */

/* global jQuery, Galleria */

(function($) {

Galleria.addTheme({
    version: '1.5.4',
    name: 'galleria-fs',
    author: 'Petri Damstén',
    defaults: {
        transition: 'slide',
        thumbCrop:  'height',
        imageCrop: true,
        easing: 'galleriaOut',
        trueFullscreen: false,
        // set this to false if you want to keep the thumbnails:
        _hideDock: Galleria.TOUCH ? false : true,
        // set this to true if you want to shrink the carousel when clicking a thumbnail:
        _closeOnClick: false,
        // set this to false if you want to show the caption all the time:
        _toggleInfo: false
    },
    init: function(options) {
        Galleria.requires(1.5, 'This version of theme requires Galleria 1.5 or later');

        // add some elements
        this.addElement('info-link','info-close');
        this.append({
            'info' : ['info-link','info-close']
        });

        this.addElement('close', 'map', 'map-close');
        this.appendChild('container', 'close');
        this.appendChild('container', 'map');
        this.appendChild('map', 'map-close');

        var timer = 0;
        // cache some stuff
        var info = this.$('info-link,info-close,info-text'),
            close = this.$('close'),
            map   = this.$('map'),
            map_close = this.$('map-close'),
            touch = Galleria.TOUCH;

        // show loader & counter with opacity
        this.$('loader, counter').show().css('opacity', 0.4);

        // some stuff for non-touch browsers
        if (! touch ) {
            this.addIdleState( this.get('image-nav-left'), { left:-50 });
            this.addIdleState( this.get('image-nav-right'), { right:-50 });
            this.addIdleState( this.get('close'), { right:-50 });
            this.addIdleState( this.get('counter'), { opacity:0 });
            this.addIdleState( this.get('info-link'), { opacity:0 });
            this.addIdleState( this.get('info-text'), { opacity:0 });
            this.addIdleState( this.get('thumbnails'), { opacity:0.25 });
        }
        var gallery = this;
        $.each(this._layers, function() {
            gallery.addIdleState(this, {opacity:0});
        });

        // toggle info
        if ( options._toggleInfo === true ) {
            info.bind( 'click:fast', function() {
                info.toggle();
            });
        } else {
            info.show();
            this.$('info-link, info-close').hide();
        }

        map.attr('id', 'galleria-map'); // openlayers needs id
        close.bind('click:fast', function() {
            if ($('#galleria-map').is(":visible")) {
              $('#galleria-map').toggle();
            }
            $('#galleria').toggle();
            fsg_on_close();
        });
        map_close.bind('click:fast', function() {
            $('#galleria-map').toggle();
        });

        // bind some stuff
        if ( touch ) {
          this.bind('image', function(e) {
            clearTimeout(timer);
            timer = setTimeout(function(e) {
              $('.galleria-infolayer').fadeOut(750);
            }, 750);
          });
          $('#galleria').bind('click', function() {
            clearTimeout(timer);
            $('.galleria-infolayer').fadeIn(300);
          });
        }
        this.bind('thumbnail', function(e) {

            if (! touch ) {
                // fade thumbnails
                $(e.thumbTarget).css('opacity', 0.6).parent().hover(function() {
                    $(this).not('.active').children().stop().fadeTo(100, 1);
                }, function() {
                    $(this).not('.active').children().stop().fadeTo(400, 0.6);
                });

                if ( e.index === this.getIndex() ) {
                    $(e.thumbTarget).css('opacity',1);
                }
            } else {
                $(e.thumbTarget).css('opacity', this.getIndex() ? 1 : 0.6).bind('click:fast', function() {
                    $(this).css( 'opacity', 1 ).parent().siblings().children().css('opacity', 0.6);
                });
            }
        });

        var activate = function(e) {
            $(e.thumbTarget).css('opacity',1).parent().siblings().children().css('opacity', 0.6);
        };

        this.bind('loadstart', function(e) {
            if (!e.cached) {
                this.$('loader').show().fadeTo(200, 0.4);
            }
            window.setTimeout(function() {
                activate(e);
            }, touch ? 300 : 0);
            this.$('info').toggle( this.hasInfo() );
        });

        this.bind('loadfinish', function(e) {
            this.$('loader').fadeOut(200);
        });
    }
});

}(jQuery));
