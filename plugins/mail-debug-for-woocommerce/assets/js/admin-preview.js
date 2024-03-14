/** global mdwc */

jQuery( function ( $ ) {
    var box             = $( '#mdwc-preview-box' ),
        title           = $( '#mdwc-preview-box__title' ),
        content         = $( '#mdwc-preview-box__content' ),
        minify_btn      = $( '#mdwc-preview-box__minify' ),
        expand_btn      = $( '#mdwc-preview-box__expand' ),
        close_btn       = $( '#mdwc-preview-box__close' ),
        classes         = {
            minified: 'mdwc-preview-box--minified',
            expanded: 'mdwc-preview-box--expanded',
            closed  : 'mdwc-preview-box--closed'
        },
        minifyToggle    = function () {
            box.removeClass( classes.closed );
            box.removeClass( classes.expanded );
            box.toggleClass( classes.minified );
        },
        expandToggle    = function () {
            box.toggleClass( classes.expanded );
        },
        close           = function () {
            box.addClass( classes.closed );
        },
        isClosed        = function () {
            return box.hasClass( classes.closed );
        },
        open            = function () {
            box.removeClass( classes.closed );
            box.removeClass( classes.minified );
        },
        current_preview = 0;

    minify_btn.on( 'click', minifyToggle );
    expand_btn.on( 'click', expandToggle );
    close_btn.on( 'click', close );

    $( '#the-list > tr' ).on( 'click', function ( e ) {
        var id            = $( this ).find( 'th.check-column input[type=checkbox]' ).val(),
            current_title = $( this ).find( 'td.subject strong a' ).html();

        if ( current_preview === id ) {
            if ( isClosed() ) {
                open();
            } else {
                minifyToggle();
            }
            return;
        }

        current_preview = id;

        title.html( current_title );

        $.ajax( {
                    type    : "POST",
                    data    : {
                        post_id: id,
                        action : 'mdwc_get_email_message'
                    },
                    url     : ajaxurl,
                    success : function ( response ) {
                        try {
                            if ( response.error ) {
                                content.html( '<p class="error">' + response.error + '</p>' );
                            } else {
                                if ( response.message ) {
                                    content.html( response.message );
                                }
                            }
                        } catch ( err ) {
                            console.log( err.message );
                        }
                        open();
                    },
                    complete: function ( jqXHR, textStatus ) {
                        if ( textStatus !== 'abort' ) {

                        }
                    }
                } );
        open();
        minifyToggle();
    } );

    $( '#the-list > tr a' ).on( 'click', function ( e ) {
        e.stopPropagation();
    } );

    $( '.mdwc-delete-all' ).on( 'click', function ( e ) {
        return window.confirm( mdwc.i18n_delete_confirmation );
    } );
} );