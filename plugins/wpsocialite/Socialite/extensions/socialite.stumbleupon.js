/*!
 * Socialite v2.0 - Extension template
 * http://socialitejs.com
 * Copyright (c) 2011 David Bushell
 * Dual-licensed under the BSD or MIT licenses: http://socialitejs.com/license.txt
 */
(function(window, document, Socialite, undefined)
{
    // External documentation URLs

    // add required default settings
    Socialite.setup({
        'stumbleupon': {
            lang: 'en'
        }
    });

    /**
     * One network can cater for multiple widgets
     * Check the extensions repository to make sure it doesn't already exist
     * The script object is optional for extentions that require simple images or iframes
     */
    Socialite.network('stumbleupon', {
        script: {
            src     : '//platform.stumbleupon.com/1/widgets.js',
            async   : true,
            charset : 'utf-8'
        }
    });

    /**
     * Add a unique widget to the network
     * Socialite will activate elements with a class name of `stumbleupon-widget_name`, e.g. `twitter-share`
     */
    Socialite.widget('stumbleupon', 'share', {

        /**
         * Called when an instance is loaded
         */
        init: function(instance)
        {

            var el = document.createElement('su:badge');
            Socialite.copyDataAttributes(instance.el, el);

            el.setAttribute('layout', instance.el.dataset.layout);
            el.setAttribute('location', instance.el.dataset.url);
            instance.el.appendChild(el);
            if (typeof window.STMBLPN === 'object' && typeof window.STMBLPN.parse === 'function') {
                window.STMBLPN.parse(instance.el);
                Socialite.activateInstance(instance);
            }
        }
    });

})(window, window.document, window.Socialite);
