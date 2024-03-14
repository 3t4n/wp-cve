(function ($) {
    NJBAAnimation = function (settings) {
        this.settings = settings;
        this.animation = settings.animation;
        this.animation_delay = settings.animation_delay;
        this.viewport_position = settings.viewport_position;
        // console.log( this.animation );
        if (this.animation !== 'no') {
            this.nodeClass = '.fl-node-' + settings.id;
            this._initAnimations();
        }
    };
    NJBAAnimation.prototype = {
        settings: {},
        nodeClass: '',
        animation: '',
        animation_delay: 0,
        viewport_position: 90,
        /*
         * @method _initAnimations
         */
        _initAnimations: function () {
            if (typeof jQuery.fn.waypoint !== 'undefined' /*&& !FLBuilderLayout._isMobile()*/) {
                $(this.nodeClass).waypoint({
                    offset: this.viewport_position + '%',
                    handler: $.proxy(this._executeAnimation, this) //this._executeAnimation
                });
            }
        },

        /**
         * @method _executeAnimation
         */
        _executeAnimation: function (e) {
            const module = $(this.nodeClass).find('.njba-image-seperator-main'),
                animation_class = this.animation,
                delay = parseInt(this.animation_delay);
            if (delay > 0) {
                setTimeout(function () {
                    module.addClass(animation_class);
                }, delay * 1000);
            } else {
                module.addClass(animation_class);
            }
        },
    };

})(jQuery);
