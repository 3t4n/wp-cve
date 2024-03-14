"use strict";
(function ( $ ){

    // Global Variables
    var cbPublicIsMobile = true === /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? '1' : '0';
    var $instance = null;

    function CbParallaxPublic (){
        this.document = $(document);
        this.window = $(window);
        this.html = $('html');
        this.body = $('body');
        this.adminbar = $('#wpadminbar');
        this.editorContainer = $('#wp-content-editor-container');

        this.cbParallax = Cb_Parallax_Public;

        this.defaultOptions = {
            background_image_url: '',
            background_color: '',
            position_x: 'center',
            position_y: 'center',
            background_attachment: 'fixed',

            can_parallax: '0',
            parallax_enabled: '0',
            direction: 'vertical',
            vertical_scroll_direction: 'top',
            horizontal_scroll_direction: 'left',
            horizontal_alignment: 'center',
            vertical_alignment: 'center',
            overlay_image: '',
            image_width: $(window).innerWidth(),
            image_height: $(window).innerHeight(),
            overlay_path: '',
            overlay_opacity: '0.3',
            overlay_color: ''
        };
        this.direction = undefined !== this.cbParallax.image_options.direction ? this.cbParallax.image_options.direction : this.defaultOptions.direction;
        this.verticalScrollDirection = undefined !== this.cbParallax.image_options.vertical_scroll_direction ? this.cbParallax.image_options.vertical_scroll_direction : this.defaultOptions.vertical_scroll_direction;
        this.horizontalScrollDirection = undefined !== this.cbParallax.image_options.horizontal_scroll_direction ? this.cbParallax.image_options.horizontal_scroll_direction : this.defaultOptions.horizontal_scroll_direction;
        this.horizontalAlignment = undefined !== this.cbParallax.image_options.horizontal_alignment ? this.cbParallax.image_options.horizontal_alignment : this.defaultOptions.horizontal_alignment;
        this.verticalAlignment = undefined !== this.cbParallax.image_options.vertical_alignment ? this.cbParallax.image_options.vertical_alignment : this.defaultOptions.vertical_alignment;
        //
        this.parallaxEnabled = undefined !== this.cbParallax.plugin_options.parallax_enabled ? this.cbParallax.plugin_options.parallax_enabled : '0';
        this.allowOverride = undefined !== this.cbParallax.plugin_options.allow_override ? this.cbParallax.plugin_options.allow_override : '0';
        this.disableOnMobile = undefined !== this.cbParallax.plugin_options.disable_on_mobile ? this.cbParallax.plugin_options.disable_on_mobile : '0';
        //
        this.canParallax = undefined !== this.cbParallax.image_options.can_parallax ? this.cbParallax.image_options.can_parallax : '0';
        this.scrolling = {preserved: undefined !== this.cbParallax.plugin_options.preserve_scrolling ? this.cbParallax.plugin_options.preserve_scrolling : '0'};
        this.image = {
            src: undefined !== this.cbParallax.image_options.background_image_url ? this.cbParallax.image_options.background_image_url : this.defaultOptions.background_image_url,
            backgroundColor: undefined !== this.cbParallax.image_options.background_color ? this.cbParallax.image_options.background_color : this.defaultOptions.background_color,
            positionX: undefined !== this.cbParallax.image_options.position_x ? this.cbParallax.image_options.position_x : this.defaultOptions.position_x,
            positionY: undefined !== this.cbParallax.image_options.position_y ? this.cbParallax.image_options.position_y : this.defaultOptions.position_y,
            backgroundAttachment: undefined !== this.cbParallax.image_options.background_attachment ? this.cbParallax.image_options.background_attachment : this.defaultOptions.background_attachment,
            backgroundRepeat: undefined !== this.cbParallax.image_options.background_repeat ? this.cbParallax.image_options.background_repeat : this.defaultOptions.background_repeat,

            width: undefined !== this.cbParallax.image_data.image_width ? this.cbParallax.image_data.image_width : this.defaultOptions.image_width,
            height: undefined !== this.cbParallax.image_data.image_height ? this.cbParallax.image_data.image_height : this.defaultOptions.image_height
        };
        this.overlay = {
            path: undefined !== this.cbParallax.overlay_options.overlay_path ? this.cbParallax.overlay_options.overlay_path : this.defaultOptions.overlay_path,
            image: undefined !== this.cbParallax.overlay_options.overlay_image ? this.cbParallax.overlay_options.overlay_image : this.defaultOptions.overlay_image,
            opacity: undefined !== this.cbParallax.overlay_options.overlay_opacity ? this.cbParallax.overlay_options.overlay_opacity : this.defaultOptions.overlay_opacity,
            color: undefined !== this.cbParallax.overlay_options.overlay_color ? this.cbParallax.overlay_options.overlay_color : this.defaultOptions.overlay_color
        };

        this.imageAspectRatio = this.getBackgroundImageAspectRatio();
        this.context = null;
        this.canvas = null;
        this.canvasElement = null;
        this.img = null;
        this.canvas = null;
        this.parallaxfactor = 1.2;
        $instance = this;
    }

    CbParallaxPublic.prototype = {

        init: function (){
            this.imports = {polyfill: polyfill};
            this.imports = {requestAnimationFrame: requestAnimationFrame};
            window.__forceSmoothScrollPolyfill__ = true;
            polyfill();
            this.initBackground();
            this.addEvents();
        },

        initBackground: function (){
            if ('parallax' === this.getBackgroundMode()){

                this.setupCanvas();
                //this.setupCanvasJS();

                var $this = this;
                window.onload = function (){
                    var canvasDim = $this.getCanvasDimensions();
                    $this.canvas = document.getElementById('cb_parallax_canvas');
                    $this.context = $this.canvas.getContext('2d');
                    $this.img = new Image();
                    $this.img.onload = function (){
                        $this.context.drawImage(this, 0, 0, canvasDim.x, canvasDim.y);

                        $this.initParallaxBackgroundImage();
                    };
                    $this.img.src = $this.image.src;
                };
            } else{
                this.initStaticBackgroundImage();
            }
        },

        getBackgroundMode: function (){
            var mode = 'static';

            if ('1' === this.parallaxEnabled && '1' === this.canParallax){
                mode = 'parallax';
            }
            if ('1' === cbPublicIsMobile && '1' === this.disableOnMobile){
                mode = 'static';
            }

            return mode;
        },

        addEvents: function (){
            if ('1' === this.canParallax && '1' === this.parallaxEnabled){
                this.window.on('scroll', {context: this}, this.parallaxOnScroll);
                this.window.on('resize', {context: this}, this.parallaxOnResize);
            } else{
                this.window.on('resize', {context: this}, this.staticBackgroundImageOnResize);
            }
        },

        initParallaxBackgroundImage: function (){
            this.setupOverlay();
            this.addCss();
            this.updateCanvasAlignment();
            this.updateParallaxAxis();
            // We call this one once from here so everything is Radiohead, Everything in its right place :)
            this.doParallaxTranslate3DTransform(this.getParallaxTransform());
        },

        setupCanvas: function (){
            var canvasDim = this.getCanvasDimensions();

            this.body.prepend('<canvas id="cb_parallax_canvas" class="custom-background" width="' + canvasDim.x + '" height="' + canvasDim.y + '" style="background-color: ' + this.image.backgroundColor + '"></canvas>');
            this.canvasElement = $('#cb_parallax_canvas');
        },

        setupCanvasJS: function (){
            var canvasDim = this.getCanvasDimensions();
            document.body.insertAdjacentHTML('afterbegin', '<canvas id="cb_parallax_canvas" class="custom-background" width="' + canvasDim.x + '" height="' + canvasDim.y + '" style="background-color: ' + this.image.backgroundColor + '"></canvas>');
            this.canvasElement = $('#cb_parallax_canvas');
        },

        setupOverlay: function (){
            document.body.insertAdjacentHTML('afterbegin', '<div id="cb_parallax_overlay"></div>');
            this.overlayContainer = $('#cb_parallax_overlay');

            if ('0.0' !== this.overlay.opacity && 'none' !== this.overlay.opacity){
                this.overlayContainer.css({
                    'opacity': this.overlay.opacity
                });
            }
            if ('' !== this.overlay.color){
                this.overlayContainer.css({
                    'background-color': this.overlay.color
                });
            }
            if ('none' !== this.overlay.image){
                this.overlayContainer.css({
                    'background': 'url(' + this.overlay.path + this.overlay.image + ')'
                });
            }
        },

        parallaxOnScroll: function ( event ){
            var $this = this;

            if (undefined !== event){
                $this = event.data.context;
            }
            $this.isScrolling = '1';
            requestAnimationFrame($this.scrollParallaxBackgroundImage);
        },

        scrollParallaxBackgroundImage: function (){
            if ('1' === $instance.isScrolling){
                $instance.doParallaxTranslate3DTransform($instance.getParallaxTransform());
            }
            $instance.isScrolling = '0';
            requestAnimationFrame($instance.scrollParallaxBackgroundImage);
        },

        parallaxOnResize: function ( event ){
            var $this = this;

            if (undefined !== event){
                $this = event.data.context;
            }
            $this.isResizing = '1';
            requestAnimationFrame($instance.resizeParallaxBackgroundImage);
        },

        resizeParallaxBackgroundImage: function (){
            if ('1' === $instance.isResizing){
                $instance.updateCanvasDimensions();
                $instance.updateCanvasAlignment();
                $instance.doParallaxTranslate3DTransform($instance.getParallaxTransform());
                $instance.updateParallaxAxis();
            }
            $instance.isResizing = '0';
            requestAnimationFrame($instance.resizeParallaxBackgroundImage);
        },

        getBackgroundImageHorizontalAlignment: function (){
            var posX = null;
            var canvasDim = this.getCanvasDimensions();
            var aspectRatio = this.getViewportAspectRatio();
            var landscape = aspectRatio >= this.imageAspectRatio ? '1' : '0';
            var portrait = aspectRatio < this.imageAspectRatio ? '1' : '0';

            if ('vertical' === this.direction){
                if ('to top' === this.verticalScrollDirection){
                    if ('1' === landscape){
                        switch (this.horizontalAlignment){
                            case 'left':
                                posX = '0';
                                break;
                            case 'center':
                                posX = ($(window).innerWidth() / 2) - (canvasDim.x / 2);
                                break;
                            case 'right':
                                posX = $(window).innerWidth() - canvasDim.x;
                                break;
                        }
                        return parseInt(posX) + 'px';
                    } else if ('1' === portrait){
                        switch (this.horizontalAlignment){
                            case 'left':
                                posX = '0';
                                break;
                            case 'center':
                                posX = ($(window).innerWidth() / 2) - (canvasDim.x / 2);
                                break;
                            case 'right':
                                posX = $(window).innerWidth() - canvasDim.x;
                                break;
                        }
                        return parseInt(posX) + 'px';
                    }
                } else if ('to bottom' === this.verticalScrollDirection){
                    if ('1' === landscape){
                        switch (this.horizontalAlignment){
                            case 'left':
                                posX = '0';
                                break;
                            case 'center':
                                posX = ($(window).innerWidth() / 2) - (canvasDim.x / 2);
                                break;
                            case 'right':
                                posX = $(window).innerWidth() - canvasDim.x;
                                break;
                        }
                        return parseInt(posX) + 'px';
                    } else if ('1' === portrait){
                        switch (this.horizontalAlignment){
                            case 'left':
                                posX = '0';
                                break;
                            case 'center':
                                posX = ($(window).innerWidth() / 2) - (canvasDim.x / 2);
                                break;
                            case 'right':
                                posX = $(window).innerWidth() - canvasDim.x;
                                break;
                        }
                        return parseInt(posX) + 'px';
                    }
                }
            }
        },

        updateCanvasAlignment: function (){
            if ('vertical' === this.direction){
                this.canvasElement.css({'left': this.getBackgroundImageHorizontalAlignment()});
            } else if ('horizontal' === this.direction){
                this.canvasElement.css({'top': this.getBackgroundImageVerticalAlignment()});
            }
        },

        getBackgroundImageVerticalAlignment: function (){
            var posY = null;
            var canvasDim = this.getCanvasDimensions();
            var aspectRatio = this.getViewportAspectRatio();
            var landscape = aspectRatio >= this.imageAspectRatio ? '1' : '0';
            var portrait = aspectRatio < this.imageAspectRatio ? '1' : '0';

            if ('horizontal' === this.direction){
                if ('to the left' === this.horizontalScrollDirection){
                    if ('1' === landscape){
                        switch (this.verticalAlignment){
                            case 'top':
                                posY = '0';
                                break;
                            case 'center':
                                //posY = -( canvasDim.y / 2 ) - ($( window ).innerHeight() / 2 );
                                posY = ($(window).innerHeight() / 2) - (canvasDim.y / 2);
                                break;
                            case 'bottom':
                                posY = $(window).innerHeight() - canvasDim.y;
                                break;
                        }
                        return parseInt(posY) + 'px';
                    } else if ('1' === portrait){
                        switch (this.verticalAlignment){
                            case 'top':
                                posY = '0';
                                break;
                            case 'center':
                                posY = ($(window).innerHeight() / 2) - (canvasDim.y / 2);
                                //posY = - ( ( canvasDim.y / 2 ) - $( window ).innerHeight() / 2 ) / 2;
                                break;
                            case 'bottom':
                                posY = -$(window).innerHeight() + canvasDim.y;
                                break;
                        }
                        return parseInt(posY) + 'px';
                    }
                } else if ('to the right' === this.horizontalScrollDirection){
                    if ('1' === landscape){
                        switch (this.verticalAlignment){
                            case 'top':
                                posY = '0';
                                break;
                            case 'center':
                                //posY = ( $( window ).innerHeight() / 2 ) - ( canvasDim.y / 2 );
                                posY = ($(window).innerHeight() / 2) - (canvasDim.y / 2);
                                break;
                            case 'bottom':
                                posY = $(window).innerHeight() - canvasDim.y;
                                break;
                        }
                        return parseInt(posY) + 'px';
                    } else if ('1' === portrait){
                        switch (this.verticalAlignment){
                            case 'top':
                                posY = '0';
                                break;
                            case 'center':
                                //posY = - ( canvasDim.y / 2 ) + ($( window ).innerHeight() / 2 );
                                posY = ($(window).innerHeight() / 2) - (canvasDim.y / 2);
                                break;
                            case 'bottom':
                                posY = $(window).innerHeight() - canvasDim.y;
                                break;
                        }
                        return parseInt(posY) + 'px';
                    }
                }
            }
        },

        updateParallaxAxis: function (){
            if ('vertical' === this.direction){
                if ('to bottom' === this.verticalScrollDirection){
                    this.canvasElement.css({
                        'position': 'fixed',
                        'top': this.getBackgroundImageVerticalPositionInPx()
                    });
                }
            } else if ('horizontal' === this.direction){
                this.canvasElement.css({
                    'position': 'fixed',
                    'left': this.getBackgroundImageHorizontalPositionInPx()
                });
            }
        },

        getParallaxTransform: function (){
            var transform = {
                x: null,
                y: null
            };
            var ratio = this.getParallaxScrollRatio();
            var scrollingPosition = $(window).scrollTop();

            // Determines the values for the transformation.
            if ('vertical' === this.direction){
                if ('to top' === this.verticalScrollDirection){
                    transform.x = 0;
                    transform.y = -scrollingPosition * ratio;
                } else if ('to bottom' === this.verticalScrollDirection){
                    transform.x = 0;
                    transform.y = scrollingPosition * ratio;
                }
            } else if ('horizontal' === this.direction){
                if ('to the left' === this.horizontalScrollDirection){
                    transform.x = -scrollingPosition * ratio;
                    transform.y = 0;
                } else if ('to the right' === this.horizontalScrollDirection){
                    transform.x = scrollingPosition * ratio;
                    transform.y = 0;
                }
            }
            return transform;
        },

        getParallaxScrollRatio: function (){
            var canvasDim = this.getCanvasDimensions();
            var documentOffsetX = null;
            var imageOffsetY = null;
            var imageOffsetX = null;
            var ratio = null;

            if ('vertical' === this.direction){
                documentOffsetX = $(document).innerHeight() - $(window).innerHeight();
                imageOffsetY = canvasDim.y - $(window).height();
                ratio = (imageOffsetY / documentOffsetX);
            } else if ('horizontal' === this.direction){
                documentOffsetX = $(document).innerHeight() - $(window).innerHeight();
                imageOffsetX = canvasDim.x - $(window).innerWidth();
                ratio = (imageOffsetX / documentOffsetX);
            }
            return ratio;
        },

        doParallaxTranslate3DTransform: function ( transform ){
            this.canvasElement.css({
                '-webkit-transform': 'translate3d(' + transform.x + 'px, ' + transform.y + 'px, 0px)',
                '-moz-transform': 'translate3d(' + transform.x + 'px, ' + transform.y + 'px, 0px)',
                '-ms-transform': 'translate3d(' + transform.x + 'px, ' + transform.y + 'px, 0px)',
                '-o-transform': 'translate3d(' + transform.x + 'px, ' + transform.y + 'px, 0px)',
                'transform': 'translate3d(' + transform.x + 'px, ' + transform.y + 'px, 0px)'
            });
        },

        getViewportDimensions: function (){
            return {height: $(window).innerHeight(), width: $(window).innerWidth()};
        },

        getCanvasDimensions: function (){
            var displayRatio = this.window.innerWidth() / this.window.innerHeight();
            var imageRatio = this.image.width / this.image.height;
            var imageHeight = 0;
            var imageWidth = 0;

            if ('vertical' === this.direction){

                if (imageRatio < displayRatio){
                    imageWidth = this.window.innerWidth();
                    imageHeight = imageWidth / this.image.width * this.image.height;
                    return {x: (imageWidth).toFixed(0), y: (imageHeight).toFixed(0)};
                } else{
                    imageHeight = this.window.innerHeight() * this.parallaxfactor;
                    imageWidth = imageHeight / this.image.height * this.image.width;
                    return {x: (imageWidth).toFixed(0), y: (imageHeight).toFixed(0)};
                }
            }
            if ('horizontal' === this.direction){

                if (imageRatio < displayRatio){
                    imageWidth = this.window.innerWidth() * this.parallaxfactor;
                    imageHeight = imageWidth / this.image.width * this.image.height;
                    return {x: (imageWidth).toFixed(0), y: (imageHeight).toFixed(0)};
                } else{
                    imageHeight = this.window.innerHeight();
                    imageWidth = imageHeight / this.image.height * this.image.width;
                    return {x: (imageWidth).toFixed(0), y: (imageHeight).toFixed(0)};
                }
            }
        },

        updateCanvasDimensions: function (){
            var canvasDim = this.getCanvasDimensions();

            this.canvasElement.width(parseInt(canvasDim.x));
            this.canvasElement.height(parseInt(canvasDim.y));
        },

        initStaticBackgroundImage: function (){
            this.setupOverlay();
            this.addCss();
            this.setupStaticBackgroundImageContainer();
        },

        setupStaticBackgroundImageContainer: function (){
            var canvasDim = this.getStaticBackgroundImageDimensions();

            this.body.css({
                'background': 'url(' + this.image.src + ')',
                'background-size': canvasDim.width + 'px' + ' ' + canvasDim.height + 'px',
                'background-position': this.image.positionX + ' ' + this.image.positionY,
                'background-attachment': this.image.backgroundAttachment,
                'background-repeat': this.image.backgroundRepeat
            });
        },

        staticBackgroundImageOnResize: function ( event ){
            var $this = this;

            if (undefined !== event){
                $this = event.data.context;
            }
            $this.isResizing = '1';
            requestAnimationFrame($instance.updateStaticBackgroundImageAlignment);
        },

        updateStaticBackgroundImageAlignment: function (){
            if ('1' === $instance.isResizing){
                $instance.updateStaticBackgroundImageDimensions();
            }
            $instance.isResizing = '0';
            requestAnimationFrame($instance.updateStaticBackgroundImageAlignment);
        },

        updateStaticBackgroundImageDimensions: function (){
            var canvasDim = this.getStaticBackgroundImageDimensions();

            this.body.css({'background-size': canvasDim.width + 'px' + ' ' + canvasDim.height + 'px'});
        },

        getStaticBackgroundImageDimensions: function (){
            var viewportSize = this.getViewportDimensions();
            var canvasDim = {};

            if (this.getViewportAspectRatio() >= this.imageAspectRatio){
                // Landscape
                canvasDim.width = viewportSize.width;
                canvasDim.height = canvasDim.width / this.imageAspectRatio;
                return (canvasDim);
            } else{
                // Portrait
                canvasDim.height = viewportSize.height;
                canvasDim.width = canvasDim.height * this.imageAspectRatio;
                return canvasDim;
            }

        },

        addCss: function (){
            this.body.removeClass('custom-background');
            this.body.removeProp('background-image');
        },

        getBackgroundImageHorizontalPositionInPx: function (){
            var posX = null;
            var canvasDimensions = this.getCanvasDimensions();

            if ('to the left' === this.horizontalScrollDirection){
                posX = '0';
                return parseInt(posX) + 'px';
            } else if ('to the right' === this.horizontalScrollDirection){
                posX = $(window).innerWidth() - canvasDimensions.x;
                return parseInt(posX) + 'px';
            }
        },

        getBackgroundImageVerticalPositionInPx: function (){
            var posY = null;
            var canvasDimensions = this.getCanvasDimensions();

            if ('to top' === this.verticalScrollDirection){
                posY = '0';
                return parseInt(posY) + 'px';
            } else if ('to bottom' === this.verticalScrollDirection){
                posY = $(window).innerHeight() - canvasDimensions.y;
                return parseInt(posY) + 'px';
            }
        },

        getBackgroundImageAspectRatio: function (){
            return this.image.width / this.image.height;
        },

        getViewportAspectRatio: function (){
            return $(window).innerWidth() / $(window).innerHeight();
        }

    };

    $(document).one('ready', function (){
        if ('none' !== Cb_Parallax_Public.image_source.source){
            var cbParallaxPublic = new CbParallaxPublic();
            cbParallaxPublic.init();
        }
    });

})(jQuery);
