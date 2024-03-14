(function($){
/*
 * wp.media.editor.send.attachment remote extension
 */
//Override send attachment method at ready time. 
//This provide a quick fix for plugin like visual composer v4.10 that also override the method but does not ensure the original method is run.
$(document).ready(function () {
    var oldSendAttachment = wp.media.editor.send.attachment,
    sendRemoteAttachment = function( props, attachment ) {
        if (typeof attachment.isOcsRemote === 'undefined' ||
            attachment.isOcsRemote === false
        ) {
            return oldSendAttachment(props, attachment);
        }
        var caption = attachment.caption,
        options, html;

        // If captions are disabled, clear the caption.
        if ( ! wp.media.view.settings.captions ) {
            delete attachment.caption;
        }

        props = wp.media.string.props( props, attachment );

        options = {
            id: attachment.id,
            title: attachment.title,
            type: attachment.type,
            subtype: attachment.subtype,
            remotetype: attachment.remotetype,
            accountId: attachment.accountId || 0,
            remotedata: attachment.remotedata || [],
            post_content: attachment.description,
            post_excerpt: caption
        };

        if ( props.linkUrl ) {
            options.url = props.linkUrl;
        }

        if ( 'image' === attachment.type ) {
            html = wp.media.string.image( props );
            
            _.each({
                align: 'align',
                size:  'image-size',
                alt:   'image_alt'
            }, function( option, prop ) {
                if ( props[ prop ] )
                    options[ option ] = props[ prop ];
            });

            options.width  = attachment.width || 0;
            options.height = attachment.height || 0;
            options.imgurl = attachment.url || options.url;

            if (options['image-size'].length > 0 &&
                typeof attachment.sizes[options['image-size']] !== 'undefined'
            ) {
                options.imgurl = attachment.sizes[options['image-size']].url || options.imgurl;
                options.width  = attachment.sizes[options['image-size']].width || options.width;
                options.height = attachment.sizes[options['image-size']].height || options.height;
            }
        } else if ( 'video' === attachment.type ) {
            html = wp.media.string.video( props, attachment );
        } else if ( 'audio' === attachment.type ) {
            html = wp.media.string.audio( props, attachment );
        } else {
            html = wp.media.string.link( props );
            options.post_title = props.title;
        }

        return wp.media.post( 'send-remote-attachment-to-editor', {
            nonce:      rmlSendToEditorParams.nonce,
            attachment: options,
            html:       html,
            post_id:    wp.media.view.settings.post.id,
        });
    };
    wp.media.editor.send.attachment = sendRemoteAttachment;
});
/**
 * wp.media.view.RemoteUploaderInline
 */
wp.media.view.RemoteUploaderInline = wp.media.View.extend({
    tagName:   'div',
    className: 'remote-uploader',
    template:  wp.media.template('remote-media-upload'),
    // bind view events
    events: {
        'input':  'refresh',
        'keyup':  'refresh',
        'change': 'refresh'
    },
    initialize: function() {
        _.defaults( this.options, {
            message: '',
            status:  true
        });
        
        var state = this.controller.state(),
            template = state.get('uploadTemplate');
        if (template) {
            this.template = wp.media.template(template);
        }

        if ( this.options.status ) {
            this.views.set( '.upload-inline-status', new wp.media.view.UploaderStatus({
                controller: this.controller
            }) );
        }
    },
    render: function() {
        wp.media.View.prototype.render.apply( this, arguments );
        this.refresh();
        return this;
    },
    refresh: function( event ) {},
    hide: function() {
        this.$el.addClass( 'hidden' );
    }
});

/**
 * 
 */
wp.media.remotequery = function( props ) {
    return new wp.media.model.RemoteAttachments( null, {
        props: _.extend( _.defaults( props || {}, { orderby: 'date' } ), { query: true } )
    });
};
    
wp.media.model.RemoteAttachments = wp.media.model.Attachments.extend({
    initialize: function() {
        wp.media.model.Attachments.prototype.initialize.apply( this, arguments );
    },
    _requery: function(refresh) {
        var props;
        if ( this.props.get('query') ) {
            props = this.props.toJSON();
            props.cache = ( true !== refresh );
            this.mirror( wp.media.model.RemoteQuery.get( this.props.toJSON() ) );
        }
    }
});

wp.media.model.RemoteQuery = wp.media.model.Query.extend({
        initialize: function() {
            wp.media.model.Query.prototype.initialize.apply( this, arguments );
        },
        more: function () {
            this._more = wp.media.model.Query.prototype.more.apply( this, arguments );

            this._more.fail( function( response ) {
                if (response.statuscode === 429) {
                    alert('You have reached this service data rate limit on this IP address for this hour. Try again in a bit.');
                }
                if (response.msg) {
                    console.log('Error getting remote library data:\n'+response.msg);
                }
                
            });

            return this._more;
        },
        parse: function( resp, xhr ) {
            if ( ! _.isArray( resp ) ) {
                resp = [resp];
            }
    
            return _.map( resp, function( attrs ) {
                var id, attachment, newAttributes;
    
                if ( attrs instanceof Backbone.Model ) {
                    id = attrs.get( 'id' );
                    attrs = attrs.attributes;
                } else {
                    id = attrs.id;
                }
    
                //wp.media.model.Attachment.get uses _.memoize function that caches based on the id
                //To prevent collision between 2 libraries of the same remote location or
                //between 2 different attachment of same ID across 2 different remote libraries
                //we need to add accountId to id so that the cache returns the proper model from the proper remote library
                if (attrs.accountId) {
                    id = attrs.accountId+id;
                }

                attachment = wp.media.model.Attachment.get( id );
                newAttributes = attachment.parse( attrs, xhr );
    
                if ( ! _.isEqual( attachment.attributes, newAttributes ) ) {
                    attachment.set( newAttributes );
                }
    
                return attachment;
            });
        },
        sync: function( method, model, options ) {
            var fallback;
    
            // Overload the read method so Attachment.fetch() functions correctly.
            if ( 'read' === method ) {
                options = options || {};
                options.context = this;
                options.data = _.extend( options.data || {}, {
                    action:  'query-remote-attachments',
                    post_id: wp.media.model.settings.post.id,
                    security: rmlQueryAttachmentsParams.nonce
                });
    
                // Clone the args so manipulation is non-destructive.
                args = _.clone( this.args );
    
                // Determine which page to query.
                if ( -1 !== args.posts_per_page ) {
                    args.paged = Math.floor( this.length / args.posts_per_page ) + 1;
                }
    
                options.data.query = args;
                return wp.media.ajax( options );
    
            // Otherwise, fall back to Backbone.sync()
            } else {
                fallback = wp.media.model.Attachments.prototype.sync ? wp.media.model.Attachments.prototype : Backbone;
                return fallback.sync.apply( this, arguments );
            }
        }
    }, {
        // Caches query objects so queries can be easily reused.
        get: (function(){
            var queries = [];

            return function( props, options ) {
                var args     = {},
                    orderby  = wp.media.model.RemoteQuery.orderby,
                    defaults = wp.media.model.RemoteQuery.defaultProps,
                    query,
                    cache    = !! props.cache || _.isUndefined( props.cache );
    
                // Remove the `query` property. This isn't linked to a query,
                // this *is* the query.
                delete props.query;
                delete props.cache;
    
                // Fill default args.
                _.defaults( props, defaults );
    
                // Normalize the order.
                props.order = props.order.toUpperCase();
                if ( 'DESC' !== props.order && 'ASC' !== props.order ) {
                    props.order = defaults.order.toUpperCase();
                }
    
                // Ensure we have a valid orderby value.
                if ( ! _.contains( orderby.allowed, props.orderby ) ) {
                    props.orderby = defaults.orderby;
                }
    
                _.each( [ 'include', 'exclude' ], function( prop ) {
                    if ( props[ prop ] && ! _.isArray( props[ prop ] ) ) {
                        props[ prop ] = [ props[ prop ] ];
                    }
                } );
    
                // Generate the query `args` object.
                // Correct any differing property names.
                _.each( props, function( value, prop ) {
                    if ( _.isNull( value ) ) {
                        return;
                    }
    
                    args[ wp.media.model.RemoteQuery.propmap[ prop ] || prop ] = value;
                });
    
                // Fill any other default query args.
                _.defaults( args, wp.media.model.RemoteQuery.defaultArgs );
    
                // `props.orderby` does not always map directly to `args.orderby`.
                // Substitute exceptions specified in orderby.keymap.
                args.orderby = orderby.valuemap[ props.orderby ] || props.orderby;
    
                // Search the query cache for a matching query.
                if ( cache ) {
                    query = _.find( queries, function( query ) {
                        return _.isEqual( query.args, args );
                    });
                } else {
                    queries = [];
                }
    
                // Otherwise, create a new query and add it to the cache.
                if ( ! query ) {
                    query = new wp.media.model.RemoteQuery( [], _.extend( options || {}, {
                        props: props,
                        args:  args
                    } ) );
                    queries.push( query );
                }
    
                return query;
            };
        }())
});

/**
 * wp.media.view.AttachmentFilters.RemoteCustom
 *
 */
wp.media.view.AttachmentFilters.RemoteCustom = wp.media.view.AttachmentFilters.extend({
    className: 'rml-attachment-filters attachment-filters',
    createFilters: function() {
        var filters = {},
            remotefilters = this.model.get('remotefilters');

        _.each(remotefilters, function(remotefilter) {
            var filter = {
                text: remotefilter.text || 'Undefined',
                props: remotefilter.props || {uploadedTo: null,orderby: 'date',order: 'DESC'},
                priority: remotefilter.priority || 10
            }
            if (remotefilter.slug) {
                filters[remotefilter.slug] = (filter);
            }
            
        });

        this.filters = filters;
    }
});

/**
 * wp.media.controller.RemoteLibrary
 */
wp.media.controller.RemoteLibrary = wp.media.controller.Library.extend({
    defaults: {
        id:         'remote-library',
        multiple:   'add', // false, 'add', 'reset'
        describe:   false,
        toolbar:    'select',
        sidebar:    'settings',
        content:    'upload',
        router:     'browse',
        menu:       'default',
        date:       false,
        remote:     true,
        searchable: true,
        filterable: false,
        sortable:   false,
        autoSelect: true,

    // Allow local edit of attachment details like title, caption, alt text and description
        allowLocalEdits: true,
        
        // Uses a user setting to override the content mode.
        contentUserSetting: true,

        // Sync the selection from the last state when 'multiple' matches.
        syncSelection: true
    }
});
/**
 * New wp.media.view.MediaFrame.Post
 */
var oldMediaFrame = wp.media.view.MediaFrame.Post;
wp.media.view.MediaFrame.Post = oldMediaFrame.extend({
    initialize: function() {
        oldMediaFrame.prototype.initialize.apply( this, arguments );
    },
    
    createStates: function() {
        oldMediaFrame.prototype.createStates.apply( this, arguments );

        var options = this.options,
            that = this;
        _.each(wp.media.view.settings.remoteMediaAccounts, function(account) {
            var serviceSettings = wp.media.view.settings.remoteServiceSettings[account.type] || [];
            that.states.add([
                new wp.media.controller.RemoteLibrary({
                    id:         'remote-library-'+account.id,
                    sectionid:  account.id,
                    title:      account.title,
                    service:    account.type,
                    priority:   30,
                    toolbar:    'main-remote',
                    uploadTemplate: serviceSettings.uploadTemplate || '',
                    filterable: account.filterable || 'uploaded',
                    library:  wp.media.remotequery( _.defaults({
                        isOcsRemote: true,
                        account_id: account.id,
                        remotefilters: account.filters || [],
                        uioptions: account.uioptions || [],
                        orderby: 'menuOrder',
                        order: 'ASC'
                    }, options.library ) ),
                    state:    'remote-library-'+account.id,
                    editable:   true,
                    displaySettings: true,
                    displayUserSettings: true,
                    //content:    'remote-upload',
                    menu:       'default',
                    AttachmentView: wp.media.view.Attachment.RemoteLibrary
                })
            ]);
        }, this);
    },
    bindHandlers: function() {
        oldMediaFrame.prototype.bindHandlers.apply( this, arguments );

        this.on( 'toolbar:create:main-remote', this.createToolbar, this );
        this.on( 'toolbar:render:main-remote', this.mainInsertToolbar, this );
    },
    uploadContent: function() {
        var sectionid = this.state().get('sectionid');
        if (sectionid) {
            this.$el.removeClass('hide-toolbar');
            this.content.set(new wp.media.view.RemoteUploaderInline({
                controller: this,
                model: this.state().props
            }));
        } else {
            wp.media.view.MediaFrame.Select.prototype.uploadContent.apply( this, arguments );
        }
    }
});
wp.media.view.RemoteAttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
    createSingle: function () {
        var sidebar = this.sidebar,
            single = this.options.selection.single(),
            type = single.get('type'),
            isRemote = single.get('isOcsRemote') || false;

        if (isRemote !== true) {
            return wp.media.view.AttachmentsBrowser.prototype.createSingle.apply( this, arguments );
        }
        //Set type from remote type to display same attachment display settings than native supported type

        // if (remotetype === 'image') {
        //     single.set('type', remotetype);
        //     wp.media.view.AttachmentsBrowser.prototype.createSingle.apply( this, arguments );
        //     single.set('type', 'remote');
        // } else {
        wp.media.view.AttachmentsBrowser.prototype.createSingle.apply( this, arguments );
        // }
        
        
        // Show the sidebar on mobile
        if ( this.model.id === 'remote-library-'+this.model.get('sectionid') ) {
            sidebar.$el.addClass( 'visible' );
        }
    },
    createToolbar: function () {
        wp.media.view.AttachmentsBrowser.prototype.createToolbar.apply( this, arguments );

        if ( 'custom' === this.options.filters ) {
            this.toolbar.set( 'filters', new wp.media.view.AttachmentFilters.RemoteCustom({
                controller: this.controller,
                model:      this.collection.props,
                priority:   -80
            }).render() );
        }

        //Add class to attachments browser to distinguish remote browser and allow targetted styling 
        this.$el.addClass('remote-attachments-browser');
    }
});
var oldBrowseContent = wp.media.view.MediaFrame.Select.prototype.browseContent;
wp.media.view.MediaFrame.Select.prototype.browseContent = function( contentRegion ) {
    var state = this.state(),
        isRemoteLibrary = state.get('remote');
    if (isRemoteLibrary === true) {
        var library = state.get('library');
            // test = wp.media.remotequery(library.props.toJSON());
        this.$el.removeClass('hide-toolbar');
        // state.get('library')._requery(true);
        // Browse our library of attachments.
        contentRegion.view = new wp.media.view.RemoteAttachmentsBrowser({
            controller: this,
            collection: library,
            selection:  state.get('selection'),
            model:      state,
            sortable:   state.get('sortable'),
            search:     state.get('searchable'),
            filters:    state.get('filterable'),
            date:       state.get('date'),
            display:    state.has('display') ? state.get('display') : state.get('displaySettings'),
            dragInfo:   state.get('dragInfo'),

            idealColumnWidth: state.get('idealColumnWidth'),
            suggestedWidth:   state.get('suggestedWidth'),
            suggestedHeight:  state.get('suggestedHeight'),

            AttachmentView: state.get('AttachmentView')
        });
    } else {
        oldBrowseContent.apply( this, arguments );
    }
}
/**
 * wp.media.view.Attachment.RemoteLibrary
 */
wp.media.view.Attachment.RemoteLibrary = wp.media.view.Attachment.Library.extend({
    template:  wp.media.template('attachment-remote'),
    toggleSelection: function( ) {
        wp.media.view.Attachment.Library.prototype.toggleSelection.apply( this, arguments );
    }
});
/**
 * wp.media.view.Attachment.Selection
 */
wp.media.view.Attachment.RemoteSelection = wp.media.view.Attachment.Selection.extend({
    template:  wp.media.template('attachment-remote')
});
/**
 * wp.media.view.Attachments.Selection
 * 
 * Use new RemoteSelection view by default
 */
var oldAttachmentsSelection = wp.media.view.Attachments.Selection;
wp.media.view.Attachments.Selection = oldAttachmentsSelection.extend({
    initialize: function() {
        _.defaults( this.options, {
            // The single `Attachment` view to be used in the `Attachments` view.
            AttachmentView: wp.media.view.Attachment.RemoteSelection
        });
        return oldAttachmentsSelection.prototype.initialize.apply( this, arguments );
    }
});

}(jQuery));
