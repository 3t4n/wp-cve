/*
 * cookies.js v0.1
 * By: CALCULATED FIELD PROGRAMMERS
 * Includes operations for managing cookies
 * Copyright 2023 CODEPEOPLE
 */
;
(function (root) {
    var lib = {};

    function _cookie_name( cname ) {
        cname = cname + '';
        cname = cname.replace( /^\s+/, '' ).replace( /\s+$/, '' );
        return cname.length ? cname : false;
    }

    /*** PUBLIC FUNCTIONS ***/

    if ( window.CFFSETCOOKIE == undefined ) {
        lib.CFFSETCOOKIE = lib.cffsetcookie = function( cname, cvalue, exdays ) {
            let name = _cookie_name( cname ),
                expires = '';

            if ( name ) {
                if( typeof exdays != 'undefined' && ! isNaN( exdays ) ) {
                    const d = new Date();
                    d.setTime( d.getTime() + ( exdays * 24 * 60 * 60 * 1000 ) );
                    expires = ";expires=" + d.toUTCString();

                }
                document.cookie = name + "=" + cvalue + expires + ";path=/";
                return true;
            }

            return false;
        };
    }

    if ( window.CFFGETCOOKIE == undefined ) {
        lib.CFFGETCOOKIE = lib.cffgetcookie = function( cname ) {
            let name = _cookie_name( cname );

            if ( name ) {

                name = name + "=";
                let ca = document.cookie.split( ';' ),
                    c;

                for ( let i = 0; i < ca.length; i++ ) {
                    c = ca[i];
                    c = c.replace( /^\s+/g, '' );
                    if ( c.indexOf( name ) == 0 ) {
                        return c.substring( name.length );
                    }
                }
            }
            return null;
        };
    }

    if ( window.CFFCHECKCOOKIE == undefined ) {
        lib.CFFCHECKCOOKIE = lib.cffcheckcookie = function( cname ) {
            let name = _cookie_name( cname );

            return ( ! name || null == lib.CFFGETCOOKIE( name ) ) ? false : true;
        };
    }

    if ( window.CFFDELETECOOKIE == undefined ) {
        lib.CFFDELETECOOKIE = lib.cffdeletecookie = function( cname ) {
            let name = _cookie_name( cname );

            if ( name && lib.CFFCHECKCOOKIE( name ) ) {
                document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            }
        };
    }

    root.CF_COOKIES = lib;

})(this);
