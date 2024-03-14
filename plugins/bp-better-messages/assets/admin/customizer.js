/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {
    function hexToRgb(hex) {
        // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
        var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function(m, r, g, b) {
            return r + r + g + g + b + b;
        });

        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? parseInt(result[1], 16) + ", " + parseInt(result[2], 16) + ", " + parseInt(result[3], 16) : null;
    }

    function updateGeneralRule(id, value){
        let elementId = 'bm-style-' + id;
        let element   = jQuery('#' + elementId);
        if( element.length === 0 ){
            let html = '<style id="' + elementId + '">'+ value +'</style>';
            jQuery('head').append(html);
        } else {
            element.html( value );
        }
    }

    function updateScriptSetting(key, value){
        document.dispatchEvent(new CustomEvent('better-messages-update-setting', {
            detail: {
                key: key,
                value: value
            }
        }));
    }

    function updateCssRule(id, value){
        let elementId = 'bm-style-' + id;
        let element   = jQuery('#' + elementId);
        if( element.length === 0 ){
            let html = '<style id="' + elementId + '">:root{'+ value +'}</style>';

            jQuery('head').append(html);
        } else {
            element.html( ':root{'+ value +'}' );
        }
    }

    function updateDarkCssRule(id, value){
        let elementId = 'bm-style-' + id;
        let element   = jQuery('#' + elementId);
        if( element.length === 0 ){
            let html = '<style id="' + elementId + '">body.bm-messages-dark{'+ value +'}</style>';
            jQuery('head').append(html);
        } else {
            element.html( 'body.bm-messages-dark{'+ value +'}' );
        }
    }

    var simpleSettings = [
        ['bm-avatars-list', 'avatars'],
        ['bm-date-position', 'datePosition'],
        ['bm-time-format', 'timeFormat'],
        ['bm-private-sub-name', 'subName']
    ];

    simpleSettings.map( ( item ) => {
        wp.customize( item[0], function( value ) {
            updateScriptSetting( item[1], value() )

            value.bind( function( newval ) {
                updateScriptSetting( item[1], newval )
            });
        } );
    } )

    wp.customize( 'bm-widgets-position', function( value ) {
        function newValue( value ){
            if( value === 'right' ){
                return '';
            } else {
                return '.bp-better-messages-list{right: auto;left:var(--bm-mini-widgets-offset)}.bp-better-messages-mini{left:70px;right: auto}.bp-better-messages-list+.bp-better-messages-mini{right:auto;left:var(--bm-mini-chats-offset);}';
            }
        }

        updateGeneralRule( 'bm-widgets-position', newValue(value()) );

        value.bind( function( newval ) {
            updateGeneralRule( 'bm-widgets-position', newValue(newval) );
        } );
    } );


    wp.customize( 'bm-date-enabled', function( value ) {
        function newValue( value ){
            if( value ){
                return '';
            } else {
                return '.bp-messages-wrap .bm-messages-list .bm-list .bm-sticky-date{display:none}';
            }
        }

        updateGeneralRule( 'bm-date-enabled', newValue(value()) );

        value.bind( function( newval ) {
            updateGeneralRule( 'bm-date-enabled', newValue(newval) );
        } );
    } );


    wp.customize( 'bm-show-avatar-group', function( value ) {
        function newValue( value ){
            if( value === true ){
                return '';
            } else {
                return '.bp-messages-wrap .thread-info .avatar-group{display: none !important}.bp-messages-wrap .thread-info .avatar-group+.thread-info-data{max-width: 100% !important}';
            }
        }

        updateGeneralRule( 'bm-show-avatar-group', newValue(value()) );
        value.bind( function( newval ) {
            updateGeneralRule( 'bm-show-avatar-group', newValue(newval) );
        } );
    } );

    wp.customize( 'bm-theme', function( value ) {
        if( value() === 'dark' ){
            jQuery('body').addClass('bm-messages-dark').removeClass('bm-messages-light');
        } else {
            jQuery('body').removeClass('bm-messages-dark').addClass('bm-messages-light');
        }

        value.bind( function( newval ) {
            if( newval === 'dark' ){
                jQuery('body').addClass('bm-messages-dark').removeClass('bm-messages-light');
            } else {
                jQuery('body').removeClass('bm-messages-dark').addClass('bm-messages-light');
            }
        } );
    } );

    wp.customize( 'bm-border-radius', function( value ) {
        updateCssRule( 'bm-border-radius', '--bm-border-radius: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-border-radius', '--bm-border-radius: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-widgets-button-radius', function( value ) {
        updateCssRule( 'bm-widgets-button-radius', '--bm-widgets-button-radius: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-widgets-button-radius', '--bm-widgets-button-radius: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-widgets-border-radius', function( value ) {
        updateCssRule( 'bm-widgets-border-radius', '--bm-mini-chats-border-radius: ' + value() + 'px ' + value() + 'px 0 0' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-widgets-border-radius', '--bm-mini-chats-border-radius: ' + newval + 'px ' + newval + 'px 0 0' );
        } );
    } );



    wp.customize( 'main-bm-color', function( value ) {
        updateCssRule( 'main-bm-color', '--main-bm-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'main-bm-color', '--main-bm-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'main-bm-color-dark', function( value ) {
        updateDarkCssRule( 'main-bm-color-dark', '--main-bm-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'main-bm-color-dark', '--main-bm-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-primary-bg', function( value ) {
        updateCssRule( 'bm-primary-bg', '--bm-bg-color: ' + hexToRgb( value() ) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-primary-bg', '--bm-bg-color: ' + hexToRgb( newval ) );
        } );
    } );

    wp.customize( 'bm-primary-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-primary-bg-dark', '--bm-bg-color-dark: ' + hexToRgb( value() ) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-primary-bg-dark', '--bm-bg-color-dark: ' + hexToRgb( newval ) );
        } );
    } );

    wp.customize( 'bm-secondary-bg', function( value ) {
        updateCssRule( 'bm-secondary-bg', '--bm-bg-secondary: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-secondary-bg', '--bm-bg-secondary: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-secondary-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-secondary-bg-dark', '--bm-bg-secondary-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-secondary-bg-dark', '--bm-bg-secondary-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-hover-bg', function( value ) {
        updateCssRule( 'bm-hover-bg', '--bm-hover-bg: ' +  hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-hover-bg', '--bm-hover-bg: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-hover-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-hover-bg-dark', '--bm-hover-bg-dark: ' +  hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-hover-bg-dark', '--bm-hover-bg-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-primary-border', function( value ) {
        updateCssRule( 'bm-primary-border', '--bm-border-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-primary-border', '--bm-border-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-primary-border-dark', function( value ) {
        updateDarkCssRule( 'bm-primary-border-dark', '--bm-border-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-primary-border-dark', '--bm-border-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-secondary-border', function( value ) {
        updateCssRule( 'bm-secondary-border', '--bm-border-secondary-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-secondary-border', '--bm-border-secondary-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-secondary-border-dark', function( value ) {
        updateDarkCssRule( 'bm-secondary-border-dark', '--bm-border-secondary-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-secondary-border-dark', '--bm-border-secondary-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-text-color', function( value ) {
        updateCssRule( 'bm-text-color', '--bm-text-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-text-color', '--bm-text-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-text-color-dark', function( value ) {
        updateDarkCssRule( 'bm-text-color-dark', '--bm-text-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-text-colo-darkr', '--bm-text-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-right-side-nickname', function( value ) {
        updateCssRule( 'bm-modern-right-side-nickname', '--right-message-nickname-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-modern-right-side-nickname', '--right-message-nickname-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-left-side-nickname', function( value ) {
        updateCssRule( 'bm-modern-left-side-nickname', '--left-message-nickname-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-modern-left-side-nickname', '--left-message-nickname-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-right-side-nickname-dark', function( value ) {
        updateDarkCssRule( 'bm-modern-right-side-nickname-dark', '--right-message-nickname-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-right-side-nickname-dark', '--right-message-nickname-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-left-side-nickname-dark', function( value ) {
        updateDarkCssRule( 'bm-modern-left-side-nickname-dark', '--left-message-nickname-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-left-side-nickname-dark', '--left-message-nickname-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-left-side-bg', function( value ) {
        updateDarkCssRule( 'bm-modern-left-side-bg', '--left-message-bg-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-left-side-bg', '--left-message-bg-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-left-side-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-modern-left-side-bg-dark', '--left-message-bg-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-left-side-bg-dark', '--left-message-bg-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-left-side-color', function( value ) {
        updateCssRule( 'bm-modern-left-side-color', '--left-message-text-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-modern-left-side-color', '--left-message-text-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-left-side-color-dark', function( value ) {
        updateDarkCssRule( 'bm-modern-left-side-color-dark', '--left-message-text-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-left-side-color-dark', '--left-message-text-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-right-side-bg', function( value ) {
        updateCssRule( 'bm-modern-right-side-bg', '--right-message-bg-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-modern-right-side-bg', '--right-message-bg-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-right-side-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-modern-right-side-bg-dark', '--right-message-bg-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-right-side-bg-dark', '--right-message-bg-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-sticky-date-bg', function( value ) {
        updateCssRule( 'bm-sticky-date-bg', '--bm-sticky-date-bg: ' + value() );
        value.bind( function( newval ) {
            updateCssRule( 'bm-sticky-date-bg', '--bm-sticky-date-bg: ' + newval );
        } );
    } );

    wp.customize( 'bm-sticky-date-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-sticky-date-bg-dark', '--bm-sticky-date-bg-dark: ' + value() );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-sticky-date-bg-dark', '--bm-sticky-date-bg-dark: ' + newval );
        } );
    } );

    wp.customize( 'bm-sticky-date-color', function( value ) {
        updateCssRule( 'bm-sticky-date-color', '--bm-sticky-date-color: ' + value() );
        value.bind( function( newval ) {
            updateCssRule( 'bm-sticky-date-color', '--bm-sticky-date-color: ' + newval );
        } );
    } );

    wp.customize( 'bm-sticky-date-color-dark', function( value ) {
        updateDarkCssRule( 'bm-sticky-date-color-dark', '--bm-sticky-date-color-dark: ' + value() );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-sticky-date-color-dark', '--bm-sticky-date-color-dark: ' + newval );
        } );
    } );


    wp.customize( 'bm-tooltip-bg', function( value ) {
        updateCssRule( 'bm-tooltip-bg', '--bm-tooltip-bg: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-tooltip-bg', '--bm-tooltip-bg: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-tooltip-bg-dark', function( value ) {
        updateDarkCssRule( 'bm-tooltip-bg-dark', '--bm-tooltip-bg-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-tooltip-bg-dark', '--bm-tooltip-bg-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-tooltip-color', function( value ) {
        updateCssRule( 'bm-tooltip-color', '--bm-tooltip-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-tooltip-color', '--bm-tooltip-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-tooltip-color-dark', function( value ) {
        updateDarkCssRule( 'bm-tooltip-color-dark', '--bm-tooltip-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-tooltip-color-dark', '--bm-tooltip-color-dark: ' + hexToRgb(newval) );
        } );
    } );


    wp.customize( 'bm-modern-right-side-color', function( value ) {
        updateCssRule( 'bm-modern-right-side-color', '--right-message-text-color: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateCssRule( 'bm-modern-right-side-color', '--right-message-text-color: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-right-side-color-dark', function( value ) {
        updateDarkCssRule( 'bm-modern-right-side-color-dark', '--right-message-text-color-dark: ' + hexToRgb(value()) );
        value.bind( function( newval ) {
            updateDarkCssRule( 'bm-modern-right-side-color-dark', '--right-message-text-color-dark: ' + hexToRgb(newval) );
        } );
    } );

    wp.customize( 'bm-modern-radius', function( value ) {
        updateCssRule( 'bm-modern-radius', '--bm-message-border-radius: ' + value()  + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-modern-radius', '--bm-message-border-radius: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-avatar-radius', function( value ) {
        updateCssRule( 'bm-avatar-radius', '--bm-avatar-radius: ' + value()  + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-avatar-radius', '--bm-avatar-radius: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-date-radius', function( value ) {
        updateCssRule( 'bm-date-radius', '--bm-date-radius: ' + value()  + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-date-radius', '--bm-date-radius: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-mini-chats-width', function( value ) {
        updateCssRule( 'bm-mini-chats-width', '--bm-mini-chats-width: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-mini-chats-width', '--bm-mini-chats-width: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-mini-chats-height', function( value ) {
        updateCssRule( 'bm-mini-chats-height', '--bm-mini-chats-height: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-mini-chats-height', '--bm-mini-chats-height: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-mini-widgets-width', function( value ) {
        updateCssRule( 'bm-mini-widgets-width', '--bm-mini-widgets-width: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-mini-widgets-width', '--bm-mini-widgets-width: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-mini-widgets-height', function( value ) {
        updateCssRule( 'bm-mini-widgets-height', '--bm-mini-widgets-height: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-mini-widgets-height', '--bm-mini-widgets-height: ' + newval + 'px' );
        } );
    } );

    wp.customize( 'bm-mini-widgets-indent', function( value ) {
        updateCssRule( 'bm-mini-widgets-indent', '--bm-mini-widgets-offset: ' + value() + 'px' );
        value.bind( function( newval ) {
            updateCssRule( 'bm-mini-widgets-indent', '--bm-mini-widgets-offset: ' + newval + 'px' );
        } );
    } );


} )( jQuery );
