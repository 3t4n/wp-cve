/**
 * Copyright (c) 2015 Leonardo Cardoso (http://leocardz.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.0
 */
( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        // Init Vars.
        $.YOUZIFY_LP = {};
        var URL_REGEX = /((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_\-~@\(\)\%]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&#%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?/i;
        var URL_REGEX2 = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
        var youzify_lp_folder = Youzify.youzify_url + 'includes/public/core/functions/live-preview/';
        var default_lp_form = $( '#youzify-wall-form' ).find( '.lp-prepost-container' ).html();

        // Check if Content contains Url.
        var hasUrl = function( $text ) {
            return URL_REGEX.test( $text );
        };

        // Check if Content is Url.
        function isUrl( $text ) {
            return URL_REGEX2.test( $text );
        };

        // Detect Textarea Paste.
        var elements = document.getElementsByClassName( 'youzify-wall-textarea' );
        var youzify_get_link_preview_data_on_paste = function(event) {
            let paste = (event.clipboardData || window.clipboardData).getData('text');
            $.youzify_get_link_preview_data( $( event.target ).closest( 'form' ), paste );
        };
        for ( var i = 0; i < elements.length; i++ ) {
            elements[i].addEventListener( 'paste', youzify_get_link_preview_data_on_paste, false);
        }

        // Detect Textarea Keyup.
        $( document ).on( 'keyup', '.youzify-wall-textarea', function( e) {
            if ( ( e.which == 13 || e.which == 32 || e.which == 17 ) )  {
                $.youzify_get_link_preview_data( $( this ).closest( 'form' ), $( this ).val() );
            }
        });

        // Track Emojiareaone Keyup.
        $( document ).on( 'keyup', '.youzify-emojionearea-editor', function( e ) {
            // if ( ( e.which == 13 || e.which == 32 || e.which == 17 ) ) {
                $.youzify_get_link_preview_data( $( this ).closest( 'form' ), $( this ).text() );
            // }
        });

        // Hide Thumbnail
        $( document ).on( 'change', '.lp-preview-no-thubmnail-text input', function() {
            var live_preview_form = $( this ).closest( '.lp-prepost-container' );
            if ( this.checked ) {
                live_preview_form.find( '.lp-preview-image' ).fadeOut( 200, function() {
                    live_preview_form.addClass( 'youzify-lp-no-thumbnail' );
                });
            } else {
                live_preview_form.find( '.lp-preview-image' ).fadeIn( 200, function() {
                    live_preview_form.removeClass( 'youzify-lp-no-thumbnail' );
                });
            }
        });

        // Close Url Preview & Rest Form
        $( document ).on( 'click', '.lp-button-cancel', function() {
            $( this ).closest( '.youzify-lp-prepost' ).attr( 'data-loaded', false );
            $( this ).closest( '.lp-prepost-container' ).fadeOut( 200, function() {
                $( this ).html( default_lp_form ).attr( 'class', 'lp-prepost-container' );
            });
        });

        // Display Title Edit Input
        $( document ).on( 'click', '.lp-preview-title', function() {
            $( this ).fadeOut( 200, function() {
                $( this ).closest( '.youzify-lp-prepost' ).find( '.lp-preview-replace-title' ).fadeIn();
            });
        });

        // Display Title Edit Input
        $( document ).on( 'click', '.lp-preview-description', function() {
            $( this ).fadeOut( 200, function() {
                $( this ).closest( '.youzify-lp-prepost' ).find( '.lp-preview-replace-description' ).fadeIn();
            });
        });

        // Previous.
        $( document ).on( 'click', '.youzify-lp-previous-image', function() {

            // Init Vars.
            var current_index = parseInt( $( this ).closest( '.lp-preview-thubmnail-buttons' ).attr( 'data-current' ) );

            if ( current_index < 1 ) {
                return;
            }

            // Update Image.
            $.youzify_update_live_preview_image( $( this ).closest( '.youzify-lp-prepost' ), current_index - 1 );

        });

        // Next.
        $( document ).on( 'click', '.youzify-lp-next-image', function() {

            // Init Vars.
            var current_index = parseInt( $( this ).closest( '.lp-preview-thubmnail-buttons' ).attr( 'data-current' ) );
            var new_index = current_index + 1;

            if ( ! $.YOUZIFY_LP.images[ new_index ] ) {
                return;
            }

            // Update Image.
            $.youzify_update_live_preview_image( $( this ).closest( '.youzify-lp-prepost' ), new_index );

        });

        /**
         * Update Live Preview Image.
         */
        $.youzify_update_live_preview_image = function( form, index ) {

            // Update Image.
            form.find( 'input[name="url_preview_img"]' ).val( $.YOUZIFY_LP.images[ index ] );
            form.find( '.lp-preview-image' ).css( 'background-image', 'url(' + $.YOUZIFY_LP.images[ index ] + ')' );

            // Update Pagination.
            form.find( '.lp-preview-thubmnail-buttons' ).attr( 'data-current', index );
            form.find( '.lp-preview-pagination' ).find( '.lp-preview-thubmnail-pagination' ).text( index + 1 );

        }

        /**
         * Get Link Preview.
         */
        $.youzify_get_link_preview_data = function ( form, $text ) {

            // Hide Live Preview for Comments.
            if ( form.find( 'input[name="post_type"]' ).val() == 'activity_comment' ) {
                return;
            }

            // Verify text is not empty & it has URL and there's no previous fetching.
            if ( form.find( '.youzify-lp-prepost' ).attr( 'data-loaded' ) == 'true' || $text == "" || ! hasUrl( $text ) ) {
                return;
            }

            var get_url = $text.match( URL_REGEX );

            var isUrl = new RegExp( URL_REGEX2 );

            if ( isUrl.test( get_url[0] ) ) {

                // Disable Submit Button.
                form.find( '.youzify-wall-post, .youzify-update-post' ).attr( 'disabled', true );

                // Display Loader.
                form.find( '.lp-loading-text' ).fadeIn();

                // Set Actions.
                form.find( '.youzify-lp-prepost' ).attr( 'data-loaded', true );

                // Disable Posting form.
                // ------------------------------------

                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: { action: 'youzify_get_url_live_preview', text: $text },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    success: function( response ) {

                        if ( $.trim( response ) != '' ) {

                            // Get Response.
                            response = $.parseJSON( response );

                            // Display Live Url Preview.
                            $.youzify_set_live_preview_form( form.find( '.youzify-lp-prepost' ), response );

                        } else {
                            form.find( '.lp-loading-text' ).hide();
                        }

                        // Enable Submit Button.
                        form.find( '.youzify-wall-post, .youzify-update-post' ).attr( 'disabled', false );

                    }

                });
            }
        }

    });

    /**
     * Set Live Preview Form.
     */
    $.youzify_set_live_preview_form = function ( form, data ) {

        // Init Vars.
        var preview_container = form.find( '.lp-prepost-container' ),
            elements = {
                '{{preview.title}}' : data.title,
                '{{preview.site}}' : data.site,
                '{{preview.description}}' : data.description,
                '{{preview.image}}' : data.image,
                '{{thumbnailPaginationText}}' : 1,
                '{{thumbnailText}}' : data.images ? data.images.length : 1,
            };

        $.YOUZIFY_LP.images = data.images;

        if ( data.image ) {
            form.find( '.lp-preview-image' ).css( 'background-image', 'url(' + data.image + ')' ).prepend( '<input type="hidden" name="url_preview_img" value="' + data.image + '">' );
        }

        if ( data.link ) {
            form.find( '.lp-preview-image' ).prepend( '<input type="hidden" name="url_preview_link" value="' + data.link + '">' );
        }

        if ( data.site ) {
            form.find( '.lp-preview-image' ).prepend( '<input type="hidden" name="url_preview_site" value="' + data.site + '">' );
        }

        if ( data.title ) {
            form.find( '.lp-preview-replace-title-wrap' ).append( '<input type="text" class="lp-preview-replace-title" name="url_preview_title" value="' + data.title + '">')
        }

        if ( data.description ) {
            form.find( '.lp-preview-replace-description-wrap' ).append( '<textarea name="url_preview_desc" class="lp-no-resize lp-preview-replace-description">' + data.description + '</textarea>' )
        }

        // Remove Pagination if there less than 2 images.
        if ( data.images && data.images.length < 2 ) {
            preview_container.find( '.lp-preview-pagination' ).remove();
        } else {
            preview_container.find( '.lp-preview-thubmnail-buttons' ).attr( 'data-current', 0 );
        }

        // Remove Video Icon.
        if ( data.video == false ) {
            preview_container.find( '.lp-preview-video-icon' ).remove();
        }

        // Replace Preview Tags.
        $.each( elements, function( tag, value) {
            var myregexp = new RegExp( tag, 'g' );
            var newhtml = preview_container.html().replace( myregexp, value );
            preview_container.html( newhtml );
        });

        if ( data.use_thumbnail && data.use_thumbnail == 'on' ) {
            // preview_container
            preview_container.find( 'input[name="url_preview_use_thumbnail"]' ).attr( 'checked', true ).trigger( 'change' );
        }

        // Hide Loader & Display Url Preview.
        form.find( '.lp-loading-text' ).fadeOut( 200, function() {
            preview_container.fadeIn();
        });

    }

})( jQuery );