( function( $, _ ) {

    var isTouchDevice = ( 'ontouchend' in document );

    // this controller contains the application logic
    wp.media.controller.AddExternal = wp.media.controller.State.extend({
    
        initialize: function() {
            // this model contains all the relevant data needed for the application
            this.props = new Backbone.Model( { url: '' } );
            this.props.on( 'change:url', this.refresh, this );
        },
    
        // called each time the model changes
        refresh: function() {
            // update the toolbar
            this.frame.toolbar.get().refresh();
        },
    
        // called when the toolbar button is clicked
        addoEmbed: function( controller ) {
            wp.media.post( 'add-oembed', {
                url:     this.props.get( 'url' ),
                width:   this.props.get( 'width' ),
                height:  this.props.get( 'height' ),
                post_id: wp.media.view.settings.post.id,
                nonce:   wp.media.view.settings.post.nonce
            }).done( function( response ) {
                var attachment = wp.media.model.Attachment.create( response );
                var edit = controller.state( 'insert' );
                // add attachment to the media library
                edit.get( 'library' ).add( attachment );
            }).fail( function() {
                console.log( 'AJAX failed' );
            });
        }
   
    });

    // this toolbar contains the buttons at the bottom
    wp.media.view.Toolbar.AddExternal = wp.media.view.Toolbar.extend({
        
        initialize: function() {
            _.defaults( this.options, {
                event: 'add',
                close: false,
                items: {
                    add: {
                        text: wp.media.view.l10n.AddExternalMediaButton, // added via 'media_view_strings' filter,
                        style: 'primary',
                        priority: 80,
                        requires: false,
                        click: this.addoEmbed
                    }
                }
            });

            wp.media.view.Toolbar.prototype.initialize.apply( this, arguments );
        },

        // called each time the model changes
        refresh: function() {
            // disable the button if there is no data
            var url = this.controller.state().props.get( 'url' );
            this.get( 'add' ).model.set( 'disabled', ! url );
        
            // call the parent refresh
            wp.media.view.Toolbar.prototype.refresh.apply( this, arguments );
        },
    
        // triggered when the button is clicked
        addoEmbed: function() {
            this.controller.state().addoEmbed( this.controller );
            // switch to the library view
            this.controller.setState( 'insert' );
        }
    
    });

    // this view contains the main content
    wp.media.view.AddExternal = wp.media.View.extend({
        
        className: 'media-embed',

        initialize: function() {
            this.url = new wp.media.view.AddExternalUrl({
                controller: this.controller,
                model:      this.model.props
            }).render();

            this.views.set( [ this.url ] );
            this.refresh();
            this.model.on( 'change:type', this.refresh, this );
            this.model.on( 'change:loading', this.loading, this );
        },

        settings: function( view ) {
            if ( this._settings ) {
                this._settings.remove();
            }
            this._settings = view;
            this.views.add( view );
        },

        refresh: function() {
            this.settings( new wp.media.view.AddExternalSettings({
                controller: this.controller,
                model:      this.model.props,
                priority:   40
            }) );
        },

        loading: function() {
            this.$el.toggleClass( 'embed-loading', this.model.get( 'loading' ) );
        }

    });

    // this view contains the url field
    wp.media.view.AddExternalUrl = wp.media.View.extend({
        
        tagName:   'label',
        className: 'embed-url',

        events: {
            'input':  'updateUrl',
            'keyup':  'updateUrl',
            'change': 'updateUrl'
        },

        initialize: function() {
            var self = this;

            this.$input = $( '<input id="embed-url-field" type="url" />' ).val( this.model.get( 'url' ) );
            this.input = this.$input[0];

            this.$el.append( this.input );

            this.model.on( 'change:url', this.render, this );
        },

        render: function() {
            var $input = this.$input;

            if ( $input.is( ':focus' ) ) {
                return;
            }

            this.input.value = this.model.get( 'url' ) || 'http://';
            wp.media.View.prototype.render.apply( this, arguments );
            return this;
        },

        ready: function() {
            if ( ! isTouchDevice ) {
                this.focus();
            }
        },

        updateUrl: function( event ) {
            this.model.set( 'url', event.target.value );
        },

        focus: function() {
            var $input = this.$input;
            if ( $input.is( ':visible' ) ) {
                $input.focus()[0].select();
            }
        }
        
    });

    // this view contains the oembed preview, width and height fields
    wp.media.view.AddExternalSettings = wp.media.view.Settings.extend({

        className: 'embed-link-settings',
        template:  wp.media.template( 'add-external-settings' ),

        initialize: function() {
            this.spinner = $( '<span class="spinner" />' );
            this.$el.append( this.spinner[0] );
            this.listenTo( this.model, 'change:url', this.updateoEmbed );
        },

        updateoEmbed: function() {
            var url = this.model.get( 'url' );

            // clear out previous results
            this.$( '.embed-container' ).hide().find( '.embed-preview' ).html( '' );

            // only proceed with embed if the field contains more than 6 characters
            if ( url && url.length < 6 ) {
                return;
            }

            this.spinner.show();

            setTimeout( _.bind( this.fetch, this ), 500 );
        },
        
        fetch: function() {
            // check if they haven't typed in 500 ms
            if ( $( '#embed-url-field' ).val() !== this.model.get( 'url' ) ) {
                return;
            }

            wp.ajax.send( 'parse-embed', {
                data : {
                    post_ID: wp.media.view.settings.post.id,
                    shortcode: '[embed]' + this.model.get( 'url' ) + '[/embed]'
                }
            } ).done( _.bind( this.renderoEmbed, this ) );
        },

        renderoEmbed: function( response ) {
            var html = ( response && response.body ) || '';

            this.spinner.hide();

            this.$( '.embed-container' ).show().find( '.embed-preview' ).html( html );
        }

    });
    
    // supersede the default MediaFrame.Post view
    var oldMediaFrame = wp.media.view.MediaFrame.Post;
    wp.media.view.MediaFrame.Post = oldMediaFrame.extend({

        initialize: function() {
            
            oldMediaFrame.prototype.initialize.apply( this, arguments );
        
            this.states.add([
                new wp.media.controller.AddExternal({
                    id:         'add-external',
                    menu:       'default',
                    content:    'custom',
                    title:      wp.media.view.l10n.AddExternalMediaMenuTitle, // added via 'media_view_strings' filter
                    priority:   200,
                    toolbar:    'add-external',
                    type:       'link'
                })
            ]);

            this.on( 'content:render:custom', this.createAddExternalContent, this );
            this.on( 'toolbar:create:add-external', this.createAddExternalToolbar, this );
        },
    
        createAddExternalToolbar: function( toolbar ) {
            toolbar.view = new wp.media.view.Toolbar.AddExternal({
                controller: this
            });
        },

        createAddExternalContent: function() {
        
            // this view has no router
            this.$el.addClass( 'hide-router' );

            var view = new wp.media.view.AddExternal({
                controller: this,
                model: this.state()
            });

            this.content.set( view );
        }

    });

}( jQuery, _ ));