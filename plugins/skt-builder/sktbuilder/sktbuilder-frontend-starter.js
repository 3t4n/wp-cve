/*global jQuery*/
/*jslint browser: true */
(function($) {
    'use strict';

    $.holdReady(true);

    /**
     * The Sktbuilder starter class 
     * Load all JS and CSS files
     *  
     * @version 0.0.1
     * @class  SktbuilderStarter
     */
    function SktbuilderStarter(options) {
        var self = this;

        if (!(options.driver instanceof Object)) {
            throw new Error('Driver parameter mast be set!');
        }

        this.options = options;
        this.options.sktbuilderUrl = this.options.sktbuilderUrl || (window.location.protocol != 'file:' ? (window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + "/") : "") + "sktbuilder/";
        this.options.sktbuilderUrl = this.options.sktbuilderUrl + (this.options.sktbuilderUrl.indexOf("/", this.options.sktbuilderUrl.length - "/".length) !== -1 ? '' : '/');
        var loaderSrc = this.options.sktbuilderUrl + "loader.min.js";
        var script = document.createElement('script');
        script.setAttribute('type', 'text/javascript');
        script.setAttribute('src', loaderSrc);
        script.onload = function() {
            options.driver.loadLibrariesData(function(err, libs) {
                if (err) {
                    console.error("Libraries have been not loaded from driver " + self.options.driver.constructor.name + "." + err);
                    return;
                }
                if (typeof(libs) == 'undefined') {
                    console.error("Libraries have been not loaded from driver " + self.options.driver.constructor.name + ". Check 'loadLibrariesData' method.");
                    return;
                }

                window.loader = new Loader();
                window.loader.once('complete', function() {
                    try {
                        if (window.parent.frames['sktbuilder-iframe']) {
                            $('#sktbuilder-blocks').empty();
                            window.parent.jQuery('#sktbuilder-iframe').trigger('libraries_loaded');
                            //call ready to build block event
                        } else {
                            // console.info('frame not found');
                        }
                    } catch (err) {
                        console.error(err);
                    }
                    $.holdReady(false);
                });
                for (var i = 0; i < libs.length; i++) {

                    var libUrl = libs[i].url.replace(/\/+$/g, '') + "/"; //Trim slashes in the end and add /

                    if (libs[i].res) {
                        var res = libs[i].res;
                        for (var j = 0; j < res.length; j++) {
                            if (res[j].src.indexOf("http://") !== 0 && res[j].src.indexOf("https://") !== 0) {
                                res[j].src = libUrl + res[j].src.replace(/^\/+/g, ''); //Trim slashes in the begining
                            }
                            window.loader.add(res[j]);
                        }
                    }
                }
                window.loader.start();
            });

        };
        script.onerror = function() {
            console.log("Can't load loader.js file at " + self.options.sktbuilderUrl);
        };
        document.head.appendChild(script);
    }

    window.SktbuilderStarter = SktbuilderStarter;
}(jQuery));
