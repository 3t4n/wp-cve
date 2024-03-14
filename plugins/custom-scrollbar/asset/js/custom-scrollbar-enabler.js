/**
 * Custom Scrollbar
 * 
 * http://en.michaeluno.jp/custom-scrollbar/
 * Copyright (c) 2015-2020 Michael Uno
 * 
 */
(function($){

    $.fn.initializeCustomScrollbars = function( aScrollbars ) {

        $.each( aScrollbars, function( _iIndex, _aScrollbar ) {

            var _subjectElement    = $( _aScrollbar[ 'selector' ] );
            if ( ! _subjectElement.length ) {
                return true; // skip;
            }

            _setInlineCSS( _aScrollbar );

            // Initialize the scrollbar
            var _aScrollbarOptions = _getScrollbarOptions( _aScrollbar );
            $( _aScrollbar[ 'selector' ] ).mCustomScrollbar( 'destroy' ).mCustomScrollbar( _aScrollbarOptions );

            _setCustomColors( _aScrollbar, _iIndex );

            _subjectElement.trigger( 'cs_initialized_scrollbar', _aScrollbar ); // [1.3.5+]

            if ( 'undefined' !== typeof customScrollbarEnabler && customScrollbarEnabler.debugMode ) {
                console.log( customScrollbarEnabler.pluginName, _aScrollbarOptions.name, _aScrollbarOptions );
            }

        } );

    } // initializeCustomScrollbars()

    function _getScrollbarOptions( _aScrollbar ) {

        var _iWidth  = Number( _aScrollbar[ 'width' ][ 'size' ] );
        var _iHeight = Number( _aScrollbar[ 'height' ][ 'size' ] );
        var _sAxis = '';
        if ( _iHeight ) {
            _sAxis += 'y';
        }
        if ( _iWidth ) {
            _sAxis += 'x';
        }

        var _bisWidth = _iWidth
            ? ( 'px' === _aScrollbar[ 'width' ][ 'unit' ]
                ? _iWidth
                : String( _iWidth ) + '%'
            )
            : false;
        var _bisHeight = _iHeight
            ? ( 'px' === _aScrollbar[ 'height' ][ 'unit' ]
                ? _iHeight
                : String( _iHeight ) + '%'
            )
            : false;

        var _aDefaults = {
            advanced            : {
                autoExpandHorizontalScroll  : true  // required for horizontal scrollbar
            },
            mouseWheel          : {
                scrollAmount: 'auto'  // `auto` by default
            },
            scrollButtons       : {
                enable: true,
                scrollAmount: 1000
            }
        };
        var _aOptions = {
            axis                : _sAxis, // vertical/horizontal scrollbar. e.g. 'x', 'y', 'yx'
            theme               : _aScrollbar[ 'theme' ],
            setWidth            : _bisWidth,  // (integer) px, (string) %, (boolean) false
            setHeight           : _bisHeight, // (integer) px, (string) %, (boolean) false
            scrollbarPosition   : _aScrollbar[ 'position' ]
        };
        $_aOptions = $.extend(
            true,   // recursive
            _aDefaults,
            _aScrollbar,
            _aOptions
        );
        return $_aOptions;

    }
    function _setInlineCSS( _aScrollbar ) {

        $.each( _aScrollbar[ 'inline_css' ], function( _iIndex, _aInlineCSS ) {

            if ( 'undefined' === typeof _aInlineCSS[ 'property' ] ) {
                return true; // continue
            }
            if ( '' === _aInlineCSS[ 'property' ] ) {
                return true; // continue
            }
            if ( 'undefined' === typeof _aInlineCSS[ 'value' ] ) {
                return true; // continue
            }
            $( _aScrollbar[ 'selector' ] ).css(
                _aInlineCSS[ 'property' ], _aInlineCSS[ 'value' ]
            );

        } );

    }
    function _setCustomColors( _aScrollbar, _iIndex ) {

        // We add a custom class name to the target element.
        var _sElementClassName = 'custom_scrollbar_' + String( _iIndex );
        $( _aScrollbar[ 'selector' ] ).addClass( _sElementClassName );

        var _sSelector = '.' + _sElementClassName;
        if ( _aScrollbar[ 'mCSB_draggerContainer' ] ) {
            $( _sSelector + ' .mCSB_draggerContainer' ).css( 'background-color', _aScrollbar[ 'mCSB_draggerContainer' ] );
        }
        if ( _aScrollbar[ 'mCSB_dragger' ] ) {
            $( _sSelector + ' .mCSB_dragger' ).css( 'background-color', _aScrollbar[ 'mCSB_dragger' ] );
        }
        if ( _aScrollbar[ 'mCSB_dragger_bar' ] ) {
            $( _sSelector + ' .mCSB_dragger_bar').css( 'background-color', _aScrollbar[ 'mCSB_dragger_bar' ] );
        }
        if ( _aScrollbar[ 'mCSB_draggerRail' ] ) {
            $( _sSelector + ' .mCSB_draggerRail' ).css( 'background-color', _aScrollbar[ 'mCSB_draggerRail' ] );
        }
        if ( _aScrollbar[ 'mCSB_scrollTools' ] ) {
            $( _sSelector + ' .mCSB_scrollTools' ).css( 'background-color', _aScrollbar[ 'mCSB_scrollTools' ] );
        }

    }

    /**
     * Start the main routine.
     */
    $( document ).ready( function() {

        if ( 'undefined' === typeof customScrollbarEnabler ) {
            return true;
        }

        if ( customScrollbarEnabler.debugMode ) {
            console.log( customScrollbarEnabler.pluginName, customScrollbarEnabler );
            console.log( 'jquery:', jQuery.fn.jquery );
        }

        /**
         * Initialize active scrollbars.
         */
        $( this ).initializeCustomScrollbars( customScrollbarEnabler[ 'scrollbars' ] );

        /**
         * Initializes scrollbars on AJAX page loads.
         * @type {array}
         * @private
         */
        var _aScrollbarsThatInitializeOnAjaxLoad = _getScrollbarsByEnabledOption( 'initialize_on_ajax_load' );
        $( document ).ajaxStop( function() {
            $( this ).initializeCustomScrollbars( _aScrollbarsThatInitializeOnAjaxLoad );
        });

        /**
         * Responsive handling.
         * @type {array}
         * @private
         */
        var _aResponsiveScrollbars = _getScrollbarsByEnabledOption( 'responsive', 'enable' );

        $( window ).on( 'resize', function(){

            var _iClientWidth       = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var _aScrollbarsInRange = {};
            $.each( _aResponsiveScrollbars, function( _iIndex, _aScrollbar ) {
                if ( _isInTheRanges( _aScrollbar[ 'responsive' ][ 'screen_width_range' ], _iClientWidth ) ) {
                    _aScrollbarsInRange[ _iIndex ] = _aScrollbar;
                } else {
                    $( _aScrollbar[ 'selector' ] ).mCustomScrollbar( 'destroy' );
                    $( _aScrollbar[ 'selector' ] ).attr('style', function(i, style) { // remove the set inline style of height from the container
                        return style.replace(/height[^;]+;?/g, '');
                    });
                }
            } );

            $( this ).initializeCustomScrollbars( _aScrollbarsInRange ); // on window resize

            /**
             *
             * @return {boolean}
             * @private
             */
            function _isInTheRanges( aRanges, iClientWidth ) {
                var _bInRange = false;
                $.each( aRanges, function( _iIndex, _aRange ) {

                    // No max-limit?
                    if ( 0 === _aRange[ 1 ] ) {
                        if ( _aRange[ 0 ] <= iClientWidth ) {
                            _bInRange = true;
                            return false;   // break the iteration
                        }
                        return true;    // continue; skip the iteration
                    }

                    // In the range?
                    if ( _aRange[ 0 ] <= iClientWidth && iClientWidth <= _aRange[ 1 ] ) {
                        _bInRange = true;
                        return false;   // break the iteration
                    }

                } );

                return _bInRange;

            }

        } ); // load resize

        
    }); // .ready()

    /**
     *
     * @param sOptionName
     * @param $sSecondKey
     * @return array
     * @private
     */
    function _getScrollbarsByEnabledOption( sOptionName, $sSecondKey ) {

        var _aScrollbars = {};
        if ( 'undefined' === typeof customScrollbarEnabler[ 'scrollbars' ] ) {
            return _aScrollbars;
        }

        $.each( customScrollbarEnabler[ 'scrollbars' ], function( _iIndex, _aScrollbar ) {
            if ( $sSecondKey ) {
                if ( ! _aScrollbar[ sOptionName ][ $sSecondKey ] ) {
                    return true;    // continue
                }
            } else {
                if ( ! _aScrollbar[ sOptionName ] ) {
                    return true;    // continue
                }
            }
            _aScrollbars[ _iIndex ] = _aScrollbar;
        } );
        return _aScrollbars;

    }

}(jQuery));