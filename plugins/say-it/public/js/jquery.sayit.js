//Based on https://github.com/jquery-boilerplate/jquery-boilerplate
;( function( $, window, document, undefined ) {
    "use strict";
    
    // Global object for the Google Audio player 
    window.streamAudio = null;

    // The actual plugin constructor
    function Plugin ( element ) {
        this.el = $(element);
        this.text = this.el.attr('data-say-content') || this.el.text();
        this.mp3_src = this.el.attr('data-mp3-file');
        this.mode = this.el.attr('data-mode');
        this.tooltip = this.el.attr('data-tooltip');
        this.msg = null;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend( Plugin.prototype, {
        init: function() {
            this.addTooltip();
            if(this.mp3_src && this.mode !== 'html5'){
                this.initMp3file();
            }else{
                this.initHTML5Speak();
            }
        },
        addTooltip: function() {
            if(this.tooltip){
                this.el.append('<span class="sayit-tooltip">'+this.tooltip+'</span>')
            }
        },
        initHTML5Speak: function() {
            this.msg = new SpeechSynthesisUtterance();

            // Set the Speech
            this.msg.text = this.el.attr('data-say-content') || this.el.text();
            this.msg.rate = this.el.attr('data-speed') || 1;
            this.msg.lang = this.el.attr('data-lang') || 'en-US';

            // Bind function
            this.msg.onstart = (e) => this.el.addClass('active');
            this.msg.onend = (e) => this.el.removeClass('active');
            
            this.el.on('click', this.HTML5Speak.bind(this));
        },
        initMp3file: function() {
            this.el.on('click', this.AltMp3Speak.bind(this));
        },
        AltMp3Speak: function(e) {
            e.stopPropagation();
            if(window.streamAudio){
                window.streamAudio.pause();
            }
            window.streamAudio = new Audio(this.mp3_src);
            window.streamAudio.onplay = () => this.el.addClass('active');
            window.streamAudio.onended = () => this.el.removeClass('active');
            window.streamAudio.onpause = () => this.el.removeClass('active');
            window.streamAudio.play();
            return;
        },
        HTML5Speak: function(e) {
            e.stopPropagation();
            // Speacking before ? Stop !
            if(speechSynthesis.speaking) speechSynthesis.cancel()        
            // Queue this utterance.
            speechSynthesis.speak(this.msg);
        }
        
    } );

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn.sayIt = function( options ) {
        let streamAudio = null;
        return this.each( function() {
            if ( !$.data( this, "plugin_sayIt" ) ) {
                $.data( this, "plugin_sayIt", new Plugin( this, options, streamAudio ) );
            }
        } );
    };

    function bulkLoadMP3(callback){
        var toBeLoaded = $('.sayit:not([data-mp3-file])');

        var wordsArray = toBeLoaded.map(function(){
            return $(this).attr('data-say-content') || $(this).text();
        }).get();

        if(wordsArray.length <= 0){
            return callback();
        }

        wp.ajax.post( "sayit_mp3_bulk", {words: wordsArray} )
            .done(function(response) {
                console.log(response);
                toBeLoaded.each(function(index){
                    $(this).attr('data-mp3-file', response[index]);
                });
                callback();
            }).fail(function() {
                console.log("erreur de récupération de mp3 sayit");
                callback();
            });
    }
    
    $(function(){
        bulkLoadMP3(function(){
            $('.sayit').sayIt();
        });

        $('body').on('click', function(){
            if(speechSynthesis.speaking) speechSynthesis.cancel()
            if(window.streamAudio) window.streamAudio.pause();
        })
    })


} )( jQuery, window, document );