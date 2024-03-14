;(function ($) {
    NjbaFBPageModule = function (settings) {
        this.id = settings.id;
        this.node = $('.fl-node-' + settings.id)[0];
        this.settings = settings;
        this._init();
    };
    NjbaFBPageModule.prototype = {
        id: '',
        node: '',
        settings: {},
        _init: function () {
            this._initSDK();
            this._parse();
            $('body').delegate('.fl-builder-njba-facebook-page-settings #fl-field-layout select', 'change', $.proxy(this._change, this));
        },
        _initSDK: function () {
            if ($('#fb-root').length === 0) {
                $('body').prepend('<div id="fb-root"></div>');
            }
            const d = document, s = 'script', id = 'facebook-jssdk';
            let js;
            const fjs = d.getElementsByTagName(s)[0];

            if (d.getElementById(id)) return;

            js = d.createElement(s);
            js.id = id;
            js.src = this.settings.sdkUrl;
            fjs.parentNode.insertBefore(js, fjs);
        },
        _change: function (e) {
            e.stopPropagation();

            const node = this.node,
                tabs = $('.fl-builder-njba-facebook-page-settings #fl-field-layout select').val();
            $(node).find('.fb-page').attr({
                'data-tabs': tabs
            });
            this._parse();
        },
        _parse: function () {
            const node = this.node;
            if ('undefined' !== typeof FB) {
                FB.XFBML.parse(node);
            }
        }
    };
})(jQuery);
