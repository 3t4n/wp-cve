/**
 * Created by pavel on 30.05.2016.
 */
jQuery( function ( $ ) {

    if(!window.ajaxurl){
        window.ajaxurl = window.location.origin +'/wp-admin/admin-ajax.php';
    }

    if ( typeof window.DM === 'undefined' ) {
        window.DM = {};
    }

    window.DM.aliExtension = (function () {
        var $this;

        var options = {
            sleep : 5000,
            method : 'ajax'
        };

        var stageLoaderPages = {
            active : false,
            stack : [],
            _observers : [],
            current : null
        };

        function htmlToObj( html ) {
            var div = $( '<div></div>' );
            return $( div ).append( html );
        }

        function getPage( link ) {
            window.postMessage( { type : "requestHtml", method: options.method, url : link }, "*" );
        }

        function b64DecodeUnicode( str ) {
            return str ? window.Base64.decode( str ) : false;
        }

        function addStack( link, observer, context, index ) {
            var is_context = context || null;
            if ( typeof stageLoaderPages._observers[ link ] === 'undefined' )stageLoaderPages._observers[ link ] = [];
            stageLoaderPages._observers[ link ].push( { observer : observer, context : is_context, index : index } );
            stageLoaderPages.stack.push( link );
        }

        function getStack() {
            stageLoaderPages.current = stageLoaderPages.stack.pop();
            return stageLoaderPages.current;
        }

        function notify( link, data ) {
            if ( Object.keys( stageLoaderPages._observers ).length ) {

                var cb = stageLoaderPages._observers[ link ],
                    i;

                for ( i in cb ) {
                    var item        = cb[ i ];
                    data[ 'index' ] = item.index;
                    data[ 'notifyLink' ] = link;
                    item.observer.call( item.context, data );
                }
                delete stageLoaderPages._observers[ link ];
            }
        }

        return {
            init    : function () {
                $this = this;
                window.addEventListener( "message", function ( event ) {

                    if ( event.source !== window )
                        return;

                    if ( !event.data.type )
                        return;

                    if ( event.data.type === "responseHtml" ) {

                        if( typeof event.data.info.html !== 'object' ) {
                            event.data.info.html = b64DecodeUnicode(event.data.info.html);
                            event.data.info.obj = htmlToObj(event.data.info.html);
                        } else {
                            event.data.info.obj = event.data.info.html;
                        }

                        notify( event.data.info.url, event.data.info );

                        setTimeout(function (  ) {
                            var linkPages = getStack();
                            if ( linkPages ) {
                                getPage( linkPages );
                            } else {
                                stageLoaderPages.active = false;
                            }
                        }, options.sleep);
                    }

                }, false );

            },
            addTask : function ( link, observer, context, index ) {
                addStack( link, observer, context, index );
                if ( !stageLoaderPages.active ) {
                    stageLoaderPages.active = true;
                    getPage( getStack() );
                }
            },
            sleepTask: function(time){
                options.sleep = time * 1000;
                return 'set sleep - ' + time + 'sec';
            },
            enableAjax: function(){
                options.method = 'ajax';
                return 'set method - ajax';
            },
            enableIframe: function(){
                options.method = 'iframe';
                return 'set method - iframe';
            },
            getPage: function ( link ) {
                return new Promise(function (resolve, reject) {
                    var idResolve = null;
                    $this.addTask(link, function (params) {
                        clearTimeout(idResolve);
                        resolve(params);
                    }, this );

                    idResolve = setTimeout(function () {
                        reject( 'fail get page ' + link )
                    }, 120000);
                });
            },
            sendAliExtension(action, info){
                return sendAliExtension(action, info);
            }
        }
    })();

    window.DM.aliExtension.init();
    function sendAliExtension(action, info) {

        return new Promise(function (resolve, reject) {
            info = info || {};

            const postMessageID = 'postMessageID_' + Date.now();

            const listener = function (event) {

                if ( event.source !== window )
                    return;

                if ( !event.data.type )
                    return;

                //console.log('event.data.info', event.data.info);
                //console.log(postMessageID);
                if ( event.data.type === "adsGoogleExtension:toStorePromise" && event.data.postMessageID === postMessageID ) {

                    window.removeEventListener('message', listener, false);
                    //console.log('event.data.info', event.data.info);
                    return resolve(event.data.info);
                }
            };

            window.addEventListener('message', listener, false);


            window.postMessage( {
                type         : "adsGoogleExtension:toBgPromise",
                data         : {
                    postMessageID : postMessageID,
                    action  : action,
                    info    : info

                }
            }, "*" );


        })
    }
} );
