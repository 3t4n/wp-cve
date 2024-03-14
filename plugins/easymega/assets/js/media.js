jQuery( document ).ready( function( $ ){
    var $document = $( document );
    var addParamsURL = function( url, data )
    {
        if ( ! $.isEmptyObject(data) )
        {
            url += ( url.indexOf('?') >= 0 ? '&' : '?' ) + $.param(data);
        }

        return url;
    };

    var MegaMenuWPMedia =  {
        setAttachment: function( attachment ){
            this.attachment = attachment;
        },
        getThumb: function( attachment ){
            if ( typeof attachment !== "undefined" ) {
                this.attachment = attachment;
            }
            var t = new Date().getTime();
            if ( typeof this.attachment.sizes !== "undefined" ) {
                if ( typeof this.attachment.sizes.medium !== "undefined" ) {
                    return addParamsURL( this.attachment.sizes.medium.url, { t : t } );
                }
            }
            return addParamsURL( attachment.url, { t : t } );
        },
        getURL: function( attachment ) {
            if ( typeof attachment !== "undefined" ) {
                this.attachment = attachment;
            }
            var t = new Date().getTime();
            return addParamsURL( this.attachment.url, { t : t } );
        },
        getID: function( attachment ){
            if ( typeof attachment !== "undefined" ) {
                this.attachment = attachment;
            }
            return this.attachment.id;
        },
        getInputID: function( attachment ){
            $( '.attachment-id', this.preview ).val( );
        },
        setPreview: function( $el ){
            this.preview = $el;
        },
        insertImage: function( attachment ){
            if ( typeof attachment !== "undefined" ) {
                this.attachment = attachment;
            }

            var url = this.getThumb();
            var full_url = this.getURL() ;
            var id = this.getID( );
            $( '.media-item-preview', this.preview ).html(  '<img src="'+url+'" alt="">' );
            $( '.attachment-id', this.preview ).val( id ).trigger( 'change' );
            $( '.attachment-url', this.preview ).val( full_url ).trigger( 'change' );
            this.preview.addClass( 'attachment-added' );

        },
        insertVideo: function(attachment ){
            if ( typeof attachment !== "undefined" ) {
                this.attachment = attachment;
            }

            var url = this.getURL();
            var id = this.getID();
            var mime = this.attachment.mime;
            var html = '<video width="100%" height="" controls><source src="'+url+'" type="'+mime+'">Your browser does not support the video tag.</video>';
            $( '.media-item-preview', this.preview ).html( html );
            $( '.attachment-id', this.preview ).val( id ).trigger( 'change' );
            $( '.attachment-url', this.preview ).val( url ).trigger( 'change' );
            this.preview.addClass( 'attachment-added' );
        },
        insertFile: function( attachment ){
            if ( typeof attachment !== "undefined" ) {
                this.attachment = attachment;
            }
            var url = attachment.url;
            var basename = url.replace(/^.*[\\\/]/, '');

            $( '.media-item-preview', this.preview ).html( '<a href="'+url+'" target="_blank">'+basename+'</a>' );
            $( '.attachment-id', this.preview ).val( this.getID() ).trigger( 'change' );
            $( '.attachment-url', this.preview ).val( url ).trigger( 'change' );
            this.preview.addClass( 'attachment-added' );

        },
        remove: function( $el ){
            if ( typeof $el !== "undefined" ) {
                this.preview = $el;
            }

            $( '.media-item-preview', this.preview ).removeAttr( 'style').html( '' );
            $( '.attachment-id, .attachment-url', this.preview ).val( '' ).trigger( 'change' );
            this.preview.removeClass( 'attachment-added' );
        }

    };

    var MegaMenuWPMediaImage = wp.media({
        title: wp.media.view.l10n.addMedia,
        multiple: false,
        library: {type: 'image' }
    });

    MegaMenuWPMediaImage.on('select', function () {
        var attachment = MegaMenuWPMediaImage.state().get('selection').first().toJSON();
        console.log( attachment );
        MegaMenuWPMedia.insertImage( attachment );
    });


    $document.on( 'click', '.megamenu-wp-media .select-media, .megamenu-wp-media .change-media', function () {
        var w = $( this ).closest( '.megamenu-wp-media' );
        MegaMenuWPMedia.setPreview( w );
        MegaMenuWPMediaImage.open();
    } );

    $document.on( 'click', '.megamenu-wp-media .remove-media', function () {
        var w = $( this ).closest( '.megamenu-wp-media' );
        MegaMenuWPMedia.setPreview( w );
        MegaMenuWPMedia.remove( w );
    } );



} );